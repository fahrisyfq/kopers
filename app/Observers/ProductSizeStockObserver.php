<?php

namespace App\Observers;

use App\Models\ProductSize;
use App\Models\OrderItem;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class ProductSizeStockObserver
{
    public function updated(ProductSize $size)
    {
        // Cek jika stok berubah (Manual update dari Admin)
        if ($size->isDirty('stock')) {
            
            $oldStock = $size->getOriginal('stock');
            $newStock = $size->stock;
            $diff = $newStock - $oldStock;

            if ($diff == 0) return;

            // === A. JIKA STOK BERKURANG ===
            if ($diff < 0) {
                $this->createLog($size, 'out', abs($diff), $oldStock, $newStock, 'Update Manual (Stok Dikurangi)');
                $this->syncParentProduct($size); 
                return;
            }

            // === B. JIKA STOK BERTAMBAH (RESTOCK) ===
            
            // Cek hutang PO khusus untuk SIZE ini
            $poItems = OrderItem::where('product_size_id', $size->id)
                ->where('is_preorder', true)
                ->where('preorder_status', 'waiting')
                ->orderBy('created_at', 'asc')
                ->get();

            $totalHutangPO = $poItems->sum('quantity');

            // Skenario 1: Tidak ada PO
            if ($totalHutangPO <= 0) {
                $this->createLog($size, 'in', $diff, $oldStock, $newStock, 'Restock Size ' . $size->size);
                $this->syncParentProduct($size);
                return;
            }

            // Skenario 2: Ada PO (Auto-Fulfillment)
            DB::transaction(function () use ($size, $diff, $oldStock, $newStock, $poItems) {
                
                $stokTersedia = $newStock;
                $fulfilledCount = 0;

                foreach ($poItems as $item) {
                    if ($stokTersedia >= $item->quantity) {
                        $stokTersedia -= $item->quantity;
                        $item->update(['preorder_status' => 'ready']); // Ubah status jadi ready
                        $fulfilledCount += $item->quantity;
                    } else {
                        break; 
                    }
                }

                // 1. Log Masuk
                $this->createLog($size, 'in', $diff, $oldStock, $newStock, "Restock Size {$size->size}");

                if ($fulfilledCount > 0) {
                    $saldoAkhir = $newStock - $fulfilledCount;

                    // 2. Log Keluar
                    $this->createLog($size, 'out', $fulfilledCount, $newStock, $saldoAkhir, "Otomatis: Dialokasikan ke PO (Size {$size->size})");

                    // 3. Ubah Log PO Lama jadi Ready
                    $oldPOLogs = StockMovement::where('product_size_id', $size->id)
                        ->where('is_preorder', true)
                        ->where('movement_type', 'in')
                        ->orderBy('created_at', 'asc')
                        ->limit($fulfilledCount)
                        ->get();

                    foreach ($oldPOLogs as $log) {
                        $log->update([
                            'movement_type' => 'out',
                            'note' => $log->note . ' (Status: Ready/Siap Diambil)',
                            'skipProductUpdate' => true
                        ]);
                    }

                    // 4. Update Stok Size (Hard Update)
                    DB::table('product_sizes')->where('id', $size->id)->update([
                        'stock' => $stokTersedia
                    ]);
                }

                // 5. 🔥 SINKRONISASI TOTAL (PEMBAHARUAN PENTING) 🔥
                // Panggil fungsi ini SETELAH transaksi selesai agar hitungannya akurat
                $this->syncParentProduct($size);
            });
        }
    }

    protected function createLog($size, $type, $qty, $before, $after, $note)
    {
        StockMovement::create([
            'product_id'      => $size->product_id,
            'product_size_id' => $size->id,
            'movement_type'   => $type,
            'quantity'        => $qty,
            'balance_before'  => $before,
            'balance_after'   => $after,
            'note'            => $note,
            'skipProductUpdate' => true 
        ]);
    }

    // 🔥 FUNGSI INI KUNCINYA (SELF-HEALING) 🔥
    protected function syncParentProduct(ProductSize $size)
    {
        if (!$size->product_id) return;

        // 1. Hitung Ulang Total Stok Fisik
        $totalStock = DB::table('product_sizes')
                        ->where('product_id', $size->product_id)
                        ->sum('stock');
        
        // 2. Hitung Ulang Total PO Waiting (Langsung dari OrderItem)
        // Ini menjamin angka di tabel produk PASTI SAMA dengan jumlah pesanan yang belum lunas
        $totalPOWaiting = DB::table('order_items')
                            ->where('product_id', $size->product_id)
                            ->where('is_preorder', true)
                            ->where('preorder_status', 'waiting') // Hanya yang waiting
                            ->whereNull('deleted_at') // Cek soft delete
                            ->sum('quantity');

        // 3. Update Parent Product dengan angka yang 100% Valid
        DB::table('products')
            ->where('id', $size->product_id)
            ->update([
                'stock' => $totalStock,
                'preorder_quantity' => $totalPOWaiting
            ]);
    }

    public function created(ProductSize $size)
    {
        if ($size->stock > 0) {
            $this->createLog($size, 'in', $size->stock, 0, $size->stock, 'Stok Awal Size ' . $size->size);
            $this->syncParentProduct($size);
        }
    }
}
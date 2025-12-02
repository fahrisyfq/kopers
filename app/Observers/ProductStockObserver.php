<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\OrderItem;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductStockObserver
{
    public function updated(Product $product)
    {
        // Cek jika stok berubah (Manual update dari Admin)
        if ($product->isDirty('stock') && $product->category !== 'Seragam Sekolah') {
            
            $oldStock = $product->getOriginal('stock');
            $newStock = $product->stock;
            $diff = $newStock - $oldStock;

            if ($diff == 0) return;

            // === A. LOGIKA STOK BERKURANG ===
            if ($diff < 0) {
                $this->createLog($product, 'out', abs($diff), $oldStock, $newStock, 'Update Manual (Stok Dikurangi)');
                return;
            }

            // === B. LOGIKA STOK BERTAMBAH (RESTOCK) ===
            
            // Cek Hutang PO Real dari Database
            $poItems = OrderItem::where('product_id', $product->id)
                ->where('is_preorder', true)
                ->where('preorder_status', 'waiting')
                ->orderBy('created_at', 'asc')
                ->get();

            $totalHutangPO = $poItems->sum('quantity');

            // Skenario 1: Tidak ada hutang PO
            if ($totalHutangPO <= 0) {
                $this->createLog($product, 'in', $diff, $oldStock, $newStock, 'Restock Barang (Admin)');
                return;
            }

            // Skenario 2: ADA hutang PO (Auto-Fulfillment)
            DB::transaction(function () use ($product, $diff, $oldStock, $newStock, $poItems) {
                
                $stokTersedia = $newStock;
                $fulfilledCount = 0;

                foreach ($poItems as $item) {
                    if ($stokTersedia >= $item->quantity) {
                        $stokTersedia -= $item->quantity;
                        $item->update(['preorder_status' => 'ready']);
                        $fulfilledCount += $item->quantity;
                    } else {
                        break; 
                    }
                }

                // 1. Catat Log Restock (Stok Fisik Masuk)
                $this->createLog($product, 'in', $diff, $oldStock, $newStock, 'Restock Barang (Admin)');

                if ($fulfilledCount > 0) {
                    $saldoStokFisik = $newStock - $fulfilledCount;

                    // 2. Catat Log Alokasi (Stok Fisik Keluar dipakai PO)
                    $this->createLog($product, 'out', $fulfilledCount, $newStock, $saldoStokFisik, "Fisik dialokasikan ke PO");

                    // 3. 🔥 UBAH LOG "PESAN PO" LAMA MENJADI "READY PO" 🔥
                    // Kita cari log lama, dan kita update barisnya (Bukan buat baru)
                    
                    // Ambil log lama sejumlah yang dilunasi
                    $oldPOLogs = StockMovement::where('product_id', $product->id)
                        ->where('is_preorder', true)
                        ->where('movement_type', 'in') // Cari yang masih "Masuk/Pesan"
                        ->orderBy('created_at', 'asc') // Urutkan dari yang terlama (FIFO)
                        ->limit($fulfilledCount) // Batasi sesuai jumlah yang ready
                        ->get();

                    foreach ($oldPOLogs as $log) {
                        $log->update([
                            'movement_type' => 'out', // Ubah tipe jadi OUT agar label berubah jadi "Ready PO"
                            'note' => $log->note . ' (Status: Ready/Siap Diambil)', // Update catatan
                            'skipProductUpdate' => true
                        ]);
                    }

                    // 4. Update Data Produk (Hard Update)
                    DB::table('products')->where('id', $product->id)->update([
                        'stock' => $stokTersedia,
                        'preorder_quantity' => DB::raw("GREATEST(0, preorder_quantity - $fulfilledCount)")
                    ]);
                }
            });
        }
    }

    protected function createLog($product, $type, $qty, $before, $after, $note)
    {
        StockMovement::create([
            'product_id' => $product->id,
            'movement_type' => $type,
            'quantity' => $qty,
            'balance_before' => $before,
            'balance_after' => $after,
            'note' => $note,
            'is_preorder' => false, 
            'skipProductUpdate' => true 
        ]);
    }
    
    public function created(Product $product)
    {
        if ($product->stock > 0 && $product->category !== 'Seragam Sekolah') {
            $this->createLog($product, 'in', $product->stock, 0, $product->stock, 'Stok Awal');
        }
    }
}
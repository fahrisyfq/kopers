<?php

namespace App\Observers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class OrderItemObserver
{
    // Pastikan pakai 'created', BUKAN 'saved'
    public function created(OrderItem $item)
    {
        // 1. Cek apakah ini Pre-order? (Jangan potong stok fisik)
        if ($item->is_preorder) return;

        // 2. CEK DUPLIKASI (PENTING!)
        // Kita cek apakah sudah ada log stok keluar untuk Item ID ini?
        // Catatan harus spesifik mencantumkan ID Item agar unik
        $noteUnik = "Penjualan Order #{$item->order_id} (Item-{$item->id})";

        $sudahAdaLog = StockMovement::where('product_id', $item->product_id)
            ->where('note', $noteUnik)
            ->exists();

        if ($sudahAdaLog) {
            return; // STOP! Jangan eksekusi lagi karena sudah tercatat.
        }

        DB::transaction(function () use ($item, $noteUnik) {
            
            // === LOGIKA STOK SAMA SEPERTI SEBELUMNYA ===
            
            // KASUS 1: PRODUK DENGAN UKURAN
            if ($item->product_size_id) {
                $size = ProductSize::find($item->product_size_id);
                if ($size) {
                    $oldStock = $size->stock;
                    $newStock = max(0, $size->stock - $item->quantity); // Cegah minus

                    $size->updateQuietly(['stock' => $newStock]);
                    $this->syncParentProduct($size);

                    $this->createLog($item->product_id, $item->product_size_id, $item->quantity, $oldStock, $newStock, $noteUnik);
                }
            } 
            // KASUS 2: PRODUK TANPA UKURAN
            else {
                $product = Product::find($item->product_id);
                if ($product) {
                    $oldStock = $product->stock;
                    $newStock = max(0, $product->stock - $item->quantity);

                    $product->updateQuietly(['stock' => $newStock]);

                    $this->createLog($item->product_id, null, $item->quantity, $oldStock, $newStock, $noteUnik);
                }
            }
        });
    }

    protected function createLog($productId, $sizeId, $qty, $before, $after, $note)
    {
        StockMovement::create([
            'product_id'      => $productId,
            'product_size_id' => $sizeId,
            'movement_type'   => 'out',
            'quantity'        => $qty,
            'balance_before'  => $before,
            'balance_after'   => $after,
            'note'            => $note, // Note ini sekarang berisi ID unik
            'is_preorder'     => false,
            'skipProductUpdate' => true 
        ]);
    }

    protected function syncParentProduct(ProductSize $size)
    {
        if ($size->product) {
            $totalStock = $size->product->sizes()->sum('stock');
            $size->product->updateQuietly(['stock' => $totalStock]);
        }
    }
}
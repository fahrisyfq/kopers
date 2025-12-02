<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    protected $fillable = ['product_id', 'size', 'stock'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // protected static function booted()
    // {
    //     static::saved(function ($size) {
    //         // ✅ Ambil pre-order yang menunggu untuk ukuran ini
    //         $preOrders = \App\Models\OrderItem::where('product_size_id', $size->id)
    //             ->where('is_preorder', true)
    //             ->where('preorder_status', 'waiting')
    //             ->orderBy('created_at')
    //             ->get();

    //         if ($size->stock > 0 && $preOrders->count() > 0) {
    //             foreach ($preOrders as $item) {
    //                 if ($size->stock >= $item->quantity) {
    //                     // stok cukup untuk semua pre-order item
    //                     $size->stock -= $item->quantity;

    //                     // ✅ Update status pre-order jadi ready
    //                     $item->update([
    //                         'is_preorder' => false,
    //                         'preorder_status' => 'ready',
    //                     ]);
    //                 } else {
    //                     // stok hanya cukup sebagian
    //                     $item->update([
    //                         'quantity' => $item->quantity - $size->stock,
    //                     ]);
    //                     $size->stock = 0;
    //                     break;
    //                 }
    //             }

    //             // ✅ Simpan ulang sisa stok tanpa trigger ulang event
    //             $size->saveQuietly();
    //         }

    //         // ✅ Update total stok produk
    //         if ($size->product) {
    //             $total = $size->product->sizes()->sum('stock');
    //             $size->product->updateQuietly(['stock' => $total]);
    //         }
    //     });
    // }
}

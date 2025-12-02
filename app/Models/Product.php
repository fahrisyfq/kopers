<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahkan jika menggunakan Factory

class Product extends Model
{
    use HasFactory; // Tambahkan jika menggunakan Factory

    protected $fillable = [
        'title',
        'description',
        'price',
        'stock',
        'image',
        'category',
        'is_preorder',
        'is_active', // 💡 Ditambahkan
        'preorder_quantity', // 💡 Ditambahkan
    ];

    protected $casts = [
        'is_preorder' => 'boolean',
        'is_active' => 'boolean', // 💡 Ditambahkan
    ];

    // === Relasi ===
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    // === Update stok otomatis setiap kali disimpan ===
//     protected static function booted()
// {
//     // === Update total stok dari sizes untuk kategori yang punya ukuran ===
//     static::saved(function ($product) {
//         if ($product->sizes()->exists()) {
//             $totalStock = $product->sizes->sum('stock');
//             if ((int)$product->stock !== (int)$totalStock) {
//                 $product->stock = $totalStock;
//                 $product->saveQuietly();
//             }
//         }
//     });

//     // === Sinkron pre-order untuk SEMUA kategori ===
//     static::saved(function ($product) {
//         // Jalankan hanya jika stok berubah (hindari infinite loop)
//         if ($product->wasChanged('stock')) {

//             $preOrders = \App\Models\OrderItem::where('product_id', $product->id)
//                 ->where('is_preorder', true)
//                 ->where('preorder_status', 'waiting')
//                 ->orderBy('created_at')
//                 ->get();

//             if ($product->stock > 0 && $preOrders->count() > 0) {
//                 $currentStock = $product->stock;
//                 $needsUpdate = false;

//                 foreach ($preOrders as $item) {
//                     if ($currentStock >= $item->quantity) {
//                         $currentStock -= $item->quantity;

//                         $item->update([
//                             'is_preorder' => false,
//                             'preorder_status' => 'ready',
//                         ]);

//                         $needsUpdate = true;
//                     } elseif ($currentStock > 0) {
//                         $item->update([
//                             'quantity' => $item->quantity - $currentStock,
//                         ]);
//                         $currentStock = 0;
//                         $needsUpdate = true;
//                         break;
//                     } else {
//                         break; // stok habis
//                     }
//                 }

//                 if ($needsUpdate && $product->stock !== $currentStock) {
//                     $product->stock = $currentStock;
//                     $product->saveQuietly();
//                 }
//             }
//         }
//     });
// }



    // === Cek apakah bisa dipesan ===
    public function isAvailableForOrder(): bool
    {
        // 💡 Produk harus Aktif DAN memiliki stok > 0 atau Pre-order diaktifkan
        return $this->is_active && ($this->stock > 0 || $this->is_preorder);
    }

    // === Tentukan label status untuk ditampilkan di tabel ===
    public function stockStatusLabel(): string
    {
        $stock = $this->category === 'Seragam Sekolah'
            ? $this->sizes->sum('stock')
            : $this->stock;

        if ($stock > 0) {
            return 'Ready Stock';
        }

        return $this->is_preorder ? 'Pre Order' : 'Habis';
    }

    public function getPreorderQuantityAttribute()
    {
        return $this->orderItems()
            ->where('is_preorder', true)
            ->where('preorder_status', 'waiting')
            ->sum('quantity');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Fungsi bantu untuk hitung total stok berdasarkan kartu stok
    public function getStockFromCardAttribute()
    {
        $in = $this->stockMovements()->where('type', 'in')->sum('quantity');
        $out = $this->stockMovements()->where('type', 'out')->sum('quantity');
        return $in - $out;
    }

}
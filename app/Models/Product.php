<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'stock',
        'image',
        'category',
        'is_preorder',
    ];

    protected $casts = [
        'is_preorder' => 'boolean',
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
    protected static function booted()
{
    // === Update total stok dari sizes ===
    static::saved(function ($product) {
        if ($product->sizes()->exists()) {
            $totalStock = $product->sizes->sum('stock');
            if ($product->stock !== $totalStock) {
                $product->stock = $totalStock;
                $product->saveQuietly();
            }
        }
    });

    // === Sinkron pre-order saat stok bertambah ===
    static::saved(function ($product) {
        // ambil pre-order yang masih waiting
        $preOrders = \App\Models\OrderItem::where('product_id', $product->id)
            ->where('is_preorder', true)
            ->where('preorder_status', 'waiting')
            ->orderBy('created_at')
            ->get();

        // kalau ada stok dan ada pre-order
        if ($product->stock > 0 && $preOrders->count() > 0) {
            foreach ($preOrders as $item) {
                if ($product->stock >= $item->quantity) {
                    // kurangi stok dan ubah status pre-order
                    $product->stock -= $item->quantity;
                    $product->saveQuietly();

                    $item->update([
                        'is_preorder' => false,
                        'preorder_status' => 'ready',
                    ]);
                } else {
                    // kalau stok tidak cukup untuk semua pre-order
                    $item->update([
                        'quantity' => $item->quantity - $product->stock,
                    ]);
                    $product->stock = 0;
                    $product->saveQuietly();
                    break;
                }
            }
        }
    });
}



    // === Cek apakah bisa dipesan ===
    public function isAvailableForOrder(): bool
    {
        return $this->stock > 0 || $this->is_preorder;
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

}

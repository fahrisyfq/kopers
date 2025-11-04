<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
         'order_id',
        'product_id',
        'product_size_id',
        'quantity',
        'price',
        'subtotal',
        'payment_status', 
        'is_preorder',
        'preorder_status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productSize()
    {
    return $this->belongsTo(\App\Models\ProductSize::class);
    }


    public function size()
    {
        return $this->belongsTo(ProductSize::class, 'product_size_id');
    }

    protected static function booted()
    {
         static::created(function ($item) {
        // ❌ HAPUS bagian pengurangan stok agar tidak double
        if ($item->order) {
            $item->order->calculateTotalPrice();
        }
    });

    static::updated(function ($item) {
        if ($item->wasChanged('quantity') && $item->size) {
            $diff = $item->quantity - $item->getOriginal('quantity');
            // hanya kurangi atau tambah stok jika quantity berubah dari halaman admin
            if ($diff > 0) {
                $item->size->decrement('stock', $diff);
            } elseif ($diff < 0) {
                $item->size->increment('stock', abs($diff));
            }
        }

        if ($item->order) {
            $item->order->calculateTotalPrice();
        }
    });

    static::deleted(function ($item) {
        if ($item->size) {
            $item->size->increment('stock', $item->quantity);
        }
        if ($item->order) {
            $item->order->calculateTotalPrice();
        }
    });
    }

    public function getStatusLabelAttribute()
{
    if ($this->is_preorder && $this->preorder_status === 'waiting') {
        return 'Pre-Order';
    }

    if ($this->is_preorder && $this->preorder_status === 'ready') {
        return 'Ready Stock — Bisa datang ke sekolah untuk mengukur & melakukan pembayaran.';
    }

    return 'Ready Stock';
}

}


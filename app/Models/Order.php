<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// Anda mungkin tidak memerlukan ini, 'Has' tidak digunakan
// use Illuminate\Testing\Fluent\Concerns\Has; 

class Order extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'user_id',
        'total_price',
        'payment_method',
        'status',
        'payment_status',
        'proof_of_payment', // 🟢 DITAMBAHKAN INI
        'is_printed', // 🟢 DITAMBAHKAN INI
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function booted()
    {
        static::saved(function (Order $order) {
            $order->calculateTotalPrice();
        });
    }

    public function calculateTotalPrice(): float
    {
        // Gunakan relasi items() untuk konsistensi
        $total = $this->items()->sum(\DB::raw('price * quantity')); 
        
        if ((float)$this->total_price !== (float)$total) {
            $this->forceFill(['total_price' => $total])->saveQuietly();
        }
        return $total;
    }
}
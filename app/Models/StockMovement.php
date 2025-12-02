<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_size_id',
        'movement_type',
        'quantity',
        'is_preorder',
        'note',
        'balance_before',
        'balance_after',
        // 'user_id', 
    ];

    // Flag untuk mencegah looping (penting!)
    public $skipProductUpdate = false;

    public function product() { return $this->belongsTo(Product::class); }
    public function productSize() { return $this->belongsTo(ProductSize::class); }

}
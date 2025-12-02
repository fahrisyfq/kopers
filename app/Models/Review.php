<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // [PENTING] Tambahkan ini

class Review extends Model
{
    protected $fillable = ['user_id', 'product_id', 'rating', 'body', 'is_anonymous', 'is_approved'];

// Relasi ke Product
public function product()
{
    return $this->belongsTo(Product::class);
}

// Relasi ke User
public function user()
{
    return $this->belongsTo(User::class);
}
}
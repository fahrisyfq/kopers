<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GlobalSetting extends Model
{
    use HasFactory;
    
    protected $table = 'global_settings'; 

    protected $fillable = ['is_store_open'];

    protected $casts = [
        'is_store_open' => 'boolean',
    ];
    
    /**
     * Mengambil status toko global.
     */
    public static function getStoreStatus(): bool
    {
        // Ambil baris pertama, jika tidak ada, defaultkan ke true (Aktif)
        return self::first()->is_store_open ?? true;
    }
}
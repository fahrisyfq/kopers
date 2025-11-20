<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_size_id',
        'movement_type',
        'quantity',
        'note',
        'balance_before',
        'balance_after',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productSize()
    {
        return $this->belongsTo(ProductSize::class);
    }

    protected static function booted()
    {
        static::creating(function ($movement) {
            $movement->description = $movement->note ?? 'Tidak ada deskripsi';

            // === Jika berdasarkan size (kategori Seragam Sekolah)
            if ($movement->product_size_id) {
                $size = ProductSize::find($movement->product_size_id);
                if (!$size) return;

                $currentStock = $size->stock ?? 0;
                $movement->balance_before = $currentStock; // stok sebelum perubahan

                $newStock = $currentStock;
                switch ($movement->movement_type) {
                    case 'in':
                        $newStock += $movement->quantity;
                        break;
                    case 'out':
                        $newStock = max(0, $currentStock - $movement->quantity);
                        break;
                    case 'preorder':
                        // tidak ubah stok langsung
                        break;
                }

                $movement->balance_after = $newStock;
                $size->update(['stock' => $newStock]);

                // Update stok total produk (sum semua size)
                if ($size->product) {
                    $totalStock = $size->product->sizes()->sum('stock');
                    $size->product->updateQuietly(['stock' => $totalStock]);
                }

                return;
            }

            // === Jika produk tidak punya ukuran
            $product = Product::find($movement->product_id);
            if (!$product) return;

            $currentStock = $product->stock ?? 0;
            $movement->balance_before = $currentStock; // stok sebelum perubahan

            $newStock = $currentStock;
            switch ($movement->movement_type) {
                case 'in':
                    $newStock += $movement->quantity;
                    break;
                case 'out':
                    $newStock = max(0, $currentStock - $movement->quantity);
                    break;
                case 'preorder':
                    break;
            }

            $movement->balance_after = $newStock;
            $product->update(['stock' => $newStock]);
        });
    }
}

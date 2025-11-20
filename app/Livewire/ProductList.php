<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\GlobalSetting; // 💡 Import GlobalSetting
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public function render()
    {
        // 💡 Ambil status toko global
        $isStoreOpen = GlobalSetting::getStoreStatus();

        // 💡 Ambil produk aktif, tetapi jika toko tutup, tidak ada produk yang dimuat.
        $query = Product::where('is_active', true);

        if (!$isStoreOpen) {
            $products = collect(); // Kembalikan koleksi kosong jika toko tutup
        } else {
            $products = $query->latest()->paginate(9); // 9 produk per halaman
        }
        
        return view('livewire.product-list', compact('products', 'isStoreOpen')); // Kirim status toko
    }
}
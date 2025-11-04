<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public function render()
    {
        $products = Product::latest()->paginate(9); // 9 produk per halaman
        return view('livewire.product-list', compact('products'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  public function index()
{
    $products = Product::with(['images', 'sizes'])->get();
    return view('products.index', compact('products'));
}
public function show($id)
{
    $product = Product::with(['images', 'sizes'])->findOrFail($id);
    return view('products.detail', compact('product'));
}

    public function render()
    {
    $products = Product::with(['images', 'sizes'])->get();
    return view('livewire.product-list', compact('products'));
    }

    public function search(Request $request)
{
    $query = $request->input('q');
    $products = Product::where('title', 'like', "%{$query}%")
        ->orWhere('description', 'like', "%{$query}%")
        ->get();
    return view('products.index', compact('products', 'query'));
}
}

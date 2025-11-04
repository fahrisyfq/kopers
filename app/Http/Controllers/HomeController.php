<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 4 produk terbaru (bisa ubah sesuai kebutuhan)
        $products = Product::latest()->take(4)->get();

        return view('home', compact('products'));
    }
}

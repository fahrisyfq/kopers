<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\GlobalSetting; // 💡 TAMBAH INI
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // FILTER: Hanya ambil produk yang Aktif
        $products = Product::with(['images', 'sizes'])
                        ->where('is_active', true) 
                        ->get();
                        
        // 💡 Ambil status toko global
        $isStoreOpen = GlobalSetting::getStoreStatus();
                        
        return view('products.index', compact('products', 'isStoreOpen')); // 💡 Kirim status toko
    }

    public function show($id)
    {
        // Cek Status Toko Global (Jika toko tutup, jangan tampilkan produk)
        if (!GlobalSetting::getStoreStatus()) {
             abort(404);
        }
        
        // FILTER: Hanya tampilkan produk yang Aktif. Jika tidak aktif, return 404.
        $product = Product::with(['images', 'sizes'])
                        ->where('is_active', true)
                        ->findOrFail($id);
        
        return view('products.detail', compact('product'));
    }

    public function render()
    {
        // 💡 Ambil status toko global
        $isStoreOpen = GlobalSetting::getStoreStatus();
        
        // FILTER: Hanya ambil produk yang Aktif
        $query = Product::with(['images', 'sizes'])
                        ->where('is_active', true);
        
        // Jangan tampilkan data jika toko tutup (kecuali Livewire handle sendiri)
        if (!$isStoreOpen) {
            $products = collect();
        } else {
            $products = $query->get();
        }
        
        return view('livewire.product-list', compact('products', 'isStoreOpen'));
    }

    public function search(Request $request)
    {
        // Cek Status Toko Global (Agar pencarian dinonaktifkan jika toko tutup)
        if (!GlobalSetting::getStoreStatus()) {
             $products = collect();
             $query = $request->input('q');
             return view('products.index', compact('products', 'query'));
        }
        
        $query = $request->input('q');
        // FILTER: Hanya cari produk yang Aktif
        $products = Product::where('is_active', true) 
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%");
            })
            ->get();
        return view('products.index', compact('products', 'query'));
    }
}
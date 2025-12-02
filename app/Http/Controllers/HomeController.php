<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. AMBIL DATA PRODUK (Untuk seksi Produk Unggulan)
        $products = Product::latest()->take(4)->get();

        // 2. AMBIL DATA ULASAN
        $reviewsData = Review::with(['user', 'product'])
            ->where('is_approved', true)
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($review) {
                // --- LOGIKA NAMA ---
                // Jika anonim -> 'Siswa SMKN 8'
                // Jika tidak -> Cek kolom 'nama_lengkap', jika kosong pakai 'name' (email name)
                $displayName = $review->is_anonymous 
                    ? 'Siswa SMKN 8' 
                    : ($review->user->nama_lengkap ?? $review->user->name);

                return [
                    'name' => $displayName,
                    'role' => $review->is_anonymous ? 'Disamarkan' : ($review->user->kelas ?? 'Siswa'),
                    'text' => $review->body,
                    'rating' => $review->rating,
                    'time' => $review->created_at->diffForHumans(),
                    'is_anon' => (bool) $review->is_anonymous,
                    
                    // Avatar pakai nama yang ditampilkan
                    'avatar' => $review->is_anonymous 
                        ? null 
                        : 'https://ui-avatars.com/api/?name=' . urlencode($displayName) . '&background=random&color=fff',
                    
                    // Data Produk
                    'product' => $review->product ? [
                        'title' => $review->product->title,
                        'image' => $review->product->image 
                    ] : null,
                ];
            });

        // 3. KIRIM KE VIEW
        return view('home', [
            'products' => $products,
            'reviews' => $reviewsData,
        ]);
    }
}
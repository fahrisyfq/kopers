<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        try {
            // 1. Validasi
            $request->validate([
                'product_id'   => 'required|exists:products,id',
                'rating'       => 'required|integer|min:1|max:5',
                'body'         => 'nullable|string|max:500',
                'is_anonymous' => 'boolean',
            ]);

            $user = Auth::user();

            // 2. Cek apakah user pernah memesan produk ini (Status apapun)
            $hasOrdered = Order::where('user_id', $user->id)
                ->whereHas('items', function ($query) use ($request) {
                    $query->where('product_id', $request->product_id);
                })
                ->exists();

            if (!$hasOrdered) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda harus memiliki riwayat pesanan produk ini untuk mengulas.'
                ], 403);
            }

            // 3. Cek Duplikat Review
            $exists = Review::where('user_id', $user->id)
                ->where('product_id', $request->product_id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah mengulas produk ini.'
                ], 422);
            }

            // 4. Simpan
            Review::create([
                'user_id'      => $user->id,
                'product_id'   => $request->product_id,
                'rating'       => $request->rating,
                'body'         => $request->body,
                'is_anonymous' => $request->boolean('is_anonymous'),
                'is_approved'  => false,
            ]);

            return response()->json(['success' => true, 'message' => 'Ulasan berhasil dikirim!']);

        } catch (\Exception $e) {
            // Log error sistem untuk debugging
            Log::error('Review Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}
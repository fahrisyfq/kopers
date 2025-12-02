<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CartController,
    LoginController,
    OrderController,
    ProductController,
    ProfileController,
    HomeController,
    ContactController,
    ReviewController
};
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\Review;

/*
|--------------------------------------------------------------------------
| 🌐 HALAMAN UMUM
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category', fn() => view('category'))->name('category');
Route::get('/kontak', fn() => view('kontak'))->name('kontak');
// routes/web.php
// routes/web.php

Route::get('/testimoni', function () {
    // 1. Ambil data review dari database, urutkan terbaru
    $reviews = Review::with(['user', 'product']) // Eager load relasi biar cepat
        ->latest()
        ->get()
        ->map(function ($review) {
            // 2. Format data agar sesuai dengan AlpineJS di view
            return [
                'name'    => $review->is_anonymous ? 'Sobat Kopsis' : ($review->user->name ?? 'Pengguna'),
                'role'    => 'Siswa', // Bisa disesuaikan dynamic kalau ada data kelas
                'avatar'  => $review->is_anonymous 
                                ? 'https://ui-avatars.com/api/?name=Anonim&background=334155&color=ffffff' 
                                : ($review->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name ?? 'User')),
                'rating'  => $review->rating,
                'text'    => $review->body ?? 'Tidak ada komentar tertulis.',
                'time'    => $review->created_at->diffForHumans(), // Contoh: "2 jam yang lalu"
                'is_anon' => (bool) $review->is_anonymous,
                'product' => $review->product ? [
                    'title' => $review->product->title,
                    // Pastikan path image sesuai storage kamu
                    'image' => $review->product->image ? asset('storage/' . $review->product->image) : null
                ] : null
            ];
        });

    // 3. Kirim variabel $reviews ke view
    return view('partials.testimonials', [
        'reviews' => $reviews
    ]);
})->name('testimonials.index');
/*
|--------------------------------------------------------------------------
| 🛍️ PRODUK
|--------------------------------------------------------------------------
*/

Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
Route::get('/products', [ProductController::class, 'index'])->name('product.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/search', [ProductController::class, 'search'])->name('product.search');

/*
|--------------------------------------------------------------------------
| 🧾 ORDER
|--------------------------------------------------------------------------
*/

Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

/*
|--------------------------------------------------------------------------
| 🔐 AUTH (GOOGLE LOGIN + LOGOUT)
|--------------------------------------------------------------------------
*/

// Login via Google
Route::get('/auth/google', [LoginController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [LoginController::class, 'handleGoogleCallback']);

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Login manual (POST)
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/register', [LoginController::class, 'register'])->name('register');

// GET /login → redirect ke Google (biar error MethodNotAllowed nggak muncul)
Route::get('/login', function () {
    return redirect()->route('google.login');
})->name('login.form');

/*
|--------------------------------------------------------------------------
| 👤 PROFIL USER
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:web'])->group(function () {
    // Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/lengkapi-profil', [ProfileController::class, 'complete'])->name('profile.complete');
    Route::put('/lengkapi-profil', [ProfileController::class, 'storeComplete'])->name('profile.storeComplete');
    Route::get('/edit-profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/edit-profil', [ProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| 🛒 CART & CHECKOUT (Hanya user login + profil lengkap)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:web', 'profile.complete'])->group(function () {
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/count', function () {
    return response()->json(['count' => count(session('cart', []))]);
});


    // Ajax Cart
    Route::post('/cart/ajax-update', [CartController::class, 'ajaxUpdate'])->name('cart.ajaxUpdate');
    Route::post('/cart/ajax-remove', [CartController::class, 'ajaxRemove'])->name('cart.ajaxRemove');
    Route::get('/cart/ajax-get', [CartController::class, 'ajaxGet'])->name('cart.ajaxGet');
    Route::post('/cart/ajax-add/{id}', [CartController::class, 'ajaxAdd'])->name('cart.ajaxAdd');

    // Checkout
    Route::get('/checkout', [CartController::class, 'checkoutIndex'])->name('checkout.index');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // Beli langsung
    Route::post('/cart/buy/{id}', [CartController::class, 'buy'])->name('cart.buy');

});

Route::middleware(['auth'])->group(function () {
    // Route untuk kirim review
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    
    // Route pesanan saya (asumsi sudah ada)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});
/*
|--------------------------------------------------------------------------
| 🧑‍💼 ADMIN (FILAMENT)
|--------------------------------------------------------------------------
|
| Admin pakai guard `admin` dan login lewat Filament
| URL: /admin/login → otomatis disediakan oleh Filament
|
*/

/*
|--------------------------------------------------------------------------
| 📩 KONTAK / PESAN
|--------------------------------------------------------------------------
*/

Route::post('/kirim-pesan', [ContactController::class, 'kirim'])->name('kirim.pesan');

/*
|--------------------------------------------------------------------------
| ⚙️ FILAMENT CUSTOM ACTION
|--------------------------------------------------------------------------
*/

Route::patch('/filament/order-items/{id}/update-status', function ($id, Request $request) {
    $item = OrderItem::findOrFail($id);
    $item->update(['payment_status' => $request->payment_status]);
    return back();
})->name('filament.update-payment-status');


Route::get('/panduan', function () {
    return view('panduan');
})->name('panduan');

use App\Models\Product;
use App\Models\ProductSize;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

Route::get('/fix-data-po', function () {
    DB::beginTransaction();
    try {
        $fixedCount = 0;
        $logs = [];

        // === 1. PERBAIKI PRODUK VARIAN (UKURAN) ===
        $sizes = ProductSize::all();
        foreach ($sizes as $size) {
            // Hitung berapa kali sistem PERNAH mengalokasikan stok fisik ke PO
            $totalAllocated = StockMovement::where('product_size_id', $size->id)
                ->where('movement_type', 'out')
                ->where('note', 'like', '%Dialokasikan%') // Kuncinya di history ini
                ->sum('quantity');

            // Hitung berapa pesanan yang SAAT INI statusnya Ready
            $totalReady = OrderItem::where('product_size_id', $size->id)
                ->where('is_preorder', true)
                ->where('preorder_status', 'ready')
                ->sum('quantity');

            // Selisihnya adalah item yang "Nyangkut" (Stok fisik udah ilang, tapi status belum ready)
            $diff = $totalAllocated - $totalReady;

            if ($diff > 0) {
                // Ambil pesanan WAITING terlama, ubah paksa jadi READY
                $stuckItems = OrderItem::where('product_size_id', $size->id)
                    ->where('is_preorder', true)
                    ->where('preorder_status', 'waiting')
                    ->orderBy('created_at', 'asc')
                    ->limit($diff)
                    ->get();

                foreach ($stuckItems as $item) {
                    $item->update(['preorder_status' => 'ready']);
                    $fixedCount++;
                }
                $logs[] = "Size {$size->size}: Fixed {$diff} items.";
            }
        }

        // === 2. HITUNG ULANG TOTAL WAITING DI TABEL PRODUK ===
        $products = Product::all();
        foreach ($products as $product) {
            $realWaiting = OrderItem::where('product_id', $product->id)
                ->where('is_preorder', true)
                ->where('preorder_status', 'waiting')
                ->sum('quantity');

            $product->updateQuietly(['preorder_quantity' => $realWaiting]);
        }

        DB::commit();
        return response()->json([
            'status' => 'SUCCESS',
            'message' => "Berhasil memperbaiki status {$fixedCount} pesanan yang nyangkut.",
            'details' => $logs
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return "Error: " . $e->getMessage();
    }
});
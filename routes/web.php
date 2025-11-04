<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CartController,
    LoginController,
    OrderController,
    ProductController,
    ProfileController,
    HomeController,
    ContactController
};
use App\Models\OrderItem;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| 🌐 HALAMAN UMUM
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category', fn() => view('category'))->name('category');
Route::get('/kontak', fn() => view('kontak'))->name('kontak');

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

    // Riwayat order
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

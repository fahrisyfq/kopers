<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /** ========================
     * 🛒 Halaman Keranjang
     * ======================== */
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    /** Tambah produk ke keranjang (form biasa) */
    public function add(Request $request, $id)
{
    $user = auth()->user();

    // ✅ Cek apakah profil sudah lengkap
    if (
        empty($user->nama_lengkap) ||
        empty($user->nisn) ||
        empty($user->kelas) ||
        empty($user->jurusan) ||
        empty($user->no_telp_siswa) ||
        empty($user->no_telp_ortu)
    ) {
        return redirect()->route('profile.complete')
            ->with('warning', 'Silakan lengkapi profil terlebih dahulu sebelum menambah produk ke keranjang.');
    }

    // ✅ Jika profil lengkap, lanjut tambah ke keranjang
    return $this->addToCart($request, $id)
        ? redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang.')
        : redirect()->back()->with('error', 'Gagal menambahkan produk. Stok habis dan tidak bisa pre-order.');
}


    /** Tambah produk via AJAX */
    public function ajaxAdd(Request $request, $id)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Harus login untuk belanja.']);
        }

        $user = auth()->user();
       if (
            empty($user->nama_lengkap) ||
            empty($user->nisn) ||
            empty($user->kelas) ||
            empty($user->jurusan) ||
            empty($user->no_telp_siswa) ||
            empty($user->no_telp_ortu)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Lengkapi profil dulu sebelum belanja.',
                'redirect' => route('profile.complete'),
            ]);
        }


        $cart = $this->addToCart($request, $id);

        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Stok habis dan produk tidak bisa pre-order.']);
        }

        return response()->json(['success' => true, 'cart' => $cart, 'message' => 'Produk ditambahkan ke keranjang.']);
    }

    /** Update quantity */
    public function update(Request $request, $key)
    {
        $cart = session('cart', []);
        if (!isset($cart[$key])) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan di keranjang.');
        }

        $item = $cart[$key];
        $newQty = max(1, (int) $request->input('quantity', 1));
        $stock = (int) ($item['stock'] ?? 0);

        if ($newQty > $stock && !$item['is_preorder']) {
            return redirect()->back()->with('error', '❌ Stok produk tidak mencukupi.');
        }

        $cart[$key]['quantity'] = $newQty;
        session(['cart' => $cart]);

        return redirect()->back()->with('success', 'Jumlah produk diperbarui.');
    }

    /** Hapus item dari keranjang */
    public function remove(Request $request, $key)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }
        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }

    /** Kosongkan keranjang */
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Keranjang dikosongkan.');
    }

    /** Update quantity via AJAX */
    public function ajaxUpdate(Request $request)
    {
        $key = $request->input('key');
        $qty = max(1, (int) $request->input('quantity', 1));
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $maxStock = $cart[$key]['stock'] ?? 0;
            $cart[$key]['quantity'] = $cart[$key]['is_preorder'] ? $qty : min($qty, $maxStock);
            session()->put('cart', $cart);
        }

        return response()->json(['cart' => $cart]);
    }

    /** Hapus item via AJAX */
    public function ajaxRemove(Request $request)
    {
        $key = $request->input('key');
        $cart = session()->get('cart', []);
        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }
        return response()->json(['cart' => $cart]);
    }

    /** Ambil data keranjang */
    public function ajaxGet()
    {
        return response()->json(session()->get('cart', []));
    }

    /** ========================
     * 💳 Checkout
     * ======================== */
    public function checkoutIndex()
    {
        $cart = session()->get('cart', []);
        return view('checkout.index', compact('cart'));
    }

    public function checkout(Request $request)
    {
        $selected = $request->input('selected', []);
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        if (empty($selected)) {
            return redirect()->route('checkout.index')->with('error', 'Pilih minimal 1 produk untuk checkout.');
        }

        // 🟢 1. UBAH VALIDASI: Tambahkan metode baru & validasi file
        $request->validate([
            'payment_method' => 'required|in:cash,kjp,transfer_bank,e_wallet',
            'proof_of_payment' => 'required_if:payment_method,transfer_bank,e_wallet|nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Max 2MB
        ]);

        $paymentMethod = $request->input('payment_method');
        $proofPath = null; // Inisialisasi path file

        // 🟢 2. TAMBAHKAN LOGIKA UPLOAD FILE
        if ($request->hasFile('proof_of_payment')) {
            try {
                // Simpan file di 'storage/app/public/payment_proofs'
                // Pastikan Anda sudah menjalankan `php artisan storage:link`
                $proofPath = $request->file('proof_of_payment')->store('payment_proofs', 'public');
            } catch (\Throwable $e) {
                Log::error('Gagal upload bukti pembayaran: ' . $e->getMessage());
                return redirect()->route('checkout.index')->with('error', 'Gagal mengunggah bukti pembayaran.');
            }
        }

        $total = 0;
        foreach ($selected as $key) {
            if (isset($cart[$key])) {
                $total += ($cart[$key]['price'] ?? 0) * ($cart[$key]['quantity'] ?? 1);
            }
        }

        try {
            \DB::beginTransaction();

            // 🟢 3. UBAH ORDER::CREATE: Masukkan path bukti bayar
            $order = Order::create([
                'user_id' => auth()->id(),
                'total_price' => $total,
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending', // Tetap pending, menunggu verifikasi admin
                'proof_of_payment' => $proofPath, // Simpan path file
            ]);

            // ... (Logika foreach untuk order items tetap sama) ...
            foreach ($selected as $key) {
                if (!isset($cart[$key])) continue;

                $item = $cart[$key];
                $quantity = $item['quantity'] ?? 1;
                $product = Product::lockForUpdate()->find($item['id']);
                if (!$product) continue;

                $productSizeId = null;
                $isPreorder = false;

                /** 🔹 Jika produk punya ukuran */
                if (!empty($item['size'])) {
                    $productSize = \App\Models\ProductSize::where('product_id', $product->id)
                        ->where('size', $item['size'])
                        ->lockForUpdate()
                        ->first();

                    if (!$productSize) continue;
                    $isPreorder = ($product->is_preorder && $productSize->stock <= 0);

                    if ($productSize->stock < $quantity && !$isPreorder) {
                        \DB::rollBack();
                        return redirect()->route('checkout.index')->with(
                            'error',
                            "Stok tidak cukup untuk {$item['title']} ukuran {$item['size']}."
                        );
                    }
                    if ($isPreorder) {
                        $product->increment('preorder_quantity', $quantity);
                    } else {
                        $productSize->decrement('stock', $quantity);
                    }
                    $productSizeId = $productSize->id;

                } else {
                    /** 🔹 Produk tanpa ukuran */
                    $isPreorder = ($product->is_preorder && $product->stock <= 0);

                    if ($product->stock < $quantity && !$isPreorder) {
                        \DB::rollBack();
                        return redirect()->route('checkout.index')->with(
                            'error',
                            "Stok tidak cukup untuk {$item['title']}."
                        );
                    }
                    if ($isPreorder) {
                        $product->increment('preorder_quantity', $quantity);
                    } else {
                        $product->decrement('stock', $quantity);
                    }
                }

                /** Simpan item ke tabel order_items */
                $order->items()->create([
                    'product_id' => $product->id,
                    'product_size_id' => $productSizeId,
                    'quantity' => $quantity,
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $quantity,
                    'is_preorder' => $isPreorder,
                    'preorder_status' => $isPreorder ? 'waiting' : 'ready',
                ]);

                unset($cart[$key]);
            }

            session()->put('cart', $cart);
            \DB::commit();

        } catch (\Throwable $e) {
            \DB::rollBack();

            // 🟢 4. TAMBAHKAN ROLLBACK FILE: Hapus file jika DB gagal
            if ($proofPath && Storage::disk('public')->exists($proofPath)) {
                Storage::disk('public')->delete($proofPath);
            }

            Log::error('Checkout gagal: ' . $e->getMessage());
            return redirect()->route('checkout.index')->with('error', 'Terjadi kesalahan saat checkout.');
        }

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat!');
    }

    /** ========================
     * 🧩 Helper: Tambah Produk ke Keranjang
     * ======================== */
    private function addToCart(Request $request, $id)
    {
        // ... (Fungsi ini tidak perlu diubah, biarkan seperti aslinya) ...
        $product = Product::findOrFail($id);
        $size = $request->input('size');
        $cartKey = $id . ($size ? '-' . $size : '');
        $cart = session()->get('cart', []);

        $maxStock = $size
            ? optional($product->sizes()->where('size', $size)->first())->stock ?? 0
            : $product->stock;

        if ($maxStock <= 0 && !$product->is_preorder) {
            return false;
        }

        $cartItem = $cart[$cartKey] ?? [
            'id' => $product->id,
            'title' => $product->title,
            'price' => $product->price,
            'quantity' => 0,
            'stock' => $maxStock,
            'category' => $product->category,
            'size' => $size,
            'image' => $product->image ?? null,
            'old_price' => $product->old_price ?? null,
            'is_preorder' => $product->is_preorder,
        ];

        if ($maxStock > 0) {
            $cartItem['quantity'] = min(($cartItem['quantity'] ?? 0) + 1, $maxStock);
        } else {
            $cartItem['quantity'] = ($cartItem['quantity'] ?? 0) + 1;
        }

        $cart[$cartKey] = $cartItem;
        session()->put('cart', $cart);

        return $cart;
    }
}

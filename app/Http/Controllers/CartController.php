<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;      // [TAMBAHAN] Pastikan ini ada
use Illuminate\Support\Facades\Storage;  // [TAMBAHAN] Pastikan ini ada

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
        $cart = session()->get('cart');

        if (!isset($cart[$key])) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Item tidak ditemukan.'], 404);
            }
            return redirect()->route('cart.index')->with('error', 'Item tidak ditemukan.');
        }

        $quantity = (int) $request->input('quantity', 1);
        $stock = $cart[$key]['stock']; 

        if ($quantity < 1) {
            $quantity = 1;
        }
        if ($quantity > $stock && !$cart[$key]['is_preorder']) { // [PERBAIKAN] Izinkan jika preorder
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Stok tidak mencukupi.'], 422);
            }
            return redirect()->route('cart.index')->with('error', 'Stok tidak mencukupi.');
        }

        $cart[$key]['quantity'] = $quantity;
        session()->put('cart', $cart);

        if ($request->wantsJson()) {
            
            $newSubtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
            $newTotal = $newSubtotal;
            $newItemTotal = $cart[$key]['price'] * $quantity;

            return response()->json([
                'success' => true,
                'message' => 'Kuantitas diperbarui!',
                'itemTotalFormatted' => 'Rp ' . number_format($newItemTotal, 0, ',', '.'),
                'subtotalFormatted' => 'Rp ' . number_format($newSubtotal, 0, ',', '.'),
                'totalFormatted' => 'Rp ' . number_format($newTotal, 0, ',', '.'),
                'itemCount' => count($cart)
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Kuantitas diperbarui!');
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

    /** Update quantity via AJAX (Fungsi ini sepertinya duplikat, tapi kita biarkan) */
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

    /**
     * [PERBAIKAN UTAMA]
     * Menampilkan halaman checkout HANYA dengan item yang dipilih
     */
    public function checkoutIndex(Request $request)
    {
        // Ambil SEMUA item di keranjang
        $cart = session()->get('cart', []);
        
        // Ambil array 'selected' dari URL (?selected[]=key1&selected[]=key2)
        $selectedKeys = $request->input('selected', []);

        if (empty($selectedKeys) || empty($cart)) {
            // Jika tidak ada yang dipilih, kembalikan ke keranjang
            return redirect()->route('cart.index')->with('error', 'Anda harus memilih minimal satu item untuk checkout.');
        }

        // Filter keranjang agar HANYA berisi item yang dicentang
        $selectedItems = collect($cart)->filter(function($item, $key) use ($selectedKeys) {
            // Pastikan key ada di daftar yang dipilih
            return in_array($key, $selectedKeys);
        })->all();

        // Jika setelah filter ternyata kosong (misal session aneh)
        if (empty($selectedItems)) {
             return redirect()->route('cart.index')->with('error', 'Item yang dipilih tidak valid. Silakan coba lagi.');
        }

        // Kirim HANYA item yang terpilih ke view checkout
        return view('checkout.index', [
            'selectedItems' => $selectedItems // Ganti nama variabel
        ]);
    }

    /** Memproses pembayaran checkout */
    public function checkout(Request $request)
    {
        $selected = $request->input('selected', []);
        $cart = session()->get('cart', []); // Ambil cart utuh dari session

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        if (empty($selected)) {
            // Ini seharusnya tidak terjadi jika validasi di checkoutIndex() benar
            return redirect()->route('cart.index')->with('error', 'Pilih minimal 1 produk untuk checkout.');
        }

        // 🟢 1. Validasi: Tambahkan metode baru & validasi file
        $request->validate([
            'payment_method' => 'required|in:cash,kjp,transfer_bank,e_wallet',
            'proof_of_payment' => 'required_if:payment_method,transfer_bank,e_wallet|nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Max 2MB
        ], [
            'proof_of_payment.required_if' => 'Bukti pembayaran wajib di-upload untuk metode ini.'
        ]);

        $paymentMethod = $request->input('payment_method');
        $proofPath = null;

        // 🟢 2. Logika Upload File
        if ($request->hasFile('proof_of_payment')) {
            try {
                $proofPath = $request->file('proof_of_payment')->store('payment_proofs', 'public');
            } catch (\Throwable $e) {
                Log::error('Gagal upload bukti pembayaran: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal mengunggah bukti pembayaran.');
            }
        }

        $total = 0;
        // [PERBAIKAN] Filter cart berdasarkan $selected untuk menghitung total
        $itemsToCheckout = collect($cart)->filter(function($item, $key) use ($selected) {
            return in_array($key, $selected);
        });

        foreach ($itemsToCheckout as $key => $item) {
             $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }

        try {
            \DB::beginTransaction();

            // 🟢 3. Simpan Order
            $order = Order::create([
                'user_id' => auth()->id(),
                'total_price' => $total,
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending', 
                'proof_of_payment' => $proofPath, 
            ]);

            // Loop HANYA pada item yang dipilih
            foreach ($itemsToCheckout as $key => $item) {
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

                    if (!$productSize) {
                        throw new \Exception("Ukuran produk {$item['title']} tidak ditemukan.");
                    }
                    $isPreorder = ($product->is_preorder && $productSize->stock <= 0);

                    if ($productSize->stock < $quantity && !$isPreorder) {
                        throw new \Exception("Stok tidak cukup untuk {$item['title']} ukuran {$item['size']}.");
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
                         throw new \Exception("Stok tidak cukup untuk {$item['title']}.");
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

                // Hapus item dari session cart
                unset($cart[$key]);
            }

            session()->put('cart', $cart); // Simpan sisa keranjang
            \DB::commit();

        } catch (\Throwable $e) {
            \DB::rollBack();

            // 🟢 4. Hapus file jika DB gagal
            if ($proofPath && Storage::disk('public')->exists($proofPath)) {
                Storage::disk('public')->delete($proofPath);
            }

            Log::error('Checkout gagal: ' . $e->getMessage());
            // [PERBAIKAN] Redirect kembali ke halaman checkout
            return redirect()->route('checkout.index', ['selected' => $selected])
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat!');
    }

    /** ========================
     * 🧩 Helper: Tambah Produk ke Keranjang
     * ======================== */
    private function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $size = $request->input('size');
        $cartKey = $id . ($size ? '-' . $size : '');
        $cart = session()->get('cart', []);

        $maxStock = $size
            ? optional($product->sizes()->where('size', $size)->first())->stock ?? 0
            : $product->stock;
        
        $isPreorder = $product->is_preorder;

        // Cek jika bisa preorder atau stok ada
        if ($maxStock <= 0 && !$isPreorder) {
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
            'is_preorder' => $isPreorder,
        ];

        // Jika tidak preorder, batasi dengan stok
        if (!$isPreorder && $maxStock > 0) {
            $cartItem['quantity'] = min(($cartItem['quantity'] ?? 0) + 1, $maxStock);
        } else {
            // Jika preorder (atau stok ada), tambahkan saja
            $cartItem['quantity'] = ($cartItem['quantity'] ?? 0) + 1;
        }

        $cart[$cartKey] = $cartItem;
        session()->put('cart', $cart);

        return $cart;
    }
}
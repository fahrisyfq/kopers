<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\StockMovement; // Pastikan Import ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, $id)
    {
        if (auth()->user()->is_blocked) {
            return redirect()->back()->with('error', 'Akun Anda diblokir sementara. Hubungi admin koperasi.');
        }
        $user = auth()->user();
        if (empty($user->nama_lengkap) || empty($user->nisn) || empty($user->kelas) || empty($user->jurusan) || empty($user->no_telp_siswa) || empty($user->no_telp_ortu)) {
            return redirect()->route('profile.complete')->with('warning', 'Silakan lengkapi profil terlebih dahulu sebelum menambah produk ke keranjang.');
        }
        return $this->addToCart($request, $id) ? redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang.') : redirect()->back()->with('error', 'Gagal menambahkan produk. Stok habis dan tidak bisa pre-order.');
    }

    public function ajaxAdd(Request $request, $id)
    {
        if (auth()->user()->is_blocked) {
            return response()->json(['success' => false, 'message' => 'Akun Anda diblokir. Hubungi admin.']);
        }
        if (!auth()->check()) return response()->json(['success' => false, 'message' => 'Harus login untuk belanja.']);
        $user = auth()->user();
        if (empty($user->nama_lengkap) || empty($user->nisn) || empty($user->kelas) || empty($user->jurusan) || empty($user->no_telp_siswa) || empty($user->no_telp_ortu)) {
            return response()->json(['success' => false, 'message' => 'Lengkapi profil dulu sebelum belanja.', 'redirect' => route('profile.complete')]);
        }
        $cart = $this->addToCart($request, $id);
        if (!$cart) return response()->json(['success' => false, 'message' => 'Stok habis dan produk tidak bisa pre-order.']);
        return response()->json(['success' => true, 'cart' => $cart, 'message' => 'Produk ditambahkan ke keranjang.']);
    }

    public function update(Request $request, $key)
    {
        $cart = session()->get('cart');
        if (!isset($cart[$key])) {
            if ($request->wantsJson()) return response()->json(['error' => 'Item tidak ditemukan.'], 404);
            return redirect()->route('cart.index')->with('error', 'Item tidak ditemukan.');
        }
        $quantity = (int) $request->input('quantity', 1);
        $stock = $cart[$key]['stock'];
        if ($quantity < 1) $quantity = 1;
        if ($quantity > $stock && !$cart[$key]['is_preorder']) {
            if ($request->wantsJson()) return response()->json(['error' => 'Stok tidak mencukupi.'], 422);
            return redirect()->route('cart.index')->with('error', 'Stok tidak mencukupi.');
        }
        $cart[$key]['quantity'] = $quantity;
        session()->put('cart', $cart);
        if ($request->wantsJson()) {
            $newSubtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
            return response()->json([
                'success' => true, 'message' => 'Kuantitas diperbarui!',
                'itemTotalFormatted' => 'Rp ' . number_format($cart[$key]['price'] * $quantity, 0, ',', '.'),
                'subtotalFormatted' => 'Rp ' . number_format($newSubtotal, 0, ',', '.'),
                'totalFormatted' => 'Rp ' . number_format($newSubtotal, 0, ',', '.'),
                'itemCount' => count($cart)
            ]);
        }
        return redirect()->route('cart.index')->with('success', 'Kuantitas diperbarui!');
    }

    public function remove(Request $request, $key)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$key])) { unset($cart[$key]); session()->put('cart', $cart); }
        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Keranjang dikosongkan.');
    }
    
    public function ajaxUpdate(Request $request) { /* Biarkan kode lama */ }
    public function ajaxRemove(Request $request) { /* Biarkan kode lama */ }
    public function ajaxGet() { return response()->json(session()->get('cart', [])); }

    public function checkoutIndex(Request $request)
    {
        $cart = session()->get('cart', []);
        $selectedKeys = $request->input('selected', []);
        if (empty($selectedKeys) || empty($cart)) return redirect()->route('cart.index')->with('error', 'Anda harus memilih minimal satu item untuk checkout.');
        $selectedItems = collect($cart)->filter(fn($item, $key) => in_array($key, $selectedKeys))->all();
        if (empty($selectedItems)) return redirect()->route('cart.index')->with('error', 'Item yang dipilih tidak valid.');
        return view('checkout.index', ['selectedItems' => $selectedItems]);
    }

    /** ============================================================
     * 🔥 LOGIKA CHECKOUT (FIX PRE-ORDER MASUK) 🔥
     * ============================================================ */
    public function checkout(Request $request)
    {
        if (auth()->user()->is_blocked) {
            return redirect()->route('cart.index')->with('error', 'Akun Anda diblokir. Tidak dapat memproses pesanan.');
        }

        $selected = $request->input('selected', []);
        $cart = session()->get('cart', []);

        if (empty($cart)) return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        if (empty($selected)) return redirect()->route('cart.index')->with('error', 'Pilih minimal 1 produk.');

        $request->validate([
            'payment_method' => 'required|in:cash,kjp,transfer_bank,e_wallet',
            'proof_of_payment' => 'required_if:payment_method,transfer_bank,e_wallet|nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], ['proof_of_payment.required_if' => 'Bukti pembayaran wajib di-upload.']);

        $paymentMethod = $request->input('payment_method');
        $proofPath = null;

        if ($request->hasFile('proof_of_payment')) {
            try {
                $proofPath = $request->file('proof_of_payment')->store('payment_proofs', 'public');
            } catch (\Throwable $e) {
                Log::error('Gagal upload: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal upload bukti.');
            }
        }

        $itemsToCheckout = collect($cart)->filter(fn($item, $key) => in_array($key, $selected));
        $total = $itemsToCheckout->sum(fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 1));

        try {
            \DB::beginTransaction();

            // 1. Buat Order
            $order = Order::create([
                'user_id' => auth()->id(),
                'total_price' => $total,
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending', 
                'proof_of_payment' => $proofPath, 
            ]);

            foreach ($itemsToCheckout as $key => $item) {
                $quantity = $item['quantity'] ?? 1;
                $product = Product::lockForUpdate()->find($item['id']);
                if (!$product) continue;

                $productSizeId = null;
                $isPreorder = false;

                // === Logic Produk dengan Ukuran ===
                if (!empty($item['size'])) {
                    $productSize = ProductSize::where('product_id', $product->id)
                        ->where('size', $item['size'])->lockForUpdate()->first();

                    if (!$productSize) throw new \Exception("Ukuran {$item['size']} tidak ditemukan.");

                    // Cek Preorder/Stok
                    $isPreorder = ($product->is_preorder && $productSize->stock <= 0);
                    
                    // Validasi Stok (Hanya cek, jangan potong disini)
                    if ($productSize->stock < $quantity && !$isPreorder) {
                        throw new \Exception("Stok tidak cukup: {$item['title']} ({$item['size']}).");
                    }
                    
                    $productSizeId = $productSize->id;

                    if ($isPreorder) {
                        // [KASUS A] Pre-Order -> Tambah Antrian (Manual di Controller karena Observer skip PO)
                        $product->increment('preorder_quantity', $quantity);

                        // Catat Log PO Masuk
                        StockMovement::create([
                            'product_id'      => $product->id,
                            'product_size_id' => $productSizeId,
                            'movement_type'   => 'in',
                            'quantity'        => $quantity,
                            'note'            => 'Pesanan PO Masuk #' . $order->id,
                            'is_preorder'     => true,
                        ]);
                    } 
                    // ❌ ELSE (Ready Stock) DIHAPUS 
                    // Biarkan Observer yang menangani pemotongan stok & logging saat OrderItem dibuat di bawah.

                } else {
                    // === Logic Produk Tanpa Ukuran ===
                    $isPreorder = ($product->is_preorder && $product->stock <= 0);
                    
                    // Validasi Stok
                    if ($product->stock < $quantity && !$isPreorder) {
                         throw new \Exception("Stok tidak cukup: {$item['title']}.");
                    }
                    
                    if ($isPreorder) {
                        // [KASUS A] Pre-Order Tanpa Size
                        $product->increment('preorder_quantity', $quantity);

                        // Catat Log PO Masuk
                        StockMovement::create([
                            'product_id'      => $product->id,
                            'product_size_id' => null,
                            'movement_type'   => 'in',
                            'quantity'        => $quantity,
                            'note'            => 'Pesanan PO Masuk #' . $order->id,
                            'is_preorder'     => true,
                        ]);
                    } 
                    // ❌ ELSE (Ready Stock) DIHAPUS
                }

                // Simpan Item ke Order
                // 🔥 DISINI MAGIC-NYA: Saat baris ini jalan, OrderItemObserver akan terpanggil otomatis!
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
            if ($proofPath && Storage::disk('public')->exists($proofPath)) Storage::disk('public')->delete($proofPath);
            Log::error('Checkout gagal: ' . $e->getMessage());
            return redirect()->route('checkout.index', ['selected' => $selected])->with('error', 'Error: ' . $e->getMessage());
        }

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat!');
    }

    // Helper addToCart
    private function addToCart(Request $request, $id) {
        $product = Product::findOrFail($id);
        $size = $request->input('size');
        $cartKey = $id . ($size ? '-' . $size : '');
        $cart = session()->get('cart', []);
        $maxStock = $size ? optional($product->sizes()->where('size', $size)->first())->stock ?? 0 : $product->stock;
        $isPreorder = $product->is_preorder;
        if ($maxStock <= 0 && !$isPreorder) return false;
        $cartItem = $cart[$cartKey] ?? [
            'id' => $product->id, 'title' => $product->title, 'price' => $product->price,
            'quantity' => 0, 'stock' => $maxStock, 'category' => $product->category,
            'size' => $size, 'image' => $product->image ?? null, 'old_price' => $product->old_price ?? null,
            'is_preorder' => $isPreorder,
        ];
        if (!$isPreorder && $maxStock > 0) {
            $cartItem['quantity'] = min(($cartItem['quantity'] ?? 0) + 1, $maxStock);
        } else {
            $cartItem['quantity'] = ($cartItem['quantity'] ?? 0) + 1;
        }
        $cart[$cartKey] = $cartItem;
        session()->put('cart', $cart);
        return $cart;
    }
}
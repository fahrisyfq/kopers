<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Koperasi Sekolah')</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="icon" href="{{ asset('images/logo.jpg') }}" type="image/jpeg">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @livewireStyles
    @stack('styles')
    
    <style>
        /* Font Poppins */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');
        
        body, .font-poppins { font-family: 'Poppins', sans-serif; }

        /* Card Border Berputar (untuk Modal) */
        .card {
            --glow-primary: hsla(197, 71%, 80%, 0.6); --glow-secondary: hsla(158, 64%, 80%, 0.6); 
            --card-bg: rgba(255, 255, 255, 0.98); --card-shadow: rgba(59, 130, 246, 0.08); 
            --card-shadow-hover: rgba(59, 130, 246, 0.15); --border-line: rgba(219, 234, 254, 0.6); /* blue-100 */
            position: relative; border-radius: 1rem; /* rounded-2xl */
            overflow: hidden; 
            box-shadow: 0 10px 30px -5px var(--card-shadow); z-index: 1; 
        }
        .card .card__content {
            position: relative; z-index: 2; background-color: var(--card-bg);
            backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px); 
            border-radius: 0.9rem; /* Sedikit lebih kecil dari card */ 
            border: 1px solid var(--border-line); 
        }
        .card::before {
            content: ""; pointer-events: none; position: absolute; z-index: -1; 
            top: 50%; left: 50%; transform: translate(-50%, -50%);
            width: calc(100% + 20px); height: calc(100% + 20px); 
            background-image: conic-gradient(from var(--angle), var(--glow-secondary), var(--primary-glow), var(--glow-secondary));
            filter: blur(16px); opacity: 0.4; animation: rotate 12s linear infinite; 
        }

        @keyframes rotate { to { --angle: 360deg; } }
        @property --angle { syntax: "<angle>"; initial-value: 0deg; inherits: false; }

        /* Custom Scrollbar untuk Modal */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #64748b; }

        /* [x-cloak] utility */
        [x-cloak] { display: none !important; }
    </style>
</head>

<body 
    x-data="{ openPanduan: false, mobileMenuOpen: false }"
    x-cloak
    class="bg-gray-100 text-gray-900 font-poppins antialiased"
>

    {{-- ✅ ALERT HANDLER --}}
    @if(session('success'))
        <div id="success-alert" class="fixed top-6 left-1/2 transform -translate-x-1/2 z-[9999] bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2 transition-opacity duration-500 opacity-100" style="min-width: 260px;">
            <i class="fas fa-check-circle text-xl"></i>
            <span>{{ session('success') }}</span>
        </div>
        <script> setTimeout(() => { const el = document.getElementById('success-alert'); if (el) { el.style.opacity = '0'; setTimeout(() => el.remove(), 600); } }, 2500); </script>
    @endif
    @if(session('error'))
         <div id="error-alert" class="fixed top-6 left-1/2 transform -translate-x-1/2 z-[9999] bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2 transition-opacity duration-500 opacity-100" style="min-width: 260px;">
            <i class="fas fa-exclamation-circle text-xl"></i>
            <span>{{ session('error') }}</span>
        </div>
        <script> setTimeout(() => { const el = document.getElementById('error-alert'); if (el) { el.style.opacity = '0'; setTimeout(() => el.remove(), 600); } }, 3000); </script>
    @endif

    {{-- ✅ Navbar --}}
    @include('component.navbar')

    {{-- ✅ Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- ✅ Footer --}}
    @include('component.footer')

    {{-- ✅ Modal Pembayaran QRIS --}}
    <div id="payment-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
        {{-- ... (Konten Modal QRIS Anda) ... --}}
    </div>
    
    {{-- 
        ============================================================
        ==        MODAL PANDUAN (KODE DIPERBARUI)                 ==
        ============================================================
    --}}
    
    {{-- 1. Overlay (Latar Belakang) --}}
    <div 
        x-show="openPanduan"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-cloak
        @keydown.escape.window="openPanduan = false"
        class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm z-[999] p-4"
        @click.self="openPanduan = false" 
    >
        {{-- 2. Konten Modal --}}
        <div 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="card w-full max-w-2xl max-h-[90vh] flex flex-col"
        >
            <div class="card__content bg-white/95 backdrop-blur-sm rounded-xl shadow-lg flex flex-col flex-1 overflow-hidden">
                <div class="flex-shrink-0 flex items-center justify-between p-5 border-b border-gray-200 bg-gradient-to-r from-blue-50/50 to-emerald-50/50 rounded-t-lg">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-gradient-to-br from-blue-100 to-emerald-100 text-blue-600 rounded-xl shadow-sm">
                            <i class="fas fa-book-open text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-blue-700">Panduan Penggunaan</h2>
                            <p class="text-xs text-gray-500">Panduan lengkap untuk siswa Koperasi SMKN 8</p>
                        </div>
                    </div>
                    <button @click="openPanduan = false" class="text-gray-400 hover:text-red-500 transition-colors rounded-full p-1">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="flex-1 p-6 text-sm text-gray-700 overflow-y-auto custom-scrollbar space-y-6">

                    {{-- Section Langkah-langkah --}}
                    <section>
                        <h3 class="font-bold text-base text-gray-800 mb-4">Cara Memesan</h3>
                        <div class="space-y-4">
                            
                            {{-- Step 1 --}}
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-bold flex items-center justify-center border-2 border-blue-200">1</div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800">Login & Lengkapi Profil</h4>
                                    <p class="text-xs text-gray-600">Login pakai akun Google sekolah. Jika baru, Anda akan diminta melengkapi profil (NISN, Kelas, Jurusan, No. HP). Data ini **wajib** diisi.</p>
                                </div>
                            </div>
                            
                            {{-- Step 2 --}}
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-bold flex items-center justify-center border-2 border-blue-200">2</div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800">Pilih Produk & Ukuran</h4>
                                    <p class="text-xs text-gray-600">Masuk ke **Katalog Produk**. Klik produk untuk melihat detail, lalu pilih **Ukuran** yang sesuai.</p>
                                </div>
                            </div>
                            
                            {{-- Step 3 --}}
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-bold flex items-center justify-center border-2 border-blue-200">3</div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800">Tambah ke Keranjang</h4>
                                    <p class="text-xs text-gray-600">Klik tombol **"Tambah ke Keranjang"**. Buka keranjang Anda (ikon 🛒) untuk memeriksa pesanan.</p>
                                </div>
                            </div>
                            
                            {{-- Step 4 (DIPERBARUI) --}}
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-bold flex items-center justify-center border-2 border-blue-200">4</div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800">Checkout & Pembayaran</h4>
                                    <p class="text-xs text-gray-600">Di halaman **Keranjang**, pastikan pesanan Anda benar, lalu klik **"Lanjut ke Checkout"**. Di halaman Checkout, pilih item dan metode pembayaran (Cash, KJP, **QRIS**, atau **Transfer Bank (BCA)**).</p>
                                </div>
                            </div>
                            
                            {{-- Step 5 --}}
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-bold flex items-center justify-center border-2 border-blue-200">5</div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800">Pantau Pesanan</h4>
                                    <p class="text-xs text-gray-600">Setelah checkout, Anda bisa melihat status pesanan di halaman **"Pesanan Saya"** (ikon <i class="fas fa-box"></i> di navbar).</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Section Catatan Penting --}}
                    <section class="border-t border-gray-200 pt-5 mt-5">
                        <h3 class="font-bold text-base text-gray-800 mb-4">Informasi Penting</h3>
                        
                        <div class="flex items-start gap-3 mb-4">
                             <div class="flex-shrink-0 w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center">
                                 <i class="fas fa-clock"></i>
                             </div>
                             <div class="flex-1">
                                 <h4 class="font-semibold text-gray-800">Tentang Pre-Order</h4>
                                 <p class="text-xs text-gray-600">Jika stok produk habis, pesanan Anda akan otomatis menjadi **Pre-Order**. Pesanan tetap dicatat dan akan diproses saat stok tersedia kembali. Pantau statusnya di halaman "Pesanan Saya".</p>
                             </div>
                        </div>
                        
                         <div class="flex items-start gap-3 mb-4"> {{-- (DIPERBARUI) --}}
                             <div class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                 <i class="fas fa-money-bill-wave"></i>
                             </div>
                             <div class="flex-1">
                                 <h4 class="font-semibold text-gray-800">Tentang Pembayaran</h4>
                                 <p class="text-xs text-gray-600">
                                      <strong>Cash:</strong> Setelah memesan, Anda harus datang ke koperasi untuk membayar dan mengkonfirmasi pesanan agar dapat diproses.
                                      <br>
                                      <strong>KJP:</strong> Admin akan mencatat penggunaan dana KJP Anda. Anda tidak perlu membayar tunai.
                                      <br>
                                      <strong>QRIS:</strong> Bayar langsung dari HP Anda. Scan kode QR yang muncul saat checkout. Pesanan akan otomatis terkonfirmasi setelah pembayaran berhasil.
                                      <br>
                                      {{-- (BARU) Menambahkan info Transfer Bank --}}
                                      <strong>Transfer Bank (BCA):</strong> Anda akan menerima nomor rekening tujuan setelah checkout. Harap transfer sesuai total dan **upload bukti transfer** di halaman "Pesanan Saya" agar pesanan dapat dikonfirmasi oleh admin.
                                 </p>
                             </div>
                         </div>
                         
                         {{-- (BARU) Menambahkan info Pengambilan Barang --}}
                         <div class="flex items-start gap-3">
                             <div class="flex-shrink-0 w-8 h-8 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center">
                                 <i class="fas fa-box-open"></i>
                             </div>
                             <div class="flex-1">
                                 <h4 class="font-semibold text-gray-800">Tentang Pengambilan Barang</h4>
                                 <p class="text-xs text-gray-600">
                                      Setelah pesanan Anda berstatus **"Siap Diambil"** (cek di halaman 'Pesanan Saya'), Anda dapat mengambil barang di koperasi dengan menunjukkan detail pesanan Anda kepada admin.
                                 </p>
                             </div>
                         </div>

                    </section>
                </div>

                <div class="flex-shrink-0 mt-4 p-4 text-center border-t border-gray-200 bg-gray-50/50 rounded-b-lg">
                    <button @click="openPanduan = false"
                            class="bg-blue-600 text-white text-sm font-medium px-6 py-2 rounded-lg hover:bg-blue-700 transition-all duration-200 transform hover:scale-105 shadow-md">
                        Saya Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- ✅ Akhir Modal Panduan --}}


    {{-- ✅ Script Section --}}
    @livewireScripts
    @stack('scripts')
    @vite('resources/js/app.js')

    <script>
        // Validasi bukti transfer
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form[action="{{ route('cart.checkout') }}"]');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const input = form.querySelector('input[name="transfer_proof"]');
                    // (DIPERBARUI) Validasi ini hanya berlaku jika metode transfer dipilih
                    const paymentMethod = form.querySelector('input[name="payment_method"]:checked');
                    if (paymentMethod && paymentMethod.value === 'transfer' && input && (!input.files || input.files.length === 0)) {
                        alert('Silakan upload minimal 1 bukti transfer sebelum konfirmasi pembayaran.');
                        input.focus();
                        e.preventDefault();
                    }
                });
            }
        });

        // Data global cart
        window.cartData = @json(session('cart', []));
        window.isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
    </script>
</body>
</html>
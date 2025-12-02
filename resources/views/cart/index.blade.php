@extends('layout.app')

@section('title', 'Keranjang Belanja')

{{-- SweetAlert2 untuk konfirmasi hapus --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-emerald-50 pt-28 pb-20 font-poppins">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 md:mb-10 pb-5 border-b border-gray-200">
            <div class="flex items-center gap-3 mb-4 sm:mb-0">
                <span class="text-emerald-600 bg-gradient-to-br from-emerald-100 to-teal-100 p-2.5 rounded-xl text-2xl shadow-sm">
                    <i class="fas fa-shopping-cart fa-fw"></i>
                </span>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800 tracking-tight">Keranjang Belanja</h1>
            </div>
             @if(!empty($cart))
                 <form action="{{ route('cart.clear') }}" method="POST" class="delete-item" data-title="Kosongkan Keranjang?" data-text="Semua item akan dihapus dari keranjang.">
                     @csrf
                     @method('DELETE')
                     <button type="submit"
                             class="flex items-center gap-1.5 text-red-600 hover:text-red-800 text-xs font-semibold px-3 py-1.5 rounded-lg border border-red-200 hover:bg-red-50 transition-colors duration-200 self-end sm:self-center shadow-sm hover:shadow">
                         <i class="fas fa-trash-alt text-xs"></i> Kosongkan Keranjang
                     </button>
                 </form>
             @endif
        </div>

        {{-- 🔔 Notifikasi --}}
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Kondisi Keranjang Kosong --}}
        @if(empty($cart))
            <div class="card max-w-lg mx-auto animate-fadeIn mt-10">
                <div class="card__content bg-white/95 backdrop-blur-sm p-8 md:p-12 rounded-xl border border-emerald-100 shadow text-center">
                    <svg class="mx-auto w-32 h-32 md:w-40 md:h-40 text-emerald-300 mb-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                         <defs> <linearGradient id="cartGradient" x1="0%" y1="0%" x2="100%" y2="100%"> <stop offset="0%" style="stop-color: #6ee7b7; stop-opacity: 1" /> <stop offset="100%" style="stop-color: #3b82f6; stop-opacity: 0.8" /> </linearGradient> </defs>
                        <path d="M3 3H5.25L7.05 13.11C7.14387 13.627 7.42512 14.0844 7.83982 14.3918C8.25452 14.6992 8.77017 14.8339 9.28 14.77L17.78 13.77C18.2898 13.7061 18.7755 13.5708 19.1802 13.2634C19.5849 12.956 19.8761 12.4986 19.97 11.98L21.71 4.98C21.8016 4.49247 21.6961 3.98971 21.4111 3.56611C21.126 3.14251 20.6811 2.82773 20.17 2.78L5.25 2.78" stroke="url(#cartGradient)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path d="M9 18C9.55228 18 10 18.4477 10 19C10 19.5523 9.55228 20 9 20C8.44772 20 8 19.5523 8 19C8 18.4477 8.44772 18 9 18Z" fill="url(#cartGradient)"/> <path d="M17 18C17.5523 18 18 18.4477 18 19C18 19.5523 17.5523 20 17 20C16.4477 20 16 19.5523 16 19C16 18.4477 16.4477 18 17 18Z" fill="url(#cartGradient)"/>
                    </svg>
                    <p class="text-gray-700 text-lg md:text-xl font-semibold mb-3"> Wah, keranjang belanjamu kosong! </p>
                    <p class="text-gray-500 text-sm mb-8"> Ayo jelajahi katalog kami dan temukan produk menarik untukmu. </p>
                    <a href="{{ route('product.index') }}" class="cta-button relative inline-flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 text-white font-semibold px-7 py-2.5 rounded-lg shadow-lg hover:shadow-emerald-300/50 transition-all duration-300 transform hover:scale-[1.03] active:scale-[0.98] overflow-hidden">
                        <i class="fas fa-store text-sm"></i> 
                        <span>Jelajahi Produk</span>
                        <span class="shine"></span>
                    </a>
                </div>
            </div>

        {{-- Kondisi Keranjang Ada Isi --}}
        @else
            {{-- Form untuk mengirim item terpilih ke checkout --}}
            <form id="cart-form" action="{{ route('checkout.index') }}" method="GET"> 
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
                    
                    <div class="lg:col-span-8 space-y-4"> 
                        @foreach($cart as $key => $item)
                            <div class="card cart-item" x-data="{ qty: {{ $item['quantity'] }} }"> 
                                <div class="card__content flex gap-3 sm:gap-4 py-4 px-3 sm:p-5 bg-white/95 rounded-xl border border-gray-100 shadow-sm transition-all duration-300 relative">
                                    
                                    {{-- Kolom 1: Checkbox --}}
                                    <div class="flex-shrink-0 pt-1 sm:pt-1.5"> 
                                        <input type="checkbox" name="selected[]" value="{{ $key }}" class="select-item w-5 h-5 accent-emerald-600 cursor-pointer rounded-md shadow-sm border-gray-300">
                                    </div>

                                    {{-- Kolom 2: Gambar --}}
                                    <div class="w-20 h-20 sm:w-24 sm:h-24 flex-shrink-0 overflow-hidden rounded-lg border border-gray-100 bg-gray-50 shadow-inner">
                                        @if(!empty($item['image']))
                                            <img src="{{ asset('storage/'. $item['image']) }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No Img</div>
                                        @endif
                                    </div>

                                    {{-- Kolom 3: Info & Aksi Mobile --}}
                                    <div class="flex-1 min-w-0 flex flex-col justify-between">
                                        {{-- Info Block --}}
                                        <div class="flex-1">
                                            <h2 class="font-semibold text-gray-800 text-base leading-snug tracking-tight line-clamp-2 pr-6 sm:pr-0">
                                                {{ $item['title'] }}
                                            </h2>
                                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-500 mt-1 mb-1 sm:mb-2">
                                                <span class="flex items-center gap-1"><i class="fas fa-tag text-emerald-500 text-[10px]"></i> {{ $item['category'] }}</span>
                                                @if($item['category'] === 'Seragam Sekolah' && !empty($item['size']))
                                                     <span class="flex items-center gap-1"><i class="fas fa-ruler text-emerald-500 text-[10px]"></i> Ukuran: <strong class="text-gray-700">{{ $item['size'] }}</strong></span>
                                                @endif
                                            </div>
                                             <span class="text-emerald-600 font-bold text-sm sm:text-base tracking-tight">
                                                  Rp {{ number_format($item['price'],0,',','.') }}
                                             </span>
                                        </div>
                                        
                                        {{-- Action Block (HANYA TAMPIL DI MOBILE) --}}
                                        <div class="flex sm:hidden items-end justify-between mt-3">
                                            {{-- Kotak Qty Mobile --}}
                                            <div class="py-2 px-3 bg-white border border-gray-200 rounded-lg shadow-sm w-[110px]">
                                                <div class="flex flex-col gap-y-1">
                                                    <label for="quantity-{{$key}}-mobile" class="text-xs text-gray-500">Jumlah</label>
                                                    <div class="flex items-center justify-between gap-x-3">
                                                        <input id="quantity-{{$key}}-mobile" class="w-full p-0 bg-transparent border-0 text-gray-800 text-base font-bold focus:ring-0 [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none dark:text-gray-800" style="-moz-appearance: textfield;" type="number" min="1" max="{{ $item['stock'] }}" :value="qty" readonly>
                                                        <div class="flex items-center gap-x-1.5">
                                                            {{-- [PERBAIKAN] SVG - (Minus) Mobile --}}
                                                            <button type="button" @click="qty = Math.max(1, qty - 1); updateQuantity('{{ $key }}', qty, '{{ $item['price'] }}')" class="size-6 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-full border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" :disabled="qty <= 1">
                                                                <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path d="M5 12h14"></path>
                                                                </svg>
                                                            </button>
                                                            {{-- [PERBAIKAN] SVG + (Plus) Mobile --}}
                                                            <button type="button" @click="qty = Math.min({{ $item['stock'] }}, qty + 1); updateQuantity('{{ $key }}', qty, '{{ $item['price'] }}')" class="size-6 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-full border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" :disabled="qty >= {{ $item['stock'] }}">
                                                                <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path d="M5 12h14"></path>
                                                                    <path d="M12 5v14"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="text-xs text-gray-400 text-center mt-1">
                                                        @if($item['stock'] <= 10 && $item['stock'] > 0) Sisa <span class="font-bold text-red-500">{{ $item['stock'] }}</span>
                                                        @elseif($item['stock'] > 10) Stok: <span class="font-bold text-emerald-600">{{ $item['stock'] }}</span>
                                                        @else <span class="font-bold text-red-600">Stok Habis</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- Tombol Hapus Mobile --}}
                                            <form action="{{ route('cart.remove', $key) }}" method="POST" class="inline delete-item" data-title="Hapus produk ini?">
                                                @csrf
                                                <button type="submit" title="Hapus item" class="text-gray-400 hover:text-red-600 hover:bg-red-50 w-9 h-9 flex items-center justify-center rounded-full transition-colors duration-200">
                                                    <i class="fas fa-trash-alt text-sm"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    {{-- Kolom 4: Desktop Actions (HANYA TAMPIL DI DESKTOP) --}}
                                    <div class="hidden sm:flex flex-col items-end justify-between gap-2 pl-4">
                                        {{-- Tombol Hapus Desktop --}}
                                        <form action="{{ route('cart.remove', $key) }}" method="POST" class="inline delete-item" data-title="Hapus produk ini?">
                                            @csrf
                                            <button type="submit" title="Hapus item" class="text-gray-400 hover:text-red-600 hover:bg-red-50 w-9 h-9 flex items-center justify-center rounded-full transition-colors duration-200">
                                                <i class="fas fa-trash-alt text-sm"></i>
                                            </button>
                                        </form>
                                        {{-- Kotak Qty Desktop --}}
                                        <div class="py-2 px-3 bg-white border border-gray-200 rounded-lg shadow-sm w-[110px]">
                                            <div class="flex flex-col gap-y-1">
                                                <label for="quantity-{{$key}}" class="text-xs text-gray-500">Jumlah</label>
                                                <div class="flex items-center justify-between gap-x-3">
                                                    <input id="quantity-{{$key}}" class="w-full p-0 bg-transparent border-0 text-gray-800 text-base font-bold focus:ring-0 [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none dark:text-gray-800" style="-moz-appearance: textfield;" type="number" min="1" max="{{ $item['stock'] }}" :value="qty" readonly>
                                                    <div class="flex items-center gap-x-1.5">
                                                        {{-- [PERBAIKAN] SVG - (Minus) Desktop --}}
                                                        <button type="button" @click="qty = Math.max(1, qty - 1); updateQuantity('{{ $key }}', qty, '{{ $item['price'] }}')" class="size-6 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-full border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" :disabled="qty <= 1">
                                                            <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M5 12h14"></path>
                                                            </svg>
                                                        </button>
                                                        {{-- [PERBAIKAN] SVG + (Plus) Desktop --}}
                                                        <button type="button" @click="qty = Math.min({{ $item['stock'] }}, qty + 1); updateQuantity('{{ $key }}', qty, '{{ $item['price'] }}')" class="size-6 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-full border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" :disabled="qty >= {{ $item['stock'] }}">
                                                            <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M5 12h14"></path>
                                                                <path d="M12 5v14"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="text-xs text-gray-400 text-center mt-1">
                                                    @if($item['stock'] <= 10 && $item['stock'] > 0) Sisa <span class="font-bold text-red-500">{{ $item['stock'] }}</span>
                                                    @elseif($item['stock'] > 10) Stok: <span class="font-bold text-emerald-600">{{ $item['stock'] }}</span>
                                                    @else <span class="font-bold text-red-600">Stok Habis</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Subtotal per Item Desktop --}}
                                        <div id="item-total-{{ $key }}" class="text-right font-bold text-gray-800 text-base w-32 flex-shrink-0 tabular-nums price-update">
                                            <span>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="lg:col-span-4 lg:sticky lg:top-28"> 
                        <div class="card">
                            <div class="card__content bg-white/95 backdrop-blur-sm rounded-xl border border-emerald-100 shadow-lg p-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-5 pb-4 border-b border-gray-200 flex items-center gap-2">
                                    <i class="fas fa-file-invoice-dollar text-emerald-600"></i>
                                    Ringkasan Pilihan
                                </h3>
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between text-gray-600">
                                        <span>Subtotal (<span id="cart-item-count" class="font-medium">0</span> item)</span>
                                        <span id="cart-subtotal" class="font-medium text-gray-800 tabular-nums">Rp 0</span>
                                    </div>
                                </div>
                                <div class="border-t border-gray-200 my-4"></div>
                                <div class="flex justify-between items-center mb-5">
                                    <span class="text-base font-semibold text-gray-900">Total Pilihan</span>
                                    <span id="cart-total" class="text-2xl font-extrabold text-emerald-700 tabular-nums price-update-total p-1 -m-1">Rp 0</span>
                                </div>
                                
                                {{-- [PENAMBAHAN] Catatan untuk mencentang produk --}}
                                <div class="flex items-start gap-2.5 bg-blue-50 text-blue-700 p-3 rounded-lg text-xs mb-5 border border-blue-200">
                                    <i class="fas fa-info-circle text-blue-500 mt-0.5 flex-shrink-0 fa-fw"></i>
                                    <div>
                                        Silakan centang item yang ingin Anda beli. Total akan dihitung otomatis.
                                    </div>
                                </div>

                                <div class="flex items-center justify-center gap-5 w-full pb-5">
                                    <button type="button" id="select-all" class="flex items-center gap-1.5 text-emerald-600 hover:text-emerald-700 transition text-xs font-medium">
                                        <i class="fas fa-check-square"></i> Centang Semua
                                    </button>
                                    <button type="button" id="select-none" class="flex items-center gap-1.5 text-gray-500 hover:text-gray-700 transition text-xs font-medium">
                                        <i class="fas fa-square"></i> Bersihkan
                                    </button>
                                </div>

                                <button type="submit" 
                                   class="cta-button w-full relative inline-flex items-center justify-center gap-2.5 bg-gradient-to-r from-emerald-600 via-teal-500 to-cyan-500 
                                          text-white font-semibold px-8 py-3 rounded-lg shadow-lg 
                                          hover:shadow-cyan-400/50 transition-all duration-300 
                                          transform hover:scale-[1.03] active:scale-[0.98] overflow-hidden text-base group">
                                    <span class="relative z-10">Lanjut ke Checkout</span>
                                    <i class="fas fa-arrow-right text-sm relative z-10 transform group-hover:translate-x-1 transition-transform duration-300"></i>
                                    <span class="shine"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @endif

    </div> 
</div>

<script>
// --- [BAGIAN 1: KALKULASI TOTAL PILIHAN] ---
function formatRupiah(angka) {
    return 'Rp ' + parseFloat(angka).toLocaleString('id-ID');
}
const checkboxes = document.querySelectorAll('.select-item');
const subtotalEl = document.getElementById('cart-subtotal');
const totalEl = document.getElementById('cart-total');
const itemCountEl = document.getElementById('cart-item-count');
const selectAllBtn = document.getElementById('select-all');
const selectNoneBtn = document.getElementById('select-none');
const basePrices = {};
@if(!empty($cart))
    @foreach($cart as $key => $item)
        basePrices['{{ $key }}'] = {{ $item['price'] }};
    @endforeach
@endif

function updateCartSummary() {
    let total = 0;
    let itemCount = 0;
    
    checkboxes.forEach(cb => {
        if (cb.checked) {
            const key = cb.value;
            // [PERBAIKAN] Ambil qty dari kedua input (mobile & desktop)
            const qtyInput = document.getElementById(`quantity-${key}`) || document.getElementById(`quantity-${key}-mobile`);
            const qty = parseInt(qtyInput.value);
            
            total += (basePrices[key] || 0) * qty;
            itemCount++;
        }
    });
    
    const formattedTotal = formatRupiah(total);
    if (subtotalEl) subtotalEl.textContent = formattedTotal;
    if (totalEl) {
        if (totalEl.textContent !== formattedTotal) {
            totalEl.textContent = formattedTotal;
            // [TAMBAHAN] Animasi flash saat total diupdate
            totalEl.classList.add('price-update');
            setTimeout(() => totalEl.classList.remove('price-update'), 400); // Durasi animasi
        }
    }
    if (itemCountEl) itemCountEl.textContent = itemCount;
}
checkboxes.forEach(cb => cb.addEventListener('change', updateCartSummary));
selectAllBtn?.addEventListener('click', () => { checkboxes.forEach(cb => cb.checked = true); updateCartSummary(); });
selectNoneBtn?.addEventListener('click', () => { checkboxes.forEach(cb => cb.checked = false); updateCartSummary(); });
document.addEventListener('DOMContentLoaded', updateCartSummary);


// --- [BAGIAN 2: AJAX UPDATE KUANTITAS] ---
function debounce(func, delay = 300) {
    let timer;
    return function(...args) {
        clearTimeout(timer);
        timer = setTimeout(() => {
            func.apply(this, args);
        }, delay);
    };
}
const updateQuantity = debounce(async (key, newQty, itemPrice) => {
    
    // [PERBAIKAN] Update total harga per item (desktop)
    const itemTotalEl = document.getElementById(`item-total-${key}`);
    const newItemTotal = itemPrice * newQty;
    if (itemTotalEl) {
        itemTotalEl.innerHTML = `<span>${formatRupiah(newItemTotal)}</span>`;
        // [TAMBAHAN] Animasi flash untuk subtotal item
        itemTotalEl.classList.add('price-update');
        setTimeout(() => itemTotalEl.classList.remove('price-update'), 400);
    }
    
    // Update juga input di Qty box lainnya (desktop/mobile)
    const qtyInputDesktop = document.getElementById(`quantity-${key}`);
    const qtyInputMobile = document.getElementById(`quantity-${key}-mobile`);
    if(qtyInputDesktop) qtyInputDesktop.value = newQty;
    if(qtyInputMobile) qtyInputMobile.value = newQty;
    
    // Panggil fungsi kalkulasi total pilihan
    updateCartSummary();

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url = `{{ url('/cart/update') }}/${key}`; 

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                quantity: newQty,
                _method: 'POST'
            })
        });
        if (!response.ok) {
            throw new Error('Server error');
        }
        const data = await response.json();
        updateCartSummary();

    } catch (error) {
        console.error('Gagal update quantity:', error);
        Swal.fire('Error', 'Gagal mengupdate keranjang. Silakan muat ulang.', 'error');
    }
});

// --- [BAGIAN 3: SWEETALERT MODERN & MINIMALIS] ---
document.addEventListener('DOMContentLoaded', function () {
    
    // Fungsi SweetAlert standar
    const showModernAlert = (config) => {
        Swal.fire({
            title: config.title,
            text: config.text,
            icon: config.icon || 'warning',
            showCancelButton: config.showCancelButton || false,
            confirmButtonText: config.confirmButtonText || 'OK',
            cancelButtonText: config.cancelButtonText || 'Batal',
            reverseButtons: true,
            background: '#ffffff',
            color: '#374151',
            width: '320px', // [PERBAIKAN] Buat lebih kecil
            customClass: {
                popup: 'modern-alert-popup',
                title: 'modern-alert-title',
                htmlContainer: 'modern-alert-html',
                confirmButton: `modern-alert-confirm ${config.confirmClass || 'bg-emerald-600 hover:bg-emerald-700'}`,
                cancelButton: 'modern-alert-cancel',
                icon: 'modern-alert-icon'
            },
            ...config.options // Opsi tambahan
        }).then(config.thenCallback || (() => {})); // [FIX] Tambahkan callback default
    };

    // Logika SweetAlert untuk Hapus Item
    document.querySelectorAll('.delete-item').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const title = form.dataset.title || 'Hapus item ini?';
            const text = form.dataset.text || 'Tindakan ini tidak dapat dibatalkan.';
            const isClearCart = form.dataset.title === 'Kosongkan Keranjang?';
            
            showModernAlert({
                title: title,
                text: text,
                showCancelButton: true,
                confirmButtonText: isClearCart ? 'Ya, Kosongkan' : 'Ya, hapus',
                confirmClass: 'bg-red-600 hover:bg-red-700', // [PERBAIKAN] Warna merah
                thenCallback: (result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                }
            });
        });
    });
    
    // Validasi item terpilih SEBELUM submit ke checkout
    const checkoutForm = document.getElementById('cart-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            const selectedItems = document.querySelectorAll('.select-item:checked');
            if (selectedItems.length === 0) {
                e.preventDefault(); // Hentikan pengiriman form
                showModernAlert({
                    title: 'Oops... Belum Ada Item!',
                    text: 'Anda harus memilih (mencentang) minimal satu item untuk di-checkout.',
                    icon: 'warning',
                    confirmButtonText: 'OK, Saya Mengerti'
                });
            }
        });
    }
});
</script>

<style>
/* Font Poppins */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');
.font-poppins { font-family: 'Poppins', sans-serif; }

/* Card Border Berputar (Emerald/Teal Theme) */
.card {
    --glow-primary: hsla(158, 64%, 80%, 0.6); --glow-secondary: hsla(170, 55%, 80%, 0.6); 
    --card-bg: rgba(255, 255, 255, 0.98); --card-shadow: rgba(20, 184, 166, 0.06); 
    --card-shadow-hover: rgba(20, 184, 166, 0.12); --border-line: rgba(204, 251, 241, 0.6); 
    position: relative; border-radius: 0.75rem; overflow: hidden; 
    box-shadow: 0 4px 15px -5px var(--card-shadow); z-index: 1; 
}
.card .card__content {
    position: relative; z-index: 2; background-color: var(--card-bg);
    backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); 
    border-radius: 0.65rem; border: 1px solid var(--border-line); 
    transition: transform 0.3s ease-out, box-shadow 0.3s ease-out; 
}
.card::before {
    content: ""; pointer-events: none; position: absolute; z-index: -1; 
    top: 50%; left: 50%; transform: translate(-50%, -50%);
    width: calc(100% + 20px); height: calc(100% + 20px); 
    background-image: conic-gradient(from var(--angle), var(--glow-secondary), var(--primary-glow), var(--glow-secondary));
    filter: blur(16px); opacity: 0.35; animation: rotate 12s linear infinite; 
    transition: opacity 0.3s ease-out, filter 0.3s ease-out; 
}
.card:hover .card__content { 
    transform: scale(1.01); 
    box-shadow: 0 10px 25px -8px var(--card-shadow-hover); 
}
.card:hover::before { opacity: 0.45; filter: blur(18px); }

@keyframes rotate { to { --angle: 360deg; } }
@property --angle { syntax: "<angle>"; initial-value: 0deg; inherits: false; }


/* Tombol Aksi (Checkout, Jelajahi) */
.cta-button {
    background-size: 200% auto; 
    transition: all 0.4s cubic-bezier(.4,0,.2,1); 
}
.cta-button:hover {
    background-position: right center; 
    box-shadow: 0 7px 20px -4px rgba(20, 184, 166, 0.45); 
    transform: scale(1.03) translateY(-2px); 
}
.cta-button .shine {
    position: absolute; top: -50%; left: -150%; width: 25px; height: 200%;
    background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.4) 50%, rgba(255,255,255,0) 100%);
    transform: rotate(35deg); transition: left 0.7s cubic-bezier(0.23, 1, 0.32, 1); pointer-events: none;
}
.cta-button:hover .shine { left: 150%; }

/* Animasi Halaman */
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.animate-fadeIn { animation: fadeIn 0.8s ease-out both; }

/* Line Clamp & Tabular Nums */
.line-clamp-2 {
    overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    min-height: 2.25rem;
}
.tabular-nums {
    font-variant-numeric: tabular-nums;
}

/* Menghilangkan panah di input number */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type="number"] {
    -moz-appearance: textfield;
}

/* [PERBAIKAN] Style SweetAlert Modern & Minimalis */
.modern-alert-popup {
    border-radius: 0.75rem !important; /* rounded-xl */
    box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1) !important; /* shadow-2xl */
    padding: 1.25rem !important; /* p-5 */
    border: 1px solid #f3f4f6; /* border-gray-100 */
}
.modern-alert-title {
    font-size: 1.125rem !important; /* text-lg */
    font-weight: 700 !important; /* font-bold */
    color: #1f2937 !important; /* text-gray-800 */
    padding: 0 !important;
    margin: 0 !important;
}
.modern-alert-html {
    font-size: 0.875rem !important; /* text-sm */
    color: #4b5563 !important; /* text-gray-600 */
    margin-top: 0.5rem !important; /* mt-2 */
    margin-bottom: 1rem !important; /* mb-4 */
    padding: 0 !important;
}
.modern-alert-icon {
    /* [PERBAIKAN] Kecilkan ikonnya */
    width: 3rem !important; /* w-12 */
    height: 3rem !important; /* h-12 */
    margin: 0 auto 1rem !important; /* mx-auto mb-4 */
    border-width: 3px !important;
}
.modern-alert-icon.swal2-warning {
    color: #f59e0b !important; /* text-amber-500 */
    border-color: #fef3c7 !important; /* border-amber-100 */
}
.modern-alert-icon.swal2-error {
    color: #ef4444 !important; /* text-red-500 */
    border-color: #fee2e2 !important; /* border-red-100 */
}
.modern-alert-confirm,
.modern-alert-cancel {
    font-size: 0.875rem !important; /* text-sm */
    font-weight: 600 !important; /* font-semibold */
    border-radius: 0.5rem !important; /* rounded-lg */
    padding: 0.5rem 1rem !important; /* py-2 px-4 */
    transition: all 0.2s ease !important;
    border: none !important;
    box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1) !important; /* shadow-sm */
}
.modern-alert-confirm {
    color: white !important;
}
.modern-alert-cancel {
    background-color: #f3f4f6 !important; /* bg-gray-100 */
    color: #374151 !important; /* text-gray-700 */
}
.modern-alert-cancel:hover {
    background-color: #e5e7eb !important; /* hover:bg-gray-200 */
}
.swal2-actions {
    margin-top: 1rem !important; /* mt-4 */
    gap: 0.75rem !important; /* gap-3 */
}

/* [TAMBAHAN] Animasi flash untuk total */
@keyframes flash-emerald {
    0% { background-color: transparent; }
    50% { background-color: #ecfdf5; transform: scale(1.05); } /* bg-emerald-50 */
    100% { background-color: transparent; }
}
.price-update {
    animation: flash-emerald 0.4s ease-out;
    border-radius: 0.5rem; /* rounded-lg */
}
.price-update-total {
    transition: background-color 0.1s ease-out; /* Transisi halus untuk 'kembali' */
}
</style>
@endsection
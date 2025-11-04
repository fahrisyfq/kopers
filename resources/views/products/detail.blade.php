<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
{{-- Pastikan Alpine.js di-load SEBELUM script Add to Cart jika ada interaksi --}}
<script src="//unpkg.com/alpinejs" defer></script> 
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@extends('layout.app')

@section('title', $product->title)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-emerald-50 font-poppins">
    {{-- Container dengan padding atas untuk navbar --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-16">

        <nav class="flex items-center gap-1.5 text-sm text-slate-500 mb-6"> 
            <a href="{{ route('product.index') }}" class="hover:text-blue-600 transition duration-200">Katalog</a>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-slate-700 font-medium line-clamp-1" title="{{ $product->title }}">{{ $product->title }}</span>
        </nav>

        {{-- Card Container --}}
        <div class="card max-w-4xl mx-auto">
            <div class="card__content bg-white/95 backdrop-blur-md rounded-xl shadow-lg border border-blue-100 overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-5 md:p-7">

                    <div class="w-full space-y-3">
                        {{-- Gambar Utama Swiper --}}
                        <div style="--swiper-navigation-color: #334155; --swiper-pagination-color: #3b82f6" 
                             class="swiper mainImageSwiper relative rounded-lg overflow-hidden border border-gray-200 aspect-square bg-gray-100">
                            <div class="swiper-wrapper">
                                @if($product->image)
                                    <div class="swiper-slide"><img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" class="w-full h-full object-cover"></div>
                                @endif
                                @foreach($product->images as $img)
                                    <div class="swiper-slide"><img src="{{ $img->url }}" alt="{{ $product->title }} - Gambar Tambahan {{ $loop->iteration }}" class="w-full h-full object-cover"></div>
                                @endforeach
                                @if(!$product->image && $product->images->isEmpty())
                                     <div class="swiper-slide flex items-center justify-center bg-gray-200">
                                         <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="swiper-button-next main-swiper-button"></div>
                            <div class="swiper-button-prev main-swiper-button"></div>
                            <div class="absolute top-2.5 left-2.5 z-10">
                                <span class="badge bg-blue-600 text-white">{{ $product->category }}</span>
                            </div>
                        </div>

                        {{-- Thumbnails Swiper --}}
                        <div thumbsSlider="" class="swiper thumbsSwiper rounded">
                            <div class="swiper-wrapper">
                                 @if($product->image)
                                    <div class="swiper-slide thumb-slide"><img src="{{ asset('storage/' . $product->image) }}" alt="Thumbnail {{ $product->title }}" /></div>
                                @endif
                                @foreach($product->images as $img)
                                    <div class="swiper-slide thumb-slide"><img src="{{ $img->url }}" alt="Thumbnail {{ $product->title }} - Gambar Tambahan {{ $loop->iteration }}" /></div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col space-y-4">
                        
                        <div>
                            <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 leading-tight mb-1.5">{{ $product->title }}</h1>
                        </div>

                        <div class="bg-gradient-to-r from-blue-50 to-emerald-50 rounded-lg p-3 border border-blue-100 shadow-sm">
                            <p class="text-xs text-blue-800 mb-0.5 font-medium uppercase tracking-wide">Harga</p>
                            <span class="text-3xl font-extrabold text-blue-600 tracking-tight">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                        </div>

                        <div>
                             <h3 class="text-sm font-semibold text-slate-700 mb-1.5">Deskripsi</h3>
                             <div class="text-gray-600 text-sm leading-relaxed prose prose-sm max-w-none">
                                 {!! nl2br(e($product->description)) !!} 
                             </div>
                        </div>

                        {{-- Pilihan Size + Stok + Tombol --}}
                        @if($product->category === 'Seragam Sekolah' && $product->sizes->count())
                            <div 
                                x-data="{ 
                                    selectedSize: '', selectedStock: 0,
                                    preorder: {{ $product->is_preorder ? 'true' : 'false' }},
                                    sizes: {{ $product->sizes->map(fn($s) => ['size' => $s->size, 'stock' => $s->stock])->values() }},
                                    selectSize(sizeData) { this.selectedSize = sizeData.size; this.selectedStock = sizeData.stock; },
                                    init() {
                                        const available = this.sizes.find(s => s.stock > 0);
                                        if (available) { this.selectSize(available); } 
                                        else if (this.sizes.length > 0) { this.selectSize(this.sizes[0]); }
                                    }
                                }"
                                class="pt-4 border-t border-gray-200"
                            >
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Ukuran:</label>
                                <div class="grid grid-cols-5 gap-2 mb-3">
                                    <template x-for="s in sizes" :key="s.size">
                                        <button type="button"
                                            @click="selectSize(s)"
                                            :disabled="s.stock <= 0 && !preorder"
                                            :class="{
                                                'ring-2 ring-blue-500 ring-offset-1 bg-blue-600 text-white shadow-md scale-105': selectedSize === s.size,
                                                'bg-white border-gray-300 text-gray-700 hover:border-blue-500 hover:bg-blue-50': selectedSize !== s.size && s.stock > 0,
                                                'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed line-through relative overflow-hidden': s.stock <= 0 && !preorder
                                            }"
                                            class="rounded-md border text-sm font-medium transition-all duration-200 flex items-center justify-center py-2.5"
                                        >
                                            <span x-text="s.size"></span>
                                            <template x-if="s.stock <= 0 && !preorder">
                                                 <span class="absolute inset-0 bg-gradient-to-br from-transparent via-gray-300/30 to-transparent opacity-70"></span>
                                            </template>
                                        </button>
                                    </template>
                                </div>

                                <div class="flex items-center gap-2 mb-4 p-2 bg-gray-100 rounded-md border border-gray-200 text-xs">
                                     <i class="fas fa-info-circle text-blue-500"></i>
                                     <span class="text-slate-700">
                                         Stok ukuran <strong x-text="selectedSize"></strong>: 
                                         <span class="font-bold" x-text="selectedStock > 0 ? selectedStock : (preorder ? 'Pre-Order' : 'Habis')"></span>
                                     </span>
                                </div>

                                <div class="mt-auto"> 
                                    <template x-if="selectedStock > 0">
                                         @guest
                                             <a href="{{ route('login') }}" class="button-action button-yellow">
                                                 <i class="fas fa-sign-in-alt"></i> Login untuk Membeli
                                             </a>
                                         @else
                                             @if(/* Cek profil lengkap - GANTI DENGAN KONDISI ASLI ANDA */ empty(auth()->user()->nama_lengkap) || empty(auth()->user()->nisn) || empty(auth()->user()->kelas) || empty(auth()->user()->jurusan) || empty(auth()->user()->no_telp_siswa) || empty(auth()->user()->no_telp_ortu) )
                                                 <a href="{{ route('profile.complete') }}" class="button-action button-orange">
                                                    <i class="fas fa-user-edit"></i> Lengkapi Profil Dulu
                                                 </a>
                                             @else
                                                 {{-- Tombol Add to Cart --}}
                                                 <button type="button" class="add-to-cart button-action button-primary"
                                                         data-id="{{ $product->id }}" x-bind:data-size="selectedSize">
                                                     <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                                                 </button>
                                             @endif
                                         @endguest
                                    </template>
                                    
                                    <template x-if="selectedStock <= 0 && preorder">
                                         @guest
                                             <a href="{{ route('login') }}" class="button-action button-yellow">
                                                 <i class="fas fa-sign-in-alt"></i> Login untuk Pre-Order
                                             </a>
                                         @else
                                              @if(/* Cek profil lengkap - GANTI DENGAN KONDISI ASLI ANDA */ empty(auth()->user()->nama_lengkap) || empty(auth()->user()->nisn) || empty(auth()->user()->kelas) || empty(auth()->user()->jurusan) || empty(auth()->user()->no_telp_siswa) || empty(auth()->user()->no_telp_ortu) )
                                                 <a href="{{ route('profile.complete') }}" class="button-action button-orange">
                                                    <i class="fas fa-user-edit"></i> Lengkapi Profil Dulu
                                                 </a>
                                             @else
                                                 {{-- Tombol Pre-Order --}}
                                                  <button type="button" class="add-to-cart button-action button-preorder" 
                                                          data-id="{{ $product->id }}" x-bind:data-size="selectedSize">
                                                      <i class="fas fa-clock"></i> Pre-Order Sekarang
                                                  </button>
                                              @endif
                                         @endguest
                                    </template>

                                    <template x-if="selectedStock <= 0 && !preorder">
                                         <button type="button" disabled class="button-action button-disabled">
                                             <i class="fas fa-ban"></i> Stok Habis
                                         </button>
                                    </template>
                                </div>
                            </div>
                        
                        {{-- Jika Bukan Seragam / Tidak Ada Size --}}
                        @else 
                            <div 
                                x-data="{ stock: {{ $product->stock ?? 0 }}, preorder: {{ $product->is_preorder ? 'true' : 'false' }} }"
                                class="pt-4 border-t border-gray-200"
                            >
                                <div class="flex items-center gap-2 mb-4 p-2 bg-gray-100 rounded-md border border-gray-200 text-xs">
                                     <i class="fas fa-info-circle text-blue-500"></i>
                                     <span class="text-slate-700">
                                         Stok: 
                                         <span class="font-bold" x-text="stock > 0 ? stock : (preorder ? 'Pre-Order' : 'Habis')"></span>
                                     </span>
                                </div>
                                <div class="mt-auto">
                                    <template x-if="stock > 0">
                                         @guest
                                             <a href="{{ route('login') }}" class="button-action button-yellow">
                                                 <i class="fas fa-sign-in-alt"></i> Login untuk Membeli
                                             </a>
                                         @else
                                             @if(/* Cek profil lengkap - GANTI DENGAN KONDISI ASLI ANDA */ empty(auth()->user()->nama_lengkap) || empty(auth()->user()->nisn) || empty(auth()->user()->kelas) || empty(auth()->user()->jurusan) || empty(auth()->user()->no_telp_siswa) || empty(auth()->user()->no_telp_ortu) )
                                                  <a href="{{ route('profile.complete') }}" class="button-action button-orange">
                                                    <i class="fas fa-user-edit"></i> Lengkapi Profil Dulu
                                                 </a>
                                             @else
                                                 {{-- Tombol Add to Cart (tanpa size) --}}
                                                 <button type="button" class="add-to-cart button-action button-primary" data-id="{{ $product->id }}">
                                                     <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                                                 </button>
                                             @endif
                                         @endguest
                                    </template>
                                    <template x-if="stock <= 0 && preorder">
                                          @guest
                                             <a href="{{ route('login') }}" class="button-action button-yellow">
                                                 <i class="fas fa-sign-in-alt"></i> Login untuk Pre-Order
                                             </a>
                                         @else
                                              @if(/* Cek profil lengkap - GANTI DENGAN KONDISI ASLI ANDA */ empty(auth()->user()->nama_lengkap) || empty(auth()->user()->nisn) || empty(auth()->user()->kelas) || empty(auth()->user()->jurusan) || empty(auth()->user()->no_telp_siswa) || empty(auth()->user()->no_telp_ortu) )
                                                  <a href="{{ route('profile.complete') }}" class="button-action button-orange">
                                                    <i class="fas fa-user-edit"></i> Lengkapi Profil Dulu
                                                 </a>
                                             @else
                                                  {{-- Tombol Pre-Order (tanpa size) --}}
                                                  <button type="button" class="add-to-cart button-action button-preorder" data-id="{{ $product->id }}">
                                                      <i class="fas fa-clock"></i> Pre-Order Sekarang
                                                  </button>
                                              @endif
                                         @endguest
                                    </template>
                                     <template x-if="stock <= 0 && !preorder">
                                         <button type="button" disabled class="button-action button-disabled">
                                             <i class="fas fa-ban"></i> Stok Habis
                                         </button>
                                    </template>
                                </div>
                            </div>
                        @endif
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi Swiper Thumbnail
    var thumbsSwiper = new Swiper(".thumbsSwiper", {
        spaceBetween: 8, slidesPerView: 'auto', freeMode: true,
        watchSlidesProgress: true, centerInsufficientSlides: true, 
        breakpoints: { 320: { slidesPerView: 3.5, spaceBetween: 6 }, 480: { slidesPerView: 4.5, spaceBetween: 8 }, 640: { slidesPerView: 5.5, spaceBetween: 8 }, }
    });
    
    // Inisialisasi Swiper Utama
    var mainSwiper = new Swiper(".mainImageSwiper", {
        loop: false, spaceBetween: 10,
        navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
        thumbs: { swiper: thumbsSwiper },
        on: {
            init: function () {
                const totalSlides = this.slides.length;
                const navNext = this.navigation.nextEl;
                const navPrev = this.navigation.prevEl;
                const thumbContainer = document.querySelector('.thumbsSwiper');
                if (totalSlides <= 1) {
                    if(navNext) navNext.style.display = 'none';
                    if(navPrev) navPrev.style.display = 'none';
                    if(thumbContainer) thumbContainer.style.display = 'none';
                } else {
                    this.params.loop = true; this.loopCreate();
                    if(navNext) navNext.style.display = 'flex'; 
                    if(navPrev) navPrev.style.display = 'flex';
                }
                this.update(); 
            }
        }
    });

    // SweetAlert Toast
    const Toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true,
        background: 'rgba(255, 255, 255, 0.95)', color: '#1f2937', iconColor: '#10b981', 
        customClass: { popup: 'rounded-lg shadow-lg backdrop-blur-sm border border-gray-100 !p-3', title: 'text-sm font-semibold', timerProgressBar: 'bg-gradient-to-r from-emerald-400 to-cyan-400' },
        didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
    });

    // === Add to Cart Logic ===
    // Pastikan meta tag csrf-token ada di <head> layout Anda!
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content"); 

    document.addEventListener('click', async e => {
        const addToCartButton = e.target.closest('.add-to-cart');
        if (addToCartButton && csrfToken) { // Hanya jalan jika tombol ditekan & token ada
            const btn = addToCartButton;
            const id = btn.dataset.id;
            const size = btn.dataset.size ?? ''; 

            btn.disabled = true; 
            btn.innerHTML = `<i class="fas fa-spinner fa-spin mr-1.5"></i> Proses...`; 

            try {
                // Ganti URL jika perlu
                const response = await fetch(`/cart/ajax-add/${id}`, { 
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                        "X-CSRF-TOKEN": csrfToken, // Gunakan variabel token
                        "Accept": "application/json",
                    },
                    body: new URLSearchParams({ size }) // Kirim size
                });

                // Cek jika response bukan JSON (misal error server)
                if (!response.ok) {
                     throw new Error(`Server error: ${response.status} ${response.statusText}`);
                }
                
                const data = await response.json(); // Harus mengembalikan JSON

                if (data.success) {
                    Toast.fire({ icon: 'success', title: data.message || 'Produk ditambahkan!' });
                    
                    // Update Cart Count (opsional, jika Anda punya endpoint ini)
                    try {
                        // Ganti URL jika perlu
                        const countResponse = await fetch(`/cart/count`); 
                        if (countResponse.ok) {
                            const countData = await countResponse.json();
                            const badge = document.querySelector('#cart-count'); 
                            const badgeMobile = document.querySelector('#cart-count-mobile'); 
                            if (badge) { 
                                badge.textContent = countData.count; 
                                badge.classList.toggle('hidden', countData.count == 0); 
                            }
                            if (badgeMobile) { 
                                badgeMobile.textContent = countData.count; 
                                badgeMobile.classList.toggle('hidden', countData.count == 0); 
                            }
                        }
                    } catch (countError) {
                        console.error('Failed to update cart count:', countError);
                    }

                } else {
                    // Tampilkan pesan error dari backend jika ada
                    Swal.fire({
                        icon: 'error', title: 'Gagal', text: data.message || 'Stok tidak cukup atau terjadi kesalahan.',
                        confirmButtonText: 'OK', confirmButtonColor: '#dc2626', background: 'rgba(255,255,255,0.95)', color: '#1f2937',
                        customClass: { popup: 'rounded-xl shadow-lg' },
                    });
                }
            } catch (error) {
                // Tangani error fetch (network error) atau error JSON parsing
                console.error('Add to cart error:', error);
                Swal.fire({
                    icon: 'error', title: 'Oops...', text: 'Tidak dapat menambahkan produk. Periksa koneksi Anda atau coba lagi nanti.',
                    confirmButtonColor: '#f59e0b', background: 'rgba(255,255,255,0.95)', color: '#1f2937',
                    customClass: { popup: 'rounded-xl shadow-lg' },
                });
            } finally {
                // Kembalikan tombol ke state semula
                setTimeout(() => {
                    btn.disabled = false;
                    if (btn.classList.contains('button-preorder')) {
                        btn.innerHTML = `<i class="fas fa-clock"></i> Pre-Order Sekarang`;
                    } else {
                        btn.innerHTML = `<i class="fas fa-cart-plus"></i> Tambah ke Keranjang`;
                    }
                }, 800);
            }
        } else if (addToCartButton && !csrfToken) {
             console.error('CSRF token meta tag not found!');
             Swal.fire({ icon: 'error', title: 'Kesalahan Konfigurasi', text: 'Tidak dapat memproses permintaan.', });
        }
    });
});
</script>

<style>
/* Font Poppins */
.font-poppins { font-family: 'Poppins', sans-serif; }

/* Card Border Berputar (Lebih Minimalis) */
.card {
    --glow-primary: hsla(197, 71%, 80%, 0.5); --glow-secondary: hsla(158, 64%, 80%, 0.5); 
    --card-bg: rgba(255, 255, 255, 0.98); --card-shadow: rgba(59, 130, 246, 0.05); 
    --border-line: rgba(226, 232, 240, 0.7); 
    position: relative; border-radius: 1rem; overflow: hidden; 
    box-shadow: 0 4px 15px -5px var(--card-shadow); z-index: 1; 
}
.card .card__content {
    position: relative; z-index: 2; background-color: var(--card-bg);
    backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); 
    border-radius: 0.9rem; border: 1px solid var(--border-line); 
}
.card::before {
    content: ""; pointer-events: none; position: absolute; z-index: -1; 
    top: 50%; left: 50%; transform: translate(-50%, -50%);
    width: calc(100% + 20px); height: calc(100% + 20px); 
    background-image: conic-gradient(from var(--angle), var(--glow-secondary), var(--primary-glow), var(--glow-secondary));
    filter: blur(18px); opacity: 0.3; animation: rotate 12s linear infinite; 
}
@keyframes rotate { to { --angle: 360deg; } }
@property --angle { syntax: "<angle>"; initial-value: 0deg; inherits: false; }

/* Swiper Styling */
.mainImageSwiper .main-swiper-button { 
    color: #334155 !important; background-color: rgba(255, 255, 255, 0.6); backdrop-filter: blur(2px);
    width: 32px !important; height: 32px !important; border-radius: 50%; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transition: all 0.25s ease-out; opacity: 0.8;
}
.mainImageSwiper .main-swiper-button:hover { background-color: rgba(255, 255, 255, 0.9); transform: scale(1.1); opacity: 1; }
.mainImageSwiper .main-swiper-button::after { font-size: 12px !important; font-weight: 700; }

.thumbsSwiper { height: 70px; box-sizing: border-box; padding: 2px 0 !important; } 
.thumbsSwiper .thumb-slide {
    height: 100%; opacity: 0.5; cursor: pointer; border-radius: 6px; overflow: hidden; 
    border: 3px solid transparent; transition: opacity 0.3s ease, border-color 0.3s ease, transform 0.3s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.thumbsSwiper .thumb-slide img { width: 100%; height: 100%; object-fit: cover; }
.thumbsSwiper .swiper-slide-thumb-active { opacity: 1; border-color: #3b82f6; transform: scale(1.05); }

/* Badge Produk */
.badge {
    padding: 3px 8px; font-size: 9px; font-weight: 700; border-radius: 6px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1); letter-spacing: 0.5px; text-transform: uppercase;
    background-color: rgba(0,0,0,0.4); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,0.1); color: white; 
}
.badge.bg-blue-600 { background-color: rgba(37, 99, 235, 0.85); } 
.badge.bg-green-500 { background-color: rgba(34, 197, 94, 0.85); } 
.badge.bg-red-500 { background-color: rgba(239, 68, 68, 0.85); } 
.badge.bg-orange-500 { background-color: rgba(249, 115, 22, 0.85); } 

/* Tombol Aksi */
.button-action {
    display: flex; align-items: center; justify-content: center; gap: 0.6rem; 
    width: 100%; padding: 0.75rem 0; border-radius: 0.5rem; 
    font-size: 0.875rem; font-semibold; 
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.1); 
    transition: all 0.3s ease-out; background-size: 200% auto; 
}
.button-action:not(:disabled):hover { transform: scale(1.03) translateY(-1px); background-position: right center; }
/* Warna Tombol */
.button-primary { background-image: linear-gradient(to right, #10b981, #2dd4bf, #67e8f9); color: white; }
.button-primary:hover { box-shadow: 0 7px 15px -3px rgba(20, 184, 166, 0.4); } 
.button-preorder { background-image: linear-gradient(to right, #22c55e, #16a34a, #15803d); color: white; }
.button-preorder:hover { box-shadow: 0 7px 15px -3px rgba(22, 163, 74, 0.4); } 
.button-yellow { background-image: linear-gradient(to right, #f59e0b, #facc15, #fde047); color: #422006; }
.button-yellow:hover { box-shadow: 0 7px 15px -3px rgba(245, 158, 11, 0.4); } 
.button-orange { background-image: linear-gradient(to right, #f97316, #ea580c, #c2410c); color: white; }
.button-orange:hover { box-shadow: 0 7px 15px -3px rgba(249, 115, 22, 0.4); } 
.button-disabled { background-color: #9ca3af; color: #e5e7eb; cursor: not-allowed; box-shadow: none; transform: none; }

/* SweetAlert Customization */
.swal2-popup.swal2-toast { font-size: 0.8rem !important; padding: 0.7rem !important; }
.swal2-title { font-size: 0.9rem !important; }
@media (max-width: 640px) {
    .swal2-popup:not(.swal2-toast) { width: 90% !important; font-size: 14px !important; padding: 1rem !important; }
    .swal2-title:not(.swal2-toast *) { font-size: 16px !important; }
    .swal2-html-container { font-size: 13px !important; }
}

/* Animasi Halaman */
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeInSlow { from { opacity: 0; } to { opacity: 1; } }
@keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
.animate-fadeIn { animation: fadeIn 0.8s ease-out both; }
.animate-fadeInSlow { animation: fadeInSlow 1.2s ease-out both; }
.animate-slideUp { animation: slideUp 0.9s ease-out both; }

/* Animasi Blob */
@keyframes spin-slow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.animate-spin-slow { animation: spin-slow 25s linear infinite; }
</style>
@endsection
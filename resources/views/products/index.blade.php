@extends('layout.app')

@section('title', 'Katalog Produk')

@section('content')
<div class="pt-28 pb-20 bg-gradient-to-br from-blue-50 via-white to-emerald-50 min-h-screen font-poppins">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">

        {{-- 🛍️ Header Katalog Produk --}}
        <div class="flex flex-col sm:flex-row items-center justify-between mb-10 border-b border-gray-200 pb-5">
            <div class="flex items-center gap-3 mb-4 sm:mb-0">
                <div class="p-3 rounded-xl bg-gradient-to-br from-blue-100 to-emerald-100 text-blue-600 shadow-sm">
                    <i class="fas fa-store text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight 
                               text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-emerald-500 to-teal-500">
                        Katalog Produk
                    </h1>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Temukan pilihan seragam & perlengkapan sekolah terbaik ✨
                    </p>
                </div>
            </div>
            <button id="sizeGuideBtn" 
                    class="relative flex items-center gap-2 px-5 py-2.5 rounded-lg 
                           bg-gradient-to-r from-blue-600 to-cyan-500 
                           text-white text-sm font-semibold shadow-md 
                           hover:from-blue-500 hover:to-cyan-400 hover:shadow-lg 
                           transition-all duration-300 ease-in-out group focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                 <span class="button-glow"></span>
                <span class="relative z-10 flex items-center gap-2">
                    <i class="fas fa-ruler-combined text-sm group-hover:rotate-6 transition-transform duration-300"></i>
                    Panduan Ukuran
                </span>
            </button>
        </div>

        {{-- Modal Panduan Ukuran --}}
        <div id="sizeGuideModal" 
             class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-[999] p-4 opacity-0 pointer-events-none transition-opacity duration-300 ease-out">
            <div class="relative max-w-3xl w-full transform scale-95 transition-transform duration-300 ease-out">
                 <button id="closeModalBtn" 
                         class="absolute -top-4 -right-4 text-white bg-slate-700 hover:bg-slate-600 rounded-full w-9 h-9 flex items-center justify-center transition-all duration-300 z-10 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                 </button>
                <div class="bg-white rounded-lg shadow-2xl overflow-hidden">
                    <img id="modalImage"
                        src="{{ asset('images/size.jpg') }}"
                        alt="Panduan Ukuran"
                        class="w-full h-auto max-h-[85vh] object-contain" />
                </div>
            </div>
        </div>


        {{-- Kategori: Seragam Sekolah --}}
        @php $seragamProducts = $products->filter(fn($p) => $p->category === 'Seragam Sekolah'); @endphp
        @if($seragamProducts->count() > 0)
        <div class="mb-12">
            <div class="flex items-center gap-4 mb-6 p-4 rounded-lg bg-gradient-to-r from-blue-100 via-white to-blue-50 border border-blue-100 shadow-sm">
                <div class="p-3 rounded-lg bg-blue-600 text-white shadow">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-blue-800">Seragam Sekolah</h2>
                    <p class="text-sm text-blue-600">{{ $seragamProducts->count() }} Produk Tersedia</p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 md:gap-6">
                @foreach($seragamProducts as $product)
                    @php $totalStock = $product->sizes->sum('stock'); @endphp
                    <a href="{{ route('product.show', $product->id) }}" class="group product-card block">
                        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 transition-all duration-300 ease-out hover:shadow-xl hover:-translate-y-1.5 flex flex-col h-full">
                            
                            <div class="relative h-48 sm:h-56 bg-gray-100 overflow-hidden">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}"
                                         class="w-full h-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-105" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                         <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif

                                <div class="absolute top-2.5 right-2.5">
                                    @if($totalStock == 0 && $product->is_preorder)
                                        <span class="badge bg-orange-500 text-white">Pre Order</span>
                                    @elseif($totalStock > 0)
                                        <span class="badge bg-green-500 text-white flex items-center gap-1"><i class="fas fa-check text-xs"></i> Stok: {{ $totalStock }}</span>
                                    @else
                                         <span class="badge bg-red-500 text-white flex items-center gap-1"><i class="fas fa-times text-xs"></i> Habis</span>
                                    @endif
                                </div>

                                {{-- PERUBAHAN: Overlay Hover (Hanya Lihat Detail) --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end justify-end p-3 
                                            opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 
                                            transition-all duration-300 ease-out">
                                     <span class="text-white text-xs font-semibold flex items-center gap-1 opacity-80 group-hover:opacity-100 transition-opacity">
                                         Lihat Detail <i class="fas fa-arrow-right text-[10px] transform group-hover:translate-x-0.5 transition-transform duration-200"></i>
                                     </span>
                                </div>
                            </div>

                            <div class="p-4 flex flex-col flex-grow">
                                <h2 class="text-sm font-semibold text-gray-800 mb-1 line-clamp-1 group-hover:text-blue-600 transition-colors">
                                    {{ $product->title }}
                                </h2>
                                
                                <div class="mb-2">
                                    <span class="text-lg font-bold text-blue-600">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                </div>

                                <p class="text-gray-500 text-xs mb-3 line-clamp-2 h-[30px] overflow-hidden flex-grow">
                                    {{ $product->description }}
                                </p>

                                <div class="mt-auto pt-2 border-t border-gray-100">
                                    <p class="text-[11px] font-medium text-gray-500 mb-1.5">Ukuran Tersedia:</p>
                                    <div class="flex flex-wrap gap-1.5">
                                        @forelse($product->sizes as $size)
                                            <span class="px-2 py-0.5 text-[10px] rounded font-semibold border
                                                {{ $size->stock > 0 
                                                   ? 'bg-emerald-50 text-emerald-700 border-emerald-200' 
                                                   : 'bg-gray-100 text-gray-400 border-gray-200 line-through' }}">
                                                {{ $size->size }} 
                                                <span class="opacity-70">({{ $size->stock }})</span>
                                            </span>
                                        @empty
                                            <span class="text-gray-400 text-xs italic">-</span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Kategori: Atribut Sekolah --}}
        @php $atributProducts = $products->filter(fn($p) => $p->category === 'Atribut Sekolah'); @endphp
        @if($atributProducts->count() > 0)
        <div class="mb-12">
            <div class="flex items-center gap-4 mb-6 p-4 rounded-lg bg-gradient-to-r from-emerald-100 via-white to-emerald-50 border border-emerald-100 shadow-sm">
                <div class="p-3 rounded-lg bg-emerald-600 text-white shadow">
                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-emerald-800">Atribut Sekolah</h2>
                    <p class="text-sm text-emerald-600">{{ $atributProducts->count() }} Produk Tersedia</p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 md:gap-6">
                @foreach($atributProducts as $product)
                     @php 
                         $totalStock = $product->sizes->isNotEmpty() 
                                     ? $product->sizes->sum('stock') 
                                     : ($product->stock ?? 0); 
                     @endphp
                     <a href="{{ route('product.show', $product->id) }}" class="group product-card block">
                        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 transition-all duration-300 ease-out hover:shadow-xl hover:-translate-y-1.5 flex flex-col h-full">
                            <div class="relative h-48 sm:h-56 bg-gray-100 overflow-hidden">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}"
                                         class="w-full h-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-105" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                         <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif

                                <div class="absolute top-2.5 right-2.5">
                                     @if($totalStock == 0 && $product->is_preorder)
                                        <span class="badge bg-orange-500 text-white">Pre Order</span>
                                    @elseif($totalStock > 0)
                                        <span class="badge bg-green-500 text-white flex items-center gap-1"><i class="fas fa-check text-xs"></i> Stok: {{ $totalStock }}</span>
                                    @else
                                         <span class="badge bg-red-500 text-white flex items-center gap-1"><i class="fas fa-times text-xs"></i> Habis</span>
                                    @endif
                                </div>
                                
                                {{-- PERUBAHAN: Overlay Hover (Hanya Lihat Detail) --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end justify-end p-3 
                                            opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 
                                            transition-all duration-300 ease-out">
                                     <span class="text-white text-xs font-semibold flex items-center gap-1 opacity-80 group-hover:opacity-100 transition-opacity">
                                         Lihat Detail <i class="fas fa-arrow-right text-[10px] transform group-hover:translate-x-0.5 transition-transform duration-200"></i>
                                     </span>
                                </div>
                            </div>
                            <div class="p-4 flex flex-col flex-grow">
                                <h2 class="text-sm font-semibold text-gray-800 mb-1 line-clamp-1 group-hover:text-blue-600 transition-colors">
                                    {{ $product->title }}
                                </h2>
                                <div class="mb-2">
                                    <span class="text-lg font-bold text-blue-600">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                </div>
                                <p class="text-gray-500 text-xs mb-3 line-clamp-2 h-[30px] overflow-hidden flex-grow">
                                    {{ $product->description }}
                                </p>
                                <div class="mt-auto pt-2 border-t border-gray-100">
                                     <p class="text-[11px] font-medium text-gray-500 mb-1.5">Ukuran:</p>
                                    <div class="flex flex-wrap gap-1.5">
                                        @if($product->sizes->isNotEmpty())
                                             @foreach($product->sizes as $size)
                                                <span class="px-2 py-0.5 text-[10px] rounded font-semibold border
                                                    {{ $size->stock > 0 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-gray-100 text-gray-400 border-gray-200 line-through' }}">
                                                    {{ $size->size }} 
                                                    <span class="opacity-70">({{ $size->stock }})</span>
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-gray-400 text-xs italic">N/A</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Kosong --}}
        @if($seragamProducts->isEmpty() && $atributProducts->isEmpty())
        <div class="text-center py-20">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-100 to-emerald-100 rounded-full mb-6 shadow-sm">
                 <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Oops! Belum Ada Produk</h3>
            <p class="text-gray-500">Produk akan segera ditambahkan. Cek kembali nanti ya!</p>
        </div>
        @endif
    </div>
</div>

<script>
// Script Modal (Sama seperti sebelumnya)
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('sizeGuideModal');
    const openBtn = document.getElementById('sizeGuideBtn');
    const closeBtn = document.getElementById('closeModalBtn');
    const modalContent = modal.querySelector('.relative.max-w-3xl'); 

    function openModal() {
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modalContent.classList.remove('scale-95');
    }
    function closeModal() {
        modal.classList.add('opacity-0');
        modalContent.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('pointer-events-none'); }, 300); 
    }

    if (openBtn && modal && closeBtn && modalContent) { // Add checks
        openBtn.addEventListener('click', openModal);
        closeBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !modal.classList.contains('opacity-0')) closeModal(); });
    } else {
        console.error("Modal elements not found!");
    }
});
</script>

<style>
/* Font Poppins */
.font-poppins { font-family: 'Poppins', sans-serif; }

/* Line Clamp */
.line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.h-\[30px\] { height: 30px; } 

/* Animasi Tombol Panduan */
@keyframes pulse-glow-soft {
    0%, 100% { box-shadow: 0 0 12px -2px rgba(59, 130, 246, 0.3); } 
    50% { box-shadow: 0 0 20px 0px rgba(96, 165, 250, 0.5); } 
}
#sizeGuideBtn .button-glow {
    position: absolute; inset: -2px; border-radius: inherit; 
    box-shadow: 0 0 12px -2px rgba(59, 130, 246, 0.3);
    animation: pulse-glow-soft 2.5s infinite ease-in-out;
    z-index: 0; 
    opacity: 0.8;
}

/* Style Badge Produk */
.badge {
    padding: 3px 8px; font-size: 9px; font-weight: 700; border-radius: 6px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1); letter-spacing: 0.5px; text-transform: uppercase;
    /* Menambahkan backdrop blur agar lebih terbaca di atas gambar */
    background-color: rgba(0,0,0,0.3);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,0.1);
}
/* Warna spesifik badge (override background default) */
.badge.bg-green-500 { background-color: rgba(34, 197, 94, 0.8); } /* green-500 with opacity */
.badge.bg-red-500 { background-color: rgba(239, 68, 68, 0.8); } /* red-500 with opacity */
.badge.bg-orange-500 { background-color: rgba(249, 115, 22, 0.8); } /* orange-500 with opacity */


/* Animasi Modal */
#sizeGuideModal { transition: opacity 0.3s ease-out; }
#sizeGuideModal > .relative { transition: transform 0.3s ease-out; }

/* Animasi Halaman */
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeInSlow { from { opacity: 0; } to { opacity: 1; } }
@keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

.animate-fadeIn { animation: fadeIn 0.8s ease-out both; }
.animate-fadeInSlow { animation: fadeInSlow 1.2s ease-out both; }
.animate-slideUp { animation: slideUp 0.9s ease-out both; }
.animate-slideUp.delay-1 { animation-delay: 0.1s; } 
.animate-slideUp.delay-2 { animation-delay: 0.25s; } 

/* Animasi Blob */
@keyframes spin-slow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.animate-spin-slow { animation: spin-slow 25s linear infinite; }

</style>
@endsection
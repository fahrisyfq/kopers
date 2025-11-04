<section id="products" class="py-16 md:py-24 bg-gray-50 relative overflow-hidden font-poppins">

    <div class="absolute z-0 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none">
        <div class="w-[600px] h-[600px] md:w-[800px] md:h-[800px] bg-gradient-to-tr from-emerald-200 to-cyan-200
                    rounded-full blur-2xl opacity-30 animate-spin-slow">
        </div>
    </div>
    
    <div class="container mx-auto relative z-10">
        <div class="text-center mb-16 px-4">
            <h2 class="text-4xl md:text-5xl font-extrabold mb-4 animate-fade-in
                       text-transparent bg-clip-text bg-gradient-to-r 
                       from-emerald-500 via-teal-500 to-cyan-500 section-title-glow">
                Produk Unggulan
            </h2>
            <p class="text-slate-600 max-w-2xl mx-auto text-lg animate-fade-in-slow">
                Pilihan produk kebutuhan sekolah, alat tulis, dan atribut SMKN 8 yang terjangkau dan berkualitas.
            </p>
        </div>

        <div 
            x-data="{ isPaused: false }" 
            @mouseenter="isPaused = true" 
            @mouseleave="isPaused = false"
            class="relative w-full overflow-hidden"
            style="mask-image: linear-gradient(to right, transparent, white 5%, white 95%, transparent);"
        >
            <div 
                class="flex gap-4 md:gap-8 w-max" 
                :class="isPaused ? 'animation-paused' : ''" 
                id="product-carousel-track"
            >
                {{-- Produk Loop 1 --}}
                @foreach ($products as $product)
                    {{-- PERUBAHAN: Hover disederhanakan (hanya shadow), border dihilangkan --}}
                    <div class="product-card bg-white rounded-2xl overflow-hidden shadow-lg group hover:shadow-xl transition-shadow duration-300 w-64 sm:w-72 md:w-80 flex-shrink-0">
                        <div class="relative overflow-hidden h-64">
                             {{-- PERUBAHAN: Hover scale gambar dihapus --}}
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->nama_produk }}" class="w-full h-full object-cover transition-transform duration-500 ease-in-out" /> 
                            
                            {{-- Hover Overlay & Ikon Mata DINONAKTIFKAN SEMENTARA
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                                <i class="fas fa-eye text-white text-4xl"></i>
                            </div> 
                            --}}

                            <div class="absolute top-3 left-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-md">
                                {{ $loop->first ? 'Favorit' : ($loop->iteration == 2 ? 'Baru' : 'Best Seller') }}
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="text-lg font-bold mb-1 text-slate-800">{{ $product->nama_produk }}</h3>
                            {{-- PERUBAHAN: Menggunakan height tetap + overflow-hidden (tanpa line-clamp) --}}
                            <p class="text-gray-600 text-sm mb-2 h-10 leading-snug overflow-hidden"> 
                                {{ $product->deskripsi }} 
                            </p>
                            <span class="text-emerald-600 font-bold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach

                {{-- Produk Loop 2 (Duplikat untuk Animasi) --}}
                @foreach ($products as $product)
                     <div class="product-card bg-white rounded-2xl overflow-hidden shadow-lg group hover:shadow-xl transition-shadow duration-300 w-64 sm:w-72 md:w-80 flex-shrink-0">
                        <div class="relative overflow-hidden h-64">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->nama_produk }}" class="w-full h-full object-cover transition-transform duration-500 ease-in-out" />
                            
                            {{-- Hover Overlay & Ikon Mata DINONAKTIFKAN SEMENTARA
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                                <i class="fas fa-eye text-white text-4xl"></i>
                            </div>
                            --}}

                            <div class="absolute top-3 left-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-md">
                                {{ $loop->first ? 'Favorit' : ($loop->iteration == 2 ? 'Baru' : 'Best Seller') }}
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="text-lg font-bold mb-1 text-slate-800">{{ $product->nama_produk }}</h3>
                            <p class="text-gray-600 text-sm mb-2 h-10 leading-snug overflow-hidden"> 
                                {{ $product->deskripsi }}
                            </p>
                            <span class="text-emerald-600 font-bold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="text-center mt-16">
            <a href="{{ route('produk.index') }}" class="inline-flex items-center px-8 py-3 border-2 border-emerald-500 text-emerald-600 font-bold rounded-full bg-white hover:bg-emerald-500 hover:text-white shadow-lg transition-all duration-300 text-lg group transform hover:-translate-y-1">
                Lihat Semua Produk
                <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform duration-300"></i>
            </a>
        </div>
    </div>
</section>

<style>
/* Import Font Poppins */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap');

#products, #products .product-card {
    font-family: 'Poppins', sans-serif;
}

/* Glow Judul Section */
.section-title-glow {
    text-shadow: 0 0 30px rgba(52, 211, 153, 0.3);
}

/* Animasi Scroll Carousel */
@keyframes scroll-left {
    from { transform: translate3d(0, 0, 0); } /* Menggunakan translate3d */
    to   { transform: translate3d(-50%, 0, 0); } /* Menggunakan translate3d */
}

#product-carousel-track {
    animation: scroll-left 35s linear infinite;
    transition: animation-duration 0.8s ease; 
    will-change: transform; 
}

/* Pause saat hover */
.animation-paused {
    animation-duration: 999s !important; 
}

/* Animasi Fade-in */
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px);}
    to { opacity: 1; transform: translateY(0);}
}
.animate-fade-in { animation: fade-in 1s ease both; } 
.animate-fade-in-slow { animation: fade-in 1.5s ease both; }

/* Animasi Blob */
@keyframes spin-slow {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
.animate-spin-slow {
    animation: spin-slow 25s linear infinite;
}

/* PERUBAHAN: Menghapus .line-clamp-2, menggunakan h-10 + leading-snug + overflow-hidden */
.h-10 { height: 2.5rem; } /* Pastikan tinggi cukup untuk 2 baris text-sm dengan leading-snug */
.leading-snug { line-height: 1.375; } /* Tailwind default untuk text-sm */

</style>

{{-- Komentar tentang plugin line-clamp dihapus karena tidak dipakai di versi ini --}}
<section id="products" class="py-16 md:py-24 bg-gray-50 relative overflow-hidden font-poppins">

    {{-- Latar Belakang Aurora --}}
    <div class="aurora-background">
        <div class="aurora-blob aurora-blob-1"></div>
        <div class="aurora-blob aurora-blob-2"></div>
    </div>
    
    <div class="container mx-auto relative z-10">
        <div class="text-center mb-12 md:mb-16 px-4" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-extrabold mb-4
                       text-transparent bg-clip-text bg-gradient-to-r 
                       from-emerald-500 via-teal-500 to-cyan-500 section-title-glow">
                Produk Unggulan
            </h2>
            <p class="text-slate-600 max-w-2xl mx-auto text-base md:text-lg" data-aos="fade-up" data-aos-delay="100">
                Pilihan produk kebutuhan sekolah, alat tulis, dan atribut SMKN 8 yang terjangkau dan berkualitas.
            </p>
        </div>

        {{-- Carousel Auto-Scroll dengan Pause Halus --}}
        <div 
            class="relative w-full overflow-hidden carousel-container"
            data-aos="fade-up" data-aos-delay="200"
            style="mask-image: linear-gradient(to right, transparent, white 5%, white 95%, transparent);"
        >
            <div 
                class="flex gap-4 md:gap-6 w-max" 
                id="product-carousel-track"
            >
                {{-- Loop 1 (Asli) --}}
                @foreach ($products as $product)
                    {{-- [DIUBAH] Kartu sekarang menjadi <div>, bukan <a> --}}
                    <div class="product-card-glass rounded-2xl overflow-hidden 
                                w-[280px] sm:w-[300px]
                                flex-shrink-0 group">
                        
                        <div class="relative overflow-hidden h-64">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->nama_produk }}" 
                                 class="w-full h-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-105" /> 
                            
                            {{-- Overlay & Ikon Mata Diaktifkan --}}
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 pointer-events-none">
                                <i class="fas fa-eye text-white text-4xl transform transition-transform duration-300 group-hover:scale-110"></i>
                            </div> 

                            <div class="absolute top-3 left-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-md">
                                {{ $loop->first ? 'Favorit' : ($loop->iteration == 2 ? 'Baru' : 'Best Seller') }}
                            </div>
                        </div>
                        
                        <div class="p-5">
                            <h3 class="text-lg font-bold mb-1 text-slate-800 truncate">{{ $product->nama_produk }}</h3>
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2"> 
                                {{ $product->deskripsi }} 
                            </p>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-emerald-600 font-bold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="flex items-center text-xs font-medium text-teal-700 bg-teal-100 px-2 py-1 rounded-full">
                                    <i class="fas fa-tag mr-1.5 opacity-70"></i>
                                    {{ $product->category->nama_kategori ?? 'Umum' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                {{-- Loop 2 (Duplikat untuk animasi seamless) --}}
                @foreach ($products as $product)
                    <div class="product-card-glass rounded-2xl overflow-hidden 
                                w-[280px] sm:w-[300px]
                                flex-shrink-0 group">
                        
                        <div class="relative overflow-hidden h-64">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->nama_produk }}" 
                                 class="w-full h-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-105" /> 
                            
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 pointer-events-none">
                                <i class="fas fa-eye text-white text-4xl transform transition-transform duration-300 group-hover:scale-110"></i>
                            </div> 

                            <div class="absolute top-3 left-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-md">
                                {{ $loop->first ? 'Favorit' : ($loop->iteration == 2 ? 'Baru' : 'Best Seller') }}
                            </div>
                        </div>
                        
                        <div class="p-5">
                            <h3 class="text-lg font-bold mb-1 text-slate-800 truncate">{{ $product->nama_produk }}</h3>
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                {{ $product->deskripsi }} 
                            </p>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-emerald-600 font-bold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="flex items-center text-xs font-medium text-teal-700 bg-teal-100 px-2 py-1 rounded-full">
                                    <i class="fas fa-tag mr-1.5 opacity-70"></i>
                                    {{ $product->category->nama_kategori ?? 'Umum' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="text-center mt-12 md:mt-16" data-aos="fade-up" data-aos-delay="400">
            <a href="{{ route('produk.index') }}" class="inline-flex items-center px-8 py-3 border-2 border-emerald-500 text-emerald-600 font-bold rounded-full bg-white hover:bg-emerald-500 hover:text-white shadow-lg transition-all duration-300 text-lg group transform hover:-translate-y-1">
                Lihat Semua Produk
                <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform duration-300"></i>
            </a>
        </div>
    </div>
</section>

<style>
/* Import Font Poppins */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

#products, #products .product-card-glass { /* [FIX] Terapkan font ke kartu juga */
    font-family: 'Poppins', sans-serif;
}

/* ========================================
🔹 ANIMASI LATAR "AURORA"
========================================
*/
.aurora-background {
    position: absolute;
    inset: 0;
    overflow: hidden;
    z-index: 0;
    pointer-events: none;
}
.aurora-blob {
    position: absolute;
    filter: blur(120px);
    opacity: 0.15;
    border-radius: 50%;
    animation: aurora-float 25s infinite ease-in-out;
}
.aurora-blob-1 {
    width: 500px;
    height: 500px;
    background: #34d399; /* emerald */
    top: -20%;
    left: -10%;
    animation-delay: 0s;
}
.aurora-blob-2 {
    width: 400px;
    height: 400px;
    background: #38bdf8; /* light-blue */
    top: 20%;
    right: 0%;
    animation-delay: -8s;
}
@keyframes aurora-float {
    0% { transform: translateY(0px) translateX(0px) rotate(0deg); }
    50% { transform: translateY(80px) translateX(100px) rotate(180deg); }
    100% { transform: translateY(0px) translateX(0px) rotate(360deg); }
}

/* Glow Judul Section */
.section-title-glow {
    text-shadow: 0 0 30px rgba(52, 211, 153, 0.3);
}

/* ========================================
🔹 KARTU PRODUK (GLASSMORPHISM)
========================================
*/
.product-card-glass {
    background: rgba(255, 255, 255, 0.6); /* Latar semi-transparan */
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.product-card-glass:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.15);
}

/* ========================================
🔹 AUTO-SCROLL CAROUSEL
========================================
*/
@keyframes scroll-left {
    from { transform: translateX(0); }
    to { transform: translateX(-50%); }
}

#product-carousel-track {
    animation-name: scroll-left;
    animation-duration: 40s; 
    animation-timing-function: linear;
    animation-iteration-count: infinite;
    will-change: transform;
    /* Transisi untuk play-state agar mulus */
    transition: animation-play-state 0.5s ease-out;
}

.carousel-container:hover #product-carousel-track {
    animation-play-state: paused;
}

/* Animasi Fade-in (AOS) */
/* [DIHAPUS] CSS Fade-in manual dihapus, diganti AOS */

/* Deskripsi 2 baris */
.line-clamp-2 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
    min-height: 2.5rem; /* 2 baris text-sm */
}

</style>

{{-- Script untuk Animate On Scroll (AOS) --}}
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
      duration: 1000, 
      easing: 'ease-out-quad',
      once: true,
      mirror: false,
  });
</script>
</body>
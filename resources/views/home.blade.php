@extends('layout.app')

@section('title', 'Home')

@section('content')
@php
    $heroImages = [
        asset('images/atas.jpg'),
        asset('images/atasss.jpg'),
        asset('images/atassss.jpg'),
    ];
@endphp

<section 
    x-data="{
        images: @js($heroImages),
        active: 0,
        next() { this.active = (this.active + 1) % this.images.length }
    }"
    x-init="setInterval(() => next(), 7000)" 
    class="relative h-[90vh] md:h-screen w-full overflow-hidden bg-black font-poppins" {{-- Font Poppins ditambahkan di sini --}}
>
    {{-- Background Carousel --}}
    <template x-for="(img, idx) in images" :key="idx">
        <div
            class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000 ease-in-out"
            :class="active === idx ? 'opacity-100 z-10 animate-ken-burns' : 'opacity-0 z-0'"
            :style="`background-image: url('${img}')`"
        ></div>
    </template>

    {{-- Overlay Lebih Dinamis --}}
    <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-black/80 z-20 overflow-hidden">
        {{-- Garis Animasi Halus --}}
        <div class="absolute inset-0 lines-bg z-0 opacity-5"></div> {{-- Opacity dikurangi --}}
    </div>

    {{-- Navigation Dots (Modern Pill Style) --}}
    <div class="absolute bottom-8 left-1/2 z-30 -translate-x-1/2 flex space-x-2">
        <template x-for="(img, idx) in images" :key="idx">
            <button 
                @click="active = idx"
                class="h-2 rounded-full transition-all duration-300"
                :class="active === idx 
                    ? 'bg-emerald-400 w-6 shadow-lg shadow-emerald-400/50' 
                    : 'bg-white/40 hover:bg-white/60 w-2'" {{-- Hover lebih terang --}}
            ></button>
        </template>
    </div>

    {{-- Scroll Down Indicator (Animasi Dihaluskan) --}}
    <div class="absolute bottom-20 left-1/2 z-30 -translate-x-1/2 hidden md:block"> {{-- Posisi disesuaikan sedikit --}}
        <a href="#about" class="scroll-indicator flex flex-col items-center group"> {{-- Ditambah group --}}
            <span class="text-white text-xs font-medium mb-2 opacity-70 group-hover:opacity-100 transition-opacity duration-300 animate-fade-in anim-delay-800"> {{-- Font size disesuaikan --}}
                Jelajahi
            </span>
            <div class="arrow-container">
                <i class="fas fa-arrow-down text-white text-xl group-hover:text-emerald-300 transition-colors duration-300"></i> {{-- Ukuran & hover disesuaikan --}}
                <div class="line"></div>
            </div>
        </a>
    </div>

    {{-- Hero Content (Animasi Dihaluskan) --}}
    <div class="container mx-auto px-6 relative z-30 h-full flex items-center justify-center md:justify-start">
        <div class="max-w-2xl text-center md:text-left">
            <h2 class="text-sm sm:text-lg text-emerald-300 font-medium mb-3 tracking-wider uppercase drop-shadow-[0_0_12px_rgba(16,185,129,0.9)] animate-fade-in">
                Selamat Datang di
            </h2>

            <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-5 
                       text-transparent bg-clip-text 
                       bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-300 
                       text-glow-animate animate-fade-in anim-delay-200">
                Koperasi SMKN 8 Jakarta
            </h1>

            <p class="text-base md:text-lg text-gray-200 mb-8 leading-relaxed max-w-lg mx-auto md:mx-0 drop-shadow-[0_2px_10px_rgba(0,0,0,0.8)] animate-fade-in anim-delay-400"> {{-- Warna teks sedikit disesuaikan --}}
                <span class="text-emerald-300 font-semibold">Praktis, Hemat, dan Terpercaya.</span><br>
                Kami menyediakan <span class="font-semibold text-white">seragam, atribut, dan perlengkapan sekolah</span>
                untuk mendukung kegiatan belajar siswa dengan pelayanan terbaik.
            </p>

            {{-- Tombol CTA dengan Efek Shine --}}
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-5 justify-center md:justify-start animate-fade-in anim-delay-600"> {{-- Gap disesuaikan --}}
                <a href="#products" 
                   class="cta-button relative inline-flex items-center justify-center px-6 py-3 rounded-lg text-white font-semibold 
                          bg-gradient-to-r from-emerald-500 to-teal-500 
                          hover:from-emerald-600 hover:to-teal-600 
                          shadow-lg hover:shadow-emerald-400/50 
                          transition-all duration-300 transform hover:-translate-y-1 w-full sm:w-auto overflow-hidden">
                    <i class="fas fa-shopping-cart mr-2"></i> Belanja Sekarang
                    <span class="shine"></span>
                </a>
                <a href="#about" 
                   class="cta-button relative inline-flex items-center justify-center px-6 py-3 rounded-lg text-white font-semibold 
                          bg-gradient-to-r from-blue-500 to-cyan-500 
                          hover:from-blue-600 hover:to-cyan-600 
                          shadow-lg hover:shadow-blue-400/50 
                          transition-all duration-300 transform hover:-translate-y-1 w-full sm:w-auto overflow-hidden">
                    <i class="fas fa-info-circle mr-2"></i> Tentang Kami
                    <span class="shine"></span>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
/* Import Font Poppins (jika belum ada di layout utama) */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

.font-poppins { font-family: 'Poppins', sans-serif; }

/* === Animasi teks staggered (Easing Dihaluskan) === */
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); filter: brightness(0.8); } /* Mulai sedikit dari bawah & redup */
    to { opacity: 1; transform: translateY(0); filter: brightness(1); }
}
.animate-fade-in {
    animation: fade-in 1s cubic-bezier(0.25, 0.46, 0.45, 0.94) both; /* Easing lebih halus */
}
.anim-delay-200 { animation-delay: 200ms; }
.anim-delay-400 { animation-delay: 400ms; }
.anim-delay-600 { animation-delay: 600ms; }
.anim-delay-800 { animation-delay: 800ms; } /* Untuk teks scroll down */

/* === Animasi Ken Burns (Easing Dihaluskan, Zoom Sedikit Dikurangi) === */
@keyframes animate-ken-burns {
    0% { transform: scale(1); }
    100% { transform: scale(1.08); } 
}
.animate-ken-burns {
    animation: animate-ken-burns 7s ease-in-out both; /* Easing in-out */
}

/* === Animasi Glow Teks (Tidak Berubah) === */
@keyframes soft-glow {
    /* ... (kode keyframes soft-glow Anda) ... */
    0% { text-shadow: 0 0 8px rgba(16,185,129,0.4), 0 0 15px rgba(6,182,212,0.3); }
    50% { text-shadow: 0 0 20px rgba(16,185,129,0.6), 0 0 40px rgba(6,182,212,0.5); }
    100% { text-shadow: 0 0 8px rgba(16,185,129,0.4), 0 0 15px rgba(6,182,212,0.3); }
}
.text-glow-animate { animation: soft-glow 3s ease-in-out infinite; }

/* === Indikator Scroll Down (Animasi Dihaluskan) === */
.scroll-indicator .arrow-container {
    position: relative; width: 30px; height: 30px; display: flex;
    justify-content: center; align-items: center; overflow: hidden; 
}
.scroll-indicator .arrow-container i {
    z-index: 1; 
    /* PERUBAHAN: Animasi diperlambat & easing disesuaikan */
    animation: bounce-arrow 1.8s infinite ease-in-out; 
}
.scroll-indicator .arrow-container .line {
    position: absolute; width: 2px; height: 0; background-color: rgba(255, 255, 255, 0.6); /* Lebih terang sedikit */
    bottom: -2px; /* Mulai sedikit di bawah panah */ left: 50%; transform: translateX(-50%);
    /* PERUBAHAN: Animasi diperlambat & easing disesuaikan */
    animation: draw-line 1.8s infinite ease-in-out; 
    z-index: 0; 
}

@keyframes bounce-arrow {
    /* PERUBAHAN: Pantulan lebih halus */
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-6px); } 
}
@keyframes draw-line {
    /* PERUBAHAN: Delay & tinggi disesuaikan */
    0% { height: 0; opacity: 0; }
    20% { height: 0; opacity: 0; } 
    60% { height: 35px; opacity: 0.8; } 
    100% { height: 0; opacity: 0; }
}

/* === BARU: Latar Belakang Garis Animasi === */
.lines-bg {
    background: 
        linear-gradient(45deg, rgba(255,255,255,0.05) 1px, transparent 1px, transparent 10px), /* Warna lebih subtle */
        linear-gradient(-45deg, rgba(255,255,255,0.05) 1px, transparent 1px, transparent 10px);
    background-size: 60px 60px; /* Ukuran pola dikecilkan */
    animation: move-lines 20s linear infinite; /* Animasi diperlambat */
}
@keyframes move-lines {
    0% { background-position: 0 0; }
    100% { background-position: 60px 60px; }
}

/* === BARU: Efek Shine pada Tombol CTA === */
.cta-button .shine {
    position: absolute;
    top: -50%;
    left: -150%; /* Mulai jauh di kiri */
    width: 25px; /* Lebar kilatan */
    height: 200%;
    background: linear-gradient(
        to right, 
        rgba(255, 255, 255, 0) 0%, 
        rgba(255, 255, 255, 0.4) 50%, 
        rgba(255, 255, 255, 0) 100%
    ); /* Gradien kilatan */
    transform: rotate(35deg);
    transition: left 0.7s cubic-bezier(0.23, 1, 0.32, 1); /* Transisi lebih lambat & smooth */
    pointer-events: none; /* Agar tidak menghalangi klik */
}
.cta-button:hover .shine {
    left: 150%; /* Bergerak ke kanan saat hover */
}
</style>
@include('partials.about')
@include('partials.features')
@include('partials.menuproduk')
@endsection
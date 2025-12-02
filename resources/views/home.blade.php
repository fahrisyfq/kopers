@extends('layout.app')

@section('title', 'Home')

@section('content')
@php
    $heroImages = [
        asset('images/atas.jpeg'),
        asset('images/atasss.jpeg'),
        asset('images/atassss.jpeg'),
    ];
@endphp

<section 
    x-data="{
        images: @js($heroImages),
        active: 0,
        next() { this.active = (this.active + 1) % this.images.length }
    }"
    x-init="setInterval(() => next(), 7000)" {{-- Ganti gambar tetap setiap 7 detik --}}
    class="relative h-[90vh] md:h-screen w-full overflow-hidden bg-black font-poppins"
>
    {{-- Background Carousel (DIUBAH: Transisi Fade Sederhana) --}}
    <template x-for="(img, idx) in images" :key="idx">
        <div
            class="absolute inset-0 bg-cover bg-center"
            {{-- [DIUBAH] Durasi fade diperpanjang menjadi 3 detik (3000ms) --}}
            x-show="active === idx"
            x-transition:enter="transition-opacity duration-3000 ease-in-out"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-3000 ease-in-out"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            {{-- Animasi Ken Burns tetap ada --}}
            :class="{
                'animate-ken-burns-1': idx === 0,
                'animate-ken-burns-2': idx === 1,
                'animate-ken-burns-3': idx === 2
            }"
            :style="`background-image: url('${img}')`"
        ></div>
    </template>

    {{-- Overlay Minimalis --}}
    <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/30 to-black/70 z-20"></div>

    {{-- Navigation Dots (Modern Pill Style) --}}
    <div class="absolute bottom-8 left-1/2 z-30 -translate-x-1/2 flex space-x-2">
        <template x-for="(img, idx) in images" :key="idx">
            <button 
                @click="active = idx"
                class="h-2 rounded-full transition-all duration-300"
                :class="active === idx 
                    ? 'bg-emerald-400 w-6 shadow-lg shadow-emerald-400/50' 
                    : 'bg-white/40 hover:bg-white/60 w-2'"
                :aria-label="'Go to slide ' + (idx + 1)"
            ></button>
        </template>
    </div>

    {{-- Scroll Down Indicator (Simpel) --}}
    <div class="absolute bottom-20 left-1/2 z-30 -translate-x-1/2 hidden md:block">
        <a href="#about" class="scroll-indicator flex flex-col items-center group animate-fade-in anim-delay-800">
            <span class="text-white text-xs font-medium mb-2 opacity-70 group-hover:opacity-100 transition-opacity duration-300">
                Jelajahi
            </span>
            <div class="w-8 h-8 flex items-center justify-center animate-bounce">
                <i class="fas fa-arrow-down text-white text-xl group-hover:text-emerald-300 transition-colors duration-300"></i>
            </div>
        </a>
    </div>

    {{-- Hero Content --}}
    <div class="container mx-auto px-6 relative z-30 h-full flex items-center justify-center md:justify-start">
        <div class="max-w-2xl text-center md:text-left">
            <h2 class="text-sm sm:text-lg text-emerald-300 font-medium mb-3 tracking-wider uppercase drop-shadow-[0_0_12px_rgba(16,185,129,0.9)] animate-fade-in">
                Selamat Datang di
            </h2>

            <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-5 
                       text-transparent bg-clip-text 
                       bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-300 
                       static-text-glow 
                       animate-fade-in anim-delay-200">
                Koperasi SMKN 8 Jakarta
            </h1>

            <p class="text-base md:text-lg text-gray-100 mb-8 leading-relaxed max-w-lg mx-auto md:mx-0 drop-shadow-[0_2px_10px_rgba(0,0,0,0.8)] animate-fade-in anim-delay-400">
                <span class="text-emerald-300 font-semibold">Praktis, Hemat, dan Terpercaya.</span><br>
                Kami menyediakan seragam, atribut, dan perlengkapan sekolah untuk mendukung kegiatan belajar siswa.
            </p>

            {{-- Tombol CTA (Minimalis) --}}
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-5 justify-center md:justify-start animate-fade-in anim-delay-600">
                <a href="#products" 
                   class="inline-flex items-center justify-center px-6 py-3 rounded-lg text-white font-semibold 
                          bg-gradient-to-r from-emerald-500 to-teal-500 
                          hover:from-emerald-600 hover:to-teal-600 
                          shadow-lg hover:shadow-emerald-400/50 
                          transition-all duration-300 transform hover:-translate-y-1 w-full sm:w-auto">
                    <i class="fas fa-shopping-cart mr-2"></i> Belanja Sekarang
                </a>
                <a href="#about" 
                   class="inline-flex items-center justify-center px-6 py-3 rounded-lg text-white font-semibold 
                          bg-gradient-to-r from-blue-500 to-cyan-500 
                          hover:from-blue-600 hover:to-cyan-600 
                          shadow-lg hover:shadow-blue-400/50 
                          transition-all duration-300 transform hover:-translate-y-1 w-full sm:w-auto">
                    <i class="fas fa-info-circle mr-2"></i> Tentang Kami
                </a>
            </div>
        </div>
    </div>
</section>

<style>
/* Import Font Poppins */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

.font-poppins { font-family: 'Poppins', sans-serif; }

/* === Animasi teks staggered === */
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); filter: brightness(0.8); }
    to { opacity: 1; transform: translateY(0); filter: brightness(1); }
}
.animate-fade-in {
    animation: fade-in 1s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
}
.anim-delay-200 { animation-delay: 200ms; }
.anim-delay-400 { animation-delay: 400ms; }
.anim-delay-600 { animation-delay: 600ms; }
.anim-delay-800 { animation-delay: 800ms; }

/* ==============================================
   [PERBAIKAN] 3 Animasi Ken Burns Dibuat Bolak-balik
   ==============================================
*/
@keyframes ken-burns-1 { /* Zoom + Pan Kiri */
    0% { transform: scale(1.05) translate(0, 0); }
    100% { transform: scale(1.15) translate(-3%, 0); } 
}
.animate-ken-burns-1 {
    animation-name: ken-burns-1;
    animation-duration: 14s; /* [DIUBAH] 7s -> 14s */
    animation-timing-function: ease-in-out;
    animation-iteration-count: infinite; /* [DIUBAH] Ditambahkan */
    animation-direction: alternate; /* [DIUBAH] Ditambahkan */
}

@keyframes ken-burns-2 { /* Zoom + Pan Kanan */
    0% { transform: scale(1.05) translate(0, 0); }
    100% { transform: scale(1.15) translate(3%, 0); } 
}
.animate-ken-burns-2 {
    animation-name: ken-burns-2;
    animation-duration: 14s; /* [DIUBAH] 7s -> 14s */
    animation-timing-function: ease-in-out;
    animation-iteration-count: infinite; /* [DIUBAH] Ditambahkan */
    animation-direction: alternate; /* [DIUBAH] Ditambahkan */
}

@keyframes ken-burns-3 { /* Zoom Tengah */
    0% { transform: scale(1); }
    100% { transform: scale(1.1); } 
}
.animate-ken-burns-3 {
    animation-name: ken-burns-3;
    animation-duration: 14s; /* [DIUBAH] 7s -> 14s */
    animation-timing-function: ease-in-out;
    animation-iteration-count: infinite; /* [DIUBAH] Ditambahkan */
    animation-direction: alternate; /* [DIUBAH] Ditambahkan */
}
/* ==============================================
   AKHIR PERBAIKAN
   ==============================================
*/

/* === Animasi Glow Teks (Statis) === */
.static-text-glow {
    text-shadow: 0 0 15px rgba(16,185,129,0.5), 0 0 30px rgba(6,182,212,0.4);
}
</style>

{{-- Memanggil section lain di bawahnya --}}
@include('partials.about')
@include('partials.features')
@include('partials.menuproduk')
@include('partials.testimonials')

{{-- [WAJIB] Tambahkan script AOS di akhir <body> (jika belum ada di app.blade.php) --}}
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
      duration: 1000, 
      easing: 'ease-out-quad',
      once: true,
      mirror: false,
  });
</script>
@endsection
<head>
    {{-- ... (semua tag head Anda yang lain) ... --}}
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        /* Import Font Poppins */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

        #about {
            font-family: 'Poppins', sans-serif;
        }

        /* 1. EFEK KACA (GLASSMORPHISM) */
        .glass-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 1.25rem; /* rounded-2xl */
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.15);
        }

        /* Ikon untuk kartu keunggulan */
        .feature-icon {
            width: 3.5rem; /* Sedikit lebih kecil */
            height: 3.5rem; /* Sedikit lebih kecil */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem; /* Jarak lebih kecil */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        .icon-green {
            background: linear-gradient(135deg, #a7f3d0, #6ee7b7);
            color: #065f46; /* emerald-800 */
        }
        .icon-blue {
            background: linear-gradient(135deg, #bae6fd, #7dd3fc);
            color: #0c4a6e; /* sky-800 */
        }
        .icon-teal {
            background: linear-gradient(135deg, #99f6e4, #5eead4);
            color: #115e59; /* teal-800 */
        }
        
        /* Animasi untuk ikon di Misi */
        @keyframes bounce-icon {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); } /* Sedikit lebih kecil */
        }
        .group-hover\:animate-bounce-icon:hover .feature-icon {
            animation: bounce-icon 0.8s ease-in-out;
        }


        /* 2. ANIMASI BLOB (LEBIH HALUS & LEBIH TIPIS) */
        .animated-blob {
            position: absolute;
            width: 450px; /* Sedikit lebih kecil */
            height: 450px; /* Sedikit lebih kecil */
            background: linear-gradient(180deg, rgba(52, 211, 153, 0.15) 0%, rgba(59, 130, 246, 0.15) 100%); /* Opacity lebih rendah */
            border-radius: 50%;
            filter: blur(100px); /* Blur sedikit lebih rendah */
            z-index: 1;
            opacity: 0.6; /* Opacity lebih rendah */
            animation: morph-and-float 22s infinite ease-in-out; /* Durasi lebih lama */
            pointer-events: none;
        }

        @keyframes morph-and-float {
            0% {
                transform: translate(0, 0) scale(1);
                border-radius: 50% 50% 50% 50%;
            }
            25% {
                transform: translate(120px, 60px) scale(1.05); /* Gerakan & skala lebih halus */
                border-radius: 60% 40% 70% 30%;
            }
            50% {
                transform: translate(50px, -100px) scale(0.95); /* Gerakan & skala lebih halus */
                border-radius: 40% 60% 30% 70%;
            }
            75% {
                transform: translate(-60px, -60px) scale(1); /* Gerakan & skala lebih halus */
                border-radius: 50% 50% 60% 40%;
            }
            100% {
                transform: translate(0, 0) scale(1);
                border-radius: 50% 50% 50% 50%;
            }
        }
        
        .section-title-glow {
            text-shadow: 0 0 20px rgba(52, 211, 153, 0.2); /* Glow lebih halus */
        }

        /* 3. Carousel */
        .carousel-arrow {
            background: rgba(255, 255, 255, 0.7); /* Lebih transparan */
            border: 1px solid rgba(255, 255, 255, 0.2); /* Border lebih tipis */
            box-shadow: 0 1px 8px rgba(0,0,0,0.08); /* Shadow lebih halus */
            color: #34d399; /* emerald-500 */
            transition: all 0.2s ease;
        }
        .carousel-arrow:hover {
            background: #34d399; /* emerald-500 */
            color: white;
            box-shadow: 0 3px 12px rgba(0,0,0,0.15); /* Shadow lebih menonjol saat hover */
        }
        .carousel-dot {
            background-color: rgba(255,255,255,0.6);
            transition: all 0.2s ease;
        }
        .carousel-dot.active {
            background-color: #34d399; /* emerald-500 */
            width: 1.25rem;
        }

    </style>
</head>
<body>

{{-- ====================================== --}}
{{-- ⬇️ AWAL KODE YANG DIPERBARUI ⬇️ --}}
{{-- ====================================== --}}
<section id="about" class="py-16 md:py-24 bg-gradient-to-br from-emerald-50 via-white to-blue-50 relative overflow-hidden font-poppins">
    
    {{-- Blob disembunyikan di mobile (lg:block) --}}
    <div class="animated-blob hidden lg:block" style="top: 10%; left: -10%;"></div>
    <div class="animated-blob hidden lg:block" style="bottom: 5%; right: -15%; animation-delay: -10s; transform: scale(0.8);"></div>
    
    <div class="container mx-auto px-6 relative z-10">
        
        {{-- 1. Judul & Subjudul --}}
        <div class="text-center max-w-3xl mx-auto mb-10 md:mb-14" data-aos="fade-up">
            <h2 class="text-3xl sm:text-4xl font-extrabold mb-3 text-slate-900">
                Mengenal Lebih Dekat 
                <span class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 bg-clip-text text-transparent section-title-glow">
                    Koperasi SMKN 8
                </span>
            </h2>
            <p class="text-base md:text-lg text-slate-600 leading-relaxed">
                Unit usaha sekolah yang berdedikasi untuk kemajuan siswa dan kemudahan seluruh warga sekolah.
            </p>
        </div>

        {{-- 2. Teks Deskripsi & Carousel Gambar --}}
        <div class="grid lg:grid-cols-2 items-center gap-10 lg:gap-14 mb-14 md:mb-16">
            
            {{-- Kolom Teks Deskripsi --}}
            <div class="space-y-4" data-aos="fade-right" data-aos-delay="200">
                <p class="text-slate-700 text-base md:text-lg leading-relaxed">
                    <span class="font-bold text-emerald-700">Koperasi SMKN 8</span> adalah unit usaha sekolah yang berdedikasi menyediakan 
                    <span class="font-semibold text-teal-700">seragam dan atribut sekolah</span> berkualitas bagi siswa dan guru. 
                    Kami hadir untuk memudahkan pemenuhan kebutuhan perlengkapan sekolah dengan harga terjangkau.
                </p>
                <p class="text-slate-700 text-base md:text-lg leading-relaxed">
                    Dengan semangat <span class="font-semibold text-teal-700">kemandirian</span> dan 
                    <span class="font-semibold text-emerald-700">kewirausahaan</span>, koperasi ini juga menjadi sarana belajar praktis bagi siswa dalam mengelola usaha, sambil mendukung terciptanya lingkungan sekolah yang rapi dan disiplin.
                </p>
            </div>
            
            {{-- Kolom Carousel Gambar (FIXED Double Box) --}}
            <div data-aos="fade-left" data-aos-delay="400">
                <div x-data="{ 
                        activeSlide: 0, 
                        slides: [
                            '{{ asset('images/atas.jpeg') }}', 
                            '{{ asset('images/atasss.jpeg') }}', 
                            '{{ asset('images/atassss.jpeg') }}'
                        ],
                        intervalId: null,
                        startAutoplay() {
                            {{-- [UPDATE] Durasi ganti gambar menjadi 3000ms (3 detik) --}}
                            this.intervalId = setInterval(() => {
                                this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                            }, 3000); 
                        },
                        stopAutoplay() {
                            clearInterval(this.intervalId);
                        },
                        nextSlide() {
                            this.stopAutoplay();
                            this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                            this.startAutoplay();
                        },
                        prevSlide() {
                            this.stopAutoplay();
                            this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
                            this.startAutoplay();
                        }
                    }" 
                    x-init="startAutoplay()" 
                    @mouseover="stopAutoplay()" 
                    @mouseleave="startAutoplay()"
                    class="relative rounded-2xl shadow-xl overflow-hidden h-[280px] sm:h-[350px] border border-gray-100"> 
                    
                    {{-- Gambar Carousel --}}
                    <template x-for="(slide, index) in slides" :key="index">
                        <img :src="slide" 
                             alt="Koperasi SMKN 8" 
                             x-show="activeSlide === index" 
                             x-transition:enter="transition ease-out duration-700"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-300 absolute w-full h-full object-cover"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="absolute w-full h-full object-cover">
                    </template>

                    {{-- Navigasi Panah --}}
                    <button @click="prevSlide()" 
                            class="absolute top-1/2 left-4 transform -translate-y-1/2 rounded-full w-9 h-9 flex items-center justify-center carousel-arrow opacity-0 group-hover:opacity-100 transition-opacity">
                        <i class="fas fa-chevron-left text-lg"></i>
                    </button>
                    <button @click="nextSlide()" 
                            class="absolute top-1/2 right-4 transform -translate-y-1/2 rounded-full w-9 h-9 flex items-center justify-center carousel-arrow opacity-0 group-hover:opacity-100 transition-opacity">
                        <i class="fas fa-chevron-right text-lg"></i>
                    </button>

                    {{-- Indikator Titik --}}
                    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                        <template x-for="(slide, index) in slides" :key="index">
                            <button @click="activeSlide = index; stopAutoplay(); startAutoplay();" 
                                    class="h-2 rounded-full carousel-dot"
                                    :class="{ 'w-6 active': activeSlide === index, 'w-2': activeSlide !== index }"></button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        {{-- Seksi Misi Kami --}}
        <div class="mb-14 md:mb-16 text-center max-w-4xl mx-auto" data-aos="fade-up" data-aos-delay="600">
            <h3 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-6">Misi Kami</h3>
            <ul class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
                <li class="glass-card p-5 flex flex-col items-center justify-center group transform hover:scale-105 transition-transform duration-300 group-hover:animate-bounce-icon">
                    <div class="feature-icon icon-teal">
                        <i class="fas fa-handshake text-xl"></i>
                    </div>
                    <p class="text-gray-700 font-semibold text-center text-sm mt-2">Meningkatkan kesejahteraan warga sekolah.</p>
                </li>
                <li class="glass-card p-5 flex flex-col items-center justify-center group transform hover:scale-105 transition-transform duration-300 group-hover:animate-bounce-icon" data-aos-delay="100">
                    <div class="feature-icon icon-blue">
                        <i class="fas fa-lightbulb text-xl"></i>
                    </div>
                    <p class="text-gray-700 font-semibold text-center text-sm mt-2">Mengembangkan potensi wirausaha siswa.</p>
                </li>
                <li class="glass-card p-5 flex flex-col items-center justify-center group transform hover:scale-105 transition-transform duration-300 group-hover:animate-bounce-icon" data-aos-delay="200">
                    <div class="feature-icon icon-green">
                        <i class="fas fa-seedling text-xl"></i>
                    </div>
                    <p class="text-gray-700 font-semibold text-center text-sm mt-2">Menciptakan lingkungan sekolah yang mandiri.</p>
                </li>
            </ul>
        </div>


        {{-- Seksi Keunggulan Kami --}}
        <div class="mb-10">
            <h3 class="text-2xl sm:text-3xl font-bold text-slate-800 text-center mb-8 md:mb-10" data-aos="fade-up">
                Mengapa Memilih Kami?
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="glass-card p-5 pt-7 text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex justify-center">
                        <div class="feature-icon icon-green">
                            <i class="fas fa-gem text-xl"></i>
                        </div>
                    </div>
                    <h4 class="font-bold text-lg mb-2 text-emerald-800">Kualitas Prima</h4>
                    <p class="text-gray-600 text-sm">Produk pilihan dengan bahan terbaik, nyaman, dan tahan lama untuk aktivitas sekolah.</p>
                </div>
                
                <div class="glass-card p-5 pt-7 text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex justify-center">
                        <div class="feature-icon icon-blue">
                            <i class="fas fa-wallet text-xl"></i>
                        </div>
                    </div>
                    <h4 class="font-bold text-lg mb-2 text-blue-800">Harga Ramah Kantong</h4>
                    <p class="text-gray-600 text-sm">Harga sangat bersahabat, dirancang untuk mendukung kebutuhan seluruh siswa tanpa memberatkan.</p>
                </div>
                
                <div class="glass-card p-5 pt-7 text-center" data-aos="fade-up" data-aos-delay="500">
                    <div class="flex justify-center">
                        <div class="feature-icon icon-teal">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                    </div>
                    <h4 class="font-bold text-lg mb-2 text-teal-800">Inovasi & Pembelajaran</h4>
                    <p class="text-gray-600 text-sm">Mewujudkan lingkungan belajar wirausaha yang dinamis dan berorientasi pada masa depan.</p>
                </div>
                
            </div>
        </div>

        {{-- Bagian Kontak Singkat --}}
        <div class="text-center mt-14 md:mt-20" data-aos="fade-up" data-aos-delay="700">
            <h3 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-6">Hubungi Kami</h3>
            <div class="glass-card inline-flex flex-col sm:flex-row items-center justify-center p-5 sm:p-8 gap-5 sm:gap-10">
                <a href="tel:+6281234567890" class="flex items-center gap-3 text-gray-700 hover:text-emerald-600 transition-colors duration-300">
                    <div class="p-2.5 rounded-full bg-emerald-100 text-emerald-600 shadow-sm">
                        <i class="fas fa-phone-alt text-base"></i>
                    </div>
                    <span class="font-semibold text-sm">+62 812 3456 7890</span>
                </a>
                <a href="mailto:koperasi@smkn8jakarta.sch.id" class="flex items-center gap-3 text-gray-700 hover:text-emerald-600 transition-colors duration-300">
                    <div class="p-2.5 rounded-full bg-emerald-100 text-emerald-600 shadow-sm">
                        <i class="fas fa-envelope text-base"></i>
                    </div>
                    <span class="font-semibold text-sm">koperasi@smkn8jakarta.sch.id</span>
                </a>
            </div>
        </div>
        
    </div>
</section>

{{-- ====================================== --}}
{{-- ⬆️ AKHIR KODE YANG DIPERBARUI ⬆️ --}}
{{-- ====================================== --}}


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
<head>
    {{-- ... (semua tag head Anda yang lain) ... --}}
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        /* Import Font Poppins */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }

        /* ========================================
        🔹 EFEK KACA (GLASSMORPHISM)
        ========================================
        */
        .glass-card {
            background: rgba(255, 255, 255, 0.6); /* Latar semi-transparan */
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

        /* Glow Judul Section */
        .section-title-glow {
            text-shadow: 0 0 30px rgba(52, 211, 153, 0.3);
        }
        
        /* ========================================
        🔹 ALUR PEMESANAN (BARU)
        ========================================
        */
        .step-card {
            background-color: #fff;
            border-radius: 1.25rem; /* rounded-2xl */
            padding: 1.5rem; /* p-6 */
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 4px 6px -4px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%; /* Bikin kartu sama tinggi */
        }
        .step-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 30px -10px rgba(0, 128, 128, 0.1); /* Shadow teal halus */
        }
        .step-icon-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 4rem; /* w-16 */
            height: 4rem; /* h-16 */
            border-radius: 50%;
            margin-bottom: 1.25rem; /* mb-5 */
        }
        .step-icon-bg {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            opacity: 0.15; /* Latar belakang transparan */
            position: absolute;
        }
        .step-icon-number {
            position: absolute;
            top: -0.25rem; /* -top-1 */
            right: -0.25rem; /* -right-1 */
            width: 1.5rem; /* w-6 */
            height: 1.5rem; /* h-6 */
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem; /* text-xs */
            font-weight: 700; /* font-bold */
            color: white;
            background: #2563eb; /* bg-blue-600 */
            border-radius: 50%;
            border: 2px solid white;
        }
        
        /* Garis Penghubung (dari screenshot) */
        .step-container {
            position: relative;
        }
        @media (min-width: 1024px) { /* Hanya di 'lg' ke atas */
            .step-container:not(:last-child)::after {
                content: '';
                position: absolute;
                top: 2rem; /* Sejajar tengah ikon */
                right: -2.5rem; /* (gap-10 / 2) */
                width: 2.5rem; /* Panjang garis = gap */
                height: 2px;
                background-image: linear-gradient(to right, #cbd5e1 50%, transparent 50%); /* Garis putus-putus */
                background-size: 8px 2px;
                background-repeat: repeat-x;
                opacity: 0.8;
                z-index: 5;
            }
        }

    </style>
</head>
<body>

{{-- ====================================== --}}
{{-- ⬇️ SEKSI #FEATURES (DESAIN DIUBAH) ⬇️ --}}
{{-- ====================================== --}}
<section class="py-16 md:py-24 bg-slate-50 relative overflow-hidden font-poppins" id="features"> 
    
    {{-- Latar Belakang Aurora --}}
    <div class="aurora-background">
        <div class="aurora-blob aurora-blob-1"></div>
        <div class="aurora-blob aurora-blob-2"></div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-14 md:mb-16">
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4 tracking-tight" data-aos="fade-up">
                Mengapa Memilih 
                <span class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 bg-clip-text text-transparent section-title-glow">
                    Koperasi SMKN 8?
                </span>
            </h2>
            <p class="text-slate-600 max-w-3xl mx-auto text-base md:text-lg" data-aos="fade-up" data-aos-delay="100">
                Tempat terpercaya untuk memenuhi kebutuhan seragam dan atribut sekolah bagi seluruh siswa dan guru.
            </p>
        </div>

        {{-- [DIUBAH] Menggunakan .glass-card, BUKAN .card-border-spin --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div class="glass-card" data-aos="fade-up" data-aos-delay="200">
                <div class="p-7 text-center">
                    <div class="flex justify-center mb-6">
                        <div class="feature-icon icon-green">
                            <i class="fas fa-tshirt text-3xl"></i>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-emerald-800">Seragam Sekolah</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Menyediakan seragam harian, olahraga, pramuka, dan lainnya sesuai kebutuhan siswa SMKN 8.
                    </p>
                </div>
            </div>

            <div class="glass-card" data-aos="fade-up" data-aos-delay="300">
                 <div class="p-7 text-center">
                    <div class="flex justify-center mb-6">
                        <div class="feature-icon icon-blue">
                            <i class="fas fa-user-shield text-3xl"></i>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-blue-800">Atribut Resmi</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Tersedia atribut resmi seperti topi, dasi, ikat pinggang, badge, dan perlengkapan lainnya.
                    </p>
                </div>
            </div>

            <div class="glass-card" data-aos="fade-up" data-aos-delay="400">
                 <div class="p-7 text-center">
                    <div class="flex justify-center mb-6">
                        <div class="feature-icon icon-teal">
                            <i class="fas fa-check-circle text-3xl"></i>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-teal-800">Kualitas Terjamin</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Produk koperasi terstandarisasi, nyaman dipakai, dan mendukung kegiatan belajar mengajar.
                    </p>
                </div>
            </div>
            
        </div>
    </div>
</section>

{{-- ====================================== --}}
{{-- ⬇️ [TAMBAHAN] SEKSI ALUR PEMESANAN ⬇️ --}}
{{-- ====================================== --}}

<section id="how-it-works" class="py-16 md:py-24 bg-white relative overflow-hidden font-poppins">
    
    {{-- Latar Belakang Titik-titik (Dot Grid) --}}
    <div class="absolute inset-0 z-0 opacity-40" 
         style="background-image: radial-gradient(#d1d5db 1px, transparent 1px); background-size: 1.5rem 1.5rem;">
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        
        {{-- Judul Seksi --}}
        <div class="text-center max-w-3xl mx-auto mb-12 md:mb-16" data-aos="fade-up">
            <h2 class="text-3xl sm:text-4xl font-extrabold mb-3 text-slate-900">
                Alur Pemesanan
            </h2>
            <p class="text-base md:text-lg text-slate-600 leading-relaxed">
                Hanya butuh 4 langkah mudah untuk mendapatkan perlengkapan sekolah Anda.
            </p>
        </div>

        {{-- Grid 4 Langkah (Sesuai Screenshot) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-y-10 lg:gap-10">
            
            {{-- Langkah 1: Pilih Produk --}}
            <div class="step-container text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="step-card">
                    <div class="flex justify-center">
                        <div class="step-icon-wrapper">
                            <div class="step-icon-bg bg-emerald-500"></div>
                            <i class="fas fa-search text-3xl text-emerald-600"></i>
                            <div class="step-icon-number">1</div>
                        </div>
                    </div>
                    <h4 class="font-bold text-lg mb-2 text-emerald-800">1. Pilih Produk</h4>
                    <p class="text-gray-600 text-sm">Jelajahi katalog, temukan produk, dan pastikan ukuran sudah sesuai.</p>
                </div>
            </div>
            
            {{-- Langkah 2: Checkout --}}
            <div class="step-container text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="step-card">
                    <div class="flex justify-center">
                        <div class="step-icon-wrapper">
                            <div class="step-icon-bg bg-blue-500"></div>
                            <i class="fas fa-shopping-cart text-3xl text-blue-600"></i>
                            <div class="step-icon-number">2</div>
                        </div>
                    </div>
                    <h4 class="font-bold text-lg mb-2 text-blue-800">2. Checkout & Bayar</h4>
                    <p class="text-gray-600 text-sm">Pilih metode pembayaran (QRIS, Transfer, KJP, atau Cash).</p>
                </div>
            </div>

            {{-- Langkah 3: Konfirmasi --}}
            <div class="step-container text-center" data-aos="fade-up" data-aos-delay="300">
                <div class="step-card">
                    <div class="flex justify-center">
                        <div class="step-icon-wrapper">
                            <div class="step-icon-bg bg-cyan-500"></div>
                            <i class="fas fa-receipt text-3xl text-cyan-600"></i>
                            <div class="step-icon-number">3</div>
                        </div>
                    </div>
                    <h4 class="font-bold text-lg mb-2 text-cyan-800">3. Tunggu Konfirmasi</h4>
                    <p class="text-gray-600 text-sm">Admin akan memverifikasi pembayaran Anda dan menyiapkan pesanan.</p>
                </div>
            </div>
            
            {{-- Langkah 4: Ambil --}}
            <div class="step-container text-center" data-aos="fade-up" data-aos-delay="400">
                <div class="step-card">
                    <div class="flex justify-center">
                        <div class="step-icon-wrapper">
                            <div class="step-icon-bg bg-teal-500"></div>
                            <i class="fas fa-box-open text-3xl text-teal-600"></i>
                            <div class="step-icon-number">4</div>
                        </div>
                    </div>
                    <h4 class="font-bold text-lg mb-2 text-teal-800">4. Ambil di Koperasi</h4>
                    <p class="text-gray-600 text-sm">Datang ke koperasi dan tunjukkan bukti pesanan Anda untuk mengambil barang.</p>
                </div>
            </div>
            
        </div>
        
    </div>
</section>

{{-- ====================================== --}}
{{-- ⬆️ AKHIR SEKSI BARU ⬆️ --}}
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
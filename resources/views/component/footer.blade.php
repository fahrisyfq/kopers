<footer class="bg-slate-900 text-white shadow-inner font-sans
               bg-gradient-to-r from-slate-900 via-emerald-900/10 to-slate-900">
    
    <div class="container mx-auto px-4 py-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-8">
            
            <div class="md:col-span-1">
                <div class="flex items-center space-x-3 mb-4">
                    <img src="/images/logo.jpg" alt="Logo Koperasi SMKN 8"
                        class="h-12 w-12 rounded-full border-2 border-emerald-400 shadow bg-white object-cover">
                    <div>
                        {{-- Warna diubah ke emerald/white agar kontras --}}
                        <h3 class="text-lg font-bold tracking-wide text-emerald-300">KOPERASI <span class="text-white">SMKN 8</span></h3>
                        <span class="text-xs text-emerald-100">Bersama, Berdaya, Berkarya!</span>
                    </div>
                </div>
                <p class="text-slate-300 text-sm leading-relaxed mb-6">
                    Pusat kebutuhan sekolah & simpan pinjam untuk siswa dan guru.
                </p>
                <div class="flex space-x-4 mt-3">
                    <a href="#" class="text-slate-400 hover:text-emerald-400 transition text-xl"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-slate-400 hover:text-emerald-400 transition text-xl"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-slate-400 hover:text-emerald-400 transition text-xl"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-slate-400 hover:text-emerald-400 transition text-xl"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>

            <div class="md:col-span-1 grid grid-cols-2 gap-8">
                <div>
                    <h4 class="text-base font-semibold mb-4 text-emerald-300">Navigasi</h4>
                    <ul class="space-y-2 text-sm mt-1">
                        <li><a href="/" class="flex items-center gap-2 text-slate-300 hover:text-white"><i class="fas fa-home fa-fw"></i>Beranda</a></li>
                        <li><a href="#products" class="flex items-center gap-2 text-slate-300 hover:text-white"><i class="fas fa-box-open fa-fw"></i>Produk</a></li>
                        <li><a href="#contact" class="flex items-center gap-2 text-slate-300 hover:text-white"><i class="fas fa-envelope fa-fw"></i>Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-base font-semibold mb-4 text-emerald-300">Produk Populer</h4>
                    <ul class="space-y-2 text-sm mt-1">
                        <li><a href="#" class="flex items-center gap-2 text-slate-300 hover:text-white"><i class="fas fa-user-tie fa-fw text-emerald-400/50"></i>Seragam</a></li>
                        <li><a href="#" class="flex items-center gap-2 text-slate-300 hover:text-white"><i class="fas fa-graduation-cap fa-fw text-emerald-400/50"></i>Topi & Gesper</a></li>
                        <li><a href="#" class="flex items-center gap-2 text-slate-300 hover:text-white"><i class="fas fa-user-shield fa-fw text-emerald-400/50"></i>Dasi</a></li>
                    </ul>
                </div>
            </div>

            <div class="md:col-span-1">
                <h4 class="text-base font-semibold mb-4 text-emerald-300">Operasional</h4>
                <div class="space-y-2 text-sm mb-5">
                    <p class="text-slate-300 flex items-center"><i class="fas fa-clock fa-fw mr-2 text-emerald-400/50"></i>Senin-Jumat 07.00-15.00</p>
                    <p class="text-slate-300 flex items-center"><i class="fas fa-calendar-times fa-fw mr-2 text-emerald-400/50"></i>Libur nasional tutup</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 pb-12">
        <div class="border-t border-slate-700 pt-12">
            
            <h5 class="text-base font-semibold text-emerald-300 text-center mb-6 uppercase tracking-wider">
                Sistem Info
            </h5>

            <div class="light-button flex justify-center flex-wrap gap-4 md:gap-8 items-center">
                
                {{-- Tombol Biru (Info) --}}
                <button class="bt bt-blue">
                    <div class="button-holder">
                        <i class="fas fa-code text-2xl"></i>
                        <span class="text-sm mt-1">v1.0</span>
                    </div>
                    <div class="light-holder">
                        <div class="dot"></div>
                        <div class="light"></div>
                    </div>
                </button>

                {{-- Tombol Biru (Info) --}}
                <button class="bt bt-blue">
                    <div class="button-holder">
                        <i class="fas fa-database text-2xl"></i>
                        <span class="text-sm mt-1">PostgreSQL</span>
                    </div>
                    <div class="light-holder">
                        <div class="dot"></div>
                        <div class="light"></div>
                    </div>
                </button>

                {{-- Tombol Biru (Info) --}}
                <button class="bt bt-blue">
                    <div class="button-holder">
                        <i class="fab fa-laravel text-2xl"></i>
                        <span class="text-sm mt-1">Laravel 12</span>
                    </div>
                    <div class="light-holder">
                        <div class="dot"></div>
                        <div class="light"></div>
                    </div>
                </button>

                {{-- Tombol Hijau (Status) --}}
                <button class="bt bt-green">
                    <div class="button-holder">
                        {{-- Mengganti ikon 'signal' dengan 'globe' yang lebih modern --}}
                        <i class="fas fa-globe text-2xl"></i> 
                        <span class="text-sm mt-1">Online</span>
                    </div>
                    <div class="light-holder">
                        <div class="dot"></div>
                        <div class="light"></div>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <div class="bg-black/20 py-6">
        <div class="container mx-auto px-4 flex flex-col md:flex-row justify-between items-center text-sm text-slate-400">
            <p class="mb-2 md:mb-0">
                © 2025 Koperasi SMKN 8 Jakarta. All rights reserved.
            </p>
            <div class="flex space-x-4">
                <a href="#" class="hover:text-white transition">Privasi</a>
                <span>|</span>
                <a href="#" class="hover:text-white transition">Syarat</a>
                <span>|</span>
                <a href="#" class="hover:text-white transition">FAQ</a>
            </div>
        </div>
    </div>
</footer>

<style>
.light-button button.bt {
    position: relative;
    /* PERUBAHAN: Tinggi dikecilkan agar lebih compact */
    height: 120px; 
    display: flex;
    align-items: flex-end;
    outline: none;
    background: none;
    border: none;
    cursor: default; /* Ubah ke default karena ini hanya display info */
}
.light-button button.bt .button-holder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    /* PERUBAHAN: Ukuran box dikecilkan */
    height: 80px; 
    width: 80px;
    /* PERUBAHAN: Warna background disesuaikan dengan tema gelap */
    background-color: #1e293b; /* slate-800 */
    border-radius: 10px;
    color: #cbd5e1; /* slate-300 */
    font-weight: 600;
    transition: 300ms;
    /* PERUBAHAN: Outline disesuaikan */
    outline: #334155 2px solid; /* slate-700 */
    outline-offset: 20;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
}
.light-button button.bt .button-holder i {
    color: #cbd5e1; /* slate-300 */
    transition: 300ms;
}
.light-button button.bt .light-holder {
    position: absolute;
    /* PERUBAHAN: Tinggi & Lebar disesuaikan */
    height: 120px;
    width: 80px; 
    display: flex;
    flex-direction: column;
    align-items: center;
}
.light-button button.bt .light-holder .dot {
    position: absolute;
    top: 0;
    width: 10px;
    height: 10px;
    border-radius: 10px;
    z-index: 2;
    animation: blink 2s infinite ease-in-out;
}
.light-button button.bt .light-holder .light {
    position: absolute;
    top: 0;
    /* PERUBAHAN: Lebar disesuaikan */
    width: 160px; 
    height: 120px;
    clip-path: polygon(50% 0%, 25% 100%, 75% 100%);
    background: transparent;
    transition: background 300ms ease;
}

/* Animasi Blink (Sedikit dihaluskan) */
@keyframes blink {
    0%, 100% { opacity: 0.6; }
    50% { opacity: 1; }
}

/* === PERUBAHAN: Warna Biru (Info) === */
.light-button button.bt-blue .dot {
    background-color: #60a5fa; /* blue-400 */
    box-shadow: 0 0 15px #60a5fa;
}
.light-button button.bt-blue:hover .button-holder {
    color: #93c5fd; /* blue-300 */
    outline: #60a5fa 2px solid;
    outline-offset: 4px;
    box-shadow: 0 0 25px rgba(96, 165, 250, 0.5);
}
.light-button button.bt-blue:hover .button-holder i {
    color: #93c5fd; /* blue-300 */
}
.light-button button.bt-blue:hover .light-holder .light {
    background: linear-gradient(180deg, rgba(96, 165, 250, 0.7) 0%, rgba(255, 255, 255, 0) 90%);
}

/* === PERUBAHAN: Warna Hijau (Status Online) === */
.light-button button.bt-green .dot {
    background-color: #34d399; /* emerald-400 */
    box-shadow: 0 0 15px #34d399;
}
.light-button button.bt-green:hover .button-holder {
    color: #6ee7b7; /* emerald-300 */
    outline: #34d399 2px solid;
    outline-offset: 4px;
    box-shadow: 0 0 25px rgba(52, 211, 153, 0.6);
}
.light-button button.bt-green:hover .button-holder i {
    color: #6ee7b7; /* emerald-300 */
}
.light-button button.bt-green:hover .light-holder .light {
    background: linear-gradient(180deg, rgba(52, 211, 153, 0.8) 0%, rgba(255, 255, 255, 0) 90%);
}

</style>
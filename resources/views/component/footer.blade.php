<footer x-data="{ devModalOpen: false }" class="relative bg-[#0f172a] text-slate-300 font-sans overflow-hidden border-t border-slate-800">
    
    {{-- BACKGROUND & AMBIENT LIGHT --}}
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-[#0B1120] to-black opacity-90"></div>
    <div class="absolute top-0 left-1/4 w-64 h-64 bg-emerald-500/5 blur-[80px] rounded-full pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-64 h-64 bg-blue-600/5 blur-[80px] rounded-full pointer-events-none"></div>

    <div class="relative container mx-auto px-6 pt-16 pb-8 z-10">
        
        {{-- GRID UTAMA --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8 mb-16 text-center md:text-left">
            
            {{-- KOLOM 1: Brand --}}
            <div class="flex flex-col items-center md:items-start">
                <div class="flex items-center space-x-3 mb-5 group cursor-default">
                    <div class="relative">
                        <div class="absolute -inset-1 bg-gradient-to-r from-emerald-500 to-blue-500 rounded-full blur opacity-25 group-hover:opacity-50 transition duration-500"></div>
                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="relative h-12 w-12 rounded-full border-2 border-slate-800 bg-slate-900 object-cover">
                    </div>
                    <div class="flex flex-col items-start">
                        <h3 class="text-lg font-black tracking-wider text-white leading-none">KOPERASI</h3>
                        <span class="text-[10px] font-bold text-emerald-400 tracking-[0.2em]">SMKN 8 JAKARTA</span>
                    </div>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed mb-6 max-w-xs mx-auto md:mx-0">
                    Penyedia resmi seragam, atribut, dan perlengkapan sekolah berkualitas untuk mendukung kegiatan belajar siswa.
                </p>
                <div class="flex gap-3 justify-center md:justify-start">
                    <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" class="social-btn"><i class="far fa-envelope"></i></a>
                </div>
            </div>

            {{-- KOLOM 2: Menu --}}
            <div>
                <h4 class="text-white font-bold uppercase tracking-wider mb-5 border-b-2 border-emerald-500/30 inline-block pb-1">Menu</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="/" class="footer-link justify-center md:justify-start">Beranda</a></li>
                    <li><a href="{{ route('product.index') }}" class="footer-link justify-center md:justify-start">Katalog Seragam</a></li>
                    <li><a href="{{ route('cart.index') }}" class="footer-link justify-center md:justify-start">Keranjang</a></li>
                    <li><a href="{{ route('kontak') }}" class="footer-link justify-center md:justify-start">Kontak Kami</a></li>
                </ul>
            </div>

            {{-- KOLOM 3: Lokasi --}}
            <div>
                <h4 class="text-white font-bold uppercase tracking-wider mb-5 border-b-2 border-emerald-500/30 inline-block pb-1">Lokasi</h4>
                <ul class="space-y-4 text-sm">
                    <li class="flex flex-col md:flex-row gap-2 md:gap-3 items-center md:items-start">
                        <i class="fas fa-map-marker-alt text-emerald-500 mt-1"></i>
                        <span class="text-slate-400">Jl. Pejaten Raya, Pejaten Barat, Ps. Minggu, Jakarta Selatan.</span>
                    </li>
                    <li class="flex flex-col md:flex-row gap-2 md:gap-3 items-center md:items-start">
                        <i class="far fa-clock text-emerald-500 mt-1"></i>
                        <div class="flex flex-col">
                            <span class="text-white font-medium">Jam Operasional</span>
                            <span class="text-slate-500 text-xs">Senin - Jumat (07.00 - 15.00)</span>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- KOLOM 4: Sistem Info (Light Buttons) --}}
            <div class="flex flex-col items-center lg:items-start">
                <h4 class="text-white font-bold uppercase tracking-wider mb-5 border-b-2 border-emerald-500/30 inline-block pb-1">Sistem Info</h4>
                
                <div class="light-button flex flex-wrap justify-center lg:justify-start gap-4">
                    
                    {{-- Laravel --}}
                    <button class="bt bt-blue cursor-default scale-90">
                        <div class="light-holder">
                            <div class="dot"></div>
                            <div class="light"></div>
                        </div>
                        <div class="button-holder">
                            <i class="fab fa-laravel text-xl mb-1"></i>
                            <span class="text-[9px] font-bold uppercase">Laravel</span>
                        </div>
                    </button>

                    {{-- Database --}}
                    <button class="bt bt-blue cursor-default scale-90">
                        <div class="light-holder">
                            <div class="dot"></div>
                            <div class="light"></div>
                        </div>
                        <div class="button-holder">
                            <i class="fas fa-database text-lg mb-1"></i>
                            <span class="text-[9px] font-bold uppercase">V1.0</span>
                        </div>
                    </button>

                    {{-- Online (KEDIP HIJAU AKTIF) --}}
                    <button class="bt bt-green cursor-default scale-90">
                        <div class="light-holder">
                            <div class="dot"></div> {{-- Ini yang kedip --}}
                            <div class="light"></div>
                        </div>
                        <div class="button-holder">
                            <i class="fas fa-globe text-lg mb-1"></i>
                            <span class="text-[9px] font-bold uppercase">Online</span>
                        </div>
                    </button>

                </div>
            </div>
        </div>

        {{-- BARIS BAWAH --}}
        <div class="border-t border-slate-800/60 pt-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-slate-500 text-xs text-center md:text-left">
                &copy; {{ date('Y') }} <strong class="text-slate-300">Koperasi SMKN 8 Jakarta</strong>. Hak Cipta Dilindungi.
            </p>
            
            {{-- Developer Button --}}
            <button @click="devModalOpen = true" 
                    class="group relative flex items-center gap-3 bg-slate-900 border border-slate-700 hover:border-emerald-500/50 rounded-full py-1.5 pl-1.5 pr-4 transition-all duration-300 hover:shadow-[0_0_15px_rgba(16,185,129,0.15)]">
                <div class="flex -space-x-3">
                    {{-- Foto Dev 1 --}}
                    <img src="images/linggam.png" class="w-7 h-7 rounded-full border-2 border-slate-900 object-cover relative z-10 group-hover:scale-110 transition-transform">
                    {{-- Foto Dev 2 --}}
                    <img src="images/fahri.jpeg" class="w-7 h-7 rounded-full border-2 border-slate-900 object-cover relative z-0 group-hover:translate-x-2 transition-transform">
                </div>
                <div class="flex flex-col items-start text-left">
                    <span class="text-[9px] uppercase text-slate-500 font-bold tracking-wider group-hover:text-emerald-400 transition-colors">Developed by</span>
                    <span class="text-[10px] text-slate-300 font-medium">Tim IT SMKN 8 JKT</span>
                </div>
            </button>
        </div>
    </div>

    {{-- ================================================== --}}
    {{-- MODAL DEVELOPER (POP-UP) --}}
    {{-- ================================================== --}}
    <div x-show="devModalOpen" 
         class="fixed inset-0 z-[100] flex items-center justify-center px-4"
         style="display: none;" x-cloak>
        
        <div x-show="devModalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="devModalOpen = false"
             class="absolute inset-0 bg-black/80 backdrop-blur-sm">
        </div>

        <div x-show="devModalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90 translate-y-10"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-90 translate-y-10"
             class="relative bg-[#0f172a] border border-slate-700 rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden">
            
            {{-- Hiasan Atas --}}
            <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-b from-slate-800 to-transparent opacity-50 pointer-events-none"></div>
            <button @click="devModalOpen = false" class="absolute top-4 right-4 text-slate-400 hover:text-white z-20 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>

            <div class="p-8 relative z-10">
                <h3 class="text-2xl font-bold text-white text-center mb-1">Meet the Developers</h3>
                <p class="text-slate-500 text-center text-xs mb-10 uppercase tracking-widest">Tim RPL - Koperasi SMKN 8 Jakarta</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    
                    {{-- DEVELOPER 1 --}}
                    <div class="relative group p-6 rounded-2xl bg-slate-800/40 border border-slate-700 hover:border-emerald-500/50 hover:bg-slate-800/70 transition-all duration-300 text-center">
                        <div class="absolute -top-6 left-1/2 -translate-x-1/2">
                            <div class="relative">
                                <div class="absolute inset-0 bg-emerald-500 blur-md rounded-full opacity-0 group-hover:opacity-50 transition-opacity"></div>
                                {{-- FOTO --}}
                                <img src="images/linggam.png" class="relative w-20 h-20 rounded-full border-4 border-[#0f172a] object-cover">
                            </div>
                        </div>
                        <div class="mt-10">
                            <h4 class="text-lg font-bold text-white group-hover:text-emerald-400 transition-colors">Gung Linggam</h4>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4 block">FrontEnd Developer</span>
                            
                            {{-- Tech Stack --}}
                            <div class="flex justify-center gap-2 mb-4">
                                <span class="px-2 py-1 bg-slate-900 rounded text-[10px] text-slate-300 border border-slate-700">C</span>
                                <span class="px-2 py-1 bg-slate-900 rounded text-[10px] text-slate-300 border border-slate-700">C++</span>
                                <span class="px-2 py-1 bg-slate-900 rounded text-[10px] text-slate-300 border border-slate-700">Python</span>
                            </div>
                            
                            <div class="flex justify-center gap-4 text-slate-400">
                                <a href="https://github.com/gunglinggam" class="hover:text-white transition-colors"><i class="fab fa-github text-lg"></i></a>
                                <a href="https://www.instagram.com/gunglinggam_?igsh=MXJ6NGhuZmdvaTJ3bw==" class="hover:text-white transition-colors"><i class="fab fa-instagram text-lg"></i></a>
                                <a href="https://www.linkedin.com/in/gung-linggam-096704326/" class="hover:text-white transition-colors"><i class="fab fa-linkedin text-lg"></i></a>
                            </div>
                        </div>
                    </div>

                    {{-- DEVELOPER 2 --}}
                    <div class="relative group p-6 rounded-2xl bg-slate-800/40 border border-slate-700 hover:border-blue-500/50 hover:bg-slate-800/70 transition-all duration-300 text-center">
                        <div class="absolute -top-6 left-1/2 -translate-x-1/2">
                            <div class="relative">
                                <div class="absolute inset-0 bg-blue-500 blur-md rounded-full opacity-0 group-hover:opacity-50 transition-opacity"></div>
                                {{-- FOTO --}}
                                <img src="images/fahri.jpeg" class="relative w-20 h-20 rounded-full border-4 border-[#0f172a] object-cover">
                            </div>
                        </div>
                        <div class="mt-10">
                            <h4 class="text-lg font-bold text-white group-hover:text-blue-400 transition-colors">Fahri</h4>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4 block">BackEnd Developer</span>
                            
                            {{-- Tech Stack --}}
                            <div class="flex justify-center gap-2 mb-4">
                                <span class="px-2 py-1 bg-slate-900 rounded text-[10px] text-slate-300 border border-slate-700">C</span>
                                <span class="px-2 py-1 bg-slate-900 rounded text-[10px] text-slate-300 border border-slate-700">C++</span>
                                <span class="px-2 py-1 bg-slate-900 rounded text-[10px] text-slate-300 border border-slate-700">Python</span>
                            </div>

                            <div class="flex justify-center gap-4 text-slate-400">
                                <a href="https://github.com/fahrisyfq/" class="hover:text-white transition-colors"><i class="fab fa-github text-lg"></i></a>
                                <a href="https://www.instagram.com/fahrisyfq?igsh=bnB1NWJ6ZmhueXh1" class="hover:text-white transition-colors"><i class="fab fa-instagram text-lg"></i></a>
                                <a href="https://www.linkedin.com/in/fahripai/" class="hover:text-white transition-colors"><i class="fab fa-linkedin text-lg"></i></a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</footer>

<style>
/* === SOCIAL BUTTON === */
.social-btn {
    width: 2.25rem; height: 2.25rem;
    display: flex; align-items: center; justify-content: center;
    border-radius: 0.5rem;
    background-color: #1e293b; color: #94a3b8;
    border: 1px solid #334155; transition: all 0.3s ease;
}
.social-btn:hover {
    background-color: #0f172a; border-color: #10b981;
    color: #10b981; transform: translateY(-3px);
}

/* === FOOTER LINK === */
.footer-link {
    color: #94a3b8; transition: all 0.2s;
    display: flex; align-items: center; gap: 0.5rem;
}
.footer-link:hover { color: white; padding-left: 4px; }

/* === LIGHT BUTTON (SYSTEM INFO) === */
.light-button button.bt {
    position: relative; height: 80px; width: 60px;
    display: flex; align-items: flex-end; justify-content: center;
    outline: none; background: none; border: none;
}
.button-holder {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    height: 60px; width: 60px;
    background-color: #0f172a; border-radius: 12px;
    color: #475569; transition: 300ms;
    border: 1px solid #1e293b; z-index: 2;
    position: relative;
}
.light-holder {
    position: absolute; top: 0; left: 0; width: 100%; height: 100%;
    display: flex; flex-direction: column; align-items: center; pointer-events: none;
}
.dot {
    width: 6px; height: 6px; border-radius: 50%; background-color: #334155;
    margin-bottom: 8px; transition: 300ms; z-index: 3; position: absolute; top: -10px;
}
.light {
    width: 80px; height: 60px; background: transparent; opacity: 0;
    clip-path: polygon(50% 0%, 10% 100%, 90% 100%);
    transform: translateY(-10px); transition: 300ms;
    position: absolute; top: -5px;
}

/* Hover Blue */
.bt-blue:hover .button-holder { color: #93c5fd; border-color: #3b82f6; box-shadow: 0 0 15px rgba(59,130,246,0.3); transform: translateY(-2px); }
.bt-blue:hover .dot { background-color: #60a5fa; box-shadow: 0 0 10px #60a5fa; }
.bt-blue:hover .light { background: linear-gradient(to bottom, rgba(96,165,250,0.5), transparent); opacity: 1; transform: translateY(0); }

/* Hover Green + Animasi Kedip */
.bt-green:hover .button-holder { color: #6ee7b7; border-color: #10b981; box-shadow: 0 0 15px rgba(16,185,129,0.3); transform: translateY(-2px); }
.bt-green:hover .dot { background-color: #34d399; box-shadow: 0 0 10px #34d399; animation: blink-green 1.5s infinite ease-in-out; }
.bt-green:hover .light { background: linear-gradient(to bottom, rgba(52,211,153,0.5), transparent); opacity: 1; transform: translateY(0); }

@keyframes blink-green {
    0%, 100% { opacity: 1; transform: scale(1); box-shadow: 0 0 10px #34d399; }
    50% { opacity: 0.4; transform: scale(0.8); box-shadow: 0 0 2px #34d399; }
}
</style>
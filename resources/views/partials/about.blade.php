<section id="about" class="py-16 md:py-24 bg-gradient-to-br from-emerald-50 via-white to-blue-50 relative overflow-hidden font-poppins">
    {{-- Gelombang SVG Halus --}}
    <svg class="absolute top-0 left-0 w-full h-32 opacity-30 pointer-events-none" viewBox="0 0 1440 320">
        <path fill="#a7f3d0" fill-opacity="0.5" d="M0,128L1440,32L1440,0L0,0Z"></path>
    </svg>

    <div class="container mx-auto px-6 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
            
            <div class="lg:w-2/5 flex justify-center">
                 <div class="logo-card group w-[280px] h-[280px]">
                     <div class="logo-content flex flex-col items-center justify-center text-center p-4 relative z-10">
                         <img src="/images/logo.jpg" alt="Logo Koperasi SMKN 8"
                             class="rounded-full w-24 h-24 shadow-md border-4 border-emerald-300 transition-transform duration-500 group-hover:scale-110 animate-idlePulse mb-4">
                         <div class="logo-text transition-all duration-500 ease-out">
                             <h3 class="text-emerald-800 font-extrabold text-2xl tracking-wide transition-all duration-500">
                                 Koperasi SMKN 8
                             </h3>
                             <p class="text-gray-600 text-sm font-medium">SMK Negeri 8 Jakarta</p>
                         </div>
                     </div>
                     {{-- Blob dengan warna lebih kontras --}}
                     <div class="blob blob-emerald"></div> 
                     <div class="logo-border relative z-20"></div> 
                 </div>
            </div>

            <div class="lg:w-3/5">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-6 animate-fade-in text-slate-900">
                    Tentang 
                    <span class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 bg-clip-text text-transparent section-title-glow">
                        Koperasi SMKN 8
                    </span>
                </h2>

                <p class="text-slate-700 mb-4 text-base md:text-lg leading-relaxed animate-fade-in-slow">
                    <span class="font-bold text-emerald-700">Koperasi SMKN 8</span> adalah unit usaha sekolah yang berdedikasi menyediakan 
                    <span class="font-semibold text-teal-700">seragam dan atribut sekolah</span> berkualitas bagi siswa dan guru. 
                    Kami hadir untuk memudahkan pemenuhan kebutuhan perlengkapan sekolah dengan harga terjangkau.
                </p>

                <p class="text-slate-700 mb-8 text-base md:text-lg leading-relaxed animate-fade-in-slow anim-delay-200">
                    Dengan semangat <span class="font-semibold text-teal-700">kemandirian</span> dan 
                    <span class="font-semibold text-emerald-700">kewirausahaan</span>, koperasi ini juga menjadi sarana belajar praktis bagi siswa dalam mengelola usaha, sambil mendukung terciptanya lingkungan sekolah yang rapi dan disiplin.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 md:gap-8 mb-10 animate-fade-in-slow anim-delay-400">
                    
                    {{-- Kartu Blob 1: Seragam --}}
                    <div class="feature-blob-card">
                        <div class="bg">
                            <div class="p-5 flex flex-col items-center text-center h-full justify-center">
                                <div class="bg-emerald-100 p-4 rounded-full shadow mb-4">
                                    <i class="fas fa-tshirt text-emerald-600 text-3xl"></i>
                                </div>
                                <h4 class="font-bold text-lg mb-1 text-emerald-800">Seragam Sekolah</h4>
                                <p class="text-gray-600 text-sm">Harian, olahraga, <br>dan pramuka.</p>
                            </div>
                        </div>
                        <div class="blob blob-green"></div>
                    </div>

                    {{-- Kartu Blob 2: Atribut --}}
                    <div class="feature-blob-card">
                        <div class="bg">
                             <div class="p-5 flex flex-col items-center text-center h-full justify-center">
                                <div class="bg-cyan-100 p-4 rounded-full shadow mb-4">
                                    <i class="fas fa-user-shield text-cyan-600 text-3xl"></i> 
                                </div>
                                <h4 class="font-bold text-lg mb-1 text-cyan-800">Atribut Sekolah</h4>
                                <p class="text-gray-600 text-sm">Topi, dasi, gesper, <br>dan lainnya.</p>
                            </div>
                        </div>
                         <div class="blob blob-blue"></div>
                    </div>

                </div>

                {{-- Tombol CTA (Tidak Berubah) --}}
                <a href="#features" 
                   class="inline-flex items-center bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-bold py-3 px-7 rounded-full shadow-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-emerald-200 animate-fade-in-slow anim-delay-600">
                    <i class="fas fa-star mr-2"></i> Lihat Keunggulan Kami
                </a>
            </div>
        </div>
    </div>
</section>


<style>
/* Import Font Poppins */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

#about {
    font-family: 'Poppins', sans-serif;
}

/* Animasi Fade-in */
@keyframes fade-in {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in { animation: fade-in 1s ease both; }
.animate-fade-in-slow { animation: fade-in 1.5s ease both; }
.anim-delay-200 { animation-delay: 200ms; }
.anim-delay-400 { animation-delay: 400ms; }
.anim-delay-600 { animation-delay: 600ms; }

/* Glow Judul Section */
.section-title-glow {
    text-shadow: 0 0 30px rgba(52, 211, 153, 0.3);
}

/* 🌿 Logo Card */
.logo-card {
  background: linear-gradient(145deg, #e0fcf3 0%, #cfffee 50%, #b3ffdf 100%);
  border-radius: 25px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative; 
  overflow: hidden; 
  transition: all 0.4s ease-in-out;
  box-shadow: 0 10px 25px rgba(16, 185, 129, 0.15); 
  z-index: 0; 
}
.logo-content {
    position: relative; 
    z-index: 2; 
    /* PERUBAHAN: Background sedikit lebih transparan */
    background-color: rgba(255, 255, 255, 0.65); 
    width: calc(100% - 10px); 
    height: calc(100% - 10px);
    border-radius: 20px; 
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
}

@keyframes idlePulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.03); }
}
.animate-idlePulse {
  animation: idlePulse 3s infinite ease-in-out;
}

.logo-border {
  position: absolute;
  inset: 0;
  border: 3px solid transparent;
  border-radius: 25px;
  transition: all 0.4s ease-in-out;
  opacity: 0;
  pointer-events: none; 
  z-index: 3; 
}
.logo-card:hover .logo-border {
  border-color: #34d399; 
  opacity: 1;
  inset: 10px;
}
.logo-card:hover img {
    transform: scale(1.1); 
}

/* Style Dasar Blob */
.blob { 
  position: absolute;
  z-index: 1; 
  top: 50%;
  left: 50%;
  border-radius: 50%;
  filter: blur(18px); 
  animation: blob-bounce 8s infinite ease; 
}

/* Blob Kartu Fitur */
.feature-blob-card .blob {
  width: 120px; 
  height: 120px;
  opacity: 0.7; 
}
.blob-green {
    background-color: #34d399; /* emerald-400 */
}
.blob-blue {
    background-color: #60a5fa; /* blue-400 */
    animation-delay: -4s; 
}

/* Blob Kartu Logo */
.logo-card .blob {
    width: 150px; 
    height: 150px;
    /* PERUBAHAN: Opacity & Blur disesuaikan */
    opacity: 0.65; 
    filter: blur(22px); 
}
.blob-emerald {
    /* PERUBAHAN: Warna lebih kontras */
    background-color: #10b981; /* emerald-500 */ 
    animation-duration: 9s; 
}

/* Kartu Fitur */
.feature-blob-card {
  position: relative;
  width: 100%; 
  height: 220px; 
  border-radius: 14px;
  z-index: 10; 
  overflow: hidden;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  box-shadow: 0 8px 32px 0 rgba(16, 185, 129, 0.1), 0 4px 12px 0 rgba(16, 185, 129, 0.08);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.feature-blob-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px 0 rgba(16, 185, 129, 0.15), 0 6px 16px 0 rgba(16, 185, 129, 0.1);
}
.feature-blob-card .bg {
  position: absolute;
  top: 5px;
  left: 5px;
  right: 5px;
  bottom: 5px;
  z-index: 2;
  background: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(12px); 
  -webkit-backdrop-filter: blur(12px); 
  border-radius: 10px;
  overflow: hidden;
  outline: 1px solid rgba(255, 255, 255, 0.5); 
}

/* Animasi Blob Bounce */
@keyframes blob-bounce {
  0% { transform: translate(-100%, -100%) translate3d(0, 0, 0); }
  25% { transform: translate(-100%, -100%) translate3d(100%, 0, 0); }
  50% { transform: translate(-100%, -100%) translate3d(100%, 100%, 0); }
  75% { transform: translate(-100%, -100%) translate3d(0, 100%, 0); }
  100% { transform: translate(-100%, -100%) translate3d(0, 0, 0); }
}
</style>
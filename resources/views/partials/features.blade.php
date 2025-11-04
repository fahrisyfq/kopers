<section class="py-16 md:py-24 bg-white relative overflow-hidden font-poppins" id="features"> 
    <div class="absolute inset-0 z-0 opacity-40" 
         style="background-image: radial-gradient(#d1d5db 1px, transparent 1px); background-size: 1.5rem 1.5rem;">
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-14 md:mb-16">
            <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-4 tracking-tight animate-fade-in">
                Mengapa Memilih 
                <span class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 bg-clip-text text-transparent section-title-glow">
                  Koperasi SMKN 8?
                </span>
            </h2>
            <p class="text-slate-600 max-w-3xl mx-auto text-lg animate-fade-in-slow">
                Koperasi SMKN 8 menjadi tempat terpercaya untuk memenuhi kebutuhan seragam dan atribut sekolah bagi seluruh siswa dan guru.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-10">
            
            {{-- PERUBAHAN: Menambahkan transisi dan hover scale/shadow ke container --}}
            <div class="card-border-spin-container fade-in delay-1 group transition-all duration-400 ease-out hover:scale-[1.02] hover:shadow-lg hover:shadow-emerald-400/20"> 
                {{-- PERUBAHAN: Menghapus hover translate/shadow dari kartu inner, padding disesuaikan --}}
                <div class="feature-card bg-white p-7 rounded-2xl shadow-md text-center relative z-10 transition-shadow duration-300">
                    <div class="flex justify-center mb-6">
                        <div class="bg-emerald-100 p-5 rounded-full shadow-sm transition-transform duration-300 group-hover:scale-110 border-2 border-emerald-200">
                            <i class="fas fa-tshirt text-emerald-600 text-4xl"></i>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-800">Seragam Sekolah Lengkap</h3>
                    <p class="text-slate-600 text-base leading-relaxed">
                        Menyediakan seragam harian, olahraga, pramuka, dan lainnya sesuai kebutuhan siswa SMKN 8.
                    </p>
                </div>
            </div>

            <div class="card-border-spin-container fade-in delay-2 group transition-all duration-400 ease-out hover:scale-[1.02] hover:shadow-lg hover:shadow-blue-400/20">
                 <div class="feature-card bg-white p-7 rounded-2xl shadow-md text-center relative z-10 transition-shadow duration-300">
                    <div class="flex justify-center mb-6">
                        <div class="bg-blue-100 p-5 rounded-full shadow-sm transition-transform duration-300 group-hover:scale-110 border-2 border-blue-200">
                            <i class="fas fa-user-shield text-blue-600 text-4xl"></i>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-800">Atribut Sekolah Resmi</h3>
                    <p class="text-slate-600 text-base leading-relaxed">
                        Tersedia atribut resmi seperti topi, dasi, ikat pinggang, badge, dan perlengkapan lainnya.
                    </p>
                </div>
            </div>

            <div class="card-border-spin-container fade-in delay-3 group transition-all duration-400 ease-out hover:scale-[1.02] hover:shadow-lg hover:shadow-teal-400/20">
                 <div class="feature-card bg-white p-7 rounded-2xl shadow-md text-center relative z-10 transition-shadow duration-300">
                    <div class="flex justify-center mb-6">
                        <div class="bg-teal-100 p-5 rounded-full shadow-sm transition-transform duration-300 group-hover:scale-110 border-2 border-teal-200">
                            <i class="fas fa-check-circle text-teal-600 text-4xl"></i>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-800">Kualitas Terjamin</h3>
                    <p class="text-slate-600 text-base leading-relaxed">
                        Produk koperasi terstandarisasi, nyaman dipakai, dan mendukung kegiatan belajar mengajar.
                    </p>
                </div>
            </div>
            
        </div>
    </div>
</section>

<style>
/* Import Font Poppins */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap');

#features {
    font-family: 'Poppins', sans-serif;
}

/* Animasi Fade-in */
@keyframes fadeInUp {
    0% { opacity: 0; transform: translateY(30px); }
    100% { opacity: 1; transform: translateY(0); }
}
.fade-in { 
    opacity: 0; 
    animation: fadeInUp 0.8s ease-out forwards; 
}
.fade-in.delay-1 { animation-delay: 0.1s; }
.fade-in.delay-2 { animation-delay: 0.2s; }
.fade-in.delay-3 { animation-delay: 0.3s; }

/* Glow Judul Section */
.section-title-glow {
    text-shadow: 0 0 30px rgba(52, 211, 153, 0.3);
}

/* Container Border */
.card-border-spin-container {
    position: relative;
    padding: 2px; 
    border-radius: 1.25rem; 
    overflow: hidden; 
    z-index: 1; 
    /* PERUBAHAN: Menambahkan transisi untuk scale */
    transition: transform 0.4s ease-out, box-shadow 0.4s ease-out; 
}

/* Kartu Konten Utama */
.feature-card {
    position: relative;
    z-index: 10; 
    height: 100%; 
}

/* Pseudo-elements untuk border berputar (::before & ::after) */
.card-border-spin-container::before,
.card-border-spin-container::after {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: inherit; 
    background: conic-gradient(
        from var(--angle, 0deg), 
        #a7f3d0, #6ee7b7, #34d399, #2dd4bf, #67e8f9, #a5f3fc, #a7f3d0
    );
    z-index: -1; 
    
    /* PERUBAHAN: Durasi idle diperlambat, opacity awal dinaikkan */
    animation: spin 8s linear infinite; 
    opacity: 0.8; 

    /* Transisi untuk efek hover */
    transition: opacity 0.4s ease-out, animation-duration 0.4s ease-out;
}

/* Efek Glow (::after) */
.card-border-spin-container::after {
    filter: blur(10px); 
    z-index: -2; 
    /* Transisi untuk filter blur */
    transition: opacity 0.4s ease-out, animation-duration 0.4s ease-out, filter 0.4s ease-out;
}

/* Efek Hover Baru (pada container) */
.card-border-spin-container:hover::before,
.card-border-spin-container:hover::after {
    /* Opacity jadi 1, durasi animasi dipercepat */
    opacity: 1; 
    animation-duration: 1.5s; 
}
.card-border-spin-container:hover::after {
    /* PERUBAHAN: Glow diintensifkan lebih jauh */
    filter: blur(20px); 
}

/* Variabel CSS Sudut Rotasi */
@property --angle {
  syntax: '<angle>';
  initial-value: 0deg;
  inherits: false;
}

/* Animasi Putar */
@keyframes spin {
  0% { --angle: 0deg; }
  100% { --angle: 360deg; }
}

</style>
@extends('layout.app')

@section('title', 'Hubungi Kami')

@section('content')
{{-- PERUBAHAN: Background disesuaikan sedikit --}}
<section id="contact" class="pt-28 pb-20 bg-gradient-to-br from-blue-50 via-white to-emerald-50 font-poppins"> {{-- Warna background disesuaikan --}}
    <div class="container mx-auto px-6">
        <div class="text-center mb-16 animate-fadeIn">
            {{-- PERUBAHAN: Warna header diubah ke biru --}}
            <h2 class="text-3xl md:text-4xl font-extrabold text-blue-600 mb-3 tracking-tight"> 
                Hubungi Kami
            </h2>
            <p class="text-slate-600 max-w-2xl mx-auto text-base md:text-lg leading-relaxed">
                Ada pertanyaan atau butuh bantuan? Kami siap membantu kamu dengan senang hati 😊  
                Silakan isi formulir atau kunjungi lokasi kami secara langsung.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
            
            <div class="card animate-slideUp delay-1"> 
                {{-- PERUBAHAN: Border di card__content disesuaikan --}}
                <div class="card__content bg-white/90 backdrop-blur-lg rounded-xl shadow-lg border border-blue-100 overflow-hidden p-7 md:p-8 transition-all duration-300 ease-out">
                    <div class="space-y-6">
                        @php
                            // Menggunakan warna biru/emerald untuk link dan ikon
                             $contacts = [
                                ['icon' => 'fa-map-marker-alt', 'icon_bg' => 'bg-blue-100', 'icon_text' => 'text-blue-600', 'title' => 'Alamat', 'desc' => 'Jl. Pejaten Raya No.34, RT.6/RW.6, Pejaten Bar., Ps. Minggu, Kota Jakarta Selatan, DKI Jakarta 12510'],
                                ['icon' => 'fa-phone-alt', 'icon_bg' => 'bg-emerald-100', 'icon_text' => 'text-emerald-600', 'title' => 'Telepon / WhatsApp', 'desc' => '(021) 7805878<br><a href="https://wa.me/6281234567890" target="_blank" class="text-blue-600 hover:underline">+62 812-3456-7890</a>'],
                                ['icon' => 'fa-envelope', 'icon_bg' => 'bg-blue-100', 'icon_text' => 'text-blue-600', 'title' => 'Email', 'desc' => '<a href="mailto:koperasi@smkn8jakarta.sch.id" class="text-blue-600 hover:underline">koperasi@smkn8jakarta.sch.id</a>'],
                                ['icon' => 'fa-clock', 'icon_bg' => 'bg-emerald-100', 'icon_text' => 'text-emerald-600', 'title' => 'Jam Operasional', 'desc' => 'Senin - Jumat: 07.00 - 15.00 WIB<br>Sabtu & Minggu: Libur'],
                            ];
                        @endphp

                        @foreach($contacts as $item)
                        <div class="flex items-start space-x-4 group/item"> 
                            {{-- PERUBAHAN: Warna ikon disesuaikan --}}
                            <div class="{{ $item['icon_bg'] }} {{ $item['icon_text'] }} p-3 rounded-lg text-lg shadow-sm transition-all duration-300 group-hover/item:scale-110 group-hover/item:shadow-md group-hover/item:brightness-105">
                                <i class="fas {{ $item['icon'] }} fa-fw"></i> 
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 text-base mb-0.5">{{ $item['title'] }}</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">{!! $item['desc'] !!}</p>
                            </div>
                        </div>
                        @endforeach

                        <div class="pt-5 border-t border-gray-100">
                            <p class="font-medium text-gray-700 mb-3 text-sm">Temukan Kami:</p>
                            <div class="flex space-x-5 text-xl"> 
                                <a href="#" title="Facebook" class="text-blue-600 hover:text-blue-800 transition transform hover:scale-125"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" title="Instagram" class="text-pink-600 hover:text-pink-800 transition transform hover:scale-125"><i class="fab fa-instagram"></i></a>
                                <a href="#" title="Twitter" class="text-blue-400 hover:text-blue-600 transition transform hover:scale-125"><i class="fab fa-twitter"></i></a>
                                <a href="#" title="WhatsApp" class="text-green-500 hover:text-green-700 transition transform hover:scale-125"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card animate-slideUp delay-2">
                {{-- PERUBAHAN: Border di card__content disesuaikan --}}
                <div class="card__content bg-white/90 backdrop-blur-lg rounded-xl shadow-lg border border-blue-100 overflow-hidden p-7 md:p-8 transition-all duration-300 ease-out">
                    <form action="{{ route('kirim.pesan') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <div class="relative">
                                <span class="input-icon left-3"><i class="fas fa-user"></i></span>
                                <input type="text" name="name" id="name" class="input-field pl-10 @error('name') input-error @enderror" required placeholder="Nama Anda">
                            </div>
                             @error('name') <p class="error-message"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="form-label">Email</label>
                             <div class="relative">
                                <span class="input-icon left-3"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" id="email" class="input-field pl-10 @error('email') input-error @enderror" required placeholder="email@anda.com">
                            </div>
                            @error('email') <p class="error-message"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="phone" class="form-label">Nomor Telepon <span class="text-gray-400 text-xs">(Opsional)</span></label>
                             <div class="relative">
                                <span class="input-icon left-3"><i class="fas fa-phone"></i></span>
                                <input type="tel" name="phone" id="phone" class="input-field pl-10 @error('phone') input-error @enderror" placeholder="08xxxxxxxxxx">
                            </div>
                             @error('phone') <p class="error-message"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="message" class="form-label">Pesan</label>
                            <textarea name="message" id="message" rows="4" class="input-field @error('message') input-error @enderror" required placeholder="Tulis pesan Anda di sini..."></textarea>
                            @error('message') <p class="error-message"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <button type="submit"
                                {{-- PERUBAHAN: Warna tombol disamakan dengan form profil --}}
                                class="submit-button w-full bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 text-white font-semibold py-2.5 rounded-lg shadow-lg transition-all duration-300 transform hover:scale-[1.03] flex items-center justify-center gap-2 text-base">
                            <i class="fas fa-paper-plane"></i> Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="mt-16 md:mt-20 animate-fadeInSlow">
            <h3 class="text-2xl font-bold text-center mb-8 text-slate-800">Temukan Lokasi Kami</h3>
            <div class="rounded-xl overflow-hidden shadow-lg border border-gray-200">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.891267292391!2d106.83368807441063!3d-6.278023761453096!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f23e0287b8e1%3A0x2a98e9f790322749!2sSMK%20Negeri%208%20Jakarta!5e0!3m2!1sid!2sid!4v1749535668745!5m2!1sid!2sid" 
                    width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</section>

<style>
/* Import Font Poppins */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

.font-poppins { font-family: 'Poppins', sans-serif; }

/* === Card Styling Baru (Adaptasi Biru/Emerald Theme) === */
.card {
    /* PERUBAHAN: Warna glow & shadow disesuaikan ke biru/emerald */
    --glow-primary: hsla(197, 71%, 73%, 0.7); /* Cyan terang */
    --glow-secondary: hsla(158, 64%, 73%, 0.7); /* Emerald terang */
    --card-bg: rgba(255, 255, 255, 0.9); 
    --card-shadow: rgba(59, 130, 246, 0.08); /* Shadow biru halus */
    --card-shadow-hover: rgba(59, 130, 246, 0.15); /* Shadow biru hover */
    --border-line: rgba(191, 219, 254, 0.5); /* Warna border biru muda (blue-200) */
    
    position: relative;
    border-radius: 1.25rem; 
    overflow: hidden; 
    box-shadow: 0 8px 25px -5px var(--card-shadow);
    z-index: 1; 
    transition: box-shadow 0.3s ease-out; 
}

/* Konten di dalam card */
.card .card__content {
    position: relative; 
    z-index: 2; 
    background-color: var(--card-bg);
    backdrop-filter: blur(8px); 
    -webkit-backdrop-filter: blur(8px);
    border-radius: 1.1rem; 
    border: 1px solid var(--border-line); 
    transition: transform 0.3s ease-out, box-shadow 0.3s ease-out; 
}

/* Pseudo-element untuk border berputar */
.card::before {
    content: "";
    pointer-events: none;
    position: absolute;
    z-index: -1; 
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: calc(100% + 30px); 
    height: calc(100% + 30px);
    
    background-image: conic-gradient(
        from var(--angle), 
        var(--glow-secondary), 
        var(--glow-primary), 
        var(--glow-secondary)
    );
    filter: blur(12px); 
    opacity: 0.6; 
    animation: rotate 9s linear infinite; 
    transition: opacity 0.3s ease-out, filter 0.3s ease-out; /* Tambahkan transisi */
}

/* Efek Hover Baru */
.card:hover .card__content {
    transform: scale(1.02); 
    box-shadow: 0 15px 35px -5px var(--card-shadow-hover); 
}
.card:hover::before {
     opacity: 0.75; 
     filter: blur(14px);
}

/* Animasi Rotasi */
@keyframes rotate { to { --angle: 360deg; } }
@property --angle { syntax: "<angle>"; initial-value: 0deg; inherits: false; }


/* === Form Styling (Warna Focus Disesuaikan ke Biru) === */
.form-label {
    display: block;
    font-size: 0.8rem; 
    font-weight: 500;
    color: #4b5563; 
    margin-bottom: 0.2rem; 
}
.input-field, textarea.input-field { 
    width: 100%;
    border: 1px solid #e5e7eb; 
    padding: 0.6rem 0.75rem; 
    border-radius: 0.5rem; 
    outline: none;
    font-size: 0.85rem; 
    color: #1f2937; 
    background-color: #f9fafb; 
    transition: all 0.2s ease-in-out; 
}
.input-field.pl-10 { padding-left: 2.5rem; } 

.input-field:focus, textarea.input-field:focus { 
    /* PERUBAHAN: Warna focus diubah ke biru */
    border-color: #3b82f6; /* blue-500 */
    background-color: #fff;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2); /* Shadow biru */
}

.input-icon {
    position: absolute;
    top: 0.6rem; 
    transform: none; 
    color: #9ca3af; 
    font-size: 0.85rem; 
    pointer-events: none;
    z-index: 5; 
}
.input-icon.left-3 { left: 0.7rem; } 

/* Error Styling */
.input-error { border-color: #ef4444; background-color: #fee2e2; }
.error-message { color: #dc2626; font-size: 0.75rem; margin-top: 0.2rem; display: flex; align-items: center; }

/* Tombol Submit (Warna disesuaikan) */
.submit-button {
    background-size: 200% auto; 
    transition: all 0.4s ease-out; 
}
.submit-button:hover {
    background-position: right center; 
    /* PERUBAHAN: Warna shadow disesuaikan */
    box-shadow: 0 4px 15px 0 rgba(20, 184, 166, 0.3); /* Shadow teal */
    transform: scale(1.03); 
}

/* Animasi Halaman */
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeInSlow { from { opacity: 0; } to { opacity: 1; } }
@keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

.animate-fadeIn { animation: fadeIn 0.8s ease-out both; }
.animate-fadeInSlow { animation: fadeInSlow 1.2s ease-out both; }
.animate-slideUp { animation: slideUp 0.9s ease-out both; }
.animate-slideUp.delay-1 { animation-delay: 0.1s; } 
.animate-slideUp.delay-2 { animation-delay: 0.25s; } 

</style>
@endsection
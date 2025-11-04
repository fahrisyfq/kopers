@extends('layout.app')

@section('title', 'Lengkapi Profil')

@section('content')
{{-- PERUBAHAN: py-16 -> pt-28 pb-12 --}}
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-emerald-50 via-white to-blue-100 pt-28 pb-12 px-4 font-poppins">

    <div class="card w-full max-w-md"> 
        <div class="card__border"></div> 

        <div class="card__content bg-white/95 backdrop-blur-md rounded-xl shadow-lg border border-blue-100 overflow-hidden"> {{-- Radius disesuaikan sedikit --}}
            {{-- Header --}}
             {{-- PERUBAHAN: Padding vertikal header dikurangi --}}
            <div class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white text-center py-5 px-6 rounded-t-lg"> 
                <h1 class="text-xl font-semibold flex items-center justify-center gap-2"> {{-- Ukuran font disesuaikan --}}
                    <i class="fas fa-user-check text-yellow-300"></i>
                    Lengkapi Profil Siswa
                </h1>
                <p class="text-blue-100 text-xs mt-1 opacity-90">Pastikan data yang Anda masukkan sudah benar.</p> {{-- Ukuran font disesuaikan --}}
            </div>

            {{-- Alert Sukses --}}
            @if (session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-400 text-emerald-700 p-3 mx-5 mt-4 rounded-md text-sm"> {{-- Padding & margin disesuaikan --}}
                    <i class="fas fa-check-circle mr-1.5"></i> {{ session('success') }}
                </div>
            @endif

            {{-- Body Form --}}
            {{-- PERUBAHAN: Padding & Space dikurangi --}}
            <div class="p-5 space-y-4"> 
                <form action="{{ route('profile.storeComplete') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="form-label">Nama Lengkap</label>
                        <div class="relative">
                            <span class="input-icon left-3"><i class="fas fa-user"></i></span>
                            <input type="text" name="nama_lengkap" class="input-field pl-10 @error('nama_lengkap') input-error @enderror"
                                value="{{ old('nama_lengkap') }}" placeholder="Masukkan nama lengkap sesuai Akta" required> 
                        </div>
                        @error('nama_lengkap') <p class="error-message"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                    </div>

                    {{-- NISN & NIS --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4"> 
                        <div>
                            <label class="form-label">NISN</label>
                             <div class="relative">
                                <span class="input-icon left-3"><i class="fas fa-id-card"></i></span>
                                <input type="text" name="nisn" class="input-field pl-10 @error('nisn') input-error @enderror"
                                    value="{{ old('nisn', $user->nisn ?? '') }}" placeholder="10 Digit NISN" required inputmode="numeric">
                             </div>
                            @error('nisn') <p class="error-message"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="form-label">NIS</label>
                            <div class="relative">
                                <span class="input-icon left-3"><i class="fas fa-hashtag"></i></span>
                                <input type="text" name="nis" id="nis" class="input-field pl-10 @error('nis') input-error @enderror"
                                    value="{{ old('nis', $user->nis ?? '') }}" placeholder="Contoh: 12345"> 
                            </div>
                            @if(empty($user->nis))
                                <p class="text-xs text-gray-500 mt-1">⚠️ Jika belum memiliki isi "-"</p> {{-- Teks dipersingkat --}}
                            @endif
                            @error('nis') <p class="error-message"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Kelas --}}
                    <div>
                        <label class="form-label">Kelas</label>
                        <div class="relative">
                            <span class="input-icon left-3"><i class="fas fa-chalkboard-teacher"></i></span>
                            <select name="kelas" class="select-field pl-10 @error('kelas') input-error @enderror" required>
                                <option value="" disabled {{ old('kelas', $user->kelas) ? '' : 'selected' }}>-- Pilih Kelas --</option>
                                @foreach(['10','11','12'] as $kelas)
                                    <option value="{{ $kelas }}" {{ old('kelas', $user->kelas) == $kelas ? 'selected' : '' }}>
                                        Kelas {{ $kelas }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="select-arrow"><i class="fas fa-chevron-down"></i></span>
                        </div>
                        @error('kelas') <p class="error-message"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                    </div>

                    {{-- Jurusan --}}
                    <div>
                        <label class="form-label">Jurusan</label>
                         <div class="relative">
                            <span class="input-icon left-3"><i class="fas fa-book-reader"></i></span>
                            <select name="jurusan" class="select-field pl-10 @error('jurusan') input-error @enderror" required>
                                <option value="" disabled {{ old('jurusan', $user->jurusan) ? '' : 'selected' }}>-- Pilih Jurusan --</option>
                                @foreach(['AKL 1','AKL 2','AKL 3','MP 1','Manlog','BR 1','BR 2','BD','UPW','RPL','Belum Ditentukan'] as $jurusan)
                                    <option value="{{ $jurusan }}" {{ old('jurusan', $user->jurusan) == $jurusan ? 'selected' : '' }}>
                                        {{ $jurusan }}
                                    </option>
                                @endforeach
                            </select>
                             <span class="select-arrow"><i class="fas fa-chevron-down"></i></span>
                        </div>
                        @error('jurusan') <p class="error-message"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                    </div>

                    {{-- Nomor Telepon Siswa --}}
                    <div>
                        <label class="form-label">No. Telepon Siswa (WA)</label> {{-- Dipersingkat --}}
                        <div class="relative flex items-center">
                            <span class="prefix">+62</span>
                             <span class="input-icon" style="left: calc(3.4rem + 10px);"><i class="fas fa-mobile-alt"></i></span> 
                            <input type="text" name="no_telp_siswa" class="input-field rounded-l-none pl-11 @error('no_telp_siswa') input-error @enderror" 
                                   value="{{ old('no_telp_siswa', ltrim($user->no_telp_siswa, '+62')) }}" placeholder="812xxxxxxx" required inputmode="tel">
                        </div>
                        @error('no_telp_siswa') <p class="error-message"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                    </div>

                    {{-- Nomor Telepon Orang Tua --}}
                    <div>
                        <label class="form-label">No. Telepon Orang Tua</label> {{-- Dipersingkat --}}
                         <div class="relative flex items-center">
                            <span class="prefix">+62</span>
                            <span class="input-icon" style="left: calc(3.4rem + 10px);"><i class="fas fa-phone-alt"></i></span>
                            <input type="text" name="no_telp_ortu" class="input-field rounded-l-none pl-11 @error('no_telp_ortu') input-error @enderror"
                                   value="{{ old('no_telp_ortu', ltrim($user->no_telp_ortu, '+62')) }}" placeholder="812xxxxxxx" required inputmode="tel">
                        </div>
                        @error('no_telp_ortu') <p class="error-message"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                    </div>

                    {{-- Tombol Simpan --}}
                    <button type="submit"
                            class="submit-button w-full bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 text-white font-semibold py-2.5 rounded-lg shadow-lg transition-all duration-300 transform hover:scale-[1.03] flex items-center justify-center gap-2 text-base"> {{-- Padding tombol dikurangi --}}
                        <i class="fas fa-check-circle"></i> Simpan Profil
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Import Font Poppins */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

.font-poppins { font-family: 'Poppins', sans-serif; }

/* Card Border Berputar */
.card {
    --primary-glow: hsl(197, 71%, 73%); 
    --secondary-glow: hsl(158, 64%, 73%); 
    
    position: relative;
    padding: 2px; 
    border-radius: 1.1rem; /* Disesuaikan dengan konten */
    overflow: hidden;
}

.card .card__border {
    pointer-events: none;
    position: absolute;
    z-index: 1; 
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: calc(100% + 20px); 
    height: calc(100% + 20px);
    
    background-image: conic-gradient(
        from var(--angle), 
        var(--secondary-glow), 
        var(--primary-glow), 
        var(--secondary-glow)
    );
    filter: blur(5px); 
    opacity: 0.7; 
    animation: rotate 8s linear infinite; 
}

/* Konten Card */
.card .card__content {
    position: relative;
    z-index: 2;
    background-color: rgba(255, 255, 255, 0.95); 
    border-radius: 1rem; /* Disesuaikan dengan container */
}

/* Animasi Rotasi */
@keyframes rotate { to { --angle: 360deg; } }
@property --angle { syntax: "<angle>"; initial-value: 0deg; inherits: false; }

/* Form Styling */
.form-label {
    display: block;
    font-size: 0.8rem; /* Lebih kecil */
    font-weight: 500;
    color: #4b5563; 
    margin-bottom: 0.2rem; /* Lebih rapat */
}
.input-field, .select-field {
    width: 100%;
    border: 1px solid #d1d5db; 
    padding: 0.6rem 0.75rem; /* Padding dikurangi */
    border-radius: 0.5rem; /* Radius disesuaikan */
    outline: none;
    font-size: 0.85rem; /* Font input sedikit lebih kecil */
    color: #1f2937; 
    background-color: #f8fafc; /* Sedikit lebih terang */ 
    transition: all 0.2s ease-in-out; /* Transisi lebih cepat */
}
/* Penyesuaian padding kiri */
.input-field.pl-10, .select-field.pl-10 { padding-left: 2.5rem; } /* Dikurangi */
.input-field.pl-11 { padding-left: 2.8rem; } /* Dikurangi */

.input-field:focus, .select-field:focus {
    border-color: #3b82f6; 
    background-color: #fff;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2); /* Shadow lebih tipis */
}
.input-icon {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af; 
    font-size: 0.85rem; /* Disesuaikan */
    pointer-events: none;
    z-index: 5; 
}
.input-icon.left-3 { left: 0.7rem; } /* Disesuaikan */
/* Kalkulasi disesuaikan */
/* Asumsi prefix width sekitar 3.2rem + 8px padding */
/* `left: calc(3.2rem + 8px);` atau `left: 3.7rem;` (sekitar 59.2px) */

.prefix {
    padding: 0.6rem 0.7rem; /* Padding disesuaikan */
    background-color: #e5e7eb; 
    border: 1px solid #d1d5db;
    border-right: 0;
    color: #4b5563; 
    font-size: 0.85rem; /* Disesuaikan */
    border-radius: 0.5rem 0 0 0.5rem; /* Disesuaikan */
    flex-shrink: 0; 
    /* Tinggi disesuaikan dengan input baru */
    height: calc(1.7rem + 1.2rem + 2px); 
    display: inline-flex; 
    align-items: center; 
}

/* Select Dropdown */
.select-field {
    appearance: none;
    background-image: none; 
    cursor: pointer;
}
.select-arrow {
    position: absolute;
    top: 50%;
    right: 0.7rem; /* Disesuaikan */
    transform: translateY(-50%);
    color: #9ca3af; 
    font-size: 0.75rem; /* Sedikit lebih kecil */
    pointer-events: none;
}

/* Error Styling */
.input-error {
    border-color: #ef4444; 
    background-color: #fee2e2; 
}
.error-message {
    color: #dc2626; 
    font-size: 0.75rem; /* Lebih kecil */
    margin-top: 0.2rem; /* Lebih rapat */
    display: flex;
    align-items: center;
}

/* Style & Animasi Tombol Submit */
.submit-button {
    background-size: 200% auto; 
    transition: all 0.4s ease-out; 
}
.submit-button:hover {
    background-position: right center; 
    box-shadow: 0 4px 15px 0 rgba(45, 212, 191, 0.3); /* Shadow sedikit dikurangi */
    transform: scale(1.03); 
}
</style>
@endsection
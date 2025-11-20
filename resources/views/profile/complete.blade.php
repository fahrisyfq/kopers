@extends('layout.app')

@section('title', 'Lengkapi Profil')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-emerald-50 via-white to-blue-100 pt-28 pb-12 px-4 font-poppins">

    <div class="card w-full max-w-md"> 
        <div class="card__border"></div> 

        <div class="card__content bg-white/95 backdrop-blur-md rounded-xl shadow-lg border border-blue-100 overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white text-center py-5 px-6 rounded-t-lg"> 
                <h1 class="text-xl font-semibold flex items-center justify-center gap-2">
                    <i class="fas fa-user-check text-yellow-300"></i>
                    Lengkapi Profil Siswa
                </h1>
                <p class="text-blue-100 text-xs mt-1 opacity-90">Pastikan data yang Anda masukkan sudah benar.</p>
            </div>

            {{-- Alert Sukses --}}
            @if (session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-400 text-emerald-700 p-3 mx-5 mt-4 rounded-md text-sm">
                    <i class="fas fa-check-circle mr-1.5"></i> {{ session('success') }}
                </div>
            @endif

            {{-- Body Form --}}
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
                                {{-- [TAMBAHAN] Atribut pattern, maxlength, dan title --}}
                                <input type="text" name="nisn" class="input-field pl-10 @error('nisn') input-error @enderror"
                                    value="{{ old('nisn', $user->nisn ?? '') }}" placeholder="10 Digit NISN" 
                                    required inputmode="numeric" 
                                    pattern="\d{10}" maxlength="10" title="NISN harus 10 digit angka">
                             </div>
                            @error('nisn') <p class="error-message"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="form-label">NIS</label>
                            <div class="relative">
                                <span class="input-icon left-3"><i class="fas fa-hashtag"></i></span>
                                <input type="text" name="nis" id="nis" class="input-field pl-10 @error('nis') input-error @enderror"
                                    value="{{ old('nis', $user->nis ?? '') }}" placeholder="Contoh: 12345" maxlength="10"> 
                            </div>
                            @if(empty($user->nis))
                                <p class="text-xs text-gray-500 mt-1">⚠️ Jika belum memiliki isi "-"</p>
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
                        <label class="form-label">No. Telepon Siswa (WA)</label>
                        <div class="relative flex items-center">
                            <span class="prefix">+62</span>
                             <span class="input-icon" style="left: calc(3.4rem + 10px);"><i class="fas fa-mobile-alt"></i></span> 
                            {{-- [TAMBAHAN] ID ditambahkan & maxlength --}}
                            <input type="text" name="no_telp_siswa" id="no_telp_siswa" class="input-field rounded-l-none pl-11 @error('no_telp_siswa') input-error @enderror" 
                                   value="{{ old('no_telp_siswa', ltrim($user->no_telp_siswa, '+62')) }}" placeholder="812xxxxxxx" 
                                   required inputmode="tel" maxlength="12">
                        </div>
                        {{-- [TAMBAHAN] Helper text untuk validasi '0' --}}
                        <p id="no_telp_siswa_helper" class="error-message" style="display: none;"><i class="fas fa-exclamation-circle mr-1"></i> Jangan awali dengan 0. Langsung masukkan angka 8.</p>
                        @error('no_telp_siswa') <p class="error-message"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                    </div>

                    {{-- Nomor Telepon Orang Tua --}}
                    <div>
                        <label class="form-label">No. Telepon Orang Tua</label>
                         <div class="relative flex items-center">
                            <span class="prefix">+62</span>
                            <span class="input-icon" style="left: calc(3.4rem + 10px);"><i class="fas fa-phone-alt"></i></span>
                            {{-- [TAMBAHAN] ID ditambahkan & maxlength --}}
                            <input type="text" name="no_telp_ortu" id="no_telp_ortu" class="input-field rounded-l-none pl-11 @error('no_telp_ortu') input-error @enderror"
                                   value="{{ old('no_telp_ortu', ltrim($user->no_telp_ortu, '+62')) }}" placeholder="812xxxxxxx" 
                                   required inputmode="tel" maxlength="12">
                        </div>
                        {{-- [TAMBAHAN] Helper text untuk validasi '0' --}}
                        <p id="no_telp_ortu_helper" class="error-message" style="display: none;"><i class="fas fa-exclamation-circle mr-1"></i> Jangan awali dengan 0. Langsung masukkan angka 8.</p>
                        @error('no_telp_ortu') <p class="error-message"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                    </div>

                    {{-- [TAMBAHAN] Catatan Penting --}}
                    <div class="!mt-6 p-3.5 bg-yellow-50 border border-yellow-200 rounded-lg text-yellow-800 text-xs space-y-2">
                        <p class="font-bold flex items-center gap-1.5"><i class="fas fa-exclamation-triangle"></i> Catatan Penting:</p>
                        <ul class="list-disc list-inside pl-1 space-y-1">
                            <li>Data Nama Lengkap dan NISN tidak dapat diubah setelah disimpan. Pastikan Anda mengisinya dengan benar.</li>
                            <li>Jika Anda siswa baru (kelas 10) dan belum memiliki NIS atau Jurusan, pilih opsi "-" atau "Belum Ditentukan".</li>
                        </ul>
                    </div>

                    {{-- Tombol Simpan --}}
                    <button type="submit"
                            class="submit-button w-full bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 text-white font-semibold py-2.5 rounded-lg shadow-lg transition-all duration-300 transform hover:scale-[1.03] flex items-center justify-center gap-2 text-base">
                        <i class="fas fa-check-circle"></i> Simpan Profil
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* ... (SEMUA KODE CSS ANDA DARI ATAS TETAP DI SINI) ... */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
.font-poppins { font-family: 'Poppins', sans-serif; }
.card {
    --primary-glow: hsl(197, 71%, 73%); 
    --secondary-glow: hsl(158, 64%, 73%); 
    position: relative;
    padding: 2px; 
    border-radius: 1.1rem;
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
.card .card__content {
    position: relative;
    z-index: 2;
    background-color: rgba(255, 255, 255, 0.95); 
    border-radius: 1rem;
}
@keyframes rotate { to { --angle: 360deg; } }
@property --angle { syntax: "<angle>"; initial-value: 0deg; inherits: false; }
.form-label {
    display: block;
    font-size: 0.8rem;
    font-weight: 500;
    color: #4b5563; 
    margin-bottom: 0.2rem;
}
.input-field, .select-field {
    width: 100%;
    border: 1px solid #d1d5db; 
    padding: 0.6rem 0.75rem;
    border-radius: 0.5rem;
    outline: none;
    font-size: 0.85rem;
    color: #1f2937; 
    background-color: #f8fafc; 
    transition: all 0.2s ease-in-out;
}
.input-field.pl-10, .select-field.pl-10 { padding-left: 2.5rem; }
.input-field.pl-11 { padding-left: 2.8rem; }
.input-field:focus, .select-field:focus {
    border-color: #3b82f6; 
    background-color: #fff;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
}
.input-icon {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af; 
    font-size: 0.85rem;
    pointer-events: none;
    z-index: 5; 
}
.input-icon.left-3 { left: 0.7rem; }
.prefix {
    padding: 0.6rem 0.7rem;
    background-color: #e5e7eb; 
    border: 1px solid #d1d5db;
    border-right: 0;
    color: #4b5563; 
    font-size: 0.85rem;
    border-radius: 0.5rem 0 0 0.5rem;
    flex-shrink: 0; 
    height: calc(1.7rem + 1.2rem + 2px); 
    display: inline-flex; 
    align-items: center; 
}
.select-field {
    appearance: none;
    background-image: none; 
    cursor: pointer;
}
.select-arrow {
    position: absolute;
    top: 50%;
    right: 0.7rem;
    transform: translateY(-50%);
    color: #9ca3af; 
    font-size: 0.75rem;
    pointer-events: none;
}
.input-error {
    border-color: #ef4444; 
    background-color: #fee2e2; 
}
.error-message {
    color: #dc2626; 
    font-size: 0.75rem;
    margin-top: 0.2rem;
    display: flex;
    align-items: center;
}
.submit-button {
    background-size: 200% auto; 
    transition: all 0.4s ease-out; 
}
.submit-button:hover {
    background-position: right center; 
    box-shadow: 0 4px 15px 0 rgba(45, 212, 191, 0.3);
    transform: scale(1.03); 
}
</style>

{{-- ================================================== --}}
{{-- [BARU] Script untuk validasi input '0' dan lainnya --}}
{{-- ================================================== --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // Fungsi untuk menangani input nomor telepon
        function handlePhoneInput(inputId, helperId) {
            const input = document.getElementById(inputId);
            const helper = document.getElementById(helperId);

            if (!input) return; // Pastikan elemen ada

            input.addEventListener('input', function(e) {
                let value = e.target.value;
                
                // 1. Hapus semua karakter non-digit
                let numericValue = value.replace(/\D/g, '');

                if (numericValue.startsWith('0')) {
                    // 2. Hapus '0' di depan
                    numericValue = numericValue.substring(1);
                    // 3. Tampilkan pesan bantuan
                    helper.style.display = 'flex';
                } else {
                    // 4. Sembunyikan pesan bantuan
                    helper.style.display = 'none';
                }
                
                // 5. Set nilai input yang sudah bersih
                e.target.value = numericValue;
            });
        }

        // Terapkan ke kedua input telepon
        handlePhoneInput('no_telp_siswa', 'no_telp_siswa_helper');
        handlePhoneInput('no_telp_ortu', 'no_telp_ortu_helper');

        // [TAMBAHAN] Validasi NISN (hanya angka)
        const nisnInput = document.querySelector('input[name="nisn"]');
        if (nisnInput) {
            nisnInput.addEventListener('input', function(e) {
                // Hapus semua karakter non-digit
                e.target.value = e.target.value.replace(/\D/g, '');
            });
        }
        
        // [TAMBAHAN] Validasi NIS (hanya angka atau strip)
        const nisInput = document.getElementById('nis');
        if (nisInput) {
            nisInput.addEventListener('input', function(e) {
                // Hanya izinkan angka atau strip (-)
                e.target.value = e.target.value.replace(/[^0-9-]/g, '');
            });
        }
    });
</script>
@endsection
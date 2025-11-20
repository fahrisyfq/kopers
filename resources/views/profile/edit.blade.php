@extends('layout.app')

@section('title', 'Edit Profil')

@section('content')
{{-- Padding atas untuk menghindari navbar --}}
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-emerald-50 via-white to-blue-100 py-28 px-4 font-poppins">

    <div class="card w-full max-w-md"> 
        <div class="card__border"></div> 
        
        <div class="card__content overflow-hidden"> 
            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white text-center py-5 px-6"> 
                <h1 class="text-xl font-semibold flex items-center justify-center gap-2"> 
                    <i class="fas fa-user-cog text-yellow-300"></i>
                    Edit Profil Siswa
                </h1>
                <p class="text-blue-100 text-xs mt-1 opacity-90">Perbarui data diri Anda.</p> 
            </div>

            {{-- Alert Sukses --}}
            @if (session('success'))
                <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-800 p-3 mx-5 mt-4 rounded text-sm shadow-sm"> 
                    <i class="fas fa-check-circle mr-1.5"></i> {{ session('success') }}
                </div>
            @endif

            {{-- Body Form --}}
            <div class="p-5 md:p-6 space-y-4"> 
                <form id="editProfileForm" action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Nama Lengkap (Readonly) --}}
                    <div>
                        <label class="form-label">Nama Lengkap</label>
                        <div class="relative">
                            <span class="input-icon left-3 text-gray-400"><i class="fas fa-user"></i></span>
                            <input type="text" class="input-field pl-10 bg-gray-100 text-gray-500 cursor-not-allowed" 
                                   value="{{ $user->nama_lengkap }}" readonly>
                        </div>
                    </div>

                    {{-- NISN (Readonly) --}}
                    <div>
                        <label class="form-label">NISN</label>
                         <div class="relative">
                            <span class="input-icon left-3 text-gray-400"><i class="fas fa-id-card"></i></span>
                            <input type="text" class="input-field pl-10 bg-gray-100 text-gray-500 cursor-not-allowed" 
                                   value="{{ $user->nisn }}" readonly>
                         </div>
                    </div>

                    {{-- NIS (Editable) --}}
                    <div>
                        <label class="form-label">NIS</label>
                        <div class="relative">
                            <span class="input-icon left-3"><i class="fas fa-hashtag"></i></span>
                            <input type="text" name="nis" id="nis" class="input-field pl-10 @error('nis') input-error @enderror"
                                value="{{ old('nis', $user->nis ?? '') }}" placeholder="Contoh: 12345" maxlength="10"> 
                        </div>
                        @if(empty($user->nis))
                            <p class="text-xs text-gray-500 mt-1">⚠️ Kosongkan jika belum punya</p>
                        @endif
                        @error('nis') <p class="error-message"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                    </div>

                    {{-- Kelas (Editable) --}}
                    <div>
                        <label class="form-label">Kelas</label>
                        <div class="relative">
                            <span class="input-icon left-3"><i class="fas fa-chalkboard-teacher"></i></span>
                            <select name="kelas" id="kelas" class="select-field pl-10 @error('kelas') input-error @enderror" required>
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

                    {{-- Jurusan (Editable) --}}
                    <div>
                        <label class="form-label">Jurusan</label>
                         <div class="relative">
                            <span class="input-icon left-3"><i class="fas fa-book-reader"></i></span>
                            <select name="jurusan" id="jurusan" class="select-field pl-10 @error('jurusan') input-error @enderror" required>
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

                    {{-- Nomor Telepon Siswa (Editable) --}}
                    <div>
                        <label class="form-label">No. Telepon Siswa (WA)</label> 
                        <div class="relative flex items-center">
                            <span class="prefix">+62</span>
                             {{-- [FIX] Style kalkulasi ikon diperbaiki --}}
                             <span class="input-icon" style="left: 3.7rem;"><i class="fas fa-mobile-alt"></i></span> 
                            {{-- [FIX] ltrim diperbaiki, maxlength ditambah --}}
                            <input type="text" name="no_telp_siswa" id="no_telp_siswa" class="input-field rounded-l-none pl-11 @error('no_telp_siswa') input-error @enderror" 
                                   value="{{ old('no_telp_siswa', ltrim($user->no_telp_siswa, '62')) }}" placeholder="812xxxxxxx" required inputmode="tel" maxlength="12">
                        </div>
                        {{-- [BARU] Helper text untuk validasi '0' --}}
                        <p id="no_telp_siswa_helper" class="error-message" style="display: none;"><i class="fas fa-exclamation-circle mr-1"></i> Jangan awali dengan 0. Langsung masukkan angka 8.</p>
                        @error('no_telp_siswa') <p class="error-message"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                    </div>

                    {{-- Nomor Telepon Orang Tua (Editable) --}}
                    <div>
                        <label class="form-label">No. Telepon Orang Tua</label> 
                         <div class="relative flex items-center">
                            <span class="prefix">+62</span>
                            {{-- [FIX] Style kalkulasi ikon diperbaiki --}}
                            <span class="input-icon" style="left: 3.7rem;"><i class="fas fa-phone-alt"></i></span>
                            {{-- [FIX] ltrim diperbaiki, maxlength ditambah --}}
                            <input type="text" name="no_telp_ortu" id="no_telp_ortu" class="input-field rounded-l-none pl-11 @error('no_telp_ortu') input-error @enderror"
                                   value="{{ old('no_telp_ortu', ltrim($user->no_telp_ortu, '62')) }}" placeholder="812xxxxxxx" required inputmode="tel" maxlength="12">
                        </div>
                        {{-- [BARU] Helper text untuk validasi '0' --}}
                        <p id="no_telp_ortu_helper" class="error-message" style="display: none;"><i class="fas fa-exclamation-circle mr-1"></i> Jangan awali dengan 0. Langsung masukkan angka 8.</p>
                        @error('no_telp_ortu') <p class="error-message"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                    </div>

                    {{-- [BARU] Catatan Penting --}}
                    <div class="!mt-6 p-3.5 bg-yellow-50 border border-yellow-200 rounded-lg text-yellow-800 text-xs space-y-2">
                        <p class="font-bold flex items-center gap-1.5"><i class="fas fa-exclamation-triangle"></i> Catatan Penting:</p>
                        <ul class="list-disc list-inside pl-1 space-y-1">
                            <li>Data Nama Lengkap dan NISN tidak dapat diubah dari halaman ini.</li>
                            <li>Pastikan data Anda yang lain (NIS, Kelas, Jurusan, No. HP) sudah benar sebelum disimpan.</li>
                        </ul>
                    </div>

                    {{-- Tombol Simpan (Dengan Validasi JS) --}}
                    <button id="saveBtn" type="submit" disabled
                            class="submit-button w-full bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 text-white font-semibold py-2.5 rounded-lg shadow-lg transition-all duration-300 transform flex items-center justify-center gap-2 text-base opacity-60 cursor-not-allowed">
                        <i class="fas fa-save"></i> Simpan Perubahan
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

/* === Card Styling Baru (Adaptasi Light Theme) === */
.card {
    --primary-glow: hsla(197, 71%, 73%, 0.8); /* Cyan */
    --secondary-glow: hsla(158, 64%, 73%, 0.8); /* Emerald */
    --card-bg: rgba(255, 255, 255, 0.85); /* Background Card */
    --card-shadow: rgba(59, 130, 246, 0.1); /* Shadow biru halus */
    --border-line: rgba(200, 210, 225, 0.5); /* Warna border halus */
    
    position: relative;
    border-radius: 1.25rem; /* rounded-2xl */
    overflow: hidden; /* Sembunyikan overflow border */
    box-shadow: 0 10px 30px -5px var(--card-shadow);
    z-index: 1; /* Agar di atas background */
}

/* Konten di dalam card */
.card .card__content {
    position: relative; 
    z-index: 2; /* Di atas ::before/::after */
    background-color: var(--card-bg);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 1.15rem; /* Sedikit lebih kecil */
    border: 1px solid var(--border-line); 
}

/* Pseudo-element untuk border berputar */
.card::before {
    content: "";
    pointer-events: none;
    position: absolute;
    z-index: -1; /* Di belakang konten */
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: calc(100% + 40px); /* Lebih besar untuk glow */
    height: calc(100% + 40px);
    
    background-image: conic-gradient(
        from var(--angle), 
        var(--glow-secondary), 
        var(--primary-glow), 
        var(--secondary-glow)
    );
    filter: blur(15px); /* Glow yang lebih intens */
    opacity: 0.6; /* Visibilitas glow */
    animation: rotate 8s linear infinite; /* Animasi putar */
}

@keyframes rotate { to { --angle: 360deg; } }
@property --angle { syntax: "<angle>"; initial-value: 0deg; inherits: false; }

/* === Form Styling (Disesuaikan) === */
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
.input-field[readonly] {
    background-color: #e5e7eb; 
    color: #6b7280; 
    cursor: not-allowed;
    box-shadow: none;
    border-color: #d1d5db;
}
.input-field[readonly]:focus {
    border-color: #d1d5db;
    box-shadow: none;
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
/* [FIX] Kalkulasi CSS diperbaiki agar lebih pas */
.prefix + .input-icon {
    left: 3.7rem; 
}

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

/* Select Dropdown */
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

/* Error Styling */
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

/* Tombol Submit */
.submit-button {
    background-size: 200% auto; 
    transition: all 0.4s ease-out; 
}
.submit-button:not(:disabled):hover {
    background-position: right center; 
    box-shadow: 0 4px 15px 0 rgba(45, 212, 191, 0.3); 
    transform: scale(1.03); 
}
.submit-button:disabled {
    /* [FIX] Style disabled disesuaikan agar lebih jelas */
    background-image: none;
    background-color: #9ca3af; /* Abu-abu */
    opacity: 0.6;
    cursor: not-allowed;
    box-shadow: none;
    transform: none;
}

</style>

{{-- ================================================== --}}
{{-- [BARU] Script gabungan untuk validasi '0' DAN tombol disabled --}}
{{-- ================================================== --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    
    // --- Bagian 1: Validasi Input Real-time ---

    function handlePhoneInput(inputId, helperId) {
        const input = document.getElementById(inputId);
        const helper = document.getElementById(helperId);
        if (!input) return;

        input.addEventListener('input', function(e) {
            let value = e.target.value;
            let numericValue = value.replace(/\D/g, ''); // Hapus non-digit

            if (numericValue.startsWith('0')) {
                numericValue = numericValue.substring(1); // Hapus 0 di depan
                helper.style.display = 'flex';
            } else {
                helper.style.display = 'none';
            }
            e.target.value = numericValue;
        });
    }

    handlePhoneInput('no_telp_siswa', 'no_telp_siswa_helper');
    handlePhoneInput('no_telp_ortu', 'no_telp_ortu_helper');

    const nisInput = document.getElementById('nis');
    if (nisInput) {
        nisInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9-]/g, ''); // Hanya angka dan strip
        });
    }

    // --- Bagian 2: Logika Tombol Disabled ---

    const form = document.getElementById('editProfileForm');
    const saveBtn = document.getElementById('saveBtn');
    
    // Hanya field yang bisa diedit
    const editableFields = form.querySelectorAll('input:not([readonly]), select'); 

    // Simpan nilai awal form
    const initialValues = {};
    editableFields.forEach(field => {
        initialValues[field.name] = field.value;
    });

    function checkChangesAndValidity() {
        let hasChanged = false;
        // Gunakan checkValidity() bawaan HTML5 untuk cek 'required', 'pattern', dll.
        let allValid = form.checkValidity(); 

        editableFields.forEach(field => {
            // Cek perubahan
            if (field.value !== initialValues[field.name]) {
                hasChanged = true;
            }
        });

        // Aktifkan tombol HANYA jika ada perubahan DAN semua field valid
        if (hasChanged && allValid) {
            saveBtn.disabled = false;
            saveBtn.classList.remove('opacity-60', 'cursor-not-allowed');
        } else {
            saveBtn.disabled = true;
            saveBtn.classList.add('opacity-60', 'cursor-not-allowed');
        }
    }

    // Dengarkan event input atau change pada setiap field
    editableFields.forEach(field => {
        field.addEventListener('input', checkChangesAndValidity);
        field.addEventListener('change', checkChangesAndValidity); // Untuk select
    });
    
    // Panggil check awal saat halaman load (tombol harus disabled awalnya)
    // Kita panggil di sini agar 'allValid' bisa langsung cek form
    checkChangesAndValidity(); 
});
</script>
@endsection
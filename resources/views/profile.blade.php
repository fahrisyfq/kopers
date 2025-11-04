{{-- filepath: c:\xampp3\htdocs\assesmen\resources\views\profile.blade.php --}}
@extends('layout.app')

@section('title', 'Profile Perusahaan')

@section('content')
<section class="mt-16 max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-8 mb-10">
        <h1 class="text-3xl font-bold text-center mb-4 text-indigo-700 animate-fade-in">Profil Perusahaan HAVIKA</h1>
        <p class="text-gray-700 text-center mb-6 animate-fade-in-slow">
            HAVIKA adalah perusahaan yang bergerak di bidang jasa dan solusi teknologi, berkomitmen memberikan layanan terbaik untuk klien di seluruh Indonesia. Kami mengedepankan profesionalisme, inovasi, dan integritas dalam setiap layanan yang kami berikan.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h2 class="text-xl font-semibold text-indigo-600 mb-2">Tentang Kami</h2>
                <p class="text-gray-600">
                    Berdiri sejak 2020, HAVIKA telah melayani berbagai klien dari sektor pemerintahan, pendidikan, hingga UMKM. Kami percaya bahwa teknologi adalah kunci kemajuan, dan kami siap menjadi mitra transformasi digital Anda.
                </p>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-indigo-600 mb-2">Nilai Perusahaan</h2>
                <ul class="list-disc list-inside text-gray-600">
                    <li>Inovasi berkelanjutan</li>
                    <li>Transparansi dan kejujuran</li>
                    <li>Kerja tim dan kolaborasi</li>
                    <li>Fokus pada kepuasan pelanggan</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Struktur Organisasi --}}
    <div class="bg-white rounded-lg shadow p-8 mb-10">
        <h2 class="text-2xl font-bold text-center mb-8 text-indigo-700 animate-fade-in">Struktur Organisasi HAVIKA</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 text-center mb-12">
            {{-- CEO --}}
            <div class="relative group team-card border-0 rounded-2xl p-8 shadow-xl bg-gradient-to-br from-indigo-50 via-white to-indigo-100 transition-all duration-300 hover:scale-105 hover:shadow-2xl animate-fade-in">
                <span class="absolute top-4 left-4 bg-indigo-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow">CEO</span>
                <img src="images/ulp.jpg" alt="Roqib Ahdiyaka Kusuma" class="w-28 h-28 mx-auto rounded-full border-4 border-indigo-400 shadow-lg ring-4 ring-indigo-200 group-hover:ring-indigo-400 transition mb-4">
                <div class="font-extrabold text-xl text-indigo-700 mb-1 drop-shadow">Roqib Ahdiyaka Kusuma</div>
                <div class="text-base text-gray-700 font-medium">CEO</div>
            </div>
            {{-- Direktur --}}
            <div class="relative group team-card border-0 rounded-2xl p-8 shadow-xl bg-gradient-to-br from-indigo-50 via-white to-indigo-100 transition-all duration-300 hover:scale-105 hover:shadow-2xl animate-fade-in">
                <span class="absolute top-4 left-4 bg-indigo-400 text-white text-xs font-bold px-3 py-1 rounded-full shadow">Direktur</span>
                <img src="images/ulp.jpg" alt="Muhammad Akmal Zaidan" class="w-28 h-28 mx-auto rounded-full border-4 border-indigo-300 shadow-lg ring-4 ring-indigo-100 group-hover:ring-indigo-400 transition mb-4">
                <div class="font-extrabold text-xl text-indigo-700 mb-1 drop-shadow">Muhammad Akmal Zaidan</div>
                <div class="text-base text-gray-700 font-medium">Direktur</div>
            </div>
            {{-- Sekretaris 1 --}}
            <div class="relative group team-card border-0 rounded-2xl p-8 shadow-xl bg-gradient-to-br from-yellow-50 via-white to-yellow-100 transition-all duration-300 hover:scale-105 hover:shadow-2xl animate-fade-in">
                <span class="absolute top-4 left-4 bg-yellow-400 text-white text-xs font-bold px-3 py-1 rounded-full shadow">Sekretaris 1</span>
                <img src="images/sekre1.jpg" alt="Selviana Puji Rahayu" class="w-28 h-28 mx-auto rounded-full border-4 border-yellow-400 shadow-lg ring-4 ring-yellow-200 group-hover:ring-yellow-400 transition mb-4">
                <div class="font-extrabold text-xl text-yellow-700 mb-1 drop-shadow">Selviana Puji Rahayu</div>
                <div class="text-base text-gray-700 font-medium">Sekretaris 1</div>
            </div>
            {{-- Sekretaris 2 --}}
            <div class="relative group team-card border-0 rounded-2xl p-8 shadow-xl bg-gradient-to-br from-yellow-50 via-white to-yellow-100 transition-all duration-300 hover:scale-105 hover:shadow-2xl animate-fade-in">
                <span class="absolute top-4 left-4 bg-yellow-400 text-white text-xs font-bold px-3 py-1 rounded-full shadow">Sekretaris 2</span>
                <img src="images/sekre2.jpg" alt="Septiana Adinda" class="w-28 h-28 mx-auto rounded-full border-4 border-yellow-400 shadow-lg ring-4 ring-yellow-200 group-hover:ring-yellow-400 transition mb-4">
                <div class="font-extrabold text-xl text-yellow-700 mb-1 drop-shadow">Septiana Adinda</div>
                <div class="text-base text-gray-700 font-medium">Sekretaris 2</div>
            </div>
            {{-- Bendahara 1 --}}
            <div class="relative group team-card border-0 rounded-2xl p-8 shadow-xl bg-gradient-to-br from-pink-50 via-white to-pink-100 transition-all duration-300 hover:scale-105 hover:shadow-2xl animate-fade-in">
                <span class="absolute top-4 left-4 bg-pink-400 text-white text-xs font-bold px-3 py-1 rounded-full shadow">Bendahara 1</span>
                <img src="images/ben1.jpg" alt="Putri Paramesthi" class="w-28 h-28 mx-auto rounded-full border-4 border-pink-400 shadow-lg ring-4 ring-pink-200 group-hover:ring-pink-400 transition mb-4">
                <div class="font-extrabold text-xl text-pink-700 mb-1 drop-shadow">Paramesthi Nindya Nirmala</div>
                <div class="text-base text-gray-700                             font-medium">Bendahara 1</div>
            </div>
            {{-- Bendahara 2 --}}
            <div class="relative group team-card border-0 rounded-2xl p-8 shadow-xl bg-gradient-to-br from-pink-50 via-white to-pink-100 transition-all duration-300 hover:scale-105 hover:shadow-2xl animate-fade-in">
                <span class="absolute top-4 left-4 bg-pink-400 text-white text-xs font-bold px-3 py-1 rounded-full shadow">Bendahara 2</span>
                <img src="images/ben2.jpg" alt="Nindya N. Safira Angraini" class="w-28 h-28 mx-auto rounded-full border-4 border-pink-400 shadow-lg ring-4 ring-pink-200 group-hover:ring-pink-400 transition mb-4">
                <div class="font-extrabold text-xl text-pink-700 mb-1 drop-shadow">Nindya N. Safira Angraini</div>
                <div class="text-base text-gray-700 font-medium">Bendahara 2</div>
            </div>
            {{-- Kepala Divisi --}}
            <div class="relative group team-card border-0 rounded-2xl p-8 shadow-xl bg-gradient-to-br from-green-50 via-white to-green-100 transition-all duration-300 hover:scale-105 hover:shadow-2xl animate-fade-in">
                <span class="absolute top-4 left-4 bg-green-400 text-white text-xs font-bold px-3 py-1 rounded-full shadow">Kepala Divisi</span>
                <img src="images/ulp.webp" alt="Radittya Saputra" class="w-28 h-28 mx-auto rounded-full border-4 border-green-400 shadow-lg ring-4 ring-green-200 group-hover:ring-green-400 transition mb-4">
                <div class="font-extrabold text-xl text-green-700 mb-1 drop-shadow">Radittya Saputra</div>
                <div class="text-base text-gray-700 font-medium">Kepala Divisi MICE</div>
            </div>
            <div class="relative group team-card border-0 rounded-2xl p-8 shadow-xl bg-gradient-to-br from-green-50 via-white to-green-100 transition-all duration-300 hover:scale-105 hover:shadow-2xl animate-fade-in">
                <span class="absolute top-4 left-4 bg-green-400 text-white text-xs font-bold px-3 py-1 rounded-full shadow">Kepala Divisi</span>
                <img src="images/IT.webp" alt="Radittya Saputra" class="w-28 h-28 mx-auto rounded-full border-4 border-green-400 shadow-lg ring-4 ring-green-200 group-hover:ring-green-400 transition mb-4">
                <div class="font-extrabold text-xl text-green-700 mb-1 drop-shadow">I Gusti Agung Linggam P K</div>
                <div class="text-base text-gray-700 font-medium">Kepala Divisi IT</div>
            </div>
            <div class="relative group team-card border-0 rounded-2xl p-8 shadow-xl bg-gradient-to-br from-green-50 via-white to-green-100 transition-all duration-300 hover:scale-105 hover:shadow-2xl animate-fade-in">
                <span class="absolute top-4 left-4 bg-green-400 text-white text-xs font-bold px-3 py-1 rounded-full shadow">Kepala Divisi</span>
                <img src="images/BR.jpg" alt="Radittya Saputra" class="w-28 h-28 mx-auto rounded-full border-4 border-green-400 shadow-lg ring-4 ring-green-200 group-hover:ring-green-400 transition mb-4">
                <div class="font-extrabold text-xl text-green-700 mb-1 drop-shadow">Satrio Langlang Buana</div>
                <div class="text-base text-gray-700 font-medium">Kepala Divisi Marketing</div>
            </div>
            <div class="relative group team-card border-0 rounded-2xl p-8 shadow-xl bg-gradient-to-br from-green-50 via-white to-green-100 transition-all duration-300 hover:scale-105 hover:shadow-2xl animate-fade-in">
                <span class="absolute top-4 left-4 bg-green-400 text-white text-xs font-bold px-3 py-1 rounded-full shadow">Kepala Divisi</span>
                <img src="images/acc.jpg" alt="Radittya Saputra" class="w-28 h-28 mx-auto rounded-full border-4 border-green-400 shadow-lg ring-4 ring-green-200 group-hover:ring-green-400 transition mb-4">
                <div class="font-extrabold text-xl text-green-700 mb-1 drop-shadow">Rahma Zulaekha</div>
                <div class="text-base text-gray-700 font-medium">Kepala Divisi Accounting</div>
            </div>
            <div class="relative group team-card border-0 rounded-2xl p-8 shadow-xl bg-gradient-to-br from-green-50 via-white to-green-100 transition-all duration-300 hover:scale-105 hover:shadow-2xl animate-fade-in">
                <span class="absolute top-4 left-4 bg-green-400 text-white text-xs font-bold px-3 py-1 rounded-full shadow">Kepala Divisi</span>
                <img src="images/adm.jpg" alt="Radittya Saputra" class="w-28 h-28 mx-auto rounded-full border-4 border-green-400 shadow-lg ring-4 ring-green-200 group-hover:ring-green-400 transition mb-4">
                <div class="font-extrabold text-xl text-green-700 mb-1 drop-shadow">Rani Ramadani</div>
                <div class="text-base text-gray-700 font-medium">Kepala Divisi Administrasi</div>
            </div>
            {{-- Tambahkan kepala divisi lain jika ada --}}
        </div>
    </div>

    {{-- ...existing code... --}}

{{-- Anggota Divisi --}}
<h3 class="text-2xl font-semibold text-center mb-10 text-indigo-700 animate-fade-in">Anggota Divisi</h3>

{{-- Divisi Accounting --}}
<h4 class="text-lg font-bold text-yellow-600 mb-4 mt-10">Divisi Accounting</h4>
<ul class="list-disc list-inside text-gray-700 mb-8">
    <li>Salsabil Ulfah<span class="text-xs text-gray-500">(Staff Accounting)</span></li>
    <li>Raisyah Putri Ramahdani<span class="text-xs text-gray-500">(Staff Accounting)</span></li>
        <li>Rahma Zulaekha<span class="text-xs text-gray-500">(Staff Accounting)</span></li>
        <li>Paramesthi Nindya Nirmala<span class="text-xs text-gray-500">(Staff Accounting)</span></li>
        <li>Safira Anggraini<span class="text-xs text-gray-500">(Staff Accounting)</span></li>
        <li>Nurul Marhamah<span class="text-xs text-gray-500">(Staff Accounting)</span></li>
        <li>Octavianty Fitria<span class="text-xs text-gray-500">(Staff Accounting)</span></li>
        <li>Radian Geovani Cahyo Anggoro<span class="text-xs text-gray-500">(Staff Accounting)</span></li>
        <li>Rahmat Satria Wahyoto<span class="text-xs text-gray-500">(Staff Accounting)</span></li>
        <li>Rahmi Nur Fatimah<span class="text-xs text-gray-500">(Staff Accounting)</span></li>
        <li>Raisya Khairrunisyah<span class="text-xs text-gray-500">(Staff Accounting)</span></li>
        <li>Ovita Khairunnisa Abduh<span class="text-xs text-gray-500">(Staff Accounting)</span></li>
    {{-- Tambah anggota lain di sini --}}
</ul>

{{-- Divisi Administrasi --}}
<h4 class="text-lg font-bold text-pink-600 mb-4 mt-10">Divisi Administrasi</h4>
<ul class="list-disc list-inside text-gray-700 mb-8">
    <li>Rani Ramadani<span class="text-xs text-gray-500">(Staff Administrasi)</span></li>
    <li>Septiana Adinda Putri<span class="text-xs text-gray-500">(Staff Administrasi)</span></li>
    <li>Roqib Ahdiyaka Kusuma<span class="text-xs text-gray-500">(Staff Administrasi)</span></li>
    <li>Selviana Puji Rahayu<span class="text-xs text-gray-500">(Staff Administrasi)</span></li>
    <li>Shendy Paramitha Azzahra<span class="text-xs text-gray-500">(Staff Administrasi)</span></li>
    {{-- Tambah anggota lain di sini --}}
</ul>

{{-- Divisi MICE --}}
<h4 class="text-lg font-bold text-green-600 mb-4 mt-10">Divisi MICE</h4>
<ul class="list-disc list-inside text-gray-700 mb-8">
    <li>Muhammad Akmal Zayyidan<span class="text-xs text-gray-500">(Staff MICE)</span></li>
    <li>Radittya Saputra<span class="text-xs text-gray-500">(Staff MICE)</span></li>
    <li>Syakila Hanifa<span class="text-xs text-gray-500">(Staff MICE)</span></li>
    <li>Kayla Putri Agustin<span class="text-xs text-gray-500">(Staff MICE)</span></li>
    {{-- Tambah anggota lain di sini --}}
</ul>

{{-- Divisi Marketing --}}
<h4 class="text-lg font-bold text-blue-600 mb-4 mt-10">Divisi Marketing</h4>
<ul class="list-disc list-inside text-gray-700 mb-8">
    <li>Hafizh Nabil Amro<span class="text-xs text-gray-500">(Staff Marketing)</span></li>
    <li>Neysha Azhalia<span class="text-xs text-gray-500">(Staff Marketing)</span></li>
    <li>Putri Adila Chairani<span class="text-xs text-gray-500">(Staff Marketing)</span></li>
    <li>Raisha Halira<span class="text-xs text-gray-500">(Staff Marketing)</span></li>
    <li>Naila Gusti Rahma<span class="text-xs text-gray-500">(Staff Marketing)</span></li>
    <li>Pita sadewi<span class="text-xs text-gray-500">(Staff Marketing)</span></li>
    <li>Radja Destra<span class="text-xs text-gray-500">(Staff Marketing)</span></li>
    <li>Raisya Althafunnisa<span class="text-xs text-gray-500">(Staff Marketing)</span></li>
    <li>Samsi Tabriz Sangaji<span class="text-xs text-gray-500">(Staff Marketing)</span></li>
    <li>Sasya Salsabilah<span class="text-xs text-gray-500">(Staff Marketing)</span></li>
    <li>Satrio Langlang Buana<span class="text-xs text-gray-500">(Staff Marketing)</span></li>
    {{-- Tambah anggota lain di sini --}}
</ul>

{{-- Divisi IT --}}
<h4 class="text-lg font-bold text-indigo-600 mb-4 mt-10">Divisi IT</h4>
<ul class="list-disc list-inside text-gray-700 mb-8">
    <li>Ahmad Fadli Apriansyah<span class="text-xs text-gray-500">(Staff IT)</span></li>
    <li>Athiyyah Syifa<span class="text-xs text-gray-500">(Staff IT)</span></li>
    <li>Fahri Ahmad Syafiq<span class="text-xs text-gray-500">(Staff IT)</span></li>
    <li>I Gusti Agung Linggam P.K<span class="text-xs text-gray-500">(Staff IT)</span></li>
    {{-- Tambah anggota lain di sini --}}
</ul>

{{-- ...existing code... --}}
</section>

{{-- Animasi sederhana dengan Tailwind --}}
<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px);}
    to { opacity: 1; transform: translateY(0);}
}
.animate-fade-in { animation: fade-in 1s ease; }
.animate-fade-in-slow { animation: fade-in 1.5s ease; }
</style>
@endsection
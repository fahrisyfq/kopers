<x-filament-panels::page>

    {{-- [DIUBAH] Menggunakan AlpineJS untuk modal filter --}}
    <div x-data="{ isFilterOpen: false }">

        {{-- 1. BAGIAN HEADER & TOMBOL FILTER --}}
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Pesanan Siswa</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tinjau semua pesanan yang masuk berdasarkan filter.</p>
            </div>
            {{-- Tombol untuk membuka modal filter --}}
            <x-filament::button
                icon="heroicon-o-funnel"
                color="gray"
                @click="isFilterOpen = true"
            >
                Filter
            </x-filament::button>
        </div>

        {{-- 2. BAGIAN TOTAL (KARTU STATISTIK) --}}
        <div class="p-4 bg-white rounded-xl shadow-sm dark:bg-gray-800 border dark:border-gray-700 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        @php
                            // Membuat label dinamis berdasarkan filter yang aktif
                            $filterLabel = 'Total Siswa Ditemukan'; // Default
                            if ($this->filterStatus) {
                                $filterLabel = 'Total Siswa (Status: ' . ($this->opsiStatus[$this->filterStatus] ?? $this->filterStatus) . ')';
                            } elseif ($this->filterProduk) {
                                $filterLabel = 'Total Siswa (Produk: ' . ($this->opsiProduk[$this->filterProduk] ?? $this->filterProduk) . ')';
                            } elseif ($this->filterUkuran) {
                                $filterLabel = 'Total Siswa (Ukuran: ' . ($this->opsiUkuran[$this->filterUkuran] ?? $this->filterUkuran) . ')';
                            } elseif ($this->filterKelas) {
                                $filterLabel = 'Total Siswa (Kelas: ' . $this->filterKelas . ')';
                            } elseif ($this->filterJurusan) {
                                $filterLabel = 'Total Siswa (Jurusan: ' . $this->filterJurusan . ')';
                            }
                        @endphp
                        {{ $filterLabel }}
                    </h3>
                </div>
                <div class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                    {{ $totalSiswa }}
                </div>
            </div>
        </div>

        {{-- 3. BAGIAN DAFTAR SISWA & PRODUK (DESAIN BARU) --}}
        <div class="mt-6 space-y-6">
            @forelse($users as $user)
                
                {{-- [DESAIN BARU] Header Siswa --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border dark:border-gray-700 overflow-hidden">
                    <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between bg-gray-50 dark:bg-gray-700/50 border-b dark:border-gray-700">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $user->nama_lengkap }}</h3>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-gray-500 dark:text-gray-400 mt-1">
                                <span class="whitespace-nowrap"><i class="fas fa-graduation-cap fa-fw mr-1 text-gray-400"></i> {{ $user->kelas }} - {{ $user->jurusan }}</span>
                                <span class="whitespace-nowrap"><i class="fas fa-id-card fa-fw mr-1 text-gray-400"></i> NISN: {{ $user->nisn }}</span>
                                <span class="whitespace-nowrap"><i class="fas fa-id-badge fa-fw mr-1 text-gray-400"></i> NIS: {{ $user->nis }}</span>
                            </div>
                        </div>
                        
                        {{-- ====================================================== --}}
                        {{-- [PERBAIKAN] Menggunakan <x-filament::button> --}}
                        {{-- ====================================================== --}}
                        <div class="flex-shrink-0 flex gap-2 mt-3 sm:mt-0">
                            @if($user->no_telp_siswa)
                                <x-filament::button
                                    tag="a"
                                    href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->no_telp_siswa) }}"
                                    target="_blank"
                                    color="info" {{-- info = biru --}}
                                    size="xs"
                                >
                                    <x-slot name="icon">
                                        <i class="fab fa-whatsapp"></i>
                                    </x-slot>
                                    Hubungi Siswa
                                </x-filament::button>
                            @endif
                            @if($user->no_telp_ortu)
                                <x-filament::button
                                    tag="a"
                                    href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->no_telp_ortu) }}"
                                    target="_blank"
                                    color="success" {{-- success = hijau --}}
                                    size="xs"
                                >
                                    <x-slot name="icon">
                                        <i class="fab fa-whatsapp"></i>
                                    </x-slot>
                                    Hubungi Ortu
                                </x-filament::button>
                            @endif
                        </div>
                        {{-- ====================================================== --}}
                        {{-- AKHIR PERBAIKAN --}}
                        {{-- ====================================================== --}}
                    </div>

                    {{-- [DESAIN BARU] Daftar Pesanan (Grup berdasarkan Order) --}}
                    <div class="p-4 sm:p-5">
                        @if($user->orders->isEmpty())
                            <span class="text-sm text-gray-400 italic">
                                @if($this->filterStatus || $this->filterProduk || $this->filterUkuran)
                                    Tidak ada produk yang cocok dengan filter untuk siswa ini.
                                @else
                                    Siswa ini belum memesan produk.
                                @endif
                            </span>
                        @else
                            <div class="space-y-4">
                                @foreach($user->orders as $order)
                                    {{-- Wrapper per Pesanan --}}
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                        
                                        {{-- Header Pesanan (Tanggal, Status) --}}
                                        <div class="bg-gray-100 dark:bg-gray-700/50 px-4 py-2 flex flex-col sm:flex-row justify-between sm:items-center text-sm">
                                            <div class="text-gray-700 dark:text-gray-200 font-medium">
                                                <i class="fas fa-calendar-alt fa-fw mr-1.5 text-gray-400"></i>
                                                Tanggal Pesanan:
                                                <span class="font-semibold text-emerald-600 dark:text-emerald-400">
                                                    {{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-2 mt-1 sm:mt-0">
                                                {{-- Badge Status --}}
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                    @switch($order->payment_status)
                                                        @case('pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @break
                                                        @case('paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @break
                                                        @case('cash') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @break
                                                        @case('cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @break
                                                        @default bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                                    @endswitch
                                                ">
                                                    {{ ucfirst($order->payment_status) }}
                                                </span>
                                                {{-- Badge Metode --}}
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-200 text-gray-700 dark:bg-gray-600 dark:text-gray-200">
                                                    {{ ucfirst($order->payment_method) }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Daftar Item dalam Pesanan Ini --}}
                                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($order->items as $item)
                                                <li class="py-3 px-4">
                                                    {{-- Baris 1: Judul & Harga --}}
                                                    <div class="flex items-center justify-between">
                                                        <span class="font-semibold text-gray-800 dark:text-gray-200">
                                                            {{ $item->product?->title ?? 'Produk Dihapus' }}
                                                        </span>
                                                        <span class="font-semibold text-gray-800 dark:text-gray-200 text-sm">
                                                            Rp{{ number_format($item->price, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                    {{-- Baris 2: Info & Status Stok --}}
                                                    <div class="flex items-center justify-between mt-1">
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            <span>Qty: {{ $item->quantity }}</span>
                                                            @if($item->size)
                                                                <span class="mx-1">|</span>
                                                                <span>Ukuran: {{ $item->size->size }}</span>
                                                            @endif
                                                        </div>
                                                        @if(isset($item->product_status))
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                                                {{ $item->product_status === 'Pre-Order' 
                                                                    ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' 
                                                                    : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                                                                {{ $item->product_status }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center p-8 bg-white rounded-lg shadow-sm dark:bg-gray-800 border dark:border-gray-700">
                    <i class="fas fa-search text-4xl text-gray-400 mb-3"></i>
                    <p class="font-semibold text-gray-700 dark:text-gray-200">Tidak ada siswa yang ditemukan</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Coba ubah filter atau kata kunci pencarian Anda.</p>
                </div>
            @endforelse

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>

        {{-- 4. MODAL FILTER (BARU) --}}
        <div x-show="isFilterOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 flex items-start justify-center bg-black/50 backdrop-blur-sm p-4"
             @keydown.escape.window="isFilterOpen = false"
             style="display: none;"
             x-cloak>

            <div @click.outside="isFilterOpen = false"
                 x-show="isFilterOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-4"
                 class="w-full max-w-3xl bg-white dark:bg-gray-800 rounded-xl shadow-lg mt-20">
                
                {{-- Header Modal --}}
                <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Filter Pesanan</h3>
                    <button @click="isFilterOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                    </button>
                </div>

                {{-- Body Modal (Isi Filter) --}}
                <div class="p-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Filter Siswa --}}
                        <div>
                            <label for="filterKelasModal" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Filter Kelas</label>
                            <select wire:model.live="filterKelas" id="filterKelasModal" 
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Semua Kelas</option>
                                @foreach($this->opsiKelas as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="filterJurusanModal" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Filter Jurusan</label>
                            <select wire:model.live="filterJurusan" id="filterJurusanModal" 
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Semua Jurusan</option>
                                @foreach($this->opsiJurusan as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="searchNamaModal" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Cari Nama Siswa</label>
                            <input wire:model.live.debounce.300ms="searchNama" id="searchNamaModal" type="text" placeholder="Ketik nama siswa..." 
                                   class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Filter Pesanan --}}
                        <div>
                            <label for="filterStatusModal" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Filter Status</label>
                            <select wire:model.live="filterStatus" id="filterStatusModal" 
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Semua Status</option>
                                @foreach($this->opsiStatus as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="filterProdukModal" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Filter Produk</label>
                            <select wire:model.live="filterProduk" id="filterProdukModal" 
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Semua Produk</option>
                                @foreach($this->opsiProduk as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="filterUkuranModal" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Filter Ukuran</label>
                            <select wire:model.live="filterUkuran" id="filterUkuranModal" 
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Semua Ukuran</option>
                                @foreach($this->opsiUkuran as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="filterWaktu" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            Filter Waktu Penjualan
                        </label>
                        <select wire:model.live="filterWaktu" id="filterWaktu"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm 
                                   focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm 
                                   dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Semua Waktu</option>
                            @foreach($this->opsiWaktu as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Footer Modal --}}
                <div class="p-4 bg-gray-50 dark:bg-gray-800/50 border-t dark:border-gray-700 flex justify-end gap-3">
                    <x-filament::button
                        color="gray"
                        @click="isFilterOpen = false"
                    >
                        Tutup
                    </x-filament::button>
                </div>
            </div>
        </div>

    </div>
</x-filament-panels::page>
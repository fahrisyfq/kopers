{{-- [PERBAIKAN 1] Tambahkan x-data untuk modal pop-up --}}
<x-filament::page
    x-data="{
        proofModalOpen: false,
        currentProofUrl: ''
    }"
>
    <div class="space-y-6">
        {{-- 🔹 Header --}}
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                📦 Daftar Siswa dengan Produk Pre-Order
            </h2>
            <div class="text-sm text-gray-500">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>

        {{-- 🔹 Filter Dropdown & Search Bar (Dengan Indikator Loading) --}}
        <div wire:loading.class.delay="opacity-50" class="flex flex-wrap items-end gap-4 bg-gray-50 p-4 rounded-xl border border-gray-200 shadow-sm">
            {{-- Filter Kelas --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Filter Kelas</label>
                <select
                    wire:model.live="selectedKelas"
                    class="text-sm border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-300 focus:border-blue-400 shadow-sm"
                >
                    <option value="">Semua Kelas</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="Belum Ditentukan">Belum Ditentukan</option>
                </select>
            </div>

            {{-- Filter Jurusan --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Filter Jurusan</label>
                <select
                    wire:model.live="selectedJurusan"
                    class="text-sm border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-300 focus:border-blue-400 shadow-sm"
                >
                    <option value="">Semua Jurusan</option>
                    <option value="AKL 1">AKL 1</option>
                    <option value="AKL 2">AKL 2</option>
                    <option value="AKL 3">AKL 3</option>
                    <option value="MP 1">MP 1</option>
                    <option value="Manlog">Manlog</option>
                    <option value="BR 1">BR 1</option>
                    <option value="BR 2">BR 2</option>
                    <option value="BD">BD</option>
                    <option value="UPW">UPW</option>
                    <option value="RPL">RPL</option>
                    <option value="Belum Ditentukan">Belum Ditentukan</option>
                </select>
            </div>

            {{-- 🔍 Search Nama Siswa --}}
            <div class="flex-1 min-w-[250px] relative">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Cari Nama Siswa</label>
                <div class="relative">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="searchTerm"
                        placeholder="Ketik nama siswa..."
                        class="w-full text-sm border-gray-300 rounded-lg pl-10 pr-4 py-2 focus:ring-blue-300 focus:border-blue-400 shadow-sm"
                    >
                    <x-heroicon-o-magnifying-glass class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" />
                </div>
            </div>
            
            {{-- Indikator Loading --}}
            <div wire:loading.delay wire:target="selectedKelas, selectedJurusan, searchTerm" class="text-blue-600">
                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>

        {{-- 🔹 Tidak ada data (Desain Baru) --}}
        @if($usersWithPreOrders->isEmpty())
            <div class="flex flex-col items-center justify-center p-12 bg-white rounded-xl shadow-sm border border-gray-100 text-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <x-heroicon-o-exclamation-triangle class="w-8 h-8" />
                </div>
                <h3 class="mt-4 text-lg font-bold text-gray-800">Tidak Ada Pre-Order Ditemukan</h3>
                <p class="mt-1 text-sm text-gray-500">Tidak ada siswa yang cocok dengan filter Anda saat ini.</p>
            </div>
        @else
            {{-- 🔹 Tampilan Kartu (Desain Baru) --}}
            <div class="space-y-6">
                @foreach ($usersWithPreOrders as $user)
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 divide-y divide-gray-200 overflow-hidden">
                        
                        {{-- Header Kartu: Info Siswa & Tombol Kontak --}}
                        <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $user->nama_lengkap }}</h3>
                                <div class="mt-1 flex items-center flex-wrap gap-x-3 gap-y-1 text-sm text-gray-600">
                                    <span>
                                        <x-heroicon-s-academic-cap class="w-4 h-4 inline -mt-0.5 mr-1 text-gray-400" />
                                        {{ $user->kelas ?? '?' }} - {{ $user->jurusan ?? '?' }}
                                    </span>
                                    <span class="hidden sm:inline">&middot;</span>
                                    <span>
                                        <x-heroicon-s-identification class="w-4 h-4 inline -mt-0.5 mr-1 text-gray-400" />
                                        NISN: <span class="font-medium text-gray-800">{{ $user->nisn ?? '-' }}</span>
                                        
                                        {{-- [PERBAIKAN 2] NIS DITAMBAHKAN KEMBALI --}}
                                        <span class="text-gray-300 mx-1">|</span>
                                        NIS: <span class="font-medium text-gray-800">{{ $user->nis ?? '-' }}</span>
                                    </span>
                                </div>
                            </div>
                            
                            {{-- Tombol Kontak --}}
                            <div class="flex-shrink-0 flex items-center gap-2">
                                <button 
                                    wire:click="contactUser({{ $user->id }})"
                                    class="flex items-center gap-1.5 text-xs font-semibold px-3 py-2 rounded-lg text-white shadow-md hover:shadow-lg transition-all duration-200 active:scale-95"
                                    style="background: linear-gradient(to right, #2563eb, #3b82f6);"
                                >
                                    <x-heroicon-o-user class="w-4 h-4 text-white" />
                                    Hubungi Siswa
                                </button>
                                <button 
                                    wire:click="contactParent({{ $user->id }})"
                                    class="flex items-center gap-1.5 text-xs font-semibold px-3 py-2 rounded-lg text-white shadow-md hover:shadow-lg transition-all duration-200 active:scale-95"
                                    style="background: linear-gradient(to right, #16a34a, #22c55e);"
                                >
                                    <x-heroicon-o-chat-bubble-left-right class="w-4 h-4 text-white" />
                                    Hubungi Ortu
                                </button>
                            </div>
                        </div>

                        {{-- Body Kartu: Daftar Pre-Order --}}
                        <div class="p-4 sm:p-5 space-y-3 bg-gray-50/50">
                            @foreach ($user->orders as $order)
                                @foreach ($order->items as $item)
                                    <div class="flex items-start gap-3 p-3.5 bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-xl shadow-sm transition hover:shadow-md">
                                        
                                        {{-- Checkbox --}}
                                        <input
                                            type="checkbox"
                                            wire:model="selectedItems.{{ $item->id }}"
                                            class="mt-1 h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-400 focus:ring-offset-0"
                                        >
                                        
                                        {{-- Info Produk (Kiri) --}}
                                        <div class="flex-1 min-w-0">

                                            {{-- 🕒 Tambahan: Waktu Pesanan --}}
                                            <div class="text-xs text-gray-500 mb-1">
                                                <x-heroicon-o-clipboard-document-list class="w-4 h-4 inline -mt-0.5 mr-1 text-gray-400" />
                                                Tanggal Pesanan:
                                                <span class="font-medium text-gray-700">
                                                    {{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d M Y, H:i') }} WIB
                                                </span>
                                            </div>

                                            <div class="font-semibold text-gray-900 truncate">
                                                {{ $item->product->title ?? '-' }}
                                            </div>
                                            @if($item->productSize)
                                                <div class="text-xs text-gray-600">
                                                    Ukuran: {{ $item->productSize->size }}
                                                </div>
                                            @endif
                                            <div class="text-sm text-gray-700 mt-1">
                                                <span class="font-medium text-gray-800">
                                                    Rp{{ number_format($item->price ?? 0, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Info Status (Kanan) --}}
                                        <div class="text-right space-y-2 flex-shrink-0 ml-4 flex flex-col items-end">
                                            
                                            {{-- Badge Metode Pembayaran --}}
                                            <div>
                                                @php
                                                    $method = $order->payment_method;
                                                    $color = match($method) {
                                                        'cash' => 'bg-green-100 text-green-700',
                                                        'kjp' => 'bg-blue-100 text-blue-700',
                                                        'transfer_bank' => 'bg-yellow-100 text-yellow-700',
                                                        'e_wallet' => 'bg-purple-100 text-purple-700',
                                                        default => 'bg-gray-100 text-gray-700',
                                                    };
                                                    $label = match($method) {
                                                        'cash' => 'Cash',
                                                        'kjp' => 'KJP',
                                                        'transfer_bank' => 'Transfer',
                                                        'e_wallet' => 'E-Wallet',
                                                        default => $method,
                                                    };
                                                @endphp
                                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                                    {{ $label }}
                                                </span>
                                            </div>

                                            {{-- [PERBAIKAN 3] Link diubah jadi tombol modal --}}
                                            @if($order->proof_of_payment)
                                                <button 
                                                   type="button"
                                                   wire:key="proof-{{ $order->id }}"
                                                   @click="currentProofUrl = '{{ asset('storage/' . $order->proof_of_payment) }}'; proofModalOpen = true;"
                                                   class="text-xs text-blue-600 hover:underline font-medium flex items-center justify-end gap-1">
                                                    Lihat Bukti
                                                    <x-heroicon-o-eye class="w-3 h-3" />
                                                </button>
                                            @endif
                                            
                                            {{-- Dropdown Status Pembayaran --}}
                                            <select
                                                wire:model="statuses.{{ $order->id }}"
                                                wire:change="updateStatus({{ $order->id }})"
                                                class="text-xs bg-white border-gray-300 rounded-lg px-2 py-1 focus:ring focus:ring-blue-200 focus:border-blue-400 shadow-sm"
                                            >
                                                <option value="pending">Pending</option>
                                                <option value="cash">Cash</option>
                                                <option value="paid">Paid</option>
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- 🔹 Modal Lihat Bukti Pembayaran (BARU) --}}
    <div
        x-show="proofModalOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @keydown.escape.window="proofModalOpen = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4"
        style="display: none;"
        x-cloak
    >
        <div @click.outside="proofModalOpen = false" class="relative w-full max-w-lg rounded-xl shadow-2xl">
            {{-- Tombol 'X' Sesuai Permintaan --}}
            <button
                @click="proofModalOpen = false"
                class="absolute -top-3 -right-3 z-10 w-10 h-10 flex items-center justify-center bg-white rounded-full text-gray-700 hover:text-red-500 transition-colors shadow-lg">
                <x-heroicon-o-x-mark class="w-6 h-6" />
            </button>
            
            <div class="overflow-hidden rounded-lg border-4 border-white">
                <img :src="currentProofUrl" alt="Bukti Pembayaran" class="w-full h-auto max-h-[80vh] object-contain">
            </div>
        </div>
    </div>

    {{-- 🔹 Script --}}
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('openMultipleWhatsApp', ({ urls }) => {
                urls.forEach(url => window.open(url, '_blank'));
            });
        });
    </script>
</x-filament::page>

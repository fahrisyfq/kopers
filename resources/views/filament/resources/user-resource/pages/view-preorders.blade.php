<x-filament::page>
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

        {{-- 🔹 Filter Dropdown & Search Bar --}}
        <div class="flex flex-wrap items-end gap-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
            {{-- Filter Kelas --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Filter Kelas</label>
                <select
                    wire:model.live="selectedKelas"
                    class="text-sm border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-300 focus:border-blue-400"
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
                    class="text-sm border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-300 focus:border-blue-400"
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
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Cari Nama Siswa</label>
                <input
                    type="text"
                    wire:model.live="searchTerm"
                    placeholder="Ketik nama siswa..."
                    class="w-full text-sm border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-300 focus:border-blue-400"
                >
            </div>
        </div>

        {{-- 🔹 Tidak ada data --}}
        @if($usersWithPreOrders->isEmpty())
            <div class="p-6 bg-yellow-50 border border-yellow-200 rounded-xl text-yellow-700 flex items-center gap-3">
                <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-yellow-500" />
                <span>Tidak ada pesanan pre-order saat ini.</span>
            </div>
        @else
            {{-- 🔹 Tabel utama --}}
            <div class="overflow-x-auto bg-white shadow-lg rounded-xl border border-gray-100">
                <table class="min-w-full text-sm text-gray-700 border-collapse">
                    <thead class="bg-gradient-to-r from-blue-50 to-indigo-50 text-gray-700 font-semibold">
                        <tr>
                            <th class="px-4 py-3 border text-left">NISN</th>
                            <th class="px-4 py-3 border text-left">NIS</th>
                            <th class="px-4 py-3 border text-left">Nama Lengkap</th>
                            <th class="px-4 py-3 border text-left">Kelas</th>
                            <th class="px-4 py-3 border text-left">Jurusan</th>
                            <th class="px-4 py-3 border text-left">Produk Pre-Order</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($usersWithPreOrders as $user)
                            <tr class="hover:bg-blue-50/40 transition-colors">
                                <td class="px-4 py-2 border">{{ $user->nisn }}</td>
                                <td class="px-4 py-2 border">{{ $user->nis }}</td>
                                <td class="px-4 py-2 border font-medium text-gray-800">{{ $user->nama_lengkap }}</td>
                                <td class="px-4 py-2 border">{{ $user->kelas }}</td>
                                <td class="px-4 py-2 border">{{ $user->jurusan }}</td>

                                {{-- Produk Pre-Order --}}
                                <td class="px-4 py-2 border">
                                    @foreach ($user->orders as $order)
                                        @foreach ($order->items as $item)
                                            <div
                                                class="p-3 mb-2 bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-lg shadow-sm hover:shadow transition"
                                            >
                                                <div class="flex justify-between items-start">
                                                    <div class="flex gap-2">
                                                        <input
                                                            type="checkbox"
                                                            wire:model="selectedItems.{{ $item->id }}"
                                                            class="mt-1 text-blue-600 rounded focus:ring-blue-400"
                                                        >
                                                        <div>
                                                            <div class="font-semibold text-gray-900">
                                                                {{ $item->product->title ?? '-' }}
                                                            </div>

                                                            @if($item->productSize)
                                                                <div class="text-xs text-gray-600">
                                                                    Ukuran: {{ $item->productSize->size }}
                                                                </div>
                                                            @endif

                                                            <div class="text-xs text-gray-700 mt-1">
                                                                💰 Harga:
                                                                <span class="font-medium text-gray-800">
                                                                    Rp{{ number_format($item->price ?? 0, 0, ',', '.') }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- 🟢 BAGIAN YANG DIPERBARUI 🟢 --}}
                                                    <div class="text-right space-y-2 flex-shrink-0 ml-4">
                                                        
                                                        {{-- 1. Badge Metode Pembayaran --}}
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

                                                        {{-- 2. Link Bukti Pembayaran (jika ada) --}}
                                                        @if($order->proof_of_payment)
                                                            <a href="{{ asset('storage/' . $order->proof_of_payment) }}" 
                                                               target="_blank" 
                                                               class="text-xs text-blue-600 hover:underline font-medium flex items-center justify-end gap-1">
                                                                Lihat Bukti
                                                                <x-heroicon-o-arrow-top-right-on-square class="w-3 h-3" />
                                                            </a>
                                                        @endif
                                                        
                                                        {{-- 3. Dropdown Status Pembayaran --}}
                                                        <select
                                                            wire:model="statuses.{{ $order->id }}"
                                                            wire:change="updateStatus({{ $order->id }})"
                                                            class="text-xs bg-white border-gray-300 rounded-lg px-2 py-1 focus:ring focus:ring-blue-200 focus:border-blue-400"
                                                        >
                                                            <option value="pending">Pending</option>
                                                            <option value="cash">Cash</option>
                                                            <option value="paid">Paid</option>
                                                        </select>
                                                    </div>
                                                    {{-- 🟢 AKHIR BAGIAN YANG DIPERBARUI 🟢 --}}

                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach

                                    {{-- 🔹 Tombol WhatsApp --}}
                                    <div class="flex gap-3 mt-4">
                                        {{-- Tombol Siswa --}}
                                        <button 
                                            wire:click="contactUser({{ $user->id }})"
                                            class="flex items-center gap-2 text-xs font-semibold px-4 py-2 rounded-lg text-white shadow-md hover:shadow-lg transition-all duration-200 active:scale-95"
                                            style="background: linear-gradient(to right, #2563eb, #3b82f6);"
                                        >
                                            <x-heroicon-o-user class="w-4 h-4 text-white" />
                                            Hubungi Siswa
                                        </button>

                                        {{-- Tombol Orang Tua --}}
                                        <button 
                                            wire:click="contactParent({{ $user->id }})"
                                            class="flex items-center gap-2 text-xs font-semibold px-4 py-2 rounded-lg text-white shadow-md hover:shadow-lg transition-all duration-200 active:scale-95"
                                            style="background: linear-gradient(to right, #16a34a, #22c55e);"
                                        >
                                            <x-heroicon-o-chat-bubble-left-right class="w-4 h-4 text-white" />
                                            Hubungi Orang Tua
                                        </button>
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
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
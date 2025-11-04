@extends('layout.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-emerald-50 pt-28 pb-24 font-poppins">
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8" 
         x-data="{ isModalOpen: false, modalImageUrl: '', modalFileType: '' }">

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 md:mb-10 pb-5 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-gradient-to-br from-blue-100 to-teal-100 text-blue-600 rounded-xl shadow-sm">
                    <i class="fas fa-clipboard-list text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800 tracking-tight">
                        Pesanan Saya
                    </h1>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Lihat daftar dan status pesanan kamu di sini.
                    </p>
                </div>
            </div>
             <a href="{{ route('product.index') }}" {{-- Link kembali ke katalog --}}
                class="flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-700 transition duration-200 mt-3 sm:mt-0">
                 <i class="fas fa-store text-xs"></i> Kembali ke Katalog
             </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-2.5 rounded-lg mb-6 flex items-center gap-2 text-sm shadow-sm">
                <i class="fas fa-check-circle text-emerald-500"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="space-y-6">
            @forelse($orders as $order)
                <div class="card animate-fadeInUp">
                    <div class="card__content bg-white/95 backdrop-blur-sm rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        
                        <div class="flex flex-col sm:flex-row justify-between sm:items-start border-b border-gray-100 px-5 py-4 bg-gray-50/70 rounded-t-lg gap-4">
                            
                            {{-- Kolom Kiri: Tanggal & Status --}}
                            <div>
                                <p class="text-xs text-gray-500 font-medium">
                                    TANGGAL PESANAN: {{ $order->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                                </p>
                                
                                <div class="mt-2.5">
                                    @php
                                        $statusConfig = [
                                            'pending' => ['color' => 'bg-yellow-100 text-yellow-800 border-yellow-200', 'icon' => 'fa-clock'],
                                            'cash' => ['color' => 'bg-blue-100 text-blue-800 border-blue-200', 'icon' => 'fa-money-bill-wave'],
                                            'paid' => ['color' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'icon' => 'fa-check-circle'],
                                            'cancelled' => ['color' => 'bg-red-100 text-red-800 border-red-200', 'icon' => 'fa-times-circle'],
                                            'default' => ['color' => 'bg-gray-100 text-gray-800 border-gray-200', 'icon' => 'fa-question-circle']
                                        ];
                                        $config = $statusConfig[$order->payment_status] ?? $statusConfig['default'];
                                    @endphp
                                    <span class="text-xs font-bold px-3 py-1 rounded-full border {{ $config['color'] }} flex items-center gap-1.5 w-fit">
                                        <i class="fas {{ $config['icon'] }} text-xs"></i>
                                        Status: {{ ucfirst($order->payment_status) }}
                                    </span>

                                    @if($order->proof_of_payment)
                                        @php
                                            $path = $order->proof_of_payment;
                                            $url = asset('storage/' . $path);
                                            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                        @endphp
                                        <button 
                                           @click="
                                               modalImageUrl = '{{ $url }}';
                                               modalFileType = '{{ $ext }}';
                                               isModalOpen = true;
                                           "
                                           class="flex items-center gap-1.5 text-xs text-blue-600 hover:text-blue-700 font-medium mt-2 transition w-fit">
                                            <i class="fas fa-eye fa-fw"></i>
                                            <span>Lihat Bukti Pembayaran</span>
                                        </button>
                                    @endif

                                </div>
                            </div>

                            {{-- Kolom Kanan: Total & Info Siswa --}}
                            <div class="sm:text-right">
                                <p class="text-xs text-gray-500">Total Pembayaran</p>
                                <p class="text-xl font-extrabold text-blue-700 tabular-nums">
                                    Rp {{ number_format($order->total_price,0,',','.') }}
                                </p>
                                <p class="text-sm font-semibold text-gray-800 mt-1.5">
                                    {{ $order->user->nama_lengkap ?? 'Tanpa Nama' }}
                                </p>
                                <p class="text-xs text-gray-600">
                                    {{ $order->user->kelas ?? '-' }} - {{ $order->user->jurusan ?? '-' }}
                                </p>
                            </div>
                        </div>

                        {{-- List Produk --}}
                        <div class="divide-y divide-gray-100 px-5 py-2">
                            @foreach($order->items as $item)
                                <div class="flex items-start gap-4 py-4">
                                    
                                    <div class="relative w-16 h-16 rounded-lg overflow-hidden border border-gray-100 shadow-sm flex-shrink-0 bg-white">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/'. $item->product->image) }}"
                                                 alt="{{ $item->product->title }}"
                                                 class="object-cover w-full h-full transition duration-300">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                                <i class="fas fa-image text-gray-300 text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-800 text-sm leading-tight truncate">
                                            {{ $item->product->title ?? 'Produk dihapus' }}
                                        </p>
                                        @if($item->productSize)
                                            <p class="text-xs text-gray-600 mt-0.5">
                                                Ukuran: <span class="font-medium text-gray-800">{{ $item->productSize->size }}</span>
                                            </p>
                                        @endif
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            Qty: <span class="font-medium">{{ $item->quantity }}</span>
                                        </p>

                                        @if($item->is_preorder && $item->preorder_status === 'waiting')
                                            <p class="text-xs text-amber-600 font-medium mt-1.5 flex items-center gap-1">
                                                <i class="fas fa-clock fa-fw"></i>
                                                <span>Sedang diproses</span>
                                            </p>
                                        @elseif($item->preorder_status === 'ready')
                                            <p class="text-xs text-emerald-600 font-medium mt-1.5 flex items-center gap-1">
                                                <i class="fas fa-check-circle fa-fw"></i>
                                                <span>Siap diambil</span>
                                            </p>
                                        @endif
                                    </div>
                                    
                                    <div class="sm:text-right flex-shrink-0 ml-4">
                                        <p class="text-xs text-gray-500">Subtotal</p>
                                        <p class="text-base font-semibold text-gray-800 tabular-nums">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                <div class="card animate-fadeIn mt-10">
                    <div class="card__content bg-white/95 backdrop-blur-sm p-8 md:p-12 rounded-xl border border-blue-100 shadow text-center">
                        <svg class="mx-auto w-28 h-28 text-blue-300 mb-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <defs> <linearGradient id="emptyOrderGradient" x1="0%" y1="0%" x2="100%" y2="100%"> <stop offset="0%" style="stop-color: #a5f3fc; stop-opacity: 1" /> <stop offset="100%" style="stop-color: #60a5fa; stop-opacity: 0.8" /> </linearGradient> </defs>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M6 2C4.34315 2 3 3.34315 3 5V19C3 20.6569 4.34315 22 6 22H18C19.6569 22 21 20.6569 21 19V5C21 3.34315 19.6569 2 18 2H6ZM6 4C5.44772 4 5 4.44772 5 5V19C5 19.5523 5.44772 20 6 20H18C18.5523 20 19 19.5523 19 19V5C19 4.44772 18.5523 4 18 4H6Z" fill="url(#emptyOrderGradient)" />
                            <path d="M9 9H15" stroke="url(#emptyOrderGradient)" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M9 13H15" stroke="url(#emptyOrderGradient)" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M9 17H12" stroke="url(#emptyOrderGradient)" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Anda Belum Punya Pesanan</h3>
                        <p class="text-gray-500 text-sm mb-8">Semua pesanan yang Anda buat akan muncul di sini.</p>
                        <a href="{{ route('product.index') }}" 
                           class="cta-button relative inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 via-cyan-500 to-emerald-500 text-white font-semibold px-7 py-2.5 rounded-lg shadow-lg hover:shadow-cyan-300/50 transition-all duration-300 transform hover:scale-[1.03] active:scale-[0.98] overflow-hidden">
                            <i class="fas fa-store text-sm"></i> 
                            <span>Mulai Belanja</span>
                            <span class="shine"></span>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
        
        {{-- 🟢 MODAL GLOBAL DENGAN PERBAIKAN SCROLL MOBILE 🟢 --}}
        <div x-show="isModalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
             style="display: none;">
            
            {{-- Backdrop (klik untuk menutup) --}}
            <div @click="isModalOpen = false" class="fixed inset-0 bg-black/75 backdrop-blur-sm"></div>
            
            {{-- 1. PERBAIKAN: Panel Modal dibuat scrollable --}}
            <div x-show="isModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative w-full max-w-2xl bg-white rounded-xl shadow-xl flex flex-col max-h-[90vh] overflow-y-auto">
                
                {{-- Header Modal --}}
                <div class="flex items-center justify-between p-4 border-b sticky top-0 bg-white z-10">
                    <h3 class="text-lg font-semibold text-gray-800">Bukti Pembayaran</h3>
                    <button @click="isModalOpen = false" class="text-gray-400 hover:text-gray-600 transition rounded-full p-1 hover:bg-gray-100">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                {{-- 2. PERBAIKAN: Konten Modal tidak lagi scrollable secara internal --}}
                <div class="p-4 sm:p-6 bg-gray-50">
                    
                    {{-- Template untuk Gambar --}}
                    <template x-if="['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(modalFileType)">
                        <img :src="modalImageUrl" alt="Bukti Pembayaran" class="w-full h-auto rounded-md object-contain">
                    </template>

                    {{-- Template untuk PDF --}}
                    <template x-if="modalFileType === 'pdf'">
                        <iframe :src="modalImageUrl" class="w-full border rounded-md" style="height: 70vh;"></iframe>
                    </template>

                    {{-- Template untuk Fallback --}}
                    <template x-if="!['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'].includes(modalFileType) && modalFileType !== ''">
                         <div class="text-center p-4">
                            <p class="font-semibold text-gray-700">Format file tidak didukung untuk pratinjau</p>
                            <a :href="modalImageUrl" target="_blank" class="text-blue-600 hover:underline mt-2 inline-block">
                                Download file
                            </a>
                        </div>
                    </template>
                </div>

                {{-- Footer Modal --}}
                <div class="flex items-center justify-between p-4 bg-white border-t rounded-b-xl sticky bottom-0 z-10">
                    <a :href="modalImageUrl" target="_blank" class="text-sm text-blue-600 hover:underline flex items-center gap-1.5">
                        Buka di tab baru
                        <i class="fas fa-external-link-alt text-xs"></i>
                    </a>
                    <button @click="isModalOpen = false" class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
/* Font Poppins */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');
.font-poppins { font-family: 'Poppins', sans-serif; }

/* Card Border Berputar (Blue/Emerald Theme) */
.card {
    --glow-primary: hsla(197, 71%, 80%, 0.6); --glow-secondary: hsla(158, 64%, 80%, 0.6); 
    --card-bg: rgba(255, 255, 255, 0.98); --card-shadow: rgba(59, 130, 246, 0.06); 
    --card-shadow-hover: rgba(59, 130, 246, 0.12); --border-line: rgba(219, 234, 254, 0.6); /* blue-100 */
    position: relative; border-radius: 1rem; 
    overflow: hidden; 
    box-shadow: 0 4px 15px -5px var(--card-shadow); z-index: 1; 
}
.card .card__content {
    position: relative; z-index: 2; background-color: var(--card-bg);
    backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); 
    border-radius: 0.9rem; 
    border: 1px solid var(--border-line); 
    transition: transform 0.3s ease-out, box-shadow 0.3s ease-out; 
}
.card::before {
    content: ""; pointer-events: none; position: absolute; z-index: -1; 
    top: 50%; left: 50%; transform: translate(-50%, -50%);
    width: calc(100% + 20px); height: calc(100% + 20px); 
    background-image: conic-gradient(from var(--angle), var(--glow-secondary), var(--primary-glow), var(--glow-secondary));
    filter: blur(16px); opacity: 0.35; animation: rotate 12s linear infinite; 
    transition: opacity 0.3s ease-out, filter 0.3s ease-out; 
}
.card:hover .card__content { 
    transform: scale(1.01); 
    box-shadow: 0 10px 25px -8px var(--card-shadow-hover); 
}
.card:hover::before { opacity: 0.5; filter: blur(18px); }

@keyframes rotate { to { --angle: 360deg; } }
@property --angle { syntax: "<angle>"; initial-value: 0deg; inherits: false; }

/* Animasi Halaman */
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
.animate-fadeInUp { animation: fadeInUp 0.7s ease-out both; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.animate-fadeIn { animation: fadeIn 0.8s ease-out both; }

/* Tombol CTA (Mulai Belanja) */
.cta-button {
    background-size: 200% auto; 
    transition: all 0.4s cubic-bezier(.4,0,.2,1); 
}
.cta-button:hover:not(:disabled) {
    background-position: right center; 
    box-shadow: 0 7px 20px -4px rgba(59, 130, 246, 0.4); /* Shadow Biru/Cyan */
    transform: scale(1.03) translateY(-2px); 
}
.cta-button .shine {
    position: absolute; top: -50%; left: -150%; width: 25px; height: 200%;
    background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.4) 50%, rgba(255,255,255,0) 100%);
    transform: rotate(35deg); transition: left 0.7s cubic-bezier(0.23, 1, 0.32, 1); pointer-events: none;
}
.cta-button:hover .shine { left: 150%; }

/* Style Badge Produk (dari katalog) */
.badge {
    padding: 2px 6px; font-size: 9px; font-weight: 700; border-radius: 6px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1); letter-spacing: 0.5px; text-transform: uppercase;
    background-color: rgba(0,0,0,0.4); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,0.1); color: white; 
}
.badge.bg-emerald-500 { background-color: rgba(16, 185, 129, 0.85); } 
.badge.bg-amber-500 { background-color: rgba(245, 158, 11, 0.85); } 

/* Untuk angka agar tidak "jiggle" */
.tabular-nums { font-variant-numeric: tabular-nums; }
</style>
@endpush
@extends('layout.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-emerald-50 pt-24 pb-24 font-poppins">
    
    {{-- Container Utama dengan Alpine JS --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8" 
         x-data="orderPageData()">

        {{-- Header Halaman --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4 border-b border-gray-200 pb-6">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gradient-to-br from-blue-100 to-teal-100 text-blue-600 rounded-2xl shadow-sm">
                    <i class="fas fa-clipboard-list text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">
                        Pesanan Saya
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Riwayat belanja & ulasan produk kamu.
                    </p>
                </div>
            </div>
             <a href="{{ route('product.index') }}" 
                class="flex items-center justify-center gap-2 text-sm font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 py-2.5 px-4 rounded-xl transition duration-200 w-full md:w-auto">
                 <i class="fas fa-store text-xs"></i> Kembali ke Katalog
             </a>
        </div>

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 text-sm shadow-sm animate-fadeIn">
                <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- List Pesanan --}}
        <div class="space-y-6">
            @forelse($orders as $order)
                <div class="card animate-fadeInUp group">
                    <div class="card__content bg-white/80 backdrop-blur-md rounded-2xl border border-white/50 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                        
                        {{-- Header Card Pesanan --}}
                        <div class="flex flex-col md:flex-row justify-between md:items-start border-b border-gray-100 px-5 py-4 bg-gray-50/50 gap-4">
                            
                            {{-- Kiri: Tanggal & Status --}}
                            <div class="space-y-3 w-full md:w-auto">
                                <div class="flex items-center justify-between md:justify-start gap-2">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-1 rounded-md bg-gray-200 text-gray-600 text-[10px] font-bold uppercase tracking-wider">
                                            {{ $order->created_at->setTimezone('Asia/Jakarta')->format('d M Y') }}
                                        </span>
                                        <span class="text-xs text-gray-400 font-mono">
                                            {{ $order->created_at->setTimezone('Asia/Jakarta')->format('H:i') }} WIB
                                        </span>
                                    </div>
                                    
                                    {{-- Total Harga (Mobile Only - Biar kelihatan di atas) --}}
                                    <div class="md:hidden">
                                        <p class="text-sm font-extrabold text-gray-800 tabular-nums">
                                            Rp {{ number_format($order->total_price,0,',','.') }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex flex-wrap items-center gap-2">
                                    @php
                                        $statusConfig = [
                                            'pending' => ['color' => 'bg-amber-100 text-amber-700 border-amber-200', 'icon' => 'fa-clock'],
                                            'cash' => ['color' => 'bg-blue-100 text-blue-700 border-blue-200', 'icon' => 'fa-money-bill-wave'],
                                            'paid' => ['color' => 'bg-emerald-100 text-emerald-700 border-emerald-200', 'icon' => 'fa-check-circle'],
                                            'cancelled' => ['color' => 'bg-red-100 text-red-700 border-red-200', 'icon' => 'fa-times-circle'],
                                            'default' => ['color' => 'bg-gray-100 text-gray-700 border-gray-200', 'icon' => 'fa-question-circle']
                                        ];
                                        $config = $statusConfig[$order->payment_status] ?? $statusConfig['default'];
                                    @endphp
                                    <span class="text-xs font-bold px-3 py-1 rounded-full border {{ $config['color'] }} flex items-center gap-1.5 shadow-sm">
                                        <i class="fas {{ $config['icon'] }} text-[10px]"></i>
                                        {{ ucfirst($order->payment_status) }}
                                    </span>

                                    @if($order->proof_of_payment)
                                        @php
                                            $url = asset('storage/' . $order->proof_of_payment);
                                            $ext = strtolower(pathinfo($order->proof_of_payment, PATHINFO_EXTENSION));
                                        @endphp
                                        <button @click="openProofModal('{{ $url }}', '{{ $ext }}')"
                                                class="text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-100 px-3 py-1 rounded-full transition flex items-center gap-1.5">
                                             <i class="fas fa-file-invoice"></i> Bukti
                                        </button>
                                    @endif
                                </div>
                            </div>

                            {{-- Kanan: Total Harga (Desktop Only) --}}
                            <div class="hidden md:block text-right border-l border-gray-200 pl-4">
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-0.5">Total Belanja</p>
                                <p class="text-xl font-extrabold text-gray-800 tabular-nums">
                                    Rp {{ number_format($order->total_price,0,',','.') }}
                                </p>
                            </div>
                        </div>

                        {{-- List Item Produk --}}
                            <div class="divide-y divide-gray-100">
                                @foreach($order->items as $item)
                                    @php
                                        // 1. HITUNG JUMLAH YANG SUDAH DIRETUR UNTUK ITEM INI
                                        // Kita cek ke tabel StockMovement berdasarkan Note yang mengandung "Retur Order #ID"
                                        $returnedQty = \App\Models\StockMovement::where('product_id', $item->product_id)
                                            ->where('product_size_id', $item->product_size_id)
                                            ->where('movement_type', 'in') // Retur = Barang Masuk
                                            ->where('note', 'like', '%Retur Order #' . $order->id . '%')
                                            ->sum('quantity');

                                        // 2. CEK APAKAH FULL RETUR?
                                        $isReturned = $returnedQty >= $item->quantity;
                                    @endphp

                                    {{-- KONDISI CSS: Jika diretur, kasih background abu & grayscale --}}
                                    <div class="p-4 sm:p-5 transition-colors duration-200 
                                                {{ $isReturned ? 'bg-gray-100/80 grayscale opacity-75' : 'hover:bg-gray-50/50' }}">
                                        
                                        <div class="flex flex-col sm:flex-row gap-4 sm:items-center">
                                            
                                            {{-- Gambar & Detail --}}
                                            <div class="flex items-start gap-3 sm:gap-4 flex-1">
                                                {{-- Image --}}
                                                <div class="relative w-16 h-16 sm:w-16 sm:h-16 rounded-lg overflow-hidden border border-gray-100 shadow-sm flex-shrink-0 bg-white">
                                                    @if($item->product && $item->product->image)
                                                        <img src="{{ asset('storage/'. $item->product->image) }}"
                                                             alt="{{ $item->product->title }}"
                                                             class="object-cover w-full h-full">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center bg-gray-50 text-gray-300">
                                                            <i class="fas fa-image text-2xl"></i>
                                                        </div>
                                                    @endif

                                                    {{-- OVERLAY JIKA DIRETUR --}}
                                                    @if($isReturned)
                                                        <div class="absolute inset-0 bg-black/10 flex items-center justify-center">
                                                            <i class="fas fa-ban text-white drop-shadow-md"></i>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Info --}}
                                                <div class="flex-1 min-w-0 space-y-1">
                                                    <div class="flex items-start justify-between gap-2">
                                                        <p class="font-bold text-gray-800 text-sm line-clamp-2 leading-snug">
                                                            {{ $item->product->title ?? 'Produk tidak tersedia' }}
                                                        </p>
                                                        
                                                        {{-- BADGE STATUS RETUR --}}
                                                        @if($isReturned)
                                                            <span class="shrink-0 inline-flex items-center gap-1 text-[10px] font-bold text-red-600 bg-red-100 px-2 py-0.5 rounded border border-red-200 uppercase tracking-wide">
                                                                <i class="fas fa-undo-alt"></i> Diretur
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
                                                        @if($item->productSize)
                                                            <span class="bg-gray-100 px-2 py-0.5 rounded text-gray-700 font-medium border border-gray-200">
                                                                {{ $item->productSize->size }}
                                                            </span>
                                                        @endif
                                                        <span>Qty: {{ $item->quantity }}</span>
                                                    </div>
                                                    
                                                    {{-- LOGIKA STATUS PO vs SIAP AMBIL (Hanya muncul jika TIDAK diretur) --}}
                                                    @if(!$isReturned)
                                                        <div class="pt-1">
                                                            @if($item->preorder_status === 'ready')
                                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                                                                    <i class="fas fa-check-double"></i> Siap Diambil
                                                                </span>
                                                            @elseif($item->is_preorder && $item->preorder_status === 'waiting')
                                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-100">
                                                                    <i class="fas fa-clock animate-pulse"></i> Pre-Order: Diproses
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            {{-- Harga & Action (Mobile Responsive) --}}
                                            <div class="flex items-center justify-between w-full sm:w-auto border-t border-gray-200/50 pt-3 sm:border-t-0 sm:pt-0">
                                                {{-- Harga Mobile --}}
                                                <p class="text-sm font-bold text-gray-800 tabular-nums sm:hidden {{ $isReturned ? 'line-through text-gray-400' : '' }}">
                                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                                </p>
                                                
                                                <div class="flex flex-row sm:flex-col items-center gap-3 ml-auto sm:ml-0 w-full sm:w-auto">
                                                    {{-- Harga Desktop --}}
                                                    <p class="hidden sm:block text-sm font-bold text-gray-800 tabular-nums text-right {{ $isReturned ? 'line-through text-gray-400' : '' }}">
                                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                                    </p>

                                                    {{-- Tombol Ulasan (HANYA MUNCUL JIKA TIDAK DIRETUR) --}}
                                                    @if($item->product && !$isReturned)
                                                        <button 
                                                            @click="openReviewModal(
                                                                '{{ $item->product->id }}', 
                                                                '{{ addslashes($item->product->title) }}', 
                                                                '{{ $item->product->image ? asset('storage/'.$item->product->image) : '' }}'
                                                            )"
                                                            class="w-full sm:w-auto justify-center text-xs font-semibold text-emerald-600 bg-white border border-emerald-200 hover:bg-emerald-50 hover:border-emerald-300 px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2 shadow-sm active:scale-95">
                                                            <i class="far fa-star text-emerald-500"></i>
                                                            Beri Ulasan
                                                        </button>
                                                    @elseif($isReturned)
                                                        {{-- Keterangan Pengganti Tombol --}}
                                                        <span class="text-[10px] font-medium text-red-500 italic">
                                                            Produk dikembalikan
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                    </div>
                </div>
            @empty
                {{-- State Kosong --}}
                <div class="flex flex-col items-center justify-center py-16 px-4 text-center animate-fadeIn">
                    <div class="w-40 h-40 bg-blue-50 rounded-full flex items-center justify-center mb-6 shadow-inner">
                        <svg class="w-20 h-20 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Pesanan</h3>
                    <p class="text-gray-500 max-w-xs mx-auto mb-8 leading-relaxed">
                        Kamu belum pernah belanja nih. Yuk cari barang kebutuhanmu sekarang!
                    </p>
                    <a href="{{ route('product.index') }}" class="cta-button inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-blue-500 text-white font-bold px-8 py-3 rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300 hover:-translate-y-1">
                        <span>Mulai Belanja</span>
                        <i class="fas fa-arrow-right text-sm"></i>
                    </a>
                </div>
            @endforelse
        </div>
        
        {{-- ========================================== --}}
        {{-- MODAL BUKTI PEMBAYARAN --}}
        {{-- ========================================== --}}
        <div x-show="isModalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[999] flex items-center justify-center p-4"
             style="display: none;" x-cloak>
            
            <div @click="isModalOpen = false" class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm"></div>
            
            <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl flex flex-col max-h-[85vh] overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b bg-white sticky top-0 z-10">
                    <h3 class="text-lg font-bold text-gray-800">Bukti Pembayaran</h3>
                    <button @click="isModalOpen = false" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-6 bg-gray-50 flex justify-center overflow-y-auto h-full">
                     <template x-if="['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(modalFileType)">
                        <img :src="modalImageUrl" class="w-full h-auto rounded-lg shadow-sm object-contain">
                    </template>
                    <template x-if="modalFileType === 'pdf'">
                        <iframe :src="modalImageUrl" class="w-full h-[60vh] border rounded-lg bg-white" style="min-height: 400px;"></iframe>
                    </template>
                    <template x-if="!['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'].includes(modalFileType) && modalFileType !== ''">
                         <div class="text-center p-8">
                             <p class="text-gray-600 font-medium mb-3">Format file tidak didukung untuk pratinjau.</p>
                             <a :href="modalImageUrl" target="_blank" class="text-blue-600 font-bold hover:underline">Download File</a>
                         </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- MODAL FORM ULASAN --}}
        {{-- ========================================== --}}
        <div x-show="reviewModalOpen" 
             class="fixed inset-0 z-[999] flex items-center justify-center px-4"
             style="display: none;" x-cloak>
            
            {{-- Backdrop --}}
            <div x-show="reviewModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="reviewModalOpen = false"
                 class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm">
            </div>

            {{-- Content --}}
            <div x-show="reviewModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="relative bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden z-10 flex flex-col max-h-[90vh]">
                
                {{-- Header --}}
                <div class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 p-6 text-center relative shrink-0">
                    <button @click="reviewModalOpen = false" class="absolute top-4 right-4 w-8 h-8 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-white transition focus:outline-none backdrop-blur-sm">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-3 backdrop-blur-md border border-white/30 shadow-lg">
                        <i class="fas fa-star text-yellow-300 text-2xl drop-shadow-md"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-white tracking-tight">Beri Ulasan</h3>
                    <p class="text-emerald-50 text-xs mt-1 font-medium opacity-90">Bagaimana kualitas produk ini?</p>
                </div>

                <form @submit.prevent="submitReview" class="p-6 space-y-5 overflow-y-auto">
                    
                    {{-- INFO OPSIONAL --}}
                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 flex items-start gap-2 text-xs text-blue-700">
                        <i class="fas fa-info-circle mt-0.5 flex-shrink-0"></i>
                        <span><strong>Info:</strong> Berikan bintang saja sudah cukup. Tulisan ulasan bersifat <strong>opsional</strong> (tidak wajib).</span>
                    </div>

                    {{-- Product Info --}}
                    <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <div class="w-12 h-12 bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden flex-shrink-0 flex items-center justify-center">
                             <template x-if="activeProduct.image">
                                <img :src="activeProduct.image" class="w-full h-full object-cover">
                             </template>
                             <template x-if="!activeProduct.image">
                                <i class="fas fa-box text-slate-300 text-lg"></i>
                             </template>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] uppercase font-bold text-slate-400 mb-0.5">Produk</p>
                            <p class="text-sm font-bold text-slate-800 truncate leading-tight" x-text="activeProduct.name"></p>
                        </div>
                    </div>

                    {{-- Rating Input --}}
                    <div class="flex flex-col items-center gap-2 py-1">
                        <div class="flex gap-3 text-4xl cursor-pointer">
                            <template x-for="i in 5">
                                <button type="button" class="focus:outline-none transition-transform duration-150 hover:scale-110 active:scale-95"
                                        @click="rating = i"
                                        @mouseover="hoverRating = i"
                                        @mouseleave="hoverRating = 0">
                                    <i class="fas fa-star drop-shadow-sm"
                                       :class="(hoverRating || rating) >= i ? 'text-yellow-400' : 'text-slate-200'"></i>
                                </button>
                            </template>
                        </div>
                        <p class="text-xs font-bold transition-colors duration-300" 
                           :class="rating > 0 ? 'text-emerald-600' : 'text-slate-400'"
                           x-text="rating > 0 ? (rating === 5 ? 'Sempurna!' : (rating >= 4 ? 'Sangat Bagus' : (rating === 3 ? 'Bagus' : 'Kurang'))) : 'Ketuk bintang untuk menilai'">
                        </p>
                    </div>

                    {{-- Textarea OPSIONAL --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2 ml-1">
                            Tulisan Ulasan <span class="text-slate-400 font-normal lowercase ml-1">(opsional)</span>
                        </label>
                        <textarea x-model="reviewText" rows="3" 
                                  class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm transition-all placeholder:text-slate-400 p-3 resize-none shadow-sm" 
                                  placeholder="Ceritakan pengalamanmu menggunakan produk ini... (Boleh dikosongkan)"></textarea>
                    </div>

                    {{-- Anonim Toggle --}}
                    <div class="flex items-center justify-between bg-white p-3 rounded-xl border border-slate-200 hover:border-slate-300 transition cursor-pointer" @click="isAnonymous = !isAnonymous">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center transition-all duration-300"
                                 :class="isAnonymous ? 'bg-slate-800 text-white shadow-md scale-110' : 'bg-slate-100 text-slate-400'">
                                <i class="fas" :class="isAnonymous ? 'fa-user-secret' : 'fa-user'"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-700" x-text="isAnonymous ? 'Mode Anonim Aktif' : 'Tampilkan Nama Saya'"></span>
                                <span class="text-[10px] text-slate-500">Sembunyikan identitas di ulasan</span>
                            </div>
                        </div>
                        <div class="relative inline-flex items-center cursor-pointer scale-90 pointer-events-none">
                            <input type="checkbox" x-model="isAnonymous" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-slate-800 shadow-inner"></div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" 
                            :disabled="isSubmitting"
                            :class="{ 'opacity-75 cursor-not-allowed': isSubmitting }"
                            class="w-full py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition-all active:scale-[0.98] flex items-center justify-center gap-2 mt-2">
                        <span x-show="!isSubmitting" class="flex items-center gap-2">
                            <span>Kirim Ulasan Sekarang</span>
                            <i class="fas fa-paper-plane text-sm"></i>
                        </span>
                        <span x-show="isSubmitting" class="flex items-center gap-2" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i>
                            <span>Mengirim...</span>
                        </span>
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');
    .font-poppins { font-family: 'Poppins', sans-serif; }
    [x-cloak] { display: none !important; }
    .card { position: relative; z-index: 1; }
    .cta-button { background-size: 200% auto; transition: all 0.4s cubic-bezier(.4,0,.2,1); }
    .cta-button:hover { background-position: right center; }
    .tabular-nums { font-variant-numeric: tabular-nums; }
</style>
@endpush

@push('scripts')
{{-- Pastikan SweetAlert2 dimuat --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('alpine:init', () => {
        
        // --- KONFIGURASI TOAST MINIMALIS ---
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end', // Default desktop
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            },
            customClass: {
                popup: 'rounded-xl shadow-lg border border-gray-100', // Styling minimalis
                title: 'font-poppins text-sm',
                icon: 'text-xs'
            }
        });

        // Cek layar HP agar Toast muncul di bawah
        if (window.innerWidth < 640) {
            Toast.params.position = 'bottom-center'; 
        }

        Alpine.data('orderPageData', () => ({
            isModalOpen: false,
            modalImageUrl: '',
            modalFileType: '',
            reviewModalOpen: false,
            rating: 0,
            hoverRating: 0,
            isAnonymous: false,
            reviewText: '',
            isSubmitting: false,
            activeProduct: { id: null, name: '', image: '' },

            openProofModal(url, ext) {
                this.modalImageUrl = url;
                this.modalFileType = ext;
                this.isModalOpen = true;
            },

            openReviewModal(productId, productName, productImage) {
                this.rating = 0;
                this.reviewText = '';
                this.isAnonymous = false;
                this.hoverRating = 0;
                this.isSubmitting = false;
                this.activeProduct = { id: productId, name: productName, image: productImage };
                this.reviewModalOpen = true;
            },

            submitReview() {
                if (this.rating === 0) {
                    Toast.fire({
                        icon: 'warning',
                        title: 'Mohon pilih bintang rating dulu'
                    });
                    return;
                }
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!csrfToken) {
                    Toast.fire({ icon: 'error', title: 'Token Error (Refresh halaman)' });
                    return;
                }

                this.isSubmitting = true;

                fetch("{{ route('reviews.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: this.activeProduct.id,
                        rating: this.rating,
                        body: this.reviewText,
                        is_anonymous: this.isAnonymous
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        this.reviewModalOpen = false;
                        Toast.fire({
                            icon: 'success',
                            title: 'Ulasan berhasil dikirim! Terima kasih.'
                        });

                        setTimeout(() => {
                        window.location.href = "{{ route('home') }}#testimonials";
                        }, 1500);

                    } else {
                        throw new Error(data.message || 'Gagal mengirim ulasan.');
                    }
                })
                .catch(error => {
                    console.error('Review Error:', error);
                    let msg = error.message || 'Terjadi kesalahan sistem.';
                    if(error.errors) {
                        msg = Object.values(error.errors).flat().join('\n');
                    }
                    Toast.fire({
                        icon: 'error',
                        title: msg
                    });
                })
                .finally(() => {
                    this.isSubmitting = false;
                });
            }
        }));
    });
</script>
@endpush 
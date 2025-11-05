@extends('layout.app')

@section('title', 'Keranjang Belanja')

{{-- SweetAlert2 untuk konfirmasi hapus --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-emerald-50 pt-28 pb-20 font-poppins">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 md:mb-10 pb-5 border-b border-gray-200">
            <div class="flex items-center gap-3 mb-4 sm:mb-0">
                <span class="text-emerald-600 bg-gradient-to-br from-emerald-100 to-teal-100 p-2.5 rounded-xl text-2xl shadow-sm">
                    <i class="fas fa-shopping-cart fa-fw"></i>
                </span>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800 tracking-tight">Keranjang Belanja</h1>
            </div>
             @if(!empty($cart))
                 <form action="{{ route('cart.clear') }}" method="POST" class="delete-item" data-title="Kosongkan Keranjang?" data-text="Semua item akan dihapus dari keranjang."> {{-- Class & data untuk JS --}}
                     @csrf
                     @method('DELETE')
                     <button type="submit"
                             class="flex items-center gap-1.5 text-red-600 hover:text-red-800 text-xs font-semibold px-3 py-1.5 rounded-lg border border-red-200 hover:bg-red-50 transition-colors duration-200 self-end sm:self-center shadow-sm hover:shadow">
                         <i class="fas fa-trash-alt text-xs"></i> Kosongkan Keranjang
                     </button>
                 </form>
             @endif
        </div>

        {{-- Kondisi Keranjang Kosong --}}
        @if(empty($cart))
            <div class="card max-w-lg mx-auto animate-fadeIn mt-10">
                <div class="card__content bg-white/95 backdrop-blur-sm p-8 md:p-12 rounded-xl border border-emerald-100 shadow text-center">
                    <svg class="mx-auto w-32 h-32 md:w-40 md:h-40 text-emerald-300 mb-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                         <defs> <linearGradient id="cartGradient" x1="0%" y1="0%" x2="100%" y2="100%"> <stop offset="0%" style="stop-color: #6ee7b7; stop-opacity: 1" /> <stop offset="100%" style="stop-color: #3b82f6; stop-opacity: 0.8" /> </linearGradient> </defs>
                        <path d="M3 3H5.25L7.05 13.11C7.14387 13.627 7.42512 14.0844 7.83982 14.3918C8.25452 14.6992 8.77017 14.8339 9.28 14.77L17.78 13.77C18.2898 13.7061 18.7755 13.5708 19.1802 13.2634C19.5849 12.956 19.8761 12.4986 19.97 11.98L21.71 4.98C21.8016 4.49247 21.6961 3.98971 21.4111 3.56611C21.126 3.14251 20.6811 2.82773 20.17 2.78L5.25 2.78" stroke="url(#cartGradient)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path d="M9 18C9.55228 18 10 18.4477 10 19C10 19.5523 9.55228 20 9 20C8.44772 20 8 19.5523 8 19C8 18.4477 8.44772 18 9 18Z" fill="url(#cartGradient)"/> <path d="M17 18C17.5523 18 18 18.4477 18 19C18 19.5523 17.5523 20 17 20C16.4477 20 16 19.5523 16 19C16 18.4477 16.4477 18 17 18Z" fill="url(#cartGradient)"/>
                    </svg>
                    <p class="text-gray-700 text-lg md:text-xl font-semibold mb-3"> Wah, keranjang belanjamu kosong! </p>
                    <p class="text-gray-500 text-sm mb-8"> Ayo jelajahi katalog kami dan temukan produk menarik untukmu. </p>
                    <a href="{{ route('product.index') }}" class="cta-button relative inline-flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 text-white font-semibold px-7 py-2.5 rounded-lg shadow-lg hover:shadow-emerald-300/50 transition-all duration-300 transform hover:scale-[1.03] active:scale-[0.98] overflow-hidden">
                        <i class="fas fa-store text-sm"></i> 
                        <span>Jelajahi Produk</span>
                        <span class="shine"></span>
                    </a>
                </div>
            </div>

        {{-- Kondisi Keranjang Ada Isi --}}
        @else
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
                
                <div class="lg:col-span-8 space-y-4"> 
                    @foreach($cart as $key => $item)
                        <div class="card cart-item"> 
                            <div class="card__content flex flex-col sm:flex-row items-start py-4 px-5 bg-white/95 rounded-xl border border-gray-100 shadow-sm transition-all duration-300 relative">
                                
                                <div class="w-24 h-24 sm:w-20 sm:h-20 flex-shrink-0 overflow-hidden rounded-lg border border-gray-100 bg-gray-50 mb-3 sm:mb-0 shadow-inner">
                                    @if(!empty($item['image']))
                                        <img src="{{ asset('storage/'. $item['image']) }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No Img</div>
                                    @endif
                                </div>

                                <div class="flex-1 sm:ml-5 w-full">
                                    <h2 class="font-semibold text-gray-800 text-base leading-snug tracking-tight mb-1 line-clamp-2 pr-6">
                                        {{ $item['title'] }}
                                    </h2>
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-500 mb-2">
                                        <span class="flex items-center gap-1"><i class="fas fa-tag text-emerald-500 text-[10px]"></i> {{ $item['category'] }}</span>
                                        @if($item['category'] === 'Seragam Sekolah' && !empty($item['size']))
                                             <span class="flex items-center gap-1"><i class="fas fa-ruler text-emerald-500 text-[10px]"></i> Ukuran: <strong class="text-gray-700">{{ $item['size'] }}</strong></span>
                                        @endif
                                    </div>
                                     <span class="text-emerald-600 font-bold text-base tracking-tight">
                                          Rp {{ number_format($item['price'],0,',','.') }}
                                     </span>
                                </div>

                                <div class="flex flex-col sm:flex-row items-end sm:items-center gap-4 w-full sm:w-auto mt-4 sm:mt-0 sm:ml-5">
                                    
                                    {{-- ====================================================== --}}
                                    {{--      PERBAIKAN DI SINI: INPUT NUMBER DENGAN LABEL     --}}
                                    {{-- ====================================================== --}}
                                    <div class="py-2 px-3 bg-white border border-gray-200 rounded-lg shadow-sm w-[150px]"> {{-- Beri lebar fixed biar tidak melebar --}}
                                        <div class="flex flex-col gap-y-1"> {{-- Menggunakan flex-col untuk label dan input --}}
                                            <label for="quantity-{{$key}}" class="text-xs text-gray-500">Jumlah</label> {{-- Label di atas --}}
                                            <div class="flex items-center justify-between gap-x-3"> {{-- Flex untuk input dan tombol --}}
                                                <input id="quantity-{{$key}}" 
                                                       class="w-full p-0 bg-transparent border-0 text-gray-800 text-base font-bold focus:ring-0 [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none dark:text-gray-800" 
                                                       style="-moz-appearance: textfield;" 
                                                       type="number" 
                                                       value="{{ $item['quantity'] }}" 
                                                       min="1" 
                                                       max="{{ $item['stock'] }}"
                                                       readonly> {{-- Set readonly agar hanya bisa diubah via tombol --}}
                                                
                                                <div class="flex items-center gap-x-1.5">
                                                    <form action="{{ route('cart.update', $key) }}" method="POST" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="quantity" value="{{ max(1, $item['quantity'] - 1) }}">
                                                        <button type="submit" 
                                                                class="size-6 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-full border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none"
                                                                {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                                            <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M5 12h14"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                        
                                                    <form action="{{ route('cart.update', $key) }}" method="POST" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="quantity" value="{{ min($item['stock'], $item['quantity'] + 1) }}">
                                                        <button type="submit" 
                                                                class="size-6 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-full border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none"
                                                                {{ $item['quantity'] >= $item['stock'] ? 'disabled' : '' }}>
                                                            <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M5 12h14"></path>
                                                                <path d="M12 5v14"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- ====================================================== --}}
                                    {{--             AKHIR PERBAIKAN INPUT QTY                 --}}
                                    {{-- ====================================================== --}}


                                    <div class="text-right font-bold text-gray-800 text-base w-28 sm:w-32 flex-shrink-0 tabular-nums">
                                        <span>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                    </div>
                                    
                                    <form action="{{ route('cart.remove', $key) }}" method="POST" class="inline delete-item" data-title="Hapus produk ini?">
                                        @csrf
                                        <button type="submit" title="Hapus item"
                                                class="text-gray-400 hover:text-red-600 hover:bg-red-50 w-10 h-10 flex items-center justify-center rounded-full transition-colors duration-200 sm:ml-2">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="lg:col-span-4 lg:sticky lg:top-28"> 
                    @php
                        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
                        // $ppn = $subtotal * 0.11; // PPN Dihapus
                        $total = $subtotal; // Total = Subtotal
                    @endphp

                    <div class="card">
                        <div class="card__content bg-white/95 backdrop-blur-sm rounded-xl border border-emerald-100 shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-5 pb-4 border-b border-gray-200 flex items-center gap-2">
                                <i class="fas fa-file-invoice-dollar text-emerald-600"></i>
                                Ringkasan Belanja
                            </h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between text-gray-600">
                                    <span>Subtotal (<span class="font-medium">{{ count($cart) }}</span> item)</span>
                                    <span class="font-medium text-gray-800 tabular-nums">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="border-t border-gray-200 my-4"></div>
                            <div class="flex justify-between items-center mb-6">
                                <span class="text-base font-semibold text-gray-900">Total Belanja</span>
                                <span class="text-2xl font-extrabold text-emerald-700 tabular-nums">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>

                            <a href="{{ route('checkout.index') }}" 
                               class="cta-button w-full relative inline-flex items-center justify-center gap-2.5 bg-gradient-to-r from-emerald-600 via-teal-500 to-cyan-500 
                                     text-white font-semibold px-8 py-3 rounded-lg shadow-lg 
                                     hover:shadow-cyan-400/50 transition-all duration-300 
                                     transform hover:scale-[1.03] active:scale-[0.98] overflow-hidden text-base group">
                                <span class="relative z-10">Lanjut ke Checkout</span>
                                <i class="fas fa-arrow-right text-sm relative z-10 transform group-hover:translate-x-1 transition-transform duration-300"></i>
                                <span class="shine"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div> {{-- Penutup div .container --}}
</div>

{{-- Script SweetAlert untuk .delete-item --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-item').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Ambil data dari form untuk custom text
            const title = form.dataset.title || 'Hapus item ini?';
            const text = form.dataset.text || 'Tindakan ini tidak dapat dibatalkan.';
            const isClearCart = form.dataset.title === 'Kosongkan Keranjang?'; // Lebih spesifik
            
            const confirmButtonColor = isClearCart ? '#dc2626' : '#e11d48'; // Merah
            const confirmButtonText = isClearCart ? '<i class="fas fa-trash-alt mr-1"></i> Ya, Kosongkan' : '<i class="fas fa-check mr-1"></i> Ya, hapus';
            const popupBorder = isClearCart ? 'border-red-100' : 'border-gray-100';

            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: confirmButtonText,
                cancelButtonText: '<i class="fas fa-times mr-1"></i> Batal',
                confirmButtonColor: confirmButtonColor, 
                cancelButtonColor: '#d1d5db',
                background: '#ffffff',
                color: '#374151',
                reverseButtons: true,
                backdrop: 'rgba(0,0,0,0.25)',
                width: '320px',
                customClass: {
                    popup: `rounded-xl shadow-lg ${popupBorder} p-4`,
                    title: 'text-base font-semibold text-gray-800',
                    htmlContainer: 'text-sm text-gray-600',
                    confirmButton: 'text-sm px-4 py-1.5 rounded-md font-medium text-white transition-all duration-150',
                    cancelButton: 'text-sm px-4 py-1.5 rounded-md font-medium bg-gray-200 text-gray-700 hover:bg-gray-300 transition-all duration-150',
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit form asli
                }
            });
        });
    });
});
</script>

<style>
/* Font Poppins */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');
.font-poppins { font-family: 'Poppins', sans-serif; }

/* Card Border Berputar (Emerald/Teal Theme) */
.card {
    --glow-primary: hsla(158, 64%, 80%, 0.6); --glow-secondary: hsla(170, 55%, 80%, 0.6); 
    --card-bg: rgba(255, 255, 255, 0.98); --card-shadow: rgba(20, 184, 166, 0.06); 
    --card-shadow-hover: rgba(20, 184, 166, 0.12); --border-line: rgba(204, 251, 241, 0.6); 
    position: relative; border-radius: 0.75rem; overflow: hidden; 
    box-shadow: 0 4px 15px -5px var(--card-shadow); z-index: 1; 
}
.card .card__content {
    position: relative; z-index: 2; background-color: var(--card-bg);
    backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); 
    border-radius: 0.65rem; border: 1px solid var(--border-line); 
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
.card:hover::before { opacity: 0.45; filter: blur(18px); }

@keyframes rotate { to { --angle: 360deg; } }
@property --angle { syntax: "<angle>"; initial-value: 0deg; inherits: false; }


/* Tombol Aksi (Checkout, Jelajahi) */
.cta-button {
    background-size: 200% auto; 
    transition: all 0.4s cubic-bezier(.4,0,.2,1); 
}
.cta-button:hover {
    background-position: right center; 
    box-shadow: 0 7px 20px -4px rgba(20, 184, 166, 0.45); 
    transform: scale(1.03) translateY(-2px); 
}
.cta-button .shine {
    position: absolute; top: -50%; left: -150%; width: 25px; height: 200%;
    background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.4) 50%, rgba(255,255,255,0) 100%);
    transform: rotate(35deg); transition: left 0.7s cubic-bezier(0.23, 1, 0.32, 1); pointer-events: none;
}
.cta-button:hover .shine { left: 150%; }

/* Animasi Halaman */
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.animate-fadeIn { animation: fadeIn 0.8s ease-out both; }

/* Line Clamp & Tabular Nums */
.line-clamp-2 {
    overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    min-height: 2.25rem; /* Sesuaikan jika perlu */
}
.tabular-nums {
    font-variant-numeric: tabular-nums; /* Angka tidak "jiggle" */
}

/* Menghilangkan panah di input number */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type="number"] {
    -moz-appearance: textfield; /* Firefox */
}
</style>
@endsection
@extends('layout.app')

@section('title', 'Checkout')

{{-- Script SweetAlert2 ditambahkan di sini untuk alert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-blue-50 pt-28 pb-40 sm:pb-24">
    <div class="container mx-auto px-5 sm:px-6 lg:px-10">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-10 border-b border-gray-200 pb-4">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-emerald-100 text-emerald-600 rounded-2xl shadow-inner">
                    <i class="fas fa-file-invoice-dollar text-xl"></i>
                </div>
                <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">Checkout</h1>
            </div>
            <span class="text-xs sm:text-sm text-gray-500 flex items-center gap-2 mt-3 sm:mt-0">
                <i class="fas fa-lock text-emerald-500"></i>
                <span>Pembayaran Aman & Terlindungi</span>
            </span>
        </div>

        {{-- 🔔 Notifikasi --}}
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 shadow-sm">
                {{ session('error') }}
            </div>
        @endif
        
        {{-- Tampilkan error validasi dari Controller (penting) --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 shadow-sm">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- x-data di tag <form> sekarang mengontrol semuanya --}}
        <form id="checkout-form" action="{{ route('cart.checkout') }}" method="POST" enctype="multipart/form-data" 
            x-data="{
                payment: '{{ old('payment_method') ?? 'cash' }}', 
                qrisModalOpen: false, 
                copyTextBCA: 'Salin', 
                copyTextMandiri: 'Salin',
                copyToClipboard(text, bank) {
                    navigator.clipboard.writeText(text).then(() => {
                        if (bank === 'bca') {
                            this.copyTextBCA = 'Disalin!';
                            this.copyTextMandiri = 'Salin';
                            setTimeout(() => { this.copyTextBCA = 'Salin' }, 2000);
                        } else if (bank === 'mandiri') {
                            this.copyTextMandiri = 'Disalin!';
                            this.copyTextBCA = 'Salin';
                            setTimeout(() => { this.copyTextMandiri = 'Salin' }, 2000);
                        }
                    }).catch(err => {
                        console.error('Gagal menyalin: ', err);
                        alert('Gagal menyalin nomor rekening.');
                    });
                }
            }">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 lg:gap-8 xl:gap-12">

                {{-- ================== Kolom Kiri ================== --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- 🛒 Daftar Produk --}}
                    <div class="grid gap-4">
                        @forelse($cart as $key => $item)
                            <div class="flex items-start gap-4 bg-white border border-gray-100 rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all duration-300 group">
                                
                                {{-- Checkbox --}}
                                <div class="flex-shrink-0 pt-1"> 
                                    <input type="checkbox" name="selected[]" value="{{ $key }}" class="select-item w-5 h-5 accent-emerald-600 cursor-pointer rounded-md shadow-sm border-gray-300">
                                </div>
                                
                                {{-- Gambar --}}
                                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl overflow-hidden flex-shrink-0 border border-gray-100">
                                    @if(!empty($item['image']))
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['title'] }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 ease-in-out">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs bg-gray-50 p-2">No Image</div>
                                    @endif
                                </div>
                                
                                {{-- Wrapper untuk Info & Harga --}}
                                <div class="flex-1 flex flex-col sm:flex-row sm:items-center sm:justify-between min-w-0">
                                    
                                    {{-- Info Produk --}}
                                    <div class="flex-1 min-w-0">
                                        <h2 class="font-semibold text-gray-800 text-base sm:text-lg leading-tight group-hover:text-emerald-600 transition-colors truncate">
                                            {{ $item['title'] }}
                                        </h2>
                                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5 truncate">{{ $item['category'] }}</p>
                                        <div class="flex flex-wrap items-center gap-2 mt-2">
                                            @if($item['category'] === 'Seragam Sekolah' && !empty($item['size']))
                                                <span class="text-xs font-medium text-gray-600 bg-gray-100 px-2.5 py-1 rounded-full">
                                                    <i class="fas fa-ruler-combined fa-fw mr-1 text-gray-400"></i> Ukuran: {{ $item['size'] }}
                                                </span>
                                            @endif
                                            <span class="text-xs font-medium text-gray-600 bg-gray-100 px-2.5 py-1 rounded-full">
                                                <i class="fas fa-box fa-fw mr-1 text-gray-400"></i> Qty: {{ $item['quantity'] }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    {{-- Harga --}}
                                    <div class="flex-shrink-0 text-left sm:text-right sm:pl-4 mt-2 sm:mt-0">
                                        <p class="text-emerald-600 font-bold text-base sm:text-lg">
                                            Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-12 px-6 bg-white rounded-2xl shadow-sm border border-gray-100">
                                <i class="fas fa-shopping-cart text-4xl mb-4 text-emerald-500"></i>
                                <h3 class="text-xl font-semibold text-gray-700">Keranjang Anda Kosong</h3>
                                <a href="{{ url('/') }}" class="inline-block mt-5 bg-emerald-600 text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-emerald-700 transition shadow-md">Mulai Belanja</a>
                            </div>
                        @endforelse
                    </div>

                    {{-- 💳 Metode Pembayaran --}}
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-lg transition">
                        <h3 class="text-gray-800 font-semibold text-lg mb-4 flex items-center gap-2">
                            <i class="fas fa-credit-card text-emerald-600"></i> Metode Pembayaran
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @php
                                $payments = [
                                    ['id' => 'cash', 'icon' => 'fa-money-bill-wave', 'label' => 'Cash', 'desc' => 'Bayar langsung di kasir.'],
                                    // [PERBAIKAN] Deskripsi KJP diubah
                                    ['id' => 'kjp', 'icon' => 'fa-id-card', 'label' => 'KJP (Bank DKI)', 'desc' => 'Bayar di kasir dengan gesek/tap kartu.'],
                                    // [PERBAIKAN] Deskripsi Transfer diubah
                                    ['id' => 'transfer_bank', 'icon' => 'fa-building-columns', 'label' => 'Transfer Bank', 'desc' => 'Transfer manual ke rekening bank.'],
                                    ['id' => 'e_wallet', 'icon' => 'fa-qrcode', 'label' => 'E-Wallet / QRIS', 'desc' => 'Scan QRIS untuk pembayaran cepat.'],
                                ];
                            @endphp

                            @foreach($payments as $p)
                                <label 
                                    @click="payment = '{{ $p['id'] }}'"
                                    class="relative flex items-center gap-4 p-4 rounded-xl cursor-pointer transition-all duration-300 transform border-2"
                                    :class="{
                                        'bg-emerald-50 border-emerald-400 shadow-lg scale-[1.02]': payment === '{{ $p['id'] }}',
                                        'bg-white border-gray-200 hover:bg-white hover:border-emerald-300 hover:shadow-lg hover:-translate-y-1 shadow-sm': payment !== '{{ $p['id'] }}'
                                    }"
                                >
                                    <div x-show="payment === '{{ $p['id'] }}'" 
                                         x-transition:enter="ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-75"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="ease-in duration-100"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-75"
                                         class="absolute top-2 right-2 w-5 h-5 flex items-center justify-center rounded-full bg-emerald-600 text-white" 
                                         style="display: none;">
                                        <i class="fas fa-check text-xs"></i>
                                    </div>
                                    
                                    <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                         :class="{
                                             'bg-emerald-100 text-emerald-600': payment === '{{ $p['id'] }}',
                                             'bg-gray-100 text-gray-500': payment !== '{{ $p['id'] }}'
                                         }"
                                    >
                                        <i class="fas {{ $p['icon'] }} text-xl"></i>
                                    </div>
                                    
                                    <div class="flex-1">
                                        <p class="font-bold text-base transition-colors"
                                           :class="payment === '{{ $p['id'] }}' ? 'text-emerald-800' : 'text-gray-800'"
                                        >
                                            {{ $p['label'] }}
                                        </p>
                                        <p class="text-sm transition-colors"
                                           :class="payment === '{{ $p['id'] }}' ? 'text-emerald-700' : 'text-gray-500'"
                                        >
                                            {{ $p['desc'] }}
                                        </p>
                                    </div>
                                    
                                    <input type="radio" name="payment_method" value="{{ $p['id'] }}" x-model="payment" class="hidden">
                                </label>
                            @endforeach
                        </div>

                        {{-- Info QRIS & Bank (Desain Baru) --}}
                        <div x-show="payment === 'transfer_bank' || payment === 'e_wallet'" x-transition 
                             class="mt-6 pt-5 border-t border-gray-200" style="display: none;" x-cloak>
                            
                            <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 space-y-6">
                                
                                {{-- Bagian Transfer Bank (dengan Tombol Salin) --}}
                                <div x-show="payment === 'transfer_bank'" style="display: none;">
                                    <h4 class="font-semibold text-emerald-700 text-sm mb-3">Silakan transfer ke rekening:</h4>
                                    <div class="space-y-3">
                                        {{-- Rekening 1 (Contoh: Mandiri) --}}
                                        <div class="flex items-center gap-3 bg-white p-3.5 rounded-xl border border-gray-200 shadow-sm">
                                            <img src="images/images.png" alt="Mandiri" class="h-4">
                                            <div class="flex-1">
                                                <p class="text-gray-700 text-sm">a.n <span class="font-semibold">Koperasi SMKN 8</span></p>
                                                <code class="text-gray-900 font-bold text-base tracking-wider">123 456 7890</code>
                                            </div>
                                            <button 
                                                type="button" 
                                                @click="copyToClipboard('1234567890', 'mandiri')"
                                                class="flex-shrink-0 text-xs font-semibold px-3 py-1.5 rounded-md transition-colors"
                                                :class="copyTextMandiri === 'Salin' ? 'bg-blue-100 text-blue-700 hover:bg-blue-200' : 'bg-green-100 text-green-700'"
                                                x-text="copyTextMandiri">
                                                Salin
                                            </button>
                                        </div>
                                        {{-- Rekening 2 (BCA) --}}
                                        <div class="flex items-center gap-3 bg-white p-3.5 rounded-xl border border-gray-200 shadow-sm">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia_logo.svg/2560px-Bank_Central_Asia_logo.svg.png" alt="BCA" class="h-4">
                                            <div class="flex-1">
                                                <p class="text-gray-700 text-sm">a.n <span class="font-semibold">Koperasi SMKN 8</span></p>
                                                <code class="text-gray-900 font-bold text-base tracking-wider">098 765 4321</code>
                                            </div>
                                            <button 
                                                type="button" 
                                                @click="copyToClipboard('0987654321', 'bca')"
                                                class="flex-shrink-0 text-xs font-semibold px-3 py-1.5 rounded-md transition-colors"
                                                :class="copyTextBCA === 'Salin' ? 'bg-blue-100 text-blue-700 hover:bg-blue-200' : 'bg-green-100 text-green-700'"
                                                x-text="copyTextBCA">
                                                Salin
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Bagian QRIS (Bisa diperbesar + download) --}}
                                <div x-show="payment === 'e_wallet'" style="display: none;">
                                    <h4 class="font-semibold text-emerald-700 text-sm mb-3 text-center">Scan QRIS untuk pembayaran:</h4>
                                    <div class="flex flex-col items-center">
                                        <div class="relative group w-48 sm:w-52">
                                            <img src="{{ asset('images/qris1.jpg') }}" alt="QRIS Koperasi" 
                                                 class="w-full rounded-lg shadow-md border border-gray-200 bg-white p-2 cursor-pointer"
                                                 @click="qrisModalOpen = true">
                                            <div @click="qrisModalOpen = true" 
                                                 class="absolute inset-0 bg-black/60 flex items-center justify-center rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 cursor-pointer">
                                                <i class="fas fa-search-plus text-white text-3xl"></i>
                                            </div>
                                        </div>
                                        <a href="{{ asset('images/qris1.jpg') }}" download="QRIS-Koperasi-SMKN8.jpg"
                                           class="inline-flex items-center justify-center gap-2 text-sm font-medium text-emerald-700 bg-emerald-100 hover:bg-emerald-200 transition-colors px-4 py-2 rounded-lg mt-4">
                                            <i class="fas fa-download text-xs"></i>
                                            Download QRIS
                                        </a>
                                    </div>
                                </div>

                                {{-- Bagian Upload Bukti --}}
                                <div class="pt-5 border-t border-gray-200">
                                    <label class="block font-medium text-gray-800 text-sm mb-1.5">Upload Bukti Pembayaran:</label>
                                    <input type="file" name="proof_of_payment" accept="image/*,.pdf"
                                           class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-white
                                                  file:mr-3 file:py-2 file:px-4 file:rounded-l-lg file:border-0
                                                  file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-600
                                                  hover:file:bg-gray-200 transition-colors duration-200">
                                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, atau PDF (maks. 2MB)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ================== Kolom Kanan ================== --}}
                <div class="lg:col-span-1">
                    <div class="lg:sticky lg:top-32">
                        <div class="sticky bottom-0 left-0 right-0 w-full bg-white border-t border-gray-200 p-5 sm:static sm:border sm:rounded-2xl sm:p-6 sm:shadow-lg">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-receipt text-emerald-600"></i> Ringkasan Belanja
                            </h3>

                            <div class="space-y-3 mt-5 pt-5 border-t border-gray-100">
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Subtotal</span>
                                    <span id="subtotal-amount" class="font-medium text-gray-800">Rp 0</span>
                                </div>
                            </div>

                            <hr class="my-4 border-dashed border-gray-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Total</p>
                                    <p id="total-amount" class="text-2xl sm:text-3xl font-extrabold text-emerald-700">Rp 0</p>
                                </div>
                            </div>

                            <div class="flex flex-col gap-3 text-sm w-full mt-6">
                                <button type="submit" class="flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white px-6 py-3 rounded-xl font-semibold shadow-md hover:shadow-lg transition-all w-full text-base">
                                    Checkout & Bayar
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                                <a href="{{ route('cart.index') }}" class="flex items-center justify-center gap-2 text-sm font-medium text-gray-600 hover:text-emerald-600 transition px-6 py-2.5 rounded-xl border border-gray-300 bg-white hover:bg-gray-50 w-full">
                                    <i class="fas fa-arrow-left text-xs"></i> Kembali ke Keranjang
                                </a>

                                <div class="flex items-center justify-center gap-5 w-full pt-2">
                                    <button type="button" id="select-all" class="flex items-center gap-1.5 text-emerald-600 hover:text-emerald-700 transition text-xs font-medium">
                                        <i class="fas fa-check-square"></i> Centang Semua
                                    </button>
                                    <button type="button" id="select-none" class="flex items-center gap-1.5 text-gray-500 hover:text-gray-700 transition text-xs font-medium">
                                        <i class="fas fa-square"></i> Bersihkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- Modal untuk Perbesar QRIS --}}
<div x-show="qrisModalOpen" 
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @keydown.escape.window="qrisModalOpen = false"
     class="fixed inset-0 z-[999] flex items-center justify-center bg-black/70 backdrop-blur-sm p-4" 
     x-cloak>
    
    <div class="relative max-w-sm w-full" @click.outside="qrisModalOpen = false">
        <button @click="qrisModalOpen = false" class="absolute -top-4 -right-4 w-10 h-10 flex items-center justify-center bg-white rounded-full text-gray-700 hover:text-red-500 transition-colors shadow-lg">
            <i class="fas fa-times text-xl"></i>
        </button>
        <img src="{{ asset('images/qris1.jpg') }}" alt="Scan QRIS" class="w-full rounded-2xl border-4 border-white shadow-2xl">
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    
    // Fungsi untuk memutar suara
    function playCheckoutSound() {
        try {
            if ('speechSynthesis' in window) {
                const message = new SpeechSynthesisUtterance();
                message.text = "terimakasih telah checkout produk ini";
                message.lang = 'id-ID';
                window.speechSynthesis.speak(message);
            }
        } catch (e) {
            console.warn("Speech Synthesis tidak didukung atau gagal: ", e);
        }
    }

    const checkboxes = document.querySelectorAll('.select-item');
    const totalAmount = document.getElementById('total-amount');
    const subtotalAmount = document.getElementById('subtotal-amount');
    const selectAllBtn = document.getElementById('select-all');
    const selectNoneBtn = document.getElementById('select-none');

    const prices = @json(collect($cart)->mapWithKeys(fn($i, $k) => [$k => $i['price'] * $i['quantity']]));

    function updateTotal() {
        let total = 0;
        checkboxes.forEach(cb => { if (cb.checked) total += prices[cb.value] ?? 0; });
        const formatted = 'Rp ' + total.toLocaleString('id-ID');
        if (subtotalAmount) subtotalAmount.textContent = formatted;
        if (totalAmount) totalAmount.textContent = formatted;
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updateTotal));
    selectAllBtn?.addEventListener('click', () => { checkboxes.forEach(cb => cb.checked = true); updateTotal(); });
    selectNoneBtn?.addEventListener('click', () => { checkboxes.forEach(cb => cb.checked = false); updateTotal(); });
    updateTotal();

    // Validasi Form Checkout
    const checkoutForm = document.getElementById('checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault(); 

            const selectedItems = document.querySelectorAll('.select-item:checked');
            if (selectedItems.length === 0) {
                Swal.fire({
                    title: 'Oops... Belum Ada Item!',
                    text: 'Anda harus memilih minimal satu item untuk di-checkout.',
                    icon: 'warning',
                    confirmButtonText: 'OK, Saya Mengerti',
                    confirmButtonColor: '#10b981',
                    customClass: {
                        popup: 'rounded-xl shadow-lg',
                        title: 'text-lg font-semibold text-gray-800',
                    }
                });
                return; 
            }
            
            const formData = new FormData(checkoutForm);
            const currentPaymentMethod = formData.get('payment_method');
            
            const proofInput = document.querySelector('input[name="proof_of_payment"]');
            const hasFile = proofInput && proofInput.files && proofInput.files.length > 0;
            
            const requiresProof = (currentPaymentMethod === 'e_wallet' || currentPaymentMethod === 'transfer_bank');

            if (requiresProof && !hasFile) {
                Swal.fire({
                    title: 'Bukti Pembayaran Diperlukan',
                    text: 'Anda harus meng-upload bukti pembayaran untuk metode yang dipilih.',
                    icon: 'error',
                    confirmButtonText: 'OK, Saya Mengerti',
                    confirmButtonColor: '#10b981',
                    customClass: {
                        popup: 'rounded-xl shadow-lg',
                        title: 'text-lg font-semibold text-gray-800',
                    }
                });
                return; 
            }
            
            // Panggil suara
            playCheckoutSound();
            
            // Tunda submit sedikit agar suara sempat terputar
            setTimeout(() => {
                checkoutForm.submit();
            }, 300); // Penundaan 300ms
        });
    }

    @if($errors->has('proof_of_payment') || old('payment_method') == 'transfer_bank' || old('payment_method') == 'e_wallet')
        const alpineEl = document.querySelector('[x-data]');
        if (alpineEl && alpineEl.__x) {
            alpineEl.__x.data.payment = '{{ old('payment_method') }}';
        }
    @endif
});
</script>
@endsection
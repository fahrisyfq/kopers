<!-- filepath: resources/views/orders/show.blade.php -->
@extends('layout.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white rounded-2xl shadow-2xl border border-yellow-100 overflow-hidden">
    <div class="px-8 py-8">
        <h2 class="text-2xl font-extrabold mb-6 text-indigo-700 flex items-center gap-3 drop-shadow">
            <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 7v4a1 1 0 001 1h3v6a1 1 0 001 1h6a1 1 0 001-1v-6h3a1 1 0 001-1V7a1 1 0 00-1-1H4a1 1 0 00-1 1z"/>
            </svg>
            Detail Pesanan <span class="text-gray-400 font-normal">#{{ $order->id }}</span>
        </h2>
        <div class="mb-6 flex items-center justify-between bg-gradient-to-r from-yellow-50 via-white to-indigo-50 rounded-lg px-4 py-3 shadow">
            <span class="text-gray-600 font-semibold">Total Pembayaran:</span>
            <span class="text-2xl font-extrabold text-yellow-600 drop-shadow">Rp {{ number_format($order->total_price,0,',','.') }}</span>
        </div>
        @if($order->transfer_proof)
            <div class="mb-6">
                <div class="font-semibold text-indigo-700 mb-2">Bukti Transfer:</div>
                <div class="flex gap-4 flex-wrap justify-center">
                    @if(is_array($order->transfer_proof))
                        @foreach($order->transfer_proof as $proof)
                            <img src="{{ asset('storage/' . $proof) }}" class="w-44 h-44 object-cover rounded-xl shadow-lg border-2 border-yellow-200 bg-white" alt="Bukti Transfer">
                        @endforeach
                    @else
                        <img src="{{ asset('storage/' . $order->transfer_proof) }}" class="w-44 h-44 object-cover rounded-xl shadow-lg border-2 border-yellow-200 bg-white" alt="Bukti Transfer">
                    @endif
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 mb-4 rounded shadow flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-8 text-center">
            <a href="{{ route('product.index') }}" class="inline-block bg-gradient-to-r from-indigo-500 to-indigo-700 hover:from-indigo-600 hover:to-indigo-800 text-white px-7 py-2.5 rounded-lg shadow-lg transition font-bold text-base">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Menu Produk
            </a>
        </div>
    </div>
</div>
@endsection
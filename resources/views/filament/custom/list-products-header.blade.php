<div class="flex items-center justify-between mb-4">
    {{-- Kiri: Komponen Livewire Toggle --}}
    <div>
        @livewire($storeStatusToggle)
    </div>

    {{-- Kanan: Tombol Tambah Produk --}}
    <div class="flex items-center gap-2">
        @foreach ($this->getHeaderActions() as $action)
            {{ $action }}
        @endforeach
    </div>
</div>

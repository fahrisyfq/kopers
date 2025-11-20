<div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow border dark:border-gray-700 flex items-center justify-between mb-4">
    {{-- Grup Kiri --}}
    <div class="flex items-center gap-3">
        <div class="text-xl font-bold text-gray-900 dark:text-white whitespace-nowrap">
            Aktifkan Toko?
        </div>

        <label for="store_toggle" class="flex items-center cursor-pointer">
            <input type="checkbox" id="store_toggle" 
                class="w-10 h-5 text-blue-600 rounded-full focus:ring-blue-500 dark:focus:ring-blue-600 border-gray-300 dark:border-gray-700 bg-gray-200 dark:bg-gray-700" 
                wire:model.live="isStoreOpen">
        </label>
    </div>

    {{-- Grup Kanan --}}
    <div class="text-right ml-4">
        <h3 class="text-base font-bold {{ $isStoreOpen ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400' }}">
            Status Toko: {{ $isStoreOpen ? 'Buka' : 'Tutup' }}
        </h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
            Jika Tutup, katalog web akan dinonaktifkan (abu-abu).
        </p>
    </div>
</div>

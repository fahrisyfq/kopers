@extends('layout.app')

@section('popup')
<div id="product-modal" class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white w-full max-w-4xl rounded-lg shadow-lg overflow-hidden">
        <div class="flex justify-between items-center p-4 border-b">
            <h2 id="modal-title" class="text-xl font-bold text-gray-800">Judul Kategori</h2>
            <button onclick="closeModal()" class="text-gray-500 hover:text-red-500 text-2xl">&times;</button>
        </div>
        <div id="modal-content" class="p-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 max-h-[70vh] overflow-y-auto">
            <!-- Isi produk dimasukkan lewat JS -->
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="sticky top-16 md:top-0 bg-white shadow-sm z-10">
    <div class="container mx-auto px-4">
        <div class="flex gap-4 border-b py-4">
            <button onclick="openModal('ganci')" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                Gantungan Kunci
            </button>
            <button onclick="openModal('bucket')" class="px-4 py-2 bg-pink-600 text-white rounded hover:bg-pink-700 transition">
                Bucket Bunga
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('product-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalContent = document.getElementById('modal-content');

    function openModal(category) {
        modal.classList.remove('hidden');
        let products = [];

        if (category === 'ganci') {
            modalTitle.innerText = 'Gantungan Kunci';
            products = [
                { name: 'Ganci Lucu 1', img: 'https://source.unsplash.com/400x400/?keychain,1' },
                { name: 'Ganci Unik 2', img: 'https://source.unsplash.com/400x400/?keychain,2' },
            ];
        } else if (category === 'bucket') {
            modalTitle.innerText = 'Bucket Bunga';
            products = [
                { name: 'Bucket Mawar', img: 'https://source.unsplash.com/400x400/?bouquet,1' },
                { name: 'Bucket Sunflower', img: 'https://source.unsplash.com/400x400/?bouquet,2' },
            ];
        }

        modalContent.innerHTML = '';
        products.forEach(product => {
            const card = `
                <div class="bg-white rounded shadow hover:shadow-md transition">
                    <img src="${product.img}" alt="${product.name}" class="w-full h-48 object-cover rounded-t">
                    <div class="p-3">
                        <h3 class="font-semibold text-sm text-gray-800 mb-1">${product.name}</h3>
                        <a href="#" class="text-indigo-600 text-sm hover:underline">Lihat Produk</a>
                    </div>
                </div>
            `;
            modalContent.insertAdjacentHTML('beforeend', card);
        });
    }

    function closeModal() {
        modal.classList.add('hidden');
    }
</script>
@endpush

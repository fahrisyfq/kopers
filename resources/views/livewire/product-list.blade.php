<div x-data="{ showModal: false, modalProduct: {}, activeImg: 0, selectedSize: '' }">
    @php
        $categories = $products->groupBy('category');
    @endphp

    @foreach($categories as $category => $items)
        <div class="mb-10">
            <div class="flex items-center gap-3 mb-4">
                <span class="inline-block bg-indigo-400 text-white text-xs font-bold px-3 py-1 rounded-full shadow">{{ $category }}</span>
                <h2 class="text-xl md:text-2xl font-extrabold tracking-tight text-indigo-700">{{ $category }}</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                @foreach($items as $product)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden group hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 flex flex-col">
                        <div 
                            class="relative overflow-hidden h-36 flex items-center justify-center bg-gradient-to-br from-yellow-50 via-white to-indigo-50 cursor-pointer"
                            @click="
                                showModal = true;
                                modalProduct = {
                                    title: @js($product->title),
                                    images: @js($product->images->pluck('url')->map(fn($url) => asset('storage/' . $url))),
                                    category: @js($product->category),
                                    price: '{{ number_format($product->price, 0, ',', '.') }}',
                                    description: @js($product->description),
                                    sizes: @js($product->sizes->map(fn($s) => ['size' => $s->size, 'stock' => $s->stock])),
                                };
                                activeImg = 0;
                                selectedSize = modalProduct.sizes.length ? modalProduct.sizes[0].size : '';
                            "
                        >
                            <img src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->url) : asset('storage/' . $product->image) }}" alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                        </div>
                        <div class="p-3 flex flex-col flex-1">
                            <h2 class="text-base font-bold mb-0.5 text-indigo-700 truncate">{{ $product->title }}</h2>
                            <p class="text-[11px] text-yellow-600 font-semibold mb-0.5">{{ $product->category }}</p>
                            <p class="text-indigo-600 font-bold text-base mb-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <p class="text-gray-600 text-xs mb-2 line-clamp-2">{{ $product->description }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <div class="mt-6 flex justify-center">
        {{ $products->links() }}
    </div>

    <!-- Modal Produk -->
    <div x-show="showModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative">
            <button @click="showModal = false" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-xl">&times;</button>
            <!-- Carousel Gambar -->
            <div class="mb-4">
                <template x-for="(img, idx) in modalProduct.images" :key="idx">
                    <img :src="img" class="w-full h-48 object-contain rounded-lg border border-gray-100 bg-gray-50" x-show="activeImg === idx">
                </template>
                <div class="flex justify-center mt-2 gap-2">
                    <template x-for="(img, idx) in modalProduct.images" :key="idx">
                        <button @click="activeImg = idx" :class="activeImg === idx ? 'border-green-500' : 'border-gray-300'" class="w-8 h-8 border rounded overflow-hidden">
                            <img :src="img" class="w-full h-full object-cover">
                        </button>
                    </template>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-indigo-700 mb-2" x-text="modalProduct.title"></h2>
            <p class="text-indigo-600 font-bold text-lg mb-2">Rp <span x-text="modalProduct.price"></span></p>
            <p class="text-gray-700 text-sm mb-3" x-text="modalProduct.description"></p>
            <!-- Pilihan Ukuran -->
            <div class="mb-3" x-show="modalProduct.sizes.length">
                <label class="block text-sm font-bold mb-1">Pilih Ukuran:</label>
                <select x-model="selectedSize" class="w-full border rounded px-2 py-1">
                    <template x-for="size in modalProduct.sizes" :key="size.size">
                        <option :value="size.size" x-text="size.size + ' (Stok: ' + size.stock + ')'"></option>
                    </template>
                </select>
            </div>
            <template x-if="modalProduct.sizes.length">
                <span class="inline-block bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow mb-2">
                    Stok: <span x-text="modalProduct.sizes.find(s => s.size === selectedSize)?.stock"></span>
                </span>
            </template>
            <button class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 rounded shadow mt-2">Beli Seragam</button>
        </div>
    </div>
</div>
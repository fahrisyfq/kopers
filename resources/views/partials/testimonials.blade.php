{{-- PUSH STYLES --}}
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    /* Pagination Dots */
    .swiper-pagination-bullet { background-color: #cbd5e1; opacity: 1; width: 6px; height: 6px; transition: all 0.3s ease; }
    .swiper-pagination-bullet-active { background-color: #10b981; width: 18px; border-radius: 3px; }
    /* Navigation Arrows (Desktop) */
    .swiper-button-next, .swiper-button-prev { color: #10b981; background: white; width: 36px; height: 36px; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,0.08); transition: all 0.3s ease; }
    .swiper-button-next:hover, .swiper-button-prev:hover { background: #ecfdf5; transform: scale(1.05); }
    .swiper-button-next::after, .swiper-button-prev::after { font-size: 14px; font-weight: bold; }
    /* Card Effect */
    .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.8); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); }
</style>
@endpush

{{-- MAIN SECTION --}}
<section id="testimonials" class="py-16 relative overflow-hidden font-poppins bg-slate-50/80" x-data="testimonialsData()">

    {{-- Background Decoration (Halus) --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-[10%] -left-[5%] w-[500px] h-[500px] bg-emerald-200/20 rounded-full blur-[100px] mix-blend-multiply"></div>
        <div class="absolute top-[40%] -right-[5%] w-[400px] h-[400px] bg-blue-200/20 rounded-full blur-[90px] mix-blend-multiply"></div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 relative z-10">
        
        {{-- HEADER --}}
        <div class="text-center max-w-2xl mx-auto mb-10" data-aos="fade-up">
            <span class="inline-flex items-center gap-1.5 text-emerald-600 font-bold tracking-wider text-[10px] uppercase bg-white px-3 py-1 rounded-full mb-3 shadow-sm border border-emerald-100/50">
                <i class="fas fa-heart text-emerald-500"></i> Ulasan
            </span>
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-2 leading-tight tracking-tight">
                Kata Sobat <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Kopsis</span>
            </h2>
            <p class="text-slate-500 text-xs md:text-sm leading-relaxed">
                Pengalaman teman-teman yang sudah berbelanja di sini.
            </p>
        </div>

        {{-- SLIDER CONTAINER --}}
        <div class="relative px-0 md:px-8">
            
            {{-- Navigasi Panah --}}
            <div class="swiper-button-prev !hidden md:!flex !-left-4"></div>
            <div class="swiper-button-next !hidden md:!flex !-right-4"></div>

            <div class="swiper testimonialSwiper !pb-12 !pt-2">
                <div class="swiper-wrapper">
                    
                    {{-- STATE: KOSONG --}}
                    @if(collect($reviews)->isEmpty())
                        <div class="swiper-slide w-full">
                            <div class="flex flex-col items-center justify-center py-10 bg-white/60 rounded-3xl border-2 border-dashed border-slate-200 text-center max-w-sm mx-auto">
                                <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center mb-3 text-slate-300">
                                    <i class="far fa-comment-dots text-2xl"></i>
                                </div>
                                <h3 class="text-sm font-bold text-slate-700">Belum ada ulasan</h3>
                                <p class="text-slate-500 text-[10px] mt-1">Yuk, jadilah yang pertama!</p>
                            </div>
                        </div>
                    @else
                        {{-- STATE: ADA DATA (CARD) --}}
                        @foreach($reviews as $review)
                            <div class="swiper-slide h-auto">
                                <div class="glass-card p-5 md:p-6 rounded-3xl transition-all duration-300 hover:shadow-[0_10px_30px_rgba(16,185,129,0.08)] hover:-translate-y-1 flex flex-col h-full group relative overflow-hidden">
                                    
                                    {{-- Tanda Kutip --}}
                                    <div class="absolute top-3 right-5 text-4xl font-serif text-slate-100 group-hover:text-emerald-100/60 transition-colors select-none">”</div>

                                    {{-- 1. Profil User --}}
                                    <div class="flex items-center gap-3 mb-3 relative z-10">
                                        <div class="flex-shrink-0">
                                            @if($review['is_anon'])
                                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-slate-700 to-slate-800 flex items-center justify-center text-white shadow-sm ring-2 ring-white/80">
                                                    <i class="fas fa-user-secret text-xs"></i>
                                                </div>
                                            @else
                                                <img src="{{ $review['avatar'] }}" class="w-9 h-9 rounded-full object-cover shadow-sm ring-2 ring-white/80 bg-gray-50" alt="Avatar">
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-slate-800 text-sm truncate">{{ $review['name'] }}</h4>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span class="text-[8px] font-semibold px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-100/50 truncate">{{ $review['role'] }}</span>
                                                <span class="text-[8px] text-slate-400">&bull; {{ $review['time'] }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- 2. Rating Stars --}}
                                    <div class="flex mb-3 text-yellow-400 text-[9px] gap-0.5 relative z-10">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas {{ $i <= $review['rating'] ? 'fa-star drop-shadow-sm' : 'fa-star text-slate-200' }}"></i>
                                        @endfor
                                    </div>

                                    {{-- 3. Isi Review --}}
                                    <div class="flex-grow mb-4 relative z-10">
                                        <p class="text-slate-600 text-xs leading-relaxed line-clamp-3">
                                            {{ $review['text'] }}
                                        </p>
                                    </div>

                                    {{-- 🔥 4. Produk yang dibeli (VERSI IKON) 🔥 --}}
                                    @if($review['product'])
                                        <div class="mt-auto pt-3 border-t border-dashed border-slate-100 relative z-10">
                                            <div class="flex items-center gap-2.5 bg-slate-50/70 p-2 rounded-xl border border-slate-100 group-hover:border-emerald-100/50 transition-colors">
                                                
                                                {{-- IKON PENGGANTI GAMBAR --}}
                                                <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shadow-sm border border-slate-100/80 flex-shrink-0 group-hover:scale-105 transition-transform">
                                                    <i class="fas fa-shopping-bag text-emerald-500 text-xs"></i>
                                                </div>
                                                
                                                <div class="min-w-0">
                                                    <p class="text-[8px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Membeli</p>
                                                    <p class="text-[10px] font-bold text-slate-700 truncate w-full" title="{{ $review['product']['title'] }}">
                                                        {{ $review['product']['title'] }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                
                {{-- Pagination Dots --}}
                <div class="swiper-pagination"></div>
            </div>
        </div>

        {{-- Tombol CTA --}}
        @if(collect($reviews)->isNotEmpty())
            <div class="mt-2 text-center">
                <a href="{{ route('product.index') }}" 
                   class="inline-flex items-center gap-2 bg-white text-emerald-600 text-xs font-bold py-2 px-5 rounded-full border border-emerald-100 shadow-sm hover:shadow-md hover:border-emerald-200 hover:-translate-y-0.5 transition-all">
                    <span>Lihat Produk Lainnya</span>
                    <i class="fas fa-arrow-right text-[9px]"></i>
                </a>
            </div>
        @endif

    </div>
</section>

{{-- PUSH SCRIPTS --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Inisialisasi Swiper
        const swiper = new Swiper(".testimonialSwiper", {
            slidesPerView: 1.1, // Default Mobile: Sedikit kelihatan slide berikutnya
            spaceBetween: 16,
            centeredSlides: false,
            loop: {{ count($reviews) > 3 ? 'true' : 'false' }},
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
                dynamicBullets: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                640: { // Tablet Kecil
                    slidesPerView: 2.2,
                    spaceBetween: 20,
                },
                1024: { // Desktop
                    slidesPerView: 3.2,
                    spaceBetween: 24,
                    centeredSlides: false,
                },
            },
        });
    });

    // Alpine Data (Opsional)
    document.addEventListener('alpine:init', () => {
        Alpine.data('testimonialsData', () => ({
            reviews: @json($reviews),
        }));
    });
</script>
@endpush
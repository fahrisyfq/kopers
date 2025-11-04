<header id="main-navbar" class="fixed top-0 left-0 w-full bg-white/80 backdrop-blur-md border-b border-blue-100 z-50 transition-all duration-300">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">

        <a href="{{ url('/') }}" class="logo flex-shrink-0 flex items-center space-x-2 fade-in-right">
            <div class="bg-gradient-to-tr from-blue-100 to-blue-200 p-2.5 rounded-xl shadow-sm hover:shadow-blue-200 transition-all duration-700">
                <i class="fas fa-university text-blue-600 text-xl logo-icon"></i>
            </div>
            <span class="text-xl md:text-2xl font-extrabold logo-glow tracking-tight">
                Koperasi Sekolah
            </span>
        </a>

        <div class="hidden md:flex justify-center flex-1">
            <nav class="main-nav-links">
                <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Beranda</a> 
                <a href="{{ route('product.index') }}" class="nav-link {{ request()->is('product*') ? 'active' : '' }}">Produk</a>
                <a href="{{ route('kontak') }}" class="nav-link {{ request()->is('kontak') ? 'active' : '' }}">Kontak</a>
            </nav>
        </div>

        <div class="hidden md:flex items-center space-x-4">
            
            <form action="{{ route('product.search') }}" method="GET" class="relative">
                <input type="text" name="q" placeholder="Cari..." 
                       class="pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition-all w-48 hover:w-56" />
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </form>

            <button id="star-btn" class="p-2 rounded-full hover:bg-yellow-100 transition relative">
                <i id="star-icon" class="fas fa-star text-lg text-yellow-500 transition-all duration-300"></i>
                <span id="star-confetti" class="absolute inset-0 pointer-events-none"></span>
            </button>
            <div id="star-welcome"
                 class="absolute left-1/2 top-14 -translate-x-1/2 bg-yellow-50 border border-yellow-300 text-yellow-800 font-semibold px-3 py-1.5 rounded-lg shadow opacity-0 pointer-events-none transition-all duration-500 z-[99999] text-sm">
                Selamat datang di koperasi sekolah!
            </div>

            @auth
                @if(empty(Auth::user()->nis) || empty(Auth::user()->kelas) || empty(Auth::user()->jurusan))
                    <button type="button" class="relative cursor-not-allowed opacity-50" title="Lengkapi profil terlebih dahulu untuk melihat pesanan!">
                        <i class="fas fa-shopping-cart text-lg text-gray-400"></i>
                    </button>
                @else
                    <a href="{{ route('cart.index') }}" class="relative">
                        <i class="fas fa-shopping-cart text-lg text-blue-600"></i>
                        <span id="cart-count"
                              class="absolute -top-1 -right-2 bg-red-500 text-white text-[10px] px-1.5 rounded-full {{ count(session('cart', [])) > 0 ? '' : 'hidden' }}">
                            {{ count(session('cart', [])) }}
                        </span>
                    </a>
                @endif
            @else
                <button type="button" class="relative cursor-not-allowed opacity-50" title="Silakan login terlebih dahulu untuk melihat pesanan!">
                    <i class="fas fa-shopping-cart text-lg text-gray-400"></i>
                </button>
            @endauth

            @auth
                 @if(empty(Auth::user()->nis) || empty(Auth::user()->kelas) || empty(Auth::user()->jurusan))
                    <button type="button" class="relative cursor-not-allowed opacity-50" title="Lengkapi profil terlebih dahulu untuk melihat pesanan!">
                        <i class="fas fa-box text-lg text-gray-400"></i>
                    </button>
                @else
                    <a href="{{ route('orders.index') }}" class="relative hover:scale-105 transition-transform">
                        <i class="fas fa-box text-lg text-blue-600"></i>
                    </a>
                @endif
            @else
                <button type="button" class="relative cursor-not-allowed opacity-50" title="Silakan login terlebih dahulu untuk melihat pesanan!">
                    <i class="fas fa-box text-lg text-gray-400"></i>
                </button>
            @endauth

            @auth
                @if(empty(Auth::user()->nis) || empty(Auth::user()->kelas) || empty(Auth::user()->jurusan))
                    <a href="{{ route('profile.complete') }}"
                       class="flex items-center gap-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all">
                        <i class="fas fa-user-edit"></i><span>Lengkapi</span>
                    </a>
                @else
                    @if(session('profile_completed'))
                        <div class="flex items-center gap-1.5 bg-green-50 border border-green-400 text-green-700 px-3 py-1.5 rounded-lg text-xs font-medium animate-pulse">
                            <i class="fas fa-check-circle"></i> <span>Profil Lengkap</span>
                        </div>
                    @endif
                @endif
            @else
                <button id="open-modal"
                        class="flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all">
                    <i class="fas fa-sign-in-alt"></i><span>Masuk</span>
                </button>
            @endauth

            @auth
                <div class="flex items-center space-x-2 border-l border-gray-200 pl-3">
                    <div class="profile-dropdown relative">
                        <button class="profile-button flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 hover:bg-blue-100 transition-all text-blue-700 text-sm font-medium">
                            <div class="w-7 h-7 rounded-full bg-blue-200 flex items-center justify-center">
                                <i class="fas fa-user text-blue-600 text-xs"></i>
                            </div>
                            <span>{{ Str::limit(Auth::user()->name, 10) }}</span>
                            <i class="fas fa-chevron-down text-xs ml-1"></i>
                        </button>
                        <div class="profile-dropdown-content absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg py-1 z-10 hidden">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-100 text-sm">
                                <i class="fas fa-user-cog w-4"></i> Kelola Profil
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 text-sm">
                                    <i class="fas fa-sign-out-alt w-4"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endauth
        </div>

        <div class="flex md:hidden items-center space-x-3">
            @auth
                @if(empty(Auth::user()->nis) || empty(Auth::user()->kelas) || empty(Auth::user()->jurusan))
                    <button type="button" class="relative cursor-not-allowed opacity-50" title="Lengkapi profil...">
                        <i class="fas fa-shopping-cart text-lg text-gray-400"></i>
                    </button>
                @else
                    <a href="{{ route('cart.index') }}" class="relative">
                        <i class="fas fa-shopping-cart text-lg text-blue-600"></i>
                        <span id="cart-count-mobile-header"
                              class="absolute -top-1 -right-2 bg-red-500 text-white text-[10px] px-1.5 rounded-full {{ count(session('cart', [])) > 0 ? '' : 'hidden' }}">
                            {{ count(session('cart', [])) }}
                        </span>
                    </a>
                @endif
            @else
                 <button type="button" class="relative cursor-not-allowed opacity-50" title="Silakan login terlebih dahulu!">
                    <i class="fas fa-shopping-cart text-lg text-gray-400"></i>
                </button>
            @endauth
            <button id="mobile-menu-button" @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-full hover:bg-blue-100">
                <i class="fas fa-bars text-lg text-blue-700"></i>
            </button>
        </div>

    </div>

    <div class="w-full bg-blue-50 border-t border-blue-100 text-sm">
        <div class="container mx-auto px-4 py-2 flex flex-col sm:flex-row justify-between items-center flex-wrap gap-2">
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 text-xs text-gray-600">
                <p class="flex items-center">
                    <i class="fas fa-clock mr-1 text-yellow-500"></i>
                    <span>Senin–Jumat 07.00–15.00</span>
                </p>
                <p class="flex items-center">
                    <i class="fas fa-calendar-times mr-1 text-red-400"></i>
                    <span>Libur nasional tutup</span>
                </p>
            </div>
            <button @click="openPanduan = true" class="flex items-center gap-1.5 text-blue-600 font-medium hover:text-blue-700 text-xs sm:text-sm transition-colors">
                <i class="fas fa-circle-info text-blue-500"></i>
                <span class="underline underline-offset-4 decoration-2">Panduan</span>
            </button>
        </div>
    </div>
</header>

<div class="container mx-auto px-4 mt-3">
    <div class="flex items-center space-x-1 text-sm">
        <i class="fas fa-star text-yellow-500"></i>
        <a href="{{ url('/produk-terlaris') }}" class="text-blue-700 font-semibold hover:underline">Produk Terlaris</a>
    </div>
</div>

<div id="auth-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-xl w-full max-w-sm mx-4 shadow-2xl border border-blue-100 relative p-6 text-center">
        <img src="/images/logo.jpg" alt="Logo" class="w-14 h-14 mx-auto mb-3 rounded-full shadow bg-yellow-50">
        <h2 class="text-xl font-bold text-blue-700 mb-1">Masuk ke Akun</h2>
        <p class="text-gray-500 text-sm mb-4">Pilih metode login:</p>
        <a href="{{ route('google.login') }}"
           class="w-full flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg shadow transition text-sm mb-3">
            <i class="fab fa-google"></i> Masuk sebagai Siswa
        </a>
        <a href="{{ url('/admin/login') }}" 
           class="hidden admin-login w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg shadow transition text-sm">
            <i class="fas fa-user-shield"></i> Masuk sebagai Admin
        </a>
        <button id="close-modal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-lg">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>


<div 
    x-show="mobileMenuOpen" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[99]" 
    @click="mobileMenuOpen = false"
    style="display: none;"
    x-cloak
></div>

<div 
    x-show="mobileMenuOpen" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed top-0 left-0 w-80 h-full bg-white shadow-2xl z-[100] flex flex-col"
    style="display: none;"
    x-cloak
>
    <div class="flex items-center justify-between p-4 border-b border-blue-100">
        <a href="{{ url('/') }}" class="logo flex items-center space-x-2">
            <div class="bg-gradient-to-tr from-blue-100 to-blue-200 p-2.5 rounded-xl shadow-sm">
                <i class="fas fa-university text-blue-600 text-xl logo-icon"></i>
            </div>
            <span class="text-xl font-extrabold logo-glow tracking-tight">Koperasi</span>
        </a>
        <button @click="mobileMenuOpen = false" class="p-2 text-gray-400 hover:text-red-500">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <div class="flex-1 flex flex-col justify-between overflow-y-auto">
        <nav class="p-4 space-y-2">
            <a href="{{ url('/') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-50 text-blue-700 font-medium transition-colors">
                <i class="fas fa-home fa-fw w-5"></i>
                <span>Beranda</span>
            </a>
            <a href="{{ route('product.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-50 text-blue-700 font-medium transition-colors">
                <i class="fas fa-box fa-fw w-5"></i>
                <span>Produk</span>
            </a>
            <a href="{{ route('kontak') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-50 text-blue-700 font-medium transition-colors">
                <i class="fas fa-envelope fa-fw w-5"></i>
                <span>Kontak</span>
            </a>
            
            <div class="pt-2">
                <form action="{{ route('product.search') }}" method="GET">
                    <div class="relative">
                        <input type="text" name="q" placeholder="Cari produk..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500 text-sm">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                </form>
            </div>
            
            <hr class="pt-2">
            
            @auth
                @if(empty(Auth::user()->nis) || empty(Auth::user()->kelas) || empty(Auth::user()->jurusan))
                    <a href="{{ route('profile.complete') }}" class="flex items-center gap-3 p-3 rounded-lg bg-yellow-100 text-yellow-700 font-medium transition-colors">
                        <i class="fas fa-user-edit fa-fw w-5"></i>
                        <span>Lengkapi Profil Anda</span>
                    </a>
                @else
                    <a href="{{ route('cart.index') }}" class="flex items-center justify-between gap-3 p-3 rounded-lg hover:bg-blue-50 text-blue-700 font-medium transition-colors">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-shopping-cart fa-fw w-5"></i>
                            <span>Keranjang</span>
                        </div>
                        <span id="mobile-cart-count" class="bg-red-500 text-white text-[10px] px-1.5 rounded-full {{ count(session('cart', [])) > 0 ? '' : 'hidden' }}">
                            {{ count(session('cart', [])) }}
                        </span>
                    </a>
                    <a href="{{ route('orders.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-50 text-blue-700 font-medium transition-colors">
                        <i class="fas fa-box fa-fw w-5"></i>
                        <span>Pesanan Saya</span>
                    </a>
                @endif
            @else
                <button @click.prevent="mobileMenuOpen = false; document.getElementById('open-modal').click();" class="w-full flex items-center gap-3 p-3 rounded-lg hover:bg-blue-50 text-blue-700 font-medium transition-colors">
                    <i class="fas fa-sign-in-alt fa-fw w-5"></i>
                    <span>Masuk / Login</span>
                </button>
            @endauth

            <button @click.prevent="mobileMenuOpen = false; openPanduan = true" class="w-full flex items-center gap-3 p-3 rounded-lg hover:bg-blue-50 text-blue-700 font-medium transition-colors">
                <i class="fas fa-circle-info fa-fw w-5"></i>
                <span>Panduan</span>
            </button>
        </nav>

        <div class="p-4 border-t border-gray-100">
            @auth
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-800 text-sm">{{ Auth::user()->name }}</span>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('profile.edit') }}" 
                       class="flex-1 flex items-center justify-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all">
                        <i class="fas fa-user-cog"></i> Profil
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all">
                            Logout
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </div>
</div>


<script>
    const openModal = document.getElementById("open-modal");
    const closeModal = document.getElementById("close-modal");
    const modal = document.getElementById("auth-modal");
    openModal?.addEventListener("click", () => modal.classList.remove("hidden"));
    closeModal?.addEventListener("click", () => modal.classList.add("hidden"));

    // Efek Bintang (Tidak berubah)
    document.getElementById('star-btn')?.addEventListener('click', function () {
        const star = document.getElementById('star-icon');
        const confettiContainer = document.getElementById('star-confetti');
        const welcome = document.getElementById('star-welcome');
        star.classList.add('star-animate');
        setTimeout(() => star.classList.remove('star-animate'), 700);
        confettiContainer.innerHTML = '';
        const total = 15;
        for (let i = 0; i < total; i++) {
            const s = document.createElement('span');
            s.className = 'mini-star';
            s.style.left = '50%';
            s.style.top = '50%';
            const spread = 60 + Math.random() * 60;
            const offsetX = (Math.random() - 0.5) * 80;
            s.style.setProperty('--fall-x', `${offsetX}px`);
            s.style.setProperty('--fall-y', `${spread}px`);
            s.innerHTML = `<svg width="14" height="14" viewBox="0 0 20 20" fill="${['#fbbf24','#f472b6','#60a5fa','#34d399','#f87171'][i%5]}" xmlns="http://www.w3.org/2000/svg"><polygon points="10,2 12,7.5 18,8 13.5,12 15,18 10,14.5 5,18 6.5,12 2,8 8,7.5"/></svg>`;
            confettiContainer.appendChild(s);
            setTimeout(() => s.remove(), 1600);
        }
        welcome.classList.remove('opacity-0');
        setTimeout(() => welcome.classList.add('opacity-0'), 1800);
    });

    // Shortcut Admin (Tidak berubah)
    document.addEventListener('keydown', e => {
        if (e.altKey && e.key.toLowerCase() === 'a') {
            document.querySelector('.admin-login').classList.toggle('hidden');
        }
    });

    // Script Cart Count (Tidak berubah)
    const mainCartCount = document.getElementById('cart-count');
    const mobileCartCount = document.getElementById('mobile-cart-count');
    const mobileHeaderCartCount = document.getElementById('cart-count-mobile-header');
    if (mainCartCount) {
        const observer = new MutationObserver(mutations => {
            for (let mutation of mutations) {
                if (mutation.type === 'childList' || mutation.type === 'attributes') {
                    const count = mainCartCount.textContent.trim();
                    const isHidden = count === '0' || mainCartCount.classList.contains('hidden');
                    if (mobileCartCount) {
                        mobileCartCount.textContent = count;
                        if (isHidden) { mobileCartCount.classList.add('hidden'); } 
                        else { mobileCartCount.classList.remove('hidden'); }
                    }
                    if (mobileHeaderCartCount) {
                        mobileHeaderCartCount.textContent = count;
                        if (isHidden) { mobileHeaderCartCount.classList.add('hidden'); } 
                        else { mobileHeaderCartCount.classList.remove('hidden'); }
                    }
                }
            }
        });
        observer.observe(mainCartCount, { attributes: true, childList: true, subtree: true });
    }

    // Dropdown Profil
    document.addEventListener('DOMContentLoaded', () => {
        const profileDropdown = document.querySelector('.profile-dropdown');
        const profileButton = profileDropdown?.querySelector('.profile-button');
        const dropdownContent = profileDropdown?.querySelector('.profile-dropdown-content');

        if (profileButton && dropdownContent) {
            profileButton.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdownContent.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!profileDropdown.contains(e.target)) {
                    dropdownContent.classList.add('hidden');
                }
            });
        }
    });
</script>

<style>
    /* Animasi Bintang, Logo Glow, Fade-in (Tidak Berubah) */
    @keyframes spin-star {
        0% { transform: rotate(0deg) scale(1);}
        60% { transform: rotate(360deg) scale(1.3);}
        100% { transform: rotate(360deg) scale(1);}
    }
    .star-animate {
        animation: spin-star 0.7s cubic-bezier(.68,-0.55,.27,1.55);
        color: #f59e42 !important;
    }
    .mini-star {
        position: absolute;
        width: 14px;
        height: 14px;
        animation: mini-star-fall 1.5s cubic-bezier(.68,-0.55,.27,1.55);
    }
    @keyframes mini-star-fall {
        0% {opacity: 1; transform: translate(-50%, -50%) scale(0.8);}
        60% {opacity: 1; transform: translate(calc(-50% + var(--fall-x)), calc(-50% + var(--fall-y)*0.6)) scale(1.2);}
        100% {opacity: 0; transform: translate(calc(-50% + var(--fall-x)), calc(-50% + var(--fall-y))) scale(0.5);}
    }

    @keyframes fade-in-right-soft {
        0% { opacity: 0; transform: translateX(30px); filter: brightness(0.7) blur(3px); }
        100% { opacity: 1; transform: translateX(0); filter: brightness(1) blur(0); }
    }
    .fade-in-right {
        animation: fade-in-right-soft 1.3s ease-out both;
    }

    .logo-glow {
        background: linear-gradient(90deg, #22c55e, #3b82f6);
        -webkit-background-clip: text;
        color: transparent;
        text-shadow: 0 0 10px rgba(59, 130, 246, 0.2);
        transition: all 1s ease;
        animation: text-soft-glow 3s ease-in-out infinite alternate;
    }
    @keyframes text-soft-glow {
        0% { text-shadow: 0 0 8px rgba(59, 130, 246, 0.2), 0 0 15px rgba(34, 197, 94, 0.15); }
        100% { text-shadow: 0 0 14px rgba(59, 130, 246, 0.4), 0 0 25px rgba(34, 197, 94, 0.25); }
    }
    .logo-icon {
        transition: all 0.8s ease;
        filter: drop-shadow(0 0 3px rgba(37, 99, 235, 0.3));
    }
    a.logo:hover .logo-icon {
        transform: scale(1.08) rotate(3deg);
        filter: drop-shadow(0 0 10px rgba(37, 99, 235, 0.5));
    }
    a.logo:hover .logo-glow {
        letter-spacing: 0.5px;
        text-shadow: 0 0 16px rgba(59, 130, 246, 0.5), 0 0 25px rgba(34, 197, 94, 0.3);
        transform: scale(1.03);
    }
    /* Selesai Animasi Logo */

    /* ====================================================== */
    /* CSS NAVIGASI UTAMA (BERANDA, PRODUK, KONTAK) */
    /* ====================================================== */
    .main-nav-links {
        display: flex;
        gap: 2rem; /* Jarak antar link diperbesar */
        margin-left: 2.5rem;
    }

    .nav-link {
        position: relative;
        color: #4b5563; /* text-gray-600 */
        font-weight: 500; /* medium */
        font-size: 0.95rem; /* Sedikit disesuaikan */
        padding: 0.5rem 0; /* Padding vertikal */
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .nav-link:hover {
        color: #1e3a8a; /* text-blue-800 */
    }

    /* Garis bawah animasi */
    .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px; /* Garis lebih tebal */
        background-color: #3b82f6; /* bg-blue-500 */
        border-radius: 2px;
        transform: scaleX(0); /* Awalnya tersembunyi */
        transform-origin: center;
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); /* Transisi 'elastic' */
    }

    .nav-link:hover::after,
    .nav-link.active::after {
        transform: scaleX(1); /* Muncul saat hover atau aktif */
    }

    .nav-link.active {
        color: #1e3a8a; /* text-blue-800 */
        font-weight: 600; /* semi-bold */
    }

    /* ====================================================== */
    /* CSS DROPDOWN PROFIL */
    /* ====================================================== */
    .profile-dropdown-content {
        animation: fadeIn 0.3s ease-out;
        transform-origin: top right;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95) translateY(-5px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    
    /* [DIHAPUS] CSS Kustom untuk .btn-edit-profile dan .logout-btn dihapus */
</style>
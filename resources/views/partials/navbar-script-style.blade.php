<!-- Script Interaktif Navbar -->
<script>
    // Auto-hide alert welcome
    window.addEventListener("DOMContentLoaded", function() {
        const welcomeAlert = document.getElementById("welcome-alert");
        if (welcomeAlert) {
            setTimeout(() => {
                welcomeAlert.classList.add("opacity-0", "translate-y-[-100%]");
                setTimeout(() => welcomeAlert.remove(), 500);
            }, 3000);
        }
    });

    // Toggle cart popup
    document.getElementById('open-cart')?.addEventListener('click', function() {
        document.getElementById('cart-popup').classList.remove('hidden');
    });
    document.getElementById('close-cart')?.addEventListener('click', function() {
        document.getElementById('cart-popup').classList.add('hidden');
    });

    // Modal dan menu mobile
    const mobileBtn = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const authModal = document.getElementById("auth-modal");
    const openModal = document.getElementById("open-modal");
    const closeModal = document.getElementById("close-modal");
    const loginTab = document.getElementById("login-tab");
    const registerTab = document.getElementById("register-tab");
    const loginForm = document.getElementById("login-form");
    const registerForm = document.getElementById("register-form");
    const showRegister = document.getElementById("show-register");
    const showLogin = document.getElementById("show-login");

    mobileBtn?.addEventListener("click", () => {
        mobileMenu.classList.toggle("hidden");
    });

    openModal?.addEventListener("click", () => {
        authModal.classList.remove("hidden");
        loginForm?.classList.remove("hidden");
        registerForm?.classList.add("hidden");
        loginTab?.classList.add("border-yellow-500");
        registerTab?.classList.remove("border-yellow-500");
    });

    closeModal?.addEventListener("click", () => authModal.classList.add("hidden"));

    loginTab?.addEventListener("click", () => {
        loginForm?.classList.remove("hidden");
        registerForm?.classList.add("hidden");
        loginTab?.classList.add("border-yellow-500");
        registerTab?.classList.remove("border-yellow-500");
    });

    registerTab?.addEventListener("click", () => {
        loginForm?.classList.add("hidden");
        registerForm?.classList.remove("hidden");
        loginTab?.classList.remove("border-yellow-500");
        registerTab?.classList.add("border-yellow-500");
    });

    showRegister?.addEventListener("click", () => {
        loginTab?.click();
        registerTab?.click();
    });

    showLogin?.addEventListener("click", () => {
        registerTab?.click();
        loginTab?.click();
    });

    // Navbar transparan saat scroll
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('main-navbar');
        if (window.scrollY > 30) {
            navbar.classList.add('bg-opacity-70', 'backdrop-blur', 'shadow-lg');
            navbar.classList.remove('bg-white');
            navbar.style.backgroundColor = 'rgba(255,255,255,0.7)';
        } else {
            navbar.classList.remove('bg-opacity-70', 'backdrop-blur', 'shadow-lg');
            navbar.classList.add('bg-white');
            navbar.style.backgroundColor = '';
        }
    });

    // Efek animasi bintang
    document.getElementById('star-btn')?.addEventListener('click', function() {
        const star = document.getElementById('star-icon');
        const confettiContainer = document.getElementById('star-confetti');
        const welcome = document.getElementById('star-welcome');
        star.classList.add('star-animate');
        setTimeout(() => star.classList.remove('star-animate'), 700);

        confettiContainer.innerHTML = '';
        const totalStars = 22;
        for (let i = 0; i < totalStars; i++) {
            const miniStar = document.createElement('span');
            miniStar.className = 'mini-star';
            miniStar.style.left = '50%';
            miniStar.style.top = '50%';
            const spread = 60 + Math.random() * 60;
            const offsetX = (Math.random() - 0.5) * 80;
            miniStar.style.setProperty('--fall-x', `${offsetX}px`);
            miniStar.style.setProperty('--fall-y', `${spread}px`);
            miniStar.innerHTML = `
                <svg width="16" height="16" viewBox="0 0 20 20" fill="${['#fbbf24','#fde68a','#f59e42','#f472b6','#60a5fa','#a78bfa','#34d399','#f87171'][i%8]}" xmlns="http://www.w3.org/2000/svg">
                    <polygon points="10,2 12,7.5 18,8 13.5,12 15,18 10,14.5 5,18 6.5,12 2,8 8,7.5"/>
                </svg>
            `;
            confettiContainer.appendChild(miniStar);
            setTimeout(() => miniStar.remove(), 1800);
        }

        if (welcome) {
            welcome.classList.remove('opacity-0', 'pointer-events-none');
            welcome.classList.add('opacity-100');
            setTimeout(() => {
                welcome.classList.remove('opacity-100');
                welcome.classList.add('opacity-0', 'pointer-events-none');
            }, 1800);
        }
    });

    // Cart popup animasi
    const cartPopup = document.getElementById('cart-popup');
    document.getElementById('open-cart')?.addEventListener('click', function() {
        cartPopup.classList.remove('hidden');
        setTimeout(() => cartPopup.classList.add('show'), 10);
    });
    document.getElementById('close-cart')?.addEventListener('click', function() {
        cartPopup.classList.remove('show');
        setTimeout(() => cartPopup.classList.add('hidden'), 350);
    });
</script>

<!-- Style Kustom Navbar -->
<style>
    #main-navbar {
        transition: background-color 0.3s, box-shadow 0.3s;
        backdrop-filter: blur(6px);
    }
    .nav-link {
        @apply text-gray-700 hover:text-blue-600 font-medium transition px-2 py-1 flex items-center;
    }
    .nav-link.active {
        color: #2563eb !important;
        font-weight: bold;
        text-decoration: underline;
        text-underline-offset: 6px;
    }
    @keyframes spin-star {
        0% { transform: rotate(0deg) scale(1);}
        60% { transform: rotate(360deg) scale(1.3);}
        100% { transform: rotate(360deg) scale(1);}
    }
    .star-animate {
        animation: spin-star 0.7s cubic-bezier(.68,-0.55,.27,1.55);
        color: #f59e42 !important;
        text-shadow: 0 0 10px #fde68a, 0 0 20px #f59e42;
    }
    .mini-star {
        position: absolute;
        width: 16px;
        height: 16px;
        opacity: 0.95;
        pointer-events: none;
        z-index: 10;
        animation: mini-star-fall 1.6s cubic-bezier(.68,-0.55,.27,1.55);
    }
    @keyframes mini-star-fall {
        0% {
            opacity: 1;
            transform: translate(-50%, -50%) scale(0.7) rotate(0deg);
        }
        60% {
            opacity: 1;
            transform: translate(calc(-50% + var(--fall-x,0px)), calc(-50% + var(--fall-y,0px)*0.6)) scale(1.2) rotate(180deg);
        }
        100% {
            opacity: 0;
            transform: translate(calc(-50% + var(--fall-x,0px)), calc(-50% + var(--fall-y,0px))) scale(0.5) rotate(360deg);
        }
    }
    #star-welcome {
        transition: opacity 0.5s, transform 0.5s;
        opacity: 0;
        pointer-events: none;
    }
    #star-welcome.opacity-100 {
        opacity: 1;
        pointer-events: auto;
        transform: translate(-50%, 0) scale(1.08);
    }
    #cart-popup {
        transition: opacity 0.35s cubic-bezier(.4,0,.2,1), visibility 0.35s, background 0.35s;
    }
    #cart-popup.show {
        opacity: 1 !important;
        pointer-events: auto !important;
    }
    #cart-popup .bg-white {
        transition: transform 0.35s cubic-bezier(.4,0,.2,1);
        transform: scale(1);
    }
    #cart-popup:not(.show) .bg-white {
        transform: scale(0.95);
    }
</style>

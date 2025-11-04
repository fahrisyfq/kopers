
window.cartData = window.cartData || {};

window.increaseQty = function(key) {
    const item = window.cartData[key];
    if (item) {
        if (item.quantity < item.stock) {
            item.quantity++;
            fetch('/cart/ajax-update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ key: key, quantity: item.quantity })
            }).then(() => updateCartBadge());
        } else {
            showCartAlert('Jumlah melebihi stok tersedia!', 'error');
        }
    }
};

// -------------------------------
// Fungsi Kurangi Qty
// -------------------------------
window.decreaseQty = function(key) {
    const item = window.cartData[key];
    if (item && item.quantity > 1) {
        item.quantity--;
        fetch('/cart/ajax-update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ key: key, quantity: item.quantity })
        }).then(() => updateCartBadge());
    }
};

// -------------------------------
// Fungsi Hapus Item
// -------------------------------
window.removeItem = function(key) {
    if (window.cartData[key]) {
        delete window.cartData[key];
        fetch('/cart/ajax-remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ key: key })
        }).then(() => updateCartBadge());
    }
};

// -------------------------------
// Badge Cart di Navbar
// -------------------------------
function updateCartBadge() {
    const badge = document.getElementById('cart-count-badge');
    const cart = window.cartData || {};
    let count = 0;
    Object.values(cart).forEach(item => {
        count += item.quantity;
    });
    if (badge) {
        if (count > 0) {
            badge.textContent = count;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }
}

// -------------------------------
// Modal Pembayaran
// -------------------------------
document.addEventListener('DOMContentLoaded', function() {
    const checkoutBtn = document.getElementById('checkout-btn');
    const paymentModal = document.getElementById("payment-modal");
    const closePayment = document.getElementById("close-payment");
    const paymentProducts = document.getElementById("payment-products");
    const paymentTotal = document.getElementById("payment-total");
    const backToCartBtn = document.getElementById("back-to-cart");
    const confirmPaymentBtn = document.getElementById("confirm-payment");

    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function() {
            openPaymentModal();
        });
    }

    function openPaymentModal() {
        const cart = window.cartData || {};
        let html = '';
        let total = 0;

        // Cek kategori di cart
        let hasBucket = false;
        let hasKeychain = false;
        Object.values(cart).forEach(item => {
            if (item.category && item.category.toLowerCase().includes('bucket')) hasBucket = true;
            if (item.category && item.category.toLowerCase().includes('kunci')) hasKeychain = true;
        });

        // QRIS sesuai kategori
        let qrisHtml = '';
        if (hasBucket && hasKeychain) {
            qrisHtml = `<img src="/images/qris_all.png" alt="QRIS All" class="mx-auto w-40 mb-4">`;
        } else if (hasBucket) {
            qrisHtml = `<img src="/images/qris_bucket.png" alt="QRIS Bucket" class="mx-auto w-40 mb-4">`;
        } else if (hasKeychain) {
            qrisHtml = `<img src="/images/qris_keychain.png" alt="QRIS Keychain" class="mx-auto w-40 mb-4">`;
        } else {
            qrisHtml = `<div class="text-gray-400 text-center mb-4">Tidak ada produk di keranjang.</div>`;
        }

        document.getElementById("qris-area").innerHTML = `
            ${qrisHtml}
            <p class="text-center text-gray-600 text-sm mb-2">Scan QR di atas dengan aplikasi pembayaran favorit Anda.</p>
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-2">
                <i class="fas fa-lock"></i> Pembayaran aman & terenkripsi
            </div>
        `;  

        // Produk & total
        if (!cart || Object.keys(cart).length === 0) {
            html = '<p class="text-gray-500">Keranjang kosong.</p>';
        } else {
            Object.entries(cart).forEach(([id, item]) => {
                const subtotal = item.price * item.quantity;
                total += subtotal;
                html += `
                    <div class="flex justify-between items-center">
                        <span>${item.title} <span class="text-xs text-gray-500">x${item.quantity}</span></span>
                        <span>Rp ${subtotal.toLocaleString('id-ID')}</span>
                    </div>
                `;
            });
        }
        paymentProducts.innerHTML = html;
        paymentTotal.innerText = "Total: Rp " + total.toLocaleString('id-ID');
        paymentModal.classList.remove("hidden");
    }

    // Tutup modal
    backToCartBtn?.addEventListener("click", () => paymentModal.classList.add("hidden"));
    closePayment?.addEventListener("click", () => paymentModal.classList.add("hidden"));

    // Konfirmasi bayar
    confirmPaymentBtn?.addEventListener("click", function() {
        const input = document.querySelector('input[type="file"][name="transfer_proof"]');
        if (!input || !input.files || input.files.length === 0) {
            showCartAlert('Silakan upload minimal 1 bukti transfer sebelum konfirmasi pembayaran.', 'error');
            input?.focus();
            return;
        }
        paymentModal.classList.add("hidden");
        showCartAlert("Berhasil dipesan!");
    });

    // Event Add to Cart
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!window.isLoggedIn) {
                e.preventDefault();
                document.getElementById('auth-modal')?.classList.remove('hidden');
                return false;
            }
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');

            let size = null;
            let sizeId = null;
            const sizeSelect = this.querySelector('select[name="size"]');
            if (sizeSelect) {
                size = sizeSelect.options[sizeSelect.selectedIndex].text;
                sizeId = sizeSelect.value;
            }

            fetch(`/cart/ajax-add/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ size: size, size_id: sizeId })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    showCartAlert(data.message, 'error');
                    return;
                }
                window.cartData = data.cart;
                updateCartBadge();
                showCartAlert('Produk berhasil ditambahkan ke keranjang!');
            })
            .catch(err => {
                console.error(err);
                showCartAlert('Terjadi kesalahan saat menambah ke keranjang.', 'error');
            });
        });
    });

    // Jalankan pertama kali
    updateCartBadge();
});

// -------------------------------
// Notifikasi Alert
// -------------------------------
function showCartAlert(message, type = 'success') {
    document.getElementById('cart-alert')?.remove();
    const alert = document.createElement('div');
    alert.id = 'cart-alert';
    alert.className = `fixed top-6 left-1/2 transform -translate-x-1/2 z-[9999] ${type === 'error' ? 'bg-red-500' : 'bg-green-500'} text-white px-6 py-3 rounded shadow-lg flex items-center gap-2 transition-opacity duration-500 opacity-100 animate-fade-in`;
    alert.style.minWidth = '220px';
    alert.innerHTML = `<i class="fas fa-${type === 'error' ? 'times-circle' : 'check-circle'} text-xl"></i> <span>${message}</span>`;
    document.body.appendChild(alert);
    setTimeout(() => {
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 600);
    }, 2000);
}
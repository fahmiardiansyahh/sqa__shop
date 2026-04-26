$(document).ready(function() {

    // ==========================================
    // FORM VALIDATION - Register
    // ==========================================
    $('#registerForm').on('submit', function(e) {
        let isValid = true;
        $('.is-invalid').removeClass('is-invalid');
        
        const nama = $('#nama_lengkap');
        const email = $('#email');
        const password = $('#password');
        const konfirmasi = $('#konfirmasi_password');
        const telepon = $('#no_telepon');

        if (nama.val().trim().length < 3) {
            nama.addClass('is-invalid');
            nama.next('.invalid-feedback').text('Nama minimal 3 karakter');
            isValid = false;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.val())) {
            email.addClass('is-invalid');
            email.next('.invalid-feedback').text('Format email tidak valid');
            isValid = false;
        }

        if (password.val().length < 6) {
            password.addClass('is-invalid');
            password.next('.invalid-feedback').text('Password minimal 6 karakter');
            isValid = false;
        }

        if (password.val() !== konfirmasi.val()) {
            konfirmasi.addClass('is-invalid');
            konfirmasi.next('.invalid-feedback').text('Konfirmasi password tidak cocok');
            isValid = false;
        }

        if (telepon.val().trim() !== '' && !/^[0-9]{10,15}$/.test(telepon.val())) {
            telepon.addClass('is-invalid');
            telepon.next('.invalid-feedback').text('No telepon harus 10-15 digit angka');
            isValid = false;
        }

        if (!isValid) e.preventDefault();
    });

    // ==========================================
    // FORM VALIDATION - Login
    // ==========================================
    $('#loginForm').on('submit', function(e) {
        let isValid = true;
        $('.is-invalid').removeClass('is-invalid');

        const email = $('#email');
        const password = $('#password');

        if (email.val().trim() === '') {
            email.addClass('is-invalid');
            isValid = false;
        }
        if (password.val().trim() === '') {
            password.addClass('is-invalid');
            isValid = false;
        }

        if (!isValid) e.preventDefault();
    });

    // ==========================================
    // CART MANAGEMENT (localStorage)
    // ==========================================
    window.SQACart = {
        getCart: function() {
            return JSON.parse(localStorage.getItem('sqa_cart') || '[]');
        },
        saveCart: function(cart) {
            localStorage.setItem('sqa_cart', JSON.stringify(cart));
            this.updateBadge();
        },
        addItem: function(id, nama, harga, gambar) {
            let cart = this.getCart();
            let existing = cart.find(item => item.id === id);
            if (existing) {
                existing.jumlah += 1;
            } else {
                cart.push({ id, nama, harga: parseFloat(harga), gambar, jumlah: 1 });
            }
            this.saveCart(cart);
            this.showToast(nama + ' ditambahkan ke keranjang!');
        },
        removeItem: function(id) {
            let cart = this.getCart().filter(item => item.id !== id);
            this.saveCart(cart);
        },
        updateQty: function(id, qty) {
            let cart = this.getCart();
            let item = cart.find(i => i.id === id);
            if (item) {
                item.jumlah = Math.max(1, qty);
                this.saveCart(cart);
            }
        },
        getTotal: function() {
            return this.getCart().reduce((sum, item) => sum + (item.harga * item.jumlah), 0);
        },
        getCount: function() {
            return this.getCart().reduce((sum, item) => sum + item.jumlah, 0);
        },
        clearCart: function() {
            localStorage.removeItem('sqa_cart');
            this.updateBadge();
        },
        updateBadge: function() {
            const count = this.getCount();
            const badge = $('#cartBadge');
            if (count > 0) {
                badge.text(count).show();
            } else {
                badge.hide();
            }
        },
        showToast: function(msg) {
            const toast = $('<div class="alert alert-success alert-custom" style="position:fixed;top:80px;right:20px;z-index:9999;min-width:280px;animation:fadeInUp 0.4s ease">' + msg + '</div>');
            $('body').append(toast);
            setTimeout(() => toast.fadeOut(400, function(){ $(this).remove(); }), 2500);
        },
        formatRupiah: function(angka) {
            return 'Rp ' + angka.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    };

    // Update badge on page load
    SQACart.updateBadge();

    // Add to cart button
    $(document).on('click', '.btn-add-cart', function() {
        const card = $(this).closest('.product-card');
        const id = $(this).data('id');
        const nama = card.find('h5').text();
        const harga = $(this).data('harga');
        const gambar = $(this).data('gambar');
        SQACart.addItem(id, nama, harga, gambar);
    });

    // ==========================================
    // CHECKOUT PAGE RENDERING
    // ==========================================
    if ($('#checkoutItems').length) {
        renderCheckout();
    }

    function renderCheckout() {
        const cart = SQACart.getCart();
        const container = $('#checkoutItems');
        const totalEl = $('#checkoutTotal');
        const hiddenCart = $('#hiddenCartData');
        container.empty();

        if (cart.length === 0) {
            container.html('<p class="text-center" style="color:#8888A8;">Keranjang kosong. <a href="index.php" style="color:#6C5CE7;">Belanja sekarang</a></p>');
            totalEl.text('Rp 0');
            return;
        }

        cart.forEach(function(item) {
            container.append(
                '<div class="order-summary-item" data-id="' + item.id + '">' +
                    '<div>' +
                        '<strong>' + item.nama + '</strong><br>' +
                        '<small style="color:#8888A8;">' + SQACart.formatRupiah(item.harga) + ' x ' + item.jumlah + '</small>' +
                    '</div>' +
                    '<div class="text-right">' +
                        '<strong>' + SQACart.formatRupiah(item.harga * item.jumlah) + '</strong><br>' +
                        '<button class="btn btn-sm btn-outline-danger mt-1 btn-remove-item" data-id="' + item.id + '" style="font-size:0.7rem;padding:2px 8px;border-radius:6px;">Hapus</button>' +
                    '</div>' +
                '</div>'
            );
        });

        totalEl.text(SQACart.formatRupiah(SQACart.getTotal()));
        hiddenCart.val(JSON.stringify(cart));
    }

    $(document).on('click', '.btn-remove-item', function() {
        SQACart.removeItem($(this).data('id'));
        renderCheckout();
    });

    // Checkout form validation
    $('#checkoutForm').on('submit', function(e) {
        let isValid = true;
        $('.is-invalid').removeClass('is-invalid');

        if ($('#alamat_pengiriman').val().trim().length < 10) {
            $('#alamat_pengiriman').addClass('is-invalid');
            isValid = false;
        }
        if (!$('#metode_pembayaran').val()) {
            $('#metode_pembayaran').addClass('is-invalid');
            isValid = false;
        }
        if (SQACart.getCart().length === 0) {
            SQACart.showToast('Keranjang belanja kosong!');
            isValid = false;
        }

        if (!isValid) e.preventDefault();
    });

    // Real-time validation feedback
    $(document).on('input', '.form-control-custom', function() {
        $(this).removeClass('is-invalid');
    });

    // Animate elements on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.product-card').forEach(el => observer.observe(el));
});

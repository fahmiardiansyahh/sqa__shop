<?php
require_once 'config.php';
if (!isLoggedIn()) redirect('login.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - SQA Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="fas fa-store"></i> SQA Shop</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navMenu">
            <i class="fas fa-bars" style="color:#B8B8D0;"></i>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home"></i> Beranda</a></li>
                <li class="nav-item"><a class="nav-link active" href="checkout.php"><i class="fas fa-shopping-cart"></i> Keranjang</a></li>
                <li class="nav-item"><a class="nav-link" href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout (<?= htmlspecialchars($_SESSION['user_nama']); ?>)
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container" style="padding-top:100px;">
    <div class="page-title"><h2><i class="fas fa-shopping-cart"></i> Checkout</h2></div>

    <?php $flash = getFlash(); if ($flash): ?>
        <div class="alert alert-<?= $flash['type']; ?> alert-custom alert-dismissible fade show">
            <?= $flash['message']; ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    <?php endif; ?>

    <form id="checkoutForm" action="process_checkout.php" method="POST">
        <div class="row">
            <div class="col-lg-7">
                <div class="checkout-section">
                    <h4><i class="fas fa-truck"></i> Informasi Pengiriman</h4>
                    <div class="form-group">
                        <label for="nama_penerima"><i class="fas fa-user"></i> Nama Penerima</label>
                        <input type="text" class="form-control form-control-custom" id="nama_penerima"
                            value="<?= htmlspecialchars($_SESSION['user_nama']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="alamat_pengiriman"><i class="fas fa-map-marker-alt"></i> Alamat Pengiriman</label>
                        <textarea class="form-control form-control-custom" id="alamat_pengiriman" name="alamat_pengiriman" 
                            rows="3" placeholder="Masukkan alamat lengkap pengiriman" required></textarea>
                        <div class="invalid-feedback">Alamat minimal 10 karakter</div>
                    </div>
                    <div class="form-group">
                        <label for="metode_pembayaran"><i class="fas fa-credit-card"></i> Metode Pembayaran</label>
                        <select class="form-control form-control-custom" id="metode_pembayaran" name="metode_pembayaran" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="transfer_bank">Transfer Bank</option>
                            <option value="cod">Cash on Delivery (COD)</option>
                            <option value="e_wallet">E-Wallet</option>
                        </select>
                        <div class="invalid-feedback">Pilih metode pembayaran</div>
                    </div>
                    <div class="form-group">
                        <label for="catatan"><i class="fas fa-sticky-note"></i> Catatan (Opsional)</label>
                        <textarea class="form-control form-control-custom" id="catatan" name="catatan" rows="2" 
                            placeholder="Catatan untuk penjual..."></textarea>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="checkout-section">
                    <h4><i class="fas fa-receipt"></i> Ringkasan Pesanan</h4>
                    <div id="checkoutItems"></div>
                    <div class="order-total">
                        <span>Total:</span>
                        <span id="checkoutTotal">Rp 0</span>
                    </div>
                    <input type="hidden" name="cart_data" id="hiddenCartData">
                    <button type="submit" class="btn btn-primary-custom btn-block" id="btnCheckout">
                        <i class="fas fa-check-circle"></i> Buat Pesanan
                    </button>
                    <a href="index.php" class="btn btn-secondary-custom btn-block mt-2">
                        <i class="fas fa-arrow-left"></i> Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<footer class="footer">
    <div class="container">
        <p>&copy; 2026 SQA Shop - Aplikasi Pembelajaran SQA | BSI</p>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/app.js"></script>
</body>
</html>

<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SQA Shop - Aplikasi E-Commerce untuk Pembelajaran Testing">
    <title>SQA Shop - Beranda</title>
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
                <li class="nav-item"><a class="nav-link active" href="index.php"><i class="fas fa-home"></i> Beranda</a></li>
                <?php if (isLoggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="checkout.php">
                            <i class="fas fa-shopping-cart"></i> Keranjang
                            <span class="cart-badge" id="cartBadge" style="display:none;">0</span>
                        </a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout (<?= htmlspecialchars($_SESSION['user_nama']); ?>)
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php"><i class="fas fa-user-plus"></i> Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="container">
        <h1>Selamat Datang di SQA Shop</h1>
        <p>Aplikasi e-commerce sederhana untuk pembelajaran Software Quality Assurance & Testing</p>
    </div>
</section>

<!-- Flash Message -->
<div class="container">
    <?php $flash = getFlash(); if ($flash): ?>
        <div class="alert alert-<?= $flash['type']; ?> alert-custom alert-dismissible fade show" role="alert">
            <?= $flash['message']; ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    <?php endif; ?>
</div>

<!-- Products -->
<section class="py-4">
    <div class="container">
        <div class="row">
            <?php
            $result = $conn->query("SELECT * FROM products ORDER BY id ASC");
            if ($result && $result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
            ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="product-card animate-in">
                    <div class="product-img-wrapper">
                        <img src="https://picsum.photos/seed/<?= $row['id']; ?>/400/250" alt="<?= htmlspecialchars($row['nama_produk']); ?>">
                        <span class="product-badge"><?= htmlspecialchars($row['kategori']); ?></span>
                    </div>
                    <div class="product-body">
                        <h5><?= htmlspecialchars($row['nama_produk']); ?></h5>
                        <p class="desc"><?= htmlspecialchars(substr($row['deskripsi'], 0, 80)); ?>...</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="price"><?= formatRupiah($row['harga']); ?></span>
                            <span class="stock-info">Stok: <span class="badge badge-pill" style="background:rgba(0,206,201,0.2);color:#00CEC9;"><?= $row['stok']; ?></span></span>
                        </div>
                        <?php if (isLoggedIn()): ?>
                            <button class="btn btn-primary-custom btn-block btn-add-cart"
                                data-id="<?= $row['id']; ?>"
                                data-harga="<?= $row['harga']; ?>"
                                data-gambar="<?= $row['gambar']; ?>"
                                id="btnAddCart<?= $row['id']; ?>">
                                <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                            </button>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-secondary-custom btn-block">
                                <i class="fas fa-sign-in-alt"></i> Login untuk Membeli
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; else: ?>
                <div class="col-12 text-center py-5">
                    <p style="color:#8888A8;">Belum ada produk tersedia.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p>&copy; 2026 SQA Shop - Aplikasi Pembelajaran Software Quality Assurance | BSI</p>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/app.js"></script>
</body>
</html>

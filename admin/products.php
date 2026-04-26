<?php
require_once '../config.php';
requireAdmin();

$products = $conn->query("SELECT * FROM products ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - SQA Shop Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="dashboard.php"><i class="fas fa-shield-alt"></i> SQA Admin</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#adminNav">
            <i class="fas fa-bars" style="color:#B8B8D0;"></i>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="products.php"><i class="fas fa-boxes"></i> Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="orders.php"><i class="fas fa-clipboard-list"></i> Pesanan</a></li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="../index.php"><i class="fas fa-store"></i> Lihat Toko</a></li>
                <li class="nav-item"><a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout (<?= htmlspecialchars($_SESSION['user_nama']); ?>)</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="admin-content">
    <div class="container-fluid px-4">
        <div class="admin-page-header d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h2><i class="fas fa-boxes"></i> Kelola Produk</h2>
                <p class="admin-subtitle">Tambah, edit, dan hapus produk toko Anda</p>
            </div>
            <a href="product_form.php" class="btn btn-primary-custom" id="btnTambahProduk">
                <i class="fas fa-plus-circle"></i> Tambah Produk Baru
            </a>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type']; ?> alert-custom alert-dismissible fade show">
                <?= $flash['message']; ?>
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        <?php endif; ?>

        <div class="admin-card">
            <div class="table-responsive">
                <table class="table admin-table" id="productsTable">
                    <thead>
                        <tr><th>ID</th><th>Gambar</th><th>Nama Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php if ($products->num_rows > 0): ?>
                            <?php while ($row = $products->fetch_assoc()): ?>
                                <tr>
                                    <td><strong>#<?= $row['id']; ?></strong></td>
                                    <td><img src="https://picsum.photos/seed/<?= $row['id']; ?>/60/60" alt="" class="admin-product-thumb"></td>
                                    <td>
                                        <strong><?= htmlspecialchars($row['nama_produk']); ?></strong>
                                        <small class="d-block" style="color:#8888A8;"><?= htmlspecialchars(substr($row['deskripsi'], 0, 50)); ?>...</small>
                                    </td>
                                    <td><span class="badge admin-badge" style="background:rgba(108,92,231,0.2);color:#6C5CE7;"><?= htmlspecialchars($row['kategori']); ?></span></td>
                                    <td><?= formatRupiah($row['harga']); ?></td>
                                    <td>
                                        <?php if ($row['stok'] < 10): ?>
                                            <span class="badge badge-danger admin-badge"><?= $row['stok']; ?> <i class="fas fa-exclamation-circle"></i></span>
                                        <?php elseif ($row['stok'] < 25): ?>
                                            <span class="badge badge-warning admin-badge"><?= $row['stok']; ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-success admin-badge"><?= $row['stok']; ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="admin-actions">
                                            <a href="product_form.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-info admin-action-btn" title="Edit"><i class="fas fa-edit"></i></a>
                                            <a href="process_product.php?delete=<?= $row['id']; ?>" class="btn btn-sm btn-outline-danger admin-action-btn" title="Hapus" onclick="return confirm('Yakin ingin menghapus produk ini?');"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center py-4" style="color:#8888A8;">Belum ada produk.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

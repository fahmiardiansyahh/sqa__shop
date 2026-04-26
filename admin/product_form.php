<?php
require_once '../config.php';
requireAdmin();

$isEdit = false;
$product = ['nama_produk'=>'','deskripsi'=>'','harga'=>'','stok'=>'','gambar'=>'default.jpg','kategori'=>''];

if (isset($_GET['id'])) {
    $isEdit = true;
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        setFlash('danger', 'Produk tidak ditemukan!');
        redirect('products.php');
    }
    $product = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'Edit' : 'Tambah'; ?> Produk - SQA Shop Admin</title>
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
        <div class="admin-page-header">
            <h2><i class="fas fa-<?= $isEdit ? 'edit' : 'plus-circle'; ?>"></i> <?= $isEdit ? 'Edit Produk' : 'Tambah Produk Baru'; ?></h2>
            <p class="admin-subtitle"><?= $isEdit ? 'Perbarui informasi produk' : 'Isi form untuk menambahkan produk baru'; ?></p>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type']; ?> alert-custom alert-dismissible fade show">
                <?= $flash['message']; ?>
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <div class="admin-card">
                    <form action="process_product.php" method="POST" id="productForm">
                        <?php if ($isEdit): ?>
                            <input type="hidden" name="id" value="<?= $product['id']; ?>">
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="nama_produk"><i class="fas fa-tag"></i> Nama Produk</label>
                            <input type="text" class="form-control form-control-custom" id="nama_produk" name="nama_produk" 
                                value="<?= htmlspecialchars($product['nama_produk']); ?>" placeholder="Masukkan nama produk" required>
                        </div>

                        <div class="form-group">
                            <label for="deskripsi"><i class="fas fa-align-left"></i> Deskripsi</label>
                            <textarea class="form-control form-control-custom" id="deskripsi" name="deskripsi" rows="4" 
                                placeholder="Deskripsi lengkap produk"><?= htmlspecialchars($product['deskripsi']); ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="harga"><i class="fas fa-money-bill"></i> Harga (Rp)</label>
                                    <input type="number" class="form-control form-control-custom" id="harga" name="harga" 
                                        value="<?= $product['harga']; ?>" placeholder="0" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="stok"><i class="fas fa-cubes"></i> Stok</label>
                                    <input type="number" class="form-control form-control-custom" id="stok" name="stok" 
                                        value="<?= $product['stok']; ?>" placeholder="0" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="kategori"><i class="fas fa-folder"></i> Kategori</label>
                                    <select class="form-control form-control-custom" id="kategori" name="kategori" required>
                                        <option value="">-- Pilih --</option>
                                        <?php foreach(['Elektronik','Aksesoris','Pakaian','Makanan','Lainnya'] as $kat): ?>
                                            <option value="<?= $kat; ?>" <?= ($product['kategori'] === $kat) ? 'selected' : ''; ?>><?= $kat; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="gambar"><i class="fas fa-image"></i> Nama File Gambar</label>
                            <input type="text" class="form-control form-control-custom" id="gambar" name="gambar" 
                                value="<?= htmlspecialchars($product['gambar']); ?>" placeholder="contoh: laptop.jpg">
                            <small style="color:#8888A8;">Gambar menggunakan picsum.photos berdasarkan ID produk</small>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="products.php" class="btn btn-secondary-custom"><i class="fas fa-arrow-left"></i> Kembali</a>
                            <button type="submit" class="btn btn-primary-custom" id="btnSimpanProduk">
                                <i class="fas fa-save"></i> <?= $isEdit ? 'Update Produk' : 'Simpan Produk'; ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="admin-card">
                    <h5 style="color:#fff;font-weight:700;margin-bottom:16px;"><i class="fas fa-eye"></i> Preview</h5>
                    <div class="product-card" style="margin:0;">
                        <div class="product-img-wrapper">
                            <img src="https://picsum.photos/seed/<?= $isEdit ? $product['id'] : 'new'; ?>/400/250" alt="Preview">
                        </div>
                        <div class="product-body">
                            <h5 id="previewNama"><?= $isEdit ? htmlspecialchars($product['nama_produk']) : 'Nama Produk'; ?></h5>
                            <p class="desc" id="previewDesc"><?= $isEdit ? htmlspecialchars(substr($product['deskripsi'], 0, 80)) : 'Deskripsi produk...'; ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price" id="previewHarga"><?= $isEdit ? formatRupiah($product['harga']) : 'Rp 0'; ?></span>
                                <span class="stock-info">Stok: <span class="badge badge-pill" style="background:rgba(0,206,201,0.2);color:#00CEC9;" id="previewStok"><?= $isEdit ? $product['stok'] : '0'; ?></span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    $('#nama_produk').on('input', function() { $('#previewNama').text($(this).val() || 'Nama Produk'); });
    $('#deskripsi').on('input', function() { $('#previewDesc').text(($(this).val() || 'Deskripsi produk...').substring(0, 80)); });
    $('#stok').on('input', function() { $('#previewStok').text($(this).val() || '0'); });
    $('#harga').on('input', function() {
        var val = parseInt($(this).val()) || 0;
        $('#previewHarga').text('Rp ' + val.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
    });
});
</script>
</body>
</html>

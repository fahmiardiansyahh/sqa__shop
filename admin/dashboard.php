<?php
require_once '../config.php';
requireAdmin();

// Statistik
$totalProduk = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
$totalPesanan = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];
$totalPendapatan = $conn->query("SELECT COALESCE(SUM(total_harga), 0) as total FROM orders WHERE status != 'dibatalkan'")->fetch_assoc()['total'];
$stokRendah = $conn->query("SELECT COUNT(*) as total FROM products WHERE stok < 10")->fetch_assoc()['total'];

// Pesanan terbaru
$recentOrders = $conn->query("SELECT o.*, u.nama_lengkap FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5");

// Produk stok rendah
$lowStockProducts = $conn->query("SELECT * FROM products WHERE stok < 10 ORDER BY stok ASC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SQA Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<!-- Admin Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="dashboard.php"><i class="fas fa-shield-alt"></i> SQA Admin</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#adminNav">
            <i class="fas fa-bars" style="color:#B8B8D0;"></i>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link active" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="products.php"><i class="fas fa-boxes"></i> Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="orders.php"><i class="fas fa-clipboard-list"></i> Pesanan</a></li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="../index.php"><i class="fas fa-store"></i> Lihat Toko</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout (<?= htmlspecialchars($_SESSION['user_nama']); ?>)</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="admin-content">
    <div class="container-fluid px-4">
        <!-- Page Header -->
        <div class="admin-page-header">
            <h2><i class="fas fa-tachometer-alt"></i> Dashboard</h2>
            <p class="admin-subtitle">Selamat datang kembali, <?= htmlspecialchars($_SESSION['user_nama']); ?>!</p>
        </div>

        <!-- Flash Message -->
        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type']; ?> alert-custom alert-dismissible fade show">
                <?= $flash['message']; ?>
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        <?php endif; ?>

        <!-- Stat Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="admin-stat-card">
                    <div class="stat-icon" style="background:linear-gradient(135deg,#6C5CE7,#a29bfe);">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-label">Total Produk</span>
                        <span class="stat-value"><?= $totalProduk; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="admin-stat-card">
                    <div class="stat-icon" style="background:linear-gradient(135deg,#00CEC9,#81ecec);">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-label">Total Pesanan</span>
                        <span class="stat-value"><?= $totalPesanan; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="admin-stat-card">
                    <div class="stat-icon" style="background:linear-gradient(135deg,#00b894,#55efc4);">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-label">Total Pendapatan</span>
                        <span class="stat-value"><?= formatRupiah($totalPendapatan); ?></span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="admin-stat-card">
                    <div class="stat-icon" style="background:linear-gradient(135deg,#e17055,#fab1a0);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-label">Stok Rendah</span>
                        <span class="stat-value"><?= $stokRendah; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Orders -->
            <div class="col-lg-8 mb-4">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h5><i class="fas fa-clock"></i> Pesanan Terbaru</h5>
                        <a href="orders.php" class="btn btn-sm btn-outline-primary-custom">Lihat Semua</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table admin-table">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($recentOrders->num_rows > 0): ?>
                                    <?php while ($order = $recentOrders->fetch_assoc()): ?>
                                        <?php
                                        $statusColors = ['pending'=>'warning','diproses'=>'info','dikirim'=>'primary','selesai'=>'success','dibatalkan'=>'danger'];
                                        $color = $statusColors[$order['status']] ?? 'secondary';
                                        ?>
                                        <tr>
                                            <td><strong>#<?= $order['id']; ?></strong></td>
                                            <td><?= htmlspecialchars($order['nama_lengkap']); ?></td>
                                            <td><?= formatRupiah($order['total_harga']); ?></td>
                                            <td><span class="badge badge-<?= $color; ?> admin-badge"><?= ucfirst($order['status']); ?></span></td>
                                            <td><?= date('d M Y, H:i', strtotime($order['created_at'])); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="text-center" style="color:#8888A8;">Belum ada pesanan</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Low Stock Alert -->
            <div class="col-lg-4 mb-4">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h5><i class="fas fa-exclamation-circle" style="color:#e17055;"></i> Stok Rendah</h5>
                    </div>
                    <?php if ($lowStockProducts->num_rows > 0): ?>
                        <?php while ($product = $lowStockProducts->fetch_assoc()): ?>
                            <div class="low-stock-item">
                                <div>
                                    <strong><?= htmlspecialchars($product['nama_produk']); ?></strong>
                                    <small style="color:#8888A8;display:block;"><?= htmlspecialchars($product['kategori']); ?></small>
                                </div>
                                <span class="badge badge-danger admin-badge"><?= $product['stok']; ?> unit</span>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-center py-3" style="color:#8888A8;"><i class="fas fa-check-circle" style="color:#00b894;"></i> Semua stok aman!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

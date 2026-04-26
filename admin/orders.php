<?php
require_once '../config.php';
requireAdmin();

$orders = $conn->query("SELECT o.*, u.nama_lengkap, u.email FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - SQA Shop Admin</title>
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
                <li class="nav-item"><a class="nav-link" href="products.php"><i class="fas fa-boxes"></i> Produk</a></li>
                <li class="nav-item"><a class="nav-link active" href="orders.php"><i class="fas fa-clipboard-list"></i> Pesanan</a></li>
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
            <h2><i class="fas fa-clipboard-list"></i> Kelola Pesanan</h2>
            <p class="admin-subtitle">Lihat dan kelola status semua pesanan</p>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type']; ?> alert-custom alert-dismissible fade show">
                <?= $flash['message']; ?>
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        <?php endif; ?>

        <div class="admin-card">
            <div class="table-responsive">
                <table class="table admin-table">
                    <thead>
                        <tr><th>#ID</th><th>Customer</th><th>Total</th><th>Metode</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php if ($orders->num_rows > 0): ?>
                            <?php while ($order = $orders->fetch_assoc()): ?>
                                <?php
                                $statusColors = ['pending'=>'warning','diproses'=>'info','dikirim'=>'primary','selesai'=>'success','dibatalkan'=>'danger'];
                                $color = $statusColors[$order['status']] ?? 'secondary';
                                // Get order items
                                $items_stmt = $conn->prepare("SELECT oi.*, p.nama_produk FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
                                $items_stmt->bind_param("i", $order['id']);
                                $items_stmt->execute();
                                $items = $items_stmt->get_result();
                                ?>
                                <tr>
                                    <td><strong>#<?= $order['id']; ?></strong></td>
                                    <td>
                                        <strong><?= htmlspecialchars($order['nama_lengkap']); ?></strong>
                                        <small class="d-block" style="color:#8888A8;"><?= htmlspecialchars($order['email']); ?></small>
                                    </td>
                                    <td><strong><?= formatRupiah($order['total_harga']); ?></strong></td>
                                    <td><span style="color:#B8B8D0;"><?= str_replace('_', ' ', ucfirst($order['metode_pembayaran'])); ?></span></td>
                                    <td><span class="badge badge-<?= $color; ?> admin-badge"><?= ucfirst($order['status']); ?></span></td>
                                    <td><span style="color:#B8B8D0;"><?= date('d M Y, H:i', strtotime($order['created_at'])); ?></span></td>
                                    <td>
                                        <form action="process_order.php" method="POST" class="d-flex align-items-center" style="gap:6px;">
                                            <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                                            <select name="status" class="form-control form-control-custom" style="padding:6px 10px;font-size:0.8rem;width:auto;">
                                                <?php foreach(['pending','diproses','dikirim','selesai','dibatalkan'] as $s): ?>
                                                    <option value="<?= $s; ?>" <?= ($order['status'] === $s) ? 'selected' : ''; ?>><?= ucfirst($s); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-primary-custom" style="padding:6px 12px;font-size:0.8rem;">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <!-- Order detail row -->
                                <tr class="order-detail-row">
                                    <td colspan="7" style="padding:8px 20px;border-top:none;">
                                        <small style="color:#8888A8;">
                                            <i class="fas fa-box"></i> Items:
                                            <?php while ($item = $items->fetch_assoc()): ?>
                                                <span class="mr-3"><?= htmlspecialchars($item['nama_produk']); ?> (x<?= $item['jumlah']; ?>)</span>
                                            <?php endwhile; ?>
                                            | <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars(substr($order['alamat_pengiriman'], 0, 50)); ?>
                                        </small>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center py-4" style="color:#8888A8;">Belum ada pesanan.</td></tr>
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

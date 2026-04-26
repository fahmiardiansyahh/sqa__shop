<?php
require_once 'config.php';
if (!isLoggedIn()) redirect('login.php');

$user_id = $_SESSION['user_id'];
$orders = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$orders->bind_param("i", $user_id);
$orders->execute();
$result = $orders->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - SQA Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="fas fa-store"></i> SQA Shop</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navMenu">
            <i class="fas fa-bars" style="color:#B8B8D0;"></i>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home"></i> Beranda</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="checkout.php"><i class="fas fa-shopping-cart"></i> Keranjang
                        <span class="cart-badge" id="cartBadge" style="display:none;">0</span>
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link active" href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout (<?= htmlspecialchars($_SESSION['user_nama']); ?>)</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container" style="padding-top:100px;">
    <div class="page-title"><h2><i class="fas fa-history"></i> Riwayat Pesanan</h2></div>

    <?php $flash = getFlash(); if ($flash): ?>
        <div class="alert alert-<?= $flash['type']; ?> alert-custom alert-dismissible fade show">
            <?= $flash['message']; ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($order = $result->fetch_assoc()): ?>
            <div class="checkout-section animate-in">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 style="color:#fff;font-weight:700;margin:0;">Pesanan #<?= $order['id']; ?></h5>
                        <small style="color:#8888A8;"><?= date('d M Y, H:i', strtotime($order['created_at'])); ?></small>
                    </div>
                    <div>
                        <?php
                        $statusColors = ['pending'=>'warning','diproses'=>'info','dikirim'=>'primary','selesai'=>'success','dibatalkan'=>'danger'];
                        $color = $statusColors[$order['status']] ?? 'secondary';
                        ?>
                        <span class="badge badge-<?= $color; ?>" style="padding:6px 14px;border-radius:8px;font-size:0.8rem;">
                            <?= ucfirst($order['status']); ?>
                        </span>
                    </div>
                </div>
                <?php
                $items_stmt = $conn->prepare("SELECT oi.*, p.nama_produk FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
                $items_stmt->bind_param("i", $order['id']);
                $items_stmt->execute();
                $items_result = $items_stmt->get_result();
                while ($item = $items_result->fetch_assoc()):
                ?>
                <div class="order-summary-item">
                    <span><?= htmlspecialchars($item['nama_produk']); ?> x<?= $item['jumlah']; ?></span>
                    <span><?= formatRupiah($item['subtotal']); ?></span>
                </div>
                <?php endwhile; ?>
                <div class="order-total">
                    <span>Total:</span>
                    <span><?= formatRupiah($order['total_harga']); ?></span>
                </div>
                <div style="color:#8888A8;font-size:0.85rem;">
                    <i class="fas fa-credit-card"></i> <?= str_replace('_', ' ', ucfirst($order['metode_pembayaran'])); ?> |
                    <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars(substr($order['alamat_pengiriman'], 0, 60)); ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="checkout-section text-center py-5">
            <i class="fas fa-box-open" style="font-size:3rem;color:#8888A8;margin-bottom:16px;"></i>
            <p style="color:#8888A8;">Belum ada pesanan. <a href="index.php" style="color:#6C5CE7;">Mulai belanja</a></p>
        </div>
    <?php endif; ?>
</div>

<footer class="footer">
    <div class="container"><p>&copy; 2026 SQA Shop - Aplikasi Pembelajaran SQA | BSI</p></div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/app.js"></script>
<script>
// Clear cart after successful checkout (if redirected from process_checkout)
<?php if (getFlash()): ?>
localStorage.removeItem('sqa_cart');
<?php endif; ?>
</script>
</body>
</html>

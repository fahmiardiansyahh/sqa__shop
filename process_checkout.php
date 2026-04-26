<?php
require_once 'config.php';

if (!isLoggedIn()) redirect('login.php');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('checkout.php');

$user_id = $_SESSION['user_id'];
$alamat = sanitize($conn, $_POST['alamat_pengiriman']);
$metode = sanitize($conn, $_POST['metode_pembayaran']);
$catatan = sanitize($conn, $_POST['catatan'] ?? '');
$cart_data = json_decode($_POST['cart_data'], true);

// Validasi
if (strlen($alamat) < 10) {
    setFlash('danger', 'Alamat pengiriman minimal 10 karakter!');
    redirect('checkout.php');
}
if (!in_array($metode, ['transfer_bank', 'cod', 'e_wallet'])) {
    setFlash('danger', 'Metode pembayaran tidak valid!');
    redirect('checkout.php');
}
if (empty($cart_data)) {
    setFlash('danger', 'Keranjang belanja kosong!');
    redirect('checkout.php');
}

// Hitung total & validasi produk
$total = 0;
$items = [];
foreach ($cart_data as $item) {
    $pid = intval($item['id']);
    $jumlah = intval($item['jumlah']);
    $stmt = $conn->prepare("SELECT id, harga, stok FROM products WHERE id = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if (!$product) {
        setFlash('danger', 'Produk tidak ditemukan!');
        redirect('checkout.php');
    }
    if ($jumlah > $product['stok']) {
        setFlash('danger', 'Stok tidak mencukupi!');
        redirect('checkout.php');
    }

    $subtotal = $product['harga'] * $jumlah;
    $total += $subtotal;
    $items[] = ['product_id' => $pid, 'jumlah' => $jumlah, 'harga' => $product['harga'], 'subtotal' => $subtotal];
}

// Transaction
$conn->begin_transaction();
try {
    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_harga, metode_pembayaran, alamat_pengiriman, catatan) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("idsss", $user_id, $total, $metode, $alamat, $catatan);
    $stmt->execute();
    $order_id = $conn->insert_id;

    // Insert order items & update stock
    foreach ($items as $item) {
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, jumlah, harga_satuan, subtotal) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiidd", $order_id, $item['product_id'], $item['jumlah'], $item['harga'], $item['subtotal']);
        $stmt->execute();

        $stmt = $conn->prepare("UPDATE products SET stok = stok - ? WHERE id = ?");
        $stmt->bind_param("ii", $item['jumlah'], $item['product_id']);
        $stmt->execute();
    }

    $conn->commit();
    setFlash('success', 'Pesanan #' . $order_id . ' berhasil dibuat! Total: Rp ' . number_format($total, 0, ',', '.'));
    redirect('riwayat.php');
} catch (Exception $e) {
    $conn->rollback();
    setFlash('danger', 'Terjadi kesalahan saat membuat pesanan. Coba lagi.');
    redirect('checkout.php');
}
?>

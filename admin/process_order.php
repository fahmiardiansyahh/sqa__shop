<?php
require_once '../config.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('orders.php');

$order_id = intval($_POST['order_id']);
$status = sanitize($conn, $_POST['status']);

$validStatuses = ['pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan'];
if (!in_array($status, $validStatuses)) {
    setFlash('danger', 'Status tidak valid!');
    redirect('orders.php');
}

$stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $order_id);

if ($stmt->execute()) {
    setFlash('success', 'Status pesanan #' . $order_id . ' berhasil diubah menjadi "' . ucfirst($status) . '"!');
} else {
    setFlash('danger', 'Gagal mengubah status pesanan.');
}

redirect('orders.php');
?>

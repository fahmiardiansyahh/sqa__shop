<?php
require_once '../config.php';
requireAdmin();

// DELETE Product
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        setFlash('success', 'Produk berhasil dihapus!');
    } else {
        setFlash('danger', 'Gagal menghapus produk. Mungkin produk terkait dengan pesanan.');
    }
    redirect('products.php');
}

// CREATE or UPDATE Product
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('products.php');

$nama = sanitize($conn, $_POST['nama_produk']);
$deskripsi = sanitize($conn, $_POST['deskripsi']);
$harga = floatval($_POST['harga']);
$stok = intval($_POST['stok']);
$gambar = sanitize($conn, $_POST['gambar'] ?: 'default.jpg');
$kategori = sanitize($conn, $_POST['kategori']);

// Validasi
if (strlen($nama) < 3) {
    setFlash('danger', 'Nama produk minimal 3 karakter!');
    redirect(isset($_POST['id']) ? 'product_form.php?id=' . $_POST['id'] : 'product_form.php');
}
if ($harga <= 0) {
    setFlash('danger', 'Harga harus lebih dari 0!');
    redirect(isset($_POST['id']) ? 'product_form.php?id=' . $_POST['id'] : 'product_form.php');
}
if ($stok < 0) {
    setFlash('danger', 'Stok tidak boleh negatif!');
    redirect(isset($_POST['id']) ? 'product_form.php?id=' . $_POST['id'] : 'product_form.php');
}

if (isset($_POST['id']) && !empty($_POST['id'])) {
    // UPDATE
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("UPDATE products SET nama_produk=?, deskripsi=?, harga=?, stok=?, gambar=?, kategori=? WHERE id=?");
    $stmt->bind_param("ssdissi", $nama, $deskripsi, $harga, $stok, $gambar, $kategori, $id);
    if ($stmt->execute()) {
        setFlash('success', 'Produk berhasil diperbarui!');
    } else {
        setFlash('danger', 'Gagal memperbarui produk.');
    }
} else {
    // CREATE
    $stmt = $conn->prepare("INSERT INTO products (nama_produk, deskripsi, harga, stok, gambar, kategori) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdiss", $nama, $deskripsi, $harga, $stok, $gambar, $kategori);
    if ($stmt->execute()) {
        setFlash('success', 'Produk baru berhasil ditambahkan!');
    } else {
        setFlash('danger', 'Gagal menambahkan produk.');
    }
}

redirect('products.php');
?>

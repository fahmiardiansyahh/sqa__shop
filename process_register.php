<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('register.php');

$nama = sanitize($conn, $_POST['nama_lengkap']);
$email = sanitize($conn, $_POST['email']);
$password = $_POST['password'];
$konfirmasi = $_POST['konfirmasi_password'];
$telepon = sanitize($conn, $_POST['no_telepon']);
$alamat = sanitize($conn, $_POST['alamat']);

// Validasi server-side
if (strlen($nama) < 3) {
    setFlash('danger', 'Nama minimal 3 karakter!');
    redirect('register.php');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setFlash('danger', 'Format email tidak valid!');
    redirect('register.php');
}
if (strlen($password) < 6) {
    setFlash('danger', 'Password minimal 6 karakter!');
    redirect('register.php');
}
if ($password !== $konfirmasi) {
    setFlash('danger', 'Konfirmasi password tidak cocok!');
    redirect('register.php');
}

// Cek email sudah terdaftar
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    setFlash('danger', 'Email sudah terdaftar! Gunakan email lain.');
    redirect('register.php');
}

// Insert user
$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (nama_lengkap, email, password, no_telepon, alamat) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $nama, $email, $hashed, $telepon, $alamat);

if ($stmt->execute()) {
    setFlash('success', 'Registrasi berhasil! Silakan login.');
    redirect('login.php');
} else {
    setFlash('danger', 'Registrasi gagal. Coba lagi.');
    redirect('register.php');
}
?>

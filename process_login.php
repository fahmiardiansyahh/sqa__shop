<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('login.php');

$email = sanitize($conn, $_POST['email']);
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    setFlash('danger', 'Email dan password wajib diisi!');
    redirect('login.php');
}

$stmt = $conn->prepare("SELECT id, nama_lengkap, email, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nama'] = $user['nama_lengkap'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        
        if ($user['role'] === 'admin') {
            setFlash('success', 'Selamat datang, Admin ' . htmlspecialchars($user['nama_lengkap']) . '!');
            redirect('admin/dashboard.php');
        } else {
            setFlash('success', 'Selamat datang, ' . htmlspecialchars($user['nama_lengkap']) . '!');
            redirect('index.php');
        }
    }
}

setFlash('danger', 'Email atau password salah!');
redirect('login.php');
?>

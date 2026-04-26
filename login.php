<?php
require_once 'config.php';
if (isLoggedIn()) redirect('index.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SQA Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="text-center mb-4">
            <a href="index.php" style="text-decoration:none;">
                <h2><i class="fas fa-store"></i> SQA Shop</h2>
            </a>
            <p class="subtitle">Masuk ke akun Anda</p>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type']; ?> alert-custom" role="alert">
                <?= $flash['message']; ?>
            </div>
        <?php endif; ?>

        <form id="loginForm" action="process_login.php" method="POST">
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" class="form-control form-control-custom" id="email" name="email" 
                    placeholder="contoh@email.com" required>
                <div class="invalid-feedback">Email wajib diisi</div>
            </div>
            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Password</label>
                <input type="password" class="form-control form-control-custom" id="password" name="password" 
                    placeholder="Masukkan password" required>
                <div class="invalid-feedback">Password wajib diisi</div>
            </div>
            <button type="submit" class="btn btn-primary-custom btn-block mt-4" id="btnLogin">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

        <div class="text-center mt-4">
            <p style="color:#8888A8;">Belum punya akun? 
                <a href="register.php" style="color:#6C5CE7;font-weight:600;">Daftar Sekarang</a>
            </p>
            <a href="index.php" style="color:#8888A8;font-size:0.85rem;">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/app.js"></script>
</body>
</html>

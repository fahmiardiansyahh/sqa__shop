<?php
require_once 'config.php';
if (isLoggedIn()) redirect('index.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SQA Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card" style="max-width:520px;">
        <div class="text-center mb-4">
            <a href="index.php" style="text-decoration:none;">
                <h2><i class="fas fa-store"></i> SQA Shop</h2>
            </a>
            <p class="subtitle">Buat akun baru</p>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type']; ?> alert-custom"><?= $flash['message']; ?></div>
        <?php endif; ?>

        <form id="registerForm" action="process_register.php" method="POST">
            <div class="form-group">
                <label for="nama_lengkap"><i class="fas fa-user"></i> Nama Lengkap</label>
                <input type="text" class="form-control form-control-custom" id="nama_lengkap" name="nama_lengkap" 
                    placeholder="Nama lengkap Anda" required>
                <div class="invalid-feedback">Nama minimal 3 karakter</div>
            </div>
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" class="form-control form-control-custom" id="email" name="email" 
                    placeholder="contoh@email.com" required>
                <div class="invalid-feedback">Format email tidak valid</div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password"><i class="fas fa-lock"></i> Password</label>
                        <input type="password" class="form-control form-control-custom" id="password" name="password" 
                            placeholder="Min. 6 karakter" required>
                        <div class="invalid-feedback">Password minimal 6 karakter</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="konfirmasi_password"><i class="fas fa-lock"></i> Konfirmasi</label>
                        <input type="password" class="form-control form-control-custom" id="konfirmasi_password" name="konfirmasi_password" 
                            placeholder="Ulangi password" required>
                        <div class="invalid-feedback">Password tidak cocok</div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="no_telepon"><i class="fas fa-phone"></i> No. Telepon</label>
                <input type="text" class="form-control form-control-custom" id="no_telepon" name="no_telepon" 
                    placeholder="08xxxxxxxxxx">
                <div class="invalid-feedback">No telepon harus 10-15 digit</div>
            </div>
            <div class="form-group">
                <label for="alamat"><i class="fas fa-map-marker-alt"></i> Alamat</label>
                <textarea class="form-control form-control-custom" id="alamat" name="alamat" rows="2" 
                    placeholder="Alamat lengkap (opsional)"></textarea>
            </div>
            <button type="submit" class="btn btn-primary-custom btn-block mt-3" id="btnRegister">
                <i class="fas fa-user-plus"></i> Daftar
            </button>
        </form>

        <div class="text-center mt-4">
            <p style="color:#8888A8;">Sudah punya akun? 
                <a href="login.php" style="color:#6C5CE7;font-weight:600;">Login</a>
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

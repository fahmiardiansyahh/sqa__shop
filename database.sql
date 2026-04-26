-- ============================================
-- Database: sqa_shop
-- Aplikasi E-Commerce Sederhana untuk SQA Testing
-- ============================================

CREATE DATABASE IF NOT EXISTS sqa_shop;
USE sqa_shop;

-- ============================================
-- Tabel: users
-- Menyimpan data pengguna (login & register)
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'pembeli') NOT NULL DEFAULT 'pembeli',
    alamat TEXT,
    no_telepon VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Data Sampel: Admin User
-- Email: admin@sqashop.com | Password: admin123
-- ============================================
INSERT INTO users (nama_lengkap, email, password, role) VALUES
('Admin SQA Shop', 'admin@sqashop.com', '$2y$10$4FIYXRnYB.qAFaKKon859uSW8jcM9gMpx09lAc.K/dz12I5Pj/262', 'admin');

-- ============================================
-- Tabel: products
-- Menyimpan data produk
-- ============================================
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(200) NOT NULL,
    deskripsi TEXT,
    harga DECIMAL(12,2) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    gambar VARCHAR(255) DEFAULT 'default.jpg',
    kategori VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: orders
-- Menyimpan data pesanan / checkout
-- ============================================
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_harga DECIMAL(12,2) NOT NULL,
    metode_pembayaran ENUM('transfer_bank', 'cod', 'e_wallet') NOT NULL DEFAULT 'transfer_bank',
    alamat_pengiriman TEXT NOT NULL,
    catatan TEXT,
    status ENUM('pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: order_items
-- Menyimpan detail item di setiap pesanan
-- ============================================
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    jumlah INT NOT NULL DEFAULT 1,
    harga_satuan DECIMAL(12,2) NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Data Sampel: Products
-- ============================================
INSERT INTO products (nama_produk, deskripsi, harga, stok, gambar, kategori) VALUES
('Laptop Gaming Pro', 'Laptop gaming dengan prosesor terbaru, RAM 16GB, SSD 512GB, dan GPU dedicated untuk pengalaman gaming terbaik.', 15500000.00, 25, 'laptop.jpg', 'Elektronik'),
('Headphone Wireless X', 'Headphone wireless premium dengan noise cancellation, baterai 30 jam, dan kualitas suara Hi-Res Audio.', 1250000.00, 50, 'headphone.jpg', 'Aksesoris'),
('Smartwatch Ultra', 'Smartwatch dengan layar AMOLED, tracking kesehatan lengkap, GPS built-in, dan water resistant 50m.', 3750000.00, 30, 'smartwatch.jpg', 'Elektronik'),
('Keyboard Mechanical RGB', 'Keyboard mechanical dengan switch Cherry MX, RGB per-key, dan build quality premium aluminium.', 950000.00, 40, 'keyboard.jpg', 'Aksesoris'),
('Mouse Gaming Precision', 'Mouse gaming dengan sensor 25000 DPI, weight tuning system, dan ergonomic design.', 850000.00, 60, 'mouse.jpg', 'Aksesoris'),
('Monitor 4K UltraWide', 'Monitor 34 inch ultrawide 4K dengan panel IPS, HDR400, dan refresh rate 144Hz.', 8500000.00, 15, 'monitor.jpg', 'Elektronik');

# 🛒 SQA Shop

Aplikasi **E-Commerce sederhana** yang dibangun untuk keperluan pembelajaran **Software Quality Assurance (SQA) & Testing**. Proyek ini menyediakan alur lengkap mulai dari registrasi, login, belanja produk, checkout, hingga riwayat pesanan — siap digunakan sebagai bahan praktik pengujian perangkat lunak.

---

## ✨ Fitur Utama

| Fitur | Deskripsi |
|---|---|
| 🔐 **Register & Login** | Registrasi akun baru dan autentikasi pengguna dengan hashing password (bcrypt) |
| 🏠 **Halaman Beranda** | Menampilkan katalog produk dengan gambar, harga, kategori, dan stok |
| 🛒 **Keranjang Belanja** | Tambah produk ke keranjang (disimpan di `localStorage`), atur jumlah, hapus item |
| 💳 **Checkout** | Form pengiriman, pilihan metode pembayaran (Transfer Bank, COD, E-Wallet), dan catatan |
| 📋 **Riwayat Pesanan** | Melihat semua pesanan beserta detail item, total, status, dan metode pembayaran |
| 🚪 **Logout** | Mengakhiri sesi pengguna |
| ✅ **Validasi Form** | Validasi client-side (jQuery) dan server-side (PHP) |
| 📱 **Responsive** | Desain responsif menggunakan Bootstrap 4, bisa diakses dari desktop maupun mobile |

---

## 🛠️ Tech Stack

- **Backend:** PHP (Native / Vanilla PHP)
- **Database:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3, JavaScript (jQuery)
- **UI Framework:** Bootstrap 4.6
- **Icons:** Font Awesome 5

---

## 📁 Struktur Proyek

```
sqa_shop/
├── css/
│   └── style.css              # Custom stylesheet
├── js/
│   └── app.js                 # Client-side logic (cart, validasi, dll)
├── config.php                 # Konfigurasi database & helper functions
├── database.sql               # SQL schema & data sampel
├── index.php                  # Halaman beranda (katalog produk)
├── login.php                  # Halaman login
├── process_login.php          # Proses autentikasi login
├── register.php               # Halaman registrasi
├── process_register.php       # Proses registrasi akun baru
├── checkout.php               # Halaman checkout / keranjang
├── process_checkout.php       # Proses pembuatan pesanan
├── riwayat.php                # Halaman riwayat pesanan
├── logout.php                 # Proses logout
└── README.md                  # Dokumentasi proyek
```

---

## ⚙️ Persyaratan Sistem

Pastikan sudah terinstal:

- **PHP** ≥ 7.4
- **MySQL** ≥ 5.7 atau **MariaDB** ≥ 10.3
- **Web Server** (Apache / Nginx)
- **Browser** modern (Chrome, Firefox, Edge, dll.)

> 💡 **Rekomendasi:** Gunakan [Laragon](https://laragon.org/) (Windows), [XAMPP](https://www.apachefriends.org/), atau [MAMP](https://www.mamp.info/) agar lebih mudah.

---

## 🚀 Cara Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/username/sqa_shop.git
```

> Ganti `username` dengan username GitHub kamu.

### 2. Pindahkan ke Folder Web Server

Letakkan folder proyek di direktori root web server:

| Web Server | Lokasi |
|---|---|
| **Laragon** | `C:\laragon\www\sqa_shop` |
| **XAMPP** | `C:\xampp\htdocs\sqa_shop` |
| **MAMP** | `/Applications/MAMP/htdocs/sqa_shop` |
| **Linux (Apache)** | `/var/www/html/sqa_shop` |

### 3. Buat Database

Buka **phpMyAdmin** (atau MySQL client lainnya), lalu jalankan file `database.sql`:

**Opsi A — Melalui phpMyAdmin:**
1. Buka `http://localhost/phpmyadmin`
2. Klik tab **Import**
3. Pilih file `database.sql` dari folder proyek
4. Klik **Go / Eksekusi**

**Opsi B — Melalui Terminal / Command Line:**

```bash
mysql -u root -p < database.sql
```

> File `database.sql` akan otomatis membuat database `sqa_shop` beserta tabel-tabel dan data sampel produk.

### 4. Konfigurasi Database

Buka file `config.php` dan sesuaikan pengaturan koneksi database jika diperlukan:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // Sesuaikan username MySQL
define('DB_PASS', '');           // Sesuaikan password MySQL
define('DB_NAME', 'sqa_shop');
```

> ⚠️ Default konfigurasi menggunakan `root` tanpa password (standar Laragon/XAMPP).

### 5. Jalankan Aplikasi

Pastikan web server (Apache) dan MySQL sudah **running**, lalu buka browser dan akses:

```
http://localhost/sqa_shop
```

---

## 🧪 Panduan Penggunaan (untuk Testing)

### Alur Pengujian Dasar

```
Register → Login → Lihat Produk → Tambah ke Keranjang → Checkout → Cek Riwayat → Logout
```

### Skenario Testing yang Bisa Dicoba

| No | Skenario | Halaman |
|---|---|---|
| 1 | Registrasi dengan data valid | `register.php` |
| 2 | Registrasi dengan email duplikat | `register.php` |
| 3 | Registrasi dengan password tidak cocok | `register.php` |
| 4 | Login dengan kredensial valid | `login.php` |
| 5 | Login dengan email/password salah | `login.php` |
| 6 | Menambahkan produk ke keranjang | `index.php` |
| 7 | Checkout dengan data lengkap | `checkout.php` |
| 8 | Checkout tanpa mengisi alamat | `checkout.php` |
| 9 | Checkout tanpa memilih metode pembayaran | `checkout.php` |
| 10 | Melihat riwayat pesanan setelah checkout | `riwayat.php` |
| 11 | Akses halaman checkout tanpa login | `checkout.php` |
| 12 | Logout dan cek sesi berakhir | `logout.php` |

---

## 📊 Struktur Database

### Tabel `users`
Menyimpan data pengguna yang terdaftar.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | INT (PK, AI) | ID unik pengguna |
| `nama_lengkap` | VARCHAR(100) | Nama lengkap |
| `email` | VARCHAR(100) | Email (unik) |
| `password` | VARCHAR(255) | Password (bcrypt hash) |
| `alamat` | TEXT | Alamat (opsional) |
| `no_telepon` | VARCHAR(20) | Nomor telepon |
| `created_at` | TIMESTAMP | Tanggal daftar |

### Tabel `products`
Menyimpan data katalog produk.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | INT (PK, AI) | ID unik produk |
| `nama_produk` | VARCHAR(200) | Nama produk |
| `deskripsi` | TEXT | Deskripsi produk |
| `harga` | DECIMAL(12,2) | Harga dalam Rupiah |
| `stok` | INT | Jumlah stok |
| `gambar` | VARCHAR(255) | Nama file gambar |
| `kategori` | VARCHAR(100) | Kategori produk |

### Tabel `orders`
Menyimpan data pesanan.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | INT (PK, AI) | ID unik pesanan |
| `user_id` | INT (FK) | Relasi ke `users` |
| `total_harga` | DECIMAL(12,2) | Total harga pesanan |
| `metode_pembayaran` | ENUM | `transfer_bank`, `cod`, `e_wallet` |
| `alamat_pengiriman` | TEXT | Alamat pengiriman |
| `status` | ENUM | `pending`, `diproses`, `dikirim`, `selesai`, `dibatalkan` |

### Tabel `order_items`
Menyimpan detail item di setiap pesanan.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | INT (PK, AI) | ID unik item |
| `order_id` | INT (FK) | Relasi ke `orders` |
| `product_id` | INT (FK) | Relasi ke `products` |
| `jumlah` | INT | Jumlah item |
| `harga_satuan` | DECIMAL(12,2) | Harga per unit |
| `subtotal` | DECIMAL(12,2) | Subtotal (jumlah × harga) |


## 📝 Catatan

- Aplikasi ini dibuat untuk **keperluan pembelajaran dan testing** saja, bukan untuk production.
- Password pengguna di-hash menggunakan **bcrypt** (`password_hash`).
- Keranjang belanja disimpan di **localStorage** browser (client-side).
- Gambar produk menggunakan layanan [Picsum Photos](https://picsum.photos/) sebagai placeholder.

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan pembelajaran mata kuliah **Software Quality Assurance** — **Universitas BSI**.

---

<p align="center">Made by Fahmi Ardiansyah for SQA Learning</p>

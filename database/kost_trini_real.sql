-- ========================================================
-- Database: kost_trini (DATA ASLI SURVEI LAPANGAN)
-- Proyek: Web Pencarian Kost Trini (Sleman, DIY)
-- Catatan: File ini berisi data asli hasil survei tim di kawasan Trini
--          Gunakan file ini nanti saat aplikasi siap dirilis.
-- ========================================================

CREATE DATABASE IF NOT EXISTS `kost_trini` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `kost_trini`;

-- --------------------------------------------------------
-- 1. Struktur Tabel `kost`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `kost_fasilitas`;
DROP TABLE IF EXISTS `fasilitas`;
DROP TABLE IF EXISTS `kost`;
DROP TABLE IF EXISTS `admin_users`;

CREATE TABLE `kost` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_kost` VARCHAR(150) NOT NULL,
  `tipe` ENUM('Putra', 'Putri', 'Campur') NOT NULL,
  `harga_per_bulan` INT NOT NULL,
  `alamat` TEXT NOT NULL,
  `area` VARCHAR(100) NOT NULL DEFAULT 'Trini, Sinduadi, Mlati, Sleman',
  `jarak_kampus` VARCHAR(100) DEFAULT '3-5 Menit ke Kampus 1 UTY',
  `ukuran_kamar` VARCHAR(50) DEFAULT '3 x 3.5 m',
  `tipe_listrik` ENUM('Termasuk Listrik', 'Token Sendiri') DEFAULT 'Termasuk Listrik',
  `sisa_kamar` INT DEFAULT 2,
  `total_kamar` INT DEFAULT 10,
  `rating` DECIMAL(2,1) DEFAULT 4.7,
  `deskripsi` TEXT,
  `foto_utama` VARCHAR(255) NOT NULL,
  `whatsapp_pemilik` VARCHAR(25) NOT NULL,
  `nama_pemilik` VARCHAR(100) NOT NULL,
  `is_featured` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 2. Struktur Tabel `fasilitas`
-- --------------------------------------------------------
CREATE TABLE `fasilitas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_fasilitas` VARCHAR(100) NOT NULL,
  `kategori` ENUM('Kamar', 'Umum', 'Kamar Mandi', 'Parkir') DEFAULT 'Kamar',
  `icon` VARCHAR(50) DEFAULT 'fa-check'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 3. Pivot Tabel `kost_fasilitas` (Many-to-Many)
-- --------------------------------------------------------
CREATE TABLE `kost_fasilitas` (
  `kost_id` INT NOT NULL,
  `fasilitas_id` INT NOT NULL,
  PRIMARY KEY (`kost_id`, `fasilitas_id`),
  FOREIGN KEY (`kost_id`) REFERENCES `kost`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`fasilitas_id`) REFERENCES `fasilitas`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 4. Tabel `admin_users`
-- --------------------------------------------------------
CREATE TABLE `admin_users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================================
-- DATA MASTER FASILITAS
-- ========================================================
INSERT INTO `fasilitas` (`id`, `nama_fasilitas`, `kategori`, `icon`) VALUES
(1, 'Free WiFi', 'Umum', 'fa-wifi'),
(2, 'Free Listrik (Termasuk Listrik)', 'Umum', 'fa-bolt'),
(3, 'Kamar Mandi Dalam', 'Kamar Mandi', 'fa-shower'),
(4, 'Kamar Mandi Luar', 'Kamar Mandi', 'fa-bath'),
(5, 'Kasur & Bantal (Isian)', 'Kamar', 'fa-bed'),
(6, 'Lemari Pakaian', 'Kamar', 'fa-door-closed'),
(7, 'Opsi Kosongan', 'Kamar', 'fa-box-open'),
(8, 'Bisa Request Isian', 'Kamar', 'fa-handshake-angle'),
(9, 'Parkir Motor Luas', 'Parkir', 'fa-motorcycle'),
(10, 'Dapur Bersama', 'Umum', 'fa-utensils'),
(11, 'Akses Bebas 24 Jam', 'Umum', 'fa-clock'),
(12, 'Air Sumur Jernih & Lancar', 'Umum', 'fa-faucet-drip');

-- Admin Default (Password: admin123)
INSERT INTO `admin_users` (`id`, `username`, `password`, `nama_lengkap`) VALUES
(1, 'admin', '$2y$10$wT.fBkWgP08e/GZszf1.5.kYjS9LwXJbV33/n1W/6Y0hC4wS9Wb.O', 'Administrator Kost Trini');

-- ========================================================
-- DATA ASLI SURVEI KOST TRINI (HASIL LAPANGAN)
-- ========================================================
INSERT INTO `kost` (`id`, `nama_kost`, `tipe`, `harga_per_bulan`, `alamat`, `area`, `jarak_kampus`, `ukuran_kamar`, `tipe_listrik`, `sisa_kamar`, `total_kamar`, `rating`, `deskripsi`, `foto_utama`, `whatsapp_pemilik`, `nama_pemilik`, `is_featured`) VALUES

-- 1. Kost Bu Eni (Cowok, 30 Kamar, Free Wifi & Listrik, KM Dalam & Luar)
(1, 'Kost Putra Bu Eni Trini', 'Putra', 450000, 'Jl. Trini Indah No. 18, RT 04/RW 15, Sinduadi, Mlati, Sleman', 'Trini, Sinduadi', '3 Menit ke Kampus 1 UTY', '3 x 3.5 m', 'Termasuk Listrik', 4, 30, 4.8, 
'Kost khusus cowok/putra dengan kapasitas besar (30 kamar) di lingkungan tenang kawasan Trini.

Keunggulan Utama:
- FREE WiFi dan FREE Listrik (Sudah termasuk biaya sewa)
- Pilihan Kamar Mandi Luar: Rp 450.000 / bulan
- Pilihan Kamar Mandi Dalam: Rp 650.000 / bulan
- Fasilitas fleksibel: Bisa kosongan maupun request isian (kasur & lemari).
- Parkiran motor luas dan aman di dalam pagar.', 
'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?w=800&auto=format&fit=crop&q=80', '6281234567801', 'Ibu Eni', 1),

-- 2. Kost Pak Aan (10 Kamar, Isian Lemari & Kasur, KM Luar 500rb & KM Dalam 650rb)
(2, 'Kost Putra & Mahasiswa Pak Aan', 'Putra', 500000, 'Jl. Kebon Agung Gg. Trini Asri, Mlati, Sleman', 'Trini Tengah', '4 Menit ke Kampus 1 UTY', '3 x 4 m', 'Termasuk Listrik', 2, 10, 4.7, 
'Kost nyaman dengan total 10 kamar milik Pak Aan di Trini Mlati. 

Fasilitas & Pilihan Tarif:
- Sudah isian lengkap: Kasur empuk dan lemari pakaian
- Kamar Mandi Luar: Rp 500.000 / bulan
- Kamar Mandi Dalam: Rp 650.000 / bulan
- Suasana bersih, ventilasi udara segar, dan dekat dengan warung makan murah serta minimarket.', 
'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=800&auto=format&fit=crop&q=80', '6281234567802', 'Bapak Aan', 1),

-- 3. Kost Mandiri Trini (7 Kamar, KM Dalam 3 Juta/6 Bln, Kosongan 4 Juta/Thn)
(3, 'Kost Mandiri Trini 7 Pintu', 'Campur', 500000, 'Trini Kulon RT 02/RW 14, Sinduadi, Mlati, Sleman', 'Trini Kulon', '3 Menit ke Kampus 1 UTY', '3 x 3.5 m', 'Termasuk Listrik', 2, 7, 4.6, 
'Kost tenang dan strategis dengan total 7 kamar. Fleksibel tergantung request penyewa (bisa isian atau kosongan).

Sistem Pembayaran:
- Kamar Mandi Dalam: Rp 3.000.000 / 6 bulan (setara Rp 500.000 / bulan)
- Kosongan: Rp 4.000.000 / tahun (sangat hemat, setara ~Rp 333.000 / bulan)
- Lingkungan aman dan kondusif untuk belajar.', 
'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800&auto=format&fit=crop&q=80', '6281234567803', 'Pengelola Kost Trini', 1),

-- 4. Kost Ibu Jila (4 Kamar, Kosongan, KM Luar 500rb, Bisa Request)
(4, 'Kost Ibu Jila Trini', 'Putri', 500000, 'Jl. Trini Kidul RT 05/RW 15, Dekat Masjid Al-Ikhlas, Mlati', 'Trini Kidul', '5 Menit ke Kampus 1 UTY', '3 x 3 m', 'Termasuk Listrik', 1, 4, 4.8, 
'Kost ramah dan bersih milik Ibu Jila dengan total 4 kamar. Suasana kekeluargaan seperti di rumah sendiri.

Keterangan:
- Harga: Rp 500.000 / bulan
- Kamar mandi luar bersih
- Standar tipe kosongan, namun penyewa BISA REQUEST isian kasur/perabot sesuai kesepakatan dengan Ibu Jila
- Sudah termasuk listrik dan air.', 
'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?w=800&auto=format&fit=crop&q=80', '6281234567804', 'Ibu Jila', 0),

-- 5. Kost Pak DI (5 Kamar, Kosongan, KM Luar 500rb)
(5, 'Kost Pak DI Trini', 'Putra', 500000, 'Gg. Trini Mulya No. 08, Sinduadi, Mlati, Sleman', 'Trini Sinduadi', '4 Menit ke Kampus 1 UTY', '3 x 3 m', 'Termasuk Listrik', 2, 5, 4.5, 
'Kost hemat dan asri dengan total 5 kamar milik Pak DI di kawasan Trini.

Keterangan:
- Harga sewa: Rp 500.000 / bulan
- Fasilitas: Kamar mandi luar, kosongan
- Cocok untuk mahasiswa yang membawa kasur dan lemari sendiri
- Lingkungan ramah, aman, dan parkiran motor tertata rapi.', 
'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&auto=format&fit=crop&q=80', '6281234567805', 'Pak DI', 0);

-- Relasi Fasilitas
INSERT INTO `kost_fasilitas` (`kost_id`, `fasilitas_id`) VALUES
-- Kost 1: Kost Bu Eni
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7), (1, 8), (1, 9), (1, 11), (1, 12),
-- Kost 2: Kost Pak Aan
(2, 2), (2, 3), (2, 4), (2, 5), (2, 6), (2, 9), (2, 11), (2, 12),
-- Kost 3: Kost Mandiri Trini
(3, 2), (3, 3), (3, 5), (3, 6), (3, 7), (3, 8), (3, 9), (3, 11), (3, 12),
-- Kost 4: Kost Ibu Jila
(4, 2), (4, 4), (4, 7), (4, 8), (4, 9), (4, 10), (4, 12),
-- Kost 5: Kost Pak DI
(5, 2), (5, 4), (5, 7), (5, 9), (5, 11), (5, 12);

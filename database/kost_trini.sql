-- ========================================================
-- Database: kost_trini
-- Proyek: Web Pencarian Kost Trini (Sleman, DIY)
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
  `jarak_kampus` VARCHAR(100) DEFAULT '3 Menit ke Kampus 1 UTY',
  `ukuran_kamar` VARCHAR(50) DEFAULT '3 x 4 m',
  `tipe_listrik` ENUM('Termasuk Listrik', 'Token Sendiri') DEFAULT 'Token Sendiri',
  `sisa_kamar` INT DEFAULT 2,
  `total_kamar` INT DEFAULT 12,
  `rating` DECIMAL(2,1) DEFAULT 4.8,
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
-- DATA DUMMY (SEED DATA)
-- ========================================================

-- Master Fasilitas
INSERT INTO `fasilitas` (`id`, `nama_fasilitas`, `kategori`, `icon`) VALUES
(1, 'WiFi Cepat', 'Umum', 'fa-wifi'),
(2, 'AC (Air Conditioner)', 'Kamar', 'fa-snowflake'),
(3, 'Kamar Mandi Dalam', 'Kamar Mandi', 'fa-shower'),
(4, 'Kasur Springbed & Bantal', 'Kamar', 'fa-bed'),
(5, 'Lemari Pakaian & Meja', 'Kamar', 'fa-door-closed'),
(6, 'Parkir Motor Luas', 'Parkir', 'fa-motorcycle'),
(7, 'Parkir Mobil', 'Parkir', 'fa-car'),
(8, 'Dapur Bersama + Kulkas', 'Umum', 'fa-utensils'),
(9, 'Water Heater', 'Kamar Mandi', 'fa-hot-tub-person'),
(10, 'CCTV 24 Jam & Penjaga', 'Umum', 'fa-shield-halved'),
(11, 'Kloset Duduk', 'Kamar Mandi', 'fa-toilet'),
(12, 'Akses Bebas 24 Jam', 'Umum', 'fa-clock');

-- Admin Dummy (Password default: 'admin123')
INSERT INTO `admin_users` (`id`, `username`, `password`, `nama_lengkap`) VALUES
(1, 'admin', '$2y$10$wT.fBkWgP08e/GZszf1.5.kYjS9LwXJbV33/n1W/6Y0hC4wS9Wb.O', 'Administrator Trini');

-- Data Dummy Kost Realistis di Area Trini Sleman
INSERT INTO `kost` (`id`, `nama_kost`, `tipe`, `harga_per_bulan`, `alamat`, `area`, `jarak_kampus`, `ukuran_kamar`, `tipe_listrik`, `sisa_kamar`, `total_kamar`, `rating`, `deskripsi`, `foto_utama`, `whatsapp_pemilik`, `nama_pemilik`, `is_featured`) VALUES
(1, 'Kost Putri Trini Asri Eksklusif', 'Putri', 950000, 'Jl. Trini Indah No. 12, RT 03/RW 15, Sinduadi, Mlati, Sleman', 'Trini, Sinduadi', '3 Menit ke Kampus 1 UTY', '3 x 4 m', 'Token Sendiri', 2, 14, 4.9, 'Kost putri bersih, nyaman, dan sangat aman di area perumahan Trini. Dekat sekali dengan kampus UTY 1, minimarket, dan kuliner ringroad. Lingkungan tenang sangat kondusif untuk belajar.', 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800&auto=format&fit=crop&q=80', '6281234567801', 'Ibu Hj. Aminah', 1),

(2, 'Pondok Trini Barokah Putra', 'Putra', 550000, 'Gg. Cendrawasih, Trini Kidul, Mlati, Sleman', 'Trini Kidul', '5 Menit ke Kampus 1 UTY', '3 x 3 m', 'Termasuk Listrik', 3, 10, 4.6, 'Kost putra hemat dan bersahabat dengan kantong mahasiswa. Sudah termasuk biaya listrik, air sumur jernih, dan fasilitas dapur bersama. Parkiran motor aman di dalam pagar.', 'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?w=800&auto=format&fit=crop&q=80', '6281234567802', 'Bapak Sumardi', 0),

(3, 'Kost Sinduadi Trini Residence', 'Campur', 1450000, 'Jl. Monjali - Trini Km 1.5, Sinduadi, Mlati, Sleman', 'Trini Sinduadi', '4 Menit ke Kampus 1 UTY', '4 x 4 m', 'Token Sendiri', 1, 16, 4.9, 'Kost eksklusif campur (pasutri/mahasiswa/karyawan). Kamar lega ber-AC, kamar mandi dalam dengan water heater, meja kerja ergonomis, smart lock, dan parkiran mobil memadai.', 'https://images.unsplash.com/photo-1540518614846-7ede433c4ef3?w=800&auto=format&fit=crop&q=80', '6281234567803', 'Ibu Retno Wardani', 1),

(4, 'Kost Griya Trini Utama Putra', 'Putra', 850000, 'Jl. Kebon Agung No. 45, Trini, Mlati, Sleman', 'Trini Utara', '3 Menit ke Kampus 1 UTY', '3.5 x 3.5 m', 'Token Sendiri', 4, 12, 4.7, 'Kost putra modern ber-AC dengan koneksi internet fiber optic 100 Mbps, cocok untuk mahasiswa IT atau gamer. Kamar mandi dalam, kasur empuk, dan lemari pakaian besar.', 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=800&auto=format&fit=crop&q=80', '6281234567804', 'Bapak Handoko', 1),

(5, 'Kost Muslimah Annisa Trini', 'Putri', 700000, 'Trini RT 02/RW 14, Dekat Masjid Al-Ikhlas Trini, Mlati', 'Trini Tengah', '4 Menit ke Kampus 1 UTY', '3 x 3.5 m', 'Termasuk Listrik', 2, 8, 4.8, 'Kost khusus muslimah dengan pagar tertutup dan pengawasan ibu kost yang ramah. Suasana kekeluargaan, bersih, dekat dengan masjid dan warung makan murah.', 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?w=800&auto=format&fit=crop&q=80', '6281234567805', 'Umi Khadijah', 0),

(6, 'Kost Gardenia Trini Premiere', 'Campur', 1800000, 'Jl. Trini Timur No. 8, Sinduadi, Mlati, Sleman', 'Trini Timur', '6 Menit ke JCM & UTY', '4 x 5 m', 'Token Sendiri', 2, 20, 5.0, 'Hunian kost premium dengan konsep minimalis estetik. Dilengkapi balkon pribadi, water heater, cleaning service mingguan, security 24 jam, dan ruang santai rooftop.', 'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=800&auto=format&fit=crop&q=80', '6281234567806', 'Management Gardenia', 1),

(7, 'Kost Pondok Hijau Mlati-Trini', 'Putra', 600000, 'Trini Kulon RT 05, Belakang Kampus UTY Mlati', 'Trini Kulon', '2 Menit Jalan Kaki ke UTY', '3 x 3 m', 'Termasuk Listrik', 1, 9, 4.5, 'Sangat dekat dengan kampus, bisa ditempuh jalan kaki 2 menit saja. Cocok untuk mahasiswa yang tidak membawa kendaraan. Bebas jam malam dengan kunci gerbang masing-masing.', 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&auto=format&fit=crop&q=80', '6281234567807', 'Pak Bambang', 0),

(8, 'Kost Melati Trini Cantik', 'Putri', 1100000, 'Jl. Ringroad Utara Gg. Trini Asri, Sinduadi, Mlati', 'Trini Sinduadi', '5 Menit ke UTY & MMTC', '3.5 x 4 m', 'Token Sendiri', 3, 15, 4.8, 'Kost putri fasilitas komplit: AC dingin, WiFi kencang, kamar mandi dalam, free refill air galon, dan fasilitas laundry gratis setiap minggu.', 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&auto=format&fit=crop&q=80', '6281234567808', 'Ibu Hj. Maryati', 1);

-- Relasi Fasilitas untuk masing-masing kost
-- Kost 1: Putri Asri (WiFi, AC, KM Dalam, Kasur, Lemari, Parkir Motor, Dapur, CCTV, Kloset Duduk)
INSERT INTO `kost_fasilitas` (`kost_id`, `fasilitas_id`) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 8), (1, 10), (1, 11),
-- Kost 2: Pondok Trini Barokah Putra (WiFi, Kasur, Lemari, Parkir Motor, Dapur, Akses 24 Jam)
(2, 1), (2, 4), (2, 5), (2, 6), (2, 8), (2, 12),
-- Kost 3: Sinduadi Trini Residence (WiFi, AC, KM Dalam, Kasur, Lemari, Parkir Mobil, Parkir Motor, Dapur, Water Heater, CCTV, Kloset Duduk, Akses 24 Jam)
(3, 1), (3, 2), (3, 3), (3, 4), (3, 5), (3, 6), (3, 7), (3, 8), (3, 9), (3, 10), (3, 11), (3, 12),
-- Kost 4: Griya Trini Utama Putra (WiFi, AC, KM Dalam, Kasur, Lemari, Parkir Motor, Dapur, Akses 24 Jam, CCTV)
(4, 1), (4, 2), (4, 3), (4, 4), (4, 5), (4, 6), (4, 8), (4, 10), (4, 12),
-- Kost 5: Kost Muslimah Annisa Trini (Kasur, Lemari, Parkir Motor, Dapur, CCTV)
(5, 4), (5, 5), (5, 6), (5, 8), (5, 10),
-- Kost 6: Gardenia Trini Premiere (Semua Fasilitas Lengkap 1 s/d 12)
(6, 1), (6, 2), (6, 3), (6, 4), (6, 5), (6, 6), (6, 7), (6, 8), (6, 9), (6, 10), (6, 11), (6, 12),
-- Kost 7: Kost Pondok Hijau Mlati-Trini (WiFi, Kasur, Lemari, Parkir Motor, Akses 24 Jam)
(7, 1), (7, 4), (7, 5), (7, 6), (7, 12),
-- Kost 8: Kost Melati Trini Cantik (WiFi, AC, KM Dalam, Kasur, Lemari, Parkir Motor, Dapur, CCTV, Kloset Duduk)
(8, 1), (8, 2), (8, 3), (8, 4), (8, 5), (8, 6), (8, 8), (8, 10), (8, 11);

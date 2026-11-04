# 🏡 Web Pencarian Kost Trini (Sleman, DIY)

> Proyek Kolaborasi Web Tugas Akhir / Pemrograman Web (Tim 5 Orang)

Platform web pencarian tempat kost khusus wilayah **Trini, Sinduadi, Mlati, Kabupaten Sleman, D.I. Yogyakarta** (area strategis dekat Kampus 1 Universitas Teknologi Yogyakarta / UTY, Ringroad Utara, dan Monjali).

---

## 🚀 Fitur Utama

- **Pencarian & Filter Cepat**: Cari berdasarkan nama kost, jalan, tipe kost (Putra, Putri, Campur), budget maksimal, serta kelengkapan fasilitas.
- **Halaman Detail Lengkap**: Foto resolusi tinggi, spesifikasi kamar (luas, listrik include/token), sisa kuota kamar, jarak ke kampus UTY, dan daftar fasilitas berikon.
- **Direct WhatsApp Chat**: Tombol booking langsung menghubungkan pencari kost ke nomor WhatsApp pemilik kost dengan template pesan terisi otomatis.
- **Panel Admin / Kelola Kost**: Manajemen data kost (Create, Read, Update, Delete) serta statistik jumlah kost secara real-time.
- **1-Click Auto Setup**: Inisialisasi database dan 8 data dummy realistis hanya dengan sekali klik tanpa ribet konfigurasi SQL manual.

---

## 🛠️ Tech Stack

- **Backend**: PHP 8.x (Native dengan mysqli & Prepared Statements)
- **Database**: MySQL 8.x / MariaDB
- **Frontend**: Bootstrap 5.3, FontAwesome 6 Icons, Custom CSS
- **Web Server Local**: Laragon (Apache/Nginx)

---

## 📂 Struktur Folder Proyek

```text
Pencarian Kost Trini/
├── admin/                     # Panel Admin Kelola Data Kost
│   ├── index.php              # Daftar kost & ringkasan statistik
│   ├── tambah.php             # Form tambah data kost baru
│   ├── edit.php               # Form edit data kost
│   └── hapus.php              # Logika hapus data kost
├── assets/
│   ├── css/
│   │   └── style.css          # Styling kustom (Modern UI)
│   └── js/
│       └── main.js            # JavaScript interaktif
├── config/
│   └── database.php           # Koneksi database MySQL
├── database/
│   ├── kost_trini_dummy.sql   # Data Dummy (8 kost untuk pengembangan saat ini)
│   ├── kost_trini_real.sql    # Data Asli Survei (Kost Bu Eni, Pak Aan, Ibu Jila, dll)
│   └── kost_trini.sql         # Default skema & data
├── includes/
│   ├── header.php             # HTML head & library CDN
│   ├── navbar.php             # Navigasi utama
│   └── footer.php             # Footer & copyright
├── index.php                  # Halaman utama pencarian & listing kost
├── detail.php                 # Halaman detail kamar kost & WhatsApp
├── setup.php                  # Auto-installer database & seeder
├── .gitignore                 # Konfigurasi file yang diabaikan Git
└── README.md                  # Dokumentasi proyek
```

---

## ⚡ Panduan Instalasi & Menjalankan Proyek

### 1. Prasyarat
- Install **Laragon** (atau XAMPP) di Windows.
- Pastikan Apache dan MySQL sudah berstatus **Running** (Klik tombol **Start All** di Laragon).

### 2. Setup Database & Data Dummy
Ada 2 cara yang bisa dipilih:

#### Cara A: Otomatis via Browser (Sangat Direkomendasikan)
1. Letakkan folder ini di `C:\laragon\www\Pencarian Kost Trini`.
2. Buka browser dan akses:
   ```text
   http://localhost/Pencarian%20Kost%20Trini/setup.php
   ```
3. Klik tombol **"Inisialisasi Database Sekarang"**.
4. Selesai! Database `kost_trini` dan 8 data dummy langsung siap digunakan.

#### Cara B: Manual via phpMyAdmin / HeidiSQL
1. Buka phpMyAdmin (`http://localhost/phpmyadmin`).
2. Buat database baru bernama `kost_trini`.
3. Buka tab **Import** dan pilih file `database/kost_trini.sql`.
4. Klik **Import**.

---

## 👥 Rekomendasi Pembagian Tugas (Tim 5 Mahasiswa)

Untuk kelancaran kolaborasi 5 orang, berikut pembagian peran yang direkomendasikan:

| No | Peran / Anggota | Tanggung Jawab & Bagian File |
|---|---|---|
| **1** | **Frontend Engineer (UI/UX)** | Mendesain tampilan beranda, banner hero, responsive mobile di [index.php](file:///c:/laragon/www/Pencarian%20Kost%20Trini/index.php) dan styling di [assets/css/style.css](file:///c:/laragon/www/Pencarian%20Kost%20Trini/assets/css/style.css). |
| **2** | **Detail Page & Integrasi WA** | Menyempurnakan layout halaman detail, galeri foto, spesifikasi kamar, dan format link WhatsApp pemilik di [detail.php](file:///c:/laragon/www/Pencarian%20Kost%20Trini/detail.php). |
| **3** | **Backend & Database Engineer** | Mengelola skema SQL [database/kost_trini.sql](file:///c:/laragon/www/Pencarian%20Kost%20Trini/database/kost_trini.sql), relasi fasilitas, dan konfigurasi di [config/database.php](file:///c:/laragon/www/Pencarian%20Kost%20Trini/config/database.php). |
| **4** | **Admin Dashboard Engineer** | Mengembangkan fitur CRUD (Create, Read, Update, Delete) di folder [admin/](file:///c:/laragon/www/Pencarian%20Kost%20Trini/admin/) (index, tambah, edit, hapus). |
| **5** | **Filter, Search & QA Testing** | Mengoptimalkan logika filter pencarian multi-kriteria di [index.php](file:///c:/laragon/www/Pencarian%20Kost%20Trini/index.php), pengujian bug/validasi form, dan dokumentasi laporan. |

---

## 🌿 Panduan Kolaborasi Git & GitHub

### 1. Inisialisasi Pertama (Ketua Kelompok)
```bash
git init
git add .
git commit -m "feat: inisialisasi struktur awal web pencarian kost trini dengan dummy data"
git branch -M main
git remote add origin <URL_REPOSITORY_GITHUB_KAMU>
git push -u origin main
```

### 2. Cara Rekan Tim Bekerja (Clone & Branch)
Setiap anggota tim disarankan membuat branch baru saat mengerjakan fitur agar tidak konflik:
```bash
# 1. Clone repository
git clone <URL_REPOSITORY>

# 2. Buat branch baru untuk fitur yang dikerjakan
git checkout -b feature/nama-fitur

# 3. Lakukan perubahan kode, lalu commit
git add .
git commit -m "feat: menambahkan filter harga kost"

# 4. Push ke GitHub
git push origin feature/nama-fitur
```
Setelah itu, buat **Pull Request** di GitHub agar ketua tim dapat memeriksa dan menggabungkan kodenya.

---

## 📝 Catatan Tambahan
- Default username & password admin untuk pengujian:
  - **Username**: `admin`
  - **Password**: `admin123`

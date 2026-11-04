<?php
/**
 * Setup & Auto Seeder Database Kost Trini
 * Berfungsi untuk inisialisasi database: Pilihan Data Dummy atau Data Asli Survei
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');

$message = '';
$status = '';
$selectedType = 'dummy';

if (isset($_POST['run_setup'])) {
    $selectedType = $_POST['data_type'] ?? 'dummy';
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
        if ($conn->connect_error) {
            throw new Exception("Koneksi ke MySQL gagal: " . $conn->connect_error);
        }

        $fileName = ($selectedType === 'real') ? 'kost_trini_real.sql' : 'kost_trini_dummy.sql';
        $sqlFile = __DIR__ . '/database/' . $fileName;

        if (!file_exists($sqlFile)) {
            throw new Exception("File SQL database/{$fileName} tidak ditemukan!");
        }

        $sqlQueries = file_get_contents($sqlFile);

        // Eksekusi multi query
        if ($conn->multi_query($sqlQueries)) {
            do {
                if ($result = $conn->store_result()) {
                    $result->free();
                }
            } while ($conn->more_results() && $conn->next_result());

            $status = 'success';
            if ($selectedType === 'real') {
                $message = 'Selamat! Database <strong>kost_trini</strong> dengan <strong>Data Asli Hasil Survei Lapangan</strong> (Kost Bu Eni, Pak Aan, Ibu Jila, dll) berhasil diimpor!';
            } else {
                $message = 'Selamat! Database <strong>kost_trini</strong> dengan <strong>Data Dummy (8 Kost)</strong> berhasil diimpor untuk pengembangan web!';
            }
        } else {
            throw new Exception("Gagal mengeksekusi script SQL: " . $conn->error);
        }
        $conn->close();
    } catch (Exception $e) {
        $status = 'error';
        $message = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Database - Kost Trini</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .option-card {
            cursor: pointer;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            transition: all 0.2s ease;
        }
        .option-card:hover {
            border-color: #93c5fd;
            background-color: #f8fafc;
        }
        .form-check-input:checked ~ .option-body {
            color: #1e3a8a;
        }
    </style>
</head>
<body class="bg-light d-flex align-items-center min-vh-100 py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <span class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-3" style="width: 70px; height: 70px;">
                                <i class="fa-solid fa-database fa-2x"></i>
                            </span>
                            <h3 class="fw-bold text-dark mb-1">Setup Database Kost Trini</h3>
                            <p class="text-muted small">
                                Pilih jenis dataset yang ingin diinisialisasi ke database <code>kost_trini</code> MySQL Laragon.
                            </p>
                        </div>

                        <?php if ($status === 'success'): ?>
                            <div class="alert alert-success d-flex align-items-center gap-3 mb-4 rounded-3">
                                <i class="fa-solid fa-circle-check fa-2x text-success"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Proses Berhasil!</h6>
                                    <div class="small"><?= $message ?></div>
                                </div>
                            </div>
                            <div class="d-grid gap-2">
                                <a href="index.php" class="btn btn-primary btn-lg rounded-pill fw-semibold shadow-sm">
                                    <i class="fa-solid fa-house me-2"></i> Buka Halaman Utama Web
                                </a>
                                <a href="admin/index.php" class="btn btn-outline-secondary rounded-pill">
                                    <i class="fa-solid fa-gauge me-2"></i> Masuk Panel Admin
                                </a>
                            </div>
                        <?php else: ?>
                            <?php if ($status === 'error'): ?>
                                <div class="alert alert-danger d-flex align-items-center gap-3 mb-4 rounded-3">
                                    <i class="fa-solid fa-triangle-exclamation fa-2x"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Terjadi Kesalahan!</h6>
                                        <div class="small"><?= htmlspecialchars($message) ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <form method="POST">
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark mb-3">Pilih Sumber Data Database:</label>
                                    
                                    <!-- Opsi 1: Data Dummy (Default) -->
                                    <label class="option-card p-3 d-flex align-items-start gap-3 mb-3 w-100">
                                        <input class="form-check-input mt-1" type="radio" name="data_type" value="dummy" checked>
                                        <div class="option-body">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <span class="fw-bold text-dark">Data Dummy (Default Pengembangan)</span>
                                                <span class="badge bg-primary">Rekomendasi Saat Ini</span>
                                            </div>
                                            <div class="small text-muted mb-2">
                                                Memuat 8 data variasi kost lengkap untuk kebutuhan pembuatan tampilan, filter, dan fitur kelompok.
                                            </div>
                                            <div class="small text-secondary">
                                                <i class="fa-solid fa-file-code me-1"></i> File: <code>database/kost_trini_dummy.sql</code>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Opsi 2: Data Real Survei Lapangan -->
                                    <label class="option-card p-3 d-flex align-items-start gap-3 w-100">
                                        <input class="form-check-input mt-1" type="radio" name="data_type" value="real">
                                        <div class="option-body">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <span class="fw-bold text-dark">Data Asli (Hasil Survei Lapangan)</span>
                                                <span class="badge bg-success">Siap Digunakan Nanti</span>
                                            </div>
                                            <div class="small text-muted mb-2">
                                                Memuat data asli survei tim: <strong>Kost Bu Eni (30 kmr), Kost Pak Aan (10 kmr), Kost Mandiri Trini (7 kmr), Kost Ibu Jila (4 kmr), Kost Pak DI (5 kmr)</strong>.
                                            </div>
                                            <div class="small text-secondary">
                                                <i class="fa-solid fa-file-code me-1"></i> File: <code>database/kost_trini_real.sql</code>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <button type="submit" name="run_setup" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm">
                                    <i class="fa-solid fa-bolt me-2"></i> Jalankan Inisialisasi Database
                                </button>
                            </form>

                            <div class="text-center mt-3">
                                <a href="index.php" class="text-decoration-none small text-muted">
                                    Kembali ke Beranda &rarr;
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="text-center mt-3 text-muted small">
                    Proyek Tugas Pemrograman Web &bull; Tim 5 Mahasiswa
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
/**
 * Setup & Auto Seeder Database Kost Trini
 * Berfungsi untuk inisialisasi database dan dummy data dalam 1 klik
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');

$message = '';
$status = '';

if (isset($_POST['run_setup'])) {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
        if ($conn->connect_error) {
            throw new Exception("Koneksi ke MySQL gagal: " . $conn->connect_error);
        }

        $sqlFile = __DIR__ . '/database/kost_trini.sql';
        if (!file_exists($sqlFile)) {
            throw new Exception("File SQL database/kost_trini.sql tidak ditemukan!");
        }

        $sqlQueries = file_get_contents($sqlFile);

        // Eksekusi multi query
        if ($conn->multi_query($sqlQueries)) {
            do {
                // Bersihkan result set untuk query berikutnya
                if ($result = $conn->store_result()) {
                    $result->free();
                }
            } while ($conn->more_results() && $conn->next_result());

            $status = 'success';
            $message = 'Selamat! Database <strong>kost_trini</strong> dan <strong>Data Dummy</strong> berhasil dibuat dan diimpor!';
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
    <title>Setup Database & Data Dummy - Kost Trini</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="bg-light d-flex align-items-center min-vh-100">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4 p-md-5 text-center">
                        <div class="mb-4">
                            <span class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle" style="width: 80px; height: 80px;">
                                <i class="fa-solid fa-database fa-2x"></i>
                            </span>
                        </div>
                        
                        <h3 class="fw-bold text-dark mb-2">Setup Database Kost Trini</h3>
                        <p class="text-muted small mb-4">
                            Halaman ini akan otomatis membuat database <code>kost_trini</code>, tabel-tabel terkait, serta mengisinya dengan <strong>8 data dummy kost</strong> realistis di area Trini Sleman.
                        </p>

                        <?php if ($status === 'success'): ?>
                            <div class="alert alert-success text-start d-flex align-items-center gap-3 mb-4">
                                <i class="fa-solid fa-circle-check fa-2x"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Berhasil!</h6>
                                    <div class="small"><?= $message ?></div>
                                </div>
                            </div>
                            <div class="d-grid gap-2">
                                <a href="index.php" class="btn btn-primary btn-lg rounded-pill fw-semibold">
                                    <i class="fa-solid fa-house me-2"></i> Buka Halaman Utama
                                </a>
                                <a href="admin/index.php" class="btn btn-outline-secondary rounded-pill">
                                    <i class="fa-solid fa-gauge me-2"></i> Masuk Panel Admin
                                </a>
                            </div>
                        <?php else: ?>
                            <?php if ($status === 'error'): ?>
                                <div class="alert alert-danger text-start d-flex align-items-center gap-3 mb-4">
                                    <i class="fa-solid fa-triangle-exclamation fa-2x"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Terjadi Kesalahan!</h6>
                                        <div class="small"><?= htmlspecialchars($message) ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="card bg-white border rounded-3 p-3 text-start small mb-4">
                                <div class="fw-semibold text-dark mb-2"><i class="fa-solid fa-info-circle text-primary me-1"></i> Data yang akan dibuat:</div>
                                <ul class="mb-0 ps-3 text-muted">
                                    <li>Tabel <code>kost</code> dengan 8 data kost lengkap di Trini</li>
                                    <li>Tabel <code>fasilitas</code> (WiFi, AC, Kamar Mandi Dalam, dll)</li>
                                    <li>Akun Admin default: <code>admin</code> / Password: <code>admin123</code></li>
                                </ul>
                            </div>

                            <form method="POST">
                                <button type="submit" name="run_setup" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm">
                                    <i class="fa-solid fa-bolt me-2"></i> Inisialisasi Database Sekarang
                                </button>
                            </form>
                            <div class="mt-3">
                                <a href="index.php" class="text-decoration-none small text-muted">
                                    Sudah pernah setup? <span class="text-primary">Langsung ke Beranda &rarr;</span>
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

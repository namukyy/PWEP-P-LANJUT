<?php
$pageTitle = "Panel Kelola Kost";
require_once __DIR__ . '/../config/database.php';

$conn = getDBConnection();

// Pesan notifikasi
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

// Ambil statistik ringkas
$statTotal = $conn->query("SELECT COUNT(*) AS total FROM kost")->fetch_assoc()['total'];
$statPutra = $conn->query("SELECT COUNT(*) AS total FROM kost WHERE tipe = 'Putra'")->fetch_assoc()['total'];
$statPutri = $conn->query("SELECT COUNT(*) AS total FROM kost WHERE tipe = 'Putri'")->fetch_assoc()['total'];
$statCampur = $conn->query("SELECT COUNT(*) AS total FROM kost WHERE tipe = 'Campur'")->fetch_assoc()['total'];

// Ambil semua daftar kost
$kosts = $conn->query("SELECT * FROM kost ORDER BY id DESC");

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="container py-4">
    <!-- Header Admin -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold mb-1"><i class="fa-solid fa-gauge-high text-primary me-2"></i>Panel Kelola Kost Trini</h3>
            <p class="text-muted small mb-0">Kelola data kost, fasilitas, dan kontak pemilik untuk keperluan kelompok.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="../index.php" class="btn btn-outline-secondary rounded-pill">
                <i class="fa-solid fa-eye me-1"></i> Lihat Web
            </a>
            <a href="tambah.php" class="btn btn-primary rounded-pill fw-semibold shadow-sm">
                <i class="fa-solid fa-plus me-1"></i> Tambah Kost Baru
            </a>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    <?php if ($msg === 'tambah_sukses'): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> Data kost baru berhasil ditambahkan!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif ($msg === 'edit_sukses'): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> Perubahan data kost berhasil disimpan!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif ($msg === 'hapus_sukses'): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
            <i class="fa-solid fa-trash me-2"></i> Data kost berhasil dihapus dari sistem!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Statistik Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Total Kost</div>
                        <h3 class="fw-bold mb-0 text-dark"><?= $statTotal ?></h3>
                    </div>
                    <span class="p-3 bg-primary bg-opacity-10 text-primary rounded-3">
                        <i class="fa-solid fa-house-chimney fa-lg"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Kost Putra</div>
                        <h3 class="fw-bold mb-0 text-info"><?= $statPutra ?></h3>
                    </div>
                    <span class="p-3 bg-info bg-opacity-10 text-info rounded-3">
                        <i class="fa-solid fa-mars fa-lg"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Kost Putri</div>
                        <h3 class="fw-bold mb-0 text-danger"><?= $statPutri ?></h3>
                    </div>
                    <span class="p-3 bg-danger bg-opacity-10 text-danger rounded-3">
                        <i class="fa-solid fa-venus fa-lg"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Kost Campur</div>
                        <h3 class="fw-bold mb-0 text-purple" style="color: #7c3aed;"><?= $statCampur ?></h3>
                    </div>
                    <span class="p-3 rounded-3" style="background: rgba(124, 58, 237, 0.1); color: #7c3aed;">
                        <i class="fa-solid fa-venus-mars fa-lg"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Data Kost -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark">Daftar Kost Terdaftar</h6>
            <span class="badge bg-light text-muted border"><?= $statTotal ?> Kost</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-uppercase text-muted">
                    <tr>
                        <th class="ps-4">Kost</th>
                        <th>Tipe</th>
                        <th>Harga / Bulan</th>
                        <th>Sisa Kamar</th>
                        <th>Pemilik & WhatsApp</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($kosts->num_rows === 0): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                Belum ada data kost. Silakan klik <a href="tambah.php">Tambah Kost Baru</a> atau <a href="../setup.php">import data dummy</a>.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php while ($row = $kosts->fetch_assoc()): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="<?= htmlspecialchars($row['foto_utama']) ?>" alt="Foto" class="rounded-3" style="width: 50px; height: 50px; object-fit: cover;">
                                        <div>
                                            <a href="../detail.php?id=<?= $row['id'] ?>" target="_blank" class="fw-bold text-dark text-decoration-none">
                                                <?= htmlspecialchars($row['nama_kost']) ?>
                                            </a>
                                            <div class="small text-muted"><i class="fa-solid fa-map-pin me-1 text-danger"></i> <?= htmlspecialchars($row['area']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                        $badgeBg = 'bg-secondary';
                                        if ($row['tipe'] === 'Putra') $badgeBg = 'bg-info text-dark';
                                        if ($row['tipe'] === 'Putri') $badgeBg = 'bg-danger';
                                        if ($row['tipe'] === 'Campur') $badgeBg = 'bg-primary';
                                    ?>
                                    <span class="badge <?= $badgeBg ?> rounded-pill px-3"><?= $row['tipe'] ?></span>
                                </td>
                                <td class="fw-bold text-primary">
                                    Rp <?= number_format($row['harga_per_bulan'], 0, ',', '.') ?>
                                </td>
                                <td>
                                    <span class="badge <?= $row['sisa_kamar'] > 0 ? 'bg-success' : 'bg-secondary' ?> bg-opacity-10 text-<?= $row['sisa_kamar'] > 0 ? 'success' : 'muted' ?> border">
                                        <?= $row['sisa_kamar'] ?> Kamar
                                    </span>
                                </td>
                                <td>
                                    <div class="small fw-semibold"><?= htmlspecialchars($row['nama_pemilik']) ?></div>
                                    <div class="small text-muted"><i class="fa-brands fa-whatsapp text-success me-1"></i> <?= htmlspecialchars($row['whatsapp_pemilik']) ?></div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="../detail.php?id=<?= $row['id'] ?>" target="_blank" class="btn btn-outline-secondary" title="Lihat Tampilan">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary" title="Edit Data">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-outline-danger" onclick="return confirm('Apakah kamu yakin ingin menghapus data kost <?= addslashes($row['nama_kost']) ?>?')" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

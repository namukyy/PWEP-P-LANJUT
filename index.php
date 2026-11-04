<?php
$pageTitle = "Beranda - Cari Kost di Trini Sleman";
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();

// Ambil parameter filter & search
$search    = isset($_GET['q']) ? trim($_GET['q']) : '';
$tipe      = isset($_GET['tipe']) ? trim($_GET['tipe']) : '';
$harga_max = isset($_GET['harga_max']) && is_numeric($_GET['harga_max']) ? (int)$_GET['harga_max'] : 0;
$fasilitas = isset($_GET['fasilitas']) ? (int)$_GET['fasilitas'] : 0;

// Query builder
$sql = "SELECT k.*, 
        GROUP_CONCAT(f.nama_fasilitas SEPARATOR ', ') AS daftar_fasilitas
        FROM kost k
        LEFT JOIN kost_fasilitas kf ON k.id = kf.kost_id
        LEFT JOIN fasilitas f ON kf.fasilitas_id = f.id
        WHERE 1=1";

$params = [];
$types  = "";

if (!empty($search)) {
    $sql .= " AND (k.nama_kost LIKE ? OR k.alamat LIKE ? OR k.area LIKE ?)";
    $searchTerm = "%" . $search . "%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "sss";
}

if (!empty($tipe) && in_array($tipe, ['Putra', 'Putri', 'Campur'])) {
    $sql .= " AND k.tipe = ?";
    $params[] = $tipe;
    $types .= "s";
}

if ($harga_max > 0) {
    $sql .= " AND k.harga_per_bulan <= ?";
    $params[] = $harga_max;
    $types .= "i";
}

if ($fasilitas > 0) {
    $sql .= " AND k.id IN (SELECT kost_id FROM kost_fasilitas WHERE fasilitas_id = ?)";
    $params[] = $fasilitas;
    $types .= "i";
}

$sql .= " GROUP BY k.id ORDER BY k.is_featured DESC, k.id ASC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$kostList = [];
while ($row = $result->fetch_assoc()) {
    $kostList[] = $row;
}

// Ambil daftar fasilitas untuk dropdown filter
$fasilitasQuery = $conn->query("SELECT * FROM fasilitas ORDER BY nama_fasilitas ASC");
$allFasilitas = [];
while ($f = $fasilitasQuery->fetch_assoc()) {
    $allFasilitas[] = $f;
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Hero Banner -->
<section class="hero-section text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <span class="badge bg-white text-primary px-3 py-2 rounded-pill fw-bold mb-3 shadow-sm">
                    <i class="fa-solid fa-map-pin me-1 text-danger"></i> Kawasan Trini, Mlati, Sleman (Dekat UTY)
                </span>
                <h1 class="display-5 fw-extrabold text-white mb-3">
                    Temukan Kost Impian di Area <span class="text-warning">Trini Sleman</span>
                </h1>
                <p class="lead text-white-50 mb-4">
                    Pilihan kost putra, putri, dan campur terdekat dengan kampus, fasilitas lengkap, harga transparan langsung hubungi pemilik via WhatsApp.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Filter & Search Box -->
<div class="container">
    <div class="search-box p-4">
        <form action="index.php" method="GET" class="row g-3 align-items-end">
            <!-- Keyword Search -->
            <div class="col-lg-4 col-md-6">
                <label class="form-label small fw-bold text-dark mb-1">
                    <i class="fa-solid fa-magnifying-glass text-primary me-1"></i> Cari Nama Kost / Jalan
                </label>
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Contoh: Kost Asri, Sinduadi..." value="<?= htmlspecialchars($search) ?>">
                </div>
            </div>

            <!-- Tipe Kost -->
            <div class="col-lg-2 col-md-6">
                <label class="form-label small fw-bold text-dark mb-1">
                    <i class="fa-solid fa-venus-mars text-primary me-1"></i> Tipe Kost
                </label>
                <select name="tipe" class="form-select">
                    <option value="">Semua Tipe</option>
                    <option value="Putra" <?= $tipe === 'Putra' ? 'selected' : '' ?>>Putra</option>
                    <option value="Putri" <?= $tipe === 'Putri' ? 'selected' : '' ?>>Putri</option>
                    <option value="Campur" <?= $tipe === 'Campur' ? 'selected' : '' ?>>Campur</option>
                </select>
            </div>

            <!-- Range Budget -->
            <div class="col-lg-3 col-md-6">
                <label class="form-label small fw-bold text-dark mb-1">
                    <i class="fa-solid fa-rupiah-sign text-primary me-1"></i> Budget Maksimal / Bln
                </label>
                <select name="harga_max" class="form-select">
                    <option value="">Semua Harga</option>
                    <option value="500000" <?= $harga_max == 500000 ? 'selected' : '' ?>>s/d Rp 500.000 (Super Hemat)</option>
                    <option value="650000" <?= $harga_max == 650000 ? 'selected' : '' ?>>s/d Rp 650.000 (Standard & Isian)</option>
                    <option value="1000000" <?= $harga_max == 1000000 ? 'selected' : '' ?>>s/d Rp 1.000.000</option>
                    <option value="1500000" <?= $harga_max == 1500000 ? 'selected' : '' ?>>s/d Rp 1.500.000</option>
                </select>
            </div>

            <!-- Fasilitas Pilihan -->
            <div class="col-lg-2 col-md-6">
                <label class="form-label small fw-bold text-dark mb-1">
                    <i class="fa-solid fa-list-check text-primary me-1"></i> Fasilitas
                </label>
                <select name="fasilitas" class="form-select">
                    <option value="">Semua Fasilitas</option>
                    <?php foreach ($allFasilitas as $f): ?>
                        <option value="<?= $f['id'] ?>" <?= $fasilitas == $f['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($f['nama_fasilitas']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="col-lg-1 col-md-12 d-grid">
                <button type="submit" class="btn btn-primary fw-bold" style="height: 42px;" title="Terapkan Filter">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </div>
        </form>

        <?php if (!empty($search) || !empty($tipe) || $harga_max > 0 || $fasilitas > 0): ?>
            <div class="d-flex align-items-center gap-2 mt-3 pt-3 border-top flex-wrap">
                <span class="small text-muted">Filter aktif:</span>
                <?php if (!empty($search)): ?>
                    <span class="badge bg-light text-dark border">Pencarian: "<?= htmlspecialchars($search) ?>"</span>
                <?php endif; ?>
                <?php if (!empty($tipe)): ?>
                    <span class="badge bg-light text-dark border">Tipe: <?= htmlspecialchars($tipe) ?></span>
                <?php endif; ?>
                <?php if ($harga_max > 0): ?>
                    <span class="badge bg-light text-dark border">&le; Rp <?= number_format($harga_max, 0, ',', '.') ?></span>
                <?php endif; ?>
                <a href="index.php" class="btn btn-sm btn-link text-danger text-decoration-none p-0 ms-2">
                    <i class="fa-solid fa-xmark me-1"></i> Reset Filter
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Main Listing Section -->
<main class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">Daftar Kost di Area Trini</h4>
            <p class="text-muted small mb-0">Menampilkan <strong><?= count($kostList) ?></strong> tempat kost pilihan di sekitar Trini Sleman</p>
        </div>
        <div class="small text-muted">
            <i class="fa-solid fa-circle-check text-success me-1"></i> Data Realistis & Terverifikasi
        </div>
    </div>

    <?php if (empty($kostList)): ?>
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center my-4">
            <div class="mb-3 text-muted">
                <i class="fa-solid fa-house-circle-xmark fa-4x text-secondary opacity-50"></i>
            </div>
            <h5 class="fw-bold text-dark">Tidak ada kost yang cocok dengan filter</h5>
            <p class="text-muted small mb-4">Coba longgarkan kata kunci pencarian atau reset filter untuk melihat semua daftar kost.</p>
            <div>
                <a href="index.php" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="fa-solid fa-rotate-left me-1"></i> Reset Semua Filter
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($kostList as $k): ?>
                <?php 
                    $badgeClass = 'badge-campur';
                    if ($k['tipe'] === 'Putra') $badgeClass = 'badge-putra';
                    if ($k['tipe'] === 'Putri') $badgeClass = 'badge-putri';
                ?>
                <div class="col-lg-3 col-md-6">
                    <div class="kost-card">
                        <div class="kost-image-wrapper">
                            <img src="<?= htmlspecialchars($k['foto_utama']) ?>" alt="<?= htmlspecialchars($k['nama_kost']) ?>" loading="lazy">
                            <span class="badge-gender <?= $badgeClass ?>"><?= $k['tipe'] ?></span>
                            <?php if ($k['is_featured']): ?>
                                <span class="badge-featured"><i class="fa-solid fa-star me-1"></i> Pilihan</span>
                            <?php endif; ?>
                        </div>

                        <div class="card-body p-3 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small text-muted">
                                    <i class="fa-solid fa-location-dot text-danger me-1"></i> <?= htmlspecialchars($k['area']) ?>
                                </span>
                                <span class="small text-warning fw-bold">
                                    <i class="fa-solid fa-star"></i> <?= $k['rating'] ?>
                                </span>
                            </div>

                            <h6 class="card-title fw-bold text-dark mb-2 text-truncate" title="<?= htmlspecialchars($k['nama_kost']) ?>">
                                <a href="detail.php?id=<?= $k['id'] ?>" class="text-dark text-decoration-none">
                                    <?= htmlspecialchars($k['nama_kost']) ?>
                                </a>
                            </h6>

                            <div class="small text-muted mb-2">
                                <i class="fa-solid fa-person-walking text-primary me-1"></i> <?= htmlspecialchars($k['jarak_kampus']) ?>
                            </div>

                            <!-- Preview Fasilitas -->
                            <div class="d-flex flex-wrap gap-1 mb-3">
                                <?php 
                                    $arrFas = !empty($k['daftar_fasilitas']) ? explode(', ', $k['daftar_fasilitas']) : [];
                                    $topFas = array_slice($arrFas, 0, 3);
                                    foreach ($topFas as $tf):
                                ?>
                                    <span class="facility-pill"><?= htmlspecialchars($tf) ?></span>
                                <?php endforeach; ?>
                                <?php if (count($arrFas) > 3): ?>
                                    <span class="facility-pill">+<?= count($arrFas) - 3 ?> lainnya</span>
                                <?php endif; ?>
                            </div>

                            <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small text-muted" style="font-size: 0.7rem;">Mulai dari</div>
                                    <div class="kost-price">
                                        Rp <?= number_format($k['harga_per_bulan'], 0, ',', '.') ?><span class="text-muted fw-normal" style="font-size: 0.75rem;">/bln</span>
                                    </div>
                                </div>
                                <a href="detail.php?id=<?= $k['id'] ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                                    Detail &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<!-- Info Banner Mahasiswa / Kolaborasi -->
<section class="container mb-5">
    <div class="card border-0 rounded-4 p-4 p-md-5 text-white" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <span class="badge bg-primary mb-2">Proyek Kolaborasi Kelompok</span>
                <h4 class="fw-bold mb-2">Punya data kost baru di area Trini yang ingin ditambahkan?</h4>
                <p class="text-white-50 mb-0 small">
                    Gunakan panel admin untuk menambah, mengedit, atau mengelola data kost secara mandiri bersama seluruh anggota tim.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="admin/tambah.php" class="btn btn-primary btn-lg rounded-pill px-4 fw-bold shadow-sm">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Kost Baru
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Ambil detail kost
$stmt = $conn->prepare("SELECT * FROM kost WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$kost = $res->fetch_assoc();

if (!$kost) {
    header('Location: index.php');
    exit;
}

$pageTitle = $kost['nama_kost'];

// Ambil fasilitas kost
$fasStmt = $conn->prepare("
    SELECT f.* FROM fasilitas f
    JOIN kost_fasilitas kf ON f.id = kf.fasilitas_id
    WHERE kf.kost_id = ?
");
$fasStmt->bind_param("i", $id);
$fasStmt->execute();
$fasResult = $fasStmt->get_result();
$fasilitas = [];
while ($f = $fasResult->fetch_assoc()) {
    $fasilitas[] = $f;
}

// Rekomendasi kost sejenis (3 kost selain ini)
$recStmt = $conn->prepare("
    SELECT * FROM kost 
    WHERE id != ? AND tipe = ? 
    LIMIT 3
");
$recStmt->bind_param("is", $id, $kost['tipe']);
$recStmt->execute();
$recResult = $recStmt->get_result();
$rekomendasi = [];
while ($r = $recResult->fetch_assoc()) {
    $rekomendasi[] = $r;
}

// Generate URL WhatsApp pesan otomatis
$waText = urlencode("Halo {$kost['nama_pemilik']}, saya melihat iklan '{$kost['nama_kost']}' di website Kost Trini. Apakah saat ini masih ada kamar yang tersedia?");
$waUrl = "https://wa.me/{$kost['whatsapp_pemilik']}?text={$waText}";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item"><a href="index.php?tipe=<?= $kost['tipe'] ?>" class="text-decoration-none">Kost <?= $kost['tipe'] ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($kost['nama_kost']) ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Kolom Kiri: Detail & Foto -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="position-relative">
                    <img src="<?= htmlspecialchars($kost['foto_utama']) ?>" alt="<?= htmlspecialchars($kost['nama_kost']) ?>" class="detail-hero-img w-100">
                    
                    <?php 
                        $badgeClass = 'badge-campur';
                        if ($kost['tipe'] === 'Putra') $badgeClass = 'badge-putra';
                        if ($kost['tipe'] === 'Putri') $badgeClass = 'badge-putri';
                    ?>
                    <span class="badge-gender <?= $badgeClass ?> fs-6">
                        Kost <?= $kost['tipe'] ?>
                    </span>
                    
                    <?php if ($kost['is_featured']): ?>
                        <span class="badge-featured fs-6">
                            <i class="fa-solid fa-star me-1"></i> Pilihan Utama
                        </span>
                    <?php endif; ?>
                </div>

                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2 flex-wrap gap-2">
                        <div>
                            <h2 class="fw-extrabold text-dark mb-1"><?= htmlspecialchars($kost['nama_kost']) ?></h2>
                            <p class="text-muted small mb-0">
                                <i class="fa-solid fa-location-dot text-danger me-1"></i> <?= htmlspecialchars($kost['alamat']) ?>
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">
                                <i class="fa-solid fa-star me-1"></i> <?= $kost['rating'] ?> / 5.0
                            </span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Spesifikasi Kamar Grid -->
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-circle-info text-primary me-2"></i>Spesifikasi Kamar</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-3">
                            <div class="spec-box">
                                <div class="text-muted small mb-1"><i class="fa-solid fa-vector-square text-primary"></i> Ukuran</div>
                                <div class="fw-bold"><?= htmlspecialchars($kost['ukuran_kamar']) ?></div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="spec-box">
                                <div class="text-muted small mb-1"><i class="fa-solid fa-bolt text-warning"></i> Listrik</div>
                                <div class="fw-bold"><?= htmlspecialchars($kost['tipe_listrik']) ?></div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="spec-box">
                                <div class="text-muted small mb-1"><i class="fa-solid fa-door-open text-success"></i> Sisa Kamar</div>
                                <div class="fw-bold text-success"><?= $kost['sisa_kamar'] ?> dari <?= $kost['total_kamar'] ?></div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="spec-box">
                                <div class="text-muted small mb-1"><i class="fa-solid fa-route text-danger"></i> Jarak Kampus</div>
                                <div class="fw-bold"><?= htmlspecialchars($kost['jarak_kampus']) ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Fasilitas Kost & Kamar -->
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-check-double text-primary me-2"></i>Fasilitas Lengkap</h5>
                    <div class="row g-2 mb-4">
                        <?php if (empty($fasilitas)): ?>
                            <div class="col-12 text-muted small">Tidak ada informasi fasilitas.</div>
                        <?php else: ?>
                            <?php foreach ($fasilitas as $f): ?>
                                <div class="col-md-4 col-6">
                                    <div class="p-2 border rounded-3 d-flex align-items-center gap-2 bg-light">
                                        <i class="fa-solid <?= htmlspecialchars($f['icon']) ?> text-primary fa-fw"></i>
                                        <span class="small fw-semibold"><?= htmlspecialchars($f['nama_fasilitas']) ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Deskripsi -->
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-align-left text-primary me-2"></i>Deskripsi Kost</h5>
                    <p class="text-muted lh-base mb-4">
                        <?= nl2br(htmlspecialchars($kost['deskripsi'])) ?>
                    </p>

                    <!-- Lokasi & Sekitarnya -->
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-map-location-dot text-primary me-2"></i>Lokasi & Akses Sekitar</h5>
                    <div class="card bg-light border-0 rounded-3 p-3">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fa-solid fa-university text-primary fa-2x"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Dekat Kampus 1 Universitas Teknologi Yogyakarta (UTY)</h6>
                                <p class="text-muted small mb-0">Hanya berjarak sekitar <?= htmlspecialchars($kost['jarak_kampus']) ?>, sangat dekat ke jalan Ringroad Utara, minimarket 24 jam, warung makan, dan photocopy.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Card Booking & Kontak Pemilik -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 90px;">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <span class="text-muted small">Mulai dari</span>
                        <div class="d-flex align-items-baseline gap-1">
                            <h3 class="fw-extrabold text-primary mb-0">Rp <?= number_format($kost['harga_per_bulan'], 0, ',', '.') ?></h3>
                            <span class="text-muted small">/ bulan</span>
                        </div>
                    </div>

                    <?php if ($kost['sisa_kamar'] <= 2 && $kost['sisa_kamar'] > 0): ?>
                        <div class="alert alert-warning py-2 px-3 small d-flex align-items-center gap-2 mb-3 rounded-3">
                            <i class="fa-solid fa-fire text-danger"></i>
                            <span>Kamar tersisa <strong><?= $kost['sisa_kamar'] ?> kamar lagi</strong>! Segera hubungi pemilik.</span>
                        </div>
                    <?php endif; ?>

                    <div class="card bg-light border-0 rounded-3 p-3 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 45px; height: 45px;">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                            <div>
                                <div class="small text-muted">Pemilik Kost</div>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($kost['nama_pemilik']) ?></div>
                                <div class="small text-muted"><i class="fa-brands fa-whatsapp text-success me-1"></i> <?= htmlspecialchars($kost['whatsapp_pemilik']) ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mb-3">
                        <a href="<?= $waUrl ?>" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp btn-lg rounded-pill py-3 text-center">
                            <i class="fa-brands fa-whatsapp fa-lg me-2"></i> Tanya Pemilik via WA
                        </a>
                        <a href="index.php" class="btn btn-outline-secondary rounded-pill">
                            <i class="fa-solid fa-arrow-left me-1"></i> Kembali Cari Kost Lain
                        </a>
                    </div>

                    <div class="small text-muted text-center">
                        <i class="fa-solid fa-shield-halved text-success me-1"></i> Bebas biaya perantara / komisi
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rekomendasi Kost Lainnya -->
    <?php if (!empty($rekomendasi)): ?>
        <section class="mt-5">
            <h4 class="fw-bold mb-4">Rekomendasi Kost <?= $kost['tipe'] ?> Lainnya di Trini</h4>
            <div class="row g-4">
                <?php foreach ($rekomendasi as $rk): ?>
                    <div class="col-md-4">
                        <div class="kost-card">
                            <div class="kost-image-wrapper" style="height: 180px;">
                                <img src="<?= htmlspecialchars($rk['foto_utama']) ?>" alt="<?= htmlspecialchars($rk['nama_kost']) ?>">
                                <span class="badge-gender badge-<?= strtolower($rk['tipe']) ?>"><?= $rk['tipe'] ?></span>
                            </div>
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-1">
                                    <a href="detail.php?id=<?= $rk['id'] ?>" class="text-dark text-decoration-none">
                                        <?= htmlspecialchars($rk['nama_kost']) ?>
                                    </a>
                                </h6>
                                <p class="small text-muted mb-2"><i class="fa-solid fa-location-dot text-danger me-1"></i> <?= htmlspecialchars($rk['area']) ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-primary">Rp <?= number_format($rk['harga_per_bulan'], 0, ',', '.') ?>/bln</span>
                                    <a href="detail.php?id=<?= $rk['id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill">Lihat</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

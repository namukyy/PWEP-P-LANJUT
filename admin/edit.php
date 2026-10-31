<?php
$pageTitle = "Edit Data Kost";
require_once __DIR__ . '/../config/database.php';

$conn = getDBConnection();
$error = '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Ambil data kost
$stmt = $conn->prepare("SELECT * FROM kost WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$kost = $stmt->get_result()->fetch_assoc();

if (!$kost) {
    header('Location: index.php');
    exit;
}

// Ambil master fasilitas
$fasilitasQuery = $conn->query("SELECT * FROM fasilitas ORDER BY nama_fasilitas ASC");
$allFasilitas = [];
while ($f = $fasilitasQuery->fetch_assoc()) {
    $allFasilitas[] = $f;
}

// Ambil fasilitas aktif kost ini
$activeFasStmt = $conn->prepare("SELECT fasilitas_id FROM kost_fasilitas WHERE kost_id = ?");
$activeFasStmt->bind_param("i", $id);
$activeFasStmt->execute();
$activeFasResult = $activeFasStmt->get_result();
$currentFasilitas = [];
while ($row = $activeFasResult->fetch_assoc()) {
    $currentFasilitas[] = $row['fasilitas_id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kost        = trim($_POST['nama_kost'] ?? '');
    $tipe             = trim($_POST['tipe'] ?? 'Putra');
    $harga_per_bulan  = (int)($_POST['harga_per_bulan'] ?? 0);
    $alamat           = trim($_POST['alamat'] ?? '');
    $area             = trim($_POST['area'] ?? '');
    $jarak_kampus     = trim($_POST['jarak_kampus'] ?? '');
    $ukuran_kamar     = trim($_POST['ukuran_kamar'] ?? '');
    $tipe_listrik     = trim($_POST['tipe_listrik'] ?? 'Token Sendiri');
    $sisa_kamar       = (int)($_POST['sisa_kamar'] ?? 0);
    $total_kamar      = (int)($_POST['total_kamar'] ?? 1);
    $foto_utama       = trim($_POST['foto_utama'] ?? '');
    $whatsapp_pemilik = trim($_POST['whatsapp_pemilik'] ?? '');
    $nama_pemilik     = trim($_POST['nama_pemilik'] ?? '');
    $deskripsi        = trim($_POST['deskripsi'] ?? '');
    $is_featured      = isset($_POST['is_featured']) ? 1 : 0;
    $selectedFasilitas = $_POST['fasilitas'] ?? [];

    if (empty($nama_kost) || empty($alamat) || $harga_per_bulan <= 0 || empty($whatsapp_pemilik)) {
        $error = "Mohon lengkapi data yang wajib diisi!";
    } else {
        if (str_starts_with($whatsapp_pemilik, '08')) {
            $whatsapp_pemilik = '628' . substr($whatsapp_pemilik, 2);
        }

        $updateStmt = $conn->prepare("
            UPDATE kost SET 
                nama_kost = ?, tipe = ?, harga_per_bulan = ?, alamat = ?, area = ?,
                jarak_kampus = ?, ukuran_kamar = ?, tipe_listrik = ?, sisa_kamar = ?,
                total_kamar = ?, deskripsi = ?, foto_utama = ?, whatsapp_pemilik = ?,
                nama_pemilik = ?, is_featured = ?
            WHERE id = ?
        ");
        $updateStmt->bind_param(
            "ssisssssiissssii",
            $nama_kost, $tipe, $harga_per_bulan, $alamat, $area,
            $jarak_kampus, $ukuran_kamar, $tipe_listrik, $sisa_kamar,
            $total_kamar, $deskripsi, $foto_utama, $whatsapp_pemilik,
            $nama_pemilik, $is_featured, $id
        );

        if ($updateStmt->execute()) {
            // Update pivot fasilitas
            $conn->query("DELETE FROM kost_fasilitas WHERE kost_id = $id");
            if (!empty($selectedFasilitas)) {
                $insFas = $conn->prepare("INSERT INTO kost_fasilitas (kost_id, fasilitas_id) VALUES (?, ?)");
                foreach ($selectedFasilitas as $fasId) {
                    $fasIdInt = (int)$fasId;
                    $insFas->bind_param("ii", $id, $fasIdInt);
                    $insFas->execute();
                }
            }

            header("Location: index.php?msg=edit_sukses");
            exit;
        } else {
            $error = "Gagal memperbarui data: " . $conn->error;
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>Edit Data Kost</h3>
                    <p class="text-muted small mb-0">Ubah informasi kost <?= htmlspecialchars($kost['nama_kost']) ?></p>
                </div>
                <a href="index.php" class="btn btn-outline-secondary rounded-pill">
                    <i class="fa-solid fa-arrow-left me-1"></i> Batal & Kembali
                </a>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger rounded-3" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <form action="edit.php?id=<?= $id ?>" method="POST">
                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">1. Informasi Utama Kost</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Nama Kost <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kost" class="form-control" value="<?= htmlspecialchars($kost['nama_kost']) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Tipe Kost <span class="text-danger">*</span></label>
                            <select name="tipe" class="form-select" required>
                                <option value="Putra" <?= $kost['tipe'] === 'Putra' ? 'selected' : '' ?>>Putra</option>
                                <option value="Putri" <?= $kost['tipe'] === 'Putri' ? 'selected' : '' ?>>Putri</option>
                                <option value="Campur" <?= $kost['tipe'] === 'Campur' ? 'selected' : '' ?>>Campur</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Harga Sewa / Bulan (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga_per_bulan" class="form-control" value="<?= $kost['harga_per_bulan'] ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Area / Kawasan</label>
                            <input type="text" name="area" class="form-control" value="<?= htmlspecialchars($kost['area']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Jarak ke Kampus</label>
                            <input type="text" name="jarak_kampus" class="form-control" value="<?= htmlspecialchars($kost['jarak_kampus']) ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="alamat" class="form-control" rows="2" required><?= htmlspecialchars($kost['alamat']) ?></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">URL Foto Utama</label>
                            <input type="url" name="foto_utama" class="form-control" value="<?= htmlspecialchars($kost['foto_utama']) ?>">
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">2. Spesifikasi Kamar</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <label class="form-label small fw-bold">Ukuran Kamar</label>
                            <input type="text" name="ukuran_kamar" class="form-control" value="<?= htmlspecialchars($kost['ukuran_kamar']) ?>">
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label small fw-bold">Listrik</label>
                            <select name="tipe_listrik" class="form-select">
                                <option value="Token Sendiri" <?= $kost['tipe_listrik'] === 'Token Sendiri' ? 'selected' : '' ?>>Token Sendiri</option>
                                <option value="Termasuk Listrik" <?= $kost['tipe_listrik'] === 'Termasuk Listrik' ? 'selected' : '' ?>>Termasuk Listrik</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label small fw-bold">Sisa Kamar</label>
                            <input type="number" name="sisa_kamar" class="form-control" value="<?= $kost['sisa_kamar'] ?>" min="0">
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label small fw-bold">Total Kamar</label>
                            <input type="number" name="total_kamar" class="form-control" value="<?= $kost['total_kamar'] ?>" min="1">
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">3. Fasilitas yang Tersedia</h5>
                    <div class="row g-2 mb-4">
                        <?php foreach ($allFasilitas as $f): ?>
                            <div class="col-md-4 col-6">
                                <div class="form-check p-2 border rounded-3 bg-light">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="fasilitas[]" value="<?= $f['id'] ?>" id="fas_<?= $f['id'] ?>" <?= in_array($f['id'], $currentFasilitas) ? 'checked' : '' ?>>
                                    <label class="form-check-label small fw-semibold" for="fas_<?= $f['id'] ?>">
                                        <i class="fa-solid <?= $f['icon'] ?> text-primary me-1"></i> <?= htmlspecialchars($f['nama_fasilitas']) ?>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">4. Kontak Pemilik & Deskripsi</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nama Pemilik <span class="text-danger">*</span></label>
                            <input type="text" name="nama_pemilik" class="form-control" value="<?= htmlspecialchars($kost['nama_pemilik']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">No. WhatsApp Pemilik <span class="text-danger">*</span></label>
                            <input type="text" name="whatsapp_pemilik" class="form-control" value="<?= htmlspecialchars($kost['whatsapp_pemilik']) ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Deskripsi Kost</label>
                            <textarea name="deskripsi" class="form-control" rows="4"><?= htmlspecialchars($kost['deskripsi']) ?></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" <?= $kost['is_featured'] ? 'checked' : '' ?>>
                                <label class="form-check-label small fw-bold" for="is_featured">
                                    <i class="fa-solid fa-star text-warning me-1"></i> Jadikan Kost Pilihan / Featured
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="index.php" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

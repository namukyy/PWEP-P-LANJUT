<footer class="mt-auto py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 p-2" style="width: 32px; height: 32px;">
                        <i class="fa-solid fa-house-chimney"></i>
                    </span>
                    <h5 class="text-white fw-bold mb-0">Kost<span class="text-primary">Trini</span>.id</h5>
                </div>
                <p class="small text-muted">
                    Platform pencarian kost terpercaya dan terlengkap khusus wilayah Trini, Sinduadi, Mlati, Sleman (Dekat Kampus 1 UTY & sekitarnya).
                </p>
                <div class="d-flex gap-2 text-white">
                    <span class="badge bg-secondary bg-opacity-25 text-white-50"><i class="fa-brands fa-github me-1"></i> Open Source Proyek</span>
                    <span class="badge bg-secondary bg-opacity-25 text-white-50"><i class="fa-solid fa-users me-1"></i> Tim 5 Orang</span>
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <h6 class="text-white fw-bold mb-3">Navigasi</h6>
                <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                    <li><a href="<?= BASE_URL ?>index.php">Beranda</a></li>
                    <li><a href="<?= BASE_URL ?>index.php?tipe=Putra">Kost Putra Trini</a></li>
                    <li><a href="<?= BASE_URL ?>index.php?tipe=Putri">Kost Putri Trini</a></li>
                    <li><a href="<?= BASE_URL ?>index.php?tipe=Campur">Kost Campur</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold mb-3">Area Kampus Terdekat</h6>
                <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                    <li><span class="text-white-50"><i class="fa-solid fa-location-dot text-primary me-2"></i> Kampus 1 UTY (3 - 5 mnt)</span></li>
                    <li><span class="text-white-50"><i class="fa-solid fa-location-dot text-primary me-2"></i> Ringroad Utara Mlati</span></li>
                    <li><span class="text-white-50"><i class="fa-solid fa-location-dot text-primary me-2"></i> Sinduadi & Monjali</span></li>
                    <li><span class="text-white-50"><i class="fa-solid fa-location-dot text-primary me-2"></i> MMTC & JCM</span></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold mb-3">Proyek Mahasiswa</h6>
                <p class="small text-muted mb-2">
                    Dibuat untuk tugas mata kuliah Pemrograman Web oleh kelompok kolaborasi 5 orang.
                </p>
                <a href="<?= BASE_URL ?>setup.php" class="btn btn-outline-light btn-sm rounded-pill">
                    <i class="fa-solid fa-rotate me-1"></i> Reset / Re-seed Data
                </a>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center small text-muted">
            <div>&copy; <?= date('Y') ?> Pencarian Kost Trini. Hak Cipta Dilindungi.</div>
            <div class="mt-2 mt-md-0">
                Kolaborasi Proyek Tim 5 Mahasiswa &bull; Sleman, D.I. Yogyakarta
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Main JS -->
<script src="<?= BASE_URL ?>assets/js/main.js"></script>
</body>
</html>

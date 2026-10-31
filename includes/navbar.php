<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm py-3" style="background-color: #0f172a !important;">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 text-white" href="<?= BASE_URL ?>index.php">
            <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 p-2" style="width: 36px; height: 36px;">
                <i class="fa-solid fa-house-chimney"></i>
            </span>
            <span>Kost<span class="text-primary">Trini</span>.id</span>
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                <li class="nav-item">
                    <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'index.php' && !isset($_GET['tipe'])) ? 'active text-primary fw-semibold' : '' ?>" href="<?= BASE_URL ?>index.php">
                        <i class="fa-solid fa-compass me-1"></i> Jelajah Kost
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (isset($_GET['tipe']) && $_GET['tipe'] == 'Putra') ? 'active text-primary fw-semibold' : '' ?>" href="<?= BASE_URL ?>index.php?tipe=Putra">
                        Kost Putra
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (isset($_GET['tipe']) && $_GET['tipe'] == 'Putri') ? 'active text-primary fw-semibold' : '' ?>" href="<?= BASE_URL ?>index.php?tipe=Putri">
                        Kost Putri
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (isset($_GET['tipe']) && $_GET['tipe'] == 'Campur') ? 'active text-primary fw-semibold' : '' ?>" href="<?= BASE_URL ?>index.php?tipe=Campur">
                        Kost Campur
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <a href="<?= BASE_URL ?>setup.php" class="btn btn-outline-light btn-sm rounded-pill px-3" data-bs-toggle="tooltip" title="Reset / Install Ulang Database & Data Dummy">
                    <i class="fa-solid fa-database me-1"></i> Data Dummy
                </a>
                <a href="<?= BASE_URL ?>admin/index.php" class="btn btn-primary btn-sm rounded-pill px-3 fw-semibold shadow-sm">
                    <i class="fa-solid fa-lock me-1"></i> Panel Admin
                </a>
            </div>
        </div>
    </div>
</nav>

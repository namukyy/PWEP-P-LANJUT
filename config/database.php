<?php
/**
 * Konfigurasi Database - Sistem Pencarian Kost Trini
 * Default Laragon: Host = localhost, User = root, Password = (kosong)
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'kost_trini');

function getDBConnection() {
    static $conn = null;
    if ($conn !== null) {
        return $conn;
    }

    try {
        // Coba koneksi langsung ke database
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $conn->set_charset('utf8mb4');

        if ($conn->connect_error) {
            throw new Exception($conn->connect_error);
        }
        return $conn;
    } catch (Exception $e) {
        // Cek apakah server MySQL hidup tapi database belum dibuat
        $testServer = @new mysqli(DB_HOST, DB_USER, DB_PASS);
        if (!$testServer->connect_error) {
            // Server MySQL hidup, tapi database kost_trini belum ada
            if (basename($_SERVER['PHP_SELF']) !== 'setup.php') {
                header('Location: setup.php');
                exit;
            }
        }

        // Tampilkan pesan error ramah
        die('
        <div style="font-family: system-ui, -apple-system, sans-serif; max-width: 600px; margin: 50px auto; padding: 25px; border-radius: 12px; background: #fff1f2; border: 1px solid #fecdd3; color: #9f1239;">
            <h2 style="margin-top:0;">⚠️ Koneksi Database Gagal</h2>
            <p>' . htmlspecialchars($e->getMessage()) . '</p>
            <p><strong>Langkah Mengatasi:</strong></p>
            <ol>
                <li>Pastikan MySQL di <b>Laragon</b> sudah di-klik <i>Start All</i>.</li>
                <li>Buka halaman setup otomatis: <a href="setup.php" style="color:#be123c; font-weight:bold;">Klik di sini untuk Setup Database & Data Dummy Otomatis</a></li>
                <li>Atau import manual file <code>database/kost_trini.sql</code> via phpMyAdmin.</li>
            </ol>
        </div>');
    }
}

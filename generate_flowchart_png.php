<?php
/**
 * Script Generator Flowchart PNG - Kost Trini
 * Menghasilkan file PNG beresolusi tinggi dengan font Segoe UI
 */

$width = 1100;
$height = 1460;
$im = imagecreatetruecolor($width, $height);
imagealphablending($im, true);
imagesavealpha($im, true);

// Warna Palet Modern
$bg = imagecolorallocate($im, 248, 250, 252);        // #f8fafc
$headerBg = imagecolorallocate($im, 30, 41, 59);    // #1e293b
$white = imagecolorallocate($im, 255, 255, 255);
$textDark = imagecolorallocate($im, 15, 23, 42);     // #0f172a
$textMuted = imagecolorallocate($im, 100, 116, 139); // #64748b
$lineColor = imagecolorallocate($im, 71, 85, 105);   // #475569
$arrowColor = imagecolorallocate($im, 37, 99, 235);  // #2563eb

// Node Colors
$cBlue = imagecolorallocate($im, 37, 99, 235);       // #2563eb
$cIndigo = imagecolorallocate($im, 79, 70, 229);     // #4f46e5
$cGreen = imagecolorallocate($im, 16, 185, 129);     // #10b981
$cWaGreen = imagecolorallocate($im, 34, 197, 94);    // #22c55e
$cAmber = imagecolorallocate($im, 217, 119, 6);      // #d97706
$cSky = imagecolorallocate($im, 2, 132, 199);        // #0284c7
$cRed = imagecolorallocate($im, 225, 29, 72);        // #e11d48
$cDark = imagecolorallocate($im, 51, 65, 85);        // #334155

// Font Paths
$fontBold = 'C:/Windows/Fonts/segoeuib.ttf';
$fontReg  = 'C:/Windows/Fonts/segoeui.ttf';

// Fill Background
imagefilledrectangle($im, 0, 0, $width, $height, $bg);

// Header Banner
imagefilledrectangle($im, 0, 0, $width, 90, $headerBg);
imagettftext($im, 18, 0, 40, 45, $white, $fontBold, "FLOWCHART SISTEM PENCARIAN KOST TRINI SLEMAN");
imagettftext($im, 11, 0, 40, 72, imagecolorallocate($im, 203, 213, 225), $fontReg, "Alur Pengguna: Pencarian, Filter Kriteria, Halaman Detail & Integrasi Direct WhatsApp (UTY)");

// Helper Functions
function drawArrow($im, $x1, $y1, $x2, $y2, $color, $label = "", $font = null, $labelColor = null) {
    imagesetthickness($im, 2);
    imageline($im, $x1, $y1, $x2, $y2, $color);
    // Arrow Head
    $angle = atan2($y2 - $y1, $x2 - $x1);
    $arrowLen = 10;
    $x_a1 = $x2 - $arrowLen * cos($angle - M_PI / 6);
    $y_a1 = $y2 - $arrowLen * sin($angle - M_PI / 6);
    $x_a2 = $x2 - $arrowLen * cos($angle + M_PI / 6);
    $y_a2 = $y2 - $arrowLen * sin($angle + M_PI / 6);
    imagefilledpolygon($im, [$x2, $y2, (int)$x_a1, (int)$y_a1, (int)$x_a2, (int)$y_a2], $color);

    if (!empty($label) && $font) {
        $midX = ($x1 + $x2) / 2 + 8;
        $midY = ($y1 + $y2) / 2 + 4;
        imagettftext($im, 10, 0, (int)$midX, (int)$midY, $labelColor, $font, $label);
    }
}

function drawRoundedBox($im, $x, $y, $w, $h, $radius, $fillColor, $borderColor = null) {
    $x1 = $x - $w / 2;
    $y1 = $y - $h / 2;
    $x2 = $x + $w / 2;
    $y2 = $y + $h / 2;

    // Center fill
    imagefilledrectangle($im, (int)$x1 + $radius, (int)$y1, (int)$x2 - $radius, (int)$y2, $fillColor);
    imagefilledrectangle($im, (int)$x1, (int)$y1 + $radius, (int)$x2, (int)$y2 - $radius, $fillColor);
    // Corners
    imagefilledellipse($im, (int)$x1 + $radius, (int)$y1 + $radius, $radius * 2, $radius * 2, $fillColor);
    imagefilledellipse($im, (int)$x2 - $radius, (int)$y1 + $radius, $radius * 2, $radius * 2, $fillColor);
    imagefilledellipse($im, (int)$x1 + $radius, (int)$y2 - $radius, $radius * 2, $radius * 2, $fillColor);
    imagefilledellipse($im, (int)$x2 - $radius, (int)$y2 - $radius, $radius * 2, $radius * 2, $fillColor);
}

function drawDiamond($im, $x, $y, $w, $h, $fillColor) {
    $points = [
        $x, $y - $h / 2,
        $x + $w / 2, $y,
        $x, $y + $h / 2,
        $x - $w / 2, $y
    ];
    imagefilledpolygon($im, $points, $fillColor);
}

function drawParallelogram($im, $x, $y, $w, $h, $skew, $fillColor) {
    $points = [
        $x - $w / 2 + $skew, $y - $h / 2,
        $x + $w / 2, $y - $h / 2,
        $x + $w / 2 - $skew, $y + $h / 2,
        $x - $w / 2, $y + $h / 2
    ];
    imagefilledpolygon($im, $points, $fillColor);
}

// ----------------------------------------------------
// NODES DRAWING
// ----------------------------------------------------

$cx = 450; // Central X axis

// 1. Mulai
drawRoundedBox($im, $cx, 130, 160, 44, 22, $cBlue);
imagettftext($im, 12, 0, $cx - 24, 136, $white, $fontBold, "Mulai");

// Panah 1 -> 2
drawArrow($im, $cx, 152, $cx, 195, $lineColor);

// 2. Buka Web
drawRoundedBox($im, $cx, 225, 420, 56, 10, $cDark);
imagettftext($im, 11, 0, $cx - 165, 222, $white, $fontBold, "Pengguna Membuka Web Pencarian Kost Trini");
imagettftext($im, 9.5, 0, $cx - 120, 240, imagecolorallocate($im, 226, 232, 240), $fontReg, "(Mengakses halaman beranda index.php)");

// Panah 2 -> 3
drawArrow($im, $cx, 253, $cx, 290, $lineColor);

// 3. Input Kriteria
drawParallelogram($im, $cx, 340, 480, 95, 25, $cSky);
imagettftext($im, 11, 0, $cx - 165, 318, $white, $fontBold, "Input Kriteria Pencarian & Filter:");
imagettftext($im, 9.5, 0, $cx - 165, 338, $white, $fontReg, "1. Kata Kunci (Nama Kost / Jalan / Kawasan Trini)");
imagettftext($im, 9.5, 0, $cx - 165, 356, $white, $fontReg, "2. Tipe: Putra / Putri / Campur  |  3. Budget Maksimal");
imagettftext($im, 9.5, 0, $cx - 165, 374, $white, $fontReg, "4. Fasilitas (WiFi, Kamar Mandi Dalam, Kasur, Listrik)");

// Panah 3 -> 4
drawArrow($im, $cx, 388, $cx, 425, $lineColor, "Klik Filter", $fontBold, $cBlue);

// 4. Query SQL
drawRoundedBox($im, $cx, 455, 420, 56, 10, $cDark);
imagettftext($im, 11, 0, $cx - 160, 452, $white, $fontBold, "Sistem Menyusun & Menjalankan Dynamic SQL");
imagettftext($im, 9.5, 0, $cx - 170, 470, imagecolorallocate($im, 226, 232, 240), $fontReg, "Prepared Statement query ke database kost_trini & pivot fasilitas");

// Panah 4 -> 5
drawArrow($im, $cx, 483, $cx, 515, $lineColor);

// 5. Decision 1: Ada Kost?
drawDiamond($im, $cx, 570, 260, 100, $cAmber);
imagettftext($im, 11, 0, $cx - 68, 566, $white, $fontBold, "Apakah Data Kost");
imagettftext($im, 11, 0, $cx - 55, 584, $white, $fontBold, "Ditemukan?");

// Branch 5 -> Tidak (ke kanan)
drawArrow($im, $cx + 130, 570, 780, 570, $cRed, "Tidak", $fontBold, $cRed);

// Node Pesan Kosong
drawRoundedBox($im, 890, 570, 200, 60, 8, imagecolorallocate($im, 255, 241, 242));
imagettftext($im, 9.5, 0, 805, 564, $cRed, $fontBold, "Tampilkan Pesan Kosong:");
imagettftext($im, 8.5, 0, 805, 582, $textDark, $fontReg, "\"Tidak ada kost yang cocok\"");

// Panah looping reset filter
drawArrow($im, 890, 600, 890, 640, $lineColor);
drawRoundedBox($im, 890, 665, 180, 45, 8, $cDark);
imagettftext($im, 9.5, 0, 825, 670, $white, $fontBold, "Klik \"Reset Filter\"");

// Loop back to input
imagesetthickness($im, 2);
imageline($im, 890, 688, 890, 715, $lineColor);
imageline($im, 890, 715, 750, 715, $lineColor);
imageline($im, 750, 715, 750, 340, $lineColor);
drawArrow($im, 750, 340, $cx + 240, 340, $lineColor, "Ulangi", $fontBold, $cBlue);

// Branch 5 -> Ya (ke bawah)
drawArrow($im, $cx, 620, $cx, 665, $cGreen, "Ya", $fontBold, $cGreen);

// 6. Tampilkan Grid Kost
drawParallelogram($im, $cx, 700, 440, 65, 20, $cGreen);
imagettftext($im, 11, 0, $cx - 165, 696, $white, $fontBold, "Sistem Menampilkan Daftar Kartu Kost");
imagettftext($im, 9.5, 0, $cx - 170, 716, $white, $fontReg, "Menampilkan foto, tipe badge, rating, harga/bln, & fasilitas");

// Panah 6 -> 7
drawArrow($im, $cx, 733, $cx, 770, $lineColor);

// 7. Pilih Kost (Detail)
drawRoundedBox($im, $cx, 798, 420, 54, 10, $cBlue);
imagettftext($im, 11, 0, $cx - 150, 796, $white, $fontBold, "Pengguna Memilih Salah Satu Kost");
imagettftext($im, 9.5, 0, $cx - 145, 814, imagecolorallocate($im, 219, 234, 254), $fontReg, "Menekan tombol \"Detail ->\" menuju detail.php?id=...");

// Panah 7 -> 8
drawArrow($im, $cx, 825, $cx, 860, $lineColor);

// 8. Tampil Halaman Detail
drawParallelogram($im, $cx, 895, 460, 68, 20, $cIndigo);
imagettftext($im, 11, 0, $cx - 175, 888, $white, $fontBold, "Halaman Detail Kost Terbuka Lengkap");
imagettftext($im, 9.5, 0, $cx - 180, 908, $white, $fontReg, "Spesifikasi kamar, galeri foto, fasilitas berikon, & jarak ke UTY");

// Panah 8 -> 9
drawArrow($im, $cx, 929, $cx, 965, $lineColor);

// 9. Decision 2: Tanya Pemilik WA?
drawDiamond($im, $cx, 1025, 260, 100, $cAmber);
imagettftext($im, 11, 0, $cx - 68, 1021, $white, $fontBold, "Ingin Booking /");
imagettftext($im, 11, 0, $cx - 75, 1039, $white, $fontBold, "Tanya via WA?");

// Branch 9 -> Tidak (ke kanan)
drawArrow($im, $cx + 130, 1025, 780, 1025, $lineColor, "Tidak", $fontBold, $textMuted);
drawRoundedBox($im, 880, 1025, 180, 50, 8, $cDark);
imagettftext($im, 9.5, 0, 805, 1030, $white, $fontBold, "Kembali Cari Kost Lain");
imagesetthickness($im, 2);
imageline($im, 880, 1050, 880, 1080, $lineColor);
imageline($im, 880, 1080, 600, 1080, $lineColor);
drawArrow($im, 600, 1080, $cx + 20, 1375, $lineColor);

// Branch 9 -> Ya (ke bawah)
drawArrow($im, $cx, 1075, $cx, 1120, $cGreen, "Ya", $fontBold, $cGreen);

// 10. Klik WhatsApp Direct
drawRoundedBox($im, $cx, 1150, 420, 56, 10, $cWaGreen);
imagettftext($im, 11, 0, $cx - 150, 1146, $white, $fontBold, "Klik Tombol \"Tanya Pemilik via WA\"");
imagettftext($im, 9.5, 0, $cx - 170, 1164, imagecolorallocate($im, 240, 253, 244), $fontReg, "Sistem membuat link WhatsApp + template pesan otomatis");

// Panah 10 -> 11
drawArrow($im, $cx, 1178, $cx, 1215, $lineColor);

// 11. Buka WA
drawParallelogram($im, $cx, 1250, 440, 65, 20, $cGreen);
imagettftext($im, 11, 0, $cx - 165, 1245, $white, $fontBold, "Aplikasi WhatsApp Terbuka Otomatis");
imagettftext($im, 9.5, 0, $cx - 170, 1265, $white, $fontReg, "Pengguna terhubung langsung dengan nomor pemilik kost");

// Panah 11 -> 12
drawArrow($im, $cx, 1283, $cx, 1355, $lineColor);

// 12. Selesai
drawRoundedBox($im, $cx, 1380, 160, 44, 22, $cDark);
imagettftext($im, 12, 0, $cx - 28, 1386, $white, $fontBold, "Selesai");

// Simpan File PNG ke Root dan Assets
$outputPath1 = __DIR__ . '/flowchart_pencarian_kost.png';
$outputPath2 = __DIR__ . '/assets/images/flowchart_pencarian_kost.png';

imagepng($im, $outputPath1, 9);
imagepng($im, $outputPath2, 9);
imagedestroy($im);

echo "Berhasil membuat flowchart PNG di:\n- $outputPath1\n- $outputPath2\n";

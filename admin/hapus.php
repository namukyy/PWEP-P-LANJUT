<?php
require_once __DIR__ . '/../config/database.php';

$conn = getDBConnection();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Hapus relasi fasilitas terlebih dahulu
    $conn->query("DELETE FROM kost_fasilitas WHERE kost_id = $id");

    // Hapus data kost
    $stmt = $conn->prepare("DELETE FROM kost WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: index.php?msg=hapus_sukses");
exit;

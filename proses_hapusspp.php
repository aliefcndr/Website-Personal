<?php
include 'koneksi.php';

$id = $_GET["id"];

// Validasi input
if (!isset($id) || !is_numeric($id)) {
    die("ID tidak valid.");
}

try {
    // Mulai transaksi
    $koneksi->begin_transaction();

    // Hapus data terkait di tabel pembayaran
    $query = $koneksi->prepare("DELETE FROM pembayaran WHERE id_spp = ?");
    $query->bind_param("i", $id);
    if (!$query->execute()) {
        throw new Exception("Gagal menghapus data di tabel pembayaran: " . $query->error);
    }

    // Hapus data di tabel spp
    $query = $koneksi->prepare("DELETE FROM spp WHERE id_spp = ?");
    $query->bind_param("i", $id);
    if (!$query->execute()) {
        throw new Exception("Gagal menghapus data di tabel spp: " . $query->error);
    }

    // Komit transaksi
    $koneksi->commit();

    echo "<script>alert('Data berhasil dihapus.');window.location='spp.php';</script>";
} catch (Exception $e) {
    // Rollback transaksi jika terjadi kesalahan
    $koneksi->rollback();
    die("Gagal menghapus data: " . $e->getMessage());
}

// Tutup statement dan koneksi
$query->close();
$koneksi->close();
?>

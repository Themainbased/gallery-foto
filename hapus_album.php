<?php
include "koneksi.php"; 
session_start();

$albumid = $_GET['albumid'];

// Mengambil daftar foto yang terkait dengan album
$sql_get_photos = mysqli_query($conn, "SELECT fotoid, lokasifile FROM foto WHERE albumid='$albumid'");
while ($data_photo = mysqli_fetch_assoc($sql_get_photos)) {
    // Menghapus file dari sistem file jika ada
    $filename = $data_photo['lokasifile'];
    if (!empty($filename)) {
        $file_path = "gambar/" . $filename;
        if (file_exists($file_path)) {
            unlink($file_path); // Hapus file dari sistem file
        }
    }

    // Menghapus entri foto dari database
    mysqli_query($conn, "DELETE FROM foto WHERE fotoid='" . $data_photo['fotoid'] . "'");
}

// Menghapus album dari database
$sql_delete_album = mysqli_query($conn, "DELETE FROM album WHERE albumid='$albumid'");

if ($sql_delete_album) {
    // Jika berhasil dihapus, arahkan kembali ke halaman album
    header("Location: album.php");
    exit();
} else {
    // Jika terjadi kesalahan, tampilkan pesan atau lakukan tindakan yang sesuai
    echo "Terjadi kesalahan saat menghapus album.";
    // Atau arahkan kembali ke halaman sebelumnya
    // header("Location: sebelumnya.php");
    exit();
}
?>

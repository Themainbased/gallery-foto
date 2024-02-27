<?php
include "koneksi.php"; 
session_start();

// Mendapatkan fotoid dari URL
$fotoid = $_GET['fotoid'];

// Mengambil nama file foto dari database
$sql_get_filename = mysqli_query($conn, "SELECT lokasifile FROM foto WHERE fotoid='$fotoid'");
$data_filename = mysqli_fetch_assoc($sql_get_filename);
$filename = $data_filename['lokasifile'];

// Hapus file dari sistem file jika ada
if (!empty($filename)) {
    $file_path = "gambar/" . $filename;
    if (file_exists($file_path)) {
        unlink($file_path); // Hapus file dari sistem file
    }
}

// Hapus data foto dari database
$sql_delete_foto = mysqli_query($conn, "DELETE FROM foto WHERE fotoid='$fotoid'");

if ($sql_delete_foto) {
    // Jika berhasil dihapus, arahkan kembali ke halaman foto
    header("Location: foto.php");
    exit();
} else {
    // Jika terjadi kesalahan, tampilkan pesan atau lakukan tindakan yang sesuai
    echo "Terjadi kesalahan saat menghapus foto.";
    // Atau arahkan kembali ke halaman sebelumnya
    // header("Location: sebelumnya.php");
    exit();
}
?>

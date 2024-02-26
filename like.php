<?php
include "koneksi.php";
session_start();

if (!isset($_SESSION['userid'])) {
    // Jika pengguna belum login, arahkan kembali ke halaman login
    header("location:index.php");
} else {
    $fotoid = $_GET['fotoid'];
    $userid = $_SESSION['userid'];

    // Periksa apakah pengguna sudah menyukai foto ini atau belum
    $sql = mysqli_query($conn, "SELECT * FROM likefoto WHERE fotoid='$fotoid' AND userid='$userid'");

    if (mysqli_num_rows($sql) == 1) {
        // Jika sudah menyukai foto ini, hapus like
        mysqli_query($conn, "DELETE FROM likefoto WHERE fotoid='$fotoid' AND userid='$userid'");
        header("location:index.php");
    } else {
        // Jika belum menyukai foto ini, tambahkan like
        $tanggallike = date("Y-m-d");
        mysqli_query($conn, "INSERT INTO likefoto VALUES('', '$fotoid', '$userid', '$tanggallike')");
        header("location:index.php");
    }
}
?>

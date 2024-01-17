<?php
include "koneksi.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Mengambil hashed password dari database berdasarkan username
    $query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");

    if ($query) {
        $data = mysqli_fetch_assoc($query);

        // Verifikasi password menggunakan password_verify
        if ($data && password_verify($password, $data['password'])) {
            $_SESSION['userid'] = $data['userid'];
            $_SESSION['namalengkap'] = $data['namalengkap'];
            header("location: home.php");
            exit();
        } else {
            echo "Username atau password salah";
            header("location: login.php");
            exit();
        }
    } else {
        echo "Error: " . mysqli_error($conn);
        exit();
    }
} else {
    // Handle jika form tidak di-submit
    header("location: login.php");
    exit();
}
?>

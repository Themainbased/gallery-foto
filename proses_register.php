<?php

include "koneksi.php";

// Proses Penginputan
$username=$_POST["username"];
$password=$_POST["password"];
$email=$_POST["email"];
$namalengkap=$_POST["namalengkap"];
$alamat=$_POST["alamat"];

// Mengenkripsi Password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Proses Memasukan Field Yang Sudah diisi kedalam tabel user pokokna mah gaes 
$sql=mysqli_query($conn, "insert into user values('','$username','$hashedPassword','$email','$namalengkap','$alamat')");

// Setelah Proses Penginputan Maka a0kan langsung mengarahkan ke halaman login
header("location:login.php");
?>
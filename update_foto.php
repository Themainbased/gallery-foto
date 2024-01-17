<?php
include "koneksi.php";
session_start();

$judulfoto = $_POST['judulfoto'];
$deskripsifoto = $_POST['deskripsifoto'];
$albumid = $_POST['albumid'];

// Get the fotoid from the form or URL, depending on how you pass it
$fotoid = $_POST['fotoid']; // If you pass it through the form
// OR
// $fotoid = $_GET['fotoid']; // If you pass it through the URL

// Check if a new file is uploaded
if ($_FILES['lokasifile']['name'] != "") {
    $rand = rand();
    $ekstensi =  array('png', 'jpg', 'jpeg', 'gif');
    $filename = $_FILES['lokasifile']['name'];
    $ukuran = $_FILES['lokasifile']['size'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);

    // Validate file extension
    if (!in_array($ext, $ekstensi)) {
        header("location:foto.php?error=1");
        exit();
    }

    // Validate file size
    if ($ukuran < 1044070) {
        $xx = $rand . '_' . $filename;
        move_uploaded_file($_FILES['lokasifile']['tmp_name'], 'gambar/' . $rand . '_' . $filename);

        // Use prepared statement to prevent SQL injection
        $stmt = mysqli_prepare($conn, "UPDATE foto SET judulfoto=?, deskripsifoto=?, lokasifile=?, albumid=? WHERE fotoid=?");
        mysqli_stmt_bind_param($stmt, "ssssi", $judulfoto, $deskripsifoto, $xx, $albumid, $fotoid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("location:foto.php");
    } else {
        header("location:foto.php?error=2");
        exit();
    }
} else {
    // If no new file is uploaded
    $stmt = mysqli_prepare($conn, "UPDATE foto SET judulfoto=?, deskripsifoto=?, albumid=? WHERE fotoid=?");
    mysqli_stmt_bind_param($stmt, "sssi", $judulfoto, $deskripsifoto, $albumid, $fotoid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location:foto.php");
}
?>

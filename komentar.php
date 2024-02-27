<?php
include "koneksi.php";
session_start();
if (!isset($_SESSION['userid'])) {
    header("location:login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Komentar</title>
    <!-- Add Bootstrap CSS link here -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="resources/style.css">
    <link rel="stylesheet" href="resources/komentar.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
</head>

<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <a class="navbar-brand" href="#"><img src="resources/logo/logo.png" width="50px"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item "><a class="nav-link" href="album.php">Album</a></li>
                <li class="nav-item"><a class="nav-link" href="foto.php">Foto</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= $_SESSION['namalengkap'] ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="profile.php">Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <br>
    <br>
    <br>
    <div class="container ">
        <form action="tambah_komentar.php" method="post">
            <?php
            $fotoid = $_GET['fotoid'];
            $sql = mysqli_query($conn, "SELECT * from foto where fotoid='$fotoid'");
            while ($data = mysqli_fetch_array($sql)) {
            ?>
                <input type="text" name="fotoid" value="<?= $data['fotoid'] ?>" hidden>

                <div class="card mt-3">
                    <div class="card-body">
                        <img src="gambar/<?= $data['lokasifile'] ?>" class="card-img-top img-fluid">
                        <h5 class="card-title"><?= $data['judulfoto'] ?></h5>
                        <p class="card-text"><?= $data['deskripsifoto'] ?></p>
                        <div class="form-group">
                            <label for="isikomentar">Komentar</label>
                            <input type="text" class="form-control" name="isikomentar">
                        </div>
                        <input type="submit" class="btn btn-primary" value="Tambah">
                    </div>
                </div>
            <?php
            }
            ?>
        </form>

        <div class="mt-5">
            <?php
            // Ambil fotoid dari URL
            $fotoid = $_GET['fotoid'];

            // Perbarui query untuk mengambil komentar yang terkait dengan fotoid
            $sql = mysqli_query($conn, "SELECT komentarfoto.*, foto.judulfoto, user.namalengkap 
            FROM komentarfoto 
            INNER JOIN foto ON komentarfoto.fotoid = foto.fotoid 
            INNER JOIN user ON komentarfoto.userid = user.userid 
            WHERE komentarfoto.fotoid='$fotoid'");
            while ($data = mysqli_fetch_array($sql)) {
            ?>
                <div class="card mt-3"  data-aos="fade-down" data-aos-delay="300">
                    <div class="card-body">
                        <h5 class="card-title"><?= $data['judulfoto'] ?></h5>
                        <p class="card-text">User: <?= $data['namalengkap'] ?></p>
                        <p class="card-text"><?= $data['isikomentar'] ?></p>
                        <p class="card-text"><small class="text-muted"><?= $data['tanggalkomentar'] ?></small></p>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <!-- Add Bootstrap JS and Popper.js scripts here -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
        <script>
            AOS.init();
        </script>
</body>

</html>

<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("location:login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Edit Foto</title>
    <!-- Add Bootstrap CSS link here -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item "><a class="nav-link" href="album.php">Album</a></li>
                <li class="nav-item active"><a class="nav-link" href="foto.php">Foto</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= $_SESSION['namalengkap'] ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Form Edit Foto -->
    <div class="container">
        <h1 class="mt-5">Halaman Edit Foto</h1>
        <p>Hai, <b><?= $_SESSION['namalengkap'] ?></b></p>

        <form action="update_foto.php" method="post" enctype="multipart/form-data">
            <?php
            include "koneksi.php";
            // Periksa apakah fotoid tersedia dalam URL
            if (isset($_GET['fotoid'])) {
                $fotoid = $_GET['fotoid'];
                $sql = mysqli_query($conn, "select * from foto where fotoid='$fotoid'");
                while ($data = mysqli_fetch_array($sql)) {
            ?>
                    <input type="hidden" name="fotoid" value="<?= $data['fotoid'] ?>">
                    <div class="form-group">
                        <label for="judulfoto">Judul</label>
                        <input type="text" class="form-control" name="judulfoto" value="<?= $data['judulfoto'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="deskripsifoto">Deskripsi</label>
                        <input type="text" class="form-control" name="deskripsifoto" value="<?= $data['deskripsifoto'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="albumid">Album</label>
                        <select class="form-control" name="albumid">
                            <?php
                            $userid = $_SESSION['userid'];
                            $sql2 = mysqli_query($conn, "select * from album where userid='$userid'");
                            while ($data2 = mysqli_fetch_array($sql2)) {
                                $selected = ($data2['albumid'] == $data['albumid']) ? 'selected' : '';
                            ?>
                                <option value="<?= $data2['albumid'] ?>" <?= $selected ?>><?= $data2['namaalbum'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Ubah">
                    </div>
            <?php
                }
            } else {
                echo "Fotoid tidak ditemukan.";
            }
            ?>
        </form>
    </div>
    <!-- Add Bootstrap JS and Popper.js scripts here -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

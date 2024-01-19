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
    <title>Halaman Foto</title>
    <!-- Add Bootstrap CSS link here -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <a class="navbar-brand" href="#">Gallery Foto Ardi</a>
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

    <!-- Sesi Crud -->
    <div class="container mt-5">
        <h1>Halaman Foto</h1>
        <p>Hai, <b><?= $_SESSION['namalengkap'] ?></b></p>

        <form action="tambah_foto.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="judulfoto">Judul</label>
                <input type="text" class="form-control" name="judulfoto">
            </div>
            <div class="form-group">
                <label for="deskripsifoto">Deskripsi</label>
                <input type="text" class="form-control" name="deskripsifoto">
            </div>
            <div class="form-group">
                <label for="lokasifile">Lokasi File</label>
                <input type="file" class="form-control" name="lokasifile">
            </div>
            <div class="form-group">
                <label for="albumid">Album</label>
                <select class="form-control" name="albumid">
                    <?php
                    include "koneksi.php";
                    $userid = $_SESSION['userid'];
                    $sql = mysqli_query($conn, "SELECT * from album where userid='$userid'");
                    while ($data = mysqli_fetch_array($sql)) {
                    ?>
                        <option value="<?= $data['albumid'] ?>"><?= $data['namaalbum'] ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Tambah">
            </div>
        </form>

        <!-- View Tabel Hasil Input -->
        <div class="table-responsive mt-5">
            <table class="table table-bordered table-stripped">
                <thead class="thead-dark">
                    <tr>
                        <th>Unique id</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Tanggal Unggah</th>
                        <th>Lokasi File</th>
                        <th>Album</th>
                        <th>Disukai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "koneksi.php";
                    $userid = $_SESSION['userid'];
                    $sql = mysqli_query($conn, "SELECT * FROM foto, album WHERE foto.userid='$userid' AND foto.albumid=album.albumid ORDER BY fotoid DESC");
                    while ($data = mysqli_fetch_array($sql)) {
                        $modifiedFotoid = PHP_INT_MAX - $data['fotoid'] + 1; //Manipulate id from user
                    ?>
                        <tr>
                            <td><?= $modifiedFotoid ?></td>
                            <td><?= $data['judulfoto'] ?></td>
                            <td><?= $data['deskripsifoto'] ?></td>
                            <td><?= $data['tanggalunggah'] ?></td>
                            <td>
                                <img src="gambar/<?= $data['lokasifile'] ?>" width="200px" class="img-thumbnail">
                            </td>
                            <td><?= $data['namaalbum'] ?></td>
                            <td>
                                <?php
                                $fotoid = $data['fotoid'];
                                $sql2 = mysqli_query($conn, "SELECT * FROM likefoto WHERE fotoid='$fotoid'");
                                echo mysqli_num_rows($sql2);
                                ?>
                            </td>
                            <td>
                                <button class="btn btn-danger" onclick="confirmDelete(<?= $data['fotoid'] ?>)">Hapus</button>
                                <a href="edit_foto.php?fotoid=<?= $data['fotoid'] ?>" class="btn btn-info">Edit</a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function confirmDelete(fotoid) {
            var confirmation = confirm("Apakah Anda yakin ingin menghapus foto ini?");
            if (confirmation) {
                window.location.href = "hapus_foto.php?fotoid=" + fotoid;
            }
        }
    </script>

    <!-- Add Bootstrap JS and Popper.js scripts here -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>
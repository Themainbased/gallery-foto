<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("location:login.php");
    exit; // Stop further execution
}

include "koneksi.php";
$userid = $_SESSION['userid'];

// Pagination
$limit = 10; // Jumlah item per halaman
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1; // Default halaman pertama
}
$start = ($page - 1) * $limit;
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
    <!-- Sesi Crud -->
    <div class="container mt-5">
        <h1>Halaman Foto</h1>
        <p>Hai, <b> <?= $_SESSION['namalengkap'] ?></b></p>

        <!-- Form Tambah Foto -->
        <form action="tambah_foto.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
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
                <select class="form-control" name="albumid" id="albumid">
                    <option value="">-- Pilih Album --</option>
                    <?php
                    $sql_album = mysqli_query($conn, "SELECT * FROM album WHERE userid='$userid'");
                    while ($data_album = mysqli_fetch_array($sql_album)) {
                    ?>
                        <option value="<?= $data_album['albumid'] ?>"><?= $data_album['namaalbum'] ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Tambah">
            </div>
        </form>



        <!-- Tabel Foto -->
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
                    $sql_foto = mysqli_query($conn, "SELECT foto.*, album.namaalbum FROM foto LEFT JOIN album ON foto.albumid = album.albumid WHERE foto.userid='$userid' ORDER BY foto.fotoid DESC LIMIT $start, $limit");
                    while ($data_foto = mysqli_fetch_array($sql_foto)) {
                        $modifiedFotoid = PHP_INT_MAX - $data_foto['fotoid'] + 1; //Manipulate id from user
                    ?>
                        <tr>
                            <td><?= $modifiedFotoid ?></td>
                            <td><?= $data_foto['judulfoto'] ?></td>
                            <td><?= $data_foto['deskripsifoto'] ?></td>
                            <td><?= $data_foto['tanggalunggah'] ?></td>
                            <td><img src="gambar/<?= $data_foto['lokasifile'] ?>" width="200px" class="img-thumbnail"></td>
                            <td><?= isset($data_foto['namaalbum']) ? $data_foto['namaalbum'] : 'Tidak Ada Album' ?></td>
                            <td>
                                <?php
                                $fotoid = $data_foto['fotoid'];
                                $sql_likes = mysqli_query($conn, "SELECT * FROM likefoto WHERE fotoid='$fotoid'");
                                echo mysqli_num_rows($sql_likes);
                                ?>
                            </td>
                            <td>
                                <button class="btn btn-danger" onclick="confirmDelete(<?= $data_foto['fotoid'] ?>)">Hapus</button>
                                <a href="edit_foto.php?fotoid=<?= $data_foto['fotoid'] ?>" class="btn btn-info">Edit</a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php
                $result_pagination = mysqli_query($conn, "SELECT COUNT(*) AS total FROM foto WHERE userid='$userid'");
                $data_pagination = mysqli_fetch_assoc($result_pagination);
                $total_pages = ceil($data_pagination['total'] / $limit);

                // Previous button
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="foto.php?page=' . ($page - 1) . '">Previous</a></li>';
                }

                // Page numbers
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '"><a class="page-link" href="foto.php?page=' . $i . '">' . $i . '</a></li>';
                }

                // Next button
                if ($page < $total_pages) {
                    echo '<li class="page-item"><a class="page-link" href="foto.php?page=' . ($page + 1) . '">Next</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div>

    <!-- Add Bootstrap JS and Popper.js scripts here -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="resources/main.js"></script>
</body>


</html>
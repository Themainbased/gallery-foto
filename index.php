<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Landing</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Gallery Foto Ardi</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <?php
                session_start();
                if (!isset($_SESSION['userid'])) {
                ?>
                <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                <?php
                } else {
                ?>
                <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="album.php">Album</a></li>
                <li class="nav-item"><a class="nav-link" href="foto.php">Foto</a></li>
                <!-- User Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= $_SESSION['namalengkap'] ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <!-- Add additional dropdown items if needed -->
                        <a class="dropdown-item" href="profile.php">Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </div>
                </li>
                <?php
                }
                ?>
            </ul>
        </div>
    </nav>



    <!-- Your existing content here -->
    <div class=" container row mt-4">
        <?php
        include "koneksi.php";
        $sql = mysqli_query($conn, "select * from foto,user where foto.userid=user.userid");
        while ($data = mysqli_fetch_array($sql)) {
        ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="gambar/<?= $data['lokasifile'] ?>" class="card-img-top img-fluid"
                    alt="<?= $data['judulfoto'] ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= $data['judulfoto'] ?></h5>
                    <p class="card-text">Deskripsi : <?= $data['deskripsifoto'] ?></p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Karya: <?= $data['namalengkap'] ?></li>
                    <li class="list-group-item">Jumlah Like:
                        <?php
                            $fotoid = $data['fotoid'];
                            $sql2 = mysqli_query($conn, "select * from likefoto where fotoid='$fotoid'");
                            echo mysqli_num_rows($sql2);
                            ?>
                    </li>
                </ul>
                <div class="card-body">
                    <a href="like.php?fotoid=<?= $data['fotoid'] ?>" class="card-link">Like</a>
                    <a href="komentar.php?fotoid=<?= $data['fotoid'] ?>" class="card-link">Komentar</a>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
    </div>

    <!-- Add Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
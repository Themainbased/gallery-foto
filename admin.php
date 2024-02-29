<?php
// Mulai sesi
session_start();

// Periksa apakah pengguna sudah login dan memiliki peran admin
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'admin') {
    // Jika tidak, arahkan mereka kembali ke halaman login atau halaman lain
    header("Location: login.php");
    exit();
}

// Menghubungkan ke file koneksi.php
include 'koneksi.php';

// Inisialisasi pesan kesalahan
$errors = [];
$success_message = "";

// Cek apakah form telah di-submit untuk menghapus pengguna
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    // Validasi input ID pengguna yang akan dihapus
    $user_id = $_POST['userid'];
    if (empty($user_id)) {
        $errors[] = "ID pengguna harus diisi";
    }

    // Jika tidak ada kesalahan validasi, lanjutkan proses penghapusan
    if (empty($errors)) {
        // Melakukan query untuk menghapus pengguna dari database
        $query = "DELETE FROM user WHERE userid = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);

        // Eksekusi query
        if ($stmt->execute()) {
            $success_message = "Pengguna berhasil dihapus!";
        } else {
            $errors[] = "Gagal menghapus pengguna. Silakan coba lagi.";
        }
    }
}

// Cek apakah form telah di-submit untuk banned pengguna
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ban_user'])) {
    // Validasi input ID pengguna yang akan dibanned
    $banned_user_id = $_POST['userid'];
    if (empty($banned_user_id)) {
        $errors[] = "ID pengguna harus diisi";
    }

    // Jika tidak ada kesalahan validasi, lanjutkan proses banned
    if (empty($errors)) {
        // Tambahkan ID pengguna ke dalam file teks
        $banned_users_file = 'banned_users.txt';
        file_put_contents($banned_users_file, $banned_user_id . PHP_EOL, FILE_APPEND);

        $success_message = "Pengguna berhasil dibanned!";
    }
}

// Cek apakah form telah di-submit untuk unbanned pengguna
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['unban_user'])) {
    // Validasi input ID pengguna yang akan diunbanned
    $unbanned_user_id = $_POST['userid'];
    if (empty($unbanned_user_id)) {
        $errors[] = "ID pengguna harus diisi";
    }

    // Jika tidak ada kesalahan validasi, lanjutkan proses unbanned
    if (empty($errors)) {
        // Baca isi file banned_users.txt
        $banned_users_file = 'banned_users.txt';
        $banned_users = file($banned_users_file, FILE_IGNORE_NEW_LINES);

        // Hapus ID pengguna dari daftar banned
        $updated_banned_users = array_diff($banned_users, [$unbanned_user_id]);

        // Simpan kembali isi file banned_users.txt
        file_put_contents($banned_users_file, implode(PHP_EOL, $updated_banned_users));

        $success_message = "Pengguna berhasil diunbanned!";
    }
}

// Mengambil daftar pengguna dari database
$query_users = "SELECT userid, username, role FROM user";
$result_users = $conn->query($query_users);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Admin Panel</h2>
        <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error) : ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php if (!empty($success_message)) : ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_users->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row['userid']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['role']; ?></td>
                        <td>
                            <form method="post">
                                <form method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                    <input type="hidden" name="userid" value="<?php echo $row['userid']; ?>">
                                    <button type="submit" class="btn btn-danger" name="delete_user">Delete</button>
                                </form>

                            </form>
                            <form method="post" style="margin-top: 5px;">
                                <input type="hidden" name="userid" value="<?php echo $row['userid']; ?>">
                                <button type="submit" class="btn btn-warning" name="ban_user">Ban</button>
                            </form>
                            <form method="post" style="margin-top: 5px;">
                                <input type="hidden" name="userid" value="<?php echo $row['userid']; ?>">
                                <button type="submit" class="btn btn-success" name="unban_user">Unban</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="container">
            <a href="index.php" class="btn btn-outline-primary">back to menu</a>
        </div>
    </div>
</body>

</html>
<?php
include('koneksi.php');
include('proses_profile.php');


// Check if the user is already logged in
if (!isset($_SESSION['userid'])) {
    header("location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <title>Edit Profile</title>
</head>

<body>

    <div class="container">
        <?php
        // Display error messages if any
        if (!empty($errors)) {
            echo "<div class='alert alert-danger' role='alert'>";
            foreach ($errors as $error) {
                echo "<p>{$error}</p>";
            }
            echo "</div>";
        }
        ?>

        <br>

        <?php if (isset($userData['profile'])) : ?>
            <div class="form-group text-center">
                <img src="<?php echo $userData['profile']; ?>" alt="Profile Picture" width="150" class="rounded"><br>
                <small class="text-muted">Click 'Choose File' button to change profile picture</small>
            </div>
        <?php else : ?>
            <!-- Display upload button if profile picture doesn't exist -->
            <div class="form-group">
                <label for="profile_picture">Profile Picture:</label>
                <input type="file" class="form-control-file" id="profile_picture" name="profile_picture">
            </div>
        <?php endif; ?>

        <form method="POST" action="profile.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="profile_picture">Profile Picture:</label>
                <?php if (isset($userData['profile_picture']) && !empty($userData['profile_picture'])) : ?>
                    <img src="<?php echo $userData['profile_picture']; ?>" class="img-fluid mb-2" alt="Profile Picture">
                <?php endif; ?>
                <input type="file" class="form-control-file" id="profile_picture" name="profile_picture">
            </div>

            <div class="form-group">
                <label for="email">username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($userData['username']) ? htmlspecialchars($userData['username']) : ''; ?> ">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($userData['email']) ? htmlspecialchars($userData['email']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="namalengkap">Full Name:</label>
                <input type="text" class="form-control" id="namalengkap" name="namalengkap" value="<?php echo isset($userData['namalengkap']) ? htmlspecialchars($userData['namalengkap']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="alamat">Address:</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3"><?php echo isset($userData['alamat']) ? htmlspecialchars($userData['alamat']) : ''; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="foto.php" class="btn btn-danger">Back To Menu</a>
        </form>

    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>

</html>

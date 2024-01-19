<?php
session_start();
include('koneksi.php'); // Ensure this file includes database connection

// Check if the user is already logged in
if (!isset($_SESSION['userid'])) {
    header("location: login.php");
    exit();
}

// Get user data from the session
$userid = $_SESSION['userid'];
$currentUsername = $_SESSION['namalengkap'];

// Process data editing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ... (rest of your code)
    $newUsername = $_POST["username"];
    $newEmail = $_POST["email"];
    $newFullName = $_POST["namalengkap"];
    $newAddress = $_POST["alamat"];
    
    // Perform data update
    $updateQuery = "UPDATE user SET username=?, email=?, namalengkap=?, alamat=? WHERE userid=?";
    $stmt = $conn->prepare($updateQuery);

    if ($stmt === false) {
        die("Error in query: " . $conn->error);
    }

    $stmt->bind_param('ssssi', $newUsername, $newEmail, $newFullName, $newAddress, $userid);

    if ($stmt->execute()) {
        // Update session with new data
        $_SESSION['namalengkap'] = $newFullName;

        // Redirect to the profile page with a success message
        header("location: profile.php?success=1");
        exit();
    } else {
        // Failed to perform the update, add an error message
        $errors[] = "Failed to update. Please try again.";
    }

    // Close the statement
    $stmt->close();
}

// Get user data from the database to display in the form
$query = "SELECT * FROM user WHERE userid=?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die("Error in query: " . $conn->error);
}

$stmt->bind_param('i', $userid_param);
$userid_param = $userid;
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
} else {
    // Handle if user data is not found
    $errors[] = "User data not found.";
}

// Close the statement
$stmt->close();

// Display success message if any
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo "<p style='color: green;'>Data update successful!</p>";
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
        <h1>Edit Profile</h1>

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

        <form method="POST" action="profile.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username"
                    value="<?php echo htmlspecialchars($currentUsername); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="<?php echo isset($userData['email']) ? htmlspecialchars($userData['email']) : ''; ?>"
                    required>
            </div>
            <div class="form-group">
                <label for="namalengkap">Full Name:</label>
                <input type="text" class="form-control" id="namalengkap" name="namalengkap"
                    value="<?php echo isset($userData['namalengkap']) ? htmlspecialchars($userData['namalengkap']) : ''; ?>"
                    required>
            </div>
            <div class="form-group">
                <label for="alamat">Address:</label>
                <textarea class="form-control" id="alamat" name="alamat"
                    rows="3"><?php echo isset($userData['alamat']) ? htmlspecialchars($userData['alamat']) : ''; ?></textarea>
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
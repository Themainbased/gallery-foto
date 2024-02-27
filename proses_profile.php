<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    header("location: login.php");
    exit();
}

// Include database connection
include('koneksi.php');

// Process data editing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare variables
    $userid = $_SESSION['userid'];
    $newUsername = $_POST["username"];
    $newEmail = $_POST["email"];
    $newFullName = $_POST["namalengkap"];
    $newAddress = $_POST["alamat"];

    // Handle profile picture upload
    if(isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] == 0) {
        $targetDirectory = "profile/";
        $targetFile = $targetDirectory . basename($_FILES["profile_picture"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["profile_picture"]["size"] > 500000) {
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $uploadOk = 0;
        }

        if ($uploadOk == 1 && move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
            // Update user profile picture in the database
            $updateQuery = "UPDATE user SET username=?, email=?, namalengkap=?, alamat=?, profile=? WHERE userid=?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param('sssssi', $newUsername, $newEmail, $newFullName, $newAddress, $targetFile, $userid);
        } else {
            // Handle data update without profile picture
            $updateQuery = "UPDATE user SET username=?, email=?, namalengkap=?, alamat=? WHERE userid=?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param('ssssi', $newUsername, $newEmail, $newFullName, $newAddress, $userid);
        }
    } else {
        // Handle data update without profile picture
        $updateQuery = "UPDATE user SET username=?, email=?, namalengkap=?, alamat=? WHERE userid=?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param('ssssi', $newUsername, $newEmail, $newFullName, $newAddress, $userid);
    }

    if ($stmt === false) {
        die("Error in query: " . $conn->error);
    }

    if ($stmt->execute()) {
        // Update session with new data
        $_SESSION['namalengkap'] = $newFullName;
        $_SESSION['username'] = $newUsername;

        // Regenerate session ID for security
        session_regenerate_id(true);

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
$userid_param = $_SESSION['userid'];
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
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Data update successful!</strong>
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>&times;</span>
    </button>
  </div>";
}
?>

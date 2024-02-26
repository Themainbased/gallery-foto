<?php

// Start or resume the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    // Redirect to the login page or handle the case where the user is not logged in
    header("location: login.php");
    exit();
}

// Assuming you have established a database connection named $conn

// Get user data from the session
$userid = $_SESSION['userid'];

// Process data editing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare variables
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
        $_SESSION['username'] = $newUsername; // Update username in session
        
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
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Data update successful!</strong>
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>&times;</span>
    </button>
  </div>";
}

?>

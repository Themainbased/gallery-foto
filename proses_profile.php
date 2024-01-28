<?php

// Get user data from the session
$userid = $_SESSION['userid'];

// Process data editing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newEmail = $_POST["email"];
    $newFullName = $_POST["namalengkap"];
    $newAddress = $_POST["alamat"];

    // Perform data update
    $updateQuery = "UPDATE user SET email=?, namalengkap=?, alamat=? WHERE userid=?";
    $stmt = $conn->prepare($updateQuery);

    if ($stmt === false) {
        die("Error in query: " . $conn->error);
    }

    $stmt->bind_param('sssi', $newEmail, $newFullName, $newAddress, $userid);

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
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Data update successful!</strong>
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>&times;</span>
    </button>
  </div>";
}

?>
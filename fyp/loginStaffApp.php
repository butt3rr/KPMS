<?php
include "dbconn_login.php";

$staffID = $_POST['staffID'];
$password = $_POST['password'];

// Fetch hashed password from the database
$sql = "SELECT password FROM staff WHERE staffID = '$staffID'";
$response = mysqli_query($conn, $sql);

if($response) {
    // Check if the user exists
    if(mysqli_num_rows($response) === 1) {
        $row = mysqli_fetch_assoc($response);
        $hashedPasswordFromDB = $row['password'];

        // Verify the password
        if(password_verify($password, $hashedPasswordFromDB)) {
            $result['status'] = 'success';
        } else {
            $result['status'] = 'error';
        }
    } else {
        // User does not exist
        $result['status'] = 'error';
    }
} else {
    // Error in query
    $result['status'] = 'error';
}

echo json_encode($result);

mysqli_close($conn);
?>

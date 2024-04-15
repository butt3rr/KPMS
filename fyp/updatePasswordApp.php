<?php
include "dbconn_login.php";

// Get the new password and username or phone number from the POST request
$newPassword = $_POST['newPassword'];
$phoneNumberOrUsername = $_POST['phoneNumberOrUsername'];

// Prepare a SQL statement to update the password in the database
$sql = "UPDATE users SET password = ? WHERE username = ? OR phoneNum = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $newPassword, $phoneNumberOrUsername, $phoneNumberOrUsername);

// Execute the SQL statement
if ($stmt->execute()) {
    // Password updated successfully
    echo "Password updated successfully";
} else {
    // Error occurred while updating password
    echo "Error occurred while updating password";
}

// Close the statement
$stmt->close();

// Close the database connection
$conn->close();
?>

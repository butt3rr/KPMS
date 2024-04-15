<?php
include "dbconn_login.php";

// Get the phone number or username from the POST request
$phoneNumberOrUsername = $_POST['phoneNumberOrUsername'];

// Prepare a SQL query to check if the user exists
$sql = "SELECT * FROM users WHERE username = ? OR phoneNum = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $phoneNumberOrUsername, $phoneNumberOrUsername);

// Execute the query
$stmt->execute();

// Store the result
$result = $stmt->get_result();

// Check if there is at least one row returned
if ($result->num_rows > 0) {
    // User exists in the database
    echo "exists";
} else {
    // User does not exist in the database
    echo "Username or Phone Number does not exist";
}

// Close the statement
$stmt->close();

// Close the database connection
$conn->close();
?>

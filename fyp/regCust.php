<?php
include "dbconn_login.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $phoneNum = $_POST['phoneNum'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username or phone number already exists
    $checkStmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR phoneNum = ?");
    $checkStmt->bind_param("ss", $username, $phoneNum);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Username or phone number already exists
        echo "Username or Phone Number Already Exists. Please Choose Different Username or Phone Number";
    } else {
        // Username and phone number are unique, proceed with registration
        $insertStmt = $conn->prepare("INSERT INTO users (fullname, phoneNum, username, password) VALUES (?, ?, ?, ?)");
        $insertStmt->bind_param("ssss", $fullname, $phoneNum, $username, $password);

        if ($insertStmt->execute()) {
            echo "User Successfully Registered!";
        } else {
            echo "Failed to register user";
        }
    }
} else {
    echo "Invalid request method";
}
?>

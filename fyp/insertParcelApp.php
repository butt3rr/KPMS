<?php
include "dbconn_login.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from POST request
    $custName = $_POST['custName'];
    $custPhoneNum = $_POST['custPhoneNum'];
    $trackingNumber = $_POST['trackingNumber'];
    $courierName = $_POST['courierName'];

    // Check if trackingNumber already exists
    $checkStmt = $conn->prepare("SELECT trackingNumber FROM customer WHERE trackingNumber = ?");
    $checkStmt->bind_param("s", $trackingNumber);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // If trackingNumber exists, send a specific message
        echo "Error: Tracking number already exists!";
    } else {
        // If trackingNumber does not exist, proceed to insert data into database
        $stmt = $conn->prepare("INSERT INTO customer (custName, custPhoneNum, trackingNumber, courierName, status) VALUES (?, ?, ?, ?, ?)");
        // Set default status to "Unavailable"
        $defaultStatus = "0";
        $stmt->bind_param("sssss", $custName, $custPhoneNum, $trackingNumber, $courierName, $defaultStatus);
        
        if ($stmt->execute()) {
            echo "Form submitted successfully!";
        } else {
            echo "Form submission failed!";
        }

        $stmt->close();
    }

    $checkStmt->close();
    $conn->close();
} else {
    echo "Invalid request method!";
}
?>

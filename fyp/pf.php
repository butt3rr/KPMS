<?php
include "dbconn_login.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from POST request
    $receiverName = $_POST['receiverName'];
    $receiverPhoneNum = $_POST['receiverPhoneNum'];
    $trackingNumber = $_POST['trackingNumber'];

    // Check if the tracking number already exists
    $checkStmt = $conn->prepare("SELECT * FROM form WHERE trackingNumber = ?");
    $checkStmt->bind_param("s", $trackingNumber);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Tracking number already exists
        echo "Tracking number already exists. Please choose a different tracking number.";
    } else {
        // Tracking number is unique, proceed with insertion
        // Insert data into database
        $stmt = $conn->prepare("INSERT INTO form (receiverName, receiverPhoneNum, trackingNumber, status) VALUES (?, ?, ?, ?)");
        // Set default status to "Unavailable"
        $defaultStatus = "Unavailable";
        $stmt->bind_param("ssss", $receiverName, $receiverPhoneNum, $trackingNumber, $defaultStatus);
        
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

<?php
include "dbconn_login.php";

$trackingNumber = $_POST['trackingNumber'];
$custName = $_POST['custName'];
$custPhoneNum = $_POST['custPhoneNum'];
$courierName = $_POST['courierName'];
$oldTrackingNumber = $_POST['oldTrackingNumber'];

// Build the SQL query
$sql = "UPDATE customer SET ";
if (!empty($trackingNumber)) {
    $sql .= "trackingNumber='$trackingNumber', ";
}
if (!empty($custName)) {
    $sql .= "custName='$custName', ";
}
if (!empty($custPhoneNum)) {
    $sql .= "custPhoneNum='$custPhoneNum', ";
}
if (!empty($courierName)) {
    $sql .= "courierName='$courierName', ";
}
$sql = rtrim($sql, ", "); // Remove trailing comma
$sql .= " WHERE trackingNumber='$oldTrackingNumber'";

// Execute the SQL query
if (mysqli_query($conn, $sql)) {
    echo "Details updated successfully";
} else {
    echo "Error updating details: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
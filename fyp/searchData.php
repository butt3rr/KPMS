<?php
include "dbconn_login.php";

// Use the $_POST superglobal to access POST parameters
$trackingNumber = $_POST["trackingNumber"];
$st = $conn->prepare("SELECT * FROM customer WHERE trackingNumber = ?");
$st->bind_param("s", $trackingNumber);
$st->execute();
$rs = $st->get_result();

if ($rs->num_rows > 0) {
    $row = $rs->fetch_assoc();
    if ($row["status"] == 1) {
        echo "Claimed";
    } else {
        echo "Unclaimed";
    }
} else {
    echo "Tracking number not found.";
}
$conn->close();
?>

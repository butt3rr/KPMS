<?php
include "dbconn_login.php";

$fullName = $_POST['fullName'];
$username = $_POST['username'];
$phoneNum = $_POST['phoneNum'];


$sql = "UPDATE users SET fullName='$fullName', username='$username' WHERE phoneNum='$phoneNum'";


if (mysqli_query($conn, $sql)) {
    echo "Profile updated successfully";
} else {
    echo "Error updating profile: " . mysqli_error($conn);
}

mysqli_close($conn);
?>

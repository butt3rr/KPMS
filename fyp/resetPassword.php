<?php
include "dbconn_login.php";

$oldpassword = mysqli_real_escape_string($conn, $_POST['oldpassword']);
$newpassword = mysqli_real_escape_string($conn, $_POST['newpassword']);
$username = mysqli_real_escape_string($conn, $_POST['username']);

// Check if the old password is correct
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$oldpassword'";
$query = mysqli_query($conn, $sql);

if(mysqli_num_rows($query) == 1) {
    // Old password is correct, update to the new password
    $update = "UPDATE users SET password = '$newpassword' WHERE username = '$username'";
    $res = mysqli_query($conn, $update);
    
    if($res) {
        echo json_encode(array("success" => true, "message" => "Password Successfully Changed"));
    } else {
        echo json_encode(array("success" => false, "message" => "Error updating password"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Old Password Did Not Match"));
}

mysqli_close($conn);
?>
<?php
include "dbconn_login.php";

$username = $_POST['username'];
$password = $_POST['password'];

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT fullname, username, phoneNum FROM users WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$response = $stmt->get_result();

$result = array();

if($response->num_rows === 1) {
    $row = $response->fetch_assoc(); // Fetch user details
    $result['status'] = 'success';
    $result['data'] = array(
        'fullName' => $row['fullname'],
        'username' => $row['username'],
        'phoneNum' => $row['phoneNum'],
    );
    echo json_encode($result);
} else {
    $result['status'] = 'error';
    echo json_encode($result);
}

mysqli_close($conn);
?>






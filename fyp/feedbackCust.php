<?php
// Include database connection
include("dbconn_login.php");

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Check if form fields are set
if(isset($_POST['username']) && isset($_POST['feedback'])) {
    // Sanitize input
    $username = sanitizeInput($_POST['username']);
    $feedback = sanitizeInput($_POST['feedback']);
    
    // Check if an image is uploaded
    if(isset($_POST['image']) && !empty($_POST['image'])) {
        // Decode and save the image
        $imageData = $_POST['image'];
        $imagePath = 'images/'.date("d-m-Y").'-'.time().'-'.rand(10000, 100000). '.jpg';
        if(file_put_contents($imagePath, base64_decode($imageData))) {
            // Insert data into database
            $sql = "INSERT INTO feedback (username, feedback, media) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            if($stmt) {
                mysqli_stmt_bind_param($stmt, "sss", $username, $feedback, $imagePath);
                if(mysqli_stmt_execute($stmt)) {
                    echo 'success';
                } else {
                    echo 'Failed to insert to database';
                }
                mysqli_stmt_close($stmt);
            } else {
                echo 'Database error';
            }
        } else {
            echo 'Failed to upload image';
        }
    } else {
        // Insert data into database without image
        $sql = "INSERT INTO feedback (username, feedback) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $username, $feedback);
            if(mysqli_stmt_execute($stmt)) {
                echo 'success';
            } else {
                echo 'Failed to insert to database';
            }
            mysqli_stmt_close($stmt);
        } else {
            echo 'Database error';
        }
    }
} else {
    echo 'Incomplete form data';
}
?>

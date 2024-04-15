<?php
include "dbconn_login.php"; // Include your database connection file

if(isset($_GET['username'])) {
    $username = $_GET['username'];

    // Fetch feedback data including the media field
    $fetch_query = "SELECT feedback.*, users.phoneNum FROM feedback INNER JOIN users ON feedback.username = users.username WHERE feedback.username = ?";
    $stmt = mysqli_prepare($conn, $fetch_query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $feedback = $row['feedback'];
        $media = $row['media'];
        $phoneNum = $row['phoneNum'];

        // Output the feedback information
        echo "<p><strong>Username:</strong> $username</p>";
        echo "<p><strong>Feedback:</strong> $feedback</p>";
        echo "<p><strong>Media:</strong></p>";

        // Display the image if media field is not empty
        if(!empty($media)) {
            // Output the image tag with the correct path
            echo '<img src="'.$media.'" style="max-width: 100%;" />';
        } else {
            echo "No media attached.";
        }

        // Output phone number
        echo "<p>&nbsp;</p>";
        echo "<p><strong>Phone Number:</strong> $phoneNum</p>";
    } else {
        echo "No feedback found for this user.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo "Invalid request.";
}
?>

<?php

include "dbconn_login.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve phone number from the form
    $phoneNum = $_POST['phoneNum'];
    
    // Check if the phone number exists in the database
    $check_phone_query = "SELECT * FROM staff WHERE phoneNum = '$phoneNum'";
    $check_phone_result = mysqli_query($conn, $check_phone_query);
    
    if (mysqli_num_rows($check_phone_result) > 0) {
        // Generate temporary password
        $temporary_password = generateTemporaryPassword(); // Implement this function
        
        // Hash the temporary password
        $hashed_password = password_hash($temporary_password, PASSWORD_DEFAULT);
        
        // Update user's record with the hashed temporary password
        $update_query = "UPDATE staff SET password = '$hashed_password' WHERE phoneNum = '$phoneNum'";
        $update_result = mysqli_query($conn, $update_query);
        
        if ($update_result) {
            // Send the temporary password to the user (via SMS, WhatsApp, email, etc.)
            sendTemporaryPassword($phoneNum, $temporary_password); // Implement this function
            
            // Redirect the user to a confirmation page
            header("Location: loginStaff.php");
            exit;
        } else {
            echo "Error updating password. Please try again.";
        }
    } else {
		 echo " <script> alert('Phone number not found in the database.');
		document.location='forgotPassword.php'; </script> ";
     
    }
}

// Function to generate a random alphanumeric password
function generateTemporaryPassword() {
    $length = 8; // Length of the temporary password
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $temporary_password = '';

    for ($i = 0; $i < $length; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $temporary_password .= $characters[$index];
    }

    return $temporary_password;
}

// Function to send the temporary password to the user
function sendTemporaryPassword($phoneNum, $temporary_password) {
    $whatsapp_message = "Dear user,\n\nA temporary password has been generated for your KPMS account. Please use the following temporary password to log in:\n\nTemporary Password: *$temporary_password*\n\nFor security reasons, we recommend changing your password after logging in.\n\nThank you.";

    $whatsapp_link = "https://api.whatsapp.com/send?phone=+6$phoneNum&text=" . urlencode($whatsapp_message);
    
    // Open the WhatsApp link in a new tab
    echo "<script>window.open('$whatsapp_link', '_blank');</script>";
    
    // Redirect the current page to forgotPassword.php
    echo "<script>window.location.href = 'forgotPassword.php';</script>";
    exit;
}

?>

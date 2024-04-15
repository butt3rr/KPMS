<?php
include "dbconn_login.php"; // Make sure you have included your connection file

// Check if the delete parameter and staffID are set
if(isset($_GET['delete']) && isset($_GET['staffID'])) {
    $staffID = $_GET['staffID'];

    $delete = "DELETE FROM staff WHERE staffID = '" . mysqli_real_escape_string($conn, $staffID) . "'";
    $delete_run = mysqli_query($conn, $delete);
    
    // Check if data deleted successfully
    if($delete_run) {
        echo "<script> alert('Data Deleted Successfully!'); window.location.href='komasaStaff.php'; </script>";
    } else {
        echo "<script> alert('Data Failed to Delete!'); window.location.href='komasaStaff.php'; </script>";
    }
} else {
    // Invalid request
    echo "<script>alert('Invalid request.');</script>";
    // Redirect to the current page to refresh the staff list
    echo "<script>window.location.href = 'komasaStaff.php';</script>";
}

mysqli_close($conn);
?>

<?php
include "dbconn_login.php"; // Make sure you have included your connection file

// Check if the delete parameter and formID are set
if(isset($_GET['delete']) && isset($_GET['formID'])) {
    $formID = $_GET['formID'];

    $delete = "DELETE FROM form WHERE formID = '" . mysqli_real_escape_string($conn, $formID) . "'";
    $delete_run = mysqli_query($conn, $delete);
    
    // Check if data deleted successfully
    if($delete_run) {
        echo "<script> alert('Data Deleted Successfully!'); window.location.href='parcelForm.php'; </script>";
    } else {
        echo "<script> alert('Data Failed to Delete!'); window.location.href='parcelForm.php'; </script>";
    }
}

if (isset($_GET['delete']) && $_GET['delete'] == 'true' && isset($_GET['fbID'])) {
    $fbID = $_GET['fbID'];

    // Perform the deletion
    $delete_query = "DELETE FROM feedback WHERE fbID = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $fbID);
    mysqli_stmt_execute($stmt);

    // Check if deletion was successful
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        // Deletion successful
        echo "<script>alert('Feedback deleted successfully.');</script>";
        // Redirect to the current page to refresh the feedback list
        echo "<script>window.location.href = 'feedbackList.php';</script>";
    } else {
        // Deletion failed
        echo "<script>alert('Failed to delete feedback.');</script>";
        // Redirect to the current page to refresh the feedback list
        echo "<script>window.location.href = 'feedbackList.php';</script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    // Invalid request
    echo "<script>alert('Invalid request.');</script>";
    // Redirect to the current page to refresh the feedback list
    echo "<script>window.location.href = 'feedbackList.php';</script>";
}
?>

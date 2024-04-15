<?php
session_start();
include 'dbconn_login.php'; // Your database connection file

// Change password
if (isset($_POST["change_password"])) {
    $currentPassword = $_POST["current_password"];
    $newPassword = $_POST["new_password"];
    $confirmNewPassword = $_POST["confirm_new_password"];

    // Check if current password, new password, and confirm new password are not empty
    if (empty($currentPassword) || empty($newPassword) || empty($confirmNewPassword)) {
        echo "<script>alert('Fill in All Fields!'); window.location.href='myProfile.php';</script>";
        exit;
    }

    // Check if new password matches the confirm new password
    if ($newPassword !== $confirmNewPassword) {
        echo "<script>alert('New Password Does Not Match'); window.location.href='myProfile.php';</script>";
        exit;
    }

    $staffID = $_SESSION['staffID']; // Assuming you store staffID in session
    // Verify current password
    $stmt = $conn->prepare("SELECT password FROM staff WHERE staffID = ?");
    $stmt->bind_param("s", $staffID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row && password_verify($currentPassword, $row["password"])) {
        // Current password is correct, proceed with updating
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateStmt = $conn->prepare("UPDATE staff SET password = ? WHERE staffID = ?");
        $updateStmt->bind_param("si", $newPasswordHash, $staffID);
        if ($updateStmt->execute()) {
            echo "<script>alert('Password Updated Successfully!'); document.location='myProfile.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error Updating Password'); window.location.href='myProfile.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Current Password is Incorrect'); window.location.href='myProfile.php';</script>";
        exit();
    }
}

// Update staff details
if (isset($_POST["edit_all_fields"])) {
    $staffID = $_SESSION['staffID'];
    $newName = $_POST['new_name'];
    $newPhone = $_POST['new_phone'];

    // Update name
    $stmtName = $conn->prepare("UPDATE staff SET staffName = ? WHERE staffID = ?");
    $stmtName->bind_param("si", $newName, $staffID);
    $stmtName->execute();

    // Update phone number
    $stmtPhone = $conn->prepare("UPDATE staff SET phoneNum = ? WHERE staffID = ?");
    $stmtPhone->bind_param("si", $newPhone, $staffID);
    $stmtPhone->execute();

    echo "<script>alert('Data Updated Successfully!'); document.location='myProfile.php';</script>";
    exit();
}

// Add Staff
if (isset($_POST["add_staff"]) && $_SESSION['staffID'] == 10) {
    $staffName = $_POST['staffName'];
    $position = $_POST['position'];
    $phoneNum = $_POST['phoneNum'];

    // Check if phoneNumber is already in use
    $checkPhoneQuery = "SELECT phoneNum FROM staff WHERE phoneNum = ?";
    $checkPhoneStmt = $conn->prepare($checkPhoneQuery);
    
    if ($checkPhoneStmt) {
        $checkPhoneStmt->bind_param("s", $phoneNum);
        $checkPhoneStmt->execute();
        $checkPhoneStmt->store_result(); // Needed for num_rows check below

        if ($checkPhoneStmt->num_rows > 0) {
            echo "<script>alert('Phone Number is already in use.'); window.location.href='komasaStaff.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Error Preparing Statement for Phone Number Check'); window.location.href='komasaStaff.php';</script>";
        exit();
    }

    // Default password and status
    $defaultPassword = "1234";

    // Hash the default password
    $hashedPassword = password_hash($defaultPassword, PASSWORD_DEFAULT);

    // If phone number is not in use, proceed to insert new staff
    $stmt = $conn->prepare("INSERT INTO staff (staffName, position, phoneNum, password) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssss", $staffName, $position, $phoneNum, $hashedPassword);
        if ($stmt->execute()) {
            echo "<script>alert('New Staff Added Successfully!'); document.location='komasaStaff.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error Adding New Staff'); window.location.href='komasaStaff.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Error Preparing Statement'); window.location.href='komasaStaff.php';</script>";
        exit();
    }
}


$conn->close();
?>

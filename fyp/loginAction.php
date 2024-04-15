<?php 
session_start(); 
include "dbconn_login.php";

if (isset($_POST['staffID']) && isset($_POST['password'])) {

	function validate($data){
       $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}

	$staffID = validate($_POST['staffID']);
	$password = validate($_POST['password']);

	if (empty($staffID)) {
		header("Location: loginStaff.php?error=Staff ID is required");
	    exit();
	} elseif (empty($password)) {
        header("Location: loginStaff.php?error=Password is required");
	    exit();
	} else {
		$sql = "SELECT * FROM staff WHERE staffID='$staffID'";
		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) === 1) {
			$row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row['password'])) {
            	$_SESSION['staffID'] = $row['staffID'];
            	$_SESSION['staffName'] = $row['staffName'];
            	$_SESSION['position'] = $row['position'];
				$_SESSION['phoneNum'] = $row['phoneNum'];
            	header("Location: testCrud.php");
		        exit();
            } else {
				header("Location: loginStaff.php?error=Incorrect password");
		        exit();
			}
		} else {
			header("Location: loginStaff.php?error=Incorrect staff ID");
	        exit();
		}
	}
	
} else {
	header("Location: loginStaff.php");
	exit();
}
?>

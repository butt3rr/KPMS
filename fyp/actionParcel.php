<?php
include "dbconn_login.php";

	if (isset($_POST['submit'])) {
    $trackingNumber = $_POST['trackingNumber'];
    $custName = $_POST['custName'];
    $custPhoneNum = $_POST['custPhoneNum'];
    $courierName = $_POST['courierName'];
    $status = $_POST['status'];
    $recipientPhoneNum = $_POST['recipientPhoneNum'];

    // Check if the tracking number already exists in the database
    $check_query = "SELECT * FROM customer WHERE trackingNumber = '$trackingNumber'";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Tracking Number Already Exists! Please Fill in Details Again'); window.location.href='testCrud.php';</script>";
        exit(); // Exit the script to prevent further execution
    }

    // Insert data into the database
    $insert = "INSERT INTO customer(trackingNumber, custName, custPhoneNum, courierName, status) VALUES ('$trackingNumber', '$custName', '$custPhoneNum', '$courierName', '$status')";

    $insert_run = mysqli_query($conn, $insert);

    // Check if data insert was successful
    if ($insert_run) {
        // Display alert message using JavaScript
        echo " <script> alert('Parcel Added Successfully!');
		document.location='testCrud.php'; </script> ";
    } else {
        $_SESSION['status'] = "Data Failed to Insert";
        header('location: testCrud.php');
    }
}
if (isset($_POST['edit'])) {
    // Step 1: Check if the tracking number exists (excluding the current record).
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM customer WHERE trackingNumber = ? AND custID != ?");
    $checkStmt->bind_param("si", $_POST['trackingNumber'], $_POST['custID']);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();
    
    if ($count > 0) {
        // If the tracking number already exists for another record.
        echo "<script>alert('Tracking Number already exists in the system.'); document.location='testCrud.php';</script>";
    } else {
        // Step 2: If it doesn't exist, update the record.
        $stmt = $conn->prepare("UPDATE customer SET trackingNumber=?, custName=?, custPhoneNum=?, courierName=?, status=? WHERE custID=?");
        $stmt->bind_param("sssssi", $_POST['trackingNumber'], $_POST['custName'], $_POST['custPhoneNum'], $_POST['courierName'], $_POST['status'], $_POST['custID']);
        
        if ($stmt->execute()) {
            echo "<script>alert('Data Updated Successfully!'); document.location='testCrud.php';</script>";
        } else {
            echo "<script>alert('Data Failed to Update!'); document.location='testCrud.php';</script>";
        }
        $stmt->close();
    }
}

//else {
    // Redirect back or show an error message if `edit` is not set
   // echo "<script>alert('Invalid request!'); window.location.href='testCrud.php';</script>";
//}

if(isset($_POST['delete']))
{
	
	$delete = "DELETE FROM customer WHERE custID = '$_POST[custID]'  ";
	
	
	$delete_run = mysqli_query($conn, $delete);
	
	//check if data delete successfully
	if($delete_run)
	{
		echo " <script> alert('Data Deleted Successfully!');
		document.location='testCrud.php'; </script> ";
	}
	else
	{
		echo " <script> alert('Data Failed to Delete!');
		document.location='testCrud.php'; </script> ";
	}
}

	if(isset($_POST['verify'])) {
$trackingNumber = isset($_POST['trackingNumber']) ? $_POST['trackingNumber'] : '';
$recipientPhoneNum = isset($_POST['recipientPhoneNum']) ? $_POST['recipientPhoneNum'] : '';

if (!empty($trackingNumber) && !empty($recipientPhoneNum)) {
    // Validate the tracking number exists in the 'customer' table
    $stmt = $conn->prepare("SELECT * FROM customer WHERE trackingNumber = ?");
    $stmt->bind_param("s", $trackingNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $stmt = $conn->prepare("INSERT INTO parcelclaim (trackingNumber, recipientPhoneNum) VALUES (?, ?)");
        $stmt->bind_param("ss", $trackingNumber, $recipientPhoneNum);

        if ($stmt->execute()) {
            $updateStmt = $conn->prepare("UPDATE customer SET status = 1 WHERE trackingNumber = ?");
            $updateStmt->bind_param("s", $trackingNumber);
            $updateStmt->execute();
			
            echo " <script> alert('Parcel Verified Successfully!');
		document.location='testCrud.php'; </script> ";
        } else {
            echo " <script> alert('Parcel Failed to Verify!');
		document.location='testCrud.php'; </script> ";
        }
    } else {
        echo "The tracking number does not exist.";
    }
    $stmt->close();
} else {
    echo "Tracking Number and Phone Number are required.";
}

	}
	
//view parcel 
if(isset($_GET['trackingNumber'])) {
    $trackingNumber = $_GET['trackingNumber'];

    // Query to fetch details from parcelclaim based on tracking number
    $query = "SELECT * FROM parcelclaim WHERE trackingNumber = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $trackingNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Output parcel details as HTML (you can customize this as needed)
        echo "<p>Tracking Number: {$row['trackingNumber']}</p>";
        echo "<p>Phone Number: {$row['recipientPhoneNum']}</p>";
		echo "<p>Time of Claim: {$row['timeClaim']}</p>";
    } else {
        echo "No details found for this tracking number.";
    }
} else {
    echo "Tracking number parameter is missing.";
}

?>
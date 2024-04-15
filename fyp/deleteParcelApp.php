<?php
include "dbconn_login.php";

$trackingNumber = $_POST['trackingNumber'];

$query = "SELECT * FROM customer WHERE trackingNumber = '$trackingNumber'";
$check = mysqli_query($conn, $query);
$result = array();
if (mysqli_num_rows($check) === 1) {
	$sql = "DELETE FROM customer WHERE trackingNumber = '$trackingNumber'";
	if(mysqli_query($conn, $sql)) {
		$result['state'] = 'delete';
		echo json_encode($result);
	} else{
		echo "Parcel details deleted";
	}
} else{
	echo "Invalid Parcel or Deleted Parcel";
}

?>
<?php 
include "dbconn_login.php";

$sql = "SELECT trackingNumber, custName, custPhoneNum, courierName FROM customer WHERE status='0'";
$result = mysqli_query($conn, $sql);
$history = array();

while($row = mysqli_fetch_assoc($result)) {
	$index['trackingNumber'] = $row['trackingNumber'];
	$index['custName'] = $row['custName'];
	$index['custPhoneNum'] = $row['custPhoneNum'];
	$index['courierName'] = $row['courierName'];
	array_push($history, $index);
}
echo json_encode($history);

?>
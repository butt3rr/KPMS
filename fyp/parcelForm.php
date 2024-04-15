
<!doctype html>

<html><!-- InstanceBegin template="/Templates/template.dwt" codeOutsideHTMLIsLocked="true" -->
<head>

<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" >
<link rel="icon" type="image/png" href="favicon.ico"/>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Parcel Form</title>
<!-- InstanceEndEditable -->
<link rel="stylesheet" href="css_template.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<!-- InstanceBeginEditable name="head" -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
	
<!--	 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css"> -->
<!-- InstanceEndEditable -->
</head>

<body>
	
	<?php 

session_start();
include "dbconn_login.php";
include "function.php";

// SQL to count rows with status "ready to collect"
$countSql = "SELECT COUNT(*) AS readyToCollectCount FROM form WHERE status = 'Ready to Collect'";
$result = $conn->query($countSql);
$row = $result->fetch_assoc();
$readyToCollectCount = $row['readyToCollectCount'];

if (!isset($_SESSION['staffID'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Get staff details
$staffID = $_SESSION['staffID'];
$query = "SELECT * FROM staff WHERE staffID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $staffID);
$stmt->execute();
$result = $stmt->get_result();

// Check if staff details are retrieved
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $staffName = $row['staffName'];
    $position = $row['position'];
    $phoneNum = $row['phoneNum'];
	
} else {
    // Redirect to login page if staff details not found
    header("Location: loginStaff.php");
    exit();
}


?>

	<div class="sidenav">
  <a href="testCrud.php"><i class="bi bi-box-seam-fill"></i>&nbsp;Manage Parcel&nbsp;</a> <hr>
  <a href="parcelForm.php"><i class="bi bi-file-earmark-check-fill"></i>&nbsp;Customer Parcel Form <span class="badge rounded-pill bg-info"><?php echo $readyToCollectCount; ?></span> </a> <hr>
<a href="report.php"><i class="bi bi-table"></i>&nbsp;View Report&nbsp;</a> <hr>
  <a href="feedbackList.php"><i class="bi bi-chat-left-text-fill"></i>&nbsp;Customer Feedback&nbsp;</a> <hr>
		<?php
  // Check if staffID is set in the session and equals 1
  if (isset($_SESSION['staffID']) && $_SESSION['staffID'] == 10) {
      // Display the form for staffID = 1
  ?>
	    <a href="komasaStaff.php"><i class="bi bi-people-fill"></i>&nbsp;KOMASA Staff&nbsp;</a> <hr>
<?php
  } ?>
		
		

  <a href="myProfile.php"><i class="bi bi-person-fill"></i>&nbsp;My Account&nbsp;</a>
</div>

<div class="main">
	&nbsp;



	<span>
<nav >
		 <ul>
            <li><img src="uptmLogo.png" width="160" height="75" alt="uptm logo"/></li>
            <li><img src="komasa.png" width="145" height="70" alt="komasa logo"/>&nbsp;</li>
            <li> <div class="nav-center">
                <a href="testCrud.php">KOMASA PARCEL MANAGEMENT SYSTEM</a>
            </div> </li>&nbsp;
			 &nbsp;&nbsp;
            <div class="staff-info">
                <li><a class="active" href="myProfile.php"><i class="bi bi-person-fill"></i>&nbsp;<?php echo $_SESSION['staffName']; ?></a></li>
                <li><a class="logout" href="logoutStaff.php"><i class="bi bi-box-arrow-right"></i>&nbsp;LOG OUT</a></li>
            </div>
        </ul>
	</nav>
		</span>
	<span> </span>

	<hr>
	<div class="content">
	<!-- InstanceBeginEditable name="content" -->
	<?php

// Retrieve data from the first table 
$sql1 = "SELECT trackingNumber,status FROM customer"; 
$result1 = $conn->query($sql1); 
 
// Retrieve data from the second table 
$sql2 = "SELECT trackingNumber FROM form"; 
$result2 = $conn->query($sql2); 

// Convert query results to arrays 
$data1 = array();
while ($row = $result1->fetch_assoc()) {
    // Store tracking numbers and status in lowercase
    $data1[strtolower($row['trackingNumber'])] = $row['status'];
} 
 
$data2 = array(); 
while ($row = $result2->fetch_assoc()) { 
    $data2[] = strtolower($row['trackingNumber']); // Store only the tracking numbers in lowercase
} 

//Initialize counter for updated rows
$updatedRows = 0; 

// Update status based on tracking number presence and status in customer table
foreach ($data2 as $trackingNumber) {
    if (array_key_exists($trackingNumber, $data1)) {
        $status = $data1[$trackingNumber];
        if ($status == 1) {
            $updateSql = "UPDATE form SET status = 'Claimed' WHERE LOWER(trackingNumber) = '" . $conn->real_escape_string($trackingNumber) . "'";
        } else {
            $updateSql = "UPDATE form SET status = 'Ready to Collect' WHERE LOWER(trackingNumber) = '" . $conn->real_escape_string($trackingNumber) . "'";
        }
    } else {
        // Tracking number not found in customer table, mark as Unavailable
        $updateSql = "UPDATE form SET status = 'Unavailable' WHERE LOWER(trackingNumber) = '" . $conn->real_escape_string($trackingNumber) . "'";
    }
    
    if ($conn->query($updateSql)) {
        $updatedRows += $conn->affected_rows;
    }
}
?>

<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">LIST OF PARCEL FORMS</h4>
                    <br>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover " id="dataTable">
                            <thead>
                                <tr>
                                    <th>Form ID</th>
                                    <th>Tracking Number</th>
                                    <th>Customer Name</th>
                                    <th>Phone Number</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //read all row from db
                                $sql = "SELECT * FROM form";
                                $result = mysqli_query($conn, $sql);
                                
                                if(!$result){
                                    die("Invalid query:" . $conn->error);
                                }
                                
                                if(mysqli_num_rows($result) > 0)
                                {
                                    foreach($result as $rows)
                                    {
                                        ?>
                                        <?php 
                                        $phone = $rows['receiverPhoneNum']; // Assume this contains the receiver's phone number
                                        $message = "Dear {$rows['receiverName']},\n\nYour parcel with tracking number {$rows['trackingNumber']} has arrived at KOMASA. You can collect your parcel at UPTM KOMASA, Level 4, at your earliest convenience. Thank you.";
                                        $encodedMessage = urlencode($message);
                                        $whatsAppLink = "https://api.whatsapp.com/send?phone=+6{$phone}&text={$encodedMessage}";
                                        ?>
                                        
                                        <tr>
                                            <td><?= $rows['formID']; ?></td>
                                            <td><?= $rows['trackingNumber']; ?></td>
                                            <td><?= $rows['receiverName']; ?></td>
                                            <td>
                                                <a href="<?php echo $whatsAppLink; ?>" target="_blank" class="btn btn-success btn-sm">
                                                    <i class="bi bi-whatsapp"></i> <?php echo $rows['receiverPhoneNum']; ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?php 
                                                // Apply green font and bold for "Ready to Collect" status
                                                if ($rows['status'] == 'Ready to Collect') {
                                                    echo '<span style="color: green; font-weight: bold;">' . $rows['status'] . '</span>';
                                                } elseif ($rows['status'] == 'Unavailable') {
                                                    echo '<span class="text-danger">' . $rows['status'] . '</span>'; // Red font for "Unavailable"
                                                } else {
                                                    echo $rows['status']; // Default status display
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <button onclick="confirmDeletion(<?= $rows['formID']; ?>)" class="btn btn-danger btn-sm"><i class="bi bi-trash3"></i></button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }  
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
	
	<script src="https://code.jquery.com/jquery-3.7.0.js"></script> 
	<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
	
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


	
	<script>
		$(function(){
			new DataTable('#dataTable');
		});
	</script>
	
	<script>
    function confirmDeletion(formID) {
        var confirmAction = confirm("Are you sure you want to delete this record?");
        if (confirmAction) {
            // User confirmed deletion, proceed with redirection
            window.location.href = 'actionForm.php?delete=true&formID=' + formID;
        } else {
            // User cancelled, do nothing
            console.log('Deletion cancelled');
        }
    }
</script>

			


	<!-- InstanceEndEditable --></div>
   

</body>
	
<!--<footer>
	<hr>
		<p>&copy; 2024 KPMS Final Year Project. All rights reserved.</p>
	</footer> -->
<!-- InstanceEnd --></html>



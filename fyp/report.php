
<!doctype html>

<html><!-- InstanceBegin template="/Templates/template.dwt" codeOutsideHTMLIsLocked="true" -->
<head>

<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" >
<link rel="icon" type="image/png" href="favicon.ico"/>
<!-- InstanceBeginEditable name="doctitle" -->
<title>View Report</title>
<!-- InstanceEndEditable -->
<link rel="stylesheet" href="css_template.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<!-- InstanceBeginEditable name="head" -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


<!-- Bootstrap 5 icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	
<!-- Bootstrap data Table -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
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
// Initialize variables to store form input
$start_date = $end_date = '';
$statusMessage = ''; // Initialize status message

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve start date, end date, and status from form submission
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    // Format the start date and end date in "dd-mm-yyyy" format
    $start_date_formatted = date("d-m-Y", strtotime($start_date));
    $end_date_formatted = date("d-m-Y", strtotime($end_date));

    $statusValue = ($status == 'claimed') ? 1 : (($status == 'unclaimed') ? 0 : '');

    // Construct the SQL query based on the selected status
    $query = "SELECT * FROM customer WHERE dateTimeParcel BETWEEN '$start_date' AND '$end_date'";
    if ($statusValue !== '') {
        $query .= " AND status = '$statusValue'";
    }

    // Execute the query
    $result = mysqli_query($conn, $query);

    // Check if query was successful
    if (!$result) {
        // Handle query error
        echo "Error: " . mysqli_error($conn);
        $result = null; // Ensure no further processing happens with $result
    } elseif (mysqli_num_rows($result) > 0) {
        $numRows = mysqli_num_rows($result);
        if (!empty($status)) {
            $statusMessage = "Total $numRows ". ($status == 'claimed' ? 'claimed' : 'unclaimed') ." parcels from $start_date_formatted to $end_date_formatted";
        } else {
            $statusMessage = "Total $numRows parcels (claimed and unclaimed) from $start_date_formatted to $end_date_formatted";
        }
    } else {
        if (!empty($status)) {
            $statusMessage = "No " . ($status == 'claimed' ? 'claimed' : 'unclaimed') . " parcels found from $start_date_formatted to $end_date_formatted.";
        } else {
            $statusMessage = "No data found from $start_date_formatted to $end_date_formatted.";
        }
    }
}
?>

				<div class="container-md">
			<div class="edit-box">
        <h4 class="mt-1 mb-3">VIEW REPORT</h4>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
			    <div class="form-row">
			      <div class="form-group col-md-6">
			        <label for="start_date">Start Date:</label>
			        <input type="date" class="form-control" id="start_date" name="start_date" required>
			      </div>
			      <div class="form-group col-md-6">
			        <label for="end_date">End Date:</label>
			        <input type="date" class="form-control" id="end_date" name="end_date" required>
			      </div>
					
			   <div class="form-group has-feedback col-md-6">
        <label for="status">Status:</label>
        <div class="position-relative">
            <select class="form-control" id="status" name="status">
                <option value="">Claimed & Unclaimed</option>
                <option value="claimed">Claimed</option>
                <option value="unclaimed">Unclaimed</option>
            </select>
            <i class="bi bi-caret-down-fill position-absolute top-50 end-0 translate-middle-y pe-2"></i>
        </div>
    </div>
			    </div>
			<br>
			    <button type="submit" class="btn btn-primary"><i class="bi bi-filter"></i>&nbsp;Filter Data</button>
				          <button onclick="printDiv('printableArea')" class="btn btn-info"><i class="bi bi-printer"></i>&nbsp;Print</button>
			  </form>
				</div>
    </div>

			
			
		
	<?php
if ($result && mysqli_num_rows($result) > 0) {
    echo "	<div class='container-md'>
			<div class='edit-box'>
	<div id='printableArea' class='text-success'><div><strong>$statusMessage</strong></div> <br>";
    echo "<table class='table table-bordered'><thead>
            <tr>
                <th>Date/Time</th>
                <th>Tracking Number</th>
            </tr>
          </thead><tbody>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['dateTimeParcel'] . "</td>";
        echo "<td>" . $row['trackingNumber'] . "</td>";
        echo "</tr>";
    }
    echo "</tbody></table></div>";
} elseif (empty($statusMessage)) {
    echo "No data found.";
} else {
    echo "<div id='printableArea'><div><strong>$statusMessage</strong></div></div>
	</div> </div>";
}
?>
	

		</div>
	</div>
		
			
	<script src="https://code.jquery.com/jquery-3.7.0.js"></script> 
	<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
	
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	
<script>
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    // Create a new window to print the table content
    var printWindow = window.open('', '_blank');
    printWindow.document.open();
    // Write the table content with additional styling for printing
    printWindow.document.write('<html><head><title>Print Table</title><style>@media print {table {width: 100%; border-collapse: collapse;} th, td {border: 1px solid black; padding: 8px;}}</style></head><body>' + printContents + '</body></html>');
    printWindow.document.close();

    // Call the print function after content is loaded
    printWindow.print();

    // Restore the original content
    document.body.innerHTML = originalContents;
}
</script>
	<!-- InstanceEndEditable --></div>
   

</body>
	
<!--<footer>
	<hr>
		<p>&copy; 2024 KPMS Final Year Project. All rights reserved.</p>
	</footer> -->
<!-- InstanceEnd --></html>



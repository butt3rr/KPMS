
<!doctype html>

<html><!-- InstanceBegin template="/Templates/template.dwt" codeOutsideHTMLIsLocked="true" -->
<head>

<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" >
<link rel="icon" type="image/png" href="favicon.ico"/>
<!-- InstanceBeginEditable name="doctitle" -->
<title>My Profile</title>
<!-- InstanceEndEditable -->
<link rel="stylesheet" href="css_template.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<!-- InstanceBeginEditable name="head" -->
	<link rel="stylesheet" href="css_template.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
		// Check if user is logged in
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

$stmt->close();

?>
				<div class="container-md">
			<div class="edit-box">
        <h4 class="mt-1 mb-3">STAFF DETAILS</h4>
   <?php
    // Check if user is logged in and get their staffID
    //session_start();
    if (isset($_SESSION['staffID'])) {
        $staffID = $_SESSION['staffID'];

        // Display profile information

        // Get staff details based on staffID
        $query = "SELECT * FROM staff WHERE staffID = '$staffID'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $staffName = $row['staffName'];
            echo "<p class='px-4 py-2'>Name: " . $staffName . " </p>";
            echo "<p class='px-4 py-2'>Position: " . $row['position'] . "</p>";
            echo "<p class='px-4 py-2'>Phone Number: " . $row['phoneNum'] . "</p>";
        }
		
    }
    ?>
	  <a href="editProfile.php" class="btn btn-primary"><i class="bi bi-pencil-square"></i>&nbsp;Edit Profile</a>
	  <a href="changePassword.php" class="btn btn-primary"> <i class="bi bi-key"></i>&nbsp;Change Password</a>
				</div>
    </div>
		

	
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

	<!-- InstanceEndEditable --></div>
   

</body>
	
<!--<footer>
	<hr>
		<p>&copy; 2024 KPMS Final Year Project. All rights reserved.</p>
	</footer> -->
<!-- InstanceEnd --></html>



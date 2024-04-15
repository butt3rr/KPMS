
<!doctype html>

<html><!-- InstanceBegin template="/Templates/template.dwt" codeOutsideHTMLIsLocked="true" -->
<head>

<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" >
<link rel="icon" type="image/png" href="favicon.ico"/>
<!-- InstanceBeginEditable name="doctitle" -->
<title>KOMASA Staff</title>
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
	
    <div class="container mt-3">
	 <div class="row justify-content-center">
		 <div class="col-md-12">
			 <?php
			 if(isset($_SESSION['status']) && $_SESSION['status'] !='')
			 {	 
			?>
		<!--	 <div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>THANK YOU</strong> <?php echo $_SESSION['status']; ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div> -->
			<?php 
				 unset($_SESSION['status']);
			 }
			 ?>
			 
			 <div class="card">
				 <div class="card-header">
					 <h4 class="text-center">LIST OF KOMASA STAFF</h4>
					 <?php
					 if (isset($_SESSION['staffID']) && $_SESSION['staffID'] == 10) {
      // Display the form for staffID = 1
  ?>
	  <a href="addNewStaff.php" class="btn btn-primary"> <i class="bi bi-person-plus"></i>&nbsp;Add New Staff</a>
<?php
  } ?>
  
				 </div>
				 <div class="card-body">
					 <div class="table-responsive">
					 <table class="table table-striped table-bordered table-hover " id="dataTable">
  <thead>
    <tr>
      <th scope="col">Staff ID</th>
      <th scope="col">Staff Name</th>
	  <th scope="col">Position</th>
      <th scope="col">Phone Number</th>
	  <th scope="col">Date Created</th>
	  <th scope="col">Action</th>
	  
		
      
    </tr>
  </thead>
  <tbody>
	  <?php
	 // $custID=1;
	  $fetch_query = "SELECT * FROM staff";
	  $fetch_query_run = mysqli_query($conn, $fetch_query);
	  
	  if(mysqli_num_rows($fetch_query_run) > 0)
	  {
		  while($row = mysqli_fetch_array($fetch_query_run))
		  {
	 ?>
	  <tr>
      <td><?php echo $row['staffID'] ?></td>
	  <td><?php echo $row['staffName']; ?></td>
	  <td><?php echo $row['position']; ?></td>
	  <td><?php echo $row['phoneNum']; ?></td>
	  <td><?php echo date('d-m-Y', strtotime($row['dateCreated'])); ?></td>
	  <td><?php 
    echo '<button onclick="confirmDeletion(' . $row['staffID'] . ')" class="delete-btn btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>';

    ?></td> 
    </tr> 
	   <?php
			  
		  }
		  
		  
	  }
	  else
	  {
		  ?>
	  <tr colspan="5">NO RECORDS FOUND</tr>
	  <?php
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
	
	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
	
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<!--	<script src="app.js"></script> -->
	<script src="https://code.jquery.com/jquery-3.7.0.js"></script> 
	<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
	
	<script>
		$(function(){
			new DataTable('#dataTable');
		});
		
        function confirmDeletion(staffID) {
    var confirmAction = confirm("Are you sure you want to delete this record?");
    if (confirmAction) {
        // Proceed with redirection to actionStaff.php for database deletion
        window.location.href = 'actionStaff.php?delete=true&staffID=' + staffID;
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



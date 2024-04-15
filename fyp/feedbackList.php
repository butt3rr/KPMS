
<!doctype html>

<html><!-- InstanceBegin template="/Templates/template.dwt" codeOutsideHTMLIsLocked="true" -->
<head>

<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" >
<link rel="icon" type="image/png" href="favicon.ico"/>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Customer Feedback</title>
<!-- InstanceEndEditable -->
<link rel="stylesheet" href="css_template.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<!-- InstanceBeginEditable name="head" -->
<!-- Bootstrap 5-->
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
			<!-- <div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>HELLO!</strong> <?php echo $_SESSION['status']; ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div> -->
			<?php 
				 unset($_SESSION['status']);
			 }
			 ?>
			 
			 <div class="card">
				 <div class="card-header">
					 <h4 class="text-center">CUSTOMER FEEDBACK</h4>
				 </div>

				 <div class="card-body">
					 <div class="table-responsive">
					 <table class="table table-striped table-bordered table-hover " id="dataTable">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Username</th>
	  <th scope="col">Phone Number</th>
	  <th scope="col">Feedback</th>
      <th scope="col">Media (if any)</th>
		<th scope="col">Date/Time</th>
	  <th scope="col">Action</th>
	 
    </tr>
  </thead>
  <tbody>
	  <?php
	  $fbID=1;
	  $fetch_query = "SELECT feedback.*, users.phoneNum FROM feedback INNER JOIN users ON feedback.username = users.username";
          $fetch_query_run = mysqli_query($conn, $fetch_query);
	  
	  if(mysqli_num_rows($fetch_query_run) > 0)
	  {
		  while($row = mysqli_fetch_array($fetch_query_run))
		  {
	 ?>
	  <tr>
      <td><?= $fbID++ ?></td>
	  <td><?php echo $row['username']; ?></td>
	
		<?php  $phoneNumber = $row['phoneNum']; // Phone number from the database
// Output the phone number with a WhatsApp link
echo "<td><a href='https://wa.me/$phoneNumber' target='_blank' class='btn btn-success btn-sm'><i class='bi bi-whatsapp'></i>&nbsp;$phoneNumber</a></td>"; ?>
		  
		<?php  $fullFeedback = $row['feedback']; // Assume this contains the full feedback text
$feedbackLength = 100; // Number of characters to display before "View More"

// Check if feedback length is more than $feedbackLength characters
if (strlen($fullFeedback) > $feedbackLength) {
    $shortFeedback = substr($fullFeedback, 0, $feedbackLength) . '...'; // Shorten feedback
    echo "<td>
            <span class='feedback-preview'>$shortFeedback</span>
            <span class='feedback-full' style='display:none;'>$fullFeedback</span>
            <button class='view-more-btn btn btn-link'>View More</button>
          </td>";
} else {
    // If feedback is short enough, just display it
    echo "<td>$fullFeedback</td>"; 
}?>
		  
<?php $imageURL = $row['media']; // URL of the image

// Output the image with a class for styling and data-attribute to hold the image URL
echo "<td><img src='$imageURL' class='clickable-image' data-image-url='$imageURL' style='width: 100px; cursor: pointer;'></td>"; ?>
		  
		 <td><?php echo $row['dateTime']; ?></td>
		  <td><?php 
    echo '<button onclick="confirmDeletion(' . $row['fbID'] . ')" class="delete-btn btn btn-danger btn-sm" data-username="' . $row['username'] . '"><i class="bi bi-trash"></i></button>'; 
    ?></td>
    </tr> 
	  
<!-- Modal -->
	 <!-- Modal for displaying larger image -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">View Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" alt="Enlarged Image" style="max-width: 100%; max-height: 80vh;">
            </div>
        </div>
    </div>
</div>



	 
	  
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
	</script>
	
<script>
$(document).ready(function() {
    $('.view-btn').click(function() {
        var username = $(this).data('username');
        
        $.ajax({
            url: 'actionFeedback.php',
            type: 'GET',
            data: {username: username},
            success: function(response) {
                // Populate modal with response data
                $('#feedback-modal-body').html(response);
                $('#feedbackDetailsModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});

</script>
		
<script>
    function confirmDeletion(fbID) {
        var confirmAction = confirm("Are you sure you want to delete this record?");
        if (confirmAction) {
            // User confirmed deletion, proceed with redirection to actionForm.php
            window.location.href = 'actionForm.php?delete=true&fbID=' + fbID;
        } else {
            // User cancelled, do nothing
            console.log('Deletion cancelled');
        }
    }
</script>
		
		<script>
$(document).ready(function() {
    $('body').on('click', '.view-more-btn', function() {
        // Toggle visibility of feedback spans
        $(this).siblings('.feedback-preview, .feedback-full').toggle();
        
        // Update button text
        if ($(this).text() === 'View More') {
            $(this).text('View Less');
        } else {
            $(this).text('View More');
        }
    });
});
</script>
		
<script>
$(document).ready(function() {
    // Use delegated event handling for dynamic elements
    $(document).on('click', '.clickable-image', function() {
        var imageURL = $(this).data('image-url');
        
        // Set the image source and show the modal
        $('#imageModal').find('img').attr('src', imageURL);
        $('#imageModal').modal('show');
    });
});

</script>



	
	<!-- InstanceEndEditable --></div>
   

</body>
	
<!--<footer>
	<hr>
		<p>&copy; 2024 KPMS Final Year Project. All rights reserved.</p>
	</footer> -->
<!-- InstanceEnd --></html>




<!doctype html>

<html><!-- InstanceBegin template="/Templates/template.dwt" codeOutsideHTMLIsLocked="true" -->
<head>

<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" >
<link rel="icon" type="image/png" href="favicon.ico"/>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Manage Parcel</title>
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
	
<!-- Insert Modal -->
<!-- Modal -->
<div class="modal fade" id="insertModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Parcel Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="actionParcel.php" method="post" id="parcelForm">
        <div class="modal-body">
          <div class="form-group mb-3">
            <label for="">Tracking Number</label>
            <input type="text" class="form-control" name="trackingNumber" style="text-transform: capitalize;" required>
          </div>
          <div class="form-group mb-3">
    <label for="custName">Customer Name</label>
    <input type="text" class="form-control" name="custName" id="custName" style="text-transform: capitalize;" required>
    <small id="nameError" class="text-danger" style="display:none;">Please enter only alphabetic characters.</small>
</div>
          <div class="form-group mb-3">
    <label for="">Customer Phone Number</label>
    <input type="text" class="form-control" name="custPhoneNum" id="custPhoneNum" placeholder="e.g., 0123456789" pattern="01\d{8,9}"  >
    <small id="phoneError" class="text-danger" style="display:none;">Please enter a valid phone number with 10 or 11 digits.</small>
</div>
          <div class="form-group mb-3">
            <label for="">Courier Name</label>
			   <select class="form-select" name="courierName" required>
              <option >DHL</option>
              <option >Shopee Express</option>
			<option >Flash Express</option>
				   <option >Pos Laju</option>
				   <option>JNT</option>
				    <option>SkyNet</option>
				   <option>Ninja Van</option>
				    <option>Pgeon</option>
				    <option>GDex</option>
				    <option>FedEx</option>
				    <option>ABX Express</option>
				    <option>City Link</option>
				    <option>Lalamove</option>
				    <option>Others</option>
				   
            </select>
         <!--   <input type="text" class="form-control" name="courierName" style="text-transform: capitalize;"> -->
          </div>
<div class="form-group mb-3">
            <label for="">Status</label>
            <select class="form-select" name="status" value="Unclaimed">
              <option value="0">Unclaimed</option>
              <option value="1">Claimed</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="submit" class="btn btn-primary">Submit</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End of modal -->
	
	
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
					 <h4 class="text-center">MANAGE PARCEL</h4>
					 <!-- Button trigger modal -->
<button type="button" class="btn btn-success float-end mb-3" data-bs-toggle="modal" data-bs-target="#insertModal">
 <i class="bi bi-box2-heart"></i> &nbsp; Add New Parcel
</button>
  
				 </div>
				 <div class="card-body">
					 <div class="table-responsive">
					 <table class="table table-striped table-bordered table-hover " id="dataTable">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Tracking Number</th>
	  <th scope="col">Customer Name</th>
      <th scope="col">Customer Phone Number</th>
	  <th scope="col">Courier Name</th>
	  <th scope="col">Date/Time Added</th>
	  <th scope="col">Status</th>
	  <th scope="col">Action</th>
		
      
    </tr>
  </thead>
  <tbody>
	  <?php
	  $custID=1;
	  $fetch_query = "SELECT * FROM customer";
	  $fetch_query_run = mysqli_query($conn, $fetch_query);
	  
	  if(mysqli_num_rows($fetch_query_run) > 0)
	  {
		  while($row = mysqli_fetch_array($fetch_query_run))
		  {
	 ?>
	  <tr>
      <td><?= $custID++ ?></td>
	  <td><?php echo $row['trackingNumber']; ?></td>
	  <td><?php echo $row['custName']; ?></td>
<td> 
    <?php if (!empty($row['custPhoneNum'])) : ?>
        <?php
$whatsapp_message = "Dear {$row['custName']},\n\nYour parcel with tracking number {$row['trackingNumber']} has arrived at KOMASA on {$row['dateTimeParcel']}. You can collect your parcel at UPTM KOMASA, Level 4, at your earliest convenience. Thank you.";

$phoneNum = '+60' . $row['custPhoneNum'];
$whatsapp_link = "https://api.whatsapp.com/send?phone=" . urlencode($phoneNum) . "&text=" . urlencode($whatsapp_message);
?>

<a href="<?= $whatsapp_link ?>" target="_blank" class="btn btn-success btn-sm">
    <i class="bi bi-whatsapp"></i> <?= htmlspecialchars($row['custPhoneNum']) ?>
</a>
    <?php else : ?>
        <?= htmlspecialchars($row['custPhoneNum']) ?>
    <?php endif; ?>
</td>
	  <td><?php echo $row['courierName']; ?></td>
	  <td><?php echo $row['dateTimeParcel']; ?></td>
	  <td><?php 
		  if($row['status']==0){
			echo '<a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#claimModal' . $custID . '">Unclaimed</a>';
			  
		  }else{
		  echo '<p><a href="status.php?custID='.$row['custID'].'&status=1" class="btn btn-success btn-sm">Claimed</a></p>';
			  
echo '<button class="view-btn btn btn-info btn-sm " data-tracking="' . $row['trackingNumber'] . '"><i class="bi bi-eye"></i></button>';

		  }?>
	   </td> 
		  
		  <td>
		<a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $custID ?>"><i class="bi bi-pencil-square"></i></a>
		<a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $custID ?>"><i class="bi bi-trash3"></i></a>
		</td>
    </tr> 
	  
	  <!-- Edit Modal -->
<div class="modal fade" id="editModal<?= $custID ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Edit Parcel Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="actionParcel.php" method="post" id="editParcelForm<?= $custID ?>">
        <input type="hidden" name="custID" value="<?= htmlspecialchars($row['custID']) ?>">
        <div class="modal-body">
          <div class="form-group mb-3">
            <label>Tracking Number</label>
            <input type="text" class="form-control" name="trackingNumber" value="<?= htmlspecialchars($row['trackingNumber']) ?>" style="text-transform: capitalize;" required>
          </div>
          <div class="form-group mb-3">
            <label>Customer Name</label>
            <input type="text" class="form-control" name="custName" value="<?= htmlspecialchars($row['custName']) ?>" style="text-transform: capitalize;"  pattern="[A-Za-z\s]+" title="Please enter only alphabetic characters for the name."required>
          </div>
          <div class="form-group mb-3">
            <label>Customer Phone Number</label>
<input type="tel" class="form-control" id="custPhoneNum<?= $custID ?>" name="custPhoneNum" value="<?= $row['custPhoneNum'] ?>" placeholder="e.g., 0123456789" pattern="01[0-9]{8,9}" title="Phone number must start with '01' and be 10 or 11 digits long">
          </div>
          <div class="form-group mb-3">
            <label>Courier Name</label>
             <select class="form-select" name="courierName" required>
				 <option value="<?= htmlspecialchars($row['courierName']) ?>"><?= htmlspecialchars($row['courierName']) ?></option>
              <option >DHL</option>
              <option >Shopee Express</option>
			<option >Flash Express</option>
				   <option >Pos Laju</option>
				   <option>JNT</option>
				    <option>SkyNet</option>
				   <option>Ninja Van</option>
				    <option>Pgeon</option>
				    <option>GDex</option>
				    <option>FedEx</option>
				    <option>ABX Express</option>
				    <option>City Link</option>
				    <option>Lalamove</option>
				    <option>Others</option>
				   
            </select>
          </div>
          <div class="form-group mb-3">
            <label>Status</label>
            <select class="form-select" name="status">
              <option value="<?= htmlspecialchars($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></option>
              <option value="0">Unclaimed</option>
              <option value="1">Claimed</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="edit" class="btn btn-primary">Edit</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

	  
	<!-- Delete Modal -->
<!-- Modal -->
<div class="modal fade" id="deleteModal<?= $custID?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Delete Parcel</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
		<form action="actionParcel.php" method="post">
			<input type="hidden" name=custID value="<?= $row['custID'] ?>">
			
      <div class="modal-body">
		  <h5 class="text-center">Are You Sure You Want to Delete This?<br>
		  <span class="text-danger"><?=$row['trackingNumber']?> - <?= $row['custName'] ?></span>
		  </h5>
      </div >
			
      <div class="modal-footer">
		  <button type="submit" name="delete" class="btn btn-primary">Delete</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>  
	</form>
    </div>
  </div>
</div>
<!-- End of delete modal -->
	  
	  <!-- Claim Modal -->
	<div class="modal fade" id="claimModal<?= $custID ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Verify Parcel Claim</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="actionParcel.php" method="post" id="parcelForm2">
                <input type="hidden" name="custID" value="<?= $row['custID'] ?>">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="">Tracking Number</label>
                        <input type="text" readonly class="form-control" name="trackingNumber" value="<?= $row['trackingNumber'] ?>" style="text-transform: capitalize;" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Recipient Phone Number</label>
                        <input type="tel" class="form-control" name="recipientPhoneNum" placeholder="e.g., 0123456789" pattern="01[0-9]{8,9}" title="Phone number must start with '01' and be 10 or 11 digits long" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="verify" class="btn btn-primary">Verify</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
	  
	  <!-- View Modal -->
<div class="modal fade" id="parcelDetailsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="parcelDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header justify-content-between">
    <h5 class="modal-title" id="parcelDetailsModalLabel">View Parcel Details</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
      <div class="modal-body" id="modal-body">
        <!-- Parcel details will be displayed here -->
      </div>
      <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
    // Event delegation for dynamically added elements
    $('.table').on('click', '.view-btn', function() {
        var trackingNumber = $(this).data('tracking');
        
        $.ajax({
            url: 'actionParcel.php',
            type: 'GET',
            data: {trackingNumber: trackingNumber},
            success: function(response) {
                // Populate modal with response data
                $('#modal-body').html(response);
                $('#parcelDetailsModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});

</script>
		
<script>
	document.getElementById('parcelForm').addEventListener('submit', function(event) {
    var phoneInput = document.getElementById('custPhoneNum');
    var phoneValue = phoneInput.value.trim();

    // Check if the phone number is "01"
    if (phoneValue === "01") {
        // If the phone number is "01", set it to an empty string
        phoneInput.value = "";
    }
});
		</script>
		


		<script>
		document.getElementById('custName').addEventListener('input', function(event) {
        var input = event.target.value;
        var regex = /^[a-zA-Z\s]*$/; // Regular expression to match alphabetic characters and spaces

        if (!regex.test(input)) {
            // If input contains non-alphabetic characters, show error message and disable form submission
            document.getElementById('nameError').style.display = 'block';
            this.setCustomValidity("Please enter only alphabetic characters.");
        } else {
            // If input is valid, hide error message and enable form submission
            document.getElementById('nameError').style.display = 'none';
            this.setCustomValidity("");
        }
    }); </script>
		

		






	


	

	


	
	<!-- InstanceEndEditable --></div>
   

</body>
	
<!--<footer>
	<hr>
		<p>&copy; 2024 KPMS Final Year Project. All rights reserved.</p>
	</footer> -->
<!-- InstanceEnd --></html>



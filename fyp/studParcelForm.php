
<!doctype html>

<html><!-- InstanceBegin template="/Templates/template.dwt" codeOutsideHTMLIsLocked="true" -->
<head>

<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" >
<!-- InstanceBeginEditable name="doctitle" -->
<title>Parcel Form</title>
<!-- InstanceEndEditable -->
<link rel="stylesheet" href="css_template.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<!-- InstanceBeginEditable name="head" -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- InstanceEndEditable -->
</head>

<body>
	<!-- InstanceBeginEditable name="EditRegion4" -->EditRegion4<!-- InstanceEndEditable -->
	<?php 

session_start();
include "dbconn_login.php";
include "function.php";

// SQL to count rows with status "ready to collect"
$countSql = "SELECT COUNT(*) AS readyToCollectCount FROM form WHERE status = 'Ready to Collect'";
$result = $conn->query($countSql);
$row = $result->fetch_assoc();
$readyToCollectCount = $row['readyToCollectCount'];
?>

	<div class="sidenav">
  <a href="testCrud.php">Manage Parcel&nbsp;</a> <hr>
  <a href="parcelForm.php">Customer Parcel Form <span class="badge rounded-pill bg-info"><?php echo $readyToCollectCount; ?></span> </a> <hr>
  <a href="feedbackList.php">Customer Feedback&nbsp;</a> <hr>
  <a href="myProfile.php">My Account&nbsp;</a>
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
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Search Keyword</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7">

                                <form action="" method="GET">
                                    <div class="input-group mb-3">
                                        <input type="text" name="search" required value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>" class="form-control" placeholder="Search Tracking Number/Name/Phone Number/Status">
                                        <button type="submit" class="btn btn-primary">Search</button>
										 </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
									<th>Form ID</th>
                                    <th>Tracking Number</th>
                                    <th>Customer Name</th>
									<th>Phone Number</th>
                                    <th>Status</th>
                                    </tr>
                            </thead>
                            <tbody>
                                <?php 
                                   // $con = mysqli_connect("localhost","root","","kpms");
								
								 $sql = "SELECT * FROM parcel";
			   $result = mysqli_query($conn, $sql);

                                    /*if(isset($_GET['search']))
                                    {
                                        $filtervalues = $_GET['search'];
                                        $query = "SELECT * FROM parcel WHERE CONCAT(formID,trackingNumber,receiverName,receiverPhoneNum,status) LIKE '%$filtervalues%' ";
                                        $query_run = mysqli_query($con, $query); */

                                        if(mysqli_num_rows($result) > 0)
                                        {
                                            foreach($result as $rows)
                                            {
                                                ?>
												<?php $whatsAppLink = "https://api.whatsapp.com/send?phone=+6{$rows['receiverPhoneNum']}&text=" . urlencode("Hello {$rows['receiverName']}. Your tracking number {$rows['trackingNumber']} has arrived at KOMASA"); ?>
								
                                                <tr>
													<td><?= $rows['formID']; ?></td>
                                                    <td><?= $rows['trackingNumber']; ?></td>
                                                    <td><?= $rows['receiverName']; ?></td>
													<td><a href="<?php echo $whatsAppLink; ?>" target="_blank"><?= $rows['receiverPhoneNum']; ?></td>
													 <td><?= $rows['status']; ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }  
                                        else
                                        {
                                            ?>
                                                <tr>
                                                    <td colspan="4" style="text-align:center">No Record Found</td>
                                                </tr>
                                            <?php
                                        }
                                    //}
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
	<!-- InstanceEndEditable --></div>
   

</body>
	
<footer>
	<hr>
		<p>&copy; 2024 FYP2 Nur Hanifah. All rights reserved.</p>
	</footer>
<!-- InstanceEnd --></html>



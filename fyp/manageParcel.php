
<!doctype html>

<html><!-- InstanceBegin template="/Templates/template.dwt" codeOutsideHTMLIsLocked="true" -->
<head>

<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" >
<!-- InstanceBeginEditable name="doctitle" -->
<title>template interface</title>
<!-- InstanceEndEditable -->
<link rel="stylesheet" href="css_template.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<!-- InstanceBeginEditable name="head" -->
	 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
						
                        <h4>Search Keyword <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userForm" style="text-align: right">New Parcel <i class="bi bi-box-seam"></i></button>
						<br></h4>
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
									<th>ID</th>
                                    <th>Tracking Number</th>
                                    <th>Customer Name</th>
                                    <th>Status</th>
									<th>nak tulis apa ni</th>
                                    </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $con = mysqli_connect("localhost","root","","kpms");

                                    if(isset($_GET['search']))
                                    {
                                        $filtervalues = $_GET['search'];
                                        $query = "SELECT * FROM customer WHERE CONCAT(custID,trackingNumber,custName,status) LIKE '%$filtervalues%' ";
                                        $query_run = mysqli_query($con, $query);

                                        if(mysqli_num_rows($query_run) > 0)
                                        {
                                            foreach($query_run as $items)
                                            {
                                                ?>
                                                <tr>
													<td><?= $items['custID']; ?></td>
                                                    <td><?= $items['trackingNumber']; ?></td>
                                                    <td><?= $items['custName']; ?></td>
													 <td><?= $items['status']; ?></td>
													<td>
													<a class="btn btn-primary btn-sm" href="editParcel.php">Edit</a>
													<a class="btn btn-danger btn-sm" href="deleteParcel.php">Delete</a></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        else
                                        {
                                            ?>
                                                <tr>
                                                    <td colspan="4">No Record Found</td>
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

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
	<!-- InstanceEndEditable --></div>
   

</body>
	
<footer>
	<hr>
		<p>&copy; 2024 FYP2 Nur Hanifah. All rights reserved.</p>
	</footer>
<!-- InstanceEnd --></html>



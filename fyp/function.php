<?php

function check_login($conn)
{

	if(isset($_SESSION['staffID']))
	{

		$staffID = $_SESSION['staffID'];
		$query = "select * from staff where staffID = '$staffID' limit 1";

		$result = mysqli_query($conn,$query);
		if($result && mysqli_num_rows($result) > 0)
		{

			$user_data = mysqli_fetch_assoc($result);
			return $user_data;
		}
	}

	//redirect to login
	header("Location: manageParcel.php");
	die;

}

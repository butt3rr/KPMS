<?php
session_start(); 
include "dbconn_login.php";
include "function.php"; 

$custID=$_GET['custID'];
$status=$_GET['status'];

$update = "UPDATE customer SET status=$status WHERE custID=$custID";

mysqli_query($conn, $update);

header('location:testCrud.php');
?>


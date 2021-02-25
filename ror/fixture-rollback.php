<?php
error_reporting(E_ALL ^ E_NOTICE);
include(realpath(dirname(dirname(__FILE__)))."/include/access.php");
include(realpath(dirname(dirname(__FILE__)))."/include/rt.php");

$qx = mysqli_query($dbcnx, "select status from fixture_requests where id = '{$_GET['id']}'");
if (mysqli_num_rows($qx)==0) {
	echo "Oops! Invalid query id: {$_GET['id']}";
}else{

$status = mysqli_result($qx,0,"status");

if ((isset($_GET['q'])) and ($_GET['q']=="advance")) {
	switch($status) {
		case "processed":$ns = "vp_ok";break;
		case "vp_ok":$ns = "processed";break;
		case "rm_ok":$ns = "vp_ok";break;
		case "waiting":$ns = "rm_ok";break;	
		case "rm_deny":$ns = "waiting";break;	
	}
}else{
	switch($status) {
		case "processed":$ns = "vp_ok";break;
		case "vp_ok":$ns = "rm_ok";break;
		case "rm_ok":$ns = "waiting";break;
		case "waiting":$ns = "rm_deny";break;	
		case "rm_deny":$ns = "waiting";break;	
	}
}

$qy = mysqli_query($dbcnx, "UPDATE `fixture_requests` SET `status` = '$ns' WHERE `id` = '{$_GET['id']}' LIMIT 1");
if (!$qy) {
	die("Invalid query: " . mysqli_error($dbcnx));
} 

echo "success";
}
?>
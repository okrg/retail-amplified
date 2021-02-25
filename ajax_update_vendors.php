<?php 
	include("include/access.php");
	dbConnect();
	
	$id = $_POST['id'];
	$companyarray = json_decode($_POST['vendors']);
	
	$q = "UPDATE projects SET companyarray = '".serialize($companyarray)."' WHERE id = $id";

	$result = mysql_query($q);		 

	if($result) {
		print '1';
	} else {
		print 'error:'.mysql_error();
	}

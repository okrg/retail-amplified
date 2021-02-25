<?php 
	include("include/access.php");
	dbConnect();
	
	$column = $_GET['column'];
	$table = $_GET['table'];
	
	$data_query = "SELECT DISTINCT ".mysql_real_escape_string($column)." FROM ".$table;
	$result = mysql_query($data_query);
	while($drow = mysql_fetch_array($result)) {
		if(empty($drow[0])) {continue;}
		$data[] = $drow[0];
	}


	if($result) {
		header("Content-Type: application/json", true);
		print json_encode($data);


		exit;
	} else {
		print 'error:'.mysql_error();
	}



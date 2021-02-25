<?php 
	include("include/access.php");
	

	$table = $_POST['table'];
	$column = $_POST['column'];
	$value = mysql_real_escape_string( $_POST['value'] );
	$id = $_POST['id'];
	$field_id = $_POST['field_id'];

	//Set key name
	if($table == 'projects'){
		$key = 'id';
	} else {
		$key = 'project_id';
	}
	
	//Check if date is being submitted, if so, conver it to mysql foramt
	if (is_date($value)) {
		$value = date('Y-m-d', strtotime($value));
	}

	

	$q = "UPDATE $table SET $column = '$value' WHERE $key = $id";
	//print '0:'.$q;

	$result = mysql_query($q);		 

	if (mysql_affected_rows()==0) {
		//$q = "INSERT INTO $table ($key, $column) VALUES ($id, '$value')";
		$q = "INSERT INTO $table ($key, $column) VALUES ($id, '$value') ON DUPLICATE KEY UPDATE $column=VALUES($column)";
		//print '1:'.$q;
		$result = mysql_query($q);
	}

	if($result) {
	
		//Insert update into changes table
		$q = "INSERT INTO changes (project_id,field_id) VALUES ($id, '$field_id')";
		//print ' 2:'.$q;
		$result = mysql_query($q);

		$fivedaysago = date ('Y-m-d', strtotime('-5 day'));
		$q = "delete  FROM `changes` WHERE `date` < '".$fivedaysago."'";
		$result = mysql_query($q);
		
		print '1';
	} else {
		print $q;
		print 'error:'.mysql_error();
	}
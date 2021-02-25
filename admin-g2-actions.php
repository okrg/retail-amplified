<?php

//Check for action = close and if so, clear record
if ($_GET['action'] == "close") {
	$count = 0;
	if ($_POST['clearbox'] == "") {
		echo "<script language=\"JavaScript\">\n";
		echo "alert(\"No requests were selected!\");\n";
		echo "history.back();";
		echo "</script>";


		} else {
	
		foreach($_POST['clearbox'] as $value) {
		$sql = "update repair_orders set status='clear' where id = ".$value;
			if (!mysql_query($sql)) {
				error("A database error occured: " . mysql_error());
			}
		$count++;
		}
		echo "<span id=\"announce\">Closed $count !</span>";
	}
}

//Check for action = trash and if so, trash record
if ($_GET['action'] == "trash") {
	$count = 0;
	if ($_POST['clearbox'] == "") {
		echo "<script language=\"JavaScript\">\n";
		echo "alert(\"No requests were selected!\");\n";
		echo "history.back();";
		echo "</script>";
		} else {	
		
		foreach($_POST['clearbox'] as $value) {
		$sql = "delete from repair_orders where id = ".$value;
			if (!mysql_query($sql)) {
				error("A database error occured: " . mysql_error());
			}
		$count++;
		}
		echo "<span id=\"announce\">Trashed $count !</span>";
	}
}


//Check for reporting mode
if (($_GET['action'] == "report") || ($_POST['action'] == "report")) {
	$reporting_mode = TRUE;
	$_POST['action'] = "report";
	
	if (isset($_POST['type'])) {
		foreach($_POST['type'] as $value) {
			$reporting_type_sql .= "type='".$value."'";
			$reporting_type_sql .= " or ";
			$fr .= "<li>Type: <strong>".$value."</strong></li>";
		}
			$reporting_type_sql .= "type='x'";

	}

	if (isset($_POST['status'])) {
		foreach($_POST['status'] as $value) {
			$reporting_status_sql .= "status='".$value."'";
			$reporting_status_sql .= " or ";
			$fr .= "<li>Status: <strong>".$value."</strong></li>";
		}
		$reporting_status_sql .= "status='x'";
	}

	if (isset($_POST['priority'])) {
		foreach($_POST['priority'] as $value) {
			$reporting_priority_sql .= "priority='".$value."' ";
			$reporting_priority_sql .= " or ";
			$fr .= "<li>Priority: <strong>".$value."</strong></li>";
		}
		$reporting_priority_sql .= "priority='x'";
	}

	if ((isset($_POST['store_number'])) && ($_POST['store_number'] != "")) {
		$reporting_stno_sql .= "store_number='".$_POST['store_number']."'";
		$fr .= "<li>Store #: <strong>".$value."</strong></li>";
	}
	if ((isset($_POST['store_district'])) && ($_POST['store_district'] != "")) {
		$reporting_stdi_sql .= "store_district='".$_POST['store_district']."'";
		$fr .= "<li>District #: <strong>".$value."</strong></li>";
	}
	if ((isset($_POST['store_region'])) && ($_POST['store_region'] != "")) {
		$reporting_stre_sql .= "store_region='".$_POST['store_region']."'";
		$fr .= "<li>Region #: <strong>".$value."</strong></li>";
	}	
	if ((isset($_POST['chain'])) && ($_POST['chain'] != "")) {
		if ($_POST['chain']==1) {
			$reporting_stch_sql .= "store_number<600";
			$fr .= "<li>Chain: <strong>Charlotte Russe</strong></li>";
			} else {
			$reporting_stch_sql .= "store_number>599";
			$fr .= "<li>Chain: <strong>Rampage</strong></li>";
			}
	}	

}


?>
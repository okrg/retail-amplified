<?php

error_reporting(E_ALL ^ E_NOTICE);
include("../include/access.php");
include("../include/rt.php");
//Set dynamic database vars depending on mode
	if ($_GET['mode'] == "ROR") {
		//Assign the rt vars that are assingable....
		$sql = "UPDATE rt_rors SET ";
		$sql .= "watchlist = CONCAT(watchlist,',','$uid'),";
		$sql .= "WHERE id = '".$_GET['id']."'";		

	} elseif ($_GET['mode'] == "FREQ") {
		//Assign the rt vars that are assingable....
		$sql = "UPDATE rt_freqs SET ";
		$sql .= "watchlist = CONCAT(watchlist,',','$uid'),";
		$sql .= "WHERE id = '".$_GET['parent']."'";		

	}
	if (!mysql_query($sql)) {
		$summary_msg .= "<h1>Error assigning action</h1><p>A database error occured when adding comments to the database: </p>";
		$summary_msg .= "<p><small>".mysql_error()."</small></p>";
	} else {
		$summary_msg .= "<p>Assignment updated.</p>";
	}
if ($_GET['mode'] == "ROR") {
header('Location: home.php?mode=ROR');
} elseif ($_GET['mode'] == "FREQ") {
header('Location: home.php?mode=FREQ');
}
?>

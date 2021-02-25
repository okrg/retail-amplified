<?php
error_reporting(E_ALL ^ E_NOTICE);
include("../include/access.php");
dbConnect();
if (isset($_GET['edit'])) {
	include("../include/rt.php");
	//Load types and urgencies
	$t = Types();
	$u = Urgencies();

	if ($_GET['edit'] == "urgency") {
		if ($_GET['mode'] == "ROR"){$db = "rt_rors";}
		if ($_GET['mode'] == "FREQ"){$db = "rt_freqs";}
		$sql = "UPDATE $db SET urgency = '".$_POST['value']."' where id = '".$_GET['id']."'";
		$result = mysql_query($sql);
		if (!$result) {
			print "no workie:".mysql_error();
		} else {
			print "<img src=\"images/".$u[$_POST['value']][1].".gif\" />&nbsp;".$u[$_POST['value']][0];
		}
	}

	if ($_GET['edit'] == "type") {
			$sql = "UPDATE rt_rors SET type = '".$_POST['value']."' where id = '".$_GET['id']."'";
		$result = mysql_query($sql);
		if (!$result) {
			print "no workie:".mysql_error();
		} else {
			print $t[$_POST['value']][0]."<input type=\"hidden\" name=\"type\" value=\"".$t[$_POST['value']][0]."\" />";
		}
	}

} else {
	if($_GET['mode'] == "FLOOR"){
		if ($_GET['date'] == "cleaner"){$sql = "UPDATE projects SET cleaner_floor_date = '".$_POST['value']."' where id = ".$_GET['id'];}
		if ($_GET['date'] == "suggested"){$sql = "UPDATE projects SET suggested_floor_date = '".$_POST['value']."' where id = ".$_GET['id'];}
	} else {
		if ($_GET['mode'] == "FREQ"){$db = "rt_freq_responses";}
		if ($_GET['mode'] == "ROR"){$db = "rt_ror_responses";}
		$sql = "UPDATE $db SET body = '".$_POST['value']."' where id = ".$_GET['id'];
	}

	$result = mysql_query($sql);
	if (!$result) {
		print "no workie:".mysql_error();
	} else {
		print $_POST['value'];
	}

}

?>

<?php

error_reporting(E_ALL ^ E_NOTICE);
include("../include/access.php");
include("../include/rt.php");

if (isset($_GET['mode'])) {
	$mode = $_GET['mode'];
} else {
	switch($unique_user_id){
	case "92":
	case "295":
	case "90":
	case "390":
		$mode = "FREQ";
		break;
	default:
		$mode = "ROR";
		break;
	}
}


if (!isset($editok)):
$pageheading = "Floor Cleaning Scheduler";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>Floor Cleaning Scheduler</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" media="all">@import "rt.css";</style>
<script type="text/javascript">
	window.onload = function() {
	document.getElementById('lhome').className='link';
	document.getElementById('lnew').className='link';
	document.getElementById('lopen').className='link';
	document.getElementById('lcompleted').className='link';
	document.getElementById('ladmin').className='link';
	document.getElementById('lreport').className='link';
	document.getElementById('lcreate').className='link';
	document.getElementById('lfloor').className='current';
	document.getElementById('progress').style.visibility = "hidden";
}
</script>
<script type="text/javascript" src="rt.js"></script>
<script type="text/javascript" src="scriptaculous/prototype.js"></script>
<script type="text/javascript" src="scriptaculous/scriptaculous.js"></script>
</head>
<body>
<a name="top"></a>
<div id="progress" style="visibility: visible;">Loading...</div>
<div id="menu"><?php include("menu.php");?></div>
<a name="top"></a>
<h1><?=$pageheading?></h1>
<div id="maincontainer">
<?php
	dbConnect();
	//show store list
	$sql = "SELECT * FROM projects WHERE chain=1 ";
	if ($usergroup == 2){$g = g2filter($uid);$sql .= "AND $g[1] = $g[0] ";}
	$sql .= "order by store_number";
	$result = mysql_query($sql);
	echo "<table width=\"100%\" id=\"datarows\" cellspacing=\"0\" cellpadding=\"0\"><thead>";
	echo "<tr style=\"text-align:left;font-size:11px;\">";
	echo "<th>Store Number</th>";
	echo "<th>Location</th>";
	echo "<th>Scheduled Floor Cleaning</th>";
	echo "<th>Suggested Date</th>";
	echo "<th>Notify</th>";
	echo "</tr>";
	while($row = mysql_fetch_object($result)) {
		echo "<tr>";
		echo "<td><p>$row->store_number</p></td>";
		echo "<td><p>$row->sitename</p></td>";


		if ($row->cleaner_floor_date != "0000-00-00"){
			echo "<td><p id=\"cleaner".$row->id."\">$row->cleaner_floor_date</p>";
			echo "<script type=\"text/javascript\">new Ajax.InPlaceEditor('cleaner".$row->id."', 'eip.php?id=".$row->id."&mode=FLOOR&date=cleaner', {rows:1,cols:10});</script></td>";
		}else{
			echo "<td><p id=\"cleaner".$row->id."\">Not set</p>";
			echo "<script type=\"text/javascript\">new Ajax.InPlaceEditor('cleaner".$row->id."', 'eip.php?id=".$row->id."&mode=FLOOR&date=cleaner', {rows:1,cols:10});</script></td>";
		}
		
		
		if ($row->suggested_floor_date != "0000-00-00"){
			echo "<td><p id=\"suggested".$row->id."\">$row->suggested_floor_date</p>";
			echo "<script type=\"text/javascript\">new Ajax.InPlaceEditor('suggested".$row->id."', 'eip.php?id=".$row->id."&mode=FLOOR&date=suggested', {rows:1,cols:10});</script></td>";
		}else{
			echo "<td><p id=\"suggested".$row->id."\">Not set</p>";
			echo "<script type=\"text/javascript\">new Ajax.InPlaceEditor('suggested".$row->id."', 'eip.php?id=".$row->id."&mode=FLOOR&date=suggested', {rows:1,cols:10});</script></td>";
		}
		
		echo "<td id=\"box".$row->id."\"><p><a href=\"javascript:ajax_do('floor_notify.php?uid=$uid&id=".$row->id."');\">Send Notification</a></p></td>";
		
		echo "</tr>";
		
	}
	echo "</table>";
	
	
?>
</div>
</body>
</html>
<?php
else:
	dbConnect();

//Set the sql statment..to save the request into the right db
	$sql = "insert into fixture_blanket set fixture_key = ".$_POST['id'].",qty = ".$_POST['new_qty'].",mod_date = CURDATE()";
	if (!mysql_query($sql)) {error("A database error occured: " . mysql_error());}
	$pageheading = "$fixture->name - $fixture->desc";


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>Request Submitted</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" media="all">@import "rt.css";</style>
<script type="text/javascript" src="rt.js"></script>
</head>
<body>
<a name="top"></a>
<h1>Floor Cleaning Change Submitted</h1>
<div id="maincontainer">
<?php


?>
</div>
</body>
</html>
<?php
endif;
?>


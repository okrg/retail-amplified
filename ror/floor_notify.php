<?php
include("../include/db.php");
include("../include/common.php");
include("../include/rt.php");

dbConnect();
$sql = "select fullname, groupid, company_id from users where userid = '{$_GET['uid']}'";
$result = mysql_query($sql);
//Grab the user's full name for use in the page.
$username = mysql_result($result,0,"fullname");
$usergroup = mysql_result($result,0,"groupid");
$usercompany = mysql_result($result,0, "company_id");
mysql_free_result($result);
$sql = "select store_number, sitename, suggested_floor_date, cleaner_floor_date from projects where id= '{$_GET['id']}'";
$result = mysql_query($sql);
//Grab the user's full name for use in the page.
$store_number = mysql_result($result,0,"store_number");
$sitename = mysql_result($result,0,"sitename");
$suggested = mysql_result($result,0,"suggested_floor_date");
$cleaner = mysql_result($result,0,"cleaner_floor_date");
mysql_free_result($result);
if ($cleaner == "0000-00-00"){$cleaner="Not set";}
if ($suggested == "0000-00-00"){
	$msg="<strong>Error:</strong> No date set!";
}else{
//Call mail script
//Get addresses for FLOOR CLEANING
	$addresses = "brakzilla@gmail.com";
	$subjectline = "RE: Charlotte Russe Floor Cleaning #$store_number - $sitename";
	$link = "http://construction.charlotte-russe.com/ror";
	$notice_text = "Greetings, $addresses
	
	$username has suggested a new date for floor cleaning for:
	Charlotte Russe: #$store_number - $sitename
	New Date: $suggested	
	Original Scheduled Date: $cleaner
	
	This was an automated message.
	http:/"."/construction.charlotte-russe.com/ror";
	
	mail($addresses, $subjectline, $notice_text, "From:Collaboration Network <no-reply@charlotte-russe.com>");
	//Signal success
	$msg = "<strong>Success:</strong> Notification Sent!";
}
?>
document.getElementById('<?php echo "box".$_GET['id'];?>').innerHTML='<p><?php echo $msg;?></p>';
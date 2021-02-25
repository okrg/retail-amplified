<?php
error_reporting(E_ALL ^ E_NOTICE);
include("../include/db.php");

dbConnect();
$result = mysql_query("select * from rt_rors");
while($row = mysql_fetch_object($result)) {
	$subres = mysql_query("select * from rt_ror_responses where parent_key = ".$row->id);	
	$children = mysql_num_rows($subres);
	$give = mysql_query("update rt_rors set has_children = $children where id = ".$row->id);
	print "<p>updated $row->id</p>";
}

?>


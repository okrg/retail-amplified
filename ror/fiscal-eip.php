<?php
error_reporting(E_ALL ^ E_NOTICE);
include("../include/access.php");
dbConnect();
$clean = str_replace(',','',$_POST['value']);
$sql = "UPDATE fixture_fiscal SET ".$_GET['f']." = '$clean' where id = 1";
$result = mysql_query($sql);
if (!$result) {
	print "no workie:".mysql_error();
} else {
	print $_POST['value'];
}


?>

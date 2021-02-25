<?php
error_reporting(E_ALL ^ E_NOTICE);
include("include/access.php");
dbConnect();

if ($_GET['f'] == "approved_amt") {
	$clean = str_replace(',','',$_POST['value']);
	$sql = "UPDATE change_orders SET ".$_GET['f']." = '$clean' where id = {$_GET['id']}";
} elseif ($_GET['f'] == "pm_comment") {
	$clean = str_replace(',','',$_POST['value']);
	$sql = "UPDATE change_orders SET ".$_GET['f']." = '$clean' where id = {$_GET['id']}";
} else {
	$sql = "UPDATE change_orders SET ".$_GET['f']." = '{$_POST['value']}' where id = {$_GET['id']}";
}

$result = mysql_query($sql);
if (!$result) {
	print "no workie:".mysql_error();
} else {
	print stripslashes(preg_replace("/\n/","<br />",$_POST['value']));
}


?>

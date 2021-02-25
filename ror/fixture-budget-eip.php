<?php
error_reporting(E_ALL ^ E_NOTICE);
include("../include/access.php");
include("../include/rt.php");
dbConnect();
$clean = str_replace(',','',$_POST['value']);
$sql = "UPDATE rt_freq_budgets SET ".$_GET['f']." = '$clean' where region = ".$_GET['x'];
$result = mysql_query($sql);
if (!$result) {
	print "Sorry no workie: $sql ".mysql_error();
} else {
	$formatted = moneyFormat($_POST['value']);
	print $formatted;
}


?>

<?php
error_reporting(E_ALL ^ E_NOTICE);
include(realpath(dirname(dirname(__FILE__)))."/include/access.php");
include(realpath(dirname(dirname(__FILE__)))."/include/rt.php");
$pageheading = "Fixture Request Catalog";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>RT Home</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" media="all">@import "rt.css";</style>
<script type="text/javascript" src="rt.js"></script>
<script type="text/javascript">
	window.onload = function() {
	document.getElementById('lhome').className='link';
	document.getElementById('lwaiting').className='link';
	document.getElementById('lrm_ok').className='link';
	document.getElementById('lvp_ok').className='link';
	document.getElementById('lrm_deny').className='link';
	document.getElementById('lvp_deny').className='link';
	document.getElementById('lreport').className='link';
	document.getElementById('lcreate').className='link';
	document.getElementById('lcreate').className='current';
	document.getElementById('progress').style.visibility = "hidden";
}
</script>
</head>
<body>
<?
// create an array of all non-null fields 
$requests = array();
foreach ($_POST as $k=>$v) {
	if($v!="") {
		if(substr($k,0,1) == "t") {
			$fixid = ltrim($k,"t-");
			$requests[$fixid][cat]=$v;
		}
		if(substr($k,0,1) == "q") {
			$fixid = ltrim($k,"q-");
			$requests[$fixid][qty]=$v;
		}
	}
}
//check for empty form
if(count($requests)==0) {error("Oops! All fields were empty");}

//now go through the array of items and make sure there are valid pairs
$errors = array();
while (list($key, $array) = each($requests)) {
	if (!array_key_exists('cat', $array)) {$errors[$key]="Request Type";}
	if (!array_key_exists('qty', $array)) {$errors[$key]="Qty";}
}
//if invalid pair found, display the warning and send the person back.
$error_list = "";
if(count($errors)>0) {
	while (list($key, $value) = each($errors)) {
		$f = mysqli_query($dbcnx, "select name from fixture_key where id = $key");
		$name = mysqli_result($f,"name",0);
		$error_list .= "\\n $name is missing $value";
	}
	error("Oops! Some info is missing:\\n ".$error_list);
}


?>
<a name="top"></a>
<div id="progress" style="visibility: visible;">Loading...</div>
<div id="menu"><?php include("fixture-menu.php");?></div>
<h1><?=$pageheading?></h1>
<div id="maincontainer">
<form name="confirm" action="fixture-submit.php" method="post">
<input type="hidden" name="id" value="<?=$_POST['loc']?>" />

<div align="center">
	<p><strong>Step 3.</strong> Confirm orders and click Submit to send your request.</p>
	<input type="submit" value="Submit" class="macrobutton" />
<?

$loc_data = loc_data($_POST['loc']);
echo "<ul class=\"fixture_list\">";
echo "<li class=\"loc\">$loc_data</li>";

$total = 0;
foreach ($requests as $key => $value) {
	$f = mysqli_query($dbcnx, "select * from fixture_key where id = $key");
	while($fixture = mysqli_fetch_object($f)) {
		$qty = $value['qty'];
		echo "<input type=\"hidden\" name=\"q-$key\" value=\"$qty\" />";
		$cat = $value['cat'];	
		echo "<input type=\"hidden\" name=\"t-$key\" value=\"$cat\" />";
		echo "<li class=\"waiting\">"
		."<span style=\"float:right;\">$qty @ $".$fixture->cost."/ea. = $".number_format($qty*$fixture->cost,2)."</span>"
		."<b>$fixture->name</b> - ".ucwords(strtolower($fixture->desc))." [$cat]"
		."</li>";
		$total+=number_format($qty*$fixture->cost,2);
		if ($fixture->vm==1){ $vm = TRUE;}
	}
}
echo "<li class=\"total\">Total: $".number_format($total,2)."</li>";
echo "</ul>";
if ($vm) {echo "<input type=\"hidden\" name=\"vm\" value=\"1\" />";}
?>

<div style="clear:both;">&nbsp;</div>
<input type="submit" value="Submit" class="macrobutton" />
</form>
</div><!-- align center -->
</div>
</body>
</html>
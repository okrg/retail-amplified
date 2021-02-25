<?php
error_reporting(E_ALL ^ E_NOTICE);
include("../include/access.php");
include("../include/rt.php");

$yesterday = mktime(0, 0, 0, date("m") , date("d") - 1, date("Y"));
$yesterday = date("U", $yesterday);
$yesterday= getdate($yesterday);
$today = getdate();

$pageheading = "Report Builder";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>Report Builder</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" media="all">@import "rt.css";</style>


<script type="text/javascript" src="rt.js"></script>
<script type="text/javascript" src="report.js"></script>
<script type="text/javascript" src="scriptaculous/prototype.js"></script>
<script type="text/javascript" src="scriptaculous/scriptaculous.js"></script>
<script type="text/javascript">
	window.onload = function() {
	document.getElementById('lhome').className='link';
	document.getElementById('lnew').className='link';
	document.getElementById('lopen').className='link';
	document.getElementById('lcompleted').className='link';
	document.getElementById('lreport').className='link';
	document.getElementById('lcreate').className='link';
	document.getElementById('lreport').className='current';
	document.getElementById('progress').style.visibility = "hidden";
}
</script>
</head>
<body id="report">
<a name="top"></a>
<div id="progress" style="visibility: visible;">Loading...</div>
<div id="menu"><?php include("ror-menu.php");?></div>
<h1><?=$pageheading?></h1>
<div id="maincontainer">
<?php include("ror_report.php"); ?>
</div>
 </body>
</html>
<?php
error_reporting(E_ALL ^ E_NOTICE);
include("../include/access.php");
include("../include/rt.php");
$pageheading = "Administration";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>Admin</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" media="all">@import "rt.css";</style>
<script type="text/javascript" src="rt.js"></script>
<script type="text/javascript">
	window.onload = function() {
	document.getElementById('lhome').className='link';
	document.getElementById('lnew').className='link';
	document.getElementById('lopen').className='link';
	document.getElementById('lcompleted').className='link';
	document.getElementById('ladmin').className='link';
	document.getElementById('lreport').className='link';
	document.getElementById('lcreate').className='link';
	document.getElementById('ladmin').className='current';
	document.getElementById('progress').style.visibility = "hidden";
}
</script>
</head>
<body>
<a name="top"></a>
<div id="progress" style="visibility: visible;">Loading...</div>
<div id="menu"><?php include("menu.php");?></div>
<h1><?=$pageheading?></h1>
<div id="maincontainer">
<?php
if ($_GET['mode'] == "ROR") {
	echo "<p>We are desparately working on getting this page finished for you!</p>";
} elseif ($_GET['mode'] == "FREQ") {
	echo "<p>View Fixture Stats</p>";
	//load fixtures
	$f = Fixtures();
	while($fixture = mysql_fetch_object($f)) {
	$fixture_list .= "<li>[$fixture->name] <a href=\"freq-stats.php?id=".$fixture->id."\" target=\"main\">".ucwords(strtolower($fixture->desc))."</a>";
	if ($fixture->dds == 1) {$fixture_list .= " (DDS only)";}
	if ($fixture->exclude == 1) {$fixture_list .= " (Excluded)";}
	$fixture_list .= "</li>";
	}

	echo "<ul>".$fixture_list."</ul>";
}
?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</div>
 </body>
</html>
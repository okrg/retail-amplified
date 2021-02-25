<?php
error_reporting(E_ALL);
include("../include/access.php");
include("../include/rt.php");
dbConnect();
if (isset($editok)){
	//Set the sql statment..to save the request into the right db
	$sql = "insert into fixture_blanket set fixture_key = ".$_GET['id'].",qty = ".$_POST['new_qty'].",mod_date = CURDATE()";
	if (!mysql_query($sql)) {error("A database error occured: " . mysql_error());}

	$result = mysql_query("select * from fixture_key where id = ".$_GET['id']);
	$fixture = mysql_fetch_object($result);
	$fixture->desc = ucwords(strtolower($fixture->desc));
	$pageheading = "$fixture->name - $fixture->desc";
}

$result = mysql_query("select * from fixture_key where id = ".$_GET['id']);
$fixture = mysql_fetch_object($result);
$fixture->desc = ucwords(strtolower($fixture->desc));
$pageheading = "$fixture->name - $fixture->desc";


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>Fixture Stats</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" media="all">@import "rt.css";</style>
<script type="text/javascript">
parent.menu.document.getElementById('lhome').className='link';
parent.menu.document.getElementById('lnew').className='link';
parent.menu.document.getElementById('lopen').className='link';
parent.menu.document.getElementById('lcompleted').className='link';
parent.menu.document.getElementById('ladmin').className='current';
parent.menu.document.getElementById('lreport').className='link';
parent.menu.document.getElementById('lcreate').className='link';
</script>
<script type="text/javascript" src="rt.js"></script>
</head>
<body onload="window.focus();">
<a name="top"></a>
<h1><?=$pageheading?></h1>
<div id="maincontainer">
<?php
	echo "<table>";
	echo "<tr><td>";
	echo "<img src=\"../images/fixtures/".$fixture->name.".jpg\" border=\"0\" />";
	echo "</td><td class=\"boxed\">";
	$result = mysql_query("select min(qty),mod_date from fixture_blanket where fixture_key = ".$_GET['id']." group by mod_date desc");
	if(mysql_num_rows($result)>0) {
		echo "<h3>Current Blanket Qty:".mysql_result($result,0,"min(qty)")."</h3>";
	}else{
		echo "<h3>No blanket qty set</h3>";
	}
		echo "<form name=\"update\" method=\"post\" action=\"$PHP_SELF?id=".$_GET['id']."\">";
		echo "<input id=\"uwidg\" name=\"new_qty\" type=\"text\" size=\"5\" />";
		echo "<input type=\"submit\" value=\"submit\" name=\"editok\" /></form>";

	echo "</td></tr></table>";

	//determine wether you will show the chart or not.
	$result = mysql_query("select min(qty), date_format( mod_date, '%c/%e/%y' ) AS fdate from fixture_blanket where fixture_key = ".$_GET['id']." group by mod_date");
	if(mysql_num_rows($result)<1) {
		echo "<p>No blanket history to show for this fixture</p>"; //No history to show.
	} else {
		//include charts.php to access the InsertChart function
		include "charts.php";
		echo "<p>Blanket history chart</p>";
		echo InsertChart ( "charts.swf", "charts_library", "freq-history.php?fid=".$_GET['id']."&uniqueID=".rand()."", 600, 250 );
	}
?>
</div>
</body>
</html>
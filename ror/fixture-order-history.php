<?php
error_reporting(E_ALL ^ E_NOTICE);
include(realpath(dirname(dirname(__FILE__)))."/include/access.php");
include(realpath(dirname(dirname(__FILE__)))."/include/rt.php");
$pageheading = "Home";
$t =Types();
$u = Urgencies();
$season = "jun19";

function tell_status($s) {
	if ($s == "waiting")$ts = "Waiting";
	if ($s == "rm_ok")$ts = "RM Approved";
	if ($s == "rm_deny")$ts = "RM Denied";
	if ($s == "vp_ok")$ts = "VP Approved";
	if ($s == "vp_deny")$ts = "VP Denied";
	if ($s == "processed")$ts = "Processed";
	return $ts;
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>RT Home</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" media="all">@import "rt.css";</style>
<script type="text/javascript" src="rt.js"></script>
<script type="text/javascript" src="sorttable.js"></script>
<script type="text/javascript">
	window.onload = function() {
	document.getElementById('lhome').className='link';
	document.getElementById('lwaiting').className='link';
	document.getElementById('lrm_ok').className='link';
	document.getElementById('lvp_ok').className='link';
	document.getElementById('lrm_deny').className='link';
	document.getElementById('lvp_deny').className='link';
	document.getElementById('lprocessed').className='link';
	document.getElementById('lreport').className='link';
	document.getElementById('lcreate').className='link';
	document.getElementById('lreport').className='current';
	document.getElementById('progress').style.visibility = "hidden";
}
</script>
</head>
<body>
<a name="top"></a>
<div id="progress" style="visibility: visible;">Loading...</div>
<div id="menu"><?php include("fixture-menu.php");?></div>

<h1>Fixture Order History</h1>
<div id="maincontainer"> 
<blockquote><p>Select any of the following critiera to build a report set.</p>
<form name="changer" action="<?=$_SERVER['PHP_SELF']?>" method="post">
<!--  <select name="region" onchange="location='fixture-order-history.php?d=store_region&x='+this.value;document.changer.reset();">-->
	<select name="store_region">
	<option value="" selected="selected">By Region</option>
	<?php
	$selectsql = "select distinct store_region from projects order by store_region asc";
	$result = mysqli_query($dbcnx, $selectsql);
	if (!$result){error("A databass error has occured.$user_uid_rank $user_domain\\n".mysqli_error($dbcnx));}
	while ($row = mysqli_fetch_object($result))
	{if ($row->store_region==0){continue;}else{print "<option value=\"$row->store_region\">Region: ".intval($row->store_region)."</option>";}}
	?>
	</select>

<!--  <select name="district" onchange="location='fixture-order-history.php?d=store_district&x='+this.value;document.changer.reset();">-->
   <select name="store_district">
	<option value="" selected="selected">By District</option>
	<?php
	$selectsql = "select distinct store_district from projects order by store_district asc";
	$result = mysqli_query($dbcnx, $selectsql);
	if (!$result){error("A databass error has occured.$user_uid_rank $user_domain\\n".mysqli_error($dbcnx));}
	while ($row = mysqli_fetch_object($result))
	{if ($row->store_district==0){continue;}else{print "<option value=\"$row->store_district\">District: ".intval($row->store_district)."</option>";}}
	?>
  </select>

<!--  <select name="number" onchange="location='fixture-order-history.php?d=store_number&x='+this.value;document.changer.reset();">-->
	<select name="store_number">
	<option value="" selected="selected">By Store #</option>
	<?php
	$selectsql = "select distinct store_number from projects order by store_number asc";
	$result = mysqli_query($dbcnx, $selectsql);
	if (!$result){error("A databass error has occured.$user_uid_rank $user_domain\\n".mysqli_error($dbcnx));}
	while ($row = mysqli_fetch_object($result))
	{if ($row->store_number==0){continue;}else{print "<option value=\"$row->store_number\">Store #: ".intval($row->store_number)."</option>";}}
	?>
  </select>

<!--  <select name="season" onchange="location='fixture-order-history.php?d=season&x='+this.value;document.changer.reset();">
-->	
	<select name="season">
    <option value="" selected="selected">By Season</option>
	<?php
	$selectsql = "select distinct season from fixture_requests order by season asc";
	$result = mysqli_query($dbcnx, $selectsql);
	if (!$result){error("A databass error has occured.$user_uid_rank $user_domain\\n".mysqli_error($dbcnx));}
	while ($row = mysqli_fetch_object($result))
	{if ($row->season==""){continue;}else{print "<option value=\"$row->season\">Season: $row->season</option>";}}
	?>
  </select>
  
  
<!--    <select name="status" onchange="location='fixture-order-history.php?d=status&x='+this.value;document.changer.reset();">
-->	
	<select name="status">
    <option value="" selected="selected">By Status</option>
	<?php
	$selectsql = "select distinct status from fixture_requests order by status asc";
	$result = mysqli_query($dbcnx, $selectsql);
	if (!$result){error("A databass error has occured.$user_uid_rank $user_domain\\n".mysqli_error($dbcnx));}
	while ($row = mysqli_fetch_object($result))
	{if ($row->status==""){continue;}else{print "<option value=\"$row->status\">Status: ".tell_status($row->status)."</option>";}}
	?>
  </select>


<!--  <select name="fixture" onchange="location='fixture-order-history.php?d=fix_key&x='+this.value;document.changer.reset();">
-->	
	<select name="fix_key">
	<option value="" selected="selected">By Fixture</option>
	<?php
	$selectsql = "select distinct fix_key, name, `desc` from fixture_requests, fixture_key where fixture_requests.fix_key = fixture_key.id order by name asc";
	$result = mysqli_query($dbcnx, $selectsql);
	if (!$result){error("A databass error has occured.$user_uid_rank $user_domain\\n".mysqli_error($dbcnx));}
	while ($row = mysqli_fetch_object($result))
	{if ($row->fix_key==0){continue;}else{print "<option value=\"$row->fix_key\">Fixture: $row->name ".ucwords(strtolower($row->desc))."</option>";}}
	?>
  </select>
  <input name="submit" value="submit"  type="submit" />
</form>
</blockquote>


<?php
//if(isset($_GET['d'])) {
//
//if($_GET['d'] == "store_region")echo "<blockquote><strong>Order History for Store Region: {$_GET['x']}</strong></blockquote>";
//if($_GET['d'] == "store_district")echo "<blockquote><strong>Order History for Store District: {$_GET['x']}</strong></blockquote>";
//if($_GET['d'] == "store_number")echo "<blockquote><strong>Order History for Store Number: {$_GET['x']}</strong></blockquote>";
//if($_GET['d'] == "season")echo "<blockquote><strong>Order History for Season: {$_GET['x']}</strong></blockquote>";
//if($_GET['d'] == "fix_key")echo "<blockquote><strong>Order History for Fixture: ".mysql_do("select name from fixture_key where id = {$_GET['x']}")."</strong></blockquote>";
//if($_GET['d'] == "status")echo "<blockquote><strong>Order History for Status: ".tell_status($_GET['x'])."</strong></blockquote>";

if(isset($_POST['submit'])){	
	$msg = array();
	$query = array();
	
	if(isset($_POST['store_region']) and ($_POST['store_region'] != "")){
		$msg[] = "Store Region: {$_POST['store_region']}"; 
		$query[] = "store_region = {$_POST['store_region']}";
		}
	if(isset($_POST['store_district']) and ($_POST['store_district'] != "")){
		$msg[] = "Store District: {$_POST['store_district']}"; 
		$query[] = "store_district = {$_POST['store_district']}";
		}
	if(isset($_POST['store_number']) and ($_POST['store_number'] != "")){
		$msg[] = "Store Number: {$_POST['store_number']}"; 
		$query[] = "store_number = {$_POST['store_number']}";
		}
	if(isset($_POST['season']) and ($_POST['season'] != "")){
		$msg[] = "Season: {$_POST['season']}"; 
		$query[] = "season = '{$_POST['season']}'";
		}
	if(isset($_POST['fix_key']) and ($_POST['fix_key'] != "")){
		$msg[] = "Fixture: ". mysql_do("select name from fixture_key where id = {$_POST['fix_key']}"); 
		$query[] = "fix_key = {$_POST['fix_key']}";
		}
	if(isset($_POST['status']) and ($_POST['status'] != "")){
		$msg[] = "Status: ".tell_status($_POST['status']); 
		$query[] = "status = '{$_POST['status']}'";
		}

	echo "<blockquote>Order History for:";
	echo "<ul>";
		foreach($msg as $item) {echo "<li>" . $item . "</li>";}	
	echo "</ul>";
	echo "</blockquote>";

	foreach ($query as $q){$queries .= "$q and ";}

	$sql = "SELECT *, fixture_requests.id as request_id from fixture_requests,fixture_key,projects where $queries fixture_requests.fix_key = fixture_key.id and fixture_requests.loc_key = projects.id order by store_region";

	$result = mysqli_query($dbcnx, $sql);
	
	if (mysqli_num_rows($result)==0) {
		echo "<p>No results match your criteria!</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>";
		exit;
		}
	

	echo "<table class=\"sortable\" style=\"font-size:11px;\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\"><thead>" 
	."<tr>"
	."<th>#</th>"
	."<th>Location</th>"
	."<th>District</th>"		
	."<th>Region</th>"				
	."<th>Fixture</th>"
	."<th>Category</th>"
	."<th>Type</th>"
	."<th>Status</th>"
	."<th>Qty</th>"
	."<th><nobr>Each $</nobr></th>"
	."<th><nobr>Total $</nobr></th>"
	."<th>Season</th>"
	."<th>Date</th>"
	."</tr>"
	."</thead>";
	while($row = mysqli_fetch_object($result)) {
		echo ""
		."<tr>"
		."<td>".intval($row->store_number)."</td>"
		."<td>$row->sitename</td>"
		."<td>".intval($row->store_district)."</td>"		
		."<td>".intval($row->store_region)."</td>"				
		."<td>$row->name ".ucwords(strtolower($row->desc))."</td>"
		."<td>$row->cat</td>"
		."<td>$row->category</td>"
		."<td>".tell_status($row->status)."</td>"		
		."<td>$row->qty</td>"
		."<td>".number_format($row->cost,2)."</td>"
		."<td>".number_format($row->qty*$row->cost,2)."</td>"
		."<td>$row->season</td>"
		."<td>$row->date</td>"
		."</tr>";
	}
	echo "</table>";
}	
?>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</div>
</body>
</html>
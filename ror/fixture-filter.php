<?php
error_reporting(E_ALL ^ E_NOTICE);
include(realpath(dirname(dirname(__FILE__)))."/include/access.php");
include(realpath(dirname(dirname(__FILE__)))."/include/rt.php");
$pageheading = "Home";
$t =Types();
$u = Urgencies();
$season = "jun19";
$h1_season = "June 2019";
if ($_GET['status'] == "waiting"){$h1_status = "Waiting";}
if ($_GET['status'] == "rm_ok"){$h1_status = "RM Approved";}
if ($_GET['status'] == "rm_deny"){$h1_status = "RM Denied";}
if ($_GET['status'] == "vp_ok"){$h1_status = "VP Approved";}
if ($_GET['status'] == "vp_deny"){$h1_status = "VP Denied";}
if ($_GET['status'] == "processed"){$h1_status = "Processed";}

if ($_GET['vm']==1) {
	$request_type = " Mannequin Requests"; 
	$vm_conditional = " WHERE fixture_key.id=fixture_requests.fix_key and fixture_key.vm=1 and";
	$vm_conditional2 = " WHERE fixture_key.vm=1 and";
} else {
	$request_type = " Fixture Requests"; 
	$vm_conditional = " WHERE ";
	$vm_conditional2 = " WHERE ";
}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>RT Home</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" media="all">@import "rt.css";</style>
<script type="text/javascript" src="rt.js"></script>
<script type="text/javascript" src="jquery-1.2.6.min.js"></script>
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
	document.getElementById('l<?=$_GET['status']?>').className='current';
	document.getElementById('progress').style.visibility = "hidden";
}
</script>
</head>
<body>
<a name="top"></a>
<div id="progress" style="visibility: visible;">Loading...</div>
<div id="menu"><?php include("fixture-menu.php");?></div>


<h1><?=$h1_status?> <?=$request_type?> for <?=$h1_season?></h1>
<div id="maincontainer"> 
<?php

dbConnect();

if(isset($_GET['mass_vp_ok'])) {
	$vpsql = "UPDATE fixture_requests, projects SET status = 'vp_ok', vp_approval = CURDATE() WHERE loc_key = projects.id and and status = 'rm_ok' season = '$season' and projects.store_region = ".$_GET['mass_vp_ok'];
	if (!mysqli_query($dbcnx, $vpsql)) {
		print "<blockquote>Error assigning request status</h1><p>A database error occured when adding comments to the database: <p><small>".mysqli_error($dbcnx)."</small></p></blockquote>";
	} else {
		print "<blockquote>All requests in Region ".$_GET['mass_vp_ok']." were moved to VP Approved status.</blockquote>";
	}
}


if($usergroup == 2) {
	$g = g2filter($uid);
	$sql = "SELECT store_region, status, loc_key FROM projects, fixture_requests, fixture_key $vm_conditional loc_key = projects.id and season = '$season' and fixture_requests.status = '".$_GET['status']."' and $g[1] = $g[0] GROUP  BY store_region";
} else {
	$sql = "SELECT store_region, status, loc_key FROM projects, fixture_requests, fixture_key $vm_conditional loc_key = projects.id and season = '$season' and fixture_requests.status = '".$_GET	['status']."' GROUP  BY store_region";
}
$resultz = mysqli_query($dbcnx, $sql);
while($rowz = mysqli_fetch_object($resultz)) {

print "<fieldset>";
print "<legend>Region  ".intval($rowz->store_region)."</legend>";

if($usergroup == 2) {
	$g = g2filter($uid);
	$sql = "SELECT store_district, status, loc_key FROM projects, fixture_requests, fixture_key $vm_conditional projects.store_region = '".$rowz->store_region."' and loc_key = projects.id and season = '$season' and fixture_requests.status = '".$_GET['status']."' and $g[1] = $g[0] GROUP  BY store_district";
	if ($g[1] == "store_region") {$is_rm = TRUE;}
} else {
	$sql = "SELECT store_district, status, loc_key FROM projects, fixture_requests, fixture_key $vm_conditional projects.store_region = '".$rowz->store_region."' and loc_key = projects.id and season = '$season' and fixture_requests.status = '".$_GET	['status']."' GROUP  BY store_district";
}

if (($usergroup < 2) or ($is_rm)) {
	print "<blockquote>";
	print "<h1>Region ".intval($rowz->store_region)." ".status_translate($rowz->status)." Total: ".moneyFormat(get_total($rowz->status,"projects.store_region",$rowz->store_region))."</h1>";
	if (($usergroup < 2) and ($_GET['status'] == "rm_ok")) { print " <p><a href=\"$PHP_SELF?status=rm_ok&mass_vp_ok=".$rowz->store_region."\">Approve all orders in this region to VP Approved status</a></p>";}
	//sets the vp_ok status for all in this region
	
	print "</blockquote>";
}


$result = mysqli_query($dbcnx, $sql);

if (mysqli_num_rows($result)>0){
	if ($_GET['status'] == "processed"){
		echo "<blockquote>These requests have already been processed.</blockquote>";
	}else {
		echo "<blockquote>Click on the store location name to approve or deny requests. </blockquote>";
		echo "<blockquote><strong>Tip:</strong> Hover your mouse cursor over the <img src=\"/ror/images/request-date.gif\" align=\"absmiddle\"/> icon to see the key dates related to that request. </blockquote>";
	}

	//<a href=\"{$_SERVER['REQUEST_URI']}&vm=1\">Manage Mannequin Requests.</a>
	
}else{
	echo "<blockquote>There are no ".$request_type." which are \"$h1_status\".</blockquote>";
}

while($row = mysqli_fetch_object($result)) {
	echo "<div style=\"clear:both;\"><h2>District ".intval($row->store_district)."</h2></div>";
	if(isset($_GET['filter'])){
		$sqly = "SELECT distinct store_number,sitename, projects.id, sitecity, sitestate, top10, status, loc_key FROM projects, fixture_requests, fixture_key $vm_conditional loc_key = projects.id and season = '$season'  and fixture_requests.status = '".$_GET['status']."' and ".$_GET['filter']." = ".$_GET['q']." AND fixture_key.chain = 1 ORDER  BY store_district,store_number";
	}else{
		$sqly = "SELECT distinct store_number, sitename,projects.id,sitecity, sitestate, top10, status, loc_key FROM projects, fixture_requests, fixture_key $vm_conditional loc_key = projects.id and season = '$season'  and fixture_requests.status = '".$_GET['status']."' and store_district = '".$row->store_district."' AND fixture_key.chain = 1 ORDER  BY store_district,store_number";
	}
	$resulty = mysqli_query($dbcnx, $sqly);

	echo "<div class=\"fixture_list\">";
	while($rowy = mysqli_fetch_object($resulty)) {
		echo "<table class=\"store\">";
		echo "<tr>";
		echo "<td colspan=\"2\" class=\"loc\">";
		echo " <b><a href=\"javascript:ViewPop('fixture-view.php?id=$rowy->id','view');\">".intval($rowy->store_number)." $rowy->sitename</a></b> ";
		if ($rowy->top10 == 1) {echo "<img src=\"images/trophy.gif\" align=\"absmiddle\" /> ";}
		echo "($rowy->sitecity, $rowy->sitestate)";
		echo "</td>";
		echo "</tr>";
		$sqlx = "SELECT *, fixture_requests.id AS req_id from fixture_requests,fixture_key $vm_conditional2 fixture_requests.status = '".$_GET['status']."' and season = '$season' and fixture_requests.fix_key = fixture_key.id and fixture_requests.loc_key = $rowy->id";	
		$resultx = mysqli_query($dbcnx, $sqlx);
		if (mysqli_num_rows($resultx)>0){
			while($rowx = mysqli_fetch_object($resultx)) {
				if ($rowx->expedite == 1) {echo "<tr class=\"expedite\">";}
				else { echo "<tr id=\"row_$rowx->req_id\" class=\"$rowx->status\">";}
				if ($usergroup <2){
				echo "<td onmouseover=\"$('#roll_$rowx->req_id').show();\" onmouseout=\"$('#roll_$rowx->req_id').hide();\">";
				} else {
				echo "<td>";
				}
				if ($rowx->expedite == 1) echo "<img src=\"/ror/images/expedite.gif\" align=\"absmiddle\" title=\"Approved to Ship Early\"/>&nbsp;";
				echo "<b>$rowx->name</b> - ".ucwords(strtolower($rowx->desc))." [$rowx->category]";
				if ($rowx->date != "0000-00-00") echo "<img src=\"/ror/images/request-date.gif\" align=\"absmiddle\" title=\"Request Date: $rowx->date\"/>&nbsp;";
				if ($rowx->rm_approval != "0000-00-00") echo "<img src=\"/ror/images/rm_approval.gif\" align=\"absmiddle\" title=\"RM Approval Date: $rowx->rm_approval\"/>&nbsp;";
				if ($rowx->rm_deny != "0000-00-00") echo "<img src=\"/ror/images/rm_deny.gif\" align=\"absmiddle\" title=\"RM Deny Date: $rowx->rm_deny\"/>&nbsp;";
				if ($rowx->vp_approval != "0000-00-00") echo "<img src=\"/ror/images/vp_approval.gif\" align=\"absmiddle\" title=\"VP Approval Date: $rowx->vp_approval\"/>&nbsp;";
				if ($rowx->vp_deny != "0000-00-00") echo "<img src=\"/ror/images/vp_deny.gif\" align=\"absmiddle\" title=\"VP Deny Date:$rowx->vp_deny\"/>&nbsp;";									
				if ($rowx->processed != "0000-00-00") echo "<img src=\"/ror/images/processed.gif\" align=\"absmiddle\" title=\"Processed Date:$rowx->processed\"/>&nbsp;";
	
				echo "<span style=\"font-size:85%;display:none;padding:0 20px;\" id=\"roll_$rowx->req_id\">
<a href=\"#\" onclick=\"$.get('fixture-rollback.php?id=$rowx->req_id',
  function(data){if (data == 'success')$('#row_$rowx->req_id').fadeOut();else alert('Warning: ' + data);});\">Rollback</a> &nbsp;&nbsp;
<a href=\"#\" onclick=\"$.get('fixture-rollback.php?id=$rowx->req_id&q=advance',
  function(data){if (data == 'success')$('#row_$rowx->req_id').fadeOut();else alert('Warning: ' + data);});\">Approve</a></span>";				
				echo "</td>";
				echo "<td class=\"total\">$rowx->qty @ $".$rowx->cost."/ea. = $".number_format($rowx->qty*$rowx->cost,2)."</td>";
				echo "</tr>";
			}
		}
		$waiting_total = mysql_do("SELECT sum(cost*qty) from fixture_requests,fixture_key $vm_conditional2 fixture_requests.status = 'waiting' and season = '$season'  and fixture_requests.fix_key = fixture_key.id and fixture_requests.loc_key = $rowy->id");		
		$rm_ok_total = mysql_do("SELECT sum(cost*qty) from fixture_requests,fixture_key $vm_conditional2 fixture_requests.status = 'rm_ok' and season = '$season' and fixture_requests.fix_key = fixture_key.id and fixture_requests.loc_key = $rowy->id");		
		$vp_ok_total = mysql_do("SELECT sum(cost*qty) from fixture_requests,fixture_key $vm_conditional2 fixture_requests.status = 'vp_ok' and season = '$season' and fixture_requests.fix_key = fixture_key.id and fixture_requests.loc_key = $rowy->id");	
		$processed_total = mysql_do("SELECT sum(cost*qty) from fixture_requests,fixture_key $vm_conditional2 fixture_requests.status = 'processed' and season = '$season' and fixture_requests.fix_key = fixture_key.id and fixture_requests.loc_key = $rowy->id");		

		if ($waiting_total>0) echo "<tr class=\"summary\"><td><strong>Pending: </strong></td><td class=\"total\">$".number_format($waiting_total,2)."</td> </tr>";
		if ($rm_ok_total>0) echo "<tr class=\"summary\"><td><strong>RM Approved: </strong></td><td class=\"total\">$".number_format($rm_ok_total,2)."</td> </tr>";
		if ($vp_ok_total>0) echo "<tr class=\"summary\"><td><strong>VP Approved:</strong> </td><td class=\"total\">$".number_format($vp_ok_total,2)."</td> </tr>";	
		if ($processed_total>0) echo "<tr class=\"summary\"><td><strong>Processed:</strong> </td><td class=\"total\">$".number_format($processed_total,2)."</td> </tr>";	

		

		echo "</table>";//store
	}
	echo "</div>";//fixture_list
}

print "</fieldset>";
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
<p>&nbsp;</p>
</div>
</body>
</html>
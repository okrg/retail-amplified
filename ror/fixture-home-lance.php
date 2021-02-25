<?php
error_reporting(E_ALL ^ E_NOTICE);
include("../include/access.php");
include("../include/rt.php");
$pageheading = "Fixture Requests Home";
//Load types and urgencies and fixtures
$t =Types();
$u = Urgencies();
$h1_season = "Feb 09";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title><?=$pageheading?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" media="all">@import "rt.css";</style>
<script type="text/javascript" src="rt.js"></script>
<script type="text/javascript" src="scriptaculous/prototype.js"></script>
<script type="text/javascript" src="scriptaculous/scriptaculous.js"></script>
<script type="text/javascript" src="scriptaculous/Ajax.InPlaceSelect.js"></script>

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
	document.getElementById('lhome').className='current';
	document.getElementById('progress').style.visibility = "hidden";
}

</script>
</head>
<body>
<a name="top"></a>
<div id="progress" style="visibility: visible;">Loading...</div>
<div id="menu"><?php include("fixture-menu.php");?></div>
<h1><?=$pageheading?></h1>
<div id="maincontainer"> 
<table>
<tr>
<td>
<?php include("timeline.php");?>
</td>
<td>
<?php if ($usergroup < 2) {
//Total cost
?>
<div class="box"><h4>Totals</h4>
<table class="store">
<tr class="summary"><td>Pending Total: </td><td class="total"><?php echo get_total("waiting",all,0); ?></td></tr>
<tr class="summary"><td>RM Approved Total: </td><td class="total"><?php echo get_total("rm_ok",all,0); ?></td></tr>
<tr class="summary"><td>VP Approved Total: </td><td class="total"><?php echo get_total("vp_ok",all,0); ?></td></tr>
<!--<tr class="summary"><td>Processed Total: </td><td class="total"><?php //echo get_total("processed",all,0); ?></td></tr>-->
<?php if ($usercompany == 6) { ?>
	<tr class="summary"><td>Mannequin Total: </td><td class="total"><?php echo get_total("m",all,0); ?></td></tr>
<?php } ?>
<tr class="summary">
<td><strong>Fiscal 2009 Budget</strong></td>
<td><strong>$500,000.00</strong></td>
</tr>
<tr class="summary">
<td><strong>Orders to Date</strong></td>
<td>
<strong>$<span id="fiscal">
<?php echo number_format(mysql_do("select fiscal from fixture_fiscal where id = 1"),2); ?>
</span>
<?php
	if ($unique_user_id == 90) {
	echo "<script type=\"text/javascript\">"
		."new Ajax.InPlaceEditor('fiscal','fiscal-eip.php?f=fiscal',{rows:1,cols:12});</script>";
		}
?></strong>
</td>
</tr>
<tr class="summary">
<td><strong>Budget Balance Available</strong></td>
<td>
<strong>$<span id="balance" title="Auto recalculates upon reloading of screen">
<?php //echo number_format(mysql_do("select balance from fixture_fiscal where id = 1"),2); ?>
<?php //Autocalculates balance = fiscal - 500,000 
	echo number_format((500000 - mysql_do("select fiscal from fixture_fiscal where id = 1")),2);
?>
</span>
<?php
//	if ($unique_user_id == 90) {
//
//	echo "<script type=\"text/javascript\">"
//		."new Ajax.InPlaceEditor('balance','fiscal-eip.php?f=balance',{rows:1,cols:12});</scr"."ipt>";
//	}
?></strong>
</td>
</tr>
</table>
</div>
<br />

<div class="box">
<form name="changer">
  <select name="region" onchange="ajax_do('fixture-stats-lance.php?d=store_region&x='+this.value);document.changer.reset();">
	<option value="" selected="selected">Select a Region</option>
	<?php
	$selectsql = "select distinct store_region from projects order by store_region asc";
	$result = mysql_query($selectsql);
	if (!$result){error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error());}
	while ($row = mysql_fetch_object($result))
	{if ($row->store_region==0){continue;}else{print "<option value=\"$row->store_region\">Region: ".intval($row->store_region)."</option>";}}
	?>
	</select>

<!--  <select name="district" onchange="ajax_do('fixture-stats-lance.php?d=store_district&x='+this.value);document.changer.reset();">
	<option value="" selected="selected">Select a District</option>
	<?php
//	$selectsql = "select distinct store_district from projects order by store_district asc";
//	$result = mysql_query($selectsql);
//	if (!$result){error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error());}
//	while ($row = mysql_fetch_object($result))
//	{if ($row->store_district==0){continue;}else{print "<option value=\"$row->store_district\">District: ".intval($row->store_district)."</option>";}}
	?>
  </select>-->
</form>
<div id="statbox"><small>Select either a region or a district from these drop downs to see specific amounts.</small></div>
<?php } else {
	$g = g2filter($uid);
	?>
<form name="changer">
<input type="button" value="Load Fixture Stats" onclick="ajax_do('fixture-stats-lance.php?d=<?=$g[1]?>&x=<?=$g[0]?>');" />
</form>
<div id="statbox"></div>
<?php } ?>
</div>
	<ul>
		<li class="link"><a href="fixture-home.php">Home</a></li>
		<li class="link"><a href="fixture-request.php">Create Request</a><br />Use this form to submit your fixture requests.</li>
		<li class="link"><a href="fixture-filter.php?status=waiting">Waiting</a><br />Fixture requests that are waiting for approval.</li>
		<li class="link"><a href="fixture-filter.php?status=rm_ok">RM Approved</a><br />Fixture requests that are waiting for VP Approval.</li>
		<li class="link"><a href="fixture-filter.php?status=vp_ok">VP Approved</a><br />Approved fixture requests ready to ship.</li>        
		<li class="link"><a href="fixture-filter.php?status=processed">Processed</a><br />Fixture requests that have been approved and processed.</li>
        <li class="link"><a href="fixture-order-history.php">Fixture Order History</a><br />Fixture request order history.</li>
		<!--<li class="link" id="lfloor"><a href="floor.php">Floor Cleaning</a></li>-->
	</ul>
</td>
</tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<!--<h2>Fixture Request Status</h2>-->
<?php //include("fixture-list.php");?>
</div>
</body>
</html>
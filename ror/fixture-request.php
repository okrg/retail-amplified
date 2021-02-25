<?php
error_reporting(E_ALL ^ E_NOTICE);
include(realpath(dirname(dirname(__FILE__)))."/include/access.php");
include(realpath(dirname(dirname(__FILE__)))."/include/rt.php");
$pageheading = "Fixture Request Catalog";

function fixture_catalog($fixture_object) {
	while($fixture = mysqli_fetch_object($fixture_object)):
	?>

	<div class="keyfixture">
		<div class="desc">
			<div class="price"><?=$fixture->cost?></div>
			<div><?=$fixture->name?> <?=ucwords(strtolower($fixture->desc))?></div>
		</div>
		<div class="fixture">
			<a href="../images/fixtures/<?=$fixture->name?>.jpg" class="thickbox">
				<img src="../images/fixtures/thumbs/<?=$fixture->name?>.jpg" border="0" />
			</a>
		</div>		
		<div class="ordertool">
			<table><tr><td>Request Type</td><td>Qty</td></tr><tr><td>
			<select name="t-<?=$fixture->id?>">
				<option value="" selected>-Select-</option>
				<option value="Base Minimum">Base Minimum</option>
				<option value="New">New</option>
				<option value="Replacement">Replacement</option>
			</select>
			</td><td>
			<input name="q-<?=$fixture->id?>"" size="4" />
			</td></tr></table>			
		</div>
	</div>
	<?php
	endwhile;
}


//create the code for the store drop down list
if ($usergroup < 2) {
	$loc_sql = "select id, sitenum, sitename, store_number from projects where chain=1 and store_number != '' order by store_number";
} else {
	$g = g2filter($uid);
	$loc_sql = "select id, sitenum, sitename, store_number from projects where $g[1] = $g[0] and chain=1 order by store_number";
}

//Execute filter on db!
$result = mysqli_query($dbcnx, $loc_sql);
if (!$result){error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error($dbcnx));}
$loc_list = "";
while ($row = mysqli_fetch_array($result)){$loc_list .= "<option value=\"".$row['id']."\" />#".intval($row['store_number'])." ".$row['sitename']."</option>";}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>RT Home</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" media="all">@import "rt.css";</style>
<style type="text/css" media="all">@import "/thickbox/thickbox.css";</style>
<script type="text/javascript" src="/jquery/jquery-1.2.2.pack.js"></script>
<script type="text/javascript" src="/thickbox/thickbox-compressed.js"></script>
<script type="text/javascript" src="rt.js"></script>
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
	document.getElementById('lcreate').className='current';
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
<!--
<h1 style="color:red;border:1px solid red;margin:20px;background:#eee;padding:20px;text-align:center;">Requests are no longer being taken for the current fixture delivery. This request form has been disabled and it will not work. </h1>
-->
<?php //if ($usergroup < 2):  ?>
<form name="cart" action="fixture-confirm.php" method="post">
<?php //php else: ?>
<!--<form name="cart" action="fixture-freeze.php" method="post">-->
<?php //endif; ?>

<div align="left">
    <p>&nbsp;</p>
	<p><strong>Step 1.</strong> Select store you are requesting fixtures for.	<select name="loc"><?=$loc_list?></select> </p>
    <p><strong>Step 2.</strong> Specify a Request Type and Quantity for each fixture that you are requesting. </p>
    <p><Strong>Step 3.</Strong> Click Submit to confirm your order.</p>
    <p>&nbsp;</p>
</div>
	<div align="center">
	  <input type="submit" value="Submit" class="macrobutton" />
	  &nbsp;<input type="reset" value="Reset Order Form" class="macrobutton" />
    <p>&nbsp;</p>
</div>
<?php
$cat = 'Ready to Wear Fixtures';
	print "<h2>$cat</h2>";
	$v = Fixtures($cat);	
	fixture_catalog(Fixtures($cat));

$cat = 'Accessory Fixtures';
	print "<h2>$cat</h2>";
	fixture_catalog(Fixtures($cat));

$cat = 'Hardware';
	print "<h2>$cat</h2>";
	fixture_catalog(Fixtures($cat));

$cat = 'Tables';
	print "<h2>$cat</h2>";
	fixture_catalog(Fixtures($cat));

$cat = 'Light Wall Accessories';
	print "<h2>$cat</h2>";
	fixture_catalog(Fixtures($cat));

$cat = 'Mill Work';
	print "<h2>$cat</h2>";
	fixture_catalog(Fixtures($cat));

$cat = 'Body Forms';
	print "<h2>$cat</h2>";
	fixture_catalog(Fixtures($cat));

?>
    </div>
	<div style="clear:both;">&nbsp;</div>
<div align="center"><input type="submit" value="Submit" class="macrobutton" />&nbsp;<input type="reset" value="Clear Form" class="macrobutton" /></div>
</form>
</div>
</body>
</html>
<?php
///* Include Files *********************/
//session_start(); 
//include("../include/database.php");
//include("../include/db.php");
//include("../include/login.php");
///*************************************/
//if ($logged_in) {

include("../include/access.php");
include("../include/rt.php");

if (isset($_GET['mode'])) {
	$mode = $_GET['mode'];
} else {
	switch($unique_user_id){
	case "92":
	case "295":
	case "90":
	case "390":
		$mode = "FREQ";
		break;
	default:
		$mode = "ROR";
		break;
	}
}

$pageheading = "Home";
//Load types and urgencies and fixtures
$t =Types();
$u = Urgencies();


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>RT Home</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" media="all">@import "rt.css";</style>
<script type="text/javascript" src="rt.js"></script>
<script type="text/javascript">
	parent.menu.document.getElementById('lhome').className='link';
	parent.menu.document.getElementById('lnew').className='link';
	parent.menu.document.getElementById('lopen').className='link';
	parent.menu.document.getElementById('lcompleted').className='link';
	parent.menu.document.getElementById('lreport').className='link';
	parent.menu.document.getElementById('lcreate').className='link';
	parent.menu.document.getElementById('lhome').className='current';
	window.onload = function() {document.getElementById('progress').style.visibility = "hidden";}
</script>


</head>
<body>
<a name="top"></a>
<div id="progress" style="visibility: visible;">Loading...</div>

<h1><?=$pageheading?></h1>
<div id="maincontainer"> 

<?php 
	if(($_GET['mode']=="ROR")and($usergroup<2)) {
		echo "<p><a href=\"home.php?mode=ROR\"><img src=\"images/ror_icon.gif\" border=\"0\" /></a></p>";
		include("ror_dashboard.php");
	} elseif(($_GET['mode']=="FREQ")and($usergroup<2)) {
		echo "<p><a href=\"home.php?mode=FREQ\"><img src=\"images/freq_icon.gif\" border=\"0\" /></a></p>";
		include("freq_dashboard.php");
	}
?>

<h2>My Watch List</h2>
<?php 
	if(($_GET['mode']=="ROR")and($usergroup<2)) {
		dashboardRWatchList();
	} elseif(($_GET['mode']=="FREQ")and($usergroup<2)) {
		dashboardFWatchList();
	}
?>

<form name="new" id="new" method="POST" action="view.php?workset=1&mode=<?=$_GET['mode']?>" target="view">
<h2>Newest Requests</h2>
<?php 
	if($_GET['mode']=="ROR") {
		dashboardRTable('new');
	} elseif($_GET['mode']=="FREQ") {
		dashboardFTable('new');
	}
?>
<input type="hidden" name="starter" value="<?=$workset[0]?>" />
</form>

<form name="open" id="open" method="POST" action="view.php?workset=1&mode=<?=$_GET['mode']?>" target="view">
<h2>Recent Open Requests</h2>
<?php 
	if($_GET['mode']=="ROR") {
		dashboardRTable('open');
	} elseif($_GET['mode']=="FREQ") {
		dashboardFTable('open');
	}
?>
	<input type="hidden" name="starter" value="<?=$workset[0]?>" />
</form>

</div>
 </body>
</html>
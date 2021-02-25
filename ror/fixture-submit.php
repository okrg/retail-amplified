<?php //submit.php
error_reporting(E_ALL ^ E_NOTICE);
include(realpath(dirname(dirname(__FILE__)))."/include/access.php");
include(realpath(dirname(dirname(__FILE__)))."/include/rt.php");
//get loc data
$sql = "select * from projects where id = ".$_POST['id'];
$result = mysqli_query($dbcnx, $sql);
if (!$result) {error("Error with database: ".mysqli_error($dbcnx));exit;}
$loc = mysqli_fetch_object($result);
$summary_msg = "";

// create an array of all non-null fields 
$requests = array();
foreach ($_POST as $k=>$v) {
	if(substr($k,0,1) == "t") {
		$fixid = ltrim($k,"t-");
		$requests[$fixid][cat]=$v;
	}
	if(substr($k,0,1) == "q") {
		$fixid = ltrim($k,"q-");
		$requests[$fixid][qty]=$v;
	}
}

foreach ($requests as $key => $value) {
	$insert_sql = "insert into fixture_requests set 
	fix_key = $key, 
	loc_key = ".$_POST['id'].",
	qty = ".$value['qty'].",
	category = '".$value['cat']."',
	status = 'waiting',
	season = 'jun19',
	date = CURDATE()";
	if(!mysqli_query($dbcnx, $insert_sql)) {$msg= "An error occured when trying to submit request: ".mysqli_error($dbcnx);} else {$msg = "Request submitted successfuly!";}
}


//Call mail script
//Get addresses
if($loc->store_region != "") {
	$ruid = "rm".intval($loc->store_region);
	$addresses = mysql_do("select email from users where userid = '$ruid'");
	
	//VM Addresses
	if($_POST['vm'] == 1){$addresses .= ", brakzilla@gmail.com";}
	
	$subjectline = "Fixture Request #".intval($loc->store_number)." ".$loc->sitename;
	$link = "http://construction.charlotte-russe.com/ror/fixture-home.php";
	$notice_text = "Greetings,
	
	$username has submitted a fixture request for approval.
	
	Location: #".$loc->store_number." ".$loc->sitename." (".$loc->sitecity.",".$loc->sitestate.")
	
	This was an automated message.
	http:/"."/construction.charlotte-russe.com/ror/fixture-request.php";
	//mail($addresses, $subjectline, $notice_text, "From:Collaboration Network <no-reply@charlotte-russe.com>");
	//Add to report
	//$summary_msg .= "<p>The following message was e-mailed to corporate staff:</p>";
	//$summary_msg .= "<div style=\"width:600px;color:#000\"><pre>$notice_text</pre></div>";
} else {
//	$summary_msg .="<p>Unable to email the Regional Manager, becuase no region is specified for this location!</p>";
}






?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>Success!</title>
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
<a name="top"></a>
<div id="progress" style="visibility: visible;">Loading...</div>
<div id="menu"><?php include("fixture-menu.php");?></div>
<div id="maincontainer"> 
<p><?=$msg?></p>
<?=$summary_msg?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><a href="fixture-home.php">Return to home page</a></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
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
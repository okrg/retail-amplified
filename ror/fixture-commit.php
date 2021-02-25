<?php //edit.php
error_reporting(E_ALL ^ E_NOTICE);
include("../include/access.php");
include("../include/rt.php");
if (!isset($_POST['requests'])) {error("No items were checked, go back and check at least one item.");exit;}
if (!isset($_GET['q'])) {error("Error: Q not set!");exit;}
//get loc data
$sql = "select * from projects where id = ".$_POST['id'];
$result = mysql_query($sql);
if (!$result) {error("Error with database: ".mysql_error());exit;}
$loc = mysql_fetch_object($result);
$summary_msg = "";


//if ($_GET['q'] == "vp")$notify_vp = 1;

while (list($key, $value) = each($_POST['requests'])) {
	if (isset($_POST['ok']))$sql = "UPDATE fixture_requests SET status = '". $_GET['q']."_ok',". $_GET['q']."_approval = CURDATE() where id = $value";
	if (isset($_POST['deny']))$sql = "UPDATE fixture_requests SET status = '". $_GET['q']."_deny',". $_GET['q']."_deny = CURDATE() where id = $value";
	if (isset($_POST['processed']))$sql = "UPDATE fixture_requests SET status = 'processed', processed = CURDATE() where id = $value";
	if (isset($_POST['expedite']))$sql = "UPDATE fixture_requests SET status = 'vp_ok',vp_approval = CURDATE(), expedite = 1 where id = $value";
	if (!mysql_query($sql)) {
		$summary_msg .= "<h1>$sql Error assigning request status</h1><p>A database error occured when adding comments to the database: </p>";
		$summary_msg .= "<p><small>".mysql_error()."</small></p>";
	} else {
		$summary_msg .= "<p>$sql Fixture Request #$value status updated.</p>";
	}
}

if ($notify_vp == 1) {
//Call mail script
//Get addresses

//$subjectline = "RE: VP Approval Notification for #".intval($loc->store_number);
$notice_text = "Greetings,

A new fixture request for #".intval($loc->store_number)." ".$loc->sitename." has been approved.

This was an automated message.
http:/"."/construction.charlotte-russe.com/ror/fixture-home.php";

//mail($addresses, $subjectline, $notice_text, "From:Collaboration Network <no-reply@charlotte-russe.com>");
//Add to report
//$summary_msg .= "<p>The following message was e-mailed to corporate staff:</p>";
//$summary_msg .= "<div style=\"width:600px;color:#000\"><pre>$notice_text</pre></div>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>Success!</title>
<link rel="stylesheet" href="rt.css" />
<script type="text/javascript" src="rt.js"></script>
<script type="text/javascript" src="multifile.js"></script>
<script type="text/javascript" src="scriptaculous/prototype.js"></script>
<script type="text/javascript" src="scriptaculous/scriptaculous.js"></script>

</head>
<body id="pop" onload="parent.opener.location.reload();window.focus();">
<blockquote>
<?php echo $summary_msg; ?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<a href="javascript:window.close();">Close this window</a>
</blockquote>
</body>
</html>

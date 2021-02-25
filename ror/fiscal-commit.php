<?php //edit fixture fiscal.php
error_reporting(E_ALL ^ E_NOTICE);
include("../include/access.php");
include("../include/rt.php");
if (isset($_POST['expedite']))$sql = "UPDATE fixture_requests SET status = 'vp_ok',vp_approval = CURDATE(), expedite = 1 where id = $value";
	if (!mysql_query($sql)) {
		$summary_msg .= "<h1>$sql Error assigning request status</h1><p>A database error occured when adding comments to the database: </p>";
		$summary_msg .= "<p><small>".mysql_error()."</small></p>";
	} else {
		$summary_msg .= "<p>$sql Fixture Total Values updated.</p>";
	}
}


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

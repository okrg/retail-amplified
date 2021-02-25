<?php //view.php
error_reporting(E_ALL ^ E_NOTICE);
include("../include/access.php");
include("../include/rt.php");
$f = Fixtures();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>Key</title>
<link rel="stylesheet" href="rt.css" />
<script type="text/javascript" src="rt.js"></script>
</head>
<body id="pop" onload="window.focus();">
<?php 
while($fixture = mysql_fetch_object($f)) {
	echo "<div class=\"keyfixture\">";
	echo "<a href=\"#\" onclick=\"window.opener.document.request.fixture.value='$fixture->id';window.close();\"><img src=\"../images/fixtures/thumbs/".$fixture->name.".jpg\" border=\"0\" /></a>";
	echo "<br /><a href=\"#\" onclick=\"window.opener.document.request.fixture.value='$fixture->id';window.close();\">$fixture->name ".ucwords(strtolower($fixture->desc))."</a>";
	echo "</div>";
}
?>
</body>
</html>

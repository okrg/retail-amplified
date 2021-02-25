<?php //view.php
error_reporting(E_ALL ^ E_NOTICE);
include("../include/access.php");
include("../include/rt.php");
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
	$sql = "SELECT DISTINCT store_district FROM projects WHERE store_number > 0 AND store_district > 0 ORDER  BY store_district";
	$result = mysql_query($sql);
	while($row = mysql_fetch_object($result)) {
		echo "<p class=\"keydistrict\">District $row->store_district</p>";
		$sqly = "SELECT store_number, sitename FROM projects WHERE store_district = '".$row->store_district."' ORDER  BY store_district,store_number";
		$resulty = mysql_query($sqly);
		while($rowy = mysql_fetch_object($resulty)) {
			echo "<p class=\"keystore\"><a href=\"#\" onclick=\"window.opener.document.request.location.value='".intval($rowy->store_number)."';window.close();\">".intval($rowy->store_number)." $rowy->sitename</a></p>";
		}
	}

?>
</body>
</html>

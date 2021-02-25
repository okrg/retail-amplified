<?php
error_reporting(E_ALL ^ E_NOTICE);
include("../include/access.php");
include("../include/rt.php");
$pageheading = "spawn";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>RT List</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" media="all">@import "rt.css";</style>
<script type="text/javascript" src="rt.js"></script>
</head>
<body>
<a name="top"></a>
<div id="maincontainer">
<h1><?=$pageheading?></h1>
<?php
dbConnect();
$sql = "SELECT * FROM rt_rors where id>0";
	$result = mysql_query($sql);
	if (!$result) {error("Error with database: ".mysql_error());}
	//Start iterating through rows
	while ($row = mysql_fetch_object($result)) {
		$sqly = "update rt_rors set creation = '".$row->creation_date." ".$row->creation_time."' where id = '".$row->id."'";
		$resy = mysql_query($sqly);
		if (!$resy) {error("Error with database: ".mysql_error());}
	}

?>
</div>
 </body>
</html>
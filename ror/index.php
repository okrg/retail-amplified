<?php
error_reporting(E_ALL ^ E_NOTICE);
include("../include/access.php");
include("../include/rt.php");
$pageheading = "Facilities Management Home";
//Load types and urgencies and fixtures
$t =Types();
$u = Urgencies();
//header( 'Location: http://construction.charlotte-russe.com/ror/fixture-home.php' );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title><?=$pageheading?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" media="all">@import "rt.css";</style>
<script type="text/javascript" src="rt.js"></script>
</head>
<body>
<a name="top"></a>
<div id="menu"><p class="header"><?php include("../include/header.php"); ?></p></div>
<h1><?=$pageheading?></h1>
<div id="maincontainer"> 
<p>&nbsp;</p>
<table border="0" id="rorhome">
  <tr>
	<td class="blu"><a href="fixture-home.php">Fixture Requests</a></td>
        <?php if ($usergroup <2) { ?><td class="red"><a href="ror-home.php">Repair Order Requests</a></td><?php } ?>
  </tr>
</table>
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
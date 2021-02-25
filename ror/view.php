<?php //view.php
error_reporting(E_ALL ^ E_NOTICE);
include("../include/access.php");
include("../include/rt.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>Viewer</title>
<link rel="stylesheet" href="rt.css" />
<script type="text/javascript" src="rt.js"></script>
<script type="text/javascript" src="multifile.js"></script>
<script type="text/javascript" src="scriptaculous/prototype.js"></script>
<script type="text/javascript" src="scriptaculous/scriptaculous.js"></script>
<script type="text/javascript" src="scriptaculous/Ajax.InPlaceSelect.js"></script>
</head>
<body id="pop">
<?php include("viewcode.php");?>
</body>
</html>

<?php // logout.php
include("../include/access.php");
dbConnect();
$sql = "insert into viewlog set
		user = '$uid',
		project = 'Logged out'";
if (!mysql_query($sql))
echo ("Unable to write to site access log");

session_unset();
// unset cookies
if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>Collaboration Network</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" media="all">@import "../stylesheet.css";</style>
</head>
<body>
<div id="frame">
<div id="header"></div>

<div id="logincity"></div>

<div id="gradbar"></div>

<div id="content">
	<h1>:: Logged out</h1>
	<div class="databox">
	<p>You have been successfully logged out of the system.</p>
	<p><a href="index.php">Click here</a> to log in to the system again.</p>
	</div>
</div>
</div>
</body>
</html>
<?php //
error_reporting(E_ALL ^ E_NOTICE);
$dbhost = "db1";
$dbuser = "construction";
$dbpass = "c0llab0r8";
$db = "construction";

function dbConnect() {
    global $dbhost, $dbuser, $dbpass, $db;
    
    $dbcnx = @mysql_connect($dbhost, $dbuser, $dbpass)
        or die("The site database appears to be down.");

    if (!@mysql_select_db($db))
        die("The site database is unavailable.");
    
    return $dbcnx;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>

<title>Collaboration Network</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" media="all">@import "/stylesheet.css";</style>
<style type="text/css" media="all">@import "/thickbox/thickbox.css";</style>
<script type="text/javascript" src="/jquery/jquery-1.2.2.pack.js"></script>
<script type="text/javascript" src="/thickbox/thickbox-compressed.js"></script>
</head>
<body>
<div id="frame">
<div id="header"></div>

<div id="indexcity"></div>

<div id="gradbar">

</div>
<div id="content">	
<?php

		$id = $HTTP_SERVER_VARS['QUERY_STRING'];
		if (!$id) {
				echo "<h1>:: Error!</h1>";
				echo "<div class=\"databox\">";
				echo "<p><strong>You need a valid URL to view the photos!</strong></p>";
				echo "</div>";
				exit;
			}

		dbConnect();
		$sql = "select sitename from projects where id=$id";
		$result = mysql_query($sql);
			if (!result)
				error("A databass error has occured.\\n".mysql_error());
		$project=mysql_fetch_object($result);
			
		$sql = "select author, comment from gallerylog where galleryname='GRAND' and project= $id";
		$result= mysql_query($sql);
		if (!result)
			error("A databass error has occured.\\n".mysql_error());
		$distro = mysql_fetch_object($result);

		if ($distro->author == "")
			$distro->author="No owner!";
			if ($distro->comment == "")
			{
				$distro->comment="No comment exists in the database for this file";
			} else {
				$distro->comment=stripslashes($distro->comment);
			}
?>	
	<h1>:: Project Photos</h1>
	<div class="databox">
<?php
	echo "<div class=\"filebox\">";
	echo "<h2>$project->sitename</h2>";
	echo "<p><strong>Final Photos</strong></p>";
	echo "<strong>Uploaded by: </strong>$distro->author<br />";
	echo "<strong>Comments: </strong>$distro->comment</p>";
	echo "<br />";
	echo "<p><small>Each photo opens in a new windows.</small></p>";
	echo "</div>";
	
	echo "<br />";
	
	//scan for folders and files that are NOT photos and NOT system folders or files
	$linkspace= "filespace/$id/photos/GRAND/";
	$filespace = "../filespace/$id/photos/GRAND/";
	$files = array();
	$dirs  = array();
	if (!file_exists($filespace)) {
				echo "<h1>:: Error!</h1>";
				echo "<div class=\"databox\">";
				echo "<p><small>You must create a folder named: <strong>GRAND</strong></small></p>";
				echo "</div>";
				exit;
			} else {
	$handle=opendir($filespace);
	}			
	
	while (false !== ($file = readdir($handle)))
	{
		if($file=='.'||$file=='..'||$file=='.htaccess'||$file=='thumbs')
			continue;
		if(is_dir($filespace.$file))
			$dirs[]=$file;
		else
			$files[]=$file;
	}
	closedir($handle);
	//Sort arrays in natural order and reset pointer to first entry
	sort($dirs, SORT_REGULAR);
	sort($files, SORT_REGULAR);
	reset($dirs);
	reset($files);

	//Render table!	
	echo "<table class=\"litezone\" width=\"100%\" cellpadding=\"8\" cellspacing=\"0\">";
	echo "<tr>";
	$tablecol=0;

	//Now do files in root!
	foreach($files as $key=>$value)
	{
		$rawvalue = rawurlencode($value);
		//Show file name and link to it
		echo "<td width=\"33%\" valign=\"bottom\">";
		//echo "<a href=\"#\" onClick=\"n('".$linkspace.$rawvalue."');return false\">";
		echo "<a href=\"".$filespace.$rawvalue."\" class=\"thickbox\" rel=\"gallery\">";

		$ext = substr($value,-3);
		$ext = strtolower($ext);

		if ($ext == "jpg")
			echo "<img src=\"".$filespace."thumbs/$rawvalue\" align=\"absmiddle\" border=\"0\" style=\"border:1px #000 solid;\" />";
		else
			echo "<img src=\"images/nothumb.jpg\" align=\"absmiddle\" border=\"0\" style=\"border:1px #000 solid;\" />";
		echo "<br />";
		echo $value;
		echo "</a>";
		echo "</td>";
		$tablecol++;
		
		if ($tablecol==4) {
			echo "</tr><tr>";
			$tablecol=0;
		}
	}
	if ($tablecol == 1)
		echo "<td></td><td></td>";
	if ($tablecol == 2)
		echo "<td></td>";

	echo "</tr>";
	echo "</table>";
	echo "<br />";
	//Offer upload option to admins, show object count to all users
	echo "<div class=\"filebox\">";
	echo "<p>&nbsp;".count($files)." Photo(s) in this album";
	echo "&nbsp;&nbsp;";
	echo "<img src=\"images/download.gif\" align=\"absmiddle\" />&nbsp;";

	echo "</p>";
	
	echo "</div>";

	//Clear arrays and path var
	unset($filespace,$files, $dirs);
	?>
	
</div>	



</div></div>
 </body>
</html>
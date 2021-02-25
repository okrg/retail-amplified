<?php
set_time_limit(0);

switch($_GET['mode']) {

case "upload":
// Process edit
echo "<div id=\"content\">";
$id = $_POST['project_id'];
$name = $_POST['project_folder_name'];
$nicename = stripslashes($name);
$maindir = "./filespace/$id/$nicename";
$uploaddir = $maindir . "/";

for($i=0; $i<count($_FILES['userfile']['tmp_name']); $i++)
{ 
	$tempname = $_FILES['userfile']['tmp_name'][$i]; 
	$filename = $_FILES['userfile']['name'][$i];
	$filename = eregi_replace("'", "_", $filename);
	if ($tempname != "")
	{
		print "<br />maindir ".$maindir;
		print "<br />uploaddir ". $uploaddir;
		print "<br />filename ". $filename;
		print "<br />tempname ". $tempname;	
		
		if (file_exists($uploaddir.$filename))
		{
			$summary_msg .= "<p><strong>Warning:</strong> File of that name already exists. An underscore was added to the file name.</p>";
			while (file_exists($uploaddir.$filename)) {
				 $_FILES['userfile']['name'][$i] = "_".$filename;
			 	$filename = $_FILES['userfile']['name'][$i];
			}
		}
	}
}

$summary_msg .= "<p>";
for($i=0; $i<count($_FILES['userfile']['tmp_name']); $i++)
{ 
	$tempname = $_FILES['userfile']['tmp_name'][$i]; 
	$filename = $_FILES['userfile']['name'][$i];
	$filename = eregi_replace("'", "_", $filename);

	if ($tempname != "")
	{
		if (move_uploaded_file($tempname, $uploaddir.$filename))
		{ 
		$summary_msg .= "Uploaded: $filename<br />";
		} else {
		$summary_msg .= "Upload FAILED for $filename <br />";
		}
	}
}
$summary_msg .= "</p>";
//Print success report
echo "<h1>:: Success</h1>";
echo "<div class=\"databox\">";
echo "<table width=\"100%\" align=\"center\" cellspacing=\"4\" cellpadding=\"4\" class=\"filebox\">";
echo "<tr>";
echo "<td width=\"70\"><img src=\"images/avatar_zip.gif\" /></td>";
echo "<td>";
echo "<p>Your files have been successfully uploaded to the folder.</p>";
echo $summary_msg;
echo "</td></tr></table>";
echo "</div>";

include("folder_display.php");
echo "</div>";
break;

case "del":
// Process edit
$id = $_POST['project_id'];
$name = $_POST['project_folder_name'];
$nicename = stripslashes($name);
$filepath = $_POST['del_file_path'];
$filename = $_POST['del_file_name'];

echo "<div id=\"content\">";
	
if(unlink($filepath)) {
	echo "<h1>:: File Deleted</h1>";
	echo "<div class=\"databox\">";
	echo "<table width=\"100%\" align=\"center\" cellspacing=\"4\" cellpadding=\"4\" class=\"filebox\">";
	echo "<tr>";
	echo "<td width=\"70\"><img src=\"images/avatar_clean.gif\" /></td>";
	echo "<td>";
	echo "<p>File has been removed successfully:<br /><strong>$filename</strong></p>";
} else {
	echo "<h1>:: Oops!</h1>";
	echo "<div class=\"databox\">";
	echo "<table width=\"100%\" align=\"center\" cellspacing=\"4\" cellpadding=\"4\" class=\"filebox\">";
	echo "<tr>";
	echo "<td width=\"70\"><img src=\"images/avatar_clean.gif\" /></td>";
	echo "<td>";
	echo "<p>Unable to remove your file! There might be issues with the file permissions, contact the admin!</p>";
}
echo "</td></tr></table>";
echo "</div>";
include("folder_display.php");
echo "</div>";
break;

case "remove":
// Process edit
$id = $_POST['project_id'];
$name = $_POST['project_folder_name'];
$nicename = stripslashes($name);
$maindir = "./filespace/$id/$nicename";

echo "<div id=\"content\">";
	
if(deldir($maindir)) {
	echo "<h1>:: Folder removed</h1>";
	echo "<div class=\"databox\">";
	echo "<table width=\"100%\" align=\"center\" cellspacing=\"4\" cellpadding=\"4\" class=\"filebox\">";
	echo "<tr>";
	echo "<td width=\"70\"><img src=\"images/avatar_clean.gif\" /></td>";
	echo "<td>";
	echo "<p>Folder named: <strong>$name</strong> has been removed successfully.</p>";
	dbConnect();
	$sql = "delete from distrolog where project=$id and distroname='$name'";
	if (mysql_query($sql)) {
		echo "<p>Folder comment deleted from database.</p>";
	} else {
		echo "<p>However, there was trouble deleteing the comment from the database:".mysql_error()."</p>";
	}
} else {
	echo "<h1>:: Unable to remove folder</h1>";
	echo "<div class=\"databox\">";
	echo "<table width=\"100%\" align=\"center\" cellspacing=\"4\" cellpadding=\"4\" class=\"filebox\">";
	echo "<tr>";
	echo "<td width=\"70\"><img src=\"images/avatar_clean.gif\" /></td>";
	echo "<td>";
	echo "<p>Unable to remove your folder! There might be issues with the file permissions, contact the admin!</p>";
}
echo "<p><a href=\"$PHP_SELF?page=project&id=$id\">:: Return to project page</a></p>";
echo "</td></tr></table";
echo "</div>";
echo "</div>";
break;




case "vendordel":
// Process edit
$id = $_POST['project_id'];
//$name = $_POST['project_folder_name'];
$filepath = $_POST['delvendorfile_path'];
$filename = $_POST['delvendorfile_name'];

echo "<div id=\"content\">";

	
if(unlink($filepath)) {
	echo "<h1>:: File Deleted</h1>";
	echo "<div class=\"databox\">";
	echo "<table width=\"100%\" align=\"center\" cellspacing=\"4\" cellpadding=\"4\" class=\"filebox\">";
	echo "<tr>";
	echo "<td width=\"70\"><img src=\"images/avatar_clean.gif\" /></td>";
	echo "<td>";
	echo "<p>File has been removed successfully:<br /><strong>$filename</strong></p>";
	dbConnect();
	$sql = "delete from vendor_filelog where project=$id and filename='$name'";
	if (mysql_query($sql)) {
		echo "<p>Folder comment deleted from database.</p>";
	} else {
		echo "<p>However, there was trouble deleteing the comment from the database:".mysql_error()."</p>";
	}

} else {
	echo "<h1>:: Oops!</h1>";
	echo "<div class=\"databox\">";
	echo "<table width=\"100%\" align=\"center\" cellspacing=\"4\" cellpadding=\"4\" class=\"filebox\">";
	echo "<tr>";
	echo "<td width=\"70\"><img src=\"images/avatar_clean.gif\" /></td>";
	echo "<td>";
	echo "<p>Unable to remove your file! There might be issues with the file permissions, contact the admin!</p>";
}
echo "</td></tr></table>";
echo "</div>";
include("folder_display.php");
echo "</div>";
break;



default:
echo "<div id=\"content\">";
include("folder_display.php");
echo "</div>";
break;

}
?>
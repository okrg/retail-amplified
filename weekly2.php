<?php

switch($_GET['mode']) {
	
case "single":
	$id = $_POST['project_id'];
	$vq = mysql_query("select company_name from companies where company_id=$usercompany");
	$vo = mysql_fetch_object($vq);
	$sitename = $_POST['project_name'];
	$newcomment = $_POST['new_file_comment'];
	
	//Trim for whitespace
	$newcomment=rtrim($newcomment);
	$newcomment=ltrim($newcomment);

	//Establish location name variables
	$uploaddir = "./filespace/weekly/";
	$tempname = $_FILES['userfile']['tmp_name'][0];
	$filename = $_FILES['userfile']['name'][0];
	
	//Validate and exit upon finding conditions that cause failure
	if (!is_uploaded_file($tempname))
	{
		$summary_msg .= "<p><strong>Warning:</strong> It appears you tried to upload an invalid file!</p>";
		$summary_msg .= "<pre>".print_r($_FILES, true)."</pre>";
	} else {
		//Check to see if file already exists in filesystem
		if (file_exists($uploaddir.$filename))
		{
			$summary_msg .= "<p><strong>Warning:</strong> File of that name already exists. An underscore was added to your file</p>";
			while (file_exists($uploaddir.$filename)) {
				$filename = "_".$filename;
			}
		}
		//Assume success and move temp to new loation and complete processing and display success to user	
			move_uploaded_file($tempname, $uploaddir.$filename);
			$summary_msg.= "<p>File has been successfully uploaded:";
			$summary_msg.= "<strong>$filename</strong></p>";
			
			//Add comments and author into filelog table
			dbConnect();	
			$sql =	"insert into weekly set 
					user='$unique_user_id',
					filename='$filename',
					company='$usercompany'";
			if (!mysql_query($sql)) {
				$summary_msg .= "<p>A database error occured when adding file to the database: </p>";
				$summary_msg .= "<p><small>".mysql_error()."</small></p>";
			} else {
				$summary_msg .= "<p><a href=\"$PHP_SELF?page=home\">Return to home page.</a></p>";
			}
			
			//Check to see if notification was called for, if so generate notification vars for $message, $comments,
			//$project and $link first since they are needed by notify.php to operate properly
			if (isset($_POST['notify']))
			{
				//Create strings for mail
				$message = "$username has uploaded the weekly report for $vo->companyname ";
				$message .= "$filename\n";
				$link = "http://construction.charlotte-russe.com/";
				$project = "$vo->companyname Weekly Report";
//				//invite vendors
				$invite_vendors = FALSE;
//				//Call mail script
				include("notify.php");
//				//Add to report
				
				$summary_msg .= "<p>The following comment was e-mailed to corporate staff:</p>".
				"<div class=\"rbroundbox\">".
				"<div class=\"rbtop\"><div></div></div>".
				"<div class=\"rbcontent\">".
				"<strong>$username wrote:</strong></br>".
				"<p>$comments</p>".
				"</div><!-- /rbcontent -->".
				"<div class=\"rbbot\"><div></div></div>".
				"</div><!-- /rbroundbox -->";
			}
			
			//Format success report
			echo "<div id=\"content\">";
				echo "<h1>:: Upload Report</h1>";
				echo "<div class=\"databox\">";

				echo "$summary_msg";

				echo "</div>";

			echo "</div>";
	}
break;



case "del":
// Process edit
$id = $_POST['project_id'];

if (isset($_POST['del_file_name'])) {
	$filepath = $_POST['del_file_path'];
	$filename = $_POST['del_file_name'];
	$delcomments = "weekly";
}



echo "<div id=\"content\">";
	
if(unlink($filepath)) {
	echo "<h1>:: Weekly Report File Deleted</h1>";
	echo "<div class=\"databox\">";
	echo "<table width=\"100%\" align=\"center\" cellspacing=\"4\" cellpadding=\"4\" class=\"filebox\">";
	echo "<tr>";
	echo "<td width=\"70\"><img src=\"images/avatar_clean.gif\" /></td>";
	echo "<td>";
	echo "<p>File has been removed successfully:<br /><strong>$filename</strong></p>";
	dbConnect();
	$sql = "delete from weekly where filename='$filename'";
	if (!mysql_query($sql)) {
		$summary_msg .= "<p>A database error occured when removing comments to the database: </p>";
		$summary_msg .= "<p><small>".mysql_error()."</small></p>";
	} else {
		$summary_msg .= "<p>Comments have been removed from the database</p>";
	}
	echo $summary_msg;
	echo "<p><a href=\"$PHP_SELF?page=home\">Return to home page.</a></p>";
} else {
	echo "<h1>:: Oops!</h1>";
	echo "<div class=\"databox\">";
	echo "<table width=\"100%\" align=\"center\" cellspacing=\"4\" cellpadding=\"4\" class=\"filebox\">";
	echo "<tr>";
	echo "<td width=\"70\"><img src=\"images/avatar_clean.gif\" /></td>";
	echo "<td>";
	echo "$filepath<br />";
	echo "$filename<br />";
	echo "<p>Unable to remove your file! There might be issues with the file permissions, contact the admin!</p>";
}
echo "</td></tr></table>";
echo "</div>";


echo "</div>";
break;


default:
	echo "<div id=\"content\">";
	echo "</div>";
break;
}
?>

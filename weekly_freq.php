<?php

switch($_GET['mode']) {
	
case "single":
	//Establish location name variables
	$uploaddir = "./filespace/weekly_freq/";
	if (!file_exists($uploaddir)) {mkdir($uploaddir);}
	$tempname = $_FILES['userfile']['tmp_name'][0];
	$filename = $_FILES['userfile']['name'][0];
	
	//Validate and exit upon finding conditions that cause failure
	if (!is_uploaded_file($tempname))
	{
		$summary_msg .= "<p><strong>Warning:</strong> It appears you tried to upload an invalid file!</p>";
		$summary_msg .= "<pre>".print_r($_FILES, true)."</pre>";
	} else {
		//Assume success and move temp to new loation and complete processing and display success to user
			  if ($handle = opendir("$uploaddir")) {
					while (false !== ($item = readdir($handle))) {
					 	if ($item != "." && $item != ".."){unlink("$uploaddir/$item");}
					 }
					}
			move_uploaded_file($tempname, $uploaddir.$filename);
			$summary_msg.= "<p>File has been successfully uploaded:";
			$summary_msg.= "<strong>$filename</strong></p>";
			
			
			//Check to see if notification was called for, if so generate notification vars for $message, $comments,
			//$project and $link first since they are needed by notify.php to operate properly
			if (isset($_POST['notify']))
			{
				//Create strings for mail
				$message = "$username has uploaded the weekly fixture report: ";
				$message .= "$filename\n";
				$link = "http://construction.charlotte-russe.com/";
				$project = "Weekly Fixture Report";
//				//invite vendors
				$invite_vendors = FALSE;
//				//Call mail script
				include("notify_dms.php");
				
				$summary_msg .= "<p>A notification was e-mailed to DMs</p>";
			}
			
			//Format success report
			echo "<div id=\"content\">";
				echo "<h1>:: Weekly Fixture Report Upload</h1>";
				echo "<div class=\"databox\">";

				echo "$summary_msg";
				
				echo "<br /><a href=\"index.php?page=admin-freq\">Back to Requests</a>";

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

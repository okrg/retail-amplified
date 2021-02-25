<?php 
	include("include/access.php");
	dbConnect();

	$project_id = mysql_real_escape_string($_REQUEST['project_id']);
	$newfolder = mysql_real_escape_string($_REQUEST['newfolder']);
	$newcomment = mysql_real_escape_string($_REQUEST['comment']);
	$sitename = mysql_real_escape_string($_REQUEST['sitename']);

	$sql =	"insert into gallerylog set 
		author='$username',
		project='$project_id',
		galleryname='$newfolder',
		comment='$newcomment'";

	if (!mysql_query($sql)) {		
		die( mysql_error() );
	}

	//Check to see if notification was called for, if so generate notification vars for $message, $comments,
	//$project and $link first since they are needed by notify.php to operate properly
	if (isset($_REQUEST['notify'])) {
		//Create strings for mail
		$message = "$author has uploaded new photos\n";
		$message .= "to a new project album named: $newfolder\n";
		$project = $sitename;
		$comments = $newcomment;
		$link = "http://construction.charlotte-russe.com/index.php?page=project&id=$project_id";

		//do not invite other vendors if this is being uploaded by another vendor. 
		if ($usergroup == 3) {
			$invite_vendors = FALSE;
		} else {
			$invite_vendors = TRUE;
		}		
		
		//Call mail script
		include("notify.php");
	}
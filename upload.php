<?php //upload.php
//This is going to be the default form action for all forms that send to eternal scripts for uploading.
//Each call of this will be made with a $mode var specifying which upload case to use between the four
//options of single, multiple, zip, photos. Each form that calls this script will also send hidden values
//which specify the sitename
set_time_limit(0);
switch($_GET['mode'])
{
case "single":
	//Establish location name variables
	$uploaddir = "./filespace/".$_GET['id']."/";
	$tempname = $HTTP_POST_FILES['userfile']['tmp_name'][0];
	$filename = $HTTP_POST_FILES['userfile']['name'][0];
	
	$summary_msg = "<p><strong>Upload report</strong></p>";

	//Validate and exit upon finding conditions that cause failure
	if (!is_uploaded_file($tempname))
	{
		error("You did not upload a file!");
		exit;
	} else {
		//Check to see if file already exists in filesystem
		if (file_exists($uploaddir.$filename))
		{
			unlink($tempname);
			error("File of that name already exists");
			exit;
		} else {
		//Assume success and move temp to new loation and complete processing and display success to user	
			move_uploaded_file($tempname, $uploaddir.$filename);
			$summary_msg.= "<p>File has been successfully uploaded:<br \>";
			$summary_msg.= "<strong>$filename</strong></p>";
			
			//Add comments and author into filelog table
			dbConnect("planetg0_projects");	
			$sql =	"insert into filelog set 
					author='$username',
					project='$id',
					filename='$filename',
					comment='$newcomment'";
			if (!mysql_query($sql))
			{
				$summary_msg .= "<p>A database error occured when adding comments to the database: ".mysql_error()."</p>";
			} else {
				$summary_msg .= "<p>Comments have been added to the database</p>";
			}
			
			//Check to see if notification was called for, if so generate notification vars for $message, $comments,
			//$project and $link first since they are needed by notify.php to operate properly
			if (isset($_POST['notify']))
			{
				//Create strings for mail
				$message = "$username has uploaded the following project file(s):\n";
				$message .= "$filename\n";
				$project = $_POST['project'];
				$comments = $_POST['comment'];
				$link = "http://construction.charlotte-russe.com/index2.php?page=project&id=$id";
				//Call mail script
				include("notify.php");
				//Add to report
				$summary_msg .= "<p>The following message was e-mailed to corporate staff:</p>";
				$summary_msg .= "<div style=\"border:1px #369 solid;background:#fff\"><pre>$notice_text</pre></div>";
			}
			
			//Format success report
			echo "<div id=\"content\">";
				echo "<h1>:: Document added</h1>";
				echo "<div class=\"databox\">$summary_msg</div>";
			echo "<p>[<a href=\"index2.php?page=project&id=$id\">Return to this project page</a>]</p>";				
			echo "</div>";
		}
	}
break;

case "multiple":
	//Establish location name variables
	$newdir = "./filespace/$id/".$newfolder;

	//Validate to make sure user entered a folder name!
	if ($newfolder == "") {
		error("You must enter a name for this file distribution.");
		exit;
	}
	//Validate foldername for illegal chars!
	if(!checkname($newfolder)) {
		error("Illegal characters used in file distribution name.");
		exit;
	}
	//Validate to make sure folder does not already exist!
	if (file_exists($newdir)) {
		error("Folder of that name already exists");
		exit;
	}
	
	if (empty($_FILES['userfile']['name'][0])) {
		error("You must have at least one file in the top upload slot!");
		exixt;
	}
	
	//If those checks have passed, then script is still alive at this point, start with upload processing
	//Create folder!	
	if (!file_exists($newdir))
			mkdir($newdir,0777);

	$uploaddir = $newdir . "/";
	$summary_msg = "<p><strong>Upload report</strong></p><p>";
	$files_uploaded = "";	
	for($i=0; $i<count($HTTP_POST_FILES['userfile']['tmp_name']); $i++) { 
		$tempname = $HTTP_POST_FILES['userfile']['tmp_name'][$i]; 
		$filename = $HTTP_POST_FILES['userfile']['name'][$i]; 
		if($tempname != "") {
			if (move_uploaded_file($tempname, $uploaddir.$filename)) {
				$files_uploaded .= "$filename\n";
		    	$summary_msg .= "Uploaded: $filename<br />";
			}
		}
	}
	$summary_msg .= "</p>";
	
	//Add comments to the database!
	dbConnect();
	$sql =	"insert into distrolog set 
			author='$username',
			project='$id',
			distroname='$newfolder',
			comment='$newcomment'";
		
	if (!mysql_query($sql)) {
		$summary_msg .= "<p>A database error occured when adding comments to the database: ".mysql_error()."</p>";
	} else {
		$summary_msg .= "<p>Comments have been added to the database</p>";
	}
	
	//Check to see if notification was called for, if so generate notification vars for $message, $comments,
	//$project and $link first since they are needed by notify.php to operate properly
	if (isset($_POST['notify'])) {
		//Create strings for mail
		$message = "$username has created a new project folder: $newfolder \n\n";
		$message .= "Adding the following project files:\n";
		$message .= $files_uploaded;
		$project = $_POST['project'];
		$comments = $_POST['newcomment'];
		$link = "http://construction.charlotte-russe.com/index2.php?page=project&id=$id";
		//Call mail script
		include("notify.php");
		//Add to report
		$summary_msg .= "<p>The following message was e-mailed to corporate staff:</p>";
		$summary_msg .= "<div style=\"border:1px #369 solid;background:#fff\"><pre>$notice_text</pre></div>";
	}
			
	//Format success report
	echo "<div id=\"content\">";
		echo "<h1>:: Document added</h1>";
		echo "<div class=\"databox\">$summary_msg";
		echo "<p>[<a href=\"index2.php?page=project&id=$id\">Return to this project page</a>]</p>";
		echo "</div>";
	echo "</div>";
break;
case "zip":
	//Establish location name variables
	$newdir = "./filespace/$id/".$newfolder."/";

	//Validate to make sure user entered a folder name!
	if ($newfolder == "") {
		error("You must enter a name for this file distribution.");
		exit;
	}
	//Validate foldername for illegal chars!
	if(!checkname($newfolder)) {
		error("Illegal characters used in file distribution name.");
		exit;
	}
	//Validate to make sure folder does not already exist!
	if (file_exists($newdir)) {
		error("Folder of that name already exists");
		exit;
	}
	
	//If those checks have passed, then script is still alive at this point, start with upload processing
	//Create folder!	
	if (!file_exists($newdir))
			mkdir($newdir,0777);

	$summary_msg = "<p><strong>Upload report</strong></p>";
	
	$tempname = $HTTP_POST_FILES['userfile']['tmp_name'][0];
	$filename = $HTTP_POST_FILES['userfile']['name'][0];
	if (!is_uploaded_file($tempname))
	{
		unlink($tempname);
		$summary_msg .= "<p>Uploaded failed! Please try again...if you continue to get this message send this text to the administrator: </p>";
		$summary_msg .= "<pre>".print_r($HTTP_POST_FILES, true)."</pre>";
	} else {
		// Very that this is a zip file, if not exit!
		$extension = substr($filename, -3);
		$extension = strtolower($extension);
		if ($extension!="zip")
		{
			error("You must upload a zip file if you are using this method!");
			exit;
		}
		// Move it to the real location
		move_uploaded_file($tempname, $uploaddir.$filename);
		//Extract the zip into the uploaddir		
		include("zip.lib.php");
		$zip = new Zip;
		$zip -> Extract($uploaddir.$filename,$uploaddir,Array(-1));
		//Delete the zip archive		
		unlink($uploaddir.$filename);
		$summary_msg .= "<p>Zip file successfully uploaded and extracted:</p>";
		//List files in new folder
		$filespace = "filespace/$id/$newfolder";
		$files = array();
		$dirs  = array();
		$handle=opendir($filespace);
		while (false !== ($file = readdir($handle))) {
			if($file=='.'||$file=='..'||$file=='.htaccess')
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
		//Write filenames to summary report and notice message!
		foreach($files as $key => $val)
		{
		$summary_msg .= "$val<br />";
		$filesuploaded .= "$val\n";
		}
		
		//Add comments to the database!
		dbConnect();
		$sql =	"insert into distrolog set 
			author='$username',
			project='$id',
			distroname='$newfolder',
			comment='$newcomment'";
		
		if (!mysql_query($sql))
		{
			$summary_msg .= "<p>A database error occured when adding comments to the database: ".mysql_error()."</p>";
		} else {
			$summary_msg .= "<p>Comments have been added to the database</p>";
		}
	
		//Check to see if notification was called for, if so generate notification vars for $message, $comments,
		//$project and $link first since they are needed by notify.php to operate properly
		if (isset($_POST['notify'])) {
			//Create strings for mail
			$message = "$username has uploaded the following project file(s):\n";
			$message .= $filesuploaded;
			$message .= "To a new project folder named: $newfolder\n";
			$project = $_POST['project'];
			$comments = $_POST['comment'];
			$link = "http://construction.charlotte-russe.com/index2.php?page=project&id=$id";
			//Call mail script
			include("notify.php");
			//Add to report
			$summary_msg .= "<p>The following message was e-mailed to corporate staff:</p>";
			$summary_msg .= "<div style=\"border:1px #369 solid;background:#fff\"><pre>$notice_text</pre></div>";
		}
	}			
			//Format success report
			echo "<div id=\"content\">";
				echo "<h1>:: Document added</h1>";
				echo "<div class=\"databox\">$summary_msg</div>";
			echo "<p>[<a href=\"index2.php?page=project&id=$id\">Return to this project page</a>]</p>";				
			echo "</div>";

break;

case "photos":
break;
}
?>
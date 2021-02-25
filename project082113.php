<?php
include("little-helpers.php");

$status = "";
$psq = "SELECT project_status FROM projects WHERE id='".$_GET['id']."'";
$psrslt = mysql_query($psq);
if (!$psrslt) 
{}
else
{
$row = mysql_fetch_assoc($psrslt);
$status = $row['project_status'];
}

if(isset($_GET['mode'])) {
	$mode = $_GET['mode']; 
} else {
	$mode = NULL;
}
switch($mode) {
case "single":
	$id = $_POST['project_id'];
	$sitename = $_POST['project_name'];
	$newcomment = $_POST['new_file_comment'];
	
	//Trim for whitespace
	$newcomment=rtrim($newcomment);$newcomment=ltrim($newcomment);

	//Establish location name variables
	$uploaddir = "./filespace/$id/";
	chmod($uploaddir, 0777);  // octal; correct value of mode
	$tempname = $_FILES['userfile']['tmp_name'][0];
	$filename = $_FILES['userfile']['name'][0];
	
	$summary_msg = "";

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
			$summary_msg.= "<p>File has been successfully uploaded:<br \>";
			$summary_msg.= "<strong>$filename</strong></p>";
			
			//Add comments and author and vendors into filelog table
			dbConnect();
			if (isset($_POST['vendors'])){
				$vendor_array = serialize($_POST['vendors']);
			} else {
				$vendor_array = "";
			}
			$sql =	"insert into filelog set 
					author='$username',
					project='$id',
					filename='$filename',
					vendor = '$vendor_array',
					comment='$newcomment'";
			if (!mysql_query($sql)) {
				$summary_msg .= "<p>A database error occured when adding comments to the database: </p>";
				$summary_msg .= "<p><small>".mysql_error()."</small></p>";
			}
			
			//Check to see if notification was called for, if so generate notification vars for $message, $comments,
			//$project and $link first since they are needed by notify.php to operate properly
			if (isset($_POST['vendors']))
			{
				//Create strings for mail
				$message = "$username has uploaded the following project file(s):\n";
				$message .= "$filename\n";
				$project = $sitename;
				$comments = $newcomment;
				$link = "http://construction.charlotte-russe.com/index.php?page=project&id=$id";
				//invite selected vendors
				$invite_select_vendors = TRUE;
				//Call mail script
				include("notify.php");
				//Add to report
				$summary_msg .= "<p>The following message was e-mailed to:  $addresses</p>";
				$summary_msg .= "<div style=\"border:1px #369 solid;background:#fff\"><pre>$notice_text</pre></div>";
			}
			
			//Format success report
			$message = <<<HTML
			<h1>:: Upload Report</h1>
			<div class="databox">
			<table width="100%" align="center" class="filebox">
			<tr>
				<td width="70"><img src="images/avatar_single.gif" /></td>
				<td>$summary_msg</td>
			</tr>
			</table>
			</div>
HTML;
	}
break;

case "multiple":
	$id = $_POST['project_id'];
	$sitename = $_POST['project_name'];
	$newfolder = $_POST['new_folder_name'];
	$newcomment = $_POST['new_folder_comment'];
	
	//Remove trailing and leading whitespace from folder name and comment !
	$newfolder=ltrim($newfolder);$newfolder=rtrim($newfolder);
	$newcomment=ltrim($newcomment);$newcomment=rtrim($newcomment);
	$summary_msg = "";
	
	//Establish location name variables
	$newdir = "./filespace/$id/".$newfolder;

	//Validate to make sure folder does not already exist!
	if (file_exists($newdir)) {
		$summary_msg .= "<p><strong>Warning:</strong> Folder of that name already exists. An underscore was added to the name you entered...</p>";
		while (file_exists($newdir)) {
		$newfolder = "_".$newfolder;
		$newdir = "./filespace/$id/".$newfolder;
		}
	}

	//If checks have passed, then script is still alive at this point, start with upload processing
	//Create folder!	
	if (!file_exists($newdir))
			mkdir($newdir,0777);
	$summary_msg .= "<p>";
	$uploaddir = $newdir . "/";
	$files_uploaded = "";	
	for($i=0; $i<count($_FILES['userfile']['tmp_name']); $i++) { 
		$tempname = $_FILES['userfile']['tmp_name'][$i]; 
		$filename = $_FILES['userfile']['name'][$i]; 
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
	if (isset($_POST['vendors'])){
		$vendor_array = serialize($_POST['vendors']);
	} else {
		$vendor_array = "";
	}	
	$sql =	"insert into distrolog set 
			author='$username',
			project='$id',
			distroname='$newfolder',
			vendor = '$vendor_array',
			comment='$newcomment'";
		
	if (!mysql_query($sql)) {

		$summary_msg .= "<p>A database error occured when adding comments to the database: </p>";
		$summary_msg .= "<p><small>".mysql_error()."</small></p>";
	}
	
	//Check to see if notification was called for, if so generate notification vars for $message, $comments,
	//$project and $link first since they are needed by notify.php to operate properly
	if (isset($_POST['vendors'])) {
		//Create strings for mail
		$message = "$username has created a new project folder: $newfolder \n\n";
		$message .= "Adding the following project files:\n";
		$message .= $files_uploaded;
		$project = $sitename;
		$comments = $newcomment;
		$newfolderformmated = str_replace(" ", "%20", $newfolder);		
		$link = "http://construction.charlotte-russe.com/index.php?page=folder&id=$id&name=$newfolderformmated";
		//invite selected vendors
		$invite_select_vendors = TRUE;
		//Call mail script
		include("notify.php");
		//Add to report
		$summary_msg .= "<p>The following message was e-mailed to:  $addresses</p>";
		$summary_msg .= "<div style=\"border:1px #369 solid;background:#fff\"><pre>$notice_text</pre></div>";
	}


			
	//Format success report
		$message = <<<HTML
		<h1>:: Upload Report</h1>
		<div class="databox">
		<table width="100%" align="center" class="filebox">
		<tr>
			<td width="70"><img src="images/avatar_folder.gif" /></td>
			<td>$summary_msg</td>
		</tr>
		</table>
		</div>
HTML;
break;
case "zip":
	$id = $_POST['project_id'];
	$sitename = $_POST['project_name'];
	$newfolder = $_POST['new_folder_name'];
	$newcomment = $_POST['new_folder_comment'];
	
	//Remove trailing and leading whitespace from folder name and comment !
	$newfolder=ltrim($newfolder);$newfolder=rtrim($newfolder);
	$newcomment=ltrim($newcomment);$newcomment=rtrim($newcomment);

	//Establish location name variables
	$newdir = "./filespace/$id/".$newfolder."/";
	//Validate to make sure folder does not already exist!
	if (file_exists($newdir)) {
		$summary_msg .= "<p><strong>Warning:</strong> Folder of that name already exists. An underscore was added to the name you entered...</p>";
		while (file_exists($newdir)) {
		$newfolder = "_".$newfolder;
		$newdir = "./filespace/$id/".$newfolder."/";
		}
	}
	
	//If those checks have passed, then script is still alive at this point, start with upload processing
	//Create folder!	
	if (!file_exists($newdir))
			mkdir($newdir,0777);

	$summary_msg = "";
	
	$tempname = $_FILES['userfile']['tmp_name'][0];
	$filename = $_FILES['userfile']['name'][0];
	if (!is_uploaded_file($tempname))
	{
		unlink($tempname);
		$summary_msg .= "<p>Uploaded failed! Please try again...if you continue to get this message send this text to the administrator: </p>";
		$summary_msg .= "<pre>".print_r($_FILES, true)."</pre>";
	} else {
		// Move it to the real location
		move_uploaded_file($tempname, $newdir.$filename);


//Extract the zip into the uploaddir		
//		include("zip.lib.php");
//		$zip = new Zip;
//		$zip->Extract($newdir.$filename,$newdir,Array(-1));


  include('pclzip.lib.php');
  $archive = new PclZip($newdir.$filename);
  if ($archive->extract(PCLZIP_OPT_PATH, $newdir) == 0) {
    die("Error : ".$archive->errorInfo(true));
  }


		//Delete the zip archive		
		unlink($newdir.$filename);
		$summary_msg .= "<p>ZIP file successfully uploaded and extracted:</p>";
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
		if (isset($_POST['vendors'])){
			$vendor_array = serialize($_POST['vendors']);
		} else {
			$vendor_array = "";
		}		
		$sql =	"insert into distrolog set 
			author='$username',
			project='$id',
			distroname='$newfolder',
			vendor = '$vendor_array',
			comment='$newcomment'";
		
		if (!mysql_query($sql)) {
		$summary_msg .= "<p>A database error occured when adding comments to the database: </p>";
		$summary_msg .= "<p><small>".mysql_error()."</small></p>";
		}
	
		//Check to see if notification was called for, if so generate notification vars for $message, $comments,
		//$project and $link first since they are needed by notify.php to operate properly
		if (isset($_POST['vendors'])) {
			//Create strings for mail
			$message = "$username has uploaded the following project file(s):\n";
			$message .= $filesuploaded;
			$message .= "To a new project folder named: $newfolder\n";
			$project = $sitename;
			$comments = $newcomment;
			//Format project distro name for URL
			$newfolderformmated = str_replace(" ", "%20", $newfolder);		
			$link = "http://construction.charlotte-russe.com/index.php?page=folder&id=$id&name=$newfolderformmated";
			//invite selected vendors
			$invite_select_vendors = TRUE;
			//Call mail script
			include("notify.php");
			//Add to report
			$summary_msg .= "<p>The following message was e-mailed to:  $addresses</p>";
	
			$summary_msg .= "<div style=\"border:1px #369 solid;background:#fff\"><pre>$notice_text</pre></div>";
		}
	}			
			//Format success report
			$message = <<<HTML
			<h1>:: Upload Report</h1>
			<div class="databox">
			<table width="100%" align="center" class="filebox">
			<tr>
				<td width="70"><img src="images/avatar_single.gif" /></td>
				<td>$summary_msg</td>
			</tr>
			</table>
			</div>
HTML;
break;

case "gallery":
	$id = $_POST['project_id'];
	$sitename = $_POST['project_name'];
	$newfolder = $_POST['new_folder_name'];
	$newcomment = $_POST['new_folder_comment'];
	
	//Remove trailing and leading whitespace from folder name and comment !
	$newfolder=ltrim($newfolder);
	$newfolder=rtrim($newfolder);
	
	$newcomment=ltrim($newcomment);
	$newcomment=rtrim($newcomment);

	//Establish location name variables
	$uploaddir = "./filespace/$id/photos/".$newfolder;
	//Validate to make sure folder does not already exist!
	if (file_exists($newdir)) {
		$summary_msg .= "<p><strong>Warning:</strong> Album of that name already exists. An underscore was added to the name you entered...</p>";
		while (file_exists($uploaddir)) {
		$newfolder = "_".$newfolder;
		$uploaddir = "./filespace/$id/photos/".$newfolder;
		}
	}
	//$thumbsdir = $uploaddir."/thumbs";	
	//If those checks have passed, then script is still alive at this point, start with upload processing
	//Create folder!	
	if (!file_exists($uploaddir)) { mkdir($uploaddir, 0777); }
	//if (!file_exists($thumbsdir)) {mkdir($thumbsdir, 0777);}

	$summary_msg = "";
	
	$tempname = $_FILES['userfile']['tmp_name'][0];
	$filename = $_FILES['userfile']['name'][0];
	if (!is_uploaded_file($tempname))
	{
		unlink($tempname);
		$summary_msg .= "<p>Uploaded failed! Please try again...if you continue to get this message send this text to the administrator: </p>";
		$summary_msg .= "<pre>".print_r($_FILES, true)."</pre>";
	} else {
		// Move it to the real location
		move_uploaded_file($tempname, $uploaddir.$filename);
//Extract the zip into the uploaddir		
//		include("zip.lib.php");
//		$zip = new Zip;
//		$zip -> Extract($uploaddir.$filename,$uploaddir,Array(-1));
//		$zip -> Extract($uploaddir.$filename,$thumbsdir,Array(-1));

		  include('pclzip.lib.php');
		  $archive = new PclZip($uploaddir.$filename);
		  if ($archive->extract(PCLZIP_OPT_PATH, $uploaddir) == 0) {
			die("Error : ".$archive->errorInfo(true));
		  }
		 /* 
		  if ($archive->extract(PCLZIP_OPT_PATH, $thumbsdir) == 0) {
			die("Error : ".$archive->errorInfo(true));
		  }
		  */
		//Delete the zip archive		
		unlink($uploaddir.$filename);
	
		//Create gallery preview
		$files = array();
		$dirs  = array();
		$handle=opendir($uploaddir);
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

		/*
		if (ereg(" ", $thumbsdir) == 1)
 			$esc_thumbsdir = ereg_replace(" ", "\ ", $thumbsdir);
		else
			$esc_thumbsdir = $thumbsdir;
		*/

		if (ereg(" ", $uploaddir) == 1)
 			$esc_uploaddir = ereg_replace(" ", "\ ", $uploaddir);
		else
			$esc_uploaddir = $uploaddir;

			
		foreach($files as $key => $val)
		{
			$ext = substr($val, -3);
			$ext = strtolower($ext);
			if ($ext != "jpg")
				continue;
			if (ereg(" ", $val) == 1)
 				    	$val = ereg_replace(" ", "\ ", $val);

			if (ereg("'", $val) == 1)
 				    	$val = ereg_replace("'", "\'", $val);

			//system("convert -geometry 150x150 $esc_thumbsdir/$val $esc_thumbsdir/$val 2>&1", $output);
			system("convert -geometry 1024x768 $esc_uploaddir/$val $esc_uploaddir/$val 2>&1", $output);
         

		}

		$summary_msg .= "<p>Photos added to album:</p>";
		$summary_msg .= "<table width=\"99%\" align=\"center\" class=\"litezone\"><tr>";
		$tablecol=0;
		foreach($files as $key => $val)
		{
			$ext = substr($val, -3);
			$ext = strtolower($ext);
			/*
			if ($ext != "jpg") {
				$thumbpath = "images/jpgonly.jpg";
			} else {
				$thumbpath =$thumbsdir."/".$val;
			}
			*/
			//$summary_msg .= "<td style=\"text-align:center;\"><img src=\"$thumbpath\" border=\"0\" /></td>";
			$tablecol++;
			if ($tablecol == 3)
			{
				$summary_msg .= "</tr><tr>";
				$tablecol=0;
			}
		}
		$summary_msg .= "</tr></table>";

		
		//Add comments to the database!
		dbConnect();
		$sql =	"insert into gallerylog set 
			author='$username',
			project='$id',
			galleryname='$newfolder',
			comment='$newcomment'";
		
		if (!mysql_query($sql)) {
		$summary_msg .= "<p>A database error occured when adding comments to the database: </p>";
		$summary_msg .= "<p><small>".mysql_error()."</small></p>";
		} else {
		$summary_msg .= "<p>Comments and author have been added to the database</p>";
		}
	
		//Check to see if notification was called for, if so generate notification vars for $message, $comments,
		//$project and $link first since they are needed by notify.php to operate properly
		if (isset($_POST['notify'])) {
			//Create strings for mail
			$message = "$username has uploaded new photos\n";
			$message .= "to a new project album named: $newfolder\n";
			$project = $sitename;
			$comments = $newcomment;
			$link = "http://construction.charlotte-russe.com/index.php?page=project&id=$id";

			//do not invite other vendors if this is being uploaded by another vendor. 
			if ($usergroup == 3) {
				$invite_vendors = FALSE;
			} else {
				$invite_vendors = TRUE;
			}
			
			//Call mail script
			include("notify.php");
			//Add to report
			$summary_msg .= "<p>The following message was e-mailed to all users attached to this project:</p>";
			$summary_msg .= "<div style=\"filebox\"><pre>$notice_text</pre></div>";
		}
	}			
			//Format success report and display thumbs
			$message = <<<HTML
			<h1>:: Photo Upload Report</h1>
			<div class="databox">
			<table width="100%" align="center" class="filebox">
			<tr>
				<td width="70"><img src="images/avatar_single.gif" /></td>
				<td>$summary_msg</td>
			</tr>
			</table>
			</div>
HTML;
break;

case "misc":
	$id = $_POST['project_id'];
	$sitename = $_POST['project_name'];
	$newcomment = $_POST['new_file_comment'];
	
	//Trim for whitespace
	$newcomment=rtrim($newcomment);
	$newcomment=ltrim($newcomment);

	//Establish location name variables
	$uploaddir = "./filespace/$id/misc/";
	$tempname = $_FILES['userfile']['tmp_name'][0];
	$filename = $_FILES['userfile']['name'][0];
	
	$summary_msg = "";

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
			$summary_msg.= "<p>File has been successfully uploaded:<br \>";
			$summary_msg.= "<strong>$filename</strong></p>";
			
			//Add comments and author into filelog table
			dbConnect();	
			$sql =	"insert into misclog set 
					author='$username',
					project='$id',
					filename='$filename',
					comment='$newcomment'";
			if (!mysql_query($sql)) {
				$summary_msg .= "<p>A database error occured when adding comments to the database: </p>";
				$summar_msg .= "<p><small>".mysql_error()."</small></p>";
			} else {
				$summary_msg .= "<p>Comments have been added to the database</p>";
			}
			
			//Check to see if notification was called for, if so generate notification vars for $message, $comments,
			//$project and $link first since they are needed by notify.php to operate properly
			if (isset($_POST['notify']))
			{
				//Create strings for mail
				$message = "$username has uploaded the following misc file:\n";
				$message .= "$filename\n";
				$project = $sitename ." misc documents\n";
				$comments = $newcomment;
				$link = "http://construction.charlotte-russe.com/index.php?page=project&id=$id";
				//Keep Vendors out
				$invite_vendors = FALSE;
				//Call mail script
				include("notify.php");
				//Add to report
				$summary_msg .= "<p>The following message was e-mailed to corporate staff:</p>";
				$summary_msg .= "<div style=\"border:1px #369 solid;background:#fff\"><pre>$notice_text</pre></div>";
			}
			
			//Format success report
			$message = <<<HTML
			<h1>:: Upload Report</h1>
			<div class="databox">
			<table width="100%" align="center" class="filebox">
			<tr>
				<td width="70"><img src="images/avatar_single.gif" /></td>
				<td>$summary_msg</td>
			</tr>
			</table>
			</div>
HTML;
	}
break;



case "budget":
	$id = $_POST['project_id'];
	$sitename = $_POST['project_name'];
	$newcomment = $_POST['new_file_comment'];
	
	//Trim for whitespace
	$newcomment=rtrim($newcomment);
	$newcomment=ltrim($newcomment);

	//Establish location name variables
	$uploaddir = "./filespace/$id/budget/";
	$tempname = $_FILES['userfile']['tmp_name'][0];
	$filename = $_FILES['userfile']['name'][0];
	
	$summary_msg = "";

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
			$summary_msg.= "<p>File has been successfully uploaded:<br \>";
			$summary_msg.= "<strong>$filename</strong></p>";
			
			//Add comments and author into filelog table
			dbConnect();	
			$sql =	"insert into budgetlog set 
					author='$username',
					project='$id',
					filename='$filename',
					comment='$newcomment'";
			if (!mysql_query($sql)) {
				$summary_msg .= "<p>A database error occured when adding comments to the database: </p>";
				$summar_msg .= "<p><small>".mysql_error()."</small></p>";
			} else {
				$summary_msg .= "<p>Comments have been added to the database</p>";
			}
			
			//Check to see if notification was called for, if so generate notification vars for $message, $comments,
			//$project and $link first since they are needed by notify.php to operate properly
			if (isset($_POST['notify']))
			{
				//Create strings for mail
				$message = "$username has uploaded the following budget file:\n";
				$message .= "$filename\n";
				$project = $sitename ." budget documents\n";
				$comments = $newcomment;
				$link = "http://construction.charlotte-russe.com/index.php?page=project&id=$id";
				//Keep Vendors out
				$invite_vendors = FALSE;
				//Call mail script
				include("notify.php");
				//Add to report
				$summary_msg .= "<p>The following message was e-mailed to corporate staff:</p>";
				$summary_msg .= "<div style=\"border:1px #369 solid;background:#fff\"><pre>$notice_text</pre></div>";
			}
			
			//Format success report
			$message = <<<HTML
			<h1>:: Upload Report</h1>
			<div class="databox">
			<table width="100%" align="center" class="filebox">
			<tr>
				<td width="70"><img src="images/avatar_single.gif" /></td>
				<td>$summary_msg</td>
			</tr>
			</table>
			</div>
HTML;
	}
break;


case "vendor_single":
	$id = $_POST['project_id'];
	$sitename = $_POST['project_name'];
	$newcomment = $_POST['new_file_comment'];
	
	//Trim for whitespace
	$newcomment=rtrim($newcomment);
	$newcomment=ltrim($newcomment);

	//Establish location name variables
	$uploaddir = "./filespace/$id/vendor/$usercompany/";
	$tempname = $_FILES['userfile']['tmp_name'][0];
	$filename = $_FILES['userfile']['name'][0];
	
	$summary_msg = "";

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
			$summary_msg.= "<p>File has been successfully uploaded:<br \>";
			$summary_msg.= "<strong>$filename</strong></p>";
			
			//Add comments and author into filelog table
			dbConnect();	
			$sql =	"insert into vendor_filelog set 
					author='$username',
					project='$id',
					filename='$filename',
					comment='$newcomment'";
			if (!mysql_query($sql)) {
				$summary_msg .= "<p>A database error occured when adding comments to the database: </p>";
				$summar_msg .= "<p><small>".mysql_error()."</small></p>";
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
				$project = $sitename;
				$comments = $newcomment;
				$link = "http://construction.charlotte-russe.com/index.php?page=project&id=$id";
				
				//Keep Vendors out
				$invite_vendors = FALSE;
				
				//Notify only Rick, Lance and Gene  cc: me and paul
				$invite_core_only = TRUE;
				
				//Call mail script
				include("notify.php");
				//Add to report
				$summary_msg .= "<p>The following message was e-mailed to corporate staff:</p>";
				$summary_msg .= "<div style=\"border:1px #369 solid;background:#fff\"><pre>$notice_text</pre></div>";
			}
			
			//Format success message
		$message = <<<HTML
		<h1>:: Upload Report</h1>
		<div class="databox">
		<table width="100%" align="center" class="filebox">
		<tr>
			<td width="70"><img src="images/avatar_single.gif" /></td>
			<td>$summary_msg</td>
		</tr>
		</table>
		</div>
HTML;
	}
break;

case "change_order":
	$id = $_POST['project_id'];
	$sitename = $_POST['project_name'];
	$store_number = $_POST['store_number'];
	$pending_amt=0;

	if(isset($_POST['li1_desc'])) {
		$desc1 = rtrim($_POST['li1_desc']);
		$desc1 = ltrim($desc1);
		$cost1 = $_POST['li1_cost'];
		$pending_amt = $cost1 + $pending_amt;
		$status1 = "Pending";
		}

	if(isset($_POST['li2_desc'])) {
		$desc2 = rtrim($_POST['li2_desc']);
		$desc2 = ltrim($desc2);
		$cost2 = $_POST['li2_cost'];
		$pending_amt = $cost2 + $pending_amt;		
		$status2 = "Pending";
		}

	if(isset($_POST['li3_desc'])) {
		$desc3 = rtrim($_POST['li3_desc']);
		$desc3 = ltrim($desc3);
		$cost3 = $_POST['li3_cost'];
		$pending_amt = $cost3 + $pending_amt;		
		$status3 = "Pending";
		}

	if(isset($_POST['li4_desc'])) {
		$desc4 = rtrim($_POST['li4_desc']);
		$desc4 = ltrim($desc4);
		$cost4 = $_POST['li4_cost'];
		$pending_amt = $cost4 + $pending_amt;		
		$status4 = "Pending";
		}

	if(isset($_POST['li5_desc'])) {
		$desc5 = rtrim($_POST['li5_desc']);
		$desc5 = ltrim($desc5);
		$cost5 = $_POST['li5_cost'];
		$pending_amt = $cost5 + $pending_amt;		
		$status5 = "Pending";
		}

	if(isset($_POST['li6_desc'])) {
		$desc6 = rtrim($_POST['li6_desc']);
		$desc6 = ltrim($desc6);
		$cost6 = $_POST['li6_cost'];
		$pending_amt = $cost6 + $pending_amt;		
		$status6 = "Pending";
		}


	$summary_msg = "";
	
	//Add comments and author into filelog table
	dbConnect();
	$q = mysql_query("select * from change_orders where loc_key = $id");
	$qc = mysql_num_rows($q);
	$tracking = $qc+1;

	
	
	$sql =	"insert into change_orders set 
			author_key='$unique_user_id',
			company_key='$usercompany',
			loc_key='$id',
			co_num=LPAD('$tracking',4,'000'),
			li1_desc='$desc1',
			li2_desc='$desc2',
			li3_desc='$desc3',
			li4_desc='$desc4',
			li5_desc='$desc5',
			li6_desc='$desc6',
			li1_cost='$cost1',
			li2_cost='$cost2',
			li3_cost='$cost3',
			li4_cost='$cost4',
			li5_cost='$cost5',
			li6_cost='$cost6',
			li1_status='$status1',
			li2_status='$status2',
			li3_status='$status3',
			li4_status='$status4',
			li5_status='$status5',
			li6_status='$status6',
			date = CURDATE()";

	if (!mysql_query($sql)) {
		$summary_msg .= "<p>A database error occured when adding comments to the database: </p>";
		$summar_msg .= "<p><small>".mysql_error()."</small></p>";
	} else {
		$summary_msg .= "<p>Comments have been added to the database</p>";
	}
	
	//Check to see if notification was called for, if so generate notification vars for $message, $comments,
	//$project and $link first since they are needed by notify.php to operate properly
	if (isset($_POST['notify']))
	{
		//Create strings for mail
		$message = "$username has submitted change order: #". str_pad($tracking, 4, "0", STR_PAD_LEFT)."\n";
		$project = $sitename ."\n";
		$subject = "Change Order: #". str_pad($tracking, 4, "0", STR_PAD_LEFT)." for #$store_number $project";
		$link = "http://construction.charlotte-russe.com/index.php?page=project&id=$id";
		$addresses = "ltoerien@charlotte-russe.com,otilio.rivera@charlotterusse.com,mack.williams@charlotterusse.com,gmoring@charlotte-russe.com,ralphr@charlotte-russe.com";

		$notice_text = "Greetings,
		$message
		
		For the the following project: $project
		
		Total Pending Cost: $".number_format($pending_amt,2)."
		
		Use this URL: $link
		
		This was an automated message.
		http:/"."/construction.charlotte-russe.com";
		
		
		mail($addresses, $subject, $notice_text, "From:Collaboration Network <no-reply@charlotte-russe.com>");
		
		//Add to report
		$summary_msg .= "<p>The following message was e-mailed to corporate staff:</p>";
		$summary_msg .= "<div style=\"border:1px #369 solid;background:#fff\"><pre>$notice_text</pre></div>";
	}
	
	//Format success message
		$message = <<<HTML
<strong>Change Order Submit Report</strong><br />$summary_msg
HTML;


break;

case "del":
	$id = $_POST['project_id'];
	
	if (isset($_POST['del_file_name'])) {	
		$filepath = $_POST['del_file_path'];
		$filename = $_POST['del_file_name'];
		$delcomments = "filelog";	
	}
	
	if (isset($_POST['delbudg_file_name'])) {
		$filepath = $_POST['delbudg_file_path'];
		$filename = $_POST['delbudg_file_name'];
		$delcomments = "budgetlog";
	}
	
	if (isset($_POST['delmisc_file_name'])) {
		$filepath = $_POST['delmisc_file_path'];
		$filename = $_POST['delmisc_file_name'];
		$delcomments = "misclog";
	}
	
	if (isset($_POST['delvendorfile_name'])) {
		$filepath = $_POST['delvendorfile_path'];
		$filename = $_POST['delvendorfile_name'];
		$delcomments = "vendor_filelog";
	}
	

		
	if(unlink($filepath)) {
		dbConnect();
		$sql = "delete from $delcomments where filename='$filename' and project = $id";
		if (!mysql_query($sql)) {
			$summary_msg .= "<p>A database error occured when removing comments to the database: </p>";
			$summary_msg .= "<p><small>".mysql_error()."</small></p>";
		} else {
			$summary_msg .= "<p>Comments have been removed from the database</p>";
		}

		$message = <<<HTML
		<h1>:: File Deleted</h1>
		<div class="databox">
		<table width="100%" align="center" class="filebox">
		<tr>
			<td width="70"><img src="images/avatar_clean.gif" /></td>
			<td>
				<p>File has been removed successfully:<br /><strong>$filename</strong></p>
				$summary_msg
			</td>
		</tr>
		</table>
		</div>
HTML;
	} else {
		$message = <<<HTML
<strong>There was a problem</strong><br /> Unable to remove your file! There might be issues with the file permissions, contact the admin!
HTML;
	}
break;

case "post_comment":
	echo "<div id=\"content\">";
	$id = $_POST['id'];
	if (isset($_POST['re_comment']) && $_POST['re_comment'] != "")
	{
		$re_comment = mysql_real_escape_string($re_comment);
		$re_category = mysql_real_escape_string($re_category);
		$q = "INSERT INTO re_comments (project_id, user_id, body, category) VALUES ({$id}, '{$unique_user_id}', '{$re_comment}', '{$re_category}')";
		$rslt = mysql_query($q);
		if (!$rslt) 
		{
			$summary_msg = "A database error has occured while saving the comment.";
		}
		else
		{
			$summary_msg = "Your comment was successfully uploaded.";
		}
	}
	
		$message = <<<HTML
<strong>Comment Report</strong><br />$summary_msg
<div><a href="?page=project&id=$id">Return to Project Page</a></div>
HTML;

break;

case "delete_comment":
	echo "<div id=\"content\">";
	$cid = $_GET['cid'];
	if (isset($cid) && $cid != "")
	{
		$q = "DELETE FROM re_comments WHERE id={$cid}";
		$rslt = mysql_query($q);
		if (!$rslt) 
		{
			$summary_msg = "A database error has occured while deleting the comment.";
		}
		else
		{
			$summary_msg = "Your comment was successfully deleted.";
		}
	}
	
		$message = <<<HTML
<strong>Delete Report</strong><br />$summary_msg
<div><a href="?page=project&id=$id">Return to Project Page</a></div>
HTML;
	


break;

}


?>


<div id="content" class="project-page">
	
	
	<?php if(isset($message)): ?>
		<div class="alert message"><?php print $message; ?></div>
	<?php endif; ?>
	
	<div id="ajax-message"></div>

<?php if(($mode == "post_comment") || ($mode == "delete_comment")): ?>
<!--Do nothing  -->

<?php else: ?>
	<div class="well">
	<a class="btn btn-mini" id="expand-all"><i class="icon-plus"></i>Expand All</a>
	<a class="btn btn-mini" id="collapse-all"><i class="icon-minus"></i>Collapse All</a>
	<a class="btn btn-mini pull-right" href="/construction_schedule_summary_report.php?id=<?=$id?>"><i class="icon-list"></i>Summary View</a>
	</div>
	
	<div class="pane open" id="project-summary">
		<div class="pane-header">Summary<span class="symbol"><i class="icon-chevron-down"></i></span></div>
		<div class="pane-content"><?php include('project_summary.php'); ?></div>
	</div>


		<div class="pane closed" id="project-schedule">
			<div class="pane-header">Schedule<span class="symbol"><i class="icon-chevron-right"></i></span></div>
			<div class="pane-content"><?php include("project_schedule.php"); ?></div>
		</div>

	<?php if($usercompany < 10): ?>

		<div class="pane closed" id="project-comments">
			<div class="pane-header">Comments<span class="symbol"><i class="icon-chevron-right"></i></span></div>
			<div class="pane-content"><?php include("project_comments.php"); ?></div>
		</div>

		<div class="pane closed" id="project-real-estate">
				<div class="pane-header">Real Estate<span class="symbol"><i class="icon-chevron-right"></i></span></div>
			<div class="pane-content"><?php include("realestate_summary.php"); ?></div>
		</div>		

		<div class="pane closed" id="project-store-attr">
				<div class="pane-header">Store Attributes<span class="symbol"><i class="icon-chevron-right"></i></span></div>
			<div class="pane-content"><?php include("project_store_attr.php"); ?></div>
		</div>	
	<?php endif; ?>

	<?php if($usercompany != 6): ?>
		<div class="pane closed" id="project-files">
			<div class="pane-header">Drawings &amp; Specs<span class="symbol"><i class="icon-chevron-right"></i></span></div>
			<div class="pane-content"><?php include("project_files.php"); ?></div>
		</div>
			
		<div class="pane closed" id="project-photos">
			<div class="pane-header">Photos<span class="symbol"><i class="icon-chevron-right"></i></span></div>
			<div class="pane-content"><?php include("project_photos.php"); ?></div>	
		</div>

		<div class="pane closed" id="project-vendor">
			<div class="pane-header">Vendor Files <span class="symbol"><i class="icon-chevron-right"></i></span></div>
			<div class="pane-content"><?php include("project_vendor.php"); ?></div>
		</div>
		
	<?php if($usercompany < 10): ?>
		<div class="pane closed" id="project-change-orders">
			<div class="pane-header">Change Orders<span class="symbol"><i class="icon-chevron-right"></i></span></div>
			<div class="pane-content"><?php include("project_change_orders.php"); ?></div>
		</div>
	
		<div class="pane closed" id="project-documents">
			<div class="pane-header">Misc. Documents <span class="symbol"><i class="icon-chevron-right"></i></span></div>
			<div class="pane-content"><?php include("project_documents.php"); ?></div>
		</div>	
	<?php endif; ?>

		
	<?php //if( $_GET['rfi'] == 1 ): //SPECIAL RFI DEBUG TEST MODE. WHEN DONE TAKE RFI OUT OF CONDITOINAL AND MAKE IT APPEAR FOR EVERYONE ?>
		<div class="pane closed" id="project-rfi">
			<div class="pane-header">RFI <span class="symbol"><i class="icon-chevron-right"></i></span></div>
			<div class="pane-content"><?php include("rfi.php"); ?></div>
		</div>
	<?php //endif; ?>


	<?php endif; ?>
<?php endif; ?>	


	<div class="well">
	<a class="btn" href="index.php">&laquo; Go back to home page</a>
	</div>
	
	

</div>
<script type="text/javascript">
$(document).ready(function() {

	$(".edit").prop('disabled', true);

<?php 

	if (($usergroup < 1) or (in_array("plans",$roles))){
		$can_edit = TRUE;
		if($usergroup < 2) {
			$field_selector = 'edit';
		} else {
			$field_selector = 'arch';
		}

	}
?>


<?php if($can_edit): ?>	
	
	$(".<?=$field_selector?>").prop('disabled', false);


	function closeField() {
		//Return to init value...
		$('.active-input').val( $('.active-input').attr('data-init-value') );				
		$('.active-input').removeClass('active-input');
		$('.active-controls').removeClass('active-controls');		
		$('#field-btns').remove();
	}

	function getTableName(code) {
		switch(code) {
		case 'r':
			return 'realestate';
		case 't':
			return 're_strategy';
		case 'd':
			return 're_storedesign';
		case 'c':
			return 're_centerinfo';
		case 'o':
			return 're_options';
		case 'k':
			return 're_kickouts';
		case 'a':
			return 'actual_dates';
		case 's': 
			return 'scheduled_dates';
		case 'p': 
			return 'projects';				
		}
	}

	
	function ajaxSuccess() {
	}

	
	$("#project-schedule-form input").datepicker({showAnim:"slideDown"});

	    $('input.percent').autoNumeric( {aSign: '%', pSign: 's', vMax:'1000.00', vMin:'-1000.00'} );
	    $('input.money').autoNumeric( {aSep: ',', aDec: '.', aSign: '$'} );
	    $('input.number').autoNumeric( {aSep: '', aPad: false, vMax: '999999'} );	
    	$('input.date').datepicker({
    		showAnim:"slideDown"
    	});

/*
    	$('table.ui-datepicker-calendar td a').live('mouseup', function() {
			
		setTimeout(function() {
	    	$('#field-save').trigger('mouseup');
			}, 500);
		    		
    	});



    	$('.typeahead li a').live('mouseup', function() {
			setTimeout(function() {
	    	$('#field-save').trigger('mouseup');
			}, 500);    		

    	});
*/    	



/*
    	$('input.date').each(function() {
    		if ( $(this).val() != '0000-00-00' ) {
	    		var dmoment = moment( $(this).val() );
	    	} else {
		    	$(this).val('');
	    	}
	    	
	    	if(dmoment) {
		    	$(this).val( dmoment.format('L') );
	    	}
    	})

*/
	//Go through each input and set the init value
	$('input.percent,input.money,input.number').each(function() {
		$(this).autoNumericSet( $(this).val() );				
	});
	

	
	//Go through each input and set the init value
	$('.<?=$field_selector?>').each(function() {
		$(this).attr('data-init-value', $(this).val() );				
	});
	


	
	$('.<?=$field_selector?>').focus(function() {		
	
		if( $(this).hasClass('project-comments') ) {return false;}
		//Destroy other instance and redeploy field btns to this input
		closeField();
		
		//Add the active class to this field so that it stays lit even if focus is temporarily lost..
		$(this).addClass('active-input');
		$(this).closest('.controls').addClass('active-controls');

		
		if( $(this).hasClass('string') ) {
		//Load data source 
		var column = $(this).attr('id').substring(2);		
		var table = getTableName( $(this).attr('id').charAt(0) );
		var $this = $(this);
		$.get('ajax_field_source_data.php', { column: column, table: table }, function (data) {
			//$this.attr('data-source', data);
			//alert(data);			
			$this.typeahead({source: data});
		});
		}
		
		

		//Insert field buttons
		$('<div id="field-btns" />').insertAfter(this);
		$('#field-btns').append('<a class="btn btn-mini btn-success" id="field-save"><i class="icon-white icon-ok"></i></a>');
		$('#field-btns').append('<a id="field-cancel">Cancel</a>');

		//if ($(this).hasClass('date')) {
		//$('#field-btns').css('visibility','hidden');		
		//}
		
		//Set coordinate position of buttons...
		var top = $(this).offset().top;
		var left = $(this).offset().left; 
		var width = $(this).width();
		var dist = left + width;
		
		$('#field-btns').css('top',top);
		$('#field-btns').css('left',dist);
	});
	

	$('#field-cancel').live('mouseup', function(e) {
		e.stopPropagation();
		closeField();
	});


	$('#field-save').live('mouseup', function(e) {
		e.stopPropagation();
		var field_id = $('.active-input').attr('id');
		var column = $('.active-input').attr('id').substring(2);
		var thisinput = $('.active-input');
		if ( thisinput.hasClass('money') || thisinput.hasClass('percent') ) {
			var newval = $('.active-input').autoNumericGet();
		} else {	
			var newval = $('.active-input').val();
		}
		
		var table = getTableName( $('.active-input').attr('id').charAt(0) );

		//Validate new value?

		//Save new value via ajax function to ajax_field_save.php
		$.ajax({
	        type: "POST",
	        url: "ajax_field_save.php",
	        data: "id=<?=$id?>&table=" + encodeURIComponent(table) + "&column=" + encodeURIComponent(column) + "&value=" + encodeURIComponent(newval) + "&field_id=" + encodeURIComponent(field_id),
	        success: function(response) {
	            if(response === '1') {
					$(thisinput).attr('data-init-value', $(thisinput).val() );
					closeField();
					$('#ajax-message').html('<div class="alert alert-success">Saved Successfully!</div>').show().delay('2000').fadeOut('500');
					$(thisinput).addClass('changed');					
	            } else {
	                $('#ajax-message').html('<div class="alert alert-error">Error:'+response+'</div>').show().delay('2000').fadeOut('500');
	                closeField();
	            }
	        }
	    });
	});



	$('#vendor-list .checkbox input').change(function() {
		//Gather all selected vendor ids into an array.
		var list = new Array();
		$('#vendor-list input:checked').each(function() {  
			list.push( $(this).val() );
		});
		
		//send array to ajax php as JSON
		//id - project id 
		//vendors -  jason array of vendor IDs
		$.ajax({
	        type: "POST",
	        url: "ajax_update_vendors.php",
	        data: "id=<?=$id?>&vendors=" + JSON.stringify(list),
	        success: function(response) {
	            if(response === '1') {
					$('#ajax-message').html('<div class="alert alert-success">Saved Successfully!</div>').show().delay('2000').fadeOut('500');
	            } else {
	                $('#ajax-message').html('<div class="alert alert-error">Error:'+response+'</div>').show().delay('2000').fadeOut('500');
	            }
	        }
	    });		
		
	});

	$('#p-landlord_approval_required_for_permit').change(function() {
		if( $(this).val() === '1' ) {
		$('tr#landlord-approval-row').css('visibility', 'visible');
		} else {
		$('tr#landlord-approval-row').css('visibility', 'hidden');
		}
	})

/*
	$(document).live('click blur focus', function(e){ 
		if (!$(event.target).closest("#field-btns,.active, .active-controls, .ui-widget,.ui-datepicker-header").length) {
		closeField();
		}
	});	
*/
	
	<?php 
		if($changes) {
			print "//Changes\n";
			foreach ($changes as $change) {
				print '$("#'.$change.'").addClass("changed");'."\n";
			}
		}
	?>
	
	
<?php endif; ?>


//global Project page JS for all visitors


		$('div.pane-header').click(function() {
			$('html, body').animate({
	        scrollTop: $(this).offset().top
	        }, 500);
			
			if( $(this).parent().hasClass('closed') ) {
				$(this).next('.pane-content').slideDown()
				.parent().addClass('open').removeClass('closed')
				.children('.pane-header').children('.symbol').html('<i class="icon-chevron-down"></i>');
			} else {
				$(this).next('.pane-content').slideUp()
				.parent().addClass('closed').removeClass('open')
				.children('.pane-header').children('.symbol').html('<i class="icon-chevron-right"></i>')
				
			}
		});
		
		$('#expand-all').click(function() {
			$('.pane-content').not('.pane-content .pane-content').slideDown().parent().addClass('open').removeClass('closed').children('.pane-header').children('.symbol').html('<i class="icon-chevron-down"></i>');
		})

		
		$('#collapse-all').click(function() {
			$('.pane-content').not('.pane-content .pane-content').slideUp().parent().addClass('closed').removeClass('open').children('.pane-header').children('.symbol').html('<i class="icon-chevron-right"></i>');
		})


		$('#expand-re').click(function() {
			$('#project-real-estate .pane-content .pane-content').slideDown().parent().addClass('open').removeClass('closed').children('.pane-header').children('.symbol').html('<i class="icon-chevron-down"></i>');
		})

		
		$('#collapse-re').click(function() {
			$('#project-real-estate .pane-content .pane-content').slideUp().parent().addClass('closed').removeClass('open').children('.pane-header').children('.symbol').html('<i class="icon-chevron-right"></i>');
		})




});



</script>
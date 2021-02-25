<?php // new-zip.php

// Process edit
// If time limit is set to zero, no time limit is imposed and script will run until upload is done...
set_time_limit(0);
	$id = $_POST['project_id'];
	$sitename = $_POST['project_name'];
	$newfolder = $_POST['new_folder_name'];
	$newcomment = $_POST['new_folder_comment'];

	

// Validate folder name and create photo folder...
	$uploaddir = "./filespace/$id/photos/". $newfolder ."/";
	$thumbsdir = $uploaddir."thumbs/";
	echo "uploaddir: ".$uploaddir."<br />";
	echo "thumbsdir: ".$thumbsdir."<br />";

	if ($newfolder == "")
	{
	error("You must enter a name for this file distribution.");
	exit;
	}
	echo "folder name not blank <br />";

	if(!checkname($newfolder))
	{
	error("Illegal characters used in file distribution name.");
	exit;
	}
	echo "folder name contains no illegal chars <br />";

	if (file_exists($uploaddir))
	{
	error("Folder of that name already exists");
	exit;
	}
	echo "folder does not already exist<br />";
	

// No exit conditions met, so it must be good, make thumbs dir too!
	if (!file_exists("./filespace/$id/photos/"))
		{
		mkdir("./filespace/$id/photos/");
		}
	mkdir($uploaddir);
   	mkdir($thumbsdir);
	echo "folders created successfully <br />";

// Accept upload post
	$tempname = $HTTP_POST_FILES['userfile']['tmp_name'][0];
	echo "tempname: ".$tempname."<br />";
	$filename = $HTTP_POST_FILES['userfile']['name'][0];
	echo "filename: ".$filename."<br />";
	//If file is not blank
	if ($tempname != "")
	{ // Move it to the real location
		echo "temp file ok <br />";
		if (move_uploaded_file($tempname, $uploaddir.$filename))
		{
		echo "moved to $uploaddir <br />";
		//Extract the zip into the uploaddir		
		include("zip.lib.php");
		$zip = new Zip;
		$zip -> Extract($uploaddir.$filename,$uploaddir,Array(-1));
		$zip -> Extract($uploaddir.$filename,$thumbsdir,Array(-1));

		echo "files extracted<br />";
		//Delete the zip archive		
		unlink($uploaddir.$filename);
		echo "original zip deleted<br />";		
		//Make thumbnails...
		$mydir = opendir($thumbsdir) ;
		$exclude = array( ".", "..");
		$filecount = 0;
		
		while($fn = readdir($mydir))
		{
			if ($fn == $exclude[0] || $fn == $exclude[1])
				continue;
			if (ereg(" ", $fn) == 1)
 		    	$fn = ereg_replace(" ", "\ ", $fn);
			system("convert $thumbsdir/$fn -resize 160x160 $thumbsdir/$fn");
			$filecount++;
		}
		closedir($mydir);		

		//List files in new folder
		$scan  = array();
		$files = array();
		$dirs  = array();

		$scan = scan_dir($uploaddir, 0, SORT_REGULAR);
		$files = $scan['files'];
		$dirs  = $scan['directories'];

		echo "<div id=\"content\">";
		echo "<h1>:: Zip file extracted</h1>";
		echo "<div class=\"databox\">";
		print "<p>Your zip file was succesfully uploaded and extracted:</p>";
		
		//List files
		echo "\n These are the files:<br />";
		echo "<table width=\"90%\" align=\"center\" style=\"border:1px #000 solid;\"><tr>";
		$tablecol=0;
		foreach($files as $key => $val)
		{
			echo "<td style=\"text-align:center;\"><a href=\"".$uploaddir.$val."\"><img src=\"".$thumbsdir.$val."\" border=\"0\" /></a></td>";
			$tablecol++;
			if ($tablecol == 3)
			{
				echo "</tr><tr>";
				$tablecol=0;
			}
		}
		
//If file invalid		
		} else {
		echo "<h1>:: Error!</h1>";
		echo "<div class=\"databox\">";
	   	print "<p>Unable to upload files!  Please send the following info to the administrator with a description of the file(s) you were trying to upload:</p>";
		print "<pre>";
		print_r($HTTP_POST_FILES);
		print "</pre>";
		}

}
?>
<a href="index2.php?page=project&id=<?=$id?>#files">:: Return to this project's page</a><br /><br />
</div>
</div>
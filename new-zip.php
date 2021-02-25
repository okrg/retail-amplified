<?php // new-zip.php
if ($usergroup == 0) {
	set_time_limit(0);
	
dbConnect('planetg0_projects');
		$sql = "select sitename from projects where id = $id";
		$result = mysql_query($sql);
		$projectname = mysql_result($result,0);

// Process edit
// If time limit is set to zero, no time limit is imposed and script will run until upload is done...


// Validate folder name and create photo folder...
	$uploaddir = "./filespace/$id/". $newfolder ."/";

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
	if (!file_exists("./filespace/$id/$newfolder/"))
		mkdir($uploaddir);

// Accept upload post
	$tempname = $HTTP_POST_FILES['userfile']['tmp_name'][0];
	$filename = $HTTP_POST_FILES['userfile']['name'][0];
	echo $tempname."<br />";
	echo $filename."<br />";
	
// Very that this is a zip file, if not exit!
	$extension = substr($filename, -3);
	$extension = strtolower($extension);
	echo $extension."<br />";
	if ($extension!="zip")
	{
		error("You must upload a zip file if you are using this method!");
		exit;
	}

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

		echo "files extracted<br />";
		//Delete the zip archive		
		unlink($uploaddir.$filename);
		echo "original zip deleted<br />";		
		
		//List files in new folder

	$filespace = "filespace/$id/$newfolder";
	$files = array();
	$dirs  = array();
	$handle=opendir($filespace);
	while (false !== ($file = readdir($handle)))
	{
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

		
		echo "<div id=\"content\">";

		echo "<h1>:: File distribution added</h1>";
		echo "<div class=\"databox\">";
		print "<p>Your files were succesfully uploaded and the folder <strong>$newfolder</strong> was created:</p>";
		
//List files
		echo "<p>These are the files:<br />";
		foreach($files as $key => $val)
		{
		echo "<strong>$val</strong><br />";
		$distrofiles .= "$val \n";
		}
		echo "</p>";

//If corporate notification option is set then write message to corporate office.
		if( $_POST['notify'] == 'on' )
		{



//Fetch all corp email address
		$sql = "select email from users where groupid=1 or groupid=0";
		$result = mysql_query($sql);	
		while($row = mysql_fetch_array($result))
		{ 
			$addresses .= $row["email"]; 
			$addresses .= ", ";
		}
		
		
//Fetch all vendor addresses attached to that project
		
		
		$sql = "select vendors from projects where id = $id";
		$result = mysql_query($sql);
		$vstring = (mysql_result($result,0,"vendors"));
		$varray = explode(", ", $vstring);
		
		foreach($varray as $vendorID)
		{

			$sql = "select email from users where id=$vendorID";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_array($result))
			{
				$addresses .= $row["email"];
				$addresses .= ", ";
			}
		} 


//Format project distro name for URL
		$newfolderformmated = str_replace(" ", "%20", $newfolder);		

//Write message
$message = "Greetings,


$username has uploaded the following file distribution:
$newfolder

For the project:
$projectname

With the following comment:
$newcomment

These are the files:
$distrofiles

Use this link to access the file distribution directly:
http://construction.charlotte-russe.com/index2.php?page=project&id=$id&folder=$newfolderformmated

Thanks,
Site Administrator

[This was an automated message]
";
		mail($addresses,"Collaboration Network: New File Distribution", $message, "From:Collaboration Network <no-reply@charlotte-russe.com>");
		}


//Add comments and author into filelog	
		$newcomments = addslashes($newcomments);
		$sql =	"insert into distrolog set 
			author='$username',
			project='$id',
			distroname='$newfolder',
			comment='$newcomment'";
		
		if (!mysql_query($sql)){error("A database error occured: " . mysql_error());}

//Show message if notification was sent.		
		if( $_POST['notify'] == 'on' )
		{
		echo "<p>The following message was e-mailed to corporate staff:</p>";
		echo "<div style=\"border:1px #369 solid;background:#fff;padding:20px;\"><pre>$message</pre></div>";
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

<?php


} else {
echo "You do not have sufficient privledges to view this page";
exit;
}
?>
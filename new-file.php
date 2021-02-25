<?php // new-file.php
if ($usergroup == 0) {

		$sql = "select sitename from projects where id = $id";
		$result = mysql_query($sql);
		$projectname = mysql_result($result,0); 

echo "<div id=\"content\">";
	
$maindir = "./filespace/$id";
$uploaddir = $maindir . "/";

for($i=0; $i<count($HTTP_POST_FILES['userfile']['tmp_name']); $i++)
{ 
	$tempname = $HTTP_POST_FILES['userfile']['tmp_name'][$i]; 
	$filename = $HTTP_POST_FILES['userfile']['name'][$i];

	if (!mysql_query($sql))
		error("A database error occured:\\n " . mysql_error());

	if (file_exists($uploaddir.$filename)) {
		error("A database error occured:\\nFile of that name already exists");
		exit;
	}


	if ($tempname != "")
	{
		if (move_uploaded_file($tempname, $uploaddir.$filename))
		{ 
		echo "<h1>:: Document added</h1>";
		echo "<div class=\"databox\">";
		print "<p>Document is valid, and was successfully uploaded:</p>";
		print "<p><b>$filename</b></p>";

		dbConnect('planetg0_projects');	

		
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
		
		foreach($varray as $vendorID) {
			
		$sql = "select email from users where id=$vendorID";
		$result = mysql_query($sql);	
		$addresses .= mysql_result($result,0,"email");
		$addresses .= ", ";
		} 
		
		
		
		


		$message = "Greetings,

$username has uploaded the following project document:
$filename

For the project:
$projectname

With the following comment:
$newcomment

Use this link to access the file directly:
http://construction.charlotte-russe.com/download.php?file=filespace/$id/$filename

Thanks,
Site Administrator

[This was an automated message]
";
//mail($addresses,"Collaboration Network: New Document", $message, "From:Collaboration Network <no-replay@charlotte-russe.com>");
		}


//Add comments and author into filelog	
		$newcomments = addslashes($newcomments);
		$sql =	"insert into filelog set 
			author='$username',
			project='$id',
			filename='$filename',
			comment='$newcomment'";
		
		if (!mysql_query($sql)){error("A database error occured: " . mysql_error());}

//Show message if notification was sent.		
		if( $_POST['notify'] == 'on' )
		{
		echo "<p>The following message was e-mailed to corporate staff:</p>";
		echo "<div style=\"border:1px #369 solid;background:#fff\"><pre>$message</pre></div>";
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
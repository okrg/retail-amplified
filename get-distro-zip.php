<?php

include("include/access.php");

// init the zip class
$zip = new ZipArchive;
$zipfile = "./zips/".rand().".zip";

// create empty zip
if ($zip->open($zipfile, ZIPARCHIVE::CREATE)!==TRUE)
    exit("cannot open <$zipfile>\n");

// determine directory to use
if (isset($photos)) $maindir = "./filespace/".$id."/photos/".$folder."/";
else $maindir = "./filespace/".$id."/".$folder."/";

$scan  = array();
$files = array();
$dirs  = array();

// log this transaction
dbConnect();
$project_id = $id;
$folder = $folder;
$filename = "Complete folder ZIP";

$sql = "insert into downloadlog set
			user = '".mysql_real_escape_string($username)."',
			project_id = $project_id,
			folder = '$folder',
			filename = '$filename'";

if (!mysql_query($sql)) echo "<p>Unable to write to site access log. contact admin<br />".mysql_error()."</p>";

mysql_close();

//Scan files into an array
$scan = scan_dir($maindir, 0, SORT_REGULAR);
$files = $scan['files'];
$dirs  = $scan['directories'];

// add the binary data stored in the string 'filedata'
foreach($files as $key => $val) 
	$zip->addFile($maindir.$val, $val);  

// close zip file
$zip->close();

// create name for zip file
$folder = stripslashes(str_replace("'", "_", $folder));
$theFileName = $folder;

// output file to browser forcing download
header ("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header ("Content-Type: application/zip");
header ('Content-Disposition: attachment; filename="'.$theFileName.'.zip"');
header("Content-Length: ".filesize($zipfile));
ob_clean();
flush();
readfile($zipfile);

// remove file from server
unlink($zipfile);
?>
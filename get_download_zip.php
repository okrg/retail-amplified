<?php
//include ("include/download-access.php");
//dbConnect();
include ("include/access.php");

$project_id = $_POST['project_id'];
$folder_type = $_POST['folder_type'];
$folder_name = $_POST['folder_name'];

$path = dirname(__FILE__)."/files/$project_id/$folder_type/$folder_name/";
$scan  = array();
$files = array();
$zip = new ZipArchive;
$zipfile = dirname(__FILE__).'/zips/'.rand().'.zip';

// create empty zip
if ($zip->open($zipfile, ZIPARCHIVE::CREATE)!==TRUE)
  exit("cannot open <$zipfile>\n");

//Scan files into an array
$scan = scan_dir($path, 0, SORT_REGULAR);
$files = $scan['files'];

// add the binary data stored in the string 'filedata'
foreach($files as $key => $file) {
  $zip->addFile($path.$file, $file);  
}

$zip->close();
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="'.$folder_name.'.zip"');
header('Content-Length: '.filesize($zipfile));
readfile($zipfile);
exit();
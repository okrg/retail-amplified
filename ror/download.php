<?php
include ("../include/download-access.php");
$namespace = explode("/", $_GET['file']);
if (($namespace[0] == "rordocs") or ($namespace[0] == "requestdocs")) {
	$filename = $_GET['file'];
	$elegantname = $namespace[2];
} else {
	$filename = "..".$_GET['file'];
	$elegantname = $namespace[2];
}
$elegantname=eregi_replace(" ", "_", $elegantname);
$filename = stripslashes($filename);
$ext = substr( $filename,-3 );
if( $filename == "" ) {
   echo "<html><body>ERROR: Empty file to download. USE download.php?file=[file path]</body></html>";
   exit;
} elseif ( ! file_exists( $filename ) ) {
   echo "<html><body>$filename ERROR: File not found. USE download.php?file=[file path]</body></html>";
   exit;
};
switch( $ext ){
case "pdf":
case "PDF":
	$ctype="application/pdf";
	$force=true;
	break;

case "exe":
case "EXE":
	$ctype="application/octet-stream";
	$force=true;
	 break;
case "zip":
case "ZIP":
	$ctype="application/zip";
	$force=true;
	break;
case "gif":
case "GIF":
	$ctype="image/gif";
	$force=true;
	break;
case "swf":
case "SWF":
	$ctype="application/x-shockwave-flash";
	$force=false;
	break;
case "png":
case "PNG":
	$ctype="image/png";
	$force=true;
	break;
case "jpg":
case "JPG":
	$ctype="image/jpg";
	$force=true;
	break;
case "txt":
case "TXT":
	$ctype="text/plain";
	$force=true;
case "doc":
case "DOC":
case "xls":
case "XLS":
default:
	$ctype="application/force-download";
	$force=true;
}
header("Pragma: public");
header("Expires: 0"); // set expiration time
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("Content-Type: $ctype");
if ($force) {
//header("Content-Disposition: attachment; filename=".basename($filename).";");
header ('Content-Disposition: attachment; filename='.$elegantname.';');
} else {
header( "Content-Disposition: filename=".basename($filename).";" );
}
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize($filename));
readfile("$filename");

exit();

?>
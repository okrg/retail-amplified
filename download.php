<?php
include ("include/download-access.php");
dbConnect();
$namespace = explode("/", $_GET['file']);
//determine what kind of download by the string
$prefix = $namespace[0];
$project_id = $namespace[1];


if(is_numeric($prefix)) {
	//USE CLOUD METHOD
	$pid = $namespace[0];
	$category = $namespace[1];
	$folder = $namespace[2];
	$file  = $namespace[3];
	$sql = "insert into downloadlog set
		user = '$username',
		company = '$usercompany',
		project_id = $pid,
		folder = '$folder',
		filename = '$file'";

	try {
		$conn = cloudConnect();
		$container = $conn->get_container($subdomain . '.' . $pid . '.' . $category);
		$download = $container->get_object($folder."/".$file);
		header("Pragma: public");
		header("Expires: 0"); // set expiration time
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
		header("Content-Type: " . $download->content_type);
		header("Content-Disposition: attachment; filename=".$file.";");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ". $download->content_length);
		$output = fopen("php://output", "w");
		$download->stream($output); # stream object content to PHP's output buffer
		fclose($output);
		exit();
	} catch (Exception $e){
		print $e->getMessage();
		exit();
	}	

} else {
	//STICK WITH OLD METHOD
	if ($prefix == "pdfspecs") {
		$filename = $namespace[1];
		$elegantname=$namespace[1];


	} elseif ($project_id == "weekly") {
		$filename = $namespace[2];
		$elegantname=$namespace[2];
		$sql = "insert into weeklylog set
				user = '$username',
				filename = '$filename'";
	} elseif( $namespace[2] == 'vendor') {
		$filename = $namespace[4];
		$elegantname=$namespace[4];		
	} else {
		if (count($namespace) == 3) {
			$folder = "";
			$filename = $namespace[2];
			$elegantname=$namespace[2];
			
		} else {
			$folder = $namespace[2];
			$filename = $namespace[3];
			$elegantname=$namespace[3];
		}
	unset($namespace);
	$sql = "insert into downloadlog set
			user = '$username',
			company = '$usercompany',
			project_id = $project_id,
			folder = '$folder',
			filename = '$filename'";			
	}

	if (!mysql_query($sql))
		echo "<p>Unable to write to site access log. contact admin<br />".mysql_error()."</p>";
			
	mysql_close();
	//$root   = $_SERVER['DOCUMENT_ROOT'];
	//$filename = "$root"."/".$_GET['file'];
	$filename = $_GET['file'];
	$elegantname=eregi_replace(" ", "_", urldecode($elegantname));
	$elegantname=eregi_replace("'", "_", $elegantname);
	$filename = stripslashes($filename);
	//$ext = substr( $filename,-3 );
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	
	if( $filename == "" ) {
	   echo "<html><body>ERROR: Empty file to download. USE download.php?file=[file path]</body></html>";
	   exit;
	} elseif ( ! file_exists( $filename ) ) {
	   echo "<html><body>ERROR: File not found. USE download.php?file=[file path]</body></html>";
	   exit;
	};
	switch( $ext ){
	case "pdf":
	case "PDF":
		$ctype="application/octet-stream";
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


}


?>
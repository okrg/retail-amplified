
<?php

//cloud_download.php?container=charlotterusse.1863.files&file=TEMPLATE%20PLANS%20-%20OUTLET%20-%2010.02.12/A3-1_STOREFR.pdf
	include ("include/download-access.php");


	dbConnect();
	$containerNS = explode(".", $_GET['container']);
	$site = $containerNS[0];
	$pid = $containerNS[1];
	$category = $containerNS[2];

	$objectNS = explode ("/", $_GET['object']);
	$folder = $objectNS[0];
	$file  = $objectNS[1];

	$sql = "insert into downloadlog set
		user = '$username',
		company = '$usercompany',
		project_id = $pid,
		folder = '$folder',
		filename = '$file'";

	try {
		mysql_query($sql);
		mysql_close();
	}catch(Exception $e) {
		echo "<p>Unable to write to site access log. contact admin<br />".mysql_error()."</p>";	
	}

	try {
		$conn = cloudConnect();
		$container = $conn->get_container($_GET['container']);
		$download = $container->get_object($_GET['object']);
		if ($file == 'archive.zip') {
			$file = $folder . ".zip";
		}
		header("Pragma: public");
		header("Expires: 0"); // set expiration time
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
		header("Content-Type: " . $download->content_type);
		header ('Content-Disposition: attachment; filename='.$file.';');
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ". $download->content_length);
		ob_clean();
    	flush();
		$output = fopen("php://output", "w");
		$download->stream($output); # stream object content to PHP's output buffer
		fclose($output);
		exit();
	} catch (Exception $e){
		print $e->getMessage();
		exit();
	}	
?>
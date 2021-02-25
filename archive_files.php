<?php

$conn = cloudConnect();

//default category
$category = 'files';

$folder = $_GET['name'];
$pid = $_GET['id'];

dbConnect();
$nicename = stripslashes($folder);
$sql = "select * from distrolog where distroname='$nicename' and project= $id";
$result= mysql_query($sql);
if (!$result)
	error("A databass error has occured.\\n".mysql_error());
$distro = mysql_fetch_object($result);


function exapp($name, $category) {
	global $conn;
	global $subdomain;
	global $pid;
	global $msg;
	global $exapp;
	//load the category container
	try {
		$container = $conn->get_container($subdomain . '.' . $pid . '.' . $category);
	} catch (Exception $e) {
		$container = $conn->create_container($subdomain . '.' . $pid . '.' . $category);
	}		
	
	//determine if this marker already exists..
	try {
		$obj = $container->get_object($name);
		$exists = TRUE;
		//if it does, append underscore			
	} catch (Exception $e) {		
		//if exception, that means file does NOT exist
		$exists = FALSE;
	}
	if($exists) {$msg .= "<p><strong>Warning:</strong> Filename already exists. An underscore was added to your file</p>";}
	while($exists == TRUE) {			
		$path = explode('/', $name);			
		$name = $path[0] . "/_" . $path[1];
		//determine if this marker already exists..
		try {
			$obj = $container->get_object($name);
			$exists = TRUE;
			//if it does, append underscore			
		} catch (Exception $e) {		
			//if exception, that means file does NOT exist
			$exists = FALSE;				
		}			
		
	}
	
	return $name;			
}

function mkclouddir($folder, $category) {
	global $conn;
	global $username;
	global $newcomment;
	global $usercompany_name;
	global $usercompany;
	global $unique_user_id;
	global $subdomain;
	global $pid;
	
	//load the category container
	try {
		$container = $conn->get_container($subdomain . '.' . $pid . '.' . $category);
	} catch (Exception $e) {
		$container = $conn->create_container($subdomain . '.' . $pid . '.' . $category);
	}
	
	//determine if this marker already exists..
	try {
		$obj = $container->get_object($folder);
		$exists = TRUE;
		//if it does, append underscore			
	} catch (Exception $e) {		
		//if exception, that means file does NOT exist
		$exists = FALSE;
	}
	
	if(!$exists) {
		//Create a folder marker
		try {
			$marker = $container->create_object($folder);
			$marker->content_type = "application/directory";
			$marker->write('',0);
			$msg .= 'Cloud folder created';
		} catch(Exception $e) {
			$msg .= $e->getMessage();
		}		
	}
	return $msg;
}


//load the container or create it... 
try {
	$container = $conn->get_container($subdomain . '.' . $pid . '.' . $category);
} catch (Exception $e) {
	$container = $conn->create_container($subdomain . '.' . $pid . '.' . $category);
}

//get the folder name...
//$folder = exapp($folder, $category);

//create the folder name...
mkclouddir($folder, $category);	

//scan for folders and files that are NOT photos and NOT system folders or files
$filespace = "filespace/$id/$folder/";
$files = array();
$dirs  = array();
$handle=opendir($filespace);
if($handle) {
	while (false !== ($file = readdir($handle)))
	{
		if($file=='.'||$file=='..'||$file=='.htaccess')
			continue;
		if(is_dir($filespace.$file))
			$dirs[]=$file;
		else
			$files[]=$file;
	}
}
closedir($handle);
//Sort arrays in natural order and reset pointer to first entry
sort($dirs, SORT_REGULAR);
sort($files, SORT_REGULAR);
reset($dirs);
reset($files);

// init the zip class
$zip = new ZipArchive;
$zipfile = "./zips/".rand().".zip";

// create empty zip
if ($zip->open($zipfile, ZIPARCHIVE::CREATE)!==TRUE)
    exit("cannot open <$zipfile>\n");

foreach($files as $file) { 
	if($file == ".archive"){
		error('This folder is already archived!');
	}
}


foreach($files as $file) { 
	$filename = exapp($folder.'/'.$file, $category);
	try {
		$object = $container->create_object($filename);
		$object->load_from_filename($filespace.$file);
		/*
		$object->metadata = array( 
			"Author" => $distro->author,
			"Comment" => $distro->comment
		);
		$object->sync_metadata();
		*/
		$zip->addFile($filespace.$file, $file);  

		$files_uploaded .= "$filename\n";
		$msg .= "<p>Uploaded: $filename which had ts: ".$distro->timestamp."</p>";
		$msg_class = 'success';
		
	} catch (Exception $e) {
		$msg .= 'Exception caught: ' .  $e->getMessage() . "\n";

		$msg_class = 'error';
		die($msg);
	}		
}

// close zip file
$zip->close();
try {
	$zip_object = $container->create_object($folder.'/archive.zip');
	$zip_object->load_from_filename($zipfile);
} catch (Exception $e) {
	$msg .= 'Exception caught: ' .  $e->getMessage() . "\n";
	$msg_class = 'error';
	die($msg);
}		

// remove file from server
unlink($zipfile);

$msg .= "<p>Uploaded ZIP archive</p>";


foreach($files as $file) { 
	unlink($filespace.$file);
	$msg .= "<p>Deleted Local: $filename</p>";
}


touch($filespace.".archived");


if (isset($_GET['redirectProjectDwgs'])) {
	header('Location:/index.php?page=project&id='.$pid.'&showDwgs=1#project-files');
	//echo 'redirect';
}

?>

<div id="content">
	<h1>:: Files Archive Results</h1>
	<div class="databox">
		<h2><a href="index.php?page=folder&id=<?=$pid?>&name=<?=$folder?>">Return to Folder</a></h2>
		<h2><a href="index.php?page=project&id=<?=$pid?>">Return to Project Page</a></h2>
		<?php print $msg; ?>
	</div>
</div>
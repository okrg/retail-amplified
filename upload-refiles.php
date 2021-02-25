<?php

include "little-helpers.php";
include "include/db.php";

$file = $_FILES['re-files'];
$tmpname = $file['tmp_name'];
$filename = $file['name'];
$project_id = $_GET['pid'];
$user_id = $_GET['uid'];
$base_dir = "/var/www/vhosts/construction.charlotte-russe.com/httpdocs/";
$upload_dir = "realestate_files";
$msg = "";
$reloadlink = "";

if (is_uploaded_file($tmpname) && $file['error'] == UPLOAD_ERR_OK)
{
	if (!file_exists($base_dir.$upload_dir))
		mkdir($base_dir.$upload_dir);
	if (!file_exists($base_dir.$upload_dir."/{$project_id}"))
		mkdir($base_dir.$upload_dir."/{$project_id}");
	
	$path = $base_dir.$upload_dir."/{$project_id}/";
	
	if (file_exists($path.$filename))
	{
		$err_msg = "Warning: A file with this name already exists.";
	}
	else
	{	
		$dh = dbConnect();
		
		$fullname = "";
		$unqry = "SELECT * FROM users WHERE userid='{$user_id}'";
		$unr = mysql_query($unqry);
		if (!$unr) { }
		else
		{
			$unrow = mysql_fetch_assoc($unr);
			$fullname = $unrow['fullname'];
		}
		
		$qry = "INSERT INTO re_files (project_id, uploaded_by, filename) VALUES (";
		$qry = $qry."'".$project_id."','".$fullname."','".$filename."')";
		$res = mysql_query($qry);
		if (!$res) 
		{
			$err_msg = "Warning: Database error encountered.";
		}
		else
		{
			move_uploaded_file($tmpname, $path.$filename);
			$msg = "Your file [{$filename}] has been successfully uploaded. ";
			$reloadlink = "/index.php?page=project&id={$project_id}";
		}
		mysql_close($dh);
	}
}

$filesize = formatFileSize($file['size']);

if (isset($err_msg))
	echo '{"msg":"'.$err_msg.'","link":" "}';
else
	echo '{"msg":"'.$msg.'","link":"'.$reloadlink.'"}';



?>
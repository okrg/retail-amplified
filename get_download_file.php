<?php
//include ("include/download-access.php");
//dbConnect();
include ("include/access.php");
$project_id = $_POST['project_id'];
$rfi_id = $_POST['rfi_id'];
$cop_id = $_POST['cop_id'];
$co_request_id = $_POST['co_request_id'];
$folder_type = $_POST['folder_type'];
$folder_name = $_POST['folder_name'];
$file = $_POST['file'];
/*
$query = "select * from file_folders WHERE id = $folder_id LIMIT 1";
$result = mysqli_query($dbcnx, $query) or die (mysqli_error($dbcnx)); 
$data = mysqli_fetch_assoc($result);
$name = $data['name'];
$type = $data['type'];
$project_id = $data['project_id'];
*/
if(!empty($rfi_id)) {
  $download = dirname(__FILE__)."/files/$project_id/rfi/$rfi_id/$file";
} elseif(!empty($cop_id)) {
  $download = dirname(__FILE__)."/files/$project_id/cop/$cop_id/$file";
} elseif(!empty($co_request_id)) {
  $download = dirname(__FILE__)."/files/$project_id/co_request/$co_request_id/$file";
} else {
  $download = dirname(__FILE__)."/files/$project_id/$folder_type/$folder_name/$file";
}
header('Content-Disposition: attachment; filename="'.$file.'";');
header('Content-Transfer-Encoding: binary');
header('Content-Length: '.filesize($download));
readfile($download);
exit();
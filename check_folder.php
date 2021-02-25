<?php

$project_id = $_REQUEST['project_id'];
$folder_type = $_REQUEST['folder_type'];
$folder_name = $_REQUEST['folder_name'];

$destination = realpath(dirname(__FILE__))."/files/$project_id/$folder_type/$folder_name";

if (file_exists($destination)) {
  $code = 'FOLDER_EXISTS';
} else {
  $code = 'OK';
}
$response = new stdClass();
$response->code = $code;
$response->project_id = $project_id;
$response->folder_name = $folder_name;
header('Content-Type: application/json');
echo json_encode($response);
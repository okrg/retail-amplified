<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include("include/access.php");

$project_id = mysqli_real_escape_string($dbcnx, $_POST['project_id']);
$project_modules = mysqli_real_escape_string($dbcnx, $_POST['project_modules']);

if(isset($_POST["action"])) {

  if($_POST['action'] == 'edit'){
    $query = "UPDATE projects SET
      project_modules = '$project_modules' 
      WHERE id = $project_id";
    $result = mysqli_query($dbcnx, $query) or send_response(mysqli_error($dbcnx));  

    if($result) {
      send_response('MODULES_UPDATED');
    }
  }
}
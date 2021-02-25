<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include("include/access.php");

$project_id = mysqli_real_escape_string($dbcnx, $_POST['project_id']);
$group_id = mysqli_real_escape_string($dbcnx, $_POST['group_id']);
$whitelister = mysqli_real_escape_string($dbcnx, $_POST['whitelister']);
$project_access_id = mysqli_real_escape_string($dbcnx, $_POST['project_access_id']);


if(isset($_POST["action"])) {
  if($_POST['action'] == 'toggle'){
    $query = "SELECT * FROM cna_project_access WHERE group_id = $group_id and project_id = $project_id";
    $result = mysqli_query($dbcnx, $query) or send_response(mysqli_error($dbcnx));
    if(mysqli_num_rows($result) > 0) {
      $query = "DELETE FROM cna_project_access WHERE group_id = $group_id and project_id = $project_id";
      $result = mysqli_query($dbcnx, $query) or send_response(mysqli_error($dbcnx));  
      if($result) {
        send_response('GROUP_REMOVED');
      }
    } else {
      $query = "INSERT INTO cna_project_access SET         
        project_id = '$project_id',
        group_id = '$group_id',
        whitelister = '$whitelister',
        date_added = CURDATE()";
      $result = mysqli_query($dbcnx, $query) or send_response(mysqli_error($dbcnx));  

      if($result) {        
        send_response('GROUP_ADDED');
      }
    }
  }
}
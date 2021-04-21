<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include("include/access.php");

$project_name = mysqli_real_escape_string($dbcnx, $_POST['project_name']);

if(isset($_POST["action"])) {
  if($_POST['action'] == 'add'){
    $query = "INSERT INTO projects SET 
      sitename = '$project_name',
      dateadded = CURDATE()";
    $result = mysqli_query($dbcnx, $query) or send_response(mysqli_error($dbcnx));

    if($result) {
      send_response('PROJECT_ADDED', mysqli_insert_id($dbcnx));
    }
  }
}
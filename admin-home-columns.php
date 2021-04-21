<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include("include/access.php");

$user_id = mysqli_real_escape_string($dbcnx, $_POST['user_id']);
$home_columns = mysqli_real_escape_string($dbcnx, $_POST['home_columns']);

if(isset($_POST["action"])) {

  if($_POST['action'] == 'edit'){
    $query = "UPDATE cna_users SET
      home_columns = '$home_columns' 
      WHERE id = $user_id";
    $result = mysqli_query($dbcnx, $query) or send_response(mysqli_error($dbcnx));  

    if($result) {
      send_response('COLUMNS_UPDATED');
    }
  }
}
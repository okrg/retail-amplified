<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include("include/access.php");

$user_id = mysqli_real_escape_string($dbcnx, $_POST['user_id']);
$old_pass = mysqli_real_escape_string($dbcnx, $_POST['old_pass']);
$new_pass = mysqli_real_escape_string($dbcnx, $_POST['new_pass']);
$new_pass_confirm = mysqli_real_escape_string($dbcnx, $_POST['new_pass_confirm']);



$query = "SELECT * FROM cna_users WHERE id = $user_id AND user_pass = '$old_pass'";    
$result = mysqli_query($dbcnx, $query) or send_response(mysqli_error($dbcnx));    
if(mysqli_num_rows($result) == 0) {
  send_response('INVALID_OLD_PASSWORD');
} else {
  $query = "UPDATE cna_users 
    SET user_pass = '$new_pass' 
    WHERE id = $user_id";
  $result = mysqli_query($dbcnx, $query) or send_response(mysqli_error($dbcnx));  

  if($result) {        
    send_response('PASSWORD_UPDATED');
  }
}


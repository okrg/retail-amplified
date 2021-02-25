<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include("include/access.php");

$group_id = mysqli_real_escape_string($dbcnx, $_POST['group_id']);
$group_name = mysqli_real_escape_string($dbcnx, $_POST['group_name']);
$group_role = mysqli_real_escape_string($dbcnx, $_POST['group_role']);
$group_category = mysqli_real_escape_string($dbcnx, $_POST['group_category']);


if(isset($_POST["action"])) {
  if($_POST['action'] == 'add'){      
    
    $query = "SELECT * FROM cna_groups WHERE group_name = '$group_name'";    
    $result = mysqli_query($dbcnx, $query) or send_response(mysqli_error($dbcnx));    
    if(mysqli_num_rows($result) > 0) {
      send_response('GROUP_EXISTS'.$query);
    } else {
      $query = "INSERT INTO cna_groups SET         
        group_name = '$group_name',
        group_role = '$group_role',
        group_category = '$group_category',
        date_added = CURDATE()";
      $result = mysqli_query($dbcnx, $query) or send_response(mysqli_error($dbcnx));  

      if($result) {        
        send_response('GROUP_ADDED');
      }
    }
  }

  if($_POST['action'] == 'edit'){
    $query = "UPDATE cna_groups SET       
      group_name = '$group_name',
      group_role = '$group_role',
      group_category = '$group_category' 
      WHERE id = $group_id";
    $result = mysqli_query($dbcnx, $query) or send_response(mysqli_error($dbcnx));  

    if($result) {      
      send_response('GROUP_UPDATED');
    }
  }
}
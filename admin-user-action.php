<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include("include/access.php");

$user_id = mysqli_real_escape_string($dbcnx, $_POST['user_id']);
$user_email = mysqli_real_escape_string($dbcnx, $_POST['user_email']);
$user_name = mysqli_real_escape_string($dbcnx, $_POST['user_name']);
$user_group = mysqli_real_escape_string($dbcnx, $_POST['user_group']);


if(isset($_POST["action"])) {
  if($_POST['action'] == 'add'){
    $user_pass = substr(md5(time()),0,6);
    $query = "SELECT * FROM cna_users WHERE user_email = '$user_email'";    
    $result = mysqli_query($dbcnx, $query) or send_response(mysqli_error($dbcnx));    
    if(mysqli_num_rows($result) > 0) {
      send_response('USER_EXISTS'.$query);
    } else {
      $query = "INSERT INTO cna_users SET 
        user_email = '$user_email',
        user_pass = '$user_pass',
        user_name = '$user_name',
        user_group = '$user_group',
        date_added = CURDATE()";
      $result = mysqli_query($dbcnx, $query) or send_response(mysqli_error($dbcnx));  

      if($result) {
        send_invite($user_email, $user_pass);
        send_response('USER_ADDED');
      }
    }
  }

  if($_POST['action'] == 'edit'){
    $query = "UPDATE cna_users SET 
      user_email = '$user_email',      
      user_name = '$user_name',
      user_group = '$user_group' 
      WHERE id = $user_id";
    $result = mysqli_query($dbcnx, $query) or send_response(mysqli_error($dbcnx));  

    if($result) {      
      send_response('USER_UPDATED');
    }
  }
}
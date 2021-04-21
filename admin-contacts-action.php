<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include("include/access.php");

$row_id = mysqli_real_escape_string($dbcnx, $_POST['row_id']);
$email = mysqli_real_escape_string($dbcnx, $_POST['email']);
$fname = mysqli_real_escape_string($dbcnx, $_POST['fname']);
$lname = mysqli_real_escape_string($dbcnx, $_POST['lname']);
$company = mysqli_real_escape_string($dbcnx, $_POST['company']);
$phone = mysqli_real_escape_string($dbcnx, $_POST['phone']);
$notes = mysqli_real_escape_string($dbcnx, $_POST['notes']);


if(isset($_POST["action"])) {
  if($_POST['action'] == 'add'){
    $query = "SELECT * FROM contacts WHERE email = '$email'";
    $result = mysqli_query($dbcnx, $query) or send_response(mysqli_error($dbcnx));
    if(mysqli_num_rows($result) > 0) {
      send_response('CONTACT_EXISTS'.$query);
    } else {
      $query = "INSERT INTO contacts SET
        fname = '$fname',
        lname = '$lname',
        email = '$email',
        company = '$company',
        phone = '$phone'";
      $result = mysqli_query($dbcnx, $query) or send_response(mysqli_error($dbcnx));
      if($result) {
        send_response('CONTACT_ADDED', mysqli_insert_id($dbcnx)
      );
      }
    }
  }

  if($_POST['action'] == 'edit'){
    $query = "UPDATE contacts SET
      fname = '$fname',
      lname = '$lname',
      email = '$email',
      company = '$company',' 
      WHERE id = $row_id";
    $result = mysqli_query($dbcnx, $query) or send_response(mysqli_error($dbcnx));

    if($result) {      
      send_response('CONTACT_UPDATED');
    }
  }


  if($_POST['action'] == 'delete'){
    $query = "DELETE FROM contacts WHERE id = $row_id";
    $result = mysqli_query($dbcnx, $query) or send_response(mysqli_error($dbcnx));

    if($result) {      
      send_response('CONTACT_DELETED');
    }
  }
}
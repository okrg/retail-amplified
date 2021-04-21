<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include("include/access.php");

$row_id = mysqli_real_escape_string($dbcnx, $_POST['row_id']);
$project_id = mysqli_real_escape_string($dbcnx, $_POST['project_id']);
$position = mysqli_real_escape_string($dbcnx, $_POST['position']);
$contact_id = mysqli_real_escape_string($dbcnx, $_POST['contact_id']);


if(isset($_POST["action"])) {
  if($_POST['action'] == 'add'){
    $query = "INSERT INTO project_contacts SET project_id = $project_id,
      contact_id = $contact_id,
      position = '$position'";
    $result = mysqli_query($dbcnx, $query) or send_response(mysqli_error($dbcnx));

    if($result) {
    send_invite($user_email, $user_pass);
    send_response('PROJECT_CONTACT_ADDED');
    }
  }

  if($_POST['action'] == 'edit'){
    $query = "UPDATE project_contacts SET project_id = $project_id,
      contact_id = $contact_id,
      position = '$position' WHERE id = $row_id";
    $result = mysqli_query($dbcnx, $query) or send_response(mysqli_error($dbcnx));

    if($result) {
      send_response('PROJECT_CONTACT_UPDATED');
    }
  }

  if($_POST['action'] == 'delete'){
    $query = "DELETE FROM project_contacts WHERE id = $row_id";
    $result = mysqli_query($dbcnx, $query) or send_response(mysqli_error($dbcnx));

    if($result) {
      send_response('PROJECT_CONTACT_DELETED');
    }
  }
}
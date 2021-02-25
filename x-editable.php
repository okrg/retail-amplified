<?php //x-editable.php
  include("include/access.php");
function is_date( $str ){ 
  
  if(!strstr($str, '/')){
    return FALSE;
    }
  $stamp = strtotime( $str ); 
    if (!is_numeric($stamp)) 
        return FALSE; 
    $month = date( 'm', $stamp ); 
    $day   = date( 'd', $stamp ); 
    $year  = date( 'Y', $stamp ); 
    if (checkdate($month, $day, $year)) 
        return TRUE; 
    return FALSE; 
}
  $table = mysqli_escape_string($dbcnx, $_POST['table'] );
  $column = mysqli_escape_string($dbcnx, $_POST['name'] );
  $value = mysqli_escape_string($dbcnx, $_POST['value'] );
  $id = mysqli_escape_string($dbcnx, $_POST['pk']);
  
  switch($table) {
    case 'tracker':
      $field_id = 't-'.$column;
      $key = 'id';    
    case 'projects':
      $field_id = 'p-'.$column;
      $key = 'id';
      break;
    case 'actual_dates':
      $field_id = 'a-'.$column;
      $key = 'project_id';
      break;
    case 'scheduled_dates':
      $field_id = 's-'.$column;
      $key = 'project_id';
      break;
    case 'realestate':
      $field_id = 'r-'.$column;
      $key = 'project_id';
      break;
    case 're_deal_economics':
      $field_id = 'e-'.$column;
      $key = 'project_id';
      break;
    case 're_strategy':
      $field_id = 't-'.$column;
      $key = 'project_id';
      break;
    case 're_storedesign':
      $field_id = 'd-'.$column;
      $key = 'project_id';
      break;
    case 're_centerinfo':
      $field_id = 'c-'.$column;
      $key = 'project_id';
      break;
    case 're_options':
      $field_id = 'o-'.$column;
      $key = 'project_id';
      break;
    case 're_kickouts':
      $field_id = 'k-'.$column;
      $key = 'project_id';
      break;
  }
   
  //Check if date is being submitted, if so, conver it to mysql foramt
  if (is_date($value)) {
    $value = date('Y-m-d', strtotime($value));
  }

  $query = "UPDATE $table SET `$column` = '$value' WHERE $key = $id";
  print $query;
  $result = mysqli_query($dbcnx, $query);     

  if (mysqli_affected_rows($dbcnx)==0) {
    //$q = "INSERT INTO $table ($key, $column) VALUES ($id, '$value')";
    $query = "INSERT INTO $table ($key, $column) VALUES ($id, '$value') ON DUPLICATE KEY UPDATE $column=VALUES($column)";
    //print '1:'.$query;
    $result = mysqli_query($dbcnx, $query);
  }

  if($result) {
    //Insert update into changes table
    $query = "INSERT INTO changes (project_id,field_id) VALUES ($id, '$field_id')";
    $result = mysqli_query($dbcnx, $query);

    $fivedaysago = date ('Y-m-d', strtotime('-5 day'));
    $query = "delete  FROM `changes` WHERE `date` < '".$fivedaysago."'";
    $result = mysqli_query($dbcnx, $query);
    
    header("HTTP/1.1 200 OK");
  } else {
    http_response_code(400);
    print $q;
    print 'error:'.mysqli_error($dbcnx);
  }
<?php //xe/projects.php
  include("../include/access.php");
   
  $table = 'projects';
  $column = mysql_real_escape_string( $_POST['name'] );
  $value = mysql_real_escape_string( $_POST['value'] );
  $id = mysql_real_escape_string($_POST['pk']);
  $field_id = 'p-'.$column;
  $key = 'id';
    
  //Check if date is being submitted, if so, conver it to mysql foramt
  if (is_date($value)) {
    $value = date('Y-m-d', strtotime($value));
  }

  $q = "UPDATE $table SET $column = '$value' WHERE $key = $id";
  $result = mysql_query($q);     

  if (mysql_affected_rows()==0) {
    //$q = "INSERT INTO $table ($key, $column) VALUES ($id, '$value')";
    $q = "INSERT INTO $table ($key, $column) VALUES ($id, '$value') ON DUPLICATE KEY UPDATE $column=VALUES($column)";
    //print '1:'.$q;
    $result = mysql_query($q);
  }

  if($result) {
  
    //Insert update into changes table
    $q = "INSERT INTO changes (project_id,field_id) VALUES ($id, '$field_id')";
    $result = mysql_query($q);

    $fivedaysago = date ('Y-m-d', strtotime('-5 day'));
    $q = "delete  FROM `changes` WHERE `date` < '".$fivedaysago."'";
    $result = mysql_query($q);
    
    header("HTTP/1.1 200 OK");
  } else {
    http_response_code(400);
    print $q;
    print 'error:'.mysql_error();
  }
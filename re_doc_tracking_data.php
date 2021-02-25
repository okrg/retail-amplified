<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include("include/access.php");
include("little-helpers.php");

function loadData($table,$id) {
  global $dbcnx;
  $query = "SELECT * FROM $table WHERE project_id = $id";
  $result = mysqli_query($dbcnx, $query) or die ("no query load data ". $query);  
  $data = array();
  
  while($row = mysqli_fetch_assoc($result)) {
      $data[] = $row;
  }
  if ($data){
    return $data[0];
  } else {
    return false;
  }
} 


$query = "SELECT id,store_number FROM projects WHERE (project_status = 'active' or project_status = 'proposed') ORDER BY CASE WHEN store_opening_date = '0000-00-00' THEN 2 ELSE 1 END, store_opening_date ASC, sitename ASC";

$result = mysqli_query($dbcnx, $query) or die ("no query q" . $query);  
$stores = array();
$index = 0;
while($row = mysqli_fetch_assoc($result)) {

    $stores[$index]['id'] = $row['id'];
    
    $stores[$index]['store_number'] = intval($row['store_number']);
    
    if(intval($row['store_number']) == 0){
      unset($stores[$index]);
      continue;
    }

    $subquery = "SELECT * FROM projects WHERE id = ".$row['id'];
    
    $sresult = mysqli_query($dbcnx, $subquery) or die ("no query");  
    $subdata = array();
    while($srow = mysqli_fetch_assoc($sresult)) {
      $subdata[] = $srow;
      }

    $stores[$index]['project'] = $subdata[0];
    $stores[$index]['realestate'] = loadData('realestate', $row['id']);
    $stores[$index]['actual'] = loadData('actual_dates', $row['id']);
    $stores[$index]['scheduled'] = loadData('scheduled_dates', $row['id']);
    $stores[$index]['centerinfo'] = loadData('re_centerinfo', $row['id']);
    $stores[$index]['kickouts'] = loadData('re_kickouts', $row['id']);

    $_comments_query = "SELECT * FROM comments WHERE project_id = ".$row['id']." AND type='real_estate'";    
    $_comments_result = mysqli_query($dbcnx, $_comments_query);
    $_comments = array();
    $_comments_index = 0;
    while($_comments_row = mysqli_fetch_assoc($_comments_result)) {
      $_comments[$_comments_index] = $_comments_row;
      $_comments[$_comments_index]['datetime'] = date ('m/d/y', strtotime($_comments_row['timestamp']));
      $_comments[$_comments_index]['author'] = get_user_fullname_by_id($_comments_row['author_id']);
      $names = explode(" ", $_comments[$_comments_index]['author']);
      $_comments[$_comments_index]['author_initials'] = "";
      foreach ($names as $n) {
        $_comments[$_comments_index]['author_initials'] .= $n[0];
      }

      $_comments_index++;
    }  
    $stores[$index]['realestate']['comments'] = $_comments;
    
    //Determine if there have been recent changes for this store
    $changes_query = "SELECT * FROM changes WHERE project_id = ".$row['id']." and field_id in ('s-start_construction', 'p-store_opening_date','p-schedule_notes')";
    $changes_result = mysqli_query($dbcnx, $changes_query) or die ("no query");  
    if(mysqli_num_rows($changes_result) > 0 ) {
      $stores[$index]['change_status'] = 'changed';
    } else {
      $stores[$index]['change_status'] = 'static';
    }

    $index++;
}
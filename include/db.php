<?php // db.php
$site = array('db' => 'demo', 'company' => 'DEMO Inc.', 'subdomain' => 'demo');

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = 'root';
$db = 'cnaapp';
$sitename = 'Retail Amplified';
$subdomain = 'cna-app';

define('DB_HOST', $dbhost);
define('DB_NAME', $db);
define('DB_CHARSET', 'utf8');
define('DB_USER', $dbuser);
define('DB_PASSWORD', $dbpass);

function dbConnect() {
  global $dbhost, $dbuser, $dbpass, $db;
  $dbcnx = @mysqli_connect($dbhost, $dbuser, $dbpass, $db)
    or die("The site database appears to be down.");

  //if (!@mysqli_select_db($db))
    //die("The site database is unavailable?");
    
  return $dbcnx;
}

$dbcnx = dbConnect();

function dbClose($db) {
  mysqli_close($db);
}

function get_user_fullname_by_id($id) {
  global $dbcnx;
  $sql = "SELECT * FROM users WHERE id = $id LIMIT 1";
  $_user = mysqli_query($dbcnx, $sql);
  $_user_data = mysqli_fetch_assoc($_user);
  return $_user_data['fullname'];
}


?>

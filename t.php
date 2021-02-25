<?php
error_reporting(E_ALL ^ E_NOTICE);
include("include/db.php");
//Check against hash table called URLhash
$tid = $_GET['u'];
dbConnect();
$result = mysql_query("select * from tinyurl where id = $tid");
if(mysql_num_rows($result)>0){
	$url = mysql_result($result,0,'url');
	//Redirect to URL
	header("Location: $url");
} else {
	//Redirect to home page?
	$url = "http://construction.charlotte-russe.com/index.php";
	header("Location: $url");
}
?>
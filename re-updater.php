<?php
error_reporting(E_ALL ^ E_NOTICE);
include("include/access.php");
$db = dbConnect();

$input = $_POST['value']; $type = $_GET['type'];
$id = $_GET['id']; 
$table = $_GET['table']; $column = $_GET['column'];

if ($type == "date")
{
	$t = strtotime($input);
	if ($t == false)
	{
		print "Invalid date. Please try again.";
		exit;
	}
	else
	{
		$input = date("Y-m-d", $t);
		$output = date("m/d/Y", $t);
	}
}
if ($type == "number")
{
	//if input has comma, take it out and check if it's numeric, otherwise punt
}
if ($type == "text")
{
	$output = $input;
}

$sql = "UPDATE {$table} SET {$column} = '{$input}' where project_id = '{$id}'";
$result = mysql_query($sql);
if (!$result) 
{
	print "no workie:".mysql_error()." {$sql}";
} 
else 
{
	print $output;
}

mysql_close($db);
?>
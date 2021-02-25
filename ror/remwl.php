<?php

error_reporting(E_ALL ^ E_NOTICE);
include("../include/access.php");
include("../include/rt.php");
//Set dynamic database vars depending on mode
//Assign the rt vars that are assingable....
$sql = "UPDATE rt_rors SET ";
$sql .= "watchlist = REPLACE(watchlist,',$uid','') ";
$sql .= "WHERE id = ".$_GET['id'];		
$res = mysql_query($sql);
if (!$res)die;
header('Location: ror-home.php?mode=ROR');
?>

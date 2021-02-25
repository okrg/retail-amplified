<?php
error_reporting(E_ALL);
include("include/db.php");
dbConnect();
//This sets the type key and urgency key systems
$t = array(
	1=>array("Cashwrap","t_cashwrap"),
	2=>array("Ceiling Tiles","t_ceiling_tiles"),
	3=>array("Detex","t_detex"),
	4=>array("Door(excluding locks)","t_door"),
	5=>array("Fire Violation/Extinguishers","t_fire"),			
	6=>array("Flooring","t_flooring"),
	7=>array("Gate","t_gate"),
	8=>array("HVAC","t_hvac"),
	9=>array("Leak","t_leak"),
	10=>array("Lighting","t_lighting"),
	11=>array("Locks","t_locks"),
	12=>array("Muzak/Sound System","t_muzaksound"),
	13=>array("Other","t_other"),
	14=>array("Plumbing","t_plumbing"),
	15=>array("Pest Control","t_pest_control"),
	16=>array("Safe","t_safe"),
	17=>array("Storefront Sign","t_storefront"),
	18=>array("Walls/Paint","t_walls_paint")
	);

$u = array(
	10=>array("Hazard","u_hazard"),
	20=>array("High","u_high"),
	30=>array("Normal","u_normal"),
	40=>array("Low","u_low"),
	50=>array("Minor","u_minor")
	);


echo "<h1>Say Cheese!</h1>";

//Creates a new Type Key entry for all those null ones
//$sql = "SELECT type,id,timestamp FROM repair_orders WHERE parent = 0 order by id";
//$result = mysql_query($sql);
//if (!$result) {echo "error: ".mysql_error();}
//
//while ($row = mysql_fetch_array($result)) { 
//	if ($row['type'] == "Phones/Electrical") {
//		$type_key = 10;
//	} else {
//		for($i = 1; $i <= 18; $i += 1) {
//			if ($row['type'] == $t[$i][0]) {
//				$type_key = $i;
//				break;
//			}
//		} 
//	}
////Write new type key careful not to reset the time stamp!
//	$qsql = "UPDATE repair_orders SET
//			type_key = ".$type_key.",
//			timestamp = ".$row['timestamp']." WHERE id=".$row['id'];
//	echo $qsql;
//	$qresult = mysql_query($qsql);
//	if (!$qresult) {
//		echo "error with this one: ".$row['id']."!<br />".mysql_error();
//	}else{
//		echo "success with: ".$row['id']."!<br />";}
//
//}

//converts new urgency key
//$sql = "SELECT priority,id,timestamp FROM repair_orders WHERE parent = 0 and urgency_key = 0 order by id";
//$result = mysql_query($sql);
//if (!$result) {echo "error: ".mysql_error();}
//while ($row = mysql_fetch_array($result)) { 
//	for($i = 10; $i <= 50; $i += 10) {
//		if ($row['priority'] == $u[$i][0]) {
//			$urg_key = $i;
//			break;
//		}
//	} 
//
//	$qsql = "UPDATE repair_orders SET
//			urgency_key= ".$urg_key.",
//			timestamp = ".$row['timestamp']." WHERE id=".$row['id'];
//	echo $qsql;
//	$qresult = mysql_query($qsql);
//	if (!$qresult) {echo "error with this one: ".$row['id']."!<br />".mysql_error();}else{
//					echo "success with: ".$row['id']."!<br />";}
//}


//Fixes busted statuses
$sql = "SELECT id,timestamp from repair_orders WHERE status='clear' and new_status='open' order by id";
$result = mysql_query($sql);
if (!$result) {echo "error: ".mysql_error();}

while ($row = mysql_fetch_array($result)) { 
	$qsql = "UPDATE rt_rors SET
			status = 'completed',
			ts = ".$row['timestamp']." WHERE id=".$row['id'];
	echo $qsql;
	$qresult = mysql_query($qsql);
	if (!$qresult) {echo "error with this one: ".$row['id']."!<br />".mysql_error();}else{
					echo "success with: ".$row['id']."!<br />";}
}



////converts to new status key
//$sql = "SELECT status,id,timestamp FROM repair_orders WHERE parent = 0 and new_status = ' ' order by id";
//$result = mysql_query($sql);
//if (!$result) {echo "error: ".mysql_error();}
//while ($row = mysql_fetch_array($result)) { 
//	if ($row['status'] == 'clear') {$new_status = "completed";}
//	if ($row['status'] == 'pending') {$new_status = "new";}
//	if ($row['status'] == 'answered') {$new_status = "open";}	
//
//	$qsql = "UPDATE repair_orders SET
//			new_status= '$new_status',
//			timestamp = ".$row['timestamp']." WHERE id=".$row['id'];
//	echo $qsql;
//	$qresult = mysql_query($qsql);
//	if (!$qresult) {echo "error with this one: ".$row['id']."!<br />".mysql_error();}else{
//					echo "success with: ".$row['id']."!<br />";}
//}
//
////calculate children????
//$sql = "SELECT id,timestamp FROM repair_orders WHERE parent = 0 order by id";
//$result = mysql_query($sql);
//if (!$result) {echo "error: ".mysql_error();}
//
//while ($row = mysql_fetch_array($result)) { 
//	$csql = "select id from repair_orders where parent = ".$row['id']."";
//	$cresult = mysql_query($csql);
//	$count = mysql_num_rows($cresult);
//	
//	$qsql = "UPDATE repair_orders SET
//			has_children = $count,
//			timestamp = ".$row['timestamp']." WHERE id=".$row['id'];
//	echo $qsql;
//	$qresult = mysql_query($qsql);
//	if (!$qresult) {echo "error with this one: ".$row['id']."!<br />".mysql_error();}else{
//					echo "success with: ".$row['id']."!<br />";}
//}


////convert issue date to creation timestamp
//$sql = "SELECT id,issue_date,store_district,timestamp FROM repair_orders WHERE parent = 0 and creation = '0000-00-00 00:00:00' order by id";
//$result = mysql_query($sql);
//if (!$result) {echo "error: ".mysql_error();}
//
//while ($row = mysql_fetch_array($result)) { 
//	//strip dash from dates
//	$newdate = str_replace("-","",$row['issue_date']);
//	$newdate = $newdate."000000";
//
//	$qsql = "UPDATE repair_orders SET
//			creation = $newdate,
//			timestamp = ".$row['timestamp']." WHERE id=".$row['id'];
//	echo $qsql;
//	$qresult = mysql_query($qsql);
//	if (!$qresult) {echo "error with this one: ".$row['id']."!<br />".mysql_error();}else{
//					echo "success with: ".$row['id']."!<br />";}
//}

//$fsql = "SELECT * from repair_orders where parent=0";
//$fresult = mysql_query($fsql);
//if (!$fresult) {echo "error: ".mysql_error();}
//while ($r = mysql_fetch_object($fresult)) { 
//	//Get the User ID of each DM that submitted a request
//	$usql = "select id,userid from users where userid LIKE 'dm".$r->store_district."'";
//	$uresult = mysql_query($usql);
//	$author = mysql_result($uresult,0,"id");
//	$body = addslashes($r->body);
//	$qsql = "INSERT INTO rt_rors(id,loc_key,author_key,status,type,urgency,has_children,po_num,body,tracking,creation,ts) 
//	VALUES('$r->id','$r->project_id','$author','$r->new_status','$r->type_key','$r->urgency_key','$r->has_children','$r->po_num','$body','$r->tracking','$r->creation','$r->timestamp')";
//	$qresult = mysql_query($qsql);
//	if (!$qresult) {
//		echo "error with this one: ".$r->id."!<br />".mysql_error();
//	}else{
//		echo "success with: ".$r->id."!author = $author!<br />";
//	}
//}





//convert ror_response timestamp to creation date
//$sql = "SELECT id,ts,UNIX_TIMESTAMP(ts) AS FORMATED_TIME FROM rt_ror_responses WHERE id = 60";
//$sql = "SELECT id,ts,UNIX_TIMESTAMP(ts) AS FORMATED_TIME FROM rt_ror_responses WHERE id > 60";
//$result = mysql_query($sql);
//if (!$result) {echo "error: ".mysql_error();}
//
//while ($row = mysql_fetch_array($result)) { 
//	$newdate = date("Y-m-d H:i:s",$row['FORMATED_TIME']);
//
//	$qsql = "UPDATE rt_ror_responses SET
//			creation = '$newdate',
//			ts = ".$row['ts']." WHERE id=".$row['id'];
//	echo $qsql;
//
//	$qresult = mysql_query($qsql);
//	if (!$qresult) {echo "error with this one: ".$row['id']."!<br />".mysql_error();}else{
//					echo "success with: ".$row['id']."!<br />";}
//}


////convert ror_response views to 7
//$sql = "SELECT id,ts FROM rt_ror_responses WHERE id > 60";
////$sql = "SELECT id,ts FROM rt_ror_responses WHERE id = 60";
//$result = mysql_query($sql);
//if (!$result) {echo "error: ".mysql_error();}
//
//while ($row = mysql_fetch_array($result)) { 
//	$qsql = "UPDATE rt_ror_responses SET
//			view = 7,
//			ts = ".$row['ts']." WHERE id=".$row['id'];
//	echo $qsql;
//
//	$qresult = mysql_query($qsql);
//	if (!$qresult) {echo "error with this one: ".$row['id']."!<br />".mysql_error();}else{
//					echo "success with: ".$row['id']."!<br />";}
//}

//convert all fixture orderes data into rt_freqs data
//$sql = "SELECT *,UNIX_TIMESTAMP(timestamp) AS FORMATED_TIME from fixture_orders where parent=0";
////$sql = "SELECT *,UNIX_TIMESTAMP(timestamp) AS FORMATED_TIME from fixture_orders where id=3";
//$result = mysql_query($sql);
//if (!$result) {echo "error: ".mysql_error();}
//while ($row = mysql_fetch_array($result)) { 
//	$csql = "select id from fixture_orders where parent = ".$row['id']."";
//	$cresult = mysql_query($csql);
//	$count = mysql_num_rows($cresult);
//
//	for($i = 10; $i <= 50; $i += 10) {
//		if ($row['priority'] == $u[$i][0]) {
//			$urg_key = $i;
//			break;
//		}
//	} 
//	
//	$newdate = date("Y-m-d H:i:s",$row['FORMATED_TIME']);
//
//	$usql = "select id,userid from users where userid LIKE 'dm".$row['store_district']."'";
//	$uresult = mysql_query($usql);
//	$author = mysql_result($uresult,0,"id");
//	
//	if ($row['status'] == 'clear') {$new_status = "completed";}
//	if ($row['status'] == 'pending') {$new_status = "new";}
//	if ($row['status'] == 'answered') {$new_status = "open";}	
//
//	if ($row['po_num'] == ''){$po_num = "0";}else{$po_num = $row['po_num'];}
//
//	$fsql = "SELECT * FROM `fixture_tracker` WHERE `trackerId` LIKE '".$row['cartId']."'";
//	$fresult = mysql_query($fsql);
//	if (!$fresult) {echo "error: ".mysql_error();}
//	while ($frow = mysql_fetch_array($fresult)) { 
//		if ($frow['addBool']==1) {$replace=0;}else{$replace=1;}
//		$qsql = "INSERT INTO rt_freqs(opk,loc_key,author_key,status,urgency,has_children,po_num,fixture_key,qty,replacement,body,creation,ts) 
//				VALUES(".$row['id'].",".$row['project_id'].",".$author.",'".$new_status."',".$urg_key.",".$count.",".$po_num.",".$frow['itemId'].",".$frow['qty'].",".$replace.",'".addslashes($row['body'])."','".$newdate."',".$row['timestamp'].")";
//				
//		echo $qsql;
//		$qresult = mysql_query($qsql);
//		if (!$qresult) {
//			echo "error with this one: ".$row['id']."!<br />".mysql_error();
//		}else{
//			echo "success with: ".$row['id']."!<br />";
//		}
//		unset($replace);
//	}
//
//}

////convert all request orderes responses into rt_ror_responses
//$sql = "SELECT *,UNIX_TIMESTAMP(timestamp) AS FORMATED_TIME from repair_orders where parent>0";
//$result = mysql_query($sql);
//if (!$result) {echo "error: ".mysql_error();}
//while ($row = mysql_fetch_array($result)) {
//
//	$usql = "select id,fullname from users where fullname LIKE '".$row['author']."'";
//	$uresult = mysql_query($usql);
//	if (mysql_num_rows($uresult)>0) {
//		$author = mysql_result($uresult,0,"id");
//	} else {
//		$author=521;
//	}
//
//	$newdate = date("Y-m-d H:i:s",$row['FORMATED_TIME']);
//	
//	$qsql = "INSERT INTO rt_ror_responses(parent_key,author_key,view,body,creation,ts) 
//			VALUES(".$row['parent'].",$author,7,'".addslashes($row['body'])."','".$newdate."',".$row['timestamp'].")";
//			
//	echo $qsql;
//	$qresult = mysql_query($qsql);
//	if (!$qresult) {
//		echo "error with this one: ".$row['id']."!<br />".mysql_error();
//	}else{
//		echo "success with: ".$row['id']."!<br />";
//	}
//}

////calculate the new fixture key
//$sql = "SELECT id,name,timestamp FROM fixture_orders WHERE parent > 0";
////$sql = "SELECT id,name,timestamp FROM fixture_orders WHERE id=17";
//$result = mysql_query($sql);
//if (!$result) {echo "error: ".mysql_error();}
//
//while ($row = mysql_fetch_array($result)) { 
//	$csql = "select id from fixture_key where name = '".$row['name']."'";
//	$cresult = mysql_query($csql);
//	$count = mysql_result($cresult,0);
//	
//	$qsql = "UPDATE fixture_orders SET
//			fixture_key = $count,
//			timestamp = ".$row['timestamp']." WHERE id=".$row['id'];
//	echo $qsql;
//	$qresult = mysql_query($qsql);
//	if (!$qresult) {echo "error with this one: ".$row['id']."!<br />".mysql_error();}else{
//					echo "success with: ".$row['id']."!<br />";}
//}


?>
<?php // db.php
//
//$dbhost = "db1";
//$dbuser = "construction";
//$dbpass = "c0llab0r8";
//$db = "construction";
//
//function dbConnect() {
//    global $dbhost, $dbuser, $dbpass, $db;
//    
//    $dbcnx = @mysql_connect($dbhost, $dbuser, $dbpass)
//        or die("The site database appears to be down.");
//
//    if (!@mysql_select_db($db))
//        die("The site database is unavailable.");
//    
//    return $dbcnx;
//}
//dbConnect();
//echo "ready";
//$result = mysql_query("select id, vendorarray from projects where id = 60");
//while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
//
//		$vendorarray = $row["vendorarray"];
//		$vendorarray = unserialize($vendorarray);
//		echo "<pre>";
//		print_r($vendorarray);
//		echo "</pre>";
//		$project_id = $row["id"];
//		echo "$project_id<br />";
//		$companyarray = array();
//
//	
//	foreach($vendorarray as $unique_id) {
//		$sql = "select company_id from users where id = $unique_id";
//		$sresult = mysql_query($sql);
//		$company_id = mysql_result($sresult,0,"company_id");		
//		echo "$unique_id -> $company_id<br />";
//		if (!in_array($company_id, $companyarray)) {
//			$companyarray[] = $company_id;
//		}
//		
//}
//	$newcompanyarray=serialize($companyarray);
//	
//
////Set the sql statment..
//	$sql =	"update projects set companyarray='$newcompanyarray' where id = '$project_id'";
//
//	if (!mysql_query($sql))
//		print("A database error occured in proccessing your submission.\\n");				
//		
//		
//		echo "<pre>";
//		print_r($companyarray);
//		echo "</pre>";
// 		unset($companyarray);
// 		echo "<hr />";
//}
?>
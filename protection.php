<?php	//check for changes?


$dbhost = "db1";
$dbuser = "construction";
$dbpass = "c0llab0r8";
$db = "construction";

function dbConnect() {
    global $dbhost, $dbuser, $dbpass, $db;
    
    $dbcnx = @mysql_connect($dbhost, $dbuser, $dbpass)
        or die("The site database appears to be down.");

    if (!@mysql_select_db($db))
        die("The site database is unavailable.");
    
    return $dbcnx;
}
dbConnect();
echo "Region Monitor <br />";
echo "<strong><a href=\"#sum\">  go to summary  </a></strong>";
echo "<br />";
$mass_sql = "select id, store_region, region_mon, ts from projects order by id asc";
$mass_result = mysql_query($mass_sql);
$error_count = 0;
$good_count = 0;
while ($row = mysql_fetch_array($mass_result)) {
$request_id=$row["id"];
$store_region=$row["store_region"];
$region_mon=$row["region_mon"];
$ts = $row["ts"];



if ($store_region != $region_mon) { 
	echo "<small><b>non-match! on #$request_id</b> $ts</small><br />";
	$error_count++;
	} else {
	echo "<small>#$request_id matched $ts</small><br />";
	$good_count++;
	}
}
echo "<div style=\"background:#eee;position:absolute;top:10px;left:200px;\">";
echo "<p>Errors: $error_count</p>";
echo "<p>Goods: $good_count</p>";
echo "</div>";



?>
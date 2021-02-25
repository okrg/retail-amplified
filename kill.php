<?php
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

	$sql = "FLUSH TABLES";
//	$result = mysql_query($sql);
//	$countz = mysql_result($result, 0, "count(*)");
//	$countz = mysql_result($result, 0, "Msg_text");

	//if (!$result) 	{
//		echo "error".mysql_error();
//	} else {
$result = mysql_list_processes();
while ($row = mysql_fetch_assoc($result)){
    print "<p>";
	printf("%s %s %s %s %s\n", $row["Id"], $row["Host"], $row["db"],
        $row["Command"], $row["Time"]);
    print "</p>";		
}
mysql_free_result($result);

	print "MySQL status = ".mysql_stat()."\n";
	mysql_close($con);
	//}

	
	//echo $countz;
?>
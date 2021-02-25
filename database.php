<?

/**
 * Connect to the mysql database.
 */
$conn = mysql_connect("db1", "construction", "c0llab0r8") or die(mysql_error());
mysql_select_db('construction', $conn) or die(mysql_error());

?>

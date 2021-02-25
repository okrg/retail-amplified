<?php
	dbConnect();
	$count = 0;

	foreach($_POST['clearbox'] as $value) {
		$sql = "update ror_clone set status='clear' where id = ".$value;
		if (!mysql_query($sql)) {
			error("A database error occured: " . mysql_error());
		}
		$count++;
	}
	echo "<span style=\"announce\">Cleared $count !</span>";

include("new-admin-g2.php"); ?>
<?php //admin-logs.php
?>
<div id="content">
<h1>:: View access logs</h1>
<a name="top"></a>
<div class="databox">
<p>Below are two activity logs for the site. The site access log shows when users log in and out of the system. The file download log keeps of track of when users download files.
</div>

<h1>:: Site access log</h1>
<a name="access"></a>
<div class="databox">
<?php

dbConnect();

	//Create list of users that have accessed site 50 at a time, show most recent first
	
	if (!isset($spill)) {
		$spill = 0;
		print "<p>Showing 50 most recent site access log entries. <a href=\"$PHP_SELF?page=admin-logs&spill=50\">View previous 50</a>.</p>";
		} else {
		$spillplus = $spill + 50;
		$spillminus = $spill - 50;
		print "<p>";
		print "[<a href=\"$PHP_SELF?page=admin-logs&spill=$spillplus\">Previous 50 entries</a>]&nbsp;";
		print "[<a href=\"$PHP_SELF?page=admin-logs\">Most recent entries</a>]&nbsp;";
		print "</p>";
	}
	
	$sql = "select * from viewlog order by ts desc limit $spill, 50";

	$result = mysql_query($sql);
	if (!$result)
		error("A databass error has occured in processing your request.\\n". mysql_error());

	print "<table border=\"0\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" class=\"filebox\">";
	print "<tr><th class=\"files\">Entry #</th>";
	print "<th class=\"files\">User</th>";
	print "<th class=\"files\">Action</th>";
	print "<th class=\"files\" style=\"border-right:none;\">Timestamp</th>";
	print "</tr>";

	while ($row = mysql_fetch_array($result)) {
		$id = $row["id"];
		$user = $row["user"];
		$project = $row["project"];
		$ts = $row["ts"];
		$ts = revertTimeStamp($ts);
		
		print "<tr>";	
		print "<td class=\"files\">$id</td>";
		print "<td class=\"files\">$user</td>";
		print "<td class=\"files\">$project</td>";
		print "<td class=\"files\">$ts</td>";
		print "</tr>";
		}
	print "</table>";

?>
</div>

</div>
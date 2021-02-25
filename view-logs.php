<?php //view-logs.php
?>
<div id="content">
<h1>:: View access logs</h1>
<a name="top"></a>
<div class="databox">
<p>Below are two activity logs for the site. The site access log shows when users log in and out of the system. The file download log keeps of track of when users download files.
<p>The time stamp has been fixed, and the times should now reflect Pacific timezone.</p>
<p>[<a href="#access">Site access log</a>]&nbsp;[<a href="#downloads">File download log</a>]</p>

</div>

<h1>:: Site access log</h1>
<a name="access"></a>
<div class="databox">
<?php

dbConnect('planetg0_projects');

	//Create list of users that have accessed site 50 at a time, show most recent first
	
	if (!isset($spill)) {
		$spill = 0;
		print "<p>Showing 50 most recent site access log entries. <a href=\"$PHP_SELF?page=view-logs&spill=50\">View previous 50</a>.</p>";
		} else {
		$spillplus = $spill + 50;
		$spillminus = $spill - 50;
		print "<p>";
		print "[<a href=\"$PHP_SELF?page=view-logs&spill=$spillplus\">Previous 50 entries</a>]&nbsp;";
		print "[<a href=\"$PHP_SELF?page=view-logs\">Most recent entries</a>]&nbsp;";
		print "</p>";
	}
	
	$sql = "select * from viewlog order by ts desc limit $spill, 50";

	$result = mysql_query($sql);
	if (!result)
		error("A databass error has occured in processing your request.\\n". mysql_error());

	print "<table border=\"0\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\" width=\"90%\" style=\"border:1px #999 solid;\">";
	print "<tr><th style=\"border-right:1px #999 solid;\">Entry #</th>";
	print "<th style=\"border-right:1px #999 solid;\">User</th>";
	print "<th style=\"border-right:1px #999 solid;\">Action</th>";
	print "<th>Timestamp</th>";
	print "</tr>";

	$index=1;

	

	while ($row = mysql_fetch_array($result)) {
		$id = $row["id"];
		$user = $row["user"];
		$project = $row["project"];
		$ts = $row["ts"];
		$ts = revertTimeStamp($ts);
		
		if (!$zebra) {
		print "<tr bgcolor=\"#ffffff\">";
		$zebra = true;
		} else {
		print "<tr bgcolor=\"#eeeeee\">";
		$zebra = false;
		}
		
		print "<td style=\"border-right:1px #999 solid;\">$id</td>";
		print "<td style=\"border-right:1px #999 solid;\">$user</td>";
		print "<td style=\"border-right:1px #999 solid;\">$project</td>";
		print "<td>$ts</td>";
		print "</tr>";
		}
	print "</table>";

?>
</div>
<a name="downloads"></a>
<h1>:: File download log</h1>

<div class="databox">
<?php

dbConnect('planetg0_projects');

	//Create list of users

	print "<p>Showing the most recently downloaded files, sorted by project name.</p>";
	
	//Create drop box of projects
		$sql = "select id, sitename from projects order by sitename desc";
		$result = mysql_query($sql);

	if (!result)
	{
	error("A databass error has occured.\\n".mysql_error());
	}
	print "<div>";
	print "<form><select style=\"font-family:Verdana;font-size:10px;background:#eee;\" name=\"projectlist\" onChange=\"goto_anchor(this.form.projectlist)\">";
	print "<option value=\"#downloads\">---Take a ride down memory lane---</option>";
	
//Cultivate data set
	while ($row = mysql_fetch_array($result))
	{
	$id = $row["id"];
	$sitename = $row["sitename"];	
	
	print "<option value=\"#$id\">$sitename</option>";

	}
	echo "</select></form></div>";
	
	
	
	$sql = "select * from downloadlog order by sitename desc, ts desc";

	$result = mysql_query($sql);
	if (!result)
		error("A databass error has occured in processing your request.\\n". mysql_error());
		
	print "<div align=\"center\">";
	print "<table cellspacing=\"0\" style=\"border:1px #999 solid;\">";
	print "<tr>";
	print "<th style=\"border-right:1px #ccc solid;\">User</th>";
	print "<th style=\"border-right:1px #ccc solid;\">(Distribution)&nbsp;&nbsp;Filename</th>";
	print "<th style=\"border-right:1px #ccc solid;\">Timestamp</th>";
	print "</tr>";

	while ($row = mysql_fetch_array($result)) {
		$id = $row["id"];
		$user = $row["user"];
		$sitename = $row["sitename"];
		$filename= $row["filename"];
		$ts = $row["ts"];
			$ts = revertTimeStamp($ts);
			
		$namespace = explode("/", $filename);
		
			
		if (!isset($divtest)) {
				$divtest = $sitename;
				print "<tr bgcolor=\"#dddddd\"><td style=\"padding:10px;border-top:2px #999 solid;\" colspan=\"2\"><a name=\"$namespace[1]\"></a><b>$sitename</b></td>";
				print "<td style=\"padding:10px;border-top:2px #999 solid;\">[<a href=\"#downloads\">Back to Top</a>]</td></tr>";
			} else {
				if ($divtest != $sitename) {

				print "<tr bgcolor=\"#dddddd\"><td style=\"padding:10px;border-top:2px #999 solid;\" colspan=\"2\"><a name=\"$namespace[1]\"></a><b>$sitename</b></td>";
				print "<td style=\"padding:10px;border-top:2px #999 solid;\">[<a href=\"#downloads\">Back to Top</a>]</td></tr>";
					$divtest = $sitename;
				}
			}
					
		$co_sql = "select company from users where fullname = '$user'";
		$co_result = mysql_query($co_sql);
		if (!result) {error("A database error occured:".mysql_error());}
		$co = mysql_result($co_result,0,"company");
		
		

		if (!isset($namespace[3])) {
			$filename = "$namespace[2]";
		} else {
			$filename = "<b>(</b>$namespace[2]<b>)</b>&nbsp;&nbsp;$namespace[3]";
		}
		
		if (!$zebra) {
		print "<tr bgcolor=\"#ffffff\">";
		$zebra = true;
		} else {
		print "<tr bgcolor=\"#eeeeee\">";
		$zebra = false;
		}
		print "<td style=\"border-right:1px #ccc solid;\" >$user<br />$co</td>";
		print "<td style=\"border-right:1px #ccc solid;\">$filename</td>";
		print "<td>$ts</td>";
		print "</tr>";

		}
	print "</table>";
	print "</div>";

?>
</div>

</div>
<div id="content">
<h1>::All Locations</h1>
<div class="databox">
<p><img src="images/avatar_clean.gif" /> Tip: Press <strong>Ctrl+F</strong> to search for specific text</p>

<?php
	dbConnect();
	$sql = "select * from projects where chain = 1 order by store_number";
	$result = mysql_query($sql);
	if (!$result){error("A databass error has occured.\\n".mysql_error());}

	print "<div class=\"sortbox\">";
	print "<table width=\"100%\" cellpadding=\"0\" class=\"sortable\" id=\"vmops\" cellspacing=\"0\">";
	print "<tr>";
	print "<th>Number</th>";
	print "<th>Location Name</th>";	
	print "<th>District</th>";
	print "<th>Region</th>";
	print "<th>City</th>";
	print "<th>State</th>";		
	print "</tr>";

//Cultivate data set
	while ($row = mysql_fetch_object($result)) {
		if ($row->id == 471 or $row->id == 523 or $row->id == 548 or $row->id == 478) {continue;}
		print "<tr>";
		print "<td class=\"project\">$row->store_number &nbsp;</td>";
		print "<td class=\"project\"><a href=\"$PHP_SELF?page=project&id=$row->id\">$row->sitename</a> &nbsp;</td>";
		print "<td class=\"project\">$row->store_district &nbsp;</td>";
		print "<td class=\"project\">$row->store_region &nbsp;</td>";	
		print "<td class=\"project\">$row->sitecity &nbsp;</td>";
		print "<td class=\"project\">$row->sitestate &nbsp;</td>";
		print "</tr>";
	}
	echo "</table></div>";

?>
</div>
</div>
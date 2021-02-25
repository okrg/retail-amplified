<div id="content">
<h1>::Stores BY Region</h1>
<div class="databox">

<?php
//Charlotte-Russe box
	echo "<h2>Archived Charlotte Russe stores by Region</h2>";
	
	dbConnect();
	if (!isset($sort)) {$sortstyle = "store_region";} else {$sortstyle = $sort;}
//Vendor filter, vendors only see projects that have them tagged as vendors for that project
	if ($usergroup == 3) {
		$sql = "select id from users where userid = '$uid' and pwd = '$pwd'";
		$result = mysql_query($sql);
		if (!$result) {error("A database error occured:".mysql_error());}
		$vendorID = mysql_result($result,0,"id");
		$sql = "SELECT  id, sitenum, sitename, datetouched, store_number, store_district, store_region FROM projects WHERE `vendorarray` LIKE '%:\"".$vendorID."\";%'  and chain=1 and project_status='archive' order by sitename desc";
		} else {
//All other useres get the full list
		$sql = "select id, sitename, datetouched, store_number, store_district, store_region, chain from projects where project_status='archive' order by $sortstyle";
	}
//Execute filter on db!
	$result = mysql_query($sql);

	if (!result)
	{
	error("A databass error has occured.\\n".mysql_error());
	}
	
	if (mysql_num_rows($result) == 0) {
		print "<div class=\"filebox\">";
		print "<p><strong>Notice:</strong> It appears that there are currently no projects which you have been granted access to. Check back at a later date<em>!!</em></p>";
		print "</div>";
	} else {
		
	print "<div class=\"filebox\">";
	print "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
	print "<tr>";
	print "<th><a href=\"$PHP_SELF?page=archive&sort=datetouched\">Last Modified</a></th>";
	print "<th><a href=\"$PHP_SELF?page=archive&sort=store_region\">Region</a></th>";
	print "<th><a href=\"$PHP_SELF?page=archive&sort=store_district\">District</a></th>";
	print "<th><a href=\"$PHP_SELF?page=archive&sort=store_number\">Store #</a></th>"; 
	print "<th><a href=\"$PHP_SELF?page=archive&sort=sitename\">Location Name</a></th>";	
	print "<th><a href=\"$PHP_SELF?page=archive&sort=chain\">Chain</a></th>";
	print "</tr>";
	$count=0;
	
//Cultivate data set
	while ($row = mysql_fetch_array($result))
	{
	$id = $row["id"];
	$store_number = $row["store_number"];
	$store_region = $row["store_region"];
	$store_district = $row["store_district"];
	$sitename = $row["sitename"];
	$chain = $row["chain"];
	$datetouched = $row["datetouched"];
	$datetouched = dateconvert($datetouched);
	
	if ($chain == 1) {
		$chain_name = "Charlotte Russe";
		} elseif ($chain == 2) {
		$chain_name = "Rampage";
		}
		
	$count++;	
	print "<tr><td class=\"project\">$datetouched &nbsp;</td>";
	print "<td class=\"project\">$store_region &nbsp;</td>";
	print "<td class=\"project\">$store_district &nbsp;</td>";
	print "<td class=\"project\">$store_number &nbsp;</td>";
	print "<td class=\"project\"><a href=\"$PHP_SELF?page=project&id=$id\">$sitename</a> &nbsp;</td>";
	print "<td class=\"project\">$chain_name &nbsp;</td>";	
	print "</tr>";
	
	if ($count == 40) {
				print "<tr>";
				print "<th><a href=\"$PHP_SELF?page=archive&sort=datetouched\">Last Modified</a></th>";
				print "<th><a href=\"$PHP_SELF?page=archive&sort=store_region\">Region</a></th>";
				print "<th><a href=\"$PHP_SELF?page=archive&sort=store_district\">District</a></th>";
				print "<th><a href=\"$PHP_SELF?page=archive&sort=store_number\">Store #</a></th>"; 
				print "<th><a href=\"$PHP_SELF?page=archive&sort=sitename\">Location Name</a></th>";	
				print "<th><a href=\"$PHP_SELF?page=archive&sort=chain\">Chain</a></th>";
				print "</tr>";
				$count=0;		
				}
	}
	echo "</table></div>";
}

?>
</div>
</div>
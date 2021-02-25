<?php
	if (!isset($sort)) {$sortstyle = "Region"; $sort=NULL;} else {$sortstyle = $sort;}
	switch ($sort) {
		case "datetouched": $sortstyle="date last modified";
		break;
		case "store_region": $sortstyle="store region";
		break;
		case "store_district": $sortstyle="store district";
		break;
		case "store_number": $sortstyle="store number";
		break;
		case "sitename": $sortstyle="location name";
		break;
		case "chain": $sortstyle="store chain";
		break;
		default: $sortstyle="date last modified"; $sort = "datetouched desc";
		break;	
		}
		$filtermsg = "";
	if (isset($_GET['restrict'])) {
		switch ($_GET['restrict']) {
			case "store_region": $filter="store region";
				$q = $_GET['q'];
			break;

			case "store_district": $filter="store district";
				$q = $_GET['q'];
			break;
			
			case "project_status": $filter= "project status";
				$q = $_GET['q'];
			break;
		
			case "chain": $filter="store chain";
				if ($_GET['q'] == 1) { $q="Charlotte Russe";} elseif ($_GET['q'] == 2) {$q="Rampage";}
			break;
			}
		$filtermsg = "<h2>[Filtered View] Showing all stores in $filter: $q</h2>";
		$filtermsg .= "[<a href=\"$PHP_SELF?page=archive\">Reset Filter</a>]<br /><br />";
		}		
?>
<div id="content">
<h1>::Stores BY <?=$sortstyle?></h1>
<div class="databox">

<?php
	echo "$filtermsg";
	dbConnect();
//Vendor filter, vendors only see projects that have them tagged as vendors for that project
	if ($usergroup == 3) 
	{
		$sql = "select id from users where userid = '$uid' and pwd = '$pwd'";
		$result = mysql_query($sql);
		if (!$result) {error("A database error occured:".mysql_error());}
		$vendorID = mysql_result($result,0,"id");
		$sql = "SELECT  id, sitenum, sitename, datetouched, store_number, store_district, store_region FROM projects WHERE `vendorarray` LIKE '%:\"".$vendorID."\";%'  and chain=1 and project_status='archive' order by sitename desc";
	}
	else if ($usercompany == 7)
	{
		if (isset($_GET['restrict'])) {
			$sql = "select id, sitename, datetouched, store_number, store_district, store_region, chain, project_status from projects where {$_GET['restrict']}='{$_GET['q']}' order by $sort";		
		} else {	
		$sql = "select id, sitename, datetouched, store_number, store_district, store_region, chain, project_status from projects order by $sort";
		}
	}
	else 
	{
		//All other useres get the full list
		if (isset($_GET['restrict'])) {
			$sql = "select id, sitename, datetouched, store_number, store_district, store_region, chain, project_status from projects where {$_GET['restrict']}='{$_GET['q']}' and project_status != 'real_estate' order by $sort";		
		} else {	
		$sql = "select id, sitename, datetouched, store_number, store_district, store_region, chain, project_status from projects where project_status != 'real_estate' order by $sort";
		}
	}
//Execute filter on db!
	$result = mysql_query($sql);

	if (!$result)
	{
	error("A databass error has occured.\\n".mysql_error());
	}
	
	if (mysql_num_rows($result) == 0) {
		print "<div class=\"sortbox\">";
		print "<p><strong>Notice:</strong> It appears that there are currently no projects which you have been granted access to. Check back at a later date<em>!!</em></p>";
		print "</div>";
	} else {
	print "<p>Active projects are indicated by &#8226;.<br />";
	print "You may filter for <a href=\"$PHP_SELF?page=archive&restrict=project_status&q=active\">active projects only</a> or <a href=\"$PHP_SELF?page=archive&restrict=project_status&q=archive\">archive projects only</a>.</p>	";
	print "<div class=\"sortbox\">";
	print "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
	print "<tr>";
	if (isset($_GET['restrict'])) { //if filter is set, preserve filter criteria
		if (isset($_GET['sort'])) { //however, if filter is set and and has already been sorted,.don't repeat the sort from q string, start over
	print "<th><a href=\"$PHP_SELF?page=archive&restrict={$_GET['restrict']}&q={$_GET['q']}&sort=datetouched\">Last Modified</a></th>";
	print "<th><a href=\"$PHP_SELF?page=archive&restrict={$_GET['restrict']}&q={$_GET['q']}&sort=store_region\">Region</a></th>";
	print "<th><a href=\"$PHP_SELF?page=archive&restrict={$_GET['restrict']}&q={$_GET['q']}&sort=store_district\">District</a></th>";
	print "<th><a href=\"$PHP_SELF?page=archive&restrict={$_GET['restrict']}&q={$_GET['q']}&sort=store_number\">Store #</a></th>"; 
	print "<th><a href=\"$PHP_SELF?page=archive&restrict={$_GET['restrict']}&q={$_GET['q']}&sort=sitename\">Location Name</a></th>";	
	print "<th><a href=\"$PHP_SELF?page=archive&restrict={$_GET['restrict']}&q={$_GET['q']}&sort=chain\">Chain</a></th>";
	} else { 
	print "<th><a href=\"$PHP_SELF?{$_SERVER['QUERY_STRING']}&sort=datetouched\">Last Modified</a></th>";
	print "<th><a href=\"$PHP_SELF?{$_SERVER['QUERY_STRING']}&sort=store_region\">Region</a></th>";
	print "<th><a href=\"$PHP_SELF?{$_SERVER['QUERY_STRING']}&sort=store_district\">District</a></th>";
	print "<th><a href=\"$PHP_SELF?{$_SERVER['QUERY_STRING']}&sort=store_number\">Store #</a></th>"; 
	print "<th><a href=\"$PHP_SELF?{$_SERVER['QUERY_STRING']}&sort=sitename\">Location Name</a></th>";	
	print "<th><a href=\"$PHP_SELF?{$_SERVER['QUERY_STRING']}&sort=chain\">Chain</a></th>";
	}
	} else { //else no filter, no worries
	print "<th><a href=\"$PHP_SELF?page=archive&sort=datetouched\">Last Modified</a></th>";
	print "<th><a href=\"$PHP_SELF?page=archive&sort=store_region\">Region</a></th>";
	print "<th><a href=\"$PHP_SELF?page=archive&sort=store_district\">District</a></th>";
	print "<th><a href=\"$PHP_SELF?page=archive&sort=store_number\">Store #</a></th>"; 
	print "<th><a href=\"$PHP_SELF?page=archive&sort=sitename\">Location Name</a></th>";	
	print "<th><a href=\"$PHP_SELF?page=archive&sort=chain\">Chain</a></th>";
	}
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
	$project_status = $row["project_status"];
	$datetouched = $row["datetouched"];
	$datetouched = dateconvert($datetouched);
	
	if ($chain == 1) {$chain_name = "Charlotte Russe";} elseif ($chain == 2) {$chain_name = "Rampage";} //Indicate what chain the store is in.
		
	if ($project_status == "active") { $status = "&#8226;";} else { $status = "";} //Flag active projects with a solid dot character
		
	$count++;	
	print "<tr><td class=\"project\">$datetouched &nbsp;</td>";
	print "<td class=\"project\"><a href=\"$PHP_SELF?page=archive&restrict=store_region&q=$store_region\">$store_region</a>&nbsp;</td>";
	print "<td class=\"project\"><a href=\"$PHP_SELF?page=archive&restrict=store_district&q=$store_district\">$store_district</a>&nbsp;</td>";
	print "<td class=\"project\">$store_number &nbsp;</td>";
	print "<td class=\"project\">$status <a href=\"$PHP_SELF?page=project&id=$id\">$sitename</a> &nbsp;</td>";
	print "<td class=\"project\"><a href=\"$PHP_SELF?page=archive&restrict=chain&q=$chain\">$chain_name</a>&nbsp;</td>";	
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
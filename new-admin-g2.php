<?php
//Benchmark function
function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}
	$time_start = microtime_float();

// Connect to database
dbConnect();

//Load actions
include ("admin-g2-actions.php");


if (isset($_POST['groupsof'])) {
	$groupsof = $_POST['groupsof'];
	} else {
	$groupsof = 100;
	$_POST['groupsof'] = 100;
	}

//Check for sorts, and if they are Get sent, then turn them into posts 
if (isset($_GET['sort'])) {
	$_POST['sort'] = $_GET['sort'];
	}


//Determine total number of records
$result = mysql_query("select id from ror_clone where status != 'clear' and parent = 0");
$total_num_rows = mysql_num_rows($result);


//Determine the current page range
//if a sort range does exist, echo it somehow as a "$sortrange" so that it applies to sort links too

if (!isset($_POST['range'])) {
	$listing_range = "1-$groupsof";
	$listing_header = "Open requests $listing_range ($total_num_rows total)";
	$range = "0, $groupsof";
	$sortrange = "";
	} else {
	$groupsofminusone = $groupsof-1;
	$upper_limit = $_POST['range']+$groupsofminusone;
	$listing_range = $_POST['range']."-$upper_limit";
	$listing_header = "Open requests $listing_range ($total_num_rows total)";
	$range = $_POST['range'].", $groupsof";
	}
//Default sorting and ranging
if ($_POST['sort'] == "" ) {
	$sortmethod = "issue_date desc limit $range";
	} else {
	$client_sort = $_POST['sort'];
	$sortmethod = $client_sort .", issue_date desc limit $range";
	}	
if (!isset($_POST['sort'])){
	$sortmethod = "issue_date desc limit $range";
}
//if sorting by date, set up descending
if ($_POST['sort'] == "issue_date") {
	$sortmethod = "issue_date desc limit $range";
	}


function start_table() {
	print "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr>";
	print "<th><a href=\"#\" onClick=\"check(document.actions.elements['clearbox[]']);return false;\"><small>All</small></a></th>"; 
	print "<th width=\"28\"><small><a href=\"$PHP_SELF?page=new-admin-g2&sort=store_number\">Store</a></small></th>"; 
	print "<th width=\"22\"><small><a href=\"$PHP_SELF?page=new-admin-g2&sort=store_district\">Dist.</a></small></th>"; 
	print "<th width=\"22\"><small><a href=\"$PHP_SELF?page=new-admin-g2&sort=store_region\">Reg.</a></small></th>"; 
	print "<th><small><a href=\"$PHP_SELF?page=new-admin-g2&sort=project_id\">Location</a></small></th>";
	print "<th><small><a href=\"$PHP_SELF?page=new-admin-g2&sort=type\">Type</a></small></th>";
	print "<th><small><a href=\"$PHP_SELF?page=new-admin-g2&sort=priority\">Priority</a></small></th>";
	print "<th width=\"105\" align=\"right\"><small><a href=\"$PHP_SELF?page=new-admin-g2&sort=issue_date\">Date</a></th>";
	print "</tr>";
}

if (($usercompany == 4) or ($usergroup == 0)) {

if ((isset($_POST['filter'])) && ($_POST['filter'] != "")) {
	$var = $_POST['filter_var'];
	$var = ltrim($var);
	$var = rtrim($var);
	
	$pagination=FALSE;
	$listing_header = "Filtered requests";

	switch ($_POST['filter']) {
		default:
		$filter_message = "No filtering set. Showing all!";
		$filter_sql = "";
		break;
		case "tracking":
		$filter_message = "Showing repair order request <strong>#$var</strong>";
		$filter_sql = "and tracking = '$var' ";
		$collapsed=FALSE;
		break;
		case "store_number":
		$filter_message = "Showing repair order requests for <strong>store #$var</strong>";
		$filter_sql = "and store_number = '$var' ";
		$collapsed=TRUE;
		break;
		case "store_district":
		$filter_message = "Showing repair order requests for <strong>district #$var</strong>";
		$filter_sql = "and store_district = '$var' ";
		$collapsed=TRUE;
		break;
		case "store_region":
		$filter_message = "Showing repair order requests for <strong>region #$var</strong>";
		$filter_sql = "and store_region = '$var' ";
		$region_filter=TRUE;
		break;
		}	

} else {
	$pagination=TRUE;
	$filter_message = "No filtering set. Showing all!";
	$filter_sql = "";
}




?>
<div id="content">
<div class="breadcrumbs"><a href="/">Home</a> &raquo; <a href="<?=$_SERVER['PHP_SELF']?>?page=admin">Admin Options</a> &raquo; Repair Request Orders</div>

<div class="databox">
<form name="admin_filter" method="post" action="<?php echo "$PHP_SELF?page=new-admin-g2"; ?>">
<?=$filter_message?> <select name="filter">
<option value="">-Select-</option>
<option value="tracking">Tracking Number</option>
<option value="store_number">Store Number</option>
<option value="store_district">Store District</option>
<option value="store_region">Store Region</option>
</select>
<input type="text" name="filter_var" />
<input type="submit" class="bigshinybutton" value="Go!" />
<input type="submit" class="bigshinybutton" value="Reset All" />
</form>


<?php
if ($pagination) { ?>
	<form name="groupsof" method="post" action="<?php echo "$PHP_SELF?page=new-admin-g2"; ?>">
	<?php
	if (isset($_POST['range'])) { echo "<input type=\"hidden\" name=\"range\" value=\"".$_POST['range']."\" />"; }
	if (isset($_POST['sort'])) { echo "<input type=\"hidden\" name=\"sort\" value=\"".$_POST['sort']."\" />"; }
	if ($reporting_mode) {
		echo "<input type=\"hidden\" name=\"action\" value=\"report\" />";

		if (isset($_POST['priority'])) { 
			foreach($_POST['priority'] as $value) {
				echo "<input type=\"hidden\" name=\"priority[]\" value=\"".$value."\" />";
			}
		}
		
		if (isset($_POST['type'])) { 
			foreach($_POST['type'] as $value) {
				echo "<input type=\"hidden\" name=\"type[]\" value=\"".$value."\" />";
			}
		}
	
		if (isset($_POST['status'])) { 
			foreach($_POST['status'] as $value) {
				echo "<input type=\"hidden\" name=\"status[]\" value=\"".$value."\" />";
			}
		}

		if (isset($_POST['store_number'])) { echo "<input type=\"hidden\" name=\"store_number\" value=\"".$_POST['store_number']."\" />"; }
		if (isset($_POST['store_district'])) { echo "<input type=\"hidden\" name=\"store_district\" value=\"".$_POST['store_district']."\" />"; }
		if (isset($_POST['store_region'])) { echo "<input type=\"hidden\" name=\"store_region\" value=\"".$_POST['store_region']."\" />"; }
		}
	?>
	
	

	Showing in groups of:
	<select name="groupsof" onChange="document.groupsof.submit();">
	<option value="<?=$groupsof?>"><?=$groupsof?></option>
	<option value="10">10</option>
	<option value="25">25</option>
	<option value="50">50</option>
	<option value="100">100</option>
	<option value="200">200</option>
	</select>
	</form>
<? } ?>
<a href="#" onClick="toggleBox('reports',1);"><p><strong>Report Builder</strong></p></a>
<div id="reports" style="display: none;">
<form name="reports" method="post" action="<?php echo "$PHP_SELF?page=new-admin-g2&action=report"; ?>">
<table cellpadding="10">
<tr>
<td style="vertical-align:top;">
<strong>Priority</strong><br />
<input name="priall" type="checkbox" value="" id="priall" onClick="toggleChecks('priority')" checked /><label for="priall">All priorties</label><br />
<input name="priority[]" type="checkbox" value="Not Urgent" id="radnot" disabled /><label for="radnot"><img src="/images/Not Urgent.gif" /> Not Urgent</label><br />
<input name="priority[]" type="checkbox" value="Minor" id="radmin" disabled /><label for="radmin"><img src="/images/Minor.gif" /> Minor</label><br />
<input name="priority[]" type="checkbox" value="Urgent" id="radurg" disabled /><label for="radurg"><img src="/images/Urgent.gif" /> Urgent</label><br />
<input name="priority[]" type="checkbox" value="Hazard" id="radhaz" disabled /><label for="radhaz"><img src="/images/Hazard.gif" /> Hazard<br /></label>
</td>
<td style="vertical-align:top;">
<strong>Type</strong><br />
<input name="typeall" type="checkbox" value="" id="typeall" onClick="toggleChecks('type')" checked /><label for="typeall">All types</label><br />
<input name="type[]" type="checkbox" value="Lighting" id="typelig" disabled /><label for="typelig">Lighting</label><br />
<input name="type[]" type="checkbox" value="Plumbing" id="typeplu" disabled /><label for="typeplu">Plumbing</label><br />
<input name="type[]" type="checkbox" value="Walls/Paint" id="typewal" disabled /><label for="typewal">Walls/Paint</label><br />
<input name="type[]" type="checkbox" value="Flooring" id="typeflo" disabled /><label for="typeflo">Flooring</label><br />
<input name="type[]" type="checkbox" value="Pest Control" id="typepend" disabled /><label for="typepend">Pest Control</label><br />
<input name="type[]" type="checkbox" value="Electrical" id="typeele" disabled /><label for="typeele">Electrical</label><br />
<input name="type[]" type="checkbox" value="HVAC" id="typehva" disabled /><label for="typehva">HVAC</label><br />
<input name="type[]" type="checkbox" value="Locks" id="typeloc" disabled /><label for="typeloc">Locks</label><br />
<input name="type[]" type="checkbox" value="Gate" id="typegat" disabled /><label for="typegat">Gate</label><br />
<input name="type[]" type="checkbox" value="Other" id="typeoth" disabled /><label for="typeoth">Other</label><br />
</td>
<td style="vertical-align:top;">
<strong>Status</strong><br />
<input name="statall" type="checkbox" value="" id="statall" onClick="toggleChecks('status')" checked /><label for="statall">All</label><br />
<input name="status[]" type="checkbox" value="Pending" id="statpend" disabled /><label for="statpend"><img src="/images/pending.gif" align="absmiddle" /> Pending</label><br />
<input name="status[]" type="checkbox" value="Answered" id="statans" disabled /><label for="statans"><img src="/images/answered.gif" align="absmiddle" /> Open</label><br />
<input name="status[]" type="checkbox" value="Clear" id="statclr" disabled /><label for="statclr"><img src="/images/clear.gif" align="absmiddle" /> Closed</label><br />
</td>
</tr>
<tr>
<td>Region:
<select name="store_region">
<option value="" >All</option>

<?php
$selectsql = "select distinct store_region from ror_clone order by store_region asc";
$result = mysql_query($selectsql);
	if (!$result) 	{
		error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error());
	}
	while ($row = mysql_fetch_object($result))
	{if ($row->store_region==0){continue;}else{print "<option value=\"$row->store_region\">$row->store_region</a>";}}

?>
</select>
</td>
<td>District:
<select name="store_district">
<option value="" selected>All</option>

<?php
$selectsql = "select distinct store_district from ror_clone order by store_district asc";
$result = mysql_query($selectsql);
	if (!$result) 	{
		error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error());
	}
	while ($row = mysql_fetch_object($result))
	{if ($row->store_district==0){continue;}else{print "<option value=\"$row->store_district\">$row->store_district</a>";}}

?>
</select>
</td>

<td>Store:
<select name="store_number">
<option value="" selected>All</option>

<?php
$selectsql = "select distinct store_number from ror_clone order by store_number asc";
$result = mysql_query($selectsql);
	if (!$result) 	{
		error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error());
	}
	while ($row = mysql_fetch_object($result))
	{if ($row->store_number==0){continue;}else{print "<option value=\"$row->store_number\">$row->store_number</a>";}}

?>
</select>
</td>
</tr>
</table>
<input type="submit" class="bigshinybutton" />
</form>
</div>



</div>




<?php 
	if (!$reporting_mode) {
	
	$sql = "select id, project_id, store_number, store_district, store_region, priority, timestamp, issue_date, type, status from ror_clone where status != 'clear' and parent = 0 $filter_sql";
	$sql .= "order by $sortmethod";	
	} else {
	$sql = "select id, project_id, store_number, store_district, store_region, priority, timestamp, issue_date, type, status from ror_clone where (";
	if ($reporting_stno_sql!=""){$sql .= $reporting_stno_sql;
	$sql .= ") AND (";}
	if ($reporting_stdi_sql!=""){$sql .= $reporting_stdi_sql;
	$sql .= ") AND (";}
	if ($reporting_stre_sql!=""){$sql .= $reporting_stre_sql;
	$sql .= ") AND (";}
	if (isset($_POST['type'])){$sql .= $reporting_type_sql;
	$sql .= ") AND (";}
	if (isset($_POST['status'])){$sql .= $reporting_status_sql;
	$sql .= ") AND (";}
	if (isset($_POST['priority'])){$sql .= $reporting_priority_sql;
	$sql .= ") AND parent = 0";
	} else {
	$sql .= "id>0) AND parent = 0";
	echo $sql;
	}

	$row_sql = mysql_query($sql);
	$row_nums = mysql_num_rows($row_sql);
	$listing_header = "Report $listing_range ($row_nums total results)";

	
	$sql .= " order by $sortmethod";	
	}
	print "<h1>:: $listing_header</h1>";
	$result = mysql_query($sql);
	if (!$result) 	{
		error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error());
	}
	
	if (mysql_num_rows($result) == 0) {
		print "<p>No requests to show!</p>";
	} else {
	print "<div class=\"sortbox\" style=\"width:95%;margin: 0 auto;\">";
 //previous, next links
	if ($pagination) {
		include ("admin-g2-paging.php");
		}

	print "<form method=\"post\" name=\"actions\" onSubmit=\"return confirmSubmit();\">";
	if (isset($_POST['range'])) { echo "<input type=\"hidden\" name=\"range\" value=\"".$_POST['range']."\" />"; }
	print "<input type=\"hidden\" name=\"groupsof\" value=\"".$_POST['groupsof']."\" />";
	print "<input type=\"submit\" class=\"bigshinybutton\" value=\"Close Request\" onClick=\"document.actions.action='$PHP_SELF?page=new-admin-g2&action=close'\" />";
	print "<input type=\"submit\" class=\"bigshinybutton\" value=\"Trash\" onClick=\"document.actions.action='$PHP_SELF?page=new-admin-g2&action=trash'\" />";

	echo start_table($sortrange);
	$count=0;
	
//Cultivate data set
	while ($row = mysql_fetch_object($result))
	{
	//Get location details for this request based on project_id
	$loc_res = mysql_query("select sitename from projects where id=".$row->project_id."");
	$loc_name = mysql_result($loc_res, 0);
	$statusclass=$row->status;
	if ($statusclass == "clear") {
		$statusclass="cleared";
		}
		 
	$flag = "<a href=\"index.php?page=project&id=$row->project_id\" target=\"_blank\" title=\"Project Page\">
			<img src=\"http://construction.charlotte-russe.com/images/project-page.gif\" border=\"0\" align=\"absmiddle\"></a>&nbsp;<span id=\"flag".$row->id."\" class=\"".$statusclass."\">
			<a href=\"#\" title=\"Expand\" onClick=\"javascript:ajax_do('data.php?id=".$row->id."');toggleBox('box".$row->id."',1);return false;\">$loc_name</a></span>&nbsp;";
	print "<td><input name=\"clearbox[]\" type=\"checkbox\" value=\"".$row->id."\" /></td>";		
	print "<td><small>$row->store_number &nbsp;</small></td>";
	print "<td><small>$row->store_district &nbsp;</small></td>";
	print "<td><small>$row->store_region&nbsp;</small></td>";
	print "<td>$flag</td>";
	print "<td><small>$row->type</a></small></td>";
	print "<td align=\"center\"><small><img src=\"http://construction.charlotte-russe.com/images/".$row->priority.".gif\" title=\"".$row->priority."\" /></small></td>";
	print "<td align=\"right\"><small>$row->issue_date&nbsp;</small></td>";
	print "</tr>";
	print "<tr class=\"noline\"><td></td><td></td><td></td><td></td><td colspan=\"4\">";
		print "<div id=\"box".$row->id."\" style=\"display:none;margin-left:17px;\" class=\"bigshinybutton\"></div>";
	print "</td></tr>";
	
	}
	mysql_free_result($result);

	print "</table>";
	print "</form>";


	//previous, next links
	if ($pagination) {include ("admin-g2-paging.php");}
	print "</div>";

}
?>


</div>

<?php
} else {
echo "You do not have sufficient privledges to view this page";
exit;

}


$time_end = microtime_float();
$time = $time_end - $time_start;

echo "Finished in $time seconds\n";
?>
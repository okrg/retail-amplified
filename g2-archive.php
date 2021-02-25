<?php
//Benchmark function
function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}
	$time_start = microtime_float();

//Get user domain value...
	$user_uid_rank = substr($uid,0,2);
	$user_domain = substr($uid,2);
	
	if ($user_uid_rank == "dm") {
		$domain_value="District";
		$db_string = "store_district";
		} elseif ($user_uid_rank == "rm") {
			$domain_value="Region";
			$db_string="store_region";
			}


// Connect to database
dbConnect();

//Load actions
include ("admin-g2-actions.php");

//load groupsof variable default value of 100
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




if($_POST['action']=="report"){
	$default_current = FALSE;
	$report_current = TRUE;
}

//Determine total number of records
$result = mysql_query("select id from repair_orders where status='clear' and parent = 0 and $db_string = $user_domain");
$total_num_rows = mysql_num_rows($result);


//Determine the current page range
//if a sort range does exist, echo it somehow as a "$sortrange" so that it applies to sort links too

if (!isset($_POST['range'])) {
	$listing_range = "1-$groupsof";
	$listing_header = "$view_mode $listing_range ($total_num_rows total)";
	$range = "0, $groupsof";
	$sortrange = "";
	} else {
	$groupsofminusone = $groupsof-1;
	$upper_limit = $_POST['range']+$groupsofminusone;
	$listing_range = $_POST['range']."-$upper_limit";
	$listing_header = "$view_mode $listing_range ($total_num_rows total)";
	$range = $_POST['range'].", $groupsof";
	}
//Default sorting and ranging
if ($_POST['sort'] == "" ) {
	$sortmethod = "store_number, urgency limit $range";
	} else {
	$client_sort = $_POST['sort'];
	$sortmethod = $client_sort ." limit $range";
	}	
if (!isset($_POST['sort'])){
	$sortmethod = "store_number, urgency limit $range";
}
//if sorting by date, set up descending
if ($_POST['sort'] == "issue_date") {
	$sortmethod = "issue_date desc limit $range";
	}


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
<div class="breadcrumbs"><a href="/">Home</a> &raquo; Repair Order Request History</div>

<table align="center" width="95%">
<tr><td>
<form name="admin_filter" method="post" action="<?php echo "$PHP_SELF?page=g2-archive"; ?>">
<select name="filter">
<option value="">Select Filter</option>
<option value="tracking">Tracking Number</option>
<option value="store_number">Store Number</option>
<option value="store_district">Store District</option>
<option value="store_region">Store Region</option>
</select>
<input type="text" name="filter_var" />
<input type="submit" class="bigshinybutton" value="Go!" />
<input type="submit" class="bigshinybutton" value="Reset All" />
</form>
</td>
<td align="right">
<?php
if ($pagination) { ?>
	<form name="groupsof" method="post" action="<?php echo "$PHP_SELF?page=g2-archive";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];} ?>">
	<?php
	if (isset($_POST['range'])) { echo "<input type=\"hidden\" name=\"range\" value=\"".$_POST['range']."\" />"; }
	if (isset($_POST['sort'])) { echo "<input type=\"hidden\" name=\"sort\" value=\"".$_POST['sort']."\" />"; }
	?>
	
	<select name="groupsof" onChange="document.groupsof.submit();">
	<option value="<?=$groupsof?>">Groups of <?=$groupsof?></option>
	<option value="10">Groups of 10</option>
	<option value="25">Groups of 25</option>
	<option value="50">Groups of 50</option>
	<option value="100">Groups of 100</option>
	<option value="200">Groups of 200</option>
	</select>
	</form>
<? } ?>

</td></tr></table>

<?php 
		$sql = "select id, project_id, store_number, store_district, store_region, priority, timestamp, issue_date, type, status from repair_orders where status='clear' and parent = 0 and $db_string = $user_domain ";
		$sql .= "order by $sortmethod";	
		$listing_greeting = "$domain_value $user_domain &raquo;";
		print "<div style=\"font-size:10px;margin: 5px 10px;font-weight:bold;\"><p>Showing all cleared requests for $listing_greeting  $listing_header</p></div>";
	$row_sql = mysql_query($sql);
	$row_nums = mysql_num_rows($row_sql);
	$listing_header = "Report $listing_range ($row_nums total)";

	$master_result = mysql_query($sql);
	if (!$master_result) 	{
		error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error());
	}
	
	if (mysql_num_rows($master_result) == 0) {
		print "<p>No requests to show!</p>";
	} else {

	print "<div style=\"clear:both;\"></div>";
	
	print "<div class=\"sortbox\" style=\"width:95%;margin: 0 auto;\">";
	
	
	
	
 //previous, next links
	if ($pagination) {
		include ("admin-g2-paging.php");
		}

	print "<form method=\"post\" name=\"actions\">";
	
	if (isset($_POST['range'])) { echo "<input type=\"hidden\" name=\"range\" value=\"".$_POST['range']."\" />"; }
	print "<input type=\"hidden\" name=\"groupsof\" value=\"".$_POST['groupsof']."\" />";
	print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	print "<input type=\"button\" class=\"bigshinybutton\" value=\"Expand\" onClick=\"expandChecked('data',document.actions.elements['clearbox[]']);return false;\" />";
	print "<input type=\"button\" class=\"bigshinybutton\" value=\"Collapse\" onClick=\"collapseChecked(document.actions.elements['clearbox[]']);return false;\" />";
	//echo start_table($sortrange);

	print "\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr>";
	print "<th><a href=\"#\" onClick=\"check(document.actions.elements['clearbox[]']);return false;\"><small>All</small></a></th>";
	print "<th width=\"22\"><a href=\"/index.php?page=g2-archive&sort=store_number";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}print "\"><small>Store</small></a></th>";
	print "<th width=\"22\"><a href=\"/index.php?page=g2-archive&sort=store_district";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}print "\"><small>District</small></a></th>";
	print "<th width=\"22\"><a href=\"/index.php?page=g2-archive&sort=store_region";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}print "\"><small>Region</small></a></th>";
	print "<th><a href=\"/index.php?page=g2-archive&sort=project_id";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}print "\"><small>Location</small></a></th>";
	print "<th><a href=\"/index.php?page=g2-archive&sort=type";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}print "\"><small>Type</small></a></th>";
	print "<th><a href=\"/index.php?page=g2-archive&sort=urgency";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}print "\"><small>Priority</small></a></th>";
	print "<th width=\"105\" align=\"right\"><a href=\"/index.php?page=g2-archive&sort=issue_date";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}print "\"><small>Date</small></a></th>";

	print "</tr>";
	print "\n";
	$count=0;
	
//Cultivate data set
	while ($row = mysql_fetch_object($master_result))
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
			<a href=\"#\" title=\"Expand\" onClick=\"javascript:ajax_do('data.php?ug=g2&id=".$row->id."');toggleBox('box".$row->id."',1);return false;\">$loc_name</a></span>&nbsp;";
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
	mysql_free_result($master_result);

	print "</table>";
	print "</form>";


	//previous, next links
	if ($pagination) {include ("admin-g2-paging.php");}
	print "</div>";

}
?>


</div>

<?php


$time_end = microtime_float();
$time = $time_end - $time_start;

echo "Finished in $time seconds\n";
?>
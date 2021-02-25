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

//Check for default restrictions
if (!isset($_GET['restrict'])) {
	$view_restrictions ="status != 'clear'";
	} else {
	$view_restrictions ="status = '".$_GET['restrict']."'";
}
//Set the view mode restriction meanings
switch($_GET['restrict']) {
	case "answered":
		$view_mode = "Answered";
		$answered_current = TRUE;
		break;
	case "pending":
		$view_mode = "Pending";
		$pending_current = TRUE;
		break;
	case "clear":
		$view_mode = "Cleared";
		$clear_current = TRUE;
		break;
	default:
		$view_mode = "Open";
		$default_current = TRUE;
		break;
}


if($_POST['action']=="report"){
	$default_current = FALSE;
	$report_current = TRUE;
}

//Determine total number of records
$result = mysql_query("select id from repair_orders where $view_restrictions and parent = 0");
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
	
function reporting_mode() {	

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
		if (isset($_POST['chain'])) { echo "<input type=\"hidden\" name=\"chain\" value=\"".$_POST['chain']."\" />"; }
}



if (($usercompany == 4) or ($usergroup == 0) or (in_array("g2",$roles))) {

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


if ($usergroup = 3) {
	if ($embed_mode) {
		echo "<div id=\"embedded_g2\">";
	} else {
		echo "<div id=\"content\">";
 	}
} else {
?>
<div id="content">
<div class="breadcrumbs"><a href="/">Home</a> &raquo; <a href="<?=$_SERVER['PHP_SELF']?>?page=admin">Admin Options</a> &raquo; Repair Request Orders</div>
<h1>:: Current Announcement</h1><a name="announce"></a>
<div class="databox">
<?php  //announcement databox
	dbConnect();
	$sql = "select * from blog where readers='g2'";
	$result = mysql_query($sql);
	if (!result)
		error("A databass error has occured in processing your request.\\n". mysql_error());
	while ($row = mysql_fetch_array($result)) {
		$id = $row["id"];
		$ts = $row["ts"];
			$ts = revertTimestamp($ts);
		$subject = $row["subject"];
		$body= $row["body"];
			$body = stripslashes($body);
			$body = nl2br($body);
		$author = $row["author"];
	}
?>
<h2><?=$subject?></h2>
<p><small>Posted by <?=$author?> on <?=$ts?></small></p>
<p><?=$body?></p>
<?php
if ($usergroup == 0) {echo "[<a href=\"$PHP_SELF?page=edit-announce&readers=g2\">Edit announcement</a>]</p>";} //Admin option

echo "</div>"; //close announcement databox

}//if $usergroup = 3
?>


<table align="center" width="95%">
<tr><td>
<form name="admin_filter" method="post" action="<?php echo "$PHP_SELF?page=admin-g2"; ?>">
<select name="filter">
<option value="">Select Filter</option>
<option value="tracking">Tracking Number</option>
<option value="store_number"<?php if ($unique_user_id == 195){echo " selected";}?>>Store Number</option>
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
	<form name="groupsof" method="post" action="<?php echo "$PHP_SELF?page=admin-g2";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];} ?>">
	<?php
	if (isset($_POST['range'])) { echo "<input type=\"hidden\" name=\"range\" value=\"".$_POST['range']."\" />"; }
	if (isset($_POST['sort'])) { echo "<input type=\"hidden\" name=\"sort\" value=\"".$_POST['sort']."\" />"; }
		if ($reporting_mode) {reporting_mode();}
// 	if ($reporting_mode) {
// 		echo "<input type=\"hidden\" name=\"action\" value=\"report\" />";
// 
// 		if (isset($_POST['priority'])) { 
// 			foreach($_POST['priority'] as $value) {
// 				echo "<input type=\"hidden\" name=\"priority[]\" value=\"".$value."\" />";
// 			}
// 		}
// 		
// 		if (isset($_POST['type'])) { 
// 			foreach($_POST['type'] as $value) {
// 				echo "<input type=\"hidden\" name=\"type[]\" value=\"".$value."\" />";
// 			}
// 		}
// 	
// 		if (isset($_POST['status'])) { 
// 			foreach($_POST['status'] as $value) {
// 				echo "<input type=\"hidden\" name=\"status[]\" value=\"".$value."\" />";
// 			}
// 		}
// 
// 		if (isset($_POST['store_number'])) { echo "<input type=\"hidden\" name=\"store_number\" value=\"".$_POST['store_number']."\" />"; }
// 		if (isset($_POST['store_district'])) { echo "<input type=\"hidden\" name=\"store_district\" value=\"".$_POST['store_district']."\" />"; }
// 		if (isset($_POST['store_region'])) { echo "<input type=\"hidden\" name=\"store_region\" value=\"".$_POST['store_region']."\" />"; }
// 		}
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
if (!$reporting_mode) {
	if ($this_users_usergroup == 3) {
		$cr = mysql_query("select company_name from companies where company_id = $usercompany");
		$vendorname = mysql_result($cr,0,"company_name");
		$sql = "select id, project_id, store_number, store_district, store_region, priority, timestamp, issue_date, type, status from repair_orders where $view_restrictions and parent = 0 $filter_sql";
		$sql .= "and vendor = '$vendorname'";
		$listing_greeting = "$vendorname requests only";
		$row_sql = mysql_query($sql);
		$row_nums = mysql_num_rows($row_sql);
		$listing_header = "$view_mode $listing_range ($row_nums total)";
		$sql .= " order by $sortmethod";
	} elseif ($unique_user_id == "195") {
		$sql = "select id, project_id, store_number, store_district, store_region, priority, timestamp, issue_date, type, status from repair_orders where $view_restrictions and parent = 0 $filter_sql";
		$sql .= "and store_region < 4";
		//$sql .= "and store_region < 6";
		$listing_greeting = "Sarah: Regions 1,2,3 &raquo;";
		//$listing_greeting = "Sarah: Regions 1,2,3,4,5 &raquo;";
		$row_sql = mysql_query($sql);
		$row_nums = mysql_num_rows($row_sql);
		$listing_header = "$view_mode $listing_range ($row_nums total)";
		$sql .= " order by $sortmethod";
	} elseif ($unique_user_id == "314") { //change to 355 for Laresa
		$sql = "select id, project_id, store_number, store_district, store_region, priority, timestamp, issue_date, type, status from repair_orders where $view_restrictions and parent = 0 $filter_sql";
		$sql .= "and store_region = 6";
		$listing_greeting = "Laresa: Regions 6 &raquo;";
		$row_sql = mysql_query($sql);
		$row_nums = mysql_num_rows($row_sql);
		$listing_header = "$view_mode $listing_range ($row_nums total)";
		$sql .= " order by $sortmethod";		
	} else {
		$sql = "select id, project_id, store_number, store_district, store_region, priority, timestamp, issue_date, type, status from repair_orders where $view_restrictions and parent = 0 $filter_sql";
		$sql .= "order by $sortmethod";	
		$listing_greeting = "All Regions &raquo;";		
	}
} else {
	$sql = "select id, project_id, store_number, store_district, store_region, priority, timestamp, issue_date, type, status from repair_orders where (";
	if ($reporting_stno_sql!=""){$sql .= $reporting_stno_sql;
	$sql .= ") AND (";}
	if ($reporting_stdi_sql!=""){$sql .= $reporting_stdi_sql;
	$sql .= ") AND (";}
	if ($reporting_stre_sql!=""){$sql .= $reporting_stre_sql;
	$sql .= ") AND (";}
	if ($reporting_stch_sql!=""){$sql .= $reporting_stch_sql;
	$sql .= ") AND (";}	
	if (isset($_POST['type'])){$sql .= $reporting_type_sql;
	$sql .= ") AND (";}
	if (isset($_POST['status'])){$sql .= $reporting_status_sql;
	$sql .= ") AND (";}
	if (isset($_POST['priority'])){$sql .= $reporting_priority_sql;
	$sql .= ") AND parent = 0";
	} else {
	$sql .= "id>0) AND parent = 0";
	}

	$row_sql = mysql_query($sql);
	$row_nums = mysql_num_rows($row_sql);
	$listing_header = "Report $listing_range ($row_nums total)";

	
	$sql .= " order by $sortmethod";	
	
	}
	$master_result = mysql_query($sql);
	if (!$master_result) 	{
		error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error());
	}
	
	if (mysql_num_rows($master_result) == 0) {
		print "<p>No requests to show!</p>";
		echo $usergroup;
		echo $usercompany;
		echo $username;
	} else {
	print "<div id=\"tabs2\">
	  <ul>
		<li><a href=\"$PHP_SELF?page=admin-g2\"";if($default_current){echo " id=\"current_tab\"";} print " title=\"Default\"><span>Default</span></a></li>
		<li><a href=\"$PHP_SELF?page=admin-g2&restrict=pending\"";if($pending_current){echo " id=\"current_tab\"";} print " title=\"Pending\"><span>Pending</span></a></li>
		<li><a href=\"$PHP_SELF?page=admin-g2&restrict=answered\"";if($answered_current){echo " id=\"current_tab\"";} print " title=\"Answered\"><span>Answered</span></a></li>
		<li><a href=\"$PHP_SELF?page=admin-g2&restrict=clear\"";if($clear_current){echo " id=\"current_tab\"";} print " title=\"Cleared\"><span>Cleared</span></a></li>
		<li><a href=\"#\" onClick=\"toggleBox('reports',1);\"";if($report_current){echo " id=\"current_tab\"";} print " title=\"Report\"><span>Report</span></a></li>	
	  <span style=\"font-size:11px;font-weight:bold;\">$listing_greeting  $listing_header</span>
	  </ul>
	  </div>
	  <div style=\"clear:both;\"></div>";
	
	print "<div class=\"sortbox\" style=\"width:95%;margin: 0 auto;\">";
	if ($reporting_mode) {echo "<p>Your report criteria:</p><ul>".$fr."</ul>";}
	
		include ("admin-g2-reports.php");
	
	
 //previous, next links
	if ($pagination) {
		include ("admin-g2-paging.php");
		}

	print "<form method=\"post\" name=\"actions\">";
	if ($reporting_mode) {print reporting_mode();}
	
	if (isset($_POST['range'])) { echo "<input type=\"hidden\" name=\"range\" value=\"".$_POST['range']."\" />"; }
	print "<input type=\"hidden\" name=\"groupsof\" value=\"".$_POST['groupsof']."\" />";
	if ($usergroup == 3) {
	print "&nbsp;";
	} else {
	print "<input type=\"submit\" class=\"bigshinybutton\" value=\"Close Request\" onClick=\"document.actions.action='$PHP_SELF?page=admin-g2&action=close'\" />";
	print "<input type=\"submit\" class=\"bigshinybutton\" value=\"Trash\" onClick=\"document.actions.action='$PHP_SELF?page=admin-g2&action=trash'\" />";
	print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	}
	print "<input type=\"button\" class=\"bigshinybutton\" value=\"Expand\" onClick=\"expandChecked('data',document.actions.elements['clearbox[]']);return false;\" />";
	print "<input type=\"button\" class=\"bigshinybutton\" value=\"Collapse\" onClick=\"collapseChecked(document.actions.elements['clearbox[]']);return false;\" />";
	//echo start_table($sortrange);

	print "\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr>";
	print "<th><a href=\"#\" onClick=\"check(document.actions.elements['clearbox[]']);return false;\"><small>All</small></a></th>";
	print "<th width=\"22\"><input type=\"submit\" value=\"Store\" onClick=\"document.actions.action='/index.php?page=admin-g2&sort=store_number";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}if (isset($_GET['action'])){echo"&action=".$_GET['action'];}print "'\" /></th>";
	print "<th width=\"22\"><input type=\"submit\" value=\"Dist.\" onClick=\"document.actions.action='/index.php?page=admin-g2&sort=store_district";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}if (isset($_GET['action'])){echo"&action=".$_GET['action'];}print "'\" /></th>";
	print "<th width=\"22\"><input type=\"submit\" value=\"Reg.\" onClick=\"document.actions.action='/index.php?page=admin-g2&sort=store_region";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}if (isset($_GET['action'])){echo"&action=".$_GET['action'];}print "'\" /></th>";
	print "<th><input type=\"submit\" value=\"Location\" onClick=\"document.actions.action='/index.php?page=admin-g2&sort=project_id";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}if (isset($_GET['action'])){echo"&action=".$_GET['action'];}print "'\" /></th>";
	print "<th><input type=\"submit\" value=\"Type\" onClick=\"document.actions.action='/index.php?page=admin-g2&sort=type";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}if (isset($_GET['action'])){echo"&action=".$_GET['action'];}print "'\" /></th>";
	print "<th><input type=\"submit\" value=\"Priority\" onClick=\"document.actions.action='/index.php?page=admin-g2&sort=urgency";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}if (isset($_GET['action'])){echo"&action=".$_GET['action'];}print "'\" /></th>";
	print "<th width=\"105\" align=\"right\"><input type=\"submit\" value=\"Date\" onClick=\"document.actions.action='/index.php?page=admin-g2&sort=issue_date";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}if (isset($_GET['action'])){echo"&action=".$_GET['action'];}print "'\" /></th>";
//print "<th width=\"22\"><small><a href=\"$PHP_SELF?page=admin-g2&sort=store_number";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}print "\">Store</a></small></th>"; 
//	print "<th width=\"22\"><small><a href=\"$PHP_SELF?page=admin-g2&sort=store_district";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}print "\">Dist.</a></small></th>"; 
	//print "<th width=\"22\"><small><a href=\"$PHP_SELF?page=admin-g2&sort=store_region";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}print "\">Reg.</a></small></th>"; 
	//print "<th><small><a href=\"$PHP_SELF?page=admin-g2&sort=project_id";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}print "\">Location</a></small></th>";
	//print "<th><small><a href=\"$PHP_SELF?page=admin-g2&sort=type";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}print "\">Type</a></small></th>";
	//print "<th><small><a href=\"$PHP_SELF?page=admin-g2&sort=urgency";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}print "\">Priority</a></small></th>";
	//print "<th width=\"105\" align=\"right\"><small><a href=\"$PHP_SELF?page=admin-g2&sort=issue_date";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}print "\">Date</a></th>";
	print "</tr>";
	print "\n";
	$count=0;
	
//Cultivate data set
	while ($row = mysql_fetch_object($master_result))
	{
	//Get location details for this request based on project_id
	$loc_res = mysql_query("select sitename from projects where id=".$row->project_id."");
	if(mysql_num_rows($loc_res)>0) {
		$loc_name = mysql_result($loc_res, 0);
		}
	$statusclass=$row->status;
	if ($statusclass == "clear") {
		$statusclass="cleared";
		}
		 
	$flag = "<a href=\"index.php?page=project&id=$row->project_id\" target=\"_blank\" title=\"Project Page\">
			<img src=\"http://construction2.charlotte-russe.com/images/project-page.gif\" border=\"0\" align=\"absmiddle\"></a>&nbsp;<span id=\"flag".$row->id."\" class=\"".$statusclass."\">
			<a href=\"#\" title=\"Expand\" onClick=\"javascript:ajax_do('data.php?id=".$row->id."');toggleBox('box".$row->id."',1);return false;\">$loc_name</a></span>&nbsp;";
	print "<td><input name=\"clearbox[]\" type=\"checkbox\" value=\"".$row->id."\" /></td>";		
	print "<td><small>$row->store_number &nbsp;</small></td>";
	print "<td><small>$row->store_district &nbsp;</small></td>";
	print "<td><small>$row->store_region&nbsp;</small></td>";
	print "<td>$flag</td>";
	print "<td><small>$row->type</a></small></td>";
	print "<td align=\"center\"><small><img src=\"http://construction2.charlotte-russe.com/images/".$row->priority.".gif\" title=\"".$row->priority."\" /></small></td>";
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
} else {
echo "You do not have sufficient privledges to view this page";
exit;

}


$time_end = microtime_float();
$time = $time_end - $time_start;

echo "Finished in $time seconds\n";
?>
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
include ("admin-freq-actions.php");

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
$result = mysql_query("select id from fixture_orders where $view_restrictions and parent = 0");
$total_num_rows = mysql_num_rows($result);


//Determine the current page range
//if a sort range does exist, echo it somehow as a "$sortrange" so that it applies to sort links too

if (!isset($_POST['range'])) {
	$listing_range = "1-$groupsof";
	$listing_header = "$view_mode requests: $listing_range ($total_num_rows total)";
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
	$sortmethod = "store_number, priority limit $range";
	} else {
	$client_sort = $_POST['sort'];
	$sortmethod = $client_sort ." limit $range";
	}	
if (!isset($_POST['sort'])){
	$sortmethod = "store_number, priority limit $range";
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
				echo "<input type=\"hidden\" name=\"followup[]\" value=\"".$value."\" />";
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
		//if (isset($_POST['chain'])) { echo "<input type=\"hidden\" name=\"chain\" value=\"".$_POST['chain']."\" />"; }
}

if (($usercompany == 4) or ($usergroup == 0)) {
if ((isset($_GET['filter'])) && ($_GET['filter'] != "")) {
	$_POST['filter'] = $_GET['filter'];
	$_POST['filter_var'] = $_GET['filter_var'];
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
		case "cartId":
		$filter_message = "Showing fixture order request <strong>#$var</strong>";
		$filter_sql = "and cartId = '$var' ";
		$collapsed=FALSE;
		break;
		case "store_number":
		$filter_message = "Showing fixture order requests for <strong>store #$var</strong>";
		$filter_sql = "and store_number = '$var' ";
		$collapsed=TRUE;
		break;
		case "store_district":
		$filter_message = "Showing fixture order requests for <strong>district #$var</strong>";
		$filter_sql = "and store_district = '$var' ";
		$collapsed=TRUE;
		break;
		case "store_region":
		$filter_message = "Showing fixture order requests for <strong>region #$var</strong>";
		$filter_sql = "and store_region = '$var' ";
		$region_filter=TRUE;
		break;
		}	

} else {
	$pagination=TRUE;
	$filter_message = "No filtering set. Showing all!";
	$filter_sql = "";
}

//Determine the name of the current weekly report
if ($handle = opendir("./filespace/weekly_freq")) {
	while (false !== ($item = readdir($handle))) {
		if ($item != "." && $item != ".."){$current_weekly = $item;}
	 }
	}


?>
<div id="content">
<div class="breadcrumbs"><a href="/">Home</a> &raquo; <a href="<?=$_SERVER['PHP_SELF']?>?page=admin">Admin Options</a> &raquo; Fixture Request Orders</div>


	<p style="float:right;text-align:right;"><img align="absmiddle" src="images/fx_report.gif" /><a href="#weekly" onClick="javascript:toggleBox('weekly_upload',1);">Add weekly report file</a>&nbsp;</p>
	<div id="weekly_upload" class="databox" style="display:none;clear:right;">
	<a name="weekly_upload"></a>
	<p>
	<table align="center" cellspacing="8" cellpadding="8">
	<tr>
	<td><img src="images/avatar_single.gif" /></td>
	<td>
	<h2>::Upload a new weekly report</h2>
	<p>This will replace the current weekly fixture report: <strong><?=$current_weekly?></strong></p>
	<form name="single" method="post" action="<?php echo "$PHP_SELF?page=weekly_freq&mode=single"; ?>" enctype="multipart/form-data" onSubmit="return validateSingle(this);">
	<!--
	 remove these hidden tags and add one for $usercomany
	-->
		<table width="99%" cellspacing="10" class="litezone">
		<tr>
		<td width="100" align="right" valign="top"><small><strong>File:</strong></small></td>
		<td><input class="files" name="userfile[]" size="51" type="file" onKeyPress="return noenter()"></td>
		</tr>
		<tr>
		<td align="right"><img src="images/mail.gif" /></td>
		<td><input name="notify" type="checkbox"><small>Send notification e-mail to DMs</small></input></td>
		</tr>
		<tr>
		<td></td>
		<td>
		<input class="files" type="submit" name="submit" value="upload">&nbsp;
		<input class="files" type="reset" name="reset" value="cancel" onClick="javascript:toggleBox('weekly_upload',0);">
		</td>
		</tr>
		</table>
	</form>

	</td>
	</tr>
	</table><a href="javascript:toggleBox('weekly_upload',0);"><small>(Hide)</small></a>
	</p>
	</div>


<h1>:: Current Announcement</h1><a name="announce"></a>
<div class="databox">
<?php

	dbConnect();
	$sql = "select * from blog where readers='freq'";
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
if ($usergroup == 0) {
echo "[<a href=\"$PHP_SELF?page=edit-announce&readers=freq\">Edit announcement</a>]</p>";
	}
?>
</div>

<table align="center" width="95%">
<tr><td>
<!--<form name="admin_filter" method="post" action="<?php echo "$PHP_SELF?page=admin-freq"; ?>">
<select name="filter">
<option value="">Select Filter</option>
<option value="cartId">Cart ID</option>
<option value="store_number">Store Number</option>
<option value="store_district">Store District</option>
<option value="store_region">Store Region</option>
</select>
<input type="text" name="filter_var" />
<input type="submit" class="bigshinybutton" value="Go!" />
<input type="submit" class="bigshinybutton" value="Reset All" />
</form>
--></td>
<td align="right">


</td></tr></table>

<?php 
//if (!$reporting_mode) {
//
//		$sql = "select id, project_id, store_number, store_district, store_region, priority, timestamp, issue_date, status from fixture_orders where $view_restrictions and parent = 0 $filter_sql";
//		$sql .= "order by $sortmethod";	
//
//} else {
//	$sql = "select id, project_id, store_number, store_district, store_region, priority, timestamp, issue_date, status from fixture_orders where (";
//	if ($reporting_stno_sql!=""){$sql .= $reporting_stno_sql;
//	$sql .= ") AND (";}
//	if ($reporting_stdi_sql!=""){$sql .= $reporting_stdi_sql;
//	$sql .= ") AND (";}
//	if ($reporting_stre_sql!=""){$sql .= $reporting_stre_sql;
//	$sql .= ") AND (";}
//	if ($reporting_stch_sql!=""){$sql .= $reporting_stch_sql;
//	$sql .= ") AND (";}		
//	if (isset($_POST['followup'])){$sql .= $reporting_fu_sql;
//	$sql .= ") AND (";}	
//	if (isset($_POST['status'])){$sql .= $reporting_status_sql;
//	$sql .= ") AND (";}
//	if (isset($_POST['priority'])){$sql .= $reporting_priority_sql;
//	$sql .= ") AND parent = 0";
//	} else {
//	$sql .= "id>0) AND parent = 0";
//	}
//	$row_sql = mysql_query($sql);
//	$row_nums = mysql_num_rows($row_sql);
//	$listing_header = "Report $listing_range ($row_nums total)";
//
//	
//	$sql .= " order by $sortmethod";	
//
//	}
//	$master_result = mysql_query($sql);
//	if (!$master_result) 	{
//		error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error());
//	}
//	
//	if (mysql_num_rows($master_result) == 0) {
//		print "<p>No requests to show!</p>";
//	} else {
//	print "<div id=\"tabs2\">
//	  <ul>
//		<li><a href=\"$PHP_SELF?page=admin-freq\"";if($default_current){echo " id=\"current_tab\"";} print " title=\"Default\"><span>Default</span></a></li>
//		<li><a href=\"$PHP_SELF?page=admin-freq&restrict=pending\"";if($pending_current){echo " id=\"current_tab\"";} print " title=\"Pending\"><span>Pending</span></a></li>
//		<li><a href=\"$PHP_SELF?page=admin-freq&restrict=answered\"";if($answered_current){echo " id=\"current_tab\"";} print " title=\"Answered\"><span>Answered</span></a></li>
//		<li><a href=\"$PHP_SELF?page=admin-freq&restrict=clear\"";if($clear_current){echo " id=\"current_tab\"";} print " title=\"Cleared\"><span>Cleared</span></a></li>
//		<li><a href=\"#\" onClick=\"toggleBox('reports',1);\"";if($report_current){echo " id=\"current_tab\"";} print " title=\"Report\"><span>Report</span></a></li>	
//	  <span style=\"font-size:11px;font-weight:bold;\">$listing_greeting  $listing_header $filter_message</span>
//	  </ul>
//	  </div>
//	  <div style=\"clear:both;\"></div>";
//	
//	print "<div class=\"sortbox\" style=\"width:95%;margin: 0 auto;\">";
//	if ($reporting_mode) {echo "<p>Your report criteria:</p><ul>".$fr."</ul>";}
//	
//	include ("admin-freq-reports.php");
//	
//	
// //previous, next links
//	if ($pagination) {
//		//include ("admin-freq-paging.php");
//		}
//
//	print "<form method=\"post\" name=\"actions\">";
//	if ($reporting_mode) {print reporting_mode();}
//	if (isset($_POST['range'])) { echo "<input type=\"hidden\" name=\"range\" value=\"".$_POST['range']."\" />"; }
//	print "<input type=\"hidden\" name=\"groupsof\" value=\"".$_POST['groupsof']."\" />";
//	print "<input type=\"button\" class=\"bigshinybutton\" value=\"Clear Request\" onClick=\"confirmation('close');\" />";
//	print "<input type=\"button\" class=\"bigshinybutton\" value=\"Trash\" onClick=\"confirmation('trash');\" />";
//	print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
//	print "<input type=\"button\" class=\"bigshinybutton\" value=\"Expand\" onClick=\"expandChecked('fdata',document.actions.elements['clearbox[]']);return false;\" />";
//	print "<input type=\"button\" class=\"bigshinybutton\" value=\"Collapse\" onClick=\"collapseChecked(document.actions.elements['clearbox[]']);return false;\" />";
//	
//	print "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr>";
//	print "<th><a href=\"#\" onClick=\"check(document.actions.elements['clearbox[]']);return false;\"><small>All</small></a></th>"; 
//
//	print "<th width=\"28\">
//	<input type=\"submit\" value=\"Store\" onClick=\"document.actions.action='/index.php?page=admin-freq&sort=store_number";
//	if (isset($_POST['filter'])){echo"&filter=".$_POST['filter'];}
//	if (isset($_POST['filter'])){echo"&filter_var=".$_POST['filter_var'];}
//	if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}
////	if (isset($_GET['action'])){echo"&action=".$_GET['action'];}
//	print "'\" /></th>";
//	
//	print "<th width=\"22\">
//	<input type=\"submit\" value=\"Dist.\" onClick=\"document.actions.action='/index.php?page=admin-freq&sort=store_district";
//	if (isset($_POST['filter'])){echo"&filter=".$_POST['filter'];}
//	if (isset($_POST['filter'])){echo"&filter_var=".$_POST['filter_var'];}
//	if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}
////	if (isset($_GET['action'])){echo"&action=".$_GET['action'];}
//	print "'\" /></th>";
//	
//	print "<th width=\"22\">
//	<input type=\"submit\" value=\"Reg.\" onClick=\"document.actions.action='/index.php?page=admin-freq&sort=store_region";
//	if (isset($_POST['filter'])){echo"&filter=".$_POST['filter'];}
//	if (isset($_POST['filter'])){echo"&filter_var=".$_POST['filter_var'];}
//	if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}
////	if (isset($_GET['action'])){echo"&action=".$_GET['action'];}
//	print "'\" /></th>";
//
//	print "<th>
//	<input type=\"submit\" value=\"Location\" onClick=\"document.actions.action='/index.php?page=admin-freq&sort=project_id";
//	if (isset($_POST['filter'])){echo"&filter=".$_POST['filter'];}
//	if (isset($_POST['filter'])){echo"&filter_var=".$_POST['filter_var'];}
//	if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}
////	if (isset($_GET['action'])){echo"&action=".$_GET['action'];}
//	print "'\" /></th>";
//	
//	print "<th>
//	<input type=\"submit\" value=\"Priority\" onClick=\"document.actions.action='/index.php?page=admin-freq&sort=priority";
//	if (isset($_POST['filter'])){echo"&filter=".$_POST['filter'];}
//	if (isset($_POST['filter'])){echo"&filter_var=".$_POST['filter_var'];}
//	if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}
////	if (isset($_GET['action'])){echo"&action=".$_GET['action'];}
//	print "'\" /></th>";
//
//	
//	print "<th width=\"105\" align=\"right\">
//	<input type=\"submit\" value=\"Date\" onClick=\"document.actions.action='/index.php?page=admin-freq&sort=issue_date";
//	if (isset($_POST['filter'])){echo"&filter=".$_POST['filter'];}
//	if (isset($_POST['filter'])){echo"&filter_var=".$_POST['filter_var'];}
//	if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];}
////	if (isset($_GET['action'])){echo"&action=".$_GET['action'];}
//	print "'\" /></th>";
//	print "</tr>";
//
//
//	$count=0;
//	
////Cultivate data set
//	while ($row = mysql_fetch_object($master_result))
//	{
//	//Get location details for this request based on project_id
//	$loc_res = mysql_query("select sitename from projects where id=".$row->project_id."");
//	$loc_name = mysql_result($loc_res, 0);
//	$statusclass=$row->status;
//	if ($statusclass == "clear") {
//		$statusclass="cleared";
//		}
//		 
//	$flag = "<a href=\"index.php?page=project&id=$row->project_id\" target=\"_blank\" title=\"Project Page\">
//			<img src=\"http://construction2.charlotte-russe.com/images/project-page.gif\" border=\"0\" align=\"absmiddle\"></a>&nbsp;<span id=\"flag".$row->id."\" class=\"".$statusclass."\">
//			<a href=\"#\" title=\"Expand\" onClick=\"javascript:ajax_do('fdata.php?id=".$row->id."');toggleBox('box".$row->id."',1);return false;\">$loc_name</a></span>&nbsp;";
//	print "<td><input name=\"clearbox[]\" type=\"checkbox\" value=\"".$row->id."\" /></td>";		
//	print "<td><small>$row->store_number &nbsp;</small></td>";
//	print "<td><small>$row->store_district &nbsp;</small></td>";
//	print "<td><small>$row->store_region&nbsp;</small></td>";
//	print "<td>$flag</td>";
//	print "<td align=\"center\"><small><img src=\"http://construction2.charlotte-russe.com/images/".$row->priority.".gif\" title=\"".$row->priority."\" /></small></td>";
//	print "<td align=\"right\"><small>$row->issue_date&nbsp;</small></td>";
//	print "</tr>";
//	print "<tr class=\"noline\"><td></td><td></td><td></td><td></td><td colspan=\"4\">";
//		print "<div id=\"box".$row->id."\" style=\"display:none;margin-left:17px;\" class=\"bigshinybutton\"></div>";
//	print "</td></tr>";
//	
//	}
//	mysql_free_result($master_result);
//
//	print "</table>";
//	print "</form>";
//
//
//	//previous, next links
//	if ($pagination) {
//	//include ("admin-freq-paging.php");
//	}
//	print "</div>";
//}
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
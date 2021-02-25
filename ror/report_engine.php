<?php
error_reporting(E_ALL ^ E_NOTICE);
require("SLLists.class.php");
include("../include/access.php");
include("../include/rt.php");
$t = Types();
$u = Urgencies();
$fr ="";
//Build out individual criteria sets  for status, urgency, and type
$r1_sql = ""; //Status (both)
$r2_sql = ""; //Urgency (both)
$r3_sql = ""; //Type (both)
$r4_sql = ""; //Request Type (freq)
$r5_sql = ""; //Item Number (freq)
$r6_sql = ""; //Order Status (Freq)
$sql_criteria = "";
$loc_r_sql = "";
$loc_d_sql = "";
$loc_s_sql = "";
$state_filter = "";

	//STATUS criteria=============================================
	if (isset($_POST['status'])) {
		foreach($_POST['status'] as $value) {
			if ($r1_sql != ""){$r1_sql .= " or ";}
			$r1_sql .= "status='".$value."'";

			$fr .= "<li>Status: <strong>".$value."</strong></li>";
		}
	} elseif(($_POST['report']=="quick") or (isset($filter))) {		//Quick Report ==============================================
		$r1_sql = "status='new' or status='open'";
		$fr .= "<li>Status: <strong>Quick Report</strong> (Open and New requests)</li>";
	} else {
		$r1_sql .= "";
		$fr .= "<li>Status: <strong>all</strong></li>";
	}
	
	


	
	//URGENCY criteria=============================================
	if (isset($_POST['urgency'])) {
		foreach($_POST['urgency'] as $value) {
			if ($r2_sql != ""){$r2_sql .= " or ";}
			$r2_sql .= "urgency='".$value."'";
			$fr .= "<li>Urgency: <strong>".$u[$value][0]."</strong></li>";
		}
	} elseif ((isset($_GET['filter'])) and ($_GET['filter']=="u")) { //Check for Dashboard Filter
			$r2_sql .= "urgency='".$_GET['crit']."'";
			$fr .= "<li>Urgency: <strong>".$u[$_GET['crit']][0]."</strong></li>";
	} else {														//Default 
		$r2_sql .= "";
		$fr .= "<li>Urgency: <strong>all</strong></li>";
	}
	
	
	
	
	
	//TYPE criteria=============================================
	if (isset($_POST['type'])) {
		foreach($_POST['type'] as $value) {
			if ($r3_sql != ""){$r3_sql .= " or ";}
			$r3_sql .= "type='".$value."'";
			$fr .= "<li>Type: <strong>".$t[$value][0]."</strong></li>";
		}
		
	} elseif ((isset($_GET['filter'])) and ($_GET['filter']=="t")) { //Check for Dashboard Filter
			$r3_sql .= "type='".$_GET['crit']."'";
			$fr .= "<li>Type: <strong>".$_GET['crit']."</strong></li>";
		
	} else {
		$r3_sql .= "";
		$fr .= "<li>Type: <strong>all</strong></li>";
	}

	//String together sql criterias
	if ($r1_sql != "") {										//Status
		$sql_criteria .= "(".$r1_sql.")";
	}
	if ($r2_sql != "") {										//Urgency
		if ($sql_criteria != "") {$sql_criteria .= " AND ";}
		$sql_criteria .= "(".$r2_sql.")";
	}
	if ($r3_sql != "") {										//Type
		if ($sql_criteria != "") {$sql_criteria .= " AND ";}		
		$sql_criteria .= "(".$r3_sql.")";
	}


	//Creat days string query====================================================
	$days_sql = "";
	if ($_POST['date_option'] > 0) {
		$days_sql .= " creation >= DATE_ADD(now(), INTERVAL -".$_POST['date_option']." DAY)";
		$fr .= "<li>Within the last <strong>".$_POST['date_option']." days</strong></li>";
	} elseif ($_POST['date_option'] == -1) {
		$from_date = $_POST['date_from_year']."-".$_POST['date_from_month']."-".$_POST['date_from_day']." 00:00:01";
 		$to_date = $_POST['date_to_year']."-".$_POST['date_to_month']."-".$_POST['date_to_day']." 23:59:59";
		$days_sql .= " creation >= '".$from_date."' AND creation <='".$to_date."' ";
		$fr .= "<li>From: <strong>".$from_date."</strong> To: <strong>".$from_date."</strong></li>";
	}


	//Creat vendor string query====================================================
	$vendor_sql = "";
	if ($_POST['vendor_option'] == "none") {
		$vendor_sql .= " vendor_key = 0 ";
		$fr .= "<li>Vendor: <strong>None</strong></li>";
	} elseif ($_POST['vendor_option'] == "select") {
		$vendor_sql.= " vendor_key = ".$_POST['vendor_select'];
		$fr .= "<li>Vendor: <strong>".$_POST['vendor_select']."</strong></li>";
	}


	
	//Creat response count string (has_children)=========================================
	$responses_sql = "";
	if ($_POST['responses_option'] == "none") {
		$responses_sql .= " has_children = 0 ";
		$fr .= "<li>Responses: <strong>None</strong></li>";
	} elseif ($_POST['responses_option'] == "select") {
		$responses_sql.= " has_children >= ".$_POST['responses_select'];
		$fr .= "<li>Responses: <strong>at least".$_POST['responses_select']."</strong></li>";
	}

	//STATE FILTER criteria ====================================================
	if ($_POST['store_state'] != "") {
		$state_filter_sql .= "projects.sitestate='".$_POST['store_state']."' ";
		$fr .= "<li>State: <strong>".$_POST['store_state']."</strong></li>";

	}

		
	//STORE REGION criteria ====================================================
	if ($_POST['region_option'] == "select") {
		if ($_POST['region_select'] == "all") {		
			$fr .= "<li>Region #: <strong>All</strong></li>";
		} else {
			$loc_r_sql .= " projects.store_region=".$_POST['region_select']." ";
			$fr .= "<li>Region #: <strong>".$_POST['region_select']."</strong></li>";
		}
	}

	if ($_POST['filter'] == "store_region") {
		if ($loc_r_sql != "") {$loc_r_sql .= " AND ";}
		$loc_r_sql .= "projects.store_region=".$_POST['query']." ";
		$fr .= "<li>Region #: <strong>".$_POST['query']."</strong></li>";
	}

	if ($_POST['region_option'] == "specify") {
		if ($_POST['region_specify'] != "") {
			$trimmed = ltrim($_POST['region_specify']);
			$trimmed = rtrim($trimmed);
			$trimmed = str_replace(' ', '', $trimmed);
			$regions = explode(",",$trimmed);
			foreach($regions as $region) {
				if ($loc_r_sql != "") {$loc_r_sql .= " OR ";}else{$loc_r_sql .= "(";} 
				$loc_r_sql .= "projects.store_region=".$region." ";
				$fr .= "<li>Region #: <strong>".$region."</strong></li>";
			}
			$loc_r_sql .= ")";
		}
	}

	//STORE DISTRICT criteria ====================================================
	if ($usergroup == 2) {
		$user_uid_rank = substr($uid,0,2);
		$user_domain = substr($uid,2);
		if ($user_uid_rank == "dm") {
			$domain_value="District";
			$crit_string = "store_district";
			$loc_d_sql .= "projects.store_district=$user_domain";	
		} elseif ($user_uid_rank == "rm") {
			$domain_value="Region";
			$crit_string="store_region";
			$loc_r_sql = "projects.store_region=$user_domain ";	
		}
	
	
	} else {
		
		if ($_POST['district_option'] == "select") {
			if ($_POST['district_select'] == "all") {		
				$fr .= "<li>District #: <strong>All</strong></li>";
			} else {
				if ($loc_d_sql != "") {$loc_d_sql .= " AND ";}
				$loc_d_sql .= "projects.store_district=".$_POST['district_select']." ";
				$fr .= "<li>District #: <strong>".$_POST['district_select']."</strong></li>";
			}

		}
		if ($_POST['filter'] == "store_district") {
			if ($loc_d_sql != "") {$loc_d_sql .= " AND ";}
			$loc_d_sql .= "projects.store_district=".$_POST['query']." ";
			$fr .= "<li>District #: <strong>".$_POST['query']."</strong></li>";
		}
		if ($_POST['district_option'] == "specify") {
			if ($_POST['district_specify'] != "") {
				$trimmed = ltrim($_POST['district_specify']);
				$trimmed = rtrim($trimmed);
				$trimmed = str_replace(' ', '', $trimmed);
				$districts = explode(",",$trimmed);
				foreach($districts as $district) {
					if ($loc_d_sql != "") {$loc_d_sql .= " OR ";}else{$loc_d_sql .= "(";} 
					$loc_d_sql .= "projects.store_district=".$district." ";
					$fr .= "<li>District #: <strong>".$district."</strong></li>";
				}
				$loc_d_sql .= ")";
			}
		}
	}
	//STORE NUMBER criteria ====================================================
	if ($_POST['store_option'] == "select") {
		if ($_POST['store_select'] == "all") {		
			$fr .= "<li>Store #: <strong>All</strong></li>";
		} else {
			if ($loc_s_sql != "") {$loc_s_sql .= " AND ";}
			$loc_s_sql .= " projects.store_number=".$_POST['store_select']." ";
			$fr .= "<li>Store #: <strong>".$_POST['store_select']."</strong></li>";
		}
	}

	if ($_POST['filter'] == "store_number") {
		if ($loc_s_sql != "") {$loc_s_sql .= " AND ";}
		$loc_s_sql .= "projects.store_number=".$_POST['query']." ";
		$fr .= "<li>Number #: <strong>".$_POST['query']."</strong></li>";
	}	

	if ($_POST['store_option'] == "specify") {
		if ($_POST['store_specify'] != "") {
			$trimmed = ltrim($_POST['store_specify']);
			$trimmed = rtrim($trimmed);
			$trimmed = str_replace(' ', '', $trimmed);
			$numbers = explode(",",$trimmed);
			foreach($numbers as $number) {
				if ($loc_s_sql != "") {$loc_s_sql .= " OR ";}else{$loc_s_sql .= "(";} 
				$loc_s_sql .= "projects.store_number=".$number." ";
				$fr .= "<li>Store #: <strong>".$number."</strong></li>";
			}
			$loc_s_sql .= ")";
		}
	}
	if ($_POST['store_option'] == "range") {
		if ($_POST['store_range'] != "") {
			$trimmed = ltrim($_POST['store_range']);
			$trimmed = rtrim($trimmed);
			$trimmed = str_replace(' ', '', $trimmed);
			$numbers = explode("-",$trimmed);
			if (!isset($numbers[1])){$numbers[1]=999;}
			$loc_s_sql .= "(projects.store_number >= ".$numbers[0]." AND projects.store_number <= ".$numbers[1].")";
			$fr .= "<li>Store Range: <strong>".$numbers[0]."-".$numbers[1]."</strong></li>";

		}
	}
	
	//Concatenate loc sqls
	$loc_sql = "";
	if ($loc_r_sql != "") {
		$loc_sql .= $loc_r_sql;
	}
	if ($loc_d_sql != "") {
		if ($loc_sql != "") {$loc_sql .= " AND ";}
		$loc_sql .= $loc_d_sql;
	}
	if ($loc_s_sql != "") {
		if ($loc_sql != "") {$loc_sql .= " AND ";}
		$loc_sql .= $loc_s_sql;
	}

	if ($state_filter_sql != "") {
		if ($loc_sql != "") {$loc_sql .= " AND ";}
		$loc_sql .= $state_filter_sql;
	}

	//ORDER BY criteria ====================================================
	if (isset($_POST['sort_by1'])){
		$order_by_sql = " ORDER BY ".$_POST['sort_by1']." ".$_POST['sort_dir1']." ";
		$fr .= "<li>Order By1: <strong>".$_POST['sort_by1']." ".$_POST['sort_dir1']."</strong></li>";
	} else {
		$order_by_sql = " ORDER BY creation desc ";
		$fr .= "<li>Order By 1: <strong>Request Date</strong></li>";
	}


	if (isset($_POST['sort_by2'])){
		$order_by_sql .= ", ".$_POST['sort_by2']." ".$_POST['sort_dir2']." ";
		$fr .= "<li>Order By 2: <strong>".$_POST['sort_by2']." ".$_POST['sort_dir2']."</strong></li>";
	} else {
		$order_by_sql .= ", status asc";
		$fr .= "<li>Order By 2: <strong>Request Date</strong></li>";
	}

	//Build out final SQL string================================================
	if ($_POST['preset_option'] != "") {
		$sql = "SELECT distinct rt_rors.id as rid, projects.*, rt_rors.body as real_body, rt_rors.*, rt_ror_responses.summary,rt_ror_responses.parent_key, UNIX_TIMESTAMP(rt_rors.creation) AS FORMATED_TIME FROM projects,rt_rors,rt_ror_responses WHERE rt_rors.loc_key = projects.id AND rt_ror_responses.summary = '".$_POST['preset_option']."' AND rt_ror_responses.parent_key = rt_rors.id";
		$fr .= "<li>Drop-down Response: <strong>'".$_POST['preset_option']."'</strong></li>";
	} else {
		$sql = "SELECT projects.*, rt_rors.body as real_body, rt_rors.*,rt_rors.id as rid, UNIX_TIMESTAMP(rt_rors.creation) AS FORMATED_TIME FROM projects,rt_rors WHERE rt_rors.loc_key = projects.id ";
	}

	if ($sql_criteria != "") {$sql .= " AND ".$sql_criteria;}	
	if ($loc_sql != "") {$sql .= " AND ".$loc_sql;}
	if ($responses_sql != "") {$sql .= " AND ".$responses_sql;}
	if ($vendor_sql != "") {$sql .= " AND ".$vendor_sql;}
	if ($days_sql != "") {$sql .= " AND ".$days_sql;}

	$sql .= $order_by_sql;

	if($_POST['output'] == "xls") {
		$result = @mysql_query($sql)
			or die("Couldn't execute query:<br>" . mysql_error(). "<br>" . mysql_errno());
		
		$file_type = "vnd.ms-excel";
		$file_ending = "xls";

		//header info for ber: determines file type ('.doc' or '.xls')
		header("Content-Type: application/$file_type");
		header("Content-Disposition: attachment; filename=database_dump.$file_ending");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		$sep = "\t"; //tabbed character

		if (isset($_POST['colkeys'])) {$orderArray = SLLists::getOrderArray($_POST['colkeys'],'column');}
		else {$orderArray = Array("1","2","3","4","5","6","7","8","9");}

			foreach($orderArray as $item) {
				switch($item[element]) {
				case "1":$schema_insert .= "Status".$sep;break;
				case "2":$schema_insert .= "Type".$sep;break;
				case "3":$schema_insert .= "Urgency".$sep;break;
				case "4":$schema_insert .= "Location".$sep;break;
				case "5":$schema_insert .= "City".$sep;break;
				case "6":$schema_insert .= "State".$sep;break;
				case "7":$schema_insert .= "District".$sep;break;
				case "8":$schema_insert .= "Store #".$sep;break;
				case "9":$schema_insert .= "Request Date".$sep;break;
				}
			}
			
			$schema_insert .= "Request".$sep;
			$schema_insert = str_replace($sep."$", "", $schema_insert);
			$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
			$schema_insert .= "\t";
			print(trim($schema_insert));
			print "\n";			

		while($row = mysql_fetch_object($result)){
			$schema_insert = "";
			foreach($orderArray as $item) {
				switch($item[element]) {
				case "1":$schema_insert .= "$row->status ".$sep;break;
				case "2":$schema_insert .= $t[$row->type][0]." ".$sep;break;
				case "3":$schema_insert .= $u[$row->urgency][0]." ".$sep;break;
				case "4":$schema_insert .= myTruncate($row->sitename,20, " ")." ".$sep;break;
				case "5":$schema_insert .= "$row->sitecity ".$sep;break;
				case "6":$schema_insert .= "$row->sitestate ".$sep;break;
				case "7":$schema_insert .= intval($row->store_district)." ".$sep;break;
				case "8":$schema_insert .= intval($row->store_number)." ".$sep;break;
				case "9":$schema_insert .= date("n/j/y g:ia",$row->FORMATED_TIME)." ".$sep;break;
				}
			}
			$schema_insert .= "$row->real_body".$sep;
			$schema_insert = str_replace($sep."$", "", $schema_insert);
			$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
			$schema_insert .= "\t";
			print(trim($schema_insert));
			print "\n";
		}

	} else {
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>RT List</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" media="screen">@import "rt.css";</style>
<style type="text/css" media="print">@import "print.css";</style>
<script type="text/javascript" src="rt.js"></script>
<script type="text/javascript">
	var rts=new Array();

	window.onload = function() {
	document.getElementById('lhome').className='link';
	document.getElementById('lnew').className='link';
	document.getElementById('lopen').className='link';
	document.getElementById('lcompleted').className='link';
	document.getElementById('lreport').className='link';
	document.getElementById('lcreate').className='link';
	document.getElementById('lreport').className='current';
	document.getElementById('progress').style.visibility = "hidden";
}
</script>
</head>
<body>
<a name="top"></a>
<div id="progress" style="visibility: visible;">Loading...</div>
<div id="menu"><?php include("ror-menu.php");?></div>


<h1>Custom Report</h1>
<div id="maincontainer">
<?php
	$workset = array();							//workset array

	//Determine columns to show ========================================================	
	if (isset($_POST['colkeys'])) {
		$orderArray = SLLists::getOrderArray($_POST['colkeys'],'column');
	} else {
		$orderArray = Array("1","2","3","4","5","6","7","8","9");
	}

	//Actually run the SQL ========================================================
	$result = mysql_query($sql);
	$rows = mysql_num_rows($result);

	//Show Quick Report to Charlotte-Russe Home Office
	if ($usergroup <2){ ?>
		<div id="quickreport">
		<form action="report_engine.php" name="quickreport" method="POST">
		<input type="hidden" name="report" value="quick" />
		<table align="right" cellpadding="4" cellspacing="0" border="0">
		<tr>
			<td class="gray">
				<select name="filter">
					<option value="store_number">Store:</option>
					<option value="store_district">District:</option>
					<option value="store_region">Region:</option>
				</select>
			</td>
			<td class="gray">#<input type="text" size="4" name="query" /><br /></td>
			</tr><tr>
			<td></td>
			<td class="gray"><small>Enter a number and submit</small></td>
			</tr><tr>
			<td></td>
			<td><input type="submit" value="submit" class="macrobutton" /></td>
			</tr></table>
		</form>	
		</div>
	<?php } 
	
	echo "<div class=\"summary\">"
	."<ul class=\"summary\">"
	.$fr
	."<li>".$rows." row(s) were returned</li>"
	."<li><a href=\"javascript:window.print();\"><strong>Print Report</strong></a></li>"
	."</ul>"
	."</blockquote>"
	."<table id=\"output\" cellspacing=\"0\" cellpadding=\"2\">"
	."<tr>"
	."<th></th>";
	foreach($orderArray as $item) {
		switch($item[element]) {
		case "1":echo "<th class=\"summary\">Status";break;
		case "2":echo "<th>Type</th>";break;
		case "3":echo "<th>Urgency</th>";break;
		case "4":echo "<th>Location</th>";break;
		case "5":echo "<th>City</th>";break;
		case "6":echo "<th>State</th>";break;
		case "7":echo "<th>District</th>";break;
		case "8":echo "<th>Store #</th>";break;
		case "9":echo "<th>Request Date</th>";break;
		}
	}
	echo "</tr>";	

	
	echo "<form name=\"workset\" id=\"workset\" method=\"POST\" action=\"view.php?workset=1\" target=\"view\">";
		//eventually place keys to column prefernces
		while($row = mysql_fetch_object($result)) {
			//add the id to a work set array
			$workset[]=$row->id;
			echo "<input type=\"hidden\" name=\"workset[]\" value=\"$row->id\" />";
			$creation = "$row->FORMATED_TIME"; 
			echo "<tr class=\"header\">";
			echo "<td class=\"summary\"><nobr><a class=\"items\" onclick=\"ViewPop('view.php?mode=$mode&report_window=1&id=$row->rid','view$row->rid');return false;\" href=\"#\">View</a>&nbsp;";
			echo "<a href=\"javascript:workit(".$row->id.",'workset');\"><img align=\"absmiddle\"  border=\"0\" src=\"images/start.gif\" /><a/></nobr>";
			echo "</td>";
			foreach($orderArray as $item) {
				switch($item[element]) {
				case "1":echo "<td>$row->status</td>";break;
				case "2":echo "<td>".$t[$row->type][0]."</td>";break;
				case "3":echo "<td>".$u[$row->urgency][0]."</td>";break;
				case "4":echo "<td><nobr>".myTruncate($row->sitename,20, " ")."</nobr></td>";break;
				case "5":echo "<td>$row->sitecity</td>";break;
				case "6":echo "<td>$row->sitestate</td>";break;
				case "7":echo "<td>".intval($row->store_district)."</td>";break;
				case "8":echo "<td>".intval($row->store_number)."</td>";break;
				case "9":echo "<td><nobr>".date("n/j/y g:ia",$creation)."</nobr></td>";break;
				}
			}
			echo "</tr>";
			echo "<tr>";
			echo "<td colspan=\"10\" class=\"realbody\"><p>$row->real_body</p></td>";
			echo "</tr>";
		}
	echo "</table>";
?>
<input type="hidden" name="starter" value="<?=$workset[0]?>" />
</form>
</div>
</body>
</html>
<?php } ?>
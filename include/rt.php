<?php
putenv("TZ=America/Los_Angeles");

$season = mysql_do("select season from fixture_fiscal where id = 1");
$year_label = mysql_do("select year from fixture_fiscal where id = 1");
$month_label = mysql_do("select month from fixture_fiscal where id = 1"); 
$h1_season = $month_label.' '.$year_label;
$season_nice = '<span style="color:red;font-weight:bold;">'.$month_label.' '.$year_label.'</span>';

//Translation Tables
//I'm sure there are better ways to do this (like in sql) but I didnt want to bother with
//yet another relational table for this soI'm using the methods below to have a single
//place where types and urgency meanings can be managed and added to. 

function Urgencies() {
	$u = array(
		10=>array("Hazard","u_hazard"),
		20=>array("Urgent","u_high"),
		30=>array("Not Urgent","u_normal"),
		50=>array("Minor","u_minor")
		);
	return $u;
	}


//Examples  of the old way of doing it
//<input name="typeall" type="checkbox" value="" id="typeall" onClick="toggleChecks('type')" checked /><label for="typeall">All types</label><br />
//<input name="type[]" type="checkbox" value="Other" id="typeoth" disabled /><label for="typeoth">Other</label><br />	
//Examples for how new way could be done
//$t = TranslateType();
//foreach ($t as $type) {
//	echo "<input name=\"type[]\" type=\"checkbox\" value=\"$type[1]\" disabled /><lable for=\"$type[1]\">$type[0]></label><br />";
//}
function Types() {
	$t = array(
		1=>array("Cashwrap","t_cashwrap"),
		2=>array("Ceiling Tiles","t_ceiling_tiles"),
		3=>array("Detex","t_detex"),
		4=>array("Door(excluding locks)","t_door"),
		5=>array("Fire Violation/Extinguishers","t_fire"),			
		6=>array("Flooring","t_flooring"),
		7=>array("Gate","t_gate"),
		8=>array("HVAC","t_hvac"),
		9=>array("Leak","t_leak"),
		10=>array("Lighting/Electrical","t_lighting"),
		11=>array("Locks","t_locks"),
		12=>array("Muzak/Sound","t_muzaksound"),
		13=>array("Other","t_other"),
		14=>array("Plumbing","t_plumbing"),
		15=>array("Pest Control","t_pest_control"),
		16=>array("Safe","t_safe"),
		17=>array("Storefront Sign","t_storefront"),
		18=>array("Walls/Paint","t_walls_paint"),
		);
	return $t;
	}
function Fixtures($cat,$all=FALSE) {
	global $dbcnx;
	if (isset($cat)) { //filtered by a category
    if($all) {
      $result=mysqli_query($dbcnx, "select * from fixture_key where chain=1 and cat = '$cat' order by name");
    } else {
      $result=mysqli_query($dbcnx, "select * from fixture_key where chain=1 and exclude=0 and cat = '$cat' order by name");
    }
  
  } else { //show all then
	$result=mysqli_query($dbcnx, "select * from fixture_key where chain=1 and exclude=0 order by cat");
	}
	
	return $result;
}

// Original PHP code by Chirp Internet: www.chirp.com.au 
 // Please acknowledge use of this code by including this header. 
 function myTruncate($string, $limit, $break=".", $pad="...") { 
	 // return with no change if string is shorter than $limit  
	 if(strlen($string) <= $limit) return $string; 
	 // is $break present between $limit and the end of the string?  
	 if(false !== ($breakpoint = strpos($string, $break, $limit))) { 
	 	if($breakpoint < strlen($string) - 1) { 
			$string = substr($string, 0, $breakpoint) . $pad;
			}
		}
	return $string;
}


function dashboardFWatchList() {
	global $dbcnx;
	global $uid;
	global $usergroup;
	//Load types and urgencies
	$t =Types();
	$u = Urgencies();
	
	if ($usergroup == 2) {
		$user_uid_rank = substr($uid,0,2);
		$user_domain = substr($uid,2);
		if ($user_uid_rank == "dm") {
			$domain_value="District";
			$db_string = "store_district";
		} elseif ($user_uid_rank == "rm") {
			$domain_value="Region";
			$db_string="store_region";
		}
		$sql = "SELECT rt_freqs.*,fixture_key.id AS fixture_id, fixture_key.name, fixture_key.desc, 
		projects.sitename,projects.sitecity,projects.sitestate,projects.store_number,
		projects.store_district,projects.store_region,projects.high_volume_store,UNIX_TIMESTAMP(rt_freqs.creation) AS FORMATED_TIME,UNIX_TIMESTAMP(rt_freqs.ts) AS FORMATED_TS 
		FROM rt_freqs, projects, fixture_key
		WHERE rt_freqs.loc_key = projects.id 
		AND fixture_key.id = rt_freqs.fixture_key 
		AND projects.".$db_string." = $user_domain 
		AND rt_freqs.watchlist LIKE '%$uid%' ORDER BY rt_freqs.creation asc ";

	} else {

		$sql = "SELECT rt_freqs.*,fixture_key.id AS fixture_id, fixture_key.name, fixture_key.desc, 
		projects.sitename,projects.sitecity,projects.sitestate,projects.store_number,
		projects.store_district,projects.store_region,projects.high_volume_store,UNIX_TIMESTAMP(rt_freqs.creation) AS FORMATED_TIME,UNIX_TIMESTAMP(rt_freqs.ts) AS FORMATED_TS 
		FROM rt_freqs, projects, fixture_key
		WHERE rt_freqs.loc_key = projects.id 
		AND fixture_key.id = rt_freqs.fixture_key 
		AND rt_freqs.watchlist LIKE '%$uid%' ORDER BY rt_freqs.creation asc ";
	}
	$result = mysqli_query($dbcnx, $sql);

	if (!$result) {error("Error with database: ".mysqli_error($dbcnx));}

	//Create columns
	echo "<table width=\"100%\" id=\"datarows\" class=\"sortable\" cellspacing=\"0\" cellpadding=\"0\">";
	echo "<tr style=\"text-align:left;font-size:11px;\">";
	echo "<th align=\"center\">*</th>";
	echo "<th>Location</th>";
	echo "<th>Request</th>";
	echo "<th>S</th>";
	echo "<th>D</th>";
	echo "<th>R</th>";
	echo "<th>Status</th>";
	echo "<th>Priority</th>";
	echo "<th>Activity</th>";
	echo "</tr></thead>";
	
	//Start iterating through rows
	while ($row = mysqli_fetch_object($result)) {
		//Get response count	
		$sqly = "select id from rt_ror_responses where parent_key = ".$row->id."";
		$resulty = mysqli_query($dbcnx, $sqly);
		if (!$resulty) {error("Error with database: ".mysqli_error($dbcnx));exit;}
		$count = mysqli_num_rows($resulty);
		if ($count > 0) {$count="<span class=\"msgcount\">$count</span>";}else {$count="";}

		$creation = "$row->FORMATED_TIME"; 
		$ts = "$row->FORMATED_TS"; 
		$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$m = date("m",$ts);
		$d = date("d",$ts);
		$y = date("Y",$ts);
		$date_entered_time = mktime(0,0,0,$m,$d,$y);
		$elapsed = ($today - $date_entered_time) / 86400;
		$elapsed = intval($elapsed);
		echo "<input type=\"hidden\" name=\"workset[]\" value=\"$row->id\" />";
		echo "<tr class=\"alerts\">";
		echo "<td class=\"star_store\">";
		if ($row->high_volume_store == 1) {echo "<span style=\"display:none;\">1</span> <img src=\"images/star.gif\" />";}
		echo "</td>";
		echo "<td> <a href=\"remwl.php?mode=FREQ&id=$row->id\"><img align=\"absmiddle\" src=\"images/trash.gif\" /></a> ";		
		echo "<a href=\"#\" onClick=\"ViewPop('view.php?mode=FREQ&id=$row->id','view');return false;\">".myTruncate($row->sitename,30, " ")."</a> $count";
		echo "<a href=\"javascript:workit(".$row->id.",'$status');\"><img align=\"absmiddle\"  border=\"0\" src=\"images/start.gif\" /><a/>";
		echo "</td>";
		echo "<td><p><strong>$row->name</strong> ".ucwords(strtolower(myTruncate($row->desc,20," ")))." [Qty:".$row->qty."]</p></td>";
		echo "<td class=\"small\"><p>$row->store_number&nbsp;</p></td>";
		echo "<td class=\"small\"><p>$row->store_district&nbsp;</p></td>";
		echo "<td class=\"small\"><p>$row->store_region&nbsp;</p></td>";
		echo "<td class=\"small\"><p>".ucwords($row->order_status)."</p></td>";
		echo "<td class=\"small\"><p><img align=\"absmiddle\"  src=\"images/".$u[$row->urgency][1].".gif\" />".$u[$row->urgency][0]."&nbsp;</p></td>";
		echo "<td class=\"small\"><p>$elapsed days since activity</p></td>";	
		echo "</tr>";
	}
	echo "</table>";
}

function dashboardRTable($status,$dm_response) {
	global $dbcnx;
	global $uid;
	global $usergroup;
	global $usercompany;

	//Load types and urgencies
	$t =Types();
	$u = Urgencies();

	if ($status == "open") {
		$expand = "View all open requests";
		$style = "norm";
	} elseif ($status == "new"){
		$expand = "View all new requests";
		$style = "bold";
	}

	if ($usergroup == 2) {
		$user_uid_rank = substr($uid,0,2);
		$user_domain = substr($uid,2);
		if ($user_uid_rank == "dm") {
			$domain_value="District";
			$db_string = "store_district";
		} elseif ($user_uid_rank == "rm") {
			$domain_value="Region";
			$db_string="store_region";
		}	
		$sql = "SELECT rt_rors.loc_key,rt_rors.body,rt_rors.status,rt_rors.id,rt_rors.type,rt_rors.urgency,
		projects.sitename,projects.sitecity,projects.sitestate,projects.store_number,
		projects.store_district,projects.store_region,projects.high_volume_store,UNIX_TIMESTAMP(rt_rors.creation) AS FORMATED_TIME  
		FROM rt_rors, projects 
		WHERE rt_rors.loc_key = projects.id 
		AND projects.".$db_string." = $user_domain 
		AND rt_rors.status = '$status' ORDER BY projects.store_number LIMIT 50";
	} else {
		$sql = "SELECT rt_rors.id, rt_rors.loc_key,rt_rors.body,rt_rors.status,rt_rors.type,rt_rors.urgency,
		projects.sitename,projects.sitecity,projects.sitestate,projects.store_number,
		projects.store_district,projects.store_region,projects.high_volume_store,UNIX_TIMESTAMP(rt_rors.creation) AS FORMATED_TIME";

		//If dm_response flag is on, then show only the ones that have recently been responded to by DMs
//		if ($dm_response == 1) {
//		$sql = "SELECT rt_rors.id,rt_ror_responses.parent_key, rt_rors.loc_key,rt_rors.body,rt_rors.status,rt_rors.type,rt_rors.urgency,
//		projects.sitename,projects.sitecity,projects.sitestate,projects.store_number,
//		projects.store_district,projects.store_region,projects.high_volume_store,UNIX_TIMESTAMP(rt_ror_responses.creation) AS FORMATED_TIME,
//		rt_ror_responses.id as rid, rt_ror_responses.author_key, users.id as uid, users.groupid";
//		}

		//If the Vendor is logged in, the database will be filtered to show only requests where this vendor is assigned
		if ($usergroup == 3){$sql .= ",companies.company_id ";}		
		$sql .= " FROM rt_rors,projects";
		
		//If dm_response flag is on, then show only the ones that have recentlyly been responded to by DMs
//		if ($dm_response == 1) {
//			$sql .= ",users,rt_ror_responses";
//		}
		

		//If the Vendor is logged in, dynamically insert the companies table into the FROM declaration
		if ($usergroup == 3){$sql .= ",companies ";}
		$sql .= " WHERE rt_rors.loc_key = projects.id ";

		//If dm_response flag is on, then show only the ones that have recently been responded to by DMs
		if ($dm_response == 1) {
			$sql .= " AND rt_rors.dm_response = 1";
//			$sql .= " AND rt_ror_responses.parent_key = rt_rors.id AND rt_ror_responses.author_key = users.id AND users.groupid = 2";
//			$sql .= " AND  rt_ror_responses.creation >= DATE_ADD(now(), INTERVAL - 15 DAY)";
		}
		
		//If the Vendor is logged in, the database will be filtered to show only requests where this vendor is assigned
		if ($usergroup == 3){$sql .= " AND rt_rors.vendor_key = companies.company_id AND companies.company_id = ".$usercompany." ";}
	
		$sql .= " AND rt_rors.status = '$status'";
		
		//If dm_response flag is on, then show only the ones that have recently been responded to by DMs

//		if ($dm_response == 1) {
//			$sql .= " GROUP BY rt_rors.id ORDER BY rt_ror_responses.creation DESC";
//		} else {
			$sql .= " ORDER BY store_number";
//		}
		
	}
	
	$result = mysqli_query($dbcnx, $sql);
	if (!$result) {error("Error with database: $sql ".mysqli_error($dbcnx));}

	//Create columns
	echo "<table width=\"100%\" class=\"sortable\" id=\"datarows\" cellspacing=\"0\" cellpadding=\"0\"><thead>";
	echo "<tr style=\"text-align:left;font-size:11px;\">";
	echo "<th align=\"center\">*</th>";
	echo "<th>Location</th>";
	echo "<th>Request</th>";
	echo "<th>S</th>";
	echo "<th>D</th>";
	echo "<th>R</th>";
	echo "<th>Type</th>";
	echo "<th>Priority</th>";
	echo "<th>Updated</th>";
	echo "</tr></thead>";

	//Start iterating through rows
	while ($row = mysqli_fetch_object($result)) {

		$sqly = "select id from rt_ror_responses where parent_key = ".$row->id."";
		$resulty = mysqli_query($dbcnx, $sqly);
		if (!$resulty) {error("Error with database: ".mysqli_error($dbcnx));exit;}
		$count = mysqli_num_rows($resulty);
		if ($count > 0) {$count="<span class=\"msgcount\">$count</span>";}else {$count="";}
	
		$creation = "$row->FORMATED_TIME"; 
		echo "<input type=\"hidden\" name=\"workset[]\" value=\"$row->id\" />";
		echo "<tr class=\"$style\">";
		echo "<td  class=\"star_store\">";
		if ($row->high_volume_store == 1) {echo "<span style=\"display:none;\">1</span> <img src=\"images/star.gif\" />";}
		echo "</td>";
		echo "<td><a href=\"#\" onClick=\"ViewPop('view.php?mode=ROR&id=$row->id','view$row->id');return false;\">".myTruncate($row->sitename,30, " ")."</a> $count";
		echo "<a href=\"javascript:workit(".$row->id.",'$status');\"><img align=\"absmiddle\"  border=\"0\" src=\"images/start.gif\" /><a/>";
		echo "</td>";
		echo "<td><p title=\"$row->body\">".myTruncate($row->body,20," ")."</p></td>";
		echo "<td class=\"small\"><p>$row->store_number&nbsp;</p></td>";
		echo "<td class=\"small\"><p>$row->store_district&nbsp;</p></td>";
		echo "<td class=\"small\"><p>$row->store_region&nbsp;</p></td>";
		echo "<td class=\"small\"><p>".$t[$row->type][0]."&nbsp;</p></td>";
		echo "<td class=\"small\"><p><img align=\"absmiddle\"  src=\"images/".$u[$row->urgency][1].".gif\" />".$u[$row->urgency][0]."&nbsp;</p></td>";
		echo "<td class=\"small\" width=\"90\"><p>".date("m/d/y g:ia",$creation)."</p></td>";	
		echo "</tr>";
	}
	echo "</table>";
	echo "<p><small><a href=\"list.php?show=$status&mode=ROR\">$expand</a></small></p>";
}

function dashboardFTable($status) {
	global $dbcnx;
	global $uid;
	global $usergroup;

	$u = Urgencies();

	if ($status == "open") {
		$crit = "ts";
		$expand = "View all open requests";
		$style = "norm";
	} elseif ($status == "new"){
		$crit = "creation";
		$expand = "View all new requests";
		$style = "bold";
	}
	if ($usergroup == 2) {
		$user_uid_rank = substr($uid,0,2);
		$user_domain = substr($uid,2);
		if ($user_uid_rank == "dm") {
			$domain_value="District";
			$db_string = "store_district";
		} elseif ($user_uid_rank == "rm") {
			$domain_value="Region";
			$db_string="store_region";
		}	
		$sql = "SELECT rt_freqs.*,fixture_key.id AS fixture_id, fixture_key.name, fixture_key.desc, 
		projects.sitename,projects.sitecity,projects.sitestate,projects.store_number,
		projects.store_district,projects.store_region,projects.high_volume_store,UNIX_TIMESTAMP(rt_freqs.creation) AS FORMATED_TIME  
		FROM rt_freqs, projects, fixture_key 
		WHERE rt_freqs.loc_key = projects.id 
		AND fixture_key.id = rt_freqs.fixture_key 
		AND projects.".$db_string." = $user_domain 
		AND rt_freqs.status = '$status' 
		ORDER BY rt_freqs.".$crit." DESC LIMIT 5";
	
	} else {	
		$sql = "SELECT rt_freqs.*,fixture_key.id AS fixture_id, fixture_key.name, fixture_key.desc, 
		projects.sitename,projects.sitecity,projects.sitestate,projects.store_number,
		projects.store_district,projects.store_region,projects.high_volume_store,UNIX_TIMESTAMP(rt_freqs.creation) AS FORMATED_TIME  
		FROM rt_freqs, projects, fixture_key 
		WHERE rt_freqs.loc_key = projects.id 
		AND fixture_key.id = rt_freqs.fixture_key 
		AND rt_freqs.status = '$status' 
		ORDER BY rt_freqs.".$crit." DESC LIMIT 5";
	}
	$result = mysqli_query($dbcnx, $sql);

	if (!$result) {error("Error with database: ".mysqli_error($dbcnx));}

	//Create columns
	echo "<table width=\"100%\" class=\"sortable\" id=\"datarows\" cellspacing=\"0\" cellpadding=\"0\"><thead>";
	echo "<tr style=\"text-align:left;font-size:11px;\">";
	echo "<th align=\"center\">*</th>";
	echo "<th>Sitename</th>";
	echo "<th>Request</th>";
	echo "<th>S</th>";
	echo "<th>D</th>";
	echo "<th>R</th>";
	echo "<th>Status</th>";
	echo "<th>Priority</th>";
	echo "<th>Date</th>";
	echo "</tr></thead>";

	//Start iterating through rows
	while ($row = mysqli_fetch_object($result)) {

		//Get response count	
		$sqly = "select id from rt_freq_responses where parent_key = ".$row->opk."";
		$resulty = mysqli_query($dbcnx, $sqly);
		if (!$resulty) {error("Error with database: ".mysqli_error($dbcnx));exit;}
		$count = mysqli_num_rows($resulty);
		if ($count > 0) {$count="<span class=\"msgcount\">$count</span>";}else {$count="";}	
	
		$creation = "$row->FORMATED_TIME"; 
		echo "<input type=\"hidden\" name=\"workset[]\" value=\"$row->id\" />";
		echo "<tr class=\"$style\">";
		echo "<td class=\"star_store\">";
		if ($row->high_volume_store == 1) {echo "<span style=\"display:none;\">1</span> <img src=\"images/star.gif\" />";}
		echo "</td>";
		echo "<td><a href=\"#\" onClick=\"ViewPop('view.php?mode=FREQ&id=$row->id','view');return false;\">".myTruncate($row->sitename,30, " ")."</a> $count";
		echo "<a href=\"javascript:workit(".$row->id.",'$status');\"><img align=\"absmiddle\"  border=\"0\" src=\"images/start.gif\" /><a/>";
		echo "</td>";
		echo "<td><p><strong>$row->name</strong> ".ucwords(strtolower(myTruncate($row->desc,20," ")))." [Qty:".$row->qty."]</p></td>";
		echo "<td class=\"small\"><p>$row->store_number&nbsp;</p></td>";
		echo "<td class=\"small\"><p>$row->store_district&nbsp;</p></td>";
		echo "<td class=\"small\"><p>$row->store_region&nbsp;</p></td>";
		echo "<td class=\"small\"><p>".ucwords($row->order_status)."</p></td>";
		echo "<td class=\"small\"><p><img align=\"absmiddle\"  src=\"images/".$u[$row->urgency][1].".gif\" />".$u[$row->urgency][0]."&nbsp;</p></td>";
		echo "<td class=\"small\" width=\"90\"><p>".date("n/j/y g:ia",$creation)."</p></td>";	
		echo "</tr>";
	}
	echo "</table>";
	echo "<p><small><a href=\"list.php?show=$status&mode=FREQ\">$expand</a></small></p>";
}

function g2filter($uid) {
	$g = Array();
	$user_uid_rank = substr($uid,0,2);
	$g[0] = substr($uid,2);
	if ($user_uid_rank == "dm") {
		$domain_value="District";
		$g[1] = "store_district";
	} elseif ($user_uid_rank == "rm") {
		$domain_value="Region";
		$g[1] = "store_region";
	}
	return $g;
}	

function loc_data($loc_key) {
	global $dbcnx;
	$sql = "select * from projects where id = $loc_key";
	$result = mysqli_query($dbcnx, $sql);
	if (!$result) {error("Error with database: ".mysqli_error($dbcnx));exit;}
	$loc = mysqli_fetch_object($result);
	$html = "<span style=\"float:right;\">District ".intval($loc->store_district)." / Region ".intval($loc->store_region)."</span>"
	."<div class=\"bigger\"><b>#".intval($loc->store_number)." {$loc->sitename}</b></div>"
	."{$loc->sitecity}, {$loc->sitestate}";
	return $html;
}

function mysql_do($sql) {	
	global $dbcnx;
  $r = mysqli_query($dbcnx, $sql) or die("db do query:".$sql.mysqli_error($dbcnx));
  return mysqli_result($r,0);
}

function status_translate($s) {
	//Translate funky names into taxonomy friendly names
	//$s = Status
	//$t = Translation
	switch ($s) {
		case "rm_ok": $t = "RM Approved";break;
		case "vp_ok": $t = "VP Approved";break;
		case "waiting": $t = "Waiting";break;
		case "rm_deny": $t = "RM Denied";break;
		case "vp_deny": $t = "VP Denied";break;
		case "processed": $t = "Processed";break;
    case "deny": $t = "Denied";break;
		default: $t = $s;
	}
	return $t;

}

function get_total($status,$dom,$val) {
	global $dbcnx;
	global $season;
	if ($dom=="all") {
		if ($status=="m") {
		$this_total = mysql_do("SELECT sum(cost*qty) from fixture_requests, fixture_key, projects where fixture_key.name='LM03' and season = '$season'  and fixture_requests.fix_key = fixture_key.id and fixture_requests.loc_key = projects.id");
		} else {
		$this_total = mysql_do("SELECT sum(cost*qty) from fixture_requests, fixture_key, projects where fixture_requests.status = '$status' and season = '$season'  and fixture_requests.fix_key = fixture_key.id and fixture_requests.loc_key = projects.id");
		}
	} else {
		if ($status=="m") {
		$this_total = mysql_do("SELECT sum(cost*qty) from fixture_requests, fixture_key, projects where fixture_key.name='LM03' and $dom = $val and season = '$season'  and fixture_requests.fix_key = fixture_key.id and fixture_requests.loc_key = projects.id");
		} else {
		$this_total = mysql_do("SELECT sum(cost*qty) from fixture_requests, fixture_key, projects where fixture_requests.status = '$status' and $dom = $val and season = '$season'  and fixture_requests.fix_key = fixture_key.id and fixture_requests.loc_key = projects.id");
		}

	}
	//$total = "$".number_format($this_total,2);
	
	return $this_total;
}

function get_aggregate($dom,$val) {
	global $dbcnx;
	global $season;
	if ($dom=="all") {
		$this_total = mysql_do("SELECT sum(cost*qty) from fixture_requests, fixture_key where season = '$season'  and fixture_requests.fix_key = fixture_key.id");
	} else {

		$this_total = mysql_do("SELECT sum(cost*qty) from fixture_requests, fixture_key, projects where $dom = $val and season = '$season'  and fixture_requests.fix_key = fixture_key.id and loc_key = projects.id");

	}
	$total = "$".number_format($this_total,2);
	return $total;
}
////set up default $mode 
//if (isset($_GET['mode'])) {
//	$mode = $_GET['mode'];
//} else {
//	switch($unique_user_id){
//	case "92":
//	case "295":
//	case "90":
//	case "390":
//		$mode = "FREQ";
//		break;
//	default:
//		$mode = "ROR";
//		break;
//	}
//}

function chart_data($values) {

// Port of JavaScript from http://code.google.com/apis/chart/
// http://james.cridland.net/code

// First, find the maximum value from the values given

$maxValue = max($values);

// A list of encoding characters to help later, as per Google's example
$simpleEncoding = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

$chartData = "s:";
  for ($i = 0; $i < count($values); $i++) {
    $currentValue = $values[$i];

    if ($currentValue > -1) {
    $chartData.=substr($simpleEncoding,61*($currentValue/$maxValue),1);
    }
      else {
      $chartData.='_';
      }
  }

// Return the chart data - and let the Y axis to show the maximum value
return $chartData."&chxt=y&chxl=0:|0|".$maxValue;
}

function moneyFormat($number, $currencySymbol = '$',
$decPoint = '.', $thousandsSep = ',', $decimals = 2) {
return $currencySymbol . number_format($number, $decimals,
$decPoint, $thousandsSep);
}
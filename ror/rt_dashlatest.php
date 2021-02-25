<?
include("../include/access.php");
include("../include/rt.php");


dbConnect();
	$main = "<table id=\"datarows\" class=\"sortable\" cellspacing=\"0\" width=\"99%\" cellpadding=\"0\">";
	$main .= "<tr>";
	$main .= "<th>Tracking #</th>";
	$main .= "<th>Comment</th>";
	$main .= "<th>Posted By</th>";
	$main .= "<th>Date</th>";
	$main .= "</tr>";


	if ($_GET['mode'] == "ROR") {
		$sql = "SELECT rt_ror_responses.*,projects.*,rt_rors.tracking, rt_rors.id, rt_rors.loc_key, users.fullname, users.id,UNIX_TIMESTAMP(rt_ror_responses.creation) AS FORMATED_TIME ";
		//If the Vendor is logged in, the database will be filtered to show only requests where this vendor is assigned
		if ($usergroup == 3){$sql .= ",companies.company_id ";}
		$sql .= "FROM rt_ror_responses, projects, rt_rors, users ";  
		//If the Vendor is logged in, dynamically insert the companies table into the FROM declaration
		if ($usergroup == 3){$sql .= ",companies ";}
		$sql .= "WHERE rt_ror_responses.parent_key = rt_rors.id AND projects.id = rt_rors.loc_key AND users.id = rt_ror_responses.author_key ";
		//If the DM or RM is logged in, Filter the requests g1 is either going to be store_district or store_region depending on the login
		// and g0 is going to the be number , so if dm4 is logged in g1=store_district g0=4
		if ($usergroup == 2){$g = g2filter($uid);$sql .= "AND projects.$g[1] = $g[0] ";}
		//If the Vendor is logged in, the database will be filtered to show only requests where this vendor is assigned
		if ($usergroup == 3){$sql .= "AND rt_rors.vendor_key = companies.company_id AND companies.company_id = $usercompany ";}
		$sql .=" ORDER BY rt_ror_responses.creation DESC LIMIT 10";
	} elseif($_GET['mode'] == "FREQ") {
		$sql = "SELECT rt_freq_responses.*,projects.*,rt_freqs.tracking, rt_freqs.id, rt_freqs.loc_key, users.fullname, users.id,UNIX_TIMESTAMP(rt_freq_responses.creation) AS FORMATED_TIME ";
		//If the Vendor is logged in, the database will be filtered to show only requests where this vendor is assigned
		if ($usergroup == 3){$sql .= ",companies.company_id ";}
		$sql .= "FROM rt_freq_responses, projects, rt_freqs, users ";  
		//If the Vendor is logged in, dynamically insert the companies table into the FROM declaration
		if ($usergroup == 3){$sql .= ",companies ";}
		$sql .= "WHERE rt_freq_responses.parent_key = rt_freqs.id AND projects.id = rt_freqs.loc_key AND users.id = rt_freq_responses.author_key ";
		//If the DM or RM is logged in, Filter the requests g1 is either going to be store_district or store_region depending on the login
		// and g0 is going to the be number , so if dm4 is logged in g1=store_district g0=4
		if ($usergroup == 2){$g = g2filter($uid);$sql .= "AND projects.$g[1] = $g[0] ";}
		//If the Vendor is logged in, the database will be filtered to show only requests where this vendor is assigned
		if ($usergroup == 3){$sql .= "AND rt_freqs.vendor_key = companies.company_id AND companies.company_id = $usercompany ";}
		$sql .=" ORDER BY rt_freq_responses.creation DESC LIMIT 10";
	}
	
	
	
	$results = mysql_query($sql);
	while ($row = mysql_fetch_object($results)) {
		$creation = "$row->FORMATED_TIME";
		$main .="<tr>";
		$main .="<td><a href=\"javascript:ViewPop('view.php?mode=$mode&id=$row->parent_key','view');\">$row->tracking</a></td>";
		$main .="<td width=\"40%\">$row->body</td>";
		$main .="<td>$row->fullname</td>";
		$main .="<td>".date("n/j/y g:ia",$creation)."</td>";
		$main .="</tr>";
	}
		


	$main .= "</table>";
//Escape characters
function EscapeChars($x) {
	$x = str_replace("'", "\'", $x);
	$x = str_replace('"', "'+String.fromCharCode(34)+'", $x);
	$x = str_replace ("\r\n", '\n', $x);
	$x = str_replace ("\r", '\n', $x);
	$x = str_replace ("\n", '\n', $x);
	return $x;
	}
$main = EscapeChars($main);
?>
document.getElementById('dlatest').className='none';
document.getElementById('dnew').className='none';
document.getElementById('dopen').className='none';
document.getElementById('dcompleted').className='none';
document.getElementById('statdash').innerHTML='<?php echo $main;?>';
document.getElementById('dlatest').className='current';
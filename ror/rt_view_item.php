<?php

//Load types and urgencies
$t = Types();
$u = Urgencies();
 
//pull data
if ($mode == "ROR") {
	$rt_sql = "SELECT rt_rors.*,UNIX_TIMESTAMP(rt_rors.creation) AS FORMATED_TIME, users.fullname, users.id AS userid,
			companies.company_name 
			FROM rt_rors, users, companies 
			WHERE companies.company_id = rt_rors.vendor_key 
			AND users.id = rt_rors.author_key 
			AND rt_rors.id = '$id'";
	$res_db = "rt_ror_responses";

	//set as read
	$read_sql = "update rt_rors set `read` = 1 where id = $id";
	$read_res = mysql_query($read_sql);


} elseif ($mode == "FREQ") {
	$rt_sql = "SELECT rt_freqs.*,UNIX_TIMESTAMP(rt_freqs.creation) AS FORMATED_TIME,fixture_key.id AS fixture_id,fixture_key.name,fixture_key.desc,
			users.fullname, users.id AS userid,companies.company_name 
			FROM rt_freqs,fixture_key,users,companies 
			WHERE fixture_key.id = rt_freqs.fixture_key 
			AND companies.company_id = rt_freqs.vendor_key 
			AND users.id =  rt_freqs.author_key 
			AND  rt_freqs.id = '$id'";
	$res_db = "rt_freq_responses";

	//set as read
	$read_sql = "update rt_freqs set `read` = 1 where id = $id";
	$read_res = mysql_query($read_sql);
}

//Run dynamic query
$rt_res = mysql_query($rt_sql);
if (!$rt_res){error("Error with database: ".mysql_error());exit;}
//build object
$rt = mysql_fetch_object($rt_res);
//count responses
if ($mode == "ROR") {
	$cq = mysql_query("select * from $res_db where parent_key = '$rt->id'");
} elseif ($mode == "FREQ") {
	$cq = mysql_query("select * from $res_db where parent_key = '$rt->opk' AND fixture_key = $rt->fixture_key");
}
$count = mysql_num_rows($cq);

//get date and translate
$creation = date("n/j/y g:ia",$rt->FORMATED_TIME);

	echo "<tr>";	
	echo "<td class=\"key ntb\">Name</td><td class=\"val ntb\">".$rt->fullname."<input type=\"hidden\" name=\"userid\" value=\"".$rt->userid."\" /></td>";
	echo "<td class=\"gutter\">&nbsp;</td>";
	echo "<td class=\"key ntb\">Urgency</td>";
	echo "<td class=\"val ntb\"><div id=\"urgbox\"><img src=\"images/".$u[$rt->urgency][1].".gif\" />&nbsp;".$u[$rt->urgency][0]."</div>";
	if ($usergroup<2) {
		echo "<script type=\"text/javascript\">new Ajax.InPlaceCollectionEditor('urgbox','eip.php?mode=$mode&edit=urgency&id=$rt->id',";
		echo "{collection:[";
		//This iterates through every key in the global array ($u or $t or $f) and writes it out like this [[10,'Hazard'],[20,'Urgent'],...[0,'None']]
		foreach ($u as $k) {
			$key = array_search($k,$u);
			if ($mode == "FREQ"){
				if ($key=="10") {continue;}
				if ($key=="50") {continue;}
			}
			echo "[".$key.",'".$k[0]."'],";
		}
		echo "[0,'None']],value : $rt->urgency });</script>";
		//This line closes the script command and tells the browser which item should be the default
	}
	
	echo "</td>";
	echo "</tr>";
	echo "<tr>";	
	echo "<td class=\"key\">Date</td><td class=\"val\">$creation</td>";
	echo "<td class=\"gutter\">&nbsp;</td>";

	if ($mode == "ROR") {
		echo "<td class=\"key\">Status</td><td class=\"val\">".ucwords($rt->status)."</td>";
	} elseif ($mode == "FREQ") {
		echo "<td class=\"key\">Status</td><td class=\"val\">".ucwords($rt->status).", ".ucwords($rt->order_status)."</td>";
	}
	
	echo "</tr>";
	echo "<tr>";
	
	if ($mode == "ROR") {
		echo "<td class=\"key\">Type</td>";
		echo "<td class=\"val\"><div id=\"typebox\">".$t[$rt->type][0]."<input type=\"hidden\" name=\"type\" value=\"".$t[$rt->type][0]."\" /></div>";
		if ($usergroup<2) {
			echo "<script type=\"text/javascript\">new Ajax.InPlaceCollectionEditor('typebox','eip.php?mode=$mode&edit=type&id=$rt->id',";
			echo "{collection:[";
			//This iterates through every key in the global array ($u or $t or $f) and writes it out like this [[10,'Hazard'],[20,'Urgent'],...[0,'None']]
			foreach ($t as $k) {
				$key = array_search($k,$t);
				echo "[".$key.",'".$k[0]."'],";
			}
			echo "[0,'None']],value : $rt->type });</script>";
			//This line closes the script command and tells the browser which item should be the default
		}
		echo "</td>";
	} elseif ($mode == "FREQ") {	
		echo "<td class=\"key\">Fixture</td><td class=\"val\">$rt->name ".ucwords(strtolower($rt->desc))."<input type=\"hidden\" name=\"fixture\" value=\"$rt->fixture_key\" /><br />";
		echo "<strong>Qty:</strong> ".$rt->qty."&nbsp;&nbsp;&nbsp;&nbsp;";
		//Get blanket count
		$blanket_sql = "SELECT * FROM fixture_blanket WHERE fixture_key = '$rt->fixture_id' order by mod_date desc";
		$blanket_res = mysql_query($blanket_sql);
		if (!$blanket_res){echo("$blanket_sql - Error with database: ".mysql_error());exit;}
		if ((mysql_num_rows($blanket_res)>0) and ($usergroup < 2)){
			$blanket = mysql_result($blanket_res,0,"qty");
			echo "<strong><a href=\"freq-stats.php?id=".$rt->fixture_id."\" target=\"main\">Inventory</a>:</strong> ".$blanket."&nbsp;";
			}
		echo "</td>";
		
	}
		echo "<td class=\"gutter\">&nbsp;</td>";
		echo "<td class=\"key\">Vendor</td><td class=\"val\">$rt->company_name</td>";
	echo "</tr>";
	echo "<tr>";	
		echo "<td class=\"key\">Tracking</td><td class=\"val\">$rt->tracking &nbsp;<input type=\"hidden\" name=\"tracking\" value=\"".$rt->tracking."\" /></td>";
		echo "<td class=\"gutter\">&nbsp;</td>";
		echo "<td class=\"key\">Responses</td><td class=\"val\">$count</td>";
	echo "</tr>";
	echo "<tr>";	
		echo "<td class=\"body\" colspan=\"5\"><p><small><strong>Store Contact:</strong> $rt->extra_contact</small> <br /><br /> $rt->body</p></td>";
	echo "</tr>";

?>
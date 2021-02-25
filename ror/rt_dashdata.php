<?php
include("../include/access.php");
include("../include/rt.php");
//Load types and urgencies
$t =Types();
$u = Urgencies();
$f= Fixtures();
$mode = $_GET['mode'];

if ($_GET['show'] == "completed") {$show = "'".$_GET['show']."' AND ts >= DATE_ADD(now(), INTERVAL -10 DAY) ";}else{$show = "'".$_GET['show']."'";}

$main = "";
dbConnect();

if ($mode=="ROR"){
	$main .= "<table>";
	$main .= "<tr><td>";
	$fx=0;
	foreach ($u as $k) {
		$key = array_search($k,$u);
		if ($usergroup == 2) {
			$user_uid_rank = substr($uid,0,2);
			$user_domain = substr($uid,2);
			if ($user_uid_rank=="dm"){$domain_value="District";$db_string = "store_district";}elseif($user_uid_rank=="rm"){$domain_value="Region";$db_string="store_region";}
			$res = mysql_query("SELECT * from rt_rors, projects WHERE rt_rors.urgency = ".$key." AND projects.id = rt_rors.loc_key AND projects.".$db_string." = $user_domain AND rt_rors.status = $show ");
		} else {
			$res = mysql_query("SELECT * from rt_rors WHERE urgency = ".$key." AND status = $show ");
		}
		$count = mysql_num_rows($res);
		if ($count>0){$main .= "<p class=\"dashurg\"><img src=\"images/".$k[1].".gif\" /><strong>$count </strong> <a href=\"report_engine.php?mode=$mode&filter=u&crit=$key\">$k[0]</a></p>";$fx=1;}else{continue;}
		//$main .= "<p class=\"dashurg\"><img src=\"images/".$k[1].".gif\" />$count $k[0]</p>";
	}
	if ($fx==0) {$main .= "<p>No requests to show!</p>";}
	$main .= "<div style=\"clear:both;\">&nbsp;</div>";
	$main .= "</td><td>";
	$fx=0;
	$bar_count=0;
	foreach ($t as $k) {
			$key = array_search($k,$t);
		if ($usergroup == 2) {
			$user_uid_rank = substr($uid,0,2);
			$user_domain = substr($uid,2);
			if ($user_uid_rank=="dm"){$domain_value="District";$db_string = "store_district";}elseif($user_uid_rank=="rm"){$domain_value="Region";$db_string="store_region";}
			$res = mysql_query("SELECT * from rt_rors, projects WHERE rt_rors.type = ".$key." AND projects.id = rt_rors.loc_key AND projects.".$db_string." = $user_domain AND rt_rors.status = $show ");
		} else {
			$res = mysql_query("SELECT * from rt_rors WHERE type = ".$key." AND status = $show ");
		}
		$count = mysql_num_rows($res);
		$graph_bar = $count * 2;
		if ($count>0){$main .= "<p class=\"dashtype\"><span style=\"width:".$graph_bar."px;\">&nbsp;</span><strong>$count</strong> <a href=\"report_engine.php?mode=$mode&filter=t&crit=$key\">$k[0]</a></p>";$fx=1;}else{continue;}
		$bar_count++;
		if (($bar_count % 5) ==0){$main.="</td><td>";}
		
	}	
	if ($fx==0) {$main .= "<p>No requests to show!</p>";}
	$main .= "<div style=\"clear:both;\"></div>";
	$main .= "</td></tr></table>";
} elseif ($mode=="FREQ") {
	$main .= "<table>";
	$main .= "<tr><td>";

	$fx=0;
	foreach ($u as $k) {
		$key = array_search($k,$u);
		if ($usergroup == 2) {
			$user_uid_rank = substr($uid,0,2);
			$user_domain = substr($uid,2);
			if ($user_uid_rank=="dm"){$domain_value="District";$db_string = "store_district";}elseif($user_uid_rank=="rm"){$domain_value="Region";$db_string="store_region";}
			$res = mysql_query("SELECT * from rt_freqs, projects WHERE rt_freqs.urgency = ".$key." AND projects.id = rt_freqs.loc_key AND projects.".$db_string." = $user_domain AND rt_freqs.status =  $show ");
		} else {
			$res = mysql_query("SELECT * from rt_freqs WHERE urgency = ".$key." AND status =  $show ");
		}
		$count = mysql_num_rows($res);
		if ($count>0){$main .= "<p class=\"dashurg\"><img src=\"images/".$k[1].".gif\" /><strong>$count </strong> <a href=\"report_engine.php?mode=$mode&filter=u&crit=$key\">$k[0]</a></p>";$fx=1;}else{continue;}
	}
	if ($fx==0) {$main .= "<p>No requests to show!</p>";}
	$main .= "<div style=\"clear:both;\">&nbsp;</div>";
	$main .= "</td><td>";


	$fx=0;
	while($fixture = mysql_fetch_object($f)) {
		if ($usergroup == 2) {
			$user_uid_rank = substr($uid,0,2);
			$user_domain = substr($uid,2);
			if ($user_uid_rank=="dm"){$domain_value="District";$db_string = "store_district";}elseif($user_uid_rank=="rm"){$domain_value="Region";$db_string="store_region";}
			$res = mysql_query("SELECT * from rt_freqs,projects WHERE rt_freqs.fixture_key = ".$fixture->id." AND projects.id = rt_freqs.loc_key AND projects.".$db_string." = $user_domain AND rt_freqs.status = $show ");
		} else {
			$res = mysql_query("SELECT * from rt_freqs WHERE fixture_key = ".$fixture->id." AND status = $show ");
		}
		$count = mysql_num_rows($res);
		$graph_bar = $count * 2;
		if ($count>0){$main .= "<p class=\"dashtype\"><span style=\"width:".$graph_bar."px;\">&nbsp;</span><strong>$count</strong> <a href=\"report_engine.php?mode=$mode&filter=f&crit=$fixture->name\">$fixture->name</a></p>";$fx=1;}else{continue;}
		$bar_count++;
		if (($bar_count % 5) ==0){$main.="</td><td>";}

	}	
	if ($fx==0) {$main .= "<p>No requests to show!</p>";}
	$main .= "<div style=\"clear:both;\"></div>";
	$main .= "</td></tr></table>";
}

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
document.getElementById('<?php echo "d".$_GET['show'];?>').className='current';
<?php
error_reporting(E_ALL ^ E_NOTICE);
include("../include/access.php");
include("../include/rt.php");
$mode="ROR";
$pageheading = "Repair Order Requests - Home";
//Load types and urgencies and fixtures
$t =Types();
$u = Urgencies();


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title><?=$pageheading?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" media="all">@import "rt.css";</style>
<script type="text/javascript" src="rt.js"></script>
<script type="text/javascript">
	window.onload = function() {
	document.getElementById('lhome').className='link';
	document.getElementById('lnew').className='link';
	document.getElementById('lopen').className='link';
	document.getElementById('lcompleted').className='link';
	document.getElementById('lreport').className='link';
	document.getElementById('lcreate').className='link';
	document.getElementById('lhome').className='current';
	document.getElementById('progress').style.visibility = "hidden";

}
</script>
</head>
<body>
<a name="top"></a>
<div id="progress" style="visibility: visible;">Loading...</div>
<div id="menu"><?php include("ror-menu.php");?></div>
<h1><?=$pageheading?></h1>
<div id="maincontainer"> 
<?php 
//Show announcment to DMs
if ($usergroup==2) {
	dbConnect();
	$sql = "select * from blog where readers='g2'";
	$result = mysql_query($sql);
	if (!$result){error("A databass error has occured in processing your request.\\n". mysql_error());}
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
	echo "<fieldset><legend style=\"font-size:75%;\">Announcement: Posted by $author on $ts</legend><p>$body</p></fieldset><br />";
}


// Here's an array containing some data to plot
//$test_data=array(0.5,6,12,17,2);


//http://chart.apis.google.com/chart?cht=bhg&chs=600x300&chd=s:KUIZFDPJF&chtt=Food+and+Drink+Consumed+Christmas+2007
// Here's where we call the chart, and return the encoded chart data
//echo "<img src=http://chart.apis.google.com/chart?chtt=".urlencode("It's an example!")."&cht=bhg&chs=450x125&chxt=x,y&chd=".chart_data($test_data)."&chxl=1:|Egg+nog|Christmas+Ham|Milk+(not+including+egg+nog)|Cookies|Roast+Chestnuts&chco=00AF33 />";

//Show Quick Report to Charlotte-Russe Home Office
if ($usergroup <2){ ?>
	<div id="quickreport">
	<form action="report_engine.php?mode=ROR" name="quickreport" method="POST">
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
<?php } ?>


<?php //Email Link Quick Launch
	if(isset($_GET['launch'])){
		$lid = $_GET['launch'];
		$lres = mysql_query("select rt_rors.loc_key,rt_rors.body,rt_rors.status,rt_rors.id,rt_rors.type,rt_rors.urgency,projects.sitename,projects.sitecity,projects.sitestate,projects.store_number from rt_rors,projects where rt_rors.loc_key = projects.id and rt_rors.id = $lid");
		$lror = mysql_fetch_object($lres);
		echo "<br />";
		echo "<div style=\"padding:20px;clear:both;font-size:85%;border:1px solid #3366CC;background:#D5DDF3;\">";
		echo "<a href=\"#\" onClick=\"ViewPop('view.php?mode=ROR&id=$lror->id','view');return false;\"><img src=\"images/emailnotice.gif\" style=\"float:left;margin:5px;\" /></a>";
		echo "<p><strong>You are trying to view a request you recieved via Email:</strong></p>";
		echo "<p>#$lror->store_number $lror->sitename - ".$t[$lror->type][0].", <img align=\"absmiddle\"  src=\"images/".$u[$lror->urgency][1].".gif\" />".$u[$lror->urgency][0]." <br />";
		echo "&quot;".myTruncate($lror->body,50," ")."&quot;</p>";
		echo "<a href=\"#\" onClick=\"ViewPop('view.php?mode=ROR&id=$lror->id','view');return false;\"><strong>Click here to view this request</strong></a><br style=\"clear:both;\" /></div>";
	}


	//Watchlist Code
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
		$sql = "SELECT rt_rors.*,
		projects.sitename,projects.sitecity,projects.sitestate,projects.store_number,
		projects.store_district,projects.store_region,projects.high_volume_store,
		UNIX_TIMESTAMP(rt_rors.creation) AS FORMATED_TIME,UNIX_TIMESTAMP(rt_rors.ts) AS FORMATED_TS FROM rt_rors, projects 
		WHERE rt_rors.loc_key = projects.id 
		AND projects.".$db_string." = $user_domain 
		AND rt_rors.watchlist LIKE '%$uid%' ORDER BY rt_rors.creation asc";
	} else {
		$sql = "SELECT rt_rors.*,
		projects.sitename,projects.sitecity,projects.sitestate,projects.store_number,
		projects.store_district,projects.store_region,projects.high_volume_store,
		UNIX_TIMESTAMP(rt_rors.creation) AS FORMATED_TIME,UNIX_TIMESTAMP(rt_rors.ts) AS FORMATED_TS FROM rt_rors, projects 
		WHERE rt_rors.loc_key = projects.id 
		AND rt_rors.watchlist LIKE '%$uid%' 
		ORDER BY rt_rors.creation asc";
	}

	$result = mysql_query($sql);
	if (!$result) {error("Error with database: ".mysql_error());}
	
	if (mysql_num_rows($result)>0) {
		echo "<h2>My Watch List</h2>";
		//Create columns
		echo "<table width=\"100%\" id=\"datarows\" class=\"sortable\" cellspacing=\"0\" cellpadding=\"0\">";
		echo "<tr style=\"text-align:left;font-size:11px;\">";
		echo "<th align=\"center\">*</th>";
		echo "<th>Sitename</th>";
		echo "<th>Request</th>";
		echo "<th>S</th>";
		echo "<th>D</th>";
		echo "<th>R</th>";
		echo "<th>Type</th>";
		echo "<th>Priority</th>";
		echo "<th>Activity</th>";
		echo "</tr></thead>";
		//Start iterating through rows
		while ($row = mysql_fetch_object($result)) {
		
			//Get response count	
			$sqly = "select id from rt_ror_responses where parent_key = ".$row->id."";
			$resulty = mysql_query($sqly);
			if (!$resulty) {error("Error with database: ".mysql_error());exit;}
			$count = mysql_num_rows($resulty);
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
			echo "<td> <a href=\"remwl.php?mode=ROR&id=$row->id\"><img align=\"absmiddle\" src=\"images/trash.gif\" /></a> ";
			echo "<a href=\"#\" onClick=\"ViewPop('view.php?mode=ROR&id=$row->id','view');return false;\">".myTruncate($row->sitename,30, " ")."</a> $count";
			echo "<a href=\"javascript:workit(".$row->id.",'$status');\"><img align=\"absmiddle\"  border=\"0\" src=\"images/start.gif\" /><a/>";
			echo "</td>";
			echo "<td><p>".myTruncate($row->body,20," ")."</p></td>";		
			echo "<td class=\"small\"><p>$row->store_number&nbsp;</p></td>";
			echo "<td class=\"small\"><p>$row->store_district&nbsp;</p></td>";
			echo "<td class=\"small\"><p>$row->store_region&nbsp;</p></td>";
			echo "<td class=\"small\"><p>".$t[$row->type][0]."&nbsp;</p></td>";
			echo "<td class=\"small\"><p><img align=\"absmiddle\"  src=\"images/".$u[$row->urgency][1].".gif\" />".$u[$row->urgency][0]."&nbsp;</p></td>";
			echo "<td class=\"small\"><p>$elapsed days since activity</p></td>";	
			echo "</tr>";
		}
		echo "</table>";
	}
?>

<?php if ($usergroup<2){ ?>
<form name="new" id="new" method="POST" action="view.php?workset=1&mode=<?=$mode?>" target="view">
<h2>Newest Requests</h2>
<?php dashboardRTable('new',0);?>
<input type="hidden" name="starter" value="<?=$workset[0]?>" />
</form>

<form name="recent" id="recent" method="POST" action="view.php?workset=1&mode=<?=$mode?>" target="view">
<h2>Recently Responded to by DM</h2>
<?php dashboardRTable('open',1);?>
<input type="hidden" name="starter" value="<?=$workset[0]?>" />
</form>

<?php } ?>

<?php if ($usergroup==2){ ?>
<form name="open" id="open" method="POST" action="view.php?workset=1&mode=<?=$mode?>" target="view">
<h2>Recent Open Requests</h2>
<?php dashboardRTable('open',0); ?>
<input type="hidden" name="starter" value="<?=$workset[0]?>" />
</form>
<?php } ?>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</div>
</body>
</html>
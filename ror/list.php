<?php
error_reporting(E_ALL ^ E_NOTICE);
include("../include/access.php");
include("../include/rt.php");
$show = $_GET['show'];
$mode = "ROR";
$filter = $_GET['filter'];
$modelabel = "Repair Order";
$pageheading = ucwords($_GET['show']);
if (($pageheading == "Open") and ($mode == "ROR")) {$pageheading = "Pending";}
$pageheading = $pageheading." ".$modelabel." Requests";
//Load types and urgencies
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
	var rts=new Array();

	window.onload = function() {
	document.getElementById('lhome').className='link';
	document.getElementById('lnew').className='link';
	document.getElementById('lopen').className='link';
	document.getElementById('lcompleted').className='link';
	document.getElementById('lreport').className='link';
	document.getElementById('lcreate').className='link';
	document.getElementById('l<?=$show?>').className='current';
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
<form name="workset" id="workset" method="POST" action="view.php?workset=1&mode=<?=$_GET['mode']?>" target="view">

<?php
echo "<h2><a class=\" vista\" href=\"javascript:expandAll('$uid','$show','$mode');\">+ Expand All</a></h2>";

dbConnect();
$distinct = array();						//Create distinct array
$workset = array();							//workset array
	//Create columns
	echo "<table width=\"100%\" class=\"sortable\" id=\"datarows\" cellspacing=\"0\" cellpadding=\"0\"><thead>";
	echo "<tr style=\"text-align:left;font-size:11px;\">";
	echo "<th align=\"center\">*</th>";
	echo "<th align=\"center\">*</th>";
	echo "<th>Location</th>";
	echo "<th>City</th>";
	echo "<th>ST</th>";
	echo "<th>#</th>";
	echo "<th>Dist.</th>";
	echo "<th>Reg.</th>";
	if ($_GET['show']=="completed") {$skipcounts = TRUE;}

if($_GET['mode']=="ROR") {

	$sql = "SELECT rt_rors.*,projects.*,UNIX_TIMESTAMP(rt_rors.creation) AS FORMATED_TIME ";
	//If the Vendor is logged in, the database will be filtered to show only requests where this vendor is assigned
	if ($usergroup == 3){$sql .= ",companies.company_id ";}
	$sql .= "FROM rt_rors, projects ";  
	//If the Vendor is logged in, dynamically insert the companies table into the FROM declaration
	if ($usergroup == 3){$sql .= ",companies ";}
	$sql .= "WHERE rt_rors.loc_key = projects.id ";
	//If the DM or RM is logged in, Filter the requests g1 is either going to be store_district or store_region depending on the login
	// and g0 is going to the be number , so if dm4 is logged in g1=store_district g0=4
	if ($usergroup == 2){$g = g2filter($uid);$sql .= "AND projects.$g[1] = $g[0] ";}
	//If the Vendor is logged in, the database will be filtered to show only requests where this vendor is assigned
	if ($usergroup == 3){$sql .= "AND rt_rors.vendor_key = companies.company_id AND companies.company_id = $usercompany ";}
	$sql .="AND rt_rors.status = '$show' ORDER BY creation DESC";

	$result = mysql_query($sql);
	if (!$result) {error("Error with database: ".mysql_error());}
	echo "<th>Type</th>";

} elseif($_GET['mode']=="FREQ") {
	$sql = "SELECT rt_freqs.*,projects.*,UNIX_TIMESTAMP(rt_freqs.creation) AS FORMATED_TIME ";
	
	//If the Vendor is logged in, dynamically insert the company_id field into the SELECT declaration
	if ($usergroup == 3){$sql .= ",companies.company_id ";}
	$sql .= "FROM rt_freqs, projects ";  
	
	//If the Vendor is logged in, dynamically insert the companies table into the FROM declaration
	if ($usergroup == 3){$sql .= ",companies ";}
	$sql .= "WHERE rt_freqs.loc_key = projects.id ";

	//Check to see if order status filter is set ($filter)
	if ($filter!="") {$sql .= "AND rt_freqs.order_status = '$filter' ";}

	//If the DM or RM is logged in, Filter the requests g1 is either going to be store_district or store_region depending on the login
	// and g0 is going to the be number , so if dm4 is logged in g1=store_district g0=4
	if ($usergroup == 2){$g = g2filter($uid);$sql .= "AND projects.$g[1] = $g[0] ";}
	
	//If the Vendor is logged in, the database will be filtered to show only requests where this vendor is assigned
	if ($usergroup == 3){$sql .= "AND rt_rors.vendor_key = companies.company_id AND companies.company_id = $usercompany ";}
	$sql .= "AND rt_freqs.status = '$show' ORDER BY creation DESC";	

	$result = mysql_query($sql);
	if (!$result) {error("Error with database: ".mysql_error());}
}
	echo "<th>Priority</th>";
	echo "<th>Last</th>";
	echo "</tr></thead>";

if (mysql_num_rows($result)>0){
	//Start iterating through rows
	while ($row = mysql_fetch_object($result)) {
		//add the id to a work set array
		$workset[]=$row->id;
		echo "<input type=\"hidden\" name=\"workset[]\" value=\"$row->id\" />";
		//Check if its been accounted for
		if (in_array($row->loc_key, $distinct)){continue;}
		$creation = "$row->FORMATED_TIME"; 

		if (!$skipcounts) {	//Conduct count query
			if($_GET['mode']=="ROR") {
				if ($usergroup == 3) {$count_sql = "select id from rt_rors where loc_key = $row->loc_key AND vendor_key = $usercompany AND status='".$_GET['show']."'";}
				else {$count_sql = "select id from rt_rors where loc_key = $row->loc_key AND status='".$_GET['show']."'";}
				$count_query = mysql_query($count_sql);
			} elseif($_GET['mode']=="FREQ") {
				if ($usergroup == 3) {$count_sql = "select id from rt_freqs where loc_key = $row->loc_key AND vendor_key = $usercompany AND status='".$_GET['show']."'";}
				else {$count_sql = "select id from rt_rors where loc_freqs = $row->loc_key AND status='".$_GET['show']."'";}
				$count_query = mysql_query("select id from rt_freqs where loc_key = $row->loc_key AND status = '".$_GET['show']."'");
			}
			$count = mysql_num_rows($count_query);
		} else {
			$count = "";
		}
		echo "<tr><script type=\"text/javascript\">rts.push(".$row->loc_key.");</script>";
		echo "<td class=\"star_store\">";
		if ($row->high_volume_store == 1) {echo "<span style=\"display:none;\">1</span> <img src=\"images/star.gif\" />";}
		echo "</td>";
		echo "<td class=\"star_store\">";
		if ($row->potential_remodel_store == 1) {echo "<span style=\"display:none;\">1</span> <img src=\"/images/config.gif\" />";}
		echo "</td>";		
		echo "<td><div id=\"box".$row->loc_key."_main\"><p><a href=\"javascript:ajax_do('rt_data.php?uid=$uid&s=$show&m=$mode&loc=".$row->loc_key."');\"><img src=\"images/plus.gif\" border=\"0\" /></a>&nbsp;";
		echo "<a href=\"#\" onClick=\"ajax_do('rt_data.php?uid=$uid&s=$show&m=$mode&loc=".$row->loc_key."');return false;\">".myTruncate($row->sitename,20, " ")."</a> <span class=\"count\">($count)</span>";
		echo "<a href=\"javascript:workit(".$row->id.",'workset');\"><img align=\"absmiddle\"  border=\"0\" src=\"images/start.gif\" /><a/>";
		echo "</p></div></td>";
		echo "<td class=\"small\"><div id=\"box".$row->loc_key."_city\"><p>$row->sitecity &nbsp;</p></div></td>";
		echo "<td class=\"small\"><div id=\"box".$row->loc_key."_state\"><p>$row->sitestate &nbsp;</p></div></td>";
		echo "<td class=\"small\"><div id=\"box".$row->loc_key."_store\"><p>$row->store_number&nbsp;</p></div></td>";
		echo "<td class=\"small\"><div id=\"box".$row->loc_key."_dist\"><p>$row->store_district&nbsp;</p></div></td>";
		echo "<td class=\"small\"><div id=\"box".$row->loc_key."_reg\"><p>$row->store_region&nbsp;</p></div></td>";
		if (!$skipcounts) {
			if($_GET['mode']=="ROR") {echo "<td class=\"small\"><div id=\"box".$row->loc_key."_type\"><p>".$t[$row->type][0]."&nbsp;</p></div></td>";}
			echo "<td class=\"small\"><div id=\"box".$row->loc_key."_urgency\"><p><img src=\"images/".$u[$row->urgency][1].".gif\" />".$u[$row->urgency][0]."&nbsp;</p></div></td>";
		} else {
			echo "<td><div id=\"box".$row->loc_key."_type\"></div></td><td><div id=\"box".$row->loc_key."_urgency\"></div></td>";
		}
		echo "<td class=\"small\"><div id=\"box".$row->loc_key."_date\"><p>".date("n/j/y g:ia",$creation)." &nbsp;</p></div></td>";	
		echo "</tr>";
		//Add to $distinct array so that this entrie does not get repeated
		$distinct[] = $row->loc_key;
	}
	echo "</table>";
} else {//if more than 0 rows
	echo "</table>";
	echo "<p>No requests to show...</p>";
}

?>
<input type="hidden" name="starter" value="<?=$workset[0]?>" />

</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</div>
 </body>
</html>

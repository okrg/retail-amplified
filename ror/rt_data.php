<?php
include("../include/db.php");
include("../include/common.php");
include("../include/rt.php");


dbConnect();
$sql = "select groupid, company_id from users where userid = '{$_GET['uid']}'";
$result = mysql_query($sql);
//Grab the user's full name for use in the page.
$usergroup = mysql_result($result,0,"groupid");
$usercompany = mysql_result($result,0, "company_id");

$id = $_GET['loc'];
$mode = $_GET['m'];
$status = $_GET['s'];

if (isset($_GET['reset'])) {
	if($_GET['reset']==1) {$rx=TRUE;}	
}else{
	$rx=FALSE;
}

//Load types and urgencies
$t = Types();
$u = Urgencies();

$container_id = "box".$id;

///////////////////////////////////////////////ROR block//////////////////////////////
if ($mode=="ROR") {

$sql="SELECT projects.sitename, projects.id AS project_id,
	rt_rors.id,
	rt_rors.loc_key,
	rt_rors.vendor_key,
	projects.sitecity,
	projects.sitestate,
	projects.store_number,
	projects.store_district,
	projects.store_region FROM rt_rors, projects WHERE rt_rors.loc_key = '$id' AND projects.id = rt_rors.loc_key ";

	//If the Vendor is logged in, the database will be filtered to show only requests where this vendor is assigned
//	if ($usergroup == 3){$sql .= "AND rt_rors.vendor_key = $usercompany ";}

	$sql .= "AND rt_rors.status = '$status' ORDER BY rt_rors.creation DESC";	


$result = mysql_query($sql);
if (!$result) {error("Error with database: ".mysql_error());exit;}
if (mysql_num_rows($result) == 0) {echo "Null";exit;}
$r = mysql_fetch_assoc($result);

if ($usergroup == 3) {$count_sql = "select id from rt_rors where loc_key = $id AND vendor_key = $usercompany AND status='$status'";}
else {$count_sql = "select id from rt_rors where loc_key = $id AND status='$status'";}
$count_query = mysql_query($count_sql);
$count = mysql_num_rows($count_query);

if (!$rx){
	$main = "<p><a href=\"#\" onClick=\"javascript:ajax_do('rt_data.php?uid=".$_GET['uid']."&m=$mode&s=$status&reset=1&loc=".$id."');return false;\"><img src=\"images/minus.gif\" border=\"0\" /></a>&nbsp;";
	$main .= "<a href=\"#\" onClick=\"javascript:ajax_do('rt_data.php?uid=".$_GET['uid']."&m=$mode&s=$status&reset=1&loc=".$id."');return false;\">".myTruncate($r['sitename'],30, " ")."</a> <span class=\"count\">($count)</span>";
	$main .= " <a href=\"javascript:workit(".$r['id'].",'workset');\"><img align=\"absmiddle\" border=\"0\" src=\"images/start.gif\" /><a/>";
	$main .= "<ul class=\"main\">";
} else {
	$main = "<p><a href=\"#\" onClick=\"javascript:ajax_do('rt_data.php?uid=".$_GET['uid']."&m=$mode&s=$status&loc=".$id."');return false;\"><img src=\"images/plus.gif\" border=\"0\" /></a>&nbsp;";
	$main .= "<a href=\"#\" onClick=\"javascript:ajax_do('rt_data.php?uid=".$_GET['uid']."&m=$mode&s=$status&loc=".$id."');return false;\">".myTruncate($r['sitename'],30, " ")."</a> <span class=\"count\">($count)</span>";
	$main .= " <a href=\"javascript:workit(".$r['id'].",'workset');\"><img align=\"absmiddle\"  border=\"0\" src=\"images/start.gif\" /><a/>";
	$main .= " </p>";
	$main .= "<ul class=\"main\">";
}

$city  = "<p>".$r['sitecity']."&nbsp;</p>";
$city .= "<ul class=\"city\">";
$state = "<p>".$r['sitestate']."&nbsp;</p>";
$state .= "<ul class=\"state\">";
$store = "<p>".$r['store_number']."&nbsp;</p>";
$store .= "<ul class=\"store\">";
$dist = "<p>".$r['store_district']."&nbsp;</p>";
$dist .= "<ul class=\"dist\">";
$reg = "<p>".$r['store_region']."&nbsp;</p>";
$reg .= "<ul class=\"reg\">";
$type = "<p>&nbsp;</p>";
$type .= "<ul class=\"type\">";
$urgency = "<p>&nbsp;</p>";
$urgency .= "<ul class=\"urgency\">";
$date = "<p>&nbsp;</p>";
$date .= "<ul class=\"date\">";

if (!$rx){

	if($usergroup == 3){$sql = "SELECT *,UNIX_TIMESTAMP(creation) AS FORMATED_TIME FROM rt_rors 
		WHERE loc_key = $id AND vendor_key = $usercompany AND status='$status' ORDER BY creation desc";}
	else {$sql = "SELECT *,UNIX_TIMESTAMP(creation) AS FORMATED_TIME FROM rt_rors 
		WHERE loc_key = $id AND status='$status' ORDER BY creation desc";}

	$result = mysql_query($sql);
	if (!$result) {error("Error with database: ".mysql_error());exit;}
	if (mysql_num_rows($result) == 0) {echo "Null";exit;}
	
	while ($row = mysql_fetch_object($result)){
		$creation = date("n/j/y g:ia",$row->FORMATED_TIME);
		
		$sqly = "select id from rt_ror_responses where parent_key = ".$row->id."";
		$resulty = mysql_query($sqly);
		if (!$resulty) {error("Error with database: ".mysql_error());exit;}
		$count = mysql_num_rows($resulty);
		if ($count > 0) {$count="<span class=\"msgcount\">$count</span>";}else {$count="";}
		
		if ($row->read == 0) {$read = "bold";} else {$read = "norm";}
		
		$body = myTruncate($row->body,30," ");														//Truncates body
		$main .= "<li class=\"$read main\">";
		//$main .= "<input name=\"workbox[]\" type=\"checkbox\" value=\"".$row->id."\" />";
		$main .= "<a class=\"items\" href=\"javascript:ViewPop('view.php?mode=$mode&id=$row->id','view');\">$body</a>&nbsp;$count</li>";	//Body (truncated)	
		$city .= "<li class=\"$read city\">&nbsp;</li>";
		$state .= "<li class=\"$read state\">&nbsp;</li>";
		$store .= "<li class=\"$read store\">&nbsp;</li>";
		$dist .= "<li class=\"$read dist\">&nbsp;</li>";
		$reg .= "<li class=\"$read reg\">&nbsp;</li>";
		$type .= "<li class=\"$read type\">".$t[$row->type][0]."</li>";						//Type
		$urgency .= "<li class=\"$read urgency\"><img src=\"images/".$u[$row->urgency][1].".gif\" />&nbsp;".$u[$row->urgency][0]."</li>";				//Urgency
		$date .= "<li class=\"$read date\">$creation</li>";						//Creation Date / Time
		}
}
$main .= "</ul>";
$city .= "</ul>";
$state .= "</ul>";
$store .= "</ul>";
$dist .= "</ul>";
$reg .= "</ul>";
$type .= "</ul>";
$urgency .= "</ul>";
$date .= "</ul>";		

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
$city = EscapeChars($city);
$state = EscapeChars($state);
$store = EscapeChars($store);
$dist = EscapeChars($dist);
$reg = EscapeChars($reg);
$type = EscapeChars($type);
$urgency = EscapeChars($urgency);
$date = EscapeChars($date);

?>
document.getElementById('<?php echo $container_id."_main";?>').innerHTML='<?php echo $main;?>';
document.getElementById('<?php echo $container_id."_city";?>').innerHTML='<?php echo $city;?>';
document.getElementById('<?php echo $container_id."_state";?>').innerHTML='<?php echo $state;?>';
document.getElementById('<?php echo $container_id."_store";?>').innerHTML='<?php echo $store;?>';
document.getElementById('<?php echo $container_id."_dist";?>').innerHTML='<?php echo $dist;?>';
document.getElementById('<?php echo $container_id."_reg";?>').innerHTML='<?php echo $reg;?>';
document.getElementById('<?php echo $container_id."_type";?>').innerHTML='<?php echo $type;?>';
document.getElementById('<?php echo $container_id."_urgency";?>').innerHTML='<?php echo $urgency;?>';
document.getElementById('<?php echo $container_id."_date";?>').innerHTML='<?php echo $date;?>';
<?php 


///////////////////////////////////////////////End ROR block//////////////////////////////
}elseif ($mode=="FREQ") {

///////////////////////////////////////////////Start FREQ block////////////////////////////// 	

$sql="SELECT projects.sitename,
	rt_freqs.id,
	rt_freqs.loc_key,
	rt_freqs.qty,
	rt_freqs.fixture_key,
	rt_freqs.order_status,
	rt_freqs.creation, 
	projects.sitecity,
	projects.sitestate,
	projects.store_number,
	projects.store_district,
	projects.store_region 
	FROM rt_freqs, projects 
	WHERE rt_freqs.loc_key = projects.id 
	AND rt_freqs.loc_key= $id 
	AND rt_freqs.status = '$status' 
	ORDER BY rt_freqs.creation DESC";

$result = mysql_query($sql);
if (!$result) {error("Error with database: ".mysql_error());exit;}
if (mysql_num_rows($result) == 0) {echo "Null";exit;}
$r = mysql_fetch_assoc($result);

$count_query = mysql_query("select id from rt_freqs where loc_key = $id AND status='$status'");
$count = mysql_num_rows($count_query);

if (!$rx){
	$main = "<p><a href=\"#\" onClick=\"javascript:ajax_do('rt_data.php?uid=".$_GET['uid']."&m=$mode&s=$status&reset=1&loc=".$id."');return false;\"><img src=\"images/minus.gif\" border=\"0\" /></a>&nbsp;";
	$main .= "<a href=\"#\" onClick=\"javascript:ajax_do('rt_data.php?uid=".$_GET['uid']."&m=$mode&s=$status&reset=1&loc=".$id."');return false;\">".myTruncate($r['sitename'],30, " ")."</a> <span class=\"count\">($count)</span>";
	$main .= " <a href=\"javascript:workit(".$r['id'].",'workset');\"><img align=\"absmiddle\" border=\"0\" src=\"images/start.gif\" /><a/>";
	$main .= "<ul class=\"main\">";
} else {
	$main = "<p><a href=\"#\" onClick=\"javascript:ajax_do('rt_data.php?uid=".$_GET['uid']."&m=$mode&s=$status&loc=".$id."');return false;\"><img src=\"images/plus.gif\" border=\"0\" /></a>&nbsp;";
	$main .= "<a href=\"#\" onClick=\"javascript:ajax_do('rt_data.php?uid=".$_GET['uid']."&m=$mode&s=$status&loc=".$id."');return false;\">".myTruncate($r['sitename'],30, " ")."</a> <span class=\"count\">($count)</span>";
	$main .= " <a href=\"javascript:workit(".$r['id'].",'workset');\"><img align=\"absmiddle\"  border=\"0\" src=\"images/start.gif\" /><a/>";
	$main .= " </p>";
	$main .= "<ul class=\"main\">";
}

$city  = "<p>".$r['sitecity']."&nbsp;</p>";
$city .= "<ul class=\"city\">";
$state = "<p>".$r['sitestate']."&nbsp;</p>";
$state .= "<ul class=\"state\">";
$store = "<p>".$r['store_number']."&nbsp;</p>";
$store .= "<ul class=\"store\">";
$dist = "<p>".$r['store_district']."&nbsp;</p>";
$dist .= "<ul class=\"dist\">";
$reg = "<p>".$r['store_region']."&nbsp;</p>";
$reg .= "<ul class=\"reg\">";
$urgency = "<p>&nbsp;</p>";
$urgency .= "<ul class=\"urgency\">";
$date = "<p>&nbsp;</p>";
$date .= "<ul class=\"date\">";

if (!$rx){
	$sql = "SELECT rt_freqs.*,fixture_key.id AS fixture_id ,fixture_key.name,fixture_key.desc,
			UNIX_TIMESTAMP(rt_freqs.creation) AS FORMATED_TIME  
			FROM rt_freqs, fixture_key 
			WHERE fixture_key.id = rt_freqs.fixture_key 
			AND rt_freqs.loc_key = $id 
			AND rt_freqs.status='$status' 
			ORDER BY rt_freqs.creation desc";

	$result = mysql_query($sql);
	if (!$result) {error("Error with database: ".mysql_error());exit;}
	if (mysql_num_rows($result) == 0) {echo "Null";exit;}
	
	while ($row = mysql_fetch_object($result)){
		$sqly = "select * from rt_freq_responses where parent_key = ".$row->opk." AND fixture_key = $row->fixture_key";
		$resulty = mysql_query($sqly);
		if (!$resulty) {error("Error with database: ".mysql_error());exit;}
		$count = mysql_num_rows($resulty);
		if ($count > 0) {
			$count="<span class=\"msgcount\">$count</span>";
		}else {
			$count="";
		}
		
		$creation = date("n/j/y g:ia",$row->FORMATED_TIME);
		if ($row->read == 0) {$read = "bold";} else {$read = "norm";}
		
		$body = $row->name." ".ucwords(strtolower(myTruncate($row->desc,30," ")));														//Truncates body
		$main .= "<li class=\"$read main\">";
		//$main .= "<input name=\"workbox[]\" type=\"checkbox\" value=\"".$row->id."\" />";
		$main .= "<a class=\"items\" href=\"javascript:ViewPop('view.php?mode=$mode&id=$row->id','view');\">$body</a>&nbsp;$count</li>";	//Body (truncated)	
		$city .= "<li class=\"$read\">Qty: $row->qty </li>";
		$state .= "<li class=\"$read\">".ucwords($row->order_status)."&nbsp;</li>";
		$store .= "<li class=\"$read\">&nbsp;</li>";
		$dist .= "<li class=\"$read\">&nbsp;</li>";
		$reg .= "<li class=\"$read\">&nbsp;</li>";
		$urgency .= "<li class=\"$read\"><img src=\"images/".$u[$row->urgency][1].".gif\" />&nbsp;".$u[$row->urgency][0]."</li>";				//Urgency
		$date .= "<li class=\"$read\">$creation</li>";						//Creation Date / Time
		}
}
$main .= "</ul>";
$city .= "</ul>";
$state .= "</ul>";
$store .= "</ul>";
$dist .= "</ul>";
$reg .= "</ul>";
$urgency .= "</ul>";
$date .= "</ul>";		

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
$city = EscapeChars($city);
$state = EscapeChars($state);
$store = EscapeChars($store);
$dist = EscapeChars($dist);
$reg = EscapeChars($reg);
$urgency = EscapeChars($urgency);
$date = EscapeChars($date);


?>
document.getElementById('<?php echo $container_id."_main";?>').innerHTML='<?php echo $main;?>';
document.getElementById('<?php echo $container_id."_city";?>').innerHTML='<?php echo $city;?>';
document.getElementById('<?php echo $container_id."_state";?>').innerHTML='<?php echo $state;?>';
document.getElementById('<?php echo $container_id."_store";?>').innerHTML='<?php echo $store;?>';
document.getElementById('<?php echo $container_id."_dist";?>').innerHTML='<?php echo $dist;?>';
document.getElementById('<?php echo $container_id."_reg";?>').innerHTML='<?php echo $reg;?>';
document.getElementById('<?php echo $container_id."_urgency";?>').innerHTML='<?php echo $urgency;?>';
document.getElementById('<?php echo $container_id."_date";?>').innerHTML='<?php echo $date;?>';

<?php ///////////////////////////////////////////////END FREQ block//////////////////////////////
 }
 ?>
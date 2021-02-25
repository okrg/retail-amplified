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

//Load types and urgencies
$t = Types();
$u = Urgencies();

$container_id = "box";

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
	projects.store_region FROM rt_rors, projects WHERE projects.store_number = '$id' AND projects.id = rt_rors.loc_key ";

	//If the Vendor is logged in, the database will be filtered to show only requests where this vendor is assigned
//	if ($usergroup == 3){$sql .= "AND rt_rors.vendor_key = $usercompany ";}

	$sql .= "AND rt_rors.status = '$status' ORDER BY rt_rors.creation DESC";	


$result = mysql_query($sql);
if (!$result) {error("Error with database: ".mysql_error());exit;}
if (mysql_num_rows($result) == 0) {echo "Null";exit;}
$r = mysql_fetch_assoc($result);
$loc_id = r["project_id"];
if ($usergroup == 3) {$count_sql = "select id from rt_rors where loc_key = $loc_id AND vendor_key = $usercompany AND status='$status'";}
else {$count_sql = "select id from rt_rors where loc_key = $loc_id AND status='$status'";}
$count_query = mysql_query($count_sql);
$count = mysql_num_rows($count_query);

$main = "<p>&nbsp;</p>";
$main .= "<ul class=\"main\">";
$type = "<p>&nbsp;</p>";
$type .= "<ul class=\"type\">";
$urgency = "<p>&nbsp;</p>";
$urgency .= "<ul class=\"urgency\">";
$date = "<p>&nbsp;</p>";
$date .= "<ul class=\"date\">";


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
	$type .= "<li class=\"$read type\">".$t[$row->type][0]."</li>";						//Type
	$urgency .= "<li class=\"$read urgency\"><img src=\"images/".$u[$row->urgency][1].".gif\" />&nbsp;".$u[$row->urgency][0]."</li>";				//Urgency
	$date .= "<li class=\"$read date\">$creation</li>";						//Creation Date / Time
	}

$main .= "</ul>";
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
$type = EscapeChars($type);
$urgency = EscapeChars($urgency);
$date = EscapeChars($date);

?>
document.getElementById('<?php echo $container_id."_main";?>').innerHTML='<?php echo $main;?>';
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

$main = "<p>&nbsp;</p>";
$main .= "<ul class=\"main\">";
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
		$urgency .= "<li class=\"$read\"><img src=\"images/".$u[$row->urgency][1].".gif\" />&nbsp;".$u[$row->urgency][0]."</li>";				//Urgency
		$date .= "<li class=\"$read\">$creation</li>";						//Creation Date / Time
		}
}
$main .= "</ul>";
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
$urgency = EscapeChars($urgency);
$date = EscapeChars($date);


?>
document.getElementById('<?php echo $container_id."_main";?>').innerHTML='<?php echo $main;?>';
document.getElementById('<?php echo $container_id."_urgency";?>').innerHTML='<?php echo $urgency;?>';
document.getElementById('<?php echo $container_id."_date";?>').innerHTML='<?php echo $date;?>';

<?php ///////////////////////////////////////////////END FREQ block//////////////////////////////
 }
 ?>
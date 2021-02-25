<?php
error_reporting(E_ALL ^ E_NOTICE);
include("../include/access.php");
include("../include/rt.php");

if (!isset($editok)):
$pageheading = "Create new request";

//Load types and urgencies
$t =Types();
$u = Urgencies();

//Determine what request info options will be shown
if ($usergroup <2) {
	$request_info = "<input type=\"text\" name=\"requestor\" class=\"text\" />";
	$request_info .= "&nbsp;&nbsp;
	<select name=\"request_method\">
						<option value=\"\">Request Method... </option>
						<option value=\"call\">Phone Call</option>
						<option value=\"fax\">Fax</option>
						<option value=\"email\">Email</option>
						<option value=\"email\">Corporate</option>
						<option value=\"other\">Other</option>
					</select>";



} else {
	$request_info = "$username <input type=\"hidden\" name=\"requestor\" value=\"$username\" /> <input type=\"hidden\" name=\"request_method\" value=\"web\" />";
	
}


//Determine what location options will be shown 
if ($usergroup < 2) {
	$location_options = "<input type=\"text\" name=\"location\" class=\"text\" />&nbsp;";
	$location_options .= "<a href=\"#\" onclick=\"ViewPop('key.php','key');return false;\">Key</a>";
} else {
	$user_uid_rank = substr($uid,0,2);
	$user_domain = substr($uid,2);
	if ($user_uid_rank == "dm") {
		$domain_value="District";
		$db_string = "store_district";
	} elseif ($user_uid_rank == "rm") {
		$domain_value="Region";
		$db_string="store_region";
	}
	$sql = "select id, sitenum, sitename, store_number from projects where $db_string = $user_domain order by store_number";
	//Execute filter on db!
	$result = mysql_query($sql);
	if (!$result){error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error());}
	$location_options = "";
	while ($row = mysql_fetch_array($result)){
		$id = $row["id"];
		$store_number = $row["store_number"];
		$sitename = $row["sitename"];
		$location_options .= "<label for=\"rad_$id\"><input type=\"radio\" name=\"location\" value=\"$store_number\" id=\"rad_$id\" onclick=\"changeLabel();\" />#$store_number $sitename</label>";
	}
}


$ror_types = "<select name=\"ror_type\"><option value=\"\" selected>Select Repair Type</option>";
foreach ($t as $k) {
	$key = array_search($k,$t);
	$ror_types .= "<option value=\"$key\">$k[0]</option>";
}
$ror_types .= "</select>";

$ror_urgency_options = "";
foreach ($u as $k) {
	$key = array_search($k,$u);
	if ($key=="30") {
		$checked = "checked ";
	} else {
		$checked = "";
	}
	$ror_urgency_options .= "<label class=\"urgency\" for=\"rad_$key\"><input type=\"radio\" name=\"ror_urgency\" value=\"$key\" id=\"rad_$key\" $checked/>$k[0]</label>";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>Request</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" media="all">
@import "rt.css";
</style>
<script type="text/javascript" src="rt.js"></script>
<script type="text/javascript" src="multifile.js"></script>
<script type="text/javascript">
	window.onload = function() {
	document.getElementById('lhome').className='link';
	document.getElementById('lnew').className='link';
	document.getElementById('lopen').className='link';
	document.getElementById('lcompleted').className='link';
	document.getElementById('lreport').className='link';
	document.getElementById('lcreate').className='link';
	document.getElementById('lcreate').className='current';
	document.getElementById('progress').style.visibility = "hidden";
}
</script>

 <script type="text/javascript"><!--//--><![CDATA[//><!--
 var d = document;
 
 function changeLabel(){ if(d.getElementsByTagName){
   var testForm = d.getElementById("request");
   var inputs = testForm.location; 
   var labels = testForm.getElementsByTagName("label");
   for(x=0;x<inputs.length;x++){
     if(inputs[x].checked == true) {
      labelObj = labels[x]
      labelObj.style.border = "1px dashed #CCC" // and/or
      labelObj.style.color = "red" // and/or
      labelObj.style.fontWeight = "bold"
     } else {
      labelObj = labels[x]
      labelObj.style.border = "none" // and/or
      labelObj.style.color = "#000" // and/or
      labelObj.style.fontWeight = "normal"
     } 
   }  
 }else{return}} 
 ///--><!]]></script>

</head>
<body>
<a name="top"></a>
<div id="progress" style="visibility: visible;">Loading...</div>
<div id="menu">
  <?php include("ror-menu.php");?>
</div>
<h1>
  <?=$pageheading?>
</h1>
<div id="maincontainer">
  <form name="request" id="request" method="POST" action="request.php" enctype="multipart/form-data">
    <table cellpadding="2" cellspacing="2" id="requestor">
      <tr>
        <td class="col1"><strong>Requested By</strong></td>
        <td><?=$request_info?></td>
      </tr>
      <tr class="row1">
        <td class="col1"><strong>Store</strong></td>
        <td class="locations"><?=$location_options?></td>
      </tr>
      <tr class="row2">
        <td class="col1"></td>
        <td><?=$ror_types?>
                <br />
                <strong>Priority</strong>
                <?=$ror_urgency_options?></td>
      </tr>
      <tr>
        <td class="col1"><strong>Store<br />
          Contact</strong></td>
        <td><input name="extra_contact" size="40" class="text" /></td>
      </tr>
      <tr>
        <td class="col1"><strong>Request<br />
          Comments</strong><br />
          <small>EXPLAIN IN DETAIL</small></td>
        <td><textarea name="body" cols="57" rows="10"></textarea></td>
      </tr>
      <tr>
      <td class="col1"><strong>Attach Files</strong></td>
      <td>
			<input id="my_file_element" type="file" name="file_1" >&nbsp;<small>(Up to 5 max)</small><br />
			<div id="files_list"></div>
			<script>
			<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->
			var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 5 );
			<!-- Pass in the file element -->
			multi_selector.addElement( document.getElementById( 'my_file_element' ) );
			</script>

      </tr>
      <tr>
        <td colspan="2"><table id="navigation">
            <tr>
              <td><input name="editok" type="submit" value="Submit"></td>
            </tr>
          </table></td>
      </tr>
    </table>
  </form>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</div>
</body>
</html>
<?php
else:
	dbConnect();
//Validate against null values
	if ($_POST['location'] == '') {error("You must select a store location from the list before proceeding.");}
	if ($_POST['body'] == '') {error("You must write something in the request area before proceeding");}
	if ($_POST['requestor'] == '') {error("You must specify who requested this before proceeding");}
	if ($_POST['request_method'] == '') {error("You must select a request method before proceeding");}


	$body = $_POST['body'];
	$extra_contact = $_POST['extra_contact'];
	$location = $_POST['location'];

//determine the location id by cross checking against the inserted number and getting the oldest one only
	$sql = "select id from projects where store_number = $location order by dateadded limit 1";
	$result = mysql_query($sql);
	if (!$result){ error("That store number was not found, please check and try again.");}
	$id = mysql_result($result,0);

	$t =Types();//Load types and urgencies
	$u = Urgencies();
	if ($_POST['ror_urgency'] == "") {error("You must select a priority before proceeding");}
	if ($_POST['ror_type'] == "") {error("You must select a type before proceeding");}
	$new_urgency=$_POST['ror_urgency'];
	$new_type=$_POST['ror_type'];
	$type = $t[$new_type][0];
	$urgency = $u[$new_urgency][0];
	
	$db = "rt_rors";


// Get store region and district based on submited $new_location
	$sql = "select * from projects where id=$id";
	$results = mysql_query($sql);
	$location_name = mysql_result($results,0,"sitename");
	$store_state = mysql_result($results, 0, "sitestate");
	$store_district= mysql_result($results,0,"store_district");
	$store_region= mysql_result($results,0,"store_region");
	$store_number= mysql_result($results,0,"store_number");

// Generate a tracking number for documentation
//$datevar is the current month date year, a dash char, and the time (in 24h format, the minutes, seconds) all with leading zeros
	$datevar = date("mdy-His"); 
	$tracking = $store_number."-".$datevar;


////Call mail script
	$body = stripslashes($body);
//	
	$subjectline = "ROR: #$store_number $store_state $type $tracking";
	$notice_text = "$username has issued a new repair order request: \n\nLocation: #$store_number $store_state $location_name \nWork # $tracking"; 
	$notice_text .= "\nType: $type \nPriority: $urgency\n";
//
	$link = "http://construction.charlotte-russe.com/ror/ror-home.php";
//		
	$notice_text .= "\nRequest Details: $body \n\n";
//
//	$addresses .= "srobbins@charlotte-russe.com, ";
//	$addresses .= "LUnderwood@charlotte-russe.com, ";
//	$addresses .= "Brianna.Judd@charlotterusse.com, ";
//	$addresses .= "JHammond@charlotte-russe.com, ";
//	$addresses .= "tarietta@charlotte-russe.com, ";
//	$addresses .= "brakzilla@gmail.com";
//
//	mail($addresses, $subjectline, $notice_text, "From:Collaboration Network <no-reply@charlotte-russe.com>");
//
	//Add to report
	$summary_msg .= "<h2>The following message will be e-mailed to corporate staff</h2>";
	$summary_msg .= "<div style=\"border:1px #ddd solid;background:#eee;padding:10px;\"><pre>$notice_text</pre></div>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>Confirm Request</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" media="all">
@import "rt.css";
</style>
<script type="text/javascript" src="rt.js"></script>
</head>
<body>
<a name="top"></a>
<h1>:: Confirm Request</h1>
<div id="maincontainer">
  <div id="content">
    <?php
	$storenum = $_POST['location'];
	$loc_res = mysql_query("select id from projects where store_number = $storenum");
	$loc = mysql_result($loc_res, 0, "id");

if($usergroup == 3){$sql = "SELECT rt_rors.*,UNIX_TIMESTAMP(rt_rors.creation) AS FORMATED_TIME FROM rt_rors 
		WHERE rt_rors.loc_key = $loc AND rt_rors.type = $new_type AND rt_rors.vendor_key = $usercompany AND rt_rors.status!='completed' ORDER BY rt_rors.creation desc";}

else {$sql = "SELECT rt_rors.*,UNIX_TIMESTAMP(rt_rors.creation) AS FORMATED_TIME FROM rt_rors 
		WHERE rt_rors.loc_key = $loc AND rt_rors.type = $new_type AND rt_rors.status!='completed' ORDER BY rt_rors.creation desc";}

	$result = mysql_query($sql);
	if (!$result) {error("Error with database: ".mysql_error());exit;}

	if (mysql_num_rows($result) == 0) {
		echo "<h2>No open requests currently open for this store.</h2>";
		echo "<img src=\"images/confirm_ok.jpg\" />";
		echo "<script type=\"text/javascript\">";
		echo "setTimeout('document.submit_form.submit()',15000);";
		echo "</script>";		
	}else{
		echo "<h2>Can you confirm that this is not a duplicate request?</h2>";
		echo "<img src=\"images/confirm_wait.jpg\" />";
	
		echo "<table id=\"datarows\" style=\"margin:0 10px;background:#eee;\">";
		echo "<th colspan=\"2\">Current Requests for this location</th>";
		while ($row = mysql_fetch_object($result)){
		$creation = date("n/j/y g:ia",$row->FORMATED_TIME);
		$sqly = "select id from rt_ror_responses where parent_key = ".$row->id."";
		$resulty = mysql_query($sqly);
		if (!$resulty) {error("Error with database: ".mysql_error());exit;}
		$count = mysql_num_rows($resulty);
		if ($count > 0) {$count="<span class=\"msgcount\">$count</span>";}else {$count="";}
		
		if ($row->read == 0) {$read = "bold";} else {$read = "norm";}

		$body = myTruncate($row->body,60," ");														//Truncates body
		echo "<tr>";
		echo "<td>$body</td>";	//Body (truncated)	
		echo "<td>".$t[$row->type][0]."</td>";																//Type
		echo "<td><img src=\"images/".$u[$row->urgency][1].".gif\" />&nbsp;".$u[$row->urgency][0]."</td>";	//Urgency
		echo "<td>$creation</td>";																			//Creation Date / Time
		echo "</tr>";
		}
	echo "</table>";
	}

?>
    <form action="submit_request.php" method="post" name="submit_form">
      <input type="hidden" name="location" value="<?=$_POST['location']?>" />
      <input type="hidden" name="body" value="<?=$_POST['body']?>" />
      <input type="hidden" name="extra_contact" value="<?=$_POST['extra_contact']?>" />
      <input type="hidden" name="options" value="ror" />
      <input type="hidden" name="ror_urgency" value="<?=$_POST['ror_urgency']?>" />
      <input type="hidden" name="ror_type" value="<?=$_POST['ror_type']?>" />
      <input type="hidden" name="requestor" value="<?=$_POST['requestor']?>" />
      <input type="hidden" name="request_method" value="<?=$_POST['request_method']?>" />


        <input type="submit" value="Continue Request Submission" />&nbsp;&nbsp;		<a href="#" onClick="history.back()">Cancel</a>
        
    </form>
    <div class="databox">
      <p>
        <?=$summary_msg?>
      </p>
    </div>
  </div>
</div>
</body>
</html>
<?php
endif;
?>

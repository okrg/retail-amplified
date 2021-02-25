<?php
error_reporting(E_ALL ^ E_NOTICE);
include("../include/access.php");
include("../include/rt.php");

	dbConnect();
//Validate against null values
	if ($_POST['location'] == '') {error("You must select a store location from the list before proceeding.");}
	if ($_POST['body'] == '') {error("You must write something in the request area before proceeding");}
	$body = $_POST['body'];
	$extra_contact = $_POST['extra_contact'];
	$location = $_POST['location'];

//determine the location id by cross checking against the inserted number and getting the oldest one only
	$sql = "select id from projects where store_number = $location order by dateadded limit 1";
	$result = mysql_query($sql);
	if (!$result){ error("That store number was not found, please check and try again.");}
	$id = mysql_result($result,0);



	if ($_POST['options'] == "") {error("You must select a request type before proceeding");}
		
	if ($_POST['options'] == "ror") {	
		$t =Types();//Load types and urgencies
		$u = Urgencies();
		if ($_POST['ror_urgency'] == "") {error("You must select a priority before proceeding");}
		if ($_POST['ror_type'] == "") {error("You must select a priority before proceeding");}
		$new_urgency=$_POST['ror_urgency'];
		$new_type=$_POST['ror_type'];
		$type = $t[$new_type][0];
		$urgency = $u[$new_urgency][0];
		
		$db = "rt_rors";
	}
	
	if ($_POST['options'] == "freq") {
		if ($_POST['freq_urgency'] == "") {error("You must select a priority before proceeding");}
		if ($_POST['fixture'] == "") {error("You must select a priority before proceeding");}
		if ($_POST['qty'] == "") {error("You must select a qty before proceeding");}
		
		$u = Urgencies();//Load fixtures and urgencies
		
		$new_urgency=$_POST['freq_urgency'];
		$urgency = $u[$new_urgency][0];
		
		$qty = $_POST['qty'];
		
		$new_fixture=$_POST['fixture'];
		
		$fixsql = "select * from fixture_key where id = $new_fixture";
		$fixres = mysql_query($fixsql);
		$fixture_name = mysql_result($fixres,0,"name");
		$fixture_desc = mysql_result($fixres,0,"desc");
		
		if (isset($_POST['replacement'])){
			$new_type = 1;
		}else{
			$new_type = 0;
		}
		$db = "rt_freqs";
		
	}




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

//Set the sql statment..to save the request into the right db
	$sql = "insert into $db set "; 
	if ($_POST['options'] == "ror") {$sql .= "type='$new_type',";}
	if ($_POST['options'] == "freq") {$sql .= "qty=$qty,order_status='pending',replacement='$new_type',fixture_key='$new_fixture',";}			
	$sql .= "loc_key = '$id',
			urgency = '$new_urgency',
			status = 'new',
			extra_contact = '$extra_contact',
			body = '$body',
			author_key = '$unique_user_id',
			requestor = '".$_POST['requestor']."',
			requestmethod = '".$_POST['request_method']."',
			tracking = '$tracking',
			creation = NOW()";
	if (!mysql_query($sql)) {error("A database error occured: " . mysql_error());}

//write opk key 
	if ($_POST['options'] == "freq") {
		$last = mysql_insert_id();
		if (!mysql_query("update rt_freqs set opk = $last where id = $last")) {error("A database error occured: " . mysql_error());}
	}

//Call mail script
	$body = stripslashes($body);
	
	$addresses = "";
	if ($_POST['options'] == "ror") {
		//$addresses .= "srobbins@charlotte-russe.com, ";
		//$addresses .= "jhammond@charlotte-russe.com, ";
		//$addresses .= "lunderwood@charlotte-russe.com, ";
		//$addresses .= "tarietta@charlotte-russe.com, ";
		$subjectline = "ROR: #$store_number $store_state $new_type $tracking";
		$notice_text = "$username has issued a new repair order request: \n\nLocation: #$store_number $store_state $location_name \nWork # $tracking"; 
		$notice_text .= "\nType: $type \nPriority: $urgency\n";
	}
	/*
	if ($_POST['options'] == "freq") {
		$addresses .= "elias@charlotte-russe.com, ";
		$addresses .= "amassey@charlotte-russe.com, ";
		$subjectline = "FREQ: #$store_number $store_state $fixture_name $tracking";
		$notice_text = "$username has issued a new fixture request: \n\nLocation: #$store_number $store_state $location_name \nWork # $tracking";
		$notice_text .= " \nFixture: $fixture_name - $fixture_desc";
		$notice_text .= " \nQuantity: $qty";
		$notice_text .= "\nPriority: $urgency\n";
		
	}
	*/
	$addresses .= "brakzilla@gmail.com";
	$link = "http://construction.damphost.com/ror/ror-home.php";
		
	$notice_text .= "\nRequest: $body \n\nUse this link to access the request and issue a response: $link \nThis was an automated message.\nhttp:/"."/construction.damphost.com";
	
	//mail($addresses, $subjectline, $notice_text, "From:Collaboration Network <no-reply@charlotte-russe.com>");

	//Add to report
	$summary_msg .= "<p>The following message was e-mailed to corporate staff:</p>";
	$summary_msg .= "<div style=\"border:1px #ddd solid;background:#eee;padding:10px;\"><pre>$notice_text</pre></div>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>Confirm Request</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" media="all">@import "rt.css";</style>
<script type="text/javascript" src="rt.js"></script>
</head>
<body>
<a name="top"></a>
<h1>:: Message Sent</h1>
<div id="maincontainer">
	<div id="content">
		<div class="databox">
		 	<p><?=$summary_msg?></p>
			<p>[<a href="/ror/ror-home.php">Return to home page</a>]</p>
		</div>
	</div>
</div>
</body>
</html>
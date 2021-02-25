<?php

//Figure out what addresses to send to 
//Fetch all corp email address within the Facilities domain
//$sql = "select email from users where company_id=4";
//$result = mysql_query($sql);	
//while($row = mysql_fetch_array($result))
//{ 
//	if ($row["email"]=="")
//		continue;	
//	$addresses .= $row["email"]; 
//	$addresses .= ", ";	
//}

// Set up so that only certain facilities staff only get requests from their region

//if ($store_region <= 3) {
	$addresses .= "srobbins@charlotte-russe.com, ";
//}

//if ($store_region >= 4) {
//	$addresses .= "apenar@charlotte-russe.com, ";
//}
$addresses .= "LUnderwood@charlotte-russe.com, ";
$addresses .= "Brianna.Judd@charlotterusse.com, ";
$addresses .= "JHammond@charlotte-russe.com, ";
$addresses .= "tarietta@charlotte-russe.com, ";
$addresses .= "brakzilla@gmail.com";
$subjectline = "ROR: #$store_number $store_state $new_type $tracking";
$link = "http://construction.charlotte-russe.com/index.php?page=admin-g2";
$notice_text = "Greetings,

$username has issued a new repair order request:

Location: #$store_number $store_state $location_name
Work # $tracking
Type: $new_type
Priority: $new_priority

Request: $new_body


Use this link to access the request and issue a response:
$link

This was an automated message.
http:/"."/construction.charlotte-russe.com";

mail($addresses, $subjectline, $notice_text, "From:Collaboration Network <no-reply@charlotte-russe.com>");

?>
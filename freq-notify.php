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
$result = mysql_query("select * from fixture_cart inner join fixture_key on fixture_cart.itemId = fixture_key.id where fixture_cart.cookieId = '" . GetCartId() . "' order by fixture_key.name asc");
while($row = mysql_fetch_array($result)) {
$row['desc'] = str_replace(chr(13), "",$row['desc']);
$row['desc'] = str_replace(chr(10), "",$row['desc']);
$request_text .= "Item: ".$row['name']." > Desc: ".$row['desc']." > Qty: ".$row['qty'];
if ($row['addBool']==1) {
	$request_text .= " > Add\n";
	} else if ($row['addbool']==0) {
	$request_text .= " > Replace\n";
	}

}

$type_query = mysql_query("select type_of_store from projects where id=".$_POST['new_location']);
$type_of_store = mysql_result($type_query, 0, "type_of_store");

$addresses .= "brakzilla@gmail.com, amassey@charlotte-russe.com";
$subjectline = "FREQ: #$locObj->store_number $locObj->sitestate $locObj->sitename";
$link = "http://construction.charlotte-russe.com/index.php?page=admin-freq";
$notice_text = "Greetings,

$username has issued a new fixture order request:

Location: #$locObj->store_number $locObj->sitestate $locObj->sitename
Type of Store: $type_of_store

$request_text

$mannequin_report
Priority: ".$_POST['new_priority']."
Comments: ".$_POST['new_comment']."

Use this link to access the request and issue a response:
$link

This was an automated message.
http:/"."/construction.charlotte-russe.com";

mail($addresses, $subjectline, $notice_text, "From:Collaboration Network <no-reply@charlotte-russe.com>");

?>
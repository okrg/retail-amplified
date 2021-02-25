<?php
$addresses .= ", brakzilla@gmail.com";
$subjectline = "RE: ROR: $chainname #$si_obj->store_number $ntype $tracking";
$link = "http://construction.charlotte-russe.com/";
$notice_text = "Greetings,

$username has issued a response:

Location: #$si_obj->store_number
Work # $si_obj->tracking

Response: $new_body
PO #: $new_po_num

Original Message: $si_obj->body

This was an automated message.
http:/"."/construction.charlotte-russe.com";

mail($addresses, $subjectline, $notice_text, "From:Collaboration Network <no-reply@charlotte-russe.com>");

//Write Vendor response if applicable
//Attach spec vendor contact address
//if (isset($spec_vendor_contact)) {
//	$addresses = $spec_vendor_contact;
//	$addresses .= "brakzilla@gmail.com";
//	$notice_text = "Greetings,
//	$addresses
//	$username has issued a new repair request:
//	
//	Location: #$si_obj->store_number
//	Work # $si_obj->tracking
//
//	Comments: $spec_vendor_comments
//	PO #: $new_po_num
//	
//	Original Request from Store: $si_obj->body
//	This was an automated message.
//	http:/"."/construction.charlotte-russe.com";
//	
//$addresses ="brakzilla@gmail.com";
//mail($addresses, $subjectline, $notice_text, "From:Collaboration Network <no-reply@charlotte-russe.com>");
//}

?>
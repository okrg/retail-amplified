<?php
//Create tinyurl with the supplied $link
//Insert an entry into the tinyurl table and take the last entry id and use that in the email $link
$sql = "insert into tinyurl set url = '$link'";
if (!mysql_query($sql)) {
	echo "database error trying to make tinyurl!".mysql_error();
} else {
	$link = "http://construction.charlotte-russe.com/t.php?u=".mysql_insert_id();
}

if ($invite_select_vendors) {
	//Fetch all SELECTED vendor addresses for that upload
	foreach($_POST['vendors'] as $companyID) {
		$sql = "select email from users where company_id = $companyID and active = 1";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_array($result)) {
			if ($row["email"]=="")
				continue;
			$addresses .= $row["email"];
			$addresses .= ", ";
		}//while row=mysql_fetch
	} //foreach(vendorarray as vendorid)
} else { 
	if ($invite_core_only) { //if this flag was set, then only e-mail to select few
		$addresses = "ltoerien@charlotte-russe.com, rshepp@charlotte-russe.com, gadamson@charlotte-russe.com, ralphr@charlotte-russe.com";
	} else {//Fetch all corp email address
		$sql = "select email, company_id from users where company_id = 1 and active = 1";
		$result = mysql_query($sql);	
		while($row = mysql_fetch_array($result)){ 
			if ($row["email"]=="")
				continue;
			$addresses .= $row["email"]; 
			$addresses .= ", ";
		}
	} //Core only
} //if-else 

if ($invite_vendors) {
	//Fetch all vendor addresses attached to that project
	$sql = "select companyarray from projects where id = $id";
	$result = mysql_query($sql);
	$companyarray = mysql_result($result,0,"companyarray");
	$companyarray = unserialize($companyarray);
	foreach($companyarray as $companyID) {
		if ($companyID == 1) {continue;} //Ignore re-adding those from company = 1 since they sometimes me be selected in the list of vendors... but are already included in the distro via the above code
		$sql = "select email from users where company_id = $companyID and active = 1";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_array($result)) {
			if ($row["email"]=="")
				continue;
			$addresses .= $row["email"];
			$addresses .= ", ";
		}//while row=mysql_fetch
	} //foreach(vendorarray as vendorid)
} //if invite vendors?

$notice_text = "Greetings, 
$message

For the the following project: $project

Comments: $comments

Use this link: $link

This was an automated message.
http:/"."/construction.charlotte-russe.com";
if($newfolder) {
	$subject = $project . ' - ' . $newfolder;
} else {
	$subject = $project;
}

mail($addresses, $subject, $notice_text, "From:Collaboration Network <no-reply@charlotte-russe.com>");
//mail("rolando.garcia@gmail.com", $subject, $notice_text, "From:Collaboration Network <no-reply@charlotte-russe.com>");
?>
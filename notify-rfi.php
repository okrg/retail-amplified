<?php
//Get the vendor array for this project
$query = "SELECT * FROM projects WHERE id = $project_id";
$result = mysql_query($query) or die ("no query");		
$companyarray = unserialize(mysql_result($result, 0, "companyarray"));

foreach($companyarray as $vendor_company_id) {
	$result = mysql_query("SELECT * FROM companies where company_id = $vendor_company_id");
	if(mysql_result($result, 0, "gc") == 1) { $rfi_vendors[] = $vendor_company_id; }
	if(mysql_result($result, 0, "architect") == 1) { $rfi_vendors[] = $vendor_company_id; }
	

}

//merge these vendors with the added vendors	
if(is_array($_POST['new_rfi_vendor'])) {
	$all_rfi_vendors = array_merge($rfi_vendors,$_POST['new_rfi_vendor']);
} else {
	$all_rfi_vendors = $rfi_vendors;
}

foreach($all_rfi_vendors as $rfi_vendor) {
	$result = mysql_query("SELECT email FROM users where company_id = $rfi_vendor");
	while ($row = mysql_fetch_array($result)) {
		$addresses[] = $row['email'];
	}
}


//Check for notify reply tag 
if ($notify_reply_tag) {
	//Its a reply, so send to parent author id
	$sql = "select email from users where id = $parent_author_id";
	$result = mysql_query($sql);
    $addresses[] = mysql_result($result, 0, "email");



}

$cr_pm_flag = FALSE;
//get project contact emails
$sql = "SELECT cr_project_manager_email FROM projects WHERE id = $project_id and cr_project_manager_email IS NOT NULL";
$result = mysql_query($sql);
if(mysql_result($result, 0, "cr_project_manager_email") != "") {
	$addresses[] = mysql_result($result, 0, "cr_project_manager_email");
	/*
	$addresses[] = mysql_result($result, 0, "cr_store_design_email");
	$addresses[] = mysql_result($result, 0, "gc_project_manager_email");
	$addresses[] = mysql_result($result, 0, "architect_contact_email");
	*/
	$cr_pm_flag = TRUE;
}

$sql = "SELECT cr_project_manager FROM projects WHERE id = $project_id and cr_project_manager IS NOT NULL";
$result = mysql_query($sql);
if(mysql_result($result, 0, "cr_project_manager") != "") {

	$cr_pm = mysql_result($result, 0, "cr_project_manager");
	$result = mysql_query("SELECT email FROM users where company_id = 1 and fullname LIKE '$cr_pm'");
	while ($row = mysql_fetch_array($result)) {
		$addresses[] = $row['email'];
	}
	$cr_pm_flag = TRUE;
}

if (!$cr_pm_flag) {
	 //No contact info in project designated...
	$result = mysql_query("SELECT email FROM users where company_id = 1 and title LIKE 'project manager'");
	while ($row = mysql_fetch_array($result)) {
		$addresses[] = $row['email'];
	}
}

$to = implode(',',$addresses);

foreach ($addresses as $address) {
	$notice_text = "Greetings,

$message

$comments

Use this link to access the RFI at:
$link";
	mail($address, "RFI - ".$project->sitenum." ".$project->sitename, $notice_text, "From:Collaboration Network RFI<no-reply@charlotte-russe.com>");
	//mail("rolando.garcia@gmail.com", "RFI - #".$project->sitenum."-".$project->sitename, $notice_text, "From:Collaboration Network RFI <no-reply@charlotte-russe.com>");
	//break;
}

if($new_cc_addresses) {
	//Convert posted comma separated list into an array
	$cc_addresses = explode(',',$new_cc_addresses);
	//Iterate through array
	foreach ($cc_addresses as $cc_address) {
		$cc_notice_text = "Greetings, 

$message

$comments
";
		mail($cc_address, "RFI - ".$project->sitenum." ".$project->sitename, $cc_notice_text, "From:Collaboration Network RFI <no-reply@charlotte-russe.com>");
		//mail("rolando.garcia@gmail.com", "RFI - #".$project->sitenum."-".$project->sitename, $cc_notice_text, "From:Collaboration Network RFI <no-reply@charlotte-russe.com>");
		//break;
	}
}
/*
$notice_text = "Greetings,

$message

$comments

Use this link to access the files at:
$link
";
*/
//mail($to, "RFI - ".$project, $notice_text, "From:Collaboration Network <no-reply@charlotte-russe.com>");
// mail("rolando.garcia@gmail.com", "RFI - ".$project, $notice_text, "From:Collaboration Network <no-reply@charlotte-russe.com>");
?>
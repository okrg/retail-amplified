<?php //edit.php
error_reporting(E_ALL ^ E_NOTICE);
include("../include/access.php");
include("../include/rt.php");

if(isset($_GET['closeout'])) {
	$sql = "UPDATE rt_rors SET status = 'completed' WHERE id = '".$_POST['parent']."'";
	if (!mysql_query($sql)) {
		$summary_msg .= "<h1>Error assigning action</h1><p>A database error occured when adding comments to the database: </p>";
		$summary_msg .= "<p><small>".mysql_error()."</small></p>";
	} else {
		$summary_msg .= "<p>Request Marked as Completed...</p>";
	}
} else {
	//Do normal editing 

	$view = 7;

	//check for blank body from DMs
	if($usergroup == 2) { if ($_POST['body'] == '') {error("Oops! You must write something in the message area before proceeding...");}}

	//Trim for whitespace
	$body = rtrim($_POST['body']);
	$body = ltrim($_POST['body']);
	$instructions = rtrim($_POST['instructions']);
	$instructions = ltrim($_POST['instructions']);
	$summary_msg = "";
	
	//determine if there are attachements
	if (isset($_FILES['userfile']['name'][1])){	$attachments = 1;} else {$attachments = 0;}
	
	//Add comments to the database!
	dbConnect();
	
	//Check if complete or denied, if not leave status open
	if($_POST['preset']=="Completed") {	$status = "completed";} else {	$status = "open";}
	

	//If this is being submitted by a privledged user, then assign the rt vars that are assingable...
	if ($usergroup < 2) { 		
		$sql = "UPDATE rt_rors SET ";
		if ($_POST['watchlist'] == "checked") {$sql .= " watchlist = CONCAT(watchlist,',','$uid'), ";} 
		if ($_POST['watchlist'] == "remove") {$sql .= " watchlist = REPLACE(watchlist,',$uid',''), ";} 
		$sql .= "po_num = '".$_POST['po_num']."',
			instructions = '$instructions',
			vendor_key = '".$_POST['vendor']."',
			dm_response = 0,
			has_children = has_children+1,
			status = '$status' ";
		$sql .= "WHERE id = '".$_POST['parent']."'";
		if (!mysql_query($sql)) {
			$summary_msg .= "<p>Error assigning action: A database error occured when adding comments to the database: </p>";
			$summary_msg .= "<p><small>".mysql_error()."</small></p>";
		} else {
			$summary_msg .= "<p>Assignment updated.</p>";
		}
	} else {
		$sql = "UPDATE rt_rors SET dm_response = 1, has_children = has_children+1 WHERE id = '".$_POST['parent']."'";
		if (!mysql_query($sql)) {
			$summary_msg .= "<h1>Error assigning action: A database error occured when adding comments to the database: </p>";
			$summary_msg .= "<p><small>".mysql_error()."</small></p>";
		} else {
			$summary_msg .= "<p>Assignment updated.</p>";
		}

	}




	if(isset($_POST['po_num'])) {$assignments = "PO: ".$_POST['po_num'];}
	
	//Write the rt_response
	$sql =	"insert into rt_ror_responses set";
	$sql .= " parent_key='".$_POST['parent']."',";
	$sql .=	" author_key='$unique_user_id',
				attachments=$attachments,
				summary='".$_POST['preset']."',
				body='$body',
				assignments='$assignments',
				view='$view',
				creation=NOW()";
				
				
		if (!mysql_query($sql)) {
			$summary_msg .= "<p>A database error occured when adding comments to the database: </p>";
			$summary_msg .= "<p><small>".mysql_error()."</small></p>";
		} else {
			$summary_msg .= "<p>Response added.</p>";
		}
		//Establish directory to where files are going to be uploaded
		$uploaddir = "/home/sites/www.construction.charlotte-russe.com/web/ror/rordocs/".mysql_insert_id();
	
		if ($attachments==1){
			mkdir($uploaddir, 0777);
			$files_uploaded = "";	
			for($i=0; $i<count($_FILES['userfile']['tmp_name']); $i++) { 
				$tempname = $_FILES['userfile']['tmp_name'][$i]; 
				$filename = $_FILES['userfile']['name'][$i]; 
				if($tempname != "") {
					if (move_uploaded_file($tempname, $uploaddir."/".$filename)) {
						$files_uploaded .= "$filename\n";
						$summary_msg .= "<p>Uploaded: $filename</p>";
					} //move_uploaded_file
				} //tempname
			}// for
		}//if attachments
	//}//if body is not blank
	
	if ($notify) {
	//Send notification to vendor "service_email", which is stored in the database as a field for each company.
	//	if ($notify == 2 or $notify == 6 or $notify == 7) {
	//		$sql = "select service_email from companies where company_id='".$_POST['vendor']."'";
	//		$result = mysql_query($sql);	
	//		if (mysql_num_rows($result)>0) {
	//			$addresses .= mysql_result($result,"service_email",0); 
	//			$addresses .= ", ";	
	//		}
	//	}
			
	//Invite the DM of this store.. tricky
	$sql = "select email from users where userid='dm".$_POST['store_district']."'";
	$result = mysql_query($sql);
	if (mysql_num_rows($result)>0) {
			$addresses .= mysql_result($result,"email",0); 
			$addresses .= ", ".$_POST['store_number']."@charlotterusse.com";	
	}
	
	$subjectline = "RE: ROR #".$_POST['store_number']." ".$_POST['sitestate']." ".$_POST['type']." ".$_POST['tracking'];
	$notice_text = "Greetings,
		
		$username has issued the following response:
		
		Status: ".$_POST['preset']."
		Location: #".$_POST['store_number']."
		Type: ".$_POST['type']."
		Work # ".$_POST['tracking']."
		PO #: ".$_POST['po_num']."
		
		If you have any questions or concerns regarding this request, please click on the following link and send a notification to your Maintenance Coordinator. Do not reply to this e-mail:
		
		http:/"."/construction.charlotte-russe.com/ror/ror-home.php?launch=".$_POST['parent']." ";
		
		//mail($addresses, $subjectline, $notice_text, "From:Collaboration Network <no-reply@charlotte-russe.com>");
		
		//Add to report
		$summary_msg .= "<p>Notification sent to DM and Store: $addresses </p>";
		//$summary_msg .= "<div style=\"width:500px;color:#fff;\">$notice_text</div>";
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>Editor</title>
<link rel="stylesheet" href="rt.css" />
<script type="text/javascript" src="rt.js"></script>
<script type="text/javascript" src="multifile.js"></script>
<script type="text/javascript" src="scriptaculous/prototype.js"></script>
<script type="text/javascript" src="scriptaculous/scriptaculous.js"></script>

<?php if (!isset($_POST['report_window'])) {  // If this is not a report window, then run this javascript code to refresh the window. Cannot refresh report window. ?>
	<script type="text/javascript">
        Event.observe( window, 'load', function() {
           parent.opener.location.reload();
           window.focus();
    
        } );
    </script>
<?php } ?>

</head>
<body id="pop">
<?php
echo "<div id=\"summary\">$summary_msg<a href=\"#\" onclick=\"javascript:window.close();return false;\"><img align=\"absmiddle\" border=\"0\" src=\"images/del_attachment.gif\" /></a></div>";
?>
	
<?php
include("viewcode.php");
?>

</body>
</html>

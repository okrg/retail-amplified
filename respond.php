<?php
	include("include/access.php");

	$id = $_GET['id'];
	$name = $_GET['name'];
	$container_id = "rebox";
	$container_id .= $id;

	if (isset($_GET['do'])) {
	//check for a blank response
	if (($new_body == "") & ($new_preset_body == "")) {error("You must write something in the request area before proceeding");}
	$id = $_GET['do'];
	if ($_GET['db'] == "freq") {
		//Figure out the ship date based on form input
		if ($ship_date_month=="") {
			$new_ship_date = "";
		} else {
			$new_ship_date .= $ship_date_year;
			$new_ship_date .=  substr( $ship_date_month + 100, 1 );
			$new_ship_date .=  substr( $ship_date_day + 100, 1 );
		}
		if( $_POST['followup'] == 'true' ) {
			$followup = 1;
		} else {
			$followup = 0;
		}
		$db_update = "fixture_orders";				
		//Set the sql statment..
		$sql ="insert into $db_update set 
			parent='$id',
			name = '$name',
			followup = $followup,
			status='response',
			body='$new_body',
			vendor='$new_vendor',
			po_num='$new_po_num',
			ship_date='$new_ship_date',
			author='$username'";
	} elseif ($_GET['db'] == "g2") {
		//Figure out if the special vendor contact is set
		//if so, then copy the vendor on the response and include the special vendor message and add it to the DB here
		if (isset($_POST['spec_vendor_contact_box'])) {
			$spec_vendor_contact .= $_POST['spec_vendor_contact_box'];
			$spec_vendor_contact .= ", ";
			$spec_vendor_comment = $_POST['spec_vendor_comment'];
			}
		if (isset($_POST['spec_vendor_contact_drop'])) {
			$spec_vendor_contact .= $_POST['spec_vendor_contact_drop'];
			$spec_vendor_contact .= ", ";
			$spec_vendor_comment = $_POST['spec_vendor_comment'];
			}
		//Append selected preset to the body of text that they added.
		$new_body = $new_preset_body.$new_body;	
		$db_update = "repair_orders";
		//Set the sql statment..
		$sql ="insert into $db_update set 
			parent='$id',
			status='response',
			body='$new_body',
			vendor='$new_vendor',
			spec_vendor_contact = '$spec_vendor_contact',
			spec_vendor_comment = '$spec_vendor_comment',
			po_num='$new_po_num',
			author='$username'";
	}
	//write response!
	dbConnect(); 
	
	if (!mysql_query($sql)) {
		error("A database error occured: " . mysql_error());
	} else {
		$new_body = stripslashes($new_body);
		if ($_GET['db'] == "g2") {
			//send notification back to DM and to the store
			//Get the store district and store number variables
			$si_query = "select body, store_number, store_district, type, tracking from repair_orders where id = $id";
			$si_result = mysql_query($si_query);
			$si_obj = mysql_fetch_object($si_result);
			$si_obj->store_number= str_pad($si_obj->store_number,3,"0",STR_PAD_LEFT);
			$dmuid = "dm".$si_obj->store_district;
			$sql = "select email from users where userid = '$dmuid'";
			$result = mysql_query($sql);
			$dm_address = mysql_result($result,0,"email");
			$addresses = $dm_address;
			//calculate store email address
			if ($si_obj->store_number > 599) {
				$addresses .= ", 2".$si_obj->store_number."@charlotte-russe.com";
				$chainname = "Rampage";		
			} else {
				$addresses .= ", 1".$si_obj->store_number."@charlotte-russe.com";
				$chainname = "Charlotte Russe";
			}
		
			//Send email notification using this special script just for db ajax bridge reponses
			include ("notify_response.php");
		}
	}
	if ($_POST['submit']=="Complete and Clear") {
		$statusAffector = "clear";
	} else {
		$statusAffector = "answered";	
	}
	if ($followup==1) {
		$sql = "update $db_update set status='$statusAffector', followup='$followup', vendor = '$new_vendor' where id = '$id'";
	} else {
		$sql = "update $db_update set status='$statusAffector', vendor = '$new_vendor' where id = '$id'";
	}
	if (!mysql_query($sql)) {
		error("A database error occured: " . mysql_error());
	} else {
		$report .= "Response has been added! ";
	}

	echo "
		<html xmlns=\"http://www.w3.org/1999/xhtml\">
		<head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
		<title>Issue Response</title>
		<style type=\"text/css\" media=\"all\">@import \"iframe.css\";</style>
		</head>";
		if ($_GET['db'] == "g2") {
		echo "<body onload=\"window.parent.signalResponse('flag".$id."');window.parent.toggleBox('box$id',0);window.parent.toggleBox('rebox$id',0);\">";
		} elseif ($_GET['db'] == "freq") {
		echo "<body onload=\"window.parent.signalResponse('flag".$id."');window.parent.toggleBox('box$id',1);window.parent.ajax_do('fdata.php?id=".$id."');\">";
		}
		echo "</body></html>";
	} else { //javascript:ajax_do('data.php?id=".$id."');
		
	echo "
	<html xmlns=\"http://www.w3.org/1999/xhtml\">
	<head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
	<title>Issue Response</title>
	<style type=\"text/css\" media=\"all\">@import \"iframe.css\";</style>
	<script language=\"JavaScript\">
	function fillText() {
	document.respond.new_body.value=document.respond.new_preset_body.value;
	}		
	</script>
	</head>
	<body class=\"bighshinybutton\">";
	if ($_GET['db'] == "g2") {
		$reboxcalling = $id;
		$re_box_report = "
		<form name=\"respond\" method=\"post\" action=\"".$PHP_SELF."?db=g2&do=".$id."\"><input type=\"hidden\" name=\"request_id\" value=\"x\">
		<select name=\"new_preset_body\" class=\"files\">
		<option value=\"\" selected>-Select a Preset Response-</option>
		<option value=\"Minor, Non-customer sensitive issue. Too costly for repair at this time... \">Minor, Non-customer sensitive issue. Too costly for repair at this time.</option>
		<option value=\"IT issue-Contact help desk x2428... \">IT Issue-Contact Help Desk x2428</option>
		<option value=\"Loss Prevention issue... \">Loss Prevention Issue</option>
		<option value=\"Upgrade project-Please send to RM for approval... \">Upgrade project-Please send to RM for approval</option>
		<option value=\"Fixture disposal form and store planning approval required... \">Fixture disposal form and store planning approval required</option>
		<option value=\"Purchasing issue-Contact the purchasing department x3029... \">Purchasing issue-Contact the purchasing department x3029</option>			
		<option value=\"Received your request and it has been dispatched... \">Received your request and it has been dispatched</option>
		<option value=\"Received your request and it is currently on hold... \">Received your request and it is currently on hold</option>
		<option value=\"Received your request and will need more information from the store... \">Received your request and will need more info from the store</option>
		<option value=\"Waiting on parts... \">Waiting on parts</option>
		<option value=\"Waiting on a quote... \">Waiting on a quote</option>
		<option value=\"Quote has been approved... \">Quote has been approved</option>
		<option value=\"Parts have been ordered... \">Parts have been ordered</option>
		<option value=\"Update... \">Update</option>
		<option value=\"Completed... \">Completed</option>
		<option value=\"Other... \">Other</option>
		</select><br /><br />";
		if ($usergroup==3) {
			$cr = mysql_query("select company_name from companies where company_id = $usercompany");
			$vendorname = mysql_result($cr,0,"company_name");
			$re_box_report .= "<input type=\"hidden\" name=\"new_vendor\" value=\"$vendorname\" />";
		} else {
			$re_box_report .= "<table><tr><td>
			<select name=\"new_vendor\" class=\"files\">
			<option value=\"\" selected>-Select Vendor-</option>
			<option value=\"BOSS\">BOSS</option>
			<option value=\"Metro Door\">Metro Door</option>
			<option value=\"Executive Safe\">Executive Safe</option>
			<option value=\"Royal\">Royal</option>
			<option value=\"Conditioned Air\">Conditioned Air</option>			
			<option value=\"Commercial Fire\">Commercial Fire</option>
			<option value=\"Terminex\">Terminex</option>
			<option value=\"Communication Resources\">Communication Resources</option>
			<option value=\"Cleanway\">Cleanway</option>
			<option value=\"Clearsign\">Clearsign</option>
			<option value=\"Signtech\">Signtech</option>
			<option value=\"General Contract\">General Contract</option>
			<option value=\"Saf\">Saf</option><br />
			<option value=\"One Call Services\">One Call Services</option>
			<option value=\"CLS\">CLS</option>
			<option value=\"Comfort Systems\">Comfort Systems</option>
			<option value=\"Lane Valente\">Lane Valente</option>
			<option value=\"Muzak\">Muzak</option>
			<option value=\"MC Signs\">MC Signs</option>
			<option value=\"Other\">Other</option>
			</select></td><td>";
			$re_box_report .= "<select name=\"spec_vendor_contact_drop\" class=\"files\">
			<option value=\"\" selected>-Select a Special Contact-</option>
			<option value=\"Hvac@bossfacilityservices.com\">BOSS HVAC</option>
			<option value=\"Plumbing@bossfacilityservices.com\">BOSS Plumbing</option>
			<option value=\"maintenance@bossfacilityservices.com\">BOSS Maintenance</option>
			<option value=\"lighting@bossfacilityservices.com\">BOSS Lighting</option>
			<option value=\"mcongro@metrodoor.com\">Metro-mcongro@metrodoor</option>			
			<option value=\"dpoole@metrodoor.com\">Metro-dpoole@metrodoor</option>
			</select></td></tr></table>";	
			$re_box_report .= "or enter any address:<input size=\"30\" type=\"text\" name=\"spec_vendor_contact_box\" /><br /><br />";
			$re_box_report .= "<strong>Comments to Vendor:</strong><br /><textarea name=\"spec_vendor_comments\" class=\"files\" cols=\"30\" rows=\"2\"></textarea><br /><br />";
		}
		$re_box_report .= "
		<strong>PO #:</strong><br />
		<input class=\"files\" name=\"new_po_num\" /><br /><br />";
	} elseif ($_GET['db'] == "freq") {
		$reboxcalling = $id."-".$name;
		$re_box_report = "
		<form name=\"respond\" method=\"post\" action=\"".$PHP_SELF."?db=freq&name=".$name."&do=".$id."\"><input type=\"hidden\" name=\"request_id\" value=\"x\">
		<strong>Preset Response:</strong><br />
		<select onChange=\"fillText();\" name=\"new_preset_body\" class=\"files\">
		<option value=\"\" selected>-Select One-</option>
		<option value=\"Your request has been approved. Your item(s) will ship in 4-5 weeks.\">Request approved</option>
		<option value=\"Your request is being reviewed for approval. You will be contacted to discuss. Thank you for your patience during this process.\">Request is being reviewed</option>
		<option value=\"Your request is being reviewed and in order to keep with your current fixtures, we will not be able to provide this fixture. However we will be able to provide you with an alternate option. I will contact you to discuss.\">I will contact you to discuss this</option>
		</select><br /><br />		
		<strong>Ship Date:</strong><br />
		<select class=\"files\" name=\"ship_date_month\">
		<option value =\"00\">MM</option>
		<option value =\"01\">01</option>
		<option value =\"02\">02</option>
		<option value =\"03\">03</option>
		<option value =\"04\">04</option>
		<option value =\"05\">05</option>
		<option value =\"06\">06</option>
		<option value =\"07\">07</option>
		<option value =\"08\">08</option>
		<option value =\"09\">09</option>
		<option value =\"10\">10</option>
		<option value =\"11\">11</option>
		<option value =\"12\">12</option></select>
		
		<select class=\"files\" name=\"ship_date_day\">
		<option value =\"00\">DD</option>
		<option value =\"01\">01</option>
		<option value =\"02\">02</option>
		<option value =\"03\">03</option>
		<option value =\"04\">04</option>
		<option value =\"05\">05</option>
		<option value =\"06\">06</option>
		<option value =\"07\">07</option>
		<option value =\"08\">08</option>
		<option value =\"09\">09</option>
		<option value =\"10\">10</option>
		<option value =\"11\">11</option>
		<option value =\"12\">12</option>
		<option value =\"13\">13</option>
		<option value =\"14\">14</option>
		<option value =\"15\">15</option>
		<option value =\"16\">16</option>
		<option value =\"17\">17</option>
		<option value =\"18\">18</option>
		<option value =\"19\">19</option>
		<option value =\"20\">20</option>
		<option value =\"21\">21</option>
		<option value =\"22\">22</option>
		<option value =\"23\">23</option>
		<option value =\"24\">24</option>
		<option value =\"25\">25</option>
		<option value =\"26\">26</option>
		<option value =\"27\">27</option>
		<option value =\"28\">28</option>
		<option value =\"29\">29</option>
		<option value =\"30\">30</option>
		<option value =\"31\">31</option></select>";
		$cur_year = date("Y");
		$next_year = $cur_year + 1;
		$re_box_report .= "&nbsp;<select name=\"ship_date_year\"><option value=\"$cur_year\">$cur_year";

		$re_box_report .= "</option>";
		$re_box_report .= "<option value=\"$next_year\">$next_year</option></select><br /><br />
		<strong>Vendor:</strong><br />
		<select name=\"new_vendor\" class=\"files\">
		<option value=\"\" selected>-Select One-</option>
		<option value=\"RAP\">RAP</option>
		<option value=\"Hamrock\">Hamrock</option>
		<option value=\"Leggit and Platt\">Leggit and Platt</option>
		<option value=\"Synsor\">Synsor</option>
		<option value=\"M. Lavine\">M. Lavine</option>			
		<option value=\"California Customs\">California Customs</option>
		<option value=\"Creative Forces\">Creative Forces</option>
		<option value=\"Other\">Other</option>
		</select><br /><br />";
		
	}
	$re_box_report .= "

	<strong>Response:</strong><br />
	<textarea class=\"files\" name=\"new_body\" cols=\"30\" rows=\"2\">$new_body</textarea><br />";
	if ($_GET['db'] == "freq") {
	$re_box_report .= "<input name=\"followup\" type=\"checkbox\" id=\"fubox\" value=\"true\" style=\"vertical-align: middle;\" /><label for=\"fubox\">Needs <strong>follow up</strong>.</label><br />";
	}
	$re_box_report .= "<input class=\"bigshinybutton\" type=\"submit\" name=\"submit\" value=\"Submit\">";
	if ($usergroup!=3){
	$re_box_report .= "<input class=\"bigshinybutton\" type=\"submit\" name=\"submit\" value=\"Complete and Clear\">";
	}
	$re_box_report .= "<input class=\"bigshinybutton\" type=\"button\" name=\"button\" value=\"Cancel\" onClick=\"window.parent.toggleBox('rebox".$reboxcalling."',0);\"></form>";

	echo $re_box_report;
	echo "
	</body>
	</html>";

}
?>
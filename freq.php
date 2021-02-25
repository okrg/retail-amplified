<?php 
//This script constitutes the fixture request form,
//it is basically a glorified ordering form with fixture images
//and a checkbox underneath each one to select it.
//This will only show the fixtures that are in that store.

?>
<div id="content">
<h1>Fixture Request Form</h1>
<div class="databox">
<?php 
if ($_POST['phase']=="submit") {
if ($new_addreplace == "") {
	error("You must select a request add or replace before proceeding");
}
if ($new_priority == "") {
	error("You must select a priority before proceeding");
}

switch ($new_priority) {
	case "Hazard":
		$new_urgency=1;
		break;
	case "Urgent":
		$new_urgency=2;
		break;
	case "Minor":
		$new_urgency=3;
		break;
	case "Not Urgent":
		$new_urgency=4;
		break;}

//decipher the request 
$request_items = explode(",", $_POST['request']);
while (list($key,$value) = each($request_items)) {
	$values = explode("-", $value);
	$fix_sql = "select * from fixture_key where id = $values[0]";
	$result = mysqli_query($dbcnx, $fix_sql) or die("A database error occured: " . mysqli_error($dbcnx));
	$fixture = mysqli_fetch_object($result);
	$new_request .= "<li>";
	$new_request .= "$fixture->name - $fixture->desc <strong>[".$values[1]."]</strong>";
	$new_request .= "</li>";
}



//Set the sql statment..
		$sql = "insert into fixture_orders set 
				project_id='".$_POST['pid']."',
				store_number='".$_POST['store_number']."',
				store_district = '".$_POST['store_district']."',
				store_region = '".$_POST['store_region']."',
				priority = '".$_POST['priority']."',
				urgency = '".$_POST['urgency']."',
				status = 'pending',
				body = '$new_request',
				author = '$username',
				issue_date = CURDATE(),
				timestamp=NOW()";
		
		if (!mysqli_query($dbcnx, $sql)) {error("A database error occured: " . mysqli_error($dbcnx));}


	//include("notify_fixture.php");
	//Add to report
	$summary_msg .= "<p>The following message was e-mailed to corporate staff:</p>";
	$summary_msg .= "<div style=\"border:1px #ddd solid;background:#eee;padding:10px;\"><pre>$notice_text</pre></div>";




//show phase
	echo "<p><strong>Store #:</strong> ".$_POST['store_number']."<br />";
	echo "<strong>Location:</strong> ".$_POST['location_name']."</p>";
	echo "<div class=\"sortbox\">";
	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
	echo "<tr><td class=\"progress\" colspan=\"4\">";
	echo "<div id=\"tabs2\">
	  <ul>
		<li><a href=\"$PHP_SELF?page=freq\"><span>Build a New Request</span></a></li>
		<li><a href=\"#\" onClick=\"return false;\"><span>Request Confirmed</span></a></li>
		<li><a href=\"#\" onClick=\"return false;\" id=\"current_tab\"><span>Request Submitted</span></a></li>
	  </ul>
	  </div>";
	echo "</td></tr>";
	echo "<tr><td colspan=\"4\">";
	echo "<p><strong>Thank You</strong></p>";
	echo "<p>Your request has been submitted. Check back for the status of your request.</p>";
	echo "</td></tr></table>";
	echo "</div>";



} elseif ($_POST['phase']=="confirm") {
//show phase

	  
//confirm script
	echo "<p><strong>Store #:</strong> ".$_POST['store_number']."<br />";
	echo "<strong>Location:</strong> ".$_POST['location_name']."</p>";
	echo "<div class=\"sortbox\">";
	echo "<form name=\"qty\" method=\"post\" action=\"$PHP_SELF?page=freq\">";
	echo "<input type=\"hidden\" name=\"phase\" value=\"submit\" />";
	echo "<input type=\"hidden\" name=\"pid\" value=\"".$_POST['pid']."\" />";		
	echo "<input type=\"hidden\" name=\"location_name\" value=\"".$_POST['location_name']."\" />";
	echo "<input type=\"hidden\" name=\"store_district\" value=\"".$_POST['store_district']."\" />";	
	echo "<input type=\"hidden\" name=\"store_region\" value=\"".$_POST['store_region']."\" />";	
	echo "<input type=\"hidden\" name=\"store_number\" value=\"".$_POST['store_number']."\" />";				
	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
	echo "<tr><td class=\"progress\" colspan=\"4\">";
	echo "<div id=\"tabs2\">
	  <ul>
		<li><a href=\"#\" onClick=\"history.back();\"><span>Edit Your Request</span></a></li>
		<li><a href=\"#\" onClick=\"return false;\" id=\"current_tab\"><span>Confirm Your Request</span></a></li>
		<li><a href=\"#\" onClick=\"document.qty.submit();\"><span>Submit Your Request</span></a></li>
	  </ul>
	  </div>";
	echo "</td></tr>";
	echo "<tr>";
	echo "<th>Category</th>";
	echo "<th>Ref</th>";
	echo "<th align=\"right\">Name</th>";
	echo "<th>Qty</th>";

	echo "</tr>";


foreach ($_POST as $key => $value) {
	if ((substr($key, 0, 3)=="qty")&&($value>0)) {
		$fix_value = substr($key,4);
		$sql = "select * from fixture_key where id =$fix_value";
		$result = mysqli_query($dbcnx, $sql);
		$fixture = mysqli_fetch_object($result);
		echo "<tr>";
		echo "<td><small>".$fixture->cat."</small></td>";
		echo "<td>".$fixture->desc."</td>";
		echo "<td align=\"right\"><small>".$fixture->name."</small></td>";
		echo "<td><strong>".$value."</strong></td>";
		echo "<td>";
		echo "<input class=\"files\" type=\"radio\" value=\"Add\" name=\"ar_".$fixture->id."\" id=\"".$fixture->id."addrad\"><label for=\"".$fixture->id."addrad\">Add</label>&nbsp;&nbsp;
		<input class=\"files\" type=\"radio\" value=\"Replace\" name=\"ar_".$fixture->id."\" id=\"".$fixture->id."reprad\"><label for=\"".$fixture->id."reprad\">Replace</for><br />";
		echo "</td></tr>";
		if (isset($request_body)) {
			$request_body .= ",";
		}
		$request_body .= "$fixture->id-$value";

}

}
	echo "<tr><td align=\"right\" colspan=\"5\">";
	echo "<select name=\"new_priority\" class=\"files\">
            <option value=\"\" selected>- Choose a Priority -</option>
            <option value=\"Minor\">Minor</option>
            <option value=\"Not Urgent\">Not Urgent</option>
            <option value=\"Urgent\">Urgent</option>
            <option value=\"Hazard\">Hazard!</option>
          </select></td></tr>";


	echo "<tr><td align=\"right\" colspan=\"4\"><input type=\"submit\" value=\"Submit >>\" /></td></tr>";
	echo "</table>";
	echo "<input type=\"hidden\" name=\"request\" value=\"$request_body\" />";
	echo "</form>";
	echo "</div>";


} else {
//if (isset($_POST['pid'])) {
	//Get store region and district based on the id of the submitted project id
	//$sql = "select * from projects where id={$_POST['pid']}";
	$sql = "select * from projects where id=25";	
	$results = mysqli_query($dbcnx, $sql);
	$location_name = mysqli_result($results,0,"sitename");
	$store_district= mysqli_result($results,0,"store_district");
	$store_region= mysqli_result($results,0,"store_region");
	$store_number= mysqli_result($results,0,"store_number");

	echo "<div class=\"sortbox\">";
	echo "<p><strong>Store #:</strong> $store_number<br />";
	echo "<strong>Location:</strong> $location_name</p>";	
	echo "<form name=\"selecta\" method=\"post\" action=\"$PHP_SELF?page=freq\">";
	echo "<input type=\"hidden\" name=\"phase\" value=\"confirm\" />";
	//echo "<input type=\"hidden\" name=\"pid\" value=\"".$_POST['pid']."\" />";	
	echo "<input type=\"hidden\" name=\"pid\" value=\"25\" />";		
	echo "<input type=\"hidden\" name=\"location_name\" value=\"$location_name\" />";
	echo "<input type=\"hidden\" name=\"store_district\" value=\"$store_district\" />";	
	echo "<input type=\"hidden\" name=\"store_region\" value=\"$store_region\" />";	
	echo "<input type=\"hidden\" name=\"store_number\" value=\"$store_number\" />";	
	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
	echo "<tr><td class=\"progress\" colspan=\"2\">";
	echo "<div id=\"tabs2\">
	  <ul>
		<li><a href=\"#\" id=\"current_tab\"><span>Build Your Request</span></a></li>
		<li><a href=\"#\" onClick=\"document.selecta.submit();\"><span>Confirm Your Request</span></a></li>
		<li><a href=\"#\" onClick=\"return false;\"><span>Submit Your Request</span></a></li>
	  </ul>
	  </div>";
	echo "</td></tr>";
$result = mysqli_query($dbcnx, "select * from fixture_key order by cat");
if (!$results) { echo mysqli_error($dbcnx); }
while ($fixture = mysqli_fetch_object($result)) {
		if (!isset($testcase)) {
		echo "<tr><td colspan=\"2\"><h1>$fixture->cat</h1></td><td align=\"right\"><a href=\"#\" onClick=\"expandCat('$fixture->cat');return false;\">Expand</a></td></tr>";
		$testcase = $fixture->cat;
		} else {
			if ($fixture->cat != $testcase) {
				echo "<tr><td colspan=\"2\"><h1>$fixture->cat</h1></td><td align=\"right\"><a href=\"#\" onClick=\"expandCat('$fixture->cat');return false;\">Expand</a></td></tr>";
				$testcase = $fixture->cat;
			}
		}
		echo "<tr>";
		echo "<td>$fixture->name</td>";
		echo "<td align=\"right\"><a href=\"#\" onClick=\"javascript:ajax_do('freqdata.php?id=".$fixture->id."');toggleBox('box".$fixture->id."',1);return false;\">".ucwords(strtolower(stripslashes($fixture->desc)))."</a>";
		echo "<div id=\"box".$fixture->id."\" style=\"display:none;\" class=\"bigshinybutton\"><p><input type=\"input\" name=\"qty_$fixture->id\" size=\"1\" value=\"0\" onClick=\"clickclear(this, '0')\" />p</p></div></td>";

		echo "<input type=\"hidden\" name=\"cat_$fixture->cat[]\" value=\"$fixture->id\" />";
		echo "</tr>";
			

}

}
//$results = mysqli_query($dbcnx, "select * from fixture_inventory where store = 1");
//if (!$results) { echo mysqli_error($dbcnx); }
//$inventoryArray = mysqli_fetch_array($results, MYSQL_ASSOC);
//$store = array_shift($inventoryArray);
//$location = array_shift($inventoryArray);
//
//
//	echo "<p><strong>Store #:</strong> $store<br />";
//	echo "<strong>Location:</strong> $location</p>";
//	echo "<div class=\"sortbox\">";
//	echo "<form name=\"selecta\" method=\"post\" action=\"$PHP_SELF?page=freq\">";
//	echo "<input type=\"hidden\" name=\"phase\" value=\"confirm\" />";
//	echo "<input type=\"hidden\" name=\"store\" value=\"$store\" />";
//	echo "<input type=\"hidden\" name=\"location\" value=\"$location\" />";
//  	
//	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
//	echo "<tr><td class=\"progress\" colspan=\"4\">";
//	echo "<div id=\"tabs2\">
//	  <ul>
//		<li><a href=\"#\" id=\"current_tab\"><span>Build Your Request</span></a></li>
//		<li><a href=\"#\" onClick=\"document.selecta.submit();\"><span>Confirm Your Request</span></a></li>
//		<li><a href=\"#\" onClick=\"return false;\"><span>Submit Your Request</span></a></li>
//	  </ul>
//	  </div>";
//	echo "</td></tr>";
//	echo "<tr>";
//	echo "<th>Category</th>";
//	echo "<th>Name</th>";
//	echo "<th align=\"right\">Description</th>";
//	echo "<th>&nbsp;</th>";
//	echo "</tr>";
//
//while (list($key,$value) = each($inventoryArray)) {
//
//	//list only non-zero inventory items
//
//	if ($value != 0) {
//		//strip the f_ from the key
//		$sql = "select * from fixture_key where id =".substr($key, 2);
//		$result = mysqli_query($dbcnx, $sql);
//		$fixture = mysqli_fetch_object($result);
//		echo "<tr>";
//		echo "<td><small>".$fixture->cat."</small></td>";
//		echo "<td>$fixture->name</td>";
//		echo "<td align=\"right\"><a href=\"#\" onClick=\"javascript:ajax_do('freqdata.php?id=".$fixture->id."');toggleBox('box".$fixture->id."',1);return false;\">".ucwords(strtolower(stripslashes($fixture->desc)))."</a>";
//		echo "<div id=\"box".$fixture->id."\" style=\"display:none;\" class=\"bigshinybutton\"></div></td>";
//		echo "<td><input type=\"input\" name=\"qty_$fixture->id\" size=\"1\" value=\"0\" onClick=\"clickclear(this, '0')\" /></td>";
//		echo "</tr>";
//
//	}
//}
//	echo "<tr><td align=\"right\" colspan=\"4\"><input type=\"submit\" value=\"Next >>\" /></td></tr>";
//	echo "</table>";
//	echo "</form>";
//	echo "</div>";
//}
?>	
	
	</div>
</div>

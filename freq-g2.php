<?php
	if ($_GET['do']=="clear") {
		dbConnect();
		$sql = "update repair_orders set status='clear' where id = ".$_GET['id'];
		if (!mysql_query($sql)) {error("A database error occured: " . mysql_error());}
	}
	if (!isset($editok)):
	//determine the district or region using login.
	if (!isset($sort)) {$sortstyle = "Region";
		} else {
		$sortstyle = $sort;
		}
	switch ($sort) {
		case "datetouched": 
			$sortstyle="date last modified"; 
			$sort = "datetouched desc";
			break;
			case "store_number": 
			$sortstyle="store number";
			break;
			case "chain": 
			$sortstyle="store chain";
			break;
			case "sitename": 
			$sortstyle="location name";
			break;
			default: 
			$sortstyle="location name"; 
			$sort = "sitename";
			break; 
			
	}
		$uid = strtolower($uid); // This is to guard against when people use all caps in their login name.
		$user_uid_rank = substr($uid,0,2);
		$user_domain = substr($uid,2);
		if ($user_uid_rank == "dm") {
			$domain_value="District";
			$db_string = "store_district";
			} 
		elseif ($user_uid_rank == "rm") {
			$domain_value="Region";
			$db_string="store_region";
		}


dbConnect();
$sql = "select * from blog where readers='g2'";
$result = mysql_query($sql);
if (!result){error("A databass error has occured in processing your request.\\n". mysql_error());}
	while ($row = mysql_fetch_array($result)) {
	$id = $row["id"];
	$ts = $row["ts"];
	$ts = revertTimestamp($ts);
	$subject = $row["subject"];
	$body= $row["body"];
	$body = stripslashes($body);
	$body = nl2br($body);
	$author = $row["author"];
}
?>
</head>
<body>
<div id="content">
<div class="databox">
<h2></h2>
<p><small>Posted by <?=$author?> on <?=$ts?></small></p>
<p><?=$body?></p>
</div>
<h1>:: My <?=$domain_value?></h1>
<div class="databox">
<?php 

echo "Sorry! This is temporarily offline while we work on something new...Please check back later.";
exit;

?>
<p>Listed below are stores in your . If the listing is incorrect
please contact construction department and request the list be updated.</p>

<?php 
	//Show the link to the weekly fixture report
	if ($handle = opendir("./filespace/weekly_freq/")) {
		while (false !== ($item = readdir($handle))) {
			if ($item != "." && $item != ".."){$filename=$item;}
		}
		print "<span style=\"float:right;\"><img align=\"absmiddle\" src=\"images/fx_report.gif\" /><a target=\"_blank\" href=\"filespace/weekly_freq/".$filename."\">Weekly Fixture Report</a></span>"; 
		}
	//stores in this domain
	echo "<h2>Showing all stores in $domain_value $user_domain</h2><a name=\"stores\"></a>";
	if (isset($sort)) { 
		echo "Sorted by $sortstyle";
	}
	dbConnect();
	$sql = "select id, sitenum, sitename, store_number, store_district, store_region, chain, datetouched from projects where $db_string = $user_domain order by $sort";
	//Execute filter on db!
	$result = mysql_query($sql);
	if (!$result)
		{error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error());}
		
	if (mysql_num_rows($result) == 0) {
		print "<p><strong>Notice:</strong> It appears that there are currently no stores which you have been granted access to.</p>";
	} else {
	 	print "<div class=\"sortbox\">";
	 	print "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
	 	print "<tr>";
	 	print "<th width=\"50\"><a href=\"$PHP_SELF?sort=store_number\">Store #</a></th>";
	 	print "<th><a href=\"$PHP_SELF?sort=sitename\">Location Name</a></th>";
	 	print "<th><a href=\"$PHP_SELF?sort=chain\">Chain</a></th>";
	 	print "<th>Lighting Guide</th>";
	 	print "<th width=\"120\">Repair Requests</th>";
	 	print "<th>Fixture Requests</th>";
	 	print "</tr>";
	 	$count=0;
	//Cultivate data set
		while ($row = mysql_fetch_array($result)) {
			$id = $row["id"];
			$store_number = $row["store_number"];
			$sitename = $row["sitename"];
			$chain=$row["chain"];
		if ($chain==1) {
			$chain_name="Charlotte Russe";
		} elseif ($chain==2) {
			$chain_name="Rampage";
		}
		$dropdown_options .= "<option value=\"$id\">$store_number $sitename - $chain_name</option>";
		$count++;
			//Get status of repairs for this project and fixtures as well
			$repair_result = mysql_query("select * from repair_orders where project_id=$id and status = 'answered'");
			$active_repairs = mysql_num_rows($repair_result);
			$repairs_report = "";
			$fixture_result = mysql_query("select * from fixture_orders where project_id=$id and status = 'answered'");
			$active_fixtures = mysql_num_rows($fixture_result);
			$fixtures_report = "";
			if ($active_fixtures == 0) {
				$fixtures_report .= "";
			} else {
				$fixtures_report .= "<small>$active_fixtures Answered</small><br />";
			}
			$fixture_result = mysql_query("select * from fixture_orders where project_id=$id and status = 'pending'");
			$pending_fixtures = mysql_num_rows($fixture_result);
		if ($pending_fixtures == 0) {
			$fixtures_report .= "";
		} else {
			$fixtures_report .= "<small>$pending_fixtures Pending</small>";
		}
		if (($pending_fixtures == 0) && ($active_fixtures == 0)) {
			$fixtures_report .= "<small>No Requests</small>";
		}
		$box_report ="";
		$priority_report = "";
		if ($active_repairs == 0) {
			$repairs_report .= "";
		} else {
			$repairs_report .= "<small>$active_repairs Answered</small><br />";
			$box_report .= "<div align=\"center\"><small>----Answered Requests----</small></div>";
			while($row = mysql_fetch_array($repair_result)) {
				$repair_id = $row["id"];
				$author = $row["author"];
				$priority = $row["priority"];
				$type = $row["type"];
				$body = $row["body"];
				$issue_date = $row["issue_date"];
				$box_report .= "<div class=\"bigshinybutton\">";
				$box_report .= "<a href=\"#$repair_id\">$type</a><small> on ".$issue_date." by $author</small><br />";
				$box_report .= "<small><strong>Priority:</strong> $priority</small>";
				$box_report .= "</div>";
				$box_report .= "<p style=\"margin:0 4 0 4;color:#333;\">{$row["body"]}<br /><br />";
				$box_report .= "<small><strong>Contact Name: </strong>{$row["contact_name"]}<br />";
				$box_report .= "<strong>Contact Number: </strong>{$row["contact_number"]}</small>";
				//Response from corp office!
				$response_result = mysql_query("select * from repair_orders where status = 'response' and parent=$repair_id");
				// if (mysql_num_rows($response_result)!=0) {
					while($srow = mysql_fetch_array($response_result)) {
						$author = $srow["author"];
						$body = $srow["body"];
						$vendor = $srow["vendor"];
						$po_num = $srow["po_num"];
						$res_ts = $srow["timestamp"];
						$res_ts = revertTimestamp($res_ts); 
								//start table
									$box_report .= "<div style=\"color: #555;background:#eee;border:1px #ddd solid;padding:3px;margin-bottom: 5px;\">";
								//show PO number if available
									if ($po_num != "") {
										$po_report = "<small><strong>PO:</strong> $po_num</small>";
										$box_report .= $po_report;
									}
								//show Vendor if available
									if ($vendor != "") {
										$vendor_report = "<small><strong>Vendor:</strong> $vendor</small><br />";
										$box_report .= $vendor_report;
									}
								//show comments by corporate.
									$box_report .= "<p><small>Comments from ".$author." on ".$res_ts."</small><br /><br />";
								//split body into preset and comments.
									$findme = "...";
									$pos = strpos($body, $findme);
								// Note our use of ===. Simply == would not work as expected
								// because the position of 'a' was the 0th (first) character.
									if ($pos === false) {
										$box_report .= $body;
									} else {
										$preset = substr($body, 0, $pos);
										$mpos = $pos + 3;
										$comment = substr($body, $mpos);
										$box_report .= "<strong>".$preset."</strong><br />";
										$box_report .= $comment;
									}
									$box_report .= "</p>";
									$box_report .= "</div>";
									$box_report .= "<small>[<a href=\"index.php?do=clear&id=$repair_id\">Clear this answered request</a>]</small><br /><br />";
								}
								$box_report .= "<br /><br /><br />";
								//}
							}
						}
						$repair_result = mysql_query("select * from repair_orders where project_id=$id and status = 'pending'");
						$pending_repairs = mysql_num_rows($repair_result);
						if ($pending_repairs == 0) {
							$repairs_report .= "";
						} else {
							$repairs_report .= " <small>$pending_repairs Pending</small>";
							$box_report .= "<div align=\"center\"><small>----Pending Requests----</small></div>";
							while($row = mysql_fetch_array($repair_result)) {
								$repair_id = $row["id"];
								//$subject = $row["subject"];
								$author = $row["author"];
								$priority = $row["priority"];
								$type = $row["type"];
								$ts = $row["timestamp"];
								$body = $row["body"];
								$ts = revertTimestamp($ts);
								$box_report .= "<div class=\"bigshinybutton\">";
								$box_report .= "<a href=\"#$repair_id\">$type</a><small> on $ts by $author</small><br />";
								$box_report .= "<small><strong>Priority:</strong> $priority</small>";
								$box_report .= "</div>";
								$box_report .= "<p style=\"margin:0 4 0 4;color:#333;\">{$row["body"]}<br /><br />";
								$box_report .= "<small><strong>Contact Name: </strong>{$row["contact_name"]}<br />";
								$box_report .= "<strong>Contact Number: </strong>{$row["contact_number"]}</small>";
								$box_report .= "</p>";
								$box_report .= "<div style=\"border-bottom: 1px solid #ccc;\"></div>";
							}
						}
						if (($pending_repairs == 0) && ($active_repairs == 0)) {
							$repairs_report .= "<small>No issues</small>";
							$flag = "<img src=\"images/collapse.gif\" border=\"0\" align=\"top\" />&nbsp;$sitename &nbsp;";
						} else {
							$flag = "<a href=\"#\" onClick=\"javascript:toggleBox('box_".$id."',1);return false;\"><img src=\"images/expand.gif\" border=\"0\" align=\"top\" /></a>&nbsp;$sitename &nbsp;";
						}
						print "<td class=\"project\">$store_number &nbsp;</td>";
						print "<td class=\"project\">$flag";
						print "<div id =\"box_".$id."\" style=\"display:none;\" class=\"bigshinybutton\">";
						print "<span style=\"float:right;\">";
						print "<a href=\"#\" onClick=\"javascript:toggleBox('box_".$id."',0);return false;\">";
						print "<img src=\"images/close.gif\" border=\"0\" /></a></span>";
						print "<p>$box_report</p>";
						print "</div>";
						// print "<td class=\"project\"><h5 class=\"trigger\">$sitename</h5>";
						// if ($box_report != "") {
						// print "<div class=\"bigshinybutton\">$box_report</div>";
						// }
print "</td>";
						print "<td><small>$chain_name</small></td>";
						//Links to lighting guides/surveys
						print "<td class=\"project\">";
						$isn = intval($store_number);
						$fname = "lights/".$isn."g.xls";
						if (file_exists($fname)) {
							print "<small><a href=\"/lights/".$isn."g.xls\">Download</a></small>";
						} else {
							print "<small>N/A</small>";
						}
						print "</td>";
						print "<td class=\"project\">$repairs_report&nbsp;</td>";
						//print "<td class=\"project\"><a href=\"index.php?page=freq-view&pid=$id\">Status</a>";
						//print " | ";
						//print "<a href=\"index.php?page=freq-shop&pid=$id\">Catalog</a><br />";
						//print "$fixtures_report</td>"; 
						print "<td><a href=\"http://www.construction.charlotte-russe.com/ror/fixture-home.php\">Use new form</a></td>";
						//Generated earlier shows whats been answered and whats pending 
						print "</tr>";
					}
					echo "</table></div>";
					echo "<p>";
					print "[<a href=\"#submit\">Submit a repair request</a>]&nbsp;[<a href=\"#history\">View history for this $domain_value</a>]";
					echo "</p>";
				}
		?>
</div>
<a name="submit"></a>
<h1>:: Repair Order Request</h1>
<div class="databox">
<form name="ror" method="post" action="index.php">
  <table class="litezone" align="center" cellpadding="2" cellspacing="2" width="99%">
    <tbody>
      <tr>
        <td rowspan="7" style="border-right: 1px dashed rgb(102, 102, 102);" align="right" valign="middle"><small><strong>Required<br>
Data </strong></small></td>
        <td class="col1" style="border-top: 1px dashed rgb(102, 102, 102);" width="220"><strong>Store
Location: </strong></td>
        <td class="edit_windows" style="border-top: 1px dashed rgb(102, 102, 102);">
        <select name="new_location" class="files">
        <option value="" selected="selected">Please Choose </option>
          <?=$dropdown_options?>
        </select>
        </td>
      </tr>
      <tr>
        <td class="col1"><strong>Request Type:</strong></td>
	    <td class="edit_windows">
        <select name="new_type" class="files">
        <option value="" selected="selected">Please
Choose</option>
        <option value="Lighting">Lighting</option>
        <option value="Plumbing">Plumbing</option>
        <option value="Walls/Paint">Walls/Paint</option>
        <option value="Flooring">Flooring</option>
        <option value="Pest Control">Pest Control</option>
        <option value="Electrical">Electrical</option>
        <option value="HVAC">HVAC</option>
        <option value="Locks">Locks</option>
        <option value="Gate">Gate</option>
        <option value="Leak">Leak</option>
        <option value="Cashwrap">Cashwrap</option>
        <option value="Storefront Sign">Storefront Sign</option>
        <option value="Muzak/Sound System">Muzak/Sound
System</option>
        <option value="Other">Other</option>
        </select>
        </td>
      </tr>
      <tr>
        <td class="col1"><strong>Priority:</strong></td>
        <td class="edit_windows">
        <select onFocus="this.className='ff'" onBlur="this.className='files'" name="new_priority" class="files">
        <option value="" selected="selected">Please
Choose</option>
        <option value="Minor">Minor</option>
        <option value="Not Urgent">Not Urgent</option>
        <option value="Urgent">Urgent</option>
        <option value="Hazard">Hazard!</option>
        </select>
        </td>
      </tr>
      <tr>
        <td class="col1"><strong>Store Manager
Contact or Other:</strong></td>
        <td class="edit_windows"><input name="new_contact_name" class="files" onKeyPress="return noenter()" onFocus="this.className='ff'" onBlur="this.className='files'" size="60" maxlength="60" type="text"></td>
      </tr>
      <tr>
        <td class="col1"><strong>Contact Phone
Number:</strong></td>
        <td class="edit_windows"><input name="new_contact_number" class="files" onKeyPress="return noenter()" onFocus="this.className='ff'" onBlur="this.className='files'" size="60" maxlength="60" type="text"></td>
      </tr>
      <tr>
        <td class="col1"><strong>Request</strong><br>
        <small>EXPLAIN IN DETAIL</small></td>
        <td class="edit_windows"><textarea name="new_body" cols="57" rows="10" class="files" onFocus="this.className='ff'" onBlur="this.className='files'"></textarea></td>
      </tr>
      <tr>
        <td class="col1" style="border-bottom: 1px dashed rgb(102, 102, 102);">&nbsp;</td>
        <td class="edit_windows" style="border-bottom: 1px dashed rgb(102, 102, 102);">&nbsp;</td>
      </tr>
      <tr>
        <td class="col1">&nbsp;</td>
        <td class="col1">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="col1">&nbsp;</td>
        <td class="col1">&nbsp;</td>
        <td>
       <p><input name="editok" class="submit" value="Submit" type="submit"></p>
        </td>
      </tr>
    </tbody>
  </table>
  <p><br>
  </p>
</form>
</div>
<a name="history"></a>
<h1>:: History</h1>
<div class="databox">
<p>View the <a href="index.php?page=g2-archive">the repair history for this <?=$domain_value?> here</a></p>
</div>
</div>
<?php else:
//Validate against null values
if ($new_location == "") {error("You must select a store location from the list before proceeding.");}
if ($new_body == "") {error("You must write something in the request area before proceeding");}
if ($new_type == "") {error("You must select a request type before proceeding");}
if ($new_priority == "") {error("You must select a priority before proceeding");}
// Process edit
dbConnect();
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
		break;
	}
// Get store region and district based on submited $new_location
	$sql = "select * from projects where id=$new_location";
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
//Set the sql statment..
	$sql = "insert into repair_orders set 
	project_id='$new_location',
	store_number='$store_number',
	store_district = '$store_district',
	store_region = '$store_region',
	type = '$new_type',
	priority = '$new_priority',
	urgency = '$new_urgency',
	status = 'pending',
	contact_name='$new_contact_name',
	contact_number='$new_contact_number',
	body = '$new_body',
	author = '$username',
	tracking = '$tracking',
	issue_date = CURDATE(),
	timestamp=NOW()";
	if (!mysql_query($sql)) {error("A database error occured: " . mysql_error());}
	//Call mail script
	$new_body = stripslashes($new_body);
	//$new_subject = stripslashes($new_subject);
	$new_contact_number = stripslashes($new_contact_number);
	$new_contact_name = stripslashes($new_contact_name);
	include("notify_g2.php");
	//Add to report
	$summary_msg .= "<p>The following message was e-mailed to corporate staff:</p>";
	$summary_msg .= "<div style=\"border:1px #ddd solid;background:#eee;padding:10px;\"><pre>$notice_text</pre></div>";
?>
<div id="content">
<h1>:: Repair Order Submitted</h1>
<div class="databox">
<p><?=$summary_msg?></p>
<p>[<a href="<?=$_SERVER['PHP_SELF']?>">Return to home page</a>]</p>
</div>
</div>
<?php 
	endif;
?>
</body>
</html>
<?php
	include("include/db.php");
	include("include/common.php");
	$id = $_GET['id'];
	$container_id = "box";
	$container_id .= $id;
	
	dbConnect();
	$sql = "select * from repair_orders where id=$id";
	$result = mysql_query($sql);
	
//Cultivate data set
	while ($row = mysql_fetch_array($result))
	{
		$id = $row["id"];
		$project_id = $row["project_id"];
		$body  = $row["body"];
		$author = $row["author"];
		$type = $row["type"];
		$priority = $row["priority"];
		$tracking = $row["tracking"];
		$contact_name = $row["contact_name"];
		$contact_number = $row["contact_number"];
		$store_number = $row["store_number"];
		$store_district = $row["store_district"];
		$store_region = $row["store_region"];	
		$ts = $row["timestamp"];
			$ts = revertTimestamp($ts);
		$issue_date = $row["issue_date"];
	$box_report .= "<span style=\"float:right;\"><a href=\"#\" title=\"Close\"  onClick=\"javascript:toggleBox('box$id',0);return false;\"><img src=\"images/close.gif\" border=\"0\" /></a></span>"; 	
	$box_report .= "<small><strong>Tracking:</strong> $tracking</small><br />";		
	$box_report .= "<small><strong>Type:</strong> $type</small><br />";
	$box_report .= "<small><strong>Priority:</strong> $priority</small><br />";
	$box_report .= "<p style=\"margin:0 4 0 4;color:#333;\">{$row["body"]}<br /><br />";
	$box_report .= "<strong>Contact Name: </strong>{$row["contact_name"]}<br />";
	$box_report .= "<strong>Contact Number: </strong>{$row["contact_number"]}<br />";
	$box_report .= "</p>";
	}



	$response_sql = mysql_query("SELECT COUNT(id) from repair_orders where status = 'response' and parent=$id");
	$response_count = mysql_result($response_sql,0);
	if ($response_count > 0) {
	$response_result = mysql_query("select * from repair_orders where status = 'response' and parent=$id");
    while($srow = mysql_fetch_array($response_result)) {
	 	$author = $srow["author"];
	 	$body = $srow["body"];
		$vendor = $srow["vendor"];
		$spec_vendor_contact = $srow["spec_vendor_contact"];
		$spec_vendor_comment = $srow["spec_vendor_comment"];
		$po_num = $srow["po_num"];
		$response_ts = $srow["timestamp"];
		$response_ts = revertTimestamp($response_ts);					 	
	    $box_report .= "<div style=\"margin-bottom: 5px;background:#eee;border:1px #ddd solid;\">";
	    $box_report .= "<p><small>Response from ".$author." on ".$response_ts."</small><br />".$body."<br />";
		if ($vendor != "") {$box_report .= "<small><strong>Vendor:</strong> $vendor</small><br />";}
		if ($spec_vendor_contact != "") {$box_report .= "<small><strong>Specific Vendor Contact</strong> $spec_vendor_contact</small><br />";}
		if ($spec_vendor_comment != "") {$box_report .= "<small><strong>Vendor Comment</strong> $spec_vendor_comment</small><br />";}
		if ($po_num != "") {$box_report .= "<small><strong>PO:</strong> $po_num</small>";}
		$box_report .= "</p></div>";
		}
	} else {
		$box_report .= "<p>No responses!</p>";
	}

if ($_GET['ug']=='g2') { $box_report .= "-"; } else {
//add edit features!
//write a response
$box_report .= "<a href=\"javascript:toggleBox('rebox".$id."',1);\">Add Response</a>&nbsp;";
$box_report .= "<div id=\"rebox".$id."\" style=\"display:none;\"><iframe frameborder=\"no\" width=\"100%\" height=\"350\" src=\"respond.php?db=g2&id=".$id."\" /></div>";
}


//Escape characters
$box_report = str_replace("'", "\'", $box_report);
$box_report = str_replace('"', "'+String.fromCharCode(34)+'", $box_report);
$box_report = str_replace ("\r\n", '\n', $box_report);
$box_report = str_replace ("\r", '\n', $box_report);
$box_report = str_replace ("\n", '\n', $box_report);
 ?>
div = document.getElementById('<?php echo $container_id; ?>');
div.innerHTML = '<?php echo $box_report; ?>';

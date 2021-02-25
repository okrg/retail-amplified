<?php

	include("include/db.php");
	include("include/common.php");
	$id = $_GET['id'];
	$container_id = "box";
	$container_id .= $id;
	
	dbConnect();
	$sql = "select * from fixture_orders where id=$id";
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
		$addreplace = $row["addreplace"];
		$store_number = $row["store_number"];
		$store_district = $row["store_district"];
		$store_region = $row["store_region"];	
		$ts = $row["timestamp"];
			$ts = revertTimestamp($ts);
		$issue_date = $row["issue_date"];
		$cartId = $row["cartId"];
		
		$style_query = mysql_query("select type_of_store, mannequin_style from projects where id=$project_id");
		$type_of_store = mysql_result($style_query, 0, "type_of_store");
		$mannequin_style = mysql_result($style_query, 0, "mannequin_style");

	$box_report .= "<span style=\"float:right;\"><a href=\"#\" title=\"Close\"  onClick=\"javascript:toggleBox('box$id',0);return false;\"><img src=\"images/close.gif\" border=\"0\" /></a></span>"; 	
	$box_report .= "<p><small><strong>Cart ID:</strong> $cartId ";
	$box_report .= "<strong>Type of Store:</strong> $type_of_store ";	
	$box_report .= "<strong>Priority:</strong> $priority</small><br />";
	$box_report .= "<small><strong>Comment:</strong> $body</small></p>";
	$box_report .= "<table width=\"99%\">";
	}
	$sql = "select * from fixture_tracker inner join fixture_key on fixture_tracker.itemId = fixture_key.id where fixture_tracker.trackerId='$cartId'";
	$result = mysql_query($sql);

	if (!$result){ $box_report .= mysql_error(); } else {

	while($row = mysql_fetch_array($result)) {
	$box_report .= "<tr>";
	if ($row['addBool'] == 1) {
		$addrep = "Add";
		} else {
		$addrep = "Replace";
		}


		$box_report .= "<td><strong>Item: </strong><a href=\"images/fixtures/".$row['name'].".jpg\" target=\"_blank\">".$row['name']."</a><br />";
		$box_report .= "<strong>Desc: </strong>".ucwords(strtolower($row['desc']))." <br />";
		if ($row['cat']== 'Body Forms') {
		$box_report .= "<strong>Mannequin Style:</strong> $mannequin_style <br />";
		}
		$box_report .= "<strong>Add or Replace:</strong> ".$addrep."<br />";
		$box_report .= "<strong>Qty: </strong>".$row['qty']."<br />";
		$box_report .= "<a href=\"javascript:toggleBox('rebox".$id."-".$row['name']."',1);\">Add Response</a>&nbsp;";
		$box_report .= "<div id=\"rebox$id-{$row['name']}\" style=\"display:none;\"><iframe frameborder=\"no\" width=\"300\" height=\"300\" src=\"respond.php?db=freq&name={$row['name']}&id=$id\"></iframe></div>";
		$box_report .= "</td>";
		$box_report .= "<td>";
		$response_sql = mysql_query("SELECT COUNT(id) from fixture_orders where status = 'response' and parent=$id and name='{$row['name']}'");
		$response_count = mysql_result($response_sql,0);
		if ($response_count > 0) {
		$response_result = mysql_query("select * from fixture_orders where status = 'response' and parent=$id and name='{$row['name']}'");
		while($srow = mysql_fetch_array($response_result)) {
			$followup = $srow["followup"];
			$author = $srow["author"];
			$body = $srow["body"];
			$vendor = $srow["vendor"];
			$ship_date = $srow["ship_date"];
			$response_ts = $srow["timestamp"];
			$response_ts = revertTimestamp($response_ts);					 	
			$box_report .= "<div style=\"padding:0 5px;margin: 5px 0;background:#eee;border:1px #ddd solid;\">";
			$box_report .= "<small>Response from ".$author;
			if ($followup ==  1) {
				$box_report .= "&nbsp;&nbsp;<img src=\"images/edit.gif\" align=\"absmiddle\" /> Requires <strong>follow up</strong>";
				}
			$box_report .= "<br />".$response_ts."</small><br />".$body."<br /><br />";
			if ($vendor != "") {
				$box_report .= "<small><strong>Vendor:</strong> $vendor</small><br />";
				}
			if ($ship_date != "2006-00-00") {
				$box_report .= "<small><strong>Ship Date:</strong> $ship_date</small><br />";
				}
			$box_report .= "</div>";
			}
		} else {
		$box_report .= "<p>&nbsp;</p>";
		}
		$box_report .= "</td>";
		$box_report .= "</tr>";
		$box_report .= "<tr><td colspan=\"2\"><hr /></td></tr>";

		}
	}
	$box_report .= "</table>";


//add edit features!
//write a response
//Escape characters
$box_report = str_replace("'", "\'", $box_report);
$box_report = str_replace('"', "'+String.fromCharCode(34)+'", $box_report);
$box_report = str_replace ("\r\n", '\n', $box_report);
$box_report = str_replace ("\r", '\n', $box_report);
$box_report = str_replace ("\n", '\n', $box_report);
 ?>
div = document.getElementById('<?php echo $container_id; ?>');
div.innerHTML = '<?php echo $box_report; ?>';

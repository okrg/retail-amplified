<?php
	$user_uid_rank = substr($uid,0,2);
	$user_domain = substr($uid,2);
	
	if ($user_uid_rank == "dm") {
		$domain_value="District";
		$db_string = "store_district";
		} elseif ($user_uid_rank == "rm") {
			$domain_value="Region";
			$db_string="store_region";
			}
?>
<div id="content">
<h1>:: View Fixture Requests</h1>
<div class="databox">
<p><a href="index.php">Home</a> &raquo; View Fixture Request Status</p>
<?php
//stores in this domain
	dbConnect();
	$sql = "select id, sitenum, sitename, store_number, store_district, store_region, chain, datetouched from projects where id = {$_GET['pid']}";
	//Execute filter on db!
	$result = mysql_query($sql);
	if (!$result){error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error());}
	
	if (mysql_num_rows($result) == 0) {print "<p><strong>Notice:</strong> Store record not found!</p>";
	}else{
	print "<div class=\"sortbox\">";
	$count=0;
	
//Cultivate data set
	while ($row = mysql_fetch_array($result)) {
		$sid = $row["id"];
		$store_number = $row["store_number"];
		$sitename = $row["sitename"];
		$chain=$row["chain"];
		if ($chain==1){$chain_name="Charlotte Russe";} elseif ($chain==2) {$chain_name="Rampage";}
		echo "<h2>Showing all requests for $chain_name #$store_number - $sitename</h2>";
		//Get status of fixtures for this project that are pending
		$fixture_result = mysql_query("select * from fixture_orders where project_id=$sid and status = 'pending'");
		$pending_fixtures = mysql_num_rows($fixture_result);
		if ($pending_fixtures == 0) {
			$fixtures_report .= "<small>0 Pending</small>";
		 } else {
		$fixtures_report .= " <small>$pending_fixtures Pending</small>";
		$box_report .= "<div align=\"center\"><small>----Pending Requests----</small></div>";
			while($row = mysql_fetch_array($fixture_result)) {		
				$id = $row["id"];
				$project_id = $row["project_id"];
				$body  = $row["body"];
				if ($body=="") {$body="None";}
				$author = $row["author"];
				$type = $row["type"];
				$priority = $row["priority"];
				$store_number = $row["store_number"];
				$store_district = $row["store_district"];
				$store_region = $row["store_region"];	
				$ts = $row["timestamp"];
				$ts = revertTimestamp($ts);
				$issue_date = $row["issue_date"];
				$cartId = $row["cartId"];

				$box_report .= "<p><strong>Date:</strong> $issue_date <strong>Cart ID:</strong> $cartId <br />";
				$box_report .= "<strong>Store#</strong> $store_number <strong>Dist#</strong> $store_district <strong>Priority:</strong> $priority<br />";
				$box_report .= "<strong>Comment:</strong> $body<br />";
				$box_report .= "<strong>Request:</strong></p>";
				$sql = "select * from fixture_tracker inner join fixture_key on fixture_tracker.itemId = fixture_key.id where fixture_tracker.trackerId='$cartId'";
				$result = mysql_query($sql);
				if (!$result){ $box_report .= mysql_error(); } else {
						$box_report .= "<ol>";
							while($row = mysql_fetch_array($result)) {
								if ($row['addBool'] == 1) {
								$addrep = "Add";
								} else {
								$addrep = "Replace";
								}
							$box_report .= "<li><strong>Item: </strong>".$row['name']."<br /><strong>Desc: </strong>".ucwords(strtolower($row['desc']))."<br /><strong>Qty: </strong>".$row['qty']."<br /><strong>Add or Replace: </strong>".$addrep."</li>";
							}
						$box_report .= "</ol>";
						$box_report .= "<hr />";
					}
			}		 
		 
		 }
 		//Get status of fixtures for this project that are answered
		$fixture_result = mysql_query("select * from fixture_orders where project_id=$sid and status = 'answered'");
		$answered_fixtures = mysql_num_rows($fixture_result);
		if ($answered_fixtures == 0) {
			$fixtures_report .= ", <small>0 Answered</small>";
		 } else {
		$fixtures_report .= ", <small>$answered_fixtures Answered</small>";
		$box_report .= "<div align=\"center\"><small>----answered Requests----</small></div>";
			while($row = mysql_fetch_array($fixture_result)) {		
				$id = $row["id"];
				$project_id = $row["project_id"];
				$body  = $row["body"];
				if ($body=="") {$body="None";}
				$author = $row["author"];
				$type = $row["type"];
				$priority = $row["priority"];
				$store_number = $row["store_number"];
				$store_district = $row["store_district"];
				$store_region = $row["store_region"];	
				$ts = $row["timestamp"];
				$ts = revertTimestamp($ts);
				$issue_date = $row["issue_date"];
				$cartId = $row["cartId"];

				$box_report .= "<p><strong>Date:</strong> $issue_date <strong>Cart ID:</strong> $cartId<br />";
				$box_report .= "<strong>Store#</strong> $store_number <strong>Dist#</strong> $store_district <strong>Priority:</strong> $priority<br />";
				$box_report .= "<strong>Comment:</strong> $body<br />";
				$box_report .= "<strong>Request:</strong></p>";
				$sql = "select * from fixture_tracker inner join fixture_key on fixture_tracker.itemId = fixture_key.id where fixture_tracker.trackerId='$cartId'";
				$result = mysql_query($sql);
				if (!$result){ $box_report .= mysql_error(); } else {
						$box_report .= "<ol>";
							while($row = mysql_fetch_array($result)) {
								if ($row['addBool'] == 1) {
								$addrep = "Add";
								} else {
								$addrep = "Replace";
								}
							$box_report .= "<li><strong>Item:</strong>".$row['name']."<br /><strong>Desc:</strong>".ucwords(strtolower($row['desc']))."<br /><strong>Qty:</strong>".$row['qty']."<br /><strong>Add or Replace:</strong>".$addrep."</li>";
							$response_sql = mysql_query("SELECT COUNT(id) from fixture_orders where status = 'response' and parent=$id and name='{$row['name']}'");
							$response_count = mysql_result($response_sql,0);
							if ($response_count > 0) {
							$response_result = mysql_query("select * from fixture_orders where status = 'response' and parent=$id and name='{$row['name']}'");
							while($srow = mysql_fetch_array($response_result)) {
								$author = $srow["author"];
								$body = $srow["body"];
								$vendor = $srow["vendor"];
								$ship_date = $srow["ship_date"];
								$response_ts = $srow["timestamp"];
								$response_ts = revertTimestamp($response_ts);					 	
								$box_report .= "<div style=\"padding:0 5px;margin-bottom: 5px 0;background:#eee;border:1px #ddd solid;\">";
								$box_report .= "<small>Response from ".$author."<br />".$response_ts."</small><br /><br />".$body."<br /><br />";
								if ($vendor != "") {
									$box_report .= "<small><strong>Vendor:</strong> $vendor</small><br />";
									}
								if ($ship_date != "0000-00-00") {
									$box_report .= "<small><strong>Ship Date:</strong> $ship_date</small><br />";
									}
								$box_report .= "</div>";
								}
							}

							}

						$box_report .= "</ol>";
						$box_report .= "<hr />";
					}
			}		 
		 
		 }

	}
	 if (($pending_fixtures == 0) && ($answered_fixtures == 0)) {$fixtures_report .= "<small>No issues</small>";}
	 echo $fixtures_report;
	 echo "<div class=\"bigshinybutton\">$box_report</div>";
	 }
?>
</div></div>

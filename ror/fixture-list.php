<?php
dbConnect();
if ($usergroup < 2) {
	$sql = "SELECT DISTINCT store_district, store_region FROM projects WHERE store_number > 0 AND store_district > 0 ORDER  BY store_region";
}
if ($usergroup == 2) {
	$g = g2filter($uid);
	$sql = "SELECT DISTINCT store_district FROM projects WHERE store_district = $g[0]";
	}
$result = mysql_query($sql);
while($row = mysql_fetch_object($result)) {
	if ($usergroup < 2) {
	$dvp_ok_total = mysql_do("SELECT sum(cost*qty) from fixture_requests,fixture_key, projects where fixture_requests.status = 'vp_ok' and fixture_requests.loc_key = projects.id and projects.store_district = ".$row->store_district." and fixture_requests.fix_key = fixture_key.id");
	}

if ($usergroup == 2) {
	$dvp_ok_total = mysql_do("SELECT sum(cost*qty) from fixture_requests,fixture_key, projects where fixture_requests.status = 'vp_ok' and fixture_requests.loc_key = projects.id and projects.store_district = ".$g[0]." and fixture_requests.fix_key = fixture_key.id");
}
	echo "<p class=\"keydistrict\"><span style=\"float:right;font-weight:bold;\">&nbsp;Approved: $".number_format($dvp_ok_total,2)." &nbsp;</span> District ".intval($row->store_district)."</p>";
	if(isset($_GET['filter'])){
		$sqlx = "SELECT store_number,sitename, id, sitecity, sitestate, top10 FROM projects WHERE ".$_GET['filter']." = ".$_GET['q']." AND chain = 1 ORDER  BY store_district,store_number";
	}else{
		$sqly = "SELECT store_number, sitename,id,sitecity, sitestate, top10 FROM projects WHERE store_district = '".$row->store_district."' AND chain = 1 ORDER  BY store_district,store_number";
	}
	$resulty = mysql_query($sqly);
	echo "<ul class=\"fixture_list\">";
	while($rowy = mysql_fetch_object($resulty)) {
		echo "<li class=\"loc bigger\">";

		$rm_ok_total = mysql_do("SELECT sum(cost*qty) from fixture_requests,fixture_key where fixture_requests.status = 'rm_ok' and fixture_requests.fix_key = fixture_key.id and fixture_requests.loc_key = $rowy->id");		
	
		$vp_ok_total = mysql_do("SELECT sum(cost*qty) from fixture_requests,fixture_key where fixture_requests.status = 'vp_ok' and fixture_requests.fix_key = fixture_key.id and fixture_requests.loc_key = $rowy->id");		
	
		$waiting_total = mysql_do("SELECT sum(cost*qty) from fixture_requests,fixture_key where fixture_requests.status = 'waiting' and fixture_requests.fix_key = fixture_key.id and fixture_requests.loc_key = $rowy->id");		

		if ($vp_ok_total > 0) {echo "<span class=\"vp_ok\" style=\"float:right;font-weight:bold;\">&nbsp;Approved: $".number_format($vp_ok_total,2)." &nbsp;</span>";}
		//if ($waiting_total > 0) {echo "<span class=\"waiting\" style=\"float:right;font-weight:bold;\">&nbsp;Pending: $".number_format($waiting_total,2)." &nbsp;</span>";}

		echo " <b><a href=\"javascript:ViewPop('fixture-view.php?id=$rowy->id','view');\">".intval($rowy->store_number)." $rowy->sitename</a></b> ";
		if ($rowy->top10 == 1) {echo "<img src=\"images/trophy.gif\" align=\"absmiddle\" /> ";}

		echo "($rowy->sitecity, $rowy->sitestate)</li>";
		
		//Filter by status
		if(isset($_GET['status'])){
			$sqlx = "SELECT * from fixture_requests,fixture_key where fixture_requests.status = '".$_GET['status']."' and fixture_requests.fix_key = fixture_key.id and fixture_requests.loc_key = $rowy->id";	
		} else {
			//Default for dms
			if ($usergroup == 2) {
				$sqlx = "SELECT * from fixture_requests,fixture_key where fixture_requests.status = 'waiting' and  fixture_requests.fix_key = fixture_key.id and fixture_requests.loc_key = $rowy->id";
			} else {
				$sqlx = "SELECT * from fixture_requests,fixture_key where fixture_requests.status = 'rm_ok' and  fixture_requests.fix_key = fixture_key.id and fixture_requests.loc_key = $rowy->id";
			}
		}
	
		$resultx = mysql_query($sqlx);
		if (mysql_num_rows($resultx)>0){
	
			while($rowx = mysql_fetch_object($resultx)) {
				echo "<li class=\"$rowx->status\">";
				echo "<span style=\"float:right;\">$rowx->qty @ $".$rowx->cost."/ea. = $".number_format($rowx->qty*$rowx->cost,2)."</span>";

				echo "<b>$rowx->name</b> - ".ucwords(strtolower($rowx->desc))." [$rowx->category]";
				echo "</li>";
			}
		}
	}
	echo "</ul>";
}

?>
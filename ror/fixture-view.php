<?php //view.php
error_reporting(E_ALL ^ E_NOTICE);
include(realpath(dirname(dirname(__FILE__)))."/include/access.php");
include(realpath(dirname(dirname(__FILE__)))."/include/rt.php");
$season = "jun19";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>Viewer</title>
<link rel="stylesheet" href="rt.css" />
<script type="text/javascript" src="rt.js"></script>
<script type="text/javascript" src="multifile.js"></script>
<script type="text/javascript" src="scriptaculous/prototype.js"></script>
<script type="text/javascript" src="scriptaculous/scriptaculous.js"></script>
<script type="text/javascript" src="scriptaculous/Ajax.InPlaceSelect.js"></script>
</head>
<body id="pop">
<?php
//Fill the g2filter
if ($usergroup == 2) {$g = g2filter($uid);}
//View Strip Code Simplified
$id = mysqli_escape_string($dbcnx, $_GET['id']);
$sql="SELECT * from projects where id = $id";
$result = mysqli_query($dbcnx, $sql);
if (!$result) {error("View-Strip Error with database: ".$sql.mysqli_error($dbcnx));}
$loc = mysqli_fetch_object($result);
//Show the drop down nav items
echo "<script type=\"text/javascript\"><!--//--><![CDATA[//><!--
	startList = function() {
		if (document.all&&document.getElementById) {
			navRoot = document.getElementById(\"stripnav\");
			for (i=0; i<navRoot.childNodes.length; i++) {
				node = navRoot.childNodes[i];
				if (node.nodeName==\"LI\") {
					node.onmouseover=function() {
						this.className+=\" over\";
					}
					node.onmouseout=function() {
						this.className=this.className.replace(\" over\", \"\");
					}
				}
			}
		}
	}
	window.onload=startList;
	//--><!]]></script>
";	


//Create columns
echo "<table width=\"100%\" id=\"datarows\" border=\"0\" cellpadding=\0\" cellspacing=\"0\"><thead>"
	."<tr style=\"text-align:left;font-size:11px;\">"
	."<th class=\"norm\">Location</th>"
	."<th class=\"norm\">City</th>"
	."<th class=\"norm\">State</th>"
	."<th class=\"norm\">District</th>"
	."<th class=\"norm\">Region</th>"
	."</tr></thead>"
	."<tr>"
	."<td class=\"norm\">";
	if ($loc->high_volume_store == 1) {echo " <img src=\"images/star.gif\" /> ";}
	if ($loc->potential_remodel_store == 1) {echo " <img src=\"/images/config.gif\" /> ";}
	if ($loc->top10 == 1) {echo " <img src=\"images/trophy.gif\" align=\"absmiddle\" /> ";}
echo "#".intval($loc->store_number)." "
	.myTruncate($loc->sitename,30, " ")."</td>"
	."<td class=\"norm\">$loc->sitecity</td>"
	."<td class=\"norm\">$loc->sitestate</td>"
	."<td class=\"norm\">".intval($loc->store_district)."</td>"
	."<td class=\"norm\">".intval($loc->store_region)."</td>"
	."</tr>"
	."</table>"

	."<div id=\"maincontainer\">";

if ($loc->top10==0)
$notices .= "<blockquote><strong>Notice:</strong> This is not a top 10 store, so all requests must be first approved by RM before receiving VP approval.</blockquote>";

//if ($usergroup == 2 || $loc->top10 == 1) {
	//request fixture data for RMs to view
	$sqlx = "SELECT *, fixture_requests.id as request_id from fixture_requests,fixture_key where fixture_requests.status = 'waiting' and season = '$season' and fixture_requests.fix_key = fixture_key.id and fixture_requests.loc_key = $id";
	$resultx = mysqli_query($dbcnx, $sqlx);
	if (mysqli_num_rows($resultx)>0){
		if ($loc->top10==1){$qtype="vp";}else{$qtype="rm";}
		
		echo "<form name=\"waiting\" method=\"POST\" action=\"fixture-commit.php?q=$qtype\" class=\"fixture\">"
		."<input type=\"hidden\" name=\"id\" value=\"$id\" />"
		."<h2>Pending Requests</h2>"
		."<div class=\"box\">";

		if ($loc->top10 == 1) {echo "<div class=\"box smaller\"><img src=\"images/trophy.gif\" align=\"absmiddle\" /> This is a top 10 store. VP may approve new request.</div>";}

		if (mysqli_num_rows($resultx)>1)echo "<a href=\"#\" onClick=\"check(document.waiting.elements['requests[]']);return false;\"><small>Select All</small></a>";

		echo "<ul class=\"fixture_list\">";
		
		while($rowx = mysqli_fetch_object($resultx)) {
			echo "<li class=\"$rowx->status\">"
			."<span style=\"float:right;\">$rowx->qty @ $".$rowx->cost."/ea = $".number_format($rowx->qty*$rowx->cost,2)."</span>";
		
			if ($usergroup < 2 || ($usergroup == 2 && $g[1] == "store_region")) {
			echo "<input type=\"checkbox\" name=\"requests[]\" value=\"".$rowx->request_id."\" id=\"req".$rowx->request_id."\" />";
			}

			echo "<label for=\"req".$rowx->request_id."\" ><b>$rowx->name</b> - ".ucwords(strtolower($rowx->desc))." [$rowx->category]</label>";

			echo "</li>";
			$total+=number_format($rowx->qty*$rowx->cost,2);
		}
		echo "<li class=\"summary\">";
		if ($usergroup < 2 || ($usergroup == 2 && $g[1] == "store_region")) {
			echo "<div class=\"oktools\">"
				."<span><input type=\"submit\" value=\"".strtoupper($qtype)." Approve\" name=\"ok\" /></span>"
				."<span><input type=\"submit\" value=\"".strtoupper($qtype)." Deny\" name=\"deny\" /></span>";
		
			if ($usergroup < 2 && $loc->top10 ==1) echo "<span><input type=\"submit\" value=\"VP Approve - Ship Early\" name=\"expedite\" /></span>";
		
			echo "</div>";
		}
		echo "Total Pending: $".number_format($total,2)."</li>"
		."</ul>";

		echo "<div class=\"cleary\"></div>"
		."</div>"
		."</form>";
	} else {
	$notices .= "<blockquote><strong>Notice:</strong>No waiting requests to show. <a href=\"javascript:window.close();\">Click here to close this window.</a></blockquote>";
	}
//} 


	$sqlx = "SELECT *, fixture_requests.id as request_id from fixture_requests,fixture_key where fixture_requests.status = 'rm_ok' and season = '$season' and fixture_requests.fix_key = fixture_key.id and fixture_requests.loc_key = $id";
	$resultx = mysqli_query($dbcnx, $sqlx);
	if (mysqli_num_rows($resultx)>0){
		$qtype="vp";
		echo "<form name=\"rm_ok\" method=\"POST\" action=\"fixture-commit.php?q=$qtype\" id=\"editor\" class=\"fixture\">"
		."<input type=\"hidden\" name=\"id\" value=\"$id\" />"
		."<h2>RM Approved Requests</h2>"
		."<div class=\"box\">";

		if (mysqli_num_rows($resultx)>1) echo "<a href=\"#\" onClick=\"check(document.rm_ok.elements['requests[]']);return false;\"><small>Select All</small></a>";

		echo "<ul class=\"fixture_list\">";
		while($rowx = mysqli_fetch_object($resultx)) {
			echo "<li class=\"$rowx->status\">"
			."<span style=\"float:right;\">$rowx->qty @ $".$rowx->cost."/ea = $".number_format($rowx->qty*$rowx->cost,2)."</span>";

			if ($usergroup < 2)
				echo "<input type=\"checkbox\" name=\"requests[]\" value=\"".$rowx->request_id."\" id=\"req".$rowx->request_id."\" />";
				
			echo "<b>$rowx->name</b> - ".ucwords(strtolower($rowx->desc))." [$rowx->category]";

			if ($rowx->rm_approval != "0000-00-00") echo "<img src=\"/ror/images/rm_approval.gif\" align=\"absmiddle\" title=\"$rowx->rm_approval\"/>";
			if ($rowx->rm_deny != "0000-00-00") echo "<img src=\"/ror/images/rm_deny.gif\" align=\"absmiddle\" title=\"$rowx->rm_deny\"/>";
			if ($rowx->vp_approval != "0000-00-00") echo "<img src=\"/ror/images/vp_approval.gif\" align=\"absmiddle\" title=\"$rowx->vp_approval\"/>";
			if ($rowx->vp_deny != "0000-00-00") echo "<img src=\"/ror/images/vp_deny.gif\" align=\"absmiddle\" title=\"$rowx->vp_deny\"/>";									
			if ($rowx->processed != "0000-00-00") echo "<img src=\"/ror/images/processed.gif\" align=\"absmiddle\" title=\"$rowx->processed\"/>";

			echo "</li>";
			$rmok_total+=number_format($rowx->qty*$rowx->cost,2);
		}
		echo "<li class=\"summary\">";
		
		if ($usergroup < 2) {
			echo "<div class=\"oktools\">"
			."<span><input type=\"submit\" value=\"".strtoupper($qtype)." Approve\" name=\"ok\" /></span>"
			."<span><input type=\"submit\" value=\"".strtoupper($qtype)." Deny\" name=\"deny\" /></span>"
			."<span><input type=\"submit\" value=\"VP Approve - Ship Early\" name=\"expedite\" /></span>"
			."</div>";
		}

		echo "Total RM Approved: $".number_format($rmok_total,2)
		."</li>"
		."</ul>";



		echo "<div class=\"cleary\"></div>"
		."</div>"
		."</form>";
	} else {
	$notices .=  "<blockquote><strong>Notice:</strong> No RM approved requests to show. <a href=\"javascript:window.close();\">Click here to close this window.</a></blockquote>";
	}
	
	$sqlx = "SELECT *,fixture_requests.id as request_id from fixture_requests,fixture_key where fixture_requests.status = 'vp_ok' and season = '$season' and fixture_requests.fix_key = fixture_key.id and fixture_requests.loc_key = $id";
	$resultx = mysqli_query($dbcnx, $sqlx);
	if (mysqli_num_rows($resultx)>0){
		echo "<form name=\"vp_ok\" method=\"POST\" action=\"fixture-commit.php?q=corp\" class=\"fixture\">"
		."<input type=\"hidden\" name=\"id\" value=\"$id\" />"
		."<h2>VP Approved Requests</h2>"
		."<div class=\"box\">";
		if (mysqli_num_rows($resultx)>1)echo "<a href=\"#\" onClick=\"check(document.vp_ok.elements['requests[]']);return false;\"><small>Select All</small></a>";


		echo "<ul class=\"fixture_list\">";
		while($rowx = mysqli_fetch_object($resultx)) {
			if ($rowx->expedite == 1) {echo "<li class=\"expedite\">";}
			else { echo "<li class=\"$rowx->status\">";}
		
			echo "<span style=\"float:right;\">$rowx->qty @ $".$rowx->cost."/ea = $".number_format($rowx->qty*$rowx->cost,2)."</span>";
		
			if ($usergroup < 2)	echo "<input type=\"checkbox\" name=\"requests[]\" value=\"".$rowx->request_id."\" id=\"req".$rowx->request_id."\" />";
		
			if ($rowx->expedite == 1) echo "<img src=\"/ror/images/expedite.gif\" align=\"absmiddle\" title=\"Approved to Ship Early\"/>&nbsp;";
		
			echo "<b>$rowx->name</b> - ".ucwords(strtolower($rowx->desc))." [$rowx->category]";
		
			if ($rowx->rm_approval != "0000-00-00") echo "<img src=\"/ror/images/rm_approval.gif\" align=\"absmiddle\" title=\"$rowx->rm_approval\"/>";
			if ($rowx->rm_deny != "0000-00-00") echo "<img src=\"/ror/images/rm_deny.gif\" align=\"absmiddle\" title=\"$rowx->rm_deny\"/>";
			if ($rowx->vp_approval != "0000-00-00") echo "<img src=\"/ror/images/vp_approval.gif\" align=\"absmiddle\" title=\"$rowx->vp_approval\"/>";
			if ($rowx->vp_deny != "0000-00-00") echo "<img src=\"/ror/images/vp_deny.gif\" align=\"absmiddle\" title=\"$rowx->vp_deny\"/>";									
			if ($rowx->processed != "0000-00-00") echo "<img src=\"/ror/images/processed.gif\" align=\"absmiddle\" title=\"$rowx->processed\"/>";
			
			echo "</li>";
			$vpok_total+=number_format($rowx->qty*$rowx->cost,2);
		}
		echo "<li class=\"summary\">";

		if ($usergroup < 2) {
			echo "<div class=\"oktools\">"
			."<span><input type=\"submit\" value=\"Processed\" name=\"processed\" /></span>"
			."</div>";
		}
		echo "Total VP Approved: $".number_format($vpok_total,2)."</li>"
		."</ul>";

		echo "<div class=\"cleary\"></div>"
		."</div>"
		."</form>";
	} else {
	$notices .= "<blockquote><strong>Notice:</strong> No VP approved requests to show. <a href=\"javascript:window.close();\">Click here to close this window.</a></blockquote>";
	}

echo $notices;
echo "</div>";
?>
</body>
</html>
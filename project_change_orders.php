<?php
	//See if the user has the plans role
	$rsql = "select roles from companies where company_id = $usercompany";
	$rq = mysql_query($rsql);
	$ro = mysql_result($rq,0,"roles");
	$roles =  explode(",",$ro);
	
	
	//FIND CHANGE ORDERS FROM DATABASE
	if ($usergroup < 1) {$sql = "select * from change_orders where loc_key = $id";}
	else {$sql = "select * from change_orders where loc_key = $id and company_key = $usercompany";}
	$result = mysql_query($sql);
	$co_count = mysql_num_rows($result);


	//Render table!	
	echo "<table style=\"border:1px #000 solid;\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
	echo "<tr>";
	echo "<th class=\"files\" align=\"left\">CO#</th>";
	echo "<th class=\"files\" align=\"left\">Submitted By</th>";
	echo "<th class=\"files\" align=\"left\">Date</th>";
	echo "<th class=\"files\" align=\"left\">Pending Total</th>";
	echo "<th class=\"files\" align=\"left\">Declined Total</th>";	
	echo "<th class=\"files\" align=\"left\" style=\"border-right:none;\">Approved Total</th>";
	echo "</tr>";
	
	$approved_total = 0;
	$pending_total = 0;
	$declined_total = 0;
	while ($co = mysql_fetch_object($result)) {
		//get user details
		$asql = "select fullname, company_name from users, companies where id = {$co->author_key} and users.company_id=companies.company_id";
		$ares = mysql_query($asql);
		$author = mysql_fetch_object($ares);
		
		$psum=0;
		if ($co->li1_status == "Pending") $psum = $psum+$co->li1_cost;
		if ($co->li2_status == "Pending") $psum = $psum+$co->li2_cost;
		if ($co->li3_status == "Pending") $psum = $psum+$co->li3_cost;
		if ($co->li4_status == "Pending") $psum = $psum+$co->li4_cost;
		if ($co->li5_status == "Pending") $psum = $psum+$co->li5_cost;								
		if ($co->li6_status == "Pending") $psum = $psum+$co->li6_cost;		
		$pending_total = $psum+$pending_total;
		$psum = number_format($psum,2);

		$asum=0;
		if ($co->li1_status == "Approved") $asum = $asum+$co->li1_cost;
		if ($co->li2_status == "Approved") $asum = $asum+$co->li2_cost;
		if ($co->li3_status == "Approved") $asum = $asum+$co->li3_cost;
		if ($co->li4_status == "Approved") $asum = $asum+$co->li4_cost;
		if ($co->li5_status == "Approved") $asum = $asum+$co->li5_cost;								
		if ($co->li6_status == "Approved") $asum = $asum+$co->li6_cost;
		$approved_total = $asum+$approved_total;
		$asum = number_format($asum,2);	
		
		$dsum=0;
		if ($co->li1_status == "Declined") $dsum = $dsum+$co->li1_cost;
		if ($co->li2_status == "Declined") $dsum = $dsum+$co->li2_cost;
		if ($co->li3_status == "Declined") $dsum = $dsum+$co->li3_cost;
		if ($co->li4_status == "Declined") $dsum = $dsum+$co->li4_cost;
		if ($co->li5_status == "Declined") $dsum = $dsum+$co->li5_cost;								
		if ($co->li6_status == "Declined") $dsum = $dsum+$co->li6_cost;
		$declined_total = $dsum+$declined_total;
		$dsum = number_format($dsum,2);	
		
		
		echo "<tr>"
		."<td class=\"files\"><a href=\"$PHP_SELF?page=change_order&id=$co->id\">$co->co_num</a></td>"
		."<td class=\"files\">$author->fullname @ $author->company_name</td>"
		."<td class=\"files\">$co->date</td>"
		."<td class=\"files\">$$psum</td>"
		."<td class=\"files\">$$dsum</td>"		
		."<td class=\"files\">$$asum</td>"
		."</tr>";
	}
	
	echo "<tr>";
	echo "<td class=\"files\" style=\"border-bottom:1px #000 solid;\" colspan=\"6\">&nbsp;</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td class=\"files\" style=\"border-bottom:1px #000 solid;\" colspan=\"3\">Total</td>";
	echo "<td class=\"files\" style=\"border-bottom:1px #000 solid;\">$".number_format($pending_total,2)."</td>";
	echo "<td class=\"files\" style=\"border-bottom:1px #000 solid;\">$".number_format($declined_total,2)."</td>";
	echo "<td class=\"files\" style=\"border-bottom:1px #000 solid;\">$".number_format($approved_total,2)."</td>";
	echo "</tr>";


	//Offer creation option to admins, show object count to all users
	echo "<tr>";
	echo "<td class=\"files\" colspan=\"6\">";
	echo "$co_count Change Order(s)<br />";
	
	
	if (($usergroup == 0) or (in_array("change_orders",$roles))) {
		echo "<img src=\"images/plus.gif\" align=\"absmiddle\" />";
		echo"<a href=\"#\" onclick=\"javascript:$('#submit-new').toggle(400);return false;\">Submit New Change Order</a>";
		echo "&nbsp;&nbsp;";
	}

?>
	<br />
	<table id="submit-new" class="tog" align="center" cellspacing="8" cellpadding="8">
	<tr>
	<td><img src="images/avatar_single.gif" /></td>
	<td>
	<h2>::Submit a new change order</h2>
	<form name="submit_co" method="post" action="<?php echo "$PHP_SELF?page=project&mode=change_order"; ?>" enctype="multipart/form-data" onSubmit="return validateSingle(this);">
		<input type="hidden" name="project_name" value="<?php print($sitename); ?>"></input>
        <input type="hidden" name="store_number" value="<?php print(intval($store_number)); ?>"></input>
		<input type="hidden" name="project_id" value="<?php print($id); ?>"></input>
		<table width="100%" cellspacing="2" class="litezone">

        <tr>
		<td valign="top"><h1>1</h1><small><strong>Description</strong><br /></small></td>
		<td><textarea class="files" name="li1_desc" cols="60" rows="3"></textarea></td>
		</tr>
		<tr>
		<td valign="top"><small><strong>Pending Cost $</strong></small></td>
		<td><input class="files" type="text" size="8" name="li1_cost" /></td>
		</tr>
        <tr>
        <td class="patt" colspan="2"></td>
        </tr>
        <tr>
		<td valign="top"><h1>2</h1><small><strong>Description</strong><br /></small></td>
		<td><textarea class="files" name="li2_desc" cols="60" rows="3"></textarea></td>
		</tr>
		<tr>
		<td valign="top"><small><strong>Pending Cost $</strong></small></td>
		<td><input class="files" type="text" size="8" name="li2_cost" /></td>
		</tr>
        <tr>
        <td class="patt" colspan="2"></td>
        </tr>
        <tr>
		<td valign="top"><h1>3</h1><small><strong>Description</strong><br /></small></td>
		<td><textarea class="files" name="li3_desc" cols="60" rows="3"></textarea></td>
		</tr>
		<tr>
		<td valign="top"><small><strong>Pending Cost $</strong></small></td>
		<td><input class="files" type="text" size="8" name="li3_cost" /></td>
		</tr>
        <tr>
        <td class="patt" colspan="2"></td>
        </tr>

        <tr>
		<td valign="top"><h1>4</h1><small><strong>Description</strong><br /></small></td>
		<td><textarea class="files" name="li4_desc" cols="60" rows="3"></textarea></td>
		</tr>
		<tr>
		<td valign="top"><small><strong>Pending Cost $</strong></small></td>
		<td><input class="files" type="text" size="8" name="li4_cost" /></td>
		</tr>
        <tr>
        <td class="patt" colspan="2"></td>
        </tr>

        <tr>
		<td valign="top"><h1>5</h1><small><strong>Description</strong><br /></small></td>
		<td><textarea class="files" name="li5_desc" cols="60" rows="3"></textarea></td>
		</tr>
		<tr>
		<td valign="top"><small><strong>Pending Cost $</strong></small></td>
		<td><input class="files" type="text" size="8" name="li5_cost" /></td>
		</tr>
        <tr>
        <td class="patt" colspan="2"></td>
        </tr>

        <tr>
		<td valign="top"><h1>6</h1><small><strong>Description</strong><br /></small></td>
		<td><textarea class="files" name="li6_desc" cols="60" rows="3"></textarea></td>
		</tr>
		<tr>
		<td valign="top"><small><strong>Pending Cost $</strong></small></td>
		<td><input class="files" type="text" size="8" name="li6_cost" /></td>
		</tr>        
        <tr>
        <td class="patt" colspan="2"></td>
        </tr>
		<tr>
		<td align="right"><img src="images/mail.gif" /></td>
		<td><input name="notify" type="checkbox"><small>Send notification e-mail</small></input></td>
		</tr>
		<tr>
		<td></td>
		<td>
		<input class="submit" type="submit" name="submit" value="Submit">&nbsp;
		<input class="submit" type="reset" name="reset" value="Cancel" onClick="javascript:$('#submit-new').toggle(400);return false;">
		</td>
		</tr>
		</table>
	</form>  

	</td>
	</tr>
	</table>


<?php
	echo "</td>";
	echo "</tr>";
	echo "</table>";
?>
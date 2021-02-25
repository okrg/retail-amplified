<?

//Set sorting mechanism, default to "Region"
if (!isset($sort)) {$sortstyle = "Region";} else {$sortstyle = $sort;}
	switch ($sort) {
		case "datetouched": $sortstyle="date last modified"; $sort = "datetouched desc";break;
		case "store_region": $sortstyle="store region";break;
		case "sitenum": $sortstyle="job number";break;
		case "store_district": $sortstyle="store district";break;
		case "store_number": $sortstyle="store number";break;
		case "sitename": $sortstyle="location name";break;
		case "chain": $sortstyle="store chain";break;
		default: $sortstyle="date last modified"; $sort = "datetouched desc";break;	
		}
?>
<div id="content">
<h1>:: Announcement</h1><a name="announce"></a>
<div class="databox">
	<?php
		//This part of the script digs out the announcement and displays it to the user.
		//Depending on what group the user is a part of, the announcement will display.
		dbConnect();
		if ($usergroup == 3) {
			//Show vendor announcement
			$sql = "select * from blog where readers='vendors'";
		} else {
			//Show regular announcement
			$sql = "select * from blog where readers='users'";
		}
		$result = mysql_query($sql);
		if (!$result) {error("A databass error has occured in processing your request.\\n". mysql_error());}
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
	<h2><?=$subject?></h2>
	<p><small>Posted by <?=$author?> on <?=$ts?></small></p>
	<p><?=$body?></p>
	
	<?php
	//Show Administration option to those in group 0.
	if ($usergroup == 0) {
		echo "<p><a href=\"index.php?page=edit-announce&readers=users\">Edit this announcement.</a><br />";
		echo "<a href=\"index.php?page=edit-announce&readers=vendors\">Edit the vendor announcement.</a></p>";
	}
	?>
</div>
<?php
//Corporate employees see all weekly reports.
//Vendors only see reports if their role includes weekly reports

if ((in_array("weekly",$roles)) or ($usergroup < 2)) {
?>
<h1>:: Weekly Reporting</h1>
<div class="databox">
  <?php
	//This is the weekly reports module.
	$filespace = "filespace/weekly/";
	
	//Connects to the weekly report database and reads who has uploaded what and then has links to these files.
	if ($usergroup<2) {
		echo "<p>Listed below are the weekly report files.</p>";
		echo "<div class=\"sortbox\">";
		echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
		echo "<tr>";
		echo "<th>File</th>";
		echo "<th>Date</th>";
		echo "<th>Vendor</th>";
		//echo "<th>Uploaded By</th>";
		echo "</tr>";
		//Get weekly reports for employees
		$wr = mysql_query("select * from weekly");
		while($row=mysql_fetch_object($wr)) {			
			$cr = mysql_query("select company_name from companies where company_id = ".$row->company."");
			if (!@mysql_result($cr,0,"company_name")){
				$cr_var = "<em>Company deleted</em>";
				}else{
				$cr_var = mysql_result($cr,0,"company_name");
				}

			$ur = mysql_query("select fullname from users where id = ".$row->user."");
			if (!@mysql_result($ur,0,"fullname")){
				$ur_var = "<em>User deleted</em>";
				}else{
				$ur_var = mysql_result($ur,0,"fullname");
				}

			echo "<tr>";
			echo "<td><a href=\"".$filespace."".$row->filename."\">$row->filename</a></td>";
			echo "<td>".filedate($row->timestamp)."</td>";
			echo "<td>$cr_var</td>";//company name
			//echo "<td>$ur_var</td>";//user name
			echo "</tr>";
		}
		echo "</table>";
		echo "</div>";
	} else {
		//Get weekly report info for this vendor
		$cr = mysql_query("select company_name from companies where company_id = $usercompany");
		$vendor = mysql_result($cr,0,"company_name");
		echo "<p>Current weekly report for <strong>$vendor</strong></p>";
		echo "<div class=\"sortbox\">";
		echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
		echo "<tr>";
		echo "<th>File</th>";
		echo "<th>Date</th>";
		//echo "<th>Uploaded By</th>";
		echo "</tr>";
		
		$wr = mysql_query("select * from weekly where company = $usercompany");
		while($row=mysql_fetch_object($wr)) {
			$ur = mysql_query("select fullname from users where id = $row->user");
			echo "<tr>";
			echo "<td><a href=\"#weekly_delete\" onClick=\"javascript:toggleBox('weekly_delete',1);setDelFile('".$filespace."','".$row->filename."');\">";
			echo "<img src=\"images/delete.gif\" border=\"0\" align=\"absmiddle\"/></a>&nbsp;&nbsp;";
			echo "<a href=\"".$filespace."".$row->filename."\">$row->filename</a></td>";
			echo "<td>".filedate($row->timestamp)."</td>";
			//echo "<td>".mysql_result($ur,0,"fullname")."</td>";
			echo "</tr>";
		}
		echo "</table>";
		echo "</div>";
	//Display controls for uploading a new weekly report for all vendors who have  weekly reports role
	?>
	<p><a href="#weekly" onClick="javascript:toggleBox('weekly_upload',1);">Add weekly report file</a></p>
	<div id="weekly_upload" class="filebox" style="display:none;">
	<a name="weekly_upload"></a>
	<p><a href="javascript:toggleBox('weekly_upload',0);"><small>(Hide)</small></a></p>
	<p>
	<table align="center" cellspacing="8" cellpadding="8">
	<tr>
	<td><img src="images/avatar_single.gif" /></td>
	<td>
	<h2>::Upload weekly report</h2>
	<p>Add a <strong>single file</strong> to the weekly report folder.</p>
	<form name="single" method="post" action="<?php echo "$PHP_SELF?page=weekly2&mode=single"; ?>" enctype="multipart/form-data" onSubmit="return validateSingle(this);">
		<input type="hidden" name="project_name" value="<?php echo $sitename; ?>"></input>
		<input type="hidden" name="project_id" value="<?php echo $id; ?>"></input>
	<!--
	 remove these hidden tags and add one for $usercomany
	-->
		<table width="99%" cellspacing="10" class="litezone">
		<tr>
		<td width="100" align="right" valign="top"><small><strong>File:</strong></small></td>
		<td><input class="files" name="userfile[]" size="51" type="file" onKeyPress="return noenter()"></td>
		</tr>
		<tr>
		<td align="right" valign="top"><small><strong>Comment:</strong></small></td>
		<td><textarea class="files" name="new_file_comment" cols="50" rows="5"></textarea><br /><small>(optional)</small></td>
		</tr>
		<tr>
		<td align="right"><img src="images/mail.gif" /></td>
		<td><input name="notify" type="checkbox"><small>Send notification e-mail to Charlotte Russe team.</small></input></td>
		</tr>
		<tr>
		<td></td>
		<td>
		<input class="files" type="submit" name="submit" value="upload">&nbsp;
		<input class="files" type="reset" name="reset" value="cancel" onClick="javascript:toggleBox('weekly_upload',0);">
		</td>
		</tr>
		</table>
	</form>

	</td>
	</tr>
	<tr>
	<td class="patt" colspan="2"></td>
	</tr>

	</table>
	</p>
	<p><a href="javascript:toggleBox('weekly_upload',0);"><small>(Hide)</small></a></p>
	</div>
	<br />
	<div id="weekly_delete" class="filebox" style="display:none;">
	<a name="weekly_delete"></a>
	<table width="100%" align="center" cellspacing="4" cellpadding="4">
	<tr>
	<td><img src="images/avatar_clean.gif" /></td>
	<td>
	<p>You are about to delete a file, this cannot be undone!</p>
	<form name="del" method="post" action="<?php echo "$PHP_SELF?page=weekly2&mode=del"; ?>" enctype="multipart/form-data" >
	<table width="99%" cellspacing="10" class="litezone">
		<tr>
		<td width="200" align="right" valign="top"><small><strong>Do you want to delete this file?</strong></small></td>
		<td>
		<input type="hidden" name="del_file_path"></input>
		<input type="hidden" name="project_id" value="<?php echo $id; ?>"></input>
		<input class="files" type="text" name="del_file_name" size="50"></input><br /><br />
		<input class="files" type="submit" name="submit" value="Yes, delete" onKeyPress="return noenter()">&nbsp;
		<input class="files" type="reset" name="reset" value="No, cancel" onClick="javascript:toggleBox('weekly_delete',0);">
		</td>
		</tr>
		</table>
	</form>
	</td>
	</tr>
	</table>
	</div>
<?php
	}
	?>
</div>
<?
}//if ((in_array("weekly",$roles)) or ($usergroup < 2))
?>	

<h1>:: Project List</h1><a name="projects"></a>
<div class="databox">
<p>Active construction projects which have been entered into the system made available for view. Only administrators can modify projects and grant access to vendors.</p>
<?php
	//show controls to certain useres
	echo "<p>";
		if ($usergroup < 2) {
			print "<a href=\"index.php?page=archive\">List all stores.</a><br />";
		}
		if ($usergroup == 0) {
			echo "<a href=\"index.php?page=new-project&chain=1&status=1\">Add new project.</a>";
		}
	echo "</p>";


	//Vendor filter, vendors only see projects that have them tagged as vendors for that project
	if ($usergroup == 3) {
		$sql = "SELECT * FROM projects WHERE `companyarray` LIKE '%:\"".$usercompany."\";%'  and chain=1 order by $sort";
		} else {
	//All other usere get the full list
		$sql = "select * from projects where chain=1 and project_status='active' order by $sort";
	}
	//Execute filter on db!
	$result = mysql_query($sql);
	if (!$result){error("A databass error has occured.\\n".mysql_error());}
	if (mysql_num_rows($result)>0) {
	//Start the charlotte-russe box
	echo "<h2>Charlotte-Russe: Active Stores</h2><a name=\"char\"></a>";
	if (isset($sort)) { echo "<small>Sorted by $sortstyle. <img src=\"images/clear.gif\" align=\"absmiddle\" /> = high volume store / <img src=\"images/config.gif\" align=\"absmiddle\" /> = Potential remodel</small>";}
	print "<div class=\"sortbox\">";
	print "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
	print "<tr>";
	print "<th width=\"90\"><a href=\"$PHP_SELF?sort=datetouched#char\">Last Modified</a></th>";
	print "<th><a href=\"$PHP_SELF?sort=store_region#char\">Region</a></th>";
	print "<th><a href=\"$PHP_SELF?sort=store_district#char\">District</a></th>";
	print "<th><a href=\"$PHP_SELF?sort=store_number#char\">Store #</a></th>"; 
	print "<th>*</th>"; 
	print "<th><a href=\"$PHP_SELF?sort=sitename#char\">Location Name</a></th>";
	print "<th><a href=\"$PHP_SELF?sort=sitenum#char\">Job #</a></th>";
	print "</tr>";
	$count=0;
	
//Cultivate data set
	while ($row = mysql_fetch_array($result))
	{
	$id = $row["id"];
	$store_number = $row["store_number"];
	$store_region = $row["store_region"];
	$store_district = $row["store_district"];
	$sitename = $row["sitename"];
	$sitenum = $row["sitenum"];
	$high_volume = $row["high_volume_store"];
	$potential_remodel = $row["potential_remodel_store"];
	$datetouched = $row["datetouched"];
	$datetouched = dateconvert($datetouched);
	$count++;	
	print "<tr><td class=\"project\">$datetouched &nbsp;</td>";
	print "<td class=\"project\">$store_region&nbsp;</td>";
	print "<td class=\"project\">$store_district&nbsp;</td>";
	print "<td class=\"project\">$store_number &nbsp;</td>";
	echo "<td class=\"project\">";
	if ($high_volume == 1) {echo "<span style=\"display:none;\">1</span> <img src=\"images/clear.gif\" align=\"absmiddle\" />";}
	if ($potential_remodel == 1) {echo "<span style=\"display:none;\">1</span> <img src=\"images/config.gif\" align=\"absmiddle\" />";}
	echo "</td>";
	print "<td class=\"project\"><a href=\"$PHP_SELF?page=project&id=$id\">$sitename</a> &nbsp;</td>";
	print "<td class=\"project\">$sitenum</td>";
	print "</tr>";
	
	if ($count == 40) {
				print "<tr>";
				print "<th width=\"90\"><a href=\"$PHP_SELF?sort=datetouched#char\">Last Modified</a></th>";
				print "<th><a href=\"$PHP_SELF?sort=store_region#char\">Region</a></th>";
				print "<th><a href=\"$PHP_SELF?sort=store_district#char\">District</a></th>";
				print "<th><a href=\"$PHP_SELF?sort=store_number#char\">Store #</a></th>"; 
				print "<th>*</th>"; 
				print "<th><a href=\"$PHP_SELF?sort=sitename#char\">Location Name</a></th>";
				print "<th><a href=\"$PHP_SELF?sort=sitenum#char\">Job #</a></th>";
				print "</tr>";
				$count=0;		
				}
	}
	echo "</table></div>";
	echo "<p>";
	if ($usergroup < 2) {
		print "[<a href=\"index.php?page=archive\">List All Stores</a>]";
	}
	if ($usergroup == 0) {
	echo " [<a href=\"$PHP_SELF?page=new-project&chain=1&status=1\">Add new project</a>]</p>";
	}
	echo "</p>";
}

//Vendor filter, vendors only see projects that have them tagged as vendors for that project
	if ($usergroup == 3) {
		$sql = "SELECT * FROM projects WHERE `companyarray` LIKE '%:\"".$usercompany."\";%'  and chain=2 and project_status='active' order by $sort";
		} else {
//All other useres get the full list
		$sql = "select * from projects where chain=2 and project_status='active' order by sitename desc";
	}
//Execute filter on db!
	$result = mysql_query($sql);
	if (!$result){error("A databass error has occured.\\n".mysql_error());}
	if (mysql_num_rows($result)>0) {
	//Render Rampage box
	echo "<h2>Rampage: Active Stores</h2><a name=\"ramp\"></a>";
	if (isset($sort)) { echo "<small>Sorted by $sortstyle. * = high volume store</small>";}
	print "<div class=\"sortbox\">";
	print "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
	print "<tr>";
	print "<th width=\"90\"><a href=\"$PHP_SELF?sort=datetouched#ramp\">Last Modified</a></th>";
	print "<th><a href=\"$PHP_SELF?sort=store_region#ramp\">Region</a></th>";
	print "<th><a href=\"$PHP_SELF?sort=store_district#ramp\">District</a></th>";
	print "<th><a href=\"$PHP_SELF?sort=store_number#ramp\">Store #</a></th>"; 
	print "<th><a href=\"$PHP_SELF?sort=sitename#ramp\">Location Name</a></th>";
	print "<th><a href=\"$PHP_SELF?sort=sitenum#ramp\">Job #</a></th>";	
	print "</tr>";
	$count=0;
	
//Cultivate data set
	while ($row = mysql_fetch_array($result))
	{
	$id = $row["id"];
	$store_number = $row["store_number"];
	$store_region = $row["store_region"];
	$store_district = $row["store_district"];
	$sitename = $row["sitename"];
	$sitenum = $row["sitenum"];
	$datetouched = $row["datetouched"];
	$datetouched = dateconvert($datetouched);
	$count++;	
	print "<tr><td class=\"project\">$datetouched &nbsp;</td>";
	print "<td class=\"project\">$store_region&nbsp;</td>";
	print "<td class=\"project\">$store_district&nbsp;</td>";
	print "<td class=\"project\">$store_number &nbsp;</td>";
	print "<td class=\"project\"><a href=\"$PHP_SELF?page=project&id=$id\">$sitename</a> &nbsp;</td>";
	print "<td class=\"project\">$sitenum</td>";
	print "</tr>";
	
	if ($count == 40) {
				print "<tr>";
				print "<th><a href=\"$PHP_SELF?sort=datetouched#ramp\">Last Modified</a></th>";
				print "<th><a href=\"$PHP_SELF?sort=store_region#ramp\">Region</a></th>";
				print "<th><a href=\"$PHP_SELF?sort=store_district#ramp\">District</a></th>";
				print "<th><a href=\"$PHP_SELF?sort=store_number#ramp\">Store #</a></th>"; 
				print "<th><a href=\"$PHP_SELF?sort=sitename#ramp\">Location Name</a></th>";
				print "<th><a href=\"$PHP_SELF?sort=sitenum#ramp\">Job #</a></th>";
				print "</tr>";
				$count=0;		
				}
	}
	echo "</table></div>";
}
	echo "<p>";
	if ($usergroup < 2) {
		print "[<a href=\"index.php?page=archive\">List All Projects</a>]";
	}
	if ($usergroup == 0) {
	echo " [<a href=\"$PHP_SELF?page=new-project&chain=2&status=1\">Add new project</a>]</p>";
	}
	echo "</p>";

?>
</div>
<?php
//if (in_array("g2",$roles)) {
//	$embed_mode = TRUE;
//	echo "<h1>:: Repair Order Requests</h1>";
//	echo "<div class=\"databox\">";
//	echo "<p>Listed below are the active repair order requests which have been assigned to you.</p>";
//	include("admin-g2.php");
//	echo "</div>";
//}
//if (in_array("freq",$roles)) {
//	echo "<h1>:: Fixture Requests</h1>";
//	echo "<div class=\"databox\">";
//	echo "<p>Listed below are the active fixture requests which have been assigned to you.</p>";
////	include("freq-ext.php");
//	echo "</div>";
//}
?>

</div>
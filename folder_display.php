<?php //display the contents of a folder. start with jquery script 


?>
<script>
$(document).ready(function() {
	$('#sheet-index').hide();
	$('#view-status').hide();
});
</script>
<?php
//See if the user has the plans role
$rsql = "select roles from companies where company_id = $usercompany";
$rq = mysql_query($rsql);
$ro = mysql_result($rq,0,"roles");
$roles =  explode(",",$ro);

if ($vendorfile == 1) { ?>

<h1>:: Vendor Files</h1>
<div class="databox">
<?php 	
	//scan for folders and files inside that specific vendors folder

	
	dbConnect();
	$sql = "select company_name from companies where company_id = $name";
	$result = mysql_query($sql);
	$company_name = mysql_result($result,0, "company_name");
	
	print "<p>These are the files uploaded by <strong>$company_name</strong>.</p>";
	print "<p>(<a href=\"$PHP_SELF?page=project&id=$id\"><img src=\"images/levelup.gif\" border=\"0\" align=\"absmiddle\" /> Go back to root folder</a>)</p>";

	
	
	$filespace = "filespace/$id/vendor/$name/";

	if(!file_exists($filespace))
	mkdir($filespace, 0777);


	$files = array();
	$dirs  = array();
	$handle=opendir($filespace);
	while (false !== ($file = readdir($handle)))
	{
		if($file=='.'||$file=='..'||$file=='.htaccess')
			continue;
		if(is_dir($filespace.$file))
			$dirs[]=$file;
		else
			$files[]=$file;
	}
	closedir($handle);
	//Sort arrays in natural order and reset pointer to first entry
	sort($dirs, SORT_REGULAR);
	sort($files, SORT_REGULAR);
	reset($dirs);
	reset($files);

	//Render table!	
	echo "<table style=\"border:1px #000 solid;\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
	echo "<tr>";
	echo "<th class=\"files\" align=\"left\">Name</th>";
	echo "<th class=\"files\" align=\"right\">Size</th>";
	echo "<th class=\"files\" align=\"left\">Type</th>";
	echo "<th class=\"files\" align=\"left\">Date</th>";
	echo "<th class=\"files\" align=\"left\">Creator</th>";
	echo "<th class=\"files\" align=\"left\" style=\"border-right:none;\">Comments</th>";
	echo "</tr>";
	
	//Now do files in root!
	foreach($files as $key=>$value)
	{
		echo "<tr>";
		echo "<td class=\"files\" align=\"left\">";
		if (($usergroup == "0") or (in_array("plans",$roles)))
		{
		echo "<a href=\"#delete\" onClick=\"javascript:toggleBox('vendor_delete',1);setDelVendorFile('".$filespace."','".$value."');\"><img src=\"images/delete.gif\" border=\"0\" /></a>";
		}
		echo "<a class=\"files\" href=\"download.php?file=".$filespace.urlencode($value)."\">";
		echo "<img src=\"images/file.gif\" align=\"absmiddle\" border=\"0\" />$value";
		echo "</a>";
		echo "</td>";
		
		//File size in kb or mb.. 
		$fsize = file_size(filesize($filespace.$value));
		echo "<td class=\"files\" align=\"right\">$fsize</td>";
		
		//Show file type description! Apply for common file, and default to plain EXT file as the type
		echo "<td class=\"files\" align=\"left\">".myfiletype($value)."</td>";
		
		//Formate and show date
		$fdate = filemtime($filespace.$value);			
		$fdate = date("m/j/y h:i A", $fdate);
		echo "<td class=\"files\" align=\"left\"><small>$fdate</small></td>";
		
		//Get comments and author then  format and show them
		$sql = "select author, comment from vendor_filelog where filename='$value' and project= $id";
		$result= mysql_query($sql);
		if (!result)
			error("A databass error has occured.\\n".mysql_error());
		$data = mysql_fetch_object($result);
		$author=$data->author;
		$comment=$data->comment;
		if ($author == "")
			$author="No owner!";
		if ($comment == "")
		{
			$comment="No comment exists in the database for this file";
			$shortcomment="No comment";
		} else {
			$comment=stripslashes($comment);
			$shortcomment=substr($comment,0,32);
			$shortcomment.="...";
		}
		echo "<td class=\"files\" align=\"left\"><small>$author</small></td>";
		echo "<td class=\"files\" align=\"left\"><html:abbr title=\"$comment\">$shortcomment</html:abbr></td>";
		echo "</tr>";
	}
	echo "<tr>";
	echo "<td class=\"files\" style=\"border-bottom:1px #000 solid;\" colspan=\"6\">&nbsp;</td>";
	echo "</tr>";
	
	//Offer upload option to admins, show object count to all users
	echo "<tr>";
	echo "<td class=\"files\" colspan=\"6\">";
	echo "&nbsp;".count($files)." File(s),".count($dirs)." Folder(s)";
	echo "<br />";

	echo "</td>";
	echo "</tr>";
	echo "</table>";

	//Clear arrays and path var
	unset($filespace,$files, $dirs);
	
	?>
	
	
	<br />
	<div id="vendor_delete" class="filebox" style="display:none;">
	<a name="venor_delete"></a>
	<table width="100%" align="center" cellspacing="4" cellpadding="4">
	<tr>
	<td><img src="images/avatar_clean.gif" /></td>
	<td>
	<p>You are about to delete a file, this cannot be undone!</p>
	<form name="delvendor" method="post" action="<?php echo "$PHP_SELF?page=folder&id=$id&name=$name&vendorfile=1&mode=vendordel"; ?>" enctype="multipart/form-data" >
	<table width="99%" cellspacing="10" class="litezone">
		<tr>
		<td width="200" align="right" valign="top"><small><strong>Do you want to delete this file?</strong></small></td>
		<td>
		<input type="hidden" name="delvendorfile_path"></input>
		<input type="hidden" name="project_id" value="<?php echo $id; ?>"></input>
		<input class="files" type="text" name="delvendorfile_name" size="50"></input><br /><br />
		<input class="files" type="submit" name="submit" value="Yes, delete" onKeyPress="return noenter()">&nbsp;
		<input class="files" type="reset" name="reset" value="No, cancel" onClick="javascript:toggleBox('vendor_delete',0);">
		</td>
		</tr>
		</table>
	</form>
	</td>
	</tr>
	</table>
	</div> 
	<?
	
	echo "</div>";
	
	
	} else {



		dbConnect();
		$nicename = stripslashes($name);
		$sql = "select sitename from projects where id=$id";
		$result = mysql_query($sql);
			if (!result)
				error("A databass error has occured.\\n".mysql_error());
		$project=mysql_fetch_object($result);
			
		$sql = "select * from distrolog where distroname='$nicename' and project= $id";
		$result= mysql_query($sql);
		if (!$result)
			error("A databass error has occured.\\n".mysql_error());
		$distro = mysql_fetch_object($result);

		if ($distro->author == "") {$distro->author="No owner!";}
			if ($distro->comment == "")
			{
				$distro->comment="No comment exists in the database for this file";
			} else {
				$distro->comment=stripslashes($distro->comment);
				$distro->comment=nl2br($distro->comment);
			}
			
			$name = stripslashes($name);
?>	
	<h1>:: Project Files List</h1>
	<div class="databox">
<?php
	echo "<div class=\"filebox\">";
	echo "<h2>$project->sitename</h2>";
	
	echo "<p><strong>Folder: </strong>$name&nbsp;";
	echo "(<a href=\"$PHP_SELF?page=project&id=$id\"><img src=\"images/levelup.gif\" border=\"0\" align=\"absmiddle\" /> Go back to root folder</a>)<br />";

	echo "<strong>Uploaded by: </strong>$distro->author<br />";
	echo "<strong>Comments: </strong>$distro->comment</p>";
	echo "<p><a href=\"#\" onclick=\"javascript:$('#sheet-index').toggle(400);return false;\">&raquo; Show Sheet Index</a></p>";
	?>
    <table id="sheet-index" class="tog">
    <tr><td colspan="2"><br />Note: This index may not specifically apply to this set, but should act as a guide.</td></tr>
    <tr><td><strong>Sheet Index</strong></td><td>&nbsp;</td></tr>
    <tr><td><strong>T-1</strong></td><td>Title Sheet/Sheet Index</td></tr>
    <tr><td><strong>T-1</strong></td><td>Landlord/Bldg. Dept. Notes/Resp. Sched.</td></tr>
    <tr><td><strong>SP-1</strong></td><td>Specifications/Door Hardware</td></tr>
    <tr><td><strong>SP-2</strong></td><td>Specifications</td></tr>
    <tr><td><strong>A0-1</strong></td><td>Existing Condistions/Demo Plan</td></tr>
    <tr><td><strong>A1-1</strong></td><td>Floor Const. Plan/Door Sched./Wall Types</td></tr>
    <tr><td><strong>A1-2</strong></td><td>Floor Finish Plan/Room/Finish Sched./Keynotes</td></tr>
    <tr><td><strong>A1-3</strong></td><td>Furniture/Fixture/Equipment Plan</td></tr>
    <tr><td><strong>A2-1</strong></td><td>Reflected Clg./Lighting Plan/Lighting Legend</td></tr>
    <tr><td><strong>A2-2</strong></td><td>Ceiling Dimension Plan</td></tr>
    <tr><td><strong>A3-1</strong></td><td>Storefront Elevs/Sections/Neut. Pier Details</td></tr>
    <tr><td><strong>A3-2</strong></td><td>Interior Elevs/Sales & Fitting Room</td></tr>
    <tr><td><strong>A3-3</strong></td><td>Standard Wall Sections/Interior Elevs/Enlarged Plans</td></tr>
    <tr><td><strong>A4-1</strong></td><td>General Project Details</td></tr>
    <tr><td><strong>A4-2</strong></td><td>General Project Details</td></tr>
    <tr><td><strong>A4-3</strong></td><td>General Project Details</td></tr>
    <tr><td><strong>A5-1</strong></td><td>Standard Charlotte Russe Fixture Details</td></tr>
    <tr><td><strong>A6-1</strong></td><td>Standard Charlotte Russe Millwork Details</td></tr>
    <tr><td><strong>A7-1</strong></td><td>Standard Charlotte Russe Signage Details</td></tr>
    <tr><td><strong>&nbsp;</strong></td><td>&nbsp;</td></tr>
    <tr><td><strong>M-1</strong></td><td>Project Mechanical Drawing</td></tr>
    <tr><td><strong>M-2</strong></td><td>Project Mechanical Drawing</td></tr>
    <tr><td><strong>&nbsp;</strong></td><td>&nbsp;</td></tr>
    <tr><td><strong>P-1</strong></td><td>Project Plumbing Drawing</td></tr>
    <tr><td><strong>P-2</strong></td><td>Project Plumbing Drawing</td></tr>
    <tr><td><strong>&nbsp;</strong></td><td>&nbsp;</td></tr>
    <tr><td><strong>E-1</strong></td><td>Project Electrical Drawing</td></tr>
    <tr><td><strong>E-2</strong></td><td>Project Electrical Drawing</td></tr>
    <tr><td><strong>E-3</strong></td><td>Project Electrical Drawing</td></tr>
    <tr><td><strong>E-4</strong></td><td>Project Electrical Drawing</td></tr>
    <tr><td><strong>E-5</strong></td><td>Project Electrical Drawing</td></tr>
    </table>


<?php

	if ($usergroup<2) {
	if ($distro->vendor != "") {
		$vendor_array = unserialize($distro->vendor);
		echo "<p><a href=\"#\" onclick=\"javascript:$('#view-status').toggle(400);return false;\">&raquo; Show View Status</a></p>";
		$resqty = count($vendor_array);
		$colqty = $resqty/2; //Creates three columms
		echo "<table id=\"view-status\" width=\"99%\" style=\"font-size:85%;\" class=\"tog\"><tr><td><br />";
		$rcount = 0;
		foreach ($vendor_array as $vendor) {
			$cres = mysql_query("select company_name from companies where company_id=$vendor");
			$company = mysql_result($cres,0,"company_name");
			$vsql = "select * from downloadlog where project_id = $id and company = '$vendor' and folder = '$name'";
			$vres = mysql_query($vsql);
			$count = mysql_num_rows($vres);
			if ($count > 0) {
				echo "<img src=\"/images/Minor.gif\" align=\"absmiddle\" /> $company - OK<br />";
			} else {
				echo "<img src=\"/images/Hazard.gif\" align=\"absmiddle\" /> $company - Needs to download<br />";
			}
			$rcount++;
			if ($rcount >= $colqty) { //check to see if the column qty has been exceeded, if so, then reset the column and count back to 0
				echo "</td><td>";
				$rcount = 0;
				}
		}
		echo "</td></tr></table>";	
			
	}
	}
	echo "</div>";
	
	echo "<br />";
	
	
		
	//scan for folders and files that are NOT photos and NOT system folders or files
	$filespace = "filespace/$id/$name/";
	$files = array();
	$dirs  = array();
	$handle=opendir($filespace);
	while (false !== ($file = readdir($handle)))
	{
		if($file=='.'||$file=='..'||$file=='.htaccess')
			continue;
		if($file == '.archived') {
			$archived = TRUE;
		}
		if(is_dir($filespace.$file))
			$dirs[]=$file;
		else
			$files[]=$file;
	}
	closedir($handle);
	//Sort arrays in natural order and reset pointer to first entry
	sort($dirs, SORT_REGULAR);
	sort($files, SORT_REGULAR);
	reset($dirs);
	reset($files);


	if ($archived) {
		unset($files);
		$files = array();

		$conn = cloudConnect();

		//default category
		$category = 'files';

		$folder = $_GET['name'];
		$pid = $_GET['id'];

		$container_string = $subdomain . '.' . $pid . '.' . $category;

		//load the container or create it... 
		try {
			$container = $conn->get_container($container_string);
		} catch (Exception $e) {
			$container = $conn->create_container($container_string);
		}


		//List objects	
		try {
			$list = $container->get_objects(0,NULL,NULL,$folder);
		} catch (Exception $e) {
			print 'Exception caught: ' . $e->getMessage() ;		
		}

		$i=0;
		foreach($list as $obj) {
			$object = $container->get_object($obj->name);	
			if($folder == '') {
				if($object->content_type != 'application/directory') {continue;}
				//count objects inside this dir..
				$ls = $container->get_objects(0,NULL,NULL, $obj->name);
				$cloud_files[$i]['count'] = count($ls);
			}			
			
			
			$cloud_files[$i]['name'] = $object->name;
			$cloud_files[$i]['author'] = urldecode($object->metadata['Author']);
			$cloud_files[$i]['comment'] = urldecode($object->metadata['Comment']);
			$cloud_files[$i]['date'] = $object->last_modified;
			$cloud_files[$i]['type'] = $object->content_type;
			$cloud_files[$i]['length'] = $object->content_length;									
			$i++;
		}

		
	}
	//print '<pre>';
	//print_r($cloud_files);
	//print '</pre>'	;

	//Render table!	
	echo "<table class=\"tableborders\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
	echo "<tr>";
	echo "<th class=\"files\" align=\"left\">Name</th>";
	echo "<th class=\"files\" align=\"right\">Size</th>";
	echo "<th class=\"files\" align=\"left\">Type</th>";
	echo "<th class=\"files\" align=\"left\" style=\"border-right:none;\">Date</th>";
	echo "</tr>";
	
	$zebra = false;

	$total_fsize = 0;
	
	if($archived) {
		foreach($cloud_files as $cloud_file) {

			$filepath = explode('/', $cloud_file['name']);
			$filename = $filepath[1];
			$filefolder = $filepath[0];

			if ($filename == 'archive.zip') {
				continue;
			}

			echo "<tr>";
			echo "<td class=\"files\" align=\"left\">";
			if (($usergroup == "0") or (in_array("plans",$roles)))
			{
			echo "<a href=\"#delete\" onClick=\"javascript:toggleBox('delete',1);setDelCloudFile('".$container."','".$cloud_file['name']."');\"><img src=\"images/delete.gif\" border=\"0\" align=\"absmiddle\" /></a>";
			}

			echo "<a class=\"files\" href=\"cloud_download.php?container=".$container_string."&object=".urlencode($cloud_file['name'])."\">";
			echo "<img src=\"images/file.gif\" align=\"absmiddle\" border=\"0\" />".$filename;
			echo "</a>";
			echo "</td>";
			
			//File size in kb or mb.. 
			$total_fsize += $cloud_file['length'];
			$fsize = file_size($cloud_file['length']);
			echo "<td class=\"files\" align=\"right\">$fsize</td>";
			
			//Show file type description! Apply for common file, and default to plain EXT file as the type
			echo "<td class=\"files\" align=\"left\">".myfiletype($filename)."</td>";
			
			//Formate and show date
			
			$fdate = date("m/j/y h:i A", strtotime($distro->timestamp));
			echo "<td class=\"files\" align=\"left\">$fdate</td>";
			
			echo "</tr>";
		}

	} else {
		//Local files
		foreach($files as $key=>$value)
		{
			//Show file name and link to it
			echo "<tr>";
			echo "<td class=\"files\" align=\"left\">";
			if (($usergroup == "0") or (in_array("plans",$roles)))
			{
			echo "<a href=\"#delete\" onClick=\"javascript:toggleBox('delete',1);setDelFile('".$filespace."','".$value."');\"><img src=\"images/delete.gif\" border=\"0\" align=\"absmiddle\" /></a>";
			}

			echo "<a class=\"files\" href=\"download.php?file=".$filespace.urlencode($value)."\">";
			echo "<img src=\"images/file.gif\" align=\"absmiddle\" border=\"0\" />$value";
			echo "</a>";
			echo "</td>";
			
			//File size in kb or mb.. 
			$total_fsize += filesize($filespace.$value);
			$fsize = file_size(filesize($filespace.$value));
			echo "<td class=\"files\" align=\"right\">$fsize</td>";
			
			//Show file type description! Apply for common file, and default to plain EXT file as the type
			echo "<td class=\"files\" align=\"left\">".myfiletype($value)."</td>";
			
			//Formate and show date
			$fdate = filemtime($filespace.$value);			
			$fdate = date("m/j/y h:i A", $fdate);
			echo "<td class=\"files\" align=\"left\">$fdate</td>";
			
			echo "</tr>";
		}
	}


	//Close table out with an empty row
	echo "<tr>";
	echo "<td class=\"files\" style=\"border-bottom:1px #666 solid;\" colspan=\"4\">&nbsp;</td>";
	echo "</tr>";
	
	//Offer upload option to admins, show object count to all users
	echo "<tr>";
	echo "<td class=\"files\" colspan=\"6\">";
	echo "<p>&nbsp;".count($files)." File(s) in this folder";
	echo "&nbsp;&nbsp;";
	echo "Total file size: ".file_size($total_fsize);
	echo "&nbsp;&nbsp;";
	echo "<img src=\"images/download.gif\" align=\"absmiddle\" />&nbsp;";
	if($archived) {
		echo "<a href=\"cloud_download.php?container=".$container_string."&object=".$filefolder."/archive.zip\">Download this entire folder as a ZIP</a>";
	} else {
		echo "<a href=\"get-distro-zip.php?id=$id&folder=$name\">Download this entire folder as a ZIP</a>";
	}
	echo "</p>";
	
	if(!$archived) {
		if (($usergroup == "0") or (in_array("plans",$roles)))
		{
			echo "<p><img src=\"images/plus.gif\" align=\"absmiddle\" />&nbsp;";
			echo "<a href=\"#upload\" onClick=\"javascript:toggleBox('upload',1);\">Add new documents to this folder</a></p>";

			if ($usergroup == "0") {
			echo "<p><img src=\"images/minus.gif\" align=\"absmiddle\" />&nbsp;&nbsp;";
			echo "<a href=\"#remove\" onClick=\"javascript:toggleBox('remove',1);\">Remove folder!</a></p>";
			echo '<p><a href="index.php?page=archive_files&id='.$_GET['id'].'&name='.$_GET['name'].'">Archive This Folder To Cloud</a></p>';
			}
		}
	}
	echo "</td>";
	echo "</tr>";
	echo "</table>";

	//Clear arrays and path var
	unset($filespace,$files, $dirs);
	?>
	<br />
	<div id="upload" class="filebox" style="display:none;">
	<a name="upload"></a>
	<table width="100%" align="center" cellspacing="4" cellpadding="4">
	<tr>
	<td><img src="images/avatar_single.gif" /></td>
	<td>
	<p>Select the files from your computer that you want to add to this folder.</p>
	<form name="upload" method="post" action="<?php echo "$PHP_SELF?page=folder&mode=upload"; ?>" enctype="multipart/form-data" >
	<input type="hidden" name="project_id" value="<?php echo $id; ?>"></input>
	<input type="hidden" name="project_folder_name" value="<?php echo $name; ?>"></input>
	<table width="99%" cellspacing="10" class="litezone">
		<tr>
		<td width="100" align="right" valign="top"><small><strong>Files:</strong></small></td>
		<td>
		<input class="files" name="userfile[]" type="file" size="50" onKeyPress="return noenter()"><br />
		<input class="files" name="userfile[]" type="file" size="50" onKeyPress="return noenter()"><br />
		<input class="files" name="userfile[]" type="file" size="50" onKeyPress="return noenter()"><br />
		<input class="files" name="userfile[]" type="file" size="50" onKeyPress="return noenter()"><br />
		<input class="files" name="userfile[]" type="file" size="50" onKeyPress="return noenter()"><br />
		</td>
		</tr>
		<tr>
		<td></td>
		<td>
		<input class="files" type="submit" name="submit" value="upload">&nbsp;
		<input class="files" type="reset" name="reset" value="cancel" onClick="javascript:toggleBox('upload',0);">
		</td>
		</tr>
		</table>
	</form>
	</td>
	</tr>
	</table>
	</div>
	<br />
	<div id="remove" class="filebox" style="display:none;">
	<table width="100%" align="center" cellspacing="4" cellpadding="4" class="tableborders">
	<tr>
	<td><img src="images/avatar_clean.gif" /></td>
	<td>
	<p>BE CAREFUL. You are about to delete a folder from the database! Are you sure you want to do this?</p>
	<form name="remove" method="post" action="<?php echo "$PHP_SELF?page=folder&mode=remove"; ?>" enctype="multipart/form-data" >
	<table width="99%" cellspacing="10" class="litezone">
	<input type="hidden" name="project_id" value="<?php echo $id; ?>"></input>
	<input type="hidden" name="project_folder_name" value="<?php echo $name; ?>"></input>
		<tr>
		<td width="200" align="right" valign="top"><small><strong>Are you sure you want to remove this folder?</strong></small></td>
		<td>
		<input class="files" type="submit" name="submit" value="Yes, Delete this folder" onKeyPress="return noenter()">&nbsp;
		<input class="files" type="reset" name="reset" value="Cancel" onClick="javascript:toggleBox('remove',0);">
		</td>
		</tr>
		</table>
	</form>
	</td>
	</tr>
	</table>
	<br />
	</div>
	
	<div id="delete" class="filebox" style="display:none;">
	<a name="delete"></a>
	<table width="100%" align="center" cellspacing="4" cellpadding="4" class="tableborders">
	<tr>
	<td><img src="images/avatar_clean.gif" /></td>
	<td>
	<p>You are about to delete an empty folder from the database. This is useful when cleaning up files. File removal is done this way for added security. If you want to remove a large amount of files, please contact the site admin for help.</p>
	<form name="del" method="post" action="<?php echo "$PHP_SELF?page=folder&mode=del"; ?>" enctype="multipart/form-data" >
	<table width="99%" cellspacing="10" class="litezone">
		<tr>
		<td width="200" align="right" valign="top"><small><strong>Delete this file?</strong></small></td>
		<td>
		<input type="hidden" name="del_file_path"></input>
		<input type="hidden" name="project_id" value="<?php echo $id; ?>"></input>
		<input type="hidden" name="project_folder_name" value="<?php echo $name; ?>"></input>
		<input class="files" type="text" name="del_file_name" size="50"></input><br />
		<input class="files" type="submit" name="submit" value="yes, delete" onKeyPress="return noenter()">&nbsp;
		<input class="files" type="reset" name="reset" value="no, cancel" onClick="javascript:toggleBox('delete',0);">
		</td>
		</tr>
		</table>
	</form>
	</td>
	</tr>
	</table>
	</div>
</div>

<?php } ?>

<?php 
if ($archived) { print '<div style="text-align:center;">.</div>';} 
?>
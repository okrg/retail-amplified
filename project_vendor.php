<?php //project_vendor.php

if ($usergroup < 2) {
?>

<p>These are the files added by vendors. Clicking on a folder will open that particular vendor's file space.</p>
<?php

	//scan for folders inside the /vendor folder
	$filespace = "filespace/$id/vendor/";
	
	if(!file_exists($filespace))
	mkdir($filespace, 0777);
	
	$files = array();
	$dirs  = array();
	$handle=opendir($filespace);
	$totalcount = 0;
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
	$assets = count($files)+count($dirs);

if ($assets >0) {
	//Render table!	
	echo "<table style=\"border:1px #000 solid;\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
	echo "<tr>";
	echo "<th class=\"files\" align=\"left\">Company Name</th>";
	echo "<th class=\"files\" align=\"right\">Size</th>";
	echo "<th class=\"files\" align=\"left\">Type</th>";
	echo "<th class=\"files\" align=\"left\" style=\"border-right:none;\">Date</th>";
	echo "</tr>";
	
	//Show folders first
	foreach($dirs as $key=>$value)
	{
		//Count files in folder
		$filecount = countfiles($filespace.$value);
		if ($filecount > 0) {
			$filecount = $filecount . " file(s)";
			$totalcount++;
		} else {
			continue;
		}

		//Get company name then  format and show them
		$sql = "select company_name from companies where company_id ='$value'";
		$result= mysql_query($sql);
		if (!$result)
			error("PV63:A databass error has occured.\\n".mysql_error());
		$data = mysql_fetch_object($result);
		$company=$data->company_name;


		
		//Show file name and link to it
		echo "<tr>";
		echo "<td class=\"files\" align=\"left\">";
		echo "<a class=\"files\" href=\"$PHP_SELF?page=folder&id=$id&name=$value&vendorfile=1\">";
		echo "<img src=\"images/foldericon.gif\" align=\"absmiddle\" border=\"0\" />$company";
		echo "</a>";
		echo "</td>";
		
		//Size is actually the number of files in that folder...
		echo "<td class=\"files\" align=\"right\">$filecount</td>";
		
		//Show type as folder
		echo "<td class=\"files\" align=\"left\">File Folder</td>";
		
		//Formate and show date
		$fdate = filemtime($filespace.$value);			
		$fdate = date("m/j/y h:i A", $fdate);
		echo "<td class=\"files\" align=\"left\"><small>$fdate</small></td>";


		echo "</tr>";
		}
		
	echo "<tr>";
	echo "<td class=\"files\" style=\"border-bottom:1px #000 solid;\" colspan=\"6\">&nbsp;</td>";
	echo "</tr>";
		
	//Offer upload option to admins, show object count to all users
	echo "<tr>";
	echo "<td class=\"files\" colspan=\"4\">";
		if ($totalcount == 0) {echo "No vendor files yet!";}
	echo "</td>";
	echo "</tr>";
	echo "</table>";
	}

} else {
	
?>

<h1>:: Your Uploaded Files</h1>
<div class="databox">
<?php
	//scan for folders and files inside that specific vendors folder
	
	dbConnect();
	$sql = "select company_name from companies where company_id = $usercompany";
	$result = mysql_query($sql);
	$company_name = mysql_result($result,0, "company_name");
	
	print "<p>These are the files uploaded by <strong>$company_name</strong>.</p>";

	$filespace = "filespace/$id/vendor/";
	if(!file_exists($filespace))
		mkdir($filespace, 0777);

	$filespace = "filespace/$id/vendor/$usercompany/";
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
		echo "<a href=\"#vendor_delete\" onClick=\"javascript:toggleBox('vendor_delete',1);setDelVendorFile('".$filespace."','".$value."');\"><img src=\"images/delete.gif\" border=\"0\" /></a>";
		echo "<a class=\"files\" href=\"download.php?file=".$filespace.$value."\">";
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
			error("PV189:A databass error has occured.\\n".mysql_error());
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
	echo "&nbsp;".count($files)." File(s)";
	echo "<br />";

		echo "<img src=\"images/plus.gif\" align=\"absmiddle\" />";
		echo "<a href=\"#vendor_upload\" onClick=\"javascript:toggleBox('vendor_upload',1);\">Add new documents to this folder</a>";
		echo "&nbsp;&nbsp;";
	echo "</td>";
	echo "</tr>";
	echo "</table>";

	//Clear arrays and path var
	unset($filespace,$files, $dirs);
?>
	<br />
	<div id="vendor_upload" class="filebox" style="display:none;">
	<a name="vendor_upload"></a>
	<p><a href="javascript:toggleBox('vendor_upload',0);"><small>(Hide)</small></a></p>
	<p>
	<table align="center" cellspacing="8" cellpadding="8">
	<tr>
	<td><img src="images/avatar_single.gif" /></td>
	<td>
	<h2>::Upload a single project file</h2>
	<p>Add a <strong>single file</strong> to the main project folder. This is where project directories, survey docs, and other general project files are located. You can assign comments to these files too!</p>
	<form name="single" method="post" action="<?php echo "$PHP_SELF?page=project&mode=vendor_single"; ?>" enctype="multipart/form-data" onSubmit="return validateSingle(this);">
		<input type="hidden" class="files" name="project_name" value="<?php echo $sitename; ?>"></input>
		<input type="hidden" class="files"  name="project_id" value="<?php echo $id; ?>"></input>
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
		<td><input name="notify" class="files" type="checkbox"><small>Send notification e-mail to Charlotte Russe team.</small></input></td>
		</tr>
		<tr>
		<td></td>
		<td>
		<input class="files" type="submit" name="submit" value="upload">&nbsp;
		<input class="files" type="reset" name="reset" value="cancel" onClick="javascript:toggleBox('vendor_upload',0);">
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
	<p><a href="javascript:toggleBox('vendor_upload',0);"><small>(Hide)</small></a></p>
	</div>
	<br />
	<div id="vendor_delete" class="filebox" style="display:none;">
	<a name="vendor_delete"></a>
	<table width="100%" align="center" cellspacing="4" cellpadding="4">
	<tr>
	<td><img src="images/avatar_clean.gif" /></td>
	<td>
	<p>You are about to delete a file, this cannot be undone!</p>
	<form name="delvendor" method="post" action="<?php echo "$PHP_SELF?page=project&mode=del"; ?>" enctype="multipart/form-data" >
	<table width="99%" cellspacing="10" class="litezone">
		<tr>
		<td width="200" align="right" valign="top"><small><strong>Do you want to delete this file?</strong></small></td>
		<td>
		<input class="files" type="hidden" name="delvendorfile_path"></input>
		<input class="files" type="hidden" name="project_id" value="<?php echo $id; ?>"></input>
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

</div>
<?php } ?>

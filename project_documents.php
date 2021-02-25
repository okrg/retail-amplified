<?php //project_documents.php ?>

<p>Other miscellaneous documents associated with this store. Only the Charlotte Russe users can view these files.</p>
<?php
//Repeat process for misc folder
	//scan for folders and files INSIDE PHOTOS that are NOT thumbs and NOT system folders or files
	$filespace = "filespace/$id/misc/";
	
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

	//Sort list by natural order and reset pointers
	sort($dirs, SORT_REGULAR);
	sort($files, SORT_REGULAR);
	reset($dirs);
	reset($files);
	$assets = count($files)+count($dirs);
if ($assets > 0) { //if no files, dont show table!

//Render containing table
	echo "<table class=\"tableborders\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" id=\"MiscFileTable\">";
	echo "<thead><tr>";
	echo "<th class=\"files\" align=\"left\">Name</th>";
	echo "<th class=\"files\" align=\"right\">Size</th>";
	echo "<th class=\"files\" align=\"left\">Type</th>";
	echo "<th class=\"files\" align=\"left\">Date</th>";
	echo "<th class=\"files\" align=\"left\">Owner</th>";
	echo "<th class=\"files\" align=\"left\" style=\"border-right:none;\">Comments</th>";
	echo "</tr></thead><tbody>";

	//Show galleries
	foreach($files as $key=>$value)
	{
		echo "<tr>";
		echo "<td class=\"files\" align=\"left\">";
		if ($usergroup == "0")
		{
		echo "<a href=\"#delete_misc\" onClick=\"javascript:toggleBox('delete_misc',1);setDelmiscFile('".$filespace."','".$value."');\"><img src=\"images/delete.gif\" border=\"0\" /></a>";
		}
		echo "<a class=\"files\" href=\"download.php?file=".$filespace.$value."\">";
		echo "<img src=\"images/file.gif\" align=\"absmiddle\" border=\"0\" />$value";
		echo "</a>";
		echo "</td>";
				
		//File size in kb or mb.. 
		$fsize = file_size(filesize($filespace.$value));
		echo "<td class=\"files\" align=\"right\">$fsize</td>";
		
		//Show type as gallery
		echo "<td class=\"files\" align=\"left\">misc Document</td>";
		
		//Format and show date
		$fdate = filemtime($filespace.$value);			
		$fdate = date("m/j/y h:i A", $fdate);
		echo "<td class=\"files\" align=\"left\"><small>$fdate</small></td>";
		
		//Get comments and author from miscLOG then  format and show them
		$sql = "select author, comment from misclog where filename='$value' and project= $id";
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
	//Close table out with an empty row
	echo "<tr>";
	echo "<td class=\"files\" style=\"border-bottom:1px #000 solid;\" colspan=\"6\">&nbsp;</td>";
	echo "</tr>";
	
	//Offer upload option to admins and show count of galleries to all
	echo "<tr>";
	echo "<td class=\"files\" colspan=\"6\">";
	echo "&nbsp;".count($dirs)." File(s)<br />";
	echo "</td>";
	echo "</tr></tbody>";
	echo "</table>";	
	} else { //if $files count > 0
	echo "<em>No files found.</em>";
	}
	if ($usergroup == 0)
	{
		echo "<img src=\"images/plus.gif\" align=\"absmiddle\" />";
		echo "<a href=\"#misc\" onClick=\"javascript:toggleBox('misc',1);\">Upload a new misc file</a><br />";
	}
		
	//Clear array and path var
	unset($filespace, $files, $dirs);
?>	
	<br />
	<div id="misc" class="filebox" style="display:none;">
	<a name="misc"></a>
	<p><a href="javascript:toggleBox('misc',0);"><small>(Hide)</small></a></p>
	<p>
	<table align="center" cellspacing="8" cellpadding="8">
	<tr>
	<td><img src="images/avatar_single.gif" /></td>
	<td>
	<h2>::Upload a single misc file</h2>
	<p>Add a <strong>single file</strong> to the misc folder. Only CR Team and Architects will be able to see these files.</p>
	<form name="single" method="post" action="<?php echo "$PHP_SELF?page=project&mode=misc"; ?>" enctype="multipart/form-data" onSubmit="return validateSingle(this);">
		<input type="hidden" name="project_name" value="<?php echo $sitename; ?>"></input>
		<input type="hidden" name="project_id" value="<?php echo $id; ?>"></input>
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
		<td><input name="notify" type="checkbox"><small>Send notification e-mail</small></input></td>
		</tr>
		<tr>
		<td></td>
		<td>
		<input class="files" type="submit" name="submit" value="upload">&nbsp;
		<input class="files" type="reset" name="reset" value="cancel" onClick="javascript:toggleBox('misc',0);">
		</td>
		</tr>
		</table>
	</form>
	</td>
	</tr>
	</table>
	</p>
	<p><a href="javascript:toggleBox('misc',0);"><small>(Hide)</small></a></p>
	</div>
	<br />
	<div id="delete_misc" class="filebox" style="display:none;">
	<a name="delete_misc"></a>
	<table width="100%" align="center" cellspacing="4" cellpadding="4">
	<tr>
	<td><img src="images/avatar_clean.gif" /></td>
	<td>
	<p>You are about to delete a file, this cannot be undone!</p>
	<form name="delmisc" method="post" action="<?php echo "$PHP_SELF?page=project&mode=del"; ?>" enctype="multipart/form-data" >
	<table width="99%" cellspacing="10" class="litezone">
		<tr>
		<td width="200" align="right" valign="top"><small><strong>Do you want to delete this file?</strong></small></td>
		<td>
		<input type="hidden" name="delmisc_file_path"></input>
		<input type="hidden" name="project_id" value="<?php echo $id; ?>"></input>
		<input class="files" type="text" name="delmisc_file_name" size="50"></input><br /><br />
		<input class="files" type="submit" name="submit" value="Yes, delete" onKeyPress="return noenter()">&nbsp;
		<input class="files" type="reset" name="reset" value="No, cancel" onClick="javascript:toggleBox('delete_misc',0);">
		</td>
		</tr>
		</table>
	</form>
	</td>
	</tr>
	</table>
	</div>
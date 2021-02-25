<a name="weekly"></a>
<h1>:: Weekly Reports</h1>
<div class="databox">
<p>Listed below are the weekly report files.</p>
<div class="rbroundbox">
	<div class="rbtop"><div></div></div>
		<div class="rbcontent">
<?
	dbConnect();
	$filespace = "filespace/weekly/";

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
	echo "<table class=\"week\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
	echo "<tr>";
	echo "<th class=\"week\" align=\"left\">Name</th>";
	echo "<th class=\"week\" align=\"right\">Size</th>";
	echo "<th class=\"week\" align=\"left\">Type</th>";
	echo "<th class=\"week\" align=\"left\">Date</th>";
	echo "<th class=\"week\" align=\"left\">Creator</th>";
	echo "<th class=\"week\" align=\"left\" style=\"border-right:none;\">Comments</th>";
	echo "</tr>";
	
	//Now do files in root!
	foreach($files as $key=>$value)
	{
		echo "<tr>";
		echo "<td class=\"weekfile\" align=\"left\">";
		echo "<a href=\"#weekly_delete\" onClick=\"javascript:toggleBox('weekly_delete',1);setDelFile('".$filespace."','".$value."');\">";
		echo "<img src=\"images/delete.gif\" border=\"0\" align=\"absmiddle\"/>";
		echo "</a>";

		echo "<a class=\"files\" href=\"download.php?file=".$filespace.$value."\">";
		echo "<img src=\"images/file.gif\" align=\"absmiddle\" border=\"0\" />";
		echo "$value";
		echo "</a>";
		echo "</td>";
		
		//File size in kb or mb.. 
		$fsize = file_size(filesize($filespace.$value));
		echo "<td class=\"weekfile\" align=\"right\"><small>$fsize</small></td>";
		
		//Show file type description! Apply for common file, and default to plain EXT file as the type
		echo "<td class=\"weekfile\" align=\"left\"><small>".myfiletype($value)."</small></td>";
		
		//Formate and show date
		$fdate = filemtime($filespace.$value);			
		$fdate = date("m/j/y h:i A", $fdate);
		echo "<td class=\"weekfile\" align=\"left\"><small>$fdate</small></td>";
		
		//Get comments and author then  format and show them
		$sql = "select author, comment from weekly_filelog where filename='$value'";
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
		echo "<td class=\"weekfile\" align=\"left\"><small>$author</small></td>";
		echo "<td class=\"weekfile\" align=\"left\"><small><html:abbr title=\"$comment\">$shortcomment</html:abbr></small></td>";
		echo "</tr>";
	}
	echo "<tr>";
	echo "<td class=\"week weekbottom\" colspan=\"6\">&nbsp;</td>";
	echo "</tr>";
	
	//Offer upload option to everyone I suppose..show object count to all users
	echo "<tr>";
	echo "<td class=\"week\" colspan=\"6\">";
	echo "&nbsp;".count($files)." File(s)";
	echo "<br />";

		echo "<img src=\"images/plus.gif\" align=\"absmiddle\" />";
		echo "<a href=\"#weekly\" onClick=\"javascript:toggleBox('weekly_upload',1);\">Add new documents to this folder</a>";
		echo "&nbsp;&nbsp;";
	echo "</td>";
	echo "</tr>";
	echo "</table>";

	//Clear arrays and path var
	unset($filespace,$files, $dirs);
?>
		</div><!-- /rbcontent -->
	<div class="rbbot"><div></div></div>
</div><!-- /rbroundbox -->
	<br />
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
	<form name="single" method="post" action="<?php echo "$PHP_SELF?page=weekly&mode=single"; ?>" enctype="multipart/form-data" onSubmit="return validateSingle(this);">
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
	<form name="del" method="post" action="<?php echo "$PHP_SELF?page=weekly&mode=del"; ?>" enctype="multipart/form-data" >
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



</div>




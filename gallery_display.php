<?php


dbConnect();
//See if the user has the photos role
$rsql = "select roles from companies where company_id = $usercompany";
$rq = mysql_query($rsql);
$ro = mysql_result($rq,0,"roles");
$roles =  explode(",",$ro);	


$sql = "select sitename from projects where id=$id";
$result = mysql_query($sql);
	if (!$result){error("A databass error has occured.\\n".mysql_error());}
$project=mysql_fetch_object($result);
	
$sql = "select author, comment from gallerylog where galleryname='$name' and project= $id";
$result= mysql_query($sql);
	if (!$result){error("A databass error has occured.\\n".mysql_error());}
$distro = mysql_fetch_object($result);
?>	
	<h1>:: Project Files</h1>
	<div class="databox">
<?php
	echo "<div class=\"filebox\">";
	
	 if (($usergroup == 0) or (in_array("photos",$roles))) {
		echo '<div class="pull-right">';
		echo "<div><a class=\"btn btn-mini\" href=\"image-rotate.php?id=$id&name=$name&d=90\">Rotate all 90</a> <a class=\"btn btn-mini\" href=\"image-rotate.php?id=$id&name=$name&d=180\">Rotate all 180</a></div>";
		echo '</div>';
	}

	if ( $unique_user_id == 1) {
		echo '<div class="pull-right">';
		echo "<div><a class=\"btn btn-mini\" href=\"image-compress.php?id=$id&name=$name\">Compress All</a></div>";
		echo '</div>';
	}


	echo "<h2>$project->sitename</h2>";
	echo "<p><strong>Album Name: </strong>$name&nbsp;";
	echo "(<a href=\"$PHP_SELF?page=project&id=$id\"><img src=\"images/levelup.gif\" border=\"0\" align=\"absmiddle\" /> Go back to project page</a>)<br />";

	if ($distro->author != ""){echo "<strong>Uploaded by: </strong>$distro->author<br />";}
	if ($distro->comment != ""){$distro->comment=stripslashes($distro->comment);echo "<strong>Comments: </strong>$distro->comment</p>";}
	
	echo "</div>";
	
	echo "<br />";
	
	//scan for folders and files that are NOT photos and NOT system folders or files
	$filespace = "filespace/$id/photos/$name/";
	$files = array();
	$dirs  = array();
	$handle=opendir($filespace);
	while (false !== ($file = readdir($handle)))
	{
		if($file=='.'||$file=='..'||$file=='.htaccess'||$file=='thumbs')
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
	echo "<table class=\"gallery-grid\" width=\"100%\" cellpadding=\"8\" cellspacing=\"0\">";
	echo "<tr>";
	$tablecol=0;

	//Now do files in root!
	foreach($files as $key=>$value)
	{
		$rawvalue = rawurlencode($value);
		//Show file name and link to it
		echo '<td style="width:auto;text-align:center;overflow:hidden;font-size:11px;">';
		echo "<a href=\"".$filespace.$rawvalue."\" class=\"thickbox\" rel=\"gallery\">";

		$ext = substr($value,-3);
		$ext = strtolower($ext);

		if ($ext == "jpg")
			//echo "<img src=\"".$filespace."thumbs/$rawvalue\" align=\"absmiddle\" border=\"0\" width=\"150\" style=\"border:1px #000 solid;\" />";
			echo '<img src="/phpthumb/phpThumb.php?src=/'.$filespace.$rawvalue.'&w=150" align="absmiddle" width="150" style="border:1px #000 solid;" />';
			
		else
			echo "<img src=\"images/nothumb.jpg\" align=\"absmiddle\" border=\"0\" style=\"border:1px #000 solid;\" />";

		echo "</a>";

		echo '<div>'.$value.'</div>';

		if (($usergroup == 0) or (in_array("photos",$roles))) {
			echo "<a title=\"Delete\" href=\"#delete\" class=\"btn btn-mini btn-danger\" onClick=\"javascript:toggleBox('delete',1);setDelFile('".$filespace."','".addslashes($value)."');\"><i class=\"icon-white icon-trash\"></i></a>&nbsp;";
			echo "<a title=\"Rotate 90\" href=\"image-rotate.php?id=$id&name=$name&img=$rawvalue&d=-90\" class=\"btn btn-mini btn-inverse \"><i class=\"icon-white icon-share-alt\"></i></a>&nbsp;";
		}
		echo "<a title=\"View\" href=\"#\" class=\"btn btn-mini btn-inverse\" onClick=\"$(this).prevAll('a.thickbox').click();\"><i class=\"icon-white icon-zoom-in\"></i></a>&nbsp;";
		echo "</td>";
		$tablecol++;
		
		if ($tablecol==3) {
			echo "</tr><tr>";
			$tablecol=0;
		}
	}
	if ($tablecol == 1)
		echo "<td></td><td></td>";
	if ($tablecol == 2)
		echo "<td></td>";

	echo "</tr>";
	echo "</table>";
	echo "<br />";
	//Offer upload option to admins, show object count to all users
	echo "<div class=\"filebox\">";
	echo "<p>&nbsp;".count($files)." Photo(s) in this album";
	echo "&nbsp;&nbsp;";
	echo "<img src=\"images/download.gif\" align=\"absmiddle\" />&nbsp;";
	echo "<a href=\"get-distro-zip.php?id=$id&folder=$name&photos=1\">Download this entire folder as a ZIP</a>";

	echo "</p>";
	
	if (($usergroup == 0) or (in_array("photos",$roles))) {
		echo "<p><img src=\"images/plus.gif\" align=\"absmiddle\" />&nbsp;";
		echo "<a href=\"#upload\" onClick=\"javascript:toggleBox('upload',1);\">Add new photos  to this album</a>";
		echo "<br />";
		if (count($files) == 0) {
			echo "<img src=\"images/minus.gif\" align=\"absmiddle\" />&nbsp;&nbsp;";
			echo "<a href=\"#remove\" onClick=\"javascript:toggleBox('remove',1);\">Remove empty album!</a></p>";
		}
	}
	echo "</div>";

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
	<p>Select the photos from your computer that you want to add to this album.</p>
	<form name="upload" method="post" action="<?php echo "$PHP_SELF?page=gallery&mode=upload"; ?>" enctype="multipart/form-data" >
	<input class="files" type="hidden" name="project_id" value="<?php echo $id; ?>"></input>
	<input class="files" type="hidden" name="project_folder_name" value="<?php echo $name; ?>"></input>
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
	<p>You are about to delete an empty folder from the database. This is useful when cleaning up files. File removal is done this way for added security. If you want to remove a large amount of files, please contact the site admin for help.</p>
	<form name="remove" method="post" action="<?php echo "$PHP_SELF?page=gallery&mode=remove"; ?>" enctype="multipart/form-data" >
	<table width="99%" cellspacing="10" class="litezone">
	<input class="files" type="hidden" name="project_id" value="<?php echo $id; ?>"></input>
	<input class="files" type="hidden" name="project_folder_name" value="<?php echo $name; ?>"></input>
		<tr>
		<td width="200" align="right" valign="top"><small><strong>Are you sure you want to remove this album?</strong></small></td>
		<td>
		<input class="files" type="submit" name="submit" value="yes, delete" onKeyPress="return noenter()">&nbsp;
		<input class="files" type="reset" name="reset" value="no, cancel" onClick="javascript:toggleBox('remove',0);">
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
	<p>You are about to delete an image from the database.</p>
	<form name="del" method="post" action="<?php echo "$PHP_SELF?page=gallery&mode=del"; ?>" enctype="multipart/form-data" >
	<table width="99%" cellspacing="10" class="litezone">
		<tr>
		<td width="200" align="right" valign="top"><small><strong>Delete this photo?</strong></small></td>
		<td>
		<input class="files" type="hidden" name="del_file_path"></input>
		<input class="files" type="hidden" name="project_id" value="<?php echo $id; ?>"></input>
		<input class="files" type="hidden" name="project_folder_name" value="<?php echo $name; ?>"></input>
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
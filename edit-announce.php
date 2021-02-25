<?php // edit-announce.php


if (!isset($submit)):
	dbConnect();
	$sql = "select * from blog where readers='$readers'";
	$result = mysql_query($sql);
	if (!$result)
		error("A databass error has occured in processing your request.\\n". mysql_error());
	while ($row = mysql_fetch_array($result)) {

		$ts = $row["ts"];
			$ts = revertTimestamp($ts);
		$newsubject = $row["subject"];
		$newbody= $row["body"];
			$newbody = stripslashes($newbody);
		$author = $row["author"];
	}
?>

<div id="content">

<h1>:: Edit Annoncement</h1>
<div class="databox">
<form name="edit" method="post" action="<?php echo "$PHP_SELF?page=edit-announce&readers=$readers"; ?>">
<table class="litezone" width="100%">
<tr>
<td align="right"><strong>Title:</strong></td>
<td><input class="files" type="text" name="newsubject" size="51" maxlength="64" value="<?=$newsubject?>" onKeyPress="return noenter()"></td>
</tr>
<tr>
<td align="right"><strong>Body:</strong></td>
<td><textarea class="files" name="newbody" cols="50" rows="12"><?=$newbody?></textarea></td>
</tr>
<tr>
<td></td>
<td>
<input class="files" type="submit" name="submit" value="submit">&nbsp;
<input class="files" type="button" name="button" value="cancel" onClick="history.back()">
</td>
</tr>
</table>
</form>
</div>

</div>

</body>
</html>


<?php
else:
    // Process edit submission
    dbConnect();

    $newbody = addslashes($newbody);
    
	$sql = "update blog set 
		subject = '$newsubject',
		author = '$username',
		body = '$newbody' where readers = '$readers'";
		
	if (!mysql_query($sql))
		error("A database error occured in proccessing your submission.\\n".mysql_error());
		
	$newbody = stripslashes($newbody);
?>


<div id="content">
<h1>:: Announcement Edited</h1>
<div class="databox">
<h2>Success!</h2>
<p>The announcement has been updated.</p>
<table class="litezone" width="100%">
<tr>
<td width="100" align="right"><strong>Author:</strong></td>
<td><input disabled class="files" type="text" name="newsubject" size="51" maxlength="64" value="<?=$username?>" onKeyPress="return noenter()"></td>
</tr>
<tr>
<td align="right"><strong>Title:</strong></td>
<td><input class="files" type="text" name="newsubject" size="51" maxlength="64" value="<?=$newsubject?>" onKeyPress="return noenter()"></td>
</tr>
<tr>
<td align="right"><strong>Body:</strong></td>
<td><textarea class="files" name="newbody" cols="50" rows="12"><?=$newbody?></textarea></td>
</tr>
</table>
<p><img src="images/edit.gif" align="absmiddle" /><a href="<?=$_SERVER['PHP_SELF']?>?page=edit-announce&readers=<?=$readers?>">:: Edit this announcement again</a>&nbsp;<img src="images/home.gif" align="absmiddle" /><a href="<?=$_SERVER['PHP_SELF']?>?page=admin">:: Return to admin page</a></p>
</div>
</div>


<?php
endif;
?>
<?php // edit-profile.php


if (!isset($submit)):
	dbConnect('planetg0_projects');
	
	$sql = "select * from users where userid='$uid' and pwd='$pwd'";
	$result = mysql_query($sql)
		or die("Query failed...".mysql_error());
	while ($row = mysql_fetch_array($result)) {
		$id = $row["id"];
		$email = $row["email"];
		$notes = $row["comment"];
		$notes = stripslashes($notes);
		}
?>

<div id="content">

<h1>:: Edit profile info</h1>
<div class="databox">
<form name="edit" method="post" action="<?php echo "$PHP_SELF?page=edit-profile"; ?>">
<table>
<tr>
<td valign="top">E-mail address:</td>
<td><input class="files" type="text" name="newemail" size="25" maxlength="64" value="<?=$email?>" onKeyPress="return noenter()">*
<p>This is the e-mail address where you will receive notifications.</p>
</td>
</tr>

<tr>
<td>Notes about user:</td>
<td><textarea class="files" name="newnotes" cols="30" rows="5"><?=$notes?></textarea></td>
</tr>

<tr>
<td></td>
<td>
<input class="files" type="submit" name="submit" value="submit">&nbsp;
<input class="files" type="button" name="button" value="cancel" onClick="history.back()">
</td></tr>
</table>
</form>
</div>

</div>

</body>
</html>


<?php
else:
    // Process edit submission
    dbConnect('planetg0_projects');

    if ($newemail=="") {
        error("One or more required fields were left blank.\\n".
              "Please fill them in and try again.");
    }
    $newnotes = addslashes($newnotes);
    
	$sql = "update users set 
		email = '$newemail',
		comment = '$newnotes',
		datetouched=CURDATE() where userid = '$uid'";
		
	if (!mysql_query($sql))
		error("A database error occured in proccessing your submission.\\n".mysql_error());

?>


<div id="content">
<h1>:: Profile edited</h1>
<div class="databox">
<h2>Success!</h2>
	<p>User profile for <b><?=$newname?></b> has been edited.</p>

	<table>
	<tr>
	<td>E-mail:</td>
	<td><?=$newemail?></td>
	</tr>
	<tr>
	<td>Comment:</td>
	<td><?=$newnotes?></td>
	</tr>
	</table>
	<p>[<a href="<?=$_SERVER['PHP_SELF']?>">Return to home page</a>]</p>
	</div>
</div>

	</body>
	</html>

<?php
endif;
?>
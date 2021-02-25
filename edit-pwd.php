<?php // edit-pwd.php

if (!isset($submit)):
?>
<body onLoad="document.edit.oldpwd.focus()">
<div id="content">

<h1>:: Change password</h1>
<div class="databox">
<form name="edit" method="post" action="<?php echo "$PHP_SELF?page=edit-pwd"; ?>">
<table>
<tr>
<td>Old password:</td>
<td><input class="files" type="password" name="oldpwd" size="25" maxlength="64" onKeyPress="return noenter()">*</td>
</tr>

<tr>
<td>New password:</td>
<td><input class="files" type="password" name="newpwd" size="25" maxlength="64" onKeyPress="return noenter()">*</td>
</tr>

<tr>
<td>Confirm new password:</td>
<td><input class="files" type="password" name="newpwdconfirm" size="25" maxlength="64" onKeyPress="return noenter()">*</td>
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

<?php
else:
    // Process password change submission
    dbConnect();
    	
	if ($oldpwd != $pwd) {
error("You must enter the correct OLD password to proceed");
	}

    if ($newpwd != $newpwdconfirm) {
error("New password and confirmed password must match!");
    }
    
	
	session_unregister("pwd");
	$pwd = $newpwd;
	session_register("pwd");
    
	$sql = "update users set 
		pwd = '$pwd',
		datetouched=CURDATE() where userid = '$uid'";
		
	if (!mysql_query($sql))
		error("A database error occured in proccessing your submission.\\n".mysql_error());

?>


<div id="content">
<h1>:: Password changed</h1>
<div class="databox">
<h2>Success!</h2>
	<p>Your password have been changed!</p>
	<p>[<a href="<?=$_SERVER['PHP_SELF']?>">Login to the home page with your new password</a>]</p>

</div>
</div>

<?php
endif;
?>
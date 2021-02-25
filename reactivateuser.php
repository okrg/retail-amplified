<?php
if ($usergroup != 0) {
echo "You do not have sufficient privledges to view this page";
exit;
} else {
if (!isset($delete)):
?>

<div id="content">
<h1>:: Reactivate Account?</h1>
<div class="databox">
<form name="form1" method="post" action="<?php echo "$PHP_SELF?page=reactivateuser&id=$id"; ?>">
   <h3><img src="images/user.gif" />Notice</h3>
   	<p>You are about to reactivate an account and it will now be active. The account can be cancalled again in the future if needed.</p>
          <p>Are you sure that you want to reactivate this user account?</p>
<input type="submit" name="delete" value="yes, reactivate account">
<input type="button" name="button" value="no, go back" onClick="history.back()">
</form>

</div>
</div>

<?php
else:
    // Process user delete
	dbConnect();
	$sql = "update users set active = 1, datetouched=CURDATE() where id = $id";
	if (!mysql_query($sql))
	error("A database error occured in proccessing your submission.\\n".mysql_error());
?>
<div id="content">
<h1>:: Account reactivated</h1>
<div class="databox">
<p>The user account has been reactivated and can now be logged into unless it is cancelled again.</p>
<p><a href="<?=$_SERVER['PHP_SELF']?>?page=admin-users">&laquo; Back to User Groups</a></p>
</div>
</div>
<?php endif; } ?>
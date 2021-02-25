<?php
if ($usergroup != 0) {
echo "You do not have sufficient privledges to view this page";
exit;
} else {
if (!isset($delete)):
?>

<div id="content">
<h1>:: Cancel Account?</h1>
<div class="databox">
<form name="form1" method="post" action="<?php echo "$PHP_SELF?page=deluser&id=$id"; ?>">
   <h3><img src="images/delete.gif" />Warning</h3>
   	<p>You are about to cancel an account and it will no longer be active. The account can be reactivated in the future if needed.</p>
          <p>Are you sure that you want to cancel this user account?</p>
<input type="submit" name="delete" value="yes, cancel account">
<input type="button" name="button" value="no, go back" onClick="history.back()">
</form>

</div>
</div>

<?php
else:
    // Process user delete
	dbConnect();
	$sql = "update users set active = 0, datetouched=CURDATE() where id = $id";
	if (!mysql_query($sql))
	error("A database error occured in proccessing your submission.\\n".mysql_error());
?>
<div id="content">
<h1>:: Account cancelled</h1>
<div class="databox">
<p>The user account has been cancelled and can not be logged into unless it is re-activated.</p>
<p><a href="<?=$_SERVER['PHP_SELF']?>?page=admin-users">&laquo; Back to User Groups</a></p>
</div>
</div>
<?php endif; } ?>
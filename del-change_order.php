<?php
if (!isset($delete)):
dbConnect();
$q = mysql_query("select co_num, loc_key from change_orders where id={$_GET['id']}");
$co = mysql_fetch_object($q);
?>



<div id="content">
<h1>:: Delete change order?</h1>
<div class="databox">
<form name="form1" method="post" action="<?php echo "$PHP_SELF?page=del-change_order&id={$_GET['id']}"; ?>">
   <h3><img src="images/delete.gif" />Warning!</h3>
   
          <p>Are you sure that you want to delete change order #<?=$co->co_num?> from the system?</p>
<input class="files" type="submit" name="delete" value="yes, delete">
<input class="files" type="button" name="button" value="no, cancel" onClick="history.back()">
</form>

</div>
</div>

<?php
else:
    // Process project delete
   dbConnect();
	$q = mysql_query("select co_num, loc_key from change_orders where id={$_GET['id']}");
	$co = mysql_fetch_object($q);

	$sql = "delete from change_orders where id = {$_GET['id']}";
	if (!mysql_query($sql))
	error("A database error occured in proccessing your submission.\\n".mysql_error());
?>
<div id="content">
<h1>:: Deleted Change Order</h1>

<div class="databox">
<p>The change order has been deleted from the system.</p>
<p><a href="index.php?page=project&id=<?=$co->loc_key?>"><img src="images/levelup.gif" border="0" align="absmiddle" /> Return to Project Page</a></p>
</div>
</div>
<?php endif; ?>
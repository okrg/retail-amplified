<?php
if ($usergroup != 0) {
echo "You do not have sufficient privledges to view this page";
exit;
} else {
if (!isset($delete)):

	dbConnect();
	$sql = "select * from companies where company_id={$_GET['id']}";
	$result = mysql_query($sql)
		or die("Query failed...");
	while ($row = mysql_fetch_array($result)) {
		$company = $row["company_name"];
		}
	$company = stripslashes($company);
?>

<div id="content">
<h1>::Cancel company?</h1>
<div class="databox">
<form name="form1" method="post" action="<?php echo "$PHP_SELF?page=delcompany&id=$id"; ?>">
   <h3><img src="images/delete.gif" />Warning</h3>
   	<p>You are about to cancel <?=$company?>  from the list of companies in the database.</p>
          <p>Are you sure that you want to cancel this company?</p>
<input type="submit" name="delete" value="yes, cancel company">
<input type="button" name="button" value="no, go back" onClick="history.back()">
</form>

</div>
</div>

<?php
else:
    // Process user delete
   dbConnect();
	$sql = "update companies set active = 0 where company_id = $id";

	if (!mysql_query($sql))
	error("A database error occured in proccessing your submission.\\n".mysql_error());

?>
<div id="content">
<h1>:: Company cancelled</h1>
<div class="databox">
<p>The company has been cancelled from the database.</p>
<p>[<a href="<?=$_SERVER['PHP_SELF']?>?page=admin-companies">Return to company list</a>]</p>

</div>
</div>
<?php endif; } ?>
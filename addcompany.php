<?php // addcompany.php
if ($usergroup != 0) {
echo "You do not have sufficient privledges to view this page";
exit;
} else {
if (!isset($submit)):
?>
<div id="content">

<h1>:: Add a new company</h1>
<div class="databox">
<form name="edit" method="post" action="<?php echo("$PHP_SELF?page=addcompany"); ?>">

<table id="inputform" cellpadding="0" cellspacing="0">
<tr>
	<td class="col1">Vendor Name</td>
	<td class="col2"><input type="text" name="newcompany_name" size="25" maxlength="64" onKeyPress="return noenter()">*</td>
</tr>
<tr>
	<td class="col1">Vendor Roles</td>
	<td class="col2">
	<input type="checkbox" name="role_plans" id="plans" value="plans" />&nbsp;<label for="plans">Plans</label><br />
	<input type="checkbox" name="role_weekly" id="weekly" value="weekly" />&nbsp;<label for="weekly">Weekly Reports</label><br />
	<input type="checkbox" name="role_freq" id="freq" value="freq" />&nbsp;<label for="freq">Fixture Requests</label><br />
	<input type="checkbox" name="role_g2" id="g2" value="g2" />&nbsp;<label for="g2">Repair Orders</label><br />
</td></tr>
</table>

<input type="submit" name="submit" value="submit">&nbsp;
<input type="button" name="button" value="cancel" onClick="history.back()">
</form>
</div>

</div>

<?php
else:
// Process edit submission
if ($newcompany_name == ""){
		error("Not all the required fields have been filled out.");}

	dbConnect();
    $sql = "SELECT COUNT(*) FROM companies WHERE company_name LIKE '$newcompany_name'";
    $result = mysql_query($sql);
    if (!$result) {	
        error("A database error occurred in processing your submission.\\n".mysql_error());
   }
    if (mysql_result($result,0,0)>0) {
        error("A company already exists with that name.\\n".
              "Perhaps you do not need to add this company after all.");
    }
	$new_roles .= "null,";
	//Create the text version of the role array somehow...
	if (isset($_POST['role_plans'])) {$new_roles .= "plans,";}
	if (isset($_POST['role_weekly'])) {$new_roles .= "weekly,";}
	if (isset($_POST['role_freq'])) {$new_roles .= "freq,";}
	if (isset($_POST['role_g2'])) {$new_roles .= "g2,";}		

	$sql = "insert into companies set company_name = '$newcompany_name', roles = '$new_roles'";
	if (!mysql_query($sql))
		error("A database error occured in proccessing your submission.\\n".mysql_error());
	$new_id = mysql_insert_id();
	$company_name = stripslashes($newcompany_name);
	$roles = explode(",",$new_roles);

	?>

<div id="content">
<h1>:: Company added</h1>
<div class="databox">
<h2>Success!</h2>
	
<p><img src="images/user.gif" alt="user" />&nbsp;New company was added: <b><?=$company_name?></b> have been created.</p>
<p>Special Privleges:<br />
	<input disabled type="checkbox" name="role_plans" <?php if (in_array("plans",$roles)){echo " checked";}?> />&nbsp;<label for="plans">Plans</label><br />
	<input disabled type="checkbox" name="role_weekly" <?php if (in_array("weekly",$roles)){echo " checked";}?> />&nbsp;<label for="weekly">Weekly Reports</label><br />
	<input disabled type="checkbox" name="role_freq" <?php if (in_array("freq",$roles)){echo " checked";}?> />&nbsp;<label for="freq">Fixture Requests</label><br />
	<input disabled type="checkbox" name="role_g2" <?php if (in_array("g2",$roles)){echo " checked";}?> />&nbsp;<label for="g2">Repair Orders</label><br />
</p>
<a href="<?=$_SERVER['PHP_SELF']?>?page=adduser">Add new users who belong to this company.</a><br /><br />
<a href="<?=$_SERVER['PHP_SELF']?>?page=editcompany&id=<?=$new_id?>">Edit these company settings.</a><br /><br />
<a href="<?=$_SERVER['PHP_SELF']?>?page=admin-companies">Return to the company list.</a>


</div>
</div>

<?php
endif;
}
?>
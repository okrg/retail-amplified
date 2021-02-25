<?php // edituser.php
if ($usergroup == 0) {

if (!isset($submit)):
	dbConnect();
	$sql = "select * from companies where company_id={$_GET['id']}";
	$result = mysql_query($sql)
		or die("Query failed...");

	$data = mysql_fetch_object($result);
	$company = stripslashes($data->company_name);
	$roles = explode(",",$data->roles);
	$active = $data->active;

?>
<div id="content">

<h1>:: Edit Vendor Settings</h1>
<div class="databox">
<table id="inputform" cellpadding="0" cellspacing="0">
<form name="edit" method="post" action="<?php echo "$PHP_SELF?page=editcompany&id=$id";?>">
<tr>
	<td class="col1">Vendor Name</td>
	<td class="col2"><input type="text" name="newcompany_name" size="25" maxlength="64" value="<?=$company?>" onKeyPress="return noenter()">*</td>
</tr>
<tr>
	<td class="col1">Category</td>
	<td class="col2">
		<label class="checkbox" style="display:block;">
		<input type="checkbox" value="" name="lighting" <?php if($data->lighting == 1){ echo ' checked="checked"';}?>>Lighting</label>
		
		<label class="checkbox" style="display:block;">
		<input type="checkbox" value="" name="gc" <?php if($data->gc == 1){ echo ' checked="checked"';}?>>General Contractor</label>
		
		<label class="checkbox" style="display:block;">
		<input type="checkbox" value="" name="architect" <?php if($data->architect == 1){ echo ' checked="checked"';}?>>Architect / Engineer</label>
		
		<label class="checkbox" style="display:block;">
		<input type="checkbox" value="" name="storefront" <?php if($data->storefront == 1){ echo ' checked="checked"';}?>>Storefront</label>
		
		<label class="checkbox" style="display:block;">
		<input type="checkbox" value="" name="signage" <?php if($data->signage == 1){ echo ' checked="checked"';}?>>Signage</label>
		
		<label class="checkbox" style="display:block;">
		<input type="checkbox" value="" name="flooring" <?php if($data->flooring == 1){ echo ' checked="checked"';}?>>Flooring</label>
		
		<label class="checkbox" style="display:block;">
		<input type="checkbox" value="" name="millwork" <?php if($data->millwork == 1){ echo ' checked="checked"';}?>>Millwork</label>
		
		<label class="checkbox" style="display:block;">
		<input type="checkbox" value="" name="wall_covering" <?php if($data->wall_covering == 1){ echo ' checked="checked"';}?>>Wall covering</label>
		
		<label class="checkbox" style="display:block;">
		<input type="checkbox" value="" name="decorative_elements" <?php if($data->decorative_elements == 1){ echo ' checked="checked"';}?>>Decorative Elements</label>
		
		<label class="checkbox" style="display:block;">
		<input type="checkbox" value="" name="fixtures" <?php if($data->fixtures == 1){ echo ' checked="checked"';}?>>Fixtures</label>
		
		<label class="checkbox" style="display:block;">
		<input type="checkbox" value="" name="backroom_shelving" <?php if($data->backroom_shelving == 1){ echo ' checked="checked"';}?>>Backroom Shelving</label>
		
		<label class="checkbox" style="display:block;">
		<input type="checkbox" value="" name="music" <?php if($data->music == 1){ echo ' checked="checked"';}?>>Music</label>
		
		<label class="checkbox" style="display:block;">
		<input type="checkbox" value="" name="phones" <?php if($data->phones == 1){ echo ' checked="checked"';}?>>Phones</label>
		
		<label class="checkbox" style="display:block;">
		<input type="checkbox" value="" name="mannequins" <?php if($data->mannequins == 1){ echo ' checked="checked"';}?>>Mannequins</label>
		
		<label class="checkbox" style="display:block;">
		<input type="checkbox" value="" name="alarm" <?php if($data->alarm == 1){ echo ' checked="checked"';}?>>Alarm</label>
		
		<label class="checkbox" style="display:block;">
		<input type="checkbox" value="" name="misc" <?php if($data->misc == 1){ echo ' checked="checked"';}?>>Miscellaneous</label>
		



	</td>
</tr>
<tr>
	<td class="col1">Vendor Roles</td>
	<td class="col2">
	<lable class="checkbox">
	<input type="checkbox" name="role_plans" id="plans" value="plans"<?php if (in_array("plans",$roles)){echo " checked";}?>>Plans <span class="help">Can view and upload new drawing files for projects they are assigned to</span></label><br />

	<lable class="checkbox">
   	<input type="checkbox" name="role_photos" id="photos" value="photos"<?php if (in_array("photos",$roles)){echo " checked";}?>>Photos <span class="help">Can upload new photos for projects they are assigned to</span></label><br />

	<lable class="checkbox">    
	<input type="checkbox" name="role_weekly" id="weekly" value="weekly"<?php if (in_array("weekly",$roles)){echo " checked";}?>>Weekly Reports <span class="help">Can view and update their own weekly reports</span></label><br />

	<lable class="checkbox">
	<input type="checkbox" name="role_freq" id="freq" value="freq"<?php if (in_array("freq",$roles)){echo " checked";}?>>Fixture Requests <span class="help">Can view and reply to fixture requests they are assigned to</span></label><br />

	<lable class="checkbox">
	<input type="checkbox" name="role_g2" id="g2" value="g2"<?php if (in_array("g2",$roles)){echo " checked";}?>>Repair Orders <span class="help">Can view and reply to repair order requests they are assigned to</span></label><br />
</td></tr>
</table>
<br /><br />
<input type="submit" name="submit" value="submit">&nbsp;
<input type="button" name="button" value="cancel" onClick="history.back()"><br /><br />
<?php
if ($active == 1) {
?>
<p>To deactivate this company so that it no long appears on lists you must cancel the company. This does not delete the company name, instead it keeps it stored so that report data can be preserved. The compnay can be reactivated in the future if needed.</p>
<img src="images/delete.gif" align="absmiddle" /><input type="button" name="cancel" value="Cancel company" onClick="window.location='index.php?page=delcompany&id=<?=$id?>'">
<?php
}elseif ($active == 0) {
?>
<p>To activate this cancelled company so that it can appear on lists you must reactivate the company.</p>
<img src="images/user.gif" align="absmiddle" /><input type="button" name="reactivate" value="Reactivate company" onClick="window.location='index.php?page=reactivatecompany&id=<?=$id?>'">
<?php
}
?>

</form>
</div>

</div>

<?php
else:
    // Process edit submission
    if ($newcompany_name=="") {
	    error("You cannot submit a blank name for the vendor!");
    }
    dbConnect();
	//Check for duplicates
    $sql = "SELECT COUNT(*) FROM companies WHERE company_name LIKE '$newcompany_name' and company_id != $id";
    $result = mysql_query($sql);
    if (!$result) {	
        error("A database error occurred in processing your submission.\\n".mysql_error());
	}
	if (mysql_result($result,0,0)>0) {
        error("A company already exists with that name...");
    }
	$new_roles .= "null,";
	//Create the text version of the role array somehow...
	if (isset($_POST['role_plans'])) {$new_roles .= "plans,";}
	if (isset($_POST['role_photos'])) {$new_roles .= "photos,";}
	if (isset($_POST['role_weekly'])) {$new_roles .= "weekly,";}
	if (isset($_POST['role_freq'])) {$new_roles .= "freq,";}
	if (isset($_POST['role_g2'])) {$new_roles .= "g2,";}	
	
	$lighting = (isset($_POST['lighting'])) ? "1":"0";
	$gc = (isset($_POST['gc'])) ? "1":"0";
	$architect = (isset($_POST['architect'])) ? "1":"0";
	$storefront = (isset($_POST['storefront'])) ? "1":"0";
	$signage = (isset($_POST['signage'])) ? "1":"0";
	$flooring = (isset($_POST['flooring'])) ? "1":"0";
	$millwork = (isset($_POST['millwork'])) ? "1":"0";
	$wall_covering = (isset($_POST['wall_covering'])) ? "1":"0";
	$decorative_elements = (isset($_POST['decorative_elements'])) ? "1":"0";
	$fixtures = (isset($_POST['fixtures'])) ? "1":"0";
	$backroom_shelving = (isset($_POST['backroom_shelving'])) ? "1":"0";
	$music = (isset($_POST['music'])) ? "1":"0";
	$phones = (isset($_POST['phones'])) ? "1":"0";
	$mannequins = (isset($_POST['mannequins'])) ? "1":"0";
	$alarm = (isset($_POST['alarm'])) ? "1":"0";
	$misc = (isset($_POST['misc'])) ? "1":"0";
	


	//Write changes to DB
	$sql = "update companies set 
		company_name = '$newcompany_name', 
		lighting = $lighting,
		gc = $gc,
		architect = $architect,
		storefront = $storefront,
		signage = $signage,
		flooring = $flooring,
		millwork = $millwork,
		wall_covering = $wall_covering,
		decorative_elements = $decorative_elements,
		fixtures = $fixtures,
		backroom_shelving = $backroom_shelving,
		music = $music,
		phones = $phones,
		mannequins = $mannequins,
		alarm = $alarm,
		misc = $misc,
		roles = '$new_roles' where company_id = $id";
	if (!mysql_query($sql))
		error("A database error occured in proccessing your submission.\\n".mysql_error());
		$newcompany_name = stripslashes($newcompany_name);
		$roles = explode(",",$new_roles);

?>
<div id="content">
<h1>:: Company Name Edited</h1>
<div class="databox">
<h2>Success!</h2>
	<p>Settings for <b><?=$newcompany_name?></b> have been edited.</p>
	<p>Special Privleges:<br />

	<label class="checkbox" style="display:block;">
	<input disabled type="checkbox" name="role_plans" <?php if (in_array("plans",$roles)){echo " checked";}?>>Plans</label><br />

    <label class="checkbox" style="display:block;">
	<input disabled type="checkbox" name="role_photos" <?php if (in_array("photos",$roles)){echo " checked";}?>>Photos</label><br />    

    <label class="checkbox" style="display:block;">
	<input disabled type="checkbox" name="role_weekly" <?php if (in_array("weekly",$roles)){echo " checked";}?>>Weekly Reports</label><br />

    <label class="checkbox" style="display:block;">
	<input disabled type="checkbox" name="role_freq" <?php if (in_array("freq",$roles)){echo " checked";}?>>Fixture Requests</label><br />

    <label class="checkbox" style="display:block;">
	<input disabled type="checkbox" name="role_g2" <?php if (in_array("g2",$roles)){echo " checked";}?>>Repair Orders</label><br />
	
	</p>
	<a href="<?=$_SERVER['PHP_SELF']?>?page=editcompany&id=<?=$id?>">Make additional changes.</a><br /><br />
	<a href="<?=$_SERVER['PHP_SELF']?>?page=admin-companies">Return to list of companies.</a>
</div>
</div>

<?php
endif;

} else {
	echo "You do not have sufficient privledges to view this page";
	exit;
}
?>
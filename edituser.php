<?php // edituser.php

if ($usercompany < 10) 

{


if (!isset($submit)):
	dbConnect();
	
	$sql = "select * from users where id=$id";
	$result = mysql_query($sql)
		or die("Query failed...");
	while ($row = mysql_fetch_array($result)) {
		$id = $row["id"];
		$userid = $row["userid"];
		$passwd = $row["pwd"];
		$fullname = $row["fullname"];
		//$company = $row["company"];
		//this is the old way!
		$company_id = $row["company_id"];
		$title= $row["title"];
		$phone = $row["phone"];
		$fax = $row["fax"];
		$group = $row["groupid"];
		$email = $row["email"];
		$notes = $row["comment"];
		$active = $row["active"];
			$notes = addslashes($notes);
		}
		
		//Determine user header name :: Edit Vendor or ::Edit Corp. User or ::Edit Admin
		if ($group == 3) {$userheader_name = "Vendor";}
			elseif ($group < 2) {$userheader_name = "User";}
		
		//Get name of company from companies table using company_id as critera
		$co_id_result = mysql_query("select company_name from companies where company_id = $company_id");
		$company = mysql_result($co_id_result,0,"company_name");
		$company = stripslashes($company);

?>
<div id="content">
<div class="breadcrumbs">
<a href="/">Home</a> &raquo; 
<a href="<?=$_SERVER['PHP_SELF']?>?page=admin">Admin Options</a> &raquo; 
<a href="<?=$_SERVER['PHP_SELF']?>?page=admin-users">Users</a> &raquo; 
Edit Account</div>

<h1>:: Edit <?=$userheader_name?></h1>
<div class="databox">

<form name="edit" method="post" action="<?php echo "$PHP_SELF?page=edituser&id=$id";?>">
<table>
<tr>
<td align="right">User ID:</td>
<td><h3><input type="text" name="newid" size="25" maxlength="64" value="<?=$userid?>" onKeyPress="return noenter()">*</h3></td>
</tr>
<tr>
<td align="right">Password:</td>
<td><h3><input type="text" name="newpass" size="25" maxlength="64" value="<?=$passwd?>" onKeyPress="return noenter()">*</h3></td>
</tr>
<tr>
<td align="right">Full name:</td>
<td><input type="text" name="newname" size="25" maxlength="64" value="<?=$fullname?>" onKeyPress="return noenter()"></td>
</tr>
<tr>
<td align="right">Company:</td>
<!--<td><input type="text" name="newcompany" size="25" maxlength="64" value="<?=$company?>" onKeyPress="return noenter()"></td>-->
<td>
<select name="newcompany" class="files">
<?php

echo "<option value=\"$company_id\">$company</option>";
echo "<option value=\"\">____________</option>";
$result = mysql_query("select * from companies where active=1 order by company_name");
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	
	echo "<option value=\"{$row["company_id"]}\">".stripslashes($row["company_name"])."</option>";	
	}
mysql_free_result($result);
?>
</select>&nbsp;<small><a href="<?php echo "$PHP_SELF?page=addcompany";?>">New company? You must add it first</a></small>

</td>
</tr>
<tr>
<td align="right">Title:</td>
<td><input type="text" name="newtitle" size="25" maxlength="64" value="<?=$title?>" onKeyPress="return noenter()"></td>
</tr>
<tr>
<td align="right">Phone:</td>
<td><input type="text" name="newphone" size="25" maxlength="64" value="<?=$phone?>" onKeyPress="return noenter()"></td>
</tr>
<tr>
<td align="right">Fax:</td>
<td><input type="text" name="newfax" size="25" maxlength="64" value="<?=$fax?>" onKeyPress="return noenter()"></td>
</tr>
<tr>
<td align="right">E-mail address:</td>
<td><h3><input type="text" name="newemail" size="25" maxlength="64" value="<?=$email?>" onKeyPress="return noenter()">*</h3></td>
</tr>
<tr>
<td align="right">Group:</td>
<td><select name="newgroup" class="files">
		<option value="<?=$group?>" selected><?=$group?></option>
		<option value="0">Administrator</option>
		<option value="1">Charlotte Russe Home Office Staff</option>
		<option value="2">Charlotte Russe District and Regional Mgr</option>
		<option value="3">Vendor/Architect</option>
		</select><h3>*</h3></td>
</tr>
<tr>
<td align="right">Comment:</td>
<td><textarea name="newnotes" cols="30" rows="5"><?=$notes?></textarea></td>
</tr>
<tr>
<td></td>
<td>
<input type="submit" name="submit" value="submit">&nbsp;
<input type="button" name="button" value="cancel" onClick="history.back()"><br /><br />
<?php
if ($active == 1) {
?>
<p>To deactivate an account so that the person can no longer login you must cancel it. This does not delete the account information, instead it keeps it stored so that report data can be preserved. The account can be reactivated in the future if needed.</p>
<img src="images/delete.gif" align="absmiddle" /><input type="button" name="cancel" value="Cancel account" onClick="window.location='index.php?page=deluser&id=<?=$id?>'">
<?php
}elseif ($active == 0) {
?>
<p>To activate this cancelled account so that the person can login again you must reactivate it.</p>
<img src="images/user.gif" align="absmiddle" /><input type="button" name="reactivate" value="Reactivate account" onClick="window.location='index.php?page=reactivateuser&id=<?=$id?>'">
<?php
}
?>
</td></tr>
</table>
</form>
</div>

</div>

<?php
else:
    // Process edit submission
    dbConnect();

    if ($newid=="" or $newname=="" or $newemail=="") {
        error("One or more required fields were left blank.\\n".
              "Please fill them in and try again.");
    }
    
    if ($newcompany=="") {
	    error("Invalid company!");
    }
    $newnotes = addslashes($newnotes);
    
	$sql = "update users set 
		userid = '$newid',
		pwd = '$newpass',
		fullname = '$newname',
		company_id= '$newcompany',
		title = '$newtitle',
		phone = '$newphone',
		fax = '$newfax',
		email = '$newemail',
		groupid = '$newgroup',
		comment = '$newnotes',
		datetouched=CURDATE() where id = $id";
		
	if (!mysql_query($sql))
		error("A database error occured in proccessing your submission.\\n".mysql_error());
		
		$newnotes = stripslashes($newnotes);
		$newnotes = nl2br($newnotes);
		//Get name of company from companies table using company_id as critera
		$co_id_result = mysql_query("select company_name from companies where company_id = $newcompany");
		$company = mysql_result($co_id_result,0,"company_name");
		$company = stripslashes($company);



?>
<div id="content">
<h1>:: User file edited</h1>
<div class="databox">
<h2>Success!</h2>
	<p>User details for <b><?=$newname?></b> have been edited in the <a href="<?=$_SERVER['PHP_SELF']?>?page=admin#users">user system</a>.</p>
	<table>
	<tr>
	<td align="right">User ID:</td>
	<td><?=$newid?></td>
	</tr>
	<tr>
	<td align="right">Password:</td>
	<td><?=$newpass?></td>
	</tr>
	<tr>
	<td align="right">Company:</td>
	<td><?=$company?></td>
	</tr>
	<tr>
	<td align="right">Title:</td>
	<td><?=$newtitle?></td>
	</tr>
	<tr>
	<td align="right">Phone:</td>
	<td><?=$newphone?></td>
	</tr>
	<tr>
	<td align="right">Fax:</td>
	<td><?=$newfax?></td>
	</tr>
	<tr>
	<td align="right">Group:</td>
	<td><?=$newgroup?></td>
	</tr>
	<tr>
	<td align="right">E-mail:</td>
	<td><?=$newemail?></td>
	</tr>
	<tr>
	<td align="right">Notes:</td>
	<td><?=$newnotes?></td>
	</tr>
	</table>

	<p>[<a href="<?=$_SERVER['PHP_SELF']?>?page=admin-users">Return to user list</a>]</p>
</div>
</div>

<?php
endif;

} else {
	echo "You do not have sufficient privledges to view this page";
	exit;
}
?>
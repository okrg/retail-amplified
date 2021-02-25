<?php // adduser.php
if ($usergroup != 0) {
echo "You do not have sufficient privledges to view this page";
exit;
} else {
if (!isset($submit)):
?>
<div id="content">
<div class="breadcrumbs"><a href="/">Home</a> &raquo; <a href="<?=$_SERVER['PHP_SELF']?>?page=admin">Admin Options</a> &raquo; Add User</div>
<h1>:: Add user</h1>
<div class="databox">
<form name="edit" method="post" action="<?php echo("$PHP_SELF?page=adduser"); ?>">

<table>
<form name="edit" method="post" action="<?php echo("$PHP_SELF?page=edituser&id=$id"); ?>">
<tr>
<td align="right">User ID:</td>
<td><h3><input type="text" name="newid" size="25" onKeyPress="return noenter()">*</h3></td>
</tr>
<tr>
<td align="right">Full name:</td>
<td><input type="text" name="newname" size="25" onKeyPress="return noenter()"></td>
</tr>
<tr>
<td align="right">Company:</td>
<!-- <td><input type="text" name="newcompany" size="25" onKeyPress="return noenter()"></td> -->
<td>
<select name="newcompany" class="files">
<?php

echo "<option value=\"\">-Select-</option>";
$result = mysql_query("select * from companies order by company_name");
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
<td><input type="text" name="newtitle" size="25" onKeyPress="return noenter()"></td>
</tr>
<tr>
<td align="right">Phone:</td>
<td><input type="text" name="newphone" size="25" onKeyPress="return noenter()"></td>
</tr>
<tr>
<td align="right">Fax:</td>
<td><input type="text" name="newfax" size="25" onKeyPress="return noenter()"></td>
</tr>
<tr>
<td align="right">E-mail address:</td>
<td><h3><input type="text" name="newemail" size="25" onKeyPress="return noenter()">*</h3></td>
</tr>
<tr>
<td align="right">Group:</td>
<td><select name="newgroup" class="files">
		<option value="" selected>Add to which group?</option>
		<option value="0">Administrator</option>
		<option value="1">Charlotte Russe Home Office Staff</option>
		<option value="2">Charlotte Russe District and Regional Mgr</option>
		<option value="3">Vendor/Architect</option>
		</select>*</td>
</tr>
<tr>
<td align="right">Comment:</td>
<td><textarea name="newnotes" cols="30" rows="5"><?=$notes?></textarea></td>
</tr>
</table>
<input type="submit" name="submit" value="submit">&nbsp;
<input type="button" name="button" value="cancel" onClick="history.back()">
</form>
</div>

</div>

<?php
else:
    // Process edit submission
if ($newid == "" or $newemail == "" or $newgroup == "" or $newcompany == ""){
	error("Not all the required fields have been filled out.");}

    // Check for existing user with the new id
    dbConnect('planetg0_projects');
    $sql = "SELECT COUNT(*) FROM users WHERE userid = '$newid'";
    $result = mysql_query($sql);
    if (!$result) {	
        error("A database error occurred in processing your submission.\\n".mysql_error());

   }
    if (mysql_result($result,0,0)>0) {
        error("A user already exists with your chosen userid.\\n".
              "Please try another.");
    }

	$newpass = substr(md5(time()),0,6);
	$newnotes = addslashes($newnotes);
	$sql = "insert into users set 
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
		dateadded=CURDATE()";
	if (!mysql_query($sql))
		error("A database error occured in proccessing your submission.\\n".mysql_error());

	//Email the new password to the person
	$message = "Greetings,

Your personal account for the Charlotte-Russe/Rampage construction
Collaboration Network website has been created.


To login, proceed to the following URL:
	
		http://construction2.charlotte-russe.com


Your personal user ID and password are as follows:
	
		user id: $newid
		password: $newpass
		

For security reasons, your password was randomly generated.  

It is important that we maintain secure access to
the files. Do not share your password with anyone.


Thanks,
Site Administrator

[This was an automated message]
";

	mail($newemail,"Collaboration Network: Your account info", $message, "From:Collaboration Network <no-reply@charlotte-russe.com>");

	$newnotes = stripslashes($newnotes);
	$newnotes = nl2br($newnotes);

		//Get name of company from companies table using company_id as critera
		$co_id_result = mysql_query("select company_name from companies where company_id = $newcompany");
		$company_name = mysql_result($co_id_result,0,"company_name");
		$company_name = stripslashes($company_name);

	
	
	?>

<div id="content">
<h1>:: User added</h1>
<div class="databox">
<h2>Success!</h2>
	
	<p><img src="images/user.gif" alt="user" />&nbsp;User details for <b><?=$newname?></b> have been added to the <a href="<?=$_SERVER['PHP_SELF']?>?page=admin#users">user system</a>.</p>
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
	<td><?=$company_name?></td>
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

<p> The following  e-mail notification has been sent to <a href="mailto:<?=$newemail?>"><?=$newemail?></a>:</p>
<div class="filebox"><pre><?php print_r($message); ?></pre></div>
<p><a href="<?=$_SERVER['PHP_SELF']?>?page=admin#users">:: Return to admin console</a></p>

</div>
</div>

<?php
endif;
}
?>
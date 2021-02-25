<?php // new-project.php
if(isset($_GET['chain'])) {
	$chain = $_GET['chain'];
} else {
	$chain = NULL;
}
if(isset($_GET['status'])) {
	$status = $_GET['status'];
} else {
	$status = NULL;
}
if ($usergroup == 0)
{

if (!isset($submit)):
?>


<div id="content">
<h1>:: New project</h1>
<div class="databox">
<p>To create a new project all you need to start with is a project name or job number and the store chain. You can insert the other project details after the project has been created. </p>
<div class="litezone">
<form name="addproject" method="post" action="<?php echo "$PHP_SELF?page=new-project"; ?>" >
	<table>
	<tr>
	<td align="right" valign="top"><strong>Project Name:</strong></td>
	<td><input class="files" type="text" name="newsitename" size="60" maxlength="100" onKeyPress="return noenter()"></td>
	</tr>

	<tr>
	  <td align="right" valign="top"><strong>Job #: </strong></td>
	  <td><input name="newsitenum" type="text" class="files" id="newsitenum" onKeyPress="return noenter()" size="20" maxlength="20"></td>
    </tr>
    
	<tr>
	  <td align="right" valign="top"><strong>Store #: </strong></td>
	  <td><input name="newstore_number" type="text" class="files" id="newstore_number" onKeyPress="return noenter()" size="8" maxlength="8"></td>
    </tr>

   	<tr>
	  <td align="right" valign="top"><strong>District #: </strong></td>
	  <td><input name="newstore_district" type="text" class="files" id="newstore_district" onKeyPress="return noenter()" size="8" maxlength="8"></td>
    </tr>

	<tr>
	  <td align="right" valign="top"><strong>Region #: </strong></td>
	  <td><input name="newstore_region" type="text" class="files" id="newstore_region" onKeyPress="return noenter()" size="8" maxlength="8"></td>
    </tr>
        
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
	<td align="right" valign="top"><strong>Store Chain:</strong></td>
	<td>    <input name="newsitechain" id="chain_rad_1" type="radio" value="1" <?php if ($chain=="1") echo "checked"; ?>>
            <label for="chain_rad_1"><small><strong>Charlotte Russe</strong></small></label><br />
			<input name="newsitechain" id="chain_rad_2" type="radio" value="2" <?php if ($chain=="2") echo "checked"; ?>>
			<label for="chain_rad_2"><small><strong>Rampage</strong></small></label>
	</td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
	<td align="right" valign="top"><strong>Project Status:</strong></td>
	<td>    <input name="newsitestatus" id="status_rad_active" type="radio" value="active" <?php if ($status=="1") echo "checked"; ?>>
            <label for="status_rad_active"><small><strong>Active</strong></small></label><br />
			<input name="newsitestatus" id="status_rad_real_estate" type="radio" value="real_estate" <?php if ($status=="3") echo "checked"; ?>>
            <label for="status_rad_real_estate"><small><strong>Real Estate Only</strong></small></label><br />
			<input name="newsitestatus" id="status_rad_archived" type="radio" value="archive" <?php if ($status=="2") echo "checked"; ?>>
			<label for="status_rad_archived"><small><strong>Archived</strong></small></label>
	</td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
	<td></td>
	<td>
	<input type="hidden" name="vendors[]" value="123456790"></input>
	<input class="files" type="submit" name="submit" value="add">
	<input class="files" type="button" name="button" value="cancel" onClick="history.back()">
	</td>
	</tr>
	</table>
</form>
</div>
</div>
</div>

<?php
else:
// Process edit
		$usermsg = "<ul>";
	    dbConnect();
		$newvendors = serialize( $_POST['vendors']);
		if ($newsitechain == "") {
			$newsitechain = "1";
			$usermsg .= "<li><strong>Notice:</strong> Somehow no store chain was set, defaulting  to <em>Charlotte Russe</em></li>";
		}
		if ($newsitestatus == "") {
			$newsitestatus = "active";
			$usermsg .= "<li><strong>Notice:</strong> Somehow no project status was set, defaulting to <em>Active</em></li>";
		}
		
		if ($newsitechain == "1") {
			$type_of_store = "Fashion Valley";
			$mannequin_style = "CNL";
			}
		
		$sql =	"insert into projects set 
				sitename='$newsitename',
				sitenum='$newsitenum',
				store_number='$newstore_number',
				store_district='$newstore_district',
				store_region='$newstore_region',
				chain='$newsitechain',
				type_of_store='$type_of_store',
				mannequin_style='$mannequin_style',
				project_status='$newsitestatus',
				companyarray='$newvendors',
				datetouched=CURDATE(),
				dateadded=CURDATE()";
		

		
		if (!mysql_query($sql)) {
			error("A database error occured: " . mysql_error());
		} else 
		{
			$newentryid = mysql_insert_id();
			$usermsg .= "<li>Database entry #$newentryid created successfully!</li>";
			
			if ($newsitestatus == 'real_estate')
			{
				$rsql1 = "INSERT INTO realestate set project_id='{$newentryid}'";
				$rsql2 = "INSERT INTO re_centerinfo set project_id='{$newentryid}'";
				$rsql3 = "INSERT INTO re_strategy set project_id='{$newentryid}'";
				$rsql4 = "INSERT INTO re_storedesign set project_id='{$newentryid}'";
				$r1 = mysql_query($rsql1);
				if (!$r1) {} 
				else {
				$r2 = mysql_query($rsql2);
				if (!$r2) {}
				else {
				$r3 = mysql_query($rsql3);
				if (!$r3) {}
				else {
				$r4 = mysql_query($rsql4);
				}}}
			}
		}
		
			$maindir = "./filespace/". $newentryid;			
			if (!file_exists($maindir)) {
				mkdir($maindir,0777);
				$usermsg .= "<li>Folders created successfully!</li>";
			} else {
				error("Database error, please contact the administrator: ".mysql_error());
				exit;
			}
			$usermsg .= "</ul>";
	?>
<div id="content">
<h1>:: Project added successfully</h1>
<div class="databox">
<h2><?=$newsitename?></h2>
	<p><?=$usermsg?></p>
	<p>[<a href="<?=$_SERVER['PHP_SELF']?>?page=project&id=<?=$newentryid?>">View this Project Page</a> | <a href="<?=$_SERVER['PHP_SELF']?>?page=edit-project&id=<?=$newentryid?>">Edit the project details</a>]</p>
	<p>[<a href="<?=$_SERVER['PHP_SELF']?>">Return to home page</a>]</p>
</div>
</div>

<?php
endif;
} else {
echo "You do not have sufficient privledges to view this page";
exit;
}
?>
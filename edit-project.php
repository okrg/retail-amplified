<?php //edit-project.php

if (!isset($editok)):

dbConnect();
	$sql = "select * from projects where id=$id";
	$result = mysql_query($sql);
		if (!$result) {
			error("A databass error has occured.\\n".mysql_error());
		}
	while ($row = mysql_fetch_array($result)) {
		$id = $row["id"];
		$sitename = $row["sitename"];
		$sitenum = $row["sitenum"];
		$store_number = $row["store_number"];
		$store_district = $row["store_district"];
		$store_region = $row["store_region"];
		$sitechain = $row["chain"];
		$siteaddress = $row["siteaddress"];
		$siteaddress2 = $row["siteaddress2"];
		$sitecity = $row["sitecity"];
		$sitestate = $row["sitestate"];
		$sitezip = $row["sitezip"];
		$sitephone = $row["sitephone"];
		$sitefax = $row["sitefax"];
		$pm_key = $row["pm_key"];
		$project_status = $row["project_status"];
		$high_volume = $row["high_volume_store"];
		$potential_remodel = $row["potential_remodel_store"];
		$grand_display = $row["grand_display"];
		$companyarray = $row["companyarray"];
			$companyarray = unserialize($companyarray);
		$comments = $row["comments"];
			$comments = stripslashes($comments);
		$dateadded = $row["dateadded"];
			$dateadded = dateconvert($dateadded);
		$datetouched = $row["datetouched"];
			$datetouched = dateconvert($datetouched);
		}
		
?>

<div id="content">
	<h1>:: Edit project</h1>
	<div class="databox">
	<form name="editproject" method="post" action="<?php echo "$PHP_SELF?page=edit-project&id=$id"; ?>">
	  <table align="center" cellpadding="2" cellspacing="2" class="litezone">
        <tr>
          <td class="col1">Store #</td>
          <td class="edit_windows"><input name="newstore_number" type="text" class="files" onKeyPress="return noenter()" onFocus="this.className='ff'" onBlur="this.className='files'" value="<?=$store_number?>" size="8" maxlength="8">&nbsp;&nbsp;
          <strong>District #</strong>
          <input name="newstore_district" type="text" class="files" onKeyPress="return noenter()" onFocus="this.className='ff'" onBlur="this.className='files'" value="<?=$store_district?>" size="8" maxlength="8">&nbsp;&nbsp;
          <strong>Region #</strong>
          <input name="newstore_region" type="text" class="files" onKeyPress="return noenter()" onFocus="this.className='ff'" onBlur="this.className='files'" value="<?=$store_region?>" size="8" maxlength="8">&nbsp;          </td>
        </tr>
        <tr>
        	<td class="col1">Site Name</td>
        	<td class="edit_windows"><input name="newsitename" type="text" class="files" onKeyPress="return noenter()" onFocus="this.className='ff'" onBlur="this.className='files'" value="<?=$sitename?>" size="60" maxlength="100"></td>
        </tr>
        <tr>
          <td class="col1">Job #</td>
          <td class="edit_windows"><input name="newsitenum" type="text" class="files" onKeyPress="return noenter()" onFocus="this.className='ff'" onBlur="this.className='files'" value="<?=$sitenum?>" size="10" maxlength="10"></td>
        </tr>
        <tr>
          <td class="col1">Address</td>
          <td class="edit_windows"><input name="newsiteaddress" type="text" class="files"  onKeyPress="return noenter()" onFocus="this.className='ff'" onBlur="this.className='files'" value="<?=$siteaddress?>" size="50" maxlength="64"></td>
        </tr>
        <tr>
          <td class="col1">&nbsp;</td>
          <td class="edit_windows"><input name="newsiteaddress2" type="text" class="files"  onKeyPress="return noenter()" onFocus="this.className='ff'" onBlur="this.className='files'" value="<?=$siteaddress2?>" size="50" maxlength="64"></td>
        </tr>
        <tr>
          <td class="col1">City</td>
          <td class="edit_windows"><input name="newsitecity" type="text" class="files" onKeyPress="return noenter()" onFocus="this.className='ff'" onBlur="this.className='files'" value="<?=$sitecity?>" size="40" maxlength="64"></td>
        </tr>
        <tr>
          <td class="col1">State</td>
          <td class="edit_windows"><select onFocus="this.className='ff'" onBlur="this.className='files'" name="newsitestate" class="files">
            <option value="<?=$sitestate?>" selected>
            <?=$sitestate?>
            </option>
            <option value="AL">Alabama </option>
            <option value="AK">Alaska </option>
            <option value="AZ">Arizona </option>
            <option value="AR">Arkansas </option>
            <option value="CA">California </option>
            <option value="CO">Colorado </option>
            <option value="CT">Connecticut </option>
            <option value="DE">Delaware </option>
            <option value="DC">DC </option>
            <option value="FL">Florida </option>
            <option value="GA">Georgia </option>
            <option value="HI">Hawaii </option>
            <option value="ID">Idaho </option>
            <option value="IL">Illinois </option>
            <option value="IN">Indiana </option>
            <option value="IA">Iowa </option>
            <option value="KS">Kansas </option>
            <option value="KY">Kentucky </option>
            <option value="LA">Louisiana </option>
            <option value="ME">Maine </option>
            <option value="MD">Maryland </option>
            <option value="MA">Massachusetts </option>
            <option value="MI">Michigan </option>
            <option value="MN">Minnesota </option>
            <option value="MS">Mississippi </option>
            <option value="MO">Missouri </option>
            <option value="MT">Montana </option>
            <option value="NE">Nebraska </option>
            <option value="NV">Nevada </option>
            <option value="NH">New Hampshire </option>
            <option value="NJ">New Jersey </option>
            <option value="NM">New Mexico </option>
            <option value="NY">New York </option>
            <option value="NC">North Carolina </option>
            <option value="ND">North Dakota </option>
            <option value="OH">Ohio </option>
            <option value="OK">Oklahoma </option>
            <option value="OR">Oregon </option>
            <option value="PA">Pennsylvania </option>
			<option value="PR">Puerto Rico</option>
            <option value="RI">Rhode Island </option>
            <option value="SC">South Carolina </option>
            <option value="SD">South Dakota </option>
            <option value="TN">Tennessee </option>
            <option value="TX">Texas </option>
            <option value="UT">Utah </option>
            <option value="VT">Vermont </option>
            <option value="VA">Virginia </option>
            <option value="WA">Washington </option>
            <option value="WV">West Virginia </option>
            <option value="WI">Wisconsin </option>
            <option value="WY">Wyoming </option>
          </select></td>
        </tr>
        <tr>
          <td class="col1">Zip</td>
          <td class="edit_windows"><input name="newsitezip" type="text" class="files" onKeyPress="return noenter()" onFocus="this.className='ff'" onBlur="this.className='files'" value="<?=$sitezip?>" size="10" maxlength="10"></td>
        </tr>
        <tr>
          <td class="col1">Phone</td>
          <td class="edit_windows"><input name="newsitephone" type="text" class="files" onKeyPress="return noenter()" onFocus="this.className='ff'" onBlur="this.className='files'" value="<?=$sitephone?>" size="18" maxlength="18"></td>
        </tr>
        <tr>
          <td class="col1">Fax</td>
          <td class="edit_windows"><input name="newsitefax" type="text" class="files" onKeyPress="return noenter()" onFocus="this.className='ff'" onBlur="this.className='files'" value="<?=$sitefax?>" size="18" maxlength="18"></td>
        </tr>
        <tr>
          <td class="col1">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td class="col1">Comments/Notes</td>
          <td><textarea name="newcomments" cols="50" rows="10" class="files" onFocus="this.className='ff'" onBlur="this.className='files'" ><?=$comments?></textarea></td>
        </tr>
        <tr>
          <td class="col1">High Volume Store</td>
          <td class="edit_windows">
          <?php
          echo "<img src=\"images/clear.gif\" align=\"absmiddle\" /> <input type=\"checkbox\" value=\"high_volume_store\""; 
           if ($high_volume == 1) {
	          	echo " checked=\"checked\"";
          	}
          	echo " name=\"high_volume_store\"> Include in the list of high volume stores</input>\n";
          	?>		  </td>
        </tr>
        <tr>
          <td class="col1">Potential Remodel</td>
          <td class="edit_windows">
          <?php
          echo "<img src=\"images/config.gif\" align=\"absmiddle\" /> <input type=\"checkbox\" value=\"potential_remodel_store\""; 
           if ($potential_remodel == 1) {
	          	echo " checked=\"checked\"";
          	}
          	echo " name=\"potential_remodel_store\"> This store may be remodeled.</input>\n";
          	?>		  </td>
        </tr>
        <tr>
        <td class="col1">Master Vendor List</td>
        <td><p>[<a href="javascript:toggleBox('vendors',1);">Show Vendor List</a>]&nbsp;&nbsp;[<a href="javascript:toggleBox('vendors',0);">Hide Vendor List</a>]</p>
        <div id="vendors" style="font-size:12px;font-family:Arial, sans-serif;display:none;">
        <?php
        $result = mysql_query("select company_id, company_name, cat, color from companies where active = 1 and roles LIKE '%null%' order by company_name");
        $resqty = mysql_num_rows($result);
		$colqty = $resqty/3; //Creates three columms
		echo "<table id=\"vendorlist\"><tr><td>";
		$count = 0;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            echo "<span style=\"background-color:#{$row["color"]};\"><input title=\"{$row["cat"]}\" type=\"checkbox\" id=\"ck{$row["company_id"]}\" value=\"{$row["company_id"]}\"";
            if (in_array($row["company_id"], $companyarray)) {echo " checked=\"checked\"";}
            echo " name=\"vendors[]\"></span><label for=\"ck{$row["company_id"]}\">{$row["company_name"]}</label></input><br />";
			$count++;
			if ($count >= $colqty) { //check to see if the column qty has been exceeded, if so, then reset the column and count back to 0
				echo "</td><td>";
				$count = 0;
				}
        }
		echo "</td></tr></table>";
        echo "<input type=\"hidden\" name=\"vendors[]\" value=\"123456790\"></input>";
        mysql_free_result($result);
		echo "<div class=\"cleary\"></div>";
        echo "<p>[<a href=\"javascript:toggleBox('vendors',0);\">Hide Vendor List</a>]</p>";
        ?>
        </div>
		</td>
        </tr>
        <tr>

        <td><strong>Project Status</strong></td>
        <td>
        <input name="newsitestatus" id="status_rad_active" type="radio" value="active" <?php if ($project_status=="active") echo "checked"; ?>>
        <label for="status_rad_active"><strong>Active</strong></label><br />
		<input name="newsitestatus" id="status_rad_re_only" type="radio" value="real_estate" <?php if ($project_status=="real_estate") echo "checked"; ?>>
        <label for="status_rad_re_only"><strong>Real Estate Only</strong></label><br />
        <input name="newsitestatus" id="status_rad_archive" type="radio" value="archive" <?php if ($project_status=="archive") echo "checked"; ?>>
        <label for="status_rad_archive"><strong>Archive</strong></label>			</td>
        </tr>

<!--        <td><strong>Project Manager</strong></td>
        <td>
//		<?php 
//        $result = mysql_query("select id, fullname, email from users wher id = $pm_key");
//        if (!$result){error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error());}
//        $loc_list = "";
//        while ($row = mysql_fetch_array($result)){$loc_list .= "<option value=\"".$row['id']."\" />#".intval($row['store_number'])." ".$row['sitename']."</option>";}
//        ?>	
        </td>
        </tr>-->

		<tr>
          <td class="col1">Grand Display</td>
          <td>
          <?php
          echo "<input type=\"checkbox\" value=\"grand_display\""; 
           if ($grand_display == 1) {
	          	echo " checked=\"checked\"";
          	}
          	echo " name=\"grand_display\">Activate</input>\n";
          	?>
          	<br />
          	<small>Grand Display works by displaying the photos on a public viewable page and only allows visitors to view the pages, nothing else. The link is displayed in the project summary.</small>          </td>
		</tr>
        <tr>
          <td class="col1">&nbsp;</td>
          <td><p>
              <input name="editok" type="submit" class="files" value="edit">
            &nbsp;
              <input name="button" type="button" class="files" onClick="history.back()" value="cancel">
              <br />
</p>
            <p>              
            [<a href="<?=$_SERVER['PHP_SELF']?>?page=del-project&id=<?=$id?>">Delete this project</a>]            </p></td>
        </tr>
      </table>
	  <p><br />
</p>

	
    </form>
</div>
</div>


<?php

else:
// Process edit
	dbConnect();
//Format comments
	$newcomments = addslashes($newcomments);
	$newvendors = serialize( $_POST['vendors']);

//High volume store
	$newhigh_volume_store=($_POST['high_volume_store'])?"1":"0";

//Potential remodel
	$newpotential_remodel_store=($_POST['potential_remodel_store'])?"1":"0";

//Find out if Grand Display has been activated!
	$newgrand_display=($_POST['grand_display'])?"1":"0"; 

//add leading zeros to region
	$newstore_region = str_pad($newstore_region, 2, "0", STR_PAD_LEFT); 

//Set the sql statment..
	$sql =	"update projects set 
		sitename='$newsitename',
		sitenum='$newsitenum',
		store_number = '$newstore_number',
		store_district = '$newstore_district',
		store_region = '$newstore_region',
		chain = 1,
		high_volume_store = '$newhigh_volume_store',
		potential_remodel_store = '$newpotential_remodel_store',
		project_status = '$newsitestatus',
		grand_display = '$newgrand_display',
		siteaddress='$newsiteaddress',
		siteaddress2='$newsiteaddress2',
		sitecity='$newsitecity',
		sitestate='$newsitestate',
		sitezip='$newsitezip',
		sitephone='$newsitephone',
		sitefax='$newsitefax',
		companyarray = '$newvendors',
		comments='$newcomments',
		datetouched = CURDATE() where id = '$id'";

	if (!mysql_query($sql))
		error("A database error occured in proccessing your submission.\\n".mysql_error());				
?>
<div id="content">
	<h1>:: Project edited successfully</h1>
	<div class="databox">
	<p>This project's information has been successfully modified.</p>
	<p><a href="<?=$_SERVER['PHP_SELF']?>?page=project&id=<?=$id?>">:: Return to this project page</a></p>
	</div>
</div>

<?php
endif;
?>
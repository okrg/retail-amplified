	
	<table>
	<tr><td>
	<form name="reports_tab" method="post" action="placeholder">
	<select id="fields" class="multiselect" multiple="multiple" name="fields[]">       
    <?php	
    	$count = 0;
    	foreach ($colsmap as $displayname => $columnname)
    	{
    		if ($count == 0)
    		{
    			print "<option value=\"{$columnname}\" selected=\"selected\">{$displayname}</option>";
    		}
    		else
    		{
    			print "<option value=\"{$columnname}\">{$displayname}</option>";
    		} 
    		$count = $count+1;
    	} 
    	$url = "/index.php?page=re-reportbuilder&fltr=yes&user_id={$uid}&project_id={$id}";
    ?>
    </select>
    <p>&nbsp;</p>
    <table>
  	<tr><td><input name='addfilter' type='button' class='files' value='Add Filters' onClick="submitform(reports_tab,'<?=$url?>')">
  	&nbsp;<input name='submitnofilter' type='button' class='files' value='Submit' onClick="submitform(reports_tab,'/displayreport.php?filter=no');"></td></tr>
  	</table>
	</form>
	</td>
	<td class="pre-built-reports">
	<ul>
		<li><a href="/realestate_superview.php">Main report</a></li>
		<li><a href="/realestate_superview.php">Parrot report</a></li>
		<li><a href="/realestate_superview.php">Finish Report</a></li>
		<li><a href="/realestate_superview.php">Example name</a></li>
		<li><a href="/realestate_superview.php">Another example name with more words</a></li>
		<li><a href="/realestate_superview.php">Final example name</a></li>	
	</ul>
	</td>
	</tr></table>
	<p>&nbsp;</p>
	<h2>Saved Reports</h2>
	<form id='saved_reports_form' action='' method='POST'>
	<select name='saved_query_id'>
	<?php
		$qr = "SELECT * FROM re_reports WHERE user_id='{$uid}'";
		
		$rs = mysql_query($qr);
		if (!$rs)
		{
		}
		else
		{
			print "<option>Select report</option>";
			while ($row=mysql_fetch_assoc($rs))
			{
				print "<option value='{$row['id']}'>{$row['report_name']}</option>";
			}
		}
	?>
	</select>
	<input name='sr_submit' type='button' value='Submit' onClick="submitform(saved_reports_form,'/displayreport.php?saved_report_id=yes');"/>
	</form>

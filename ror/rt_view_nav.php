<?php //view_nav.php - Sets response options and allows user to decide what to do next 
//use octal for view ant notification options (e.g. 4+2+1=7) (2+1=3 corp and vendor) (4+1=5 corp and dm)
//0+1	= 1 corp
//0+2	= 2 vendor
//1+2	= 3 (corp and vendor)
//0+4	= 4 dm
//1+4	= 5 (corp and dm)
//2+4	= 6 (dm and vendor)
//1+2+4	= 7 (corp and vendor and dm)

?>
<tr>
<td colspan="5">
	<h2>Submit Response</h2>

	<table id="navigation">
		<tr><?php if($navset) { ?>
			<td><input type="button" value="&laquo; Previous" onclick="nav_rt(<?=$prev?>,'<?=$mode?>')" /></td>
			<td><input type="button" value="Next &raquo;" onclick="nav_rt(<?=$next?>,'<?=$mode?>')" /></td>		
			<td colspan="2"><input type="button" value="Submit then Next &raquo;" onclick="submit_next(<?=$next?>)" <?php if ($rt->status == "completed"){echo "class=\"dissed\" disabled=\"true\" ";}?> /></td>
			<?php } ?>
			<td><input type="submit" value="Submit" <?php if ($rt->status == "completed"){echo "class=\"dissed\" disabled=\"true\" ";}?> /></td>
			<td><input type="button" value="Close Window" onClick="javascript: window.close();" /></td>	
		</tr>
	</table>
<?php if ($usergroup<1) { 
//check if this person is on the watch list
$watcharray = explode(",",$rt->watchlist);
if (in_array($uid,$watcharray)) {
	echo "<p class=\"watchlist\"><img src=\"images/icon_delete.gif\" align=\"absmiddle\" /><input name=\"watchlist\" type=\"checkbox\" value=\"remove\" id=\"watchlist\" /><label for=\"watchlist\">Remove from watch list</label></p>";
} else {
	echo "<p class=\"watchlist\"><img src=\"images/icon_add.gif\" align=\"absmiddle\" /><input name=\"watchlist\" type=\"checkbox\" value=\"checked\" id=\"watchlist\" /><label for=\"watchlist\">Add to watch list</label></p>";
}
?>



	<table cellspacing="0" id="options">
		<tr class="header">
		<td class="col1">Role</td>
		<td class="col2">Notification</td>
		<td>View</td>
		</tr>
		<tr>
		<td class="col1">All</td>
		<td class="col2"><input name="notifyall" type="checkbox" onClick="toggleChecks('notify')" checked /></td>
		<td><input name="viewall" type="checkbox" onClick="toggleChecks('view')" checked /></td>
		</tr>
		<tr>
		<td class="col1">DM</td>
		<td class="col2"><input name="notify[]" type="checkbox" value="4" disabled /></td>
		<td><input name="view[]" type="checkbox" value="4" disabled /></td>
		</tr>
		<tr>
		<td class="col1">Vendor</td>
		<td class="col2"><input name="notify[]" type="checkbox" value="2" disabled /></td>
		<td><input name="view[]" type="checkbox" value="2" disabled /></td>
		</tr>
	</table> 


<?php } else { ?>
	<input name="notifyall" type="hidden" value="7" />
	<input name="viewall" type="hidden" value="7" />
<?php } ?>

</td>

</tr>

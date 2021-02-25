<div id="reports" style="display: none;">
<form name="reports" method="post" action="<?php echo "$PHP_SELF?page=admin-g2&action=report"; ?>">
<table cellpadding="10">
<tr>
<td style="vertical-align:top;">
<strong>Priority</strong><br />
<input name="priall" type="checkbox" value="" id="priall" onClick="toggleChecks('priority')" checked /><label for="priall">All priorties</label><br />
<input name="priority[]" type="checkbox" value="Not Urgent" id="radnot" disabled /><label for="radnot"><img src="/images/Not Urgent.gif" /> Not Urgent</label><br />
<input name="priority[]" type="checkbox" value="Minor" id="radmin" disabled /><label for="radmin"><img src="/images/Minor.gif" /> Minor</label><br />
<input name="priority[]" type="checkbox" value="Urgent" id="radurg" disabled /><label for="radurg"><img src="/images/Urgent.gif" /> Urgent</label><br />
<input name="priority[]" type="checkbox" value="Hazard" id="radhaz" disabled /><label for="radhaz"><img src="/images/Hazard.gif" /> Hazard<br /></label>
</td>
<td style="vertical-align:top;">
<strong>Type</strong><br />
<input name="typeall" type="checkbox" value="" id="typeall" onClick="toggleChecks('type')" checked /><label for="typeall">All types</label><br />
<input name="type[]" type="checkbox" value="Lighting" id="typelig" disabled /><label for="typelig">Lighting</label><br />
<input name="type[]" type="checkbox" value="Plumbing" id="typeplu" disabled /><label for="typeplu">Plumbing</label><br />
<input name="type[]" type="checkbox" value="Walls/Paint" id="typewal" disabled /><label for="typewal">Walls/Paint</label><br />
<input name="type[]" type="checkbox" value="Flooring" id="typeflo" disabled /><label for="typeflo">Flooring</label><br />
<input name="type[]" type="checkbox" value="Pest Control" id="typepend" disabled /><label for="typepend">Pest Control</label><br />
<input name="type[]" type="checkbox" value="Electrical" id="typeele" disabled /><label for="typeele">Electrical</label><br />
<input name="type[]" type="checkbox" value="HVAC" id="typehva" disabled /><label for="typehva">HVAC</label><br />
<input name="type[]" type="checkbox" value="Locks" id="typeloc" disabled /><label for="typeloc">Locks</label><br />
<input name="type[]" type="checkbox" value="Gate" id="typegat" disabled /><label for="typegat">Gate</label><br />
<input name="type[]" type="checkbox" value="Leak" id="typelek" disabled /><label for="typelek">Leak</label><br />
<input name="type[]" type="checkbox" value="Cashwrap" id="typecash" disabled /><label for="typecash">Cashwrap</label><br />
<input name="type[]" type="checkbox" value="Storefront Sign" id="typesign" disabled /><label for="typesign">Storefront Sign</label><br />
<input name="type[]" type="checkbox" value="Muzak/Sound System" id="typemuzak" disabled /><label for="typemuzak">Muzak/Sound System</label><br />
<input name="type[]" type="checkbox" value="Other" id="typeoth" disabled /><label for="typeoth">Other</label><br />
</td>
<td style="vertical-align:top;">
<strong>Status</strong><br />
<input name="statall" type="checkbox" value="" id="statall" onClick="toggleChecks('status')" checked /><label for="statall">All</label><br />
<input name="status[]" type="checkbox" value="Pending" id="statpend" disabled /><label for="statpend"><img src="/images/pending.gif" align="absmiddle" /> Pending</label><br />
<input name="status[]" type="checkbox" value="Answered" id="statans" disabled /><label for="statans"><img src="/images/answered.gif" align="absmiddle" /> Open</label><br />
<input name="status[]" type="checkbox" value="Clear" id="statclr" disabled /><label for="statclr"><img src="/images/clear.gif" align="absmiddle" /> Closed</label><br />
</td><td></td><td></td>
</tr>
<tr>
<td>Region:
<select name="store_region">
<option value="" >All</option>

<?php
$selectsql = "select distinct store_region from repair_orders order by store_region asc";
$result = mysql_query($selectsql);
	if (!$result) 	{
		error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error());
	}
	while ($row = mysql_fetch_object($result))
	{if ($row->store_region==0){continue;}else{print "<option value=\"$row->store_region\">$row->store_region</a>";}}

?>
</select>
</td>
<td>District:
<select name="store_district">
<option value="" selected>All</option>

<?php
$selectsql = "select distinct store_district from repair_orders order by store_district asc";
$result = mysql_query($selectsql);
	if (!$result) 	{
		error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error());
	}
	while ($row = mysql_fetch_object($result))
	{if ($row->store_district==0){continue;}else{print "<option value=\"$row->store_district\">$row->store_district</a>";}}

?>
</select>
</td>

<td>Store:
<select name="store_number">
<option value="" selected>All</option>

<?php
$selectsql = "select distinct store_number from repair_orders order by store_number asc";
$result = mysql_query($selectsql);
	if (!$result) 	{
		error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error());
	}
	while ($row = mysql_fetch_object($result))
	{if ($row->store_number==0){continue;}else{print "<option value=\"$row->store_number\">$row->store_number</a>";}}

?>
</select>
</td>
<td>Chain: 
<select name="chain">
<option value="" selected>All</option>
<option value="1">Charlotte Russe</option>
<option value="2">Rampage</option>
</select>
</td>
<td><input type="submit" class="bigshinybutton" value="Generate Report" /></td>
</tr>
</table>

</form>
</div>

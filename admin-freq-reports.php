<div id="reports" style="display: none;">
<form name="reports" method="post" action="<?php echo "$PHP_SELF?page=admin-freq&action=report"; ?>">
<table cellpadding="10">
<tr>
<td style="vertical-align:top;">
<strong>Priority</strong><br />
<input name="priall" type="checkbox" value="" id="priall" onClick="toggleChecks('priority')" checked /><label for="priall">All priorties</label><br />
<input name="priority[]" type="checkbox" value="Normal" id="radnot" disabled /><label for="radnot"><img src="/images/Not Urgent.gif" /> Normal</label><br />
<input name="priority[]" type="checkbox" value="High" id="radhaz" disabled /><label for="radhaz"><img src="/images/Hazard.gif" /> High<br /></label>
</td>
<td style="vertical-align:top;">
<strong>F/U</strong><br />
<input name="fuall" type="checkbox" value="" id="fuall" onClick="toggleChecks('followup')" checked /><label for="fuall">All</label><br />
<input name="followup[]" type="checkbox" value="1" id="fu" disabled /><label for="fu">Follow-Up Req'd</label><br />
<input name="followup[]" type="checkbox" value="0" id="nofu" disabled /><label for="nofu">No Follow-Up Req'd</label><br />
</td>
<td style="vertical-align:top;">
<strong>Status</strong><br />
<input name="statall" type="checkbox" value="" id="statall" onClick="toggleChecks('status')" checked /><label for="statall">All</label><br />
<input name="status[]" type="checkbox" value="Pending" id="statpend" disabled /><label for="statpend"><img src="/images/pending.gif" align="absmiddle" /> Pending</label><br />
<input name="status[]" type="checkbox" value="Answered" id="statans" disabled /><label for="statans"><img src="/images/answered.gif" align="absmiddle" /> Open</label><br />
<input name="status[]" type="checkbox" value="Clear" id="statclr" disabled /><label for="statclr"><img src="/images/clear.gif" align="absmiddle" /> Closed</label><br />
</td><td></td>
<td></td></tr>
<tr>
<td>Region:
<select name="store_region">
<option value="" >All</option>

<?php
$selectsql = "select distinct store_region from fixture_orders order by store_region asc";
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
$selectsql = "select distinct store_district from fixture_orders order by store_district asc";
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
$selectsql = "select distinct store_number from fixture_orders order by store_number asc";
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

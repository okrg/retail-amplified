
<table cellspacing="0" cellpadding="0" id="dashboard">
<tr>
<td class="col1">
	<ul>
		<li><a href="#" id="dlatest" class="current" onclick="javascript:ajax_do('rt_dashlatest.php?mode=ROR');return false;">Latest Responses</a></li>
    	<li><a href="#" id="dnew" class="none" onclick="javascript:ajax_do('rt_dashdata.php?show=new&mode=ROR');return false;">New</a></li>
		<li><a href="#" id="dopen" class="none" onclick="javascript:ajax_do('rt_dashdata.php?show=open&mode=ROR');return false;">Pending</a></li>
		<li><a href="#" id="dcompleted" class="none" onclick="javascript:ajax_do('rt_dashdata.php?show=completed&mode=ROR');return false;">Completed Recently</a></li>
	</ul>
</td>
<td class="col2">
	<div id="statdash">
<?  
	echo "<table id=\"datarows\" class=\"sortable\" cellspacing=\"0\" width=\"99%\" cellpadding=\"0\">";
	echo "<th>Tracking #</th>";
	echo "<th>Comment</th>";
	echo "<th>Posted By</th>";
	echo "<th>Date</th>";
	$sql = "SELECT rt_ror_responses.*,projects.*,rt_rors.tracking, rt_rors.id, rt_rors.loc_key, users.fullname, users.id,UNIX_TIMESTAMP(rt_ror_responses.creation) AS FORMATED_TIME ";
	//If the Vendor is logged in, the database will be filtered to show only requests where this vendor is assigned
	if ($usergroup == 3){$sql .= ",companies.company_id ";}
	$sql .= "FROM rt_ror_responses, projects, rt_rors, users ";  
	//If the Vendor is logged in, dynamically insert the companies table into the FROM declaration
	if ($usergroup == 3){$sql .= ",companies ";}
	$sql .= "WHERE rt_ror_responses.parent_key = rt_rors.id AND projects.id = rt_rors.loc_key AND users.id = rt_ror_responses.author_key ";
	//If the DM or RM is logged in, Filter the requests g1 is either going to be store_district or store_region depending on the login
	// and g0 is going to the be number , so if dm4 is logged in g1=store_district g0=4
	if ($usergroup == 2){$g = g2filter($uid);$sql .= "AND projects.$g[1] = $g[0] ";}
	//If the Vendor is logged in, the database will be filtered to show only requests where this vendor is assigned
	if ($usergroup == 3){$sql .= "AND rt_rors.vendor_key = companies.company_id AND companies.company_id = $usercompany ";}
	$sql .=" ORDER BY rt_ror_responses.creation DESC LIMIT 10";
	$results = mysql_query($sql);
	while ($row = mysql_fetch_object($results)) {
		$creation = "$row->FORMATED_TIME";
		echo "<tr>";
		echo "<td><a href=\"javascript:ViewPop('view.php?mode=$mode&id=$row->parent_key','view');\">$row->tracking</a></td>";
		echo "<td width=\"40%\">$row->body</td>";
		echo "<td>$row->fullname</td>";
		echo "<td>".date("n/j/y g:ia",$creation)."</td>";
		echo "</tr>";
	}
	echo "</table>";
?>
	</div>
</td>
<td class="col3">
	<div id="quickreport">
	<form action="report_engine.php?mode=ROR" name="quickreport" method="POST">
	<input type="hidden" name="report" value="quick" />
	<table width="98%" cellpadding="4" cellspacing="0" border="0">
	<tr>
		<td class="gray">
			<select name="filter">
				<option value="store_number">Store:</option>
				<option value="store_district">District:</option>
				<option value="store_region">Region:</option>
			</select>
		</td>
		<td class="gray">#<input type="text" size="4" name="query" /><br /></td>
		</tr><tr>
		<td></td>
		<td class="gray"><small>Enter a number and submit</small></td>
		</tr><tr>
		<td></td>
		<td><input type="submit" value="submit" class="macrobutton" /></td>
		</tr></table>
	</form>	
			
	</div>
</td>
</tr>
</table>
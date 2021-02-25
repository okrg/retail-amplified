<?php

if ($usergroup != 0) {
echo "You do not have sufficient privledges to view this page";
exit;
} else {
?>

<div id="content">
<h1>:: Companies</h1>
<div class="databox">
<div align="center"><img src="images/usergroup.gif" /></div>
<p>List of Companies the Database</p>
<p>[<a href="index.php?page=addcompany">Add a new company</a>]</p>
<p>[<a href="index.php?page=admin-cancelled">Go to List of Cancelled companies</a>]</p>
<?php

dbconnect();

//Create list of companies
	$sql = "select * from companies where active = 1 order by company_name";
	$result = mysql_query($sql);
	if (!result)
		error("A databass error has occured in processing your request.\\n". mysql_error());

		
//Format table
	print "<div>";
	print "<table class=\"filebox\" width=\"99%\">";
	print "<tr>";
	print "<th>Company name</th>";
	print "<th>Special Access Roles</th>";
	print "<th>Settings</th>";
	print "</tr>";


	while ($row = mysql_fetch_array($result)) {
		$id = $row["company_id"];
		$company = $row["company_name"];
			$company=stripslashes($company);
		$access = $row["roles"];
			$roles = explode(",",$access);
		
		print "<tr>";
		print "<td class=\"files\">$company</td>";
		print "<td class=\"files\">";
		//any company in the system can be assigned the ability to view and act on rors, freqs, or upload plans, or weekly reports
		//in order to be able to upload plans, theywill have to be set given the plans access, and also given vendor access for a projects
		//therefore only a superuser can assign these privledges once they create the project they willhave to assign a plans vendor.
		if (in_array("plans",$roles)){echo "Plans, ";}
		if (in_array("weekly",$roles)){echo "Weekly Reports, ";}
		if (in_array("freq",$roles)){echo "Fixture Requests, ";}
		if (in_array("g2",$roles)){echo "Repair Orders, ";}

		print "</td>";
		print "<td class=\"files\" align=\"center\"><a href=\"index.php?page=editcompany&id=$id\">edit</a></td>"; 
		print "</tr>";
		}
	print "</table>";
	print "</div>";
	print "<p>[<a href=\"index.php?page=addcompany\">Add a new company</a>]</p>";



	print "</div>";
	 } ?>
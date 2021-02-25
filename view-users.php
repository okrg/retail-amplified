<?php
if ($usercompany > 10) {
echo "You do not have sufficient privledges to view this page";
exit;
} else {
$thresh_hold = $_GET['group'];
switch ($thresh_hold) {
	case "0": $group_name = "Administrators"; break;
	case "1": $group_name = "Corp Staff"; break;
	case "2": $group_name = "Regional & District Mgrs"; break;
	case "3": $group_name = "Vendors"; break;
	case "cancelled": $group_name = "Cancelled Accounts";$cancelled = TRUE;break;
}
?>

<div id="content">
<div class="breadcrumbs">
<a href="/">Home</a> &raquo; 
<a href="<?=$PHP_SELF?>?page=admin">Admin Options</a> &raquo; 

<?=$group_name?></div>
<h1>:: <?=$group_name?></h1>
<div class="databox">
<div align="center"><img src="images/usergroup.gif" /></div>

<?php
	if (!$cancelled) {
		print "<p>[<a href=\"$PHP_SELF?page=adduser\">Add a new user</a>]</p>";
	}
	dbconnect();

//Create list of users not vendors
	if ($cancelled) {
		$sql = "select * from users where active = 0 order by company_id, fullname";
	} else {
		$sql = "select * from users where groupid = $thresh_hold and active = 1 order by company_id, fullname";
	}
	$result = mysql_query($sql);
	if (!$result)
		error("A databass error has occured in processing your request.\\n". mysql_error());

if(mysql_num_rows($result)>0){

	//Format table
	print "<div>";
	print "<table class=\"filebox\">";
	print "<tr>";
	print "<th>USER ID</th>";
	print "<th>Full name</th>";
	print "<th>Company</th>";
	print "<th>Title</th>";
	print "<th>E-mail</th>";
	print "<th>Date Added</th>";
	print "<th>Edit User</th>";
	print "</tr>";

	while ($row = mysql_fetch_array($result)) {
		$id = $row["id"];
		$username = $row["userid"];
		//$passwd = $row["pwd"];
		//don't show passwords
		$fullname = $row["fullname"];
		//$company = $row["company"];
		//this was the old way of storing company name
		$company_id = $row["company_id"];
		$title = $row["title"];
		$email = $row["email"];
		$group = $row["groupid"];
		$dateadded = $row["dateadded"];
		
		//Format e-mails for partial display to save screen space, but create as a sep var to maintain a proper mailto: link
		$shortemail=substr($email,0,18);
		$shortemail.="...";
				
		//Get name of company from companies table using company_id as critera
		$co_id_result = mysql_query("select company_name from companies where company_id = $company_id");
		if (mysql_num_rows($co_id_result)>0) {
			$company = mysql_result($co_id_result,0,"company_name");
			$company = stripslashes($company);
		} else {
			$company = "ERROR: No Company!";
		}
		print "<tr>";
		print "<td class=\"files\">$username</td>";
		//print "<td class=\"files\">$passwd</td>";
		//don't show passwords
		print "<td class=\"files\">$fullname</td>";
		print "<td class=\"files\"><small>$company</small></td>";
		print "<td class=\"files\">$title</td>";
		print "<td class=\"files\"><a href=\"mailto:$email\"><small>$shortemail</small></a></td>";
		print "<td class=\"files\" align=\"center\">$dateadded</td>";
		print "<td class=\"files\" align=\"center\"><a href=\"$PHP_SELF?page=edituser&id=$id\"><img src=\"/images/edit.gif\" /></a></td>"; 
		print "</tr>";
		}
	print "</table>";
	print "</div>";
} else {
	print "No accounts found in this group.";
}

	if (!$cancelled) {
		print "<p>[<a href=\"$PHP_SELF?page=adduser\">Add a new user</a>]</p>";
	}
	print "<p><a href=\"$PHP_SELF?page=admin-users\">&laquo; Back to User Groups</a></p>";
?>
</div>

</div>
<?php } ?>
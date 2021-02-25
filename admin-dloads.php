<?php //admin-dloads.php
?>
<div id="content">
<a name="downloads"></a>
<h1>:: File download log</h1>
<div class="databox">
<p>Select the project for which you want to view download logs.</p>
<?php

	dbConnect();
	$sql = "select id, sitename, chain from projects where project_status = 'active' order by sitename, chain";

	$result = mysql_query($sql);
	if (!$result)
		error("A databass error has occured in processing your request.\\n". mysql_error());
		

	echo "<form name=\"project_select\" method=\"post\" action=\"$PHP_SELF?page=admin-dloads\">";
	echo "<select name=\"project_id\" onChange=\"project_select.submit()\">";
	echo "<option value=\"0\">-Select a project-</option>";
	
//Cultivate data set
	while ($row = mysql_fetch_array($result))
//{{{ get id and site name from each project 
		{
	$id = $row['id'];
	$sitename = $row['sitename'];
	$chain = $row['chain'];
	if ($chain == 1) {
		$chain_name = "Charlotte Russe";
	} elseif ($chain == 2) {
		$chain_name = "Rampage";
	}
	echo "<option value=\"$id\">$sitename -- $chain_name</option>";
	} //}}}
	echo "</select>";
	echo "</form>";
	?>

	<br /><br />
<?php
if (isset($_POST['project_id'])) {
	


		$sitenamesql = mysql_query("select sitename from projects where id = {$_POST['project_id']}");
		$project = mysql_fetch_object($sitenamesql);

		$sql = "select folder from downloadlog where project_id = {$_POST['project_id']} group by folder";
		$result = mysql_query($sql);
		if (!$result)
			echo "A databass error has occured in processing your request.\\n". mysql_error();

	print "<h2>$project->sitename</h2>";
	print "<p>";	
		while ($row = mysql_fetch_array($result)) {
			$folder=$row["folder"];
			if ($folder == "")
				$folder = "Root Folder";
			print "<a href=\"#$folder\">$folder</a><br />";
		}
	print "</p>";
	
	
	print "<hr />";
	
		$sql = "select folder from downloadlog where project_id = {$_POST['project_id']} group by folder";
		$result = mysql_query($sql);
		if (!$result)
			echo "A databass error has occured in processing your request.\\n". mysql_error();

	print "<p>";	
		while ($row = mysql_fetch_array($result)) {
			$folder=$row["folder"];
			if ($folder == "")
				$folder = "Root Folder";
			
			print "<a name=\"$folder\"></a>";
			print "<h2>$folder</h2>";
			
			if ($folder == "Root Folder")
				$folder = "";
			
			$folder_sql = "select * from downloadlog where project_id = {$_POST['project_id']} and folder='".mysql_real_escape_string($folder)."' order by ts desc";
			$folder_result = mysql_query($folder_sql);
			if (!$folder_result)
				echo "A databass error:\\n". mysql_error();
			
	print "<table border=\"0\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" class=\"filebox\">";  	
	print "<tr>";                                                                                                        	
	print "<th class=\"files\">File</th>";
	print "<th class=\"files\">User</th>";
	print "<th class=\"files\" style=\"border-right:none;\">Timestamp</th>";
	print "</tr>";
				while ($folder_row = mysql_fetch_array($folder_result)) {
					$id = $folder_row["id"];            				
				  	$filename = $folder_row["filename"];			
				  	$user = $folder_row["user"];        
			 	 	$ts = $folder_row["ts"];            
//				  	$ts = revertTimeStamp($ts);
					$ts = date("m/d/Y g:i a", strtotime($ts));
				  	
				  	$co_sql = "select company_id from users where fullname = '".mysql_real_escape_string($user)."'";
					$co_result = mysql_query($co_sql);
					if (mysql_num_rows($co_result) == 0) {
						continue;
					} else {
					$co = mysql_result($co_result,0,"company_id");
					}
					
				  	$co_name_sql = "select company_name from companies where company_id = '".mysql_real_escape_string($co)."'";
					$co_name_result = mysql_query($co_name_sql);
					if (mysql_num_rows($co_name_result) == 0) {
					$co_name = "Not in DB";
					} else {
					$co_name = mysql_result($co_name_result,0,"company_name");
					}
				  	print "<td class=\"files\">$filename</td>";
				  	print "<td class=\"files\">$user @ $co_name</td>";
  					print "<td class=\"files\">$ts</td>";
  					print "</tr>";
  					
  					}
	
  	print "</table>";
  	print "<small><a href=\"#top\">Top</a></small>";
  	print "<br /><br /><br />";	
		}
	
	

}?>
</div>
</div>

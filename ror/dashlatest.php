<?
$main .= "<table>";

	$sql = "SELECT rt_ror_responses.*,projects.*,rt_rors.*, users.fullname, users.id FROM rt_ror_responses, projects, rt_rors, users 
			WHERE rt_ror_responses.parent_key = rt_rors.id AND projects.id = rt_rors.loc_key AND users.id = rt_ror_responses.author_key ORDER  BY rt_ror_responses.creation DESC LIMIT 10";
	
	$results = mysql_query($sql);
	while ($row = mysql_fetch_object($results)) {
		$main .="<tr>";
		$main .="<td>$row->tracking</td>";
		$main .="<td>$row->body</td>";
		$main .="<td>$row->fullname</td>";
		$main .="<td>$row->creation</td>";
		$main .="</tr>";
	}
		


	$main .= "</table>";
	echo $main;
    ?>
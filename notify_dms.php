<?php

//Fetch all dm email address
	$sql = "select email, company_id from users where groupid=2";
	$result = mysql_query($sql);	
	while($row = mysql_fetch_array($result)){ 
		if ($row["email"]=="")
			continue;
		$addresses .= $row["email"]; 
		$addresses .= ", ";
	}
	
$addresses .= "brakzilla@hotmail.com";
$notice_text = "Greetings, $addresses
$message

This was an automated message.
http:/"."/construction.charlotte-russe.com";

//mail($addresses, $project, $notice_text, "From:Collaboration Network <no-reply@charlotte-russe.com>");
mail("brakzilla@gmail.com", $project, $notice_text, "From:Collaboration Network <no-reply@charlotte-russe.com>");
?>
<?php
if ($usergroup != 0) {
echo "You do not have sufficient privledges to view this page";
exit;
} else {
?>

<div id="content">
<h1>:: User announcement</h1>
<div class="databox">

<?php
	dbConnect('planetg0_projects');
	$sql = "select * from blog where readers='users'";
	$result = mysql_query($sql);
	if (!result)
		error("A databass error has occured in processing your request.\\n". mysql_error());
	while ($row = mysql_fetch_array($result))
		{
		$id = $row["id"];
		$ts = $row["ts"];
			$ts = revertTimestamp($ts);
		$subject = $row["subject"];
		$body= $row["body"];
			$body = stripslashes($body);
			$body = nl2br($body);
		$author = $row["author"];
		}
?>
<table class="filebox" width="100%">
<tr>
<td width="120" align="right"><strong>Author:</strong></td>
<td><?=$author?></td>
</tr><tr>
<td align="right"><strong>Header:</strong></td>
<td><?=$subject?></td>
</tr><tr>
<td align="right"><strong>Body:</strong></td>
<td><?=$body?></td>
</tr><tr>
<td align="right"><strong>Date posted:</strong></td>
<td><?=$ts?></td>
</tr></table>
<p>[<a href="<?=$_SERVER['PHP_SELF']?>?page=edit-announce&readers=users">Edit user announcement</a>]</p>
</div>

<h1>:: Vendor announcement</h1>
<div class="databox">
<?php
	dbConnect('planetg0_projects');
	$sql = "select * from blog where readers='vendors'";
	$result = mysql_query($sql);
	if (!result)
		error("A databass error has occured in processing your request.\\n". mysql_error());
	while ($row = mysql_fetch_array($result))
		{
		$id = $row["id"];
		$ts = $row["ts"];
			$ts = revertTimestamp($ts);
		$subject = $row["subject"];
		$body= $row["body"];
			$body = stripslashes($body);
			$body = nl2br($body);
		$author = $row["author"];
		}
?>
<table class="filebox" width="100%">
<tr>
<td width="120" align="right"><strong>Author:</strong></td>
<td><?=$author?></td>
</tr><tr>
<td align="right"><strong>Header:</strong></td>
<td><?=$subject?></td>
</tr><tr>
<td align="right"><strong>Body:</strong></td>
<td><?=$body?></td>
</tr><tr>
<td align="right"><strong>Date posted:</strong></td>
<td><?=$ts?></td>
</tr></table>
<p>[<a href="<?=$_SERVER['PHP_SELF']?>?page=edit-announce&readers=vendors">Edit vendor announcement</a>]</p>
</div>

</div>
<?php } ?>
<?php
if (!isset($delete)):
?>



<div id="content">
<h1>:: Delete project?</h1>
<div class="databox">
<form name="form1" method="post" action="<?php echo "$PHP_SELF?page=del-project&id=$id"; ?>">
   <h3><img src="images/delete.gif" />Warning!</h3>
   
          <p>ALL DATABASE ENTRIES AND FILES FOR THIS PROJECT WILL BE LOST PERMANENTLY!</p>
          <p>Are you sure that you want to delete this project from the website?</p>
<input class="files" type="submit" name="delete" value="yes, delete">
<input class="files" type="button" name="button" value="no, cancel" onClick="history.back()">
</form>

</div>
</div>

<?php
else:
    // Process project delete
   dbConnect();

	$sql = "delete from projects where id = $id";
	if (!mysql_query($sql))
	error("A database error occured in proccessing your submission.\\n".mysql_error());

	$sql = "delete from filelog where project = $id";
	if (!mysql_query($sql))
	error("A database error occured in proccessing your submission.\\n".mysql_error());
	
	$sql = "delete from distrolog where project = $id";
	if (!mysql_query($sql))
	error("A database error occured in proccessing your submission.\\n".mysql_error());
	
	$sql = "delete from gallerylog where project = $id";
	if (!mysql_query($sql))
	error("A database error occured in proccessing your submission.\\n".mysql_error());


	$dirname = "./filespace/$id";

	if (file_exists($dirname))
	{
	deldir($dirname);
	}
?>
<div id="content">
<h1>:: Delete project</h1>

<div class="databox">
<p>The entire project has been deleted from the website.</p>
<p>[<a href="<?=$_SERVER['PHP_SELF']?>">Return to home page</a>]</p>
</div>
</div>
<?php endif; ?>
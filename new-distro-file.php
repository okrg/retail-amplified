<?php // new-distro-file.php
if ($usergroup == 0)
{


if (!isset($submit)):
?>

<div id="content">
<h1>:: Add more files to distro</h1>
<div class="databox">
<p>Please provide the information for this file distribution and assign files to it. You can add more files once the initial distribution has been set.  You will also be able to remove certain files from the distribution.</p>
<form name="addfolder" method="post" action="<?=$_SERVER['PHP_SELF']?>?page=new-distro-file&id=<?=$id?>&distro=<?=$distro?>" enctype="multipart/form-data" >

	<div class="field">Files to upload:</div>
	<div class="data"><input name="userfile[]" type="file"></div>
	<div class="data"><input name="userfile[]" type="file"></div>
	<div class="data"><input name="userfile[]" type="file"></div>
	<div class="data"><input name="userfile[]" type="file"></div>
	<div class="data"><input name="userfile[]" type="file"></div>
	<div class="data"><input name="userfile[]" type="file"></div>
	<div class="data"><input name="userfile[]" type="file"></div>
	<div class="data"><input name="userfile[]" type="file"></div>
	<div class="data"><input name="userfile[]" type="file"></div>
	<div class="data"><input name="userfile[]" type="file"></div>
	<div class="data"><input name="userfile[]" type="file"></div>


<input type="submit" name="submit" value="add">
<input type="button" name="button" value="cancel" onClick="history.back()">

</form>
	</div>



</div>

<?php
else:
// Process edit
echo "<div id=\"content\">";
	
$maindir = "./filespace/$id/$distro";
$uploaddir = $maindir . "/";

for($i=0; $i<count($HTTP_POST_FILES['userfile']['tmp_name']); $i++)
{ 
	$tempname = $HTTP_POST_FILES['userfile']['tmp_name'][$i]; 
	$filename = $HTTP_POST_FILES['userfile']['name'][$i];
	if ($tempname != "")
	{
	if (file_exists($uploaddir.$filename)){
		error("Warning file already exists: ".$filename);
		exit;
	}
	}
}


for($i=0; $i<count($HTTP_POST_FILES['userfile']['tmp_name']); $i++)
{ 
	$tempname = $HTTP_POST_FILES['userfile']['tmp_name'][$i]; 
	$filename = $HTTP_POST_FILES['userfile']['name'][$i];

	if ($tempname != "")
	{
		if (move_uploaded_file($tempname, $uploaddir.$filename))
		{ 
		echo "<h1>:: Files added</h1>";
		echo "<div class=\"databox\">";
		print "<p>New files have been added to distribution</p>";
		} else {
		echo "<h1>:: Error!</h1>";
		echo "<div class=\"databox\">";
	   	print "Possible file upload attack!  Here's some debugging info to send to the admin for review:\n";
		print "<pre>";
		print_r($HTTP_POST_FILES);
		print "</pre>";
		}
	}

}
?>
<a href="index2.php?page=project&id=<?=$id?>&folder=<?=$distro?>#files">:: Return to this project distribution</a><br /><br />
</div>
</div>

<?php
endif;

} else {
echo "You do not have sufficient privledges to view this page";
exit;
}
?>
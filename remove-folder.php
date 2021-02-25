<?php //remove-folder.php

dbConnect('planetg0_projects');
$sql = "delete from distrolog where project='$id' and distroname='$folder'";

if (!mysql_query($sql))
	error("A database error occured: " . mysql_error());

$maindir = "./filespace/$id/$folder";

if(rmdir($maindir)) {
	$usermsg = "<p>Empty folder: <strong>$folder</strong> has been removed successfully.</p>";
} else {
	$usermsg = "<p>Error: Unable to remove folder <strong>$folder</strong></p>";
}
?>
<div id="content">
<h1>::Empty Folder Deleted</h1>
<div class="databox">
<?=$usermsg;?>
<p><a href="<?php echo "index2.php?page=project&id=$id"; ?>">::Return to project</a></p>
</div>
</div>
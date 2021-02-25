<?php //del-distro-file.php
include ("include/access.php");
if ($usergroup == 0)
{
	
if (!isset($submit)):
$filename = explode("/", $file);
$filename = $filename[3];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<title>Charlotte-Russe/Rampage Collaboration Network</title>
<style type="text/css" media="all">@import "def.css";</style>
<script type="text/javascript">
function remote(url){
window.opener.location=url
window.close()
}
</script>
</head>

<body>
<h1>:: Delete file</h1>
<div class="databox">
<form name="remfile" method="post" action="<?php echo($PHP_SELF)."?id=$id&file=$file"; ?>" >
<h3><img src="images/delete.gif" align="absmiddle" />Warning!</h3>
<p>You are about to permanently remove a file from the database</p>
<p>Are you sure you want to remove the following file:<br /><b><?php echo($filename); ?></b></p>

<input type="submit" name="submit" value="OK">
<input type="button" name="button" value="cancel" onClick="window.close()">

</form>
	</div>



</div>

</body>
</html>



<?php
else:
// Process edit
	$filename = explode("/", $file);
	$filename = $filename[3];

	if(unlink($file))
	{
	$usermsg = "<p>File has been removed successfully:<br /><strong>$filename</strong></p>";
	} else {
	$usermsg = "<p>Error....Unable to delete...</p>";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<title>Charlotte-Russe/Rampage Collaboration Network</title>
<style type="text/css" media="all">@import "def.css";</style>
<script type="text/javascript">
function remote(url){
window.opener.location.reload()
window.close()
}
</script>
</head>

<body>

<h1>:: File delete</h1>
<div class="databox">
<?=$usermsg;?>
<form>
<input type="button" value="click here to return" onClick="remote()">
</form>
</div>
</div>
</body>
</html>

<?php
endif;

} else {
	echo "You do not have sufficient privledges to view this page.";
	exit;
}
?>
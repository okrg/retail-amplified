<?php //viewcode.php
$mode = $_GET['mode'];

if (isset($_POST['newstarter'])) {
	$navset=TRUE;
	$key = $_POST['newstarter'];
	$id = $_POST['workset'][$key];
} elseif (isset($_POST['starter'])) {
	$navset=TRUE;
	$id = $_POST['starter'];
} elseif (isset($_GET['id'])) {
	$navset=FALSE;
	$id =$_GET['id'];
} elseif (isset($_POST['parent'])) {
	$navset=FALSE;
	$id = $_POST['parent'];
} else {
	error("No way to get started!");	
}

//determine OPK if this is freq mode
if ($mode == "FREQ") {
$osql = "select opk from rt_freqs where id = $id limit 1";
$ores = mysql_query($osql);
$opk = mysql_result($ores,0);
}


if (isset($_POST['workset'])) {
	//find current id in work set
	$key = array_search($id,$workset);
	$total = sizeof($workset); //total records
	//assign
	$prev = $key-1;
	$next = $key+1;
	$next_tag = $next;
	//check for limit and subtract one
	if ($next >= $total){$next = $next-1;}	
	if ($prev < 0){$prev = 0;}
}
?>
<form name="editor" method="POST" action="edit.php?mode=<?=$mode?>" id="editor" enctype="multipart/form-data">

<?php

//create array for workset so that you can pass this on to next or prev
if (isset($_POST['workset'])) {
	foreach ($_POST['workset'] as $value) {
		echo "<input type=\"hidden\" name=\"workset[]\" value=\"$value\" />";
	}
}
?>
<input type="hidden" name="parent" value="<?=$id?>" />
<input type="hidden" name="opk" value="<?=$opk?>" />
<?php
if($navset){
	echo "<input type=\"hidden\" name=\"newstarter\" value=\"$key\" />";
	echo "<span id=\"nav\">";
	echo "$next_tag of $total ";
	echo "<span class=\"navlinks\"><a href=\"#\" onclick=\"nav_rt($prev,'$mode');\">&laquo;</a> ";
	echo "<a href=\"#\" onclick=\"nav_rt($next,'$mode');\">&raquo;</a></span>";
	echo "</span>";
}
include("rt_view_strip.php");
echo "<table cellspacing=\"0\" cellpadding=\"0\" id=\"response\">";
echo "<tr><td colspan=\"5\"><h2>Request Details</h2></td></tr>";
include("rt_view_item.php");
include("rt_view_responses.php");
include("rt_view_editor.php");
include("rt_view_nav.php");
echo "</table>";
?>
</form>

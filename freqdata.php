<?php
include("include/db.php");
include("include/common.php");
$id = $_GET['id'];
$container_id = "box$id";
//$container_id .= $id;
	
dbConnect();
$sql = "select * from fixture_key where id=$id";
$result = mysql_query($sql);
$row = mysql_fetch_object($result);
$box_report .= "<span style=\"float:right;\"><a href=\"#\" title=\"Close\"  onClick=\"javascript:toggleBox('box$id',0);return false;\"><img src=\"images/close.gif\" border=\"0\" /></a></span>"; 	
if($row->exclude == 1) {
	$box_report .= "<br />";
}elseif ($row->dds == 0) {
	$box_report .= "<br /><br /><a href=\"index.php?page=freq-cart&action=add&pid=".$_GET['pid']."&id=".$row->id."&qty=1\"><img src=\"images/fixtures/".$row->name.".jpg\" border=\"0\" />";		
	$box_report .= "<div style=\"clear:both;\"><small>[Request Item]</small></a></div>";
}elseif($row->dds == 1) {
	$box_report .= "<br /><br /><img src=\"images/fixtures/".$row->name.".jpg\" border=\"0\" />";		
	$box_report .= "<div style=\"clear:both;\"><small>Not Available Online. Please order through DDS</small><br /></div>";
}

//Escape characters
$box_report = str_replace("'", "\'", $box_report);
$box_report = str_replace('"', "'+String.fromCharCode(34)+'", $box_report);
$box_report = str_replace ("\r\n", '\n', $box_report);
$box_report = str_replace ("\r", '\n', $box_report);
$box_report = str_replace ("\n", '\n', $box_report);
?>

div = document.getElementById('<?php echo $container_id; ?>');
div.innerHTML = '<?php echo $box_report; ?>';

<?php
error_reporting(E_ALL ^ E_NOTICE);
include(realpath(dirname(dirname(__FILE__)))."/include/access.php");
include(realpath(dirname(dirname(__FILE__)))."/include/rt.php");

if($_GET['d'] == "store_region")$domain_label="Region";
if($_GET['d'] == "store_district")$domain_label="Distict";

//if ($usergroup == 2) {$budget = 60000;} else {$budget = 100000;}
//$spend = get_total("rm_ok",$_GET['d'],$_GET['x']);
//$balance = $budget - $spend;

$region_id = $_GET['x'];
$budget = mysql_do("select budget from rt_freq_budgets where region = $region_id");
$processed = mysql_do("select processed from rt_freq_budgets where region = $region_id");
$balance = $budget - $processed;
$spend = get_total("processed",$_GET['d'],$_GET['x']);
$fiscal_balance = $balance - $spend;

$html .= "<h4>Totals for $domain_label: ".intval($_GET['x'])."</h4>";
if (($usergroup <2)or($usercompany == 6)) {
	$html .= "<a href=\"javascript:loadstats(".intval($_GET['x']).");\">Reload</a>";
}
$html .= "<table class=\"store\" style=\"font-weight:bold;\" >";

//if (($usergroup <2)or($usercompany == 6)) {
//$html .= "
//	<tr class=\"summary\"><td>Fiscal 09 Budget: </td><td class=\"total\"><span id=\"budget_row\">".moneyFormat($budget)."</span></td></tr>
//	<tr class=\"summary\"><td>Orders Processed to Date: </td><td class=\"total\"><span id=\"processed_row\">".moneyFormat($processed)."</span></td></tr>
//	<tr class=\"summary\"><td>Balance: </strong></td><td class=\"total\">".moneyFormat($balance)."</td></tr>
//";
//}

//if($region_id == 1){
//	$html .= "<tr class=\"summary\"><td>$season_nice Fixture Budget: </td><td class=\"total\">".moneyFormat(7500)."</td></tr>";
//} else {
	$html .= "<tr class=\"summary\"><td>$season_nice Fixture Budget: </td><td class=\"total\">".moneyFormat(15000)."</td></tr>";
//}
	
$html .= "<tr class=\"summary\"><td>$season_nice Orders Pending: </td><td class=\"total\">".moneyFormat(get_total("waiting",$_GET['d'],$_GET['x']))."</td></tr>
	<tr class=\"summary\"><td>$season_nice RM Approved: </td><td class=\"total\">".moneyFormat(get_total("rm_ok",$_GET['d'],$_GET['x']))."</td></tr>
	<tr class=\"summary\"><td>$season_nice VP Approved: </td><td class=\"total\">".moneyFormat(get_total("vp_ok",$_GET['d'],$_GET['x']))."</td></tr>
	<tr class=\"summary\"><td>$season_nice Approved Totals: </td><td class=\"total\">".moneyFormat(get_total("processed",$_GET['d'],$_GET['x']))."</td></tr>";
	
//if($region_id == 1){
//$html .= "<tr class=\"summary\"><td>$season_nice Fixture Budget Balance: </td><td class=\"total\">".moneyFormat(7500-get_total("processed",$_GET['d'],$_GET['x']))."</td></tr>";
//} else {
$html .= "<tr class=\"summary\"><td>$season_nice Fixture Budget Balance: </td><td class=\"total\">".moneyFormat(15000-get_total("processed",$_GET['d'],$_GET['x']))."</td></tr>";
//}

if ($usercompany == 6) {
	$html .= "<tr class=\"summary\"><td>Mannequin Total: </td><td class=\"total\">".moneyFormat(get_total("m",$_GET['d'],$_GET['x']))."</td></tr>";
	}
//if (($usergroup <2)or($usercompany == 6)) {
//$html .= "<tr class=\"summary\"><td><strong>Fiscal 09 Balance: </strong></td><td class=\"total\"><strong>".moneyFormat($fiscal_balance)."</strong></td></tr>";
//}
$html .= "</table>";

//Escape characters
$html = str_replace("'", "\'", $html);
$html = str_replace('"', "'+String.fromCharCode(34)+'", $html);
$html = str_replace ("\r\n", '\n', $html);
$html = str_replace ("\r", '\n', $html);
$html = str_replace ("\n", '\n', $html);
?>

div = document.getElementById('statbox');
div.innerHTML = '<?php echo $html; ?>';
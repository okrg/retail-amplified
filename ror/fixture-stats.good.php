<?php
error_reporting(E_ALL ^ E_NOTICE);
include("../include/access.php");
include("../include/rt.php");




if($_GET['d'] == "store_region")$html.= "<h4>Totals for Store Region: ".intval($_GET['x'])."</h4>";
if($_GET['d'] == "store_district")$html.= "<h4>Totals for Store District: ".intval($_GET['x'])."</h4>";
$html .= "<table class=\"store\">
<tr class=\"summary\"><td>Region ".intval($_GET['x'])." Pending Total: </td><td class=\"total\">".moneyFormat(get_total("waiting",$_GET['d'],$_GET['x']))."</td></tr>
<tr class=\"summary\"><td>Region ".intval($_GET['x'])." RM Approved Total: </td><td class=\"total\">".moneyFormat(get_total("rm_ok",$_GET['d'],$_GET['x']))."</td></tr>";

if ($usercompany == 6) {
	$html .= "<tr class=\"summary\"><td>Mannequin Total: </td><td class=\"total\">".get_total("m",$_GET['d'],$_GET['x'])."</td></tr>";
	}

if ($usergroup == 2) {$budget = 60000;} else {$budget = 100000;}
$spend = get_total("rm_ok",$_GET['d'],$_GET['x']);

$balance = $budget - $spend;

$html .= "
<tr class=\"summary\"><td><strong>Region ".intval($_GET['x'])." Budget: </strong></td><td class=\"total\"><strong>".moneyFormat($budget)."</strong></td></tr>
<tr class=\"summary\"><td><strong>Region ".intval($_GET['x'])." Balance: </strong></td><td class=\"total\"><strong>".moneyFormat($balance)."</strong></td></tr>
</table>";
//Escape characters
$html = str_replace("'", "\'", $html);
$html = str_replace('"', "'+String.fromCharCode(34)+'", $html);
$html = str_replace ("\r\n", '\n', $html);
$html = str_replace ("\r", '\n', $html);
$html = str_replace ("\n", '\n', $html);
 ?>
div = document.getElementById('statbox');
div.innerHTML = '<?php echo $html; ?>';
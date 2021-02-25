<?php
	putenv("TZ=America/Los_Angeles");
	$season_title = "June 2019";
//	$ship = mktime(0,0,0,11,1,2007);
//	$vpapp = mktime(0,0,0,10,29,2007);	
//	$appstart = mktime(0,0,0, 10,22,2007);
//	$append = mktime(0,0,0, 10,26,2007);
//	$today = mktime();

function dateDiff($dformat, $endDate, $beginDate)
    {
    $date_parts1=explode($dformat, $beginDate);
    $date_parts2=explode($dformat, $endDate);
    $start_date=gregoriantojd($date_parts1[0], $date_parts1[1], $date_parts1[2]);
    $end_date=gregoriantojd($date_parts2[0], $date_parts2[1], $date_parts2[2]);
    return $end_date - $start_date;
    }

  $today=date("m/d/Y");
  //$today=date("m/d/Y", strtotime("06/19/2019"));
  $ship="07/19/2019";
	$appstart="07/8/2019";
	$append="07/9/2019";
	$vpapp="07/12/2019";
	$days_to_ship = dateDiff("/", $ship, $today);
	$days_to_appstart = dateDiff("/", $appstart, $today);
	$days_to_vpapp = dateDiff("/", $vpapp, $today);
	$delivery = $days_to_ship+2;
	$delivery_end = $delivery+21;

$html.="<div style=\"font-size:10pt;font-family:sans-serif;\">";
$html.="<blockquote>$days_to_ship Days Till $season_title Fixture Ship Date</blockquote>";
$html.= "<blockquote><table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" style=\"width:99%;border-top:1px #ccc solid;border-left:1px #ccc solid;font-size:11px;clear:both;margin:5px 0;\">";
$html.= "<tr>";
for($counter=0;$counter<=$delivery_end;$counter++){
	$html.= "<td style=\"vertical-align:top;border-bottom:1px #ccc solid;border-right:1px #ccc solid;background:#fff url(images/background_form_element.gif) repeat-x top;padding:0 5px;text-align:center;\">";
	$html.= date("D",mktime(0, 0, 0, date("m")  , date("d")+$counter, date("Y")));
	$html.= "<br />";
	$html.= date("M",mktime(0, 0, 0, date("m")  , date("d")+$counter, date("Y")));
	$html.= "<br />";
	$html.= "<b>".date("j",mktime(0, 0, 0, date("m")  , date("d")+$counter, date("Y")))."</b>";
	switch($counter){
	case $days_to_appstart:
	case $days_to_appstart+1:
	//case $days_to_appstart+2:
	//case $days_to_appstart+3:
	//case $days_to_appstart+4:
			$html.= "<div style=\"background:#FC0;\">&nbsp;</div>";break;
	}
	if ($counter==$days_to_vpapp) {$html.= "<div style=\"background:#F93;\">&nbsp;</div>";}
	if ($counter==$days_to_ship) {$html.= "<div style=\"background:#F30;\">&nbsp;</div>";}
	if ($counter>=$delivery) {$html.= "<div style=\"background:#bbb;\">&nbsp;</div>";}
	$html.= "</td>";
	if (($counter+1) % 7 == 0) $html.="</tr><tr>";
}
$html.= "</tr>";
$html.= "</table></blockquote>";
$html.="<blockquote>Dates: <span style=\"color:#fff;\">";
$html.="<div style=\"border:1px #999 solid;background:#FC0;padding:2px;margin:2px;\">RM Approval</div>";
$html.="<div style=\"border:1px #999 solid;background:#F93;padding:2px;margin:2px;\">VP Approval</div>";
$html.="<div style=\"border:1px #999 solid;background:#F30;padding:2px;margin:2px;\">Ship Date</div>";
$html.="<div style=\"border:1px #999 solid;background:#bbb;padding:2px;margin:2px;\">Delivery</div>";
$html.="</span></blockquote>";
$html.="</div>";
$html.="<br style=\"clear:both;\" />";
echo $html;
?>

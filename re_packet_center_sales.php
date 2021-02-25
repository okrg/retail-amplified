<?php ob_start(); ?>
<?php include('re_packet_data.php'); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">  
  <style type="text/css">
  body{
    margin:0;
    font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    font-size: 11px;
    font-weight: 400;
    line-height: 1em;
    color: #212529;
    text-align: left;}
  table {
    width: 100%;
    vertical-align: top
  }
  td {
    margin: 0;
    padding: 2px;
    vertical-align: top;
    text-transform: capitalize;
  }
  td.money {
    text-align: right;
  }
  tr.underline td {
    border-bottom:1px #000 solid;
  }
  h1,h2 {
    
    line-height:1.5em;
    font-weight: bold;
    margin: 0;
    background:#ccc;
    padding: 2px;
  }
  h1 {font-size: 1.2em;}
  h2 {font-size: 1.1em;}
    
  .section {
    border: 1px #000 solid;
    margin-bottom: 1em;
  }
  .section tr:nth-child(even) {
    background:#eee;
  }
  .green { background:#28a745; text-align: center;}
  .yellow { background:#ffc107; text-align: center;}
  </style>
</head>
<body>

<h1>#<?=$project['store_number'];?> <?=$project['sitename'];?>, <?=$project['sitestate'];?></h1>
<h2>Center Sales</h2>

<div class="section">
  <table>
    <tr>
      <th>TTM Thru</td>
      <td colspan="4"><?=dateFormat($centerinfo['ttm_date']);?></td>
    </tr>
    <tr>
      <th>Center Sales PSF</td>
      <td colspan="4">$<?=number_format($centerinfo['mall_sales_psf'],2);?></td>
    </tr>
    <tr>      
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <th>Tenant</th>
      <th>SF</th>
      <th>Sales Per Sq. Ft.</th>
      <th>Volume</th>
      <th>Comments</th>
    </tr>
    <tr>
      <td><?=$centerinfo['ten_1'];?></td>
      <td><?=number_format($centerinfo['ten_1_sf']);?></td>
      <td>$<?=number_format($centerinfo['ten_1_sales_psf']);?></td>
      <td>$<?=number_format($centerinfo['ten_1_volume']);?></td>
      <td><?=$centerinfo['ten_1_comments'];?></td>
    </tr>
    <tr>
      <td><?=$centerinfo['ten_2'];?></td>
      <td><?=number_format($centerinfo['ten_2_sf']);?></td>
      <td>$<?=number_format($centerinfo['ten_2_sales_psf']);?></td>
      <td>$<?=number_format($centerinfo['ten_2_volume']);?></td>
      <td><?=$centerinfo['ten_2_comments'];?></td>
    </tr>
    <tr>
      <td><?=$centerinfo['ten_3'];?></td>
      <td><?=number_format($centerinfo['ten_3_sf']);?></td>
      <td>$<?=number_format($centerinfo['ten_3_sales_psf']);?></td>
      <td>$<?=number_format($centerinfo['ten_3_volume']);?></td>
      <td><?=$centerinfo['ten_3_comments'];?></td>
    </tr>
    <tr>
      <td><?=$centerinfo['ten_4'];?></td>
      <td><?=number_format($centerinfo['ten_4_sf']);?></td>
      <td>$<?=number_format($centerinfo['ten_4_sales_psf']);?></td>
      <td>$<?=number_format($centerinfo['ten_4_volume']);?></td>
      <td><?=$centerinfo['ten_4_comments'];?></td>
    </tr>
    <tr>
      <td><?=$centerinfo['ten_5'];?></td>
      <td><?=number_format($centerinfo['ten_5_sf']);?></td>
      <td>$<?=number_format($centerinfo['ten_5_sales_psf']);?></td>
      <td>$<?=number_format($centerinfo['ten_5_volume']);?></td>
      <td><?=$centerinfo['ten_5_comments'];?></td>
    </tr>
    <tr>
      <td><?=$centerinfo['ten_6'];?></td>
      <td><?=number_format($centerinfo['ten_6_sf']);?></td>
      <td>$<?=number_format($centerinfo['ten_6_sales_psf']);?></td>
      <td>$<?=number_format($centerinfo['ten_6_volume']);?></td>
      <td><?=$centerinfo['ten_6_comments'];?></td>
    </tr>
    <tr>
      <td><?=$centerinfo['ten_7'];?></td>
      <td><?=number_format($centerinfo['ten_7_sf']);?></td>
      <td>$<?=number_format($centerinfo['ten_7_sales_psf']);?></td>
      <td>$<?=number_format($centerinfo['ten_7_volume']);?></td>
      <td><?=$centerinfo['ten_7_comments'];?></td>
    </tr>
    <tr>
      <td><?=$centerinfo['ten_8'];?></td>
      <td><?=number_format($centerinfo['ten_8_sf']);?></td>
      <td>$<?=number_format($centerinfo['ten_8_sales_psf']);?></td>
      <td>$<?=number_format($centerinfo['ten_8_volume']);?></td>
      <td><?=$centerinfo['ten_8_comments'];?></td>
    </tr>
    <tr>
      <td><?=$centerinfo['ten_9'];?></td>
      <td><?=number_format($centerinfo['ten_9_sf']);?></td>
      <td>$<?=number_format($centerinfo['ten_9_sales_psf']);?></td>
      <td>$<?=number_format($centerinfo['ten_9_volume']);?></td>
      <td><?=$centerinfo['ten_9_comments'];?></td>
    </tr>
    <tr>
      <td><?=$centerinfo['ten_10'];?></td>
      <td><?=number_format($centerinfo['ten_10_sf']);?></td>
      <td>$<?=number_format($centerinfo['ten_10_sales_psf']);?></td>
      <td>$<?=number_format($centerinfo['ten_10_volume']);?></td>
      <td><?=$centerinfo['ten_10_comments'];?></td>
    </tr>
    <tr>
      <td><?=$centerinfo['ten_11'];?></td>
      <td><?=number_format($centerinfo['ten_11_sf']);?></td>
      <td>$<?=number_format($centerinfo['ten_11_sales_psf']);?></td>
      <td>$<?=number_format($centerinfo['ten_11_volume']);?></td>
      <td><?=$centerinfo['ten_11_comments'];?></td>
    </tr>
    <tr>
      <td><?=$centerinfo['ten_12'];?></td>
      <td><?=number_format($centerinfo['ten_12_sf']);?></td>
      <td>$<?=number_format($centerinfo['ten_12_sales_psf']);?></td>
      <td>$<?=number_format($centerinfo['ten_12_volume']);?></td>
      <td><?=$centerinfo['ten_12_comments'];?></td>
    </tr>
    <tr>
      <td><?=$centerinfo['ten_13'];?></td>
      <td><?=number_format($centerinfo['ten_13_sf']);?></td>
      <td>$<?=number_format($centerinfo['ten_13_sales_psf']);?></td>
      <td>$<?=number_format($centerinfo['ten_13_volume']);?></td>
      <td><?=$centerinfo['ten_13_comments'];?></td>
    </tr>
    <tr>
      <td><?=$centerinfo['ten_14'];?></td>
      <td><?=number_format($centerinfo['ten_14_sf']);?></td>
      <td>$<?=number_format($centerinfo['ten_14_sales_psf']);?></td>
      <td>$<?=number_format($centerinfo['ten_14_volume']);?></td>
      <td><?=$centerinfo['ten_14_comments'];?></td>
    </tr>
    <tr>
      <td><?=$centerinfo['ten_15'];?></td>
      <td><?=number_format($centerinfo['ten_15_sf']);?></td>
      <td>$<?=number_format($centerinfo['ten_15_sales_psf']);?></td>
      <td>$<?=number_format($centerinfo['ten_15_volume']);?></td>
      <td><?=$centerinfo['ten_15_comments'];?></td>
    </tr>
    <tr>
      <td><?=$centerinfo['ten_16'];?></td>
      <td><?=number_format($centerinfo['ten_16_sf']);?></td>
      <td>$<?=number_format($centerinfo['ten_16_sales_psf']);?></td>
      <td>$<?=number_format($centerinfo['ten_16_volume']);?></td>
      <td><?=$centerinfo['ten_16_comments'];?></td>
    </tr>
    <tr>
      <td><?=$centerinfo['ten_17'];?></td>
      <td><?=number_format($centerinfo['ten_17_sf']);?></td>
      <td>$<?=number_format($centerinfo['ten_17_sales_psf']);?></td>
      <td>$<?=number_format($centerinfo['ten_17_volume']);?></td>
      <td><?=$centerinfo['ten_17_comments'];?></td>
    </tr>
    <tr>
      <td><?=$centerinfo['ten_18'];?></td>
      <td><?=number_format($centerinfo['ten_18_sf']);?></td>
      <td>$<?=number_format($centerinfo['ten_18_sales_psf']);?></td>
      <td>$<?=number_format($centerinfo['ten_18_volume']);?></td>
      <td><?=$centerinfo['ten_18_comments'];?></td>
    </tr>
    <tr>
      <td><?=$centerinfo['ten_19'];?></td>
      <td><?=number_format($centerinfo['ten_19_sf']);?></td>
      <td>$<?=number_format($centerinfo['ten_19_sales_psf']);?></td>
      <td>$<?=number_format($centerinfo['ten_19_volume']);?></td>
      <td><?=$centerinfo['ten_19_comments'];?></td>
    </tr>
    <tr>
      <td><?=$centerinfo['ten_20'];?></td>
      <td><?=number_format($centerinfo['ten_20_sf']);?></td>
      <td>$<?=number_format($centerinfo['ten_20_sales_psf']);?></td>
      <td>$<?=number_format($centerinfo['ten_20_volume']);?></td>
      <td><?=$centerinfo['ten_20_comments'];?></td>
    </tr>
  </table>
</div>

</body>
</html>
<?php 
$html = ob_get_contents();

ob_end_clean();

//echo $html;

include("include/dompdf/autoload.inc.php");
use Dompdf\Dompdf;
define("DOMPDF_ENABLE_HTML5PARSER", true);
define("DOMPDF_ENABLE_FONTSUBSETTING", true);
define("DOMPDF_UNICODE_ENABLED", true);
define("DOMPDF_DPI", 120);
define("DOMPDF_ENABLE_REMOTE", true);
//define("DOMPDF_ENABLE_JAVASCRIPT", true);
//define("DOMPDF_ENABLE_CSS_FLOAT", true);
$dompdf = new Dompdf();
$dompdf->load_html($html);
$dompdf->set_paper("a4", "landscape");
$dompdf->render();

$dompdf->stream('#'.$project['store_number'].'-'.$project['sitename'].'-'.$project['sitestate'].'.pdf', array("Attachment" => 0));
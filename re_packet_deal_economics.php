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
<h2>Deal Economics</h2>
<table>
<tr>
  <td style="width:50%;">
    <div class="section">
      <h2>Current Occupancy Costs</h2>
      <table>
        <tr>
          <th>&nbsp;</th>
          <th>Annual Amount</th>
          <th>Cost PSF</th>
        </tr>
        <tr>
          <td>Base Rent</td>
          <td class="money">$<?=number_format($deal_economics['cur_base_rent_amt'],2);?></td>
          <td class="money">$<?=number_format($deal_economics['cur_base_rent_psf'],2);?></td>
        </tr>
      <tr>
        <td>% Rent</td>
        <td class="money">$<?=number_format($deal_economics['cur_pct_rent_amt'],2);?></td>
        <td class="money">$<?=number_format($deal_economics['cur_pct_rent_psf'],2);?></td>
      </tr>
      <tr>
        <td>Base Rent Comments</td>
        <td colspan="2"><?=$deal_economics['cur_base_rent_comments']?></td>
      </tr>
      <tr>
        <td colspan="5"><p>&nbsp;</p></td>
      </tr>
      <tr>
        <td>RE Taxes</td>
        <td class="money">$<?=number_format($deal_economics['cur_re_taxes_amt'],2);?></td>
        <td class="money">$<?=number_format($deal_economics['cur_re_taxes_psf'],2);?></td>
      </tr>
      <tr>
        <td>Electirc</td>
        <td class="money">$<?=number_format($deal_economics['cur_electric_amt'],2);?></td>
        <td class="money">$<?=number_format($deal_economics['cur_electric_psf'],2);?></td>
      </tr>      
      <tr>
        <td>Water &amp; Sewer</td>
        <td class="money">$<?=number_format($deal_economics['cur_water_amt'],2);?></td>
        <td class="money">$<?=number_format($deal_economics['cur_water_psf'],2);?></td>
      </tr>
      <tr>
        <td>HVAC-US</td>
        <td class="money">$<?=number_format($deal_economics['cur_hvac_amt'],2);?></td>
        <td class="money">$<?=number_format($deal_economics['cur_hvac_psf'],2);?></td>
      </tr>
      <tr class="underline">
        <td>Trash</td>
        <td class="money">$<?=number_format($deal_economics['cur_trash_amt'],2);?></td>
        <td class="money">$<?=number_format($deal_economics['cur_trash_psf'],2);?></td>
      </tr>
      <tr>
        <td>Total Extras</td>
        <td class="money">$<?=number_format($deal_economics['cur_total_extras_amt'],2);?></td>
        <td class="money">$<?=number_format($deal_economics['cur_total_extras_psf'],2);?></td>
      </tr>
      <tr>
        <td colspan="5">&nbsp;</td>
      </tr>
      <tr>
        <td>1st Yr. Occupancy</td>
        <td class="money">$<?=number_format($deal_economics['cur_total_occupancy_amt'],2);?></td>
        <td class="money">$<?=number_format($deal_economics['cur_total_occupancy_psf'],2);?></td>       
      </tr>
      </table>
    </div>
  </td>
  <td>
  <div class="section">
    <h2>New Occupancy Costs</h2>
    <table>
      <tr>
        <th>Rent Years</th>
        <th>Annual Ammount</th>
        <th>Cost PSF</th>
        <th>Breakpoint</th>
        <th>% Rent</th>      
      </tr>
      <tr>
        <td> Year 1</td>
        <td class="money">$<?=number_format($deal_economics['new_y1_annual_amt'],2);?></td>
        <td class="money">$<?=number_format($deal_economics['new_y1_cost_psf'],2);?></td>
        <td>$<?=number_format($deal_economics['new_y1_breakpoint'],2);?></td>
        <td><?=number_format($deal_economics['new_y1_percent_rent'],2);?>%</td>
      </tr>
      <tr>
        <td>% Rent</td>
        <td class="money">$<?=number_format($deal_economics['new_pct_rent_amt'],2);?></td>
        <td class="money">$<?=number_format($deal_economics['new_pct_rent_psf'],2);?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Base Rent Comments</td>
        <td colspan="4"><?=$deal_economics['new_base_rent_comments']?></td>
      </tr>
      <tr>
        <td colspan="5"><p>&nbsp;</p></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td colspan="2">Incease on Extras</td>
      </tr>
      <tr>
        <td>RE Taxes</td>
        <td class="money">$<?=number_format($deal_economics['new_re_taxes_amt'],2);?></td>
        <td class="money">$<?=number_format($deal_economics['new_re_taxes_psf'],2);?></td>
        <td colspan="2"><?=$deal_economics['new_re_taxes_increases']?></td>        
      </tr>
      <tr>
        <td>Electirc</td>
        <td class="money">$<?=number_format($deal_economics['new_electric_amt'],2);?></td>
        <td class="money">$<?=number_format($deal_economics['new_electric_psf'],2);?></td>
        <td colspan="2"><?=$deal_economics['new_electric_increases']?></td>
      </tr>
      <tr>
        <td>Water &amp; Sewer</td>
        <td class="money">$<?=number_format($deal_economics['new_water_amt'],2);?></td>
        <td class="money">$<?=number_format($deal_economics['new_water_psf'],2);?></td>
        <td colspan="2"><?=$deal_economics['new_water_increases']?></td>
      </tr>
      <tr>
        <td>HVAC-US</td>
        <td class="money">$<?=number_format($deal_economics['new_hvac_amt'],2);?></td>
        <td class="money">$<?=number_format($deal_economics['new_hvac_psf'],2);?></td>
        <td colspan="2"><?=$deal_economics['new_hvac_increases']?></td>
      </tr>
      <tr class="underline">
        <td>Trash</td>
        <td class="money">$<?=number_format($deal_economics['new_trash_amt'],2);?></td>
        <td class="money">$<?=number_format($deal_economics['new_trash_psf'],2);?></td>
        <td colspan="2"><?=$deal_economics['new_trash_increases']?></td>
      </tr>
      <tr>
        <td>Total Extras</td>
        <td class="money">$<?=number_format($deal_economics['new_total_extras_amt'],2);?></td>
        <td class="money">$<?=number_format($deal_economics['new_total_extras_psf'],2);?></td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5">&nbsp;</td>
      </tr>
      <tr>
        <td>1st Yr. Occupancy</td>
        <td class="money">$<?=number_format($deal_economics['new_total_occupancy_amt'],2);?></td>
        <td class="money">$<?=number_format($deal_economics['new_total_occupancy_psf'],2);?></td>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>
  </div>
</td>
</tr>
</table>

<div class="section">
<table>
  <tr>
    <td>Difference (New - Current):</td>
    <td class="money">$<?=number_format($deal_economics['difference'], 2);?></td>
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
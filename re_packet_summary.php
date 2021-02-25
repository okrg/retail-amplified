<?php ob_start(); ?>
<?php include('re_packet_data.php'); ?>
<?php
$net_capex_total = $realestate['capex_y1_total'] - $realestate['tenant_allowance_y1_total'];
?>


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
  th.money,
  td.money {text-align: right;}
  h1 {
    font-size: 1em;
    line-height:1.5em;
    font-weight: bold;
    margin: 0;
    background:#ccc;
    padding: 2px;
  }
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


<table>
<tr>
  <td style="width:66%;">

<div class="section">
<h1>Center &amp; Store Information</h1>
<table>
<tr>
  <th>Brand</th>
  <td colspan="3"><?=$project['brand'];?></td>
</tr>

<tr>
  <th>Deal Type</th>
  <td colspan="3"><?=$project['project_type'];?></td>
</tr>

<tr>
  <th>Term</th>
  <td colspan="3"><?=$realestate['term'];?></td>
</tr>

<tr>
  <th>Center</th>
  <td colspan="3"><?=$project['sitename'];?></td>
</tr>

<tr>
  <th>LL</th>
  <td colspan="3"><?=$realestate['developer'];?></td>
</tr>

<tr>
  <th>Center Address</th>
  <td colspan="3"><?=$project['siteaddress'];?></td>
</tr>

<tr>
  <th>Landlord</th>
  <td colspan="3"><?=$realestate['developer'];?></td>
</tr>

<tr>
  <th>Center Rank</th>
  <td colspan="3"><?=$centerinfo['center_rank'];?></td>
</tr>

<tr>
  <th>Center Sales</th>
  <td>$<?=number_format($centerinfo['mall_sales'],2);?></td>
  <th>TTM Date</th>
  <td><?=dateFormat($centerinfo['ttm_date']);?></td>
</tr>

<tr>
  <th>Center Type</th>
  <td><?=$centerinfo['center_type'];?></td>
  <th>Indoor/Outdoor</th>
  <td><?=$project['indoor_outdoor_lifestyle'];?></td>
</tr>

<tr>
  <th>Space #</th>
  <td><?=$project['siteaddress2'];?></td>
  <th>Frontage</th>
  <td><?=$centerinfo['frontage'];?></td>
</tr>

<tr>
  <th>Current Square Feet</th>
  <td><?=$realestate['current_sqft'];?></td>
  <th>Proposed Square Feet</th>
  <td><?=$realestate['lease_sqft'];?></td>
</tr>

<tr>
  <th>Location Description</th>
  <td colspan="3"><?=$realestate['location_desc'];?></td>
</tr>

<tr>
  <th>Date of Possesion</th>
  <td><?=dateFormat($scheduled['possesion_date']);?></td>
  <th>Projected Opening Date</th>
  <td><?=dateFormat($project['store_opening_date']);?></td>
</tr>

<tr>
  <th>Deal Maker</th>
  <td><?=$realestate['deal_maker'];?></td>
  <th>Grand Open</th>
  <td><?php if( $project['grand_opening'] == 0 ) {echo 'No';} if( $project['grand_opening'] == 1 ) {echo 'Yes';} ?></td>
</tr>

<tr>
  <th>Current TTM Sales</th>
  <td>$<?=number_format($realestate['curent_ttm_sales'],2);?></td>
  <th>Current CF</th>
  <td>$<?=number_format($realestate['current_cf'],2);?></td>
</tr>
</table>
</div>

<div class="section">
<h1>Financial Data &amp; Metrics</h1>

<table>

<tr>
  <th></th>
  <th>Year 1</th>
  <th>Term Average</th>
</tr>

<tr>
  <th>Sales Pick</th>
  <td>$<?=number_format($realestate['sales_pick_y1'],2);?></td>
  <td>$<?=number_format($realestate['sales_pick_term_avg'],2);?></td>
</tr>

<tr>
  <th>SPSF</th>
  <td>$<?=number_format($realestate['spsf_y1'],2);?></td>
  <td>$<?=number_format($realestate['spsf_term_avg'],2);?></td>
</tr>

<tr>
  <th>R&amp;O PSF</th>
  <td>$<?=number_format($realestate['ro_psf_y1'],2);?></td>
  <td>$<?=number_format($realestate['ro_psf_term_avg'],2);?></td>
</tr>

<tr>
  <th>R&amp;O as a % of Sales</th>
  <td><?=$realestate['ro_pct_sales_y1'];?>%</td>
  <td><?=$realestate['ro_pct_sales_term_avg'];?>%</td>
</tr>

<tr>
  <th>Operating Income</th>
  <td>$<?=number_format($realestate['oi_y1'],2);?></td>
  <td>$<?=number_format($realestate['oi_term_avg'],2);?></td>
</tr>

<tr>
  <th>OI as a % of Sales</th>
  <td><?=$realestate['oi_pct_sales_y1'];?>%</td>
  <td><?=$realestate['oi_pct_sales_term_avg'];?>%</td>
</tr>

<tr>
  <th>EBITDA</th>
  <td>$<?=number_format($realestate['ebitda_y1'],2);?></td>
  <td>$<?=number_format($realestate['ebitda_term_avg'],2);?></td>
</tr>

<tr>
  <th>EBITDA as a % of Sales</th>
  <td><?=$realestate['ebitda_pct_sales_y1'];?>%</td>
  <td><?=$realestate['ebitda_pct_sales_term_avg'];?>%</td>
</tr>

<tr>
  <th>Cash Flow</th>
  <td>$<?=number_format($realestate['cash_flow_y1'],2);?></td>
  <td>$<?=number_format($realestate['cash_flow_term_avg'],2);?></td>
</tr>

<tr>
  <th>Payback (months)</th>
  <td><?=$realestate['payback_y1'];?></td>
  <td><?=$realestate['payback_term_avg'];?></td>
</tr>

</table>
</div>

</td>
<td>

<div class="section">
<h1>Key Indicators</h1>
<table>
<tr>
  <th>Sales &gt; $1.4M</th>
  <th><?php if($realestate['sales_pick_y1'] > 1400000) { echo '<div class="green">Y</div>'; } else { echo '<div class="yellow">N</div>'; } ?></th>
</tr>

<tr>
  <th>Sales PSF &gt; $250</th>
  <th><?php if($realestate['spsf_y1'] > 250) { echo '<div class="green">Y</div>'; } else { echo '<div class="yellow">N</div>'; } ?></th>
</tr>

<tr>
  <th>R&amp;O &lt; 18.5% (YR1)</th>
  <th><?php if($realestate['ro_pct_sales_y1'] < 18.5) { echo '<div class="green">Y</div>'; } else { echo '<div class="yellow">N</div>'; } ?></th>
</tr>

<tr>
  <th>R&amp;O &lt; 20.5%</th>
  <th><?php if($realestate['ro_pct_sales_term_avg'] < 20.5) { echo '<div class="green">Y</div>'; } else { echo '<div class="yellow">N</div>'; } ?></th>
</tr>

<tr>
  <th>EBITDA &gt; 17%</th>
  <th><?php if($realestate['ebitda_pct_sales_y1'] > 17) { echo '<div class="green">Y</div>'; } else { echo '<div class="yellow">N</div>'; } ?></th>
</tr>

<tr>
  <th>CF &gt; 200K (YR1)</th>
  <th><?php if($realestate['cash_flow_y1'] > 200000) { echo '<div class="green">Y</div>'; } else { echo '<div class="yellow">N</div>'; } ?></th>
</tr>

<tr>
  <th>Net Capex &lt; $275K</th>
  <th><?php if($realestate['net_capex_total'] < 275000) { echo '<div class="green">Y</div>'; } else { echo '<div class="yellow">N</div>'; } ?></th>
</tr>

<tr>
  <th>Payback &lt; 18 months</th>
  <th><?php if($realestate['payback_y1'] < 18) { echo '<div class="green">Y</div>'; } else { echo '<div class="yellow">N</div>'; } ?></th>
</tr>

</table>
</div>

<div class="section">
<h1>Capital</h1>
<table>
<tr>
  <th>Year 1 Capital Expenditures</th>
  <th class="money">Total</th>
  <th class="money">PSF</th>
</tr>

<tr>
  <th>CapEx Gross</th>
  <td class="money">$<?=number_format($realestate['capex_y1_total'],2);?></td>
  <td class="money">$<?=number_format($realestate['capex_y1_psf'],2);?></td>
</tr>

<tr>
  <th>Tenant Allowance</th>
  <td class="money">$<?=number_format($realestate['tenant_allowance_y1_total'],2);?></td>
  <td class="money">$<?=number_format($realestate['tenant_allowance_y1_psf'],2);?></td>
</tr>

<tr>
  <th>CapEx Net</th>
  <td class="money">$<?php echo number_format( $net_capex_total, 2);?></th>
  <td class="money">$<?php echo number_format( ($realestate['capex_y1_psf'] - $realestate['tenant_allowance_y1_psf']), 2);?></th>
</tr>
</table>
</div>

<div class="section">
<h1>Rent</h1>
<table>    
<tr>
  <th>Rent</th>
  <th class="money">Total</th>
  <th class="money">PSF</th>
</tr>

<tr>
  <th>Current</th>
  <td class="money">$<?=number_format($deal_economics['cur_base_rent_amt'],2);?></td>
  <td class="money">$<?=number_format($deal_economics['cur_base_rent_psf'],2);?></td>
</tr>

<tr>
  <th>New</th>
  <td class="money">$<?=number_format($deal_economics['new_y1_annual_amt'],2);?></td>
  <td class="money">$<?=number_format($deal_economics['new_y1_cost_psf'],2);?></td>
</tr>

<tr>
  <th>Difference</th>
  <td class="money">$<?php echo number_format( ($deal_economics['cur_base_rent_amt'] - $deal_economics['new_y1_annual_amt']), 2);?></th>
  <td class="money">$<?php echo number_format( ($deal_economics['cur_base_rent_psf'] - $deal_economics['new_y1_cost_psf']), 2);?></th>
</tr>

</table>
</div>

<div class="section">
<h1>Editorial</h1>

<table>
<tr>
  <td><?=$realestate['editorial'];?></td>
</tr>
</table>
</div>

</td>
</tr>
</table>

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
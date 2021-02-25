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

<div class="section">
  <table>
    <tr>
      <th>Current Expiration</th>
      <td colspan="7"><?=dateFormat($realestate['expiry_date']);?></td>
    </tr>
  </table>
</div>

<div class="section">
  <h2>Proposed Deal Terms</h2>
  <table>
    <tr>
      <th>Possession Date</th>
      <td><?=dateFormat($scheduled['possesion_date']);?></td>
      <th>Projected Open Date</th>
      <td><?=dateFormat($scheduled['store_opening_date']);?></td>
      <th>Grand Open?</th>
      <td><?=booleanFormat($project['grand_opening']);?></td>
      <th>Projected Expiration Date</th>
      <td><?=dateFormat($realestate['projected_expiration_date']);?></td>
    </tr>
    <tr>
      <th>Proposed Space Vacant?</th>
      <td><?=$realestate['proposed_space_vacant'];?></td>
      <th>Identify TT Occupying Space</th>
      <td><?=$realestate['tt_occupying_space'];?></td>
      <th>Date Current TT to Vacate Space</th>
      <td><?=dateFormat($realestate['current_tt_vacate_space_date']);?></td>
      <th>&nbsp;</th>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th>LL Work</th>
      <td><?=$realestate['landlord_work'];?></td>
      <th>LL Work Est. Completion Date</th>
      <td><?=dateFormat($realestate['est_ll_work_date']);?></td>
      <th>CR Must Vacate Current Space by Date</th>
      <td><?=dateFormat($realestate['vacate_current_space_by_date']);?></td>
      <th>&nbsp;</th>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th>Space Delivery Terms</th>
      <td colspan="7"><?=$realestate['delivery_conditions'];?></td>
    </tr>
  </table>
</div>

<div class="section">
  <table>
    <tr>
      <th>Rent Summary</th>
      <td colspan="7"><?=$realestate['rent_summary'];?></td>
    </tr>
    <tr>
      <th>Rent Period</th>
      <td colspan="7"><?=$realestate['rent_period'];?></td>
    </tr>
    <tr>
      <th>Kickout</th>
      <td><?=$realestate['kickout_y_n'];?></td>
      <th>Sales Threshold</th>
      <td>$<?=number_format($realestate['kickout_threshold'],2);?></td>
      <th>Year</th>
      <td><?=$realestate['kickout_year'];?></td>
      <th>Penalty</th>
      <td><?=$realestate['kickout_year'];?></td>
    </tr>
    <tr>
      <th>Renewal Option</th>
      <td colspan="7"><?=$realestate['renewal_option'];?></td>
    </tr>
    <tr>
      <th>Radius Restriction</th>
      <td colspan="7"><?=$realestate['radius_restriction'];?></td>
    </tr>
    <tr>
      <th>Co-Tenancy</th>
      <td colspan="7"><?=$realestate['co_tenancy'];?></td>
    </tr>
    <tr>
      <th>Kiosk Restriction</th>
      <td colspan="7"><?=$realestate['kiosk_restriction'];?></td>
    </tr>
    <tr>
      <th>Exclusives</th>
      <td colspan="7"><?=$realestate['exclusives'];?></td>
    </tr>
    <tr>
      <th>Other</th>
      <td colspan="7"><?=$realestate['other_deal_terms'];?></td>
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
<?php ob_start(); ?>
<?php include('re_doc_tracking_data.php'); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">  
  <style type="text/css">
  body{
    margin:0;
    font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    font-size: 10px;
    font-weight: 400;
    line-height: 1em;
    color: #212529;
    text-align: left;}
  table {
    width: 100%;
    vertical-align: top
  }
  th {text-align: center;}
  td {
    margin: 0;
    padding: 4px;
    vertical-align: middle;
    text-transform: capitalize;
    text-align: center;
  }
  th.state {
    width: 1em;
  }
  td.money {
    text-align: right;
  }
  td.notes {
    text-align: left;
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

<div class="section">
  <table>
    <thead>
    <tr>
      <th colspan="15">Document Tracking <?=date('m/d/Y');?></td>
    </tr>
    <tr>
      <th>Store #</th>
      <th>Mall Name</th>
      <th>State</th>
      <th>Landlord</th>
      <th>REC Approved</th>
      <th>Lease Exp.</th>
      <th>KO Notice Period</th>
      <th>Deal Maker</th>
      <th>Date Received</th>
      <th>Atty Review</th>
      <th>Doc Type &amp; Terms</th>
      <th>Linked</th>
      <th>Sent Via</th>
      <th>Ready for Signature</th>      
      <th>Notes</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($stores as $store): ?>
    <tr>
      <td><?=$store['store_number'];?></td>
      <td><?=$store['project']['sitename'];?></td>
      <td><?=$store['project']['sitestate'];?></td>
      <td><?=$store['realestate']['developer'];?></td>
      <td><?=dateFormat($store['scheduled']['rec_approval']);?></td>
      <td><?=dateFormat($store['realestate']['expiry_date']);?></td>
      <td><?=$store['kickouts']['notice_date'];?></td>
      <td><?=$store['realestate']['deal_maker'];?></td>
      <td><?=dateFormat($store['realestate']['date_rcvd']);?></td>
      <td><?=$store['realestate']['atty_review'];?></td>
      <td><?=$store['realestate']['deal_type'];?><br /><?=$store['realestate']['terms'];?></td>
      <td><?=$store['realestate']['linked'];?><br /><?=$store['realestate']['linked_comment'];?></td>
      <td><?=$store['realestate']['sent_via'];?></td>
      <td><?=dateFormat($store['realestate']['ready_for_signature']);?></td>
      <td>        
        <?php foreach($store['realestate']['comments'] as $comment): ?>
          <span><strong><?=$comment['author_initials']?> (<?=$comment['datetime']?>):</strong> <?=$comment['message']?></span>
        <?php endforeach; ?>
      </td>
    </tr>
  <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
<?php 
$html = ob_get_contents();

ob_end_clean();
//echo '<pre>';
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
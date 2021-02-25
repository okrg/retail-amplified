<?php
$sql= "select * from change_orders where id = {$_GET['id']}";
$result = mysql_query($sql);
$co = mysql_fetch_object($result);
$desc = stripslashes(preg_replace("/\n/","<br />",$co->description));
$pm_comment = stripslashes(preg_replace("/\n/","<br />",$co->pm_comment));

if ($pm_comment == "")$pm_comment = "Click to insert comments.";

	
//get location details
$lsql = "select * from projects where id = {$co->loc_key}";
$lres = mysql_query($lsql);
$loc = mysql_fetch_object($lres);

//get user details
$asql = "select fullname, company_name from users, companies where id = {$co->author_key} and users.company_id=companies.company_id";
$ares = mysql_query($asql);
$author = mysql_fetch_object($ares);

$psum=0;
if ($co->li1_status == "Pending") $psum = $psum+$co->li1_cost;
if ($co->li2_status == "Pending") $psum = $psum+$co->li2_cost;
if ($co->li3_status == "Pending") $psum = $psum+$co->li3_cost;
if ($co->li4_status == "Pending") $psum = $psum+$co->li4_cost;
if ($co->li5_status == "Pending") $psum = $psum+$co->li5_cost;								
if ($co->li6_status == "Pending") $psum = $psum+$co->li6_cost;		
$psum = number_format($psum,2);

$asum=0;
if ($co->li1_status == "Approved") $asum = $asum+$co->li1_cost;
if ($co->li2_status == "Approved") $asum = $asum+$co->li2_cost;
if ($co->li3_status == "Approved") $asum = $asum+$co->li3_cost;
if ($co->li4_status == "Approved") $asum = $asum+$co->li4_cost;
if ($co->li5_status == "Approved") $asum = $asum+$co->li5_cost;								
if ($co->li6_status == "Approved") $asum = $asum+$co->li6_cost;
$asum = number_format($asum,2);	

$dsum=0;
if ($co->li1_status == "Declined") $dsum = $dsum+$co->li1_cost;
if ($co->li2_status == "Declined") $dsum = $dsum+$co->li2_cost;
if ($co->li3_status == "Declined") $dsum = $dsum+$co->li3_cost;
if ($co->li4_status == "Declined") $dsum = $dsum+$co->li4_cost;
if ($co->li5_status == "Declined") $dsum = $dsum+$co->li5_cost;								
if ($co->li6_status == "Declined") $dsum = $dsum+$co->li6_cost;
$dsum = number_format($dsum,2);	

?>
<div id="content">
<h1>:: View Change Order</h1>
    <div class="databox">

	<div style="float:right;margin:0 10px 10px 10px;" class="filebox">
    	<p>Location Details:</p>
        <p><strong>#<?=$loc->store_number?> - <?=$loc->sitename?></strong></p>
        <p><?=$loc->siteaddress?></p>
        <p><?=$loc->sitecity?>, <?=$loc->sitestate?> <?=$loc->sitezip?></p>
        <p>Phone: <?=$loc->sitephone?></p>
        <p><a href="index.php?page=project&id=<?=$co->loc_key?>"><img src="images/levelup.gif" border="0" align="absmiddle" /> Return to Project Page</a></p>
    </div>

	<h2>Change Order #<?=$co->co_num?></h2>
	<p><strong>Submitted By</strong>: <?=$author->fullname?> @ <?=$author->company_name?></p>
    <p><strong>Date Submitted</strong>: <?=$co->date?></p>
	<p>&nbsp;</p>
    <p><strong>PM Comment</strong>: <span id="cbox"><?=$pm_comment?></span></p>
    <?php if ($usergroup < 1) { ?>
	    <script type="text/javascript">new Ajax.InPlaceEditor('cbox','change_order-eip.php?f=pm_comment&id=<?=$_GET['id']?>',{rows:6,cols:80});</script>
    <?php } ?>
	<p>&nbsp;</p>
 
	<br style="clear:both;" />
	<table width="99%" border="1" cellpadding="4" cellspacing="0" style="border-collapse:collapse;border-color:#666;">
    <tr>
    	<th width="20">&nbsp;</th>
        <th>Description</th>
        <th width="100">Status</th>  
        <th width="90">Pending Cost</th>        
    
    </tr>
    <tr>
    	<td>1</td>
        <td><p><?=$co->li1_desc?></p></td>
        <td>
        	<span id="sbox1" <?php if($co->li1_desc == "") echo "style=\"display:none;\""; ?>>
			<?=$co->li1_status?>
            </span>
            <?php if ($usergroup < 1) { ?>
			<script type="text/javascript">
            new Ajax.InPlaceCollectionEditor('sbox1',
            'change_order-eip.php?f=li1_status&id=<?=$_GET['id']?>',
            {collection:[['Pending','Pending'],['Approved','Approved'],['Declined','Declined']],value: '<?=$co->li1_status?>'});
            </script> 
            <?php } ?>   
        </td>
        <td><p><?=$co->li1_cost?></p></td>
    </tr>
    <tr>
    	<td>2</td>
        <td><p><?=$co->li2_desc?></p></td>
        <td>
        	<span id="sbox2" <?php if($co->li2_desc == "") echo "style=\"display:none;\""; ?>>
			<?=$co->li2_status?>
            </span>
            <?php if ($usergroup < 1) { ?>
			<script type="text/javascript">
            new Ajax.InPlaceCollectionEditor('sbox2',
            'change_order-eip.php?f=li2_status&id=<?=$_GET['id']?>',
            {collection:[['Pending','Pending'],['Approved','Approved'],['Declined','Declined']],value: '<?=$co->li2_status?>'});
            </script> 
            <?php } ?>   
        </td>
        <td><p><?=$co->li2_cost?></p></td>
    </tr>
    <tr>
    	<td>3</td>
        <td><p><?=$co->li3_desc?></p></td>
        <td>
        	<span id="sbox3" <?php if($co->li3_desc == "") echo "style=\"display:none;\""; ?>>
			<?=$co->li3_status?>
            </span>
            <?php if ($usergroup < 1) { ?>
			<script type="text/javascript">
            new Ajax.InPlaceCollectionEditor('sbox3',
            'change_order-eip.php?f=li3_status&id=<?=$_GET['id']?>',
            {collection:[['Pending','Pending'],['Approved','Approved'],['Declined','Declined']],value: '<?=$co->li3_status?>'});
            </script> 
            <?php } ?>   
        </td>
        <td><p><?=$co->li3_cost?></p></td>

    </tr>
    <tr>
    	<td>4</td>
        <td><p><?=$co->li4_desc?></p></td>

        <td>
        	<span id="sbox4" <?php if($co->li4_desc == "") echo "style=\"display:none;\""; ?>>
			<?=$co->li4_status?>
            </span>
            <?php if ($usergroup < 1) { ?>
			<script type="text/javascript">
            new Ajax.InPlaceCollectionEditor('sbox4',
            'change_order-eip.php?f=li4_status&id=<?=$_GET['id']?>',
            {collection:[['Pending','Pending'],['Approved','Approved'],['Declined','Declined']],value: '<?=$co->li4_status?>'});
            </script> 
            <?php } ?>   
        </td>
        <td><p><?=$co->li4_cost?></p></td>
    </tr>
    <tr>
    	<td>5</td>
        <td><p><?=$co->li5_desc?></p></td>

        <td>
        	<span id="sbox5" <?php if($co->li5_desc == "") echo "style=\"display:none;\""; ?>>
			<?=$co->li5_status?>
            </span>
            <?php if ($usergroup < 1) { ?>
			<script type="text/javascript">
            new Ajax.InPlaceCollectionEditor('sbox5',
            'change_order-eip.php?f=li5_status&id=<?=$_GET['id']?>',
            {collection:[['Pending','Pending'],['Approved','Approved'],['Declined','Declined']],value: '<?=$co->li5_status?>'});
            </script> 
            <?php } ?>   
        </td>
        <td><p><?=$co->li5_cost?></p></td>
    </tr>
    <tr>
    	<td>6</td>
        <td><p><?=$co->li6_desc?></p></td>

        <td>
        	<span id="sbox6" <?php if($co->li6_desc == "") echo "style=\"display:none;\""; ?>>
			<?=$co->li6_status?>
            </span>
            <?php if ($usergroup < 1) { ?>
			<script type="text/javascript">
            new Ajax.InPlaceCollectionEditor('sbox6',
            'change_order-eip.php?f=li6_status&id=<?=$_GET['id']?>',
            {collection:[['Pending','Pending'],['Approved','Approved'],['Declined','Declined']],value: '<?=$co->li6_status?>'});
            </script> 
            <?php } ?>   
        </td>
        <td><p><?=$co->li6_cost?></p></td>
    </tr>
    <tr bgcolor="#8FA76F">
        <td colspan="3"><strong>Pending Total</strong></td>
        <td><strong>$<?php print($psum);?></strong></td>
    </tr>
    <tr bgcolor="#8FA76F">
        <td colspan="3"><strong>Declined Total</strong></td>
        <td><strong>$<?php print($dsum);?></strong></td>
    </tr>
    <tr bgcolor="#8FA76F">
        <td colspan="3"><strong>Approved Total</strong></td>
        <td><strong>$<?php print($asum);?></strong></td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
        <td><a href="#" onclick="javascript:location.reload(true);return false;">Recalculate</a></td>
    </tr>
    </table>
    
    
	<p>&nbsp;</p>        
	<p><a href="index.php?page=project&id=<?=$co->loc_key?>"><img src="images/levelup.gif" border="0" align="absmiddle" /> Return to Project Page</a></p>
   	<?php if ($usergroup < 1) {	?>
	<p><a href="index.php?page=del-change_order&id=<?=$_GET['id']?>"><img src="images/delete.gif" border="0" align="absmiddle" /> Delete this Change Order</a></p>
    <?php } ?>	<br style="clear:both;" />
     </div>
 </div>
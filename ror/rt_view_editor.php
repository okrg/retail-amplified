<?php //view_editor.php
if ($rt->status == "completed") { 
	echo "<tr><td colspan=\"5\"><h2>No More Responses</h2></td></tr>"; 
} else { 
$yesterday = mktime(0, 0, 0, date("m") , date("d") - 1, date("Y"));
$yesterday = date("U", $yesterday);
$yesterday= getdate($yesterday);
$today = getdate();
$tomorrow = mktime(0, 0, 0, date("m") , date("d") + 1, date("Y"));
$tomorrow = date("U", $tomorrow);
$tomorrow= getdate($tomorrow);
?>
<tr><td colspan="5">
	<h2>Add Response</h2>
	<table width="100%" id="editor"><tr><td>
	<?php if (($usergroup<1)and($mode=="ROR")) { ?>
			<p><strong>Response</strong><br />
			<select class="response" name="preset">
			<option value="Received your request and it has been dispatched... " selected="selected">Received your request and it has been dispatched</option>
			<option value="Minor, Non-customer sensitive issue. Too costly for repair at this time... ">Minor, Non-customer sensitive issue. Too costly for repair at this time.</option>
			<option value="IT issue-Contact help desk x2428... ">IT Issue-Contact Help Desk x2428</option>
			<option value="Loss Prevention issue... ">Loss Prevention Issue</option>
			<option value="Upgrade project-Please send to RM for approval... ">Upgrade project-Please send to RM for approval</option>
			<option value="Fixture disposal form and store planning approval required... ">Fixture disposal form and store planning approval required</option>
			<option value="Purchasing issue-Contact the purchasing department x3029... ">Purchasing issue-Contact the purchasing department x3029</option>			
			<option value="Received your request and it has been dispatched... ">Received your request and it has been dispatched</option>
			<option value="Received your request and it is currently on hold... ">Received your request and it is currently on hold</option>
			<option value="Received your request and will need more information from the store... ">Received your request and will need more info from the store</option>
			<option value="Waiting on parts... ">Waiting on parts</option>
			<option value="Waiting on a quote... ">Waiting on a quote</option>
			<option value="Quote has been approved... ">Quote has been approved</option>
			<option value="Parts have been ordered... ">Parts have been ordered</option>
			<option value="Completed">Completed</option>
			<option value="Other">Other</option>
			</select></p>
	<?php }elseif (($usergroup<2)and($mode=="FREQ")) {?>
			<p><strong>Response</strong><br />
			<select class="response"  name="preset">
   			<option value="Other" selected="selected">Other</option>
   			<option value="Purchase order created">Purchase order created.</option>
			<option value="Your request is being reviewed. Please reply to the question(s) listed below to assist in this process.">Your request is being reviewed. Please reply...</option>
			<option value=" Your request has been approved. Your item(s) will ship in 1-2 weeks.">Request approved. Will ship in 1-2 weeks</option>
			<option value="Your request has been approved. Your item(s) will ship in 1-2 weeks. These items will ship with inside delivery and removal of old items. Please notify the store to ensure that level of delivery service is completed.">Approved. Ships in 1-2 weeks with inside delivery</option>
			<option value="Your request has been approved. Your item(s) will ship in 4-5 weeks.">Request approved. Will ship in 4-5 weeks</option>
			<option value="We can not approve your order at this time.">We can not approve your order at this time</option>            
			<option value="This request is being reviewed with the visuals department. There are several requests for this chain wide. We are reviewing this need with visuals for a possible roll out.">Being reviewed with visuals. Several requests...</option>
            <option value="Completed">Completed</option>
           <option value="Your request has been denied at this time.">Your request has been denied at this time.</option>
			</select></p>
	<?php } ?>
			<p><strong>Questions and Comments</strong><br />
			<textarea class="comments" name="body"></textarea></p>
			<p><strong>Attach File</strong><br />
			<input id="my_file_element" type="file" name="file_1" >&nbsp;<small>(Up to 5 max)</small><br />
			<div id="files_list"></div>
			<script>
			<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->
			var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 5 );
			<!-- Pass in the file element -->
			multi_selector.addElement( document.getElementById( 'my_file_element' ) );
			</script>
			</p>
		</td>
	<?php if ($usergroup<1) { ?>
		<?php if ($mode == "ROR") { ?>
		<td class="assign" width="40%">
			<p><strong>Assign PO Number</strong><br />
			<input class="assign" type="text" name="po_num" value="<?=$rt->po_num?>" /></p>
			<p><strong>Assign to Vendor</strong><br />
			<select class="assign" name="vendor">
			<?php 
			if (isset($rt->vendor_key)) {
				echo "<option value=\"$rt->vendor_key\" selected>$rt->company_name</option>";
			} else {
				echo "<option value=\" \" selected>-Select Vendor-</option>";
			}
			$drop_sql = "select * from companies where roles LIKE '%g2%'";
			$drop_result = mysql_query($drop_sql);
			while ($row = mysql_fetch_object($drop_result)) {
				echo "<option value=\"$row->company_id\">$row->company_name</option>";
			}

			?>			

			</select>
			<p><strong>Vendor Instructions</strong></p>
			<textarea class="comments" name="instructions"><?=$rt->instructions?></textarea>
		</td>
		<?php } elseif ($mode == "FREQ") { 
		//check for dates
		$request_filled_y = substr($rt->request_filled,0,4);
		$request_filled_m = substr($rt->request_filled,5,2);
		$request_filled_d = substr($rt->request_filled,8,2);

		$target_y = substr($rt->target_ship,0,4);
		$target_m = substr($rt->target_ship,5,2);
		$target_d = substr($rt->target_ship,8,2);

		$ship_y = substr($rt->actual_ship,0,4);
		$ship_m = substr($rt->actual_ship,5,2);
		$ship_d = substr($rt->actual_ship,8,2);

		$recv_y = substr($rt->actual_recv,0,4);
		$recv_m = substr($rt->actual_recv,5,2);
		$recv_d = substr($rt->actual_recv,8,2);

		
		?>
		<td class="assign">
			<p><strong>Update Status</strong><br />
			<select class="assign" name="order_status">
			<option value="<?=$rt->order_status?>"><?php echo ucwords($rt->order_status);?></option>
			<option value="">---------</option>
			<option value="pending">Pending</option>
            <option value="purchased">Purchased</option>
			<option value="preparing">Preparing</option>
			<option value="shipped">Shipped</option>
			<option value="hold">On Hold</option>
			</select>
			<p><strong>Assign PO Number</strong><br />
			<input class="assign" type="text" name="po_num" value="<?=$rt->po_num?>" /></p>
			<p><strong>Assign to Vendor</strong><br />
			<select class="assign" name="vendor">
			<option value="<?=$rt->vendor_key?>" selected><?=$rt->company_name?></option>
			<?php
			$drop_sql = "select * from companies where roles LIKE '%freq%'";
			$drop_result = mysql_query($drop_sql);
			while ($row = mysql_fetch_object($drop_result)) {
				echo "<option value=\"$row->company_id\">$row->company_name</option>";
			}
			?>


			</select>

			<p><strong>Request Filled Date</strong><br />
			<select class="date" id="request_filled_month" name="request_filled_month">
				<option value="<?=$request_filled_m?>"><?=$request_filled_m?></option>
				<option value="01">Jan</option>
				<option value="02">Feb</option>
				<option value="03">Mar</option>
				<option value="04">Apr</option>
				<option value="05">May</option>
				<option value="06">Jun</option>
				<option value="07">Jul</option>
				<option value="08">Aug</option>
				<option value="09">Sep</option>
				<option value="10">Oct</option>
				<option value="11">Nov</option>
				<option value="12">Dec</option>
			</select>
			/
			<select class="date" name="request_filled_day">
				<option value="<?=$request_filled_d?>"><?=$request_filled_d?></option>
				<option value="01">01</option>
				<option value="02">02</option>
				<option value="03">03</option>
				<option value="04">04</option>
				<option value="05">05</option>
				<option value="06">06</option>
				<option value="07">07</option>
				<option value="08">08</option>
				<option value="09">09</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
				<option value="21">21</option>
				<option value="22">22</option>
				<option value="23">23</option>
				<option value="24">24</option>
				<option value="25">25</option>
				<option value="26">26</option>
				<option value="27">27</option>
				<option value="28">28</option>
				<option value="29">29</option>
				<option value="30">30</option>
				<option value="31">31</option>
			</select>
			/
			<select class="date" name="request_filled_year">
				<option value="<?=$request_filled_y?>"><?=$request_filled_y?></option> 
				<option value="2007">2007</option>
				<option value="2008">2008</option>
			</select>
			<a href="javascript:today1(<?php echo str_pad($yesterday[mon],2,"0",STR_PAD_LEFT);?>,<?php echo str_pad($yesterday[mday],2,"0",STR_PAD_LEFT);?>,<?=$yesterday[year]?>);"><img src="images/yesterday.gif" align="absmiddle" title="Yesterday" /></a>
			<a href="javascript:today1(<?php echo str_pad($today[mon],2,"0",STR_PAD_LEFT);?>,<?php echo str_pad($today[mday],2,"0",STR_PAD_LEFT);?>,<?=$today[year]?>);"><img src="images/today.gif" align="absmiddle" title="Today" /></a>
			<a href="javascript:today1(<?php echo str_pad($tomorrow[mon],2,"0",STR_PAD_LEFT);?>,<?php echo str_pad($tomorrow[mday],2,"0",STR_PAD_LEFT);?>,<?=$tomorrow[year]?>);"><img src="images/tomorrow.gif" align="absmiddle" title="Tomorrow" /></a>
			</p>


			
			<p><strong>Target Ship Date</strong><br />
			<select class="date" name="target_month">
				<option value="<?=$target_m?>"><?=$target_m?></option>
				<option value="01">Jan</option>
				<option value="02">Feb</option>
				<option value="03">Mar</option>
				<option value="04">Apr</option>
				<option value="05">May</option>
				<option value="06">Jun</option>
				<option value="07">Jul</option>
				<option value="08">Aug</option>
				<option value="09">Sep</option>
				<option value="10">Oct</option>
				<option value="11">Nov</option>
				<option value="12">Dec</option>
			</select>
			/
			<select class="date" name="target_day">
				<option value="<?=$target_d?>"><?=$target_d?></option>
				<option value="01">01</option>
				<option value="02">02</option>
				<option value="03">03</option>
				<option value="04">04</option>
				<option value="05">05</option>
				<option value="06">06</option>
				<option value="07">07</option>
				<option value="08">08</option>
				<option value="09">09</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
				<option value="21">21</option>
				<option value="22">22</option>
				<option value="23">23</option>
				<option value="24">24</option>
				<option value="25">25</option>
				<option value="26">26</option>
				<option value="27">27</option>
				<option value="28">28</option>
				<option value="29">29</option>
				<option value="30">30</option>
				<option value="31">31</option>
			</select>
			/
			<select class="date" name="target_year">
				<option value="<?=$target_y?>"><?=$target_y?></option> 
				<option value="2007">2007</option>
				<option value="2008">2008</option>
			</select>			
			<a href="javascript:today2(<?php echo str_pad($yesterday[mon],2,"0",STR_PAD_LEFT);?>,<?php echo str_pad($yesterday[mday],2,"0",STR_PAD_LEFT);?>,<?=$yesterday[year]?>);"><img src="images/yesterday.gif" align="absmiddle" title="Yesterday" /></a>
			<a href="javascript:today2(<?php echo str_pad($today[mon],2,"0",STR_PAD_LEFT);?>,<?php echo str_pad($today[mday],2,"0",STR_PAD_LEFT);?>,<?=$today[year]?>);"><img src="images/today.gif" align="absmiddle" title="Today" /></a>
			<a href="javascript:today2(<?php echo str_pad($tomorrow[mon],2,"0",STR_PAD_LEFT);?>,<?php echo str_pad($tomorrow[mday],2,"0",STR_PAD_LEFT);?>,<?=$tomorrow[year]?>);"><img src="images/tomorrow.gif" align="absmiddle" title="Tomorrow" /></a>
			</p>

			<p><strong>Actual Ship Date</strong><br />
			<select class="date" name="ship_month">
				<option value="<?=$ship_m?>"><?=$ship_m?></option>
				<option value="01">Jan</option>
				<option value="02">Feb</option>
				<option value="03">Mar</option>
				<option value="04">Apr</option>
				<option value="05">May</option>
				<option value="06">Jun</option>
				<option value="07">Jul</option>
				<option value="08">Aug</option>
				<option value="09">Sep</option>
				<option value="10">Oct</option>
				<option value="11">Nov</option>
				<option value="12">Dec</option>
			</select>
			/
			<select class="date" name="ship_day">
				<option value="<?=$ship_d?>"><?=$ship_d?></option>
				<option value="01">01</option>
				<option value="02">02</option>
				<option value="03">03</option>
				<option value="04">04</option>
				<option value="05">05</option>
				<option value="06">06</option>
				<option value="07">07</option>
				<option value="08">08</option>
				<option value="09">09</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
				<option value="21">21</option>
				<option value="22">22</option>
				<option value="23">23</option>
				<option value="24">24</option>
				<option value="25">25</option>
				<option value="26">26</option>
				<option value="27">27</option>
				<option value="28">28</option>
				<option value="29">29</option>
				<option value="30">30</option>
				<option value="31">31</option>
			</select>
			/
			<select class="date" name="ship_year">
				<option value="<?=$ship_y?>"><?=$ship_y?></option> 
				<option value="2007">2007</option>
				<option value="2008">2008</option>
			</select>			
			<a href="javascript:today3(<?php echo str_pad($yesterday[mon],2,"0",STR_PAD_LEFT);?>,<?php echo str_pad($yesterday[mday],2,"0",STR_PAD_LEFT);?>,<?=$yesterday[year]?>);"><img src="images/yesterday.gif" align="absmiddle" title="Yesterday" /></a>
			<a href="javascript:today3(<?php echo str_pad($today[mon],2,"0",STR_PAD_LEFT);?>,<?php echo str_pad($today[mday],2,"0",STR_PAD_LEFT);?>,<?=$today[year]?>);"><img src="images/today.gif" align="absmiddle" title="Today" /></a>
			<a href="javascript:today3(<?php echo str_pad($tomorrow[mon],2,"0",STR_PAD_LEFT);?>,<?php echo str_pad($tomorrow[mday],2,"0",STR_PAD_LEFT);?>,<?=$tomorrow[year]?>);"><img src="images/tomorrow.gif" align="absmiddle" title="Tomorrow" /></a>
			</p>

			<p><strong>Actual Receive Date</strong><br />
			<select class="date" name="recv_month">
				<option value="<?=$recv_m?>"><?=$recv_m?></option>
				<option value="01">Jan</option>
				<option value="02">Feb</option>
				<option value="03">Mar</option>
				<option value="04">Apr</option>
				<option value="05">May</option>
				<option value="06">Jun</option>
				<option value="07">Jul</option>
				<option value="08">Aug</option>
				<option value="09">Sep</option>
				<option value="10">Oct</option>
				<option value="11">Nov</option>
				<option value="12">Dec</option>
			</select>
			/
			<select class="date" name="recv_day">
				<option value="<?=$recv_d?>"><?=$recv_d?></option>
				<option value="01">01</option>
				<option value="02">02</option>
				<option value="03">03</option>
				<option value="04">04</option>
				<option value="05">05</option>
				<option value="06">06</option>
				<option value="07">07</option>
				<option value="08">08</option>
				<option value="09">09</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
				<option value="21">21</option>
				<option value="22">22</option>
				<option value="23">23</option>
				<option value="24">24</option>
				<option value="25">25</option>
				<option value="26">26</option>
				<option value="27">27</option>
				<option value="28">28</option>
				<option value="29">29</option>
				<option value="30">30</option>
				<option value="31">31</option>
			</select>
			/
			<select class="date" name="recv_year">
				<option value="<?=$recv_y?>"><?=$recv_y?></option> 
				<option value="2007">2007</option>
				<option value="2008">2008</option>
			</select>			
			<a href="javascript:today4(<?php echo str_pad($yesterday[mon],2,"0",STR_PAD_LEFT);?>,<?php echo str_pad($yesterday[mday],2,"0",STR_PAD_LEFT);?>,<?=$yesterday[year]?>);"><img src="images/yesterday.gif" align="absmiddle" title="Yesterday" /></a>
			<a href="javascript:today4(<?php echo str_pad($today[mon],2,"0",STR_PAD_LEFT);?>,<?php echo str_pad($today[mday],2,"0",STR_PAD_LEFT);?>,<?=$today[year]?>);"><img src="images/today.gif" align="absmiddle" title="Today" /></a>
			<a href="javascript:today4(<?php echo str_pad($tomorrow[mon],2,"0",STR_PAD_LEFT);?>,<?php echo str_pad($tomorrow[mday],2,"0",STR_PAD_LEFT);?>,<?=$tomorrow[year]?>);"><img src="images/tomorrow.gif" align="absmiddle" title="Tomorrow" /></a>
			</p>
		</td>
		<?php } ?>
	<?php } elseif ($usergroup == 3) { ?>
		<td>
			<?php if ($mode == "ROR") { ?>
			<p><strong>PO Number:</strong> <?=$rt->po_num?></p>
			<p><strong>Vendor Instructions:</strong> <?=$rt->instructions?></p>
			<?php } ?>
		</td>
	<?php } else { ?>
		<td>
		</td>
	<?php } ?>
		</tr></table>
</td></tr>
<?php } ?>
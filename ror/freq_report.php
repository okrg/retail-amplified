<?php //freq_report
$f = Fixtures();
$u = Urgencies();
foreach ($u as $k) {
	$key = array_search($k,$u);
	if ($key=="10") {continue;}
	if ($key=="40") {continue;}
	if ($key=="50") {continue;}
	$urgency_options .= "<input name=\"urgency[]\" type=\"checkbox\" value=\"$key\" id=\"u_$key\" disabled /><label for=\"u_$key\"><img src=\"images/$k[1].gif\" />$k[0]</label><br />";
}

?>
<form name="report_builder" id="report_builder" method="post" action="report_engine.php?mode=FREQ">
<table border="0">
	<tr>
		<td>
		<h2>Status</h2>
		<input name="statall" type="checkbox" value="" id="statall" onClick="toggleChecks('status')" checked /><label for="statall">All</label><br />
		<input name="status[]" type="checkbox" value="new" id="statpend" disabled /><label for="statpend"><img src="/images/pending.gif" align="absmiddle" />New</label><br />
		<input name="status[]" type="checkbox" value="open" id="statans" disabled /><label for="statans"><img src="/images/answered.gif" align="absmiddle" />Open</label><br />
		<input name="status[]" type="checkbox" value="completed" id="statclr" disabled /><label for="statclr"><img src="/images/clear.gif" align="absmiddle" />Complete</label><br />
		<h2>Priority</h2>
		<input name="urgall" type="checkbox" value="" id="urgall" onClick="toggleChecks('urgency')" checked /><label for="urgall">All</label><br />
		<?=$urgency_options?>

		</td>
		<td>
		<h2>Category</h2>
		<input name="typeall" type="checkbox" value="" id="typeall" onClick="toggleChecks('type')" checked /><label for="typeall">All</label><br />
		<?php
		$selectsql = "select distinct cat from fixture_key where chain =1 order by cat asc";
		$result = mysql_query($selectsql);
		if (!$result){error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error());}
		$num=0;
		while ($row = mysql_fetch_object($result)){
			$num++;
			print "<input name=\"type[]\" type =\"checkbox\" id=\"cat_$num\" value=\"$row->cat\" disabled />";
			print "<label for=\"cat_$num\">$row->cat</label><br />";
		}
	?>
		</td>
		<td>
		<h2>Add/Replace</h2>
		<input name="requestall" type="checkbox" value="request_all" id="request_all" onClick="toggleChecks('request')" checked /><label for="request_all">All</label><br />
		<input name="request[]" type="checkbox" value="0" id="request_add" disabled /><label for="request_add">Additional</label><br />
		<input name="request[]" type="checkbox" value="1" id="request_replace" disabled /><label for="request_replace">Replacement</label><br />

		<h2>Item Number</h2>
		<p>
		  <input name="item_option" type="radio" id="item_any_radio" value="any" checked="checked" onClick="ItemButtonChecked();" />
		  <label for="item_any_radio">Any</label>
		</p>
		<p>
		  <input name="item_option" type="radio" value="select" id="item_select_radio" onClick="ItemButtonChecked();" />
		  <label for="item_select_radio">Select:</label>
		  <select name="item_select" id="item_select" onChange="selectlabel('item_select_radio');">
			<option value="" selected="selected">All</option>
			<?php
				while ($row = mysql_fetch_object($f)){print "<option value=\"$row->name\">$row->name</option>";}
			?>
		  </select>
		  </p>
		<p>
		  <input name="item_option" type="radio" value="specify" id="item_specify_radio" onClick="ItemButtonChecked();" />
		  <label for="item_specify_radio">Specify:</label>
		  <input type="text" name="item_specify" id="item_specify" size="10" disabled="true" />
		</p>

		</td>
		<td rowspan="2">
		<h2>Request Date</h2>
		<p>
		<input name="date_option" type="radio" id="date_any" value="0" checked="checked" onClick="SpecifyButtonChecked();" />
		<label for="date_any">Any Date</label>
		</p>
		<p>
		<input name="date_option" type="radio" id="date_last_day" value="1" onClick="SpecifyButtonChecked();" />
		<label for="date_last_day">Within last day</label>
		</p>
		<p>
		<input name="date_option" type="radio" id="date_last_week" value="7" onClick="SpecifyButtonChecked();" />
		<label for="date_last_week">Within last 7 days</label>
		</p>
		<p>
		<input name="date_option" type="radio" id="date_last_month" value="30" onClick="SpecifyButtonChecked();" />
		<label for="date_last_month">Within last 30 days</label>
		</p>		
		<p>
		<input name="date_option" type="radio" id="date_last_quarter" value="90" onClick="SpecifyButtonChecked();" />
		<label for="date_last_quarter">Within last 90 days</label>
		</p>
		<p>
		<input name="date_option" type="radio" id="date_last_semi" value="180" onClick="SpecifyButtonChecked();" />
		<label for="date_last_semi">Within last 180 days</label>
		</p>
		<p>
		<input name="date_option" type="radio" id="date_last_year" value="365" onClick="SpecifyButtonChecked();" />
		<label for="date_last_year">Within last 365 days</label>
		</p>
		<p>
		<input name="date_option" type="radio" id="date_specify" value="-1" onClick="SpecifyButtonChecked();" />
		<label for="date_specify">Specify:</label>
		</p>
		<table border="0" cellpadding="10">
		<tr>
		<td>From</td>
		<td>
			<select class="date" name="date_from_month" id="date_from_month" disabled="true" >
				<option value="<?=$yesterday[mon]?>"><?=$yesterday[month]?></option>
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
			<select class="date" name="date_from_day" id="date_from_day" disabled="true" >
				<option value="<?=$yesterday[mday]?>"><?=$yesterday[mday]?></option>
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
			<select class="date" name="date_from_year" id="date_from_year" disabled="true" >
				<option value="<?=$yesterday[year]?>"><?=$yesterday[year]?></option> 
				<option value="2007">2007</option>
				<option value="2008">2008</option>
			</select>
			</td>
			</tr>
			<tr>
			<td>To:</td>
			<td>		
			<select class="date" name="date_to_month" id="date_to_month" disabled="true" >
				<option value="<?=$today[mon]?>"><?=$today[month]?></option>
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
			<select class="date" name="date_to_day" id="date_to_day" disabled="true" >
				<option value="<?=$today[mday]?>"><?=$today[mday]?></option>
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
			<select class="date" name="date_to_year" id="date_to_year" disabled="true" >
				<option value="<?=$today[year]?>"><?=$today[year]?></option> 
				<option value="2007">2007</option>
				<option value="2008">2008</option>
			</select>	
			</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3"><h2>Order Status</h2>
			<input name="order_statall" type="checkbox" value="" id="order_statall" onClick="toggleChecks('order_status')" checked /><label for="order_statall">All</label>&nbsp;&nbsp;
			<input name="order_status[]" type="checkbox" value="pending" id="order_statpend" disabled /><label for="order_statpend">Pending</label>&nbsp;&nbsp;
			<input name="order_status[]" type="checkbox" value="preparing" id="order_statprep" disabled /><label for="order_statprep">Preparing</label>&nbsp;&nbsp;
			<input name="order_status[]" type="checkbox" value="shipping" id="order_statship" disabled /><label for="order_statship">Shipping</label>&nbsp;&nbsp;
			<input name="order_status[]" type="checkbox" value="hold" id="order_stathold" disabled /><label for="order_stathold">On Hold</label><br />					
		</td>
	</tr>
</table>
<table border="0">
	<tr>
	<td colspan="3">
	<h2>Response Selection</h2>
			<select name="preset_option">
			<option value="" selected="selected">-- Any --</option>
			<option value="Your request has been approved. Your item(s) will ship in 1-2 weeks.">Request approved. Ships in 1-2 weeks.</option>
			<option value="Your request has been approved. Your item(s) will ship in 4-5 weeks.">Request approved.Ships in 4-5 weeks.</option>
			<option value="Your request is being reviewed for approval. You will be contacted to discuss. Thank you for your patience during this process.">Request is being reviewed</option>
			<option value="Your request is being reviewed and in order to keep with your current fixtures, we will not be able to provide this fixture. However we will be able to provide you with an alternate option. I will contact you to discuss.">I will contact you to discuss this</option>
			</select></p>

	</td>
	</tr>

  	<tr>
	    <td><h2>Attachments </h2>
			<p>
			<input name="attachments_option" type="radio" id="attachments_any" value="any" checked="checked" />
			<label for="attachments_any">Any</label>
			</p>
			<p>
			<input name="attachments_option" type="radio" value="none" id="attachments_none" />
			<label for="attachments_none">None</label>
			</p>
		</td>
	    
		<td>
    		<h2>Vendors</h2>
        	<p>
          	<input name="vendor_option" type="radio" value="any" id="vendor_any_radio" checked="checked" onClick="VendorButtonChecked();" />
          	<label for="vendor_any_radio">Any</label>
        	</p>
	      	<p>
  			<input name="vendor_option" type="radio" value="none" id="vendor_none_radio" onClick="VendorButtonChecked();" />
  			<label for="vendor_none_radio">No Vendor Assigned</label>
	      	</p>
	      	<p>
	        <input name="vendor_option" type="radio" value="select" id="vendor_select_radio" onClick="VendorButtonChecked();" />
	        <label for="vendor_select_radio">Assigned to:</label>
	        <select name="vendor_select" id="vendor_select" onChange="selectlabel('vendor_select_radio');" disabled="true">
	          <option value="" selected="selected">Select</option>
  		       <?php
					$selectsql = "select * from companies";
					$result = mysql_query($selectsql);
					if (!$result){error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error());}
					while ($row = mysql_fetch_object($result)){
						$roles = explode(",",$row->roles);
						if (in_array("freq",$roles)){print "<option value=\"$row->company_id\">$row->company_name</option>";}else{continue;}
					}
				?>
			  
	        </select>
			</p>
		</td>
 		
		<td><h2>Responses </h2>
	        <p>
	          <input name="responses_option" type="radio" value="any" id="responses_any_radio" checked="checked" onClick="AtLeastButtonChecked();" />
	          <label for="responses_any_radio">Any</label>
	        </p>
	        <p>
	          <input name="responses_option" type="radio" value="none" id="responses_none_radio" onClick="AtLeastButtonChecked();" />
	          <label for="responses_none_radio">No Respones</label>
	        </p>
	      	<p>
	        <input name="responses_option" type="radio" value="select" id="responses_at_least_radio" onClick="AtLeastButtonChecked();" />
	        <label for="responses_at_least_radio">At least:</label>
	        <select name="responses_select" id="responses_select" onChange="selectlabel('responses_at_least_radio');" disabled="true">
		         <option value="1" selected="selected">1</option>
				 <option value="2">2</option>
				 <option value="3">3</option>
				 <option value="4">4</option>
				 <option value="5">5</option>
				 <option value="10">10</option>
				 <option value="20">20</option>
				 <option value="30">30</option>
			</select> Responses
      </p>
    	</td>
  </tr>
  <tr>
    	<td><h2>Region </h2>
		      <p>
		        <input name="region_option" type="radio" id="region_any_radio" value="any" checked="checked" onClick="RegionButtonChecked();" />
		        <label for="region_any_radio">Any</label>
		      </p>
		      <p>
	        <input name="region_option" type="radio" value="select" id="region_select_radio" onClick="RegionButtonChecked();" />
	        <label for="region_select_radio">Select:</label>
	        <select name="region_select" id="region_select" onChange="selectlabel('region_select_radio');">
	          <option value="" selected="selected">All</option>
		          <?php
						$selectsql = "select distinct store_region from projects order by store_region asc";
						$result = mysql_query($selectsql);
						if (!$result){error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error());}
						while ($row = mysql_fetch_object($result))
						{if ($row->store_region==0){continue;}else{print "<option value=\"$row->store_region\">$row->store_region</option>";}}
					?>
        	</select>
			
		      </p>
		      <p>
		        <input name="region_option" type="radio" value="specify" id="region_specify_radio" onClick="RegionButtonChecked();" />
		        <label for="region_specify_radio">Specify:</label>
		        <input type="text" name="region_specify" id="region_specify" size="10" disabled="true" />
		      </p>	
		</td>
	    <td>
	    	<h2>District </h2>
	      	<p>
	        <input name="district_option" type="radio" id="district_any_radio" value="any" checked="checked" onClick="DistrictButtonChecked();" />
	        <label for="district_any_radio">Any</label>
		    </p>
			<p>
	        <input name="district_option" type="radio" value="select" id="district_select_radio" onClick="DistrictButtonChecked();" />
	        <label for="district_select_radio">Select:</label>
	        <select name="district_select" id="district_select" onChange="selectlabel('district_select_radio');">
	        <option value="" selected="selected">All</option>
	        <?php
				$selectsql = "select distinct store_district from projects order by store_district asc";
				$result = mysql_query($selectsql);
				if (!$result){error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error());}
				while ($row = mysql_fetch_object($result))
				{if ($row->store_district==0){continue;}else{print "<option value=\"$row->store_district\">$row->store_district</option>";}}
			?>
	        </select>
	      	</p>
	      	<p>
	        <input name="district_option" type="radio" value="specify" id="district_specify_radio" onClick="DistrictButtonChecked();" />
	        <label for="district_specify_radio">Specify:</label>
	        <input type="text" name="district_specify" id="district_specify" size="10" disabled="true" />
	      </p>
		</td>
	    <td>
	    	<h2>Store</h2>
		    <p>
		      <input name="store_option" type="radio" id="store_any_radio" value="any" checked="checked" onClick="StoreButtonChecked();" />
		      <label for="store_any_radio">Any</label>
		    </p>
		    <p>
		      <input name="store_option" type="radio" value="select" id="store_select_radio" onClick="StoreButtonChecked();" />
		      <label for="store_select_radio">Select:</label>
		      <select name="store_select" id="store_select" onChange="selectlabel('store_select_radio');">
		        <option value="" selected="selected">All</option>
		        <?php
					$selectsql = "select distinct store_number from projects order by store_number asc";
					$result = mysql_query($selectsql);
					if (!$result){error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error());}
					while ($row = mysql_fetch_object($result))
					{if ($row->store_number==0){continue;}else{print "<option value=\"$row->store_number\">$row->store_number</option>";}}
				?>
		      </select>
			  </p>
		    <p>
		      <input name="store_option" type="radio" value="specify" id="store_specify_radio" onClick="StoreButtonChecked();" />
		      <label for="store_specify_radio">Specify:</label>
		      <input type="text" name="store_specify" id="store_specify" size="10" disabled="true" />
		    </p>

		    <p>
		      <input name="store_option" type="radio" value="range" id="store_range_radio" onClick="StoreButtonChecked();" />
		      <label for="store_range_radio">Range:</label>
		      <input type="text" name="store_range" id="store_range" size="10" disabled="true" />
		    </p>
		</td>
	</tr>

  <tr>
  	  <td>
		<h2>Filter By State</h2>
          <select name="store_state" id="store_state">
            <option value="" selected="selected">All States</option>
            <?php
					$selectsql = "select distinct sitestate from projects order by sitestate asc";
					$result = mysql_query($selectsql);
					if (!$result){error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error());}
					while ($row = mysql_fetch_object($result))
					{if ($row->sitestate == ""){continue;}else{print "<option value=\"$row->sitestate\">$row->sitestate</option>";}}
				?>
          </select>
						
	  </td>
      <td>
	  	<h2>Output Columns </h2>
			<ul id="column">
			  <li id="column_1">Fixture Request Date</li>
			  <li id="column_2">Fixture Request Filled</li>
			  <li id="column_3">Store</li>
			  <li id="column_4">Store Name</li>
			  <li id="column_5">Requested By</li>
			  <li id="column_6">Fixture Type</li>
			  <li id="column_7">Fixture</li>
              <li id="column_8">Qty</li>
			  <li id="column_9">Order Status</li>	
			  <li id="column_10">Shipment Date</li>
			  <li id="column_11">In Store Date</li>
		  			  
			</ul>
			<script type="text/javascript" language="javascript">
			  Sortable.create('column',{ghosting:true,constraint:false});
			</script>
			<input name="colkeys" type="hidden" />
		</td>
		<td>
		<h2>Sort by</h2>
		<p>
		<select name="sort_by1">
			<option value="rt_freqs.creation" selected="selected">Fixture Request Date</option>
			<option value="rt_freqs.request_filled">Fixture Request Filled</option>
			<option value="projects.store_number">Store</option>
			<option value="projects.sitename">Store Name</option>
			<option value="users.fullname">Requested By</option>
			<option value="fixture_key.cat">Fixture Type</option>
			<option value="fixture_key.name">Fixture</option>
			<option value="rt_freqs.order_status">Order Status</option>
			<option value="rt_freqs.actual_ship">Shipment Date</option>
			<option value="rt_freqs.actual_recv">In Store Date</option>
		</select><br />
		<input type="radio" name="sort_dir1" value="asc" id="sort_asc1" />
		<label for="sort_asc">Low to High (A to Z, 0 to 9)</label>
		&nbsp;&nbsp;<br />
		<input type="radio" name="sort_dir1" value="desc" id="sort_desc1" checked="checked" />
		<label for="sort_desc">High to Low (Z to A, 9 to 0)</label>
		</p>
		<br />
		<p>
		<select name="sort_by2">
			<option value="rt_freqs.creation">Request Date</option>
			<option value="rt_freqs.status" selected="selected">Status</option>
			<option value="rt_freqs.type">Type</option>
			<option value="rt_freqs.urgency">Urgency</option>
			<option value="projects.sitename">Location</option>
			<option value="projects.sitecity">City</option>
			<option value="projects.sitestate">State</option>
			<option value="projects.store_number">Store Number</option>
			<option value="projects.store_district">Store District</option>
			<option value="projects.store_region">Store Region</option>
		</select><br />
		<input type="radio" name="sort_dir2" value="asc" id="sort_asc2" checked="checked" />
		<label for="sort_asc">Low to High (A to Z, 0 to 9)</label>
		&nbsp;&nbsp;<br />
		<input type="radio" name="sort_dir2" value="desc" id="sort_desc2" />
		<label for="sort_desc">High to Low (Z to A, 9 to 0)</label>
		</p>
        <br />
		<p>
        <input type="radio" name="output" value="normal" id="out_normal" checked="checked" />
		<label for="out_normal">Normal</label>
		&nbsp;&nbsp;<br />
		<input type="radio" name="output" value="xls" id="out_xls" />
		<label for="out_xls">Excel</label>
		</p>        
		</td>
	</tr>
</table>
<input type="submit" value="Generate Report" onClick="report_build();" />
</form>
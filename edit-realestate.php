<script>
	$(function(){$("#approval_date").datepicker({showOn:"button", buttonImage:"images/calendar.jpeg", buttonImageOnly:true, showAnim:"slideDown"});});
</script>
<script>
	$(function(){$("#opening_date").datepicker({showOn:"button", buttonImage:"images/calendar.jpeg", buttonImageOnly:true, showAnim:"slideDown"});});
</script>
<script>
	$(function(){$("#remodel_date").datepicker({showOn:"button", buttonImage:"images/calendar.jpeg", buttonImageOnly:true, showAnim:"slideDown"});});
</script>
<script>
	$(function(){$("#expiry_date").datepicker({showOn:"button", buttonImage:"images/calendar.jpeg", buttonImageOnly:true, showAnim:"slideDown"});});
</script>
<script>
	$(function(){$("#mall_sales_psf_date").datepicker({showOn:"button", buttonImage:"images/calendar.jpeg", buttonImageOnly:true, showAnim:"slideDown"});});
</script>

<?php //edit-realestate.php

include('little-helpers.php');

if (!isset($editok)):

	$section = $_GET['section'];
	switch ($section)
	{
		case "realestate":
		
			$sql = "SELECT * FROM realestate WHERE project_id={$id}";
			$result = mysql_query($sql);
			if (!$result) {
				error("A databass error has occured.\\n".mysql_error());
			}
			$row = mysql_fetch_array($result);
			
			print "<div id=\"content\">";
			print "<h1>:: Edit real estate data</h1>";
			print "<div class=\"databox\">";
			print "<form name=\"editrealestate\" method=\"post\" action=\"$PHP_SELF?page=edit-realestate&id=$id&section=realestate\">";
			print "<table align=\"center\" cellpadding=\"1\" cellspacing=\"1\" class=\"litezone\" width=\"100%\">";
			
			print "<tr>";
			print "<td class=\"col1\">Market Area</td>";
			print "<td class=\"edit_windows\"><input name=\"market_area\" type=\"text\" class=\"files\" size=\"60\" maxlength=\"100\" value=\"{$row['market_area']}\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">Developer</td>";
			print "<td class=\"edit_windows\"><input name=\"developer\" type=\"text\" class=\"files\" size=\"60\" maxlength=\"100\" value=\"{$row['developer']}\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">Gross Sqft</td>";
			print "<td class=\"edit_windows\"><input name=\"gross_sqft\" type=\"text\" class=\"files\" size=\"12\"  value=\"{$row['gross_sqft']}\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">Sales Area Sqft</td>";
			print "<td class=\"edit_windows\"><input name=\"sales_area_sqft\" type=\"text\" class=\"files\" size=\"12\"  value=\"{$row['sales_area_sqft']}\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">Sales Projection</td>";
			print "<td class=\"edit_windows\"><input name=\"sales_projection\" type=\"text\" class=\"files\" size=\"12\"  value=\"{$row['sales_projection']}\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">REC Approve Date</td>";
			print "<td class=\"edit_windows\"><input id=\"approval_date\" name=\"approval_date\" type=\"text\" class=\"files\" size=\"12\"  value=\"".dateFormat($row['approval_date'])."\"/></td>";
			print "</tr>";
    		
			print "<tr>";
			print "<td class=\"col1\">Original Opening</td>";
			print "<td class=\"edit_windows\"><input id=\"opening_date\" name=\"opening_date\" type=\"text\" class=\"files\" size=\"12\"  value=\"".dateFormat($row['opening_date'])."\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">Remodel Date</td>";
			print "<td class=\"edit_windows\"><input id=\"remodel_date\" name=\"remodel_date\" type=\"text\" class=\"files\" size=\"12\"  value=\"".dateFormat($row['remodel_date'])."\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">Lease Expires</td>";
			print "<td class=\"edit_windows\"><input id=\"expiry_date\" name=\"expiry_date\" type=\"text\" class=\"files\" size=\"12\"  value=\"".dateFormat($row['expiry_date'])."\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">TY TTM Sales</td>";
			print "<td class=\"edit_windows\"><input name=\"ty_ttm_sales\" type=\"text\" class=\"files\" size=\"12\"  value=\"{$row['ty_ttm_sales']}\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">TY Sales PSF</td>";
			print "<td class=\"edit_windows\"><input name=\"ty_sales_psf\" type=\"text\" class=\"files\" size=\"12\"  value=\"{$row['ty_sales_psf']}\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">TY TTM Op Inc</td>";
			print "<td class=\"edit_windows\"><input name=\"ty_ttm_op_inc\" type=\"text\" class=\"files\" size=\"12\"  value=\"{$row['ty_ttm_op_inc']}\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">TY TTM CF</td>";
			print "<td class=\"edit_windows\"><input name=\"ty_ttm_cf\" type=\"text\" class=\"files\" size=\"12\"  value=\"{$row['ty_ttm_cf']}\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">LY TTM Sales</td>";
			print "<td class=\"edit_windows\"><input name=\"ly_ttm_sales\" type=\"text\" class=\"files\" size=\"12\"  value=\"{$row['ly_ttm_sales']}\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">LY Sales PSF</td>";
			print "<td class=\"edit_windows\"><input name=\"ly_sales_psf\" type=\"text\" class=\"files\" size=\"12\"  value=\"{$row['ly_sales_psf']}\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">LY TTM Op Inc</td>";
			print "<td class=\"edit_windows\"><input name=\"ly_ttm_op_inc\" type=\"text\" class=\"files\" size=\"12\"  value=\"{$row['ly_ttm_op_inc']}\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">LY TTM CF</td>";
			print "<td class=\"edit_windows\"><input name=\"ly_ttm_cf\" type=\"text\" class=\"files\" size=\"12\"  value=\"{$row['ly_ttm_cf']}\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">Sales % &Delta; Vs. LY </td>";
			print "<td class=\"edit_windows\"><input name=\"sales_percentage_vs_ly\" type=\"text\" class=\"files\" size=\"12\"  value=\"{$row['sales_percentage_vs_ly']}\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">TTM R&O</td>";
			print "<td class=\"edit_windows\"><input name=\"ttm_r_and_o\" type=\"text\" class=\"files\" size=\"12\"  value=\"{$row['ttm_r_and_o']}\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">R&O % Sales</td>";
			print "<td class=\"edit_windows\"><input name=\"r_and_o_percent_sales\" type=\"text\" class=\"files\" size=\"12\"  value=\"{$row['r_and_o_percent_sales']}\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">Tenant Allowance PSF</td>";
			print "<td class=\"edit_windows\"><input name=\"tenant_allowance_psf\" type=\"text\" class=\"files\" size=\"12\"  value=\"{$row['tenant_allowance_psf']}\"/></td>";
			print "</tr>";
			
			print "<td align=\"right\"><p><input name=\"editok\" type=\"submit\" class=\"files\" value=\"Save\"/></p></td>";
			print "<td><p><input name=\"button\" type=\"button\" class=\"files\" value=\"Cancel\" onClick=\"history.back()\"/></p></td>";
			
			print "</table>";
			print "</form>";
			print "</div>";
			print "</div>";
			
		break;
		
		case "center_info":
		
			$sql = "SELECT * FROM re_centerinfo WHERE project_id={$id}";
			$result = mysql_query($sql);
			if (!$result) {
				error("A databass error has occured.\\n".mysql_error());
			}
			$row = mysql_fetch_array($result);
			
			print "<div id=\"content\">";
			print "<h1>:: Edit project</h1>";
			print "<div class=\"databox\">";
			print "<form name=\"editrealestate\" method=\"post\" action=\"$PHP_SELF?page=edit-realestate&id=$id&section=center_info\">";
			print "<table align=\"center\" cellpadding=\"1\" cellspacing=\"1\" class=\"litezone\" width=\"100%\">";
			
			$ctq = "SELECT DISTINCT center_type FROM re_centerinfo";
			$ctrs = mysql_query($ctq);
			if (!$ctrs) {error('A database error has occured.\\n'.mysql_error());}
			print "<tr>";
			print "<td class=\"col1\">Center Type</td>";
			print "<td><select name=\"center_type\" class='files'>";
			while($ctrow = mysql_fetch_assoc($ctrs))
			{
				if ($ctrow['center_type'] == $row['center_type'])
				{
					print "<option value=\"{$ctrow['center_type']}\" selected=\"true\">{$ctrow['center_type']}</option>";
				}
				else
				{
					print "<option value=\"{$ctrow['center_type']}\">{$ctrow['center_type']}</option>";
				}
			}
			print "</select></td>";
			print "</tr>";
			
			$cnq = "SELECT DISTINCT cluster_name FROM re_centerinfo";
			$cnrs = mysql_query($cnq);
			if (!$cnrs) {error('A database error has occured.\\n'.mysql_error());}
			print "<tr>";
			print "<td class=\"col1\">Cluster Name</td>";
			print "<td><select name=\"cluster_name\" class='files'>";
			while($cnrow = mysql_fetch_assoc($cnrs))
			{
				if ($cnrow['cluster_name'] == $row['cluster_name'])
				{
					print "<option value=\"{$cnrow['cluster_name']}\" selected=\"true\">{$cnrow['cluster_name']}</option>";
				}
				else
				{
					print "<option value=\"{$cnrow['cluster_name']}\">{$cnrow['cluster_name']}</option>";
				}
			}
			print "</select></td>";
			print "</tr>";
			
			$crq = "SELECT DISTINCT center_rank FROM re_centerinfo";
			$crrs = mysql_query($crq);
			if (!$crrs) {error('A database error has occured.\\n'.mysql_error());}
			print "<tr>";
			print "<td class=\"col1\">Center Rank</td>";
			print "<td><select name=\"center_rank\" class='files'>";
			while($crrow = mysql_fetch_assoc($crrs))
			{
				if ($crrow['center_rank'] == $row['center_rank'])
				{
					print "<option value=\"{$crrow['center_rank']}\" selected=\"true\">{$crrow['center_rank']}</option>";
				}
				else
				{
					print "<option value=\"{$crrow['center_rank']}\">{$crrow['center_rank']}</option>";
				}
			}
			print "</select></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">Mall GLA</td>";
			print "<td class=\"edit_windows\"><input name=\"mall_gla\" type=\"text\" class=\"files\" size=\"12\"  value=\"{$row['mall_gla']}\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">Shop GLA</td>";
			print "<td class=\"edit_windows\"><input name=\"shop_gla\" type=\"text\" class=\"files\" size=\"12\"  value=\"{$row['shop_gla']}\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">Mall Sales PSF</td>";
			print "<td class=\"edit_windows\"><input name=\"mall_sales_psf\" type=\"text\" class=\"files\" size=\"12\"  value=\"{$row['mall_sales_psf']}\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">Mall Sales PSF Date</td>";
			print "<td class=\"edit_windows\"><input id=\"mall_sales_psf_date\" name=\"mall_sales_psf_date\" type=\"text\" class=\"files\" size=\"12\"  value=\"".dateFormat($row['mall_sales_psf_date'])."\"/></td>";
			print "</tr>";
			
			print "<td align=\"right\"><p><input name=\"editok\" type=\"submit\" class=\"files\" value=\"Save\"/></p></td>";
			print "<td><p><input name=\"button\" type=\"button\" class=\"files\" value=\"Cancel\" onClick=\"history.back()\"/></p></td>";
			
			print "</table>";
			print "</form>";
			print "</div>";
			print "</div>";
		
		break;
		
		case "strategy":
			
			$sql = "SELECT * FROM re_strategy WHERE project_id={$id}";
			$result = mysql_query($sql);
			if (!$result) {
				error("A databass error has occured.\\n".mysql_error());
			}
			$row = mysql_fetch_array($result);
			
			print "<div id=\"content\">";
			print "<h1>:: Edit project</h1>";
			print "<div class=\"databox\">";
			print "<form name=\"editrealestate\" method=\"post\" action=\"$PHP_SELF?page=edit-realestate&id=$id&section=strategy\">";
			print "<table align=\"center\" cellpadding=\"1\" cellspacing=\"1\" class=\"litezone\" width=\"100%\">";
			
			$rdwq = "SELECT DISTINCT rm_dm_wishlist FROM re_strategy";
			$rdwrs = mysql_query($rdwq);
			if (!$rdwrs) {error('A database error has occured.\\n'.mysql_error());}
			print "<tr>";
			print "<td class=\"col1\">RM/DM Wishlist</td>";
			print "<td><select name=\"rm_dm_wishlist\" class='files'>";
			while($rdwrow = mysql_fetch_assoc($rdwrs))
			{
				if ($rdwrow['rm_dm_wishlist'] == $row['rm_dm_wishlist'])
				{
					print "<option value=\"{$rdwrow['rm_dm_wishlist']}\" selected=\"true\">{$rdwrow['rm_dm_wishlist']}</option>";
				}
				else
				{
					print "<option value=\"{$rdwrow['rm_dm_wishlist']}\">{$rdwrow['rm_dm_wishlist']}</option>";
				}
			}
			print "</select></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">Top Tier \"A\"</td>";
			print "<td><select name=\"top_tier_a\" class='files'>";
			if ($row['top_tier_a'] == '0')
			{
				print "<option value='1'>Yes</option>";
				print "<option value='0' selected='true'>No</option>";
			}
			else
			{
				print "<option value='1' selected='true'>Yes</option>";
				print "<option value='0'>No</option>";
			}
			print "</select></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">Forever 21</td>";
			print "<td><select name=\"forever21\" class='files'>";
			if ($row['forever21'] == '0')
			{
				print "<option value='1'>Yes</option>";
				print "<option value='0' selected='true'>No</option>";
			}
			else
			{
				print "<option value='1' selected='true'>Yes</option>";
				print "<option value='0'>No</option>";
			}
			print "</select></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">State/Area</td>";
			print "<td class=\"edit_windows\"><input name=\"state_or_area\" type=\"text\" class=\"files\" size=\"10\"  value=\"{$row['state_or_area']}\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">Mills/Outlet</td>";
			print "<td class=\"edit_windows\"><input name=\"mills_or_outlet\" type=\"text\" class=\"files\" size=\"10\"  value=\"{$row['mills_or_outlet']}\"/></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">Street</td>";
			print "<td class=\"edit_windows\"><input name=\"street\" type=\"text\" class=\"files\" size=\"60\"  value=\"{$row['street']}\"/></td>";
			print "</tr>";
			
			print "<td align=\"right\"><p><input name=\"editok\" type=\"submit\" class=\"files\" value=\"Save\"/></p></td>";
			print "<td><p><input name=\"button\" type=\"button\" class=\"files\" value=\"Cancel\" onClick=\"history.back()\"/></p></td>";
			
			print "</table>";
			print "</form>";
			print "</div>";
			print "</div>";
			
		break;
		
		case "store_design":
		
		$sql = "SELECT * FROM re_storedesign WHERE project_id={$id}";
			$result = mysql_query($sql);
			if (!$result) {
				error("A databass error has occured.\\n".mysql_error());
			}
			$row = mysql_fetch_array($result);
			
			print "<div id=\"content\">";
			print "<h1>:: Edit project</h1>";
			print "<div class=\"databox\">";
			print "<form name=\"editrealestate\" method=\"post\" action=\"$PHP_SELF?page=edit-realestate&id=$id&section=store_design\">";
			print "<table align=\"center\" cellpadding=\"1\" cellspacing=\"1\" class=\"litezone\" width=\"100%\">";
			
			$spq = "SELECT DISTINCT store_prototype FROM re_storedesign";
			$sprs = mysql_query($spq);
			if (!$sprs) {error('A database error has occured.\\n'.mysql_error());}
			print "<tr>";
			print "<td class=\"col1\">Store Prototype</td>";
			print "<td><select name=\"store_prototype\" class='files'>";
			while($sprow = mysql_fetch_assoc($sprs))
			{
				if ($sprow['store_prototype'] == $row['store_prototype'])
				{
					print "<option value=\"{$sprow['store_prototype']}\" selected=\"true\">{$sprow['store_prototype']}</option>";
				}
				else
				{
					print "<option value=\"{$sprow['store_prototype']}\">{$sprow['store_prototype']}</option>";
				}
			}
			print "</select></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">Center Cashwrap</td>";
			print "<td><select name=\"center_cashwrap\" class='files'>";
			if ($row['center_cashwrap'] == '0')
			{
				print "<option value='1'>Yes</option>";
				print "<option value='0' selected='true'>No</option>";
			}
			else
			{
				print "<option value='1' selected='true'>Yes</option>";
				print "<option value='0'>No</option>";
			}
			print "</select></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">Entry Soffit</td>";
			print "<td><select name=\"entry_soffit\" class='files'>";
			if ($row['entry_soffit'] == '0')
			{
				print "<option value='1'>Yes</option>";
				print "<option value='0' selected='true'>No</option>";
			}
			else
			{
				print "<option value='1' selected='true'>Yes</option>";
				print "<option value='0'>No</option>";
			}
			print "</select></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">Cashwrap Soffit</td>";
			print "<td><select name=\"cashwrap_soffit\" class='files'>";
			if ($row['cashwrap_soffit'] == '0')
			{
				print "<option value='1'>Yes</option>";
				print "<option value='0' selected='true'>No</option>";
			}
			else
			{
				print "<option value='1' selected='true'>Yes</option>";
				print "<option value='0'>No</option>";
			}
			print "</select></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">Illuminated Accessory Wall</td>";
			print "<td><select name=\"illuminated_accessory_wall\" class='files'>";
			if ($row['illuminated_accessory_wall'] == '0')
			{
				print "<option value='1'>Yes</option>";
				print "<option value='0' selected='true'>No</option>";
			}
			else
			{
				print "<option value='1' selected='true'>Yes</option>";
				print "<option value='0'>No</option>";
			}
			print "</select></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">Illuminated Shoe Wall</td>";
			print "<td><select name=\"illuminated_shoe_wall\" class='files'>";
			if ($row['illuminated_shoe_wall'] == '0')
			{
				print "<option value='1'>Yes</option>";
				print "<option value='0' selected='true'>No</option>";
			}
			else
			{
				print "<option value='1' selected='true'>Yes</option>";
				print "<option value='0'>No</option>";
			}
			print "</select></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">Illuminated Window Walls</td>";
			print "<td><select name=\"illuminated_window_walls\" class='files'>";
			if ($row['illuminated_window_walls'] == '0')
			{
				print "<option value='1'>Yes</option>";
				print "<option value='0' selected='true'>No</option>";
			}
			else
			{
				print "<option value='1' selected='true'>Yes</option>";
				print "<option value='0'>No</option>";
			}
			print "</select></td>";
			print "</tr>";
			
			$ffq = "SELECT DISTINCT floor_finish FROM re_storedesign";
			$ffrs = mysql_query($ffq);
			if (!$ffrs) {error('A database error has occured.\\n'.mysql_error());}
			print "<tr>";
			print "<td class=\"col1\">Floor Finish</td>";
			print "<td><select name=\"floor_finish\" class='files'>";
			while($ffrow = mysql_fetch_assoc($ffrs))
			{
				if ($ffrow['floor_finish'] == $row['floor_finish'])
				{
					print "<option value=\"{$ffrow['floor_finish']}\" selected=\"true\">{$ffrow['floor_finish']}</option>";
				}
				else
				{
					print "<option value=\"{$ffrow['floor_finish']}\">{$ffrow['floor_finish']}</option>";
				}
			}
			print "</select></td>";
			print "</tr>";
			
			print "<tr>";
			print "<td class=\"col1\">Brite Store Makeup</td>";
			print "<td><select name=\"brite_store_makeup\" class='files'>";
			$enum = array('Partial', 'Full', 'None');
			foreach ($enum as $val)
			{
				if ($row['brite_store_makeup'] == $val)
				{
					print "<option value=\"{$val}\" selected=\"true\">{$val}</option>";
				}
				else
				{
					print "<option value=\"{$val}\">{$val}</option>";
				}
			}
			print "</select></td>";
			print "</tr>";
			
			print "<td align=\"right\"><p><input name=\"editok\" type=\"submit\" class=\"files\" value=\"Save\"/></p></td>";
			print "<td><p><input name=\"button\" type=\"button\" class=\"files\" value=\"Cancel\" onClick=\"history.back()\"/></p></td>";
			
			print "</table>";
			print "</form>";
			print "</div>";
			print "</div>";
		
		break;
	}
	
?>


<?php

else:

	dbConnect();
	
	$section = $_GET['section'];
	switch ($section)
	{
		case "realestate":
			$market_area = trim($market_area);
			$developer = trim($developer);
			$gross_sqft = str_replace(",","",trim($gross_sqft));
			if (!is_numeric($gross_sqft)) {
				error('Warning: Value entered for Gross Sqft is not numeric.');}
			$sales_area_sqft = str_replace(",","",trim($sales_area_sqft));
			if (!is_numeric($sales_area_sqft)) {
				error('Warning: Value entered for Sales Area Sqft is not numeric.');}
			$sales_projection = str_replace(",","",trim($sales_projection));
			if (!is_numeric($sales_projection)) {
				error('Warning: Value entered for Sales Projection is not numeric.');}
			if (($approval_date = strtotime($approval_date)) === false) {
				error('Warning: Value entered for REC Approval Date is not valid.');}
			$approval_date = date("Y-m-d", $approval_date);
			if (($opening_date = strtotime($opening_date)) === false) {
				error('Warning: Value entered for Original Opening is not valid.');}
			$opening_date = date("Y-m-d", $opening_date);
			if (($remodel_date = strtotime($remodel_date)) === false) {
				error('Warning: Value entered for Remodel Date is not valid.');}
			$remodel_date = date("Y-m-d", $remodel_date);
			if (($expiry_date = strtotime($expiry_date)) === false) {
				error('Warning: Value entered for Lease Expiry Date is not valid.');}
			$expiry_date = date("Y-m-d", $expiry_date);
			$ty_ttm_sales = str_replace(",","",trim($ty_ttm_sales));
			if (!is_numeric($ty_ttm_sales)) {
				error('Warning: Value entered for TY TTM Sales is not numeric.');}
			$ty_sales_psf = str_replace(",","",trim($ty_sales_psf));
			if (!is_numeric($ty_sales_psf)) {
				error('Warning: Value entered for TY Sales PSF is not numeric.');}
			$ty_ttm_op_inc = str_replace(",","",trim($ty_ttm_op_inc));
			if (!is_numeric($ty_ttm_op_inc)) {
				error('Warning: Value entered for TY TTM Op Inc is not numeric.');}
			$ty_ttm_cf = str_replace(",","",trim($ty_ttm_cf));
			if (!is_numeric($ty_ttm_cf)) {
				error('Warning: Value entered for TY TTM CF is not numeric.');}
			$ly_ttm_sales = str_replace(",","",trim($ly_ttm_sales));
			if (!is_numeric($ly_ttm_sales)) {
				error('Warning: Value entered for LY TTM Sales is not numeric.');}
			$ly_sales_psf = str_replace(",","",trim($ly_sales_psf));
			if (!is_numeric($ly_sales_psf)) {
				error('Warning: Value entered for LY Sales PSF is not numeric.');}
			$ly_ttm_op_inc = str_replace(",","",trim($ly_ttm_op_inc));
			if (!is_numeric($ly_ttm_op_inc)) {
				error('Warning: Value entered for LY TTM Op Inc is not numeric.');}
			$ly_ttm_cf = str_replace(",","",trim($ly_ttm_cf));
			if (!is_numeric($ly_ttm_cf)) {
				error('Warning: Value entered for LY TTM CF is not numeric.');}
			$sales_percentage_vs_ly = str_replace(",","",trim($sales_percentage_vs_ly));
			if (!is_numeric($sales_percentage_vs_ly)) {
				error('Warning: Value entered for Sales % Delta vs LY is not numeric.');}
			$ttm_r_and_o = str_replace(",","",trim($ttm_r_and_o));
			if (!is_numeric($ttm_r_and_o)) {
				error('Warning: Value entered for TTM R&O is not numeric.');}
			$r_and_o_percent_sales = str_replace(",","",trim($r_and_o_percent_sales));
			if (!is_numeric($r_and_o_percent_sales)) {
				error('Warning: Value entered for R&O % Sales is not numeric.');}
			$tenant_allowance_psf = str_replace(",","",trim($tenant_allowance_psf));
			if (!is_numeric($tenant_allowance_psf)) {
				error('Warning: Value entered for Tenant Allowance PSF is not numeric.');}
				
			$update_query = "UPDATE realestate SET developer='{$developer}', market_area='{$market_area}', gross_sqft='{$gross_sqft}', sales_area_sqft='{$sales_area_sqft}', approval_date='{$approval_date}', opening_date='{$opening_date}', remodel_date='{$remodel_date}', expiry_date='{$expiry_date}', sales_projection='{$sales_projection}', ty_ttm_sales='{$ty_ttm_sales}', ty_sales_psf='{$ty_sales_psf}', ty_ttm_op_inc='{$ty_ttm_op_inc}', ty_ttm_cf='{$ty_ttm_cf}', sales_percentage_vs_ly='{$sales_percentage_vs_ly}', ly_ttm_sales='{$ly_ttm_sales}', ly_sales_psf='{$ly_sales_psf}', ly_ttm_op_inc='{$ly_ttm_op_inc}', ly_ttm_cf='{$ly_ttm_cf}', ttm_r_and_o='{$ttm_r_and_o}', r_and_o_percent_sales='{$r_and_o_percent_sales}', tenant_allowance_psf='{$tenant_allowance_psf}' WHERE project_id='{$id}'";
		break;
			
		case "center_info":
		
			$mall_gla = str_replace(",","",trim($mall_gla));
			if (!is_numeric($mall_gla)) {
				error('Warning: Value entered for Mall GLA is not numeric.');}
			$shop_gla = str_replace(",","",trim($shop_gla));
			if (!is_numeric($shop_gla)) {
				error('Warning: Value entered for Shop GLA is not numeric.');}
			$mall_sales_psf = str_replace(",","",trim($mall_sales_psf));
			if (!is_numeric($mall_sales_psf)) {
				error('Warning: Value entered for Mall Sales PSF is not numeric.');}
			
			if (($mall_sales_psf_date = strtotime($mall_sales_psf_date)) === false) {
				error('Warning: Value entered for Mall Sales PSF Date is not valid.');}
			
			$mall_sales_psf_date = date('Y-m-d', $mall_sales_psf_date);
			
			$update_query = "UPDATE re_centerinfo SET  center_type='{$center_type}', cluster_name='{$cluster_name}', center_rank='{$center_rank}', mall_gla='{$mall_gla}', shop_gla='{$shop_gla}', mall_sales_psf='{$mall_sales_psf}', mall_sales_psf_date='{$mall_sales_psf_date}' WHERE project_id='{$id}'";
		break;
		
		case "strategy":
			$state_or_area = trim($state_or_area);
			$mills_or_outlet = trim($mills_or_outlet);
			$street = trim($street);
			
			$update_query = "UPDATE re_strategy SET  rm_dm_wishlist='{$rm_dm_wishlist}', top_tier_a='{$top_tier_a}', forever21='{$forever21}', state_or_area='{$state_or_area}', mills_or_outlet='{$mills_or_outlet}', street='{$street}' WHERE project_id='{$id}'";
		break;
		
		case "store_design":
		
			$update_query = "UPDATE re_storedesign SET  store_prototype='{$store_prototype}', center_cashwrap='{$center_cashwrap}', entry_soffit='{$entry_soffit}', cashwrap_soffit='{$cashwrap_soffit}', illuminated_accessory_wall='{$illuminated_accessory_wall}', illuminated_shoe_wall='{$illuminated_shoe_wall}', illuminated_window_walls='{$illuminated_window_walls}', floor_finish='{$floor_finish}', brite_store_makeup='{$brite_store_makeup}' WHERE project_id='{$id}'";
		break;
	}
	
	if (!mysql_query($update_query))
		error("A database error occured in proccessing your submission.\\n".mysql_error());

?>

<div id="content">
	<h1>:: Project edited successfully</h1>
	<div class="databox">
	<p>This project's information has been successfully modified.</p>
	<p><a href="<?=$_SERVER['PHP_SELF']?>?page=project&id=<?=$id?>">:: Return to this project page</a></p>
	</div>
</div>

<?php
endif;
?>
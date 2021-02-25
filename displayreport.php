<?php
error_reporting(E_ALL);
include("include/access.php");
include("little-helpers.php");

$dh = dbConnect();

if (array_key_exists('saved_report_id', $_GET))
	$rid = $_GET['saved_report_id'];
if (isset($rid) && $rid == "yes")
{
	$saved_query_id = $_POST['saved_query_id'];
	if (isset($saved_query_id) && $saved_query_id != "")
	{
		$r = mysql_query("SELECT * FROM re_reports WHERE id='{$saved_query_id}'");
		if (!$r) {exit;} 
		else
		{
			$d = mysql_fetch_assoc($r);
			$report_query = $d['report_query']; 
		}
	}
}
else
{

	$hasfilter = $_GET['filter'];

	if (isset($_GET['fields']))
		$selected_fields = unserialize(base64_decode($_GET['fields']));
	else if (isset($_POST['fields']))
		$selected_fields = $_POST['fields'];
	else
		$selected_fields = unserialize(base64_decode($_POST['flds']));
	
	$report_query = "SELECT projects.id, sitename";
	foreach ($selected_fields as $column)
	{
		$report_query = $report_query . ", " . $column;
	}	
	$report_query = $report_query . " FROM ";	
	$tables = getTableNames($selected_fields);
	foreach ($tables as $tablename)
	{
		$report_query = $report_query . $tablename . ", ";
	}
	$report_query = substr($report_query, 0, -2);
	$report_query = $report_query . " WHERE projects.id=";
	array_shift($tables);
	foreach ($tables as $tablename)
	{
		$report_query = $report_query . "{$tablename}.project_id AND projects.id=";
	}

	$report_query = substr($report_query, 0, -17);

	
	if ($hasfilter == 'yes')
	{
		if (isset($developer) && ($developer != ""))
		{
			$selection = ($developer_op == "like") ? 
						 (" AND realestate.developer LIKE '%".trim($developer)."%'") : 
						 (" AND realestate.developer='".trim($developer)."'");
		
			$report_query = $report_query . $selection;
		}
		if (isset($market_area) && ($market_area != ""))
		{
			$selection = ($market_area_op == "like") ? 
						 (" AND realestate.market_area LIKE '%".trim($market_area)."%'") : 
						 (" AND realestate.market_area='".trim($market_area)."'");
		
			$report_query = $report_query . $selection;
		}
		if (isset($state_or_area) && ($state_or_area != ""))
		{
			$selection = ($state_or_area_op == "like") ? 
						 (" AND re_strategy.state_or_area LIKE '%".trim($state_or_area)."%'") : 
						 (" AND re_strategy.state_or_area='".trim($state_or_area)."'");
		
			$report_query = $report_query . $selection;
		}
		if (isset($mills_or_outlet) && ($mills_or_outlet != ""))
		{
			$selection = ($mills_or_outlet_op == "like") ? 
						 (" AND re_strategy.mills_or_outlet LIKE '%".trim($mills_or_outlet)."%'") : 
						 (" AND re_strategy.mills_or_outlet='".trim($mills_or_outlet)."'");
		
			$report_query = $report_query . $selection;
		}
		if (isset($street) && ($street != ""))
		{
			$selection = ($street_op == "like") ? 
						 (" AND re_strategy.street LIKE '%".trim($street)."%'") : 
						 (" AND re_strategy.street='".trim($street)."'");
		
			$report_query = $report_query . $selection;
		}
		if (isset($center_type) && ($center_type != "") && ($center_type != "any"))
		{
			$ct = ($center_type == "(blank)") ? "" : $center_type;
			$report_query = $report_query . " AND re_centerinfo.center_type='".$ct."'";
		}
		if (isset($cluster_name) && ($cluster_name != "") && ($cluster_name != "any"))
		{
			$cn = ($cluster_name == "(blank)") ? "" : $cluster_name;
			$report_query = $report_query . " AND re_centerinfo.cluster_name='".$cn."'";
		}
		if (isset($center_rank) && ($center_rank != "") && ($center_rank != "any"))
		{
			$cr = ($center_rank == "(blank)") ? "" : $center_rank;
			$report_query = $report_query . " AND re_centerinfo.center_rank='".$cr."'";
		}
		if (isset($rm_dm_wishlist) && ($rm_dm_wishlist != "") && ($rm_dm_wishlist != "any"))
		{
			$rdw = ($rm_dm_wishlist == "(blank)") ? "" : $rm_dm_wishlist;
			$report_query = $report_query . " AND re_strategy.rm_dm_wishlist='".$rdw."'";
		}
		if (isset($store_prototype) && ($store_prototype != "") && ($store_prototype != "any"))
		{
			$sp = ($store_prototype == "(blank)") ? "" : $store_prototype;
			$report_query = $report_query . " AND re_storedesign.store_prototype='".$sp."'";
		}
		if (isset($floor_finish) && ($floor_finish != "") && ($floor_finish != "any"))
		{
			$ff = ($floor_finish == "(blank)") ? "" : $floor_finish;
			$report_query = $report_query . " AND re_storedesign.floor_finish='".$ff."'";
		}
		if (isset($top_tier_a) && ($top_tier_a != "") && ($top_tier_a != "any"))
			$report_query = $report_query . " AND re_strategy.top_tier_a='".$top_tier_a."'";
		if (isset($forever21) && ($forever21 != "") && ($forever21 != "any"))
			$report_query = $report_query . " AND re_strategy.forever21='".$forever21."'";
		if (isset($center_cashwrap) && ($center_cashwrap != "") && ($center_cashwrap != "any"))
			$report_query = $report_query . " AND re_storedesign.center_cashwrap='".$center_cashwrap."'";
		if (isset($entry_soffit) && ($entry_soffit != "") && ($entry_soffit != "any"))
			$report_query = $report_query . " AND re_storedesign.entry_soffit='".$entry_soffit."'";
		if (isset($cashwrap_soffit) && ($cashwrap_soffit != "") && ($cashwrap_soffit != "any"))
			$report_query = $report_query . " AND re_storedesign.cashwrap_soffit='".$cashwrap_soffit."'";
		if (isset($illuminated_accessory_wall) && ($illuminated_accessory_wall != "") && ($illuminated_accessory_wall != "any"))
			$report_query = $report_query . " AND re_storedesign.illuminated_accessory_wall='".$illuminated_accessory_wall."'";
		if (isset($illuminated_shoe_wall) && ($illuminated_shoe_wall != "") && ($illuminated_shoe_wall != "any"))
			$report_query = $report_query . " AND re_storedesign.illuminated_shoe_wall='".$illuminated_shoe_wall."'";
		if (isset($illuminated_window_walls) && ($illuminated_window_walls != "") && ($illuminated_window_walls != "any"))
			$report_query = $report_query . " AND re_storedesign.illuminated_window_walls='".$illuminated_window_walls."'";
		if (isset($brite_store_makeup) && ($brite_store_makeup != "") && ($brite_store_makeup != "any"))
			$report_query = $report_query . " AND re_storedesign.brite_store_makeup='".$brite_store_makeup."'";
		if (isset($approval_date_from) && $approval_date_from != "" && $approval_date_from != "From"
			&& isset($approval_date_to) && $approval_date_to != "" && $approval_date_to != "To")
		{
			$from = date("Y-m-d", strtotime($approval_date_from));
			$to = date("Y-m-d", strtotime($approval_date_to));
			$report_query = $report_query . " AND realestate.approval_date BETWEEN '".$approval_date_from."' AND '".$approval_date_to."'";
		}
		if (isset($opening_date_from) && $opening_date_from != "" && $opening_date_from != "From"
			&& isset($opening_date_to) && $opening_date_to != "" && $opening_date_to != "To")
		{
			$from = date("Y-m-d", strtotime($opening_date_from));
			$to = date("Y-m-d", strtotime($opening_date_to));
			$report_query = $report_query . " AND realestate.opening_date BETWEEN '".$opening_date_from."' AND '".$opening_date_to."'";
		}
		if (isset($remodel_date_from) && $remodel_date_from != "" && $remodel_date_from != "From" 
			&& isset($remodel_date_to) && $remodel_date_to != "" && $remodel_date_to != "To")
		{
			$from = date("Y-m-d", strtotime($remodel_date_from));
			$to = date("Y-m-d", strtotime($remodel_date_to));
			$report_query = $report_query . " AND realestate.remodel_date BETWEEN '".$remodel_date_from."' AND '".$remodel_date_to."'";
		}
		if (isset($expiry_date_from) && $expiry_date_from != "" && $expiry_date_from != "From"
			&& isset($expiry_date_to) && $expiry_date_to != "" && $expiry_date_to != "To")
		{
			$from = date("Y-m-d", strtotime($expiry_date_from));
			$to = date("Y-m-d", strtotime($expiry_date_to));
			$report_query = $report_query . " AND realestate.expiry_date BETWEEN '".$expiry_date_from."' AND '".$expiry_date_to."'";
		}
		if (isset($mall_sales_psf_date_from) && $mall_sales_psf_date_from != "" && $mall_sales_psf_date_from != "From" 
			&& isset($mall_sales_psf_date_to) && $mall_sales_psf_date_to != "" && $mall_sales_psf_date_to != "To")
		{
			$from = date("Y-m-d", strtotime($mall_sales_psf_date_from));
			$to = date("Y-m-d", strtotime($mall_sales_psf_date_to));
			$report_query = $report_query . " AND re_centerinfo.mall_sales_psf_date BETWEEN '".$mall_sales_psf_date_from."' AND '".$mall_sales_psf_date_to."'";
		}
		if (isset($gross_sqft) && $gross_sqft != "")
		{
			$gross_sqft = str_replace(",","",trim($gross_sqft));
			if (!is_numeric($gross_sqft))
				{error('Warning: Filter value entered for Gross Sqft is not numeric.');}
			$report_query = $report_query . " AND realestate.gross_sqft".$gross_sqft_op."{$gross_sqft}";
		}
		if (isset($sales_area_sqft) && $sales_area_sqft != "")
		{
			$sales_area_sqft = str_replace(",","",trim($sales_area_sqft));
			if (!is_numeric($sales_area_sqft))
				{error('Warning: Filter value entered for Sales Area Sqft is not numeric.');}
			$report_query = $report_query . " AND realestate.sales_area_sqft".$sales_area_sqft_op."{$sales_area_sqft}";
		}
		if (isset($sales_projection) && $sales_projection != "")
		{
			$sales_projection = str_replace(",","",trim($sales_projection));
			if (!is_numeric($sales_projection))
				{error('Warning: Filter value entered for Sales Projection is not numeric.');}
			$report_query = $report_query . " AND realestate.sales_projection".$sales_projection_op."{$sales_projection}";
		}
		if (isset($ty_ttm_sales) && $ty_ttm_sales != "")
		{
			$ty_ttm_sales = str_replace(",","",trim($ty_ttm_sales));
			if (!is_numeric($ty_ttm_sales))
				{error('Warning: Filter value entered for TY TTM Sales is not numeric.');}
			$report_query = $report_query . " AND realestate.ty_ttm_sales".$ty_ttm_sales_op."{$ty_ttm_sales}";
		}
		if (isset($ty_sales_psf) && $ty_sales_psf != "")
		{
			$ty_sales_psf = str_replace(",","",trim($ty_sales_psf));
			if (!is_numeric($ty_sales_psf))
				{error('Warning: Filter value entered for TY Sales PSF is not numeric.');}
			$report_query = $report_query . " AND realestate.ty_sales_psf".$ty_sales_psf_op."{$ty_sales_psf}";
		}
		if (isset($ty_ttm_op_inc) && $ty_ttm_op_inc != "")
		{
			$ty_ttm_op_inc = str_replace(",","",trim($ty_ttm_op_inc));
			if (!is_numeric($ty_ttm_op_inc))
				{error('Warning: Filter value entered for TY TTM Op Inc is not numeric.');}
			$report_query = $report_query . " AND realestate.ty_ttm_op_inc".$ty_ttm_op_inc_op."{$ty_ttm_op_inc}";
		}
		if (isset($ty_ttm_cf) && $ty_ttm_cf != "")
		{
			$ty_ttm_cf = str_replace(",","",trim($ty_ttm_cf));
			if (!is_numeric($ty_ttm_cf))
				{error('Warning: Filter value entered for TY TTM CF is not numeric.');}
			$report_query = $report_query . " AND realestate.ty_ttm_cf".$ty_ttm_cf_op."{$ty_ttm_cf}";
		}
		if (isset($sales_percentage_vs_ly) && $sales_percentage_vs_ly != "")
		{
			$sales_percentage_vs_ly = str_replace(",","",trim($sales_percentage_vs_ly));
			if (!is_numeric($sales_percentage_vs_ly))
				{error('Warning: Filter value entered for Sales % &Delta; vs. LY is not numeric.');}
			$report_query = $report_query . " AND realestate.sales_percentage_vs_ly".$sales_percentage_vs_ly_op."{$sales_percentage_vs_ly}";
		}
		if (isset($ly_ttm_sales) && $ly_ttm_sales != "")
		{
			$ly_ttm_sales = str_replace(",","",trim($ly_ttm_sales));
			if (!is_numeric($ly_ttm_sales))
				{error('Warning: Filter value entered for LY TTM Sales is not numeric.');}
			$report_query = $report_query . " AND realestate.ly_ttm_sales".$ly_ttm_sales_op."{$ly_ttm_sales}";
		}
		if (isset($ly_sales_psf) && $ly_sales_psf != "")
		{
			$ly_sales_psf = str_replace(",","",trim($ly_sales_psf));
			if (!is_numeric($ly_sales_psf))
				{error('Warning: Filter value entered for LY Sales PSF is not numeric.');}
			$report_query = $report_query . " AND realestate.ly_sales_psf".$ly_sales_psf_op."{$ly_sales_psf}";
		}
		if (isset($ly_ttm_op_inc) && $ly_ttm_op_inc != "")
		{
			$ly_ttm_op_inc = str_replace(",","",trim($ly_ttm_op_inc));
			if (!is_numeric($ly_ttm_op_inc))
				{error('Warning: Filter value entered for LY TTM Op Inc is not numeric.');}
			$report_query = $report_query . " AND realestate.ly_ttm_op_inc".$ly_ttm_op_inc_op."{$ly_ttm_op_inc}";
		}
		if (isset($ly_ttm_cf) && $ly_ttm_cf != "")
		{
			$ly_ttm_cf = str_replace(",","",trim($ly_ttm_cf));
			if (!is_numeric($ly_ttm_cf))
				{error('Warning: Filter value entered for LY TTM CF is not numeric.');}
			$report_query = $report_query . " AND realestate.ly_ttm_cf".$ly_ttm_cf_op."{$ly_ttm_cf}";
		}
		if (isset($ttm_r_and_o) && $ttm_r_and_o != "")
		{
			$ttm_r_and_o = str_replace(",","",trim($ttm_r_and_o));
			if (!is_numeric($ttm_r_and_o))
				{error('Warning: Filter value entered for TTM R&O is not numeric.');}
			$report_query = $report_query . " AND realestate.ttm_r_and_o".$ttm_r_and_o_op."{$ttm_r_and_o}";
		}
		if (isset($r_and_o_percent_sales) && $r_and_o_percent_sales != "")
		{
			$r_and_o_percent_sales = str_replace(",","",trim($r_and_o_percent_sales));
			if (!is_numeric($r_and_o_percent_sales))
				{error('Warning: Filter value entered for R&O % Sales is not numeric.');}
			$report_query = $report_query . " AND realestate.r_and_o_percent_sales".$r_and_o_percent_sales_op."{$r_and_o_percent_sales}";
		}
		if (isset($tenant_allowance_psf) && $tenant_allowance_psf != "")
		{
			$tenant_allowance_psf = str_replace(",","",trim($tenant_allowance_psf));
			if (!is_numeric($tenant_allowance_psf))
				{error('Warning: Filter value entered for Tenant Allowance PSF is not numeric.');}
			$report_query = $report_query . " AND realestate.tenant_allowance_psf".$tenant_allowance_psf_op."{$tenant_allowance_psf}";
		}
		if (isset($mall_gla) && $mall_gla != "")
		{
			$mall_gla = str_replace(",","",trim($mall_gla));
			if (!is_numeric($mall_gla))
				{error('Warning: Filter value entered for Mall GLA is not numeric.');}
			$report_query = $report_query . " AND re_centerinfo.mall_gla".$mall_gla_op."{$mall_gla}";
		}
		if (isset($shop_gla) && $shop_gla != "")
		{
			$shop_gla = str_replace(",","",trim($shop_gla));
			if (!is_numeric($shop_gla))
				{error('Warning: Filter value entered for Shop GLA is not numeric.');}
			$report_query = $report_query . " AND re_centerinfo.shop_gla".$shop_gla_op."{$shop_gla}";
		}
		if (isset($mall_sales_psf) && $mall_sales_psf != "")
		{
			$mall_sales_psf = str_replace(",","",trim($mall_sales_psf));
			if (!is_numeric($mall_sales_psf))
				{error('Warning: Filter value entered for Mall Sales PSF is not numeric.');}
			$report_query = $report_query . " AND re_centerinfo.mall_sales_psf".$mall_sales_psf_op."{$mall_sales_psf}";
		}
	}

	if (isset($save_query))
	{
		$uid = $_GET['user_id'];
		$pid = $_GET['project_id'];
		$reportnm = "";
		
		if (!isset($reportname) || $reportname == "")
			$reportnm = "no-name-".date('d/m/Y-H:i',time());
		else
			$reportnm = $reportname."-".date('m/d/Y-H:i',time());
		$rq = mysql_real_escape_string($report_query);
	
		$qs = "INSERT INTO re_reports (user_id, report_name, report_query) VALUES ('{$uid}','{$reportnm}','{$rq}')";
		$r = mysql_query($qs);
		if (!$r) {error('A database error has occured.\\n'.mysql_error());}
	}
}	


print '<!DOCTYPE HTML PUBLIC "-//WC3//DTD HTML 4.01 Transitional//EN"> 
<html> 
<head>
<script language="javascript" type="text/javascript" src="/jquery/jquery-1.2.2.pack.js"></script> 
<script language="javascript" type="text/javascript" src="/jquery/jquery.tablesorter.min.js"></script>
<script language="javascript" type="text/javascript">
	$(document).ready(function() 
		{ 
			$("#realestate").tablesorter(); 
		} 
	); 
</script>
</head>
<body>';


$rq_res = mysql_query($report_query);
if (!$rq_res) {error('A databass error has occured.\\n'.mysql_error());}
$no_of_results = mysql_num_rows($rq_res);
if ($no_of_results == 0) {error('No results returned. Try search again.');}
$report_data = mysql_fetch_assoc($rq_res);



print '<style type="text/css">
		table#realestate td { text-align:center; padding:2px; border-left:1px #ccc solid; border-top:1px #ccc solid;}
		table#realestate td.left { text-align:left; }
		table#realestate { font-size:10px; font-family:arial; border-right:1px #ccc solid; border-bottom:1px #ccc solid;}
		.red { color:#FF0000; }

/* tables */
table.tablesorter {
    font-family:arial;
    background-color: #CDCDCD;
    margin:10px 0pt 15px;
    font-size: 8pt;
    width: 100%;
    text-align: left;
}
table.tablesorter thead tr th, table.tablesorter tfoot tr th {
    background-color: #e6EEEE;
    border: 1px solid #FFF;
    font-size: 8pt;
    padding: 4px;
}
table.tablesorter thead tr .header {
    background-image: url(bg.gif);
    background-repeat: no-repeat;
    background-position: center right;
    cursor: pointer;
}
table.tablesorter tbody td {
    color: #3D3D3D;
    padding: 4px;
    background-color: #FFF;
    vertical-align: top;
}
table.tablesorter tbody tr.odd td {
    background-color:#F0F0F6;
}
table.tablesorter thead tr .headerSortUp {
    background-image: url(asc.gif);
}
table.tablesorter thead tr .headerSortDown {
    background-image: url(desc.gif);
}
table.tablesorter thead tr .headerSortDown, table.tablesorter thead tr .headerSortUp {
background-color: #8dbdd8;
}
		
		
		</style>';

if (isset($reportname) && $reportname != "")
	print "<h2>{$reportname}</h2>";

print '<table id="realestate" cellspacing="0" class="tablesorter"><thead><tr><th>Location Name</th>';
$columns = array_keys($report_data);
foreach ($columns as $col)
{
	if ($col == 'sitename' || $col == 'id')
	{ continue; }
	
	print "<th>".array_search($col,$colsmap)."</th>";
}
print '</tr>';
print '</thead>';
print '<tbody>';
do
{
	$id = $report_data['id'];
	
	print '<tr>';
	foreach ($columns as $col)
	{
		if ($col == 'sitename')
			print "<td class='left'><a href='/index.php?page=project&id={$id}' class='files'>".$report_data['sitename']."</a></td>";
		else if ($col == 'developer')
			print '<td class="left">'.$report_data['developer'].'</td>';
		else if ($col == 'market_area')
			print '<td class="left">'.$report_data['market_area'].'</td>';
		else if ($col == 'gross_sqft')
			print '<td>'.number_format((float)$report_data['gross_sqft']).'</td>';
		else if ($col == 'sales_area_sqft')
			print '<td>'.number_format((float)$report_data['sales_area_sqft']).'</td>';
		else if ($col == 'approval_date')
			print '<td>'.dateFormat($report_data['approval_date']).'</td>';
		else if ($col == 'opening_date')
			print '<td>'.dateFormat($report_data['opening_date']).'</td>';
		else if ($col == 'remodel_date')
			print '<td>'.dateFormat($report_data['remodel_date']).'</td>';
		else if ($col == 'expiry_date')
			print '<td>'.dateFormat($report_data['expiry_date']).'</td>';
		/*
		else if ($col == 'sales_projection')
			print '<td>'.isNegative(dollarFormat($report_data['sales_projection'])).'</td>';
		else if ($col == 'ty_ttm_sales')
			print '<td>'.isNegative(dollarFormat($report_data['ty_ttm_sales'])).'</td>';
		else if ($col == 'ty_sales_psf')
			print '<td>'.isNegative(dollarFormat($report_data['ty_sales_psf'])).'</td>';
		else if ($col == 'ty_ttm_op_inc')
			print '<td>'.isNegative(dollarFormat($report_data['ty_ttm_op_inc'])).'</td>';
		else if ($col == 'ty_ttm_cf')
			print '<td>'.isNegative(dollarFormat($report_data['ty_ttm_cf'])).'</td>';
		else if ($col == 'ly_ttm_sales')
			print '<td>'.isNegative(dollarFormat($report_data['ly_ttm_sales'])).'</td>';
		else if ($col == 'ly_sales_psf')
			print '<td>'.isNegative(dollarFormat($report_data['ly_sales_psf'])).'</td>';
		else if ($col == 'ly_ttm_op_inc')
			print '<td>'.isNegative(dollarFormat($report_data['ly_ttm_op_inc'])).'</td>';
		else if ($col == 'ly_ttm_cf')
			print '<td>'.isNegative(dollarFormat($report_data['ly_ttm_cf'])).'</td>';
		else if ($col == 'sales_percentage_vs_ly')
			print '<td>'.$report_data['sales_percentage_vs_ly']."%".'</td>';
		else if ($col == 'ttm_r_and_o')
			print '<td>'.isNegative(dollarFormat($report_data['ttm_r_and_o'])).'</td>';
		else if ($col == 'r_and_o_percent_sales')
			print '<td>'.$report_data['r_and_o_percent_sales']."%".'</td>';
		*/
		else if ($col == 'tenant_allowance_psf')
			print '<td>'.isNegative(dollarFormat($report_data['tenant_allowance_psf'])).'</td>';
		else if ($col == 'center_type')
			print '<td>'.$report_data['center_type'].'</td>';
		else if ($col == 'cluster_name')
			print '<td>'.$report_data['cluster_name'].'</td>';
		else if ($col == 'center_rank')
			print '<td>'.$report_data['center_rank'].'</td>';
		else if ($col == 'mall_gla')
			print '<td>'.number_format((float)$report_data['mall_gla']).'</td>';
		else if ($col == 'shop_gla')
			print '<td>'.number_format((float)$report_data['shop_gla']).'</td>';
		else if ($col == 'mall_sales_psf')
			print '<td>'.isNegative(dollarFormat($report_data['mall_sales_psf'])).'</td>';
		else if ($col == 'mall_sales_psf_date')
			print '<td>'.dateFormat($report_data['mall_sales_psf_date']).'</td>';
		else if ($col == 'rm_dm_wishlist')
			print '<td>'.$report_data['rm_dm_wishlist'].'</td>';
		else if ($col == 'top_tier_a')
		{
			$d = ($report_data['top_tier_a'] == 1) ? 'Yes' : 'No';
			print '<td>'.$d.'</td>';
		}
		else if ($col == 'forever21')
		{
			$d = ($report_data['forever21'] == 1) ? 'Yes' : 'No';
			print '<td>'.$d.'</td>';
		}
		else if ($col == 'state_or_area')
			print '<td>'.$report_data['state_or_area'].'</td>';
		else if ($col == 'mills_or_outlet')
			print '<td>'.$report_data['mills_or_outlet'].'</td>';
		else if ($col == 'street')
			print '<td>'.$report_data['street'].'</td>';
		else if ($col == 'store_prototype')
			print '<td>'.$report_data['store_prototype'].'</td>';
		else if ($col == 'center_cashwrap')
		{
			$d = ($report_data['center_cashwrap'] == 1) ? 'Yes' : 'No';
			print '<td>'.$d.'</td>';
		}
		else if ($col == 'entry_soffit')
		{
			$d = ($report_data['entry_soffit'] == 1) ? 'Yes' : 'No';
			print '<td>'.$d.'</td>';
		}
		else if ($col == 'cashwrap_soffit')
		{
			$d = ($report_data['cashwrap_soffit'] == 1) ? 'Yes' : 'No';
			print '<td>'.$d.'</td>';
		}
		else if ($col == 'illuminated_accessory_wall')
		{
			$d = ($report_data['illuminated_accessory_wall'] == 1) ? 'Yes' : 'No';
			print '<td>'.$d.'</td>';
		}
		else if ($col == 'illuminated_shoe_wall')
		{
			$d = ($report_data['illuminated_shoe_wall'] == 1) ? 'Yes' : 'No';
			print '<td>'.$d.'</td>';
		}
		else if ($col == 'illuminated_window_walls')
		{
			$d = ($report_data['illuminated_window_walls'] == 1) ? 'Yes' : 'No';
			print '<td>'.$d.'</td>';
		}
		else if ($col == 'floor_finish')
			print '<td>'.$report_data['floor_finish'].'</td>';
		else if ($col == 'brite_store_makeup')
			print '<td>'.$report_data['brite_store_makeup'].'</td>';
			
	}
	print '</tr>';
	
}
while ($report_data = mysql_fetch_assoc($rq_res));
print '</tbody>';
print '</table>';

dbClose($dh);

function getTableNames($fields)
{
	$q1 = "select * from realestate limit 1";
	$r1 = mysql_query($q1);
	if (!$r1) {error('A databass error has occured.\\n'.mysql_error());}
	$realestate = mysql_fetch_array($r1);
	
	$q2 = "select * from re_centerinfo limit 1";
	$r2 = mysql_query($q2);
	if (!$r2) {error('A databass error has occured.\\n'.mysql_error());}
	$centerinfo = mysql_fetch_array($r2);
	
	$q3 = "select * from re_storedesign limit 1";
	$r3 = mysql_query($q3);
	if (!$r3) {error('A databass error has occured.\\n'.mysql_error());}
	$storedesign = mysql_fetch_array($r3);
	
	$q4 = "select * from re_strategy limit 1";
	$r4 = mysql_query($q4);
	if (!$r4) {error('A databass error has occured.\\n'.mysql_error());}
	$strategy = mysql_fetch_array($r4);	
	
	$tables = array('projects');
	
	foreach ($fields as $column)
	{
		if (array_key_exists($column, $realestate) == true)
			$tables[] = 'realestate';
		else if (array_key_exists($column, $centerinfo) == true)
			$tables[] = 're_centerinfo';
		else if (array_key_exists($column, $storedesign) == true)
			$tables[] = 're_storedesign';
		else if (array_key_exists($column, $strategy) == true)
			$tables[] = 're_strategy';
	}
	
	return array_unique($tables);
}
print '</body></html>';

?>


<?php

ini_set('display_errors', 1);  
error_reporting(E_ALL);

$columns_map = array (
						"Str #" => "store_number",
						"Mall Name" => "sitename",
						"Developer" => "developer",
						"City" => "sitecity",
						"ST" => "sitestate",
						"ZIP Code" => "sitezip",
						"Market Area" => "market_area",
						"Gross SqFt (Lease)" => "gross_sqft",
						"Sales Area SqFt (Cnstr)" => "sales_area_sqft",
						"REC Approve Date" => "approval_date",
						"Original Opening" => "opening_date",
						"Remodel Date" => "remodel_date",
						"Lease Expires" => "expiry_date",
						"Sales Projection" => "sales_projection",
						"TY TTM Sales" => "ty_ttm_sales",
						"TY Sales PSF" => "ty_sales_psf",
						"TY TTM Op Inc" => "ty_ttm_op_inc",
						"TY TTM CF" => "ty_ttm_cf",
						"Sales % ? vs. LY" => "sales_percentage_vs_ly",
						"LY TTM Sales" => "ly_ttm_sales",
						"LY Sales PSF" => "ly_sales_psf",
						"LY TTM Op Inc" => "ly_ttm_op_inc",
						"LY TTM CF" => "ly_ttm_cf",
						"TTM R&O" => "ttm_r_and_o",
						"R&O % Sales" => "r_and_o_percent_sales",
						"Tenant Allowance PSF" => "tenant_allowance_psf",
						"Center Type" => "center_type",
						"Cluster Name" => "cluster_name",
						"Center Rank" => "center_rank",
						"Mall GLA" => "mall_gla",
						"Shop GLA" => "shop_gla",
						"Mall Sales PSF" => "mall_sales_psf",
						"Mall Sales PSF Date" => "mall_sales_psf_date",
						"RM / DM Wishlist" => "rm_dm_wishlist",
						"Top Tier \"A\"" => "top_tier_a",
						"Forever 21" => "forever21",
						"State/Area" => "state_or_area",
						"Mills/Outlet" => "mills_or_outlet",
						"Street" => "street",
						"Store Prototype" => "store_prototype",
						"Center Cashwrap (Y/N)" => "center_cashwrap",
						"Entry Soffit (Y/N)" => "entry_soffit",
						"Cashwrap Soffit (Y/N)" => "cashwrap_soffit",
						"Illuminated Accessory Wall (Y/N)" => "illuminated_accessory_wall",
						"Illuminated Shoe Wall (Y/N)" => "illuminated_shoe_wall",
						"Illuminated Window Walls (Y/N)" => "illuminated_window_walls",
						"Floor Finish" => "floor_finish",
						"Brite Store Make-Up" => "brite_store_makeup"
					 );


include("include/db.php");

dbConnect();

$fh = fopen("real_estate_master.csv", "r");

$fields = fgetcsv($fh, 0, ",");

foreach ($fields as $index => $field)
{
	print "{$index} => {$field}</br>";
}

while (($data = fgetcsv($fh, 0, ",")) !== false)
{
/*
	$store_number = $data[indexOf("Str #")];
	
	if (strcmp($store_number, "") != 0)
	{				
		$sql1 = "SELECT * FROM projects WHERE store_number like %{$store_number}";
		
		if (($result = mysql_query($sql1)) == false)
			die("Error when querying {$sql}</br>");
		$row = mysql_fetch_row($result);
		if (count($row) == 0)
			die("No results returned by {$sql1}</br>");
			
		print_r($row);
		die();
		
	}
	else
	{
	*/
		/*	
		//insert in projects table first
		$sql2 = "INSERT INTO projects ".
				"({$columns_map[\"Mall Name\"]}, {{$columns_map[\"Str #\"]}}, {$columns_map[\"City\"]}, {$columns_map[\"ST\"]}, {$columns_map[\"ZIP Code\"]}) ".
				"VALUES ".
				"('". $data[indexOf("Mall Name")] . "','" . $data[indexOf("Str #")] . "','" . $data[indexOf("City")] . "','" . $data[indexOf("ST")] . "','" . $data[indexOf("ZIP Code")] . "')";
		*/
	}
	
	/*
	$sql1 = "INSERT INTO realestate ".
			"(project_id, {$fields[2]}, {$fields[6]}, {$fields[7]}, {$fields[8]}, {$fields[9]}, {$fields[10]}, {$fields[11]}, {$fields[12]}, {$fields[13]}, {$fields[14]}, {$fields[15]}, {$fields[16]}, {$fields[17]}, {$fields[18]}, {$fields[19]}, {$fields[20]}, {$fields[21]}, {$fields[22]}, {$fields[23]}, {$fields[24]}, {$fields[25]}) ".
			"VALUES ".
			"('',)";
			
	$sql2 = "INSERT INTO re_centerinfo ".
			"(project_id, {$fields[26]}, {$fields[27]}, {$fields[28]}, {$fields[29]}, {$fields[30]}, {$fields[31]}, {$fields[32]}) ".
			"VALUES ".
			"('',)";
			
	$sql3 = "INSERT INTO re_strategy ".
			"(project_id, {$fields[33]}, {$fields[34]}, {$fields[35]}forever21, {$fields[36]}state_or_area, {$fields[37]}mills_or_outlet, {$fields[38]}street) ".
			"VALUES ".
			"('',)";
			
	$sql4 = "INSERT INTO re_storedesign ".
			"(project_id, store_prototype, center_cashwrap, entry_soffit, cashwrap_soffit, illuminated_accessory_wall, illuminated_shoe_wall, illuminated_window_walls, floor_finish, brite_store_makeup) ".
			"VALUES ".
			"('',)";
			*/
			
	//print_r($data);
}


function indexOf($key)
{
	global $columns_map;
	
	$index = -1;
	foreach ($columns_map as $k)
	{
		index++;
		if (strcmp($key, $k) == 0)
			break;
	}
	return $index;
}


?>
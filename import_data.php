<?php

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

$pic = 0;
$ric = 0;

$db = dbConnect();

$fh = fopen("real_estate_master.csv", "r");

$fields = fgetcsv($fh, 0, ",");

foreach ($fields as $index => $field)
{
	print "{$index} => {$field}</br>";
}

while (($data = fgetcsv($fh, 0, ",")) !== false)
{
	$store_number = $data[indexOf("Str #")];
	$store_number = str_pad($store_number, 4, "0", STR_PAD_LEFT);
	
	$project_id = 0;
	
	if (intval($store_number) > 0)
	{				
		$sql1 = "SELECT * FROM projects WHERE store_number = '{$store_number}'";
		
		if (($result = mysql_query($sql1)) == false)
		{
			die("Error when querying {$sql1}</br>");
			mysql_close($db);
		}
		$row = mysql_fetch_row($result);
		if (count($row) == 0)
		{
			die("No results returned by {$sql1}</br>");
			mysql_close($db);
		}
		
		$project_id = $row[0];
	}
	else
	{
		//insert in projects look in addproject
		$sql2 = "INSERT INTO projects ".
				"({$columns_map['Mall Name']}, {$columns_map['Str #']}, {$columns_map['City']}, {$columns_map['ST']}, {$columns_map['ZIP Code']}, chain, type_of_store, mannequin_style, project_status, companyarray, datetouched, dateadded, new_additions) ".
				"VALUES ".
				"('". addslashes($data[indexOf("Mall Name")]) . "','" . $data[indexOf("Str #")] . "','" . addslashes($data[indexOf("City")]) . "','" . $data[indexOf("ST")] . "','" . $data[indexOf("ZIP Code")] . "','1','Fashion Valley','CNL','real_estate','" . serialize("") . "','CURDATE()','CURDATE()','1')";
				
		$pic = $pic + 1;
				
		if (($result = mysql_query($sql2)) == false)
		{
			die("Error when querying {$sql2}</br>");
			mysql_close($db);
		}
				
		$query = "SELECT id FROM projects WHERE sitename='" . addslashes($data[indexOf("Mall Name")]) . "' AND store_number='" . $data[indexOf("Str #")] . "' AND sitecity='" . addslashes($data[indexOf("City")]) . "' AND sitestate='" . $data[indexOf("ST")] . "' AND sitezip='" . $data[indexOf("ZIP Code")] . "' AND chain='1' AND type_of_store='Fashion Valley' AND mannequin_style='CNL' AND project_status='real_estate' AND companyarray='" . serialize("") . "' AND new_additions='1'";
		
		if (($res = mysql_query($query)) == false)
		{
			die("Error when querying {$query}</br>");
			mysql_close($db);
		}
				
		$row = mysql_fetch_array($res);
		$project_id = $row['id'];
	}
	
	$sql3 = "INSERT INTO realestate ".
			"(project_id, {$columns_map['Developer']}, {$columns_map['Market Area']}, {$columns_map['Gross SqFt (Lease)']}, {$columns_map['Sales Area SqFt (Cnstr)']}, {$columns_map['REC Approve Date']}, {$columns_map['Original Opening']}, {$columns_map['Remodel Date']}, {$columns_map['Lease Expires']}, {$columns_map['Sales Projection']}, {$columns_map['TY TTM Sales']}, {$columns_map['TY Sales PSF']}, {$columns_map['TY TTM Op Inc']}, {$columns_map['TY TTM CF']}, {$columns_map['Sales % ? vs. LY']}, {$columns_map['LY TTM Sales']}, {$columns_map['LY Sales PSF']}, {$columns_map['LY TTM Op Inc']}, {$columns_map['LY TTM CF']}, {$columns_map['TTM R&O']}, {$columns_map['R&O % Sales']}, {$columns_map['Tenant Allowance PSF']}) ".
			"VALUES ".
			"('" . $project_id . "','";
			
			$developer = addslashes($data[indexOf("Developer")]);
			$market_area = addslashes($data[indexOf("Market Area")]);
			$gross_sqft =  (integer) numberFormat($data[indexOf("Gross SqFt (Lease)")]);
			$sales_area_sqft = (integer) numberFormat($data[indexOf("Sales Area SqFt (Cnstr)")]);
			$approval_date = dateFormat($data[indexOf("REC Approve Date")]);
			$orig_date = dateFormat($data[indexOf("Original Opening")]);
			$remodel_date = dateFormat($data[indexOf("Remodel Date")]);
			$expiry_date = dateFormat($data[indexOf("Lease Expires")]);
			$sales_projection = decimalFormat($data[indexOf("Sales Projection")]);
			$ty_ttm_sales = decimalFormat($data[indexOf("TY TTM Sales")]);
			$ty_sales_psf = decimalFormat($data[indexOf("TY Sales PSF")]);
			$ty_ttm_op_inc = decimalFormat($data[indexOf("TY TTM Op Inc")]);
			$ty_ttm_cf = decimalFormat($data[indexOf("TY TTM CF")]);
			$ly_ttm_sales = decimalFormat($data[indexOf("LY TTM Sales")]);
			$ly_sales_psf = decimalFormat($data[indexOf("LY Sales PSF")]);
			$ly_ttm_op_inc = decimalFormat($data[indexOf("LY TTM Op Inc")]);
			$ly_ttm_cf = decimalFormat($data[indexOf("LY TTM CF")]);
			$ttm_ro = decimalFormat($data[indexOf("TTM R&O")]);
			$allowance = decimalFormat($data[indexOf("Tenant Allowance PSF")]);
						
			$sql3 = $sql3 . $developer . "','" . $market_area . "','" . $gross_sqft . "','" . $sales_area_sqft . "','" . $approval_date . "','" . $orig_date . "','" . $remodel_date . "','" . $expiry_date . "','" . $sales_projection . "','" . $ty_ttm_sales . "','" . $ty_sales_psf . "','" . $ty_ttm_op_inc . "','" . $ty_ttm_cf . "','" . $data[indexOf("Sales % ? vs. LY")] . "','" . $ly_ttm_sales . "','" . $ly_sales_psf . "','" . $ly_ttm_op_inc . "','" . $ly_ttm_cf . "','" . $ttm_ro . "','" . $data[indexOf("R&O % Sales")] . "','" . $allowance . "')";
			
			$ric = $ric + 1;
			
			if (($result = mysql_query($sql3)) == false)
			{
				die("Error when querying {$sql3}</br>");
				mysql_close($db);
			}
		
			$sql4 = "INSERT INTO re_centerinfo ".
					"(project_id, {$columns_map['Center Type']}, {$columns_map['Cluster Name']}, {$columns_map['Center Rank']}, {$columns_map['Mall GLA']}, {$columns_map['Shop GLA']}, {$columns_map['Mall Sales PSF']}, {$columns_map['Mall Sales PSF Date']}) ".
					"VALUES ".
					"('" . $project_id . "','" . $data[indexOf("Center Type")] . "','" . $data[indexOf("Cluster Name")] . "','" . $data[indexOf("Center Rank")] . "','"; 
			
			$mall_gla = $data[indexOf("Mall GLA")];
			$mall_gla = numberFormat($mall_gla);
			
			$shop_gla = $data[indexOf("Shop GLA")];
			$shop_gla = numberFormat($shop_gla);
			
			$mall_sales_psf = $data[indexOf("Mall Sales PSF")];
			$mall_sales_psf = decimalFormat($mall_sales_psf);
			
			$mall_sales_psf_date = $data[indexOf("Mall Sales PSF Date")];
			$mall_sales_psf_date .= " 2010"; 
			$mall_sales_psf_date = dateFormat($mall_sales_psf_date);
			
			$sql4 = $sql4 . $mall_gla . "','" . $shop_gla . "','" . $mall_sales_psf . "','" . $mall_sales_psf_date . "')";
			
			
			if (($result = mysql_query($sql4)) == false)
			{
				die("Error when querying {$sql4}</br>");
				mysql_close($db);
			}
			
			$sql5 = "INSERT INTO re_strategy ".
			"(project_id, {$columns_map['RM / DM Wishlist']}, {$columns_map['Top Tier "A"']}, {$columns_map['Forever 21']}, {$columns_map['State/Area']}, {$columns_map['Mills/Outlet']}, {$columns_map['Street']}) ".
			"VALUES ".
			"('" . $project_id . "','";
			
			$wishlist = $data[indexOf("RM / DM Wishlist")];
			$wishlist = ($wishlist == "-0") ? "" : $wishlist;
				
			$top_tier = $data[indexOf("Top Tier \"A\"")];
			$top_tier = ($top_tier == "A") ? 1 : 0;
			
			$forever21 = $data[indexOf("Forever 21")];
			$forever21 = ($forever21 == "F21") ? 1 : 0;
			
			$state_area = $data[indexOf("State/Area")];
			$state_area = ($state_area == "-0") ? "" : $state_area;
			
			$mills_outlet = $data[indexOf("Mills/Outlet")];
			$mills_outlet = ($mills_outlet == "-0") ? "" : $mills_outlet;
			
			$street = $data[indexOf("Street")];
			$street = ($street == "-0") ? "" : $street;
			
			$sql5 = $sql5 . $wishlist . "','" . $top_tier . "','" . $forever21 . "','" . $state_area . "','" . $mills_outlet . "','" . $street . "')";
			
			
			if (($result = mysql_query($sql5)) == false)
			{
				die("Error when querying {$sql5}</br>");
				mysql_close($db);
			}
			
			
			$sql6 = "INSERT INTO re_storedesign ".
			"(project_id, {$columns_map['Store Prototype']}, {$columns_map['Center Cashwrap (Y/N)']}, {$columns_map['Entry Soffit (Y/N)']}, {$columns_map['Cashwrap Soffit (Y/N)']}, {$columns_map['Illuminated Accessory Wall (Y/N)']}, {$columns_map['Illuminated Shoe Wall (Y/N)']}, {$columns_map['Illuminated Window Walls (Y/N)']}, {$columns_map['Floor Finish']}, {$columns_map['Brite Store Make-Up']}) ".
			"VALUES ".
			"('" . $project_id . "','" . $data[indexOf("Store Prototype")] . "','";			
			
			$cntr_cashwrap = $data[indexOf("Center Cashwrap (Y/N)")];
			$cntr_cashwrap = ($cntr_cashwrap == "Yes") ? 1 : 0;
			
			$entry_soffit = $data[indexOf("Entry Soffit (Y/N)")];
			$entry_soffit = ($entry_soffit == "Yes") ? 1 : 0;
			
			$cashwrap_soffit = $data[indexOf("Cashwrap Soffit (Y/N)")];
			$cashwrap_soffit = ($cashwrap_soffit == "Yes") ? 1 : 0;
			
			$illum_access_wall = $data[indexOf("Illuminated Accessory Wall (Y/N)")];
			$illum_access_wall = ($illum_access_wall == "Yes") ? 1 : 0;
			
			$illum_shoe_wall = $data[indexOf("Illuminated Shoe Wall (Y/N)")];
			$illum_shoe_wall = ($illum_shoe_wall == "Yes") ? 1 : 0;
			
			$illum_window_wall = $data[indexOf("Illuminated Window Walls (Y/N)")];
			$illum_window_wall = ($illum_window_wall == "Yes") ? 1 : 0;
			
			$sql6 = $sql6 . $cntr_cashwrap . "','" . $entry_soffit . "','" . $cashwrap_soffit . "','" . $illum_access_wall . "','" . $illum_shoe_wall . "','" . $illum_window_wall . "','" . $data[indexOf("Floor Finish")] . "','" . $data[indexOf("Brite Store Make-Up")] . "')";
			
			
			if (($result = mysql_query($sql6)) == false)
			{
				die("Error when querying {$sql6}</br>");
				mysql_close($db);
			}
			
	//print "</br>{$sql3}</br></br>{$sql4}</br></br>{$sql5}</br></br>{$sql6}</br>";

}

print "\nTotal number of projects inserted into projects table:  {$pic}\n";
print "\nTotal number of entries in realestate table:  {$ric}\n";

mysql_close($db);


function dateFormat($str)
{
	if (($str == " ") || ($str == ""))
		return "";
		
	$return = date("Y-m-d", strtotime($str));
	
	return (($return === false) ? 0 : $return);
}

function numberFormat($str)
{
	$arr = str_split($str);
	$return = "";
		
	foreach ($arr as $element)
	{
		if (strcmp($element,"/") == 0)
			return 0;
		if ((strcmp($element,",") == 0) ||
			(strcmp($element," ") == 0) ||
			(strcmp($element,"-") == 0))
			continue;
			
		$return .= $element;
	}
	
	return $return;
}

function decimalFormat($str)
{
	$arr = str_split($str);
	$return = "";
	$neg_flag = 0;
	
	foreach ($arr as $element)
	{
		if (strcmp($element,"(") == 0)
			$neg_flag = 1;
			
		if ((strcmp($element,"$") == 0) || 
			(strcmp($element,",") == 0) ||
			(strcmp($element," ") == 0) ||
			(strcmp($element,"(") == 0) ||
			(strcmp($element,")") == 0) ||
			(strcmp($element,"-") == 0))
			continue;
			
		$return .= $element;
	}
	
	if ($return == "")
		return 0;
	
	if ($neg_flag == 1)
		$return = "-" . $return;
		
	if (stripos($return, ".") === false)
		$return .= ".00";
		
	return $return;
}

function indexOf($key)
{	
	global $columns_map;
	
	$index = -1;
	foreach ($columns_map as $k => $v)
	{
		$index++;
		if (strcmp($key, $k) == 0)
			break;	
	}
	return $index;
}


?>
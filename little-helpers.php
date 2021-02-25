<?php
date_default_timezone_set('America/Los_Angeles');
$colsmap = array (	
						"Developer" => "developer",
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
						"Sales % &Delta; vs. LY" => "sales_percentage_vs_ly",
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

	function is_mysql_date( $str ){ 
	
	if(!strstr($str, '-')){
    return FALSE;
    }
	$stamp = strtotime( $str ); 
    if (!is_numeric($stamp)) 
        return FALSE; 
    $month = date( 'm', $stamp ); 
    $day   = date( 'd', $stamp ); 
    $year  = date( 'Y', $stamp ); 
    if (checkdate($month, $day, $year)) 
        return TRUE; 
    return FALSE; 
    }

					 
	function dollarFormat($str)
	{
		$arr = array();
		$tmp = str_split($str);
		$neg_flag = 0;
		
		if ($tmp[0] == "-")
		{
			$neg_flag = 1;
			array_shift($tmp);
		}
		
		for ($i=0; $i<3; $i++)
		{
			array_unshift($arr, array_pop($tmp));
		}
		
		$count = 0;
		while (count($tmp) > 0)
		{
			if ($count == 3)
			{
				array_unshift($arr, ",");
				$count = 0;
			}
			array_unshift($arr, array_pop($tmp));
			$count++;			
		}
		
		if ($arr[0] == ",")
		{
			array_shift($arr);
		}
		
		array_unshift($arr, "$");
		
		if ($neg_flag == 1)
		{
			array_unshift($arr, "(");
			array_push($arr, ")");
		}
		return implode("", $arr);
	}
	
	function dateFormat($str)
	{
		if ($str == "0000-00-00")
			//return 'TBD';
			return '';
		if (empty($str)) {
			//return 'TBD';
			return '';
		}
		
		if (is_mysql_date($str)) {
			return date("m/d/Y", strtotime($str));	
		} else  {
			return $str;
		}
		
		
		
	}
	
	function isNegative($dollar_amount)
	{
		if (stripos($dollar_amount, "(") !== false)
			return "<span class=\"red\">{$dollar_amount}</span>";
		
		return $dollar_amount;
	}
	
	function formatFileSize($bytes) 
	{
        if(!empty($bytes)) 
        {
            $s = array('bytes', 'kb', 'MB', 'GB', 'TB', 'PB');
            $e = floor(log($bytes)/log(1024));
 
            return sprintf('%.2f '.$s[$e], ($bytes/pow(1024, floor($e)))); 
        }
   }
	
?>
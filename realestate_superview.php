<?php

$fields = array("developer",
						"market_area",
						"gross_sqft_plan",
						"sales_area_sqft",
						"approval_date",
						"opening_date",
						"remodel_date",
						"expiry_date",
						"sales_projection",
						"ty_ttm_sales",
						"ty_sales_psf",
						"ty_ttm_op_inc",
						"ty_ttm_cf",
						"sales_percentage_vs_ly",
						"ly_ttm_sales",
						"ly_sales_psf",
						"ly_ttm_op_inc",
						"ly_ttm_cf",
						"ttm_r_and_o",
						"r_and_o_percent_sales",
						"tenant_allowance_psf",
						"center_type",
						"cluster_name",
						"center_rank",
						"mall_gla",
						"shop_gla",
						"mall_sales_psf",
						"mall_sales_psf_date",
						"rm_dm_wishlist",
						"top_tier_a",
						"forever21",
						"state_or_area",
						"mills_or_outlet",
						"street",
						"store_prototype",
						"center_cashwrap",
						"entry_soffit",
						"cashwrap_soffit",
						"illuminated_accessory_wall",
						"illuminated_shoe_wall",
						"illuminated_window_walls",
						"floor_finish",
						"brite_store_makeup"
						);

$columns = serialize($fields);
$columns = base64_encode($columns);
$postto = "/displayreport.php?fields={$columns}&filter=no";

header("Location: {$postto}");

?>
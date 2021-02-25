<?php

error_reporting(E_ALL);

include("include/access.php");
include("little-helpers.php");
//this queries the real estate tables for data and builds a row for each location 

$db = dbConnect();
$sql = 'select * from realestate';
$result = mysql_query($sql);
	if (!$result) {
		error('A databass error has occured.\\n'.mysql_error());
	}

$count=0;

print '<style type="text/css">
		table#realestate td { text-align:center; padding:2px; border-left:1px #ccc solid; border-top:1px #ccc solid;}
		table#realestate td.left { text-align:left; }
		table#realestate { font-size:10px; font-family:arial; border-right:1px #ccc solid;}
		.red { color:#FF0000; }
		</style>';

print '<table id="realestate" cellspacing="0">
	<tr>
	<th>Location Name</th>
	<th>Market Area</th>
	<th>Developer</th>
	<th>Gross SQFT</th>
	<th>Sales Area SQFT</th>
	<th>Rec Approve Date</th>
	<th>Original Opening</th>
	<th>Remodel Date</th>
	<th>Lease Expires</th>
	<th>Sales Projection</th>
	<th>TTM Sales</th>
	<th>Sales PSF</th>
	<th>TTM OP Inc</th>
	<th>TTM CF</th>
	<th>LY TTM Sales</th>
	<th>LY Sales PSF</th>
	<th>LY TTM OP Inc</th>
	<th>LY TTM CF</th>
	<th>Sales % &Delta; vs LY</th>
	<th>TTM R&amp;O</th>
	<th>R&amp;O % Sales</th>
	<th>Tenant Allowance PSF</th>
	</tr>';

while ($row = mysql_fetch_array($result))
{
	$count++;	
	
	$id = $row['project_id'];
	$query = "SELECT sitename FROM projects_test WHERE id={$id}";
	$res = mysql_query($query);
	if (!$res) 
	{
		error('A databass error has occured.\\n'.mysql_error());
	}
	$r = mysql_fetch_array($res);
	
	print '<tr>
	<td class="left">'.$r['sitename'].'</td>
	<td class="left">'.$row['market_area'].'</td>
	<td class="left">'.$row['developer'].'</td>
	<td>'.number_format((float)$row['gross_sqft']).'</td>
	<td>'.number_format((float)$row['sales_area_sqft']).'</td>
	<td>'.dateFormat($row['approval_date']).'</td>
	<td>'.dateFormat($row['opening_date']).'</td>
	<td>'.dateFormat($row['remodel_date']).'</td>
	<td>'.dateFormat($row['expiry_date']).'</td>
	<td>'.isNegative(dollarFormat($row['sales_projection'])).'</td>
	<td>'.isNegative(dollarFormat($row['ty_ttm_sales'])).'</td>
	<td>'.isNegative(dollarFormat($row['ty_sales_psf'])).'</td>
	<td>'.isNegative(dollarFormat($row['ty_ttm_op_inc'])).'</td>
	<td>'.isNegative(dollarFormat($row['ty_ttm_cf'])).'</td>
	<td>'.isNegative(dollarFormat($row['ly_ttm_sales'])).'</td>
	<td>'.isNegative(dollarFormat($row['ly_sales_psf'])).'</td>
	<td>'.isNegative(dollarFormat($row['ly_ttm_op_inc'])).'</td>
	<td>'.isNegative(dollarFormat($row['ly_ttm_cf'])).'</td>
	<td>'.$row['sales_percentage_vs_ly']."%".'</td>
	<td>'.isNegative(dollarFormat($row['ttm_r_and_o'])).'</td>
	<td>'.$row['r_and_o_percent_sales']."%".'</td>
	<td>'.isNegative(dollarFormat($row['tenant_allowance_psf'])).'</td>	
	</tr>';
}


print '</table>';

function isNegative($dollar_amount)
{
	if (stripos($dollar_amount, "(") !== false)
		return "<span class=\"red\">{$dollar_amount}</span>";
		
	return $dollar_amount;
}
mysql_close($db);
?>
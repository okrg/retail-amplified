<div id="content">
<h1>:: Checkout</h1>
<div class="databox">
<p><a href="index.php">Home</a> &raquo; <a href="index.php?page=freq-shop">Fixture Catalog</a> &raquo; <a href="index.php?page=freq-cart">View Cart</a> &raquo; Checkout</p>
<?php
include ("include/freq-funcs.php");
dbConnect(); //Connect the DB
navi();

//Get store region and district based on the id of the submitted project id using the locData function!
//$_GET['pid'] = 25;//for testing purposes
//$locObj=locData($_GET['pid']); 
CheckOut();

?>
<p>Confirm your order details and make sure that everything is as correct before proceeding.</p>
<div class="sortbox">
<p>Order Details</p>
<form name="checkout" action="index.php?page=freq-submit" method="post">
<strong>Select the store you want to ship these fixtures to:</strong><br />
<?php
//	$user_uid_rank = substr($uid,0,2);
//	$user_domain = substr($uid,2);
	$user_uid_rank = dm;
	$user_domain = 04;
	
	if ($user_uid_rank == "dm") {
		$domain_value="District";
		$db_string = "store_district";
		} elseif ($user_uid_rank == "rm") {
			$domain_value="Region";
			$db_string="store_region";
			}
	$sql = "select id, sitenum, sitename, store_number, store_district, store_region, chain, datetouched from projects where $db_string = $user_domain order by sitename";
	//Execute filter on db!
	$result = mysql_query($sql);
	if (!$result){ error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error());}
	while ($row = mysql_fetch_array($result)) {
		$id = $row["id"];
		$store_number = $row["store_number"];
		$sitename = $row["sitename"];
		$chain=$row["chain"];
		if ($chain==1){$chain_name="Charlotte Russe";} elseif ($chain==2) {$chain_name="Rampage";}
		$dropdown_options .= "<option value=\"$id\">$store_number $sitename - $chain_name</option>";
	}
	
	
	
?>
<select name="new_location" class="files">
<option value="" selected>Please Choose
</option><?=$dropdown_options?></select>
<br /><br />
<strong>What is the priority of this order?</strong><br />
<select name="new_priority" class="files">
<option value="Normal" selected>Normal</option>
<option value="High" >High</option>
</select><br /><br />
<strong>Additional Comments:</strong><br />
<textarea name="new_comment" cols="40" rows="4"></textarea>
<p>[<a href="#" onClick="document.checkout.submit();return false;">Submit order</a>]</p>
</form></div></div></div>
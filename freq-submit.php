<?php
include ("include/freq-funcs.php");
dbConnect(); //Connect the DB
$locObj=locData($_POST['new_location']);

if ($_POST['new_location'] == "") {
	error("For some reason a location was not properly selected to place this order. Sorry, start again to complete the request properly.");
}
$checkVar = CheckIfEmpty();
if ($checkVar==1) {
	error("Cart is empty!");
	}
?>
<div id="content">
<h1>:: Checkout</h1>
<div class="databox">
<?php
//Get store region and district based on the id of the submitted project id using the locData function!
$locObj=locData($_POST['new_location']);
$datevar = date("mdyHis"); 
$tracker = $datevar;
//Set the sql statment..
		$sql = "insert into fixture_orders set 
				project_id='".$_POST['new_location']."',
				store_number='$locObj->store_number',
				store_district = '$locObj->store_district',
				store_region = '$locObj->store_region',
				priority = '".$_POST['new_priority']."',
				status = 'pending',
				cartId = '$tracker',
				body = '".$_POST['new_comment']."',
				author = '$username',
				issue_date = CURDATE()";
		if (!mysql_query($sql)) {error("A database error occured: " . mysql_error());}

		$result = mysql_query("select * from fixture_cart inner join fixture_key on fixture_cart.itemId = fixture_key.id where fixture_cart.cookieId = '" . GetCartId() . "' order by fixture_key.name asc");
		while($row = mysql_fetch_array($result)) {
			if (!mysql_query("insert into fixture_tracker(trackerId, itemId, addBool, qty) values('$tracker', '{$row['itemId']}', {$row['addBool']},{$row['qty']})")) {error("A database error occured: ".mysql_error());}
			if ($row['cat']=="Body Forms") {
				$bodyFormFlag = 1;
				}
		}
		
		//determine body form style if any
		if ($bodyFormFlag == 1) {
			$bodyFormResult = mysql_query("select mannequin_style from projects where id = {$_POST['new_location']}");
			$bodyForm = mysql_result($bodyFormResult, 0, 'mannequin_style');
			$mannequin_report = "Mannequin Style: $bodyForm";
			}


	include("freq-notify.php");
	//Add to report
	echo "<p>Thank you! Your fixture order has been submitted!<br />";
	//echo "<p>The following message was e-mailed to corporate staff:</p>";
	//echo "<div style=\"border:1px #ddd solid;background:#eee;padding:10px;\"><pre>$notice_text</pre></div>";
//empty cart...actually prefix the cart
	$delcart = mysql_query("delete from fixture_cart where cookieId = '".GetCartId()."'");
	if (!$delcart) { echo mysql_error();}
	echo "<a href=\"index.php\">Return to home page</a></p>";
	
?>
</div></div>

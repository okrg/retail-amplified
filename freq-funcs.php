<?php //Fixture Request Functions
//write nav bar
function navi() {
	echo "<ul>";
	echo "<li><a href=\"index.php?page=freq-shop&chain=1\">Browse for Charlotte Russe</a></li>";
	echo "<li><a href=\"index.php?page=freq-shop&chain=2\">Browse for Rampage Fixtures</a></li>";
	echo "<li><a href=\"index.php?page=freq-cart\">View/Edit Your Cart</a></li>";
	echo "<li><a href=\"index.php?page=freq-checkout\">Checkout and Submit Order</a></li>";	
	echo "</ul>";
	}


function Heading($cat, $chain) {
$result = mysql_query("select id from fixture_key where cat = '".$cat."' and chain = $chain");
while ($row = mysql_fetch_object($result)) {

	$chsplit .= $row->id.",";
	}
	$chsplit .= "0";
echo "<tr><td colspan=\"2\"><br /><br /><br /><h1>$cat</h1><a href=\"javascript:expandCat($chsplit)\">Expand All</a>&nbsp;&nbsp;<a href=\"javascript:collapseCat($chsplit);\">Collapse</a></td></tr>";
}

function RenderCatalog($chain) {
if ($chain==1){$chainclass="char";}else{$chainclass="ramp";}
$result=mysql_query("select * from fixture_key where chain=$chain order by cat");  //Get all the items in the fixture key
if (!$results){echo mysql_error();}
echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
while ($fixture = mysql_fetch_object($result)) {
	if ($fixture->exclude == 1){continue;}
	
	if (!isset($testcase)) {
		Heading("".$fixture->cat, $chain."");
		unset($firstcell);
		$testcase = $fixture->cat;
	} else {
		if ($fixture->cat != $testcase) {
		Heading("".$fixture->cat, $chain."");
		unset($firstcell);
		$testcase = $fixture->cat;
		}
	}
	echo "<tr>";
	echo "<td>$fixture->name</td>";
	echo "<td align=\"right\">";
	echo "<a href=\"#\" onClick=\"javascript:ajax_do('freqdata.php?id=".$fixture->id."&pid=".$_GET['pid']."');toggleBox('box".$fixture->id."',1);return false;\">".ucwords(strtolower(stripslashes($fixture->desc)))."</a>&nbsp;&nbsp;&nbsp;";
//	echo "<small><input name=\"".$fixture->cat."[]\" type=\"hidden\" value=\"".$fixture->id."\" /><a href=\"$PHP_SELF?page=freq-cart&action=add&pid=".$_GET['pid']."&id=".$fixture->id."&qty=1\">[Request Item]</a></small>";
	echo "<div id=\"box".$fixture->id."\" style=\"display:none;text-align: center;\" class=\"bigshinybutton $chainclass\"></div></td>";
	echo "</tr>";
	
}
echo "</table>";

}


function GetCartId() {
// This function will generate an encrypted string and
// will set it as a cookie using set_cookie. This will
// also be used as the cookieId field in the cart table
	if(isset($_COOKIE["cartId"])) {
		return $_COOKIE["cartId"];
	} else {
// There is no cookie set. We will set the cookie
// and return the value of the users session ID
	session_start();
	setcookie("cartId", session_id(), time() + (3600));
	return session_id();
	}
}


function locData($locId) {
	$r = mysql_query("select * from projects where id=$locId");
	$o = mysql_fetch_object($r);
	return $o;
}

function AddItem($itemId, $qty) {
	$result = mysql_query("select count(*) from fixture_cart  where cookieId = '" . GetCartId() . "' and itemId = $itemId");
	$row = mysql_fetch_row($result);
	$numRows = $row[0];
	if($numRows == 0) {
	// This item doesn't exist in the users cart,
	// we will add it with an insert query
	$addres = mysql_query("insert into fixture_cart(cookieId, itemId, addBool, qty) values('" . GetCartId() . "', $itemId, 1,$qty)");
	if (!$addres) {echo mysql_error();}
	}else {
	// This item already exists in the users cart,
	// we will update it instead
	//UpdateItem($itemId, $qty);
	}	
}
function CheckIfEmpty() { 
	$result = mysql_query("select COUNT(*) from fixture_cart where cookieId = '" . GetCartId()."'");
	$row = mysql_fetch_row($result);
	$numRows = $row[0];
	if($numRows == 0) { 
		$check = 1;
		}
	return $check;
}

function ShowCart($pid) {
	global $emptyCart;
	global $cartProblem;
	$flocObj = locData($pid);
	
	$result = mysql_query("select * from fixture_cart inner join fixture_key on fixture_cart.itemId = fixture_key.id where fixture_cart.cookieId = '" . GetCartId() . "' order by fixture_key.desc asc");
	echo "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\">";
	echo "<tr><th>Quantity</th><th>Item</th><th>Description</th><th>Add/Replace</th><th>Remove?</th></tr>";
	if (mysql_num_rows($result)==0) { 
		echo "<tr><td colspan=\"4\">Cart is Empty!</td></tr>";
		$emptyCart = 1;
	}
	while($row = mysql_fetch_array($result)) {
	if ($row['chain'] != $flocObj->chain) { RemoveItem($row['itemId']); echo "Removed item due to conflict"; continue; }

	echo "<tr><td>";
	echo "<select name=\"".$row['itemId']."\" onChange=\"UpdateQty(this)\">";
	for($i = 1; $i <= 99; $i++) {
		echo "<option ";
		if($row["qty"] == $i) {
			echo " SELECTED ";
		}
		echo ">" . $i . "</option>";
	}
	echo "</select></td>";
	echo "<td>".$row['name']."</td>";
	echo "<td>".$row['desc']."</td>";
	echo "<td>";
	echo "<select name=\"".$row['itemId']."\" onChange=\"UpdateAdd(this)\">";
	echo "<option ";
		if($row['addBool'] == 1) {
			echo " SELECTED value=\"1\">Add</option>";
			echo "<option value=\"0\">Replace</option>";	
		} else {
			echo " SELECTED value=\"0\">Replace</option>";
			echo "<option value=\"1\">Add</option>";			
		}


	echo "</td>";
	echo "<td><a href=\"$PHP_SELF?page=freq-cart&pid=".$_GET['pid']."&action=remove&id=".$row['itemId']."\">Remove</a></td></tr>";
//	if (!isset($rowChain)) { //if its not yet set, 
//		$rowChain = $row['chain'];//set it
//		} else {  //if it is
//		if ($rowChain!=$row['chain']) {//check if its the same  as the rows
//			$cartProblem=1; //and flag it
//			}
//		}
	}
	echo "</table>";
	


}

function RemoveItem($itemId) {
	mysql_query("delete from fixture_cart where cookieId = '" . GetCartId() . "' and itemId = $itemId");
}
function UpdateItem($itemId, $val, $addswitch) {
	if ($addswitch==1) {
		mysql_query("update fixture_cart set addBool = $val where cookieId = '" . GetCartId() . "' and itemId = $itemId");
	} else {
		mysql_query("update fixture_cart set qty = $val where cookieId = '" . GetCartId() . "' and itemId = $itemId");
	}
}

function CheckOut() {
	$result = mysql_query("select * from fixture_cart inner join fixture_key on fixture_cart.itemId = fixture_key.id where fixture_cart.cookieId = '" . GetCartId() . "' order by fixture_key.desc asc");
	echo "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\">";
	echo "<tr><th>Qty</th><th>Item</th><th>Add/Replace?</th></tr>";
	if (mysql_num_rows($result)==0){error("You must add an item to your cart!");} else {

	while($row = mysql_fetch_array($result)) {
	echo "<tr><td>".$row['qty']."</td>";
	echo "<td>".$row['desc']."  <small>[".$row['name']."]</small></td>";
	echo "<td>";
	if ($row['addBool']==1) {
		echo "Add";
		} else if ($row['addbool']==0) {
		echo "Replace";
		}
	echo "</td></tr>";
	}
	}
	echo "</table>";
	echo "<br />";
}

?>
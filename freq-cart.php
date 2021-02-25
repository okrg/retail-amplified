<script language="JavaScript">

function UpdateQty(item)
{
itemId = item.name;
newQty = item.options[item.selectedIndex].text;

document.location.href = 'index.php?page=freq-cart&action=update&pid=<?=$_GET['pid']?>&id='+itemId+'&qty='+newQty;
}
function UpdateAdd(item)
{
itemId = item.name;
newQty = item.options[item.selectedIndex].value;

document.location.href = 'index.php?page=freq-cart&action=update&pid=<?=$_GET['pid']?>&id='+itemId+'&add='+newQty;
}




</script>

<div id="content">
<h1>:: View Cart</h1>
<div class="databox">	
<p><a href="index.php">Home</a> &raquo; <a href="index.php?page=freq-shop&pid=<?=$_GET['pid']?>">Fixture Request Catalog</a> &raquo; View Cart</p>
<?php

include ("include/freq-funcs.php");
dbConnect(); //Connect the DB
$locObj=locData($_GET['pid']);
$chain = $locObj->chain;
//get the chain var and interpret as:
//1= Charlotte Russe
//2= Rampage 
//and apply these values to RenderCatalog() as an argument, like RenderCatalog(1) for showing Charlotte Russe fixtures
//$chain = $_GET['chain'];
if ($chain == 1) {
	$chain_label = "Charlotte Russe";
	} elseif ($chain == 2) {
	$chain_label = "Rampage";
	} else {
	$chain_label = "Select Store";
	$selector = TRUE;
	}
echo "<h2>$chain_label #".$locObj->store_number."</h2>";
echo "<p>".$locObj->sitename."<br />".$locObj->sitecity.",".$locObj->sitestate."</p>";

switch($_GET["action"]) {

	case "add":
	{
		AddItem($_GET["id"], $_GET["qty"]);
		ShowCart($_GET['pid']);
		break;
	}

	case "update":
	{
		if (isset($_GET['add'])) {
		UpdateItem($_GET["id"], $_GET["add"], 1);		
		} else {
		UpdateItem($_GET["id"], $_GET["qty"], 0);
		}
		ShowCart($_GET['pid']);
		break;
	}

	case "remove":
	{
		RemoveItem($_GET["id"]);
		ShowCart($_GET['pid']);
		break;
	}

	default:
	{
		ShowCart($_GET['pid']);
	}
}
if ($emptyCart==1) {
echo "<p><a href=\"index.php?page=freq-shop&pid={$_GET['pid']}\">Return to fixture catalog</a></p>";
} elseif ($cartProblem==1) {
echo "<h2>You cannot select fixtures for multiple locations on the same request.</h2>";

} else {
?>
<br /><br /><br />
<form name="checkout" action="index.php?page=freq-submit" method="post">
<input type="hidden" name="new_location" value="<?=$_GET['pid']?>" />
<strong>What is the priority of this order?</strong><br />
<select name="new_priority" class="files">
<option value="Normal" selected>Normal</option>
<option value="High" >High</option>
</select><br /><br />
<strong>Additional Comments:</strong><br />
<textarea name="new_comment" cols="40" rows="4"></textarea>
<p>[<a href="#" onClick="document.checkout.submit();return false;">Submit order</a>]</p>
</form>
<?php } ?>
</div>
</div>
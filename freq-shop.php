<?php 
include ("include/freq-funcs.php");
dbConnect(); //Connect the DB
//$_GET['pid'] = 1;//for testing purposes
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

?>
<div id="content">
<div class="databox">
<p><a href="index.php">Home</a> &raquo; Fixture Request Catalog &raquo; <a href="index.php?page=freq-cart&pid=<?=$_GET['pid']?>">View Cart</a></p>
<?php

	dbConnect();
	$sql = "select * from blog where readers='freq'";
	$result = mysql_query($sql);
	if (!result)
		error("A databass error has occured in processing your request.\\n". mysql_error());
	while ($row = mysql_fetch_array($result)) {
		$id = $row["id"];
		$ts = $row["ts"];
			$ts = revertTimestamp($ts);
		$subject = $row["subject"];
		$body= $row["body"];
			$body = stripslashes($body);
			$body = nl2br($body);
		$author = $row["author"];
	}
?>
<h2><?=$subject?></h2>
<p><small>Posted by <?=$author?> on <?=$ts?></small></p>
<p><?=$body?></p>
</div>

<h1>::Fixture Request Catalog</h1>
<div class="databox">
<h2><?=$chain_label?> #<?=$locObj->store_number?></h2>
<p><?=$locObj->sitename?><br /><?=$locObj->sitecity?>,<?=$locObj->sitestate?></p>
<div class="sortbox">
<?php
if ($selector) {
	?>
	<div class="bigshinybutton" style="text-align:center;"><p>Select which store you are requesting fixtures for:</p>
	<a href="index.php?page=freq-shop&chain=1"><img src="images/char-bg.gif" border="0" /><br />Charlotte Russe</a><br /><br /><br />
	<a href="index.php?page=freq-shop&amp;chain=2"><img src="images/ramp-bg.gif" border="0" /><br />Rampage</a><br /><br /></div>
	<?php
	} else {
	RenderCatalog($chain);
	}
	?>


</div>


</div>
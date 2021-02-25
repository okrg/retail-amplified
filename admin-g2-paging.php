
<span style="float:right;">
<form name="next" method="post" action="<?php echo "$PHP_SELF?page=admin-g2";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];} ?>">
<input type="submit" class="bigshinybutton" value="Next <?=$groupsof?>&raquo;" />

<?php
$groupsofplusone = $groupsof + 1;
if (!isset($_POST['range'])) {
	$newrange = $groupsofplusone;
	} else {
		$newrange = $_POST['range'];
		$newrange = $newrange + $groupsof;
	}
?>
<input type="hidden" name="range" value="<?=$newrange?>" />
<?php if (isset($_POST['sort'])) { echo "<input type=\"hidden\" name=\"sort\" value=\"".$_POST['sort']."\" />"; } 

if ((isset($_POST['action'])) && ($_POST['action']=="report")) { 
	echo "<input type=\"hidden\" name=\"action\" value=\"report\" />";

	if (isset($_POST['priority'])) { 
		foreach($_POST['priority'] as $value) {
			echo "<input type=\"hidden\" name=\"priority[]\" value=\"".$value."\" />";
		}
	}
	
	if (isset($_POST['type'])) { 
		foreach($_POST['type'] as $value) {
			echo "<input type=\"hidden\" name=\"type[]\" value=\"".$value."\" />";
		}
	}

	if (isset($_POST['status'])) { 
		foreach($_POST['status'] as $value) {
			echo "<input type=\"hidden\" name=\"status[]\" value=\"".$value."\" />";
		}
	}

	if (isset($_POST['store_number'])) { 
			echo "<input type=\"hidden\" name=\"store_number\" value=\"".$_POST['store_number']."\" />";
	}
	
	if (isset($_POST['store_district'])) { 
			echo "<input type=\"hidden\" name=\"store_district\" value=\"".$_POST['store_district']."\" />";
	}

	if (isset($_POST['store_region'])) { 
			echo "<input type=\"hidden\" name=\"store_region\" value=\"".$_POST['store_region']."\" />";
	}
}
?>

<input type="hidden" name="groupsof" value="<?=$_POST['groupsof']?>" />
</form>
</span>



<?php
	if (isset($_POST['range']) && $_POST['range']>$_POST['groupsof']) {
	?>
<form name="previous" method="post" action="<?php echo "$PHP_SELF?page=admin-g2";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];} ?>">
<input type="submit" class="bigshinybutton" value="&laquo;Previous <?=$groupsof?>" />

<?php
	$groupsofplusone = $groupsof + 1;
	$newrange = $_POST['range'];
	$newrange = $newrange - $groupsof;
?>

<input type="hidden" name="range" value="<?=$newrange?>" />
<input type="hidden" name="sort" value="<?=$_POST['sort']?>" />
<input type="hidden" name="groupsof" value="<?=$_POST['groupsof']?>" />
<?php

if ((isset($_POST['action'])) && ($_POST['action']=="report")) { 
	echo "<input type=\"hidden\" name=\"action\" value=\"report\" />";

	if (isset($_POST['priority'])) { 
		foreach($_POST['priority'] as $value) {
			echo "<input type=\"hidden\" name=\"priority[]\" value=\"".$value."\" />";
		}
	}
	
	if (isset($_POST['type'])) { 
		foreach($_POST['type'] as $value) {
			echo "<input type=\"hidden\" name=\"type[]\" value=\"".$value."\" />";
		}
	}

	if (isset($_POST['status'])) { 
		foreach($_POST['status'] as $value) {
			echo "<input type=\"hidden\" name=\"status[]\" value=\"".$value."\" />";
		}
	}

	if (isset($_POST['store_number'])) { 
			echo "<input type=\"hidden\" name=\"store_number\" value=\"".$_POST['store_number']."\" />";
	}
	
	if (isset($_POST['store_district'])) { 
			echo "<input type=\"hidden\" name=\"store_district\" value=\"".$_POST['store_district']."\" />";
	}

	if (isset($_POST['store_region'])) { 
			echo "<input type=\"hidden\" name=\"store_region\" value=\"".$_POST['store_region']."\" />";
	}
}
?>

</form>
<?php } else { ?>
<form name="previous" method="post" action="<?php echo "$PHP_SELF?page=admin-g2";if (isset($_GET['restrict'])){echo"&restrict=".$_GET['restrict'];} ?>">
<input type="submit" class="bigshinybutton" value="&laquo;Back to start" /><br />
<?php if (isset($_POST['sort'])) { echo "<input type=\"hidden\" name=\"sort\" value=\"".$_POST['sort']."\" />"; } ?>
<input type="hidden" name="groupsof" value="<?=$_POST['groupsof']?>" />

</form>

<?php } ?>

<br />
<div style="clear:both;"></div>
<?php
//	print "<span style=\"float:right;\">";
//	print "<a href=\"$PHP_SELF?page=admin-g2&g=$groupsof";
//	
//	$groupsofplusone = $groupsof + 1;
//	
//	if (!isset($_GET['range'])) { //Always show next groupsof link, and never show previous groupsof link on pages where the range is too small
//		echo "&range=$groupsofplusone";
//	} else {
//		$newrange = $_GET['range'];
//		$newrange = $newrange + $groupsof;
//		echo "&range=".$newrange;
//	}
//	//Add sorts to the link if they have been somehow set via get or posts
//	if (isset($_GET['sort'])) {
//		echo "&sort=".$_GET['sort'];
//	}
//	if (isset($_POST['sort'])) {
//		echo "&sort=".$_POST['sort'];
//	}
//	
//	print "\">Next $groupsof &raquo;</a>";
//	print "</span>";
//	
//	if (isset($_GET['range'])) {
//		print "<a href=\"$PHP_SELF?page=admin-g2&g=$groupsof";
//		
//	if (($_GET['range'])>$groupsofplusone) {
//		$newrange = $_GET['range'];
//		$newrange = $newrange - $groupsof;
//		echo "&range=".$newrange;
//	}
//
//	if (isset($_GET['sort'])) {
//		echo "&sort=".$_GET['sort'];
//	}
//	if (isset($_POST['sort'])) {
//		echo "&sort=".$_POST['sort'];
//	}	
//	
//	print "\">&laquo; Previous $groupsof</a>";
//} 
//	print "<br />";
//	print "<div style=\"clear:both;display:block;\">&nbsp;</div>";
//
?>
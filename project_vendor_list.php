<div id="vendor-list">
<?php //project_vendor_list.php
	$categories = array(
		'lighting' => 'Lighting', 
		'gc' => 'General Contractors', 
		'architect' => 'Architects/Engineers', 
		'storefront' => 'Storefront', 
		'signage' => 'Signage',  
		'flooring' => 'Flooring', 
		'millwork' => 'Millwork', 
		'wall_covering' => 'Wall Covering',  
		'decorative_elements' => 'Decorative Elements', 
		'fixtures' => 'Fixtures', 
		'backroom_shelving' => 'Backroom Shelving', 
		'music' => 'Music', 
		'phones' => 'Phones', 
		'mannequins' => 'Mannequins', 
		'alarm' => 'Alarm',  
		'misc' => 'Miscellaneous');

if(!isset($companyarray)) {
	$companyarray = array();
}

		foreach($categories as $key => $label) {
				$query = "select * from companies where active = 1 and ".$key." = 1 ORDER BY company_name";
		    $result = mysqli_query($dbcnx, $query);
		    $resqty = mysqli_num_rows($result);
		    
		    if ($resqty) {
			    print '<div class="cat-label">'.$label.'</div>';
		    }
			while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
				echo '<label class="checkbox"><input type="checkbox" value="'.$row["company_id"].'"';
				if (in_array($row["company_id"], $companyarray)) {echo ' checked="checked"';}
				echo '>';
				echo $row["company_name"];
				echo '</label>';
			}
			
			$uncat_keys[] = $key ."=0";
			
		}
		
		$uncat_query = implode(" AND ", $uncat_keys);
		
		print '<div class="cat-label">Uncategorized</div>';
	    $query = "select * from companies where active = 1 AND ".$uncat_query ." ORDER BY company_name";
	    $result = mysqli_query($dbcnx, $query);
	    $resqty = mysqli_num_rows($result);
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
			echo '<label class="checkbox"><input type="checkbox" value="'.$row["company_id"].'"';
			if (in_array($row["company_id"], $companyarray)) {echo ' checked="checked"';}
			echo '>';
			echo $row["company_name"];
			echo '</label>';
		}


			
?>
<div style="clear:both;">&nbsp;</div>
</div>

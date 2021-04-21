<?php //project_syummary.php

function pprint_r($var){
	print '<pre>';
	print_r($var);
	print '</pre>';
}

	$query = "SELECT * FROM projects WHERE id = $id";
	$result = mysqli_query($dbcnx, $query) or die ("no query");	
	$data = array();
  while($row = mysqli_fetch_assoc($result)) {
	  $data[] = $row;
	}
	$project = $data[0];

	if ($project['chain']==1){ 
    $sitechain="Charlotte Russe";
  } else {
   $sitechain = "Rampage";
 }

	$companyarray = unserialize($project['companyarray']);

  $sitename = $project['sitename'];

	$query = "SELECT * FROM realestate WHERE project_id = $id";
	$result = mysqli_query($dbcnx, $query) or die ("no query");	
	$data = array();
	while($row = mysqli_fetch_assoc($result)) {
	    $data[] = $row;
	}
	$realestate = @$data[0];

	$query = "SELECT * FROM re_centerinfo WHERE project_id = $id";
	$result = mysqli_query($dbcnx, $query) or die ("no query");	
	$data = array();
	while($row = mysqli_fetch_assoc($result)) {
	    $data[] = $row;
	}
	$centerinfo = @$data[0];

	$query = "SELECT * FROM re_strategy WHERE project_id = $id";
	$result = mysqli_query($dbcnx, $query) or die ("no query");	
	$data = array();
	while($row = mysqli_fetch_assoc($result)) {
	    $data[] = $row;
	}
	$strategy = @$data[0];

	$query = "SELECT * FROM re_storedesign WHERE project_id = $id";
	$result = mysqli_query($dbcnx, $query) or die ("no query");	
	$data = array();
	while($row = mysqli_fetch_assoc($result)) {
	    $data[] = $row;
	}
	$storedesign = @$data[0];

	$query = "SELECT * FROM re_kickouts WHERE project_id = $id";
	$result = mysqli_query($dbcnx, $query) or die ("no query");	
	$data = array();
	while($row = mysqli_fetch_assoc($result)) {
	    $data[] = $row;
	}
	$kickouts = @$data[0];

	$query = "SELECT * FROM re_options WHERE project_id = $id";
	$result = mysqli_query($dbcnx, $query) or die ("no query");	
	$data = array();
	while($row = mysqli_fetch_assoc($result)) {
	    $data[] = $row;
	}
	$options = @$data[0];

	$query = "SELECT * FROM re_deal_economics WHERE project_id = $id";
	$result = mysqli_query($dbcnx, $query) or die ("no query");	
	$data = array();
	while($row = mysqli_fetch_assoc($result)) {
	    $data[] = $row;
	}
	$deal_economics = @$data[0];

	$query = "SELECT * FROM re_extra_charges WHERE project_id = $id";
	$result = mysqli_query($dbcnx, $query) or die ("no query");	
	$data = array();
	while($row = mysqli_fetch_assoc($result)) {
	    $data[] = $row;
	}
	$extra_charges = @$data[0];

	if ($project['store_number'] != 0) {
		//Find other projects that have the same store number
		$query = "SELECT id, project_status, sitename, store_opening_date, project_type FROM projects where store_number = ".$project['store_number'];
		$result = mysqli_query($dbcnx, $query);
		$stores = array();
		if(mysqli_num_rows($result) > 1) {
			while($row = mysqli_fetch_assoc($result)){
				$stores[] = $row;
			}
		}
	}
	
	
	//Get changes array
	$query = "SELECT * FROM changes WHERE project_id = $id";
	$result = mysqli_query($dbcnx, $query) or die ("no query");	
	while($row = mysqli_fetch_assoc($result)) {
	    $changes[] = $row['field_id'];
	}	

	
?>


<?php if ($stores): ?>
<div class="well">
	<ul class="nav nav-pills">
	<?php

	foreach($stores as $store) {
		if( $store['id'] == $_GET['id'] ) {
			$pclass = 'active';
		} else {
			$pclass = 'inactive';
		}
		print '<li class="nav-item">';
		print '<a class="nav-link '.$pclass.'" href="index.php?page=project&id='.$store['id'].'">'.$store['sitename'];
    print '<br /><span style="font-size:11px;font-weight:normal;">';
    

    if($store['store_opening_date'] != '0000-00-00') {
      if (strpos($store['store_opening_date'],'TBD') !== false) {
        print $store['store_opening_date'];
      } else {
        print date('m/d/Y', strtotime($store['store_opening_date']));
      }

      
    }

    if($store['project_type'] != '') {
      print ' [' . ucwords($store['project_type']) . '] ';
    }

    print '</span></a>';
		print '</li>';
	}

	?>
	</ul>
</div>
<?php endif; ?>

<div id="project-summary-form">

<div class="pane open" id="summary-main">
    
<div class="pane-content">

<div class="row">
  <div class="col">
  <div class="row">
    <div class="col" for="p-sitename">Location Name</div>
    <div class="col">
      <a href="#" data-type="text" data-table="projects" data-name="sitename" class="edit">
        <?=$project['sitename']?>
      </a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="p-sitespace">Space</div>
    <div class="col">
      <a href="#" data-type="text" data-table="projects" data-name="sitespace" class="edit">
        <?=$project['sitespace']?>
      </a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="p-tenant">Tenant</div>
    <div class="col">
      <a href="#" data-type="text" data-table="projects" data-name="tenant" class="edit">
        <?=$project['tenant']?>
      </a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="p-store_number">Store #</div>
    <div class="col">
      <a href="#" data-type="text" data-table="projects" data-name="store_number" class="edit"><?=intval($project['store_number'])?></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="p-store_district">Store District</div>
    <div class="col">
      <a href="#" data-type="text" data-table="projects" data-name="store_district" class="edit"><?=intval($project['store_district'])?></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="p-store_region">Store Region</div>
    <div class="col">
      <a href="#" data-type="text" data-table="projects" data-name="store_region" class="edit"><?=intval($project['store_region'])?></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-lease_sqft">Lease Sq. Feet </div>
    <div class="col">
      <a href="#" data-type="text" id="r-lease_sqft" class="edit"><?=$realestate['lease_sqft']?></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-sales_area_sqft">Sales Area Sq. Feet </div>
    <div class="col">
      <a href="#" data-type="text" id="r-sales_area_sqft" class="edit"><?=$realestate['sales_area_sqft']?></a>
    </div>
  </div>
  </div>
  <div class="col">
  <div class="row">
    <div class="col" for="p-project_status">Status</div>
    <div class="col">
      <a href="#" data-type="select" data-table="projects" data-name="project_status" class="edit" data-source="[{value:'proposed', text:'Proposed'}, {value:'active', text:'Active'}, {value:'archive', text:'Archive'}, {value:'real_estate', text:'Real Estate Archive'}, {value:'other',text:'Other'}, {value:'', text:'None'}]" data-value="<?=$project['project_status']?>"></a>
    </div>
  </div>




  <div class="row">
    <div class="col" for="p-project_status">Stage</div>
    <div class="col">
      <a href="#" data-type="select" data-table="projects" data-name="stage" class="edit" data-source="['TT Deal Approval','LL Deal Approval','Lease Comments','TT Signed Lease','LL Signed Lease','LLW Construction','Space Delivery','TT Construction','TT Stock and Train','TT Open']" data-value="<?=$project['stage']?>"></a>
    </div>
  </div>


    <div class="row">
    <div class="col" for="p-project_status">Risk</div>
    <div class="col">
      <a href="#" data-type="select" data-table="projects" data-name="risk" class="edit" data-source="['On Schedule','LL Lease Delay','TT Lease Delay','LL Work Delay','TT Work Delay','TT Product Delay','City Delay','Amendment Pending']" data-value="<?=$project['risk']?>"></a>
    </div>
  </div>


  <div class="row">
    <div class="col" for="p-siteaddress">Address (<a href="http://maps.google.com/maps?q=<?=$project['siteaddress']?>,<?=$project['sitecity']?>,<?=$project['sitestate']?>,<?=$project['sitezip']?>" target="_blank" title="Opens Google map in a new window!">Map</a>)
    </div>
    <div class="col">
      <a href="#" data-type="text" data-table="projects" data-name="siteaddress" class="edit"><?=$project['siteaddress']?></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="p-siteaddress2"></div>
    <div class="col">
      <a href="#" data-type="text" data-table="projects" data-name="siteaddress2" class="edit"><?=$project['siteaddress2']?></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="p-sitecity">City</div>
    <div class="col">
      <a href="#" data-type="text" data-table="projects" data-name="sitecity" class="edit"><?=$project['sitecity']?></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="p-sitestate">State</div>
    <div class="col">
      <a href="#" data-type="text" data-table="projects" data-name="sitestate" class="edit"><?=$project['sitestate']?></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="p-sitezip">Zip</div>
    <div class="col">
      <a href="#" data-type="text" data-table="projects" data-name="sitezip" class="edit"><?=$project['sitezip']?></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="p-project_type">Project Type</div>
    <div class="col">
      <a href="#" data-type="select" data-table="projects" data-name="project_type" class="edit" data-source="[{value:'new',text:'New'},{value:'renovation',text:'Renovation'},{value:'relocation',text:'Relocation'},{value:'remodel',text:'Remodel'},{value:'downsize',text:'Downsize'},{value:'temp',text:'Temp'},{value:'misc',text:'Misc'},{value:'other',text:'Other'},{value:'',text:'None'}]" data-value="<?=$project['project_type']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="p-sitenum">Job #</div>
    <div class="col">
      <a href="#" data-type="text" data-table="projects" data-name="sitenum" class="edit"><?=$project['sitenum']?></a>
    </div>
  </div>
  
  <div class="row">
    <div class="col" for="p-architect">Architect</div>
    <div class="col">
      <a href="#" data-type="text" data-table="projects" data-name="architect" class="edit"><?=$project['architect']?></a>
    </div>
  </div>
  
	</div>
</div>

<div class="row">
  <div class="col">

</div>


<div class="col">

  <div class="row">
    <div class="col">Phone</div>
    <div class="col">
      <a href="#" data-type="text" data-table="projects" data-name="sitephone" class="edit"><?=$project['sitephone']?></a>
    </div>
  </div>  
  <div class="row">
    <div class="col">Rollover Line</div>
    <div class="col">
      <a href="#" data-type="text" data-table="projects" data-name="siterollover" class="edit"><?=$project['siterollover']?></a>
    </div>
  </div>  
  <div class="row">
    <div class="col">Fax</div>
    <div class="col">
      <a href="#" data-type="text" data-table="projects" data-name="sitefax" class="edit"><?=$project['sitefax']?></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="p-sitemodem">Modem</div>
    <div class="col">
      <a href="#" data-type="text" data-table="projects" data-name="sitemodem" class="edit"><?=$project['sitemodem']?></a>
    </div>
  </div>
</div>
</div>

<div class="row">
  <div class="col">

  <div class="row">
    <div class="col" for="p-full_price_or_outlet">Design Strategy</div>
    <div class="col">
      <a href="#" data-type="select" data-table="projects" data-name="full_price_or_outlet" class="edit" data-source="[{value:'full_price',text:'Full Price'},{value:'outlet',text:'Outlet'},{value:'',text:'None'}]" data-value="<?=$project['full_price_or_outlet']?>"></a>
    </div>
  </div>  
  <div class="row">
    <div class="col" for="p-indoor_outdoor_lifestyle">Indoor/Outdoor</div>
    <div class="col">
      <a href="#" data-type="select" data-table="projects" data-name="indoor_outdoor_lifestyle" class="edit" data-source="[{value:'indoor',text:'Indoor'},{value:'outdoor',text:'Outdoor'},{value:'lifestyle',text:'Lifestyle'},{value:'',text:'None'}]" data-value="<?=$project['indoor_outdoor_lifestyle']?>"></a>      
    </div>
  </div>
  <div class="row">
    <div class="col" for="p-comments">Notes</div>
    <div class="col">      
      <a href="#" data-type="textarea" data-table="projects" data-name="comments" class="edit" data-value="<?=$project['comments']?>"></a>
    </div>
  </div>
</div>
<div class="col">
<?php if($usergroup < 3): ?>
  <div class="row">
    <div class="col" for="r-sales_projection">Sales Projection</div>
    <div class="col">
      <a href="#" data-type="text" id="r-sales_projection" class="edit"><?=$realestate['sales_projection']?></a>
    </div>
  </div>
<?php endif; ?>
</div>
</div>

</div>
</div>



	<div class="pane closed" id="summary-contacts">
		<div class="pane-header">Contacts<span class="symbol"><i class="icon-chevron-right"></i></span></div>
		<div class="pane-content"><?php include("project_contacts.php"); ?></div>
	</div>

<?php
  $project_modules = json_decode($project['project_modules']);
?>

<?php if($project_modules->store_attributes): ?>
  <div class="pane closed" id="summary-attributes">
    <div class="pane-header">Store Attributes<span class="symbol"><i class="icon-chevron-right"></i></span></div>
    <div class="pane-content"><?php include("project_store_attr.php"); ?></div>
  </div>
<?php endif; ?>

<?php if($usergroup == 0): ?>		
	<div class="pane closed" id="master-vendor-list">
		<div class="pane-header">Vendor/Architect/Consultant Group Access List<span class="symbol"><i class="icon-chevron-right"></i></span></div>
		<div class="pane-content">
      <?php //toggle groups ?>
      <?php include("project-groups-form.php"); ?>        
    </div>
	</div>
<?php endif; ?>

</div>
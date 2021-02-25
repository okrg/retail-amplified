<?php
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', '1');
include("include/access.php");
include("little-helpers.php");

dbConnect();
$id = mysqli_escape_string($dbcnx, $_GET['id']);

$query = "SELECT * FROM projects WHERE id = $id";
$result = mysqli_query($dbcnx, $query) or die ("no query");	
$data = array();
while($row = mysqli_fetch_assoc($result)) {
  $data[] = $row;
}

$project = $data[0];


$query = "SELECT * FROM realestate WHERE project_id = $id";
$result = mysqli_query($dbcnx, $query) or die ("no query");	
$data = array();
while($row = mysqli_fetch_assoc($result)) {
  $data[] = $row;
}
$real_estate = @$data[0];


$query = "SELECT * FROM re_storedesign WHERE project_id = $id";
$result = mysqli_query($dbcnx, $query) or die ("no query");	
$data = array();
while($row = mysqli_fetch_assoc($result)) {
  $data[] = $row;
}
$design = @$data[0];

$query = "SELECT * FROM scheduled_dates WHERE project_id = $id";
$result = mysqli_query($dbcnx, $query) or die ("no query");	
$data = array();
while($row = mysqli_fetch_assoc($result)) {
  $data[] = $row;
}

$scheduled = $data[0];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="scr1pz0rx.js"></script>
<script type="text/javascript" src="bootstrap.min.js"></script>
<script type="text/javascript" src="autoNumeric.js"></script>
<script type="text/javascript" src="moment.min.js"></script>

<link rel="stylesheet" href="bootstrap.min.css">
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/humanity/jquery-ui.css">
<link rel="stylesheet" href="stylesheet.css">
<link rel="stylesheet" href="jquery_fileupload/jquery.fileupload-ui.css">
<link rel="stylesheet" href="jquery_multiselect/common.css">
<link rel="stylesheet" href="jquery_multiselect/ui.multiselect.css">
<style type="text/css">
body {background: none;color:#333;}
form {margin:0;}
.form-horizontal {margin-top:40px;}
.table {width:auto;}
.table th, .table td {padding:0px;line-height:16px;}
.table .table {font-size:10px;margin-bottom:5px;}
.table .table td {line-height: 13px;}
.table table.remodel {background:#dce5d1;}
.table table.relocation {background:#ced8e3;}
.table table.remodel,.table table.relocation,
.table table.remodel td, .table table.relocation td {border-color:#999;}
th.centered, td.centered {text-align:center;}
tr.quarter-header td {background:#ccc;padding:4px; font-weight: bold;}
td.col {width:150px;}
#content{background: none;border:none;margin-top:30px;color:#333;}
#field-btns {margin-left:0px;margin-top:0;position:absolute;top:0;left:150px;z-index:10000;}
#field-cancel{text-decoration: underline;}
#ui-datepicker-div {z-index: 9999 !important;}
.ui-datepicker-calendar thead span {color:#666;}
span.label {font-size:10px;}
.form-horizontal .control-group {margin-bottom:0;}
.form-horizontal .controls {margin-left: 165px;}
.form-horizontal .control-label {width: 160px;padding-top:4px;font-size:11px;line-height: 12px;}

select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"], .uneditable-input {height:14px;font-size:12px;line-height: 12px;}
<?php if($print): ?>
.columns {position:static;margin-left:150px;}
#total-projects,#hidden-projects {display:none;}
#print-view-btn,.header .close {display:none;}
#default-view-btn {display:block;}
<?php endif; ?>

</style>

<style type="text/css">
@page
{
	size: portrait;
	margin: 1cm;
}
</style>



<style type="text/css" media="print">
@page
{
	size: portrait;
	margin: 1cm;
}
.form-horizontal {margin-top:0px;}
#content {margin:0;}
.navbar {display:none;}
.header-group {position: static;}
.superficial {display:none;}
}
</style>



<script type="text/javascript">

$(document).ready(function() {

	$(".edit").prop('disabled', true);

	function closeField() {
		//Return to init value...
		$('.active-input').val( $('.active-input').attr('data-init-value') );				
		$('.active-input').removeClass('active-input');
		$('.active-controls').removeClass('active-controls');		
		$('#field-btns').remove();
	}

	function getTableName(code) {
		switch(code) {
		case 'r':
			return 'realestate';
		case 't':
			return 're_strategy';
		case 'd':
			return 're_storedesign';
		case 'c':
			return 're_centerinfo';
		case 'o':
			return 're_options';
		case 'k':
			return 're_kickouts';
		case 'a':
			return 'actual_dates';
		case 's': 
			return 'scheduled_dates';
		case 'p': 
			return 'projects';				
		}
	}

	
	function ajaxSuccess() {
	}


<?php 

	if (($usergroup < 1) or (in_array("plans",$roles))){
		$can_edit = TRUE;
		if($usergroup < 2) {
			$field_selector = 'edit';
		} else {
			$field_selector = 'arch';
		}

	}
?>


<?php if($can_edit): ?>	

	$(".<?=$field_selector?>").prop('disabled', false);
	
	$(".date").datepicker({showAnim:"slideDown"});

	    $('input.percent').autoNumeric( {aSign: '%', pSign: 's', vMax:'1000.00', vMin:'-1000.00'} );
	    $('input.money').autoNumeric( {aSep: ',', aDec: '.', aSign: '$'} );
	    $('input.number').autoNumeric( {aSep: '', aPad: false, vMax: '999999'} );	
    	$('input.date').datepicker({
    		showAnim:"slideDown"
    	});
    	
    	/*
    	$('table.ui-datepicker-calendar td a').live('mouseup', function() {
			setTimeout(function() {
	    	$('#field-save').trigger('mouseup');
			}, 500);    		
    	});

    	$('.typeahead li a').live('mouseup', function() {
			setTimeout(function() {
	    	$('#field-save').trigger('mouseup');
			}, 500);    		

    	});
    	
    	*/
    	
	
	//Go through each input and set the init value
	$('input.percent,input.money,input.number').each(function() {
		$(this).autoNumericSet( $(this).val() );				
	});
	

	
	//Go through each input and set the init value
	$('input, textarea, select').each(function() {
		$(this).attr('data-init-value', $(this).val() );				
	});
	


	
	$(".<?=$field_selector?>").focus(function() {		
	
		if( $(this).hasClass('project-comments') ) {return false;}
		//Destroy other instance and redeploy field btns to this input
		closeField();
		
		//Add the active class to this field so that it stays lit even if focus is temporarily lost..
		$(this).addClass('active-input');
		$(this).closest('.controls').addClass('active-controls');

		
		if( $(this).hasClass('string') ) {
		//Load data source 
		var column = $(this).attr('id').substring(2);		
		var table = getTableName( $(this).attr('id').charAt(0) );
		var $this = $(this);
		$.get('ajax_field_source_data.php', { column: column, table: table }, function (data) {
			//$this.attr('data-source', data);
			//alert(data);			
			$this.typeahead({source: data});
		});
		}
		
		

		//Insert field buttons
		$('<div id="field-btns" />').insertAfter(this);
		$('#field-btns').append('<a class="btn btn-mini btn-success" id="field-save"><i class="icon-white icon-ok"></i></a>');
		$('#field-btns').append('<a id="field-cancel">Cancel</a>');

		//if ($(this).hasClass('date')) {
		//$('#field-btns').css('visibility','hidden');		
		//}
		
		//Set coordinate position of buttons...
		var top = $(this).offset().top;
		var left = $(this).offset().left; 
		var width = $(this).width();
		var dist = left + width;
		
		$('#field-btns').css('top',top);
		$('#field-btns').css('left',dist);
	});
	

	$('#field-cancel').live('mouseup', function(e) {
		e.stopPropagation();
		closeField();
	});


	$('#field-save').live('mouseup', function(e) {
		e.stopPropagation();
		var field_id = $('.active-input').attr('id');
		var column = $('.active-input').attr('id').substring(2);
		var thisinput = $('.active-input');
		if ( thisinput.hasClass('money') || thisinput.hasClass('percent') ) {
			var newval = $('.active-input').autoNumericGet();
		} else {	
			var newval = $('.active-input').val();
		}
		
		var table = getTableName( $('.active-input').attr('id').charAt(0) );

		//Validate new value?

		//Save new value via ajax function to ajax_field_save.php
		$.ajax({
	        type: "POST",
	        url: "ajax_field_save.php",
	        data: "id=<?=$id?>&table=" + encodeURIComponent(table) + "&column=" + encodeURIComponent(column) + "&value=" + encodeURIComponent(newval) + "&field_id=" + encodeURIComponent(field_id),
	        success: function(response) {
	            if(response === '1') {
					$(thisinput).attr('data-init-value', $(thisinput).val() );
					closeField();
					$('#ajax-message').html('<div class="alert alert-success">Saved Successfully!</div>').show().delay('2000').fadeOut('500');
					$(thisinput).addClass('changed');					
	            } else {
	                $('#ajax-message').html('<div class="alert alert-error">Error:'+response+'</div>').show().delay('2000').fadeOut('500');
	                closeField();
	            }
	        }
	    });
		
		
		
	});

<?php endif; ?>


    	$('input.date').each(function() {
    		if ( $(this).val() != '0000-00-00' ) {
	    		var dmoment = moment( $(this).val() );
	    	} else {
		    	$(this).val('');
	    	}
	    	
	    	if(dmoment) {
		    	$(this).val( dmoment.format('L') );
	    	}
    	});
});
</script>

</head>

<body>
<div id="ajax-message"></div>
<h2>THIS REPORT IS UNDER CONSTRUCTION TO MATCH THE NEW DATA FORMAT</h2>

<div class="navbar navbar-inverse navbar-fixed-top">
<div class="navbar-inner">
<ul class="nav">
	<li><a href="index.php?page=project&id=<?=$id?>">&laquo; Back to Project Page</a></li>
	<li><a href="index.php">Home Page</a></li>
</ul>

</div>
</div>

<div class="wide-container">
<form class="form-horizontal project-form" id="project-summary-form">

<table class="table table-bordered">
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-store_number">Store Number</label>
    <div class="controls">
      <input type="text" id="p-store_number" class="string edit" value="<?=$project['store_number']?>">
    </div>
</div>
</td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-store_opening_date">Open Date</label>
    <div class="controls">
      <input type="text" id="p-store_opening_date" class="date edit" value="<?=$project['store_opening_date']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-sitename">Store Name</label>
    <div class="controls">
      <input type="text" id="p-sitename" class="string edit" value="<?=$project['sitename']?>">
    </div>
</div>
</td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-grand_opening">Grand Opening?</label>
    <div class="controls">
      <select class="edit" class="edit" id="p-grand_opening">
      	<option value="<?=$project['grand_opening']?>"><?= $project['grand_opening'] == 1 ? 'Yes' : 'No' ?></option>
      	<option value="" disabled="disabled">--</option>
      	<option value="1">Yes</option>
      	<option value="0">No</option>
      </select>	

    </div>
</div>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-siteaddress">Address</label>
    <div class="controls">
      <input type="text" id="p-siteaddress" class="string edit" value="<?=$project['siteaddress']?>">
    </div>
</div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-sitecity">City</label>
    <div class="controls">
      <input type="text" id="p-sitecity" class="string edit" value="<?=$project['sitecity']?>">
    </div>
</div>
</td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-general_contractor">General Contractor</label>
    <div class="controls">
      <input type="text" id="p-general_contractor" class="string edit" value="<?=$project['general_contractor']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-sitestate">State</label>
    <div class="controls">
      <input type="text" id="p-sitestate" class="string edit" value="<?=$project['sitestate']?>">
    </div>
</div>
</td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-gc_project_manager">G.C. Project Manager</label>
    <div class="controls">
      <input type="text" id="p-gc_project_manager" class="string edit" value="<?=$project['gc_project_manager']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-sitezip">Zip</label>
    <div class="controls">
      <input type="text" id="p-sitezip" class="string edit" value="<?=$project['sitezip']?>">
    </div>
</div>
</td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-gc_project_manager_phone">GC Project Manager Phone</label>
    <div class="controls">
      <input type="text" id="p-gc_project_manager_phone" class="string edit" value="<?=$project['gc_project_manager_phone']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td></td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-gc_superintendent">Job Super</label>
    <div class="controls">
      <input type="text" id="p-gc_superintendent" class="string edit" value="<?=$project['gc_superintendent']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="s-lease_executed">Lease Executed</label>
    <div class="controls">
      <input type="text" id="s-lease_executed" class="date edit" value="<?=$scheduled['lease_executed']?>">
    </div>
 </td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-gc_superintendent_phone">Job Phone</label>
    <div class="controls">
      <input type="text" id="p-gc_superintendent_phone" class="string edit" value="<?=$project['gc_superintendent_phone']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="r-sales_projection">R.E. Sales Pick</label>
    <div class="controls">
      <input type="text" id="r-sales_projection" class="string edit" value="<?=$real_estate['sales_projection']?>">
    </div>
</div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-sitename">Ops. Sales Pick</label>
    <div class="controls">
      <input type="text" id="r-sales_projection" class="string edit" value="<?=$real_estate['sales_projection']?>">
    </div>
</div>
</td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-cr_project_manager">CR Corporate Contact</label>
    <div class="controls">
      <input type="text" id="p-cr_project_manager" class="string edit" value="<?=$project['cr_project_manager']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="s-landlord_approval">Final Plans Approved LL</label>
    <div class="controls">
      <input type="text" id="s-landlord_approval" class="date edit" value="<?=$scheduled['landlord_approval']?>">
    </div>
</div>
</td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-cr_project_manager_phone">CR Corporate Contact phone</label>
    <div class="controls">
      <input type="text" id="p-cr_project_manager_phone" class="string edit" value="<?=$project['cr_project_manager_phone']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-xxx">Asbestos Report</label>
    <div class="controls">
      <input type="text" id="p-xxx" class="string edit" value="<?=$project['xxx']?>">
    </div>
</div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="r-approval_date">REC Approval Date</label>
    <div class="controls">
      <input type="text" id="r-approval_date" class="date edit" value="<?=$real_estate['approval_date']?>">
    </div>
</div>
</td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-dsl_install">DSL Install</label>
    <div class="controls">
      <input type="text" id="p-dsl_install" class="date edit" value="<?=$project['dsl_install']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-project_type">Project Type</label>
    <div class="controls">
      <select class="edit" id="p-project_type">
      	<option value="<?=$project['project_type']?>"><?=ucwords($project['project_type'])?></option>
      	<option value="" disabled="disabled">--</option>
      	<option value="new">New</option>
      	<option value="relocation">Relocation</option>
      	<option value="remodel">Remodel</option>
      	<option value="other">Other</option>
      </select>
    </div>
</div>
</td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-register_delivery">Register Delivery</label>
    <div class="controls">
      <input type="text" id="p-register_delivery" class="date edit" value="<?=$project['register_delivery']?>">

    </div>
</div>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-full_price_or_outlet">Mall/Outlet</label>
    <div class="controls">
      <select class="edit" id="p-full_price_or_outlet">
      	<option value="<?=$project['full_price_or_outlet']?>"><?=ucwords($project['full_price_or_outlet'])?></option>
      	<option value="" disabled="disabled">--</option>      	
      	<option value="full_price">Full Price</option>
      	<option value="outlet">Outlet</option>
      </select>
    </div>
</div>
</td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-register_install">Register Install</label>
    <div class="controls">
      <input type="text" id="p-register_install" class="date edit" value="<?=$project['register_install']?>">

    </div>
</div>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="s-possesion_date">Possession Date</label>
    <div class="controls">
      <input type="text" id="s-possesion_date" class="date edit" value="<?=$scheduled['possesion_date']?>">
    </div>
</div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="r-space_redemise">Space Redemise Y/N</label>
    <div class="controls">
      <select class="edit" id="r-space_redemise">
      	<option value="<?=$storedesign['center_cashwrap']?>"><?=$real_estate['space_redemise'] == 1 ? 'Yes' : 'No' ?></option>
      	<option value="" disabled="disabled">--</option>      	
      	<option value="1">Yes</option>
      	<option value="0">No</option>
      </select>
    </div>
</div>
</td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-fixtures_date">Fixtures Delivery Date</label>
    <div class="controls">
      <input type="text" id="p-fixtures_date" class="date edit" value="<?=$project['fixtures_date']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="r-location_description_placement">Store Location w/in Center</label>
    <div class="controls">
      <input type="text" id="r-location_description_placement" class="string edit" value="<?=$real_estate['location_description_placement']?>">
    </div>
</div>
</td>
<td></td>
</tr>
<tr>
<td></td>
<td><strong>DDS/Merchandise</strong></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-landlord">Landlord</label>
    <div class="controls">
      <input type="text" id="p-landlord" class="string edit" value="<?=$project['landlord']?>">
    </div>
</div>
</td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-carpentry_supplies_date">Carpentry Supplies</label>
    <div class="controls">
      <input type="text" id="p-carpentry_supplies_date" class="date edit" value="<?=$project['carpentry_supplies_date']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-landlord_contact">Landlord Contact</label>
    <div class="controls">
      <input type="text" id="p-landlord_contact" class="string edit" value="<?=$project['landlord_contact']?>">
    </div>
</div>
</td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-visual_marketing_delivery_date">Visual Marketing Delivery</label>
    <div class="controls">
      <input type="text" id="p-visual_marketing_delivery_date" class="date edit" value="<?=$project['visual_marketing_delivery_date']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-landlord_phone">Landlord Phone</label>
    <div class="controls">
      <input type="text" id="p-landlord_phone" class="string edit" value="<?=$project['landlord_phone']?>">
    </div>
</div>
</td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-dds_date">DDS Supplies Delivery</label>
    <div class="controls">
      <input type="text" id="p-dds_date" class="date edit" value="<?=$project['dds_date']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-landlord_fax">LL Fax</label>
    <div class="controls">
      <input type="text" id="p-landlord_fax" class="string edit" value="<?=$project['landlord_fax']?>">
    </div>
</div>
</td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-merchandise_date">Merchandise Delivery</label>
    <div class="controls">
      <input type="text" id="p-merchandise_date" class="date edit" value="<?=$project['merchandise_date']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-tenant_coordinator">Tenant Coordinator</label>
    <div class="controls">
      <input type="text" id="p-tenant_coordinator" class="string edit" value="<?=$project['tenant_coordinator']?>">
    </div>
</div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-tenant_coordinator_phone">TC Phone</label>
    <div class="controls">
      <input type="text" id="p-tenant_coordinator_phone" class="string edit" value="<?=$project['tenant_coordinator_phone']?>">
    </div>
</div>
</td>
<td>
  <div class="control-group">
    <label class="control-label" for="s-start_construction">Construction Start Date</label>
    <div class="controls">
      <input type="text" id="s-start_construction" class="date edit" value="<?=$scheduled['start_construction']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td></td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-construction_completion_date">Construction Completion Date</label>
    <div class="controls">
      <input type="text" id="s-construction_completion_date" class="date edit" value="<?=$project['construction_completion_date']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-mall_address">Mall Address</label>
    <div class="controls">
      <input type="text" id="p-mall_address" class="string edit" value="<?=$project['mall_address']?>">
    </div>
</div>
</td>
<td>
  <div class="control-group">
    <label class="control-label" for="s-store_punch">Punch/Turnover Date</label>
    <div class="controls">
      <input type="text" id="s-store_punch" class="date edit" value="<?=$scheduled['store_punch']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td></td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-closed_for_merchandise">Close Store(Remodel/Relo only)</label>
    <div class="controls">
      <input type="text" id="p-closed_for_merchandise" class="date edit" value="<?=$project['closed_for_merchandise']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-mall_manager">Mall Manager</label>
    <div class="controls">
      <input type="text" id="p-mall_manager" class="string edit" value="<?=$project['mall_manager']?>">
    </div>
</div>
</td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-store_opening_date">Store Open Date</label>
    <div class="controls">
      <input type="text" id="p-store_opening_date" class="date edit" value="<?=$project['store_opening_date']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-mall_marketing_director">Marketing Director</label>
    <div class="controls">
      <input type="text" id="p-mall_marketing_director" class="string edit" value="<?=$project['mall_marketing_director']?>">
    </div>
</div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-mall_facilities_manager">Facilities Manager</label>
    <div class="controls">
      <input type="text" id="p-mall_facilities_manager" class="string edit" value="<?=$project['mall_facilities_manager']?>">
    </div>
</div>
</td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-regional_vp">Regional Vice President</label>
    <div class="controls">
      <input type="text" id="p-regional_vp" class="string edit" value="<?=$project['regional_vp']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-mall_phone">Mall Phone</label>
    <div class="controls">
      <input type="text" id="p-mall_phone" class="string edit" value="<?=$project['mall_phone']?>">
    </div>
</div>
</td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-district_manager">District Manager</label>
    <div class="controls">
      <input type="text" id="p-district_manager" class="string edit" value="<?=$project['district_manager']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-mall_fax">Mall Fax</label>
    <div class="controls">
      <input type="text" id="p-mall_fax" class="string edit" value="<?=$project['mall_fax']?>">
    </div>
</div>
</td>
<td></td>
</tr>
<tr>
<td></td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-sitephone">Store Phone</label>
    <div class="controls">
      <input type="text" id="p-sitephone" class="string edit" value="<?=$project['sitephone']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td><strong>Construction Purposes Only</strong></td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-sitefax">Store Fax</label>
    <div class="controls">
      <input type="text" id="p-sitefax" class="string edit" value="<?=$project['sitefax']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="r-rec_approved_sqft">REC Approved SQFT</label>
    <div class="controls">
      <input type="text" id="r-rec_approved_sqft" class="num edit" value="<?=$real_estate['rec_approved_sqft']?>">
    </div>
  </div>

</td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-siterollover">Rollover Line</label>
    <div class="controls">
      <input type="text" id="p-siterollover" class="string edit" value="<?=$project['siterollover']?>">
    </div>
</div>
</td>
</tr>

<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="r-lease_sqft">Lease Sq. Ft.</label>
    <div class="controls">
      <input type="text" id="r-lease_sqft" class="string edit" value="<?=$real_estate['lease_sqft']?>">
    </div>
</div>
</td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-sitemodem">Modem</label>
    <div class="controls">
      <input type="text" id="p-sitemodem" class="string edit" value="<?=$project['sitemodem']?>">
    </div>
</div>

</td>
</tr>

<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="r-gross_sqft_plan">Gross SQFT (Plan)</label>
    <div class="controls">
      <input type="text" id="r-gross_sqft_plan" class="num edit" value="<?=$real_estate['gross_sqft_plan']?>">
    </div>
  </div>
</td>
<td>

</td>
</tr>


<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="r-sales_area_sqft">Sales Area SQFT</label>
    <div class="controls">
      <input type="text" id="r-sales_area_sqft" class="num edit" value="<?=$real_estate['sales_area_sqft']?>">
    </div>
  </div>
</td>
<td>

</td>
</tr>



<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-architect">Architect</label>
    <div class="controls">
      <input type="text" id="p-architect" class="string edit" value="<?=$project['architect']?>">
    </div>
</div>
</td>
<td>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-engineer">Engineer</label>
    <div class="controls">
      <input type="text" id="p-engineer" class="string edit" value="<?=$project['engineer']?>">
    </div>
</div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="s-tenant_criteria_received">Criteria Handbook Rec'd</label>
    <div class="controls">
      <input type="text" id="s-tenant_criteria_received" class="date edit" value="<?=$project['tenant_criteria_received']?>">
    </div>
</div>
</td>
<td>
  <div class="control-group">
    <label class="control-label" for="p-schedule_notes">GENERAL COMMENTS</label>
    <div class="controls">
      <input type="text" id="p-schedule_notes" class="string edit" value="<?=$project['schedule_notes']?>">
    </div>
</div>
</td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="s-lod_received">LOD Rec'd</label>
    <div class="controls">
      <input type="text" id="s-lod_received" class="date edit" value="<?=$scheduled['lod_received']?>">
    </div>
</div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="s-construction_exhibit_received">As-Built Plans Rec'd</label>
    <div class="controls">
      <input type="text" id="s-construction_exhibit_received" class="date edit" value="<?=$scheduled['lod_received']?>">
    </div>
    
    </td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="s-survey_uploaded">Survey Report Rec'd</label>
    <div class="controls">
      <input type="text" id="s-survey_uploaded" class="date edit" value="<?=$scheduled['survey_uploaded']?>">
    </div>
    </td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="s-landlord_approval">LL Approval Req'd yes, rec'd</label>
    <div class="controls">
      <input type="text" id="s-landlord_approval" class="date edit" value="<?=$scheduled['landlord_approval']?>">
    </div>
</div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-signage_vendor">Signage Vendor</label>
    <div class="controls">
      <input type="text" id="p-signage_vendor" class="string edit" value="<?=$project['signage_vendor']?>">
    </div>
</div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-xxx">Sign Shop Drawings LL</label>
    <div class="controls">
      <input type="text" id="p-xxx" class="string edit" value="<?=$project['xxx']?>">
    </div>
</div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-xxx">Sign Shop Drawings App.</label>
    <div class="controls">
      <input type="text" id="p-xxx" class="string edit" value="<?=$project['xxx']?>">
    </div>
</div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-permit_expeditor">Expeditor</label>
    <div class="controls">
      <input type="text" id="p-permit_expeditor" class="string edit" value="<?=$project['permit_expeditor']?>">
    </div>
</div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="s-submit_for_permit">Date Submitted</label>
    <div class="controls">
      <input type="text" id="s-submit_for_permit" class="date edit" value="<?=$project['submit_for_permit']?>">
    </div>
</div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="s-permit_received">Date Issued</label>
    <div class="controls">
      <input type="text" id="s-permit_received" class="date edit" value="<?=$project['permit_received']?>">
    </div>
</div>
</td>
<td></td>
</tr>
<tr>
<td><strong>New Store Attributes</strong></td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="d-keys_cores">Keys & Cores (Int./Ext.)</label>
    <div class="controls">
      <input type="text" id="d-keys_cores" class="string edit" value="<?=$design['keys_cores']?>">
    </div>
</div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="d-graphics">Graphics</label>
    <div class="controls">
      <input type="text" id="d-graphics" class="string edit" value="<?=$design['graphics']?>">
    </div>
</div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="d-fitting_rooms">Fitting Rooms</label>
    <div class="controls">
      <input type="text" id="d-fitting_rooms" class="string edit" value="<?=$design['fitting_rooms']?>">

    </div>
</div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="d-registers">Registers</label>
    <div class="controls">
      <input type="text" id="d-registers" class="string edit" value="<?=$design['registers']?>">
    </div>
</div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="d-restrooms">Restrooms</label>
    <div class="controls">
      <input type="text" id="d-restrooms" class="string edit" value="<?=$design['restrooms']?>">
    </div>
</div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="d-selling_sf_sales_area_ratio">Selling SF = Sales Area Ratio</label>
    <div class="controls">
      <input type="text" id="d-selling_sf_sales_area_ratio" class="string edit" value="<?=$design['selling_sf_sales_area_ratio']?>">
    </div>
</div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="d-selling_sf_sales_area_ratio">Sales Area Sq. Ft.</label>
    <div class="controls">
      <input type="text" id="d-selling_sf_sales_area_ratio" class="string edit" value="<?=$design['selling_sf_sales_area_ratio']?>">
    </div>
  </div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="d-barricade_info">Barricade Info</label>
    <div class="controls">
      <input type="text" id="d-barricade_info" class="string edit" value="<?=$design['barricade_info']?>">
    </div>
  </div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="d-floor_finish">Flooring Type</label>
    <div class="controls">
      <input type="text" id="d-floor_finish" class="string edit" value="<?=$design['floor_finish']?>">
    </div>
  </div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="d-shoe_stock_capacity">Shoe Stock Capacity</label>
    <div class="controls">
      <input type="text" id="d-shoe_stock_capacity" class="string edit" value="<?=$design['shoe_stock_capacity']?>">
    </div>
  </div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="d-cashwrap_location">Cashwrap Location</label>
    <div class="controls">
      <input type="text" id="d-cashwrap_location" class="string edit" value="<?=$design['cashwrap_location']?>">
    </div>
  </div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="d-hvac">HVAC</label>
    <div class="controls">
      <input type="text" id="d-hvac" class="string edit" value="<?=$design['hvac']?>">
    </div>
  </div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="d-union">Union / Non-Union</label>
    <div class="controls">
      <select class="edit" id="d-union">
      	<option value="<?=$design['union']?>"><?=$design['union'] == 1 ? 'Yes' : 'No' ?></option>
      	<option value="" disabled="disabled">--</option>      	
      	<option value="1">Yes</option>
      	<option value="0">No</option>
      </select>
    </div>
  </div>
</td>
<td></td>
</tr>
<tr>
<td>
  <div class="control-group">
    <label class="control-label" for="p-type_of_store">Design Type</label>
    <div class="controls">
      <select class="edit" id="p-type_of_store">
      	<option value="<?=$project['type_of_store']?>"><?=$project['type_of_store']?></option>
      	<option value="" disabled="disabled">--</option>      	
      	<option value="Fashion Valley">Fashion Valley</option>
      	<option value="Standard (Rampage)">Standard (Rampage)</option>
      	<option value="Gen II">Gen II</option> 
      	<option value="Russell Williams">Russell Williams</option>
      	<option value=""></option>      	
      </select>
    </div>
  </div>
</td>
<td></td>
</tr>
</table>
</form>
</div>
</body>
</html>
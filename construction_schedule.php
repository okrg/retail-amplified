<?php
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', '1');
include("include/access.php");
include("little-helpers.php");
if (isset($_GET['print'])) {$print = TRUE;} else {$print = FALSE;}

dbConnect();


function loadData($table,$id) {
	global $dbcnx;
	$query = "SELECT * FROM $table WHERE project_id = $id";
	$result = mysqli_query($dbcnx, $query) or die ("no query");	
	$data = array();
	
	while($row = mysqli_fetch_assoc($result)) {
	    $data[] = $row;
	}
	if ($data){
		return $data[0];
	} else {
		return false;
	}
}	


	//Vendor filter, vendors only see projects that have them tagged as vendors for that project
	if ($usergroup == 3)  {
		$vendor_filter = " AND `companyarray` LIKE '%:\"".$usercompany."\";%'";
	} else {
		$vendor_filter = NULL;
	}




	$query = "SELECT id,store_number FROM projects WHERE (project_status = 'active' or project_status = 'proposed') ".$vendor_filter." ORDER BY CASE WHEN store_opening_date = '0000-00-00' THEN 2 ELSE 1 END, store_opening_date ASC, sitename ASC";

	$result = mysqli_query($dbcnx, $query) or die ("no query");	
	$stores = array();
	$index = 0;
	while($row = mysqli_fetch_assoc($result)) {
	    $stores[$index]['id'] = $row['id'];
	    
	    $stores[$index]['store_number'] = intval($row['store_number']);
	    
	    if(intval($row['store_number']) == 0){
		    unset($stores[$index]);
		    continue;
	    }

    	$subquery = "SELECT * FROM projects WHERE id = ".$row['id'];
    	$sresult = mysqli_query($dbcnx, $subquery) or die ("no query");	
    	$subdata = array();
    	while($srow = mysqli_fetch_assoc($sresult)) {
	    	$subdata[] = $srow;
	    	}

	    $stores[$index]['data'] = $subdata[0];
	    $stores[$index]['realestate'] = loadData('realestate', $row['id']);
	    $stores[$index]['actual'] = loadData('actual_dates', $row['id']);
	    $stores[$index]['scheduled'] = loadData('scheduled_dates', $row['id']);
	    $stores[$index]['centerinfo'] = loadData('re_centerinfo', $row['id']);
	    
	    
	    //Determine if there have been recent changes for this store
	    $changes_query = "SELECT * FROM changes WHERE project_id = ".$row['id']." and field_id in ('s-start_construction', 'p-store_opening_date','p-schedule_notes')";
    	$changes_result = mysqli_query($dbcnx, $changes_query) or die ("no query");	
    	if(mysqli_num_rows($changes_result) > 0 ) {
	    	$stores[$index]['change_status'] = 'changed';
    	} else {
	    	$stores[$index]['change_status'] = 'static';
    	}

	    $index++;
	}
	
	
	//get summary counts
	$q = "SELECT count(id) FROM projects WHERE (project_status = 'active' or project_status = 'proposed') AND YEAR(`store_opening_date`) = '2013' AND project_type = 'new'";
	$r = mysqli_query($dbcnx, $q) or die(mysqli_error($dbcnx));
	$newFY13 = mysqli_result($r, 0); 

	$q = "SELECT count(id) FROM projects WHERE (project_status = 'active' or project_status = 'proposed') AND YEAR(`store_opening_date`) = '2013' AND project_type = 'relocation'";
	$r = mysqli_query($dbcnx, $q) or die(mysqli_error($dbcnx));
	$relocationsFY13 = mysqli_result($r, 0);

	$q = "SELECT count(id) FROM projects WHERE (project_status = 'active' or project_status = 'proposed') AND YEAR(`store_opening_date`) = '2013' AND project_type = 'remodel'";
	$r = mysqli_query($dbcnx, $q) or die(mysqli_error($dbcnx));
	$remodelsFY13 = mysqli_result($r, 0);	

	$q = "SELECT count(id) FROM projects WHERE (project_status = 'active' or project_status = 'proposed') AND YEAR(`store_opening_date`) = '2013'";
	$r = mysqli_query($dbcnx, $q) or die(mysqli_error($dbcnx));
	$totalFY13 = mysqli_result($r, 0); 

	
	$q = "SELECT count(id) FROM projects WHERE (project_status = 'active' or project_status = 'proposed') AND YEAR(`store_opening_date`) = '2014' AND project_type = 'new'";
	$r = mysqli_query($dbcnx, $q) or die(mysqli_error($dbcnx));
	$newFY14 = mysqli_result($r, 0); 

	$q = "SELECT count(id) FROM projects WHERE (project_status = 'active' or project_status = 'proposed') AND YEAR(`store_opening_date`) = '2014' AND project_type = 'relocation'";
	$r = mysqli_query($dbcnx, $q) or die(mysqli_error($dbcnx));
	$relocationsFY14 = mysqli_result($r, 0);

	$q = "SELECT count(id) FROM projects WHERE (project_status = 'active' or project_status = 'proposed') AND YEAR(`store_opening_date`) = '2014' AND project_type = 'remodel'";
	$r = mysqli_query($dbcnx, $q) or die(mysqli_error($dbcnx));
	$remodelsFY14 = mysqli_result($r, 0);	
		
	$q = "SELECT count(id) FROM projects WHERE (project_status = 'active' or project_status = 'proposed') AND YEAR(`store_opening_date`) = '2014'";
	$r = mysqli_query($dbcnx, $q) or die(mysqli_error($dbcnx));
	$totalFY14 = mysqli_result($r, 0); 




?>
<!doctype html>
<html lang="en">
<head>
  <title>Collaboration Network</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <script type="text/javascript" src="/dist/js/site.js"></script>
  <link rel="stylesheet" href="/dist/css/plugins.css">
  <link rel="stylesheet" href="/dist/css/screen.css">
<style type="text/css">
body {background: none;color:#333;}
form {margin:0;}
table.table {margin:10px;width:98%;}
.table th, .table td {padding:2px;}
th.centered, td.centered {text-align:center;}
tr.quarter-header td {background:#ccc;padding:4px; font-weight: bold;}
#content{background: none;border:none;margin-top:30px;color:#333;}
#field-btns {margin-left:0px;margin-top:0;position:absolute;top:0;right:0px;z-index:10000;}
#field-cancel{text-decoration: underline;}
#ui-datepicker-div {z-index: 9999 !important;}
.ui-datepicker-calendar thead span {color:#666;}

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
#content {margin:0;}
</style>

<script>

$(document).ready(function() {

var hidden = [];
<?php 

if (isset($_GET['h'])) {
	foreach($_GET['h'] as $hidden) {
		print "hidden.push($hidden);";
	}
}
?>






	function countProjects(){
		$('#hidden-projects').html( $('.columns>div.hidden').length + ' hidden');
		$('#total-projects').html( $('.columns>div.project').length + ' projects');
		if( $('.columns>div.hidden').length > 0  ) {
			$('#reset-projects').show();
		}
	}




	function closeField() {
		$('.project div.active').each(function() {
			$(this).html( $(this).attr('data-init-value') );
			$(this).removeClass('active');
		});

		$('#field-btns').remove();
	}
	
	function insertFieldBtns() {
			//Insert field buttons
			$('<div id="field-btns" />').insertAfter( $('.project div.active') );
			$('#field-btns').append('<a class="btn btn-mini btn-success" id="field-save"><i class="icon-white icon-ok"></i></a>');
			$('#field-btns').append('<a id="field-cancel">Cancel</a>');

	}

<?php  if (!$print): ?>

		$('.columns').mousewheel(function(event, delta) {
			this.scrollLeft -= (delta * 30);
			event.preventDefault();
		});
		
	    var timer;
	    $(window).bind('scroll',function () {
	        clearTimeout(timer);
	        timer = setTimeout( refresh , 150 );
	    });
	    var refresh = function () { 
	        // do stuff
			$('.header-group').css( { 
				'top': ( $(document).scrollTop() - 2)
				});
	    };
			


		
<?php endif; ?>
		
	<?php if($usergroup < 1): ?>	
		 $('.project div.string').click(function() {
		 	if( $(this).hasClass('active') ) {
			 	return false;
		 	}
			closeField();
		 	$(this).addClass('active');
			insertFieldBtns();
			$(this).html('<input type="text" value="'+$(this).attr('data-init-value')+'">');
			if( $(this).hasClass('money') ) {
				$(this).children('input').autoNumeric( {aSep: ',', aDec: '.', aSign: '$'} ).autoNumericSet( $(this).attr('data-init-value') );
		 	}
			if( $(this).hasClass('number') ) {
				$(this).children('input').autoNumeric( {aSep: '', aPad: false, vMax: '999999'} ).autoNumericSet( $(this).attr('data-init-value') );

		 	}

			$(this).children('input').focus();
		 });

		 $('.project div.date').click(function() {
		 	if( $(this).hasClass('active') ) {
			 	return false;
		 	}
			closeField();
		 	$(this).addClass('active');
			insertFieldBtns();
			$(this).html('<input type="text" class="date" value="'+$(this).attr('data-init-value')+'">');
			$(this).find('input.date').datepicker({
    			showAnim:"slideDown"
    		}).focus();

		 });

/*
    	$('table.ui-datepicker-calendar td a').live('mouseup', function() {
			setTimeout(function() {
	    	$('#field-save').trigger('mouseup');
			}, 500);    		

    	})
*/


	$('#field-cancel').live('mouseup', function(e) {
		e.stopPropagation();
		closeField();
	});


	$('#field-save').live('mouseup', function(e) {
		e.stopPropagation();
		var field_id  = $(this).parent().prev('div.active').attr('data-field');
		var pid = $(this).closest('.project').attr('data-id');
		var column = $(this).parent().prev('div.active').attr('data-field').substring(2);
		var thisinput = $(this).parent().prev('.active').find('input');
		if ( $(this).parent().prev('.active').hasClass('money') ) {
			var newval = $(this).parent().prev('div.active').find('input').autoNumericGet();
		} else {	
			var newval = $(this).parent().prev('div.active').find('input').val();
		}
		
		switch( $(this).parent().prev('div.active').attr('data-field').charAt(0) ) {
			case 'r':
				var table = 'realestate';
				break;
			case 't':
				var table = 're_strategy';
				break;
			case 'd':
				var table = 're_storedesign';
				break;
			case 'c':
				var table = 're_centerinfo';
				break;
			case 'o':
				var table = 're_options';
				break;
			case 'k':
				var table = 're_kickouts';
				break;
			case 'a':
				var table = 'actual_dates';
				break;			
			case 's': 
				var table = 'scheduled_dates';
				break;
			case 'p': 
				var table = 'projects';
				break;			
		}
		
		//Validate new value?

		//Save new value via ajax function to ajax_field_save.php
		$.ajax({
	        type: "POST",
	        url: "ajax_field_save.php",
	        data: "id="+encodeURIComponent(pid)+"&table=" + encodeURIComponent(table) + "&column=" + encodeURIComponent(column) + "&value=" + encodeURIComponent(newval) + "&field_id=" + encodeURIComponent(field_id),
	        success: function(response) {
	            if(response === '1') {
					$(thisinput).parent().attr('data-init-value', newval );
					closeField();
					$('#ajax-message').html('<div class="alert alert-success">Saved Successfully!</div>').show().delay('2000').fadeOut('500');
					$(thisinput).closest('.project').addClass('changed');
	            } else {
	                $('#ajax-message').html('<div class="alert alert-error">Error:'+response+'</div>').show().delay('2000').fadeOut('500');
	                closeField();
	            }
	        }
	    });
		
		
		
	});

<?php endif; ?>
	countProjects();

});
</script>

</head>

<body>
<div id="ajax-message"></div>

<div class="container-fluid">

	<div class="row-fluid">
		<div class="span3">
			<table class="table table-striped table-bordered">
				<tr>
					<th colspan="2">FY2013 Construction Summary</th>
				</tr>
				<tr>
					<td>New Stores</td>
					<td><?=$newFY13?></td>
				</tr>
				<tr>
					<td>Relocations</td>
					<td><?=$relocationsFY13?></td>
				</tr>
				<tr>
					<td>Remodels</td>
					<td><?=$remodelsFY13?></td>
				</tr>								
				<tr>
					<td>Total</td>
					<td><?=$totalFY13?></td>
				</tr>	
			</table>
		</div>
		<div class="span3">
			<table class="table table-striped table-bordered">
				<tr>
					<th colspan="2">FY2014 Construction Summary</th>
				</tr>
				<tr>
					<td>New Stores</td>
					<td><?=$newFY14?></td>
				</tr>
				<tr>
					<td>Relocations</td>
					<td><?=$relocationsFY14?></td>
				</tr>
				<tr>
					<td>Remodels</td>
					<td><?=$remodelsFY14?></td>
				</tr>								
				<tr>
					<td>Total</td>
					<td><?=$totalFY14?></td>
				</tr>	
			</table>						
		</div>
		<div class="span6">
		</div>
	</div>


	<table class="table table-bordered">
	<thead>
		<tr>
		<th class="centered">#</th>
		<th>Store</th>
		<th class="centered">Outlet/<br />Mall</th>
		<th class="centered">State</th>
		<th class="centered">Construction<br />Type</th>
		<th class="centered">Temp/<br />No Temp<br />/Phased</th>
		<th class="centered">Construction Start</th>
		<th class="centered">Open Date</th>
		<th>Notes</th>
		</tr>
	</thead>
	<tbody>
		
	
<?php 
	$index = 0;	

	foreach($stores as $store):	
	$pq = FALSE;
	
	//Get date and determine what Quarter and year
	$d = strtotime($store['data']['store_opening_date']);
	$q = 'Q' . ceil(date("m", $d)/3) . ' ' . date("Y", $d);

	//Set first qrow if its not already set
	if(!isset($qrow)){
		$qrow = $q;
		$pq = TRUE;
	}
	
	//Compare q to qrow, if no match, assume new q row and set pq true
	if($q != $qrow) {
		$pq = TRUE;
		$qrow = $q;
	}
	
	if ($q == 'Q4 -0001') { $q = "TBD";}
	
	if ($pq) {
		print '<tr class="quarter-header"><td colspan="9">'.$q.'</td></tr>';
	}

?>
<tr class="project <?=$store['change_status']?>" data-id="<?=$store['id']?>">	
	<td class="centered"><a href="/index.php?page=project&id=<?=$store['id']?>"><?=$store['store_number']?></a></td>
	<td><?=$store['data']['sitename']?></td>
	<td class="centered"><div data-field="c-center_type" class="string"><?=$store['centerinfo']['center_type']?></div></td>
	<td class="centered"><div data-field="p-sitestate" class="state string"><?=$store['data']['sitestate']?></div></td>
	<td class="centered"><div><?=$store['data']['project_type']?></div></td>
	<td class="centered"><div><?=$store['data']['temp_status']?></div></td>
	<td class="centered" style="position:relative;"><div class="date" data-field="s-start_construction" data-init-value="<?=dateFormat($store['scheduled']['start_construction'])?>"><?=dateFormat($store['scheduled']['start_construction'])?></div></td>
	<td class="centered" style="position:relative;"><div class="date" data-field="p-store_opening_date" data-init-value="<?=dateFormat($store['data']['store_opening_date'])?>"><?=dateFormat($store['data']['store_opening_date'])?></div></td>
	<td style="position:relative;width:49%"><div data-field="p-schedule_notes" class="string" data-init-value="<?=$store['data']['schedule_notes']?>"><?=$store['data']['schedule_notes']?>...</div></td>


</tr>
<?php
	$index++;
	endforeach;
?>
</tbody>
</table>
</div>
</body>
</html>
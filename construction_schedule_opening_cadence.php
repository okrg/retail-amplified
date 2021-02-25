<?php
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', '1');
include("include/access.php");
include("little-helpers.php");
if (isset($_GET['print'])) {$print = TRUE;} else {$print = FALSE;}

	//Vendor filter, vendors only see projects that have them tagged as vendors for that project
	if ($usergroup == 3)  {
		$vendor_filter = " AND `companyarray` LIKE '%:\"".$usercompany."\";%'";
	} else {
		$vendor_filter = NULL;
	}


function cadenceSummary($region, $month,$year) {
	global $dbcnx;
	global $vendor_filter;
	$q = "SELECT count(id) FROM projects WHERE (project_status = 'active' or project_status = 'proposed') and MONTH(store_opening_date) = '$month'  and YEAR(store_opening_date) = '$year' and store_region = $region".$vendor_filter;
	$r = mysqli_query($dbcnx, $q) or die(mysqli_error($dbcnx));
	return mysqli_result($r, 0); 	
}	

function cadenceTotals($type,$month,$year) {
	global $dbcnx;
	global $vendor_filter;
	$q = "SELECT count(id) FROM projects WHERE (project_status = 'active' or project_status = 'proposed') and MONTH(store_opening_date) = '$month'  and YEAR(store_opening_date) = '$year' and project_type LIKE '$type'".$vendor_filter;
	$r = mysqli_query($dbcnx, $q) or die(mysqli_error($dbcnx));
	return mysqli_result($r, 0); 		
}


function cadence($month, $year) {
	global $dbcnx;
	global $vendor_filter;
	$index = 0;	
	$query = "SELECT id,store_number FROM projects WHERE (project_status = 'active' or project_status = 'proposed') and MONTH(store_opening_date) = '$month'  and YEAR(store_opening_date) = '$year' ".$vendor_filter." ORDER BY store_opening_date ASC";
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


    	$sched_query = "SELECT * FROM scheduled_dates WHERE project_id = ".$row['id'];
    	$sched_result = mysqli_query($dbcnx, $sched_query) or die ("no query");	
    	$sched_data = array();
    	while($sched_row = mysqli_fetch_assoc($sched_result)) {
	    	$sched_data[] = $sched_row;
	    	}
	    $stores[$index]['scheduled'] = $sched_data[0];





	    if($stores[$index]['data']['project_status'] == 'proposed') {
		    $proposed = TRUE;
	    } else {
		    $proposed = FALSE;
	    }

	    if($stores[$index]['data']['grand_opening'] == 1) {
		    $grand_opening = TRUE;
	    } else {
		    $grand_opening = FALSE;
	    }
	    
	    
	    
	    $html .= '<table class="table table-bordered '.strtolower($stores[$index]['data']['project_type']).'">';
	    
	    if (isset($_GET['print']) && $_GET['print']==1) {
		$html .= '<tr><td>'.$stores[$index]['store_number'].'</td>';    		    
	    } else {
		$html .= '<tr><td><a href="/index.php?page=project&id='.$stores[$index]['id'].'">'.$stores[$index]['store_number'].'</a></td>';    
	    }


	    
	    $html .= '<td>'.$stores[$index]['data']['sitename'].' '.$stores[$index]['data']['sitestate'].'</td>';
	    $html .= '<td>R'.intval($stores[$index]['data']['store_region']).'</td></tr>';
	    $html .= '<tr><td class="'.$go_class.'" colspan="2">';
	    
	    if (isset($_GET['print']) && $_GET['print']==1) {
	    	//Do nothing
	    } else {
			$html .= '<a class="mini-pane-expand btn btn-mini pull-right"><span class="symbol">+</span></a>';
	    }	    

	    if($proposed)  {
		    $html .= '<span class="label label-warning">P</span>&nbsp;';
	    }
	    
	    $html .= 'Open: '.dateFormat($stores[$index]['data']['store_opening_date']);
	    if($grand_opening)  {
		    $html .= '&nbsp;<span class="label label-info">GO</span>';
	    }
	    if (isset($_GET['print']) && $_GET['print']==1) {
	    	//Do nothing
	    } else {
	    	$html .= '<div class="mini-pane closed"><div class="mini-pane-content">';
	    }

		$html .= '<div><strong>Start Date:</strong> '.dateFormat($stores[$index]['scheduled']['start_construction']).'</div>';
		$html .= '<div><strong>CR PM:</strong> '.$stores[$index]['data']['cr_project_manager'].'</div>';
		$html .= '<div><strong>Arch:</strong> '.$stores[$index]['data']['architect'].'</div>';
	    
	    if (isset($_GET['print']) && $_GET['print']==1) {
	    	//Do nothing
	    } else {
	    	$html .= '</div></div>';
	    }

	    $html .= '</td>';	    
	    $html .= '<td>D'.intval($stores[$index]['data']['store_district']).'</td></tr>';
	    $html .= '</table>';

	    $index++;

	}
	
	
	//Get summary counts
	$html .= '<table class="table cadence-summary table-bordered">';
	$html .= '<tr><th>Region</th><th>Stores</th></tr>';
	$html .= '<tr><td>1</td><td>'.cadenceSummary(1, $month, $year).'</td></tr>';
	$html .= '<tr><td>2</td><td>'.cadenceSummary(2, $month, $year).'</td></tr>';
	$html .= '<tr><td>3</td><td>'.cadenceSummary(3, $month, $year).'</td></tr>';
	$html .= '<tr><td>4</td><td>'.cadenceSummary(4, $month, $year).'</td></tr>';
	$html .= '<tr><td colspan="2"></td></tr>';
	$html .= '<tr><td><strong>Stores Total</strong></td><td>'.cadenceTotals('%', $month, $year).'</td></tr>';
	$html .= '<tr><td><strong>Relocations</strong></td><td>'.cadenceTotals('relocation', $month, $year).'</td></tr>';
	$html .= '<tr><td><strong>Remodels</strong></td><td>'.cadenceTotals('remodel', $month, $year).'</td></tr>';		
	$html .= '</table>';

	
	
	return $html;


}





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
.table th, .table td {padding:2px;}
.table .table {font-size:10px;margin-bottom:5px;}
.table .table td {line-height: 13px;}
.table table.remodel {background:#dce5d1;}
.table table.relocation {background:#ced8e3;}
.table table.remodel,.table table.relocation,
.table table.remodel td, .table table.relocation td {border-color:#999;}

th.centered, td.centered {text-align:center;}
tr.quarter-header td {background:#ccc;padding:4px; font-weight: bold;}
td.col {width:150px;position:relative;padding:10px;}

<?php if (isset($_GET['print']) && $_GET['print']==1): ?>
/*td.col {width:30%;  }*/
table.quarter {margin-bottom:30px; width:90%;}
<?php endif; ?>
#content{background: none;border:none;margin-top:30px;color:#333;}
#field-btns {margin-left:0px;margin-top:0;position:absolute;top:0;left:150px;z-index:10000;}
#field-cancel{text-decoration: underline;}
#ui-datepicker-div {z-index: 9999 !important;}
.ui-datepicker-calendar thead span {color:#666;}
span.label {font-size:10px;}
<?php if($print): ?>
.columns {position:static;margin-left:150px;}
#total-projects,#hidden-projects {display:none;}
#print-view-btn,.header .close {display:none;}
#default-view-btn {display:block;}
<?php endif; ?>

</style>

<style type="text/css" media="print">
@page
{
	margin: 0;
}
#content {margin:0;}
#navbar,.navbar-inner, .nav {display: none;}
</style>

<script type="text/javascript">

$(document).ready(function() {

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
			$('<div id="field-btns" />').insertAfter( $('.project .line div.active') );
			$('#field-btns').append('<a class="btn btn-mini btn-success" id="field-save"><i class="icon-white icon-ok"></i></a>');
			$('#field-btns').append('<a id="field-cancel">Cancel</a>');

	}

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
	        data: "id="+encodeURIComponent(pid)+"&table=" + encodeURIComponent(table) + "&column=" + encodeURIComponent(column) + "&value=" + encodeURIComponent(newval),
	        success: function(response) {
	            if(response === '1') {
					$(thisinput).parent().attr('data-init-value', newval );
					closeField();
					$('#ajax-message').html('<div class="alert alert-success">Saved Successfully!</div>').show().delay('2000').fadeOut('500');
	            } else {
	                $('#ajax-message').html('<div class="alert alert-error">Error:'+response+'</div>').show().delay('2000').fadeOut('500');
	                closeField();
	            }
	        }
	    });
		
		
		
	});
/*
	$(document).live('click blur focus', function(e){ 
		if (!$(event.target).closest("#field-btns,.active, .ui-widget,.ui-datepicker-header").length) {
		closeField();
		}
	});	
*/
<?php endif; ?>
	countProjects();
	
	
			$('a.mini-pane-expand').click(function() {
			if( $(this).nextAll('.mini-pane').hasClass('closed') ) {
				$(this).nextAll('.mini-pane').find('.mini-pane-content').slideDown();
				$(this).children('.symbol').html('-');
				$(this).nextAll('.mini-pane').addClass('open').removeClass('closed')
			} else {
				$(this).nextAll('.mini-pane').find('.mini-pane-content').slideUp();
				$(this).children('.symbol').html('+');
				$(this).nextAll('.mini-pane').addClass('closed').removeClass('open')
				
			}
		});


});
</script>

</head>

<body>
<div id="ajax-message"></div>

<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
<ul class="navbar-nav mr-auto">
	<li class="nav-item"><a class="nav-link" href="index.php">&laquo; Back to Home</a></li>
	<li class="nav-item dropdown">
		<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Legend <b class="caret"></b></a>
		<div class="dropdown-menu">		
			<table style="width:220px;font-size:11px;" class="table table-bordered">
			<tr>
				<th colspan="2">Legend</th>
			</tr>
			<tr>
				<td style="width:24px;background:#3A87AD;"></td>
				<td>Grand Opening</td>
			</tr>
			
			<tr>
				<td style="width:24px;background:#FFFFFF;"></td>
				<td>New Store</td>
			</tr>
			<tr>
				<td style="background:#CED8E3;"></td>
				<td>Relocation</td>
			</tr>
			<tr>
				<td style="background:#DCE5D1;"></td>
				<td>Remodel</td>
			</tr>
			<tr>
				<td style="background:#F89406;"></td>
				<td>Proposed/Not REC Approved</td>
			</tr>			
			</table>
		</div>	
	</li>
		<?php if (isset($_GET['print']) && $_GET['print']==1): ?>
		<li class="nav-item"><a class="nav-link" href="?print=0"><i class="icon-list icon-white"></i> Default View</a></li>
		<?php else: ?>
		<li class="nav-item"><a class="nav-link" href="?print=1"><i class="icon-print icon-white"></i> Printable View</a></li>
		<?php endif; ?>
	</ul>
</nav>
<div class="wide-container">

	<?php //if (isset($_GET['print']) && $_GET['print']==1): ?>
	<?php 
	/*
	<table class="table table-bordered quarter" style="margin-top:50px;">
	<thead>
		<tr>
			<th colspan="3">Q1 FY 2013</th>
		</tr>
		<tr>
			<th>Feb</th>
			<th>Mar</th>
			<th>Apr</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="col"><?=cadence('2','2013') ?></td>
			<td class="col"><?=cadence('3','2013') ?></td>
			<td class="col"><?=cadence('4','2013') ?></td>
		</tr>
	</tbody>
	</table>	
	<table class="table table-bordered quarter">
	<thead>
		<tr>
			<th colspan="3">Q2 FY 2013</th>
		</tr>
		<tr>
			<th>May</th>
			<th>Jun</th>
			<th>Jul</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="col"><?=cadence('5','2013') ?></td>
			<td class="col"><?=cadence('6','2013') ?></td>
			<td class="col"><?=cadence('7','2013') ?></td>
		</tr>
	</tbody>
	</table>	
	<table class="table table-bordered quarter">
	<thead>
		<tr>
			<th colspan="3">Q3 FY 2013</th>
		</tr>
		<tr>
			<th>Aug</th>
			<th>Sep</th>
			<th>Oct</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="col"><?=cadence('8','2013') ?></td>
			<td class="col"><?=cadence('9','2013') ?></td>
			<td class="col"><?=cadence('10','2013') ?></td>
		</tr>
	</tbody>
	</table>	
	<table class="table table-bordered quarter">
	<thead>
		<tr>
			<th colspan="3">Q4 FY 2013</th>
		</tr>
		<tr>
			<th>Nov</th>
			<th>Dec</th>
			<th>Jan</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="col"><?=cadence('11','2013') ?></td>
			<td class="col"><?=cadence('12','2013') ?></td>
			<td class="col"><?=cadence('1','2014') ?></td>
		</tr>
	</tbody>
	</table>
	*/
	?>

	<?php //else: ?>
	<table class="table table-bordered quarter" style="margin-top:50px;width:2400px;max-width:2400px;">
	<thead>
		<tr>
			<th colspan="3">Q1 FY 2014</th>
			<th colspan="3">Q2 FY 2014</th>
			<th colspan="3">Q3 FY 2014</th>
			<th colspan="3">Q4 FY 2014</th>				
		</tr>
		<tr>
			<th>Feb</th>
			<th>Mar</th>
			<th>Apr</th>
			<th>May</th>
			<th>Jun</th>
			<th>Jul</th>
			<th>Aug</th>
			<th>Sep</th>
			<th>Oct</th>
			<th>Nov</th>
			<th>Dec</th>
			<th>Jan</th>			
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="col"><?=cadence('2','2014') ?></td>
			<td class="col"><?=cadence('3','2014') ?></td>
			<td class="col"><?=cadence('4','2014') ?></td>
			<td class="col"><?=cadence('5','2014') ?></td>
			<td class="col"><?=cadence('6','2014') ?></td>												
			<td class="col"><?=cadence('7','2014') ?></td>
			<td class="col"><?=cadence('8','2014') ?></td>
			<td class="col"><?=cadence('9','2014') ?></td>
			<td class="col"><?=cadence('10','2014') ?></td>
			<td class="col"><?=cadence('11','2014') ?></td>												
			<td class="col"><?=cadence('12','2014') ?></td>
			<td class="col"><?=cadence('1','2015') ?></td>
		</tr>
	</tbody>
	</table>

	<table class="table table-bordered quarter" style="margin-top:50px;width:2400px;max-width:2400px;">
	<thead>
		<tr>
			<th colspan="3">Q1 FY 2015</th>
			<th colspan="3">Q2 FY 2015</th>
			<th colspan="3">Q3 FY 2015</th>
			<th colspan="3">Q4 FY 2015</th>				
		</tr>
		<tr>
			<th>Feb</th>
			<th>Mar</th>
			<th>Apr</th>
			<th>May</th>
			<th>Jun</th>
			<th>Jul</th>
			<th>Aug</th>
			<th>Sep</th>
			<th>Oct</th>
			<th>Nov</th>
			<th>Dec</th>
			<th>Jan</th>			
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="col"><?=cadence('2','2015') ?></td>
			<td class="col"><?=cadence('3','2015') ?></td>
			<td class="col"><?=cadence('4','2015') ?></td>
			<td class="col"><?=cadence('5','2015') ?></td>
			<td class="col"><?=cadence('6','2015') ?></td>												
			<td class="col"><?=cadence('7','2015') ?></td>
			<td class="col"><?=cadence('8','2015') ?></td>
			<td class="col"><?=cadence('9','2015') ?></td>
			<td class="col"><?=cadence('10','2015') ?></td>
			<td class="col"><?=cadence('11','2015') ?></td>												
			<td class="col"><?=cadence('12','2015') ?></td>
			<td class="col"><?=cadence('1','2016') ?></td>
		</tr>
	</tbody>
	</table>

	<?php //endif; ?>

</div>
</body>
</html>
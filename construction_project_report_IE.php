<?php
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', '1');
include("include/access.php");
include("little-helpers.php");
if (isset($_GET['print'])) {$print = TRUE;} else {$print = FALSE;}

dbConnect();


function loadData($table,$id) {
	$query = "SELECT * FROM $table WHERE project_id = $id";
	$result = mysql_query($query) or die ("no query");	
	$data = array();
	
	while($row = mysql_fetch_assoc($result)) {
		$data[] = $row;
     }
	
	if ($data){
		return $data[0];
	} else {
		return false;
	}
}	

	$query = "SELECT id,store_number FROM projects WHERE project_status = 'active'";

	if(isset($_GET['view'])) {
		switch(strtolower($_GET['view'])) {
			case "new":
			$query = "SELECT id,store_number FROM projects WHERE project_type = 'new'";	
			break;

			case "relocations":
			$query = "SELECT id,store_number FROM projects WHERE project_type = 'relocation'";	
			break;

			case "remodels":
			$query = "SELECT id,store_number FROM projects WHERE project_type = 'remodel'";	
			break;
			
			case "other":
			$query = "SELECT id,store_number FROM projects WHERE project_type = 'other'";	
			break;
		}	
	}
	
	if(isset($_GET['filter'])) {
		$query = "SELECT id,store_number FROM projects WHERE ".$_GET['filter']." = '".$_GET['q']."'";	
	} 
	
	
	$result = mysql_query($query) or die ("no query");	
	$stores = array();
	$index = 0;
	while($row = mysql_fetch_assoc($result)) {
	    $stores[$index]['id'] = $row['id'];
	    
	    $stores[$index]['store_number'] = intval($row['store_number']);
	    
	    if(intval($row['store_number']) == 0){
		    unset($stores[$index]);
		    continue;
	    }

    	$subquery = "SELECT * FROM projects WHERE id = ".$row['id'];
    	$sresult = mysql_query($subquery) or die ("no query");	
    	$subdata = array();
    	while($srow = mysql_fetch_assoc($sresult)) {
	    	$subdata[] = $srow;
	    	}

	    $stores[$index]['data'] = $subdata[0];
	    $stores[$index]['realestate'] = loadData('realestate', $row['id']);
	    $stores[$index]['actual'] = loadData('actual_dates', $row['id']);
	    $stores[$index]['scheduled'] = loadData('scheduled_dates', $row['id']);
	    $stores[$index]['centerinfo'] = loadData('re_centerinfo', $row['id']);
	    /*	    
		$stores[$index]['strategy'] = loadData('re_strategy', $row['id']);
	    $stores[$index]['centerinfo'] = loadData('re_centerinfo', $row['id']);
	    $stores[$index]['kickouts'] = loadData('re_kickouts', $row['id']);
	    $stores[$index]['options'] = loadData('re_options', $row['id']);
	    */
	    $index++;
	}


$labels = <<< HTML
<div class="labels">
	<div class="header-group" id="summary">
		<div class="line">
			<div class="header">
				<span id="total-projects"></span>
			</div>
		</div>
		<div class="line">
			<div class="header">
				<span id="hidden-projects"></span>&nbsp;
				<a class="hide" href="#" id="reset-projects">Reset</a>
				
			</div>
		</div>		
	</div>
	<div class="line  superficial"><div class="header">&nbsp;</div></div>		
	<div class="line  superficial"><div class="header">&nbsp;</div></div>		
	<div class="line"><div>Address</div></div>
	<div class="line"><div>City</div></div>
	<div class="line"><div>Zip</div></div>
	<div class="line"><div>Region/District</div></div>
	<div class="line"><div>Sales Area Sq Ft.</div></div>
	<div class="line"><div>Center Type</div></div>
	<div class="line"><div>Sales Projection</div></div>
	<div class="line"><div>Developer</div></div>
	<div class="line"><div>Landlord</div></div>
	<div class="line"><div>Tenant Coordinator</div></div>
	<div class="line"><div>Deal Maker</div></div>
	<div class="line"><div>Attorney</div></div>
	<div class="line"><div>Architect</div></div>
	<div class="line"><div>CR Project Manager</div></div>
	<div class="line"><div>General Contractor</div></div>
	<div class="line"><div>GC Project Manager</div></div>
	<div class="line"><div>GC Superintendent</div></div>
	<div class="line"><div>LOI</div></div>
	<div class="line"><div>LOD Received</div></div>
	<div class="line"><div>Design Exhibit Reviewed</div></div>
	<div class="line"><div>Construction Exhibit Reviewed</div></div>
	<div class="line"><div>Lease Received</div></div>
	<div class="line"><div>Lease Executed</div></div>
	<div class="line"><div>Tenant Criteria Received</div></div>
	<div class="line"><div>Survey Uploaded</div></div>
	<div class="line"><div>Schematic Plan to Architect</div></div>
	<div class="line"><div>Preliminary Set to CR</div></div>
	<div class="line"><div>CR Preliminary Set Approval</div></div>
	<div class="line"><div>Check Set to CR</div></div>
	<div class="line"><div>CR Check Set Approval</div></div>
	<div class="line"><div>Construction Drawings Uploaded</div></div>	
	<div class="line"><div>CDs to Landlord for Approval</div></div>
	<div class="line"><div>Landlord Approval</div></div>
	<div class="line"><div>Permit Duration</div></div>
	<div class="line"><div>Submit for Permit</div></div>
	<div class="line"><div>City Comments Received</div></div>
	<div class="line"><div>CDs Revised and Resubmitted</div></div>
	<div class="line"><div>Permit Received</div></div>
	<div class="line"><div>Possesion Date</div></div>
	<div class="line"><div>GC Vendor Bid Sets Issued</div></div>
	<div class="line"><div>Bids Due</div></div>
	<div class="line"><div>GCs Qualifications Sent</div></div>
	<div class="line"><div>GCs Qualification Responses Due</div></div>
	<div class="line"><div>GC Awarded</div></div>
	<div class="line"><div>Start Construction</div></div>
	<div class="line"><div>Duration</div></div>
	<div class="line"><div>Delta 1 Revisions Issued</div></div>
	<div class="line"><div>Delta 2 Revisions Issued</div></div>
	<div class="line"><div>Delta 3 Revisions Issued</div></div>
	<div class="line"><div>Delta 4 Revisions Issued</div></div>
	<div class="line"><div>Delta 5 Revisions Issued</div></div>
	<div class="line"><div>Store Punch</div></div>
	<div class="line"><div>Store Turnover</div></div>
	<div class="line"><div>DDS</div></div>
	<div class="line"><div>Merchandise</div></div>
	<div class="line"><div>Store Opening</div></div>
</div>
HTML;

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
body {background: none;}
form {margin:0;}
#content{background: none;border:none;margin-top:30px;color:#333;}
.report-container {position:relative;white-space: nowrap; }
.line{margin:0;position:relative;clear:both;height:22px;}
.project{width:150px;display:inline-block;margin-right:-4px;border-right:2px #eee solid;position:relative;}
.header-group {width:150px;position:absolute;top:0;left:0;z-index:1000;border-top:1px #eee solid;
	  -webkit-transition: top 0.3s ease-out;
     -moz-transition: top 0.3s ease-out;
       -o-transition: top 0.3s ease-out;
          transition: top 0.3s ease-out;

	
}
.labels .line div, .line div.header, .line div.string, .line div.date {border:1px #eee solid;border-bottom-color:#c9c9c9;height:18px;padding:1px 4px; cursor: pointer;text-shadow:0px 1px 0px #fff;width:140px;display:inline-block;font-size:11px;overflow:hidden;}

.line div.hover {background:#ddd;}
.line div.header {text-align:center;background:#ddd;font-size:11px;}
.line div.date {text-align: center;}
.line div input {width:100%;font-weight:bold;font-size:11px;}
.split div input {width:100%;}
.split div.string, .split div.date {width:65px;float:left;}
.split .city {width:100px;}
.split .state {width:30px;}
.project {}
.hidden {display: none !important;}
.labels{position:absolute;top:0;left:0;width:150px;display:inline-block;background:#f9f9f9;}
.labels .line div {cursor: default; font-size:10px;}

.labels div.hover {background:#ddd;}
.columns {position:absolute; left:150px; top:0;width:90%; overflow-x: scroll; overflow-y: hidden;}
.columns table {margin-top:-1px;}
#field-btns {margin-left:0px;margin-top:0;position:absolute;top:0;left:150px;z-index:10000;}
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
.report-container {page-break-after: always;}
.project {width:120px;border-right:1px #ccc solid;}
.line {height:16px;}
.labels .line div, .line div.header, .line div.string, .line div.date {display:block;height:12px;line-height:16px;font-size:10px;width:110px;}
.labels .line div, .labels .line div.header {width:140px;}
.split div.string, .split div.date {width:50px;float:left;}
.split div.city {width:80px;}
.split div.state {width:20px;}
.columns {margin-left:150px;font-size:10px; position:static;}
.project .header-group {position:static;}
.navbar {display:none;}
.header-group {position: static;}
.superficial {display:none;}
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
		$('#hidden-projects').html( $('.columns td.hidden').length + ' hidden');
		$('#total-projects').html( $('.columns td.project').length + ' projects');
		if( $('.columns td.hidden').length > 0  ) {
			$('#reset-projects').show();
		}
	}




	function closeField() {
		$('.project .line div.active').each(function() {
			$(this).html( $(this).attr('data-init-value') );
			if ( $(this).hasClass('date') ) {
				var dmoment = moment( $(this).html() );
				if (dmoment) {
					$(this).html( dmoment.format('MM/DD/YY') );
				}
			}
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

<?php  if (!$print): ?>

		/*$('.columns').mousewheel(function(event, delta) {
			this.scrollLeft -= (delta * 30);
			event.preventDefault();
		});
		*/
		
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
		/*
		$('.project .line div').mouseover(function() {
		  $('.project .line div').removeClass('hover');
		  $(this).addClass('hover');
		  var i = $(this).parent().index();
		  $('.labels .line div').removeClass('hover');
		  $('.labels .line:nth-child('+(i+1)+') div').addClass('hover');
		  
		 });
		$('.project .line div').mouseout(function() {
		  $(this).removeClass('hover');
		  var i = $(this).parent().index();
		  $('.labels .line:nth-child('+(i+1)+') div').removeClass('hover');
		 });
*/
/*
    	$('.project .line div.date').each(function() {
    		if ( $(this).html() != '0000-00-00' ) {
	    		var dmoment = moment( $(this).html() );
	    	} else {
		    	$(this).html('');
	    	}
	    	
	    	if(dmoment) {
		    	$(this).html( dmoment.format('MM/DD/YY') );
	    	}
    	});

*/

    $('input.percent,input.money,input.number').each(function() {
		$(this).autoNumericSet( $(this).html() );				
	});

/*	//Go through each input and set the init value
	$('.project .line div').each(function() {
		if( $(this).hasClass('active') ) {
		 	return false;
		}
		$(this).attr('data-init-value', $(this).html() );				
	});
*/	
	
		$('.project .line .header').click(function() {
			var pid = $(this).closest('.project').attr('data-id');
			window.location = '/index.php?page=project&id='+pid;
		});


		$('.project .line .header').mouseover(function() {
			$(this).closest('.project').find('.header').css('color','#08c');
		});
		$('.project .line .header').mouseout(function() {
			$(this).closest('.project').find('.header').css('color','#333');
		});


		$('.project .line .header a.close').click(function(e) {
			e.stopPropagation();
			var pid = $(this).closest('.project').attr('data-id');
			$(this).closest('.project').addClass('hidden').next('.project').find('.header-group').css('left','initial');
			hidden.push(pid);
			countProjects();
			
		});
		
		$('#reset-projects').click(function() {
			$('.columns td.hidden').removeClass('hidden');
			countProjects();
			$(this).fadeOut();
			hidden.length = 0;
		});
		
		$('#print-view-btn').click(function() {
			var hiddenparam = $.param({ 'h': hidden });
			window.location = '?print=1&<?php  if (isset($_GET['view'])){print 'view='.$_GET['view'].'&';}?>'+decodeURIComponent(hiddenparam);
		});

		$('#default-view-btn').click(function() {
			var hiddenparam = $.param({ 'h': hidden });
			window.location = '?<?php  if (isset($_GET['view'])){print 'view='.$_GET['view'].'&';}?>'+decodeURIComponent(hiddenparam);
		});

	
		 $('.project .line .string').click(function() {
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

		//Load data source 
		var column = $(this).attr('data-field').substring(2);		
		var table = getTableName( $(this).attr('data-field').charAt(0) );
		var $this = $(this).children('input');
		$.get('ajax_field_source_data.php', { column: column, table: table }, function (data) {
			//$this.attr('data-source', data);
			//alert(data);			
			$this.typeahead({source: data});
		});
		


			$(this).children('input').focus();
		 });

		 $('.project .line .date').click(function() {
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


	$('#field-cancel').live('mouseup', function(e) {
		e.stopPropagation();
		closeField();
	});


	$('#field-save').live('mouseup', function(e) {
		e.stopPropagation();

		var pid = $(this).closest('.project').attr('data-id');
		var column = $(this).parent().prev('div.active').attr('data-field').substring(2);
		var thisinput = $(this).parent().prev('.active').find('input');
		if ( $(this).parent().prev('div.active').hasClass('money') ) {
			var newval = $(this).parent().prev('div.active').find('input').autoNumericGet();
		} else {	
			var newval = $(this).parent().prev('div.active').find('input').val();
		}
		
				var table = getTableName( $('div.active').attr('data-field').charAt(0) );

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

//	$(document).live('click blur focus', function(e){ 
/*	$(document).live('click', function(e){ 
		if (!$(event.target).closest("#field-btns,.active, .ui-widget,.ui-datepicker-header").length) {
		closeField();
		}
	});	
*/
	countProjects();

});
</script>

</head>

<body>
<div id="ajax-message"></div>
<div class="navbar navbar-inverse navbar-fixed-top">
<div class="navbar-inner">
<ul class="nav">
	<li<?php if(!isset($_GET['view']) && !isset($_GET['filter']) ){print ' class="active"';}?>><a href="?">All Active Projects</a></li>
	<li<?php if(isset($_GET['view'])){if($_GET['view'] == 'New') {print ' class="active"';}}?>><a href="?view=New">New Locations</a></li>
	<li<?php if(isset($_GET['view'])){if($_GET['view'] == 'Remodels') {print ' class="active"';}}?>><a href="?view=Remodels">Remodels</a></li>
	<li<?php if(isset($_GET['view'])){if($_GET['view'] == 'Relocations') {print ' class="active"';}}?>><a href="?view=Relocations">Relocations</a></li>
	<li class="dropdown<?php if(isset($_GET['filter'])){if($_GET['filter'] == 'architect') {print ' active';}}?>">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Filter by Arch <b class="caret"></b></a>
	<ul class="dropdown-menu">
	  <?php 
	  
	  	$data_query = "SELECT DISTINCT architect FROM projects";
	  	$result = mysql_query($data_query);
		while($drow = mysql_fetch_array($result)) {
			print '<li><a href="?filter=architect&q='.$drow[0].'">'.$drow[0].'</a></li>';
		}
	  ?>
	</ul>
	</li>
	<li class="dropdown<?php if(isset($_GET['filter'])){if($_GET['filter'] == 'general_contractor') {print ' active';}}?>">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Filter by GC <b class="caret"></b></a>
	<ul class="dropdown-menu">
	  <?php 
	  
	  	$data_query = "SELECT DISTINCT general_contractor FROM projects";
	  	$result = mysql_query($data_query);
		while($drow = mysql_fetch_array($result)) {
			print '<li><a href="?filter=general_contractor&q='.$drow[0].'">'.$drow[0].'</a></li>';
		}
	  ?>
	</ul>
	</li>
	<li class="dropdown<?php if(isset($_GET['filter'])){if($_GET['filter'] == 'cr_project_manager') {print ' active';}}?>">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Filter by PM <b class="caret"></b></a>
	<ul class="dropdown-menu">
	  <?php 
	  
	  	$data_query = "SELECT DISTINCT cr_project_manager FROM projects";
	  	$result = mysql_query($data_query);
		while($drow = mysql_fetch_array($result)) {
			print '<li><a href="?filter=cr_project_manager&q='.$drow[0].'">'.$drow[0].'</a></li>';
		}
	  ?>
	</ul>
	</li>

	</ul>
	
	<a href="#" class="btn btn-primary pull-right" id="print-view-btn"><i class="icon-print icon-white"></i> Printable View</a>
	<a href="#" class="btn btn-primary hide pull-right" id="default-view-btn"><i class="icon-chevron-left icon-white"></i> Back to Default View</a>
	


</div>
</div>

<div id="content">


<?php 
	$index = 0;
	
	foreach($stores as $store):
	
	$displayclass = 'shown'; 

	if(isset($_GET['h'])) {
		if (in_array($store['id'], $_GET['h'])) {
			if($print) {
				continue;
			} else {
				$displayclass = 'hidden';
			}					
	    }
	}
	
	if ($print && $index == 5) { 
		//start over
		$index = 0;
	 }

	if ($index == 0) {
		print '<div class="report-container">';
		print $labels;
		print '<div class="columns">';
		print '<table>';
		print '<tr>';
		
	}	 
	 
	 ?>
	 
   <td class="project <?=$displayclass?>" data-id="<?=$store['id']?>">	
	<div class="header-group">
	<div class="line">
		<div class="header">
		<a class="close" href="#">&times;</a>
		<strong><?=$store['store_number']?></strong>
		</div>
	</div>
	<div class="line">
		<div class="header"><?=$store['data']['sitename']?></div>
	</div>

	</div>

	<div class="line superficial"><div class="header">&nbsp;</div></div>
	<div class="line superficial"><div class="header">&nbsp;</div></div>		

	
	<div class="line">
		<div data-field="p-siteaddress" data-init-value="<?=$store['data']['siteaddress']?>" class="string"><?=$store['data']['siteaddress']?></div>
	</div>
		
	<div class="line split">
		<div data-field="p-sitecity" data-init-value="<?=$store['data']['sitecity']?>" class="city string"><?=$store['data']['sitecity']?></div>
		<div data-field="p-sitestate" data-init-value="<?=$store['data']['sitestate']?>" class="state string"><?=$store['data']['sitestate']?></div>
	</div>
	
	<div class="line">
		<div data-field="p-sitezip" data-init-value="<?=$store['data']['sitezip']?>" class="string"><?=$store['data']['sitezip']?></div>
	</div>
		
	<div class="line split">
	<div data-field="p-store_region" data-init-value="<?=$store['data']['store_region']?>" class="string"><?=$store['data']['store_region']?></div>
	<div data-field="p-store_district" data-init-value="<?=$store['data']['store_district']?>" class="string"><?=$store['data']['store_district']?></div>
	</div>
	
	<div class="line">
	<div data-field="r-sales_area_sqft" data-init-value="<?=$store['realestate']['sales_area_sqft']?>" class="string number"><?=$store['realestate']['sales_area_sqft']?></div>
	</div>
		
	
	<div class="line">
	<div data-field="c-center_type" data-init-value="<?=$store['centerinfo']['center_type']?>" class="string"><?=$store['centerinfo']['center_type']?></div>
	</div>
		
	
	<div class="line">
	<div data-field="r-sales_projection" data-init-value="<?=$store['realestate']['sales_projection']?>" class="string money"><?=$store['realestate']['sales_projection']?></div>	
	</div>
		
	
	<div class="line">
	<div data-field="r-developer" data-init-value="<?=$store['realestate']['developer']?>" class="string"><?=$store['realestate']['developer']?></div>
	</div>
		
	
	<div class="line">
	<div data-field="p-landlord" data-init-value="<?=$store['data']['landlord']?>" class="string"><?=$store['data']['landlord']?></div>
	</div>
		
	
	<div class="line">
	<div data-field="p-tenant_coordinator" data-init-value="<?=$store['data']['tenant_coordinator']?>" class="string"><?=$store['data']['tenant_coordinator']?></div>
	</div>
		
	
	<div class="line">
	<div data-field="p-deal_maker" data-init-value="<?=$store['data']['deal_maker']?>" class="string"><?=$store['data']['deal_maker']?></div>
	</div>
		
	
	<div class="line">
	<div data-field="p-attorney" data-init-value="<?=$store['data']['attorney']?>" class="string"><?=$store['data']['attorney']?></div>
	</div>
		
	
	<div class="line">
	<div data-field="p-architect" data-init-value="<?=$store['data']['architect']?>" class="string"><?=$store['data']['architect']?></div>
	</div>
		
	
	<div class="line">
	<div data-field="p-cr_project_manager" data-init-value="<?=$store['data']['cr_project_manager']?>" class="string"><?=$store['data']['cr_project_manager']?></div>
	</div>
		
	
	<div class="line">
	<div data-field="p-general_contractor" data-init-value="<?=$store['data']['general_contractor']?>" class="string"><?=$store['data']['general_contractor']?></div>
	</div>
		
	
	<div class="line">
	<div data-field="p-gc_project_manager" data-init-value="<?=$store['data']['gc_project_manager']?>" class="string"><?=$store['data']['gc_project_manager']?></div>	
	</div>
		
	
	<div class="line">
	<div data-field="p-gc_superintendent" data-init-value="<?=$store['data']['gc_superintendent']?>" class="string"><?=$store['data']['gc_superintendent']?></div>
	</div>
		

	<div class="line split">
		<div class="date" data-field="s-loi" data-init-value="<?=dateFormat($store['scheduled']['loi'])?>"><?=dateFormat($store['scheduled']['loi'])?></div>
		<div class="date" data-field="a-loi" data-init-value="<?=dateFormat($store['actual']['loi'])?>"><?=dateFormat($store['actual']['loi'])?></div>
	</div>
		
	<div class="line split">
		<div class="date" data-field="s-lod_received" data-init-value="<?=dateFormat($store['scheduled']['lod_received'])?>"><?=dateFormat($store['scheduled']['lod_received'])?></div>
		<div class="date" data-field="a-lod_received" data-init-value="<?=dateFormat($store['actual']['lod_received'])?>"><?=dateFormat($store['actual']['lod_received'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-tenant_criteria_received" data-init-value="<?=dateFormat($store['scheduled']['tenant_criteria_received'])?>"><?=dateFormat($store['scheduled']['tenant_criteria_received'])?></div>
	<div class="date" data-field="a-tenant_criteria_received" data-init-value="<?=dateFormat($store['actual']['tenant_criteria_received'])?>"><?=dateFormat($store['actual']['tenant_criteria_received'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-design_exhibit_reviewed" data-init-value="<?=dateFormat($store['scheduled']['design_exhibit_reviewed'])?>"><?=dateFormat($store['scheduled']['design_exhibit_reviewed'])?></div>
	<div class="date" data-field="a-design_exhibit_reviewed" data-init-value="<?=dateFormat($store['actual']['design_exhibit_reviewed'])?>"><?=dateFormat($store['actual']['design_exhibit_reviewed'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-construction_exhibit_reviewed" data-init-value="<?=dateFormat($store['scheduled']['construction_exhibit_reviewed'])?>"><?=dateFormat($store['scheduled']['construction_exhibit_reviewed'])?></div>
	<div class="date" data-field="a-construction_exhibit_reviewed" data-init-value="<?=dateFormat($store['actual']['construction_exhibit_reviewed'])?>"><?=dateFormat($store['actual']['construction_exhibit_reviewed'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-lease_received" data-init-value="<?=dateFormat($store['scheduled']['lease_received'])?>"><?=dateFormat($store['scheduled']['lease_received'])?></div>
	<div class="date" data-field="a-lease_received" data-init-value="<?=dateFormat($store['actual']['lease_received'])?>"><?=dateFormat($store['actual']['lease_received'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-lease_executed" data-init-value="<?=dateFormat($store['scheduled']['lease_executed'])?>"><?=dateFormat($store['scheduled']['lease_executed'])?></div>
	<div class="date" data-field="a-lease_executed" data-init-value="<?=dateFormat($store['actual']['lease_executed'])?>"><?=dateFormat($store['actual']['lease_executed'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-survey_uploaded" data-init-value="<?=dateFormat($store['scheduled']['survey_uploaded'])?>"><?=dateFormat($store['scheduled']['survey_uploaded'])?></div>
	<div class="date" data-field="a-survey_uploaded" data-init-value="<?=dateFormat($store['actual']['survey_uploaded'])?>"><?=dateFormat($store['actual']['survey_uploaded'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-schematic_plan_to_architect" data-init-value="<?=dateFormat($store['scheduled']['schematic_plan_to_architect'])?>"><?=dateFormat($store['scheduled']['schematic_plan_to_architect'])?></div>
	<div class="date" data-field="a-schematic_plan_to_architect" data-init-value="<?=dateFormat($store['actual']['schematic_plan_to_architect'])?>"><?=dateFormat($store['actual']['schematic_plan_to_architect'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-preliminary_set_to_cr" data-init-value="<?=dateFormat($store['scheduled']['preliminary_set_to_cr'])?>"><?=dateFormat($store['scheduled']['preliminary_set_to_cr'])?></div>
	<div class="date" data-field="a-preliminary_set_to_cr" data-init-value="<?=dateFormat($store['actual']['preliminary_set_to_cr'])?>"><?=dateFormat($store['actual']['preliminary_set_to_cr'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-cr_preliminary_set_approval" data-init-value="<?=dateFormat($store['scheduled']['cr_preliminary_set_approval'])?>"><?=dateFormat($store['scheduled']['cr_preliminary_set_approval'])?></div>
	<div class="date" data-field="a-cr_preliminary_set_approval" data-init-value="<?=dateFormat($store['actual']['cr_preliminary_set_approval'])?>"><?=dateFormat($store['actual']['cr_preliminary_set_approval'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-check_set_to_cr" data-init-value="<?=dateFormat($store['scheduled']['check_set_to_cr'])?>"><?=dateFormat($store['scheduled']['check_set_to_cr'])?></div>
	<div class="date" data-field="a-check_set_to_cr" data-init-value="<?=dateFormat($store['actual']['check_set_to_cr'])?>"><?=dateFormat($store['actual']['check_set_to_cr'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-cr_check_set_approval" data-init-value="<?=dateFormat($store['scheduled']['cr_check_set_approval'])?>"><?=dateFormat($store['scheduled']['cr_check_set_approval'])?></div>
	<div class="date" data-field="a-cr_check_set_approval" data-init-value="<?=dateFormat($store['actual']['cr_check_set_approval'])?>"><?=dateFormat($store['actual']['cr_check_set_approval'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-construction_drawings_uploaded" data-init-value="<?=dateFormat($store['scheduled']['construction_drawings_uploaded'])?>"><?=dateFormat($store['scheduled']['construction_drawings_uploaded'])?></div>
	<div class="date" data-field="a-construction_drawings_uploaded" data-init-value="<?=dateFormat($store['actual']['construction_drawings_uploaded'])?>"><?=dateFormat($store['actual']['construction_drawings_uploaded'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-cds_to_landlord_for_approval" data-init-value="<?=dateFormat($store['scheduled']['cds_to_landlord_for_approval'])?>"><?=dateFormat($store['scheduled']['cds_to_landlord_for_approval'])?></div>
	<div class="date" data-field="a-cds_to_landlord_for_approval" data-init-value="<?=dateFormat($store['actual']['cds_to_landlord_for_approval'])?>"><?=dateFormat($store['actual']['cds_to_landlord_for_approval'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-landlord_approval" data-init-value="<?=dateFormat($store['scheduled']['landlord_approval'])?>"><?=dateFormat($store['scheduled']['landlord_approval'])?></div>
	<div class="date" data-field="a-landlord_approval" data-init-value="<?=dateFormat($store['actual']['landlord_approval'])?>"><?=dateFormat($store['actual']['landlord_approval'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-permit_duration" data-init-value="<?=dateFormat($store['scheduled']['permit_duration'])?>"><?=dateFormat($store['scheduled']['permit_duration'])?></div>
	<div class="date" data-field="a-permit_duration" data-init-value="<?=dateFormat($store['actual']['permit_duration'])?>"><?=dateFormat($store['actual']['permit_duration'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-submit_for_permit" data-init-value="<?=dateFormat($store['scheduled']['submit_for_permit'])?>"><?=dateFormat($store['scheduled']['submit_for_permit'])?></div>
	<div class="date" data-field="a-submit_for_permit" data-init-value="<?=dateFormat($store['actual']['submit_for_permit'])?>"><?=dateFormat($store['actual']['submit_for_permit'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-city_comments_received" data-init-value="<?=dateFormat($store['scheduled']['city_comments_received'])?>"><?=dateFormat($store['scheduled']['city_comments_received'])?></div>
	<div class="date" data-field="a-city_comments_received" data-init-value="<?=dateFormat($store['actual']['city_comments_received'])?>"><?=dateFormat($store['actual']['city_comments_received'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-cds_revised_and_resubmitted" data-init-value="<?=dateFormat($store['scheduled']['cds_revised_and_resubmitted'])?>"><?=dateFormat($store['scheduled']['cds_revised_and_resubmitted'])?></div>
	<div class="date" data-field="a-cds_revised_and_resubmitted" data-init-value="<?=dateFormat($store['actual']['cds_revised_and_resubmitted'])?>"><?=dateFormat($store['actual']['cds_revised_and_resubmitted'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-permit_received" data-init-value="<?=dateFormat($store['scheduled']['permit_received'])?>"><?=dateFormat($store['scheduled']['permit_received'])?></div>
	<div class="date" data-field="a-permit_received" data-init-value="<?=dateFormat($store['actual']['permit_received'])?>"><?=dateFormat($store['actual']['permit_received'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-possesion_date" data-init-value="<?=dateFormat($store['scheduled']['possesion_date'])?>"><?=dateFormat($store['scheduled']['possesion_date'])?></div>
	<div class="date" data-field="a-possesion_date" data-init-value="<?=dateFormat($store['actual']['possesion_date'])?>"><?=dateFormat($store['actual']['possesion_date'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-gc_vendor_bid_sets_issued" data-init-value="<?=dateFormat($store['scheduled']['gc_vendor_bid_sets_issued'])?>"><?=dateFormat($store['scheduled']['gc_vendor_bid_sets_issued'])?></div>
	<div class="date" data-field="a-gc_vendor_bid_sets_issued" data-init-value="<?=dateFormat($store['actual']['gc_vendor_bid_sets_issued'])?>"><?=dateFormat($store['actual']['gc_vendor_bid_sets_issued'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-bids_due" data-init-value="<?=dateFormat($store['scheduled']['bids_due'])?>"><?=dateFormat($store['scheduled']['bids_due'])?></div>
	<div class="date" data-field="a-bids_due" data-init-value="<?=dateFormat($store['actual']['bids_due'])?>"><?=dateFormat($store['actual']['bids_due'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-gcs_qualifications_sent" data-init-value="<?=dateFormat($store['scheduled']['gcs_qualifications_sent'])?>"><?=dateFormat($store['scheduled']['gcs_qualifications_sent'])?></div>
	<div class="date" data-field="a-gcs_qualifications_sent" data-init-value="<?=dateFormat($store['actual']['gcs_qualifications_sent'])?>"><?=dateFormat($store['actual']['gcs_qualifications_sent'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-gcs_qualification_responses_due" data-init-value="<?=dateFormat($store['scheduled']['gcs_qualification_responses_due'])?>"><?=dateFormat($store['scheduled']['gcs_qualification_responses_due'])?></div>
	<div class="date" data-field="a-gcs_qualification_responses_due" data-init-value="<?=dateFormat($store['actual']['gcs_qualification_responses_due'])?>"><?=dateFormat($store['actual']['gcs_qualification_responses_due'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-gc_awarded" data-init-value="<?=dateFormat($store['scheduled']['gc_awarded'])?>"><?=dateFormat($store['scheduled']['gc_awarded'])?></div>
	<div class="date" data-field="a-gc_awarded" data-init-value="<?=dateFormat($store['actual']['gc_awarded'])?>"><?=dateFormat($store['actual']['gc_awarded'])?></div>
	</div>
		
	<div class="line split">
	<div class="date" data-field="s-start_construction" data-init-value="<?=dateFormat($store['scheduled']['start_construction'])?>"><?=dateFormat($store['scheduled']['start_construction'])?></div>
	<div class="date" data-field="a-start_construction" data-init-value="<?=dateFormat($store['actual']['start_construction'])?>"><?=dateFormat($store['actual']['start_construction'])?></div>
	</div>
		
	<div class="line">
	<div class="string" data-field="p-duration" data-init-value="<?=$store['data']['construction_duration']?>"><?=$store['data']['construction_duration']?></div>
	</div>
		
	<div class="line">
	<div class="date" data-field="p-delta_1_revisions_issued" data-init-value="<?=dateFormat($store['data']['delta_1_revisions_issued'])?>"><?=dateFormat($store['data']['delta_1_revisions_issued'])?></div>
	</div>
		
	<div class="line">
	<div class="date" data-field="p-delta_2_revisions_issued" data-init-value="<?=dateFormat($store['data']['delta_2_revisions_issued'])?>"><?=dateFormat($store['data']['delta_2_revisions_issued'])?></div>
	</div>
		
	<div class="line">
	<div class="date" data-field="p-delta_3_revisions_issued" data-init-value="<?=dateFormat($store['data']['delta_3_revisions_issued'])?>"><?=dateFormat($store['data']['delta_3_revisions_issued'])?></div>
	</div>
		
	<div class="line">
	<div class="date" data-field="p-delta_4_revisions_issued" data-init-value="<?=dateFormat($store['data']['delta_4_revisions_issued'])?>"><?=dateFormat($store['data']['delta_4_revisions_issued'])?></div>
	</div>
		
	<div class="line">
	<div class="date" data-field="p-delta_5_revisions_issued" data-init-value="<?=dateFormat($store['data']['delta_5_revisions_issued'])?>"><?=dateFormat($store['data']['delta_5_revisions_issued'])?></div>
	</div>
		
	<div class="line">
	<div class="date" data-field="p-store_punch_date" data-init-value="<?=dateFormat($store['data']['store_punch_date'])?>"><?=dateFormat($store['data']['store_punch_date'])?></div>
	</div>
		
	<div class="line">
	<div class="date" data-field="p-store_turnover_date" data-init-value="<?=dateFormat($store['data']['store_turnover_date'])?>"><?=dateFormat($store['data']['store_turnover_date'])?></div>
	</div>
		
	<div class="line">
	<div class="date" data-field="p-dds_date" data-init-value="<?=dateFormat($store['data']['dds_date'])?>"><?=dateFormat($store['data']['dds_date'])?></div>
	</div>
		
	<div class="line">
	<div class="date" data-field="p-merchandise_date" data-init-value="<?=dateFormat($store['data']['merchandise_date'])?>"><?=dateFormat($store['data']['merchandise_date'])?></div>
	</div>
		
	<div class="line">
	<div class="date" data-field="p-store_opening_date" data-init-value="<?=dateFormat($store['data']['store_opening_date'])?>"><?=dateFormat($store['data']['store_opening_date'])?></div>
	</div>
</td>
<?php


	
	if ($print && $index == 4) {
	print '</tr>';
	print '</table>';

	print '</div>';
	print '</div>';
	}
	$index++;

endforeach;


if(!$print){
	print '</tr>';
	print '</table>';
	print '</div>';
}
?>

</div>
</body>
</html>
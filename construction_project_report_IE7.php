<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

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



	//Vendor filter, vendors only see projects that have them tagged as vendors for that project
	if ($usergroup == 3)  {
		$vendor_filter = " AND `companyarray` LIKE '%:\"".$usercompany."\";%'";
	} else {
		$vendor_filter = NULL;
	}


$quarters = array(
	"q1_2013" => "2013-02-|2013-03-|2013-04-|TBD Q1 2013",
	"q2_2013" => "2013-05-|2013-06-|2013-07-|TBD Q2 2013",
	"q3_2013" => "2013-08-|2013-09-|2013-10-|TBD Q3 2013",
	"q4_2013" => "2013-11-|2013-12-|2014-01-|TBD Q4 2013|TBD 2013",
	"q1_2014" => "2014-02-|2014-03-|2014-04-|TBD Q1 2014",
	"q2_2014" => "2014-05-|2014-06-|2014-07-|TBD Q2 2014",
	"q3_2014" => "2014-08-|2014-09-|2014-10-|TBD Q3 2014",
	"q4_2014" => "2014-11-|2014-12-|2015-01-|TBD Q4 2014|TBD 2014",
	"TBD" => "0000-00"
	);

	$stores = array();
	$index = 0;

foreach($quarters as $q => $regexp) {

	$query = "SELECT id,store_number FROM projects WHERE store_opening_date REGEXP '".$regexp."' AND project_status = 'active'".$vendor_filter." ORDER BY store_opening_date ASC";

	if(isset($_GET['view'])) {
		switch(strtolower($_GET['view'])) {
			case "new":
			$query = "SELECT id,store_number FROM projects WHERE store_opening_date REGEXP '".$regexp."' AND project_status = 'active' AND project_type = 'new'".$vendor_filter." ORDER BY store_opening_date ASC";
			break;

			case "relocations":
			$query = "SELECT id,store_number FROM projects WHERE store_opening_date REGEXP '".$regexp."' AND project_status = 'active' AND project_type = 'relocation'".$vendor_filter." ORDER BY store_opening_date ASC";
			break;

			case "remodels":
			$query = "SELECT id,store_number FROM projects WHERE store_opening_date REGEXP '".$regexp."' AND project_status = 'active' AND project_type = 'remodel'".$vendor_filter." ORDER BY store_opening_date ASC";	
			break;
			
			case "other":
			$query = "SELECT id,store_number FROM projects WHERE store_opening_date REGEXP '".$regexp."' AND project_status = 'active' AND project_type = 'other'".$vendor_filter." ORDER BY store_opening_date ASC";
			break;
		}	
	}
	
	if(isset($_GET['filter'])) {
		$query = "SELECT id,store_number FROM projects WHERE store_opening_date REGEXP '".$regexp."' AND project_status = 'active' AND ".$_GET['filter']." = '".$_GET['q']."'".$vendor_filter." ORDER BY store_opening_date ASC";
	} 
	
	
	$result = mysql_query($query) or die ("no query");	

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
	    
	    //Get changes array
		$change_query = "SELECT * FROM changes WHERE project_id = ".$row['id'];
		$change_result = mysql_query($change_query) or die ("no query");	
		while($change_row = mysql_fetch_assoc($change_result)) {
		    $stores[$index]['changes'][] = $change_row['field_id'];
		    $stores[$index]['class'][$change_row['field_id']] = 'changed';
		}	

	    
	    /*	    
		$stores[$index]['strategy'] = loadData('re_strategy', $row['id']);
	    $stores[$index]['centerinfo'] = loadData('re_centerinfo', $row['id']);
	    $stores[$index]['kickouts'] = loadData('re_kickouts', $row['id']);
	    $stores[$index]['options'] = loadData('re_options', $row['id']);
	    */
	    $index++;
	}
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
	<div class="line noprint"><div>Address</div></div>
	<div class="line"><div>City</div></div>
	<div class="line noprint"><div>Zip</div></div>
	<div class="line"><div>Region/District</div></div>
	<div class="line"><div>REC Approval/SQFT</div></div>
	<div class="line arch-line"><div>Gross SQFT/Sales SQFT</div></div>
	<div class="line"><div>Project Type/Center Type</div></div>
	<div class="line"><div>Sales Projection</div></div>
	<div class="line"><div>LL</div></div>
	<div class="line"><div>Tenant Coordinator</div></div>
	<div class="line"><div>Deal Maker</div></div>
	<div class="line"><div>Attorney</div></div>
	<div class="line"><div>Architect</div></div>
	<div class="line"><div>CR Project Manager</div></div>
	<div class="line"><div>General Contractor</div></div>
	<div class="line noprint"><div>GC Project Manager</div></div>
	<div class="line noprint"><div>GC Superintendent</div></div>
	<div class="div-line"></div>
	<div class="line noprint"><div>LOI</div></div>
	<div class="line"><div>LOD Rcvd From CR</div></div>
	<div class="line"><div>Tenant Criteria Rcvd</div></div>
	<div class="line"><div>Design Exhibit Rcvd</div></div>
	<div class="line"><div>Const Exhibit Rcvd</div></div>
	<div class="line"><div>REC Approval</div></div>
	<div class="line noprint"><div>Draft Lease Rcvd</div></div>
	<div class="line noprint"><div>CR Signed Lease</div></div>
	<div class="line"><div>Fully Executed Lease</div></div>
	<div class="div-line"></div>
	<div class="line arch-line"><div>Survey (Field) Completed</div></div>
	<div class="line arch-line"><div>Survey Package Submit to CR</div></div>
	<div class="line arch-line"><div>Prelim Set to CR</div></div>
	<div class="line arch-line"><div>Prelim Approval to Arch</div></div>
	<div class="line arch-line"><div>Fix &amp; Fin Plan to CR</div></div>
	<div class="line arch-line"><div>Fix &amp; Fin Plan CR Approval</div></div>	
	<div class="line arch-line"><div>Check Set to CR</div></div>
	<div class="line arch-line"><div>Check Set Approval by Design</div></div>
	<div class="line arch-line"><div>Check Set Approval by Const</div></div>	
	<div class="line arch-line"><div>CDs Uploaded</div></div>	
	<div class="line arch-line"><div>CDs to LL for Approval</div></div>
	<div class="line arch-line"><div>LL Re-Submittal</div></div>
	<div class="line arch-line"><div>Final LL Approval</div></div>
	<div class="line arch-line"><div>LL Appr Req''d/Permit</div></div>
	<div class="line arch-line"><div>Permit Duration</div></div>
	<div class="line arch-line"><div>Bldg Dept Special Req''s</div></div>
	<div class="line arch-line"><div>Bldg Dept Submittal</div></div>
	<div class="line arch-line"><div>Bldg Dept Comments Rcvd</div></div>
	<div class="line arch-line"><div>Bldg Dept Re-Submittal</div></div>
	<div class="line arch-line"><div>Bldg Permit Issued</div></div>
	<div class="div-line"></div>
	<div class="line"><div>GC Vendor Bid Sets Issued</div></div>
	<div class="line"><div>Bids Due</div></div>
	<div class="line noprint"><div>GCs Qualifications Sent</div></div>
	<div class="line noprint"><div>GCs Qualification Responses Due</div></div>
	<div class="line"><div>GC Awarded</div></div>
	<div class="line"><div>Possesion Date</div></div>
	<div class="line"><div>Start Const</div></div>
	<div class="line"><div>Duration</div></div>
	<div class="div-line"></div>
	<div class="line arch-line"><div>Delta 1 Issued</div></div>
	<div class="line arch-line"><div>Delta 2 Issued</div></div>
	<div class="line arch-line"><div>Delta 3 Issued</div></div>
	<div class="line arch-line"><div>Delta 4 Issued</div></div>
	<div class="line arch-line"><div>Delta 5 Issued</div></div>
	<div class="line"><div>Store Punch</div></div>
	<div class="line"><div>Store Turnover</div></div>
	<div class="div-line"></div>
	<div class="line"><div>Fixtures</div></div>
	<div class="line"><div>DDS</div></div>
	<div class="line"><div>Merchandise</div></div>
	<div class="line"><div>Store Opening</div></div>
	<div class="line"><div>Grand Opening</div></div>
	<div class="line arch-line"><div>Notes</div></div>
</div>
HTML;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="scr1pz0rx.js"></script>
<script type="text/javascript" src="bootstrap.min.js"></script>
<script type="text/javascript" src="autoNumeric.js"></script>
<!--<script type="text/javascript" src="moment.min.js"></script>-->

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
.line{margin:0;position:relative;clear:both;height:22px;z-index: 1;}
.project{width:150px;display:inline-block;margin-right:-4px;border-right:2px #eee solid;position:relative;}
.header-group {width:150px;position:absolute;top:0;left:0;z-index:500;border-bottom:1px #666 solid;
	  -webkit-transition: top 0.1s ease-out;
     -moz-transition: top 0.1s ease-out;
       -o-transition: top 0.1s ease-out;
          transition: top 0.1s ease-out;

	
}
.navbar {max-height: 40px; }
.navbar-inner {margin:0;}
.navbar .form-search {margin-top:7px;}
.navbar .form-search input {height:15px;font-size:11px;line-height:14px;}
.navbar .btn {margin-top:0;}
.navbar .nav>li>a {padding:8px; font-size:11px;}
.clearable{
    position:relative;
}

span.icon_clear{
    position:absolute;
    right:10px;    
    display:none;
    
    /* now go with the styling */
    cursor:pointer;
    font: bold 1em sans-serif;
    color:#38468F;  
    }
    span.icon_clear:hover{
        color:#f52;
    }




ul.nav {white-space: nowrap;}
.labels .line div, .line div.header, .line div.string, .line div.date {border:1px #ccc solid;border-bottom-color:#ccc;height:18px;padding:1px 4px; cursor: pointer;width:140px;display:inline-block;font-size:11px;overflow:hidden;}
.labels .div-line div, .div-line {height:2px;padding:0;background:#aaa}
.line div.hover {background:#ddd;}
.line div.header {text-align:center;background:#ddd;font-size:11px;}
.line div.date {text-align: center;}
.line div input {width:96%;font-weight:bold;font-size:11px;background:#f4f0ec;line-height:12px; height:12px;margin-bottom:0;padding:2px;vertical-align: top;
z-index:9999;
border-color: rgba(82, 168, 236, 0.8);
outline: 0;
outline: thin dotted 9;
-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075),0 0 8px rgba(82, 168, 236, 0.6);
-moz-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075),0 0 8px rgba(82,168,236,0.6);
box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075),0 0 8px rgba(82, 168, 236, 0.6);
}
.split div input {width:96%;}
.split div.string, .split div.date {width:65px;float:left;}
div.dropdown {line-height: 14px;height: 14px;}
.split .city {width:100px;}
.split .state {width:30px;}
.project {}
.hidden {display: none !important;}
.labels{position:absolute;top:1px;left:0;width:150px;display:inline-block;background:#f9f9f9;z-index:1000;
	border-right:1px #666 solid;
	box-shadow:1px 1px 5px rgba(0,0,0,0.5);
	  -webkit-transition: left 0.1s ease-out;
     -moz-transition: left 0.1s ease-out;
       -o-transition: left 0.1s ease-out;
          transition: left 0.1s ease-out;	
}
td.highlight .header-group div.header {background:#e8ff3c !important;}
.labels .line div {cursor: default; font-size:10px;}
.labels .arch-line {background:#E6FFE6;}
.labels div.hover {background:#ddd;}
/*.columns {position:absolute; left:150px; top:0;width:90%; overflow-x: scroll; overflow-y: hidden;}*/
.columns {margin-left:150px;}

.columns table {margin-top:-1px;}
#field-btns {margin-left:0px;margin-top:0;position:absolute;top:0;left:150px;z-index:10000;}
#field-cancel{text-decoration: underline;}
#tbd-phrase {font-size:10px;width:110px;height:16px;line-height:15px;margin-bottom:0;margin-right:4px;padding:1px}
#tbd-btns {text-align: left;}
#tbd-btns a.btn {padding:0px 1px;line-height: 12px;font-size:10px;margin:1px;width:40px}
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
.labels .line div, .line div.header, .line div.string, .line div.date {padding:0px 4px;}
.noprint {display: none;}
#content {margin:0;}
.report-container {page-break-after: always;}
.project {width:120px;border-right:1px #ccc solid;}
.line {height:14px;}
.labels .line div, .line div.header, .line div.string, .line div.date {display:block;height:12px;line-height:12px;font-size:10px;width:110px;}
.labels .line div, .labels .line div.header {width:140px;}
.split div.string, .split div.date {width:50px;float:left;}
.split div.city {width:80px;}
.split div.state {width:20px;}
.columns {margin-left:150px;font-size:10px; position:static;}
.project .header-group {position:static;}
.navbar {display:none;}
.header-group {position: static;width:auto;}
.superficial {display:none;}

.greyed {background:#f0f0f0 !important;}
.changed {background:#ffecc0 !important;}
</style>

<script>

(function($){$.belowthefold=function(element,settings){var fold=$(window).height()+$(window).scrollTop();return fold<=$(element).offset().top-settings.threshold;};$.abovethetop=function(element,settings){var top=$(window).scrollTop();return top>=$(element).offset().top+$(element).height()-settings.threshold;};$.rightofscreen=function(element,settings){var fold=$(window).width()+$(window).scrollLeft();return fold<=$(element).offset().left-settings.threshold;};$.leftofscreen=function(element,settings){var left=$(window).scrollLeft();return left>=$(element).offset().left+$(element).width()-settings.threshold;};$.inviewport=function(element,settings){return!$.rightofscreen(element,settings)&&!$.leftofscreen(element,settings)&&!$.belowthefold(element,settings)&&!$.abovethetop(element,settings);};$.extend($.expr[':'],{"below-the-fold":function(a,i,m){return $.belowthefold(a,{threshold:0});},"above-the-top":function(a,i,m){return $.abovethetop(a,{threshold:0});},"left-of-screen":function(a,i,m){return $.leftofscreen(a,{threshold:0});},"right-of-screen":function(a,i,m){return $.rightofscreen(a,{threshold:0});},"in-viewport":function(a,i,m){return $.inviewport(a,{threshold:0});}});})(jQuery);

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
		
			if ( $(this).hasClass('dropdown') ) {
				if ( $(this).attr('data-init-value') == 0 ) {
					$(this).html( 'No' );		
				} else {
					$(this).html( 'Yes' );		
				}
			} else {
				$(this).html( $(this).attr('data-init-value') );
			}
	
			if ( $(this).hasClass('date') ) {
			//	var dmoment = moment( $(this).html() );
			//	if (dmoment) {
			//		$(this).html( dmoment.format('MM/DD/YY') );
			//	}
			}
			$(this).removeClass('active');
		});
		
		$('#ui-datepicker-div').hide();

		$('#field-btns').remove();
	}
	
	
	function insertFieldBtns() {
			//Insert field buttons
			$('<div id="field-btns" />').insertAfter( $('.project .line div.active') );
			$('#field-btns').append('<a class="btn btn-mini btn-success" id="field-save"><i class="icon-white icon-ok"></i></a>');
			$('#field-btns').append('<a id="field-cancel">Cancel</a>');
			$('#field-btns').append('<span id="field-endcap">&nbsp;</span>');
				

	
			//$('body').append('<div class="modal-backdrop"></div>');

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
			$('.labels').css( {
				'left': ( $(document).scrollLeft() - 2)
			});
	    };
<?php endif; ?>

	    $('input.percent,input.money,input.number').each(function() {
			$(this).autoNumericSet( $(this).html() );				
		});
	
		$('.project .line .header').click(function() {
			var pid = $(this).closest('.project').attr('data-id');
			window.location = '/index.php?page=project&id='+pid;
		});

/*

		$('.project .line .header').mouseover(function() {
			$(this).closest('.project').find('.header').css('color','#08c');
		});
		$('.project .line .header').mouseout(function() {
			$(this).closest('.project').find('.header').css('color','#333');
		});
*/
		


		$('.project .line .header a.close').click(function(e) {
			e.stopPropagation();
			var pid = $(this).closest('.project').attr('data-id');
			$(this).closest('.project').addClass('hidden').next('.project').find('.header-group').css('left','initial');
			hidden.push(pid);
			countProjects();
		});
		
		
		var storelist = $('td.project').map(function () { return $(this).attr('data-storename'); });
		$('#search-query').typeahead({
			source: storelist,
			updater:function (item) {
		    	var sq = item;
			$('.highlight').removeClass('highlight');
			$('td.project[data-storename~="'+sq+'"]').addClass('highlight');
			$('td.project[data-storename="'+sq+'"]').addClass('highlight');			
			var container = $('.columns');
			var scrollTo = $('.highlight');
			$('html,body').animate({
			    scrollLeft: scrollTo.offset().left - container.offset().left + container.scrollLeft()
			});
		    	    
		        return item;
        	}
			
		});



		$('#search-clear').click(function() {
			$('#search-query').val('');

			$('.highlight').removeClass('highlight');
			$('html,body').animate({
			    scrollLeft: 0
			});

		});

		
		
		$('#reset-projects').click(function() {
			$('.columns td.hidden').removeClass('hidden');
			countProjects();
			$(this).fadeOut();
			hidden.length = 0;
		});
		
		$('#print-view-btn').click(function() {
			var hiddenparam = $.param({ 'h': hidden });
			window.location = '?<?php if(isset($_SERVER['QUERY_STRING'])) { echo $_SERVER['QUERY_STRING']."&"; }?>print=1&<?php  if (isset($_GET['view'])){print 'view='.$_GET['view'].'&';}?>'+decodeURIComponent(hiddenparam);
		});
		
		


		$('#default-view-btn').click(function() {
			var hiddenparam = $.param({ 'h': hidden });
			window.location = '?<?php  if (isset($_GET['view'])){print 'view='.$_GET['view'].'&';}?>'+decodeURIComponent(hiddenparam);
		});


<?php 

	if (($usergroup < 1) or (in_array("plans",$roles))){
		$can_edit = TRUE;
		if($usergroup < 2) {
			$line_selector = 'line';
		} else {
			$line_selector = 'arch-line';
		}

	}
?>


<?php if($can_edit): ?>	
	
		 $('.project .<?=$line_selector?> .string').click(function() {
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
			
			//check if btns are in view.. if not.. temporarily swap left to -90px
			if( !$('#field-endcap').is(':in-viewport') ) {
				$('#field-btns').css({left:'-90px'});
			}
		 });

		 $('.project .<?=$line_selector?> .date').click(function() {
		 	if( $(this).hasClass('active') ) {
			 	return false;
		 	}
			closeField();
		 	$(this).addClass('active');
			insertFieldBtns();
			if( $(this).attr('data-init-value')  === 'TBD') {
				$(this).html('<input type="text" class="date" value="">');				
			} else {
				$(this).html('<input type="text" class="date" value="'+$(this).attr('data-init-value')+'">');
			}
			$(this).find('input.date').datepicker({
    			showAnim:"slideDown"
    		}).focus();
    		$('<select id="tbd-phrase">' +
    			'<option>Select TBD</option>' +
    			'<option disabled="true">&mdash;</option>' +
    			'<option>TBD 2013</option>' +
    			'<option>TBD Q3 2013</option>' +
    			'<option>TBD Q4 2013</option>' +
    			'<option disabled="true">&mdash;</option>' +
    			'<option>TBD 2014</option>' +
    			'<option>TBD Q1 2014</option>' +
    			'<option>TBD Q2 2014</option>' +
    			'<option>TBD Q3 2014</option>' +
    			'<option>TBD Q4 2014</option>' +
    			'</select>&nbsp;').insertBefore('#field-save');
		 
			//check if btns are in view.. if not.. temporarily swap left to -90px
			if( !$('#field-endcap').is(':in-viewport') ) {
				$('#field-btns').css({left:'-200px'});
			}
			

		 });
		 
		 
		 

		 $('.project .<?=$line_selector?> .dropdown').click(function() {
		 	if( $(this).hasClass('active') ) {
			 	return false;
		 	}
			closeField();
		 	$(this).addClass('active');
			insertFieldBtns();
			if ( $(this).attr('data-init-value') === '1' ) {
				var curval = 'Yes';
			} else {
				var curval = 'No';
			}
			$(this).html('<select><option value="'+$(this).attr('data-init-value')+'">'+curval+'</option><option value="" disabled="disabled">--</option><option value="1">Yes</option><option value="0">No</option></select>');
			
			//check if btns are in view.. if not.. temporarily swap left to -90px
			if( !$('#field-endcap').is(':in-viewport') ) {
				$('#field-btns').css({left:'-90px'});
			}
		 });

	$('#field-cancel').live('mouseup', function(e) {
		e.stopPropagation();
		closeField();
	});


	$('#field-save').live('mouseup', function(e) {
		e.stopPropagation();
		
		var field_id = $(this).parent().prev('div.active').attr('data-field'); 
		var pid = $(this).closest('.project').attr('data-id');
		var column = $(this).parent().prev('div.active').attr('data-field').substring(2);
		var thisinput = $(this).parent().prev('.active').find('input,select');
		
		if ( $(this).parent().prev('div.active').hasClass('money') ) {
			var newval = $(this).parent().prev('div.active').find('input').autoNumericGet();
		} else {	
			var newval = $(this).parent().prev('div.active').find('input,select').val();
		}


		if ( $(this).parent().prev('div.active').hasClass('date') ) {
			var tbd = $('#tbd-phrase').val();
			if (tbd.substring(0, 6) == "Select") {
				//Do nothing... there was no change..
			} else {
				var newval = tbd;
				$(this).parent().prev('div.active').find('input').val(tbd);
			}
		} else {
			//Do nothing
		}
		var table = getTableName( $('div.active').attr('data-field').charAt(0) );

		//Validate new value?

		//Save new value via ajax function to ajax_field_save.php
		$.ajax({
	        type: "POST",
	        url: "ajax_field_save.php",
	        data: "id="+encodeURIComponent(pid)+"&table=" + encodeURIComponent(table) + "&column=" + encodeURIComponent(column) + "&value=" + encodeURIComponent(newval) + "&field_id=" + encodeURIComponent(field_id),
	        success: function(response) {
	            if(response === '1') {
					$(thisinput).parent().attr('data-init-value', newval );
					$(thisinput).parent().addClass('changed');
					closeField();
					$('#ajax-message').html('<div class="alert alert-success">Saved Successfully!</div>').show().delay('2000').fadeOut('500');
					
					
	            } else {
	                $('#ajax-message').html('<div class="alert alert-error">Error! Please try again...</div>').show().delay('2000').fadeOut('500');
	                closeField();
	            }
	        }
	    });	
	});

	<?php endif; ?>
	countProjects();


	//$('.columns .line div').not(':empty,.changed').addClass('greyed');
	//$('.line div[data-init-value=""]').removeClass('greyed')
	//$('.line div:contains("TBD")').css({'background':'#fff'});
	


});
</script>

</head>

<body>
<div id="ajax-message"></div>
<div class="navbar navbar-inverse navbar-fixed-top">
<div class="navbar-inner">
<ul class="nav">
	<li><a href="index.php">&laquo; Home</a></li>

	<li<?php if(!isset($_GET['view']) && !isset($_GET['filter']) ){print ' class="active"';}?>><a href="?">Active</a></li>
	<li<?php if(isset($_GET['view'])){if($_GET['view'] == 'New') {print ' class="active"';}}?>><a href="?view=New">New</a></li>
	<li<?php if(isset($_GET['view'])){if($_GET['view'] == 'Remodels') {print ' class="active"';}}?>><a href="?view=Remodels">Remodels</a></li>
	<li<?php if(isset($_GET['view'])){if($_GET['view'] == 'Relocations') {print ' class="active"';}}?>><a href="?view=Relocations">Relos</a></li>
	<li class="dropdown<?php if(isset($_GET['filter'])){if($_GET['filter'] == 'architect') {print ' active';}}?>">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">By Arch <b class="caret"></b></a>
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
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">By GC <b class="caret"></b></a>
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
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">By PM <b class="caret"></b></a>
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

	<li class="linke" id="print-view-btn"><a href="#"><i class="icon-print icon-white"></i> Print View</a></li>
	<li class="hide" id="default-view-btn"><a href="#"><i class="icon-chevron-left icon-white"></i> Reset View</a></li>
	
	</ul>
	
<form class="form-search">
  <input id="search-query" type="text" class="input-medium search-query" placeholder="Search...">
  <a class="btn btn-inverse" style="height:15px;width:15px; padding:2px;line-height:15px; border-radius:12px;position:relative; left:-27px;opacity:0.6" id="search-clear" href="#">&times;</a>
</form>
	


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
	/*
	if (isset($store['changes'])) {
		print '<script>';
		print '$(document).ready(function() {';
		foreach($store['changes'] as $change) {
			print '$(".project[data-id=\''.$store['id'].'\'] div[data-field=\''.$change.'\']").addClass("changed");';
	
		}
		print '});';
		print '</script>';
	}
	*/
	 ?>

   <td class="project <?=$displayclass?>" data-id="<?=$store['id']?>" data-storename="<?=$store['store_number']?> <?=$store['data']['sitename']?>">	
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

	
	<div class="line noprint">
		<div data-field="p-siteaddress" data-init-value="<?=$store['data']['siteaddress']?>" class="string"><?=$store['data']['siteaddress']?></div>
	</div>
		
	<div class="line split">
		<div data-field="p-sitecity" data-init-value="<?=$store['data']['sitecity']?>" class="city string"><?=$store['data']['sitecity']?></div>
		<div data-field="p-sitestate" data-init-value="<?=$store['data']['sitestate']?>" class="state string"><?=$store['data']['sitestate']?></div>
	</div>
	
	<div class="line noprint">
		<div data-field="p-sitezip" data-init-value="<?=$store['data']['sitezip']?>" class="string"><?=$store['data']['sitezip']?></div>
	</div>
		
	<div class="line arch-line split">
	<div data-field="p-store_region" data-init-value="<?=$store['data']['store_region']?>" class="string"><?=$store['data']['store_region']?></div>
	<div data-field="p-store_district" data-init-value="<?=$store['data']['store_district']?>" class="string"><?=$store['data']['store_district']?></div>
	</div>
	
	<div class="line split">
		<div data-field="a-rec_approval" data-init-value="<?=dateFormat($store['actual']['rec_approval'])?>" class="date <?=$store['class']['a-rec_approval']?>">
			<?=dateFormat($store['actual']['rec_approval'])?>
		</div>
		<div data-field="r-rec_approved_sqft" data-init-value="<?=$store['realestate']['rec_approved_sqft']?>" class="string number <?=$store['class']['r-rec_approved_sqft']?>">
			<?=$store['realestate']['rec_approved_sqft']?>
		</div>
	</div>

	<div class="line arch-line split">	
		<div data-field="r-gross_sqft" data-init-value="<?=$store['realestate']['gross_sqft']?>" class="string number <?=$store['class']['r-gross_sqft']?>">
			<?=$store['realestate']['gross_sqft']?>
		</div>
		<div data-field="r-sales_area_sqft" data-init-value="<?=$store['realestate']['sales_area_sqft']?>" class="string number <?=$store['class']['r-sales_area_sqft']?>">
			<?=$store['realestate']['sales_area_sqft']?>
		</div>
	</div>

	
	<div class="line split">
		<div data-field="p-project_type" data-init-value="<?=ucfirst($store['data']['project_type'])?>" class="string <?=$store['class']['p-project_type']?>">
			<?=ucfirst($store['data']['project_type'])?>
		</div>
		<div data-field="c-center_type" data-init-value="<?=$store['centerinfo']['center_type']?>" class="string <?=$store['class']['c-center_type']?>">
			<?=$store['centerinfo']['center_type']?>
		</div>
	</div>		
	
		
	
	<div class="line">
		<div data-field="r-sales_projection" data-init-value="<?=$store['realestate']['sales_projection']?>" class="string money <?=$store['class']['r-sales_projection']?>">
			<?=$store['realestate']['sales_projection']?>
		</div>	
	</div>
		
	
	<div class="line">
		<div data-field="r-developer" data-init-value="<?=$store['realestate']['developer']?>" class="string <?=$store['class']['r-developer']?>">
			<?=$store['realestate']['developer']?>
		</div>
	</div>
	
	<div class="line">
		<div data-field="p-tenant_coordinator" data-init-value="<?=$store['data']['tenant_coordinator']?>" class="string <?=$store['class']['p-tenant_coordinator']?>">
			<?=$store['data']['tenant_coordinator']?>
		</div>
	</div>
		
	
	<div class="line">
		<div data-field="p-deal_maker" data-init-value="<?=$store['data']['deal_maker']?>" class="string <?=$store['class']['p-deal_maker']?>">
			<?=$store['data']['deal_maker']?>
		</div>
	</div>
		
	
	<div class="line">
		<div data-field="p-attorney" data-init-value="<?=$store['data']['attorney']?>" class="string <?=$store['class']['p-attorney']?>">
			<?=$store['data']['attorney']?>
		</div>
	</div>
		
	
	<div class="line">
		<div data-field="p-architect" data-init-value="<?=$store['data']['architect']?>" class="string <?=$store['class']['p-architect']?>">
			<?=$store['data']['architect']?>
		</div>
	</div>
		
	
	<div class="line">
		<div data-field="p-cr_project_manager" data-init-value="<?=$store['data']['cr_project_manager']?>" class="string <?=$store['class']['p-cr_project_manager']?>">
			<?=$store['data']['cr_project_manager']?>
		</div>
	</div>
		
	
	<div class="line">
		<div data-field="p-general_contractor" data-init-value="<?=$store['data']['general_contractor']?>" class="string <?=$store['class']['p-general_contractor']?>">
			<?=$store['data']['general_contractor']?>
		</div>
	</div>
		
	
	<div class="line noprint">
		<div data-field="p-gc_project_manager" data-init-value="<?=$store['data']['gc_project_manager']?>" class="string <?=$store['class']['p-gc_project_manager']?>">
			<?=$store['data']['gc_project_manager']?>
		</div>	
	</div>
		
	
	<div class="line noprint">
		<div data-field="p-gc_superintendent" data-init-value="<?=$store['data']['gc_superintendent']?>" class="string <?=$store['class']['p-gc_superintendent']?>">
			<?=$store['data']['gc_superintendent']?>
		</div>
	</div>
		
	<div class="div-line">	</div>


	<div class="line split noprint">
		<div class="date <?=$store['class']['s-loi']?>" data-field="s-loi" data-init-value="<?=dateFormat($store['scheduled']['loi'])?>">
			<?=dateFormat($store['scheduled']['loi'])?>
		</div>
		<div class="date <?=$store['class']['a-loi']?>" data-field="a-loi" data-init-value="<?=dateFormat($store['actual']['loi'])?>">
			<?=dateFormat($store['actual']['loi'])?>
		</div>
	</div>
		
	<div class="line split">
		<div class="date <?=$store['class']['s-lod_received']?>" data-field="s-lod_received" data-init-value="<?=dateFormat($store['scheduled']['lod_received'])?>">
		<?=dateFormat($store['scheduled']['lod_received'])?>
		</div>
		<div class="date <?=$store['class']['a-lod_received']?>" data-field="a-lod_received" data-init-value="<?=dateFormat($store['actual']['lod_received'])?>">
		<?=dateFormat($store['actual']['lod_received'])?>
		</div>
	</div>
		
	<div class="line split">
		<div class="date <?=$store['class']['s-tenant_criteria_received']?>" data-field="s-tenant_criteria_received" data-init-value="<?=dateFormat($store['scheduled']['tenant_criteria_received'])?>">
			<?=dateFormat($store['scheduled']['tenant_criteria_received'])?>
		</div>
		<div class="date <?=$store['class']['a-tenant_criteria_received']?>" data-field="a-tenant_criteria_received" data-init-value="<?=dateFormat($store['actual']['tenant_criteria_received'])?>">
			<?=dateFormat($store['actual']['tenant_criteria_received'])?>
		</div>
	</div>
		
	<div class="line split">
		<div class="date <?=$store['class']['s-design_exhibit_received']?>" data-field="s-design_exhibit_received" data-init-value="<?=dateFormat($store['scheduled']['design_exhibit_received'])?>">
			<?=dateFormat($store['scheduled']['design_exhibit_received'])?>
		</div>
		<div class="date <?=$store['class']['a-design_exhibit_received']?>" data-field="a-design_exhibit_received" data-init-value="<?=dateFormat($store['actual']['design_exhibit_received'])?>">
			<?=dateFormat($store['actual']['design_exhibit_received'])?>
		</div>
	</div>
		
	<div class="line split">
		<div class="date <?=$store['class']['s-construction_exhibit_received']?>" data-field="s-construction_exhibit_received" data-init-value="<?=dateFormat($store['scheduled']['construction_exhibit_received'])?>">
			<?=dateFormat($store['scheduled']['construction_exhibit_received'])?>
		</div>
		<div class="date <?=$store['class']['a-construction_exhibit_received']?>" data-field="a-construction_exhibit_received" data-init-value="<?=dateFormat($store['actual']['construction_exhibit_received'])?>">
			<?=dateFormat($store['actual']['construction_exhibit_received'])?>
		</div>
	</div>

	<div class="line split">
		<div class="date <?=$store['class']['s-rec_approval']?>" data-field="s-rec_approval" data-init-value="<?=dateFormat($store['scheduled']['rec_approval'])?>">
			<?=dateFormat($store['scheduled']['rec_approval'])?>
		</div>
		<div class="date <?=$store['class']['a-rec_approval']?>" data-field="a-rec_approval" data-init-value="<?=dateFormat($store['actual']['rec_approval'])?>">
			<?=dateFormat($store['actual']['rec_approval'])?>
		</div>
	</div>

		
	<div class="line split noprint">
		<div class="date <?=$store['class']['s-draft_lease_received']?>" data-field="s-draft_lease_received" data-init-value="<?=dateFormat($store['scheduled']['draft_lease_received'])?>">
			<?=dateFormat($store['scheduled']['draft_lease_received'])?>
		</div>
		<div class="date <?=$store['class']['a-draft_lease_received']?>" data-field="a-draft_lease_received" data-init-value="<?=dateFormat($store['actual']['draft_lease_received'])?>">
			<?=dateFormat($store['actual']['draft_lease_received'])?>
		</div>
	</div>

	<div class="line split noprint">
		<div class="date <?=$store['class']['s-cr_signed_lease']?>" data-field="s-cr_signed_lease" data-init-value="<?=dateFormat($store['scheduled']['cr_signed_lease'])?>">
			<?=dateFormat($store['scheduled']['cr_signed_lease'])?>
		</div>
		<div class="date <?=$store['class']['a-cr_signed_lease']?>" data-field="a-cr_signed_lease" data-init-value="<?=dateFormat($store['actual']['cr_signed_lease'])?>">
			<?=dateFormat($store['actual']['cr_signed_lease'])?>
		</div>
	</div>

		
	<div class="line split">
		<div class="date <?=$store['class']['s-lease_executed']?>" data-field="s-lease_executed" data-init-value="<?=dateFormat($store['scheduled']['lease_executed'])?>">
			<?=dateFormat($store['scheduled']['lease_executed'])?>
		</div>
		<div class="date <?=$store['class']['a-lease_executed']?>" data-field="a-lease_executed" data-init-value="<?=dateFormat($store['actual']['lease_executed'])?>">
			<?=dateFormat($store['actual']['lease_executed'])?>
		</div>
	</div>
	
	<div class="div-line">	</div>

	<div class="line arch-line split">
		<div class="date <?=$store['class']['s-survey_completed']?>" data-field="s-survey_completed" data-init-value="<?=dateFormat($store['scheduled']['survey_completed'])?>">
			<?=dateFormat($store['scheduled']['survey_completed'])?>
		</div>
		<div class="date <?=$store['class']['a-survey_completed']?>" data-field="a-survey_completed" data-init-value="<?=dateFormat($store['actual']['survey_completed'])?>">
			<?=dateFormat($store['actual']['survey_completed'])?>
		</div>
	</div>
			
	<div class="line arch-line split">
		<div class="date <?=$store['class']['s-survey_uploaded']?>" data-field="s-survey_uploaded" data-init-value="<?=dateFormat($store['scheduled']['survey_uploaded'])?>">
			<?=dateFormat($store['scheduled']['survey_uploaded'])?>
		</div>
		<div class="date <?=$store['class']['a-survey_uploaded']?>" data-field="a-survey_uploaded" data-init-value="<?=dateFormat($store['actual']['survey_uploaded'])?>">
			<?=dateFormat($store['actual']['survey_uploaded'])?>
		</div>
	</div>
		
	<div class="line arch-line split">
		<div class="date <?=$store['class']['s-preliminary_set_to_cr']?>" data-field="s-preliminary_set_to_cr" data-init-value="<?=dateFormat($store['scheduled']['preliminary_set_to_cr'])?>">
			<?=dateFormat($store['scheduled']['preliminary_set_to_cr'])?>
		</div>
		<div class="date <?=$store['class']['a-preliminary_set_to_cr']?>" data-field="a-preliminary_set_to_cr" data-init-value="<?=dateFormat($store['actual']['preliminary_set_to_cr'])?>">
			<?=dateFormat($store['actual']['preliminary_set_to_cr'])?>
		</div>
	</div>
		
	<div class="line arch-line split">
		<div class="date <?=$store['class']['s-cr_preliminary_set_approval']?>" data-field="s-cr_preliminary_set_approval" data-init-value="<?=dateFormat($store['scheduled']['cr_preliminary_set_approval'])?>">
		<?=dateFormat($store['scheduled']['cr_preliminary_set_approval'])?>
		</div>
		<div class="date <?=$store['class']['a-cr_preliminary_set_approval']?>" data-field="a-cr_preliminary_set_approval" data-init-value="<?=dateFormat($store['actual']['cr_preliminary_set_approval'])?>">
		<?=dateFormat($store['actual']['cr_preliminary_set_approval'])?>
		</div>
	</div>
	
	<div class="line arch-line split">
		<div class="date <?=$store['class']['s-fix_fin_plan_to_cr']?>" data-field="s-fix_fin_plan_to_cr" data-init-value="<?=dateFormat($store['scheduled']['fix_fin_plan_to_cr'])?>">
			<?=dateFormat($store['scheduled']['fix_fin_plan_to_cr'])?>
		</div>
		<div class="date <?=$store['class']['a-fix_fin_plan_to_cr']?>" data-field="a-fix_fin_plan_to_cr" data-init-value="<?=dateFormat($store['actual']['fix_fin_plan_to_cr'])?>">
			<?=dateFormat($store['actual']['fix_fin_plan_to_cr'])?>
		</div>
	</div>
	
	<div class="line arch-line split">
		<div class="date <?=$store['class']['s-fix_fin_plan_approval']?>" data-field="s-fix_fin_plan_approval" data-init-value="<?=dateFormat($store['scheduled']['fix_fin_plan_approval'])?>">
			<?=dateFormat($store['scheduled']['fix_fin_plan_approval'])?>
		</div>
		<div class="date <?=$store['class']['a-fix_fin_plan_approval']?>" data-field="a-fix_fin_plan_approval" data-init-value="<?=dateFormat($store['actual']['fix_fin_plan_approval'])?>">
			<?=dateFormat($store['actual']['fix_fin_plan_approval'])?>
		</div>
	</div>		
		
	<div class="line arch-line split">
		<div class="date <?=$store['class']['s-check_set_to_cr']?>" data-field="s-check_set_to_cr" data-init-value="<?=dateFormat($store['scheduled']['check_set_to_cr'])?>">
			<?=dateFormat($store['scheduled']['check_set_to_cr'])?>
		</div>
		<div class="date <?=$store['class']['a-check_set_to_cr']?>" data-field="a-check_set_to_cr" data-init-value="<?=dateFormat($store['actual']['check_set_to_cr'])?>">
			<?=dateFormat($store['actual']['check_set_to_cr'])?>
		</div>
	</div>
		
	<div class="line arch-line split">
		<div class="date <?=$store['class']['s-cr_check_set_approval_design']?>" data-field="s-cr_check_set_approval_design" data-init-value="<?=dateFormat($store['scheduled']['cr_check_set_approval_design'])?>">
			<?=dateFormat($store['scheduled']['cr_check_set_approval_design'])?>
		</div>
		<div class="date <?=$store['class']['a-cr_check_set_approval_design']?>" data-field="a-cr_check_set_approval_design" data-init-value="<?=dateFormat($store['actual']['cr_check_set_approval_design'])?>">
			<?=dateFormat($store['actual']['cr_check_set_approval_design'])?>
		</div>
	</div>

	<div class="line arch-line split">
		<div class="date <?=$store['class']['s-cr_check_set_approval_construction']?>" data-field="s-cr_check_set_approval_construction" data-init-value="<?=dateFormat($store['scheduled']['cr_check_set_approval_construction'])?>">
			<?=dateFormat($store['scheduled']['cr_check_set_approval_construction'])?>
		</div>
		<div class="date <?=$store['class']['a-cr_check_set_approval_construction']?>" data-field="a-cr_check_set_approval_construction" data-init-value="<?=dateFormat($store['actual']['cr_check_set_approval_construction'])?>">
			<?=dateFormat($store['actual']['cr_check_set_approval_construction'])?>
		</div>
	</div>
		
	<div class="line arch-line split">
		<div class="date <?=$store['class']['s-construction_drawings_uploaded']?>" data-field="s-construction_drawings_uploaded" data-init-value="<?=dateFormat($store['scheduled']['construction_drawings_uploaded'])?>">
			<?=dateFormat($store['scheduled']['construction_drawings_uploaded'])?>
		</div>
		<div class="date <?=$store['class']['a-construction_drawings_uploaded']?>" data-field="a-construction_drawings_uploaded" data-init-value="<?=dateFormat($store['actual']['construction_drawings_uploaded'])?>">
			<?=dateFormat($store['actual']['construction_drawings_uploaded'])?>
		</div>
	</div>
		
	<div class="line arch-line split">
		<div class="date <?=$store['class']['s-cds_to_landlord_for_approval']?>" data-field="s-cds_to_landlord_for_approval" data-init-value="<?=dateFormat($store['scheduled']['cds_to_landlord_for_approval'])?>">
			<?=dateFormat($store['scheduled']['cds_to_landlord_for_approval'])?>
		</div>
		<div class="date <?=$store['class']['a-cds_to_landlord_for_approval']?>" data-field="a-cds_to_landlord_for_approval" data-init-value="<?=dateFormat($store['actual']['cds_to_landlord_for_approval'])?>">
			<?=dateFormat($store['actual']['cds_to_landlord_for_approval'])?>
		</div>
	</div>
		
	<div class="line arch-line split">
		<div class="date <?=$store['class']['s-landlord_resubmittal']?>" data-field="s-landlord_resubmittal" data-init-value="<?=dateFormat($store['scheduled']['landlord_resubmittal'])?>">
			<?=dateFormat($store['scheduled']['landlord_resubmittal'])?>
		</div>
		<div class="date <?=$store['class']['a-landlord_resubmittal']?>" data-field="a-landlord_resubmittal" data-init-value="<?=dateFormat($store['actual']['landlord_resubmittal'])?>">
			<?=dateFormat($store['actual']['landlord_resubmittal'])?>
		</div>
	</div>
		
	<div class="line arch-line split">
		<div class="date <?=$store['class']['s-landlord_approval']?>" data-field="s-landlord_approval" data-init-value="<?=dateFormat($store['scheduled']['landlord_approval'])?>">
			<?=dateFormat($store['scheduled']['landlord_approval'])?>
		</div>
		<div class="date <?=$store['class']['a-landlord_approval']?>" data-field="a-landlord_approval" data-init-value="<?=dateFormat($store['actual']['landlord_approval'])?>">
			<?=dateFormat($store['actual']['landlord_approval'])?>
		</div>
	</div>

	<div class="line">
		<div class="dropdown <?=$store['class']['p-landlord_approval_required_for_permit']?>" data-field="p-landlord_approval_required_for_permit" data-init-value="<?=$store['data']['landlord_approval_required_for_permit']?>">
			<?=$store['data']['landlord_approval_required_for_permit'] == 1 ? 'Yes' : 'No' ?>
		</div>
	</div>

	<div class="line arch-line split">
		<div class="string <?=$store['class']['s-permit_duration']?>" data-field="s-permit_duration" data-init-value="<?=$store['scheduled']['permit_duration']?>">
			<?=$store['scheduled']['permit_duration']?>
		</div>
		<div class="string <?=$store['class']['a-permit_duration']?>" data-field="a-permit_duration" data-init-value="<?=$store['actual']['permit_duration']?>">
			<?=$store['actual']['permit_duration']?>
		</div>
	</div>
		
	<div class="line arch-line split">
		<div class="date <?=$store['class']['s-bldg_dept_special_reqs']?>" data-field="s-bldg_dept_special_reqs" data-init-value="<?=dateFormat($store['scheduled']['bldg_dept_special_reqs'])?>">
			<?=dateFormat($store['scheduled']['bldg_dept_special_reqs'])?>
		</div>
		<div class="date <?=$store['class']['a-bldg_dept_special_reqs']?>" data-field="a-bldg_dept_special_reqs" data-init-value="<?=dateFormat($store['actual']['bldg_dept_special_reqs'])?>">
			<?=dateFormat($store['actual']['bldg_dept_special_reqs'])?>
		</div>
	</div>
		
	<div class="line arch-line split">
		<div class="date <?=$store['class']['s-submit_for_permit']?>" data-field="s-submit_for_permit" data-init-value="<?=dateFormat($store['scheduled']['submit_for_permit'])?>">
			<?=dateFormat($store['scheduled']['submit_for_permit'])?>
		</div>
		<div class="date <?=$store['class']['a-submit_for_permit']?>" data-field="a-submit_for_permit" data-init-value="<?=dateFormat($store['actual']['submit_for_permit'])?>">
			<?=dateFormat($store['actual']['submit_for_permit'])?>
		</div>
	</div>
		
	<div class="line arch-line">
		<div class="string <?=$store['class']['p-bldg_dept_comments_rcvd']?>" data-field="p-bldg_dept_comments_rcvd" data-init-value="<?=$store['data']['bldg_dept_comments_rcvd']?>">
			<?=$store['data']['bldg_dept_comments_rcvd']?>
		</div>
	</div>
		
	<div class="line arch-line split">
		<div class="date <?=$store['class']['s-cds_revised_and_resubmitted']?>" data-field="s-cds_revised_and_resubmitted" data-init-value="<?=dateFormat($store['scheduled']['cds_revised_and_resubmitted'])?>">
			<?=dateFormat($store['scheduled']['cds_revised_and_resubmitted'])?>
		</div>
		<div class="date <?=$store['class']['a-cds_revised_and_resubmitted']?>" data-field="a-cds_revised_and_resubmitted" data-init-value="<?=dateFormat($store['actual']['cds_revised_and_resubmitted'])?>">
			<?=dateFormat($store['actual']['cds_revised_and_resubmitted'])?>
		</div>
	</div>
		
	<div class="line arch-line split">
		<div class="date <?=$store['class']['s-permit_received']?>" data-field="s-permit_received" data-init-value="<?=dateFormat($store['scheduled']['permit_received'])?>">
			<?=dateFormat($store['scheduled']['permit_received'])?>
		</div>
		<div class="date <?=$store['class']['a-permit_received']?>" data-field="a-permit_received" data-init-value="<?=dateFormat($store['actual']['permit_received'])?>">
			<?=dateFormat($store['actual']['permit_received'])?>
		</div>
	</div>
		
	<div class="div-line">	</div>
					
	<div class="line split">
		<div class="date <?=$store['class']['s-gc_vendor_bid_sets_issued']?>" data-field="s-gc_vendor_bid_sets_issued" data-init-value="<?=dateFormat($store['scheduled']['gc_vendor_bid_sets_issued'])?>">
			<?=dateFormat($store['scheduled']['gc_vendor_bid_sets_issued'])?>
		</div>
		<div class="date <?=$store['class']['a-gc_vendor_bid_sets_issued']?>" data-field="a-gc_vendor_bid_sets_issued" data-init-value="<?=dateFormat($store['actual']['gc_vendor_bid_sets_issued'])?>">
			<?=dateFormat($store['actual']['gc_vendor_bid_sets_issued'])?>
		</div>
	</div>
		
	<div class="line split ">
		<div class="date <?=$store['class']['s-bids_due']?>" data-field="s-bids_due" data-init-value="<?=dateFormat($store['scheduled']['bids_due'])?>">
			<?=dateFormat($store['scheduled']['bids_due'])?>
		</div>
		<div class="date <?=$store['class']['a-bids_due']?>" data-field="a-bids_due" data-init-value="<?=dateFormat($store['actual']['bids_due'])?>">
			<?=dateFormat($store['actual']['bids_due'])?>
		</div>
	</div>
		
	<div class="line split noprint">
		<div class="date <?=$store['class']['s-gcs_qualifications_sent']?>" data-field="s-gcs_qualifications_sent" data-init-value="<?=dateFormat($store['scheduled']['gcs_qualifications_sent'])?>">
			<?=dateFormat($store['scheduled']['gcs_qualifications_sent'])?>
		</div>
		<div class="date <?=$store['class']['a-gcs_qualifications_sent']?>" data-field="a-gcs_qualifications_sent" data-init-value="<?=dateFormat($store['actual']['gcs_qualifications_sent'])?>">
			<?=dateFormat($store['actual']['gcs_qualifications_sent'])?>
		</div>
	</div>
		
	<div class="line split noprint">
		<div class="date <?=$store['class']['s-gcs_qualification_responses_due']?>" data-field="s-gcs_qualification_responses_due" data-init-value="<?=dateFormat($store['scheduled']['gcs_qualification_responses_due'])?>">
			<?=dateFormat($store['scheduled']['gcs_qualification_responses_due'])?>
		</div>
		<div class="date <?=$store['class']['a-gcs_qualification_responses_due']?>" data-field="a-gcs_qualification_responses_due" data-init-value="<?=dateFormat($store['actual']['gcs_qualification_responses_due'])?>">
			<?=dateFormat($store['actual']['gcs_qualification_responses_due'])?>
		</div>
	</div>
		
	<div class="line split">
		<div class="date <?=$store['class']['s-gc_awarded']?>" data-field="s-gc_awarded" data-init-value="<?=dateFormat($store['scheduled']['gc_awarded'])?>">
			<?=dateFormat($store['scheduled']['gc_awarded'])?>
		</div>
		<div class="date <?=$store['class']['a-gc_awarded']?>" data-field="a-gc_awarded" data-init-value="<?=dateFormat($store['actual']['gc_awarded'])?>">
			<?=dateFormat($store['actual']['gc_awarded'])?>
		</div>
	</div>

	<div class="line split">
		<div class="date <?=$store['class']['s-possesion_date']?>" data-field="s-possesion_date" data-init-value="<?=dateFormat($store['scheduled']['possesion_date'])?>">
			<?=dateFormat($store['scheduled']['possesion_date'])?>
		</div>
		<div class="date <?=$store['class']['a-possesion_date']?>" data-field="a-possesion_date" data-init-value="<?=dateFormat($store['actual']['possesion_date'])?>">
			<?=dateFormat($store['actual']['possesion_date'])?>
		</div>
	</div>
		
	<div class="line split">
		<div class="date <?=$store['class']['s-start_construction']?>" data-field="s-start_construction" data-init-value="<?=dateFormat($store['scheduled']['start_construction'])?>">
			<?=dateFormat($store['scheduled']['start_construction'])?>
		</div>
		<div class="date <?=$store['class']['a-start_construction']?>" data-field="a-start_construction" data-init-value="<?=dateFormat($store['actual']['start_construction'])?>">
			<?=dateFormat($store['actual']['start_construction'])?>
		</div>
	</div>
		
	<div class="line">
		<div class="string <?=$store['class']['p-duration']?>" data-field="p-duration" data-init-value="<?=$store['data']['duration']?>">
			<?=$store['data']['duration']?>
		</div>
	</div>

	<div class="div-line">	</div>
			
	<div class="line arch-line">
		<div class="date <?=$store['class']['p-delta_1_revisions_issued']?>" data-field="p-delta_1_revisions_issued" data-init-value="<?=dateFormat($store['data']['delta_1_revisions_issued'])?>">
			<?=dateFormat($store['data']['delta_1_revisions_issued'])?>
		</div>
	</div>
		
	<div class="line arch-line">
		<div class="date <?=$store['class']['p-delta_2_revisions_issued']?>" data-field="p-delta_2_revisions_issued" data-init-value="<?=dateFormat($store['data']['delta_2_revisions_issued'])?>">
			<?=dateFormat($store['data']['delta_2_revisions_issued'])?>
		</div>
	</div>
		
	<div class="line arch-line">
		<div class="date <?=$store['class']['p-delta_3_revisions_issued']?>" data-field="p-delta_3_revisions_issued" data-init-value="<?=dateFormat($store['data']['delta_3_revisions_issued'])?>">
			<?=dateFormat($store['data']['delta_3_revisions_issued'])?>
		</div>
	</div>
		
	<div class="line arch-line">
		<div class="date <?=$store['class']['p-delta_4_revisions_issued']?>" data-field="p-delta_4_revisions_issued" data-init-value="<?=dateFormat($store['data']['delta_4_revisions_issued'])?>">
			<?=dateFormat($store['data']['delta_4_revisions_issued'])?>
		</div>
	</div>
		
	<div class="line arch-line">
		<div class="date <?=$store['class']['p-delta_5_revisions_issued']?>" data-field="p-delta_5_revisions_issued" data-init-value="<?=dateFormat($store['data']['delta_5_revisions_issued'])?>">
			<?=dateFormat($store['data']['delta_5_revisions_issued'])?>
		</div>
	</div>
		
	<div class="line split">
		<div class="date <?=$store['class']['s-store_punch']?>" data-field="s-store_punch" data-init-value="<?=dateFormat($store['scheduled']['store_punch'])?>">
			<?=dateFormat($store['scheduled']['store_punch'])?>
		</div>
		<div class="date <?=$store['class']['a-store_punch']?>" data-field="a-store_punch" data-init-value="<?=dateFormat($store['actual']['store_punch'])?>">
			<?=dateFormat($store['actual']['store_punch'])?>
		</div>
	</div>

	<div class="line split">
		<div class="date <?=$store['class']['s-store_turnover']?>" data-field="s-store_turnover" data-init-value="<?=dateFormat($store['scheduled']['store_turnover'])?>">
			<?=dateFormat($store['scheduled']['store_turnover'])?>
		</div>
		<div class="date <?=$store['class']['a=store_turnover']?>" data-field="a-store_turnover" data-init-value="<?=dateFormat($store['actual']['store_turnover'])?>">
			<?=dateFormat($store['actual']['store_turnover'])?>
		</div>
	</div>

	<div class="div-line">	</div>

	<div class="line">
		<div class="date <?=$store['class']['p-fixtures_date']?>" data-field="p-fixtures_date" data-init-value="<?=dateFormat($store['data']['fixtures_date'])?>">
			<?=dateFormat($store['data']['fixtures_date'])?>
		</div>
	</div>
		
	<div class="line">
		<div class="date <?=$store['class']['p-dds_date']?>" data-field="p-dds_date" data-init-value="<?=dateFormat($store['data']['dds_date'])?>">
			<?=dateFormat($store['data']['dds_date'])?>
		</div>
	</div>

	<div class="line">
		<div class="date <?=$store['class']['p-merchandise_date']?>" data-field="p-merchandise_date" data-init-value="<?=dateFormat($store['data']['merchandise_date'])?>">
			<?=dateFormat($store['data']['merchandise_date'])?>
		</div>
	</div>
		
	<div class="line">
		<div class="date <?=$store['class']['p-store_opening_date']?>" data-field="p-store_opening_date" data-init-value="<?=dateFormat($store['data']['store_opening_date'])?>">
			<?=dateFormat($store['data']['store_opening_date'])?>
		</div>
	</div>

	<div class="line">
		<div class="dropdown <?=$store['class']['p-grand_opening']?>" data-field="p-grand_opening" data-init-value="<?=$store['data']['grand_opening']?>">
			<?=$store['data']['grand_opening'] == 1 ? 'Yes' : 'No' ?>
		</div>
	</div>

		
	<div class="line arch-line">
		<div class="string <?=$store['class']['p-project_report_comments']?>" data-field="p-project_report_comments" data-init-value="<?=$store['data']['project_report_comments']?>">
			<?=$store['data']['project_report_comments']?>
		</div>
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
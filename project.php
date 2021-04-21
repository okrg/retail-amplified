<?php
  include("little-helpers.php");
  $id = mysqli_escape_string($dbcnx, $_REQUEST['id']);
  if (($usergroup < 1) or (in_array("plans",$roles))) {
    $can_edit = TRUE;
    if($usergroup < 2) {
      $field_selector = 'edit';
      $isPM = TRUE;
    } else {
      $field_selector = 'arch';
    }
  }
?>

<script type="text/javascript">
$(document).ready(function() {
  //Sort photos table by date
  if ( $("#PhotoFileTable tbody:eq(0) td" ).length) {
    $("#PhotoFileTable").tablesorter( {sortList: [[3,1]]} ); 
  }

if ( $("#MiscFileTable tbody:eq(0) td" ).length) {
    $("#MiscFileTable").tablesorter( {sortList: [[3,1]]} ); 
  }

<?php if( $_GET['id'] == 2068 ): ?>
  if ( $("#DrawingsFileTable tbody:eq(0) td" ).length) {
    $("#DrawingsFileTable").tablesorter( {sortList: [[0,0]]} ); 
  }


<?php else: ?>
  if ( $("#DrawingsFileTable tbody:eq(0) td" ).length) {
    $("#DrawingsFileTable").tablesorter( {sortList: [[3,1]]} ); 
  }
<?php endif; ?>

  $(".edit").prop('readonly', true);

//Make sure Master is always first
$("#DrawingsFileTable td a:contains('MASTER DRAWING SET')").closest('tr').insertAfter( $('#DrawingsFileTable tr:first') );
$("#DrawingsFileTable td a:contains('Master Drawing Set')").closest('tr').insertAfter( $('#DrawingsFileTable tr:first') );


<?php if($can_edit): ?>

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
  
  $('input.percent').autoNumeric( {aSign: '%', pSign: 's', vMax:'1000.00', vMin:'-1000.00'} );
  $('input.money').autoNumeric( {aSep: ',', aDec: '.', aSign: '$'} );
  $('input.number').autoNumeric( {aSep: '', aPad: false, vMax: '999999'} ); 
  
  
  //Go through each input and set the init value
  $('input.percent,input.money,input.number').each(function() {
    //$(this).autoNumericSet( $(this).val() );        
  });
  
  $.fn.editable.defaults.mode = 'inline';

  $('a.edit').editable({
    pk: <?=$id?>,
    params: function(params) {
      params.table = $(this).attr('data-table');
      return params;
    },
    url: 'x-editable.php'
  }).on('shown', function(ev, editable) {
    setTimeout(function() {
        editable.input.$input.select();
    },0);
  });

  $('a.edit-date').editable({
    pk: <?=$id?>,
    format: 'mm/dd/yyyy',    
    viewformat: 'mm/dd/yyyy',    
    datepicker: {
      weekStart: 1
    },
    params: function(params) {
      params.table = $(this).attr('data-table');
      return params;
    },
    url: 'x-editable.php'
  });

  $('a.edit-number').editable({
    pk: <?=$id?>,    
    display: function(value) {
      if(value != '') {
        $(this).text(value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
      }
    },
    params: function(params) {
      params.table = $(this).attr('data-table');
      return params;
    },
    url: 'x-editable.php'
  }).on('shown', function(ev, editable) {
    setTimeout(function() {
        editable.input.$input.select();
    },0);
  });

  $('a.edit-money').editable({
    pk: <?=$id?>,
    inputclass: 'editable-money',
    display: function(value) {
      if(value != '') {
        $(this).text('$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
      }
    },
    params: function(params) {
      params.table = $(this).attr('data-table');
      return params;
    },
    url: 'x-editable.php'
  }).on('shown', function(ev, editable) {
    setTimeout(function() {
        editable.input.$input.select();
    },0);
  });

  $('a.edit-percent').editable({
    pk: <?=$id?>,
    display: function(value) {
      if(value != '') {
        $(this).text(value + '%');
      }
    },
    params: function(params) {
      params.table = $(this).attr('data-table');
      return params;
    },
    url: 'x-editable.php'
  }).on('shown', function(ev, editable) {
    setTimeout(function() {
        editable.input.$input.select();
    },0);
  });

  $('#vendor-list .checkbox input').change(function() {
    //Gather all selected vendor ids into an array.
    var list = new Array();
    $('#vendor-list input:checked').each(function() {
      list.push( $(this).val() );
    });

    //send array to ajax php as JSON
    //id - project id 
    //vendors -  jason array of vendor IDs
    $.ajax({
      type: "POST",
      url: "ajax_update_vendors.php",
      data: "id=<?=$id?>&vendors=" + JSON.stringify(list),
      success: function(response) {
        if(response === '1') {
          $('#ajax-message').html('<div class="alert alert-success">Saved Successfully!</div>').show().delay('2000').fadeOut('500');
        } else {
          $('#ajax-message').html('<div class="alert alert-error">Error:'+response+'</div>').show().delay('2000').fadeOut('500');
        }
      }
    });
  });

  $('#p-landlord_approval_required_for_permit').change(function() {
    if( $(this).val() === '1' ) {
    $('tr#landlord-approval-row').css('visibility', 'visible');
    } else {
    $('tr#landlord-approval-row').css('visibility', 'hidden');
    }
  })


  <?php
    if($changes) {
      print "//Changes\n";
      foreach ($changes as $change) {
        print '$("#'.$change.'").addClass("changed");'."\n";
      }
    }
  ?>

<?php endif; ?>


//global Project page JS for all visitors
  $('#content').on('click', 'div.pane-header', function() {    
    $('html, body').animate({
      scrollTop: $(this).offset().top
      }, 500);
    
    if( $(this).parent().hasClass('closed') ) {
      $(this).next('.pane-content').slideDown()
      .parent().addClass('open').removeClass('closed')
      .children('.pane-header').children('.symbol').html('<i class="icon-chevron-down"></i>');
    } else {
      $(this).next('.pane-content').slideUp()
      .parent().addClass('closed').removeClass('open')
      .children('.pane-header').children('.symbol').html('<i class="icon-chevron-right"></i>');
    }
  });

  $('#expand-all').click(function() {
    $('.pane-content').not('.pane-content .pane-content').slideDown().parent().addClass('open').removeClass('closed').children('.pane-header').children('.symbol').html('<i class="icon-chevron-down"></i>');
  });

  $('#collapse-all').click(function() {
    $('.pane-content').not('.pane-content .pane-content').slideUp().parent().addClass('closed').removeClass('open').children('.pane-header').children('.symbol').html('<i class="icon-chevron-right"></i>');
  });

  $('#expand-re').click(function() {
    $('#project-real-estate .pane-content .pane-content').slideDown().parent().addClass('open').removeClass('closed').children('.pane-header').children('.symbol').html('<i class="icon-chevron-down"></i>');
  });

  $('#collapse-re').click(function() {
    $('#project-real-estate .pane-content .pane-content').slideUp().parent().addClass('closed').removeClass('open').children('.pane-header').children('.symbol').html('<i class="icon-chevron-right"></i>');
  });

  <?php if( $_GET['cop'] == 1 ): ?>
  console.log('trying to run expand');
    $('#project-cop .pane-header').trigger('click');

    $('html, body').animate({
        scrollTop: $("#project-cop").offset().top
    }, 2000);
  <?php endif; ?>

});
</script>

<div id="content" class="project-page">
  <?php if(isset($message)): ?>
    <div class="alert message"><?php print $message; ?></div>
  <?php endif; ?>
  <div id="ajax-message"></div>


<div class="well">
  <ul class="nav nav-pills nav-fill">
    <li class="nav-item">
      <a class="nav-link" href="#" id="expand-all"><i class="fa fa-plus"></i> Expand All</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#" data-toggle="modal" data-target="#new-folder-modal"><i class="fa fa-upload"></i> New Upload</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#" data-toggle="modal" data-target="#new-comment-modal"><i class="fa fa-comment"></i> New Comment</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#" data-toggle="modal" data-target="#modules-modal"><i class="fa fa-cog"></i> Modules</a>
    </li>
  </ul>
</div>

<div class="pane open" id="project-summary">
  <div class="pane-header">Summary<span class="symbol"><i class="icon-chevron-down"></i></span></div>
  <div class="pane-content"><?php include('project_summary.php'); ?></div>
</div>

<?php 
$project_modules = json_decode($project['project_modules']);
?>

<?php if($project_modules->construction_schedule): ?>
<div class="pane closed" id="project-schedule">
  <div class="pane-header">Construction Schedule<span class="symbol"><i class="icon-chevron-right"></i></span></div>
  <div class="pane-content"><?php include("project_schedule.php"); ?></div>
</div>
<?php endif; ?>


<?php if($project_modules->tenant_coordination): ?>
<div class="pane closed" id="project-schedule">
  <div class="pane-header">Tenant Coordination<span class="symbol"><i class="icon-chevron-right"></i></span></div>
  <div class="pane-content">
    <?php include("project_tenant_coordination.php"); ?>
  </div>
</div>
<?php endif; ?>

<?php if($project_modules->real_estate_data): ?>
<div class="pane closed" id="project-real-estate">
    <div class="pane-header">Real Estate Data<span class="symbol"><i class="icon-chevron-right"></i></span></div>
  <div class="pane-content"><?php include("realestate_summary.php"); ?></div>
</div>
<?php endif; ?>

<?php if($project_modules->comments): ?>
<div class="pane closed" id="project-comments">
  <div class="pane-header">Comments<span class="symbol"><i class="icon-chevron-right"></i></span></div>
  <div class="pane-content">
    <?php include("project_comments.php"); ?></div>
</div>
<?php endif; ?>

<?php if($project_modules->files): ?>
<div class="pane <?php if(isset($_GET['showDwgs'])) { echo 'open'; }else { echo  'closed';}?>" id="project-files">
  <div class="pane-header">Files<span class="symbol"><i class="icon-chevron-right"></i></span></div>
  <div class="pane-content">
  <?php include("project_downloads.php"); ?>
  </div>
</div>
<?php endif; ?>

<?php if($project_modules->photos): ?>
<div class="pane closed" id="project-photos">
  <div class="pane-header">Photos<span class="symbol"><i class="icon-chevron-right"></i></span></div>
  <div class="pane-content">
    <?php include("project_photos.php"); ?></div> 
</div>
<?php endif; ?>

<?php if($project_modules->change_orders): ?>
<div class="pane closed" id="project-cop">
  <div class="pane-header">COP Process <span class="symbol"><i class="icon-chevron-right"></i></span></div>
  <div class="pane-content">
    <?php include("cop_request_log.php"); ?></div>
</div>
<?php endif; ?>

<?php if($project_modules->rfi): ?>
<div class="pane closed" id="project-rfi">
  <div class="pane-header">RFI <span class="symbol"><i class="icon-chevron-right"></i></span></div>
  <div class="pane-content">
    <?php include("project_rfi.php"); ?></div>
</div>
<?php endif; ?>

  <?php include("project_new_comment.php"); ?>
  <?php include("project_uploads.php"); ?>
  <?php include("project_modules.php"); ?>


</div>


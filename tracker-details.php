<?php
  include("little-helpers.php");
  $id = mysqli_escape_string($dbcnx, $_REQUEST['id']);
?>
<script type="text/javascript">
$(document).ready(function() {

  $(".edit").prop('readonly', true);

  function closeField() {
    $('.active-input').val( $('.active-input').attr('data-init-value') );       
    $('.active-input').removeClass('active-input');
    $('.active-controls').removeClass('active-controls');   
    $('#field-btns').remove();
  }
  
  function ajaxSuccess() {
  }
  
  $.fn.editable.defaults.mode = 'inline';

  $('a.edit').editable({
    pk: <?=$id?>,
    params: function(params) {
      params.table = 'tracker';
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
      params.table = 'tracker';
      return params;
    },
    url: 'x-editable.php'
  });

});
</script>

<div id="content" class="project-page">
  <?php if(isset($message)): ?>
    <div class="alert message"><?php print $message; ?></div>
  <?php endif; ?>
  <div id="ajax-message"></div>

  <div class="pane open" id="tracker-summary">
    <div class="pane-header">Tracker Summary<span class="symbol"><i class="icon-chevron-down"></i></span></div>
    <div class="pane-content">
      <?php include('tracker_summary.php'); ?>
    </div>
  </div>

  <div class="pane open" id="tracker-schedule">
    <div class="pane-header">Tracker Schedule<span class="symbol"><i class="icon-chevron-right"></i></span></div>
    <div class="pane-content">
      <?php include("tracker_schedule.php"); ?>
    </div>
  </div>


</div>


<?php
$project_groups = array();
?>

<form id="group-form">
<div class="form-group">
  <h6>General Contractors</h6>
  <?php    
    $query = "SELECT * FROM cna_groups WHERE group_role = 4 AND group_category = 'General Contractors'";
    $result = mysqli_query($dbcnx, $query) or die(mysqli_error($dbcnx));
    while($r = mysqli_fetch_assoc($result)) {
      if( in_array($r['id'], $project_groups)) {
        $btn_class = 'btn-info';
        $icon_class = 'fa-check-circle';
      } else {        
        $btn_class = 'btn-outline-info';
        $icon_class = 'fa-circle-notch';
      }
  ?>
  <button class="btn btn-sm project-group <?=$btn_class?>" 
    data-group-id="<?=$r['id']?>">
    <i class="fas <?=$icon_class?>"></i> <?=$r['group_name']?>
  </button>
  <?php } ?>
</div>

<hr />
<div class="form-group">
  <h6>Lighting</h6>
  <?php    
    $query = "SELECT * FROM cna_groups WHERE group_role = 4 AND group_category = 'Lighting'";
    $result = mysqli_query($dbcnx, $query) or die(mysqli_error($dbcnx));
    while($r = mysqli_fetch_assoc($result)) {
      if( in_array($r['id'], $project_groups)) {
        $btn_class = 'btn-info';
        $icon_class = 'fa-check-circle';
      } else {        
        $btn_class = 'btn-outline-info';
        $icon_class = 'fa-circle-notch';
      }
  ?>
  <button class="btn btn-sm project-group <?=$btn_class?>" 
    data-group-id="<?=$r['id']?>">
    <i class="fas <?=$icon_class?>"></i> <?=$r['group_name']?>
  </button>
  <?php } ?>
</div>
<hr />
<div class="form-group">
  <h6>Architects/Engineers</h6>
  <?php    
    $query = "SELECT * FROM cna_groups WHERE group_role = 4 AND group_category = 'Architects/Engineers'";
    $result = mysqli_query($dbcnx, $query) or die(mysqli_error($dbcnx));
    while($r = mysqli_fetch_assoc($result)) {
      if( in_array($r['id'], $project_groups)) {
        $btn_class = 'btn-info';
        $icon_class = 'fa-check-circle';
      } else {        
        $btn_class = 'btn-outline-info';
        $icon_class = 'fa-circle-notch';
      }
  ?>
  <button class="btn btn-sm project-group <?=$btn_class?>" 
    data-group-id="<?=$r['id']?>">
    <i class="fas <?=$icon_class?>"></i> <?=$r['group_name']?>
  </button>
  <?php } ?>
</div>
<hr />
<div class="form-group">
  <h6>Signage</h6>
  <?php    
    $query = "SELECT * FROM cna_groups WHERE group_role = 4 AND group_category = 'Signage'";
    $result = mysqli_query($dbcnx, $query) or die(mysqli_error($dbcnx));
    while($r = mysqli_fetch_assoc($result)) {
      if( in_array($r['id'], $project_groups)) {
        $btn_class = 'btn-info';
        $icon_class = 'fa-check-circle';
      } else {        
        $btn_class = 'btn-outline-info';
        $icon_class = 'fa-circle-notch';
      }
  ?>
  <button class="btn btn-sm project-group <?=$btn_class?>" 
    data-group-id="<?=$r['id']?>">
    <i class="fas <?=$icon_class?>"></i> <?=$r['group_name']?>
  </button>
  <?php } ?>
</div>
<hr />
<div class="form-group">
  <h6>Storefront</h6>
  <?php    
    $query = "SELECT * FROM cna_groups WHERE group_role = 4 AND group_category = 'Storefront'";
    $result = mysqli_query($dbcnx, $query) or die(mysqli_error($dbcnx));
    while($r = mysqli_fetch_assoc($result)) {
      if( in_array($r['id'], $project_groups)) {
        $btn_class = 'btn-info';
        $icon_class = 'fa-check-circle';
      } else {        
        $btn_class = 'btn-outline-info';
        $icon_class = 'fa-circle-notch';
      }
  ?>
  <button class="btn btn-sm project-group <?=$btn_class?>" 
    data-group-id="<?=$r['id']?>">
    <i class="fas <?=$icon_class?>"></i> <?=$r['group_name']?>
  </button>
  <?php } ?>
</div>
<hr />
<div class="form-group">
  <h6>Flooring</h6>
  <?php    
    $query = "SELECT * FROM cna_groups WHERE group_role = 4 AND group_category = 'Flooring'";
    $result = mysqli_query($dbcnx, $query) or die(mysqli_error($dbcnx));
    while($r = mysqli_fetch_assoc($result)) {
      if( in_array($r['id'], $project_groups)) {
        $btn_class = 'btn-info';
        $icon_class = 'fa-check-circle';
      } else {        
        $btn_class = 'btn-outline-info';
        $icon_class = 'fa-circle-notch';
      }
  ?>
  <button class="btn btn-sm project-group <?=$btn_class?>" 
    data-group-id="<?=$r['id']?>">
    <i class="fas <?=$icon_class?>"></i> <?=$r['group_name']?>
  </button>
  <?php } ?>
</div>
<hr />
<div class="form-group">
  <h6>Millwork/Fixtures/Shelving</h6>
  <?php    
    $query = "SELECT * FROM cna_groups WHERE group_role = 4 AND group_category = 'Millwork/Fixtures/Shelving'";
    $result = mysqli_query($dbcnx, $query) or die(mysqli_error($dbcnx));
    while($r = mysqli_fetch_assoc($result)) {
      if( in_array($r['id'], $project_groups)) {
        $btn_class = 'btn-info';
        $icon_class = 'fa-check-circle';
      } else {        
        $btn_class = 'btn-outline-info';
        $icon_class = 'fa-circle-notch';
      }
  ?>
  <button class="btn btn-sm project-group <?=$btn_class?>" 
    data-group-id="<?=$r['id']?>">
    <i class="fas <?=$icon_class?>"></i> <?=$r['group_name']?>
  </button>
  <?php } ?>
</div>
<hr />
<div class="form-group">
  <h6>Music/Phones/Alarm/Networking</h6>
  <?php    
    $query = "SELECT * FROM cna_groups WHERE group_role = 4 AND group_category = 'Music/Phones/Alarm/Networking'";
    $result = mysqli_query($dbcnx, $query) or die(mysqli_error($dbcnx));
    while($r = mysqli_fetch_assoc($result)) {
      if( in_array($r['id'], $project_groups)) {
        $btn_class = 'btn-info';
        $icon_class = 'fa-check-circle';
      } else {        
        $btn_class = 'btn-outline-info';
        $icon_class = 'fa-circle-notch';
      }
  ?>
  <button class="btn btn-sm project-group <?=$btn_class?>" 
    data-group-id="<?=$r['id']?>">
    <i class="fas <?=$icon_class?>"></i> <?=$r['group_name']?>
  </button>
  <?php } ?>
</div>

<hr />
<div class="form-group">
  <h6>Misc</h6>
  <?php    
    $query = "SELECT * FROM cna_groups WHERE group_role = 4 AND group_category = 'Misc'";
    $result = mysqli_query($dbcnx, $query) or die(mysqli_error($dbcnx));
    while($r = mysqli_fetch_assoc($result)) {
      if( in_array($r['id'], $project_groups)) {
        $btn_class = 'btn-info';
        $icon_class = 'fa-check-circle';
      } else {        
        $btn_class = 'btn-outline-info';
        $icon_class = 'fa-circle-notch';
      }
  ?>
  <button class="btn btn-sm project-group <?=$btn_class?>" 
    data-group-id="<?=$r['id']?>">
    <i class="fas <?=$icon_class?>"></i> <?=$r['group_name']?>
  </button>
  <?php } ?>
</div>
<hr />
<div class="form-group">
  <h6>Other</h6>
  <?php    
    $query = "SELECT * FROM cna_groups WHERE group_role = 4 AND group_category = ''";
    $result = mysqli_query($dbcnx, $query) or die(mysqli_error($dbcnx));
    while($r = mysqli_fetch_assoc($result)) {
      if( in_array($r['id'], $project_groups)) {
        $btn_class = 'btn-info';
        $icon_class = 'fa-check-circle';
      } else {        
        $btn_class = 'btn-outline-info';
        $icon_class = 'fa-circle-notch';
      }
  ?>
  <button class="btn btn-sm project-group <?=$btn_class?>" 
    data-group-id="<?=$r['id']?>">
    <i class="fas <?=$icon_class?>"></i> <?=$r['group_name']?>
  </button>
  <?php } ?>
</div>

</form>


<script type="text/javascript">
$(document).ready(function(){

  $.load_project_whitelist = function load_project_whitelist(folder_type) {
    $.ajax({
      url: 'project-groups-action.php',
      method: 'POST',
      data: {
        action: 'fetch_whitelist', 
        project_id: <?=$id?>,
      },
      success:function(data) {
        $('#project-whitelist').html(data);
      }
    });
  }
    
  $('#group-form').on('click', 'button.project-group', function(e) {
    e.preventDefault();
    var group_id = $(this).attr('data-group-id');
    var button = $(this);
    $.ajax({
      url: 'project-groups-action.php',
      method: 'POST',
      data: {
        action: 'toggle',
        project_id: <?=$id?>,
        group_id: group_id,
        whitelister: <?=$_SESSION['unique_user_id']?>
      },      
      success:function(res) {        
        if(res.msg == 'GROUP_ADDED') {
          button.removeClass('btn-outline-info').addClass('btn-info');
          button.find('i.fas').removeClass('fa-circle-notch').addClass('fa-check-circle');
        } else if(res.msg == 'GROUP_REMOVED') {
          button.removeClass('btn-info').addClass('btn-outline-info');
          button.find('i.fas').removeClass('fa-check-circle').addClass('fa-circle-notch');
        } else { 
          wizardAlert('Error: ' + res.msg);
        }      
      }
    });
  });

  function wizardAlert(string) {
    $('#group-wizard-message').fadeIn();
    $('#group-wizard-message').append('<div>'+string+'</div>');
  }  
  $.load_project_whitelist();
});
</script>
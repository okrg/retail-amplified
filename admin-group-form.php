<?php
if ($usergroup != 0) {
  die('You do not access to this page.');
} else {
  if(isset($_GET['id'])) {
    $action = "edit";
    $form_title = 'Edit Group';  
    $group = get_group_array_by_id($_GET['id']);
  } else {
    $action = "add";
    $form_title = 'Add Group';
  }

}
?>

<div id="content">
  <h1><?=$form_title?></h1>
  <form id="group-form">
  <input type="hidden" id="group_id" value="<?=$group['id']?>">
  <div class="form-group">
    <label for="group_name">Name</label>
    <input type="email" class="form-control" id="group_name" value="<?=$group['group_name']?>">
  </div>
  <div class="form-group">
    <label for="group_role">Access Level</label>
    <select class="form-control" id="group_role">
      <?php if($action == "edit"): ?>
      <option value="<?=$group['group_role']?>">
        <?=get_group_role_name($group['group_role'])?>
      </option>
      <?php endif; ?>
      <option>&mdash;</option>   
      <option value="1">System Admin</option>
      <option value="2">Corp. Real Estate and Construction</option>
      <option value="3">Ops</option>
      <option value="4">Vendor/Arch/Consultant</option>    
    </select>
  </div>
  <div class="form-group">
    <label for="group_category">Category</label>
    <select class="form-control" id="group_category">
      <?php if($action == "edit"): ?>
      <option value="<?=$group['group_category']?>">
        <?=$group['group_category']?>
      </option>
      <?php endif; ?>
      <option>&mdash;</option>
      <option value="general contractors">General Contractors</option>
      <option value="lighting">Lighting</option>
      <option value="architects/engineers">Architects/Engineers</option>
      <option value="Storefront">Storefront</option>
      <option value="Signage">Signage</option>
      <option value="Storefront">Storefront</option>
      <option value="Flooring">Flooring</option>
      <option value="Millwork/Fixtures/Shelving">Millwork/Fixtures/Shelving</option>
      <option value="Music/Phones/Alarm/Networking">Music/Phones/Alarm/Networking</option>
      <option value="Misc">Misc</option>      
    </select>
  </div>
  <div class="alert alert-info" id="wizard-message" style="display: none;"></div>
  <button id="submit-form" class="btn btn-info my-2">Submit</button>
  <button id="cancel-form" class="btn btn-secondary my-2">Cancel</button>  
  </form>
</div>

<script type="text/javascript">
$(document).ready(function(){  
  $('#cancel-form').click(function(e) {
    e.preventDefault();
    window.location = '/index.php?page=admin-groups';
  });
  $('#submit-form').click(function(e) {    
    e.preventDefault();
    $('#wizard-message').text('').hide();
    hasError = false;
    group_id = $('#group_id').val();
    group_name = $('#group_name').val();    
    group_role = $('#group_role option:checked').val();
    group_category = $('#group_category option:checked').val();
    
    if ( group_name == '' ) {
      wizardAlert('You must enter a  name');
      hasError = true ;
    }

    if ( group_role == '') {
      wizardAlert('You must select a group level');
      hasError = true ;
    }

    if(hasError)
      return false;

    $.ajax({
      url: 'admin-group-action.php',
      method: 'POST',
      data: {
        action: '<?=$action?>',
        group_id: group_id,
        group_name: group_name,        
        group_role: group_role,
        group_category: group_category,
      },      
      success:function(res) {        
        if(res.msg == 'GROUP_ADDED') {
          wizardAlert(group_name + ' has been added.');
          $('#group-form').trigger('reset');
        } else if(res.msg == 'GROUP_UPDATED') {
          wizardAlert(group_name + ' has been updated.');
        } else if(res.msg == 'GROUP_EXISTS') {
          wizardAlert(group_name + ' already exists in our system.'); 
        } else { 
          wizardAlert('Error: ' + res.msg);
        }      
      }
    });
  });

  function wizardAlert(string) {
    $('#wizard-message').fadeIn();
    $('#wizard-message').append('<div>'+string+'</div>');
  }  
});
</script>
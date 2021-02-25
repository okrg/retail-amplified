<?php
if ($usergroup != 0) {
  die('You do not access to this page.');
} else {
  //Get groups array
  $groups = get_groups_array();
  
  if(isset($_GET['id'])) {
    $action = "edit";
    $form_title = 'Edit User';  
    $user = get_user_array_by_id($_GET['id']);
  } else {
    $action = "add";
    $form_title = 'Add User';
  }

}
?>

<div id="content">
  <h1><?=$form_title?></h1>
  <form id="user-form">
  <input type="hidden" id="user_id" value="<?=$user['id']?>">
  <div class="form-group">
    <label for="user_name">Name</label>
    <input type="text" class="form-control" id="user_name" value="<?=$user['user_name']?>">
  </div>
  <div class="form-group">
    <label for="user_email">Email address</label>
    <input type="email" class="form-control" id="user_email" value="<?=$user['user_email']?>">
  </div>
  <div class="form-group">
    <label for="user_group">Group</label>
    <select class="form-control" id="user_group">
      <?php if($action == "edit"): ?>
      <option value="<?=$user['user_group']?>">
        <?=get_group_name_by_id($user['user_group'])?>
      </option>
      <?php endif; ?>
      <option>&mdash;</option>      
    <?php foreach($groups as $group):?>
      <option value="<?=$group['id']?>"><?=$group['group_name']?></option>
    <?php endforeach; ?>
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
    window.location = '/index.php?page=admin-users';
  });
  $('#submit-form').click(function(e) {    
    e.preventDefault();    
    $('#wizard-message').text('').hide();
    hasError = false;
    user_id = $('#user_id').val();
    user_email = $('#user_email').val();
    user_name = $('#user_name').val();    
    user_group = $('#user_group option:checked').val();
    
    if ( user_name == '' ) {
      wizardAlert('You must enter a  name');
      hasError = true ;
    }

    if ( user_email == '' ) {
      wizardAlert('You must enter an email');
      hasError = true ;
    }

    if ( user_group == 'null') {
      wizardAlert('You must select a group');
      hasError = true ;
    }

    if(hasError)
      return false;

    $.ajax({
      url: 'admin-user-action.php',
      method: 'POST',
      data: {
        action: '<?=$action?>',
        user_id: user_id,
        user_name: user_name,
        user_email: user_email,        
        user_group: user_group,
      },      
      success:function(res) {        
        if(res.msg == 'USER_ADDED') {
          wizardAlert(user_email + ' has been sent an invite email with login information.');
          $('#user-form').trigger('reset');
        } else if(res.msg == 'USER_UPDATED') {
          wizardAlert(user_email + ' has been updated.');
        } else if(res.msg == 'USER_EXISTS') {
          wizardAlert(user_email + ' already exists in our system.'); 
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
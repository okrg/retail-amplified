<?php
$user_id = $_SESSION['unique_user_id'];
$user = get_user_array_by_id($user_id);
?>
<div class="card my-3">
  <div class="card-body">
    <h5 class="card-title">Update Password</h5>

  <form id="passsword">
  <input type="hidden" id="user_id" value="<?=$user['id']?>">
  <div class="form-group">
    <label for="old_pass">Old Password</label>
    <input type="password" class="form-control" id="old_pass">
  </div>
  <div class="form-group">
    <label for="new_pass">New Password</label>
    <input type="password" class="form-control" id="new_pass">
  </div>
  <div class="form-group">
    <label for="new_pass_confirm">Confirm New Password</label>
    <input type="password" class="form-control" id="new_pass_confirm">
  </div>  
  <div class="alert alert-info" id="wizard-message" style="display: none;"></div>

  <button id="submit-form" class="btn btn-info my-2">Submit</button>  
  </form>
</div>
</div>

<script type="text/javascript">
$(document).ready(function(){  

  $('#submit-form').click(function(e) {    
    e.preventDefault();
    $('#wizard-message').text('').hide();
    hasError = false;
    user_id = $('#user_id').val();
    old_pass = $('#old_pass').val();
    new_pass = $('#new_pass').val();
    new_pass_confirm = $('#new_pass_confirm').val();
    
    if ( old_pass == '' ) {
      wizardAlert('You must enter an old password.');
      hasError = true ;
    }

    if ( new_pass == '' ) {
      wizardAlert('You must enter a new password.');
      hasError = true ;
    }

    if ( new_pass_confirm == '' ) {
      wizardAlert('You must confirm the new password.');
      hasError = true ;
    }

    if ( new_pass != new_pass_confirm ) {
      wizardAlert('Your new password was not confirmed when you entered it a second time.');
      hasError = true ;
    }

    if(hasError)
      return false;

    $.ajax({
      url: 'user-password-action.php',
      method: 'POST',
      data: {
        user_id: user_id,
        old_pass: old_pass,
        new_pass: new_pass,            
      },      
      success:function(res) {        
        if(res.msg == 'INVALID_OLD_PASSWORD') {
          wizardAlert('Your old password was not correct. Please try again.');
        }else if(res.msg == 'PASSWORD_UPDATED') {
          wizardAlert('Your password has been updated.');
          $('#passsword').trigger('reset');        
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
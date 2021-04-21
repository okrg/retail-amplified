<?php
if ($usergroup != 0) {
  die('You do not access to this page.');
} else {
  $action = 'add';
  $form_title = 'New Project';
}
?>

<div id="content">
  <h1><?=$form_title?></h1>
  <form id="user-form">
  <input type="hidden" id="user_id" value="<?=$user['id']?>">
  <div class="form-group">
    <label for="project_name">Project/Location Name</label>
    <input type="text" class="form-control" id="project_name">
  </div>
  <div class="alert alert-info" id="wizard-message" style="display: none;"></div>
  <button id="submit-form" class="btn btn-info my-2">Add Project</button>
  </form>
</div>

<script type="text/javascript">
$(document).ready(function(){

  $('#submit-form').click(function(e) {
    e.preventDefault();
    $('#wizard-message').text('').hide();
    hasError = false;
    user_id = $('#user_id').val();
    project_name = $('#project_name').val();

    if ( project_name == '' ) {
      wizardAlert('You must enter a project name.');
      hasError = true ;
    }

    if(hasError)
      return false;

    $.ajax({
      url: 'admin-project-action.php',
      method: 'POST',
      data: {
        action: '<?=$action?>',
        project_name: project_name
      },
      success:function(res) {
        if(res.msg == 'PROJECT_ADDED') {
          console.log(res);
          window.location = '/index.php?page=project&id=' + res.insert_id;
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
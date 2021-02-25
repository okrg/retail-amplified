<?php
//set id of current user;
$user_id = $_SESSION['unique_user_id'];
$user = get_user_array_by_id($user_id);
$group = get_group_array_by_id($user['user_group']);
?>

<div class="card my-3">
  <div class="card-body">
    <h5 class="card-title">Account Information</h5>
    <h6><?=$user['user_name']?></h6>
    <h6><?=$user['user_email']?></h6>
    <h6><?=get_group_name_by_id($user['user_group'])?> 
      <small>[<?=get_group_role_name($user['user_group'])?>]</small></h6>
    <button class="btn btn-info my-2" id="logout-form">Log Out</button>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function(){  
  $('#logout-form').click(function(e) {
    e.preventDefault();
    window.location = 'logout.php';
  });  
});
</script>
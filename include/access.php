<?php // access.php
error_reporting(E_ALL);
include(realpath(dirname(__FILE__)).'/common.php');
include(realpath(dirname(__FILE__)).'/db.php');

$authorized = FALSE;
session_start();

function load_user_session_data($uid) {
  global $dbcnx;
  $sql = "SELECT * FROM cna_users WHERE user_email = '$uid' LIMIT 1";
  $_user = mysqli_query($dbcnx, $sql);
  $_user_data = mysqli_fetch_assoc($_user);
  $_SESSION['uid'] = $uid;
  $_SESSION['user_id'] = $_user_data['id'];
  $_SESSION['unique_user_id'] = $_user_data['id'];
  $_SESSION['user_email'] = $_user_data['user_email'];
  $_SESSION['user_name'] = $_user_data['user_name'];
  $_SESSION['user_group'] = $_user_data['user_group'];

  //Get user group role
  $user_group_id = $_user_data['user_group'];
  $sql = "SELECT * FROM cna_groups WHERE id = $user_group_id LIMIT 1";
  $_group = mysqli_query($dbcnx, $sql);
  $_group_data = mysqli_fetch_assoc($_group);
  $_SESSION['user_group_role'] = $_group_data['group_role'];
  $_SESSION['user_group_name'] = $_group_data['group_name'];
  $_SESSION['user_group_category'] = $_group_data['group_category'];

  return true;
}

// Check if user has been remembered 
if(isset($_COOKIE['uid'])){
  $authorized = TRUE;
  load_user_session_data($_COOKIE['uid']);  
}

if(!$authorized && isset($_POST['uid']) && isset($_POST['pwd'])) {
  $uid = mysqli_real_escape_string($dbcnx, $_POST['uid']);
  $pwd = mysqli_real_escape_string($dbcnx, $_POST['pwd']);  
  $query = "SELECT * FROM cna_users WHERE user_email = '$uid' AND user_pass='$pwd'";
  $result = mysqli_query($dbcnx, $query);
  if (mysqli_num_rows($result) > 0) { 
    $authorized = TRUE;
    if( load_user_session_data($uid)) {
      setcookie('uid', $_SESSION['uid'], time()+60*60*24*100, '/');
      header('Location: /index.php');
    }
  }
}

if(!$authorized) { 
?>
<!doctype html>
<html lang="en">
<head>
  <title>Collaboration Network App</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style type="text/css" media="all">@import "/dist/css/screen.css";</style>
</head>  
<body onLoad="document.login.uid.focus()">
  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-body">
            <h5 class="card-title text-center">Sign In</h5>
            <form name="login" method="post" action="<?=$_SERVER['PHP_SELF']?>?<?=$_SERVER['QUERY_STRING']?>" class="form-signin">
              <div class="form-label-group">
                <input type="email" name="uid" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
                <label for="inputEmail">Email address</label>
              </div>

              <div class="form-label-group">
                <input type="password" name="pwd" id="inputPassword" class="form-control" placeholder="Password" required>
                <label for="inputPassword">Password</label>
              </div>

              <div class="custom-control custom-checkbox mb-3">
                <input type="checkbox" class="custom-control-input" id="customCheck1">
                <label class="custom-control-label" for="customCheck1">Remember password</label>
              </div>
              <button class="btn btn-lg btn-info btn-block text-uppercase" type="submit">Sign in</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

  <?php
  exit;
}
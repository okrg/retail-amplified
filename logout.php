<?php // logout.php
include("include/access.php");
setcookie("uid", "", time()-60*60*24*100, "/");   
session_unset();
session_destroy();
?>

<!doctype html>
<html lang="en">
<head>
  <title>Collaboration Network</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style type="text/css" media="all">@import "/dist/css/screen.css";</style>
</head>  
<body>
  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-body text-center">
            <h5 class="card-title text-center">👋 Logged Out</h5>
            <p>You have been successfully logged out of the system.</p>
            <p><a href="index.php">Click here</a> to log in to the system again.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
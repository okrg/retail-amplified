<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include("include/access.php");
if(isset($_GET['page'])) {
  $page = $_GET['page']; 
} else {
  $page = "home";
}

?>
<!doctype html>
<html lang="en">
<head>
  <title>Collaboration Network</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <script type="text/javascript" src="/dist/js/site.js"></script>
  <link rel="stylesheet" href="/dist/css/plugins.css">
  <link rel="stylesheet" href="/dist/css/screen.css">
</head>
<body>
  <?php include('include/header.php'); ?>

<script type="text/javascript">
$(document).ready(function(){
  
  $('ul.typeahead').on('mousedown', 'li', function(e) {
      e.preventDefault();
  });

  <?php
  if ($usergroup == 3) {
    $sql = "SELECT id, sitename, project_type, store_number FROM projects WHERE `companyarray` LIKE '%:\"".$usercompany."\";%' ";
  } else {
    $sql = "SELECT id, sitename, project_type, store_number FROM projects WHERE project_status != 'real_estate'";
  }
    $result = mysqli_query($dbcnx, $sql);
    if (!$result){error("A databass error has occured....\\n".mysqli_error($dbcnx));}
    if (mysqli_num_rows($result)>0) {
      while ($row = mysqli_fetch_array($result)) {        
        $itemVar = '';
        
        if($row['store_number'] != '0') {
          $itemVar .= '#'. $row['store_number'] . ' - ';
        }
        
        $itemVar .= $row['sitename'];

        if($row['project_type'] != '') {
          $itemVar .= ' [' . ucwords($row['project_type']) . ']';
        }

        $jsondata[$itemVar] = $row['id'];        
      }
    }
  ?>
  
  var storelist = <?php echo json_encode($jsondata); ?>

  $('#search-query').typeahead({
    minLength:2,
    updater: function (item) {          
          window.location.href = '/index.php?page=project&id=' + storelist[item];
      },
    source: function (typeahead, query) {
      var stores=[];
      for (var sitename in storelist){
          stores.push(sitename); 
      }
      return stores;
      }
  });
  
  $('#search-clear').click(function() {
    $('#search-query').val('');
  });
});
</script>

<main role="main" class="container">
  <?php include($page.".php"); ?>
</main>
 </body>
</html>
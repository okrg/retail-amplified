<?php 
  $query = "SELECT * FROM tracker WHERE id = $id";
  $result = mysqli_query($dbcnx, $query) or die ("no query"); 
  $data = array();
  while($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }
  $tracker = $data[0];
?>
<div id="project-summary-form" class="p-2">

  <div class="row">
    <div class="col">
      <strong>Tenant</strong>
      <div><?=$tracker['tenant']?></div>
    </div>
    <div class="col">
      <strong>Stage</strong>
      <div><?=$tracker['stage']?></div>
    </div>
    <div class="col">
      <strong>Risk</strong>
      <div><?=$tracker['risk']?></div>
    </div>
  </div>

</div>
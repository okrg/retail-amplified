<?php

//try to get user columns
$sql = "SELECT home_columns FROM cna_users WHERE id = ".$_SESSION['unique_user_id'];
$result = mysqli_query($dbcnx, $sql);
if (!$result){
  error("A databass error has occured 1.\\n".mysqli_error($dbcnx));
}
$row = mysqli_fetch_array($result);
$home_columns = json_decode($row[0]);


if(empty($home_columns)) {
  //default columns
  $home_columns = array(
    'tenant',
    'store_number',
    'sitename',
    'sitespace',
    'sitecity',
    'sitestate',
    'project_status',
    'stage',
    'risk'
  );
}

$column_labels = array();
$column_labels['stage'] = 'Stage';
$column_labels['risk'] = 'Risk';
$column_labels['sitecity'] = 'City';
$column_labels['sitestate'] = 'State';
$column_labels['store_district'] = 'District';
$column_labels['store_region'] = 'Region';
$column_labels['project_manager'] = 'Project Manager';
$column_labels['architect'] = 'Architect';
$column_labels['general_contractor'] = 'General Contractor';
$column_labels['landlord'] = 'Landlord';
$column_labels['tenant'] = 'Tenant';
$column_labels['store_number'] = 'Store #';
$column_labels['sitename'] = 'Location';
$column_labels['sitespace'] = 'Space #';
$column_labels['project_status'] = 'Status';

$all_columns = array(
  'tenant',
  'store_number',
  'sitename',
  'sitespace',
  'project_status',
  'stage',
  'risk',
  'sitecity',
  'sitestate',
  'store_district',
  'store_region',
  'project_manager',
  'architect',
  'general_contractor',
  'landlord'
);

$available_columns = array_diff($all_columns, $home_columns);

$columns = implode(',', $home_columns);
?>

<div class="modal" tabindex="-1" role="dialog" id="columns-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Columns</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form  class="creplyform p-3" id="columns-form">
          <input type="hidden" id="columns-user_id" value="<?=$_SESSION['unique_user_id'];?>"/>

          <div id="shared-lists" class="row">
            <div class="col">
              <header>Available Columns</header>
              <div id="available_columns" class="list-group">
                <?php foreach($available_columns as $available_column): ?>
                  <div class="list-group-item" data-column="<?=$available_column;?>"><?=$column_labels[$available_column];?></div>
                <?php endforeach; ?>
              </div>
            </div>

            <div class="col">
              <header>Columns Used</header>
              <div id="columns_used" class="list-group">
                <?php foreach($home_columns as $home_column): ?>
                  <div class="list-group-item" data-column="<?=$home_column;?>"><?=$column_labels[$home_column];?></div>
                <?php endforeach; ?>                
              </div>
            </div>
          </div>

          <div class="form group text-center">
            <button class="btn btn-info" id="submit-home-columns">Save</button>
          </div>
        </form>

      </div>

    </div>
  </div>
</div>



<script type="text/javascript">
$(document).ready(function(){ 
  var columns_used_data;
  new Sortable(available_columns, {
      group: 'shared', // set both lists to same group
      animation: 150
  });

  new Sortable(columns_used, {
      group: 'shared',
      animation: 150,
      onSort: function(evt) {
        columns_used_data = new Array();
        $('#columns_used div.list-group-item').each(function() {
          columns_used_data.push( $(this).attr('data-column') );
        });
      }
  });


  $('#submit-home-columns').click(function(e) {
    e.preventDefault();
    var user_id = $('#columns-user_id').val();
    columns_used_data = new Array();
    $('#columns_used div.list-group-item').each(function() {
      columns_used_data.push( $(this).attr('data-column') );
    });
    $.ajax({
      url: 'admin-home-columns.php',
      method: 'POST',
      data: {
        action: 'edit',
        user_id: user_id,
        home_columns: JSON.stringify(columns_used_data)
      },
      success:function(res) {
        if(res.msg == 'COLUMNS_UPDATED') {
          location.reload();
        }
      }
    });
  });
});
</script>
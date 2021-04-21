<?php
if ($usergroup != 0) {
  exit('You do not have sufficient privledges to view this page');
} else {
  $query = "SELECT * FROM cna_users ORDER BY date_added";
  $result = mysqli_query($dbcnx, $query) or die(mysqli_error($dbcnx));  
}


?>

  <h1>Users</h1>
  <div class="my-2">
    <a class="btn btn-info" href="index.php?page=admin-user-form">Add New User</a>
  </div>

<form class="sticky-top filter-form">
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="group-filter">Filter by Group</label>
      <div id="group-filter"></div>
    </div>    
    <div class="form-group col-md-6">
      <label>Search</label>
      <input type="text" class="form-control" id="mySearchText" placeholder="Enter name, email, or group" autocomplete="off">      
    </div>
  </div>
</form>



<table class="table table-striped table-bordered" id="user-list-table">
  <thead>
    <tr>      
      <th>Name</th>      
      <th>Email</th>      
      <th class="group-filter">Group</th>
      <th>Date Added</th>      
    </tr>
  </thead>
  <tbody>
  <?php while ($row = mysqli_fetch_array($result)): ?>
    <tr data-user-id="<?=$row['id']?>">
      <td><?=$row['user_name']?></td>
      <td><?=$row['user_email']?></td>
      <td><?=get_group_name_by_id($row['user_group'])?></td>
      <td><?=$row['date_added']?></td>      
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>

<script type="text/javascript">
  $(document).ready(function() {
    var table = $('#user-list-table').DataTable({
      order: [],
      paging: false,
      columnDefs: [
        {type: "string"},
        {type: "string"},
        {type: "string"},
        {type: "date"},        
      ],
      initComplete: function () {
        this.api().columns('.group-filter').every( function () {
          var column = this;
          var select = $('<select class="form-control"><option value="">All</option></select>')
            .appendTo( $('#group-filter') )
            .on( 'change', function () {
              var val = $.fn.dataTable.util.escapeRegex( $(this).val() );
              column.search( val ? '^'+val+'$' : '', true, false ).draw();
            }); 
          column.data().unique().sort().each( function ( d, j ){
            select.append( '<option value="'+d+'">'+d+'</option>' )
          });
        });
      }
    });
    $('#user-list-table tbody').on('click', 'tr', function () {
      var uid = $(this).attr('data-user-id');
      window.location = '/index.php?page=admin-user-form&id='+uid;
    });
    $('#mySearchText').on( 'keyup', function (e) {
      e.preventDefault();
      table.search($('#mySearchText').val()).draw();
    });
  });
</script>
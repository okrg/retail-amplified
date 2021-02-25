<?php
if ($usergroup != 0) {
  exit('You do not have sufficient privledges to view this page');
} else {
  $query = "SELECT * FROM cna_groups ORDER BY date_added";
  $result = mysqli_query($dbcnx, $query) or die(mysqli_error($dbcnx));  
}


?>

  <h1>Group</h1>
  <div class="my-2">
    <a class="btn btn-info" href="index.php?page=admin-group-form">Add New Group</a>
  </div>

<form class="sticky-top filter-form">
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="role-filter">Filter by Group</label>
      <div id="role-filter"></div>
    </div>    
    <div class="form-group col-md-6">
      <label>Search</label>
      <input type="text" class="form-control" id="mySearchText" placeholder="Enter name" autocomplete="off">      
    </div>
  </div>
</form>



<table class="table table-striped table-bordered" id="group-list-table">
  <thead>
    <tr>      
      <th>Name</th>      
      <th class="role-filter">Role</th>
      <th>Date Added</th>      
    </tr>
  </thead>
  <tbody>
  <?php while ($row = mysqli_fetch_array($result)): ?>
    <tr data-group-id="<?=$row['id']?>">
      <td><?=$row['group_name']?></td>
      <td><?=get_group_role_name($row['group_role'])?></td>
      <td><?=$row['date_added']?></td>      
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>

<script type="text/javascript">
  $(document).ready(function() {
    var table = $('#group-list-table').DataTable({
      order: [],
      paging: false,
      columnDefs: [
        {type: "string"},
        {type: "string"},        
        {type: "date"},        
      ],
      initComplete: function () {
        this.api().columns('.role-filter').every( function () {
          var column = this;
          var select = $('<select class="form-control"><option value="">All</option></select>')
            .appendTo( $('#role-filter') )
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
    $('#group-list-table tbody').on('click', 'tr', function () {
      var uid = $(this).attr('data-group-id');
      window.location = '/index.php?page=admin-group-form&id='+uid;
    });
    $('#mySearchText').on( 'keyup', function (e) {
      e.preventDefault();
      table.search($('#mySearchText').val()).draw();
    });
  });
</script>
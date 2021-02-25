<?php
  $sql = "SELECT * FROM projects WHERE project_status != 'real_estate' order by datetouched DESC";
  $result = mysqli_query($dbcnx, $sql);
  if (!$result){
    error("A databass error has occured 1.\\n".mysqli_error($dbcnx));
  }
?>

<form class="sticky-top filter-form">
  <div class="form-row">
    <div class="form-group col-md-3">
      <label for="inputPassword4">Filter by Region</label>
      <div id="region-filter"></div>
    </div>
    <div class="form-group col-md-3">
      <label for="inputEmail4">Filter by Status</label>
      <div id="status-filter"></div>
    </div>    
    <div class="form-group col-md-6">
      <label>Search</label>
      <input type="text" class="form-control" id="mySearchText" placeholder="Enter name, store number, city" autocomplete="off">      
    </div>
  </div>
</form>



<table class="table table-striped table-bordered" id="project-list-table">
  <thead>
    <tr>
      <th>#</th>
      <th>Location</th>
      <th>City</th>
      <th>State</th>      
      <th>District</th>
      <th class="region-filter">Region</th>      
      <th class="status-filter">Status</th>      
    </tr>
  </thead>
  <tbody>
  <?php while ($row = mysqli_fetch_array($result)): ?>
    <tr data-project-id="<?=$row['id']?>">
      <td><?=$row['store_number']?></td>
      <td><?=$row['sitename']?></td>
      <td><?=$row['sitecity']?></td>
      <td><?=$row['sitestate']?></td>      
      <td><?=$row['store_district']?></td>
      <td><?=$row['store_region']?></td>
      <td><?=$row['project_status']?></td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>

<script type="text/javascript">
  $(document).ready(function() {
    var table = $('#project-list-table').DataTable({
      order: [],
      paging: false,
      columnDefs: [
        {type: "string"},
        {type: "num"},
        {type: "num"},
        {type: "num"},
        {type: "string"}
      ],
      initComplete: function () {
            this.api().columns('.region-filter').every( function () {
                var column = this;
                var select = $('<select class="form-control"><option value="">All</option></select>')
                    .appendTo( $('#region-filter') )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } ); 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
            this.api().columns('.status-filter').every( function () {
                var column = this;
                var select = $('<select class="form-control"><option value="">All</option></select>')
                    .appendTo( $('#status-filter') )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } ); 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
    });
    $('#project-list-table tbody').on('click', 'tr', function () {
      var pid = $(this).attr('data-project-id');
      window.location = '/index.php?page=project&id='+pid;
    });
    $('#mySearchText').on( 'keyup', function (e) {
      e.preventDefault();
      table.search($('#mySearchText').val()).draw();
    });
  });
</script>
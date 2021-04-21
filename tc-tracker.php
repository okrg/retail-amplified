<?php
  $sql = "SELECT tracker.id, tracker.project_id, tracker.tenant, tracker.stage, tracker.risk, tracker.date_modified, tracker.sitename, tracker.sitespace, tracker.location FROM tracker order by tracker.date_modified DESC";
  $result = mysqli_query($dbcnx, $sql);
  if (!$result){
    error("A databass error has occured 1.\\n".mysqli_error($dbcnx));
  }
?>

<h2>TC Tracker</h2>

<form class="sticky-top filter-form">
  <div class="form-row">
    <div class="form-group col-md-3">
      <label for="inputPassword4">Filter by Stage</label>
      <div id="stage-filter"></div>
    </div>
    <div class="form-group col-md-3">
      <label for="inputEmail4">Filter by Risk</label>
      <div id="risk-filter"></div>
    </div>
    <div class="form-group col-md-6">
      <label>Search</label>
      <input type="text" class="form-control" id="mySearchText" placeholder="Tenant, Space, Center, Location" autocomplete="off">
    </div>
  </div>
</form>

<table class="table table-striped table-bordered" id="project-list-table">
  <thead>
    <tr>
      <th>Tenant</th>
      <th>Space</th>
      <th>Center</th>
      <th>Location</th>
      <th class="stage-filter">Stage</th>
      <th class="risk-filter">Risk</th>
    </tr>
  </thead>
  <tbody>
  <?php while ($row = mysqli_fetch_array($result)): ?>
    <tr data-project-id="<?=$row['id']?>">
      <td><?=$row['tenant']?></td>
      <td><?=$row['sitespace']?></td>
      <td><?=$row['sitename']?></td>
      <td><?=$row['location']?></td>
      <td><?=$row['stage']?></td>
      <td><?=$row['risk']?></td>
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
        {type: "string"},
        {type: "string"},
        {type: "string"},
        {type: "string"},
        {type: "string"}
      ],
      initComplete: function () {
            this.api().columns('.risk-filter').every( function () {
                var column = this;
                var select = $('<select class="form-control"><option value="">All Risks</option></select>')
                    .appendTo( $('#risk-filter') )
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
            this.api().columns('.stage-filter').every( function () {
                var column = this;
                var select = $('<select class="form-control"><option value="">All Stages</option></select>')
                    .appendTo( $('#stage-filter') )
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
      window.location = '/index.php?page=tracker-details&id='+pid;
    });
    $('#mySearchText').on( 'keyup', function (e) {
      e.preventDefault();
      table.search($('#mySearchText').val()).draw();
    });
  });
</script>
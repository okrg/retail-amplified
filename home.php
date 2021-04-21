
<?php
  //Dashboard queries
  $sql = "SELECT count(id) FROM projects WHERE project_status = 'active'";
  $query = mysqli_query($dbcnx, $sql);
  $result = mysqli_fetch_array($query);
  $active_count = $result[0];


  $sql = "SELECT count(id) FROM projects WHERE risk = 'On schedule'";
  $query = mysqli_query($dbcnx, $sql);
  $result = mysqli_fetch_array($query);
  $on_schedule_count = $result[0];


  $sql = "SELECT count(id) FROM projects WHERE risk = 'LL Lease delay' or risk = 'TT Lease delay'";
  $query = mysqli_query($dbcnx, $sql);
  $result = mysqli_fetch_array($query);
  $lease_delay_count = $result[0];

  $sql = "SELECT count(id) FROM projects WHERE risk = 'LL Work delay' or risk = 'TT Work delay'";
  $query = mysqli_query($dbcnx, $sql);
  $result = mysqli_fetch_array($query);
  $work_delay_count = $result[0];


  $sql = "SELECT count(id) FROM projects WHERE risk = 'City delay'";
  $query = mysqli_query($dbcnx, $sql);
  $result = mysqli_fetch_array($query);
  $city_delay_count = $result[0];


  $sql = "SELECT count(id) FROM projects WHERE stage = 'TT Deal Approval'";
  $query = mysqli_query($dbcnx, $sql);
  $result = mysqli_fetch_array($query);
  $tt_deal_approval_count = $result[0];


  $sql = "SELECT count(id) FROM projects WHERE stage = 'LL Deal Approval'";
  $query = mysqli_query($dbcnx, $sql);
  $result = mysqli_fetch_array($query);
  $ll_deal_approval_count = $result[0];


  $sql = "SELECT count(id) FROM projects WHERE stage = 'Lease comments'";
  $query = mysqli_query($dbcnx, $sql);
  $result = mysqli_fetch_array($query);
  $lease_comments_count = $result[0];


  $sql = "SELECT count(id) FROM projects WHERE stage = 'TT Signed Lease'";
  $query = mysqli_query($dbcnx, $sql);
  $result = mysqli_fetch_array($query);
  $tt_signed_lease_count = $result[0];


  $sql = "SELECT count(id) FROM projects WHERE stage = 'LL Signed Lease'";
  $query = mysqli_query($dbcnx, $sql);
  $result = mysqli_fetch_array($query);
  $ll_signed_lease_count = $result[0];



  $sql = "SELECT count(id) FROM projects WHERE stage = 'LLW Construction'";
  $query = mysqli_query($dbcnx, $sql);
  $result = mysqli_fetch_array($query);
  $llw_construction_count = $result[0];


  $sql = "SELECT count(id) FROM projects WHERE stage = 'Space Delivery'";
  $query = mysqli_query($dbcnx, $sql);
  $result = mysqli_fetch_array($query);
  $space_delivery_count = $result[0];


  $sql = "SELECT count(id) FROM projects WHERE stage = 'TT Construction'";
  $query = mysqli_query($dbcnx, $sql);
  $result = mysqli_fetch_array($query);
  $tt_construction_count = $result[0];


  $sql = "SELECT count(id) FROM projects WHERE stage = 'TT Stock and Train'";
  $query = mysqli_query($dbcnx, $sql);
  $result = mysqli_fetch_array($query);
  $tt_stock_train_count = $result[0];


  $sql = "SELECT count(id) FROM projects WHERE stage = 'TT Open'";
  $query = mysqli_query($dbcnx, $sql);
  $result = mysqli_fetch_array($query);
  $tt_open_count = $result[0];

?>

<section id="home-dashboard" class="my-4">
  <div class="row">

    <div class="col-sm-3">
      <div class="card filter-card" data-filter="status" data-search="active">
        <div class="card-body">
          <h6 class="card-subtitle mb-2 text-muted">Active projects</h6>
          <p class="card-text"><?=$active_count; ?></p>
        </div>
      </div>
    </div>

    <div class="col-sm-3">
      <div class="card filter-card" data-filter="risk" data-search="schedule">
        <div class="card-body">
          <h6 class="card-subtitle mb-2 text-muted">On Schedule</h6>
          <p class="card-text"><?=$on_schedule_count; ?></p>
        </div>
      </div>
    </div>

    <div class="col-sm-3">
      <div class="card filter-card" data-filter="risk" data-search="lease delay">
        <div class="card-body">
          <h6 class="card-subtitle mb-2 text-muted">Lease Delay</h6>
          <p class="card-text"><?=$lease_delay_count; ?></p>
        </div>
      </div>
    </div>

    <div class="col-sm-3">
      <div class="card filter-card" data-filter="risk" data-search="lease delay">
        <div class="card-body">
          <h6 class="card-subtitle mb-2 text-muted">Work Delay</h6>
          <p class="card-text"><?=$work_delay_count; ?></p>
        </div>
      </div>
    </div>

  </div>
  <div class="row">

    <div class="col">
      <div class="card filter-card" data-filter="stage" data-search="TT Deal Approval">
        <div class="card-body">
          <h6 class="card-subtitle mb-2 text-muted">TT Deal Approval</h6>
          <p class="card-text"><?=$tt_deal_approval_count; ?></p>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card filter-card" data-filter="stage" data-search="LL Deal Approval">
        <div class="card-body">
          <h6 class="card-subtitle mb-2 text-muted">LL Deal Approval</h6>
          <p class="card-text"><?=$ll_deal_approval_count; ?></p>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card filter-card" data-filter="stage" data-search="Lease Comments">
        <div class="card-body">
          <h6 class="card-subtitle mb-2 text-muted">Lease Comments</h6>
          <p class="card-text"><?=$lease_comments_count; ?></p>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card filter-card" data-filter="stage" data-search="TT Signed Lease">
        <div class="card-body">
          <h6 class="card-subtitle mb-2 text-muted">TT Signed Lease</h6>
          <p class="card-text"><?=$tt_signed_lease_count; ?></p>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card filter-card" data-filter="stage" data-search="LL Signed Lease">
        <div class="card-body">
          <h6 class="card-subtitle mb-2 text-muted">LL Signed Lease</h6>
          <p class="card-text"><?=$ll_signed_lease_count; ?></p>
        </div>
      </div>
    </div>

  </div>

  <div class="row">

    <div class="col">
      <div class="card filter-card" data-filter="stage" data-search="LLW Construction">
        <div class="card-body">
          <h6 class="card-subtitle mb-2 text-muted">LLW Construction</h6>
          <p class="card-text"><?=$llw_construction_count; ?></p>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card filter-card" data-filter="stage" data-search="Space Delivery">
        <div class="card-body">
          <h6 class="card-subtitle mb-2 text-muted">Space Delivery</h6>
          <p class="card-text"><?=$space_delivery_count; ?></p>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card filter-card" data-filter="stage" data-search="TT Construction">
        <div class="card-body">
          <h6 class="card-subtitle mb-2 text-muted">TT Construction</h6>
          <p class="card-text"><?=$tt_construction_count; ?></p>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card filter-card" data-filter="stage" data-search="TT Stock and Train">
        <div class="card-body">
          <h6 class="card-subtitle mb-2 text-muted">TT Stock and Train</h6>
          <p class="card-text"><?=$tt_stock_train_count; ?></p>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card filter-card" data-filter="stage" data-search="TT Open">
        <div class="card-body">
          <h6 class="card-subtitle mb-2 text-muted">TT Open</h6>
          <p class="card-text"><?=$tt_open_count; ?></p>
        </div>
      </div>
    </div>

  </div>

  <p class="text-center"><a href="#" id="reset-filter">Reset Filters</a><p>

</section>



<?php
  include("home_columns.php");
  $sql = "SELECT id," . $columns . " FROM projects WHERE project_status != 'real_estate' order by datetouched DESC";

  $result = mysqli_query($dbcnx, $sql);
  if (!$result){
    error("A databass error has occured 1.\\n".$sql.mysqli_error($dbcnx));
  }
?>

<form class="sticky-top filter-form">
  <div class="form-row">
    <div class="form-group col-md-8">
      <label>Search</label>
      <input type="text" class="form-control" id="mySearchText" placeholder="Enter name, store number, city" autocomplete="off">
    </div>

    <div class="col-md-2">
      <label>&nbsp;</label>
      <ul class="nav nav-pills nav-fill">
        <li class="nav-item">
          <a class="nav-link" href="index.php?page=admin-project-form"><i class="fa fa-plus"></i> New Project</a>
        </li>
      </ul>
    </div>


    <div class="col-md-2">
      <label>&nbsp;</label>
      <ul class="nav nav-pills nav-fill">
        <li class="nav-item">
          <a class="nav-link" href="#" data-toggle="modal" data-target="#columns-modal"><i class="fa fa-cog"></i> Columns</a>
        </li>
      </ul>
    </div>
  </div>
</form>



<table class="table table-striped table-bordered" id="project-list-table">
  <thead>
    <tr>
      <?php foreach($home_columns as $home_column): ?>
        <th><?=$column_labels[$home_column]; ?></th>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
  <?php while ($row = mysqli_fetch_array($result)): ?>
    <tr data-project-id="<?=$row['id']?>">
      <?php foreach($home_columns as $home_column): ?>
        <td><?=$row[$home_column];?></td>
      <?php endforeach; ?>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>

<script type="text/javascript">
$(document).ready(function() {
  $('#project-list-table thead tr').clone(true).appendTo( '#project-list-table thead' );
  $('#project-list-table thead tr:eq(1) th').each( function (i) {
    var title = $(this).text();
    $(this).html( '<input class="dataTables-search" id="filter-'+title.toLowerCase()+'" type="text" placeholder="Search '+title+'" />' );
    $( 'input', this ).on( 'keyup change', function () {
      if ( table.column(i).search() !== this.value ) {
        table
          .column(i)
          .search( this.value )
          .draw();
      }
    });
  });
  var table = $('#project-list-table').DataTable({
    orderCellsTop: true,
    fixedHeader: true,
    order: [],
    paging: false
  });
  $('#project-list-table tbody').on('click', 'tr', function () {
    var pid = $(this).attr('data-project-id');
    window.location = '/index.php?page=project&id='+pid;
  });
  $('#mySearchText').on( 'keyup', function (e) {
    e.preventDefault();
    table.search($('#mySearchText').val()).draw();
  });

  $('.filter-card').click(function(){
    $('#filter-'+$(this).data('filter')).val($(this).data('search')).trigger('change');
  });

  $('#reset-filter').click(function(e){
    e.preventDefault();
    $('input.dataTables-search').each(function(){
      $(this).val('').trigger('change');
    });
  });

});
</script>
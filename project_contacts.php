<?php //project_contacts.php
//get contacts for this project
$query = "SELECT project_contacts.id AS row_id, project_contacts.position, contacts.* FROM project_contacts INNER JOIN contacts ON project_contacts.contact_id = contacts.id WHERE project_contacts.project_id = $id";
$result = mysqli_query($dbcnx, $query) or die(mysqli_error($dbcnx));
$project_contacts = array();
?>

<form class="sticky-top filter-form">
  <div class="form-row">
    <div class="form-group col-md-9">
      <label>Search</label>
      <input type="text" class="form-control" id="mySearchText" placeholder="Search position, name or company" autocomplete="off">
    </div>
    <div class="form-group col-md-3">

      <label>&nbsp;</label>
      <ul class="nav nav-pills nav-fill">
        <li class="nav-item">
          <a class="nav-link" href="#" data-toggle="modal" data-target="#contacts-modal"><i class="fa fa-plus"></i> Add New Contact</a>
        </li>
      </ul>

    </div>
  </div>
</form>


<table class="table table-striped table-bordered" id="group-list-table">
  <thead>
    <tr>
      <th>Position</th>
      <th>Name</th>
      <th>Company</th>
      <th>Email</th>
      <th>Phone</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  <?php while ($row = mysqli_fetch_array($result)): ?>
    <?php $project_contacts[] = $row['row_id']; ?>
    <tr data-group-id="<?=$row['row_id']?>">
      <td><?=$row['position']?></td>
      <td><?=$row['fname']?> <?=$row['lname']?></td>
      <td><?=$row['company']?></td>
      <td><?=$row['email']?></td>
      <td><?=$row['phone']?></td>
      <td><i class="fa fa-edit">  <i class="fa fa-trash"></td>
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
        {orderable: false, targets: [3,4,5]},
        {type: "string"},
        {type: "string"},
        {type: "string"},
        {type: "string"},
        {type: "string"}
      ]
    });
    /*
    $('#group-list-table tbody').on('click', 'tr', function () {
      var uid = $(this).attr('data-group-id');
      window.location = '/index.php?page=admin-contacts-form&id='+uid;
    });
    */
    $('#mySearchText').on( 'keyup', function (e) {
      e.preventDefault();
      table.search($('#mySearchText').val()).draw();
    });
  });
</script>


<style>
    /* Formatting search box */
    .search-box{
        width: 300px;
        position: relative;
        display: inline-block;
        font-size: 14px;
    }
    .search-box input[type="text"]{
        height: 32px;
        padding: 5px 10px;
        border: 1px solid #CCCCCC;
        font-size: 14px;
        margin-bottom:0;
    }
    .result{
        position: absolute;
        background: #fff;
        z-index: 999;
        top: 100%;
        left: 0;
    }
    .search-box input[type="text"], .result{
        width: 100%;
        box-sizing: border-box;
    }
    /* Formatting result items */
    .result div{
        margin: 0;
        padding: 7px 10px;
        border: 1px solid #CCCCCC;
        border-top: none;
        cursor: pointer;
    }
    .result div:hover{
        background: #f2f2f2;
    }
</style>






<div class="modal" tabindex="-1" role="dialog" id="contacts-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Contacts</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="project-contacts-form">
          
          <input type="hidden" id="known-contact-id">

          <div class="form-group">
            <label for="position">Position</label>
            <select class="form-control" id="position">
              <?php if($action == "edit"): ?>
              <option value="<?=$contact['position']?>">
                <?=$contact['position']?>
              </option>
              <?php endif; ?>
              <option>&mdash;</option>
              <option value="Deal Maker">Deal Maker</option>
              <option value="Tenant Coordinator">Tenant Coordinator</option>
              <option value="Project Manager">Project Manager</option>
              <option value="Architect of Record">Architect of Record</option>
              <option value="Designer">Designer</option>
              <option value="General Contractor">General Contractor</option>
              <option value="Landlord">Landlord</option>
              <option value="Construction Manager">Construction Manager</option>
              <option value="Client">Client</option>
              <option value="Tenant">Tenant</option>
              <option value="Engineer">Engineer</option>
              <option value="Vendor">Vendor</option>
            </select>
          </div>


          <div class="form-group">
            <div class="search-box">
              <input type="text" autocomplete="chrome-off" placeholder="Search name or company..." />
              <div class="result"></div>
            </div>
          </div>

          <div class="form-group">
            <div id="contact-search-result-card">
              <p>Or add a new contact</p>
              <div class="form-group">
                <label>First Name</label>
                <input class="form-control" type="text" id="contact-fname">
              </div>
              <div class="form-group">
                <label>Last Name</label>
                <input class="form-control" type="text" id="contact-lname">
              </div>
              <div class="form-group">
                <label>Company</label>
                <input class="form-control" type="text" id="contact-company">
              </div>
              <div class="form-group">
                <label>Email</label>
                <input class="form-control" type="text" id="contact-email">
              </div>
              <div class="form-group">
                <label>Phone</label>
                <input class="form-control" type="text" id="contact-phone">
              </div>
              <div class="contact-id"></div>
            </div>
          </div>

          <div class="form group text-center">
          <button class="btn btn-info" id="submit-project-contacts">Add Project Contact</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function(){






  $('#submit-project-contacts').click(function(e) {
    e.preventDefault();

    var known_contact = $('#known-contact-id').val();


    function addProjectContact() {
      var project_id = <?=$id;?>;
      var position = $('#position').val();
      var known_contact = $('#known-contact-id').val();
      $.ajax({
        url: 'admin-project-contacts-action.php',
        method: 'POST',
        data: {
          action: 'add',
          project_id: <?=$id;?>,
          contact_id: known_contact,
          position: position,
        },
        success:function(res) {
          if(res.msg == 'PROJECT_CONTACT_ADDED') {
            location.reload();
          }
        }
      });
    }


    if(!known_contact) {
      //add new contact
      
      $.ajax({
        url: 'admin-contacts-action.php',
        method: 'POST',
        data: {
          action: 'add',
          fname: $('#contact-fname').val(),
          lname: $('#contact-lname').val(),
          company: $('#contact-company').val(),
          email: $('#contact-email').val(),
          phone: $('#contact-phone').val()
        },
        success:function(res) {
          if(res.msg == 'CONTACT_ADDED') {
            var known_contact = res.insert_id;
            $('#known-contact-id').val(known_contact);
            addProjectContact();
          }
        }
      });
    } else {
      addProjectContact();
    }
  });
});

$(document).ready(function(){
    $('.search-box input[type="text"]').on("keyup input", function(){
        /* Get input value on change */
        var inputVal = $(this).val();
        var resultDropdown = $(this).siblings(".result");
        if(inputVal.length){
            $.get("backend-contacts-search.php", {term: inputVal}).done(function(data){
              resultDropdown.html(data);
            });
        } else{
            resultDropdown.empty();
        }
    });

    // Set search input value on click of result item
    $(document).on("click", ".result div", function(){
        $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
        $(this).parent(".result").empty();


        $('#contact-search-result-card #contact-fname').val($(this).attr('data-contact-fname'));
        $('#contact-search-result-card #contact-lname').val($(this).attr('data-contact-lname'));
        $('#contact-search-result-card #contact-company').val($(this).attr('data-contact-company'));
        $('#contact-search-result-card #contact-email').val($(this).attr('data-contact-email'));
        $('#contact-search-result-card #contact-phone').val($(this).attr('data-contact-phone'));

        $('#known-contact-id').val($(this).attr('data-contact-id'));

    });
});



</script>





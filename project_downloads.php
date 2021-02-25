
<div class="text-center mb-2">
  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#new-folder-modal"><i class="fa fa-upload"></i> Upload</button>  
</div>

<ul class="nav nav-tabs" id="folder-types" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-folder-type="construction" data-toggle="tab" href="#dl-construction" role="tab">Construction</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="profile-tab" data-folder-type="real_estate" data-toggle="tab" href="#dl-real-estate" role="tab">Real Estate</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="contact-tab" data-folder-type="vendor" data-toggle="tab" href="#dl-vendor" role="tab">Vendor</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="dl-construction" role="tabpanel">
    <div id="downloads_construction"></div>
  </div>
  <div class="tab-pane fade" id="dl-real-estate" role="tabpanel">
    <div id="downloads_real_estate"></div>
  </div>
  <div class="tab-pane fade" id="dl-vendor" role="tabpanel">
    <div id="downloads_vendor"></div>
  </div>
</div>

<form id="get-file" action="get_download_file.php" method="POST">
  <input type="hidden" name="project_id" id="get-project_id" />
  <input type="hidden" name="folder_type" id="get-folder_type" />
  <input type="hidden" name="folder_name" id="get-folder_name" />  
  <input type="hidden" name="file" id="get-file" />
</form>

<form id="get-zip" action="get_download_zip.php" method="POST">
  <input type="hidden" name="project_id" id="get-project_id" />
  <input type="hidden" name="folder_type" id="get-folder_type" />
  <input type="hidden" name="folder_name" id="get-folder_name" />  
</form>


<script type="text/javascript">
$(document).ready(function(){

  //Load the list of folders inside the selected folder type
  $.load_folder_list = function load_folder_list(folder_type) {
    $.ajax({
      url: 'downloads_action.php',
      method: 'POST',
      data: {
        action: 'fetch_folders', 
        project_id: <?=$id?>,
        folder_type: folder_type
      },
      success:function(data) {
        $('#downloads_'+folder_type).html(data);
      }
    });
  }

  //Load the list of files inside the selected folder
  $.load_file_list = function load_file_list(project_id, folder_type, folder_name){
    $.ajax({
      url: 'downloads_action.php',
      method: 'POST',
      data: {
        action: 'fetch_files', 
        folder_type: folder_type,
        folder_name: folder_name,
        project_id: project_id
      },
      success:function(data) {
        $('.dl-folder[data-folder-type="'+folder_type+'"][data-folder-name="'+folder_name+'"] .dl-folder-list').append(data);
      }
    });
  }

  //Bind click to folder open event
  $('#content').on('click', 'div.folder-pane-header', function() {    
    $('html, body').animate({
      scrollTop: $(this).offset().top
      }, 500);
    
    if( $(this).parent().hasClass('closed') ) {
      $(this).next('.folder-pane-content').slideDown()
      .parent().addClass('open').removeClass('closed')
      .children('.folder-pane-header').children('.symbol').html('<i class="icon-chevron-down"></i>');

      var folder_type = $(this).parent().attr('data-folder-type');
      var folder_name = $(this).parent().attr('data-folder-name');
      var project_id = $(this).parent().attr('data-project-id');
      $.load_file_list(project_id, folder_type, folder_name);

    } else {
      $(this).next('.folder-pane-content').slideUp()
      .parent().addClass('closed').removeClass('open')
      .children('.folder-pane-header').children('.symbol').html('<i class="icon-chevron-right"></i>');
    }
  });

  //Get individual file download sent to the browser by posting form
  $('#content').on('click', 'a.file', function(e) {
    e.preventDefault();
    $('input#get-project_id').val( $(this).attr('data-project-id') );
    $('input#get-folder_type').val( $(this).attr('data-folder-type') );
    $('input#get-folder_name').val( $(this).attr('data-folder-name') );
    $('input#get-file').val( $(this).attr('data-file') );
    $('#get-file').submit();
  });

  //Get zip file 
  $('#content').on('click', 'button.dl-folder-download', function(e) {
    e.preventDefault();
    $('input#get-project_id').val( $(this).attr('data-project-id') );
    $('input#get-folder_type').val( $(this).attr('data-folder-type') );
    $('input#get-folder_name').val( $(this).attr('data-folder-name') );
    $('#get-zip').submit();
  });



  //Bind download type click to reloading the list of folders
  $('#folder-types li a').click(function(){
    console.log( $(this).attr('data-folder-type') );
    load_folder_list( $(this).attr('data-folder-type') );    
  });

  //By default load folder lists for each type of file when page first loads
  $.load_folder_list('construction');
  $.load_folder_list('real_estate');
  $.load_folder_list('vendor');


});
</script>



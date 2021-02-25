
<div class="text-center mb-2">
  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#new-folder-modal"><i class="fa fa-upload"></i> Upload</button>  
</div>

<div id="photo_albums"></div>

<form id="get-photo" action="get_download_file.php" method="POST">
  <input type="hidden" name="project_id" id="get-project_id" />  
  <input type="hidden" name="folder_type" value="photos" />
  <input type="hidden" name="folder_name" id="get-folder_name" />  
  <input type="hidden" name="file" id="get-file" />
</form>

<form id="get-album-zip" action="get_download_zip.php" method="POST">
  <input type="hidden" name="project_id" id="get-project_id" />  
  <input type="hidden" name="folder_type" value="photos" />
  <input type="hidden" name="folder_name" id="get-folder_name" />  
</form>


<script type="text/javascript">
$(document).ready(function(){

  //Load the list of folders inside the selected folder type
  $.load_album_list = function load_album_list() {
    $.ajax({
      url: 'downloads_action.php',
      method: 'POST',
      data: {
        action: 'fetch_photo_albums', 
        project_id: <?=$id?>        
      },
      success:function(data) {
        $('#photo_albums').html(data);
      }
    });
  }

  //Load the list of files inside the selected folder
  $.load_photo_list = function load_photo_list(project_id, album_name){
    $.ajax({
      url: 'downloads_action.php',
      method: 'POST',
      data: {
        action: 'fetch_photos',         
        album_name: album_name,
        project_id: project_id
      },
      success:function(data) {
        $('.dl-album[data-album-name="'+album_name+'"] .dl-album-list').html(data);
      }
    });
  }

  //Bind click to folder open event
  $('#content').on('click', 'div.album-pane-header', function() {    
    $('html, body').animate({
      scrollTop: $(this).offset().top
      }, 500);
    
    if( $(this).parent().hasClass('closed') ) {
      $(this).next('.album-pane-content').slideDown()
      .parent().addClass('open').removeClass('closed')
      .children('.album-pane-header').children('.symbol').html('<i class="icon-chevron-down"></i>');

      var album_name = $(this).parent().attr('data-album-name');
      var project_id = $(this).parent().attr('data-project-id');
      $.load_photo_list(project_id, album_name);

    } else {
      $(this).next('.album-pane-content').slideUp()
      .parent().addClass('closed').removeClass('open')
      .children('.album-pane-header').children('.symbol').html('<i class="icon-chevron-right"></i>');
    }
  });

  //Get individual photo download sent to the browser by posting form
  $('#content').on('click', 'a.photo', function(e) {
    e.preventDefault();
    $('input#get-project_id').val( $(this).attr('data-project-id') );    
    $('input#get-folder_name').val( $(this).attr('data-album-name') );
    $('input#get-file').val( $(this).attr('data-file') );
    $('#get-photo').submit();
  });

  //Get zip of photo album
  $('#content').on('click', 'button.dl-album-download', function(e) {
    e.preventDefault();
    $('input#get-project_id').val( $(this).attr('data-project-id') );    
    $('input#get-folder_name').val( $(this).attr('data-album-name') );
    $('#get-album-zip').submit();
  });

  //By default load folder lists for each type of file when page first loads
  $.load_album_list();  

});
</script>



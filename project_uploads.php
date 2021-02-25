
<div class="modal" tabindex="-1" role="dialog" id="new-folder-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Upload</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning" id="wizard-message" style="display: none;"></div>

        <div id="file-funnel">
          <div id="file-funnel-1">
            <input type="hidden" id="new-folder-author" value="<?=$_SESSION['unique_user_id']?>" />
            <input type="hidden" id="project-id" value="<?=$id?>" />
            <div class="form-group new-folder-types">
              <div><label>Upload Type</label></div>
              <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-outline-info btn-sm ">
                <input type="radio" name="new-folder-type" value="real_estate" autocomplete="off"> Real Estate
                </label>
                <label class="btn btn-outline-info btn-sm">
                <input type="radio" name="new-folder-type" value="construction" autocomplete="off"> Construction
                </label>
                <label class="btn btn-outline-info btn-sm">
                <input type="radio" name="new-folder-type" value="photos" autocomplete="off"> Photos
                </label>
              </div>
            </div>

            <div class="form-group">
              <label for="new-folder-name">Folder Name</label>
              <input type="text" class="form-control" name="new-folder-name" id="new-folder-name" />          
              <div class="alert alert-warning mt-2" id="folder-message" style="display: none;">
              </div>
            </div>

            <div class="form-group">
              <label for="new-folder-description">Description</label>
              <textarea class="form-control" name="new-folder-description" id="new-folder-description"></textarea>
            </div>
            
            <div class="form-group">
              <label for="new-folder-groups">Share with...</label>
              <select multiple name="new-folder-groups" id="new-folder-groups" class="selectpicker" data-live-search="true" data-width="100%" title="Select vendors">
              <option value="10">ABC</option>
              <option value="20">XYZ</option>
              <option value="30">Company Inc</option>
              <option value="40">Demo</option>
              </select>
            </div>
            
            <div class="form-group">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="new-folder-notification">
                <label class="custom-control-label" for="new-folder-notification">Send email notification</label>
              </div>
            </div>

            <div class="text-center">
              <button class="btn btn-info" id="file-funnel-next">
                Continue <i class="fa fa-arrow-right"></i>
              </button>
            </div>

          </div>

          <div id="file-funnel-2" style="display: none;">
            <div class="text-center mb-2">
              <button class="btn btn-info" id="file-funnel-back"><i class="fa fa-arrow-left"></i> Go Back to Step 1</button>
            </div>


            <div id="fine-uploader-manual-trigger"></div>
          </div>
          
          <div id="file-funnel-3" style="display: none;">
            
            <div class="text-center mb-2">
              <button class="btn btn-info" id="file-funnel-new">
                <i class="fa fa-upload"></i> Start Another Upload</button>
              <h3>File Upload Complete!</h3>
            </div>
            <ul id="file-funnel-succeeded" class="alert alert-success"></ul>
            
          </div>

        </div>

      </div>

    </div>
  </div>
</div>



<script type="text/javascript">
$(document).ready(function(){

  //Init fine uploader
  $('#fine-uploader-manual-trigger').fineUploader({
    template: 'qq-template-manual-trigger',
    request: {
      endpoint: '/fine-uploader/server/endpoint.php',
    },
    callbacks: {
      onComplete: function(id,name,responseJson, xhr){
        var uuid = this.getUuid(id);
        if(responseJson.success) {
          $.ajax({
            url: 'process_upload.php',
            method: 'POST',
            data: {
              action: 'move_uploaded_file',
              project_id: $('#project-id').val(),
              folder_type: $('input[name="new-folder-type"]:checked').val(),
              folder_name: $('#new-folder-name').val(),
              file_name: name,
              uuid: uuid
            },
            success:function(data) {            
              console.log('Processed single upload:' + uuid);
            }
          });
        }
      },
      onAllComplete: function(succeeded,failed) {
        var succeeded_files = [];
        var failed_files = [];
        for(var id in succeeded) {
          succeeded_files.push(this.getName(id));
        }
        for(var id in failed){        
          failed_files.push(this.getName(id));
        }
        $('#file-funnel-2').fadeOut(function() {
          $('#file-funnel-3').fadeIn();          
          $.each(succeeded_files, function(i,val) {
            $('<li>'+val+'</li>').appendTo('#file-funnel-succeeded');
          });
          $.each(failed_files, function(i,val) {
            $('<li>'+val+'</li>').appendTo('#file-funnel-failed');
          });
        });
        console.log('processing uploads');
        $.ajax({
            url: 'process_upload.php',
            method: 'POST',
            data: {
              action: 'insert_file_folders_db_row',
              project_id: $('#project-id').val(),
              folder_type: $('input[name="new-folder-type"]:checked').val(),
              folder_name: $('#new-folder-name').val(),
              description: $('#new-folder-description').val(),
              groups: $('#new-folder-groups').val(),
              author_id: $('#new-folder-author').val()
            },
            success:function(data) {
              $.load_folder_list('construction');
              $.load_folder_list('real_estate');
              $.load_folder_list('vendor');
              $.load_album_list('photos');
              $('#fine-uploader-manual-trigger').fineUploader('clearStoredFiles');
              console.log('Processed completed folder ::' + $('#new-folder-name').val());
            }
          });
      }
    },
    thumbnails: {
      placeholders: {
        waitingPath: '/fine-uploader/placeholders/waiting-generic.png',
        notAvailablePath: '/fine-uploader/placeholders/not_available-generic.png'
      }
    },
    autoUpload: false
  });

  //Bind Upload button
  $('#trigger-upload').click(function() { 
    $('#fine-uploader-manual-trigger').fineUploader('uploadStoredFiles');
  });


  //Go back to step 1 before completing step 2
  $('button#file-funnel-back').click(function(e) {
    e.preventDefault();
    $('#file-funnel-2').fadeOut(function() {
      $('#fine-uploader-manual-trigger').fineUploader('cancelAll');
      $('#file-funnel-1').fadeIn();
    });
  });

  //Start a new upload
  $('button#file-funnel-new').click(function(e) {
    e.preventDefault();
    resetWizard();    
  });

  
  //Trigger step 2
  $('button#file-funnel-next').click(function(e) {
    e.preventDefault();
    $('#folder-message').text('').fadeOut();
    $('#wizard-message').text('').fadeOut();
    var hasError = false;

    var upload_type = $('input[name="new-folder-type"]:checked').val();
    var name = $('#new-folder-name').val()
    
    if ( typeof(upload_type) === 'undefined' ) {
      wizardAlert('You must select an upload type');
      hasError = true ;
    }
    
    if ( name == '' ) {
      wizardAlert('You must enter a folder name');
      hasError = true ;
    }

    if(hasError)
      return false;

    $.ajax({
      url: 'check_folder.php',
      method: 'POST',
      data: {
        project_id: $('#project-id').val(),
        folder_type: $('input[name="new-folder-type"]:checked').val(),
        folder_name: $('#new-folder-name').val()
      },
      //Check if the folder trying to be created already exists
      success:function(res) {
        if( res.code == 'FOLDER_EXISTS') {
          $('#folder-message').fadeIn().html('Folder named <strong>' + res.folder_name + '</strong> already exists.');
        } else {
          $('#file-funnel-1').fadeOut(function() {
            $('#file-funnel-2').fadeIn();
          });
        }
      }
    });
  });

  //Trigger reset on new folder modal being re-shown
  $('#new-folder-modal').on('show.bs.modal', function (e) {
    resetWizard();
    $('#fine-uploader-manual-trigger').fineUploader('cancelAll');
  });

  
  //Clear the form for a new upload
  function resetWizard(){
    $.when(
      $('#file-funnel-2').fadeOut(),
      $('#file-funnel-3').fadeOut()
    ).done(function() {
      $('#file-funnel-1').fadeIn();
    });
    
    $('#wizard-message').hide();
    $('#folder-message').hide();

    $('label.active').removeClass('active');
    $('input[name="new-folder-type"]').prop('checked', false);
    $('#new-folder-name').val(''),
    $('#new-folder-description').val(''),
    $('#new-folder-vendors').prop('selectedIndex',-1);
  }

  function wizardAlert(string) {
    $('#wizard-message').fadeIn();
    $('#wizard-message').append('<div>'+string+'</div>');
  }
});
</script>
<script type="text/template" id="qq-template-manual-trigger">
  <div class="qq-uploader-selector qq-uploader" qq-drop-area-text="Drop files here">
      <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
          <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
      </div>
      <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
          <span class="qq-upload-drop-area-text-selector"></span>
      </div>
      <div class="qq buttons">
          <button class="qq-upload-button-selector btn btn-secondary">
              Select files
          </button>
          <button type="button" id="trigger-upload" class="btn btn-secondary">
            Upload <i class="fa fa-arrow-right"></i></button>
      </div>
      <span class="qq-drop-processing-selector qq-drop-processing">
          <span>Processing dropped files...</span>
          <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
      </span>
      <ul class="qq-upload-list-selector qq-upload-list" aria-live="polite" aria-relevant="additions removals">
          <li>
              <div class="qq-progress-bar-container-selector">
                  <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
              </div>
              <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
              <img class="qq-thumbnail-selector" qq-max-size="100" qq-server-scale>
              <span class="qq-upload-file-selector qq-upload-file"></span>
              <span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
              <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
              <span class="qq-upload-size-selector qq-upload-size"></span>
              <button type="button" class="qq-btn qq-upload-cancel-selector qq-upload-cancel">Cancel</button>
              <button type="button" class="qq-btn qq-upload-retry-selector qq-upload-retry">Retry</button>
              <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">Delete</button>
              <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
          </li>
      </ul>

      <dialog class="qq-alert-dialog-selector">
          <div class="qq-dialog-message-selector"></div>
          <div class="qq-dialog-buttons">
              <button type="button" class="qq-cancel-button-selector">Close</button>
          </div>
      </dialog>

      <dialog class="qq-confirm-dialog-selector">
          <div class="qq-dialog-message-selector"></div>
          <div class="qq-dialog-buttons">
              <button type="button" class="qq-cancel-button-selector">No</button>
              <button type="button" class="qq-ok-button-selector">Yes</button>
          </div>
      </dialog>

      <dialog class="qq-prompt-dialog-selector">
          <div class="qq-dialog-message-selector"></div>
          <input type="text">
          <div class="qq-dialog-buttons">
              <button type="button" class="qq-cancel-button-selector">Cancel</button>
              <button type="button" class="qq-ok-button-selector">Ok</button>
          </div>
      </dialog>
  </div>
</script>
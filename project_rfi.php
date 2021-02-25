
<div class="text-center my-2">
  <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#new-rfi-modal"><i class="fa fa-plus"></i> New RFI</button>
</div>

<table class="table sortbox" id="rfi-list-table">
  <thead>
    <tr>                  
      <th>Submitted</th>
      <th>#</th>
      <th>Subject</th>  
      <th class="group-filter">Group</th>
      <th class="priority-filter">Priority</th>
      
    </tr>
  </thead>
  <tbody id="rfi-list">
  </tbody>
</table>



<div class="modal" tabindex="-1" role="dialog" id="new-rfi-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New RFI</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form id="new-rfi-form">
          <div class="alert alert-warning" id="rfi-wizard-message" style="display: none;"></div>

          <input type="hidden" id="new-rfi-id" value="" />
          <input type="hidden" id="new-rfi-author_id" value="<?=$_SESSION['unique_user_id']?>" />
          <input type="hidden" id="new-rfi-group_id" value="<?=$_SESSION['user_group_id']?>" />

          <input type="hidden" id="new-rfi-store_number" value="<?=intval($project['store_number'])?>" />
      
          <input type="hidden" id="new-rfi-project_id" value="<?=$id?>" />
          <div class="form-group new-rfi-prioritys">
            <div><label>Priority</label></div>
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
              <label class="btn btn-outline-info btn-sm ">
              <input type="radio" name="new-rfi-priority" value="low" autocomplete="off"> Low
              </label>
              <label class="btn btn-outline-info btn-sm">
              <input type="radio" name="new-rfi-priority" value="medium" autocomplete="off"> Medium
              </label>
              <label class="btn btn-outline-info btn-sm">
              <input type="radio" name="new-rfi-priority" value="high" autocomplete="off"> High
              </label>
            </div>
          </div>

          <div class="form-group">
            <label for="new-subject">Subject</label>
            <input type="text" class="form-control" name="new-rfi-subject" id="new-rfi-subject" />          
            <div class="alert alert-warning mt-2" id="subject-message" style="display: none;">
            </div>
          </div>

          <div class="form-group">
            <label for="new-rfi-body">Details</label>
            <textarea class="form-control" name="new-rfi-body" id="new-rfi-body"></textarea>
          </div>

          <div id="fine-uploader-rfi"></div>

          <div class="form group text-center my-2">
            <button type="submit" id="submit-rfi-form" class="btn btn-info">Submit RFI <i class="fa fa-arrow-right"></i></button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="view-rfi-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">RFI Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="rfi-data"></div>
      </div>
    </div>
  </div>
</div>

<form id="get-rfi-file" action="get_download_file.php" method="POST">
  <input type="hidden" name="project_id" id="get-project_id" />
  <input type="hidden" name="rfi_id" id="get-rfi_id" />
  <input type="hidden" name="file" id="get-file" />
</form>


<script type="text/javascript">
  $(document).ready(function() {
    //init table
    /*

    */
    
    $.load_rfi_list = function load_rfi_list() {
      $('#rfi-list').empty();
      $('#group-filter').empty();
      $('#priority-filter').empty();
      $.ajax({
        url: 'rfi-action.php?_=' + new Date().getTime(),
        method: 'POST',
        data: {
          action: 'load_rfi_list',           
          project_id: <?=$id?>
        },
        success:function(data) {
          $('#rfi-list').html(data).promise().done(function(){
            //$('#rfi-list-table').DataTable().reload();
            /*
            var table = $('#rfi-list-table').DataTable({
              destroy: true,
              order: [],
              paging: false,
              columnDefs: [        
                {type: "string"},
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
                this.api().columns('.priority-filter').every( function () {
                  var column = this;
                  var select = $('<select class="form-control"><option value="">All</option></select>')
                    .appendTo( $('#priority-filter') )
                    .on( 'change', function () {
                      var val = $.fn.dataTable.util.escapeRegex( $(this).val() );
                      column.search( val ? '^'+val+'$' : '', true, false ).draw();
                    }); 
                  column.data().unique().sort().each( function ( d, j ){
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                  });
                });
              }//initComplete callback
            });//DataTable function
            */
          });
        }
      });
    }

    $.load_rfi_reply_list = function load_rfi_reply_list() {
      $.ajax({
        url: 'rfi-action.php',
        method: 'POST',
        data: {
          action: 'load_rfi_reply_list',           
          rfi_id: $('#loaded-rfi-data').attr('data-rfi-id')
        },
        success:function(data) {
          $('#rfi-reply-list').html(data);
        }
      });
    }


    $.load_rfi_data = function load_rfi_data(rfi_id) {
      $.ajax({
        url: 'rfi-action.php',
        method: 'POST',
        data: {
          action: 'load_rfi_data',
          rfi_id: rfi_id
        },
        success:function(data) {
          $('#rfi-data').html(data).promise().done(function(){
            $('#view-rfi-modal h5.modal-title').text( $('#loaded-rfi-data').attr('data-rfi-number') );
            $.load_rfi_reply_list();
            $.load_rfi_reply_uploader();
          });
        }
      });
    }

    
    $.load_rfi_reply_uploader = function load_rfi_reply_uploader(){
      $('#fine-uploader-rfi-reply').fineUploader({
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
                  action: 'move_uploaded_rfi_attachment',
                  project_id: $('#loaded-rfi-data').attr('data-project-id'),
                  rfi_id: $('#new-rfi-reply-id').val(),
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
            $.new_rfi_reply_added();
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
    }


    $.new_rfi_added = function new_rfi_added() {
      $.load_rfi_list();
      $.rfi_wizard_alert('New RFI has been added for this project.');
      $('#new-rfi-form').trigger('reset');
      $('#fine-uploader-rfi').fineUploader('clearStoredFiles');
    }

    $.new_rfi_reply_added = function new_rfi_reply_added() {
      $.load_rfi_reply_list();
      $('#new-rfi-reply-form').trigger('reset');
      $('#fine-uploader-rfi-reply').fineUploader('clearStoredFiles');
    }

    $.rfi_wizard_alert = function rfi_wizard_alert(msg) {
      $('#rfi-wizard-message').fadeIn();
      $('#rfi-wizard-message').append('<div>'+msg+'</div>');
    }
    
    $('#content').on('click', '#submit-rfi-form', function(e) {
      e.preventDefault();    
      $('#rfi-wizard-message').text('').hide();
      hasError = false;
      author_id = $('#new-rfi-author_id').val();
      project_id = $('#new-rfi-project_id').val();
      store_number = $('#new-rfi-store_number').val();
      group_id = $('#new-rfi-group_id').val();
      priority = $('input[name="new-rfi-priority"]:checked').val();
      subject = $('#new-rfi-subject').val();    
      body = $('#new-rfi-body').val();    
      
      if ( subject == '' ) {
        $.rfi_wizard_alert('You must enter the subject');
        hasError = true ;
      }

      if ( body == '' ) {
        $.rfi_wizard_alert('You must enter the details');
        hasError = true ;
      }

      if ( typeof(priority) == 'undefined' )  {
        $.rfi_wizard_alert('You must select the priority');
        hasError = true ;
      }

      if(hasError)
        return false;

      $.ajax({
        url: 'rfi-action.php',
        method: 'POST',
        data: {
          action: 'add_rfi',
          author_id: author_id,
          project_id: project_id,
          group_id: group_id,
          priority: priority,
          subject: subject,
          store_number: store_number,
          body: body
        },      
        success:function(res) {        
          if(res.msg == 'RFI_ADDED') {
            $('#new-rfi-id').val( res.insert_id );
            
            var rfi_attachments = $('#fine-uploader-rfi').fineUploader('getUploads', {
              status: qq.status.SUBMITTED
            });
            if(rfi_attachments.length > 0) {
              console.log( 'rfi attchments found and uploading...');
              $('#fine-uploader-rfi').fineUploader('uploadStoredFiles');
            } else {
              $.new_rfi_added();
            }            
          } else { 
            $.rfi_wizard_alert('Error: ' + res.msg);
          }      
        }
      });
    });  

    $('#content').on('click', '#submit-rfi-reply-form', function(e) {
      e.preventDefault();      
      if ( $('#new-rfi-reply').val() == '' ) {
        return false;
      }

      $.ajax({
        url: 'rfi-action.php',
        method: 'POST',
        data: {
          action: 'add_rfi_reply',
          author_id: $('#new-rfi-author_id').val(),
          group_id: $('#new-rfi-group_id').val(),
          project_id: $('#new-rfi-project_id').val(),
          parent_rfi_id: $('#loaded-rfi-data').attr('data-rfi-id'),
          body: $('#new-rfi-reply').val()
        },      
        success:function(res) {        
          if(res.msg == 'RFI_REPLY_ADDED') {
            $('#new-rfi-reply-id').val( res.insert_id );
            
            var rfi_reply_attachments = $('#fine-uploader-rfi-reply').fineUploader('getUploads', {
              status: qq.status.SUBMITTED
            });
            if(rfi_reply_attachments.length > 0) {
              $('#fine-uploader-rfi-reply').fineUploader('uploadStoredFiles');
            } else {
              $.new_rfi_reply_added();
            }            
          }   
        }
      });
    }); 

    
    //Bind upload for RFI
    $('#fine-uploader-rfi').fineUploader({
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
                action: 'move_uploaded_rfi_attachment',
                project_id: $('#new-rfi-project_id').val(),
                rfi_id: $('#new-rfi-id').val(),
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
          $.new_rfi_added();
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







  //Get individual file download sent to the browser by posting form
  $('#content').on('click', 'a.rfi-file', function(e) {
    e.preventDefault();
    $('input#get-project_id').val( $(this).attr('data-project-id') );
    $('input#get-rfi_id').val( $(this).attr('data-rfi-id') );
    $('input#get-file').val( $(this).attr('data-file') );
    $('#get-rfi-file').submit();
  });



    //bind clicks on rfi and do something
    $('#rfi-list-table tbody').on('click', 'tr', function () {
      var rfi_id = $(this).attr('data-rfi-id');
      $.load_rfi_data(rfi_id);
      $('#view-rfi-modal').modal('show');
    });
    
    //bind search
    $('#rfi-search-text').on( 'keyup', function (e) {
      e.preventDefault();
      table.search($('#rfi-search-text').val()).draw();
    });

    //default loading
    $.load_rfi_list();

  });
</script>
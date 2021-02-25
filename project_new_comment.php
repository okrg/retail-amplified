<div class="modal" tabindex="-1" role="dialog" id="new-comment-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Comment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning" id="comment-error-message" style="display: none;"></div>

        <form onsubmit="return comments.add(this)" class="creplyform">  
          <input type="hidden" id="comment-project_id" value="<?=$id;?>"/>
          <input type="hidden" id="comment-author_id" value="<?=$_SESSION['unique_user_id'];?>" />

          <div class="form-group new-folder-types">
            <div><label>Comment Type</label></div>
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
              <label class="btn btn-outline-info btn-sm ">
              <input type="radio" name="comment-type" value="real_estate" autocomplete="off">Real Estate
              </label>
              <label class="btn btn-outline-info btn-sm">
              <input type="radio" name="comment-type" value="design" autocomplete="off">Design
              </label>
              <label class="btn btn-outline-info btn-sm">
              <input type="radio" name="comment-type" value="construction" autocomplete="off">Construction
              </label>
            </div>
          </div>

          <div class="form-group">
            <textarea id="comment-message" placeholder="Enter comment..."></textarea>
          </div>
          <div class="form group text-center">
          <button type="submit" class="btn btn-info">Submit Comment <i class="fa fa-arrow-right"></i></button>
          </div>
        </form>

      </div>

    </div>
  </div>
</div>
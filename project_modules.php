<div class="modal" tabindex="-1" role="dialog" id="modules-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modules</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form  class="creplyform" id="modules-form">
<input type="hidden" id="modules-project_id" value="<?=$id;?>"/>
<div class="form-check">
  <input class="form-check-input" <?php if($project_modules->store_attributes): ?>checked<?php endif; ?> type="checkbox" value="" id="check0">
  <label class="form-check-label" for="check0">
    Store Attributes
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" <?php if($project_modules->construction_schedule): ?>checked<?php endif; ?> type="checkbox" value="" id="check1">
  <label class="form-check-label" for="check1">
    Construction Schedule
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" <?php if($project_modules->tenant_coordination): ?>checked<?php endif; ?> type="checkbox" value="" id="check2">
  <label class="form-check-label" for="check2">
    Tenant Coordination
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" <?php if($project_modules->real_estate_data): ?>checked<?php endif; ?> type="checkbox" value="" id="check3">
  <label class="form-check-label" for="check3">
    Real Estate Data
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" <?php if($project_modules->comments): ?>checked<?php endif; ?> type="checkbox" value="" id="check4">
  <label class="form-check-label" for="check4">
    Comments
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" <?php if($project_modules->files): ?>checked<?php endif; ?> type="checkbox" value="" id="check5">
  <label class="form-check-label" for="check5">
    Files
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" <?php if($project_modules->photos): ?>checked<?php endif; ?> type="checkbox" value="" id="check6">
  <label class="form-check-label" for="check6">
    Photos
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" <?php if($project_modules->rfi): ?>checked<?php endif; ?> type="checkbox" value="" id="check7">
  <label class="form-check-label" for="check7">
    RFI
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" <?php if($project_modules->change_orders): ?>checked<?php endif; ?> type="checkbox" value="" id="check8">
  <label class="form-check-label" for="check8">
    Change Orders
  </label>
</div>
          <div class="form group text-center">
          <button class="btn btn-info" id="submit-project-modules">Save</button>
          </div>
        </form>

      </div>

    </div>
  </div>
</div>



<script type="text/javascript">
$(document).ready(function(){  

  $('#submit-project-modules').click(function(e) {
    e.preventDefault();

    project_id = $('#modules-project_id').val();

    var project_modules = new Object();
    project_modules.store_attributes = $('#check0:checkbox:checked').length > 0;
    project_modules.construction_schedule = $('#check1:checkbox:checked').length > 0;
    project_modules.tenant_coordination = $('#check2:checkbox:checked').length > 0;
    project_modules.real_estate_data = $('#check3:checkbox:checked').length > 0;
    project_modules.comments = $('#check4:checkbox:checked').length > 0;
    project_modules.files = $('#check5:checkbox:checked').length > 0;
    project_modules.photos = $('#check6:checkbox:checked').length > 0;
    project_modules.rfi = $('#check7:checkbox:checked').length > 0;
    project_modules.change_orders = $('#check8:checkbox:checked').length > 0;

    
    $.ajax({
      url: 'admin-project-modules.php',
      method: 'POST',
      data: {
        action: 'edit',
        project_id: project_id,
        project_modules: JSON.stringify(project_modules)
      },
      success:function(res) {
        if(res.msg == 'MODULES_UPDATED') {
          location.reload();
        }
      }
    });
    
  });
  
});
</script>
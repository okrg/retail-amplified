<?php 
  $query = "SELECT * FROM tracker WHERE id = ".mysqli_real_escape_string($dbcnx, $id);
  $result = mysqli_query($dbcnx, $query) or die ("no query x1");
  $data = array();
  while($row = mysqli_fetch_assoc($result)) {
      $data[] = $row;
  }

  $tracker = $data[0];

if(!empty($tracker['lease_tt_deal_approved_loi_note'])) {
  $note_summary .= '<em>TT Deal Approved/LOI</em>'.$tracker['lease_tt_deal_approved_loi_note'].'</p>';
}
if(!empty($tracker['ll_scope_complete_note'])) {
$note_summary .= '<em>Scope Complete</em>'.$tracker['ll_scope_complete_note'].'</p>';
}
if(!empty($tracker['lease_ll_deal_approval_note'])) {
$note_summary .= '<em>LL Deal Approval</em>'.$tracker['lease_ll_deal_approval_note'].'</p>';
}
if(!empty($tracker['ll_scope_approval_note'])) {
$note_summary .= '<em>Scope Approved</em>'.$tracker['ll_scope_approval_note'].'</p>';
}
if(!empty($tracker['tt_criteria_manual_sent_note'])) {
$note_summary .= '<em>Criteria Sent</em>'.$tracker['tt_criteria_manual_sent_note'].'</p>';
}
if(!empty($tracker['lease_first_draft_sent_note'])) {
$note_summary .= '<em>First Draft Lease</em>'.$tracker['lease_first_draft_sent_note'].'</p>';
}
if(!empty($tracker['tt_project_kickoff_note'])) {
$note_summary .= '<em>Project Kickoff</em>'.$tracker['tt_project_kickoff_note'].'</p>';
}
if(!empty($tracker['lease_tenant_signed_lease_note'])) {
$note_summary .= '<em>TT Lease Signed</em>'.$tracker['lease_tenant_signed_lease_note'].'</p>';
}
if(!empty($tracker['lease_ll_signed_lease_note'])) {
$note_summary .= '<em>LL Lease Signed</em>'.$tracker['lease_ll_signed_lease_note'].'</p>';
}
if(!empty($tracker['ll_cd_start_note'])) {
$note_summary .= '<em>CD Start</em>'.$tracker['ll_cd_start_note'].'</p>';
}
if(!empty($tracker['tt_preliminary_drawings_complete_note'])) {
$note_summary .= '<em>Preliminary Drawings Complete</em>'.$tracker['tt_preliminary_drawings_complete_note'].'</p>';
}
if(!empty($tracker['ll_cd_approved_note'])) {
$note_summary .= '<em>CD Approved</em>'.$tracker['ll_cd_approved_note'].'</p>';
}
if(!empty($tracker['tt_prelminary_drawings_approved_note'])) {
$note_summary .= '<em>Prelminary Drawings Approved</em>'.$tracker['tt_prelminary_drawings_approved_note'].'</p>';
}
if(!empty($tracker['ll_permit_submitted_note'])) {
$note_summary .= '<em>Permit Submitted</em>'.$tracker['ll_permit_submitted_note'].'</p>';
}
if(!empty($tracker['tt_cd_complete_note'])) {
$note_summary .= '<em>CD Complete</em>'.$tracker['tt_cd_complete_note'].'</p>';
}
if(!empty($tracker['ll_permit_approved_note'])) {
$note_summary .= '<em>Permit Approved</em>'.$tracker['ll_permit_approved_note'].'</p>';
}
if(!empty($tracker['tt_cd_approved_note'])) {
$note_summary .= '<em>CD Approved</em>'.$tracker['tt_cd_approved_note'].'</p>';
}
if(!empty($tracker['ll_construction_start_note'])) {
$note_summary .= '<em>Construction Start</em>'.$tracker['ll_construction_start_note'].'</p>';
}
if(!empty($tracker['tt_permit_submitted_note'])) {
$note_summary .= '<em>Permit Submit</em>'.$tracker['tt_permit_submitted_note'].'</p>';
}
if(!empty($tracker['ll_construction_complete_note'])) {
$note_summary .= '<em>Construction Complete</em>'.$tracker['ll_construction_complete_note'].'</p>';
}
if(!empty($tracker['tt_permit_approved_note'])) {
$note_summary .= '<em>Permit Approved</em>'.$tracker['tt_permit_approved_note'].'</p>';
}
if(!empty($tracker['lease_space_delivery_letter_note'])) {
$note_summary .= '<em>Delivery Letter</em>'.$tracker['lease_space_delivery_letter_note'].'</p>';
}
if(!empty($tracker['ll_space_delivery_note'])) {
$note_summary .= '<em>Space Delivery</em>'.$tracker['ll_space_delivery_note'].'</p>';
}
if(!empty($tracker['tt_construction_start_note'])) {
$note_summary .= '<em>Construction Start</em>'.$tracker['tt_construction_start_note'].'</p>';
}
if(!empty($tracker['tt_construction_complete_note'])) {
$note_summary .= '<em>Construction Complete</em>'.$tracker['tt_construction_complete_note'].'</p>';
}
if(!empty($tracker['lease_rental_commencement_note'])) {
$note_summary .= '<em>Rent Commence</em>'.$tracker['lease_rental_commencement_note'].'</p>';
}
if(!empty($tracker['tt_space_open_note'])) {
$note_summary .= '<em>Space Open</em>'.$tracker['tt_space_open_note'].'</p>';
}

  function hasTrackerNote($field) {
    global $tracker;
    return empty($tracker[$field . '_note']) ? 'empty' : 'hascomment';
  }
?>

<script>

$(document).ready(function() {
  $('.label i.fa-comment').click(function(){
    $(this).next('.comment').toggleClass('hidden shown');
  });

  $('#note-summary-button').click(function(){
    $(this).parent('.note-summary').toggleClass('hidden shown');
  });

});
</script>

<div id="tracker-schedule-form" class="p-2">

  <div class="row mb-4">
    <div class="col">
      <div class="note-summary hidden">
      <a id="note-summary-button" class="btn btn-sm btn-secondary">Note Summary</a>
        <div class="notes">
          <?php print $note_summary; ?>
        </div>
      </div>
    </div>
  </div>

<table class="table">
<tr>
  <th class="tracker-column">Lease</th>
  <th class="tracker-column">LL Work</th>
  <th class="tracker-column">TT Work</th>
</tr>

<tr>
  <td>
    <div class="label">TT Deal Approved/LOI
      <i class="fa fa-comment <?=hasTrackerNote('lease_tt_deal_approved_loi')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="lease_tt_deal_approved_loi_note" data-type="textarea"><?=$tracker['lease_tt_deal_approved_loi_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="lease_tt_deal_approved_loi_scheduled" data-type="date" data-value="<?=dateFormat($tracker['lease_tt_deal_approved_loi_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="lease_tt_deal_approved_loi_actual" data-type="date" data-value="<?=dateFormat($tracker['lease_tt_deal_approved_loi_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">Scope Complete
      <i class="fa fa-comment <?=hasTrackerNote('ll_scope_complete')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="ll_scope_complete_note" data-type="textarea"><?=$tracker['ll_scope_complete_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="ll_scope_complete_scheduled" data-type="date" data-value="<?=dateFormat($tracker['ll_scope_complete_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="ll_scope_complete_actual" data-type="date" data-value="<?=dateFormat($tracker['ll_scope_complete_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    &nbsp;
  </td>
</tr>

<tr>
  <td>
    <div class="label">LL Deal Approval
      <i class="fa fa-comment <?=hasTrackerNote('lease_ll_deal_approval')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="lease_ll_deal_approval_note" data-type="textarea"><?=$tracker['lease_ll_deal_approval_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="lease_ll_deal_approval_scheduled" data-type="date" data-value="<?=dateFormat($tracker['lease_ll_deal_approval_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="lease_ll_deal_approval_actual" data-type="date" data-value="<?=dateFormat($tracker['lease_ll_deal_approval_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">Scope Approved
      <i class="fa fa-comment <?=hasTrackerNote('ll_scope_approval')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="ll_scope_approval_note" data-type="textarea"><?=$tracker['ll_scope_approval_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="ll_scope_approval_scheduled" data-type="date" data-value="<?=dateFormat($tracker['ll_scope_approval_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="ll_scope_approval_actual" data-type="date" data-value="<?=dateFormat($tracker['ll_scope_approval_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">Criteria Sent
      <i class="fa fa-comment <?=hasTrackerNote('tt_criteria_manual_sent')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="tt_criteria_manual_sent_note" data-type="textarea"><?=$tracker['tt_criteria_manual_sent_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="tt_criteria_manual_sent_scheduled" data-type="date" data-value="<?=dateFormat($tracker['tt_criteria_manual_sent_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="tt_criteria_manual_sent_actual" data-type="date" data-value="<?=dateFormat($tracker['tt_criteria_manual_sent_actual'])?>"></a>
      </div>
    </div>
  </td>
</tr>

<tr>
  <td>
    <div class="label">First Draft Lease
      <i class="fa fa-comment <?=hasTrackerNote('lease_first_draft_sent')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="lease_first_draft_sent_note" data-type="textarea"><?=$tracker['lease_first_draft_sent_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="lease_first_draft_sent_scheduled" data-type="date" data-value="<?=dateFormat($tracker['lease_first_draft_sent_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="lease_first_draft_sent_actual" data-type="date" data-value="<?=dateFormat($tracker['lease_first_draft_sent_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    &nbsp;
  </td>
  <td>
    <div class="label">Project Kickoff
      <i class="fa fa-comment <?=hasTrackerNote('tt_project_kickoff')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="tt_project_kickoff_note" data-type="textarea"><?=$tracker['tt_project_kickoff_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="tt_project_kickoff_scheduled" data-type="date" data-value="<?=dateFormat($tracker['tt_project_kickoff_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="tt_project_kickoff_actual" data-type="date" data-value="<?=dateFormat($tracker['tt_project_kickoff_actual'])?>"></a>
      </div>
    </div>
  </td>
</tr>

<tr>
  <td>
    <div class="label">TT Lease Signed
      <i class="fa fa-comment <?=hasTrackerNote('lease_tenant_signed_lease')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="lease_tenant_signed_lease_note" data-type="textarea"><?=$tracker['lease_tenant_signed_lease_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="lease_tenant_signed_lease_scheduled" data-type="date" data-value="<?=dateFormat($tracker['lease_tenant_signed_lease_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="lease_tenant_signed_lease_actual" data-type="date" data-value="<?=dateFormat($tracker['lease_tenant_signed_lease_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
  &nbsp;
  </td>
  <td>
  &nbsp;
  </td>
</tr>

<tr>
  <td>
    <div class="label">LL Lease Signed
      <i class="fa fa-comment <?=hasTrackerNote('lease_ll_signed_lease')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="lease_ll_signed_lease_note" data-type="textarea"><?=$tracker['lease_ll_signed_lease_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="lease_ll_signed_lease_scheduled" data-type="date" data-value="<?=dateFormat($tracker['lease_ll_signed_lease_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="lease_ll_signed_lease_actual" data-type="date" data-value="<?=dateFormat($tracker['lease_ll_signed_lease_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">CD Start
      <i class="fa fa-comment <?=hasTrackerNote('ll_cd_start')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="ll_cd_start_note" data-type="textarea"><?=$tracker['ll_cd_start_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="ll_cd_start_scheduled" data-type="date" data-value="<?=dateFormat($tracker['ll_cd_start_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="ll_cd_start_actual" data-type="date" data-value="<?=dateFormat($tracker['ll_cd_start_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">Preliminary Drawings Complete
      <i class="fa fa-comment <?=hasTrackerNote('tt_preliminary_drawings_complete')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="tt_preliminary_drawings_complete_note" data-type="textarea"><?=$tracker['tt_preliminary_drawings_complete_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="tt_preliminary_drawings_complete_scheduled" data-type="date" data-value="<?=dateFormat($tracker['tt_preliminary_drawings_complete_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="tt_preliminary_drawings_complete_actual" data-type="date" data-value="<?=dateFormat($tracker['tt_preliminary_drawings_complete_actual'])?>"></a>
      </div>
    </div>
  </td>
</tr>

<tr>
  <td>
    &nbsp;
  </td>
  <td>
    <div class="label">CD Approved
      <i class="fa fa-comment <?=hasTrackerNote('ll_cd_approved')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="ll_cd_approved_note" data-type="textarea"><?=$tracker['ll_cd_approved_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="ll_cd_approved_scheduled" data-type="date" data-value="<?=dateFormat($tracker['ll_cd_approved_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="ll_cd_approved_actual" data-type="date" data-value="<?=dateFormat($tracker['ll_cd_approved_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">Prelminary Drawings Approved
      <i class="fa fa-comment <?=hasTrackerNote('tt_prelminary_drawings_approved')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="tt_prelminary_drawings_approved_note" data-type="textarea"><?=$tracker['tt_prelminary_drawings_approved_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="tt_prelminary_drawings_approved_scheduled" data-type="date" data-value="<?=dateFormat($tracker['tt_prelminary_drawings_approved_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="tt_prelminary_drawings_approved_actual" data-type="date" data-value="<?=dateFormat($tracker['tt_prelminary_drawings_approved_actual'])?>"></a>
      </div>
    </div>
  </td>
</tr>

<tr>
  <td>
    &nbsp;
  </td>
  <td>
    <div class="label">Permit Submitted
      <i class="fa fa-comment <?=hasTrackerNote('ll_permit_submitted')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="ll_permit_submitted_note" data-type="textarea"><?=$tracker['ll_permit_submitted_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="ll_permit_submitted_scheduled" data-type="date" data-value="<?=dateFormat($tracker['ll_permit_submitted_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="ll_permit_submitted_actual" data-type="date" data-value="<?=dateFormat($tracker['ll_permit_submitted_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">CD Complete
      <i class="fa fa-comment <?=hasTrackerNote('tt_cd_complete')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="tt_cd_complete_note" data-type="textarea"><?=$tracker['tt_cd_complete_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="tt_cd_complete_scheduled" data-type="date" data-value="<?=dateFormat($tracker['tt_cd_complete_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="tt_cd_complete_actual" data-type="date" data-value="<?=dateFormat($tracker['tt_cd_complete_actual'])?>"></a>
      </div>
    </div>
  </td>
</tr>

<tr>
  <td>
    &nbsp;
  </td>
  <td>
    <div class="label">Permit Approved
      <i class="fa fa-comment <?=hasTrackerNote('ll_permit_approved')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="ll_permit_approved_note" data-type="textarea"><?=$tracker['ll_permit_approved_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="ll_permit_approved_scheduled" data-type="date" data-value="<?=dateFormat($tracker['ll_permit_approved_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="ll_permit_approved_actual" data-type="date" data-value="<?=dateFormat($tracker['ll_permit_approved_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">CD Approved
      <i class="fa fa-comment <?=hasTrackerNote('tt_cd_approved')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="tt_cd_approved_note" data-type="textarea"><?=$tracker['tt_cd_approved_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="tt_cd_approved_scheduled" data-type="date" data-value="<?=dateFormat($tracker['tt_cd_approved_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="tt_cd_approved_actual" data-type="date" data-value="<?=dateFormat($tracker['tt_cd_approved_actual'])?>"></a>
      </div>
    </div>
  </td>
</tr>

<tr>
  <td>
    &nbsp;
  </td>
  <td>
    <div class="label">Construction Start
      <i class="fa fa-comment <?=hasTrackerNote('ll_construction_start')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="ll_construction_start_note" data-type="textarea"><?=$tracker['ll_construction_start_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="ll_construction_start_scheduled" data-type="date" data-value="<?=dateFormat($tracker['ll_construction_start_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="ll_construction_start_actual" data-type="date" data-value="<?=dateFormat($tracker['ll_construction_start_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">Permit Submit
      <i class="fa fa-comment <?=hasTrackerNote('tt_permit_submitted')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="tt_permit_submitted_note" data-type="textarea"><?=$tracker['tt_permit_submitted_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="tt_permit_submitted_scheduled" data-type="date" data-value="<?=dateFormat($tracker['tt_permit_submitted_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="tt_permit_submitted_actual" data-type="date" data-value="<?=dateFormat($tracker['tt_permit_submitted_actual'])?>"></a>
      </div>
    </div>
  </td>
</tr>

<tr>
  <td>
    &nbsp;
  </td>
  <td>
    <div class="label">Construction Complete
      <i class="fa fa-comment <?=hasTrackerNote('ll_construction_complete')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="ll_construction_complete_note" data-type="textarea"><?=$tracker['ll_construction_complete_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="ll_construction_complete_scheduled" data-type="date" data-value="<?=dateFormat($tracker['ll_construction_complete_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="ll_construction_complete_actual" data-type="date" data-value="<?=dateFormat($tracker['ll_construction_complete_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">Permit Approved
      <i class="fa fa-comment <?=hasTrackerNote('tt_permit_approved')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="tt_permit_approved_note" data-type="textarea"><?=$tracker['tt_permit_approved_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="tt_permit_approved_scheduled" data-type="date" data-value="<?=dateFormat($tracker['tt_permit_approved_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="tt_permit_approved_actual" data-type="date" data-value="<?=dateFormat($tracker['tt_permit_approved_actual'])?>"></a>
      </div>
    </div>
  </td>
</tr>

<tr>
  <td>
    <div class="label">Delivery Letter
      <i class="fa fa-comment <?=hasTrackerNote('lease_space_delivery_letter')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="lease_space_delivery_letter_note" data-type="textarea"><?=$tracker['lease_space_delivery_letter_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="lease_space_delivery_letter_scheduled" data-type="date" data-value="<?=dateFormat($tracker['lease_space_delivery_letter_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="lease_space_delivery_letter_actual" data-type="date" data-value="<?=dateFormat($tracker['lease_space_delivery_letter_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">Space Delivery
      <i class="fa fa-comment <?=hasTrackerNote('ll_space_delivery')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="ll_space_delivery_note" data-type="textarea"><?=$tracker['ll_space_delivery_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="ll_space_delivery_scheduled" data-type="date" data-value="<?=dateFormat($tracker['ll_space_delivery_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="ll_space_delivery_actual" data-type="date" data-value="<?=dateFormat($tracker['ll_space_delivery_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">Construction Start
      <i class="fa fa-comment <?=hasTrackerNote('tt_construction_start')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="tt_construction_start_note" data-type="textarea"><?=$tracker['tt_construction_start_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="tt_construction_start_scheduled" data-type="date" data-value="<?=dateFormat($tracker['tt_construction_start_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="tt_construction_start_actual" data-type="date" data-value="<?=dateFormat($tracker['tt_construction_start_actual'])?>"></a>
      </div>
    </div>
  </td>
</tr>

<tr>
  <td>
    &nbsp;
  </td>
  <td>
    &nbsp;
  </td>
  <td>
    <div class="label">Construction Complete
      <i class="fa fa-comment <?=hasTrackerNote('tt_construction_complete')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="tt_construction_complete_note" data-type="textarea"><?=$tracker['tt_construction_complete_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="tt_construction_complete_scheduled" data-type="date" data-value="<?=dateFormat($tracker['tt_construction_complete_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="tt_construction_complete_actual" data-type="date" data-value="<?=dateFormat($tracker['tt_construction_complete_actual'])?>"></a>
      </div>
    </div>
  </td>
</tr>

<tr>
  <td>
    <div class="label">Rent Commence
      <i class="fa fa-comment <?=hasTrackerNote('lease_rental_commencement')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="lease_rental_commencement_note" data-type="textarea"><?=$tracker['lease_rental_commencement_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="lease_rental_commencement_scheduled" data-type="date" data-value="<?=dateFormat($tracker['lease_rental_commencement_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="lease_rental_commencement_actual" data-type="date" data-value="<?=dateFormat($tracker['lease_rental_commencement_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    &nbsp;
  </td>
  <td>
    <div class="label">Space Open
      <i class="fa fa-comment <?=hasTrackerNote('tt_space_open')?>"></i>
      <div class="comment hidden">
        <a href="#" class="edit" data-table="tracker" data-name="tt_space_open_note" data-type="textarea"><?=$tracker['tt_space_open_note'];?></a>
      </div>
    </div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-table="tracker" data-name="tt_space_open_scheduled" data-type="date" data-value="<?=dateFormat($tracker['tt_space_open_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-table="tracker" data-name="tt_space_open_actual" data-type="date" data-value="<?=dateFormat($tracker['tt_space_open_actual'])?>"></a>
      </div>
    </div>
  </td>
</tr>
</table>

    <div class="col"><strong>Misc Schedule Notes</strong></div>
    <div class="col">
      <a href="#" data-type="textarea" data-table="projects" data-name="schedule_notes" class="edit arch" data-value="<?=stripslashes($tracker['schedule_notes'])?>"></a>
    </div>

</div>






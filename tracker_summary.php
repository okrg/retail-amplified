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


});
</script>

<div id="tracker-summary-form" class="p-2">

  <div class="row mb-4">
    <div class="col">
      TC Stage
      <div><a href="#" data-type="select" data-table="projects" data-name="stage" class="edit" data-source="['TT deal approval','LL deal approval','Lease comments','TT signed lease','LL signed lease','LLW construction','Space Delivery','TT construction','TT stock and train','TT open']" data-value="<?=$project['stage']?>"></a></div>
    </div>
    <div class="col">
      TC Risk
      <div><a href="#" data-type="select" data-table="projects" data-name="risk" class="edit" data-source="['On schedule','LL Lease delay','TT Lease delay','LL Work delay','TT Work delay','TT product delay','City delay','Amendment pending']" data-value="<?=$project['risk']?>"></a></div>
    </div>
  </div>

</div>
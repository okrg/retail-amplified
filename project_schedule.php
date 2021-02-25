<?php //project_schedule.php
	
	$query = "SELECT * FROM scheduled_dates WHERE project_id = ".mysqli_real_escape_string($dbcnx, $id);
	$result = mysqli_query($dbcnx, $query) or die ("no query x1");	
	$data = array();
	while($row = mysqli_fetch_assoc($result)) {
	    $data[] = $row;
	}

	$scheduled = $data[0];		
	
	$query = "SELECT * FROM actual_dates WHERE project_id = ".mysqli_real_escape_string($dbcnx, $id);
	$result = mysqli_query($dbcnx, $query) or die ("no query x2");	
	$data = array();
	
	while($row = mysqli_fetch_assoc($result)) {
	    $data[] = $row;
	}
	$actual = $data[0];
	
	
	if ($project['chain']==1){$sitechain="Charlotte Russe";}else{$sitechain = "Rampage";}
?>

<div id="project-schedule-form">

	<div class="row header-row">
		<div class="col">Real Estate</div>
		<div class="col">Scheduled Dates</div>
		<div class="col">Actual Dates</div>
	</div>

	<div class="row">
		<div class="col">LOI</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="loi" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($scheduled['loi'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="loi" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($actual['loi'])?>"></a>
		</div>
	</div>
	
	<div class="row">
		<div class="col">LOD Received</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="lod_received" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($scheduled['lod_received'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="lod_received" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($actual['lod_received'])?>"></a>
		</div>
	</div>
	
	<div class="row">
		<div class="col">Tenant Criteria Received</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="tenant_criteria_received" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($scheduled['tenant_criteria_received'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="tenant_criteria_received" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($actual['tenant_criteria_received'])?>"></a>
		</div>
	</div>
	
	<div class="row">
		<div class="col">Design Exhibit Received</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="design_exhibit_received" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($scheduled['design_exhibit_received'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="design_exhibit_received" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($actual['design_exhibit_received'])?>"></a>
		</div>
	</div>
	
	<div class="row">
		<div class="col">Construction Exhibit Received</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="construction_exhibit_rece href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($scheduled['construction_exhibit_received'])?>"></a></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="construction_exhibit_received" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($actual['construction_exhibit_received'])?>"></a>
		</div>
	</div>
	
	<div class="row">
		<div class="col">REC Approval</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="rec_approval" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($scheduled['rec_approval'])?>"></a>
		</div>
		<div class="col"></div>
	</div>
	
	<div class="row">
		<div class="col">Draft Lease Received</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="draft_lease_received" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($scheduled['draft_lease_received'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="draft_lease_received" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($actual['draft_lease_received'])?>"></a>
		</div>
	</div>

	  <div class="row">
    <div class="col">Ready for Signature?</div>
    <div class="col">
      <a href="#" data-type="select" data-table="realestate" data-name="ready_for_signature" class="edit" data-source="[{value:'1',text:'Yes'},{value:'0',text:'No'},{value:'',text:''}]" data-value="<?=$realestate['ready_for_signature']?>"></a>
    </div>
    <div class="col"></div>
  </div>
	
	<div class="row">
		<div class="col">CR Signed Lease</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="cr_signed_lease" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($scheduled['cr_signed_lease'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="cr_signed_lease" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($actual['cr_signed_lease'])?>"></a>
		</div>
	</div>
	
	<div class="row">
		<div class="col">Fully Executed Lease</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="lease_executed" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($scheduled['lease_executed'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="lease_executed" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($actual['lease_executed'])?>"></a>
		</div>
	</div>
	
	<div class="row header-row">
		<div class="col">Design</div>
		<div class="col">Scheduled Dates</div>
		<div class="col">Actual Dates</div>	
	</div>

	<div class="row">
		<div class="col">Survey Uploaded</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="survey_uploaded" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($scheduled['survey_uploaded'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="survey_uploaded" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($actual['survey_uploaded'])?>"></a>
		</div>
	</div>

	<div class="row">
		<div class="col">Preliminary Set to CR</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="preliminary_set_to_cr" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($scheduled['preliminary_set_to_cr'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="preliminary_set_to_cr" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($actual['preliminary_set_to_cr'])?>"></a>
		</div>
	</div>
	
	<div class="row">
		<div class="col">CR Preliminary Set Approval</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="cr_preliminary_set_approval" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($scheduled['cr_preliminary_set_approval'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="cr_preliminary_set_approval" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($actual['cr_preliminary_set_approval'])?>"></a>
		</div>
	</div>

	<div class="row">
		<div class="col">Check Set to CR</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="check_set_to_cr" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($scheduled['check_set_to_cr'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="check_set_to_cr" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($actual['check_set_to_cr'])?>"></a>
		</div>
	</div>
	
	<div class="row">
		<div class="col">Check Set Approval by Design</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="cr_check_set_approval_design" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($scheduled['cr_check_set_approval_design'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="cr_check_set_approval_design" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($actual['cr_check_set_approval_design'])?>"></a>
		</div>
	</div>
	
	<div class="row">
		<div class="col">Check Set Approval by Construction</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="cr_check_set_approval_construction" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($scheduled['cr_check_set_approval_construction'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="cr_check_set_approval_construction" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($actual['cr_check_set_approval_construction'])?>"></a>			
		</div>
	</div>
	
	<div class="row">
		<div class="col">Construction Drawings Uploaded</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="construction_drawings_uploaded" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($scheduled['construction_drawings_uploaded'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="construction_drawings_uploaded" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($actual['construction_drawings_uploaded'])?>"></a>
		</div>
	</div>

	<div class="row">
		<div class="col">CDs to Landlord for Approval</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="cds_to_landlord_for_approval" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($scheduled['cds_to_landlord_for_approval'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="cds_to_landlord_for_approval" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($actual['cds_to_landlord_for_approval'])?>"></a>
		</div>
	</div>

	<div class="row">
		<div class="col">Landlord Approval Required for Permit	</div>
		<div class="col">
			<a href="#" data-type="select" data-table="projects" data-name="landlord_approval_required_for_permit" class="edit" data-source="[{value:'1',text:'Yes'},{value:'0',text:'No'},{value:'',text:''}]" data-value="<?=$project['landlord_approval_required_for_permit']?>"></a>
		</div>
		<div class="col"></div>
	</div>

	<div class="row">
		<div class="col">Landlord Approval</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="landlord_approval" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($scheduled['landlord_approval'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="landlord_approval" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($actual['landlord_approval'])?>"></a>
		</div>
	</div>
	
	<div class="row">
		<div class="col">Permit Duration Estimate</div>
		<div class="col">
			<a href="#" data-type="textarea" data-table="projects" data-name="permit_duration" class="edit" data-value="<?=stripslashes($project['permit_duration'])?>"></a>
		</div>
		<div class="col"></div>
	</div>

	<div class="row">
		<div class="col">Submit for Permit</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="submit_for_permit" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($scheduled['submit_for_permit'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="submit_for_permit" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($actual['submit_for_permit'])?>"></a>
		</div>
	</div>

	<div class="row">
		<div class="col">City Comments Received</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="city_comments_received" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($scheduled['city_comments_received'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="city_comments_received" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($actual['city_comments_received'])?>"></a>
		</div>
	</div>

	<div class="row">
		<div class="col">CDs Revised and Resubmitted</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="cds_revised_and_resubmitted" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($scheduled['cds_revised_and_resubmitted'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="cds_revised_and_resubmitted" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($actual['cds_revised_and_resubmitted'])?>"></a>
		</div>
	</div>

	<div class="row">
		<div class="col">Permit Received</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="permit_received" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($scheduled['permit_received'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="permit_received" href="#" data-type="date" class="edit-date arch" data-value="<?=dateFormat($actual['permit_received'])?>"></a>
		</div>
	</div>

	<div class="row header-row">
		<div class="col">Construction</div>
		<div class="col">Scheduled Dates</div>
		<div class="col">Actual Dates</div>	
	</div>		
	
	<div class="row">
		<div class="col">GC/Vendor Bid Sets Issued</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="gc_vendor_bid_sets_issued" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($scheduled['gc_vendor_bid_sets_issued'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="gc_vendor_bid_sets_issued" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($actual['gc_vendor_bid_sets_issued'])?>"></a>
		</div>
	</div>
	
	<div class="row">
		<div class="col">Bids Due</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="bids_due" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($scheduled['bids_due'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="bids_due" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($actual['bids_due'])?>"></a>
		</div>
	</div>
	
	<div class="row">
		<div class="col">GCs Qualifications Sent</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="gcs_qualifications_sent" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($scheduled['gcs_qualifications_sent'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="gcs_qualifications_sent" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($actual['gcs_qualifications_sent'])?>"></a>
		</div>
	</div>

	<div class="row">
		<div class="col">GCs Qualification Responses Due</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="gcs_qualification_responses_due" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($scheduled['gcs_qualification_responses_due'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="gcs_qualification_responses_due" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($actual['gcs_qualification_responses_due'])?>"></a>
		</div>
	</div>
	
	<div class="row">
		<div class="col">GC Awarded</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="gc_awarded" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($scheduled['gc_awarded'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="gc_awarded" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($actual['gc_awarded'])?>"></a>
		</div>
	</div>
	
	<div class="row">
		<div class="col">Possession Date</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="possesion_date" href="#" data-type="date" class="edit-date allowVacant" data-value="<?=dateFormat($scheduled['possesion_date'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="possesion_date" href="#" data-type="date" class="edit-date allowVacant" data-value="<?=dateFormat($actual['possesion_date'])?>"></a>
		</div>
	</div>

	<div class="row">
		<div class="col">Start Construction</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="start_construction" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($scheduled['start_construction'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="start_construction" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($actual['start_construction'])?>"></a>
		</div>
	</div>
	
	<div class="row">
		<div class="col">Duration</div>
		<div class="col">
			<a href="#" data-type="textarea" data-table="projects" data-name="duration" class="edit" data-value="<?=stripslashes($project['duration'])?>"></a>
		</div>
		<div class="col"></div>
	</div>

	<div class="row">
		<div class="col">Delta #1 Revision Issued</div>
		<div class="col">
			<a href="#" data-table="projects" data-name="delta_1_revisions_issued" data-type="date" class="edit-date arch" data-value="<?=dateFormat($project['delta_1_revisions_issued'])?>"></a>
		</div>
		<div class="col"></div>
	</div>	

	<?php if($project['delta_1_revisions_issued'] != '0000-00-00'): ?>
	<div class="row">
		<div class="col">Delta #2 Revision Issued</div>
		<div class="col">
			<a href="#" data-table="projects" data-name="delta_2_revisions_issued" data-type="date" class="edit-date arch" data-value="<?=dateFormat($project['delta_2_revisions_issued'])?>"></a>
		</div>
		<div class="col"></div>
	</div>
	<?php endif; ?>

	<?php if($project['delta_2_revisions_issued'] != '0000-00-00'): ?>
	<div class="row">
		<div class="col">Delta #3 Revision Issued</div>
		<div class="col">
			<a href="#" data-table="projects" data-name="delta_3_revisions_issued" data-type="date" class="edit-date arch" data-value="<?=dateFormat($project['delta_3_revisions_issued'])?>"></a>
		</div>
		<div class="col"></div>
	</div>	
	<?php endif; ?>
	
	<?php if($project['delta_3_revisions_issued'] != '0000-00-00'): ?>
	<div class="row">
		<div class="col">Delta #4 Revision Issued</div>
		<div class="col">
			<a href="#" data-table="projects" data-name="delta_4_revisions_issued" data-type="date" class="edit-date arch" data-value="<?=dateFormat($project['delta_4_revisions_issued'])?>"></a>
		</div>
	  <div class="col"></div>
	</div>	
	<?php endif; ?>
	
	<?php if($project['delta_4_revisions_issued'] != '0000-00-00'): ?>
	<div class="row">
		<div class="col">Delta #5 Revision Issued</div>
		<div class="col">
			<a href="#" data-table="projects" data-name="delta_5_revisions_issued" data-type="date" class="edit-date arch" data-value="<?=dateFormat($project['delta_5_revisions_issued'])?>"></a>
		</div>
		<div class="col"></div>
	</div>	
	<?php endif; ?>
		
	<div class="row">
		<div class="col">Store Punch</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="store_punch" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($scheduled['store_punch'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="store_punch" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($actual['store_punch'])?>"></a>
		</div>
	</div>	
	
	<div class="row">
		<div class="col">Store Turnover</div>
		<div class="col">
			<a data-table="scheduled_dates" data-name="store_turnover" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($scheduled['store_turnover'])?>"></a>
		</div>
		<div class="col">
			<a data-table="actual_dates" data-name="store_turnover" href="#" data-type="date" class="edit-date" data-value="<?=dateFormat($actual['store_turnover'])?>"></a>
		</div>
	</div>	

	<div class="row header-row">
		<div class="col">Operations</div> 
		<div class="col"></div>
		<div class="col"></div>	
	</div>
	
	<div class="row">
		<div class="col">Fixtures</div>
		<div class="col">
			<a href="#" data-table="projects" data-name="fixtures_date" data-type="date" class="edit-date" data-value="<?=dateFormat($project['fixtures_date'])?>"></a>
		</div>
		<div class="col"></div>
	</div>	

	<div class="row">
		<div class="col">DDS</div>
		<div class="col">
			<a href="#" data-table="projects" data-name="dds_date" data-type="date" class="edit-date" data-value="<?=dateFormat($project['dds_date'])?>"></a>
		</div>
		<div class="col"></div>
	</div>

	<div class="row">
		<div class="col">Merchandise</div>
		<div class="col">
			<a href="#" data-table="projects" data-name="merchandise_date" data-type="date" class="edit-date" data-value="<?=dateFormat($project['merchandise_date'])?>"></a>
		</div>
		<div class="col"></div>
	</div>	
	
	<div class="row">
		<div class="col">Close Store(Remodel/Relo only)</div>
		<div class="col">
			<a href="#" data-table="projects" data-name="closed_for_merchandise" data-type="date" class="edit-date" data-value="<?=dateFormat($project['closed_for_merchandise'])?>"></a>
		</div>
		<div class="col"></div>
	</div>
	
	<div class="row">
		<div class="col">Store Opening</div>
		<div class="col">
			<a href="#" data-table="projects" data-name="store_opening_date" data-type="date" class="edit-date" data-value="<?=dateFormat($project['store_opening_date'])?>"></a>
		</div>
		<div class="col"></div>
	</div>

	<div class="row">
		<div class="col">Grand Opening</div>
		<div class="col">
			<a href="#" data-type="select" data-table="projects" data-name="grand_opening" class="edit" data-source="[{value:'1',text:'Yes'},{value:'0',text:'No'},{value:'',text:'None'}]" data-value="<?=$project['grand_opening']?>"></a>
		</div>
		<div class="col"></div>
	</div>

	<div class="row">
		<div class="col">Construction Schedule Notes</div>
		<div class="col">
			<a href="#" data-type="textarea" data-table="projects" data-name="schedule_notes" class="edit arch" data-value="<?=stripslashes($project['schedule_notes'])?>"></a>
		</div>
		<div class="col"></div>
	</div>

</div>
<?php //project_schedule.php
  
  $query = "SELECT * FROM tracker WHERE id = ".mysqli_real_escape_string($dbcnx, $id);
  $result = mysqli_query($dbcnx, $query) or die ("no query x1");  
  $data = array();
  while($row = mysqli_fetch_assoc($result)) {
      $data[] = $row;
  }

  $tracker = $data[0];

?>

<div id="tracker-schedule-form" class="p-2">

<table class="table">
<tr>
  <th class="stage">Stage</th>
  <th class="tracker-column">Lease</th>
  <th class="tracker-column">LL Work</th>
  <th class="tracker-column">TT Work</th>
</tr>
<!-- 1 -->
<tr>
  <td><div class="badge badge-primary">1</div></td>
  <td>
    <div class="label">TT Deal Approval</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="lease_tt_deal_approved_loi_scheduled" data-type="date" data-value="<?=dateFormat($tracker['lease_tt_deal_approved_loi_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="lease_tt_deal_approved_loi_actual" data-type="date" data-value="<?=dateFormat($tracker['lease_tt_deal_approved_loi_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">Scope Complete</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    &nbsp;
  </td>
</tr>
<!-- 2 -->
<tr>
  <td>
    <div class="badge badge-primary">2</div>
  </td>
  <td>
    <div class="label">LL Deal Approval</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">Scope Approved</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">Criteria Sent</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
</tr>
<!-- 3 -->
<tr>
  <td>
    <div class="badge badge-primary">3</div>
  </td>
  <td>
    <div class="label">First Draft Lease</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    &nbsp;
  </td>
  <td>
    <div class="label">Project Kickoff</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
</tr>
<!-- 4 -->
<tr>
  <td>
    <div class="badge badge-primary">4</div>
  </td>
  <td>
    <div class="label">TT Lease Signed</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
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
<!-- 5 -->
<tr>
  <td>
    <div class="badge badge-primary">5</div>
  </td>
  <td>
    <div class="label">LL Lease Signed</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">CD Start</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">Preliminary Drawings Complete</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
</tr>
<!-- 6 -->
<tr>
  <td>
    <div class="badge badge-primary">6</div>
  </td>
  <td>
    &nbsp;
  </td>
  <td>
    <div class="label">CD Complete</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">Prelminary Drawings Approved</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
</tr>
<!-- 7 -->
<tr>
  <td>
    <div class="badge badge-primary">7</div>
  </td>
  <td>
    &nbsp;
  </td>
  <td>
    <div class="label">Permit Submitted</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">CD Complete</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
</tr>
<!-- 8 -->
<tr>
  <td>
    <div class="badge badge-primary">8</div>
  </td>
  <td>
    &nbsp;
  </td>
  <td>
    <div class="label">Permit Approved</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">CD Approved</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
</tr>
<!-- 9 -->
<tr>
  <td>
    <div class="badge badge-primary">9</div>
  </td>
  <td>
    &nbsp;
  </td>
  <td>
    <div class="label">Construction Start</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">Permit Submit</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
</tr>
<!-- 10 -->
<tr>
  <td>
    <div class="badge badge-primary">10</div>
  </td>
  <td>
    &nbsp;
  </td>
  <td>
    <div class="label">Construction Complete</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">Permit Approved</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
</tr>
<!-- 11 -->
<tr>
  <td>
    <div class="badge badge-primary">11</div>
  </td>
  <td>
    <div class="label">Delivery Letter</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">Space Delivery</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    <div class="label">Construction Start</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
</tr>
<!-- 12 -->
<tr>
  <td>
    <div class="badge badge-primary">12</div>
  </td>
  <td>
    &nbsp;
  </td>
  <td>
    &nbsp;
  </td>
  <td>
    <div class="label">Construction Complete</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
</tr>
<!-- 13 -->
<tr>
  <td>
    <div class="badge badge-primary">13</div>
  </td>
  <td>
    <div class="label">Rent Commence</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
  <td>
    &nbsp;
  </td>
  <td>
    <div class="label">Space Open</div>
    <div class="date-fields">
      <div class="scheduled">Projected
        <a href="#" class="edit-date" data-name="xxxx_scheduled" data-type="date" data-value="<?=dateFormat($tracker['xxxx_scheduled'])?>"></a>
      </div>
      <div class="actual">Actual
        <a href="#" class="edit-date" data-name="xxxx_actual" data-type="date" data-value="<?=dateFormat($tracker['xxxx_actual'])?>"></a>
      </div>
    </div>
  </td>
</tr>
</table>

    <div class="col">Misc Schedule Notes</div>
    <div class="col">
      <a href="#" data-type="textarea" data-table="projects" data-name="schedule_notes" class="edit arch" data-value="<?=stripslashes($project['schedule_notes'])?>"></a>
    </div>

</div>




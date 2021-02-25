<?php
$project_id = $_GET['id'];
$current_user_id = $_SESSION['unique_user_id'];
setlocale(LC_MONETARY, 'en_US');





  $id = mysqli_real_escape_string($dbcnx, $_GET['id']);
  $query = "SELECT * FROM cop_log WHERE project =  $id ORDER BY cop_number";
  $result  = mysqli_query($dbcnx, $query) or die(mysqli_error($dbcnx));
  $i = 0;
  while($row = mysqli_fetch_assoc($result)){ 
    //Add result items
    $cops[$i] = $row;
    $cops[$i]['date_submitted'] = date('m/d/y', strtotime($row['date_submitted']));
    $cops[$i]['date_reviewed'] = date('m/d/y', strtotime($row['date_reviewed']));
    $cops[$i]['status'] = ucfirst($row['status']);
    $cops[$i]['status_label_class'] = getStatusLabelClass($row['status']);
    //$cops[$i]['formatted_amount'] = '$'.number_format($row['amount'],2);
    if($row['amount'] == 0 ) {
      $cops[$i]['formatted_amount'] = 'TBD';      
    } else {
      $cops[$i]['formatted_amount'] = money_format('%(n',$row['amount']);
    }
    
    $cops[$i]['final_amount'] = $row['final_amount'];
    //$cops[$i]['formatted_final_amount'] = money_format('%(n',$row['final_amount']);
    if($row['final_amount'] == 0 ) {
      $cops[$i]['formatted_final_amount'] = '';      
    } else {
      $cops[$i]['formatted_final_amount'] = money_format('%(n',$row['final_amount']);
    }
    

    //Get request status and amount if any.
    if($row['request'] != 0){        
      //get the status
      $request_status_query = "SELECT status from change_order_requests WHERE id = ".$row['request'];
      $request_status_result = mysqli_query($dbcnx, $request_status_query) or die(mysqli_error($dbcnx));
      $request_status = mysqli_result($request_status_result, 0, "status");
      $cops[$i]['request_status'] = ucfirst($request_status);
      $cops[$i]['request_status_label_class'] = getStatusLabelClass($request_status);
    }

    //Get the owner name and companySELECT * , companies.company_name
    $user_query = "SELECT *, companies.company_name FROM users JOIN companies on companies.company_id = users.company_id WHERE id =  ".mysqli_real_escape_string($dbcnx, $row['user']);
    $user_result = mysqli_query($dbcnx, $user_query) or die(mysqli_error($dbcnx));
    $cops[$i]['user_name'] = mysqli_result($user_result, 0, "fullname");
    $cops[$i]['user_company'] = mysqli_result($user_result, 0, "company_name");

    //Get the reviewer
    if($row['reviewed_by'] != 0){
    $reviewer_query = "SELECT *, companies.company_name FROM users JOIN companies on companies.company_id = users.company_id WHERE id =  ".mysqli_real_escape_string($dbcnx, $row['reviewed_by']);
    $reviewer_result = mysqli_query($dbcnx, $reviewer_query) or die(mysqli_error($dbcnx));
    $cops[$i]['reviewer_name'] = mysqli_result($reviewer_result, 0, "fullname");
    $cops[$i]['reviewer_company'] = mysqli_result($reviewer_result, 0, "company_name");
    }

    //Get the RFI info
    if($row['rfi_id'] != 0){
    $rfi_query = "SELECT * FROM rfi WHERE id =  ".mysqli_real_escape_string($dbcnx, $row['rfi_id']);
    $rfi_result = mysqli_query($dbcnx, $rfi_query) or die(mysqli_error($dbcnx));
    $cops[$i]['rfi_subject'] = mysqli_result($rfi_result, 0, "subject");
    $cops[$i]['auto_number'] = mysqli_result($rfi_result, 0, "auto_number");    
    $cops[$i]['rfi_id'] = mysqli_result($rfi_result, 0, "id");
    }



    //Determine if this log item has an attachment
    $this_id = $row['id'];
    $project_id = $_GET['id'];
    //$path = "/var/www/vhosts/construction.charlotte-russe.com/httpdocs/cop_files/$project_id/$this_id/";
    $path = realpath(dirname(__FILE__))."/files/$project_id/cop/$this_id/";
    if(file_exists($path)) {
      $cops[$i]['has_attachment'] = TRUE;
      //Get attachment URL
      if ($handle = opendir($path)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
              $cops[$i]['attachments'][] = $entry;
            }
        }
        closedir($handle);
      }      
    } else {
      $cops[$i]['attachment'] = FALSE;  
    }    
    $i++;
  }
  
  //Get array of request items for this project
  $request_query = "SELECT * FROM change_order_requests WHERE project = $id ORDER BY co_number";
  $request_result  = mysqli_query($dbcnx, $request_query) or die(mysqli_error($dbcnx));
  $i = 0;
  while($row = mysqli_fetch_assoc($request_result)){
    //Add result items
    $requests[$i] = $row;
    //Determine COP total
    //Get all the COP amounts
    //$request_amount_query = "SELECT sum(amount) as request_amount from cop_log WHERE id = ".$row['id'];
    $request_amount_query = "SELECT total as request_amount from change_order_requests WHERE id = ".$row['id'];
    $request_amount_result = mysqli_query($dbcnx, $request_amount_query) or die(mysqli_error($dbcnx));
    $request_amount = mysqli_result($request_amount_result, 0, "request_amount");
    $requests[$i]['amount'] = $request_amount;
    //$requests[$i]['formatted_amount'] = '$'.number_format($request_amount,2);
    $requests[$i]['formatted_amount'] = money_format('%(n',$request_amount);
    $requests[$i]['date_submitted'] = date('m/d/y', strtotime($row['date_submitted']));
    $requests[$i]['date_reviewed'] = date('m/d/y', strtotime($row['date_reviewed']));
    $requests[$i]['status'] = ucfirst($row['status']);
    $requests[$i]['status_label_class'] = getStatusLabelClass($row['status']); 
    
    //Get the owner name and companySELECT * , companies.company_name
    $user_query = "SELECT *, companies.company_name FROM users JOIN companies on companies.company_id = users.company_id WHERE id =  ".mysqli_real_escape_string($dbcnx, $row['user']);
    $user_result = mysqli_query($dbcnx, $user_query) or die(mysqli_error($dbcnx));
    $requests[$i]['user_name'] = mysqli_result($user_result, 0, 'fullname');
    $requests[$i]['user_company'] = mysqli_result($user_result, 0, 'company_name');

    //Get the reviewer
    if($row['reviewed_by'] != 0){
    $reviewer_query = "SELECT *, companies.company_name FROM users JOIN companies on companies.company_id = users.company_id WHERE id =  ".mysqli_real_escape_string($dbcnx, $row['reviewed_by']);
    $reviewer_result = mysqli_query($dbcnx, $reviewer_query) or die(mysqli_error($dbcnx));
    $requests[$i]['reviewer_name'] = mysqli_result($reviewer_result, 0, "fullname");
    $requests[$i]['reviewer_company'] = mysqli_result($reviewer_result, 0, "company_name");
    }



    //Get COPs for this request
    $cops_query = "SELECT * FROM cop_log WHERE request = ".$row['id'];
    $cops_result = mysqli_query($dbcnx, $cops_query) or die(mysqli_error($dbcnx));
    while($crow = mysqli_fetch_assoc($cops_result)) {
      $label_class = getStatusLabelClass($crow['status']);       
      $requests[$i]['cops'][] = array( 'cop_number' => $crow['cop_number'], 'amount' => $crow['amount'], 'formatted_amount' => money_format('%(n',$crow['amount']), 'status' => $crow['status'], 'status_label_class' => $label_class );
    }

    //Determine if this log item has an attachment
    $this_id = $row['id'];    
    $path = realpath(dirname(__FILE__))."/files/$project_id/co_request/$this_id/";
    if(file_exists($path)) {
      $requests[$i]['has_attachment'] = TRUE;
      //Get attachment URL
      if ($handle = opendir($path)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
              $requests[$i]['attachments'][] = $entry;
            }
        }
        closedir($handle);
      }      
    } else {
      $requests[$i]['attachment'] = FALSE;  
    }

    $i++;
  }

  //Get array of RFI's for this project
  $rfi_query = "SELECT * FROM rfi WHERE parent=0 AND project_id =  $project_id ORDER BY id";
  $rfi_result  = mysqli_query($dbcnx, $rfi_query) or die(mysqli_error($dbcnx));
  $i = 0;
  while($row = mysqli_fetch_assoc($rfi_result)){
    //Add result items
    $rfis[$i] = $row;
    $i++;
  }

  //Get aggregate totals for this project
  $pending_cops_subtotal_query = "SELECT sum(amount) as subtotal from cop_log WHERE status = 'pending' AND project = $id";
  $pending_cops_subtotal_result = mysqli_query($dbcnx, $pending_cops_subtotal_query) or die(mysqli_error($dbcnx));
  $pending_cops_subtotal = mysqli_result($pending_cops_subtotal_result, 0, 'subtotal');
  
  $declined_cops_subtotal_query = "SELECT sum(amount) as subtotal from cop_log WHERE status = 'declined' AND project = $id";
  $declined_cops_subtotal_result = mysqli_query($dbcnx, $declined_cops_subtotal_query) or die(mysqli_error($dbcnx));
  $declined_cops_subtotal = mysqli_result($declined_cops_subtotal_result, 0, 'subtotal');
  
  $approved_cops_subtotal_query = "SELECT sum(amount) as subtotal from cop_log WHERE status = 'approved' AND project = $id";
  $approved_cops_subtotal_result = mysqli_query($dbcnx, $approved_cops_subtotal_query) or die(mysqli_error($dbcnx));
  $approved_cops_subtotal = mysqli_result($approved_cops_subtotal_result, 0, 'subtotal');
  

  $pending_requests_subtotal_query = "SELECT SUM( total ) AS subtotal FROM change_order_requests WHERE change_order_requests.status = 'pending' AND change_order_requests.project = $id";
  $pending_requests_subtotal_result = mysqli_query($dbcnx, $pending_requests_subtotal_query) or die(mysqli_error($dbcnx));
  $pending_requests_subtotal = mysqli_result($pending_requests_subtotal_result, 0, 'subtotal');
  
  $declined_requests_subtotal_query = "SELECT SUM( total ) AS subtotal FROM change_order_requests WHERE change_order_requests.status = 'declined' AND change_order_requests.project = $id";
  $declined_requests_subtotal_result = mysqli_query($dbcnx, $declined_requests_subtotal_query) or die(mysqli_error($dbcnx));
  $declined_requests_subtotal = mysqli_result($declined_requests_subtotal_result, 0, 'subtotal');

  $approved_requests_subtotal_query = "SELECT SUM( total ) AS subtotal FROM change_order_requests WHERE change_order_requests.status = 'approved' AND change_order_requests.project = $id";
  $approved_requests_subtotal_result = mysqli_query($dbcnx, $approved_requests_subtotal_query) or die(mysqli_error($dbcnx));
  $approved_requests_subtotal = mysqli_result($approved_requests_subtotal_result, 0, 'subtotal');

  $project_budget_status_query = "SELECT budget_status FROM projects WHERE id = $id";
  $project_budget_status_result = mysqli_query($dbcnx, $project_budget_status_query) or die(mysqli_error($dbcnx));
  $project_budget_status = mysqli_result($project_budget_status_result, 0, 'budget_status');


?>

  
  <?php if (count($msg) > 0): ?>
    <div class="well">
      <?php 
        foreach($msg as $m){
          print '<p>'.$m.'</p>';
        }
      ?>
    </div>
  <?php endif; ?>
  <form id="ProjectBudgetForm" method="POST" action="cop-action.php">
    <input type="hidden" name="project" value="<?php echo $project['id']; ?>">
    <input type="hidden" id="ProjectBudgetStatus" name="cop_mode" value="">
    <div class="container">

          
          <div class="row">
            <div class="col">Budget Status</div>
            <div class="col">            
            <?php if($project_budget_status == 'closed'): ?>              
              <div class="btn-group">
                <a class="btn btn-danger dropdown-toggle" data-toggle="dropdown" href="#">
                  CLOSED&nbsp;<span class="caret"></span>
                </a>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="#" id="OpenProjectBudget">Re-Open Budget</a>
                </div>
              </div>
            <?php else: ?>
              <div class="btn-group">
                <a class="btn btn-success dropdown-toggle" data-toggle="dropdown" href="#">
                  OPEN&nbsp;<span class="caret"></span></a>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="#" id="CloseProjectBudget">Close Budget</a>
                </div>
              </div>
            <?php endif;?>          
            </div>
          </div>


          <div class="row">
            <div class="col">Original Contract Amount</div>
            <div class="col"><a href="#" data-type="text" data-table="projects" data-name="original_contract_amount" class="edit-money">
                <?=$project['original_contract_amount']?>
              </a>
            </div>
          </div>

          <div class="row">
            <div class="col">Approved Change Orders to Date</div>
            <div class="col">
              <strong><?php echo money_format('%(n',$approved_requests_subtotal);?></strong>
            </div>
          </div>

          <div class="row">
            <div class="col">Revised Contract Amount to Date</div>
            <div class="col">
              <strong><?php echo money_format('%(n',($approved_requests_subtotal + $project['original_contract_amount']));?></strong>
            </div>
          </div>


    </div>
  </form>
  
  <hr class="my-5">

  <?php if($project_budget_status != 'closed'): ?>    
  <div class="text-center my-2">
    <button class="btn btn-info btn-sm submit-cop-trigger"><i class="fa fa-plus"></i> New Change Order Proposal</button>
  </div>
  <?php endif; ?>
  <h2>Change Order Proposals</h2>
  <?php if (count($cops) > 0): ?>
  <div class="sortbox">
    <table id="cops-table" class="table" style="font-size:12px;">
    <thead>
      <tr>
        <th>COP #</th>
        <th>Submitted</th>
        <th>Description</th>        
        <th>Category</th>
        <th>Proposal Status</th>
        <th>COP Amount</th>
        <th>Final Status</th>
        <th>Final Amount</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      $cop_total = 0;
      foreach($cops as $cop) {
        $cop_total = $cop_total + $cop['amount'];
        $request_totals[$cop['request']] = $cop['request_amount'];
        print '<tr data-cop-id="'.$cop['id'].'">';      
        print '<td>'.$cop['cop_number'].'</td>';
        print '<td>'.$cop['date_submitted'].'</td>';
        print '<td>'.$cop['description'].'</td>';
        $shortreason = (strlen($cop['reason']) > 35) ? substr($cop['reason'],0,32).'...' : $cop['reason'];
        print '<td><html:abbr title="'.$cop['reason'].'">'.$shortreason.'</html:abbr></small></td>';
        print '<td><span class="label '.$cop['status_label_class'].'">'.$cop['status'].'</span></td>';
        print '<td>'.$cop['formatted_amount'].'</td>';
        print '<td><span class="label '.$cop['request_status_label_class'].'">'.$cop['request_status'].'</span></td>';
        print '<td>'.$cop['formatted_final_amount'].'</td>';
        print '</tr>';
      } 

      //tabulate request totals
      $calculated_request_total = 0;
      foreach ($request_totals as $request_total){
        $calculated_request_total += $request_total;
      }
      ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="4"></td>
        <td><strong>COP Total</strong></td>
        <td><?php echo money_format('%(n',$cop_total); ?></td>
        <td><strong>Final Total</strong></td>
        <td><?php echo money_format('%(n',$calculated_request_total); ?></td>
      </tr>
      <tr>
        <td colspan="4"></td>
        <td><div><strong>Subtotals</strong></div>
            <div>Pending</div>
            <div>Approved</div>
            <div>Declined</div>
        </td>
        <td>
          <div>&nbsp;</div>
          <div><?php echo money_format('%(n',$pending_cops_subtotal); ?></div>
          <div><?php echo money_format('%(n',$approved_cops_subtotal); ?></div>
          <div><?php echo money_format('%(n',$declined_cops_subtotal); ?></div>
        </td>
        <td><div><strong>Subtotals</strong></div>
            <div>Pending</div>
            <div>Approved</div>
            <div>Declined</div>
        </td>
        <td>
          <div>&nbsp;</div>
          <div><?php echo money_format('%(n',$pending_requests_subtotal); ?></div>
          <div><?php echo money_format('%(n',$approved_requests_subtotal); ?></div>
          <div><?php echo money_format('%(n',$declined_requests_subtotal); ?></div>
        </td>
      </tr>
    </tfoot>
    </table>
  </div>
  <?php else: ?>
    <div class="well">
      <p>There are no change order requests associated with your account.</p>
    </div>
  <?php endif; ?>

  <hr class="my-5">

  <?php if($project_budget_status != 'closed'): ?>
  <div class="text-center my-2">
    <button class="btn btn-info btn-sm create-request-trigger"><i class="fa fa-plus"></i> New Change Order Request</button>
  </div>    
  <?php endif; ?>
  
  <h2>Change Order Requests</h2>
  
  <?php if (count($requests) > 0): ?>
  <div class="sortbox">
    <table id="requests-table" class="table" style="font-size:12px;">
    <thead>
      <tr>
        <th>CO #</th>
        <th>Submitted</th>
        <th>COPs</th>        
        <th>Status</th>
        <th>Amount</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      
      foreach($requests as $request) {      
        print '<tr data-request-id="'.$request['id'].'">';        
        print '<td>'.$request['co_number'].'</td>';
        print '<td>'.$request['date_submitted'].'</td>';
        print '<td>';
        foreach($request['cops'] as $request_cop) {
          print '<span style="padding-right:10px;">'.$request_cop['cop_number'].'</span>';
        }
        print '</td>';    
        print '<td><span class="label '.$request['status_label_class'].'">'.$request['status'].'</span></td>';
        print '<td>'.$request['formatted_amount'].'</td>';
        print '</tr>';
      } 
      ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="3"></td>
        <td><div><strong>Subtotals</strong></div>
            <div>Pending</div>
            <div>Approved</div>
            <div>Declined</div>
        </td>
        <td>
          <div>&nbsp;</div>
          <div><?php echo money_format('%(n',$pending_requests_subtotal); ?></div>
          <div><?php echo money_format('%(n',$approved_requests_subtotal); ?></div>
          <div><?php echo money_format('%(n',$declined_requests_subtotal); ?></div>
        </td>
      </tr>
    </tfoot>
    </table>
  </div>
  <?php else: ?>
    <div class="well">
      <p>There are no change order requests associated with your account.</p>
    </div>
  <?php endif; ?>





<div class="modal" tabindex="-1" role="dialog" id="create-request-modal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Generate Change Order Request</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        

          <?php 
          $logcount = 0;
          $cop_valid = FALSE;
          foreach($cops as $cop) {
            if($cop['request'] == 0 && $cop['status'] == 'Approved') {
            $cop_valid = TRUE;
            $tableHTML .= '<tr data-cop-amount="'.$cop['amount'].'">';          
            $tableHTML .= '<td><input class="request-checkbox" type="checkbox" name="cops[]" value="'.$cop['id'].'" /></td>';              
            $tableHTML .= '<td>'.$cop['cop_number'].'</td>';
            $tableHTML .= '<td>'.date('m/d/y', strtotime($cop['date_submitted'])).'</td>';
            $tableHTML .= '<td>'.$cop['description'].'</td>';
            $tableHTML .= '<td><span class="label '.$cop['status_label_class'].'">'.$cop['status'].'</span></td>';
            $tableHTML .= '<td style="text-align:right">'.$cop['formatted_amount'].'</td>';
            $tableHTML .= '<td><div class="input-prepend"><span class="add-on">$</span><input style="width:70px;" type="text" disabled="disabled" name="final_amount['.$cop['id'].']" class="cop-final-amount" data-cop-id="'.$cop['id'].'" /></div></td>';
            $tableHTML .= '</tr>';
            } 
          } 
          
          if(!$cop_valid) { 
            print '<div class="alert alert-warning">There are no approved COP items available to submit a change order request.</div>';          
          } else {
          ?>
      <form id="SubmitRequestForm" name="create_request" method="POST" action="cop-action.php" enctype="multipart/form-data">  
        <input type="hidden" name="cop_mode" value="create_request">
        <input type="hidden" name="project" value="<?php echo $project['id']; ?>">
        <input type="hidden" name="sitename" value="<?php echo $project['sitename']; ?>">
        <input type="hidden" name="store_number" value="<?php echo $project['store_number']; ?>">

        <table class="table table-condensed table-bordered" style="font-size:11px;">
          <thead>
            <tr>
              <th></th>
              <th>#</th>
              <th>Submitted</th>
              <th>Description</th>
              <th>Status</th>
              <th>COP Amount</th>
              <th>Final Amount</th>
            </tr>
          </thead>          
          <tbody>
            <?php print $tableHTML; ?>
          </tbody>
        </table>

        <?php if($cop_valid): ?>
        <div class="form-group">
          <label>Total Request Amount</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">$</span>
            </div>
            <input class="form-control" type="text" name="request_amount" id="request_amount">
          </div>          
        </div>
        <?php endif; ?>

        <div class="form-group">
          <label>Attachments</label>
          <input type="file" multiple="true" class="form-control-file" name="attachments[]">

        </div>  

        </form>  
        <?php } ?>
  

    </div>
    <div class="modal-footer">

      
      <button class="btn" data-dismiss="modal">Close</button>
      
      <?php if($cop_valid): ?>
      <button class="btn btn-success" id="RequestSubmit">Submit</button>
      <?php endif; ?>
    </div>
    
  </div>
</div>
</div>



<div class="modal" tabindex="-1" role="dialog" id="submit-cop-modal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Submit New COP</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="modal-body">
      <form id="SubmitCOPForm" name="submit_cop" method="post" action="cop-action.php" enctype="multipart/form-data">
        <input type="hidden" name="project" value="<?php echo $project['id']; ?>">
        <input type="hidden" name="sitename" value="<?php echo $project['sitename']; ?>">
        <input type="hidden" name="store_number" value="<?php echo $project['store_number']; ?>">
        <input type="hidden" name="cop_mode" value="submit_cop">
        
        <div class="form-group">
          <label for=description">Description</label>
          <textarea class="form-control" name="description" id="description"></textarea>
        </div>

        <div class="form-group">
          <label for="reason">Category</label>
          <select class="form-control" id="reason" name="reason">
            <option value="">Select Category</option>
            <option value="Owner requests a change">Owner requests a change</option>
            <option value="The City requests a change">The City requests a change</option>
            <option value="The plans and specifications have errors or omissions">The plans and specifications have errors or omissions</option>
            <option value="Hidden conditions">Hidden conditions</option>
            <option value="GC or Subcontractors requests a change">GC or Subcontractors requests a change</option>
            <option value="Credit">Credit</option>
            <option value="Other">Other</option>
            <option value="Force Majuere">Force Majuere</option>
          </select>          
        </div>

        <div class="form-group" style="display:none;" id="other-reason-input">
          <label for="specify">Specify</label>          
          <textarea class="form-control" id="specify" name="specify" placeholder="Specify other reason"></textarea>          
        </div>



        <div class="form-group" style="display:none;" id="credit-control">
          <label for="credit">Credit Amount</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">$</span>
            </div>
            <input class="form-control" type="text" name="credit" id="credit" placeholder="Credit Amount">
          </div>          
        </div>        
        
        <div class="form-group" id="amount-control">
          <label for="Amount">Amount</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">$</span>
            </div>
            <input class="form-control" type="text" name="amount" id="amount" placeholder="Amount">            
          </div>
        </div>
        
        <div class="form-group" id="rfi-control">
          <label for="rfi_id">RFI</label>        
          <select class="form-control" id="rfi_id" name="rfi_id">
            <option value="0">Select Related RFI (Optional)</option>
            <?php foreach($rfis as $rfi): ?>
              <option value="<?=$rfi['id']?>">[<?=$rfi['auto_number']?>] <?=$rfi['subject']?></option>
            <?php endforeach; ?>              
          </select>          
        </div>

        <div class="form-group">
          <label>Attachments</label>
          <input type="file" multiple="true" class="form-control-file" name="attachments[]">
        </div>
      </form>

    </div>
    <div class="text-center mb-4">      
      <button class="btn btn-info" id="COPSubmit">Submit <i class="fa fa-arrow-right"></i></button>
    </div>
  </div>
</div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="cop-detail-modal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">COP Detail</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form class="form-horizontal" name="update_cop" id="DetailCOPForm" method="post" action="cop-action.php">
        <input type="hidden" name="project" value="<?php echo $project['id']; ?>">
        <input type="hidden" name="sitename" value="<?php echo $project['sitename']; ?>">
        <input type="hidden" name="store_number" value="<?php echo $project['store_number']; ?>">
        <input type="hidden" class="detail-field" id="DetailCOPPostCOPNumber" name="cop_number" value="">
        <input type="hidden" class="detail-field" id="DetailCOPPostOwner" name="owner" value="">
        <input type="hidden" class="detail-field" id="DetailCOPPostId" name="cop" value="">
        <input type="hidden" class="detail-field" id="DetailCOPPostMode" name="cop_mode" value="">

      <table class="table table-bordered table-condensed" style="font-size:12px;">
        <tr>
          <td style="width:100px;"><strong>Date&nbsp;Submitted</strong></td>
          <td><span class="detail-field" id="DetailCOPDateSubmitted"></span></td>
        </tr>
        <tr>
          <td><strong>COP&nbsp;Number</strong></td>
          <td><span class="detail-field" id="DetailCOPCOPNumber"></span></td>
        </tr>
        <tr>
          <td><strong>Submitted&nbsp;By</strong></td>
          <td><span class="detail-field" id="DetailCOPSubmitted"></span></td>
        </tr>
        <tr>
          <td><strong>Description</strong></td>
          <td><span class="detail-field" id="DetailCOPDescription"></span></td>
        </tr>
        <tr>
          <td><strong>Category</strong></td>
          <td><span class="detail-field" id="DetailCOPReason"></span></td>
        </tr>
        <tr>
          <td><strong>Amount</strong></td>
          <td><span class="detail-field" id="DetailCOPAmount"></span></td>
        </tr>
        <tr>
          <td><strong>Related RFI</strong></td>
          <td><span class="detail-field" id="DetailCOPRFI"></span></td>
        </tr>
        <tr>
          <td><strong>Attachments</strong></td>
          <td><div class="detail-attachments"></div></td>
        </tr>
        <tr>
          <td><strong>Status</strong></td>
          <td><span class="detail-field" id="DetailCOPStatus"></span></td>
        </tr>
        <tr>
          <td><strong>Reviewed&nbsp;By</strong></td>
          <td><span class="detail-field" id="DetailCOPReviewed"></span></td>
        </tr>
        <tr>
          <td><strong>Date&nbsp;Reviewed</strong></td>
          <td><span class="detail-field" id="DetailCOPDateReviewed"></span></td>
        </tr>        
      </table>


      </form>
    </div>
    <div class="modal-footer">
      <button class="btn btn-small pull-left" data-dismiss="modal">Close</button>
      <?php if($isPM && $project_budget_status != 'closed'): ?>
      <button class="btn btn-small btn-success pm-action" id="DetailCOPApprove">Approve</button>
      <button class="btn btn-small btn-danger pm-action" id="DetailCOPDecline">Decline</button>
      <button class="btn btn-small btn-success pm-action hide" id="DetailCOPSubmitAmount">Submit Amount</button>
      <!--<button class="btn btn-small btn-warning pm-action" id="DetailCOPPending">Pending</button>-->
      <?php endif; ?>

      
    </div>
  </div>
</div>
</div>



<div class="modal" tabindex="-1" role="dialog" id="request-detail-modal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Request Detail</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form class="form-horizontal" name="update_request" id="DetailRequestForm" method="post" action="cop-action.php"> 
        <input type="hidden" name="project" value="<?php echo $project['id']; ?>">
        <input type="hidden" name="sitename" value="<?php echo $project['sitename']; ?>">
        <input type="hidden" name="store_number" value="<?php echo $project['store_number']; ?>">
        <input type="hidden" class="detail-field" id="DetailRequestPostCONumber" name="co_number" value="">
        <input type="hidden" class="detail-field" id="DetailRequestPostOwner" name="owner" value="">
        <input type="hidden" class="detail-field" id="DetailRequestPostId" name="request" value="">
        <input type="hidden" class="detail-field" id="DetailRequestPostMode" name="cop_mode" value="">
      </form>
      <table class="table table-bordered table-condensed" style="font-size:12px;">
        <tr>
          <td style="width:100px;"><strong>Date&nbsp;Submitted</strong></td>
          <td><span class="detail-field" id="DetailRequestDateSubmitted"></span></td>
        </tr>
        <tr>
          <td><strong>CO&nbsp;Number</strong></td>
          <td><span class="detail-field" id="DetailRequestCONumber"></span></td>
        </tr>
        <tr>
          <td><strong>Submitted&nbsp;By</strong></td>
          <td><span class="detail-field" id="DetailRequestSubmitted"></span></td>
        </tr>
        <tr>
          <td><strong>Amount</strong></td>
          <td><span class="detail-field" id="DetailRequestAmount"></span></td>
        </tr>
        <tr>
          <td><strong>COP Items</strong></td>
          <td><div class="detail-cop-item-list"></div></td>
        </tr>
        <tr>
          <td><strong>Attachments</strong></td>
          <td><div class="detail-attachments"></div></td>
        </tr>
        <tr>
          <td><strong>Status</strong></td>
          <td><span class="detail-field" id="DetailRequestStatus"></span></td>
        </tr>
        <tr>
          <td><strong>Reviewed&nbsp;By</strong></td>
          <td><span class="detail-field" id="DetailRequestReviewed"></span></td>
        </tr>
        <tr>
          <td><strong>Date&nbsp;Reviewed</strong></td>
          <td><span class="detail-field" id="DetailRequestDateReviewed"></span></td>
        </tr>        
      </table>
    </div>
    <div class="modal-footer">
      <button class="btn btn-small pull-left" data-dismiss="modal">Close</button>
      <?php if($isPM && $project_budget_status != 'closed'): ?>
      <button class="btn btn-small btn-success pm-action" id="DetailRequestApprove">Approve</button>
      <button class="btn btn-small btn-danger pm-action" id="DetailRequestDecline">Decline</button>
      <!--button class="btn btn-small btn-warning pm-action" id="DetailRequestPending">Pending</button> -->
      <?php endif; ?>
    </div>
  </div>
</div>
</div>


<form id="get-cop-file" action="get_download_file.php" method="POST">
  <input type="hidden" name="project_id" id="get-project_id" />
  <input type="hidden" name="cop_id" id="get-cop_id" />  
  <input type="hidden" name="file" id="get-file" />
</form>

<form id="get-co_request-file" action="get_download_file.php" method="POST">
  <input type="hidden" name="project_id" id="get-project_id" />
  <input type="hidden" name="co_request_id" id="get-co_request_id" />  
  <input type="hidden" name="file" id="get-file" />
</form>



  <script type="text/javascript">
    var cops = <?php echo json_encode($cops); ?>;
    var requests = <?php echo json_encode($requests); ?>;
    
    $(document).ready(function() {
      

      $(document).on('click', 'a.cop-file', function(e) {    
        e.preventDefault();
        $('input#get-project_id').val( $(this).attr('data-project_id') );
        $('input#get-cop_id').val( $(this).attr('data-cop_id') );    
        $('input#get-file').val( $(this).attr('data-file') );
        $('#get-cop-file').submit();
      });

      $(document).on('click', 'a.co_request-file', function(e) {
        e.preventDefault();
        $('input#get-project_id').val( $(this).attr('data-project_id') );
        $('input#get-co_request_id').val( $(this).attr('data-co_request_id') );    
        $('input#get-file').val( $(this).attr('data-file') );
        $('#get-co_request-file').submit();
      });


      //bind submit_cop modal
      $('.submit-cop-trigger').click(function() {
        $('#submit-cop-modal').modal('toggle');
      });
      //bind create_request modal
      $('.create-request-trigger').click(function() {
        $('#create-request-modal').modal('toggle');
      });

      $('#cops-table td').click(function() {
        copID = $(this).parent('tr').attr('data-cop-id');
        if(copID == null) {return false;}
        var result = $.grep(cops, function(e){ return e.id === copID; });
        //show modal
        $('#cop-detail-modal').modal('show');

        //Reset buttons
        $('#cop-detail-modal button.pm-action').removeAttr("disabled");

        //Disable buttons if already declined or approved
        if(result[0].status === 'Declined'){
          $('#cop-detail-modal button.pm-action').attr("disabled", "disabled");
        }

        if(result[0].status === 'Approved'){
          $('#cop-detail-modal button.pm-action').attr("disabled", "disabled");
        }


        //populate attachments
        $('#cop-detail-modal .detail-attachments').empty();
        if(result[0].has_attachment){
          $('#cop-detail-modal .detail-attachments').closest('tr').show();
          for (var i = 0; i < result[0].attachments.length; ++i) {
             $('#cop-detail-modal .detail-attachments').append('<a href="#" class="list-group-item list-group-item-action cop-file" data-project_id="'+result[0].project+'" data-cop_id="'+result[0].id+'" data-file="'+encodeURIComponent(result[0].attachments[i])+'"><i class="fas fa-file"></i>&nbsp;'+result[0].attachments[i]+'</a>')
          }
        } else {
          $('#cop-detail-modal .detail-attachments').closest('tr').hide();
        }

        //populate fields in modal
        $('#DetailCOPSubmitted').empty().html( result[0].user_name + ' (' + result[0].user_company + ')' );
        $('#DetailCOPStatus').empty().html( '<span class="label '+ result[0].status_label_class+'">' + result[0].status + '</span>');
        $('#DetailCOPDateSubmitted').empty().html( result[0].date_submitted );      
        $('#DetailCOPDescription').empty().html( result[0].description );
        $('#DetailCOPReason').empty().html( result[0].reason );
        
        if (result[0].formatted_amount == 'TBD') {        
          $('#DetailCOPAmount').empty().html( '<div class="input-prepend"><span class="add-on">$</span><input type="text" name="amount" /></div>' );
          $('#DetailCOPApprove,#DetailCOPDecline').hide();
          $('#DetailCOPSubmitAmount').show();

        } else {
          $('#DetailCOPAmount').empty().html( result[0].formatted_amount );
          $('#DetailCOPApprove,#DetailCOPDecline').show();
          $('#DetailCOPSubmitAmount').hide();
        }

        $('#DetailCOPCOPNumber').empty().html( result[0].cop_number );
        $('#DetailCOPPostCOPNumber').val( result[0].cop_number );
        $('#DetailCOPPostOwner').val( result[0].user_name + ' (' + result[0].user_company + ')' );
        $('#DetailCOPPostId').val( result[0].id );

        if(result[0].rfi_id != 0) {
          $('#DetailCOPRFI').empty().html( '[' + result[0].auto_number  + '] ' + result[0].rfi_subject ).closest('tr').show();
        } else {
          $('#DetailCOPRFI').empty().closest('tr').hide();
        }


        if(result[0].reviewed_by != 0) {
          $('#DetailCOPReviewed').empty().html( result[0].reviewer_name + ' (' + result[0].reviewer_company + ')' ).closest('tr').show();
          $('#DetailCOPDateReviewed').empty().html( result[0].date_reviewed ).closest('tr').show();
        } else {
          $('#DetailCOPReviewed').empty().closest('tr').hide();
          $('#DetailCOPDateReviewed').empty().closest('tr').hide();
        }
      });

      /*
      $('#DetailCOPDelete').click(function() {
        var r = confirm("Are you sure you want to delete this COP item?");
        if (r == true) {
          $('form#DetailCOPForm #DetailCOPPostMode').val('delete_cop');
          $('form#DetailCOPForm').submit();
        } else {
          return false;
        }
      });
      */

      $('#DetailCOPPending').click(function() {
        var r = confirm("Are you sure you want to set this COP item to pending?");
        if (r == true) {
          $('form#DetailCOPForm #DetailCOPostMode').val('pending_cop');
          $('form#DetailCOPForm').submit();
        } else {
          return false;
        }
      });


      $('#DetailCOPApprove').click(function() {
        var r = confirm("Are you sure you want to approve this COP item?");
        if (r == true) {
          $('form#DetailCOPForm #DetailCOPPostMode').val('approve_cop');
          $('form#DetailCOPForm').submit();
        } else {
          return false;
        }
      });


      $('#DetailCOPDecline').click(function() {
        var r = confirm("Are you sure you want to decline this COP item?");
        if (r == true) {
          $('form#DetailCOPForm #DetailCOPPostMode').val('decline_cop');
          $('form#DetailCOPForm').submit();
        } else {
          return false;
        }
      });

      $('#DetailCOPSubmitAmount').click(function() {
        var r = confirm("Are you sure you want to submit the amount for this COP item?");
        if (r == true) {
          $('form#DetailCOPForm #DetailCOPPostMode').val('submit_amount_cop');
          $('form#DetailCOPForm').submit();
        } else {
          return false;
        }
      });

      $('#cops-table td a').click(function(e){
        e.stopPropagation(); 
      });
      $('#requests-table td').click(function() {
        requestID = $(this).parent('tr').attr('data-request-id');
        if(requestID == null) {return false;}
        var result = $.grep(requests, function(e){ return e.id === requestID; });
        //show modal
        $('#request-detail-modal').modal('show');

        //Reset buttons
        $('#request-detail-modal button.pm-action').removeAttr("disabled");

        //Disable buttons if already declined or approved
        if(result[0].status === 'Declined'){
          $('#request-detail-modal button.pm-action').attr("disabled", "disabled");
        }

        if(result[0].status === 'Approved'){
          $('#cop-detail-modal button.pm-action').attr("disabled", "disabled");
        }        

        //populate cops
        $('#request-detail-modal .detail-cop-item-list').empty();      
        for (var i = 0; i < result[0].cops.length; ++i) {
          $('#request-detail-modal .detail-cop-item-list').append('<div><strong>'+result[0].cops[i].cop_number+':</strong> '+result[0].cops[i].formatted_amount+'</div>')
        }

        //populate attachments
        $('#request-detail-modal .detail-attachments').empty();
        if(result[0].has_attachment){
          $('#request-detail-modal .detail-attachments').closest('tr').show();
          for (var i = 0; i < result[0].attachments.length; ++i) {            
            $('#request-detail-modal .detail-attachments').append('<a href="#" class="list-group-item list-group-item-action co_request-file" data-project_id="'+result[0].project+'" data-co_request_id="'+result[0].id+'" data-file="'+encodeURIComponent(result[0].attachments[i])+'"><i class="fas fa-file"></i>&nbsp;'+result[0].attachments[i]+'</a>')
          }
        } else {
          $('#request-detail-modal .detail-attachments').closest('tr').hide();
        }        

        //populate fields in modal
        $('#DetailRequestSubmitted').empty().html( result[0].user_name + ' (' +result[0].user_company + ')' );
        $('#DetailRequestDateSubmitted').empty().html( result[0].date_submitted ); 
        $('#DetailRequestStatus').empty().html( '<span class="label '+ result[0].status_label_class+'">' + result[0].status + '</span>');
        $('#DetailRequestAmount').empty().html( result[0].formatted_amount );
        $('#DetailRequestCONumber').empty().html( result[0].co_number );
        $('#DetailRequestPostOwner').val( result[0].user_name + ' (' + result[0].user_company + ')' );
        $('#DetailRequestPostCONumber').val( result[0].co_number );
        $('#DetailRequestPostId').val( result[0].id );

        if(result[0].reviewed_by != 0) {
          $('#DetailRequestReviewed').empty().html( result[0].reviewer_name + ' (' +result[0].reviewer_company + ')' ).closest('tr').show();;
          $('#DetailRequestDateReviewed').empty().html( result[0].date_reviewed ).closest('tr').show();
        } else {
          $('#DetailRequestReviewed').empty().closest('tr').hide();
          $('#DetailRequestDateReviewed').empty().closest('tr').hide();
        }
      });

      /*
      $('#DetailRequestDelete').click(function() {
        var r = confirm("Are you sure you want to delete this request?");
        if (r == true) {
          $('form#DetailRequestForm #DetailRequestPostMode').val('delete_request');
          $('form#DetailRequestForm').submit();
        } else {
          return false;
        }
      });
      */

      $('#DetailRequestPending').click(function() {
        var r = confirm("Are you sure you want to set this request to pending?");
        if (r == true) {
          $('form#DetailRequestForm #DetailRequestPostMode').val('pending_request');
          $('form#DetailRequestForm').submit();
        } else {
          return false;
        }
      });
      $('#DetailRequestApprove').click(function() {
        var r = confirm("Are you sure you want to approve this request?");
        if (r == true) {
          $('form#DetailRequestForm #DetailRequestPostMode').val('approve_request');
          $('form#DetailRequestForm').submit();
        } else {
          return false;
        }
      });
      $('#DetailRequestDecline').click(function() {
        var r = confirm("Are you sure you want to decline this request?");
        if (r == true) {
          $('form#DetailRequestForm #DetailRequestPostMode').val('decline_request');
          $('form#DetailRequestForm').submit();
        } else {
          return false;
        }
      });


      $('#OpenProjectBudget').click(function() {
        var r = confirm("Are you sure you want to open this budget?");
        if (r == true) {
          $('form#ProjectBudgetForm #ProjectBudgetStatus').val('open_budget');
          $('form#ProjectBudgetForm').submit();
        } else {
          return false;
        }
      });


      $('#CloseProjectBudget').click(function() {
        var r = confirm("Are you sure you want to close this budget?");
        if (r == true) {
          $('form#ProjectBudgetForm #ProjectBudgetStatus').val('close_budget');
          $('form#ProjectBudgetForm').submit();
        } else {
          return false;
        }
      });

      $('#COPSubmit').click(function() {
        var r = confirm("Are you sure you want to submit this COP?");
        if (r == true) {
          $('form#SubmitCOPForm').submit();
        } else {
          return false;
        }
      });
      $('#RequestSubmit').click(function() {
        var ok;
        //check if final amount exceeds line item cop amount
        $('.request-checkbox:checked').each(function() {
          var thisFinalAmount = $(this).closest('tr').find('input.cop-final-amount').val();
          var thisCOPAmount = $(this).closest('tr').attr('data-cop-amount');
          
          if (thisFinalAmount > thisCOPAmount){
            alert("The final amount cannot exceed the COP amount.");
            ok = false;
            return false;
          } else {
            ok = true;
          }
        });

        if (ok) {
          var r = confirm("Are you sure you want to submit this change order request?");
          if (r == true) {
            $('form#SubmitRequestForm').submit();
          } else {
            return false;
          }
        }
      });      


      //handle request checkbox accumulation
      $('.request-checkbox').change(function(){
        //find all selected checkboxes and add up each one
        var request_amount = 0;
        $('#request_amount').val('');
        $(this).closest('tr').find('input.cop-final-amount').val('').prop('disabled', true);

        $('.request-checkbox:checked').each(function() {
          //If final amount is blank, fill it in with the cop amount to start
          var thisAmount = $(this).closest('tr').attr('data-cop-amount')
          var thisFinal = $(this).closest('tr').find('input.cop-final-amount');
          if( $(thisFinal).val() == '' ) {
            //the final amount is blank.. fill it in
            $(thisFinal).val(thisAmount).prop('disabled', false);
          }
          request_amount = request_amount + parseFloat( $(thisFinal).val() );
          //update element
          $('#request_amount').val( request_amount.toFixed(2) );
        });
      });

      //handle final amount adjustments
      $('input.cop-final-amount').keyup(function(){
        var request_amount = 0;
        $('#request_amount').val('');
        $('.request-checkbox:checked').each(function() {
          var thisFinal = $(this).closest('tr').find('input.cop-final-amount');
          request_amount = request_amount + parseFloat( $(thisFinal).val() );
          //update element
          $('#request_amount').val( request_amount.toFixed(2) );
        });
      });

      $('#credit').keyup(function(){
        if($(this).val() > 0) {
          $(this).val(0 - $(this).val());
        }
      });
      
      $("#reason").change(function () {
        $('#credit').val('');
        $('#specify').val('');


        var picked = this.value;
        if (picked == "Other") {
          $('#other-reason-input').show();
          $('#amount-control').show();
          $('#credit-control').hide();          
        } else {
          

          $('#other-reason-input').hide();
          
          if (picked == "Credit") {
            //Switch to Credit mode
            $('#amount').val('');
            $('#amount-control').hide();
            $('#credit-control').show();
          } else {
            //Go back to regular mode
            $('#amount-control').show();
            $('#credit-control').hide();
          }
        }
      });





    });
  </script>
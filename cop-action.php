<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include('include/access.php');
$current_user_id = $_SESSION['unique_user_id'];
$project_id = mysqli_real_escape_string($dbcnx, $_POST['project']);
$store_number = mysqli_real_escape_string($dbcnx, $_POST['store_number']);
$cop = mysqli_real_escape_string($dbcnx, $_POST['cop']);
$request = mysqli_real_escape_string($dbcnx, $_POST['request']);
$rfi_id = mysqli_real_escape_string($dbcnx, $_POST['rfi_id']);
$description = mysqli_real_escape_string($dbcnx, $_POST['description']);
$reason = mysqli_real_escape_string($dbcnx, $_POST['reason']);
$amount = mysqli_real_escape_string($dbcnx, $_POST['amount']);

$mode = $_POST['cop_mode']; 


switch ($mode) {
  case "close_budget":    
    $budget_update = "UPDATE projects SET budget_status = 'closed' WHERE id = $project_id";
    $budget_update_result = mysqli_query($dbcnx, $budget_update) or die($budget_update . ':' . mysqli_error($dbcnx));
    $msg[] = "Project Budget Closed";
  break;

  case "open_budget":
    $budget_update = "UPDATE projects SET budget_status = 'open' WHERE id = $project_id";
    $budget_update_result = mysqli_query($dbcnx, $budget_update) or die($budget_update . ':' . mysqli_error($dbcnx));
    $msg[] = "Project Budget Open";
  break;


  case "delete_cop":
    $update = "DELETE FROM cop_log WHERE id = $cop";
    $update_result = mysqli_query($dbcnx, $update) or die($update . ':' . mysqli_error($dbcnx));
    $msg[] = "COP Item Deleted";
  break;

  case "pending_cop":    
    $update = "UPDATE cop_log SET status = 'pending', reviewed_by = $current_user_id, date_reviewed = NOW() WHERE id = $cop";
    $update_result = mysqli_query($dbcnx, $update) or die($update . ':' . mysqli_error($dbcnx));
    $msg[] = "COP Item Pending";
  break;


  case "approve_cop":      
    $update = "UPDATE cop_log SET status = 'approved', reviewed_by = $current_user_id, date_reviewed = NOW() WHERE id = $cop";
    $update_result = mysqli_query($dbcnx, $update) or die($update . ':' . mysqli_error($dbcnx));
    $msg[] = "COP Item Approved"; 
  break;

  case "decline_cop":
    $update = "UPDATE cop_log SET status = 'declined', reviewed_by = $current_user_id, date_reviewed = NOW() WHERE id = $cop";
    $update_result = mysqli_query($dbcnx, $update) or die($update . ':' . mysqli_error($dbcnx));
    $msg[] = "COP Item Declined";
    $notification = "COP #".$_POST['cop_number']. " was DECLINED";
  break;

  case "submit_amount_cop":    
    $amount = str_replace(",", "", $amount);
    $update = "UPDATE cop_log SET amount = $amount WHERE id = $cop";
    $update_result = mysqli_query($dbcnx, $update) or die($update . ':' . mysqli_error($dbcnx));
    $msg[] = "COP Item Amount Updated";
    $notification = "COP #".$_POST['cop_number']. " was UPDATED";
  break;


  case "delete_request":
    $update = "DELETE FROM change_order_requests WHERE id = $request";
    $update_result = mysqli_query($dbcnx, $update) or die($update . ':' . mysqli_error($dbcnx));
    $msg[] = "Request Item Deleted";
  break;

  case "pending_request":      
    $update = "UPDATE change_order_requests SET status = 'pending', reviewed_by = $current_user_id, date_reviewed = NOW() WHERE id = $request";
    $update_result = mysqli_query($dbcnx, $update) or die($update . ':' . mysqli_error($dbcnx));
    $msg[] = "Request Item Pending";
  break;


  case "approve_request":
    $update = "UPDATE change_order_requests SET status = 'approved', reviewed_by = $current_user_id, date_reviewed = NOW() WHERE id = $request";
    $update_result = mysqli_query($dbcnx, $update) or die($update . ':' . mysqli_error($dbcnx));
    $msg[] = "Request Item Approved";
    
    //get amount  notification
    $total = "SELECT * from change_order_requests WHERE id = $request";
    $total_result = mysqli_query($dbcnx, $total) or die($total . ':' . mysqli_error($dbcnx));
    $total = mysqli_result($total_result, 0, "total");

    //send approval notification
    $subject = "Approved Change Order #" . $_POST['co_number'] . " / #" . $_POST['store_number'] . "-" . $_POST['sitename'];
    $notification ="A change order was approved for #" . $_POST['store_number'] . "-" . $_POST['sitename'] . "

Total Amount: ".$total;
      sendNotification($_POST['project'], $subject, $notification, 'approval');

  break;

  case "decline_request":    
    $update = "UPDATE change_order_requests SET status = 'declined', reviewed_by = $current_user_id, date_reviewed = NOW() WHERE id = $request";
    $update_result = mysqli_query($dbcnx, $update) or die($update . ':' . mysqli_error($dbcnx));
    $msg[] = "Request Item Declined";
  break;

  case "submit_cop":    
    if(empty($_POST['project'])) {error('Project ID missing');}
    if(empty($_POST['description'])) {error('Description missing');}
    if(empty($_POST['reason'])) {error('Category missing');}        
    if(empty($_POST['amount'])){$_POST['amount'] = 0;}
    
    if(!empty($_POST['credit']) ){
      if( $_POST['credit'] > 0 ) {
        //if credit was somehow not signed, make it a negative
        $_POST['credit'] = - $_POST['credit'];
      }
      //Assign the credit to Amount
      $_POST['amount'] = $_POST['credit'];
    }

    if( $_POST['reason'] == 'Other') {
      if( empty($_POST['specify']) ) { 
        error('Other must be specified');
      } else {
       $_POST['reason'] = "Other: " . $_POST['specify']; 
      }
    }
    
    $increment_query = "SELECT count(*) AS c FROM cop_log WHERE project = $project_id";    
    $increment_result = mysqli_query($dbcnx, $increment_query);
    $increment = mysqli_result($increment_result, 0,"c");
    $increment++;
    $increment = sprintf("%03s",$increment);
    $cop_number = $store_number."-".$increment;

    $insert = "INSERT INTO cop_log SET 
      project = $project_id,
      cop_number = '".$cop_number."',
      date_submitted = NOW(),
      user = $current_user_id, 
      rfi_id = $rfi_id,
      description = '$description',
      reason = '$reason',
      amount = $amount,
      status = 'pending'";
    $result = mysqli_query($dbcnx, $insert) or die($insert . mysqli_error($dbcnx));
    
    if($result) {      
      $this_id = mysqli_insert_id($dbcnx);      
      foreach ($_FILES["attachments"]["error"] as $key => $error) {
        if ($error == UPLOAD_ERR_OK) {
          $tmpname = $_FILES["attachments"]["tmp_name"][$key];
          $filename = $_FILES["attachments"]["name"][$key];
          //In case project folder does not yet exist, try to create it
          if (!file_exists(realpath(dirname(__FILE__))."/files/$project_id/"))
            mkdir(realpath(dirname(__FILE__))."/files/$project_id/");
          //Create cop folder inside project folder
          if (!file_exists(realpath(dirname(__FILE__))."/files/$project_id/cop"))
            mkdir(realpath(dirname(__FILE__))."/files/$project_id/cop");
          //Create folder for this specific cop record
          if (!file_exists(realpath(dirname(__FILE__))."/files/$project_id/cop/$this_id/"))
            mkdir(realpath(dirname(__FILE__))."/files/$project_id/cop/$this_id/");
          
          $path = realpath(dirname(__FILE__))."/files/$project_id/cop/$this_id/";
          
          if (!file_exists($path.$filename)){
            move_uploaded_file($tmpname, $path.$filename);
            $msg[] = "New COP Item Posted";
          } 
        }
      }
      //Handle Notifications to PM
      $subject = "New COP #" . $cop_number . " / #" . $_POST['store_number'] . "-" . $_POST['sitename'];
      $notification = "$username ($usercompanyname) submitted a new COP for #" . $_POST['store_number'] . "-" . $_POST['sitename'] . "

Description: ".$_POST['description']."

Category: ".$_POST['reason']."

Amount: $".$_POST['Amount'];
      sendNotification($_POST['project'], $subject, $notification);

    }
  break;


  case "create_request":
    if(empty($_POST['cops'])) {error('COP selection missing');}
    if(empty($_POST['final_amount'])) {error('Final amount missing');}
    foreach($_POST['final_amount'] as $postedFinalAmount ) {
      if (empty($postedFinalAmount)) {
        error('Final amount missing');
      }
    }
    
    
    $increment_query = "SELECT count(*) AS c FROM change_order_requests WHERE project = $project_id";
    $increment_result = mysqli_query($dbcnx, $increment_query);
    $increment = mysqli_result($increment_result, 0,"c");
    $increment++;
    $increment = sprintf("%03s",$increment);    

    //Create request row
    $insert = "INSERT INTO change_order_requests SET 
    project = $project_id,
    co_number = '$store_number-$increment',
    total = ".mysqli_real_escape_string($dbcnx, $_POST['request_amount']).",
    date_submitted = NOW(),
    user = $current_user_id,
    status = 'pending'";

    $result = mysqli_query($dbcnx, $insert) or die($insert . ':' . mysqli_error($dbcnx));
    if($result) {
      $this_id = mysqli_insert_id($dbcnx);

      foreach ($_FILES["attachments"]["error"] as $key => $error) {
        if ($error == UPLOAD_ERR_OK) {
          $tmpname = $_FILES["attachments"]["tmp_name"][$key];
          $filename = $_FILES["attachments"]["name"][$key];
          //In case project folder does not yet exist, try to create it
          if (!file_exists(realpath(dirname(__FILE__))."/files/$project_id/"))
            mkdir(realpath(dirname(__FILE__))."/files/$project_id/");
          //Create cop folder inside project folder
          if (!file_exists(realpath(dirname(__FILE__))."/files/$project_id/co_request/"))
            mkdir(realpath(dirname(__FILE__))."/files/$project_id/co_request/");
          //Create folder for this specific co_request record
          if (!file_exists(realpath(dirname(__FILE__))."/files/$project_id/co_request/$this_id/"))
            mkdir(realpath(dirname(__FILE__))."/files/$project_id/co_request/$this_id/");
          
          $path = realpath(dirname(__FILE__))."/files/$project_id/co_request/$this_id/";
          
          if (!file_exists($path.$filename)){
            move_uploaded_file($tmpname, $path.$filename);
            $msg[] = "New Change Order Request Posted";
          }
        }
      }      

      //iterate through selected log items
      foreach($_POST['cops'] as $request_cop) {
        $this_final_amount  = $_POST['final_amount'][$request_cop];        
        //Update row to reflect request id
        $update = "UPDATE cop_log SET request = $this_id , final_amount = $this_final_amount WHERE id = $request_cop";
        $update_result = mysqli_query($dbcnx, $update) or die($update . ':' . mysqli_error($dbcnx));
      }
      //Handle Notifications to PM
      $subject = "New CO Request #" . $co_number . " / #" . $_POST['store_number'] . "-" . $_POST['sitename'];
      $notification = "$username ($usercompanyname) submitted a new Change Order Request for #" . $_POST['store_number'] . "-" . $_POST['sitename'];

      sendNotification($_POST['project'], $subject, $notification);

    } //if result
  break;
}


?>
<div style="text-align: center;margin:5rem;"><h4>Processing...please wait.</h4></div>
<!--
<?php print_r($msg); ?>
-->
<script type="text/javascript">
  window.setTimeout(function(){        
      window.location.href = "index.php?page=project&id=<?=$project_id?>&cop=1";
  }, 2000);
</script>
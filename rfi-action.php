<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include('include/access.php');
$rfi_id = mysqli_real_escape_string($dbcnx, $_POST['rfi_id']);
$parent_rfi_id = mysqli_real_escape_string($dbcnx, $_POST['parent_rfi_id']);
$project_id = mysqli_real_escape_string($dbcnx, $_POST['project_id']);
$author_id = mysqli_real_escape_string($dbcnx, $_POST['author_id']);
$group_id = mysqli_real_escape_string($dbcnx, $_POST['group_id']);
$store_number = mysqli_real_escape_string($dbcnx, $_POST['store_number']);
$subject = mysqli_real_escape_string($dbcnx, $_POST['subject']);
$body = mysqli_real_escape_string($dbcnx, $_POST['body']);
$priority = mysqli_real_escape_string($dbcnx, $_POST['priority']);

if(isset($_POST['action'])) {
    
  if($_POST['action'] == 'load_rfi_list') {
    //Get current user group id 
    //$group_id = $_SESSION['current_user_group_id'];
    $group_id = 1;
    switch($group_id) {
      case 1:
      case 2:
      case 3:
      case 4:
      //if Corp or better show all
      $query = "SELECT * FROM rfi 
        WHERE project_id = $project_id 
        AND parent = 0  
        ORDER BY datetouched";
      break;

      default:
      //if vendor, show only theirs   
      $query = "SELECT * FROM rfi 
        WHERE project_id = $project_id 
        AND parent = 0  
        AND group_id = $group_id 
        ORDER BY datetouched";
      break;
    }

    $result = mysqli_query($dbcnx, $query) or die(mysqli_error($dbcnx));
    while ($row = mysqli_fetch_array($result)): ?>
    <tr data-rfi-id="<?=$row['id']?>">
      <td><?=$row['auto_number']?></td>
      <td><?=dateconvert($row['date_added'])?></td>
      <td><?=$row['subject']?></td>
      <td><?=get_group_name_by_id($row['group_id'])?></td>
      <td><?=$row['priority']?></td>    
    </tr>
    <?php endwhile;
  }

  if($_POST['action'] == 'load_rfi_reply_list') {
    //get replies
    $query = "SELECT * FROM rfi WHERE parent = $rfi_id";
    $result = mysqli_query($dbcnx, $query) or die(mysqli_error($dbcnx));
    while($reply = mysqli_fetch_array($result)):
      $user = get_user_array_by_id($reply['author']);
      $project_id = $reply['project_id'];
      $rfi_reply_id = $reply['id'];
      ?>
      <div class="card bg-light my-3">
        <div class="card-body">
          <div>
            <small class="text-muted">
            <?=$user['user_name']?>
            &bull; 
            <?=get_group_name_by_id($reply['group_id'])?>
            &bull; 
            <?=dateconvert($reply['date_added'])?>
            </small>
          </div>
          <?=$reply['body']?>
          <div class="attachments list-group">
          <?php
          $path = realpath(dirname(__FILE__))."/files/$project_id/rfi/$rfi_reply_id";
          $get_path = "/files/$project_id/rfi/$rfi_reply_id";          
          $file_data = scandir($path);
          foreach($file_data as $file) {
            if($file === '.' or $file === '..') {
              continue;
            } else {
              $filesize = filesize("$path/$file");        
            ?>
            <a href="#" class="list-group-item list-group-item-action rfi-file" 
            data-project-id="<?=$project_id?>" 
            data-rfi-id="<?=$rfi_reply_id?>"         
            data-file="<?=$file?>">
              <i class="fas fa-file"></i>&nbsp;
              <?=$file?>&nbsp;
              <span class="file-meta">
                <?=format_file_size($filesize)?>
              </span>
            </a>
            <?php
            }
          }
          ?>
          </div>
        </div>
      </div>
    <?php endwhile; 
  }


  if($_POST['action'] == 'load_rfi_data') {
    $query = "SELECT * FROM rfi WHERE id = $rfi_id";
    $result = mysqli_query($dbcnx, $query) or die(mysqli_error($dbcnx));
    $row = mysqli_fetch_array($result);
    $user = get_user_array_by_id($row['author']);
    $project_id = $row['project_id']; 
    ?>
    <div id="loaded-rfi-data" 
    data-rfi-id="<?=$rfi_id?>" 
    data-project-id="<?=$project_id?>" 
    data-rfi-number="<?=$row['auto_number']?>">
      <div>
        <small class="text-muted">
          <?=$user['user_name']?>
          &bull; 
          <?=get_group_name_by_id($row['group_id'])?>
          &bull; 
          <?=dateconvert($row['date_added'])?>
          &bull; 
          <span class="badge badge-info"><?=$row['priority']?> priority</span>
        </small>
      </div>
      
      <h5 class="card-title"><?=$row['subject']?></h5>
      <p class="card-text mb-4"><?=$row['body']?></p>            
      <div class="attachments list-group">
      <?php
      $path = realpath(dirname(__FILE__))."/files/$project_id/rfi/$rfi_id";
      $get_path = "/files/$project_id/rfi/$rfi_id";      
      $file_data = scandir($path);
      foreach($file_data as $file) {
        if($file === '.' or $file === '..') {
          continue;
        } else {
          $filesize = filesize("$path/$file");        
        ?>
        <a href="#" class="list-group-item list-group-item-action rfi-file" 
        data-project-id="<?=$project_id?>" 
        data-rfi-id="<?=$rfi_id?>"         
        data-file="<?=$file?>">
          <i class="fas fa-file"></i>&nbsp;
          <?=$file?>&nbsp;
          <span class="file-meta">
            <?=format_file_size($filesize)?>
          </span>
        </a>
        <?php
        }
      }
      ?>
      </div>
    </div>

    <div id="rfi-reply-list"></div>

    <form id="new-rfi-reply-form">
      <input type="hidden" id="new-rfi-reply-id" value="" />
      <div class="form-group">        
        <textarea placeholder="Write a reply" class="form-control" name="new-rfi-reply" id="new-rfi-reply"></textarea>
      </div>
      <div id="fine-uploader-rfi-reply"></div>
      <div class="form group text-center my-2">
        <button type="submit" id="submit-rfi-reply-form" class="btn btn-info">Submit Reply <i class="fa fa-arrow-right"></i></button>
      </div>
    </form>

    <?php
  }

  if($_POST['action'] == 'add_rfi') {
    $query = "SELECT * FROM rfi WHERE project_id = $project_id";
    $result = mysqli_query($dbcnx, $query) or die(mysqli_error($dbcnx));
    $count = mysqli_num_rows($result);
    $next = $count++;
    $number = str_pad($next, 3, '0', STR_PAD_LEFT);

    $query = "INSERT INTO rfi SET 
      author = $author_id,
      project_id = $project_id,
      group_id = $group_id,
      subject = '$subject',
      body = '$body',
      priority = '$priority',
      status = 'new',
      auto_number = '$store_number-RFI-$number',
      date_added = CURDATE()";
    $result = mysqli_query($dbcnx, $query) or send_response(mysqli_error($dbcnx));      
    if($result) {
      $insert_id = mysqli_insert_id($dbcnx);
      send_response('RFI_ADDED', $insert_id);
    }    
  }

  if($_POST['action'] == 'add_rfi_reply') { 
    $query = "INSERT INTO rfi SET 
      author = $author_id,      
      project_id = $project_id,
      group_id = $group_id,
      body = '$body',
      parent = $parent_rfi_id,       
      date_added = CURDATE()";
    $result = mysqli_query($dbcnx, $query) or send_response(mysqli_error($dbcnx));      
    if($result) {
      $insert_id = mysqli_insert_id($dbcnx);
      send_response('RFI_REPLY_ADDED', $insert_id);
    }    
  }
}
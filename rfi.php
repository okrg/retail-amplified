<?php
date_default_timezone_set('America/Los_Angeles');

if(isset($_GET['rmode'])) {
  $rmode = $_GET['rmode']; 
} else {
  $rmode = NULL;
}

switch ($rmode) {

default:

?>
  

  <div class="sortbox">
  <table id="rfi-table" class="table">
  <thead>
    <tr>
    <th>#</th>
    <th>From</th>
    <th>Subject</th>
    <th>Priority</th>
    <th>Date</th>
    <th>Replies</th>
    </tr>
  </thead>
  <tbody>

<?php  
  dbConnect();

  //Get  all Parent RFIs for this project
  $sql = "select * from rfi where project_id = $id and rank = 'parent' order by priority desc";

  $result = mysql_query($sql);
  if (!$result) {error("A databass error has occured.\\n".mysql_error());}
  
  if (mysql_num_rows($result) > 0) {    
    //Cultivate data set
    while ($row = mysql_fetch_array($result))   {
    $from_id = $row["author"];
    $rfi_id = $row["id"];
    $rfi_number = $row["rfi_number"];
    $auto_number = $row["auto_number"];
    $subject = $row["subject"];
    $body = $row["body"];
    $priority = $row["priority"];
    $datetouched = $row["datetouched"];
    $datetouched = date('m/d/Y', strtotime($datetouched));


    //Get RFI Author data
    $author_data = mysql_query("select * from users,companies where users.id = $from_id and users.company_id = companies.company_id");
    $author_row = mysql_fetch_object($author_data);
    $author_name = $author_row->fullname;
    $author_company_id = $author_row->company_id;
    $author_company_name = $author_row->company_name;

    //Check if any child responses have vendor array match
    $vendor_added = FALSE;
    $rfi_vendors = mysql_query("select * from rfi where rfi_number = '$rfi_number' and rfi_vendors LIKE '%:\"$usercompany\"%'");
    if (mysql_num_rows($rfi_vendors) > 0) {    
      $vendor_added = TRUE;

    }

    //Determine if it's viewable by current user
    if (($usergroup < 2) || $company_is_architect || $company_is_gc || ($usercompany == $author_company_id) || $vendor_added) {
      //Do nothing
    } else {
      continue;
    }



    
    
    //Determine priority level
    switch ($priority) {
      case "1": $priority_level = "Low";break;
      case "2": $priority_level = "Medium";break;
      case "3": $priority_level = "High";break;
      default: $priority_level = "None";break;
      }  
    //get number of replies for this RFI
    $replies_sql = "select * from rfi where rfi_number = '$rfi_number' and rank = 'child'";
    $replies_result = mysql_query($replies_sql);

    if (!$replies_result) {error("A databass error has occured.\\n".mysql_error());  }
    
    $replies = mysql_num_rows($replies_result);

    echo '<tr data-rfi-url="'.$PHP_SELF.'?page=rfi&rmode=view&rfi_id='.$rfi_id.'">';
    echo "<td>$auto_number</td>";
    echo "<td>$author_name ($author_company_name)</td>";
    echo "<td>";
    echo "<a href=\"$PHP_SELF?page=rfi&rmode=view&rfi_id=$rfi_id\">";
    echo "$subject";
    echo "</a>";
    echo "</td>";    
    echo "<td>$priority_level</td>";
    echo "<td>$datetouched</td>";
    echo "<td>";
    if ($replies > 0) {
      echo "<strong>$replies</strong></td>";
    } else {
      echo "--</td>";
    }
    
    echo "</tr>";
    }
  }
?>
    </tbody>
  </table>
  </div>

  <div id="rfi_submit">
  <h2>Submit a new RFI</h2>
    <div class="pull-right">
    <div class="well">
    <form class="form form-horizontal" method="post" action="<?php echo "$PHP_SELF?page=rfi&rmode=submit&rank=p"; ?>" enctype="multipart/form-data">
    <input type="hidden" name="project_id" value="<?php echo $id; ?>"></input>
    <!--
     remove these hidden tags and add one for $usercomany
    -->

      <div class="control-group">
        <label class="control-label" for="new_rfi_subject">Subject</label>
        <div class="controls">
          <input type="text" id="new_rfi_subject" name="new_rfi_subject">
        </div>
      </div>  
      
      <div class="control-group">
        <label class="control-label" for="new_rfi_priority">Priority</label>
        <div class="controls">
         <select class="files" id="new_rfi_priority" name="new_rfi_priority" onKeyPress="return noenter()">
            <option value="1">Low (3 days)</option>
            <option value="2" selected="selected">Medium (2 days)</option>
            <option value="3">High (24 hours)</option>value="outlet">Outlet</option>
          </select>
        </div>
      </div>      

      <div class="control-group">
        <label class="control-label" for="new_rfi_body">RFI/Questions</label>
        <div class="controls">
          <textarea id="new_rfi_body" name="new_rfi_body" class="full-width" rows="6"></textarea> 
          <div><a href="#" onClick="javascript:$(this).hide();toggleBox('attachments',1);return false;"> Add Attachments</a></div>
        </div>
      </div>

      <div class="control-group hide" id="attachments">
        <label class="control-label" for="new_rfi_attachment">Attachments</label>
        <div class="controls" style="width:300px;">
          <input type="file" name="new_rfi_attachment[]" id="new_rfi_attachment">
          <input type="file" name="new_rfi_attachment[]" id="new_rfi_attachment">
          <input type="file" name="new_rfi_attachment[]" id="new_rfi_attachment">
          <input type="file" name="new_rfi_attachment[]" id="new_rfi_attachment">
          <input type="file" name="new_rfi_attachment[]" id="new_rfi_attachment">
        </div>
      </div>


      <div class="actions">
        <button class="btn pull-right" type="submit">Submit New RFI</button>
      </div>
      <div class="clearfix">&nbsp;</div>
    </form>
    </div>
    </div>
    <div style="width:300px;">
      <p>Use this form to submit a request for information from the project manager. You will receive an email notification when a response has been posted.</p>
    </div>
    <div class="clearfix">&nbsp;</div>
  </div>


<?php
break;

case "view": // This is going to be a "new page" that is stand alone?
  dbConnect();
  $sql = "select * from rfi where id = '$rfi_id'";
  $result = mysql_query($sql);
  if (!$result) {error("A databass error has occured.\\n".mysql_error());}
  $row = mysql_fetch_object($result);
  
  
  //Format date string
  $datetouched = $row->datetouched;
  $datetouched = date('m/d/Y', strtotime($datetouched));  
  
  //Clean up text
  $subject = stripslashes($row->subject);
  $body = stripslashes($row->body);

  //Determine priority level
  switch ($row->priority) {
    case "1": $priority_level = "Low (3 days)";break;
    case "2": $priority_level = "Medium (2 days)";break;
    case "3": $priority_level = "High(24 hours)";break;
    default: $priority_level = "None";break;
  }

  //get author data
  $author = $row->author;
  $author_data = mysql_query("select * from users,companies where users.id = $author and users.company_id = companies.company_id");
  $author_row = mysql_fetch_object($author_data);
  $author_name = $author_row->fullname;
  $company_name = $author_row->company_name;

  //Fetch project information based on the project_Id
  $project_info = mysql_query("select * from projects where id = $row->project_id");
  $project_row = mysql_fetch_object($project_info);


  $path = "/var/www/vhosts/construction.charlotte-russe.com/httpdocs/rfi_files/{$row->project_id}/{$row->id}";
    if (file_exists($path))
    {
        $fs = scandir($path);
        foreach ($fs as $file)
        {
          if ($file=='.' || $file=='..')
            continue;
          $attachment_link .= '<span style="font-weight:normal;font-size:11px;margin-right:10px;"><i class="icon-file"></i><a href="download.php?file=rfi_files/'.$row->project_id.'/'.$row->id.'/'.$file.'">'.$file.'</a></span>';
        }
    }


?>
<div id="content" style="padding:10px 20px;">
  <!-- Establish structure with a breadcrumb -->  
  <a href="/">Home</a> &raquo; <a href="index.php?page=project&id=<?=$row->project_id?>">#<?=$project_row->store_number?> <?=$project_row->sitename?></a> &raquo; RFI #<?=$row->rfi_number?>
  
    
  
    <div class="well">

      <div class="pull-right">
        <?php if ($unique_user_id == $author): ?>
        <a class="btn btn-mini" href="#rfi_edit" onClick="javascript:$(this).hide();toggleBox('rfi_edit',1);"><i class="icon-pencil"></i> Edit RFI</a>&nbsp;
        <?php endif; ?>
        <?php if (($usergroup == 0) || ($unique_user_id == $author)): ?>
        <a class="btn btn-mini" onclick="return confirm('Are you sure?')" href="index.php?page=rfi&id=<?=$id?>&rmode=del&rfi_id=<?=$row->id?>"><i class="icon-trash"></i> Delete RFI</a>
        <?php endif; ?>
      </div>


      <div>
      <strong>RFI </strong>
      <span class="rfi-data">#<?=$row->auto_number?></span>
      </div>

      <div>
      <strong>Store </strong>
      <span class="rfi-data"><a href="index.php?page=project&id=<?=$row->project_id?>">#<?=$project_row->store_number?> <?=$project_row->sitename?></a></span>
      </div>


      <div>
      <strong>Subject </strong>
      <span class="rfi-data"><?=$subject?></span>
      </div>

      <div>
      <strong>From </strong>
      <span class="rfi-data"><?=$author_name?> (<?=$company_name?>)</span>
      </div>

      <div>
      <strong>Date </strong>
      <span class="rfi-data"><?=$datetouched?></span>
      </div>


      <div>
      <strong>Priority </strong>
      <span class="rfi-data"><?=$priority_level?></span>
      </div>


      <?php if($attachment_link): ?>
      <div class="rfi-attachment">
        <strong>Attachment(s) </strong> 
        <?=$attachment_link?>
      </div>
      <?php endif; ?>

      <div class="rfi-body">
        <pre><?=$body?></pre>
      </div>

        <div id="rfi_edit" class="well" style="display:none;">
        <form class="form form-horizontal" method="post" action="<?php echo "$PHP_SELF?page=rfi&rmode=edit&rfi_id=$row->id"; ?>" enctype="multipart/form-data">
          <strong>Edit RFI</strong>
          <input type="hidden" name="project_id" value="<?=$project_id?>"></input>
            <div class="control-group">
              <label class="control-label" for="edit_rfi_subject">Subject</label>
              <div class="controls">
                <input type="text" id="edit_rfi_subject" name="edit_rfi_subject" value="<?=$subject?>">
              </div>
            </div>  
            
            <div class="control-group">
              <label class="control-label" for="edit_rfi_priority">Priority</label>
              <div class="controls">
               <select class="files" id="edit_rfi_priority" name="edit_rfi_priority" onKeyPress="return noenter()">
                  <option value="<?=$row->priority?>" selected><?=$priority_level?></option>
                  <option value="1">Low (3 days)</option>
                  <option value="2">Medium (2 days)</option>
                  <option value="3">High (24 hours)</option>value="outlet">Outlet</option>
                </select>
              </div>
            </div>      

            <div class="control-group">
              <label class="control-label" for="edit_rfi_body">RFI/Questions</label>
              <div class="controls">
                <textarea id="edit_rfi_body" name="edit_rfi_body" class="full-width" rows="6"><?=$body?></textarea> 
              </div>
            </div>

            <div class="actions">
              <button class="btn pull-right" type="submit">Save Edits</button>
            </div>
            <div class="clearfix">&nbsp;</div>
          </form>
          </div>


    </div>

      

  
  <div class="well">
  <form name="single" class="form" method="post" action="<?php echo "$PHP_SELF?page=rfi&rmode=submit&rfi_id=$rfi_id&rank=c"; ?>" enctype="multipart/form-data">
    <strong>Post Response</strong>
    <input type="hidden" name="new_rfi_subject" value="<?=$subject?>"></input>
    <input type="hidden" name="parent_rfi_id" value="<?=$rfi_id?>"></input>
      <div class="control-group">
        <div class="controls">
          <textarea id="new_rfi_body" name="new_rfi_body" class="full-width" rows="4" style="width:98%"></textarea> 
        </div>
      </div>
      <div class="control-group">
        <strong>CC Email Addresses </strong> <small>Separate multiple emails by a comma.</small>
        <div class="controls">
          <input type="text" id="new_rfi_cc_addresses" name="new_rfi_cc_addresses" style="width:98%" />
        </div>
      </div>
      <?php if ($usergroup < 2): ?>
      <div>
        <a href="#" onClick="javascript:$(this).hide();toggleBox('response_vendors',1);return false;"> Add Vendors</a>
      </div>
      <div class="control-group hide" id="response_vendors">
        <strong>Add vendors </strong>
        <div class="controls">
        <?php 
        $vendors = unserialize($project_row->companyarray);
        foreach($vendors as $vendor) {
          $sql = "select * from companies where company_id = $vendor";
          $result = mysql_query($sql);
          if (mysql_result($result,0,"company_id") == 1) { continue; }
          if (mysql_result($result,0,"gc") == 1) { continue; }
          if (mysql_result($result,0,"architect") == 1) { continue; }
          $company_name = mysql_result($result,0, "company_name");
        ?>
        <label class="checkbox">
          <input type="checkbox" id="new_rfi_vendor" name="new_rfi_vendor[]" value="<?=$vendor?>" /> <?=$company_name?>
        </label>
        <?php  } ?>
        </div>
      </div>
    <?php endif; ?>
      <div>
        <a href="#" onClick="javascript:$(this).hide();toggleBox('response_attachments',1);return false;"> Add Attachments</a>
      </div>
      <div class="control-group hide" id="response_attachments">
        <strong>Add attachments </strong>
        <div class="controls">
          <div><input type="file" name="new_rfi_attachment[]" id="new_rfi_attachment"></div>
          <div><input type="file" name="new_rfi_attachment[]" id="new_rfi_attachment"></div>
          <div><input type="file" name="new_rfi_attachment[]" id="new_rfi_attachment"></div>
          <div><input type="file" name="new_rfi_attachment[]" id="new_rfi_attachment"></div>
          <div><input type="file" name="new_rfi_attachment[]" id="new_rfi_attachment"></div>
        </div>
      </div>

      <div class="actions">
        <button class="btn pull-right" type="submit">Submit Response</button>
      </div>
      <div class="clearfix">&nbsp;</div>
  </form>
  </div>



    <h2 style="margin-top:50px;">Responses</h2>
<?php //figure out results
  $sql = "select * from rfi where rfi_number = '$row->rfi_number' and rank = 'child' order by datetouched";
  $result = mysql_query($sql);
  $result_qty = mysql_num_rows($result);

  if (!$result) {error("A databass error has occured.\\n".mysql_error());  }
  
  if (mysql_num_rows($result) == 0):
    echo "<div class=\"databox\">";    
    echo "<p><strong>Notice:</strong> Currently no response has been submitted for this RFI<em>!!</em></p>";
    echo "</div>";    
  
  else:  
    while ($row = mysql_fetch_array($result)):
      $rfi_id = $row["id"];
      $project_id = $row["project_id"];
      $author = $row["author"];
      $subject = $row["subject"];
      $body = $row["body"];
      $datetouched = $row["datetouched"];
      $datetouched = date('m/d/Y', strtotime($datetouched));
      
      //get author data
      $author_data = mysql_query("select * from users,companies where users.id = $author and users.company_id = companies.company_id");
      $author_row = mysql_fetch_object($author_data);
      $author_name = $author_row->fullname;
      $company_name = $author_row->company_name;

      $path = "/var/www/vhosts/construction.charlotte-russe.com/httpdocs/rfi_files/$project_id/$rfi_id";
      if (file_exists($path))
      {
          $fs = scandir($path);
          unset($response_attachment_link);
          foreach ($fs as $file)
          {
            if ($file=='.' || $file=='..')
              continue;
            $response_attachment_link .= '<span style="font-weight:normal;font-size:11px;margin-right:10px;"><i class="icon-file"></i><a href="download.php?file=rfi_files/'.$row['project_id'].'/'.$row['id'].'/'.$file.'">'.$file.'</a></span>';
          }
      }      

?>
      <div class="well" style="margin-bottom:25px;">
        <div class="pull-right">
        <?php if (($usergroup == 0) || ($unique_user_id == $author)): ?>
          <div><a class="btn btn-mini pull-right" onclick="return confirm('Are you sure?')" href="index.php?page=rfi&id=<?=$id?>&rmode=del&rfi_id=<?=$rfi_id?>"><i class="icon-trash"></i> Delete Response</a></div>
          <div class="clearfix">&nbsp;</div>
        <?php endif; ?>
        </div>

        <div>
        <strong>From: </strong>
        <span class="rfi-data"><?=$author_name?> (<?=$company_name?>)</span>
        </div>

        <div>
        <strong>Date: </strong>
        <span class="rfi-data"><?=$datetouched?></span>
        </div>
        <?php if($response_attachment_link): ?>
        <div class="rfi-attachment">
          <strong>Attachment(s): </strong> 
          <?=$response_attachment_link?>
        </div>
        <?php endif; ?>

        <div class="rfi-body">
          <pre><?=$body?></pre>
        </div>

      </div>

<?php endwhile;  ?>

<?php endif; ?>

</div><!-- #container -->

<?php
break;









//The code for submitting a new RFI
case "submit":
dbConnect();

  //Is this a parent RFI post or a child response??
  switch ($_GET['rank']) {
    case "p":
    $rank="parent";
    //New RFI Get the project id!
    $project_id = $_POST['project_id'];
    //Fetch project information based on the project_Id
    $project_info = mysql_query("select * from projects where id = $project_id");
    $project_row = mysql_fetch_object($project_info);
    $store_number = $project_row->store_number;

    //Generate an RFI number based on the time and project id
//  $date_gen = date(His);
//  $rfi_number = $project_id."-".$date_gen;
    $increment_query = mysql_query("select count(*) from rfi where project_id = $project_id and rank='parent'");
    $increment = mysql_result($increment_query, 0,"count(*)");
    $increment++;
    $increment = sprintf("%03s",$increment);
    $rfi_number = $project_id."-".$increment; //has to be unique
    $auto_number = $store_number."-RFI-".$increment; //does not have to be unique
    break;

    case "c":
    $rank="child";
    // Get project id from this RFI number based off the hidden parent rfi_id tag
    $parent_rfi_id = $_POST['parent_rfi_id'];
    $project_id_query = mysql_query("select project_id, rfi_number, author from rfi where id = $parent_rfi_id");
    $project_id = mysql_result($project_id_query, 0,"project_id");
    $rfi_number = mysql_result($project_id_query, 0,"rfi_number");
    $auto_number = mysql_result($project_id_query, 0,"auto_number");
    $parent_author_id = mysql_result($project_id_query, 0, "author");

    $newvendors = serialize($_POST['new_rfi_vendor']);

    //Tag as a response for notification
    $notify_reply_tag = TRUE;
    break;

    default:
    $rank="child";
    break;
  }



  //Get entered data
  $newsubject = $_POST['new_rfi_subject'];

  $newbody = $_POST['new_rfi_body'];
  if(!$newbody) {error('RFI text missing!');}
  $newpriority = $_POST['new_rfi_priority'];
  
  //Trim for whitespace
  $newbody=rtrim($newbody);
  $newbody=ltrim($newbody);
  $newsubject=rtrim($newsubject);
  $newsubject=ltrim($newsubject);
  
  $summary_msg = "";
  
  
  
  //Get sitename variable for the breadcrumbs
  $project_sql = mysql_query("select sitename from projects where id = $project_id");
  $project = mysql_fetch_object($project_sql);
 
  
  
  $sql = "INSERT INTO rfi SET 
      author='$unique_user_id',
      rank = '$rank',
      rfi_number='$rfi_number',
      auto_number='$auto_number',
      project_id='$project_id',
      priority = '$newpriority',
      rfi_vendors = '$newvendors',
      subject='".mysql_real_escape_string($newsubject)."',
      body='".mysql_real_escape_string($newbody)."'";
      
  //Strip slashes for displaying
  $newsubject=stripslashes($newsubject);
  $newbody=stripslashes($newbody);
  
  //Add to the database!
  if (!mysql_query($sql)) {
    $summary_msg .= "<p>A database error occured when adding RFI to the database: </p>";
    $summary_msg .= "<p><small>".mysql_error()."</small></p>";
  } else {
    $this_id = mysql_insert_id();
    //If this is a parent, get the new RFI ID, if its a child, get the Parent's
    if ($rank == "parent") {
        $rfi_id = mysql_insert_id();
      } else {
        $rfi_id_request = mysql_query("select id from rfi where rfi_number = '$rfi_number' and rank = 'parent'");
        $rfi_id = mysql_result($rfi_id_request, 0, "id");
    }

  }
$base_dir = "/var/www/vhosts/construction.charlotte-russe.com/httpdocs/";
$upload_dir = "rfi_files";
foreach ($_FILES["new_rfi_attachment"]["error"] as $key => $error) {
 if ($error == UPLOAD_ERR_OK) {
   $tmpname = $_FILES["new_rfi_attachment"]["tmp_name"][$key];
   $filename = $_FILES["new_rfi_attachment"]["name"][$key];
    if (!file_exists($base_dir.$upload_dir))
      mkdir($base_dir.$upload_dir);
    if (!file_exists($base_dir.$upload_dir."/$project_id"))
      mkdir($base_dir.$upload_dir."/$project_id");    
    if (!file_exists($base_dir.$upload_dir."/$project_id/$this_id"))
      mkdir($base_dir.$upload_dir."/$project_id/$this_id");
    
    $path = $base_dir.$upload_dir."/$project_id/$this_id/";
    
    if (file_exists($path.$filename))
    {
      $summary_msg = "Warning: A file with this name already exists.";
    }
    else
    {
      move_uploaded_file($tmpname, $path.$filename);  
    } 
  }
}


  //Generate notification vars for $message, $comments,
  //$project and $link first since they are needed by notify.php to operate properly

  //Create strings for mail
  if ($rank == "parent") {
    $message = "$username ($usercompanyname) has posted a new RFI for #{$project->store_number}{$project->sitename}";
    $comments = "$newsubject \n\n$newbody";
  } elseif ($rank == "child") {
    $message = "$username ($usercompanyname) has replied to your RFI for #{$project->store_number}{$project->sitename}";
    $comments = "Re: $newsubject \n\n$newbody";
  }
  

  $link = "http://construction.charlotte-russe.com/index.php?page=rfi&rmode=view&rfi_id=$rfi_id";
  
  //invite vendors
  $invite_vendors = FALSE;
  
  //get cc addresses
  $new_cc_addresses = $_POST['new_rfi_cc_addresses'];

  //Call mail script
  include("notify-rfi.php");
  //Add to report
  $summary_msg .= '<p>The following notification was sent:</p>';
  $summary_msg .= '<div><pre>'.$notice_text.'</pre></div>';
?>

  <div id="content" style="padding:10px 20px;">
    <a href="/">Home</a> &raquo; <a href="index.php?page=project&id=<?=$project_id?>"><?=$project->sitename?></a> &raquo; Submit RFI
    
    <?php if($rank == "parent"): ?>
      <h1>RFI Submitted</h1>
    <?php else: ?>
      <h1>Response Submitted</h1>
    <?php endif; ?>
    
    <p><a href="<?=$PHP_SELF?>?page=rfi&rmode=view&rfi_id=<?=$rfi_id?>">View RFI</a></p>

    <div class="well">  
      <?=$summary_msg?>
    </div>

    <a href="index.php?page=project&id=<?=$project_id?>">Return to <?=$project->sitename?> project page.</a>

  </div>

<?php

break;


//The code for editing entries and updating them in db
case "edit": 
// Get project id from this RFI
  $project_id_query = mysql_query("select project_id, rfi_number from rfi where id = $rfi_id");
  $project_id = mysql_result($project_id_query, 0,"project_id");

//Get entered data
  $newsubject = $_POST['edit_rfi_subject'];
  $newbody = $_POST['edit_rfi_body'];
  $newpriority = $_POST['edit_rfi_priority'];
  $newstatus = $_POST['edit_rfi_status'];
  
//Trim for whitespace (slashes stripped during post)
  $newbody=rtrim($newbody);
  $newbody=ltrim($newbody);
  $newsubject=rtrim($newsubject);
  $newsubject=ltrim($newsubject);
  
  $summary_msg = "";
  //Add comments to the database!
  dbConnect();
  //Get sitename variable for the breadcrumbs
  $project_info = mysql_query("select sitename from projects where id = $project_id");
  $row = mysql_fetch_object($project_info);
  
  $sql = "UPDATE rfi SET
      priority = '$newpriority',
      subject = '".mysql_real_escape_string($newsubject)."',
      body = '".mysql_real_escape_string($newbody)."',
      status = '$newstatus'
      WHERE id = $rfi_id LIMIT 1";
  
  //Strip slashes for displaying
  $newsubject=stripslashes($newsubject);
  $newbody=stripslashes($newbody);
  if (!mysql_query($sql)) {
    $summary_msg .= "<p>A database error occured when adding to the database: </p>";
    $summary_msg .= "<p><small>".mysql_error()."</small></p>";

  } else {
    $summary_msg .= "<p>The RFI Entry has been edited.</p>";
    $summary_msg .= "<pre><strong>$newsubject</strong><br /><br />$newbody</pre>";
    $summary_msg .= "<p><a href=\"$PHP_SELF?page=rfi&rmode=view&rfi_id=$rfi_id\">Return to RFI page</a>";
  }
      
  //Format success report
    echo "<div id=\"content\">";
    echo "<!-- Establish structure with a breadcrumb -->";
    echo "<div class=\"databox\">";
    echo "<a href=\"/\">Home</a> &raquo; <a href=\"index.php?page=project&id=$project_id\">$row->sitename</a> &raquo; Edit RFI Entry";
    echo "</div>";
    echo "<h1>:: Submit Report</h1>";
    echo "<div class=\"databox\">";
    echo "<table width=\"100%\" align=\"center\" cellspacing=\"4\" cellpadding=\"4\" class=\"filebox\">";
    echo "<tr>";
    echo "<td width=\"70\"><img src=\"images/avatar_folder.gif\" /></td>";
    echo "<td>";
    echo "$summary_msg";
    echo "</td></tr></table>";
    echo "</div>";
echo "</div>";
break;




case "del":
  $summary_msg = "";
  
//Add comments to the database!
  dbConnect();
// Get project id from this RFI
  $project_id_query = mysql_query("select project_id, rfi_number, rank from rfi where id = $rfi_id");
  $project_id = mysql_result($project_id_query, 0,"project_id");
  $rfi_number = mysql_result($project_id_query, 0,"rfi_number");
  $rank = mysql_result($project_id_query, 0,"rank");
//Getting the site name for the breadcrumbs
  $project_info = mysql_query("select sitename from projects where id = $project_id");
  $row = mysql_fetch_object($project_info);
  
  
  $sql =  "delete from rfi where id = $rfi_id";
    
  if (!mysql_query($sql)) {
    $summary_msg .= "<p>A database error occured when adding to the database: </p>";
    $summary_msg .= "<p><small>".mysql_error()."</small></p>";

  } else {
    $rfi_id_request = mysql_query("select id from rfi where rfi_number = '$rfi_number' and rank = 'parent'");
    $rfi_id = mysql_result($rfi_id_request, 0, "id");

    $summary_msg .= "<p>RFI posting has been deleted!</p>";
    if ($rank == 'child') {
      $summary_msg .= '<p><a href="index.php?page=rfi&rmode=view&rfi_id='.$rfi_id.'">Go Back to RFI</a></p>';
    }

    $summary_msg .= "<p>Go to project page for <a href=\"index.php?page=project&id=$project_id\">$row->sitename</a></p>";
    //heres an idea.. make the back to rfi statement show the title of the rfi you are going back to???"?? easy!!! single sql ref
  }

  //Format success report
    echo '<div id="content" style="padding:10px 20px;">';
    echo "<!-- Establish structure with a breadcrumb -->";
    echo "<div class=\"databox\">";
    echo "<a href=\"/\">Home</a> &raquo; <a href=\"index.php?page=project&id=$project_id\">$row->sitename</a> &raquo; Deleting";
    echo "</div>";
    echo "<h1>RFI posting deleted</h1>";
    echo "<div class=\"databox\">";
    echo "<table width=\"100%\" align=\"center\" cellspacing=\"4\" cellpadding=\"4\" class=\"filebox\">";
    echo "<tr>";
    echo "<td width=\"70\"><img src=\"images/avatar_folder.gif\" /></td>";
    echo "<td>";
    echo "$summary_msg";
    echo "</td></tr></table>";
    echo "</div>";
    echo "</div>";
break;
}

?>
<script type="text/javascript">
  $(document).ready(function(){ 
    $('#rfi-table td').click(function() { 
      window.location = $(this).parent('tr').attr('data-rfi-url');
    });
  });
</script>
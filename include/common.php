<?php // common.php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

function getStatusLabelClass($status){
  switch($status) {
      case 'approved':
      return 'label-success';
      break;
      case 'declined':
      return 'label-important';
      break;
      case 'pending':
      return 'label-warning';
      break;
    }
  }



function sendNotification($project_id, $subject, $notification, $type = 'request') {
  global $project;
  //get GC emails   
  $gc_pm_flag = FALSE;
  
  //First, try to get the GC contact emails if added to the contact area..
  $sql = "SELECT gc_project_manager_email FROM projects WHERE id = $project_id and gc_project_manager_email IS NOT NULL";
  $result = mysqli_query($dbcnx, $sql);
  if(mysqli_result($result, 0, "gc_project_manager_email") != "") {
    $addresses[] = mysqli_result($result, 0, "gc_project_manager_email");
    $gc_pm_flag = TRUE;
  }

  //If that does not work, try to get the email by searching for the name of the project manager in the user directory
  if(!$gc_pm_flag) {
    $sql = "SELECT gc_project_manager FROM projects WHERE id = $project_id and gc_project_manager IS NOT NULL";
    $result = mysqli_query($dbcnx, $sql);
    if(mysqli_result($result, 0, "gc_project_manager") != "") {
      $gc_pm = mysqli_result($result, 0, "gc_project_manager");
      $result = mysqli_query($dbcnx, "SELECT email FROM users where company_id > 1 and fullname LIKE '$gc_pm'");
      while ($row = mysqli_fetch_array($result)) {
        $addresses[] = $row['email'];
      }
      $gc_pm_flag = TRUE;
    }
  }

  //If that does not work, try to get the email by searching for all users that match the GC company name
  if (!$gc_pm_flag) {
    $result = mysqli_query($dbcnx, "SELECT email FROM users JOIN companies ON companies.company_id = users.company_id where companies.company_name LIKE '".$project['general_contractor']."'");
    while ($row = mysqli_fetch_array($result)) {
      $addresses[] = $row['email'];
    }
  }

  //get arch emails   
  $arch_pm_flag = FALSE;
  
  //First, try to get the architect contact emails if added to the contact area..
  $sql = "SELECT architect_contact_email FROM projects WHERE id = $project_id and architect_contact_email IS NOT NULL";
  $result = mysqli_query($dbcnx, $sql);
  if(mysqli_result($result, 0, "architect_contact_email") != "") {
    $addresses[] = mysqli_result($result, 0, "architect_contact_email");
    $arch_pm_flag = TRUE;
  }

  //If that does not work, try to get the email by searching for the name of the project manager in the user directory
  if(!$arch_pm_flag) {
    $sql = "SELECT architect_contact FROM projects WHERE id = $project_id and architect_contact IS NOT NULL";
    $result = mysqli_query($dbcnx, $sql);
    if(mysqli_result($result, 0, "architect_contact") != "") {
      $arch_pm = mysqli_result($result, 0, "architect_contact");
      $result = mysqli_query($dbcnx, "SELECT email FROM users where company_id > 1 and fullname LIKE '$arch_pm'");
      while ($row = mysqli_fetch_array($result)) {
        $addresses[] = $row['email'];
      }
      $arch_pm_flag = TRUE;
    }
  }

  //If that does not work, try to get the email by searching for all users that match the GC company name
  if (!$arch_pm_flag) {
    $result = mysqli_query($dbcnx, "SELECT email FROM users JOIN companies ON companies.company_id = users.company_id where companies.company_name LIKE '".$project['architect']."'");
    while ($row = mysqli_fetch_array($result)) {
      $addresses[] = $row['email'];
    }
  }

  //get Charlotte Russe PM emails
  $cr_pm_flag = FALSE;
  // First try to get project contact emails if added to the contact area..
  $sql = "SELECT cr_project_manager_email FROM projects WHERE id = $project_id and cr_project_manager_email IS NOT NULL";
  $result = mysqli_query($dbcnx, $sql);
  if(mysqli_result($result, 0, "cr_project_manager_email") != "") {
    $addresses[] = mysqli_result($result, 0, "cr_project_manager_email");
    $cr_pm_flag = TRUE;
  }

  if(!$cr_pm_flag) {
    $sql = "SELECT cr_project_manager FROM projects WHERE id = $project_id and cr_project_manager IS NOT NULL";
    $result = mysqli_query($dbcnx, $sql);
    if(mysqli_result($result, 0, "cr_project_manager") != "") {
      $cr_pm = mysqli_result($result, 0, "cr_project_manager");
      $result = mysqli_query($dbcnx, "SELECT email FROM users where company_id = 1 and fullname LIKE '$cr_pm'");
      while ($row = mysqli_fetch_array($result)) {
        $addresses[] = $row['email'];
      }
      $cr_pm_flag = TRUE;
    }
  }

  if (!$cr_pm_flag) {
    $result = mysqli_query($dbcnx, "SELECT email FROM users where company_id = 1 and title LIKE 'project manager'");
    while ($row = mysqli_fetch_array($result)) {
      $addresses[] = $row['email'];
    }
  }

  if($type == 'approval') {
    //add procurement people to approval distribution list
    $addresses[] = 'Lance.Toerien@charlotterusse.com';
    $addresses[] = 'allison.vergara@charlotterusse.com';
    $addresses[] = 'cory.niederhaus@charlotterusse.com';
  }

  //$debug = implode("|",$addresses);

  $link = "http://construction.charlotte-russe.com/index.php?page=project&id=$project_id&showCOP=1#project-change-orders-2014";

  $body = "$notification

Use this link: $link";
  //mail('rolando.garcia@gmail.com', $subject, $body, "From:Collaboration Network COP<no-reply@charlotte-russe.com>");
  //foreach ($addresses as $address) {
    //mail($address, $subject, $body, "From:Collaboration Network COP<no-reply@charlotte-russe.com>");
  //}  
}

function mysqli_result($res,$row=0,$col=0){ 
    $numrows = mysqli_num_rows($res); 
    if ($numrows && $row <= ($numrows-1) && $row >=0){
        mysqli_data_seek($res,$row);
        $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
        if (isset($resrow[$col])){
            return $resrow[$col];
        }
    }
    return false;
}

function get_groups_array(){
  global $dbcnx;
  $groups = array();
  $query = "SELECT * from cna_groups ORDER BY group_name";
  $result = mysqli_query($dbcnx, $query) or die($query . " " . mysqli_error($dbcnx));
  if(mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)){
      $groups[] = $row;      
    }
    return $groups;
  }
}



  function get_group_role_name($key){
    switch($key) {
      case "1":
        return "System Admin";
        break;
      case "2":
        return "Corp. Real Estate and Construction";
        break;
      case "3":
        return "Ops";
        break;
      case "4":
        return "Vendor/Arch/Consultant";
        break;
      default:
        return "N/A";
        break;
    }
  }


function get_user_array_by_id($id) {
  global $dbcnx;
  $user_id = mysqli_escape_string($dbcnx, $id);
  $query = "SELECT * FROM cna_users WHERE id = $user_id";
  $result = mysqli_query($dbcnx, $query) or die($query . " " . mysqli_error($dbcnx));
  if(mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    return $user;
  }
}

function get_group_array_by_id($id) {
  global $dbcnx;
  $group_id = mysqli_escape_string($dbcnx, $id);
  $query = "SELECT * FROM cna_groups WHERE id = $group_id";
  $result = mysqli_query($dbcnx, $query) or die("get_group_array_by_id " .$query . " " . mysqli_error($dbcnx));
  if(mysqli_num_rows($result) > 0) {
    $group = mysqli_fetch_assoc($result);
    return $group;
  }
}


function get_group_name_by_id($id) {
  global $dbcnx;  
  $group_id = mysqli_escape_string($dbcnx, $id);
  $query = "SELECT * FROM cna_groups WHERE id = $group_id";
  $result = mysqli_query($dbcnx, $query) or die("get_group_name_by_id " . $query . " " . mysqli_error($dbcnx));
  if(mysqli_num_rows($result) > 0) {
    $group = mysqli_fetch_assoc($result);
    return $group['group_name'];
  } else {
    return 'n/a';
  }
}

function require_admin_rights() {
  $user_id = $_SESSION['unique_user_id'];
  $user = get_user_array_by_id($user_id);
  $group = get_group_array_by_id($user['user_group']);
  if($group['group_role'] != 1)
    die('You do not access to this page.');
}


function send_response($msg, $insert_id=0) {
  $response = new stdClass();
  $response->msg = $msg;
  $response->insert_id = $insert_id;
  header('Content-Type: application/json');
  exit(json_encode($response));
}


function send_invite($user_email, $user_pass) {
    $message = "Greetings,

You are invited to join the Charlotte Russe collaboration network app.

To login, go to https://cna-app.retailamp.net and login using:

    Email: $user_email
    Password: $user_pass  

For security reasons, your password was randomly generated. You can change it anytime. Please contact support@retailamp.net if you need any help.

[This was an automated message]
";

  mail($user_email,"Welcome to Retail Amplified", $message, "From:Retail Amplified <no-reply@retailamp.net>");
}


function deldir($dir)
{
  $handle = opendir($dir);
  while (false!==($FolderOrFile = readdir($handle)))
  {
     if($FolderOrFile != "." && $FolderOrFile != "..") 
     {  
       if(is_dir("$dir/$FolderOrFile")) 
       { deldir("$dir/$FolderOrFile"); }  // recursive
       else
       { unlink("$dir/$FolderOrFile"); }
     }  
  }
  closedir($handle);
  if(rmdir($dir))
  { $success = true; }
  return $success;  
} 

function thumbnail($image_path,$thumb_path,$image_name,$maxwidth, $maxheight) 
{ 


    $src_img = imagecreatefromjpeg("$image_path/$image_name"); 
    $origw=imagesx($src_img);
  $origh=imagesy($src_img);
  
  $new_w = min($maxwidth, $origw);
  $new_h = min($maxheight, $origh);
  $prop = $origw / $origh;
  if ($prop > 1) {
    $new_h = $origh * $new_w / $origw;
  } elseif ($prop < 1) {
    $new_w = $origw * $new_h / $origh;
  }    
  //create new image
    $dst_img = imagecreatetruecolor($new_w,$new_h);
    imagecopyresized($dst_img,$src_img,0,0,0,0,$new_w,$new_h,imagesx($src_img),imagesy($src_img)); 

    imagejpeg($dst_img, "$thumb_path/$image_name"); 
    return true; 
} 


function error($msg) {
    ?>
    <html>
    <head>
    <script language="JavaScript">
    <!--
        alert("<?=$msg?>");
        history.back();
    //-->
    </script>
    </head>
    <body>
    </body>
    </html>
    <?php
    exit;
}
function restrict($msg) {
    ?>
    <html>
    <head>
    <script language="JavaScript">
    <!--
        alert("<?=$msg?>");
        window.close();
    //-->
    </script>
    </head>
    <body>
    </body>
    </html>
    <?php
    exit;
}
function dateconvert($date2convert)
{
    $year = substr( $date2convert, 0, 4 );
    $month = substr( $date2convert, 5, 2 );
    $day = substr( $date2convert, 8, 2 );
  $formatteddate = "$month/$day/$year";

if ($formatteddate == "00/00/0000")
  $formatteddate = "-";

    return $formatteddate;
}

function revertTimeStamp($timestamp) {  
  $flag = 0;
    $year=substr($timestamp,0,4);  
    $month=substr($timestamp,4,2);  
    $day=substr($timestamp,6,2);  
    $hour=substr($timestamp,8,2);  
    $minute=substr($timestamp,10,2);  
  $limit = 12;
  if ($hour > 3) {
  $hour= $hour - 3;
  } else {
    $hour = 9 + $hour;
    $day = $day - 1;
    $flag++;
  }
    
  
  if ($hour > $limit) {
    $hour = $hour - 12;
    $meridian = "PM";
  } elseif ($hour == $limit) {
    $meridian = "PM";
  } else if ($flag == 1) {
    $meridian = "PM";
  } else {
    $meridian = "AM";
  }

    $newdate = "$month/$day/$year $hour:$minute $meridian";  
    RETURN ($newdate);  
   }  

function format_file_size($size) { 
  $megabyte = 1024 * 1024; 
    if ($size > $megabyte) { /* literal.float */ 
      $re_sized = sprintf("%01.2f", $size / $megabyte) . " Mb"; 
    } elseif ($size > 1024) { 
      $re_sized = sprintf("%01.2f", $size / 1024) . " Kb"; 
    } else { 
      $re_sized = $size . " bytes"; 
    } 
  return $re_sized; 
}

function countfiles($x) {
    $mydir = opendir($x) ;
    $exclude = array( ".", "..", ".htaccess", "thumbs") ;
    $filecount = 0;
    while($fn = readdir($mydir))
    {
      if ($fn == ".archived") { return "A";}
          if ($fn == $exclude[0] || $fn == $exclude[1] || $fn == $exclude[2] || $fn==$exclude[3]) continue;
      $filecount++; 
    }
    return $filecount;
}

function scan_dir($dirname,$recurse,$sort_flag)
{
 if($dirname[strlen($dirname)-1]!='/')
 {$dirname.='/';}
 static $file_array=array();
 static $dir_array=array();
 static $ret_array=array();
 $handle=opendir($dirname);
while (false !== ($file = readdir($handle)))
 {
 if($file=='.'||$file=='..'||$file=='.htaccess'||$file=='photos'||$file=='thumbs')
 continue;
 if(is_dir($dirname.$file))
     {
       $dir_array[]=$file;
       if($recurse)
       {
         scan_dir($dirname.$file.'/',$recurse);
       }
     }
 else
 $file_array[]=$file;
 }
 closedir($handle);

 sort($file_array,$sort_flag);
 sort($dir_array,$sort_flag);

 reset($file_array);
 reset($dir_array);

 $ret_array['files']=$file_array;
 $ret_array['directories']=$dir_array;

 return $ret_array;
 
}

function checkname($element) {
  return !preg_match ("/[^A-z0-9.\-\ \_]/", $element);
}

function myfiletype($value) 
{
    //$ext=substr($value, strpos($value,".") + 1, strlen($value));
    $ext = pathinfo($value, PATHINFO_EXTENSION);

    switch($ext)
    {
      case "DWG":
      case "dwg":
        $type="DWG";
        break;
      case "DOC":
      case "doc":
        $type="Word";
        break;
      case "XLS":
      case "xls":
      case "xlsx";
        $type="Excel";
        break;
      case "JPG":
      case "jpg":
      case "GIF":
      case "gif":
      case "BMP":
      case "bmp":
      case "TIF":
      case "tif":
        $ext=strtoupper($ext);
        $type="$ext Image";
        break;
      case "ZIP":
      case "zip":
        $type="ZIP";
        break;
      case "TXT":
      case "txt":
        $type="Text";
        break;
      case "pdf":
      case "PDF":
        $type="PDF";
        break;
      default:
        $type = strtoupper($ext);
        break;
    }
    return $type;
}

?>
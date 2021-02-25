<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include("include/access.php");

function createThumbnail($imageName,$newWidth,$newHeight,$uploadDir,$moveToDir){
    $path = $uploadDir . '/' . $imageName;

    $mime = getimagesize($path);

    if($mime['mime']=='image/png'){ $src_img = imagecreatefrompng($path); }
    if($mime['mime']=='image/jpg'){ $src_img = imagecreatefromjpeg($path); }
    if($mime['mime']=='image/jpeg'){ $src_img = imagecreatefromjpeg($path); }
    if($mime['mime']=='image/pjpeg'){ $src_img = imagecreatefromjpeg($path); }

    $old_x = imageSX($src_img);
    $old_y = imageSY($src_img);

    if($old_x > $old_y)
    {
        $thumb_w    =   $newWidth;
        $thumb_h    =   $old_y/$old_x*$newWidth;
    }

    if($old_x < $old_y)
    {
        $thumb_w    =   $old_x/$old_y*$newHeight;
        $thumb_h    =   $newHeight;
    }

    if($old_x == $old_y)
    {
        $thumb_w    =   $newWidth;
        $thumb_h    =   $newHeight;
    }

    $dst_img        =   ImageCreateTrueColor($thumb_w,$thumb_h);

    imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);


    // New save location
    $new_thumb_loc = $moveToDir . $imageName;

    if($mime['mime']=='image/png'){ $result = imagepng($dst_img,$new_thumb_loc,8); }
    if($mime['mime']=='image/jpg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
    if($mime['mime']=='image/jpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
    if($mime['mime']=='image/pjpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }

    imagedestroy($dst_img);
    imagedestroy($src_img);
    return $result;
}

if(isset($_POST['action'])) {  

  if($_POST['action'] == 'move_uploaded_file') {
    $project_id = $_REQUEST['project_id'];
    $folder_type = $_REQUEST['folder_type'];
    $folder_name = $_REQUEST['folder_name'];
    $file_name = $_REQUEST['file_name'];
    $uuid = $_REQUEST['uuid'];
  
    $source = realpath(dirname(__FILE__))."/data/$uuid/$file_name";
    $source_folder = realpath(dirname(__FILE__))."/data/$uuid";

    $destination_folder = realpath(dirname(__FILE__))."/files/$project_id/$folder_type/$folder_name/";
    if (!file_exists($destination_folder)) {
      mkdir($destination_folder, 0777, true);
    }

    if(file_exists($source) && file_exists($destination_folder)) {
      //Generate thumbs if photos
      if($folder_type == 'photos'){
        $thumbs_folder = $destination_folder."/.thumbs/";
        if(!file_exists($thumbs_folder)){
          mkdir($thumbs_folder, 0777, true);   
        }
        createThumbnail($file_name,150,150,$source_folder,$thumbs_folder);
        createThumbnail($file_name,1200,1200,$source_folder,$destination_folder);
      } else {
        rename($source, $destination_folder."/$file_name");        
      }
      rmdir($source_folder);
    }
  }

  if($_POST['action'] == 'move_uploaded_rfi_attachment') {
    $project_id = $_REQUEST['project_id'];
    $rfi_id = $_REQUEST['rfi_id'];
    $file_name = $_REQUEST['file_name'];
    $uuid = $_REQUEST['uuid'];
  
    $source = realpath(dirname(__FILE__))."/data/$uuid/$file_name";
    $source_folder = realpath(dirname(__FILE__))."/data/$uuid";

    $project_rfi_folder = realpath(dirname(__FILE__))."/files/$project_id/rfi/";
    if (!file_exists($project_rfi_folder)) {
      mkdir($project_rfi_folder, 0777, true);
    }

    $destination_folder = realpath(dirname(__FILE__))."/files/$project_id/rfi/$rfi_id/";
    if (!file_exists($destination_folder)) {
      mkdir($destination_folder, 0777, true);
    }

    if(file_exists($source) && file_exists($destination_folder)) {
      rename($source, $destination_folder."/$file_name");
      rmdir($source_folder);
    }
  }


  if($_POST["action"] == "insert_file_folders_db_row") {
    $project_id = mysqli_escape_string($dbcnx, $_REQUEST['project_id']);
    $folder_type = mysqli_escape_string($dbcnx, $_REQUEST['folder_type']);
    $folder_name = mysqli_escape_string($dbcnx, $_REQUEST['folder_name']);
    $description = mysqli_escape_string($dbcnx, $_REQUEST['description']);    
    $author_id = mysqli_escape_string($dbcnx, $_REQUEST['author_id']);    
    $groups_array = $_REQUEST['groups'];
    $groups = mysqli_escape_string($dbcnx, json_encode($groups_array));

    $sql = "INSERT INTO file_folders (project_id, type, name, description, author_id, groups)
    VALUES ($project_id, '$folder_type', '$folder_name', '$description', $author_id, '$groups')";
    $_process = mysqli_query($dbcnx, $sql) or die ("Error inserting this query:" . $sql);  
  }

}
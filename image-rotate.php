<?php
//scan for folders and files that are NOT photos and NOT system folders or files
if(!isset($_REQUEST['id'])) {
  die('Error, no id set');
}

$name = $_REQUEST['name'];
$id = $_REQUEST['id'];
$d = $_REQUEST['d'];


$filespace = "filespace/$id/photos/$name/";
$files = array();
$dirs  = array();
$handle = opendir($filespace);



if(isset($_REQUEST['img'])) {
  $img = $_REQUEST['img'];
  //Single file rotate
  $image = $filespace.$img;
  // Create the canvas
  $source = imagecreatefromjpeg($image) ;
  // Rotates the image
  $rotate = imagerotate($source, $d, 0) ;
  // Outputs a jpg image, you could change this to gif or png if needed
  imagejpeg($rotate, $image);
} else {
  //Rotate entire album
  while (false !== ($file = readdir($handle))) {
      if($file=='.'||$file=='..'||$file=='.htaccess'||$file=='thumbs')
        continue;
      if(is_dir($filespace.$file))
        $dirs[]=$file;
      else
        $files[]=$file;
    }
    closedir($handle);
    //Sort arrays in natural order and reset pointer to first entry
    sort($dirs, SORT_REGULAR);
    sort($files, SORT_REGULAR);
    reset($dirs);
    reset($files);

    //Now do files in root!
    foreach($files as $key=>$value) {    
      $image = $filespace.$value;
      // Create the canvas
      $source = imagecreatefromjpeg($image) ;
      // Rotates the image
      $rotate = imagerotate($source, $d, 0) ;
      // Outputs a jpg image, you could change this to gif or png if needed
      imagejpeg($rotate, $image);
    }
  }

  header('Location:/index.php?page=gallery&id='.$id.'&name='.$name);
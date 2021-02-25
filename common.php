<?php // common.php
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
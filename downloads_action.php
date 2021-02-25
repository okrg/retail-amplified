<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include("include/access.php");

if(isset($_POST["action"])) {
  
  if($_POST["action"] == "fetch_folders") {
    $project_id = mysqli_real_escape_string($dbcnx, $_POST['project_id']);
    $folder_type = mysqli_real_escape_string($dbcnx, $_POST['folder_type']);
    $query = "select * from file_folders 
    WHERE project_id = $project_id 
    AND type = '$folder_type'";
    
    $result = mysqli_query($dbcnx, $query) or die (mysqli_error($dbcnx));
    $metas = array();
    while ($row = $result->fetch_assoc()) {
      $metas[] = $row;
    }
    mysqli_free_result($result);
    
    $glob_path = realpath(dirname(__FILE__))."/files/$project_id/$folder_type/*";
    $folder_path = realpath(dirname(__FILE__))."/files/$project_id/$folder_type";
    
    if (!file_exists($folder_path)) {
      mkdir($folder_path, 0777, true);
    }
    
    $folders = array_filter(glob($glob_path), 'is_dir');
    
    if(count($folders) > 0) {
    foreach($folders as $folder_name) { 
      $props = array();
      $props['has_meta'] = FALSE;
      $props['name'] = basename($folder_name);
      $props['mtime'] = date ("M d, Y h:i A", filemtime($folder_name));
      foreach($metas as $meta){
        if($props['name'] == $meta['name'] ) {
          $props['has_meta'] = TRUE;
          $props['description'] = $meta['description'];
          $props['groups'] = $meta['groups'];
          $props['author'] = get_user_fullname_by_id($meta['author_id']);
          continue;
        }
      }
    ?>
      <div class="folder-pane closed dl-folder" 
      data-project-id="<?=$project_id?>" 
      data-folder-type="<?=$folder_type?>" 
      data-folder-name="<?=$props['name'];?>">
        <div class="folder-pane-header"><?=$props['name'];?></div>
        <div class="folder-pane-content">

          <div class="folder-info">
            <div class="dl-folder-desc mb-2">
              <?=$props['description']?>
            </div>
            
            <div class="file-meta">
              <?php if(!empty($props['author'])):?>
              Uploaded by <?=$props['author']?> &bull;
              <?php endif; ?>            
              <span><?=$props['mtime']?></span>              
            </div>

            <?php if(!empty($props['groups'])):?>
            <div class="file-meta">
              Shared with vendors <?=$props['groups']?>
            </div>
            <?php endif; ?>

          </div>

          <div class="folder-info my-3">
            <button class="btn btn-secondary btn-sm dl-folder-download" 
            data-project-id="<?=$project_id?>" 
            data-folder-type="<?=$folder_type?>" 
            data-folder-name="<?=$props['name'];?>">
              Download All
            </button>
          </div>
          <div class="list-group dl-folder-list"></div>          
        </div>
      </div>

    <?php
    }    
    }
  }

  if($_POST["action"] == "fetch_files") {
    $project_id = mysqli_real_escape_string($dbcnx, $_POST['project_id']);
    $folder_type = mysqli_real_escape_string($dbcnx, $_POST['folder_type']);
    $folder_name = mysqli_real_escape_string($dbcnx, $_POST['folder_name']);
    $path = realpath(dirname(__FILE__))."/files/$project_id/$folder_type/$folder_name";
    $get_path = "/files/$project_id/$folder_type/$folder_name";
    $file_data = scandir($path);

    foreach($file_data as $file) {
      if($file === '.' or $file === '..') {
        continue;
      } else {
        $filesize = filesize("$path/$file");        
      ?>
        <a href="#" class="list-group-item list-group-item-action file" 
        data-project-id="<?=$project_id?>" 
        data-folder-type="<?=$folder_type?>" 
        data-folder-name="<?=$folder_name?>" 
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
 }



  if($_POST["action"] == "fetch_photo_albums") {
    $project_id = mysqli_real_escape_string($dbcnx, $_POST['project_id']);    
    
    $query = "select * from file_folders 
    WHERE project_id = $project_id 
    AND type = 'photos'";
    
    $result = mysqli_query($dbcnx, $query) or die (mysqli_error($dbcnx)); 
    $metas = array();
    while ($row = $result->fetch_assoc()) {
      $metas[] = $row;
    }
    mysqli_free_result($result);

    $glob_path = realpath(dirname(__FILE__))."/files/$project_id/photos/*";
    $folder_path = realpath(dirname(__FILE__))."/files/$project_id/photos";
    
    if (!file_exists($folder_path)) {
      mkdir($folder_path, 0777, true);
    }
    $folders = array_filter(glob($glob_path), 'is_dir');
    
    if(count($folders) > 0) {
    foreach($folders as $folder_name) { 
      $props = array();
      $props['has_meta'] = FALSE;
      $props['name'] = basename($folder_name);
      $props['mtime'] = date ("M d, Y h:i A", filemtime($folder_name));
      foreach($metas as $meta){
        if($props['name'] == $meta['name'] ) {
          $props['has_meta'] = TRUE;
          $props['description'] = $meta['description'];
          $props['groups'] = $meta['groups'];
          $props['author'] = get_user_fullname_by_id($meta['author_id']);
          continue;
        }
      }
    ?>
      <div class="album-pane closed dl-album" 
      data-project-id="<?=$project_id?>"       
      data-album-name="<?=$props['name'];?>">
        <div class="album-pane-header"><?=$props['name'];?></div>
        <div class="album-pane-content">

          <div class="folder-info">
            <div class="dl-folder-desc mb-2">
              <?=$props['description']?>
            </div>
            
            <div class="file-meta">
              <?php if(!empty($props['author'])):?>
              Uploaded by <?=$props['author']?> &bull;
              <?php endif; ?>            
              <span><?=$props['mtime']?></span>              
            </div>

            <?php if(!empty($props['groups'])):?>
            <div class="file-meta">
              Shared with vendors <?=$props['groups']?>
            </div>
            <?php endif; ?>

          </div>

          <div class="folder-info my-3">
            <button class="btn btn-secondary btn-sm dl-album-download" 
            data-project-id="<?=$project_id?>" 
            data-album-name="<?=$props['name'];?>">
              Download All
            </button>
          </div>
          <div class="dl-album-list"></div>          
        </div>
      </div>

    <?php
    }    
    }
  }

  if($_POST["action"] == "fetch_photos") {
    $project_id = mysqli_real_escape_string($dbcnx, $_POST['project_id']);    
    $album_name = mysqli_real_escape_string($dbcnx, $_POST['album_name']);
    $path = realpath(dirname(__FILE__))."/files/$project_id/photos/$album_name";
    
    $get_path = "/files/$project_id/photos/$album_name";
    $thumb_path = "/files/$project_id/photos/$album_name/.thumbs";
    $file_data = scandir($path);    

    foreach($file_data as $file) {
      if($file === '.' or $file === '..' or $file === '.thumbs') {
        continue;
      } else {
        $filesize = filesize("$path/$file");
        $thumb = "$thumb_path/$file";
        $full = "$get_path/$file";
      ?>

        <figure class="figure">
          <a href="<?=$full?>" data-toggle="lightbox" data-gallery="<?=$album_name?>">
            <img src="<?=$thumb?>" class="figure-img img-fluid" />
          </a>  
          <figcaption class="figure-caption">
            <a href="<?=$full?>" data-toggle="lightbox" data-gallery="text-<?=$album_name?>"><?=$file?></a>
            <div class="file-meta">
              <?=format_file_size($filesize)?>
            </div>
          </figcaption>
        
      </figure>
      <?php
      }
    }
 }

}
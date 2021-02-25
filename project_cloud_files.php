<?php
	$pid = $id;
	$files = array();
	$dirs  = array();
	//See if the user has the plans role
	$rsql = "select roles from companies where company_id = $usercompany";
	$rq = mysql_query($rsql);
	$ro = mysql_result($rq,0,"roles");
	$roles =  explode(",",$ro);
	$c[1] = 'Default';
	
	parse_str($_SERVER['QUERY_STRING'], $qs);
	

	//set default filter to dwg
	if(!isset($_GET['f'])) {$_GET['f'] = 'dwg';}
	
	
	$conn = cloudConnect();

	if($_GET['f'] == 'img') {
	
	try {
			$thumbs = $conn->get_container($subdomain . '.' . $pid . '.thumbs');      
		} catch (Exception $e) {
			$thumbs = $conn->create_container($subdomain . '.' . $pid . '.thumbs');      
			$thumbs->make_public();      
		}		
    
    try {			
      $previews = $conn->get_container($subdomain . '.' . $pid . '.previews');
		} catch (Exception $e) {
      $previews = $conn->create_container($subdomain . '.' . $pid . '.previews');
      $previews->make_public();
		}		    
	}
	
	
	
	
	if($conn) {
		try {
			$container = $conn->get_container($subdomain . '.' . $pid . '.' . $_GET['f']);
		} catch (Exception $e) {
			$container = $conn->create_container($subdomain . '.' . $pid . '.' . $_GET['f']);
		}
	}
	

	function mkcloudimg($tempname, $filename, $type) {
		global $conn;
		global $subdomain;
		global $pid;
		$stype = explode(".", $filename);
		$stype = $stype[count($stype)-1];
		switch($stype) {
			case 'gif':
				$simg = imagecreatefromgif($tempname);
				break;
			case 'jpg':
				$simg = imagecreatefromjpeg($tempname);
				break;
			case 'png':
				$simg = imagecreatefrompng($tempname);
				break;
		}
		
		$size = getimagesize($tempname);
    if($type == 'thumbs') {
      $nw = 150; 
      $nh = 100;
    } elseif($type == 'previews') {
      $nw = 960; 
      $nh = 640;    
    }
		$w = $size[0];
		$h = $size[1];
		$dimg = imagecreatetruecolor($nw, $nh);
		
		$wm = $w/$nw;
		$hm = $h/$nh;
		$h_height = $nh/2;
		$w_height = $nw/2;
		if($w> $h) {
			$adjusted_width = $w / $hm;
			$half_width = $adjusted_width / 2;
			$int_width = $half_width - $w_height;
			imagecopyresampled($dimg,$simg,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h);
		} elseif(($w <$h) || ($w == $h)) {
			$adjusted_height = $h / $wm;
			$half_height = $adjusted_height / 2;
			$int_height = $half_height - $h_height;			 
			imagecopyresampled($dimg,$simg,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h);
		} else {
			imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h);
		}
		$tmpfname = tempnam("/tmp", $type);
		imagejpeg($dimg,$tmpfname,70);
		
		try {
			try {
				$thumbs = $conn->get_container($subdomain . '.' . $pid . '.' . $type);
			} catch (Exception $e) {
				$thumbs = $conn->create_container($subdomain . '.' . $pid . '.' . $type);
				$thumbs->make_public();
			}			
			$thumb = $thumbs->create_object($filename);
			$thumb->load_from_filename($tmpfname);
			$thumb->metadata = array("Parent" => $filename);
			$thumb->sync_metadata();
			
			return true;
		} catch (Exception $e) {
			print 'Exception caught: ' . $e->getMessage() ;		
			return false;
		}
	}
	
	function cloudls($folder) {	
		global $container;
		global $c;
		global $cf;
	//List objects	
		try {
			$list = $container->get_objects(0,NULL,NULL,$folder);
		} catch (Exception $e) {
			print 'Exception caught: ' . $e->getMessage() ;		
		}

		$i=0;
		foreach($list as $obj) {
			$object = $container->get_object($obj->name);
			if( isset($cf) && $object->metadata['Companyid'] != $cf) {continue;}
			if($object->name == 'root') {continue;}			
			
			
			if(!array_key_exists($object->metadata['Companyid'], $c)) {
				$c[$object->metadata['Companyid']] = $object->metadata['Company'];
			}
			
			if($folder == '') {
				if($object->content_type != 'application/directory') {continue;}
				//count objects inside this dir..
				$ls = $container->get_objects(0,NULL,NULL, $obj->name);
				$result[$i]['count'] = count($ls);
			}			
			
			
			$result[$i]['name'] = $object->name;
			$result[$i]['author'] = urldecode($object->metadata['Author']);
			$result[$i]['comment'] = urldecode($object->metadata['Comment']);				
			$result[$i]['date'] = $object->last_modified;
			$result[$i]['type'] = $object->content_type;
			$result[$i]['length'] = $object->content_length;									
			$i++;
		}
		
		
		if($result){
			sort($result, SORT_REGULAR);
			reset($result);
			return $result;		
		}
	}	
	
	function render_vlist($id) {
		$sql = "select companyarray from projects where id = $id";
		$result = mysql_query($sql);
		$companyarray = mysql_result($result,0,"companyarray");
		$companyarray = unserialize($companyarray);
		
		if($companyarray) {
			print '<a href="#" class="btn small select-all-vendors">Select All</a>';
			print '<ul>';
			foreach($companyarray as $companyID) {
				
				$vres = mysql_query("select * from companies where company_id = $companyID");
				$row = mysql_fetch_array($vres);
				if($row) {			
					print '<li class="vendorlist">';
					print '<label style="background-color:#{' . $row["color"] . '};">';
					print '<input title="' . $row["cat"] . '" type="checkbox"';
					print '	id="ck1' . $row["company_id"] .'" value="' . $row["company_id"] . '"';
					print '	name="vendors[]" />';
					print '&nbsp;<span>' . $row["company_name"] . '</span>';
					print '</label>';
					print '</li>';
				}
			}
			print '</ul>';
			mysql_free_result($result);
		} else {
		
			print 'No vendors assigned to this project.';
		}		
	}

	function exapp($name, $category) {
		global $conn;
		global $subdomain;
		global $pid;
		global $msg;
		global $exapp;
		//load the category container
		try {
			$container = $conn->get_container($subdomain . '.' . $pid . '.' . $category);
		} catch (Exception $e) {
			$container = $conn->create_container($subdomain . '.' . $pid . '.' . $category);
		}		
		
		//determine if this marker already exists..
		try {
			$obj = $container->get_object($name);
			$exists = TRUE;
			//if it does, append underscore			
		} catch (Exception $e) {		
			//if exception, that means file does NOT exist
			$exists = FALSE;
		}
		if($exists) {$msg .= "<p><strong>Warning:</strong> Filename already exists. An underscore was added to your file</p>";}
		while($exists == TRUE) {			
			$path = explode('/', $name);			
			$name = $path[0] . "/_" . $path[1];
			//determine if this marker already exists..
			try {
				$obj = $container->get_object($name);
				$exists = TRUE;
				//if it does, append underscore			
			} catch (Exception $e) {		
				//if exception, that means file does NOT exist
				$exists = FALSE;				
			}			
			
		}
		
		return $name;			
	}
	
	function mkclouddir($newfolder, $category) {
		global $conn;
		global $username;
		global $newcomment;
		global $usercompany_name;
		global $usercompany;
		global $unique_user_id;
		global $subdomain;
		global $pid;
		
		//load the category container
		try {
			$container = $conn->get_container($subdomain . '.' . $pid . '.' . $category);
		} catch (Exception $e) {
			$container = $conn->create_container($subdomain . '.' . $pid . '.' . $category);
		}
		
		
		//determine if this marker already exists..
		try {
			$obj = $container->get_object($newfolder);
			$exists = TRUE;
			//if it does, append underscore			
		} catch (Exception $e) {		
			//if exception, that means file does NOT exist
			$exists = FALSE;
		}
		
		if(!$exists) {
			//Create a folder marker
			try {
				$marker = $container->create_object($newfolder);
				$marker->content_type = "application/directory";
				$marker->write('',0);
				$msg .= 'Cloud folder created';
				$marker->metadata = array( 
					"Author" => $username,
					"Comment" => $newcomment,
					"Company" => $usercompany_name,
					"CompanyID" => $usercompany,
					"AuthorUID" => $unique_user_id
					);
				$marker->sync_metadata();
			} catch(Exception $e) {
				$msg .= $e->getMessage();
			}		
		}
		return $msg;
	}
	
	//if delete====================================================
	if(isset($_POST['rm'])) {		
		try {
			$container->delete_object($_POST['path']);
			$msg .= '<p><strong>File has been deleted</strong></p>';			
			$msg_class = 'success';
		} catch (Exception $e) {			
			$msg .= 'Exception caught: ' .  $e->getMessage() . "\n";
			$msg_class = 'error';
		}
	}
	
	
	if(isset($_POST['upload_type'])) {
	
	$newcomment = ltrim(rtrim($_POST['comment']));
	$newfolder = ltrim(rtrim($_POST['folder']));
	
	$category = $_POST['category'];
	
	
	switch($_POST['upload_type']) {
	
		//if multi upload==============================================
		case "regular":			

		//load the category container
		try {
			$container = $conn->get_container($subdomain . '.' . $pid . '.' . $category);
		} catch (Exception $e) {
			$container = $conn->create_container($subdomain . '.' . $pid . '.' . $category);
		}
		
		if($newfolder) {
			if($_POST['merge']){
				//do nothing
			} else {			
				$newfolder = exapp($newfolder, $category);				
				mkclouddir($newfolder, $category);				
			}
			
			$folder = $newfolder;
		} else {
			$folder = 'root';
			//Create a root folder marker if it does not exist
			try {
				$root = $container->get_object('root');			
			} catch (Exception $e) {
				$marker = $container->create_object('root');
				$marker->content_type = "application/directory";
				$marker->write('',0);
			}			
			
		}
		
		for($i=0; $i<count($_FILES['userfile']['tmp_name']); $i++) { 
			$tempname = $_FILES['userfile']['tmp_name'][$i]; 
			$filename = $_FILES['userfile']['name'][$i]; 
			if($tempname != "") {
				$filename = exapp($folder.'/'.$filename, $category);
				try {
					$object = $container->create_object($filename);
					$object->load_from_filename($tempname);
					$files_uploaded .= "$filename\n";
					$msg .= "<p>Uploaded: $filename from $tempname</p>";
					$msg_class = 'success';
					$object->metadata = array( 
						"Author" => $username,
						"Comment" => $newcomment,
						"Company" => $usercompany_name,
						"CompanyID" => $usercompany,
						"AuthorUID" => $unique_user_id
						);
					$object->sync_metadata();
					if($_POST['category'] == 'img') {
						mkcloudimg($tempname, $filename, 'thumbs');
            mkcloudimg($tempname, $filename, 'previews');            
					}
				} catch (Exception $e) {
					$msg .= 'Exception caught: ' .  $e->getMessage() . "\n";
					$msg_class = 'error';
				}		
			}
		}		

		if (isset($_POST['vendors'])) {			
			$message = "$username has created a new project folder: $newfolder \n\n";
			$message .= "Adding the following project files:\n";
			$message .= $files_uploaded;			
			$comments = $newcomment;
			$newfolderformmated = str_replace(" ", "%20", $newfolder);		
			$link = "http://".$site_URL."/index.php?page=folder&id=$id&name=$newfolderformmated";
		}
		
		break;
	
	//if zip upload================================================
		case 'zip':			
		//Establish location name variables
		$newdir = "./filespace/$id/".$newfolder;

		//Validate to make sure folder does not already exist!
		if (file_exists($newdir)) {
			$msg .= "<p><strong>Notice:</strong> Folder of that name already exists.";
			$msg .= "An underscore was added to the name you entered...</p>";
			while (file_exists($newdir)) {
			$newfolder = "_".$newfolder;
			$newdir = "./filespace/$id/".$newfolder;
			}
		}	
		//Create folder!	
		if (!file_exists($newdir)) {
			mkdir($newdir,0777);
		}
		
		
		$tempname = $_FILES['userfile']['tmp_name'][0];
		$filename = $_FILES['userfile']['name'][0];
		if (!is_uploaded_file($tempname)) {
			unlink($tempname);
			$msg .= "<p>Uploaded failed! Please try again...";
			$msg .= "if you continue to get this message send this text to the administrator: </p>";
			$msg .= "<pre>".print_r($_FILES, true)."</pre>";
			$msg_class = 'error';
		} else {
			// Move it to the real location
			move_uploaded_file($tempname, $newdir.'/'.$filename);

			include('pclzip.lib.php');
			$archive = new PclZip($newdir . '/' . $filename);
			if ($archive->extract(PCLZIP_OPT_PATH, $newdir) == 0) {
				die("Error : ".$archive->errorInfo(true));
			}
			$msg .= "<p>ZIP file successfully uploaded and extracted:</p>";
			
			//Delete the zip archive		
			unlink($newdir.'/'.$filename);
			
			if($_POST['merge']){
				//do nothing
			} else {			
				$newfolder = exapp($newfolder, $category);
				mkclouddir($newfolder, $category);				
			}

			
			//List files in new folder		
			$zip_files = array();
			$zip_dirs = array();
			$zip_handle = opendir($newdir);

			while (false !== ($zip_file = readdir($zip_handle))) {
				if($zip_file=='.'||$zip_file=='..'||$zip_file=='.htaccess')
					continue;
				if(is_dir($newdir.'/'.$zip_file))
					$zip_dirs[]=$zip_file;
				else
					$zip_files[]=$zip_file;
			}

			closedir($zip_handle);
			
			if(count($zip_dirs) > 0) { //this zip contained folders and is invalid!!!		
				function rrmdir($newdir) {
					if (is_dir($newdir)) { 
						$zip_objects = scandir($newdir); 
						foreach ($zip_objects as $zip_object) { 
							if ($zip_object != "." && $zip_object != "..") { 
								if (filetype($newdir."/".$zip_object) == "dir") {
									rrmdir($newdir."/".$zip_object); 
								} else {
									unlink($newdir."/".$zip_object); 
								}
							} 
						} 
						reset($zip_objects); 
						rmdir($newdir); 
					} 		
				}				
				rrmdir($newdir);
				error('Invalid zip file containining folders, try rebuilding the zip without folders and try again');
				die();
			}

			//Sort arrays in natural order and reset pointer to first entry
			sort($zip_dirs, SORT_REGULAR);
			sort($zip_files, SORT_REGULAR);
			reset($zip_dirs);
			reset($zip_files);
			//Write filenames to summary report and notice message!
			foreach($zip_files as $zip_file) {
				$localname = $newdir.'/'.$zip_file;
				$filename = exapp($newfolder.'/'.$zip_file, $category);
				try {
					$object = $container->create_object($filename);
					$object->load_from_filename($localname);				
					$msg .= 'Cloud file created created: ' . $filename . '<br />';
					$msg_class = 'success';
					$object->metadata = array( 
						"Author" => $username,
						"Comment" => $newcomment,
						"Company" => $usercompany_name,
						"CompanyID" => $usercompany,
						"AuthorUID" => $unique_user_id
						);
					$object->sync_metadata();
					if($_POST['category'] == 'img') {
						mkcloudimg($localname, $filename, 'thumbs');
            mkcloudimg($localname, $filename, 'previews');
					}
					
					unlink($newdir.'/'.$zip_file);
				} catch(Exception $e) {
					$msg .= $e->getMessage();			
					$msg_class = 'error';
				}
			}
			
			rmdir($newdir);
			

			if (isset($_POST['vendors'])) {				
				$message = "$username has uploaded the following project file(s):\n";
				$message .= $filesuploaded;
				$message .= "To a new project folder named: $newfolder\n";				
				$comments = $newcomment;				
				$newfolderformmated = str_replace(" ", "%20", $newfolder);		
				$link = "http://".$site_URL."/index.php?page=folder&id=$id&name=$newfolderformmated";
			}
		}	
	
		break;
	

	
	}
		
	
	
	
		//Check to see if notification was called for, if so generate notification vars for $message, $comments,
		//$project and $link first since they are needed by notify.php to operate properly
		if (isset($_POST['vendors'])) {
			//Create strings for mail		
			$newfolderformmated = str_replace(" ", "%20", $newfolder);		
			$link = "http://".$site_URL."/index.php?page=folder&id=$id&name=$newfolderformmated";
			//invite selected vendors
			$invite_select_vendors = TRUE;
			//Call mail script
			include("notify.php");
			//Add to report
			$msg .= "<p>The following message was e-mailed to:  $addresses</p>";
			$msg .= "<div style=\"border:1px #369 solid;background:#fff\"><pre>$notice_text</pre></div>";
		}
	}
	
	

	//get directory name
	if(!isset($_GET['d'])) {
		$_GET['d'] = '';			
		$dirs = cloudls($_GET['d']);
		$files = cloudls('root');
		$header .= '<li class="prev disabled"><a href="#">Root Folder</a></li>
					<li class="disabled"><a href="#">' . count($dirs) . ' folder(s), '. count($files) . ' files(s)</a></li>';
	} else {
		unset($qs['d']);
		$files = cloudls($_GET['d']);
		$header .= '<li class="disabled"><a href="#">' . $_GET['d'] . '</a></li>
					<li class="disabled"><a href="#">'. count($files) . ' file(s)</a></li>
					<li class="prev"><a href="?' .  http_build_query($qs) . '">&larr; Back to root folder</a></li>';
	}
	
	if ($dirs || $files) {			
			$filter .= '<select id="company-filter" class="medium">';
			$filter .= '<option value="reset">Show all files</option>';
			foreach ($c as $k => $v) {
				$filter .= '<option value="' . $k . '">' . $v . '</option>';
			}
			$filter .= '</select>';
		
	} else {
		$header = '<li class="prev disabled"><a href="#">No files found</a></li>';
	}
	
	
	
	
	
?>

<?php if($msg_class): ?>
	<div class="alert-message block-message <?php print $msg_class; ?>">
		<a class="close" href="#">&times;</a>
		<p><?php print $msg; ?></p>       
	</div>
<?php endif; ?>


<div class="page-header">
    <h1>Project Files <small>All the files associated with this project, categorized by type</small></h1>
  </div>


<div class="row">
    <div class="span6 columns">
		<ul class="tabs">
		  <li id="tab-dwg"><a href="?page=project&id=<?php print $id; ?>&f=dwg">Drawings</a></li>
		  <li id="tab-img"><a href="?page=project&id=<?php print $id; ?>&f=img">Photos</a></li>
		  <li id="tab-doc"><a href="?page=project&id=<?php print $id; ?>&f=doc">Documents</a></li>		  
		</ul>  	
	</div>
    <div class="span10 columns">	
		
	
		<div class="pagination"  style="float:right;margin:0;">
		<ul>
		<?php print $header; ?>
		</ul>
		</div>

		<div class="filter"  style="float:right;margin:5px;">
		<?php print $filter; ?>
		</div>
		
	</div>
  </div>
  

<?php if ($dirs || $files): ?>
<table id="project-files" class="zebra-striped">
<thead>
<tr>
<?php if($_GET['f'] == 'img'): ?>
<th class="header">Preview</th>
<?php endif; ?>
<th class="orange header">Name</th>
<th class="green header">Size</th>
<th class="yellow header">Type</th>
<th class="purple header">Date</th>
<th class="blue header">Owner</th>
<th class="red header">Comments</th>
</tr>
</thead>
<tbody>

<?php 
	

	if($dirs) {
		foreach($dirs as $dir) {
			print '<tr>';				
			print '<td><a class="folder" href="?' . $_SERVER['QUERY_STRING'] . '&d=' . $dir['name'] . '">' . $dir['name'] . '</a></td>';
			if($_GET['f'] == 'img') {
				print '<td>&nbsp;</td>';
			}			
			print '<td>' . $dir['count'] . '</td>';
			print '<td>Folder</td>';
			print '<td>' . date("m/j/y", (strtotime($dir['date']) - 25200)) . '</td>';
			print '<td>' . $dir['author'] . '</td>';
			print '<td><html:abbr title="' . $dir['comment'] . '">' . substr($dir['comment'],0,32) . '</html:abbr></td>';
			print '</tr>';
		}
	}


	if($files) {
		foreach($files as $file) {
			print '<tr>';
			print '<td><a class="file" data-filename="' . $file['name'] . '" href="download.php?file=' . $id . '/' . $_GET['f'] . '/' . $file['name'] . '">';
			print end(explode('/', $file['name']));
			print '</a></td>';
			if($_GET['f'] == 'img') {
				print '<td>';
				//print '<a href="download.php?file=' . $id . '/' . $_GET['f'] . '/' . $file['name'] . '">';
        
        print '<a class="gallery" href="' . $previews->cdn_uri . '/' .  $file['name'] . '">';
				print '<img src="' . $thumbs->cdn_uri . '/' .  $file['name'] . '" />';
				print '</a></td>';
			}
			//File size in kb or mb.. 
			print '<td>' . file_size($file['length']) . '</td>';
			print '<td>' . $file['type'] . '</td>';
			print '<td>' . date("m/j/y", (strtotime($file['date']) - 25200)) . '</td>';			
			print '<td>' . $file['author'] . '</td>';
			print '<td><html:abbr title="' . $file['comment'] . '">' . substr($file['comment'],0,32) . '</html:abbr></td>';			
			print '</tr>';
		}
	}

?>	

</tbody>
</table>
<?php endif; ?>



<div class="well" id="project-file-actions">	
	<?php if (($usergroup == 0) or (in_array("plans",$roles))): ?>
	<a class="btn primary small" id="add-files">+ Add files</a>
	<?php endif; ?>	
</div>

	
	
<div class="modal" id="upload">
<div class="twipsy right" id="required-msg" style="top: 82px; left: 450px; display:none; ">
	<div class="twipsy-arrow"></div>
	<div class="twipsy-inner"><span id="folder-msg">Required!</span></div>
</div>
<div class="twipsy right" id="add-more-twipsy" style="top: 142px; left: 450px; ">
	<div class="twipsy-arrow"></div>
	<div class="twipsy-inner"><span id="add-another">+Add more</span></div>
</div>
<div class="twipsy right" style="bottom:80px; left: 450px; ">
	<div class="twipsy-arrow"></div>
	<div class="twipsy-inner"><span id="show-vendors">Show vendor list</span></div>
</div>

	<div class="modal-header">
		<h3>Upload Files</h3>
		<a href="#" class="close" onClick="$('#upload').fadeToggle();return false;">&times;</a>
	</div>
	
	<div class="modal-body">
		<form name="uploadform" id="upload-form" method="post" action="" enctype="multipart/form-data">		
		<input type="hidden" id="upload-type" name="upload_type" value="regular" />		
		<fieldset>
			<div class="clearfix">
			<label>Folder name</label>
			<div class="input">
				<?php if($_GET['d'] != ''):?> 
					<h5><?php print $_GET['d']; ?></h5>
					<input type="hidden" name="folder" value="<?php print $_GET['d']; ?>"> 
					<input type="hidden" name="merge" value="1">
				<?php else: ?>
					<input class="xlarge" name="folder" size="50" type="text" id="folder-name"> 
					<span class="help-block">Only numbers, letters, dashes and underscores</span>
				<?php endif; ?>
			</div>
			</div>	
			
			<div class="clearfix">
			<label for="userfile">Files</label>
			<div class="input multi-inputs" id="upload-inputs">
				<input name="userfile[]" size="51" type="file" onKeyPress="return noenter()"><br />
				
			</div>
			</div>
			
			<div class="clearfix">
			<label>Category</label>
			<div class="input">
			  <select class="large" name="category" id="category">
			  <option value="dwg" <?php if($_GET['f'] == 'dwg'){ print 'selected="selected"';} ?>>Drawings</option>
			  <option value="img" <?php if($_GET['f'] == 'img'){ print 'selected="selected"';} ?>>Photos</option>
			  <option value="doc" <?php if($_GET['f'] == 'doc'){ print 'selected="selected"';} ?>>Documents</option>			
			  </select>
			</div>
			</div>			
			
			<div class="clearfix">
			<label>Comment</label>
			<div class="input">
			  <textarea class="xlarge" name="comment"></textarea>
			  <span class="help-block">Optional </span>
			</div>
			</div>

			<div class="clearfix">
			<label id="optionsCheckboxes">Email notifications</label>
			<div class="input">			
			<div id="vlist-multi">			  
				<?php render_vlist($id); ?>			  
			 </div>
			  
			</div>
			</div>		
		</fieldset>		
		
		</form>
	</div>
	
	<div class="modal-footer">		
		<button class="btn" type="reset" name="reset" onClick="$('#upload').fadeToggle();">Cancel</button>&nbsp;
		<button type="submit" class="btn primary" name="submit" value="Upload" onClick="$('#upload-form').submit();">Upload</button>&nbsp;
		<span id="ajax" style="display:none;"><img src="/images/progress_bar4.gif" /></span>
	</div>
	
</div>
	

<div class="modal" id="delete">
	<div class="modal-header">
		<h3>Are you sure you want to delete this file?</h3>
		<a href="#" class="close" onClick="$('#delete').fadeToggle();return false;">&times;</a>
	</div>

	<div class="modal-body">
	<form id="del" name="del" method="post" action="?<?php print $_SERVER['QUERY_STRING'] ?>" enctype="multipart/form-data" >
	<input type="hidden" name="project_id" value="<?php print $id; ?>">
	<input type="hidden" class="del-path" name="path">
	<input type="hidden" name="rm" value="1">
	<h5 class="del-path"></h5>
	<p>This cannot be undone!</p>	
	</div>
	</form>
	<div class="modal-footer">		
		<button class="btn secondary" type="reset" name="reset" onClick="$('#delete').fadeToggle();">Cancel</button>&nbsp;
		<button type="submit" name="submit" class="btn danger" onClick="$('#del').submit();">Delete</button>
	</div>
	
</div>



<?php if (($usergroup == "0") or (in_array("plans",$roles))): ?>
	
<script>
  $(function() {

	var urlParams = {};
	(function () {
		var e,
			a = /\+/g,  // Regex for replacing addition symbol with a space
			r = /([^&=]+)=?([^&]*)/g,
			d = function (s) { return decodeURIComponent(s.replace(a, " ")); },
			q = window.location.search.substring(1);

		while (e = r.exec(q))
		   urlParams[d(e[1])] = d(e[2]);
	})();  
  
	
  
	$('#company-filter').change(function(){ 
		if ( $(this).val() == 'reset') {
			window.location.href = '?<?php unset($qs['cf']); print http_build_query($qs); ?>';
		} else {
			window.location.href = '?<?php print $_SERVER['QUERY_STRING'] ?>&cf=' + $(this).val();
		}
	});
  
	$('#upload-form').submit(function(){
		if( $('#folder-name').hasClass('required') ) {
			if( $('#folder-name').val() == '' ) {				
				$('#folder-name').addClass('error').closest('.clearfix').addClass('error');
				return false;
			} else {
				$('#folder-name').removeClass('error').closest('.clearfix').removeClass('error');							
			}
		}
		
		$('#upload-form').attr('action', '?page=project&id=<?php print $id; ?>&f=' + $('#category').val() );
		$('#ajax').show();		
	});
	
	$("#add-files").live('click', function(){ 			
		$("#upload").fadeToggle(); 		
		return false;		
	});
	
	$('#show-vendors').toggle(function(){ 
		$('#vlist-multi').fadeIn();
		$('#show-vendors').text('Hide vendor list');
	},function() {
		$('#vlist-multi').fadeOut();
		$('#show-vendors').text('Show vendor list');
	});
	
  
	$('#add-another').click(function() {
		if ( $('#upload-inputs input').length < 5 ) {
			$('<input name="userfile[]" type="file"/><br />').appendTo('#upload-inputs');
		}
		if ( $('#upload-inputs input').length >= 5 ) {
			$('#add-more-twipsy').remove();
			
		}
		return false;
	});
		
	$('#upload-inputs input').live('change', function() {		
		var ext = $('#upload-inputs input').val().split('.').pop().toLowerCase();
		if(ext == 'zip' || ext == 'ZIP') {
			$('#upload-type').val('zip');
			$('#folder-name').addClass('required');
			$('#add-more-twipsy').fadeOut();				
			$('#required-msg').fadeIn();
			$('#upload-inputs input:gt(0)').remove();
		} else {
			$('#add-more-twipsy').fadeIn();
			$('#required-msg').fadeOut();
		}	
	});
	
	$('<button />').html('x').addClass('btn smaller danger delete-btn').insertBefore('a.file');  
	
	$(".delete-btn").live('click', function(){ 	
		var path = $(this).next('a').attr('data-filename');
		$("#delete").fadeToggle(); 
		$("input.del-path").val(path);
		$("h5.del-path").text(path);
		return false;		
	});
	

	
	$('.alert-message a.close').click(function() {
		$(this).parent('.alert-message').slideUp();
	});
	
	$('.select-all-vendors').click(function() {
		$(this).next('ul').children('li').attr('checked', 'checked');
		return false;
	});
	

	if(urlParams['cf']){
		$('#company-filter').val(urlParams['cf']);	
	}
	
	
	if(urlParams['f']){
		$('.tabs #tab-' + urlParams['f']).addClass('active');
	} else {
		$('.tabs #tab-dwg').addClass('active');
	}
	
	<?php if($dirs || $files): ?>
	if(urlParams['f'] == 'img'){
		//$('#project-files').tablesorter({ sortList: [[4,0]] });
	} else {
		//$('#project-files').tablesorter({ sortList: [[3,0]] });
	}
	<?php endif; ?>
	
  });
</script>

<?php endif; ?>
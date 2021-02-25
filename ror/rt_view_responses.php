<?php
if ($mode == "ROR") {
	$sql = "select rt_ror_responses.*,UNIX_TIMESTAMP(creation) AS FORMATED_TIME, users.fullname, users.email from rt_ror_responses, users where users.id=rt_ror_responses.author_key AND parent_key = $id order by creation ASC";
	$docsdir = "rordocs";
} elseif ($mode == "FREQ") {	
	$sql = "select rt_freq_responses.*,UNIX_TIMESTAMP(creation) AS FORMATED_TIME, users.fullname, users.email from rt_freq_responses, users where users.id=rt_freq_responses.author_key AND parent_key = $opk AND fixture_key = $rt->fixture_key order by creation ASC";
	$docsdir = "freqdocs";
}
	$result = mysql_query($sql);
	if (mysql_num_rows($result)>0) {
?>
	<tr><td colspan="5">
	<h2>Response</h2>

<?php
		while($row = mysql_fetch_object($result)) {
			//calculate the view level message
			//default = 1 corp
			//1+2	= 3 (corp and vendor)
			//1+4	= 5 (corp and dm)
			//1+2+4	= 7 (corp and vendor and dm)
	
			if ($row->view==1) {$viewlevel="(just to home office)";if($usergroup == 2){continue;}if($usergroup == 3){continue;}}
			if ($row->view==3) {$viewlevel="(just to vendor)";if($usergroup == 2){continue;}}
			if ($row->view==5) {$viewlevel="(just to DM)";if($usergroup == 3){continue;}}
			if ($row->view==7) {$viewlevel="(to all)";}
			
			echo "<div class=\"resp\">";
			echo "<span class=\"attrib\">";
			//$grav_url = "http://www.gravatar.com/avatar.php?gravatar_id=".md5($row->email)."&amp;size=40";
			//echo "<a href=\"http://www.gravatar.com\">";
			//echo "<img style=\"border:1px #ccc solid;padding:3px;background:#fff;\" src=\"$grav_url\" alt=\"Go to gravatar.com to assign your icon\" /></a>&nbsp;";
			echo "<strong title=\"View Level:$row->view\">$row->fullname</strong> says $viewlevel: <h3>$row->summary</h3></span>";
			echo "<p id=\"edit".$row->id."\">$row->body</p>";
			if ($unique_user_id == $row->author_key) {
				echo "<script type=\"text/javascript\">new Ajax.InPlaceEditor('edit".$row->id."', 'eip.php?id=".$row->id."&mode=".$mode."', {rows:6,cols:40});</script>";
			}
			echo "<p><small>$row->assignments</small></p>";
			echo "<div class=\"date\">";
			
			if ($row->attachments>0) {
				echo "<span class=\"attachments\">";
					//scan for folders and files that are NOT photos and NOT system folders or files
					$filespace = $docsdir."/".$row->id."/";
					$files = array();
					$dirs  = array();
					if(file_exists($filespace)){			//check to see if the folder exists, otherwise you get ugly errors from  opendir(), readdir() and closedir()
						$handle=opendir($filespace);
						while (false !== ($file = readdir($handle))){
							if($file=='.'||$file=='..')
								continue;
							else
								$files[]=$file;
						}
						closedir($handle);
					} else {
						echo "Missing Attachments: \"$filespace\" must have been deleted!"; //throw a error message if you cannot find the folder
					}
					//Sort arrays in natural order and reset pointer to first entry
					sort($files, SORT_REGULAR);
					reset($files);

					//Now do files in root!
					foreach($files as $key=>$value){
//						if ($usergroup == "0"){
//							echo "<a href=\"#delete\" onClick=\"javascript:toggleBox('delete',1);setDelFile('".$filespace."','".$value."');\"><img src=\"images/delete.gif\" align=\"absmiddle\" border=\"0\" /></a>";
//						}
						echo "<img src=\"images/attachments.gif\" align=\"absmiddle\" />&nbsp;";
						echo "<a class=\"files\" href=\"download.php?file=".$filespace.$value."\">$value</a>";
						//File size in kb or mb.. 
						$fsize = file_size(filesize($filespace.$value));
						echo "<small>($fsize)</small>&nbsp;&nbsp;";
					}
				echo "</span>";
			}
			//get date and translate
			$creation = date("n/j/y g:ia",$row->FORMATED_TIME);
			echo "$creation";
			echo "</div>";
			echo "</div>";
		}
	}
?>
	</td></tr>
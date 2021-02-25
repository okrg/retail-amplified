	<?php
	$goto = "upload-refiles.php?pid={$id}&uid={$uid}";
	?>
	
	<form id='file-upload' action="<?=$goto?>" method='POST' enctype='multipart/form-data'>
		<input type='file' name='re-files' multiple></input>
		<button>Upload</button>
		<a class="btn">Upload files</a>
	</form>
	<table id="re-files"></table>
	<p></p>
	<p></p>
	
	<?php
		$path = "/var/www/vhosts/construction.charlotte-russe.com/httpdocs/realestate_files/{$id}";
		if (file_exists($path))
		{
				$fs = scandir($path);
				print "<table style=\"border:1px #000 solid;\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
				echo "<tr>";
				echo "<th class=\"files\" align=\"left\">Name</th>";
				echo "<th class=\"files\" align=\"right\">Size</th>";
				echo "<th class=\"files\" align=\"left\">Type</th>";
				echo "<th class=\"files\" align=\"left\">Date</th>";
				echo "<th class=\"files\" align=\"left\" style=\"border-right:none;\">Owner</th>";
				echo "</tr>";
				
				$farray = mysql_fetch_assoc($r);
				//print "<td class=\"files\" align=\"left\"><a class=\"files\" href=\"download.php?file=".urlencode("realestate_files/{$id}/{$file}")."\">".$file."</a></td>";
				foreach ($fs as $file)
				{
					if ($file=='.' || $file=='..')
						continue;
						
					$q = "SELECT * FROM re_files WHERE project_id='".$id."' AND filename='".$file."'";
					$r = mysql_query($q);
					if (!$r)
					{
						error("A databass error has occured.\\n".mysql_error()."\n\n{$q}\n");
						break;
					}
					$info = mysql_fetch_assoc($r);
					
					//echo "<img src=\"images/file.gif\" align=\"absmiddle\" border=\"0\" />$value";
					
					print "<tr>";
					print "<td class=\"files\" align=\"left\"><img src=\"images/file.gif\" align=\"absmiddle\" border=\"0\" /><a class=\"files\" href=\"download.php?file="."realestate_files/{$id}/{$file}"."\">".$file."</a></td>";
					print "<td class=\"files\" align=\"right\"><small>".formatFileSize(filesize($path."/".$file))."</small></td>";
					print "<td class=\"files\" align=\"left\"><small>".myfiletype($file)."</small></td>";
					print "<td class=\"files\" align=\"left\"><small>".date("m/j/y",filemtime($path."/".$file))."</small></td>";
					print "<td class=\"files\" align=\"left\"><small>".$info['uploaded_by']."</small></td>";
					print "</tr>";
				}
				print "</table>";
		}
	?>

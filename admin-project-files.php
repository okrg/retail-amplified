<?php

$summary_msg = "";
if (isset($submit)) {

	//Establish location name variables
	$type = $_POST['type'];
	$type = explode("/",$type);
	$uploaddir = $type[0];
	$filename = $type[1];
	$fullpath = $uploaddir."/".$filename;
	$tempname = $_FILES['project_file']['tmp_name'][0];
	
	if ($_FILES['project_file']['size'][0] == 0) {
		unlink($fullpath); // if a blank filename that means they want to delete the file, UNLIKELY THIS WILL EVER HAPPEN.
	} else {
		//Validate and exit upon finding conditions that cause failure
		if (!is_uploaded_file($tempname))
		{
			$summary_msg .= "<p><strong>Warning:</strong> It appears you tried to upload an invalid file!</p>";
			$summary_msg .= "<pre>".print_r($_FILES, true)."</pre>";
		} else {
			//Check to see if file already exists in filesystem
			if (file_exists($fullpath)){unlink($fullpath);}
			//Assume success and move temp to new loation and complete processing and display success to user	
				if (move_uploaded_file($tempname, $fullpath)){
					$summary_msg.= "<p>File has been successfully uploaded:<br \>";
					$summary_msg.= "<strong>$filename</strong></p>";
				} else {
					$summary_msg.= "<p>Failure</p>";
				}
		}
	}
}


?>
<div id="content">
<h1>:: Manage Project Files</h1>
<div class="databox">
<form style="padding:30px;" method="post" action="index.php?page=admin-project-files&id=<?=$_GET['id']?>" enctype="multipart/form-data">

<?php

dbConnect();
$sql = "select * from projects where id = ".$_GET['id'];
$result = mysql_query($sql);
$project = mysql_fetch_object($result);
echo $summary_msg;
	
		$isn = intval($project->store_number);
		$pisn = str_pad($isn, 3, "0", STR_PAD_LEFT);
		$sfname = "lights/".$isn."s.xls";
		$gfname = "lights/".$isn."g.xls";
		$spec01 = "pdfspecs/".$pisn."_fin_01.pdf";//Finish plan sheet 1
		$spec02 = "pdfspecs/".$pisn."_fin_02.pdf";//Finish plan sheet 2
		$spec03 = "pdfspecs/".$pisn."_fix_01.pdf";//Fixture plan sheet 1
		$spec04 = "pdfspecs/".$pisn."_fix_02.pdf";//Fixture plan sheet 2
		$spec05 = "pdfspecs/".$pisn."_flr_01.pdf";//Floor plan sheet 1
		$spec06 = "pdfspecs/".$pisn."_flr_02.pdf";//Floor plan sheet 2
		$spec07 = "pdfspecs/".$pisn."_lit_01.pdf";//Lighting plan sheet 1
		$spec08 = "pdfspecs/".$pisn."_lit_02.pdf";//Lighting plan sheet 2
		$spec09 = "pdfspecs/".$pisn."_vflr_01.pdf";//VM/OPS Plan
		$spec10 = "pdfspecs/".$pisn."_vflr_02.pdf";//VM/OPS Plan sheet 2

	echo "<p><strong>$project->sitename</strong> #$project->store_number $project->sitecity, $project->sitestate</p>";
	echo "<h2>Current Files</h2>";

	echo "<ul>";
	
	if (file_exists($sfname)) {
		print "<li><a class=\"files\" target=\"_blank\" href=\"lights/".$isn."s.xls\">";
		print "<img src=\"/images/lightbulb.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;Lighting Survey ".file_size(filesize($sfname))." </a></li>";
		$auxcount++;
		}
	if (file_exists($gfname)) {
			print "<li><a class=\"files\" target=\"_blank\" href=\"lights/".$isn."g.xls\">";
		print "<img src=\"/images/lightbulb.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;Lighting Guide ".file_size(filesize($gfname))." </a></li>";
		$auxcount++;
		}	
	if (file_exists($spec01)) {
		print "<li><a class=\"files\" target=\"_blank\" href=\"pdfspecs/".$pisn."_fin_01.pdf\">";
		print "<img src=\"/images/pdf.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;Finish pln sht 1 ".file_size(filesize($spec01)). "</a></li>";
		$auxcount++;
		}
	if (file_exists($spec02)) {
		print "<li><a class=\"files\" target=\"_blank\" href=\"pdfspecs/".$pisn."_fin_02.pdf\">";
		print "<img src=\"/images/pdf.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;Finish pln sht 2 ".file_size(filesize($spec02)). "</a></li>";
		$auxcount++;
		}
	if (file_exists($spec03)) {
		print "<li><a class=\"files\" target=\"_blank\" href=\"pdfspecs/".$pisn."_fix_01.pdf\">";
		print "<img src=\"/images/pdf.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;Fixture pln sht 1 ".file_size(filesize($spec03)). "</a></li>";
		$auxcount++;
		}
	if (file_exists($spec04)) {
		print "<li><a class=\"files\" target=\"_blank\" href=\"pdfspecs/".$pisn."_fix_02.pdf\">";
		print "<img src=\"/images/pdf.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;Fixture pln sht 2 ".file_size(filesize($spec04)). "</a></li>";
		$auxcount++;
		}
	if (file_exists($spec05)) {
		print "<li><a class=\"files\" target=\"_blank\" href=\"pdfspecs/".$pisn."_flr_01.pdf\">";
		print "<img src=\"/images/pdf.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;Floor pln sht 1 ".file_size(filesize($spec05)). "</a></li>";
		$auxcount++;
		}
	if (file_exists($spec06)) {
		print "<li><a class=\"files\" target=\"_blank\" href=\"pdfspecs/".$pisn."_flr_02.pdf\">";
		print "<img src=\"/images/pdf.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;Floor pln sht 2 ".file_size(filesize($spec06)). "</a></li>";
		$auxcount++;
		}
	if (file_exists($spec07)) {
		print "<li><a class=\"files\" target=\"_blank\" href=\"pdfspecs/".$pisn."_lit_01.pdf\">";
		print "<img src=\"/images/pdf.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;Lighting pln sht 1 ".file_size(filesize($spec07)). "</a></li>";
		$auxcount++;
		}
	if (file_exists($spec08)) {
		print "<li><a class=\"files\" target=\"_blank\" href=\"pdfspecs/".$pisn."_lit_02.pdf\">";
		print "<img src=\"/images/pdf.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;Lighting pln sht 2 ".file_size(filesize($spec08)). "</a></li>";
		$auxcount++;
		}
	if (file_exists($spec09)) {
		print "<li><a class=\"files\" target=\"_blank\" href=\"pdfspecs/".$pisn."_vflr_01.pdf\">";
		print "<img src=\"/images/pdf.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;(VM) Storefront pln sht 1 ".file_size(filesize($spec09)). "</a></li>";
		$auxcount++;
		}
	if (file_exists($spec10)) {
		print "<li><a class=\"files\" target=\"_blank\" href=\"pdfspecs/".$pisn."_vflr_02.pdf\">";
		print "<img src=\"/images/pdf.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;(VM) Storefront pln sht 2 ".file_size(filesize($spec10)). " </a></li>";
		$auxcount++;
		}
	echo "</ul>";

?>

<h2>Update Files or Add New Ones</h2>

<p>Type of file:
<select name="type">
    <option value="">-Select-</option>
    <option value="lights/<?=$isn?>s.xls">Lighting Survey</option>
    <option value="lights/<?=$isn?>g.xls">Lighting Guide</option>
    <option value="pdfspecs/<?=$pisn?>_fin_01.pdf">Finish Plan Sheet1</option>
    <option value="pdfspecs/<?=$pisn?>_fin_02.pdf">Finish Plan Sheet 2</option>
    <option value="pdfspecs/<?=$pisn?>_fix_01.pdf">Fixture Plan Sheet 1</option>
    <option value="pdfspecs/<?=$pisn?>_fix_02.pdf">Fixture Plan Sheet 2</option>
    <option value="pdfspecs/<?=$pisn?>_flr_01.pdf">Floor Plan Sheet 1</option>
    <option value="pdfspecs/<?=$pisn?>_flr_02.pdf">Floor Plan Sheet 2</option>
    <option value="pdfspecs/<?=$pisn?>_lit_01.pdf">Lighting Plan Sheet 1</option>
    <option value="pdfspecs/<?=$pisn?>_lit_02.pdf">LIghting Plan Sheet 2</option>
    <option value="pdfspecs/<?=$pisn?>_vflr_01.pdf">VM Storefront Plan Sheet 1</option>
    <option value="pdfspecs/<?=$pisn?>_vflr_02.pdf">VM Storefront Plan Sheet 2</option>
</select></p>
<p>Select file:
<input type="file" name="project_file[]" /></p>
<table id="navigation">
<tr>

<td>
	<input type="submit" value="Upload" name="submit" />
</td>
<td>
    <input type="button" value="Back to Project Page" name="back" onclick="location.href='http://construction.charlotte-russe.com/index.php?page=project&id=<?=$_GET['id']?>';" />
</td>
</tr>
</table>
</form>
</div>
</div>
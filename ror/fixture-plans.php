
<?php
error_reporting(E_ALL ^ E_NOTICE);
include(realpath(dirname(dirname(__FILE__)))."/include/access.php");
include(realpath(dirname(dirname(__FILE__)))."/include/rt.php");
$pageheading = "Fixture Request Catalog";

//create the code for the store drop down list
if ($usergroup < 2) {
	$loc_sql = "select id, sitenum, sitename, store_number from projects where chain=1 and store_number != '' order by store_number";
} else {
	$g = g2filter($uid);
	$loc_sql = "select id, sitenum, sitename, store_number from projects where $g[1] = $g[0] and chain=1 order by store_number";
}

//Execute filter on db!
$result = mysqli_query($dbcnx, $loc_sql);
if (!$result){error("A databass error has occured.$user_uid_rank $user_domain\\n".mysql_error($dbcnx));}
$loc_list = "";
while ($row = mysqli_fetch_array($result)){
	$num = intval($row['store_number']);
	$loc_list[$num] = $row['sitename'];
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>RT Home</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" media="all">@import "rt.css";</style>
<style type="text/css" media="all">@import "/thickbox/thickbox.css";</style>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="rt.js"></script>
<script type="text/javascript" src="autocolumn.min.js"></script>
<script type="text/javascript">
	$(function(){  
	document.getElementById('lhome').className='link';
	document.getElementById('lwaiting').className='link';
	document.getElementById('lrm_ok').className='link';
	document.getElementById('lvp_ok').className='link';
	document.getElementById('lrm_deny').className='link';
	document.getElementById('lvp_deny').className='link';
	document.getElementById('lprocessed').className='link';	
	document.getElementById('lreport').className='link';
	document.getElementById('lcreate').className='link';
	document.getElementById('lcreate').className='link';
	document.getElementById('lplans').className='current';	
	document.getElementById('progress').style.visibility = "hidden";	
	$('#list').columnize({width:400});  
	});  
</script>

</head>
<body>
<a name="top"></a>
<div id="progress" style="visibility: visible;">Loading...</div>
<div id="menu"><?php include("fixture-menu.php");?></div>
<h1><?=$pageheading?></h1>
<div id="maincontainer">
<p>All plans are provided as PDF files and they requrie Adobe Acrobat reader to view. If you do not have Adobe Acrobat reader, <a href="http://get.adobe.com/reader/">you can get it here.</a></p>
<br />
<br />

<div id="list" style="line-height:140%;font-size:0.85em;">


<?php
foreach ($loc_list as $store_number => $sitename) {
	$isn = intval($store_number);
	$pisn = str_pad($isn, 3, "0", STR_PAD_LEFT);
	$plan1 = "../pdfspecs/".$pisn."_vflr_01.pdf";//VM/OPS Plan
	$plan2 = "../pdfspecs/".$pisn."_vflr_02.pdf";//VM/OPS Plan sheet 2
	$spec05a = "../pdfspecs/".$pisn." A1-1.pdf";//Floor plan sheet (new)
	$spec05a1 = "../pdfspecs/".$pisn." A1-1.1.pdf";//Floor plan sheet 1 of 2
	$spec05a2 = "../pdfspecs/".$pisn." A1-1.2.pdf";//Floor plan sheet 2 of 2		
	$auxcount = 0;
	if (file_exists($plan1)) {
		print '<p>#'.$store_number.'<a target="_blank" class="files" href="/'.$plan1.'">'.$sitename.'</a></p>';
		$auxcount++;
		}
	if (file_exists($plan2)) {
		print '<p>#'.$store_number.'<a target="_blank" class="files" href="/'.$plan2.'">'.$sitename.' sheet 2</a></p>';
		$auxcount++;
		}

	if (file_exists($spec05a)) {
		print '<p>#'.$store_number.'<a target="_blank" class="files" href="/'.$spec05a.'">'.$sitename.'</a></p>';
		$auxcount++;
	}
	if (file_exists($spec05a1)) {
		print '<p>#'.$store_number.'<a target="_blank" class="files" href="/'.$spec05a1.'">'.$sitename.' sheet 1 of 2</a></p>';
		$auxcount++;
	}
	if (file_exists($spec05a2)) {
		print '<p>#'.$store_number.'<a target="_blank" class="files" href="/'.$spec05a2.'">'.$sitename.' sheet 2 of 2</a></p>';
		$auxcount++;
	}
}
?>
</div>
<br />
<br />

<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
</div>
<div style="clear:both;">&nbsp;</div>


</body>
</html>
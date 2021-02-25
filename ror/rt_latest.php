<?php
include("../include/access.php");
include("../include/rt.php");
//Load types and urgencies
$t =Types();
$u = Urgencies();
$f= Fixtures();
$mode = $_GET['mode'];
$main = "";
dbConnect();

if ($mode=="ROR"){
	$main .= "<table>";
	$main .= "<tr><td>";
	$fx=0;

	$res = mysql_query("SELECT rt_ror_responses.*,
							    projects.id,projects.sitename,projects.store_number,
								rt_rors.id,rt_rors.urgency,rt_rors.type,rt_rors.loc_key 
								from rt_ror_responses,projects,rt_rors WHERE view=7 AND rt_ror_responses.parent_key=rt_rors.id AND rt_rors.loc_key = projects.id order by creation");
	while($row=mysql_fetch_object($res)) {
	$main .= "<p>$row->sitename $row->body $row->creation</p>";
	}
	$main .= "</td></tr></table>";

} elseif ($mode=="FREQ") {

}

//Escape characters
function EscapeChars($x) {
	$x = str_replace("'", "\'", $x);
	$x = str_replace('"', "'+String.fromCharCode(34)+'", $x);
	$x = str_replace ("\r\n", '\n', $x);
	$x = str_replace ("\r", '\n', $x);
	$x = str_replace ("\n", '\n', $x);
	return $x;
	}
$main = EscapeChars($main);
?>
document.getElementById('dnew').className='none';
document.getElementById('dopen').className='none';
document.getElementById('dcompleted').className='none';
document.getElementById('statdash').innerHTML='<?php echo $main;?>';
document.getElementById('dlatest.className='current';
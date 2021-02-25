<p class="header"><?php include("../include/header.php"); ?></p>

<?php
	$cdb = "rt_rors";	
	//New Items Count for the left menu
	$csql = "select id from $cdb where status = 'new'";
	if ($usergroup == 2){
		$csql = "select * from $cdb , projects where status = 'new'";
		$g = g2filter($uid);$csql .= " AND projects.$g[1] = $g[0] AND $cdb.loc_key = projects.id";
	$newsql = mysql_query($csql);
	$newcount = mysql_num_rows($newsql);
	}

	//Open Items Count for the left menu	
	$csql = "select id from $cdb where status = 'open'";
	if ($usergroup == 2){
		$csql = "select * from $cdb , projects where status = 'open'";
		$g = g2filter($uid);$csql .= " AND projects.$g[1] = $g[0] AND $cdb.loc_key = projects.id";
		}
	$opensql = mysql_query($csql);
	$opencount = mysql_num_rows($opensql);
	
	//Completed Items Count for the left menu
	$csql = "select id from $cdb where status = 'completed'";
	if ($usergroup == 2){
		$csql = "select $cdb.*, projects.* from $cdb , projects where status = 'completed'";
		$g = g2filter($uid);$csql .= " AND projects.$g[1] = $g[0] AND $cdb.loc_key = projects.id";
		}
	$completedsql = mysql_query($csql);
	$completedcount = mysql_num_rows($completedsql);

?>

<h1 class="menu"><?=$mode?> Menu</h1>
	<ul>
		<li class="link" id="lhome"><a href="index.php?mode=<?=$mode?>">Home</a></li>
		<li class="link" id="lnew">
		<?php if ($usergroup<3) { ?>
        	<a href="list.php?show=new&mode=<?=$mode?>">New</a> (<?=$newcount?>)
		<?php } ?>
        </li>
		<li class="link" id="lopen"><a href="list.php?show=open&mode=<?=$mode?>"><? if($mode=="ROR"){echo"Pending";}else{echo"Open";} ?></a> (<?=$opencount?>)</li>
		<li class="link" id="lcompleted"><a href="list.php?show=completed&mode=<?=$mode?>">Completed</a> (<?=$completedcount?>)</li>
		<li class="link" id="lreport"><a href="report.php?mode=<?=$mode?>">Report Builder</a></li>
		<li class="link" id="lcreate"><a href="request.php?mode=<?=$mode?>">Create Request</a></li>
        <li class="link" id="lcreate"><a href="for-plans.php">Floor Plans</a></li>
		<!--<li class="link" id="lfloor"><a href="floor.php">Floor Cleaning</a></li>-->
	</ul>
    
<?php 
if (($unique_user_id==1)or($unique_user_id==4)or($usergroup==2)){
	echo "<p class=\"macrobutton smaller\"><a href=\"fixture-home.php\">Fixture Requests</a></p>";
}
?>

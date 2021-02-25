<p class="header"><?php include("../include/header.php"); ?></p>

<?php
	//New Items Count for the left menu
	$csql = "select id from rt_rors where status = 'new'";
	if ($usergroup == 2){
		$csql = "select * from rt_rors, projects where status = 'new'";
		$g = g2filter($uid);$csql .= " AND projects.$g[1] = $g[0] AND rt_rors.loc_key = projects.id";
	}
	if ($usergroup == 3){
		$csql = "select * from rt_rors, projects, companies where status = 'new' AND rt_rors.loc_key = projects.id AND rt_rors.vendor_key = companies.company_id AND companies.company_id = $usercompany";
	}

	$newsql = mysql_query($csql);
	if ($newsql) {$newcount = mysql_num_rows($newsql);}else{$newcount = 0;}

	//Open Items Count for the left menu	
	$csql = "select id from rt_rors where status = 'open'";
	if ($usergroup == 2){
		$csql = "select * from rt_rors, projects where status = 'open'";
		$g = g2filter($uid);
		$csql .= " AND projects.$g[1] = $g[0] AND rt_rors.loc_key = projects.id";
	}

	if ($usergroup == 3){
		$csql = "select * from rt_rors, projects, companies where status = 'open' AND rt_rors.loc_key = projects.id AND rt_rors.vendor_key = companies.company_id AND companies.company_id = $usercompany";
	}	

	$opensql = mysql_query($csql);
	if ($opensql){$opencount = mysql_num_rows($opensql);}else{$opencount = 0;}
	
?>

<h1 class="menu"><?=$mode?> Menu</h1>
	<ul>
		<li class="link" id="lhome"><a href="ror-home.php">Home</a></li>
		<li class="link" id="lnew"><a href="list.php?show=new&mode=<?=$mode?>">New</a> (<?=$newcount?>)</li>
		<li class="link" id="lopen"><a href="list.php?show=open&mode=<?=$mode?>">Pending</a> (<?=$opencount?>)</li>
		<li class="link" id="lcompleted"><a href="list.php?show=completed&mode=<?=$mode?>">Completed</a></li>
		<li class="link" id="lreport"><?php if($usergroup<2){?><a href="report.php?mode=<?=$mode?>">Report Builder</a><?php } ?></li>
        <li class="link" id="lcreate"><?php if($usergroup<3){?><a href="request.php?mode=<?=$mode?>">Create Request</a><?php } ?></li>
        
		<!--<li class="link" id="lfloor"><a href="floor.php">Floor Cleaning</a></li>-->
	</ul>
    
<p>&nbsp;</p>
<p>&nbsp;</p>
<ul><li class="link"><small><a href="/ror/index.php">Facilities Management Home</a></small></li></ul>
<?php if ($usergroup<2) {?>
<ul><li class="link"><small><a href="/index.php">Collaboration Network Home</a></small></li></ul>
<?php } ?>
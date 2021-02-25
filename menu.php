<a href="index2.php#top">:: Home&nbsp;<img src="images/home.gif" align="absmiddle" border="0" /></a><br />




<br/>
:: User options<img src="images/user.gif" align="absmiddle" /><br />
<a href="index2.php?page=edit-pwd">:: Change password</a><br />
<a href="index2.php?page=edit-profile">:: Edit profile</a><br />
<a href="javascript:window.external.AddFavorite('http://planetg03.com/admin/spnew/', 
'Collaboration Network')"> :: Add to Favorites</span></a><br />
<br />
<?php
	if ($usergroup == 0) {
	echo ":: Admin options<img src=\"images/config.gif\" align=\"absmiddle\" /><br />";
	echo "<a href=\"index2.php?page=new-project\">:: Add new project</a><br />";
	echo "<a href=\"index2.php?page=adduser\">:: Add user</a><br />";
	echo "<br />";
	echo "<a href=\"index2.php?page=admin\">:: Edit users</a><br />";
	echo "<a href=\"index2.php?page=edit-announce\">:: Edit announcement</a><br />";
	echo "<br />";
	echo "<a href=\"index2.php?page=view-logs\">:: View access logs<br />";
	}
?>
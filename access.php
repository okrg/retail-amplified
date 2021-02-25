<?php // access.php
//Create or resume session
session_start();
ini_set("register_globals", "1"); 
ini_set("session.gc_maxlifetime", "18000"); 
//header("Cache-control: private");
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
//header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
//header("Cache-Control: no-cache");
//header("Cache-Control: post-check=0,pre-check=0");
//header("Cache-Control: max-age=0");
//header("Pragma: no-cache");
//session_cache_limiter('private, must-revalidate');

include("common.php");
include("db.php");

// Check if user has been remembered 
if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){
	$_SESSION['uid'] = $_COOKIE['cookname'];
	$uid = $_COOKIE['cookname'];
	$_SESSION['pwd'] = $_COOKIE['cookpass'];
}

//Checks to see if the uid variable exists... if it doesn't, the user is presented 
//with a login form and script exits.
if(!isset($uid)) { 

?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns:html="http://www.w3.org/1999/xhtml">
	<head>
	<title>Collaboration Network</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style type="text/css" media="all">@import "http://construction2.charlotte-russe.com/stylesheet.css";</style>
	</head>
	<body onLoad="document.login.uid.focus()">
	<div id="frame">
	<div id="header"></div>
	
	<div id="logincity"></div>
	
	<div id="gradbar"></div>
	<div id="content">
		<h1>:: Login Required</h1>
		<div class="databox">
		<div style="text-align:center;"><img src="http://construction2.charlotte-russe.com/images/security.gif" align="absmiddle" /> This is a protected site. Enter your username and password below.</div>
		<form name="login" method="post" action="<?=$_SERVER['PHP_SELF']?>?<?=$_SERVER['QUERY_STRING']?>">
		<table width="50%" class="litezone" align="center">
		<tr>
		<td align="right"><strong>User:</strong></td>
		<td><input class="files" type="text" name="uid" size="10"></td>
		</tr><tr>
		<td align="right"><strong>Password:</strong></td>
		<td><input class="files" type="password" name="pwd" size="10"></td>
		</tr><tr>
        <td></td>
        <td align="left"><input type="checkbox" name="remember" id="remember" /><label for="remember">Remember me next time</label></td>
        </tr><tr>
		<td></td>
		<td><input class="files" type="submit" value="Login" name="submit"></td></tr>
		</table>
		</form>
        <div align="center"><p>You must have cookies enabled on your browser for the 'Remember me' feature to work properly. <a href="http://www.google.com/cookies.html">Go here for help enabling cookies.</a></p></div>
		</div>
	</div>
	</div>
	</body>
	</html>
	<?php
	exit;
}

if(!isset($_SESSION['uid'])) {
	$logme = true;
} else {
	$logme = false;
}
//After reloading, the uid and pwd are registered with the session
//the script will not check if this is a valid user.
session_register("uid");
session_register("pwd");

dbConnect();
$sql = "select * from users where userid = '$uid' and pwd = '$pwd' and active = 1";
$result = mysql_query($sql);
if (!$result) {
	error("A database error occured while checking your ".
		"login details.\\nIf this error persists, please ".
		"contact the developers.");

}
//If this was not a match, access denied...
if (mysql_num_rows($result) == 0)

{
	session_unregister("uid");
	session_unregister("pwd");
	?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:html="http://www.w3.org/1999/xhtml">
<head>
<title>Collaboration Network</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" media="all">@import "/stylesheet.css";</style>
</head>
<body onLoad="document.login.uid.focus()">
<div id="frame">
<div id="header"></div>

<div id="logincity"></div>

<div id="gradbar"></div>

<div id="content">
	<h1>:: Invalid Login!</h1>
	<div class="databox">
	<div style="text-align:center;"><img src="images/security.gif" align="absmiddle" /> This is a protected site. You must use a valid username and password to continue.</div>
	<form name="login" method="post" action="<?=$_SERVER['PHP_SELF']?>">
	<table width="50%" class="litezone" align="center">
	<tr>
	<td align="right"><strong>User:</strong></td>
	<td><input class="files" type="text" name="uid" size="10"></td>
	</tr><tr>
	<td align="right"><strong>Password:</strong></td>
	<td><input class="files" type="password" name="pwd" size="10"></td>
    </tr><tr>
    <td></td>
    <td align="left"><input type="checkbox" name="remember" id="remember" /><label for="remember">Remember me next time</label></td>
    </tr><tr>
    <td></td>
	<td><input class="files" type="submit" value="Login" name="submit"></td></tr>
	</table>
	</form>
	</div>
</div>
</div>
</body>
</html>

<?php
exit;
} else {

   /**
    * This is the cool part: the user has requested that we remember that
    * he's logged in, so we set two cookies. One to hold his username,
    * and one to hold his md5 encrypted password. We set them both to
    * expire in 100 days. Now, next time he comes to our site, we will
    * log him in automatically.
    */
   if(isset($_POST['remember'])){
		setcookie("cookname", $_SESSION['uid'], time()+60*60*24*100, "/");
		setcookie("cookpass", $_SESSION['pwd'], time()+60*60*24*100, "/");
	}

//check to see if the cookies are set, if not.. then set them...
//	if(!isset($_COOKIE['cookname']) && !isset($_COOKIE['cookpass'])){
//		setcookie("cookname", $_SESSION['uid'], time()+60*60*24*100, "/");
//		setcookie("cookpass", $_SESSION['pwd'], time()+60*60*24*100, "/");
//	}

	if ($logme) {
		$sql = "insert into viewlog set user = '$uid',project = 'Logged in'";
		if (!mysql_query($sql)){echo ("Unable to write to site access log");}
	}
}	

//Grab the user's full name for use in the page.
$username = mysql_result($result,0,"fullname");
$unique_user_id = mysql_result($result, 0, "id");
$usergroup = mysql_result($result,0,"groupid");
$this_users_usergroup = $usergroup;
$usercompany = mysql_result($result,0, "company_id");
$result = mysql_query("select roles from companies where company_id = $usercompany");
$roles = explode(",",mysql_result($result,0,"roles"));
?>
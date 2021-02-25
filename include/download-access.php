<?php // download-access.php
include("common.php");
include("db.php");

if (isset($free)) {
	continue;
} else {

//Create session
session_cache_limiter('private, must-revalidate');
session_start();


//Checks to see if the uid variable exists... if it doesn't, the user is presented 
//with a login form and script exits.

if(!isset($uid)) {
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

<title>Login required...</title>
<style type="text/css" media="all">@import "./def.css";</style>
</head>

<body onLoad="document.login.uid.focus()">

<div id="header"></div>

<div id="content">
	<h1>:: File Download</h1>
	<h2>You are trying to access a file that is protected.</h2>
	<p>You must use enter login information to continue.</p>

<div id="menu" style="text-align:right;">
<form name="login" method="post" action="<?php echo "$PHP_SELF?file=$file";?>">
	User: <input type="text" name="uid" size="10"><br />
	Password: <input type="password" name="pwd" size="10"><br /><br />
	<input type="submit" value="Login" name="submit">
	</form>
</div>


</div>
</body>
</html>

<?php
exit;
}

//After reloading, the uid and pwd are registered with the session
//the script will not check if this is a valid user.
session_register("uid");
session_register("pwd");

dbConnect();
$sql = "select * from users where
	userid = '$uid' and pwd = '$pwd'";
$result = mysql_query($sql);
if (!result) {
	error("A database error occured while checking your ".
		"login details.\\nIf this error persists, please ".
		"contact the developers.");

}
//If this was not a match, access denied...
if (mysql_num_rows($result) == 0) {
	session_unregister("uid");
	session_unregister("pwd");
	?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

<title>Incorrect login</title>
<style type="text/css" media="all">@import "./def.css";</style>
</head>

<body onLoad="document.login.uid.focus()">

<div id="header"></div>

<div id="content">
	<h1>Incorrect login...</h1>
	<p>You must use the proper username and password combination to be allowed to use this site. Make sure the Caps Lock is not on. If problems persist, contact the administrator.</p>




<div id="menu" style="text-align:right;">
<p>Try again</p>
<?php
	if (isset($file)) {
	echo "<form name=\"login\" method=\"post\" action=\"$PHP_SELF?file=$file\">";
	} else {
	echo "<form name=\"login\" method=\"post\" action=\"$PHP_SELF\">";
	}
?>
	User: <input type="text" name="uid" size="10"><br />
	Password: <input type="password" name="pwd" size="10"><br /><br />
	<input type="submit" value="Login" name="submit">
	</form>
</div>


</div>
</body>
</html>

<?php
exit;
}

//Grab the user's full name for use in the page.
$username = mysql_result($result,0,"fullname");
$usercompany = mysql_result($result,0, "company_id");
$usergroup = mysql_result($result,0,"groupid");
}
?>
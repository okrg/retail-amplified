<html>
<head>
<title> Sending Email </title>
</head>
<body>
<?php
error_reporting(E_ALL ^ E_NOTICE);
include("../include/access.php");
include("../include/rt.php");
include("timeline.php");
// Read POST request params into global vars
$sql = "select email,groupid from users where groupid = 2";
$res = mysql_query($sql);
while($row = mysql_fetch_object($res)){ 
	$to .= $row->email; 
	$to .= ", ";
}
$to .= "rolando.garcia@gmail.com,paul@visualworkz.com,ben@visualworkz.com,tim@visualworkz.com,Lance.Toerien@charlotterusse.com,Rachel.Higbee@charlotterusse.com,Rybarra@charlotte-russe.com";


$from    = "Collaboration Network <no-reply@charlotte-russe.com>";
$subject = "Notification: $days_to_delivery Days to November Fixture Delivery";
$headers = "From: $from";
// Generate a boundary string
$semi_rand = md5(time());
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
// Add the headers for a file attachment
$headers .= "\nMIME-Version: 1.0\n"."Content-Type: multipart/mixed;\n"." boundary=\"{$mime_boundary}\"";
// Add a multipart boundary above the plain message
$contents = "This is a multi-part message in MIME format.\n\n"."--{$mime_boundary}\n";
// Add a multipart boundary above the html message
$contents .= "Content-Type: text/html; charset=\"iso-8859-1\"\n"."Content-Transfer-Encoding: 7bit\n\n";
$contents .= $html;
$contents .="<p style=\"clear:both;\">&nbsp;</p>";
$contents .="<p style=\"clear:both;\">Log on to the Collaboration Network to <a href=\"http://construction.charlotte-russe.com/ror/fixture-home.php\">submit fixture requests</a>.</p>";
$contents .="<p style=\"clear:both;\">This is an automated message. http://construction.charlotte-russe.com/ror/fixture-home.php</p>";

$contents .="\n\n--{$mime_boundary}--\n";
// Send the message
echo "<div style=\"clear:both;\"></div>";
$ok = @mail($to, $subject, $contents, $headers);
if ($ok) {
  echo "<p>Mail sent! Yay PHP!</p>";
  echo "<pre>$contents</pre>";
} else {
  echo "<p>Mail could not be sent. Sorry!</p>";
  echo "<pre>$contents</pre>";  
}
?>
</body>
</html>

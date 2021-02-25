<?php // facilities-survey.php
	if ($usercompany < 10) {
		if (!isset($surveysubmit)):
?>
<div id="content">
<div class="breadcrumbs">
<a href="/">Home</a> &raquo; 
Facilities Survey</div>

<h1>:: Facilities Survey</h1>
<div class="databox">

<form name="edit" method="post" action="<?php echo "$PHP_SELF?page=facilities-survey";?>">
<input type="hidden" name="dmuid" value="<?=$uid?>" />
<input type="hidden" name="uuid" value="<?=$unique_user_id?>" />

<table>
<tr>
<td align="right">1.</td>
<td>
<p>How would you  rate the response time for dispatching your requests? </p>
<p>
    <label><input name="res1" type="radio" value="a">a) Excellent</label><br />
	<label><input name="res1" type="radio" value="b">b) Good</label><br />
	<label><input name="res1" type="radio" value="c">c) Fair</label><br />
	<label><input name="res1" type="radio" value="d">d) Poor</label>
</p>

</td>
</tr>

<tr>
<td align="right">2.</td>
<td>
<p> How would  you rate the performance of the technicians completing the job?</p>
<p>
    <label><input name="res2" type="radio" value="a">a) Excellent</label><br />
	<label><input name="res2" type="radio" value="b">b) Good</label><br />
	<label><input name="res2" type="radio" value="c">c) Fair</label><br />
	<label><input name="res2" type="radio" value="d">d) Poor</label>
</p>

</td>
</tr>


<tr>
<td align="right">3.</td>
<td>
<p>How would you rate    the communication on the status of a repair?</p>
<p>
    <label><input name="res3" type="radio" value="a">a) Excellent</label><br />
	<label><input name="res3" type="radio" value="b">b) Good</label><br />
	<label><input name="res3" type="radio" value="c">c) Fair</label><br />
	<label><input name="res3" type="radio" value="d">d) Poor</label>
</p>
</td>
</tr>


<tr>
<td align="right">4.</td>
<td>
<p>Are    the status emails and Collaboration Network updates easy to understand?</p>
<p>
    <label><input name="res4" type="radio" value="a">a) Yes</label><br />
	<label><input name="res4" type="radio" value="b">b) No</label>
</p>

</td>
</tr>


<tr>
<td align="right">5.</td>
<td>
<p>Overall how would    you rate the Repair &amp; Maintenance Services?</p>
<p>
    <label><input name="res5" type="radio" value="a">a) Excellent</label><br />
	<label><input name="res5" type="radio" value="b">b) Good</label><br />
	<label><input name="res5" type="radio" value="c">c) Fair</label><br />
	<label><input name="res5" type="radio" value="d">d) Poor</label>
</p>

</td>
</tr>

<tr>
<td align="right">6.</td>
<td><p>Do you have    any additional comments or feedback about the repair order process? </p>
  <p>
    <textarea name="comments" cols="50" rows="5"></textarea>
  </p></td>
</tr>
<tr>
<td></td>
<td>
<input type="submit" name="surveysubmit" value="Submit Survey">&nbsp;
</td></tr>
</table>
</form>
</div>

</div>

<?php
else:
    // Process edit submission
    dbConnect();
    if ($res1=="" or $res2=="" or $res3=="" or $res4=="" or $res5=="") {
        error("One or more required fields were left blank.\\n".
              "Please fill them in and try again.");
    }
    $comments = addslashes($_POST[comments]);   
	$sql = "insert into survey0107 set 
		dmuid='$_POST[dmuid]',
		res1='$_POST[res1]',
		res2='$_POST[res2]',
		res3='$_POST[res3]',
		res4='$_POST[res4]',
		res5='$_POST[res5]',
		comment = '$comments',
		dateadded=CURDATE()";
		
	if (!mysql_query($sql))
		error("A database error occured in proccessing your submission.\\n".mysql_error());
		
		
	$sql = "update users set survey0107='1' where id = $_POST[uuid]";
	if (!mysql_query($sql))
		error("A database error occured in proccessing your submission.\\n".mysql_error());
		
?>
<div id="content">
<h1>:: Survey submitted</h1>
<div class="databox">
	<p>Thank you for filling out the survey. </p>
	<p>[<a href="/">Return to home page</a>]</p>
</div>
</div>

<?php
endif;

} else {
	echo "You do not have sufficient privledges to view this page";
	exit;
}
?>
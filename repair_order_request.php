<?php //repair_order_request.php
//// this is the form that displays to DMs so they can submit a repair request
//
//if (!isset($editok)):
//?>
//
//<div id="content">
//	<h1>:: Repair Order Request</h1>
//	<div class="databox">
//	<form name="editproject" method="post" action="<?php echo "$PHP_SELF?page=repair_order_request&id=$id"; ?>">
//	  <table align="center" cellpadding="2" cellspacing="2" class="litezone" width="99%">
//        <tr>
//          <td rowspan="7" align="right" valign="middle" style="border-right:1px #666 dashed;"><small><strong>Required<br>
//          Data </strong></small></td>
//          <td width="100" class="col1" style="border-top:1px #666 dashed;"><strong>Store #: </strong></td>
//          <td class="edit_windows" style="border-top:1px #666 dashed;"><input name="newstore_number" type="text" class="files" onFocus="this.className='ff'" onBlur="this.className='files'" size="8" maxlength="8">&nbsp;&nbsp;
//          </td>
//        </tr>
//        <tr>
//          <td class="col1"><strong>Priority:</strong></td>
//          <td class="edit_windows"><select onFocus="this.className='ff'" onBlur="this.className='files'" name="newpriority" class="files">
//            <option value="NO Priority" selected>
//            - select one -
//            </option>
//            <option value="Minor">Minor</option>
//            <option value="Not Urgent">Not Urgent</option>
//            <option value="Urgent">Urgent</option>
//            <option value="Hazard">Hazard!</option>
//          </select></td>
//        </tr>
//
//        <tr>
//          <td class="col1"><strong>Contact Name:</strong></td>
//          <td class="edit_windows"><input name="new_contact_name" type="text" class="files" onKeyPress="return noenter()" onFocus="this.className='ff'" onBlur="this.className='files'" size="60" maxlength="60"></td>
//        </tr>
//        <tr>
//          <td class="col1" ><strong>Contact Phone:</strong></td>
//          <td class="edit_windows" ><input name="new_contact_fax" type="text" class="files" onKeyPress="return noenter()" onFocus="this.className='ff'" onBlur="this.className='files'" size="60" maxlength="60"></td>
//        </tr>
//        <tr>
//          <td class="col1">&nbsp;</td>
//          <td class="edit_windows">&nbsp;</td>
//        </tr>
//        <tr>
//          <td class="col1"><strong>Request</strong><br /><small>EXPLAIN IN DETAIL</small></td>
//          <td class="edit_windows"><textarea name="newcomments" cols="70" rows="10" class="files" onFocus="this.className='ff'" onBlur="this.className='files'"><?=$comments?></textarea></td>
//        </tr>          
//        <tr>
//          <td class="col1" style="border-bottom:1px #666 dashed;">&nbsp;</td>
//          <td class="edit_windows" style="border-bottom:1px #666 dashed;">&nbsp;</td>
//        </tr>
//        <tr>
//          <td class="col1">&nbsp;</td>
//          <td class="col1">&nbsp;</td>
//          <td>&nbsp;</td>
//        </tr>
//        <tr>
//          <td class="col1">&nbsp;</td>
//          <td class="col1">&nbsp;</td>
//          <td><p><input name="editok" type="submit" class="submit" value="Submit"></p></td>
//        </tr>
//      </table>
//	  <p><br />
//</p>
//
//	
//    </form>
//</div>
//</div>
//
//
//<?php
//
//else:
//// Process edit
//	dbConnect();
////Format comments
//	$newcomments = addslashes($newcomments);
//	
//	$newvendors = serialize( $_POST['vendors']);
//	
////Find out if Grand Display has been activated!
//	$newgrand_display=($_POST['grand_display'])?"1":"0"; 
//
////Set the sql statment..
////	$sql =	"update projects set 
//		sitename='$newsitename',
//		sitenum='$newsitenum',
//		store_number = '$newstore_number',
//		store_district = '$newstore_district',
//		store_region = '$newstore_region',
//		chain = '$newsitechain',
//		project_status = '$newsitestatus',
//		grand_display = '$newgrand_display',
//		siteaddress='$newsiteaddress',
//		siteaddress2='$newsiteaddress2',
//		sitecity='$newsitecity',
//		sitestate='$newsitestate',
//		sitezip='$newsitezip',
//		sitephone='$newsitephone',
//		sitefax='$newsitefax',
//		companyarray = '$newvendors',
//		comments='$newcomments',
//		datetouched = CURDATE() where id = '$id'";
//
//	if (!mysql_query($sql))
//		error("A database error occured in proccessing your submission.\\n".mysql_error());				
//?>
//<div id="content">
//	<h1>:: Project edited successfully</h1>
//	<div class="databox">
//	<p>This project's information has been successfully modified.</p>
//	<p><a href="<?=$_SERVER['PHP_SELF']?>?page=project&id=<?=$id?>">:: Return to this project page</a></p>
//	</div>
//</div>
//
//<?php
//endif;
?>
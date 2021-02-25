<?php
//error_reporting(E_ALL);
include("little-helpers.php");
			
?>

<link rel="stylesheet" href="jquery_multiselect/common.css" type="text/css" />
<link type="text/css" rel="stylesheet" href="jquery_multiselect/theme/humanity/jquery-ui-1.8.9.custom.css" />
	<link type="text/css" href="jquery_multiselect/ui.multiselect.css" rel="stylesheet" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js" type="text/javascript"></script>
	<!--
	<script type="text/javascript" src="jquery_multiselect/jquery-1.4.2.min.js"></script>
	-->
	<script type="text/javascript" src="jquery_multiselect/jquery-ui-1.8.custom.min.js"></script>
	<script type="text/javascript" src="jquery_multiselect/localisation/jquery.localisation-min.js"></script>
	<script type="text/javascript" src="jquery_multiselect/scrollTo/jquery.scrollTo-min.js"></script>
	<script type="text/javascript" src="jquery_multiselect/ui.multiselect.js"></script>
	<script type="text/javascript">
		$(function(){
			$.localise('ui-multiselect', {
				path: 'jquery_multiselect/'
			});
			$(".multiselect").multiselect();
			$(".fromto-dates").click(function(){
				$(this).val('');
			});
			
		});
	</script>
	<script type="text/javascript">
  	function submitform(f,a)
  	{
  		f.action = a;
  		f.submit();
  	}
  	</script>
	
<div id="content">
	<h1>:: Build Report</h1>
	
	<div class="databox">
	<form name="columns" method="post" action="placeholder">
	&nbsp;
	<fieldset>
    <legend>Select columns</legend>
    <select id="fields" class="multiselect" multiple="multiple" name="fields[]">
           
    <?php
       	
    	foreach ($colsmap as $displayname => $columnname)
    	{
    		if (in_array($columnname, $fields))
    			print "<option value=\"{$columnname}\" selected=\"selected\">{$displayname}</option>";
    		else
    			print "<option value=\"{$columnname}\">{$displayname}</option>";   		
    	} 
    	
    ?>
    	
    </select>
 	<p></p>
    <input name="addfilter" type="button" class="files" value="Update Filters" onClick="submitform(columns,'/index.php?page=re-reportbuilder&fltr=yes')">
  	
  	
  	</fieldset>
  	</form>
  	<p><br /></p>
  	
  	<?php
  	$dh = dbConnect();
  	if (isset($_GET['fltr']) && $_GET['fltr'] == 'yes')
  	{
  	?>
  	<form name="filters" method="post" action="placeholder2">
  	<fieldset>  	
    <legend>Filters</legend>
    
    	<?php
       	$count = 0;
       	print "<table cellpadding='2' cellspacing='2' class='litezone'>";
    	foreach ($fields as $columnname)
    	{    		
    		print "<tr>";
    		
    		print "<td align=\"right\"><small>".array_search($columnname, $colsmap)."</small></td>";
    		
    		if (($columnname == "developer") ||
    			($columnname == "market_area") ||
    			($columnname == "street"))
    		{    			
    			$inputfieldwidth = 40;
    			$secondcolumn = "<select name=\"{$columnname}_op\" class='files'>"."<option value='like'>contains</option>"."<option value='='>exactly</option></select>";
    			$thirdcolumn = "<input name=\"{$columnname}\" type='text' class='files' onKeyPress='return noenter()' value='' size=\"{$inputfieldwidth}\">";
    		}
    		if (($columnname == "center_type") ||
    			($columnname == "cluster_name") ||
    			($columnname == "center_rank") ||
    			($columnname == "rm_dm_wishlist") ||
    			($columnname == "store_prototype") ||
    			($columnname == "floor_finish"))
    		{
    			$q = "SELECT DISTINCT {$columnname} FROM " . tablename($columnname);
    			$r = mysql_query($q);
    			if (!$r) {error('A databass error has occured.\\n'.mysql_error());}
				
				$secondcolumn = "<select name=\"{$columnname}\" class='files'>";
				$secondcolumn = $secondcolumn . "<option value='any'>Any</option>";
				while($row = mysql_fetch_assoc($r))
				{
					if ($row[$columnname] == "")
					{
						$secondcolumn = $secondcolumn . "<option value=\"".$row[$columnname]."\">(blank)</option>";
						continue;
					}
					$secondcolumn = $secondcolumn . "<option value=\"".$row[$columnname]."\">".$row[$columnname]."</option>";
				}
				$thirdcolumn = "&nbsp;";
    		}
    		if (($columnname == "state_or_area") ||
    			($columnname == "mills_or_outlet"))
    		{
    			$inputfieldwidth = 8;
    			$secondcolumn = "<select name=\"{$columnname}_op\" class='files'>"."<option value='like'>contains</option>"."<option value='='>exactly</option></select>";
    			$thirdcolumn = "<input name=\"{$columnname}\" type='text' class='files' onKeyPress='return noenter()' value='' size=\"{$inputfieldwidth}\">";
    		}
    		if (($columnname == "gross_sqft") ||
				($columnname == "sales_area_sqft") ||
				($columnname == "sales_percentage_vs_ly") ||
				($columnname == "sales_projection") ||
				($columnname == "ty_ttm_sales") ||
				($columnname == "ty_sales_psf") ||
				($columnname == "ty_ttm_op_inc") ||
				($columnname == "ty_ttm_cf") ||
				($columnname == "ly_ttm_sales") ||
				($columnname == "ly_sales_psf") ||
				($columnname == "ly_ttm_op_inc") ||
				($columnname == "ly_ttm_cf") ||
				($columnname == "ttm_r_and_o") ||
				($columnname == "r_and_o_percent_sales") ||
				($columnname == "tenant_allowance_psf") ||
				($columnname == "mall_gla") ||
				($columnname == "shop_gla") ||
				($columnname == "mall_sales_psf"))
			{
				$secondcolumn = "<select name=\"{$columnname}_op\" class='files'>"."<option value='='>=</option>"."<option value='>'>></option>"."<option value='<'><</option>"."</select>";
    			$inputfieldwidth = 10;
				$thirdcolumn = "<input name=\"{$columnname}\" type='text' class='files' onKeyPress='return noenter()' value='' size=\"{$inputfieldwidth}\">";
			}
			if (($columnname == "center_cashwrap") ||
				($columnname == "entry_soffit") ||
				($columnname == "cashwrap_soffit") ||
				($columnname == "illuminated_accessory_wall") ||
				($columnname == "illuminated_shoe_wall") ||
				($columnname == "illuminated_window_walls") ||
				($columnname == "top_tier_a") ||
				($columnname == "forever21"))
			{
				$secondcolumn = "<select name=\"{$columnname}\" class='files'>"."<option value='any'>Any</option>"."<option value='1'>Yes</option>"."<option value='0'>No</option>"."</select>";
    			$thirdcolumn = "&nbsp;";
			}
			if (($columnname == "brite_store_makeup"))
			{
				$secondcolumn = "<select name=\"{$columnname}\" class='files'>"."<option value='any'>Any</option>"."<option value='Partial'>Partial</option>"."<option value='Full'>Full</option>"."<option value='None'>None</option>"."</select>";
    			$thirdcolumn = "&nbsp;";
			}
			if (($columnname == "approval_date") ||
    			($columnname == "opening_date") ||
    			($columnname == "remodel_date") ||
    			($columnname == "expiry_date") ||
    			($columnname == "mall_sales_psf_date"))
    		{
    			$cn_from = $columnname."_from";
    			$cn_to = $columnname."_to";
    			$inputfieldwidth = 10;
    			print "<script>$(function(){"."$(\"#".$cn_from."\").datepicker({showOn:\"button\", buttonImage:\"images/calendar.jpeg\", buttonImageOnly:true, showAnim:\"slideDown\"});});</script>";
    			$secondcolumn = "<input name=\"{$cn_from}\" type='text' id=\"{$cn_from}\" class='fromto-dates' onKeyPress='return noenter()' value='From' size=\"{$inputfieldwidth}\">";
    			print "<script>$(function(){"."$(\"#".$cn_to."\").datepicker({showOn:\"button\", buttonImage:\"images/calendar.jpeg\", buttonImageOnly:true, showAnim:\"slideDown\"});});</script>";
    			$thirdcolumn = "<input name=\"{$cn_to}\" type='text' id=\"{$cn_to}\" class='fromto-dates' onKeyPress='return noenter()' value='To' size=\"{$inputfieldwidth}\">";
    		}
    		
    		print "<td>{$secondcolumn}</td>";    		
    		print "<td>{$thirdcolumn}</td>";
    		
    		print "</tr>";    		
    	}
    	
    	print "<tr><td align='right'><small>Save Report</small></td>";
    	print "<td><input name='save_query' type='checkbox'/></td>";
    	print "<td></td>";
    	
    	print "<tr><td align='right'><small>Report Title</small></td>";
    	print "<td colspan=2><input name=\"reportname\" type=\"text\" class=\"files\" onKeyPress='return noenter()' size=\"50\"/></td>";
    	print "<td></td>";
    	
    	print "</table>";
    	$count = 0;
    	
    	$user_id = $_GET['user_id'];
    	$id = $_GET['project_id'];
    	$swf = "/displayreport.php?filter=yes&user_id={$user_id}&project_id={$id}";
 
    	?>
    <p></p>
    <input name="submitwithfilter" type="button" class="files" value="Submit" onClick="submitform(filters,'<?=$swf?>');"/>
    <?php
    $f = base64_encode(serialize($fields));
    ?>
    <input name='flds' type='hidden' class='files' value="<?=$f?>"/>
    
    </fieldset>
    </form>
    <?php
  	}
  	
  	function tablename($cn)
  	{
  		$s = "SELECT * FROM information_schema.columns WHERE COLUMN_NAME='".$cn."'";
  		$rs = mysql_query($s);
  		if (!$rs) {error('A databass error has occured.\\n'.mysql_error());}
  		$i = mysql_fetch_assoc($rs);
  		return $i['TABLE_NAME'];
  	}
  	
  	dbClose($dh);
  	?>

</div>
</div>




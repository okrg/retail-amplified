<?php
if ($mode == "ROR") {
		$sql="SELECT rt_rors.loc_key,rt_rors.status,rt_rors.id,rt_rors.type,
			rt_rors.urgency,rt_rors.creation,
			projects.sitename,projects.sitecity,projects.sitestate,projects.siteaddress,projects.sitezip,
			projects.store_number,projects.store_district,projects.store_region, projects.high_volume_store, projects.potential_remodel_store 
			FROM rt_rors, projects WHERE rt_rors.loc_key = projects.id AND rt_rors.id = $id";
		$res_db = "rt_ror_responses";
			
} elseif ($mode == "FREQ") {
		$sql="SELECT rt_freqs.loc_key,rt_freqs.status,rt_freqs.id,rt_freqs.creation,
			projects.sitename,projects.sitecity,projects.sitestate,projects.siteaddress,projects.sitezip,
			projects.store_number,projects.store_district,projects.store_region,projects.high_volume_store, projects.potential_remodel_store 
			FROM rt_freqs, projects WHERE rt_freqs.loc_key = projects.id AND rt_freqs.id = $id";
		$res_db = "rt_freq_responses";
}

		
		$result = mysql_query($sql);
		
		if (!$result) {error("View-Strip Error with database: ".mysql_error());}
		
		//Create columns
		echo "<table width=\"100%\" id=\"datarows\" border=\"0\" cellpadding=\0\" cellspacing=\"0\"><thead>";
		echo "<tr style=\"text-align:left;font-size:11px;\">";
		echo "<th class=\"norm\">Sitename</th>";
		echo "<th class=\"norm\">City</th>";
		echo "<th class=\"norm\">State</th>";
		echo "<th class=\"norm\">S#</th>";
		echo "<th class=\"norm\">D#</th>";
		echo "<th class=\"norm\">R#</th>";
		echo "<th class=\"norm\">Last Response</th>";
		echo "</tr></thead>";
		while ($row = mysql_fetch_object($result)) {
			//Conduct last_recieved query
			$last_result = mysql_query("select creation from $res_db where parent_key = $id order by creation DESC");
			if (mysql_num_rows($last_result)==0){
				$last_res = "No responses yet!";
			} else {
				$last_res = mysql_result($last_result,"creation");
			}
			echo "<tr>";
			echo "<td class=\"norm\">";
			if ($row->high_volume_store == 1) {echo " <img src=\"images/star.gif\" /> ";}
			if ($row->potential_remodel_store == 1) {echo " <img src=\"/images/config.gif\" /> ";}
			echo myTruncate($row->sitename,30, " ")."<input type=\"hidden\" name=\"sitename\" value=\"$row->sitename\" /></td>";
			echo "<td class=\"norm\">$row->sitecity<input type=\"hidden\" name=\"sitecity\" value=\"$row->sitecity\" /></td>";
			echo "<td class=\"norm\">$row->sitestate<input type=\"hidden\" name=\"sitestate\" value=\"$row->sitestate\" /></td>";
			echo "<td class=\"norm\">".ltrim($row->store_number,"0")."<input type=\"hidden\" name=\"store_number\" value=\"$row->store_number\" /></td>";
			echo "<td class=\"norm\">".ltrim($row->store_district,"0")."</td>";
			echo "<td class=\"norm\">".ltrim($row->store_region,"0")."</td>";
			echo "<td class=\"norm\">$last_res</td>";	
			echo "</tr>";
			$pid = $row->loc_key;
			$address = $row->siteaddress;
			$zip = $row->sitezip;
			$isn = intval($row->store_number);
			$pisn = str_pad($isn, 3, "0", STR_PAD_LEFT);
			$sfname = "/home/sites/www.construction.charlotte-russe.com/web/lights/".$isn."s.xls";
			$gfname = "/home/sites/www.construction.charlotte-russe.com/web/lights/".$isn."g.xls";
			$spec01 = "/home/sites/www.construction.charlotte-russe.com/web/pdfspecs/".$pisn."_fin_01.pdf";//Finish plan sheet 1
			$spec02 = "/home/sites/www.construction.charlotte-russe.com/web/pdfspecs/".$pisn."_fin_02.pdf";//Finish plan sheet 2
			$spec03 = "/home/sites/www.construction.charlotte-russe.com/web/pdfspecs/".$pisn."_fix_01.pdf";//Fixture plan sheet 1
			$spec04 = "/home/sites/www.construction.charlotte-russe.com/web/pdfspecs/".$pisn."_fix_02.pdf";//Fixture plan sheet 2
			$spec05 = "/home/sites/www.construction.charlotte-russe.com/web/pdfspecs/".$pisn."_flr_01.pdf";//Floor plan sheet 1
			$spec06 = "/home/sites/www.construction.charlotte-russe.com/web/pdfspecs/".$pisn."_flr_02.pdf";//Floor plan sheet 2
			$spec07 = "/home/sites/www.construction.charlotte-russe.com/web/pdfspecs/".$pisn."_lit_01.pdf";//Lighting plan sheet 1
			$spec08 = "/home/sites/www.construction.charlotte-russe.com/web/pdfspecs/".$pisn."_lit_02.pdf";//Lighting plan sheet 2
			$spec09 = "/home/sites/www.construction.charlotte-russe.com/web/pdfspecs/".$pisn."_vflr_01.pdf";//VM/OPS Plan
			$spec10 = "/home/sites/www.construction.charlotte-russe.com/web/pdfspecs/".$pisn."_vflr_02.pdf";//VM/OPS Plan sheet 2
			$auxcount = 0;
		}
		echo "</table>";

	print "<script type=\"text/javascript\"><!--//--><![CDATA[//><!--
			startList = function() {
				if (document.all&&document.getElementById) {
					navRoot = document.getElementById(\"stripnav\");
					for (i=0; i<navRoot.childNodes.length; i++) {
						node = navRoot.childNodes[i];
						if (node.nodeName==\"LI\") {
							node.onmouseover=function() {
								this.className+=\" over\";
							}
							node.onmouseout=function() {
								this.className=this.className.replace(\" over\", \"\");
							}
						}
					}
				}
			}
			window.onload=startList;
			//--><!]]></script>
	";	

	print "<div id=\"stripgrey\"><ul id=\"stripnav\">";
	print "<li><a href=\"#\">About</a><ul>";
	print "<li><a href=\"../index.php?page=project&id=$pid\" target=\"_blank\">Store Page</a></li>";
	print "<li><a href=\"http://maps.google.com/?q=$address+$zip\" target=\"_blank\">Map/Directions</a></li>";
	print "</ul></li>";
	print "<li><a href=\"#\">Files</a><ul>";
	if ($usergroup < 2) {
	print "<li><a href=\"admin_project_files.php?id=$pid&mode=$mode&rt_id=$id\">Update Files</a></li>";
	}
	if (file_exists($sfname)) {
		print "<li><a class=\"files\" href=\"download.php?file=/lights/".$isn."s.xls\">";
		print "<img src=\"/images/lightbulb.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;Lighting Survey ".file_size(filesize($sfname))." </a></li>";
		$auxcount++;
		}
	if (file_exists($gfname)) {
			print "<li><a class=\"files\" href=\"download.php?file=/lights/".$isn."g.xls\">";
		print "<img src=\"/images/lightbulb.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;Lighting Guide ".file_size(filesize($gfname))." </a></li>";
		$auxcount++;
		}	
	if (file_exists($spec01)) {
		print "<li><a class=\"files\" href=\"download.php?file=/pdfspecs/".$pisn."_fin_01.pdf\">";
		print "<img src=\"/images/pdf.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;Finish pln sht 1 ".file_size(filesize($spec01)). "</a></li>";
		$auxcount++;
		}
	if (file_exists($spec02)) {
		print "<li><a class=\"files\" href=\"download.php?file=/pdfspecs/".$pisn."_fin_02.pdf\">";
		print "<img src=\"/images/pdf.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;Finish pln sht 2 ".file_size(filesize($spec02)). "</a></li>";
		$auxcount++;
		}
	if (file_exists($spec03)) {
		print "<li><a class=\"files\" href=\"download.php?file=/pdfspecs/".$pisn."_fix_01.pdf\">";
		print "<img src=\"/images/pdf.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;Fixture pln sht 1 ".file_size(filesize($spec03)). "</a></li>";
		$auxcount++;
		}
	if (file_exists($spec04)) {
		print "<li><a class=\"files\" href=\"download.php?file=/pdfspecs/".$pisn."_fix_02.pdf\">";
		print "<img src=\"/images/pdf.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;Fixture pln sht 2 ".file_size(filesize($spec04)). "</a></li>";
		$auxcount++;
		}
	if (file_exists($spec05)) {
		print "<li><a class=\"files\" href=\"download.php?file=/pdfspecs/".$pisn."_flr_01.pdf\">";
		print "<img src=\"/images/pdf.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;Floor pln sht 1 ".file_size(filesize($spec05)). "</a></li>";
		$auxcount++;
		}
	if (file_exists($spec06)) {
		print "<li><a class=\"files\" href=\"download.php?file=/pdfspecs/".$pisn."_flr_02.pdf\">";
		print "<img src=\"/images/pdf.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;Floor pln sht 2 ".file_size(filesize($spec06)). "</a></li>";
		$auxcount++;
		}
	if (file_exists($spec07)) {
		print "<li><a class=\"files\" href=\"download.php?file=/pdfspecs/".$pisn."_lit_01.pdf\">";
		print "<img src=\"/images/pdf.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;Lighting pln sht 1 ".file_size(filesize($spec07)). "</a></li>";
		$auxcount++;
		}
	if (file_exists($spec08)) {
		print "<li><a class=\"files\" href=\"download.php?file=/pdfspecs/".$pisn."_lit_02.pdf\">";
		print "<img src=\"/images/pdf.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;Lighting pln sht 2 ".file_size(filesize($spec08)). "</a></li>";
		$auxcount++;
		}
	if (file_exists($spec09)) {
		print "<li><a class=\"files\" href=\"download.php?file=/pdfspecs/".$pisn."_vflr_01.pdf\">";
		print "<img src=\"/images/pdf.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;(VM) Storefront pln sht 1 ".file_size(filesize($spec09)). "</a></li>";
		$auxcount++;
		}
	if (file_exists($spec10)) {
		print "<li><a class=\"files\" href=\"download.php?file=/pdfspecs/".$pisn."_vflr_02.pdf\">";
		print "<img src=\"/images/pdf.gif\" align=\"absmiddle\" border=\"0\" />&nbsp;(VM) Storefront pln sht 2 ".file_size(filesize($spec10)). " </a></li>";
		$auxcount++;
		}
	if ($auxcount == 0) {print "No files to list";}
	print "</ul></li></ul></div>";
	print "<div style=\"clear:both;\"></div>";
		
		?>
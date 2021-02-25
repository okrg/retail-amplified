<?php
include("../include/db.php");
include 'charts.php';
dbConnect();

//Calculate the maximum value to show int he axis of the graph, expressed as a attribute in the axis_value array (max)
$result = mysql_query("select max(qty) from fixture_blanket where fixture_key = ".$_GET['fid']."");
$maxval = mysql_result($result,0,"max(qty)");

$result = mysql_query("select min(qty), date_format( mod_date, '%c/%e' ) AS fdate from fixture_blanket where fixture_key = ".$_GET['fid']." group by mod_date");
	$chart [ 'chart_data' ][ 0 ][ 0 ] = "";
	$chart [ 'chart_data' ][ 1 ][ 0 ] = "";
	$datecol = 1;
	while ($data = mysql_fetch_array($result)){
		$chart [ 'chart_data' ][ 0 ][ $datecol ] = $data['fdate'];
		$chart [ 'chart_data' ][ 1 ][ $datecol ] = $data['min(qty)'];
		$datecol++;
	}
	$chart['axis_category']=array('size'=>10,'color'=>"ffffff",'alpha'=>50,'font'=>"arial",'bold'=>true,'skip'=>0,'orientation'=>"horizontal");
	$chart['axis_ticks']=array('value_ticks'=>true,'category_ticks'=>true,'major_thickness'=>2,'minor_thickness'=>1,'minor_count'=>1,'major_color'=>"000000",'minor_color'=>"222222",'position'=>"outside");
	$chart['axis_value']=array('min'=>0,'max'=>$maxval,'font'=>"arial",'bold'=>true,'size'=>10,'color'=>"ffffff",'alpha'=>50,'steps'=>6,'prefix'=>"",'suffix'=>"",'decimals'=>0,'separator'=>"",'show_min'=>true);
	$chart['chart_border']=array('color'=>"000000",'top_thickness'=>2,'bottom_thickness'=>2,'left_thickness'=>2,'right_thickness'=>2);
	$chart['chart_grid_h']=array('alpha'=>10,'color'=>"000000",'thickness'=>1,'type'=>"solid");
	$chart['chart_grid_v']=array('alpha'=>10,'color'=>"000000",'thickness'=>1,'type'=>"solid");
	$chart['chart_pref']=array('line_thickness'=>2,'point_shape'=>"none",'fill_shape'=>false);
	$chart['chart_rect']=array('x'=>40,'y'=>25,'width'=>535,'height'=>200,'positive_color'=>"000000",'positive_alpha'=>30,'negative_color'=>"ff0000",'negative_alpha'=>10);
	$chart['chart_type']="Line";
	$chart['chart_value']=array('prefix'=>"    ",'suffix'=>"",'decimals'=>0,'separator'=>"",'position'=>"cursor",'hide_zero'=>true,'as_percentage'=>false,'font'=>"arial",'bold'=>true,'size'=>15,'color'=>"ffffff",'alpha'=>75);
	$chart['draw']=array(
		array('type'=>"text",'color'=>"ffffff",'alpha'=>15,'font'=>"arial",'rotation'=>-90,'bold'=>true,'size'=>50,'x'=>-10,'y'=>348,'width'=>300,'height'=>150,'text'=>"qty",'h_align'=>"center",'v_align'=>"top"),
		array('type'=>"text",'color'=>"000000",'alpha'=>15,'font'=>"arial",'rotation'=>0,'bold'=>true,'size'=>60,'x'=>0,'y'=>0,'width'=>320,'height'=>300,'text'=>"output",'h_align'=>"left",'v_align'=>"bottom")
	);
	$chart['legend_rect']=array('x'=>-100,'y'=>-100,'width'=>10,'height'=>10,'margin'=>10);
	$chart['series_color']=array("77bb11","cc5511");
	SendChartData($chart);

?>
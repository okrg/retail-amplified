<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include("include/access.php");

function booleanFormat($str) {
  if($str == 0)
    return 'No';
  if($str == 1)
    return 'Yes';
}

function dateFormat($str) {
  if ($str == "0000-00-00")
    //return 'TBD';
    return '';
  if (empty($str)) {
    //return 'TBD';
    return '';
  }
  
  if (is_mysql_date($str)) {
    return date("m/d/Y", strtotime($str));  
  } else  {
    return $str;
  }  
}


function is_mysql_date( $str ){ 
  if(!strstr($str, '-')){
  return FALSE;
  }
  $stamp = strtotime( $str ); 
  if (!is_numeric($stamp)) 
      return FALSE; 
  $month = date( 'm', $stamp ); 
  $day   = date( 'd', $stamp ); 
  $year  = date( 'Y', $stamp ); 
  if (checkdate($month, $day, $year)) 
      return TRUE; 
  return FALSE; 
}

function loadData($table) {
  global $dbcnx;
  $id = mysqli_escape_string($dbcnx, $_GET['id']);
  $query = "SELECT * FROM $table WHERE project_id = $id";
  $result = mysqli_query($dbcnx, $query) or die ("no query");  
  $data = array();
  
  while($row = mysqli_fetch_assoc($result)) {
      $data[] = $row;
  }
  if ($data){
    return $data[0];
  } else {
    return false;
  }
} 

 
$id = mysqli_escape_string($dbcnx, $_GET['id']);

$query = "SELECT * FROM projects WHERE id = $id";
$result = mysqli_query($dbcnx, $query) or die ( $query . mysqli_error($dbcnx) );  
$data = array();

while($row = mysqli_fetch_assoc($result)) {
  $data[] = $row;
}
$project = @$data[0];


$realestate = loadData('realestate');
$centerinfo = loadData('re_centerinfo');
$strategy = loadData('re_strategy');
$storedesign = loadData('re_storedesign');
$kickouts = loadData('re_kickouts');
$options = loadData('re_options');
$extra_charges = loadData('re_extra_charges');
$scheduled = loadData('scheduled_dates');
$actual = loadData('actual_dates');
$deal_economics = loadData('re_deal_economics');

//Calculate totals
$deal_economics['cur_total_extras_amt'] = 
  $deal_economics['cur_re_taxes_amt'] +
  $deal_economics['cur_electric_amt'] +
  $deal_economics['cur_water_amt'] +
  $deal_economics['cur_hvac_amt'] +
  $deal_economics['cur_trash_amt'];

$deal_economics['cur_total_extras_psf'] = 
  $deal_economics['cur_re_taxes_psf'] +
  $deal_economics['cur_electric_psf'] +
  $deal_economics['cur_water_psf'] +
  $deal_economics['cur_hvac_psf'] +
  $deal_economics['cur_trash_psf'];

$deal_economics['cur_total_occupancy_amt'] = 
  $deal_economics['cur_base_rent_amt'] +
  $deal_economics['cur_total_extras_amt'];

$deal_economics['cur_total_occupancy_psf'] = 
  $deal_economics['cur_base_rent_psf'] +
  $deal_economics['cur_total_extras_psf'];

$deal_economics['new_total_extras_amt'] = 
  $deal_economics['new_re_taxes_amt'] +
  $deal_economics['new_electric_amt'] +
  $deal_economics['new_water_amt'] +
  $deal_economics['new_hvac_amt'] +
  $deal_economics['new_trash_amt'];

$deal_economics['new_total_extras_psf'] = 
  $deal_economics['new_re_taxes_psf'] +
  $deal_economics['new_electric_psf'] +
  $deal_economics['new_water_psf'] +
  $deal_economics['new_hvac_psf'] +
  $deal_economics['new_trash_psf'];

$deal_economics['new_total_occupancy_amt'] = 
  $deal_economics['new_y1_annual_amt'] +
  $deal_economics['new_total_extras_amt'];

$deal_economics['new_total_occupancy_psf'] = 
  $deal_economics['new_y1_cost_psf'] +
  $deal_economics['new_total_extras_psf'];

$deal_economics['difference'] =
  $deal_economics['new_total_occupancy_amt'] - 
  $deal_economics['cur_total_occupancy_amt'];

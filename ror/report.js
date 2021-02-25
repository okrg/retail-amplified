 /**
 * @author rolo
 */
function selectlabel(id) {
	document.getElementById(id).checked = 'true';
}

function toggleChecks(x) {
myCollection = document.report_builder.elements[''+x+'[]'];
for(i=0;i<myCollection.length;i++){
	if (myCollection[i].disabled) {
	myCollection[i].disabled=false;
	} else {
		myCollection[i].disabled=true;
	}
}
}

function SpecifyButtonChecked() {
var buttonchecked = false;
if(document.report_builder.date_option[7].checked == true) {
	buttonchecked = true;
	document.getElementById('date_from_month').disabled = false;
	document.getElementById('date_from_day').disabled = false;
	document.getElementById('date_from_year').disabled = false;
	document.getElementById('date_to_month').disabled = false;
	document.getElementById('date_to_day').disabled = false;
	document.getElementById('date_to_year').disabled = false;
	} else {
	buttonchecked = false;
	document.getElementById('date_from_month').disabled = true;
	document.getElementById('date_from_day').disabled = true;
	document.getElementById('date_from_year').disabled = true;
	document.getElementById('date_to_month').disabled = true;
	document.getElementById('date_to_day').disabled = true;
	document.getElementById('date_to_year').disabled = true;
	}
}
function ItemButtonChecked() {
var buttonchecked = false;
if(document.report_builder.item_option[2].checked == true) {
	buttonchecked = true;
	document.getElementById('item_specify').disabled = false;
	} else {
	buttonchecked = false;
	document.getElementById('item_specify').disabled = true;
	}
}
function AtLeastButtonChecked() {
var buttonchecked = false;
if(document.report_builder.responses_option[2].checked == true) {
	buttonchecked = true;
	document.getElementById('responses_select').disabled = false;
	} else {
	buttonchecked = false;
	document.getElementById('responses_select').disabled = true;
	}
}

function VendorButtonChecked() {
var buttonchecked = false;
if(document.report_builder.vendor_option[2].checked == true) {
	buttonchecked = true;
	document.getElementById('vendor_select').disabled = false;
	} else {
	buttonchecked = false;
	document.getElementById('vendor_select').disabled = true;
	}
}

function RegionButtonChecked() {
var buttonchecked = false;
if(document.report_builder.region_option[2].checked == true) {
	buttonchecked = true;
	document.getElementById('region_specify').disabled = false;
	} else {
	buttonchecked = false;
	document.getElementById('region_specify').disabled = true;
	}
}
function DistrictButtonChecked() {
var buttonchecked = false;
if(document.report_builder.district_option[2].checked == true) {
	buttonchecked = true;
	document.getElementById('district_specify').disabled = false;
	} else {
	buttonchecked = false;
	document.getElementById('district_specify').disabled = true;
	}
}
function StoreButtonChecked() {
var buttonchecked = false;
if(document.report_builder.store_option[3].checked == true) {
	buttonchecked = true;
	document.getElementById('store_range').disabled = false;
	} else {
	buttonchecked = false;
	document.getElementById('store_range').disabled = true;
	}
if(document.report_builder.store_option[2].checked == true) {
	buttonchecked = true;
	document.getElementById('store_specify').disabled = false;
	} else {
	buttonchecked = false;
	document.getElementById('store_specify').disabled = true;
	}
}
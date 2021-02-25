/**
 * @author rolo
 */

//Function to check all boxes on new adming g2
var checkflag = "false";
function check(field) {
	if (checkflag == "false") {
	for (i = 0; i < field.length; i++) {
	field[i].checked = true;}
	checkflag = "true";
	return "Uncheck All"; }
	else {
	for (i = 0; i < field.length; i++) {
	field[i].checked = false; }
	checkflag = "false";
	return "Check All"; }
}

addEvent(window, "load", sortables_init);
var SORT_COLUMN_INDEX;
function sortables_init() {
    // Find all tables with class sortable and make them sortable
    if (!document.getElementsByTagName) return;
    tbls = document.getElementsByTagName("table");
    for (ti=0;ti<tbls.length;ti++) {
        thisTbl = tbls[ti];
        if (((' '+thisTbl.className+' ').indexOf("sortable") != -1) && (thisTbl.id)) {
            //initTable(thisTbl.id);
            ts_makeSortable(thisTbl);
        }
    }
}

function ts_makeSortable(table) {
    if (table.rows && table.rows.length > 0) {
        var firstRow = table.rows[0];
    }
    if (!firstRow) return;
    
    // We have a first row: assume it's the header, and make its contents clickable links
    for (var i=0;i<firstRow.cells.length;i++) {
        var cell = firstRow.cells[i];
        var txt = ts_getInnerText(cell);
        cell.innerHTML = '<a href="#" class="sortheader" '+ 
        'onclick="ts_resortTable(this, '+i+');return false;">' + 
        txt+'<span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a>';
    }
}

function ts_getInnerText(el) {
	if (typeof el == "string") return el;
	if (typeof el == "undefined") { return el };
	if (el.innerText) return el.innerText;	//Not needed but it is faster
	var str = "";	
	var cs = el.childNodes;
	var l = cs.length;
	for (var i = 0; i < l; i++) {
		switch (cs[i].nodeType) {
			case 1: //ELEMENT_NODE
				str += ts_getInnerText(cs[i]);
				break;
			case 3:	//TEXT_NODE
				str += cs[i].nodeValue;
				break;
		}
	}
	return str;
}

function ts_resortTable(lnk,clid) {
    // get the span
    var span;
    for (var ci=0;ci<lnk.childNodes.length;ci++) {
        if (lnk.childNodes[ci].tagName && lnk.childNodes[ci].tagName.toLowerCase() == 'span') span = lnk.childNodes[ci];
    }
    var spantext = ts_getInnerText(span);
    var td = lnk.parentNode;
    var column = clid || td.cellIndex;
    var table = getParent(td,'TABLE');
    
    // Work out a type for the column
    if (table.rows.length <= 1) return;
    var itm = ts_getInnerText(table.rows[1].cells[column]);
    sortfn = ts_sort_caseinsensitive;
    if (itm.match(/^\d\d[\/-]\d\d[\/-]\d\d\d\d$/)) sortfn = ts_sort_date;
    if (itm.match(/^\d\d[\/-]\d\d[\/-]\d\d$/)) sortfn = ts_sort_date;
    if (itm.match(/^[£$]/)) sortfn = ts_sort_currency;
    if (itm.match(/^[\d\.]+$/)) sortfn = ts_sort_numeric;
    SORT_COLUMN_INDEX = column;
    var firstRow = new Array();
    var newRows = new Array();
    for (i=0;i<table.rows[0].length;i++) { firstRow[i] = table.rows[0][i]; }
    for (j=1;j<table.rows.length;j++) { newRows[j-1] = table.rows[j]; }

    newRows.sort(sortfn);

    if (span.getAttribute("sortdir") == 'down') {
        ARROW = '&nbsp;&nbsp;&uarr;';
        newRows.reverse();
        span.setAttribute('sortdir','up');
    } else {
        ARROW = '&nbsp;&nbsp;&darr;';
        span.setAttribute('sortdir','down');
    }
    
    // We appendChild rows that already exist to the tbody, so it moves them rather than creating new ones
    // don't do sortbottom rows
    for (i=0;i<newRows.length;i++) { if (!newRows[i].className || (newRows[i].className && (newRows[i].className.indexOf('sortbottom') == -1))) table.tBodies[0].appendChild(newRows[i]);}
    // do sortbottom rows only
    for (i=0;i<newRows.length;i++) { if (newRows[i].className && (newRows[i].className.indexOf('sortbottom') != -1)) table.tBodies[0].appendChild(newRows[i]);}
    
    // Delete any other arrows there may be showing
    var allspans = document.getElementsByTagName("span");
    for (var ci=0;ci<allspans.length;ci++) {
        if (allspans[ci].className == 'sortarrow') {
            if (getParent(allspans[ci],"table") == getParent(lnk,"table")) { // in the same table as us?
                allspans[ci].innerHTML = '&nbsp;&nbsp;&nbsp;';
            }
        }
    }
        
    span.innerHTML = ARROW;
}

function getParent(el, pTagName) {
	if (el == null) return null;
	else if (el.nodeType == 1 && el.tagName.toLowerCase() == pTagName.toLowerCase())	// Gecko bug, supposed to be uppercase
		return el;
	else
		return getParent(el.parentNode, pTagName);
}
function ts_sort_date(a,b) {
    // y2k notes: two digit years less than 50 are treated as 20XX, greater than 50 are treated as 19XX
    aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]);
    bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]);
    if (aa.length == 10) {
        dt1 = aa.substr(6,4)+aa.substr(3,2)+aa.substr(0,2);
    } else {
        yr = aa.substr(6,2);
        if (parseInt(yr) < 50) { yr = '20'+yr; } else { yr = '19'+yr; }
        dt1 = yr+aa.substr(3,2)+aa.substr(0,2);
    }
    if (bb.length == 10) {
        dt2 = bb.substr(6,4)+bb.substr(3,2)+bb.substr(0,2);
    } else {
        yr = bb.substr(6,2);
        if (parseInt(yr) < 50) { yr = '20'+yr; } else { yr = '19'+yr; }
        dt2 = yr+bb.substr(3,2)+bb.substr(0,2);
    }
    if (dt1==dt2) return 0;
    if (dt1<dt2) return -1;
    return 1;
}

function ts_sort_currency(a,b) { 
    aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]).replace(/[^0-9.]/g,'');
    bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]).replace(/[^0-9.]/g,'');
    return parseFloat(aa) - parseFloat(bb);
}

function ts_sort_numeric(a,b) { 
    aa = parseFloat(ts_getInnerText(a.cells[SORT_COLUMN_INDEX]));
    if (isNaN(aa)) aa = 0;
    bb = parseFloat(ts_getInnerText(b.cells[SORT_COLUMN_INDEX])); 
    if (isNaN(bb)) bb = 0;
    return aa-bb;
}

function ts_sort_caseinsensitive(a,b) {
    aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]).toLowerCase();
    bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]).toLowerCase();
    if (aa==bb) return 0;
    if (aa<bb) return -1;
    return 1;
}

function ts_sort_default(a,b) {
    aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]);
    bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]);
    if (aa==bb) return 0;
    if (aa<bb) return -1;
    return 1;
}


function addEvent(elm, evType, fn, useCapture)
// addEvent and removeEvent
// cross-browser event handling for IE5+,  NS6 and Mozilla
// By Scott Andrew
{
  if (elm.addEventListener){
    elm.addEventListener(evType, fn, useCapture);
    return true;
  } else if (elm.attachEvent){
    var r = elm.attachEvent("on"+evType, fn);
    return r;
  } else {
    alert("Handler could not be removed");
  }
} 

//function $() {
//    var elements = new Array();
//    for (var i = 0; i < arguments.length; i++) {
//      var element = arguments[i];
//      if (typeof element == 'string') {
//   		 if (window.main.document.getElementById) {
//         element = window.main.document.getElementById(element);
//       } else if (window.main.document.all) {
//         element = window.main.document.all[element];
//       }
//      }
//      elements.push(element);
//    }
//    if (arguments.length == 1 && elements.length > 0) {
//      return elements[0];
//    } else {
//      return elements;
//    }
//}


// Get base url
url = document.location.href;
xend = url.lastIndexOf("/") + 1;
var base_url = url.substring(0, xend);
var ajax_get_error = false;

function ajax_do (url) {
	document.getElementById('progress').style.visibility = "visible";
	ajax_return (url);
}
function ajax_return(url){
	// Does URL begin with http?
	if (url.substring(0, 4) != 'http') {
		url = base_url + url;
	}
	//add shit to the end of the url...
	url = url + "&rand=" + new Date().getTime();
	// Create new JS element
	var jsel = document.createElement('SCRIPT');
	jsel.type = 'text/javascript';
	jsel.src = url;
	// Append JS element (therefore executing the 'AJAX' call)
	document.body.appendChild (jsel);
	setTimeout('hidespinner()',1000)
}
function hidespinner(){document.getElementById('progress').style.visibility = "hidden";}


function ajax_get (url, el) {
	// Has element been passed as object or id-string?
	if (typeof(el) == 'string') {
		el = document.getElementById(el);
	}

	// Valid el?
	if (el == null) { return false; }

	// Does URL begin with http?
	if (url.substring(0, 4) != 'http') {
		url = base_url + url;
	}

	// Create getfile URL
	getfile_url = base_url + 'getfile.php?url=' + escape(url) + '&el=' + escape(el.id);

	// Do Ajax
	ajax_do (getfile_url);

	return true;
}

//Function to change request entry from pending to answered
function signalResponse(el) {
window.parent.document.getElementById(el).className='answered';
}

function toggleBox(szDivID, iState) // 1 visible, 0 hidden
{
    if(document.layers)	   //NN4+
    {
       document.layers[szDivID].display = iState ? "block" : "none";
    }
    else if(document.getElementById)	  //gecko(NN6) + IE 5+
    {
        var obj = document.getElementById(szDivID);
        obj.style.display = iState ? "block" : "none";
    }
    else if(document.all)	// IE 4
    {
        document.all[szDivID].style.display = iState ? "block" : "none";
    }
}
function fillText() {
	document.editor.body.value=document.editor.preset.value;
}

function toggleChecks(x) {
myCollection = document.editor.elements[''+x+'[]'];
for(i=0;i<myCollection.length;i++){
	if (myCollection[i].disabled) {
	myCollection[i].disabled=false;z
	} else {
		myCollection[i].disabled=true;
	}
}
}
function ViewPop(url, windowid)
{
	var w = window.open(url, windowid, "width=760px,height=650px,resizable=yes,scrollbars=1", "");

}
//var newwindow = '';
//
//function ViewPop(url,windowid) {
//	if (!newwindow.closed && newwindow.location) {
//		newwindow.location.href = url;
//	}
//	else {
//		newwindow=window.open(url,windowid,'height=760px,width=650px,resizable=yes,scrollbars=1');
//		if (!newwindow.opener) newwindow.opener = self;
//	}
//	if (window.focus) {newwindow.focus()}
//
//}



function report_build() {
	var ck = Sortable.serialize('column');
	document.report_builder.colkeys.value=ck;
	document.report_builder.submit();
}

function workit(id,form) {
	var w = window.open("about:blank", "view", "width=760px,height=650px,resizable=yes,scrollbars=1", "");
	document.getElementById(form).starter.value=id;
	document.getElementById(form).submit();
}

function workbox(form) {
	var w = window.open("about:blank", "view", "width=760px,height=650px,resizable=yes,scrollbars=1", "");
	document.getElementById(form).submit();
}

function nav_rt(id,mode) {
	document.editor.action="view.php?workset=1&mode="+mode;
	document.editor.newstarter.value=id;
	document.editor.submit();
}

function nav_mark(id) {
	document.editor.action="edit.php?closeout=1";
	document.editor.submit();
}

function submit_next(id) {
	document.editor.newstarter.value=id;
	document.editor.submit();
}

function submitView(id) {
	document.view.record.value='id';
	document.view.submit();
}

function expandAll(uid,status,mode) {
	for (i=0;i<rts.length;i++) {
			ajax_do('rt_data.php?uid='+uid+'&m='+mode+'&s='+status+'&loc='+rts[i]+'');
	}
}

function checkAll(field) {
	for (i = 0; i < field.length; i++) {
		field[i].checked = true;
	}
}

<!-- Copyright 2003 Bontrager Connection, LLC
// For more information and instructions, please see 
//    "Disabling/Enabling Form Fields Dynamically" -- 
//    http://willmaster.com/possibilities/archives

function TextareaMaleChanged() {
if(document.request.MalePerspective.value.length > 100) { document.request.MalePerspective.value = document.request.MalePerspective.value.substr(0,100); }
if(document.request.MalePerspective.value.length <= 0) { document.request.malecharactercount.value = ''; }
else { document.request.malecharactercount.value = document.request.MalePerspective.value.length; }
} // function TextareaMaleChanged()

function TextareaFemaleChanged() {
if(document.request.FemalePerspective.value.length > 100) { document.request.FemalePerspective.value = document.request.FemalePerspective.value.substr(0,100); }
if(document.request.FemalePerspective.value.length <= 0) { document.request.femalecharactercount.value = ''; }
else { document.request.femalecharactercount.value = document.request.FemalePerspective.value.length; }
} // function TextareaFemaleChanged()

function RadioButtonChecked() {
var buttonchecked = false;
if(document.request.options[0].checked == true) {
	buttonchecked = true;
	document.getElementById('repair_label').style.color = "red"
	document.getElementById('repair_label').style.fontWeight = "bold"
	document.getElementById('fixture_label').style.color = "black"
	document.getElementById('fixture_label').style.fontWeight = "normal"
	
	document.getElementById('ror_options_td').className = 'active';
	document.getElementById('freq_options_td').className = 'inactive';
	document.request.fixture.value = '';
	document.request.fixture.disabled = true;
	document.getElementById('frad_20').disabled = true;
	document.getElementById('frad_30').disabled = true;
	document.request.qty.value = '';
	document.request.qty.disabled = true;
	document.request.replacement.checked = false;
	document.request.replacement.disabled = true;
	document.request.ror_type.disabled = false;
	document.getElementById('rad_10').disabled = false;
	document.getElementById('rad_20').disabled = false;
	document.getElementById('rad_30').disabled = false;
	//document.getElementById('rad_40').disabled = false;
	document.getElementById('rad_50').disabled = false;
	
	}
else if(document.request.options[1].checked == true) {
	buttonchecked = true;
	document.getElementById('fixture_label').style.color = "red"
	document.getElementById('fixture_label').style.fontWeight = "bold"
	document.getElementById('repair_label').style.color = "black"
	document.getElementById('repair_label').style.fontWeight = "normal"

	document.getElementById('freq_options_td').className = 'active';
	document.getElementById('ror_options_td').className = 'inactive';
	document.request.ror_type.value = '';
	document.request.ror_type.disabled = true;
	document.getElementById('rad_10').disabled = true;
	document.getElementById('rad_20').disabled = true;
	document.getElementById('rad_30').disabled = true;
	//document.getElementById('rad_40').disabled = true;
	document.getElementById('rad_50').disabled = true;
	document.request.fixture.disabled = false;
	document.getElementById('frad_20').disabled = false;
	document.getElementById('frad_30').disabled = false;
	document.request.qty.disabled = false;
	document.request.replacement.disabled = false;
	}

} // function RadioButtonChecked()

function today1(v1,v2,v3) {
	document.editor.request_filled_month.selectedIndex=v1;
	document.editor.request_filled_day.selectedIndex=v2;
	document.editor.request_filled_year.value=v3;
}

function today2(v1,v2,v3) {
	document.editor.target_month.selectedIndex=v1;
	document.editor.target_day.selectedIndex=v2;
	document.editor.target_year.value=v3;
}

function today3(v1,v2,v3) {
	document.editor.ship_month.selectedIndex=v1;
	document.editor.ship_day.selectedIndex=v2;
	document.editor.ship_year.value=v3;
}

function today4(v1,v2,v3) {
	document.editor.recv_month.selectedIndex=v1;
	document.editor.recv_day.selectedIndex=v2;
	document.editor.recv_year.value=v3;
}

function confirmDelete()
{
    return confirm("Are you sure you wish to delete this entry?");
}
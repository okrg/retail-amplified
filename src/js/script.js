function addbookmark(){
var bookmarkurl="http://construction.charlotte-russe.com/index.php?page=admin-freq"
var bookmarktitle="It's The Fixture Request Site"
if (document.all)
     window.external.AddFavorite(bookmarkurl,bookmarktitle)
}

function expandCat() {
  var items = expandCat.arguments.length;
   for (i = 0;i < items;i++) {
      ajax_do('freqdata.php?id='+expandCat.arguments[i]+'');
      toggleBox('box'+expandCat.arguments[i]+'',1);
  }
}


function collapseCat() {
  var items = collapseCat.arguments.length;
   for (i = 0;i < items;i++) {
      toggleBox('box'+collapseCat.arguments[i]+'',0);
  }
}

function clickclear(thisfield, defaulttext) {
  if (thisfield.value == defaulttext) {
  thisfield.value = "";
  }
}

function expandChecked(lib,field) {
  for (i=0;i<field.length;i++) {
    if (field[i].checked) {
      ajax_do(''+lib+'.php?id='+field[i].value+'');
      toggleBox('box'+field[i].value+'',1);
    }
  }
}


function collapseChecked(field) {
  for (i=0;i<field.length;i++) {
    if (field[i].checked) {
      toggleBox('box'+field[i].value+'',0);
    }
  }
}



function toggleChecks(x) {
myCollection = document.reports.elements[''+x+'[]'];
for(i=0;i<myCollection.length;i++){
  if (myCollection[i].disabled) {
  myCollection[i].disabled=false;
  } else {
    myCollection[i].disabled=true;
  }
}


}
function confirmation(domvar) {
  var agree=confirm("Are you sure you wish to continue?");
  if (agree)
    document.actions.action='index.php?page=admin-freq&action='+domvar;
    document.actions.submit();
  
}


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

//Function to change request entry from pending to answered
function signalResponse(el) {
window.parent.document.getElementById(el).className='answered';
}


// Get base url
url = document.location.href;
xend = url.lastIndexOf("/") + 1;
var base_url = url.substring(0, xend);

var ajax_get_error = false;

function ajax_do (url) {
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

  return true;
}

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

function validateZip(theForm)
{
  if (!validRequired(theForm.new_folder_name,"Folder Name"))
    return false;
  if (!checkVal(theForm.new_folder_name, "Folder Name"))
    return false;
  if (!validRequired(theForm["userfile[]"], "File"))
    return false;
  return true;
}

function validateSingle(theForm)
{
  if (!validRequired(theForm["userfile[]"], "File"))
    return false;
  return true;
}

function validateMultiple(theForm)
{
  if (!validRequired(theForm.new_folder_name,"Folder Name"))
    return false;
  if (!checkVal(theForm.new_folder_name, "Folder Name"))
    return false;
  return true;
}

function validateGallery(theForm)
{
  if (!validRequired(theForm.new_folder_name,"Album Name"))
    return false;
  if (!checkVal(theForm.new_folder_name, "Album Name"))
    return false;
  if (!validRequired(theForm["userfile[]"], "File"))
    return false;
  return true;
}

function checkVal(formField, fieldLabel)
{
  var result = true;
  if  ( /[(#@%&^*'"()!)?]/.test(formField.value))
  {
    alert('Invalid characters in "' + fieldLabel +'" field.');
    formField.focus();
    result = false;
  }
  return result;
}


function validRequired(formField,fieldLabel)
{
  var result = true;
  
  if (formField.value == "")
  {
    alert('Please enter a value for the "' + fieldLabel +'" field.');
    formField.focus();
    result = false;
  }
  
  return result;
}


function toggleBox(szDivID, iState) // 1 visible, 0 hidden
{
    if(document.layers)    //NN4+
    {
       document.layers[szDivID].display = iState ? "block" : "none";
    }
    else if(document.getElementById)    //gecko(NN6) + IE 5+
    {
        var obj = document.getElementById(szDivID);
        obj.style.display = iState ? "block" : "none";
    }
    else if(document.all) // IE 4
    {
        document.all[szDivID].style.display = iState ? "block" : "none";
    }
}
// -->


function goto_anchor(object) {
    window.location.href = object.options[object.selectedIndex].value;
}


function noenter() {
  return !(window.event && window.event.keyCode == 13); }

function setDelFile(projFolder, projFile) {
  document.del.del_file_name.value = projFile;
  document.del.del_file_path.value = projFolder + projFile;
  return false;
}

function setDelbudgFile(projFolder, projFile) {
  document.delbudg.delbudg_file_name.value = projFile;
  document.delbudg.delbudg_file_path.value = projFolder + projFile;
  return false;
}

function setDelmiscFile(projFolder, projFile) {
  document.delmisc.delmisc_file_name.value = projFile;
  document.delmisc.delmisc_file_path.value = projFolder + projFile;
  return false;
}

function setDelVendorFile(projFolder, projFile) {
  document.delvendor.delvendorfile_name.value = projFile;
  document.delvendor.delvendorfile_path.value = projFolder + projFile;
  return false;
}

$(document).on('click', '[data-toggle="lightbox"]', function(event) {
  event.preventDefault();
  $(this).ekkoLightbox({
    //alwaysShowClose: true,

  });
});

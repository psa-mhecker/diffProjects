function _PopUpParam (iwidth, iheight, mb, rs, sb, st, tb) {
	var strProperties;
	if (screen ) {
		var iPosL = (screen.width-iwidth)/2;
		var iPosT = (screen.height-iheight)/2;
	} else {
		var iPosL = 100;
		var iPosT = 100;
	}
	if (navigator.appVersion.indexOf("MSIE") != -1 ) {
		iwidth = iwidth+26;
	}
	strProperties = "width=" + iwidth + ",height=" + iheight;
	strProperties += ",left=" + iPosL + ",top=" + iPosT + ",directories=0";
	strProperties += ",hotkeys=1,location=0,menubar=" + mb;
	strProperties += ",resizable=" + rs + ",scrollbars=" + sb;
	strProperties += ",status=" + st + ",titlebar=0,toolbar=" + tb;
	return strProperties;
}

function popupSimpleNoScroll(PageURL, strName, iwidth, iheight) {
	return window.open(PageURL, strName, _PopUpParam(iwidth, iheight, 0, 0, 0, 0, 0));
}

function popupSimple(PageURL, strName, iwidth, iheight) {
	return window.open(PageURL, strName, _PopUpParam(iwidth, iheight, 0, 0, 1, 1, 0));
}

function popupMenuSimple(PageURL, strName, iwidth, iheight) {
	return window.open(PageURL, strName, _PopUpParam(iwidth, iheight, 1, 0, 1, 1, 0));
}

function CheckWindow (obj) {
	if (obj && !obj.closed )
	obj.close();
}

function popupMedia(input, strType, strPath, Field, Div, strGroup, UPLOAD_HTTP, UPLOAD_PATH, bLibrary) {
	var arr = "";
	var args = new Object;
	var strParam = "";
	var arr;
	args["mediaZone"] = "direct";
	args["rootPath"] = UPLOAD_PATH;
	args["groupPath"] = strGroup;
	args["mediaType"] = strType;
	args["format"] = "no";
	args["opener"] = window.document;
	args["Field"] = Field;
	args["Div"] = Div;
	if (bLibrary) {
		arr = showModalDialog(strPath + "/popup_media.php", args, "dialogWidth:640px; dialogHeight:470px; scroll:no; status:no; center:yes; help:no" );
	} else {
		arr = showModalDialog(strPath + "/popup_upload.htm", args, "dialogWidth:400px; dialogHeight:150px; scroll:no; status:no; center:yes; help:no" );
	}
	if (arr) {
		returnPopupMedia(arr[0], arr[1], arr[2], arr[3], arr[4]);
	}
}

function returnPopupMedia(media_id, tag, media_path, Field, Div) {
	Field.value = media_id;
	document.getElementById(Div).innerHTML = tag.replace(" href="," id='img" + Div + "' href=") + "&nbsp;&nbsp;";
}

function mediatPathWithFormat(path, format) {
    var lastDotpos = path.lastIndexOf('.');
	var now = new Date().getTime();
    return path.substring(0,lastDotpos)+'.'+format+path.substring(lastDotpos)+'?autocrop=1&t='+now;
}


function returnPopupMediaCroped(mediaId, mediaPath, field, div) {
	div = div.replace(new RegExp('\\[', 'g'),'\\[').replace(new RegExp('\\]', 'g'),'\\]');
    var $div = $('#'+div), $cont, cropId, $link, $cropLink;
	field.value = mediaId;
    $div.find('.crop-container').each(function(idx,elm) {
        $cont  = $(elm);
        $cont.show();
        cropId = $cont.data('crop');
        $link =  $cont.find('a:eq(0)');
        $link.attr('href',mediatPathWithFormat(mediaPath, cropId));
        $link.data('original', mediaPath);
        $link.html('<img src="'+mediatPathWithFormat(mediaPath, cropId)+'" height="40" />');

        $cropLink =  $cont.find('a:eq(1)');
        $cropLink.data('src',mediaPath);
    });
}

function popupColor(obj, obj2) {

	args = obj;
	var arr = showModalDialog(libDir+"/Pelican/Form/public/popup_colorpicker.htm", args, "dialogWidth:430px; dialogHeight:270px; scroll:no; status:no; center:yes; help:no" );

	if (arr != null) {
		returnPopupColor(obj, obj2, arr);
	}
}

function returnPopupColor(obj, obj2, arr) {
	obj.value = arr.toUpperCase();
	obj2.style.backgroundColor = arr.toUpperCase();
	obj.focus();
}

function popupInternalLink(obj) {

	args = obj;
	args["page"] = "";
	var arr = showModalDialog(libDir+"/Pelican/Form/public/popup_internallink.php", args, "dialogWidth:600px; dialogHeight:250px; scroll:yes; status:no; center:yes; help:no" );

	if (arr != null) {
		returnPopupInternalLink(obj, arr);
	}
}
function returnPopupInternalLink(obj, value) {
	obj.value = value;
	obj.focus();
}

var editor;

// popup editor version active X
function popupEditor(boxName, subfolder, limited) {
	editor = window.open(libDir+"/Pelican/Form/public/dhtml_editor.php?boxName=" + boxName + "&subfolder=" + subfolder + "&limited=" + limited, 'popup_editor', 'width=700,height=500,toolbar=no,status=yes,resizable=yes,menubar=no,scrollbars=no');
}
// popup editor version tinyMce
function popupEditor2(boxName, subfolder, limited) {
	if (editor) {
		editor.close();
	}
	editor = window.open(libDir+"/Pelican/Form/public/tinymce.php?boxName=" + boxName + "&subfolder=" + subfolder + "&limited=" + limited, 'popup_editor', 'width=700,height=500,toolbar=no,status=yes,resizable=yes,menubar=no,scrollbars=no');
}

function cleanEditor(boxName) {
	var obj1 = eval(boxName);
	obj1.value = '';
	var input = boxName.split('.');
	var obj2 = eval("iframeText" + input[1]);
	obj2.document.open();
	obj2.document.clear();
	obj2.document.close();
}

function resizeInner(width, height) {
	if (window.innerWidth) {
		window.innerWidth = width;
		window.innerHeight = height;
	} else {
		width += 5;
		height += 30;
		resizeTo(width, height);
	}
}

function popupSort(pageid, typeid, id) {
	popupSimpleNoScroll("/popup_sort.php?pid=" + pageid + "&uid=" + typeid + "&id=" + id, "tri", 500, 500);
}

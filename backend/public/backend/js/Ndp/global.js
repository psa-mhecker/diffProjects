function popupMediaRatio(strType, strPath, Field, Div, strGroup, UPLOAD_HTTP, UPLOAD_PATH, bLibrary, ratio) {
	var args = new Object;
	var arr;
	args["mediaZone"] = "direct";
	args["rootPath"] = UPLOAD_PATH;
	args["groupPath"] = strGroup;
	args["mediaType"] = strType;
	args["format"] = "no";
	args["opener"] = window.document;
	args["Field"] = Field;
	args["Div"] = Div;
    args["ratio"] = ratio;
    if (bLibrary) {
        arr = showModalDialog(
            "/_/Media/popup",
            args,
            "dialogWidth:640px; dialogHeight:470px; scroll:no; status:no; center:yes; help:no");
    } else {
        arr = showModalDialog(
            "/_/Media/upload",
            args,
            "dialogWidth:400px; dialogHeight:150px; scroll:no; status:no; center:yes; help:no");
    }
	if (arr) {
		returnPopupMedia(arr[0], arr[1], arr[2], arr[3], arr[4]);
	}
}
function popupImageCrop(field, div, UPLOAD_PATH, dimension) {
	var args = new Object;
	args["mediaZone"] = "direct";
	args["rootPath"] = UPLOAD_PATH;
	args["groupPath"] = '';
	args["mediaType"] = "image";
	args["format"] = "no";
	args["opener"] = window.document;
	args["Field"] = field;
	args["Div"] = div;
	args["dimension"] = dimension;
	args["crop"] = true;
	var width = $(window).width();
	var height = $(window).height();
	if(window.parent) {
		width = $(window.parent.window).width() -50;
		height = $(window.parent.window).height() -50;
	}

    showModalDialog('/_/Media/popup?screen_width='+width+'&screen_height='+(height-100),args,'top:25px;left:25px;dialogWidth:'+width+'px;dialogHeight:'+height+'px; scroll:no; status:no; center:yes; help:no');
}


function popupMediaUsage (mediaId){
    var arr;
    var args = new Object;;
    args["mediaid"] = mediaId;
	showModalDialog(
            "/_/Media/popupUsage?media_id="+mediaId,
            args,
            "dialogWidth:640px; dialogHeight:470px; scroll:no; status:no; center:yes; help:no");
}

function searchContentFiltered(strPath, strFormName, strFieldName, strZone,
		strContentType, iSiteExterne, iSess, bShowChoisissez,contentCode2) {
	CheckWindow(wManageRef);
	wManageRef = popupSimpleNoScroll("/_/Popup/content?form="
			+ escape(strFormName) + "&field=" + escape(strFieldName) + "&zone="
			+ strZone + "&contenttype=" + strContentType + "&siteexterne="
			+ iSiteExterne + "&s=" + iSess + "&choisissez=" + bShowChoisissez+"&rechercheContentCode2="+contentCode2,
			"", 914, 470);
}


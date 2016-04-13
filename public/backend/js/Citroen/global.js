function popupMediaRatio(strType, strPath, Field, Div, strGroup, UPLOAD_HTTP, UPLOAD_PATH, bLibrary, ratio) {
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

function popupMediaUsage (mediaId){
    var arr;
    var args = new Object;;
    args["mediaid"] = mediaId;
        arr = showModalDialog(
            "/_/Media/popupUsage?media_id="+mediaId,
            args,
            "dialogWidth:640px; dialogHeight:470px; scroll:no; status:no; center:yes; help:no");
}

/**
 * Fonction de récupération des paramètres GET de la page
 * @return Array Tableau associatif contenant les paramètres GET
 */
function extractUrlParams(url){	
	var t = url.split('&');
	var f = [];
	for (var i=0; i<t.length; i++){
		var x = t[ i ].split('=');
		f[x[0]]=x[1];
	}
	return f;
}

function reloadPage(url,idIframe, trad){	
    if (confirm(trad)) {
        window.location.href = url;
        top.showLoading("#frame_right_middle",true);
    }    
}

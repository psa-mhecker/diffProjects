function popupMediaHmvc(input, strType, strPath, Field, Div, strGroup, UPLOAD_HTTP, UPLOAD_PATH, bLibrary) {
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
    if(input && $(input).attr("data-ratio")) {
        args["ratio"] = $(input).attr("data-ratio") ;
    }
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

if (typeof popupMediaHmvc == 'function') {
	popupMedia = popupMediaHmvc;
}

function popupColorHmvc(obj, obj2) {

	args = obj;
	var arr = showModalDialog(
			"/_/Popup/colorPicker",
			args,
			"dialogWidth:430px; dialogHeight:270px; scroll:no; status:no; center:yes; help:no");

	if (arr != null) {
		returnPopupColor(obj, obj2, arr);
	}
}

if (typeof popupColor == 'function') {
	popupColor = popupColorHmvc;
}

function popupInternalLinkHmvc(obj) {
        var tid = "";
        if (obj.dataset.template) {
            tid = "&TID="+obj.dataset.template;
        }
	args = obj;
	args["page"] = "";
	var arr = showModalDialog(
			"/_/Popup/internalLink?LANGUE_ID="+document.getElementById("LANGUE_ID").value+tid,
			args,
			"dialogWidth:600px; dialogHeight:250px; scroll:yes; status:no; center:yes; help:no");

	if (arr != null) {
		returnPopupInternalLink(obj, arr);
	}
}

if (typeof popupInternalLink == 'function') {
	popupInternalLink = popupInternalLinkHmvc;
}

function popupEditor2Hmvc(boxName, subfolder, limited) {
	if (editor) {
		editor.close();
	}
	editor = window
			.open(
					libDir + "/Pelican/Form/public/tinymce.php?boxName="
							+ boxName + "&subfolder=" + subfolder + "&limited="
							+ limited,
					'popup_editor',
					'width=740,height=659,toolbar=no,status=yes,resizable=yes,menubar=no,scrollbars=no');
}

if (typeof popupEditor2 == 'function') {
	popupEditor2 = popupEditor2Hmvc;
}

function popupSortHmvc(pageid, typeid, id) {
	popupSimpleNoScroll("/_/Popup/sort?pid=" + pageid + "&uid=" + typeid
			+ "&id=" + id, "tri", 500, 500);
}

if (typeof popupSort == 'function') {
	popupSort = popupSortHmvc;
}

function popupSortContentHmvc(contentid, typeid, id) {
	popupSimpleNoScroll("/_/Popup/sortContent?cid=" + contentid + "&uid=" + typeid
			+ "&id=" + id, "tri", 500, 500);
}

if (typeof popupSortContent == 'function') {
	popupSortContent = popupSortContentHmvc;
}

function addMultiHmvc(obj, name, file, prefixe, compteur, limit, numberField,
		complement) {
	hidden_multi = obj["count_" + name];
	setSequence();
	name_multi = name;
	limit_multi = limit;
	prefixe_multi = prefixe;
	iframeM = document.getElementById("iframe_" + name_multi);
	var args = new Object();

	if (limit_multi && limit_multi <= eval(hidden_multi.value) + 1) {
		alert("Maximum atteint !");
	} else {
		iframeM.src = libDir + "/Pelican/Form/public/popup_multi.php?hmvc="
				+ escape(file) + "&prefixe=" + prefixe + "&compteur="
				+ hidden_multi.value + "&numberField=" + numberField + "&fname="
				+ obj.name + complement;
	}
}

if (typeof addMulti == 'function') {
	addMulti = addMultiHmvc;
}

function searchContentHmvc(strPath, strFormName, strFieldName, strZone,
		strContentType, iSiteExterne, iSess, bShowChoisissez) {
	CheckWindow(wManageRef);
	wManageRef = popupSimpleNoScroll("/_/Popup/content?form="
			+ escape(strFormName) + "&field=" + escape(strFieldName) + "&zone="
			+ strZone + "&contenttype=" + strContentType + "&siteexterne="
			+ iSiteExterne + "&s=" + iSess + "&choisissez=" + bShowChoisissez,
			"", 914, 470);
}

if (typeof searchContent == 'function') {
	searchContent = searchContentHmvc;
}

function addRefHmvc(strPath, sFormName, strFieldName, strTableName, iRefresh, bMultiple, strActionName) {
	CheckWindow(wManageRef);
	strURL = strPath + "popup_reference.php?Form=" + sFormName + "&Field=" + strFieldName + "&Table=" + strTableName + "&Ref=" + iRefresh;
	if ((arguments.length > 5) && bMultiple ) {
		strURL += "&Mul=1";
	}
	if ((arguments.length > 6) && strActionName ) {
		strURL += "&Act="+strActionName;
	}
	wManageRef = popupSimple (strURL, "RefPopup", 400, 150);
}

if (typeof addRef == 'function') {
	addRef = addRefHmvc;
}

function changeSubHmvc(divName, params) {
	inputFile = document.getElementById('file_'+divName);
	if (document.getElementById('js_'+divName)) {
		inputJs = document.getElementById('js_'+divName);
	}
	iframeM = document.getElementById("iframe_" + divName);
	iframeM.src = libDir+"/Pelican/Form/public/popup_sub.php?divname=" +divName + "&hmvc=" + inputFile.value + "&subjs=" + inputJs.value + "&" + params;
}

if (typeof changeSub == 'function') {
	changeSub = changeSubHmvc;
}

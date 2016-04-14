// Fonctions de liste
function selectAll(obj) {
	if (obj.length > 0) {
		for (i = 0; i < obj.length; i++) {
			obj.options[i].selected = true;
		}
	}
}

function assocAdd(obj, bDelOnAdd, bOrderColName, limit, bTri) {
    bTri = typeof bTri !== 'undefined' ? bTri : true;
    var oDst = eval("obj.form.elements[\"" + obj.name.substr(3, obj.name.length-3) + "[]\"]");
    var oLastSel = eval("obj.form.elements[\"" + obj.name.substr(3, obj.name.length-3) + "_last_selected\"]");
    var optionsSelected = $('#'+obj.name+' option:selected').length;
    var emplacementLibre = limit - oDst.length;
    if (limit != 0 && (oDst.length >= limit || optionsSelected > limit || optionsSelected > emplacementLibre)) {
        alert("Maximum atteint !");
    } else {
        
        for (i = 0; i < obj.length; i++) {
            if (obj.options[i].selected && obj.options[i].value) {
                oLastSel.value = obj.options[i].value;
                bExist = false;
                for (j = 0; j < oDst.length; j++) {
                    if (oDst.options[j].value == obj.options[i].value ) {
                        bExist = true;
                    }
                }
                if (!bExist ) {
                    oDst.options[oDst.length] = new Option(obj.options[i].text, obj.options[i].value);
                }
                if (bDelOnAdd ) {
                    obj.options[i--] = null;
                }
            }
        }
        if(bTri){
            sortList(oDst);
        } 
        oDst.focus();
    }
}

function assocAddSingle(obj, bDelOnAdd) {
	var oDst = eval("obj.form.elements[\"" + obj.name.substr(3, obj.name.length-3) + "\"]");
	if (oDst.length == 0 ) {
		for (i = 0; i < obj.length; i++) {
			if (obj.options[i].selected ) {
				bExist = false;
				for (j = 0; j < oDst.length; j++) {
					if (oDst.options[j].value == obj.options[i].value ) {
						bExist = true;
					}
				}
				if (!bExist ) {
					oDst.options[oDst.length] = new Option(obj.options[i].text, obj.options[i].value);
				}
				if (bDelOnAdd ) {
					obj.options[i--] = null;
				}
			}
		}
		sortList(oDst);
	}
	oDst.focus();
}

function assocDel(obj, bDelOnAdd) {
	var oSrc = eval("obj.form.src" + obj.name.substr(0, obj.name.length-2));
	if(obj.length == 0) {
		obj.options[0] = null;
	} else {
		for (i = 0; i < obj.length; i++) {
			if (obj.options[i].selected ) {
				if (bDelOnAdd && oSrc) {
					oSrc.options[oSrc.length] = new Option(obj.options[i].text, obj.options[i].value);
				}
				obj.options[i--] = null;
			}
		}
		if ($('#'+obj.name).attr('multiple') == 'multiple') {
			$('#'+obj.name).focus();
		}
		if (oSrc) {
			if (bDelOnAdd ) {
				sortList(oSrc);
			}
			oSrc.focus();
		}
	}
}

function sortListByClick(obj)
{
	for (i = 0; i < obj.length; i++) {
		for (j = i; j < obj.length; j++) {
			
			if (i < j ) {
				vTmp = obj.options[i].text;
				obj.options[i].text = obj.options[j].text;
				obj.options[j].text = vTmp;
				vTmp = obj.options[i].value;
				obj.options[i].value = obj.options[j].value;
				obj.options[j].value = vTmp;
			}
		}
	}
}

function sortList(obj) {
	for (i = 0; i < obj.length; i++) {
		for (j = i; j < obj.length; j++) {
			if (obj.options[j].text < obj.options[i].text ) {
				vTmp = obj.options[i].text;
				obj.options[i].text = obj.options[j].text;
				obj.options[j].text = vTmp;
				vTmp = obj.options[i].value;
				obj.options[i].value = obj.options[j].value;
				obj.options[j].value = vTmp;
			}
		}
	}
}

var wManageRef;

function addRef(strPath, sFormName, strFieldName, strTableName, iRefresh, bMultiple, strActionName) {
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

function addValue(Field, Lib, Val) {
	var obj = eval(Field);
	var bExist = false;
	if (obj.length) {
		for (i = 0; i < obj.length; i++) {
			if (obj.options[i].value == Val) {
				bExist = true;
			}
		}
	}
	if (!bExist) {
		obj.options[obj.length] = new Option (Lib, Val);
		sortList(obj);
		obj.value = Val;
		return true;
	} else {
		return false;
	}
}

function delValue(Field, Val) {
	var obj = eval(Field);
	var bExist = false;
	if (obj.length) {
		for (i = 0; i < obj.length; i++) {
			if (obj.options[i].value == Val) {
				obj.options[i] = null;
				return true;
			} 
		}
		return false;
	}
}

function updValue(Field, Lib, Val) {
	var obj = eval(Field);
	var bExist = false;
	if (obj.length) {
		for (i = 0; i < obj.length; i++) {
			if (obj.options[i].value == Val) {
				bExist = true;
				obj.options[i] = null;
			}
		}
	}
	if (bExist) {
		obj.options[obj.length] = new Option (Lib, Val);
		sortList(obj);
		obj.value = Val;
		return true;
	} else {
		return false;
	}
}

function searchIndexation(strPath, strFieldName, strTableName, strFind, sSearch, bShowAll) {

	var args = new Object();
	args['Table']=strTableName;
	if (!strFind && bShowAll) {
		strFind = '%';
	}
	args['Find']=escape(strFind);
	args['Search']=sSearch;

	sendAjax(args, 'remote_indexation', strFieldName)
}


function submitIndexation(strPath, strTableName, sSearch) {
	if (event.keyCode == 13 ) {
		searchIndexation(strPath, escape(event.srcElement.name.replace("iSearchVal", "src")), strTableName, event.srcElement.value, sSearch);
		event.returnValue = false;
	}
}

function ExtendSearchContent(strPath, strFormName, strFieldName, strZone, strContentType, iSiteExterne, iSess, bShowChoisissez, iMaxElem, sMsg){
		if (document.getElementById(strFieldName).length >= iMaxElem && iMaxElem){
			alert(sMsg);
		}else{
			searchContent(strPath, strFormName, strFieldName, strZone, strContentType, iSiteExterne, iSess, bShowChoisissez, iMaxElem);
		}
}

function searchContent(strPath, strFormName, strFieldName, strZone, strContentType, iSiteExterne, iSess, bShowChoisissez) {
	CheckWindow(wManageRef);
	wManageRef = popupSimpleNoScroll(strPath + "popup_content.php?form=" + escape(strFormName) + "&field=" + escape(strFieldName) + "&zone=" + strZone + "&contenttype=" + strContentType + "&siteexterne=" + iSiteExterne + "&s=" + iSess + "&choisissez=" + bShowChoisissez, "", 914, 470);
}
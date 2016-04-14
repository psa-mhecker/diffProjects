function updateURL(clearUrl) {
	var aQuery = new Array;
	pid = '';
	cid = '';
	tpl = '';
	popup_tpl = '';
	 
	var idTitle = '';
	var typeTitle = '';
	 
	if (clearUrl) {
		sBegin = '/';
		sEqual = '';
		sJoin = '-';
		sEnd = '/';
	} else {
		sBegin = '/index.php?';
		sEqual = '=';
		sJoin = '&';
		sEnd = '';
	}
	var form = document.fForm
        if(form == undefined ){
            form = document.fwForm
        }
        if(form != 'undefined' ){
            if (form.PAGE_NAVIGATION_ID.value) {
                    pid = form.PAGE_NAVIGATION_ID.value;
                    aQuery.push("pid" + sEqual + pid);
                    idTitle = pid;
                    typeTitle = 'PAGE';
            }

            if (form.CONTENT_NAVIGATION_ID.options!= undefined && form.CONTENT_NAVIGATION_ID.options[0]) {
                    cid = form.CONTENT_NAVIGATION_ID.options[0].value;
                    aQuery.push("cid" + sEqual + cid);
                    idTitle = cid;
                    typeTitle = 'CONTENT';
            }

            if (form.TPL_ID) {
                    if (form.TPL_ID.value) {
                            tpl = form.TPL_ID.value;
                            aQuery.push("tpl" + sEqual + tpl);
                    }
            }

            switch (tpl) {
                    default:
                    if (aQuery.length) {
                            if (form.NAVIGATION_PARAMETERS.value) {
                                    //lien vers des contenu categoorie
                                    sUrl = sBegin + 'pid' + sEqual + pid + sJoin + 'tpl' + sEqual + tpl + sJoin + 'zid' + sEqual + cid;
                            } else {
                                    //affichage en lien normal
                                    sUrl = sBegin + aQuery.join(sJoin);
                            }
                            form.NAVIGATION_URL.value = sUrl + sJoin + getTitle(idTitle, typeTitle, clearUrl);
                    }
                    break;
            }
        }
	return false;
}
 
function getTitle(id, type, clearUrl) {
	var sTitle = '';
	if (clearUrl) {
		if (type == 'PAGE') {
			sTitle = document.fForm.PAGE_NAVIGATION_ID.options[document.fForm.PAGE_NAVIGATION_ID.selectedIndex].innerHTML;
		}
		if (type == 'CONTENT') {
			sTitle = document.fForm.CONTENT_NAVIGATION_ID.options[0].innerHTML;
		}
		sTitle = no_accent(sTitle);
		sTitle = sTitle.replace(/\&nbsp\; /gi, ' ');
		sTitle = trim(sTitle.replace(/\-/gi, ' '));
		sTitle = sTitle.replace(/\ /gi, '-');
		sTitle = cleanText(sTitle + '.html') ;
		sTitle = sTitle.replace(/\-html/gi, '.html');
		return sTitle;
	} else {
		return sTitle;
	}
}

function no_accent(my_string) {
	var new_string = my_string;
	var pattern_accent = new Array("Ą","Ć","Ę","Ł","Ń","Ó","Ś","Ź","Ż","ą","ć","ę","ł","ń","ó","ś","ź","ż");
	var pattern_replace_accent = new Array("A", "C", "E", "L", "N", "O", "S", "Z", "Z", "a", "c", "e", "l", "n", "o", "s", "z", "z");
	if (my_string && my_string!= "") {
		for (i=0 ; i<pattern_accent.length ; i++){
			if (pattern_accent[i] && pattern_replace_accent[i]){
				new_string = new_string.replace(pattern_accent[i], pattern_replace_accent[i]);	
			}
		}
	}
	return new_string;
}

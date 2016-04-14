function isJsonString(strSaisie) {
    try {
        JSON.parse(strSaisie);
    } catch (e) {
        return false;
    }
    return true;
}


function trim(strSaisie) {
	var strRetour = "";
	var beginPos = 0, endPos;
	if (strSaisie != "") {
		while (strSaisie.charAt(beginPos) == ' ' && beginPos < strSaisie.length) {
			beginPos++;
		}
		endPos = strSaisie.length - 1;
		while (strSaisie.charAt(endPos) == ' ' && endPos > beginPos) {
			endPos--;
		}
		for (i = beginPos; i < endPos + 1; i++) {
			strRetour = strRetour + strSaisie.charAt(i);
		}
	}
	return strRetour;
}


/**
 * A Better trim
 */
function trim_(s)
{
	return s.replace(/^\s+/, '').replace(/\s+$/, ''); 
}

function isBlank(strSaisie) {
	var iSaisie = 0;
	var strBlank = "";
 
	if (typeof strSaisie != 'undefined' && strSaisie != "") {
		for (i = 0; i < strSaisie.length; i++) {
			if (strSaisie.charAt(i) != ' ') {
				return false;
                debugger;
			}
		}
	}
	return true;
}

function isMail(S) {
	var pass = 0;
	if (window.RegExp) {
		var tempS = "a";
		var tempReg = new RegExp(tempS);
		if (tempReg.test(tempS)) pass = 1;
	}
	if (!pass)
	return (S.indexOf(".") > 2) && (S.indexOf("@") > 0);
	var r1 = new RegExp("(@.*@)|(\\.\\.)|(@\\.)|(^\\.)");
	//A CONTROLER var r2 = new RegExp("^[a-zA-Z0-9\\.\\!\\#\\$\\%\\&\\'\\*\\+\\-\\/\\=\\?\\^\\_\\`\\{\\}\\~]*[a-zA-Z0-9\\!\\#\\$\\%\\&\\'\\*\\+\\-\\/\\=\\?\\^\\_\\`\\{\\}\\~]\\@(\\[?)[a-zA-Z0-9\\-\\.]+\\.([a-zA-Z]{2,3}|[0-9]{1,3})(\\]?)$");
	var r2 = new RegExp("^[a-zA-Z0-9\\.\\-\\_]*[a-zA-Z0-9\\.\\-\\_]\\@(\\[?)[a-zA-Z0-9\\-\\.]+\\.([a-zA-Z]{2,5}|[0-9]{1,10})(\\]?)$");
	return (!r1.test(S) && r2.test(S));
}

function isURL(strSaisie) {
	var regexp = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
	return regexp.test(strSaisie);
}

function isLogin(strSaisie) {
	var r = new RegExp("^[a-zA-Z0-9][a-zA-Z0-9\\&\\.\\_\\-]{1,}$");
	return (r.test(strSaisie));
}

function nl2br(obj) {
	var temp = new RegExp("\r\n" , "gi");
	var temp2 = obj.value.replace(temp, "<br />");
	obj.value = temp2;
}

function isAlphaNum(strSaisie) {
	var re = new RegExp(/^[0-9A-Za-z\\_]+$/);
	return re.test(strSaisie);
}

function countchars(obj, imax) {
    var o = document.getElementById('cnt_' + obj.name + '_div');
    var e = document.getElementById('cnt_error_' + obj.name + '_div');
    if ( imax ) {
        if(obj.value.length >= imax){
            obj.value = obj.value.substring(0,imax);
            alert(e.innerHTML);
        }
        o.innerHTML = obj.value.length + '/' + imax + ' caract&egrave;res';
    } else {
        o.innerHTML = obj.value.length + ' caract&egrave;re' + (obj.value.length > 1?'s':'');
    }
}


var objInputDiv;

function showInputDiv(obj) {

	var list = document.getElementById(obj+"_DIV");

	var elem = document.getElementById(obj+"_IMG");

	var pos = { x: elem.offsetLeft, y: elem.offsetTop };
	if (elem.offsetParent) {
		var tmp = getAbsolutePosition(elem.offsetParent);
		pos.x += tmp.x;
		pos.y += tmp.y;
	}

	if (list.style.display == 'none') {
		list.style.top = pos.y+15;
		list.style.left = pos.x;
		list.style.display = '';
		hideSelectBoxes(list,true);
	} else {
		hideSelectBoxes(list,false);
		list.style.display = 'none';
	}

	objInputDiv = obj;
}

function selectInputDiv(id , lib) {
	if (objInputDiv) {
		var oId = document.getElementById(objInputDiv);
		var oLabel = document.getElementById(objInputDiv+"_LABEL");
		if (id) {
			oId.value = id;
			oLabel.innerHTML = lib.replace('"',"'");
		}
		showInputDiv(objInputDiv);
	}
}

function cleanText(text, bUnchange, XITI) {
	var sReturn = text;
	if (sReturn) {
		if (!bUnchange) sReturn = sReturn.toLowerCase();
		sReturn = trim(sReturn.replace(/[\»\«\^\·\'\;\-\:\.\?\,\"\&\_\=\°\/\%\!\(\)]/gi,' '));
		sReturn = trim(sReturn.replace(/[\>\<]/gi,'-'));
		sReturn = sReturn.replace(/[\(\)\+]/gi,'');
		sReturn = sReturn.replace(/[\s]{1,10}/gi,'-');
		sReturn = sReturn.replace(/_{1,10}/gi,'-');
		sReturn = sReturn.replace(/[äâà]/gi,'a');
		sReturn = sReturn.replace(/[éèëê]/gi,'e');
		sReturn = sReturn.replace(/[ïî]/gi,'i');
		sReturn = sReturn.replace(/[öô]/gi,'o');
		sReturn = sReturn.replace(/[ùüû]/gi,'u');
		sReturn = sReturn.replace(/[ç]/gi,'c');
		sReturn = sReturn.replace(/-{1,10}/gi,'-');
		sReturn = sReturn.replace(/-nbsp/gi,'');
		sReturn = sReturn.replace(/nbsp-/gi,'');
		sReturn = sReturn.replace(/-[a-z0-9]{1,2}-/gi,'-');
		sReturn = sReturn.replace(/-[a-z0-9]{1,2}-/gi,'-');
		if (XITI) {
			sReturn = sReturn.replace(/-/gi,'_');
		}
	}
	return sReturn;
}

function resizeMiniword(objName, i) {
	if ((i<0 && document.getElementById(objName).height>200) || i>0) {
		document.getElementById(objName).height = parseInt(document.getElementById(objName).height) + i;
	}
}

<?php
/** Fonctions javascript des popup du backoffice
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 13/01/2006
 */
?>
<script type="text/javascript">
	function select(id0, title0) {
		if (id0) {
			id = id0;
		}
		if (title0) {
			sTitle = title0;
		}
		if (id) {
			arr = new Array();
			arr["href"] = "#LIEN_INTERNE#" + id +"#";
			arr["id"] = id;
			if (mutualisation) {
				opener.getIFrameDocument("iframeRight").changeLocation(id);
			} else {
				if (zone == "editor") {
					window.returnValue = arr;
				} else {
					var objFormField = sForm + '.' + sField;
					var objField = window.opener.document.getElementById(sField);
					if (zone != "multi") {
						window.opener.selectAll(eval('window.opener.' + objFormField));
						window.opener.assocDel(eval('window.opener.' + objFormField), false);
					}
				if (window.opener.addValue(objFormField, replaceCharacters(decodeURIComponent(sTitle)), id)) {
						bOK = true;
					} else {
						bOK = false;
					}
				}
			}
			if (zone != "multi") {
				window.close();
			} else {
				if (bOK) {
					alert("Votre choix a été prix en compte");
				} else {
					alert("Ce choix a déjà été fait");
				}
				goBack();
			}
		}
	}

	function refresh() {
		document.getElementById("buttonOk").style.display=(id?"":"none");
		document.getElementById("button_save").style.display="none";
	}

	function goBack() {
		if (id && sHistory) {
			getIFrameDocument("iframeRight").location.href=decodeURIComponent(sHistory);
			id = "";
			sHistory = "";
		}
		refresh();
	}

function replaceCharacters(html) {

	/* Special characters and their Pelican_Html equivelent */
	var set = [
	["&euro;","&lsquo;","&rsquo;","&rsquo;","&ldquo;","&rdquo;","&ndash;","&mdash;","&iexcl;","&cent;","&pound;","&pound;","&curren;","&yen;","&brvbar;","&sect;","&uml;","&copy;","&ordf;","&laquo;","&not;","­","&reg;","&macr;","&deg;","&plusmn;","&sup2;","&sup3;","&acute;","&micro;","&para;","&middot;","&cedil;","&sup1;","&ordm;","&raquo;","&frac14;","&frac12;","&frac34;","&iquest;","&Agrave;","&Aacute;","&Acirc;","&Atilde;","&Auml;","&Aring;","&AElig;","&Ccedil;","&Egrave;","&Eacute;","&Ecirc;","&Euml;","&Igrave;","&Iacute;","&Icirc;","&Iuml;","&ETH;","&Ntilde;","&Ograve;","&Oacute;","&Ocirc;","&Otilde;","&Ouml;","&times;","&Oslash;","&Ugrave;","&Uacute;","&Ucirc;","&Uuml;","&Yacute;","&THORN;","&szlig;","&agrave;","&aacute;","&acirc;","&atilde;","&auml;","&aring;","&aelig;","&ccedil;","&egrave;","&eacute;","&ecirc;","&euml;","&igrave;","&iacute;","&icirc;","&iuml;","&eth;","&ntilde;","&ograve;","&oacute;","&ocirc;","&otilde;","&ouml;","&divide;","&oslash;","&ugrave;","&uacute;","&ucirc;","&uuml;","&uuml;","&yacute;","&thorn;","&yuml;"],
	["","","","","","","","","¡","¢","£","£","¤","¥","¦","§","¨","©","ª","«","¬","­","®","¯","°","±","²","³","´","µ","¶","·","¸","¹","º","»","¼","½","¾","¿","À","Á","Â","Ã","Ä","Å","Æ","Ç","È","É","Ê","Ë","Ì","Í","Î","Ï","Ð","Ñ","Ò","Ó","Ô","Õ","Ö","×","Ø","Ù","Ú","Û","Ü","Ý","Þ","ß","à","á","â","ã","ä","å","æ","ç","è","é","ê","ë","ì","í","î","ï","ð","ñ","ò","ó","ô","õ","ö","÷","ø","ù","ú","û","ü","ü","ý","þ","ÿ"]
	];

	/* Replace each instance of one of the above special characters with it's Pelican_Html equivelent */
	if (html) {
		for(var j = 0; j < set[0].length; j++){
			html = html.replace(eval("/"+set[0][j]+"/g"),set[1][j]);
		}
	}

	/* Return the Pelican_Html or an empty string if no Pelican_Html was supplied */
	return html || "";
}
</script>

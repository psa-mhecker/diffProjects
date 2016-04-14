function mceCreateHTML(body, css) {
	var head = "";
	var finish = "";
	head = "<html><head>";
	head += "<link id=\"styleLink\" rel=\"stylesheet\" type=\"text/css\" href=\"" + css + "\" />";
	head += "</head><body>";
	finish = "</body></html>";
	return head + body + finish ;
}

function mceGetHttpPath(sHtml, MediaHttpPath, MediaVarPath) {
	if (MediaHttpPath.length > 0 && MediaVarPath.length > 0) {
		temp = new RegExp(MediaVarPath , "gi");
		sHtml = sHtml.replace(temp, MediaHttpPath);
	}
	return sHtml;
}

function betdCleanup(type, value) {
	switch (type) {
		case "get_from_editor":
		alert("get_from_editor: " + value);
		// Do custom cleanup code here
		break;
		case "insert_to_editor":
		alert("insert_to_editor: " + value);
		// Do custom cleanup code here
		break;
		case "submit_content":
		alert("submit_content: " + value);
		// Do custom cleanup code here
		break;
		case "get_from_editor_dom":
		alert("get_from_editor_dom: " + value);
		// Do custom cleanup code here
		break;
		case "insert_to_editor_dom":
		alert("insert_to_editor_dom: " + value);
		// Do custom cleanup code here
		break;
		case "setup_content_dom":
		alert("setup_content_dom: " + value);
		// Do custom cleanup code here
		break;
		case "submit_content_dom":
		alert("submit_content_dom: " + value);
		// Do custom cleanup code here
		break;
	}

	return value;
}

function sendHTML(unhtmlentities) {
	if (tidy1) {
		if (typeof window.ActiveXObject != 'undefined' ) {
			xmlDoc = new ActiveXObject("Microsoft.XMLHTTP");
			xmlDoc.onreadystatechange = resultHTML;
		} else {
			xmlDoc = new XMLHttpRequest();
			xmlDoc.onload = resultHTML ;
		}
		var RequestBuffer = "html=" + escape(tidy1.replace(/\&nbsp\;/gi,"#NBSP#")).replace(/\+/gi,"%2B");
		if (unhtmlentities) {
			RequestBuffer += "&unhtmlentities=true";
		}
		xmlDoc.open("POST", "/library/Pelican/Html/public/tidy.php");
		xmlDoc.setRequestHeader("content-type","application/x-www-form-urlencoded");
		xmlDoc.send(RequestBuffer);
	} else {
		if (tidyAction) {
			eval(tidyAction);
			tidyAction = null;
		}
	}
}

function resultHTML() {
	tidyResult = null;
	if (xmlDoc.readyState == 4) {
		// only if "OK"
		if (xmlDoc.status == 200) {
			tidyResult = unescape(xmlDoc.responseText).replace(/#NBSP#/gi,"&nbsp;");
			if (tidyResult) {
				tidyResult = tidyResult.replace(sSalt + " ","");
				tidyResult = tidyResult.replace(sSalt,"");
				tidyResult = tidyResult.replace("&shy; <script","<script");
			}
			if (tidy2 && tidyResult) {
				tidy2.innerHTML = tidyResult;
			}
			if (tidyAction) {
				eval(tidyAction);
				tidyAction = null;
			}
		} else {
			alert("There was a problem retrieving the data:\n" + xmlDoc.statusText);
		}
	}
}

function doCleanHtml() {
	doTidyHtml("doCleanHtmlResume();", true, true);
}

function doShowHtml(iToolbar) {
	if (!isHTMLMode) {
		doTidyHtml("doShowHtmlResume("+ (iToolbar||'') +");", false, true);
	} else {
		doShowHtmlResume(iToolbar);
	}
}

function doSave() {
	endSave = false;
	if (isHTMLMode) {
		doShowHtml();
	}
	doTidyHtml("doSaveResume();", true, true);
}


function doSaveResume() {
	var media_list = "";
	var img = "";
	var newImg = "";
	var content = "";

	sTmp = tidyResult;
	body = codeSweeper(sTmp);
	if (escape(body) == '%3Cp%3E%26nbsp%3B%3C/p%3E%0A') {
		body = "";
	}
	content = getMediaVarPath(body);
	if (content) {
		config.SourceField.value = content;
	} else {
		config.SourceField.value = "";
	}
	if (config.ViewMode == "popup") {
		generatePreview();
		self.close();
	}
	endSave = true;
}

var sSalt = "X13794268Y";
var tidy1;
var tidy2;
var tidyResult = '';
var tidyAction = '';
function doTidyHtml(action, replace, hideerrors, unhtmlentities) {
	tidy1 = sSalt + tbContentElement.DOM.body.innerHTML;
	tidy2 = null;
	if (replace || (!action && !replace)) {
		tidy2 = tbContentElement.DOM.body;
	}
	if (action) {
		tidyAction = action;
	}
	sendHTML(unhtmlentities);
}

function changeSub(divName, params) {
	inputFile = document.getElementById('file_'+divName);
	if (document.getElementById('js_'+divName)) {
		inputJs = document.getElementById('js_'+divName);
	}
	iframeM = document.getElementById("iframe_" + divName);
	iframeM.src = libDir+"/Pelican/Form/public/popup_sub.php?divname=" +divName + "&subfile=" + inputFile.value + "&subjs=" + inputJs.value + "&" + params;
}

function fillSub(divName, escapedHtml) {
	subDiv = document.getElementById(divName);
	subDiv.innerHTML = unescape(escapedHtml);
}

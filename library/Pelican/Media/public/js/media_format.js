var imgMediaFormat;
var srcOriginal;
var oldWidth;
var oldHeight;
var httpPath;
var absPath;
var extOriginal;

var oParent;
if (parent.current) {
	oParent = parent.current;
} else {
	oParent = top.current;
}

function changeMediaFormat() {
	if (oParent) {
		oParent.format = document.getElementById("cboMediaFormat").value;
		if (oParent.format) {
			imgMediaFormat.src = absPath + '/image_format.php?path=' + srcOriginal + '&format=' + oParent.format + getMktime();
			imgMediaFormat.removeAttribute('width', 0);
			imgMediaFormat.removeAttribute('height', 0);
			oParent.mediaPath = srcOriginal.replace(extOriginal, '.' + oParent.format + extOriginal);
		} else {
			imgMediaFormat.src = httpPath + unescape(srcOriginal);
			if (oldWidth) imgMediaFormat.setAttribute('width', oldWidth);
			if (oldHeight) imgMediaFormat.setAttribute('height', oldHeight);
			oParent.mediaPath = srcOriginal;
		}
	}
	showPicto();
}

function showPicto() {
	var sFormat = "";
	if (oParent) {
		sFormat = oParent.format;
	} else if(parent.current) {
		sFormat = parent.current.format;
	}
	if (document.getElementById("pictoView")) {
		document.getElementById("pictoView").style.display = (sFormat?"none":"");
	}
	if (document.getElementById("pictoEditor")) {
		document.getElementById("pictoEditor").style.display = (sFormat?"":"none");
	}
}

function mediaEditor() {
	iWidth = 300;
	iHeight = 400;
	initWidth = parseInt(document.getElementById("MEDIA_WIDTH").value);
	initHeight = parseInt(document.getElementById("MEDIA_HEIGHT").value);
	iWidth = (initWidth < iWidth?iWidth:initWidth);
	iHeight = (initHeight < iHeight?iHeight:initHeight);

	window.open(absPath + '/media_editor.php?path=' + srcOriginal + '&format=' + oParent.format , 'editor', 'width='+parseInt(iWidth+50)+',height='+parseInt(iHeight+200)+',top=0,left=0,menubar=0,status=1,titlebar=0,toolbar=0,resizable=1,scrollbars=1');
}

function delForcage(id) {
	if (confirm("Etes-vous sûr(e) de vouloir supprimer ce format personnalisé ?")) {
		document.location.href = document.location.href + "&delForcage=" + id;
	}
}

function viewMediaFormat(id) {
	document.getElementById("cboMediaFormat").value = id;
	changeMediaFormat();
}

function getMktime() {
	var now = new Date();
	var timestamp = now.getYear().toString();
	timestamp += '.' + now.getMonth().toString();
	timestamp += '.' + now.getDate().toString();
	timestamp += '.' + now.getHours().toString();
	timestamp += '.' + now.getMinutes().toString();
	timestamp += '.' + now.getSeconds().toString();
	return "&timestamp="+timestamp;
}
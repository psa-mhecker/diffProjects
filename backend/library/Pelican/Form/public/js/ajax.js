var xmlDoc = new Object();

function sendAjax(args, action, dest) {
	if (typeof window.ActiveXObject != 'undefined' ) {
		xmlDoc[dest] = new ActiveXObject("Microsoft.XMLHTTP");
		xmlDoc[dest].onreadystatechange = function() {resultAjax(dest);};
	} else {
		xmlDoc[dest] = new XMLHttpRequest();
		xmlDoc[dest].onload = function() {resultAjax(dest);};
	}
	var RequestBuffer = new Array();
	var i=0;
	for (elem in args) {
		RequestBuffer[i] = elem + "=" + args[elem];
		i++;
	}
	//RequestBuffer[i] = "old_source=" + escape(document.getElementById(dest).innerHTML);
	//		alert(RequestBuffer.join('&'));
	xmlDoc[dest].open("POST", '/library/Pelican/Form/public/' + action + '.php');
	xmlDoc[dest].setRequestHeader("content-type","application/x-www-form-urlencoded");
	xmlDoc[dest].send(RequestBuffer.join('&'));
}

function resultAjax(dest){
	if (xmlDoc[dest].readyState == 4) {
		// only if "OK"
		if (xmlDoc[dest].status == 200) {
			var result = xmlDoc[dest].responseText;
			if (result) {
				if (result.indexOf('<') == 0) {
					if (document.getElementById(dest).outerHTML) {
						var old = document.getElementById(dest).outerHTML;
						old = (old.substring(0,old.indexOf('>')+1));
						document.getElementById(dest).outerHTML = old + result + '</select>';
					} else {
						document.getElementById(dest).innerHTML = result;
					}
				} else {
					alert(result);
				}
			} else {
				alert('Aucun résultat');
			}
		} else {
			alert("There was a problem retrieving the data:\n" + xmlDoc[dest].statusText);
		}
	}
}

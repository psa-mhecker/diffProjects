// Cookies
function clearCookie(cookieName) {
	var now = new Date();
	var yesterday = new Date(now.getTime() - 1000 * 60 * 60 * 24);
	document.setCookie(cookieName, '', yesterday);
}

function setCookie(cookieName, cookieValue, nDays) {
	var today = new Date();
	var expire = new Date();
	if (nDays == null || nDays == 0)
		nDays = 1;
	expire.setTime(today.getTime() + 3600000 * 24 * nDays);
	document.cookie = cookieName + "=" + escape(cookieValue) + ";expires="
			+ expire.toGMTString();
}

function getCookie(cookieName) {
	var cookieValue = '';
	var posName = document.cookie.indexOf(escape(cookieName) + '=');
	if (posName != -1) {
		var posValue = posName + (escape(cookieName) + '=').length;
		var endPos = document.cookie.indexOf(';', posValue);
		if (endPos != -1) {
			cookieValue = unescape(document.cookie.substring(posValue, endPos));
		} else {
			cookieValue = unescape(document.cookie.substring(posValue));
		}
	}
	return cookieValue;
}

/** pour les compatibilité IE/Firefox */
function outerHTML(id, html) {
	var obj = document.getElementById(id);
	if (obj) {
		if (obj.outerHTML) {
			// IE
			obj.outerHTML = unescape(html);
		} else {
			// NS
			var parentObj = obj.parentNode;
			obj.parentNode.innerHTML = unescape(html);
		}
	}
}

function innerHTML(id, html) {
	var obj = document.getElementById(id);
	if (obj) {
		if (obj.outerHTML) {
			// IE
			var html1 = obj.innerHTML;
			var html2 = obj.outerHTML;
			var html3 = html2.replace(html1, unescape(html));
			obj.outerHTML = html3;
		} else {
			// NS
			obj.innerHTML = unescape(html);
		}
	}
}
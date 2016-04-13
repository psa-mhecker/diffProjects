if (typeof HTMLElement != "undefined" && !HTMLElement.prototype.insertAdjacentElement) {
	HTMLElement.prototype.insertAdjacentElement = function (where, parsedNode) {
		switch (where) {
			case 'beforeBegin':
			this.parentNode.insertBefore(parsedNode, this)
			break;
			case 'afterBegin':
			this.insertBefore(parsedNode, this.firstChild);
			break;
			case 'beforeEnd':
			this.appendChild(parsedNode);
			break;
			case 'afterEnd':
			if (this.nextSibling)
			this.parentNode.insertBefore(parsedNode, this.nextSibling);
			else this.parentNode.appendChild(parsedNode);
			break;
		}
	}
}

if (typeof HTMLElement != "undefined" && !HTMLElement.prototype.insertAdjacentHTML) {
	HTMLElement.prototype.insertAdjacentHTML = function (where, htmlStr) {
		var r = this.ownerDocument.createRange();
		r.setStartBefore(this);
		var parsedHTML = r.createContextualFragment(htmlStr);
		this.insertAdjacentElement(where, parsedHTML);
	}
}

if (typeof HTMLElement != "undefined" && !HTMLElement.prototype.insertAdjacentText) {
	HTMLElement.prototype.insertAdjacentText = function (where, txtStr) {
		var parsedText = document.createTextNode(txtStr);
		this.insertAdjacentElement(where, parsedText);
	}
}

if (typeof showModalDialog == 'undefined') {

	var DialogOpened = new Object;
	var DialogArguments = new Object;

	showModalDialog = function(url, args, windowParam) {
		var reg;
		/** récupération des arguments : utiliser opener.DialogArguments dans la popup */
		if (args) {
			DialogArguments = args;
		}
		/** traduction des dimensions */
		var params = windowParam.toLowerCase();

		reg = /\;/gi;
		params = params.replace(reg,',');
		reg = /\:/gi;
		params = params.replace(reg,'=');

		reg = /(dialog)/gi;
		params = params.replace(reg,'');

		params += ',modal=yes,directories=0,menubar=0,titlebar=0,toolbar=0';

		/** unicité de la popup */
		var tmp1 = url.split('?');
		var tmp = tmp1[0].split('/');
		var idWindow = tmp[tmp.length-1].replace('.','_');

		if (DialogOpened[idWindow]) {
			DialogOpened[idWindow].close();
		}
		/** appel de la popup */
		DialogOpened[idWindow] = window.open(url, idWindow, params);
	}
}
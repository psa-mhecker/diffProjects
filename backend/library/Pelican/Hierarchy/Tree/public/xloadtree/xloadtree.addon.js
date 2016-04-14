// configuration de xloadtree
webFXTreeConfig.rootIcon		= "/library/Pelican/Hierarchy/Tree/public/images/base.gif";
webFXTreeConfig.openRootIcon	= "/library/Pelican/Hierarchy/Tree/public/images/base.gif";
webFXTreeConfig.folderIcon		= "/library/Pelican/Hierarchy/Tree/public/images/folder.gif"
webFXTreeConfig.openFolderIcon	= "/library/Pelican/Hierarchy/Tree/public/images/folderopen.gif";
webFXTreeConfig.fileIcon		= "/library/Pelican/Hierarchy/Tree/public/images/page.gif";
webFXTreeConfig.iIcon			= "/library/Pelican/Hierarchy/Tree/public/images/mine.gif";
webFXTreeConfig.lIcon			= "/library/Pelican/Hierarchy/Tree/public/images/joinbottom.gif";
webFXTreeConfig.lMinusIcon		= "/library/Pelican/Hierarchy/Tree/public/images/minusbottom.gif";
webFXTreeConfig.lPlusIcon		= "/library/Pelican/Hierarchy/Tree/public/images/plusbottom.gif";
webFXTreeConfig.tIcon			= "/library/Pelican/Hierarchy/Tree/public/images/join.gif";
webFXTreeConfig.tMinusIcon		= "/library/Pelican/Hierarchy/Tree/public/images/minus.gif";
webFXTreeConfig.tPlusIcon		= "/library/Pelican/Hierarchy/Tree/public/images/plus.gif";
webFXTreeConfig.blankIcon		= "/library/Pelican/Hierarchy/Tree/public/images/blank.gif";
webFXTreeConfig.loadingIcon		= "/library/Pelican/Hierarchy/Tree/public/images/loading.gif";
webFXTreeConfig.loadingText		= "Chargement...";
webFXTreeConfig.errorIcon		= "/library/Pelican/Hierarchy/Tree/public/xloadtree/images/notfound.png";
webFXTreeConfig.errorLoadingText = "Erreur lors du chargement";
webFXTreeConfig.reloadText		= "Cliquer pour recharger";
webFXTreeConfig.plusIcon        = "/library/Pelican/Hierarchy/Tree/public/images/plus.gif";
webFXTreeConfig.minusIcon       = "/library/Pelican/Hierarchy/Tree/public/images/minus.gif";
webFXTreeConfig.usePersistence  = true;
// masquage du noeud Root
WebFXTree.prototype.showRootNode = false;
WebFXTree.prototype.showRootLines = false;

// id de l'objet tree lui-même
WebFXTreeAbstractNode.prototype.setObjName = function(objName){
	WebFXTreeAbstractNode.prototype.objName = objName;
}

// PATCH pour que xloadtree prenne en compte tooltip dans le xml
WebFXLoadTree._attrs = ["text", "src", "action", "id", "target", "toolTip"];

// utilisation de tooltip pour garder en mémoire l'id système (l'id des noeud changenat à chaque chargement)
WebFXTreeAbstractNode.prototype.setToolTipOld = WebFXTreeAbstractNode.prototype.setToolTip;
WebFXTreeAbstractNode.prototype.setToolTip = function (s) {
	if (s != 'undefined') {
		webFXTreeHandler.all[this.id].toolTip = s;
		if (!webFXTreeHandler.defaultRealId) {
			webFXTreeHandler.defaultRealId = this.getLastSelected();
			if (!webFXTreeHandler.defaultRealId) {
				webFXTreeHandler.defaultRealId = 'undefined';
			}
		}
		if (webFXTreeHandler.defaultRealId != 'undefined') {
			if (webFXTreeHandler.defaultRealId == s) {
				webFXTreeHandler.defaultNode = this;
				this.select(); // est activé s'il existe déjà
			}
		}
	}
}

/// sauvegarde de l'ancien select et action
WebFXTreeAbstractNode.prototype.select = function() {
	this._setSelected(true);
	if (this.objName && (webFXTreeHandler.all[this.id].toolTip ||webFXTreeHandler.all[this.id].action)) {
		this.setCookie('xloadcs' + this.objName, webFXTreeHandler.all[this.id].toolTip);
		this.setCookie('xloadcsaction' + this.objName, webFXTreeHandler.all[this.id].action);
	}
}

//Nettoyage de cookies
WebFXTreeAbstractNode.prototype.clearCookie = function() {
	var now = new Date();
	var yesterday = new Date(now.getTime() - 1000 * 60 * 60 * 24);
	if (this.objName) {
		this.setCookie('xloadco'+this.objName, 'cookieValue', yesterday);
		this.setCookie('xloadcs'+this.objName, 'cookieValue', yesterday);
		this.setCookie('xloadcsaction'+this.objName, 'cookieValue', yesterday);
	}
};

// [Cookie] Sets value in a cookie
WebFXTreeAbstractNode.prototype.setCookie = function(cookieName, cookieValue, expires, path, domain, secure) {
	document.cookie =
	escape(cookieName) + '=' + escape(cookieValue)
	+ (expires ? '; expires=' + expires.toGMTString() : '')
	+ (path ? '; path=' + path : '')
	+ (domain ? '; domain=' + domain : '')
	+ (secure ? '; secure' : '');
};

// [Cookie] Gets a value from a cookie
WebFXTreeAbstractNode.prototype.getCookie = function(cookieName) {
	var cookieValue = '';
	var posName = document.cookie.indexOf(escape(cookieName) + '=');
	if (posName != -1) {
		var posValue = posName + (escape(cookieName) + '=').length;
		var endPos = document.cookie.indexOf(';', posValue);
		if (endPos != -1) cookieValue = unescape(document.cookie.substring(posValue, endPos));
		else cookieValue = unescape(document.cookie.substring(posValue));
	}
	return (cookieValue);
};

// Retourne l'id système du dernier noeud cliqué
WebFXTreeAbstractNode.prototype.getLastSelected = function() {
	if (this.objName) {
		var sn = this.getCookie('xloadcs' + this.objName);
	}
	return (sn) ? sn : null;
}

// Retourne l'action du dernier noeud cliqué
WebFXTreeAbstractNode.prototype.getLastAction = function() {
	if (this.objName) {
		var sn = this.getCookie('xloadcsaction' + this.objName);
	}
	return (sn) ? sn : null;
}

// Fermeture de tous les noeuds
WebFXTreeAbstractNode.prototype.closeAll = function() {
	this.collapseAll();
}

//Ouverture d'un noeud et exécution de l'action associée
WebFXTreeAbstractNode.prototype.doDefault = function(init, node) {

	// Intitialisation de l'arbre (true ou false)
	var bInit=(init||false);
	var bFirst=true;
	if (bInit) {
		this.clearCookie();
	}

	var lastAction = this.getLastAction();
	if (lastAction) {
		eval(lastAction);
		if (webFXTreeHandler.defaultNode) {
			webFXTreeHandler.defaultNode.select();
		}
	} else {
		/** recherche du premier noeud actif */
		for (var id in webFXTreeHandler.all) {
			if (webFXTreeHandler.all[id].action) {
				eval(webFXTreeHandler.all[id].action);
				webFXTreeHandler.all[id].select();
				break;
			}
		}
	}
}

// execution de l'action d'un noeud
webFXTreeHandler.execNode = function (oNode) {
	oNode.select();

	if (oNode.action) {
		// Exécution javascript ou location
		if (oNode.action.indexOf("javascript")!= -1) {
			eval(oNode.action);
		} else {
			window.open(oNode.action, this.target || "_self");
		}
	}
}

// affichage d'un noeud
function tree_reveal(oNode) {
	if(oNode.parentNode) {
		oNode.parentNode.expand();
		tree_reveal(oNode.parentNode);
	}
}


// Appel de tous les ajax jusqu'au noeud recherché
WebFXTreeAbstractNode.prototype.openTo = function(oNode) {
	tree_reveal(oNode);
};

// Sélection d'un noeud (COMPATIBILITE AVEC LES METHODES DE DTREE)
WebFXTreeAbstractNode.prototype.s = function(oNode) {
	oNode.focus();
	oNode.setExpanded(true);
}

// Disable double click:
WebFXTreeAbstractNode.prototype._ondblclick = function(){}


// Show tree XML on double click - for debugging purposes only
///
// UNCOMMENT THIS FOR DEBUGGING (SHOWS THE SOURCE XML)
WebFXTreeAbstractNode.prototype._ondblclick = function(e){
	var el = e.target || e.srcElement;

	if (this.src != null)
	window.open(this.src, this.target || "_blank");
	return false;
};
///

function cancelEvent() {
	window.event.returnValue = false;
}

function drop() {
	id = getNumberId();
	obj = document.getElementById(id);
	treedrop(obj);
	dragOver(false);
}

function dragOver(state) {
	id = getNumberId();
	obj = document.getElementById(id);
	if (state) {
		obj.style.backgroundColor = "#C3DBF7";
	} else {
		obj.style.backgroundColor = "";
	}
	cancelEvent();
}

function getNumberId() {
	obj = event.srcElement;
	id = obj.id;
	return id;
}

function treedrop(obj) {
	return true;
}




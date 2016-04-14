/**
** Script permettant la gestion d'un id pour le back office (timestamp).
** Il est pour le moment utilisé pour le dtree (l'arbre de la navigation)
** 
**/

/* Les 3 fonctions qui suivent sont utilisé pour la gestion d'un cookie */
function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}

function getVar (nomVariable)
{
	var infos = location.href.substring(location.href.indexOf("?")+1, location.href.length)+"&"
	if (infos.indexOf("#")!=-1)
		infos = infos.substring(0,infos.indexOf("#"))+"&"
	var variable=0
	{
		nomVariable = nomVariable + "="
		var taille = nomVariable.length
		if (infos.indexOf(nomVariable)!=-1)
			variable = infos.substring(infos.indexOf(nomVariable)+taille,infos.length).substring(0,infos.substring(infos.indexOf(nomVariable)+taille,infos.length).indexOf("&"))
	}
	return variable
}

/* Id associé au Back Office */
var iBoId = 0;
iBoId = getVar('idbo');

if (iBoId == 0) {
	/* Lorsque l'on 'quitte' la page actuelle */
	jQuery(window).unload(function() {
		if (iBoId != 0) {
			/* Si on a un id de Back Office, on le sauvegarde dans un cookie spécifique */
			eraseCookie("boId");
			createCookie("boId", iBoId, 1);
		}
	});

	/* 
	** On regarde si on a un cookie d'id de Back Office (boId). Si c'est le cas on le supprime
	** en gardant la valeur du cookie comme id sinon on génére un nouvel id (timestamp)
	*/

	iBoId = readCookie("boId");
	if (iBoId != null) {
		eraseCookie("boId");
	} else if (iBoId == null) {
		iBoId = Number(new Date());
	}
}

/* Quand la page est chargé on ouvre l'item que l'on veut */
if (getVar('newBo') == 1 && getVar('tid') != 0 && getVar('idItem') != 0) {
	jQuery(document).ready(function() {
		menu(getVar('tid'), getVar('tc'), getVar('idItem'));
	});
}


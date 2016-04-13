/***************************************************************************************
*  @usage                                                                              *
* ATTENTION : les éléments sont à initialiser dans la page de plus haut niveau (top)   *
*		<script type="text/javascript">                                                *
*			var mediaDir='.'; // Chemin absolu de la médiathèque                       *
*                             => mettre '.' si on est en popup                         *
*			var httpMediaDir='XXX'; // Chemin http de consultation des media           *
*		</script>                                                                      *
*		<script src="/js/media.js" type="text/javascript"></script>              *
***************************************************************************************/

/** Objet contenant les informations du dernier media sélectionné */
if (!current) {
	var current = new Object;
}

/** Objet contenant les attributs du dernier media sélectionné */
current.fileAttribut = new Object;
/**
 * Objet pour le déplacement des dossiers contenant les id de dossier à couper
 * ("cut") et de dossier de destination ("paste")
 */
current.move = new Object;
current.usage = false;

/** Réinitialise l'arbre à chaque consultation */
var initTree = false;
/** Variable de sauvegarde des src des iFrame */
var oldSrc = "";
/**
 * Onglet activé par défaut dans la partie de gauche : "0" arborescence, "1"
 * "recherche"
 */
var ongletMedia;
/**
 * Variable de sauvegarde du dernier élément sélectionné dans la liste des
 * media
 */
var cSelected;
var lastPreview;

/** Initialisations par défaut */
ongletMedia = "0";
if (!current.zone) {
current.zone = "popup";
}
current.format = "";
current.path = "";
current.format = "";
current.tiny = false;

/**
 * On fenêtre modale, les arguments sont interceptés on initialise au moins le
 * type de fichier consulté
 */
var windowArguments = new Object;
/** firefox */
if (opener) {
	if (opener.DialogArguments) {
		windowArguments = opener.DialogArguments;
	}
}
/** ie en modal */
if (window.dialogArguments) {
	windowArguments = window.dialogArguments;
}

if (windowArguments) {
	if (windowArguments["format"]) {
		current.format = windowArguments["format"];
	}
	if (!windowArguments["mediaType"]) {
		windowArguments["mediaType"] = "image";
	}
}

/**
 * Paramétrage de la médiathèque : current.mediaType : type de fichier
 * consulté (image, flash, file etc...) current.fileAttribut : attributs html
 * du média courant
 *
 * Les suivants sont surtout utilisés en mode parcours Physique :
 * current.rootPath : Nom du dossier racine de la médiathèque (ex : /media/)
 * current.groupPath : Chemin absolu des sous-dossier de départ (ex :
 * /cinema/affiche) current.physicalPath : Chemin absolu du dossier ou du
 * fichier
 */
if (windowArguments) {
	if (windowArguments["mediaType"]) {
		if (windowArguments["mediaType"] != 'image' && windowArguments["mediaType"] != 'file' && windowArguments["mediaType"] != 'flash' && windowArguments["mediaType"] != 'video' && windowArguments["mediaType"] != 'video' && windowArguments["mediaType"] != 'all') {
			windowArguments["mediaType"] = 'image';
		}
	} else {
		windowArguments["mediaType"] = 'image';
	}
	if (windowArguments["fileAttributs"]) {
		current.fileAttribut = windowArguments["fileAttributs"];
	}

	if (windowArguments["filter_map"]) {
		current.filter_map = windowArguments["filter_map"];
	} else {
		current.filter_map = 0;
	}

	current.mediaType = windowArguments["mediaType"];
	current.rootPath = windowArguments["rootPath"];
	current.groupPath = (windowArguments["groupPath"]?windowArguments["groupPath"]:"");
	current.physicalPath = current.rootPath + "/" + current.groupPath + "//";
	current.physicalPath = current.physicalPath.replace("//", "/");
	current.physicalPath = current.physicalPath.replace("//", "/");
}

/**
 * @return void
 * @param string
 *            type type de fichier "image", "flash", "file" ou autre
 * @desc Filtre sur le type de média passé en paramètre si type est vide,
 *       cela permet le rechargement du contenu du dossier sélectionné
 */
function setFilter(type) {
	if (type) {
		current.mediaType = type;
	}
	$('#type', window.parent.document).val(type);
	showFolder(current.node, current.parentnode, current.physicalPath, current.isFolder, current.allowAdd, current.allowDel, true);
}

/**
 * @return void
 * @param string
 *            id Id du dossier (ou son chemin si la navigation des répertoires
 *            est physique)
 * @param string
 *            pid Id du dossier parent
 * @param boolean
 *            allowAdd Autoriser ou non l'affichage des boutons d'ajout
 * @param boolean
 *            allowDel Autoriser ou non l'affichage des boutons de suppression
 * @param string
 *            lib Libellé du dossier
 * @desc Initialisation des paramètres du dernier dossier sélectionné
 */
function setFolder(id, pid, allowAdd, allowDel, lib) {
	current.node = id;
	current.parentnode = pid;
	current.allowAdd = allowAdd;
	current.allowDel = allowDel;
	current.path = lib;

	current.physicalPath = id;
	current.initialPath = id;
}

/**
 * @return void
 * @param string
 *            folderId Id du dossier (ou son chemin si la navigation des
 *            répertoires est physique)
 * @param string
 *            folderParentId Id du dossier parent
 * @param string
 *            physpath Chemin absolu du dossier ou du fichier
 * @param boolean
 *            isFolder L"élément affiché est un dossier ou non
 * @param boolean
 *            allowAdd Autoriser ou non l'affichage des boutons d'ajout
 * @param boolean
 *            allowDel Autoriser ou non l'affichage des boutons de suppression
 * @param boolean
 *            keepSearch Garder ou non les critères de recherches en cours
 * @desc Sélection et affichage du contenu d'un dossier
 */
function showFolder(folderId, folderParentId, physpath, isFolder, allowAdd, allowDel, keepSearch, lib) {

	current.isFolder = isFolder;

	setFolder(folderId, folderParentId, allowAdd, allowDel, lib);

	current.physicalPath = physpath;
	current.fileAttribut = new Object;
	if (!current.initialPath) {
		current.initialPath = current.physicalPath;
	}

	resetMedia();
	if (document.getElementById('iframeRight')) {
		document.getElementById('iframeRight').src = vIndexIframePath + "?tid=17&root=" + current.physicalPath + "&type=" + current.mediaType + "&lib=" + mediaDir + "&format=" + current.format + "&zone=" + current.zone + (keepSearch?"":"&recherche=") + getTimeStamp() + (current.tiny?"&tiny=true":"") ; // PLA20130129 : suppression du ; avant + (keepSearch...
	}

}

/**
 * @return void
 * @param string
 *            id Id du dossier (ou son chemin si la navigation des répertoires
 *            est physique)
 * @param string
 *            pid Id du dossier parent
 * @param boolean
 *            allowAdd Autoriser ou non l'affichage des boutons d'ajout
 * @param boolean
 *            allowDel Autoriser ou non l'affichage des boutons de suppression
 * @param string
 *            lib Libellé du dossier
 * @desc Sélection et affichage du contenu d'un dossier (variante de showFolder
 *       pour XMLTREE)
 */
function goMedia(id, pid, allowAdd, allowDel, lib) {
	showFolder(id, pid, id, true, allowAdd, allowDel, false, lib);
}

/**
 * @return void
 * @desc Affichage des boutons Ajouter, supprimer, propriétés etc...
 */
function refreshButtons() {
	displayButton("buttonAddFile", (current.otherSite?"none":(current.mediaId?"none":(current.defineProperties?"none":(current.allowAdd?"":"none")))));
	if (windowArguments) {
		displayButton("buttonPropertiesFile", ((current.mediaId && windowArguments["mediaZone"] == "editor")?(current.defineProperties?"none":""):"none"));
	}
	displayButton("buttonDelFile", (current.otherSite?"none":(current.mediaId?(current.defineProperties?"none":(current.allowDel && !current.usage?"":"none")):"none"))); // &&
	displayButton("buttonAddFolder", (current.otherSite?"none":(current.defineProperties?"none":(current.allowAdd?"":"none"))));// (current.allowAdd
	displayButton("buttonEditFolder", (current.otherSite?"none":(current.defineProperties?"none":(current.allowAdd && current.isFolder?"":"none"))));// (current.allowAdd
	displayButton("buttonDelFolder", (current.otherSite?"none":(current.defineProperties?"none":(current.allowDel?"":"none"))));// (current.allowAdd
	displayButton("buttonBack", (current.mediaId && current.physicalPath != current.mediaPath?"":"none"));
	displayButton("tree", (current.defineProperties?"none":""));
	// displayButton("properties", (current.defineProperties?"":"none"));
	displayButton("buttonOk", (current.mediaId?"":"none"));
}

/**
 * @return void
 * @param string
 *            name id de l'objet bouton
 * @param string
 *            state Etat d'affichage "none" ou ""
 * @desc Fonction générique d'affichage d'un bouton
 */
function displayButton(id, state) {
	if (document.getElementById(id) && document.getElementById(id).style.display != state) {
		document.getElementById(id).style.display = state;
	}

}

/**
 * @return void
 * @desc Equivalent d'un history.go(-1)
 */
function goBack() {
	/*
	 * var url = ""; if (current.zone == "upload") { select(); } else { if
	 * (oldSrc) { url = oldSrc + getTimeStamp(); } else { resetMedia(); url =
	 * "/_/Index/child?p?tid=17&root=" + current.physicalPath
	 * +"&type="+current.mediaType + "&lib=" + mediaDir + "&format=" +
	 * current.format + getTimeStamp(); } var right =
	 * getIFrameDocument("
	 "); if (current.zone == "media" && oldSrc) {
	 * right.location.href = url; getIFrameDocument("properties",
	 * right).location.href = mediaDir + "/media_form.php?action=add&view=" +
	 * current.mediaType + "&type=file&root=" + current.physicalPath +
	 * "&initial=" + current.initialPath + "&zone=" + current.zone; } else {
	 * right.location.href = url; } oldSrc = ""; if (current.defineProperties) {
	 * current.defineProperties = false; refreshButtons(); } }
	 */
	showFolder(current.node, current.parentnode, current.physicalPath, current.isFolder, current.allowAdd, current.allowDel, true);
}

/**
 * @return void
 * @param string
 *            path Chemin du media
 * @param string
 *            tag Tag IMG associé
 * @param string
 *            media Tag IMG de la prévisualisation associé
 * @param string
 *            id Id du media
 * @desc Initialisation des paramètres du dernier media sélectionné
 */
function setMedia(path, tag, media, id) {
	current.mediaPath = path;
	current.mediaTag = tag;
	current.previewTag = media;
	current.mediaId = id;
	/**
	 * Le changement de media implique le masquage de la page de propriétés
	 * HTML
	 */
	current.defineProperties = false;
	refreshButtons();
}

/**
 * @return void
 * @desc Mise à zéro des propriétés du dernier média sélectionné
 */
function resetMedia() {
	current.fileAttribut = new Object;
	current.mediaPath = null;
	current.mediaTag = null;
	media_previewTag = null;
	current.mediaId = null;
	current.defineProperties = false;
	refreshButtons();
}

/**
 * @return void
 * @desc Affiche de la page de propriétés HTML du dernier media sélectionné
 */
function showProperties() {
	if (current.img) {
		current.fileAttribut["width"] = current.img.width;
		current.fileAttribut["height"] = current.img.height;
		buildTag();
	}
	current.defineProperties = true;
	oldSrc = document.getElementById("iframeRight").location.href;
	document.getElementById('properties').src = "_/Index/child?action=properties?file=" + current.mediaPath + "&type=" + current.mediaType;
	refreshButtons();
}

/**
 * @return void
 * @desc Sélection du media et initialisation des variables de retour de la
 *       popup
 */
function select() {
	if (windowArguments) {
		if (windowArguments["mediaZone"] == "editor") {
			if (!current.defineProperties && current.mediaType == "image") {
				showProperties();
			}
			buildTag();
			window.returnValue = unescape(current.mediaTag);
		} else {
			var arr = "";
			if (current.mediaId) {
				arr = new Array();
				arr[0] = current.mediaId;
				arr[1] = "<a href='" + httpMediaDir + unescape(current.mediaPath) + "' target='_blank' >" + unescape(current.previewTag) + "</a>";
				arr[2] = unescape(current.mediaPath);
				arr[3] = windowArguments["Field"];
				arr[4] = windowArguments["Div"];
			}
			if (opener) {
				/** firefox */
				opener.returnPopupMedia(arr[0], arr[1], arr[2], arr[3], arr[4]);
			} else {
				/** IE */
				window.returnValue = arr;
			}
		}
	}
	top.close();
}

/**
 * @return void
 * @param string
 *            action action : "add", "edit", "del", "move"
 * @param string
 *            type type d'objet concerné par l'action : "folder", "file"
 * @desc Exécution d'une action sur un dossier ou un media
 */
function setAction(action, type) {

	newfolder = "";
	/** Un chemin doit exister sinon on annule l'action */
	if (!current.physicalPath) {
		return false;
	} else {
		switch (type) {
			case "file" :
			{
				/** Confirmation de la suppression du media */
				if (!((action == "del" && confirm(aLabel["POPUP_MEDIA_MSG_DEL_FILE"])) || action == "add" || action == "move")) {
					return false;
				}
				if (action == "move") {
					/**
					 * Un media doit avoir été déplacé ("cut") un une
					 * destination choisie ("paste")
					 */
					if (!current.move["cut"] || ! current.move["paste"]) {
						alert(aLabel["POPUP_MEDIA_MSG_SELECT_MEDIA"]);
						return false;
					}
					/** Confirmation du déplacement du dossier */
					if (!confirm(aLabel["POPUP_MEDIA_MSG_MOVE_MEDIA"])) {
						return false;
					}
					action = action + "&from=" + current.move["cut"] + "&to=" + current.move["paste"] ;
				}
				break;
			}
			case "folder" :
			{
				newfolder = "";
				if (action == "del") {
					/** L'élément courant doit être un dossier */
					if (!current.isFolder) {
						alert(aLabel["POPUP_MEDIA_MSG_SELECT_FOLDER"]);
						return false;
					}
					/** Confirmation de la suppression du dossier */
					if (!confirm(aLabel["POPUP_MEDIA_MSG_DEL_FOLDER"])) {
						return false;
					}
				}
				if (action == "move") {
					/**
					 * Un dossier doit avoir été coupé ("cut") un une
					 * destination choisie ("paste")
					 */
					if (!current.move["cut"] || ! current.move["paste"]) {
						alert(aLabel["POPUP_MEDIA_MSG_SELECT_FOLDER"]);
						return false;
					}
					/** Confirmation du déplacement du dossier */
					if (!confirm(aLabel["POPUP_MEDIA_MSG_MOVE_FOLDER"])) {
						return false;
					}
					action = action + "&from=" + current.move["cut"] + "&to=" + current.move["paste"] ;
				}
				break;
			}
		}
		var path = "";
		var frame = getIFrameDocument("iframeRight");
		if (current.zone == "media") {
			frame = getIFrameDocument("properties", getIFrameDocument("iframeRight"));
		}

		var url = "/_/Media/edit?action=" + action + "&view=" + current.mediaType + "&type=" + type + "&root=" + current.physicalPath + "&initial=" + current.initialPath + "&zone=" + current.zone;
		if (current.mediaId) {
			url += "&id="+current.mediaId;
		}
		frame.location.href = url;
		refreshButtons();
	}
	return true;
}

/**
 * @return void
 * @desc Initialisation en cas de consultation des propriétés de media
 *       directement (miniword)
 */
function init() {
	if (windowArguments) {
		current.mediaZone = (windowArguments["mediaZone"] || "");
	}

	if (current.fileAttribut["src"] && current.mediaType == 'image') {
		current.physicalPath = current.fileAttribut["src"];
		current.mediaId = current.fileAttribut["src"];
		current.mediaPath = current.fileAttribut["src"];
		current.defineProperties = false;
		showProperties();
	} else {
		treeFrame = document.getElementById("tree");
		if (treeFrame) {
			treeFrame.src = mediaDir + "/media_tree.php?root=" + current.physicalPath + "&type=" + current.mediaType;
		}
	}
}

/**
 * @return void
 * @desc Génère le tag HTML associé au tyope de media en fonction des
 *       propriétés définies
 */
function buildTag() {
	var tmp;
	var obj = current.fileAttribut;

	switch(current.mediaType) {
		case "image":
		{
			if (!top.current.format && top.current.img && !current.defineProperties) {
				obj["width"] = top.current.img.getAttribute('owidth');
				obj["height"] = top.current.img.getAttribute('oheight');
			}

			if (current.mediaPath.indexOf("http://") == -1) {
				src = httpMediaDir + current.mediaPath;
			} else {
				src = current.mediaPath;
			}
			tmp = "<img src=" + src;
			if (obj["width"]) tmp += " width=" + obj["width"];
			if (obj["height"]) tmp += " height=" + obj["height"];
			if (obj["border"]) tmp += " border=" + obj["border"];
			if (obj["vspace"]) tmp += " vspace=" + obj["vspace"];
			if (obj["hspace"]) tmp += " hspace=" + obj["hspace"];
			if (obj["align"]) tmp += " align=" + obj["align"];
			if (obj["alt"]) {
				tmp += " alt=\"" + escape(obj["alt"]) + "\">";
			} else {
				tmp += " alt=\"\">";
			}
			break;
		}
		case "flash":
		{
			tmp = " <embed src='" + httpMediaDir + current.mediaPath + "'";
			if (obj["quality"]) tmp += " quality='" + obj["quality"] + "'";
			if (obj["id"]) tmp += " name='" + obj["id"] + "'";
			if (obj["height"]) tmp += " height='" + obj["height"] + "'";
			if (obj["width"]) tmp += " width='" + obj["width"] + "'";
			tmp += " scale='noscale'";
			if (obj["bgcolor"]) tmp += " bgcolor='" + obj["bgcolor"] + "'";
			tmp += " type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash'";
			if (obj["align"] && obj["align"] != "null") tmp += " align='" + obj["align"] + "'";
			tmp += " />";
			break;
		}
		case "video":
		{
			tmp = " <embed src='" + httpMediaDir + current.mediaPath + "'";
			if (obj["quality"]) tmp += " quality='" + obj["quality"] + "'";
			if (obj["id"]) tmp += " name='" + obj["id"] + "'";
			if (obj["height"]) tmp += " height='" + obj["height"] + "'";
			if (obj["width"]) tmp += " width='" + obj["width"] + "'";
			if (obj["bgcolor"]) tmp += " bgcolor='" + obj["bgcolor"] + "'";
			tmp += " type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash'";
			if (obj["align"] && obj["align"] != "null") tmp += " align='" + obj["align"] + "'";
			tmp += " />";
			break;
		}
		default:
		{
			tmp = '<a href="' + httpMediaDir + current.mediaPath + '"';
			if (obj["caption"]) {
				tmp2 = '"' + obj["caption"];
				if (obj["sizeOK"]) {
					tmp2 += ' (' + obj["size"] + ' Ko)';
				}
				tmp2 += '"';
				tmp += ' title=' + tmp2;
			}
			tmp +=  '>';
			tmp +=  windowArguments["linkText"];
			tmp += '<\/a>';
			break;
		}
	}
	current.mediaTag = tmp;
}

/**
 * @return void
 * @param string
 *            file Chemin du media
 * @param string
 *            id Id du media
 * @desc Affichage de la page de description du Media
 */
function showMedia(file, id) {
	if (file && current.zone != "media") {
		oldSrc = getIFrameDocument("iframeRight").location.href;
	}
	var query = "";
	var page = "/_/Media/?";
	var frame = document.getElementById("properties");
	if (file) {
		query = "root=" + current.physicalPath+"&type="+current.mediaType+"&preview=" + file + "&format=" + current.format + "&id=" + id + "&format=" + current.format + "&zone=" + current.zone + getTimeStamp();
	}
	page = "/_/Media/edit?";
	query = "action=edit&media=true&view=" + current.mediaType   + "&" + query;
	frame = getIFrameDocument("properties", getIFrameDocument("iframeRight"));
	if (current.zone == 'popup') {
		frame = getIFrameDocument("iframeRight");
	}
	frame.location.href = page + query;
}

/**
 * @return void
 * @param string
 *            file Chemin du media
 * @param string
 *            id Id du media
 * @param string
 *            obj Objet (javascript) HTML sélectionné
 * @desc Affichage de la page de description du Media et Mise en valeur dans la
 *       liste
 */
function previewMedia(file, id, obj) {
	showMedia(file, id);
	highlightSelected(obj);
}

/**
 * @return void
 * @param object
 *            obj Objet (javascript) HTML sélectionné
 * @desc Mise en valeur du media sélectionné dans la liste
 */
function highlightSelected(obj) {
	if (obj && current.zone == "media") {
		if (cSelected) {
			cSelected.style.backgroundColor = "";
		}
		cSelected = obj;
		cSelected.style.backgroundColor = "#C3DBF7";
	}
}

/**
 * @return void
 * @desc Rechargement des frames
 */
function reload(tiny) {
	/* // PLA20130129 : remplacer top.document par oDocument ?
	var oDocument;
	if (tiny) {
		oDocument = document;
	} else {
		oDocument = top.document;
	}
	if (current.zone == "media") {
		oDocument.location.href = oDocument.location.href;
	} else if (current.zone == "popup") {
		oDocument.location.href = oDocument.location.href;
	} else {
	  oDocument.getElementById('tree').src = oDocument.getElementById('tree').src;
	}
	*/
	if (current.zone == "media") {
		top.document.location.href = top.document.location.href;
	} else if(current.zone == "popup") {
		top.document.location.href = top.document.location.href;
	} else {
		top.document.getElementById('tree').src = top.document.getElementById('tree').src;
	}

}

/**
 * @return unknown
 * @param unknown
 *            aID
 * @param unknown
 *            obj
 * @desc Entrez la description ici...
 */
function getIFrameDocument(aID, obj) {
	oDocument = obj || document;
	// if contentDocument exists, W3C compliant (Mozilla)
	if (oDocument.getElementById(aID).contentDocument) {
		return oDocument.getElementById(aID).contentDocument;
	} else {
		// IE
		return oDocument.frames[aID].document;
	}
}

/**
 * @return void
 * @param unknown
 *            docSearch
 * @param unknown
 *            onglet
 * @desc Entrez la description ici...
 */
function activeOngletMedia(docSearch, onglet) {
	docSearch.fFormMediaSearch.root.value = current.physicalPath;
	docSearch.fFormMediaSearch.type.value = current.mediaType;
	docSearch.fFormMediaSearch.path.value = unescape(current.path);
	docSearch.fFormMediaSearch.recherche.value = "";
	docSearch.fFormMediaSearch.lib.value = mediaDir;
	if(typeof docSearch.getElementById("mediaFolder").innerText == "undefined"){
        docSearch.getElementById("mediaFolder").textContent = (current.path?unescape(current.path):"- Tout -");
    }else{
		docSearch.getElementById("mediaFolder").innerText = (current.path?unescape(current.path):"- Tout -");
    }
	if (ongletMedia) {
		docSearch.getElementById("ongletMedia"+ongletMedia+"_1").src = docSearch.getElementById("ongletMedia"+ongletMedia+"_1").src.replace("_on_", "_off_");
		docSearch.getElementById("ongletMedia"+ongletMedia+"_3").src = docSearch.getElementById("ongletMedia"+ongletMedia+"_3").src.replace("_on_", "_off_");
		docSearch.getElementById("ongletMedia"+ongletMedia+"_2").style.backgroundImage = docSearch.getElementById("ongletMedia"+ongletMedia+"_2").style.backgroundImage.replace("_on_", "_off_");
		docSearch.getElementById("divMedia"+ongletMedia).style.display = "none";
	}
	ongletMedia = onglet;
	docSearch.getElementById("ongletMedia"+ongletMedia+"_1").src = docSearch.getElementById("ongletMedia"+ongletMedia+"_1").src.replace("_off_", "_on_");
	docSearch.getElementById("ongletMedia"+ongletMedia+"_3").src = docSearch.getElementById("ongletMedia"+ongletMedia+"_3").src.replace("_off_", "_on_");
	docSearch.getElementById("ongletMedia"+ongletMedia+"_2").style.backgroundImage = docSearch.getElementById("ongletMedia"+ongletMedia+"_2").style.backgroundImage.replace("_off_", "_on_");
	docSearch.getElementById("divMedia"+ongletMedia).style.display = "";
	if (onglet == "1") {
		docSearch.fFormMediaSearch.submit();
	} else {
		$.globalEval($('#defaultjs', window.parent.document).html());
	}
}

function resetPath(docSearch) {
	current.path = "";
	activeOngletMedia(docSearch, "1");
}

/**
 * @return Paramètre d'url Timestamp
 * @desc Génération d'un timestamp pour éviter les problème de cache du
 *       navigateur
 */
function getTimeStamp() {
	var now = new Date();
	var timestamp = now.getYear().toString();
	timestamp += '.' + now.getMonth().toString();
	timestamp += '.' + now.getDate().toString();
	timestamp += '.' + now.getHours().toString();
	timestamp += '.' + now.getMinutes().toString();
	timestamp += '.' + now.getSeconds().toString();
	return "&timestamp="+timestamp;
}

/** DRAG & DROP */
var oriDragObj = "";
var destDragObj = "";
var typeDragObj = "";

function initDrag(obj, type) {
	if( !parseInt(obj) ){
		obj = getFolderId( document.getElementById(obj).nextSibling.href );
	}
	oriDragObj = obj;
	typeDragObj = type;
	destDragObj = '';
}
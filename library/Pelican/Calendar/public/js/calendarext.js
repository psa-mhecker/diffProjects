/**
* Fichier gérant la mise en place des programmations
* - fait appel à des methodes spécifiques à chaque module gérant les évènements
*   ces méthodes sont déclarées dans /calendar/module/js/module.js
*		- addEvent();
*		- chooseEvent();
*/


var selectDiv ='';//divselectionner
var previousColor ='';//stockage de la couleur du div
var previousClass ='';//stockage de la class de la cellule
var editAction = 0;

/* mise en place du div avec les parametres qui vont bien 
* stockage de la couleur de la cellule du calendrier
* changement de la couleur de la cellule du calendrier en couleur d'edition
*/
function ShowDial(divId, action, eventId) {	
	run = true;
	//on capte l'action edit pour empecher l'effet double click pour les div dans des div (sinon action edit et immediatement apres add => donc ça merde)
	if(action == 'edit') {
	   editAction=1;
	}
	if(editAction==1 && action=="add") {
	    editAction=0;
	    run = false;
	}
	if(run) {

		//on ferme le div
		closeDial();
		//changement des couleurs de fond entre le div courant et le precedement selectionner s'il existe
		var CellDiv = $(divId);
		if(selectDiv != '') {
			previousDiv = $(selectDiv);
			previousDiv.style.backgroundColor=previousColor;
		}
		previousClass = CellDiv.className;
		CellDiv.className ='CalCellSelected';
		selectDiv = divId;
		//on appel l'ouverture du div qui va bien 
		DisplayWindow(CellDiv, action, divId, eventId);
	}
}

/* fermeture du layer d'edition ouvert */
function closeDial() {
	MonLayer = $('winLayer');
	MonLayer.style.display="none";
	$('winLayer').innerHTML = '';
	if(selectDiv != '') {
		previousDiv = $(selectDiv);
		previousDiv.className=previousClass;
	}
}

/* affichage et position du div d'edition 
*appel de la methode d'add (insertion d'un nouveau contenu) 
*ou de la methode d'edition (modification ou suppression d'un contenu existant) 
*/
function DisplayWindow(obj, action, divId, eventId) {
	// on rend le layer visible a la bonne position
	MonLayer = $('winLayer');
	
	// positionnement en fct de l'ojet clické
	//postop = getPosTop(obj);
	//posleft = getPosLeft(obj);
	//MonLayer.style.top=postop -20
	//MonLayer.style.left=posleft+20
	
	// positionnement au centre
	MonLayer.style.top= 300;
	MonLayer.style.left=350;	
	
	MonLayer.style.display="inline";
	//switch en fonction des actions 
	if(action == 'add') {
		addEvent(divId, eventId);
	} else {
		chooseEvent(divId, eventId);
	}
}

/* on change l'innerHTML du div d'edition avec le retour du module */
function showResponse(originalRequest){
	$('winLayer').innerHTML = originalRequest.responseText;
}
	
/* recuperation de la position top de l'element en fonction d'ie ou firefox */
function getPosTop(el) {
var y
	if (document.getBoxObjectFor) {
		var bo = document.getBoxObjectFor(el);
		y = bo.y;
	}
	else if (el.getBoundingClientRect) {
		var bo = el.getBoundingClientRect();
		y = bo.top;
	} 
	return y
}

/* recuperation de la position left de l'element en fonction d'ie ou firefox */
function getPosLeft(el) {
var x
	if (document.getBoxObjectFor) {
		var bo = document.getBoxObjectFor(el);
		x = bo.x;
	}
	else if (el.getBoundingClientRect) {
		var bo = el.getBoundingClientRect();
		x = bo.left;
	} 
	return x
}

function URLDecode(psEncodeString){
  // Create a regular expression to search all +s in the string
  var lsRegExp = /\+/g;
  // Return the decoded string
  return unescape(String(psEncodeString).replace(lsRegExp, " "));
}

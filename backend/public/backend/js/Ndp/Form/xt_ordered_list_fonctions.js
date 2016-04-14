function preLoad(strObjName, strName, strPath) {
	eval("oImg" + strObjName + " = new Image();");
	obj = eval("oImg" + strObjName);
	obj.src = strPath + strName + ".gif";
	eval("oImg" + strObjName + "_over = new Image();");
	obj = eval("oImg" + strObjName + "_over");
	obj.src = strPath + strName + "_over.gif";
	eval("oImg" + strObjName + "_click = new Image();");
	obj = eval("oImg" + strObjName+ "_click");
	obj.src = strPath + strName + "_click.gif";
}

preLoad("top", "top", "../images/");
preLoad("up", "up", "../images/");
preLoad("down", "down", "../images/");
preLoad("bottom", "bottom", "../images/");

function ChangeImg(obj, strName) {
	obj.src = eval("oImg"+strName+".src");
}

// Fonctions pour l'ordre
function MoveTop(obj) {
	if (obj.selectedIndex != -1 ) {
		var ID = obj.options[obj.selectedIndex].value;
		var Nom = obj.options[obj.selectedIndex].text;
		var strListID = ID;

		for (i = obj.selectedIndex; i > 0; i--) {
			obj.options[i].value = obj.options[i-1].value;
			obj.options[i].text = obj.options[i-1].text;
		}
		obj.options[0].value = ID;
		obj.options[0].text = Nom;
	}
	obj.selectedIndex = 0;
}

function MoveUp(obj) {
	if ((obj.selectedIndex != -1) && (obj.selectedIndex > 0) ) {
		var ID = obj.options[obj.selectedIndex].value;
		var Nom = obj.options[obj.selectedIndex].text;

		obj.options[obj.selectedIndex].value = obj.options[obj.selectedIndex-1].value;
		obj.options[obj.selectedIndex].text = obj.options[obj.selectedIndex-1].text;
		obj.options[obj.selectedIndex-1].value = ID;
		obj.options[obj.selectedIndex-1].text = Nom;
		obj.selectedIndex = obj.selectedIndex-1;
	}
}

function MoveDown(obj) {
	if ((obj.selectedIndex != -1) && (obj.selectedIndex < obj.length-1) ) {
		var ID = obj.options[obj.selectedIndex].value;
		var Nom = obj.options[obj.selectedIndex].text;

		obj.options[obj.selectedIndex].value = obj.options[obj.selectedIndex+1].value;
		obj.options[obj.selectedIndex].text = obj.options[obj.selectedIndex+1].text;
		obj.options[obj.selectedIndex+1].value = ID;
		obj.options[obj.selectedIndex+1].text = Nom;
		obj.selectedIndex = obj.selectedIndex+1;
	}
}

function MoveBottom(obj) {
	if ((obj.selectedIndex != -1) && (obj.selectedIndex < obj.length) ) {
		var ID = obj.options[obj.selectedIndex].value;
		var Nom = obj.options[obj.selectedIndex].text;

		for (i = obj.selectedIndex; i < obj.length-1; i++) {
			obj.options[i].value = obj.options[i+1].value;
			obj.options[i].text = obj.options[i+1].text;
		}
		obj.options[obj.length-1].value = ID;
		obj.options[obj.length-1].text = Nom;
	}
	obj.selectedIndex = obj.length-1;
}

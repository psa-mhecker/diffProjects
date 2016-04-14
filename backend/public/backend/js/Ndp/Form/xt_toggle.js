function showHideModule(divID, doSetCookie) {
	var divIDobj = getElementById("DivToggle" + divID);
	var toggleobj = getElementById("Toggle" + divID);
	if (divIDobj != null && toggleobj != null) {
		if (divIDobj.style.display == "none") {
			toggleobj.src = libDir+"/public/images/toggle_open.gif";
			toggleobj.alt = "Masquer";
			divIDobj.style.display = "";
			if (doSetCookie == true) {
				setCookie('toggle'+divID, false, 30);
			}
		} else {
			toggleobj.src = libDir+"/public/images/toggle_close.gif";
			toggleobj.alt = "Afficher";
			divIDobj.style.display = "none";
			if (doSetCookie == true) {
				setCookie('toggle'+divID, true, 30);
			}
		}
	}
	 
}
 
function getElementById(n, d) {
	//v4.0
	var p, i, x;
	if (!d)
		d = document;
	if ((p = n.indexOf("?")) > 0 && parent.frames.length) {
		d = parent.frames[n.substring(p+1)].document;
		n = n.substring(0, p);
	}
	if (!(x = d[n]) && d.all) {
		x = d.all[n];
	}
	for (i = 0; !x && i < d.forms.length; i++) {
		x = d.forms[i][n];
	}
	for(i = 0; !x && d.layers && i < d.layers.length; i++) {
		x = getElementById(n, d.layers[i].document);
	}
	if (!x && document.getElementById) {
		x = document.getElementById(n);
	}
	return x;
}
 
function showHideOnglet(divID, type) {
	var divIDobj1 = getElementById(divID+"1");
	var divIDobj2 = getElementById(divID+"2");
	var divIDobj3 = getElementById(divID+"3");
	var divIDobj4 = getElementById(divID+"4");
	var divIDobj5 = getElementById(divID+"5");
	var divIDobj6 = getElementById(divID+"6");
	if (type) {
		divIDobj1.style.backgroundImage = divIDobj1.style.backgroundImage.replace("TabOff", "TabOn");
		divIDobj2.src = divIDobj2.src.replace("TabOff", "TabOn");
		divIDobj3.style.backgroundImage = divIDobj3.style.backgroundImage.replace("TabOff", "TabOn");
		divIDobj4.style.backgroundImage = divIDobj4.style.backgroundImage.replace("TabOff", "TabOn");
		divIDobj5.src = divIDobj5.src.replace("TabOff", "TabOn");
		if (divIDobj6) divIDobj6.style.display = "";
	} else {
		divIDobj1.style.backgroundImage = divIDobj1.style.backgroundImage.replace("TabOn", "TabOff");
		divIDobj2.src = divIDobj2.src.replace("TabOn", "TabOff");
		divIDobj3.style.backgroundImage = divIDobj3.style.backgroundImage.replace("TabOn", "TabOff");
		divIDobj4.style.backgroundImage = divIDobj4.style.backgroundImage.replace("TabOn", "TabOff");
		divIDobj5.src = divIDobj5.src.replace("TabOn", "TabOff");
		if (divIDobj6) divIDobj6.style.display = "none";
	}
}

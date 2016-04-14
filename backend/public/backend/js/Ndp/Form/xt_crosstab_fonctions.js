var check_all = new Image();
check_all.src = libDir+"/Pelican/Form/public/images/check_all.gif";
var check_all_over = new Image();
check_all_over.src = libDir+"/Pelican/Form/public/images/check_all_over.gif";
var check_all_click = new Image();
check_all_click.src = libDir+"/Pelican/Form/public/images/check_all_click.gif";
var uncheck_all = new Image();
uncheck_all.src = libDir+"/Pelican/Form/public/images/uncheck_all.gif";
var uncheck_all_over = new Image();
uncheck_all_over.src = libDir+"/Pelican/Form/public/images/uncheck_all_over.gif";
var uncheck_all_click = new Image();
uncheck_all_click.src = libDir+"/Pelican/Form/public/images/uncheck_all_click.gif";
 
function ChangeImg(obj, strName) {
	obj.src = eval(strName+".src");
}
 
function GlobalCheck(obj, strFieldBase, strCol, strRow, strAction, bRadio) {
	bEtat = strAction == "check";
	if (strCol != "" ) {
		for (i = 0; i < obj.elements.length; i++ ) {
			if (! bRadio) {
				if ((obj.elements[i].name.indexOf(strFieldBase) != -1) && (obj.elements[i].name.indexOf("_X"+strCol) != -1) ) {
					obj.elements[i].checked = bEtat;
				}
			} else {
				if(obj.elements[i].name){
					if (obj.elements[i].name.indexOf(strFieldBase+"_Y") != -1 ) {
					if (obj.elements[i].value == strCol) {
						obj.elements[i].checked = bEtat;
					}
				}
			}
		}
	}
	}
	else if (strRow != "" ) {
		for (i = 0; i < obj.elements.length; i++ )
		if (obj.elements[i].name.indexOf(strFieldBase+"_Y"+strRow) != -1 )
			obj.elements[i].checked = bEtat;
	} else {
		for (i = 0; i < obj.elements.length; i++ )
		if (obj.elements[i].name.indexOf(strFieldBase) != -1 )
			obj.elements[i].checked = bEtat;
	}
}

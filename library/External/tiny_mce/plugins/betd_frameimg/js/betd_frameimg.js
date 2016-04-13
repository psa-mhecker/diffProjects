
// 
var betd_InternalLinkDialog = {
	insert : function(href) {
		var inst = tinyMCEPopup.editor;
		var elm, elementArray, i;
		
		elm = inst.selection.getNode();
		elm = inst.dom.getParent(elm, "A");
		if (elm == null) {
			tinyMCEPopup.execCommand("CreateLink", false, "#mce_temp_url#");
			
			elementArray = tinymce.grep(inst.dom.select("a"), function(n) {return inst.dom.getAttrib(n, 'href') == '#mce_temp_url#';});
		for (i=0; i<elementArray.length; i++) {
			elm = elementArray[i];
			// Move cursor to end
			try {
				tinyMCEPopup.editor.selection.collapse(false);
			} catch (ex) {
				// Ignore
			}
			setAttrib(elm, 'href', href);
		}
			
		} 
		tinyMCEPopup.close();
	}
};

function setAttrib(elm, attrib, value) {
	var formObj = document.forms[0];
	var valueElm = formObj.elements[attrib.toLowerCase()];

	if (typeof(value) == "undefined" || value == null) {
		value = "";

		if (valueElm)
			value = valueElm.value;
	}
	
	if (value != "") {
		elm.setAttribute(attrib.toLowerCase(), value);

		if (attrib == "style")
			attrib = "style.cssText";

//		if (attrib.substring(0, 2) == 'on')
//			value = 'return true;' + value;

		if (attrib == "class")
			attrib = "className";

		elm[attrib] = value;
	} else
		elm.removeAttribute(attrib);
}



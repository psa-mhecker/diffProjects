HM_DOM = (document.getElementById?true:false);
HM_NS4 = (document.layers?true:false);
HM_IE = (document.all?true:false);
HM_IE4 = HM_IE && !HM_DOM;
HM_Mac = (navigator.appVersion.indexOf("Mac") != -1);
HM_IE4M = HM_IE4 && HM_Mac;
HM_Opera = (navigator.userAgent.indexOf("Opera") != -1);
HM_Konqueror = (navigator.userAgent.indexOf("Konqueror") != -1);
HM_Safari = (navigator.vendor == 'KDE' || (document.childNodes && !document.all && !navigator.taintEnabled ));
HM_IsMenu = !HM_Opera && !HM_Konqueror && !HM_IE4M && (HM_DOM || HM_NS4 || HM_IE4);
HM_BrowserString = HM_NS4 ? "NS4" : HM_DOM ? "DOM" : "IE4";
HM_IE_version = (HM_IE) ? parseFloat(navigator.appVersion.split("MSIE")[1]) : null;
 
if (typeof isDescendant != 'function') {
	function isDescendant(el,em) {
		return false;
	}
}

//allows DIV tags to show over SELECT boxes
//  various browsers have trouble with this
function hideSelectBoxes(el, on) {
	if ((HM_IE && !HM_Mac && !HM_Opera) || HM_Safari) {
		//get menu dimensions
		var p = getAbsolutePosition(el);
		 
		if (HM_IE_version >= 5.5) {
			//in this case, we create an IFRAME that sits just under the div tag
			//this is done because IE 5.5+ exibits the strange behaviour that
			//an iframe can be displayed over a select box AND under a div tag
			//but a div tag, by itself, can never go over a select box
			//so, by layering them like this, the menu is properly displayed
			 
			//      var iframeID = el.id+"_hide_iframe";
			var iframeID = "hide_iframe";
			var myIframe;
			if (myIframe = document.getElementById(iframeID)) {
				myIframe.style.display = (on?"":"none");
				myIframe.style.left = p.x;
				myIframe.style.top = p.y;
				myIframe.style.width = el.offsetWidth;
				myIframe.style.height = el.offsetHeight;
			}
			else //create the iframe
			{
				myIframe = document.createElement("IFRAME");
				myIframe.setAttribute("id", iframeID);
				myIframe.setAttribute("src", "/library/blank.html");
				 
				myIframe.style.border = 0;
				myIframe.style.position = "absolute";
				myIframe.style.left = p.x;
				myIframe.style.top = p.y;
				myIframe.style.width = el.offsetWidth;
				myIframe.style.height = el.offsetHeight;
				myIframe.style.zIndex = el.style.zIndex-1;
				myIframe.style.filter = "Alpha(opacity=0)";
				 
				document.body.appendChild(myIframe);
			}
		}
		else //IE version is below 5.5
		{
			//in this case, the browser will not allow a div to be displayed
			//over a select box no matter what, so we simply grab all the
			//select boxes and hide them if there is overlap with the new menu
			//or show them if the menu is closing
			 
			//get menu dimensions
			var HM_EX1 = p.x;
			var HM_EX2 = el.offsetWidth + HM_EX1;
			var HM_EY1 = p.y;
			var HM_EY2 = el.offsetHeight + HM_EY1;
			 
			//now get all "trouble" elements
			var tags = new Array("applet", "iframe", "select");
			 
			for (var k = 0; k < tags.length; k++ ) {
				var elems = document.getElementsByTagName(tags[k]);
				var elem = null;
				 
				for (var i = 0; i < elems.length; i++) {
					elem = elems[i];
					 
					//get elem dimensions
					p = getAbsolutePosition(elem);
					var HM_CX1 = p.x;
					var HM_CX2 = elem.offsetWidth + HM_CX1;
					var HM_CY1 = p.y;
					var HM_CY2 = elem.offsetHeight + HM_CY1;
					 
					if (!((HM_CX1 > HM_EX2) || (HM_CX2 < HM_EX1) || (HM_CY1 > HM_EY2) || (HM_CY2 < HM_EY1)) && !isDescendant(el, elem) ) {
						//elements overlap, hide or show elem
						if (on) {
							//save old value
							elem[el.id] = elem.style.visibility;
							 
							//hide element
							elem.style.visibility = "hidden";
						} else {
							//restore old value
							elem.style.visibility = elem[el.id];
						}
					}
				}
			}
		}
	}
}
 
// finds position on page, even for inline elements
function getAbsolutePosition(elem) {
	var r = { x: elem.offsetLeft, y: elem.offsetTop };
	if (elem.offsetParent) {
		var tmp = getAbsolutePosition(elem.offsetParent);
		r.x += tmp.x;
		r.y += tmp.y;
	}
	return r;
}
/**
* $Id: editor_plugin_src.js 126 2008-09-15 11:20:55Z spocke $
*
* @author pascale.laurent@businessdecision.com
*
*/

(function() {
	tinymce.PluginManager.requireLangPack('betd_index');

	tinymce.create('tinymce.plugins.betd_index', {
		init : function(ed, url) {
			var t = this;
			t.editor = ed;

			// Register commands
			ed.addCommand('betd_index', function() {
				
				// recherche de toutes les ancres
				var aAnchors = ed.dom.doc.anchors; 
				
				if (!aAnchors || aAnchors.length < 1) {
					alert('Aucune ancre n\'a été définie');
					// on ne devrait pas supprimer TOC s'il existe ?
					return;
				}
				
				// div TOC pour l'index
				var myToc = ed.dom.doc.getElementById('toc');
				
				if (!myToc) { 
					// pas de TOC, on crée l'élément DOM
					var myDiv = ed.getDoc().createElement('div');
					myDiv.setAttribute('id','toc');
					var myStart = ed.getDoc().documentElement.childNodes[1].firstChild; // 1er paragraphe
					if (myStart.nodeName == 'P' && myStart.textContent.length < 1 && myStart.parentNode.childNodes.length > 1) {
						myStart = myStart.parentNode.childNodes[1]; // on décalle vers le bas le nouveau DIV si <P>&nbsp;</P> intrus au début
					}
					// placement de TOC au début du texte
					ed.dom.doc.body.insertBefore(myDiv,myStart);
					var myToc = ed.dom.doc.getElementById('toc');
				} else {
					// TOC existe, on le vide
					myToc.removeChild(myToc.childNodes[0]);
				}
				
				// création de l'élément de liste UL
				var myUl = ed.getDoc().createElement('ul');
				myToc.appendChild(myUl);
				
				// génération de l'index des ancres, élément par élément
				for (aa = 0; aa < aAnchors.length; aa++) {
					if (aAnchors[aa].name) {
						var reg = new RegExp("[-]", "g");
						var myLi = ed.getDoc().createElement('li');
						var myLink = ed.getDoc().createElement('a');
						myLink.setAttribute('title',aAnchors[aa].name.replace(reg, ' '));
						myLink.setAttribute('href','#'+ aAnchors[aa].name);
						var myTxt = ed.getDoc().createTextNode(aAnchors[aa].name.replace(reg, ' '));
						myLink.appendChild(myTxt);
						myLi.appendChild(myLink);
						myToc.firstChild.appendChild(myLi);
					}
				}
				
			});
			// Register buttons
			ed.addButton('betd_index', {title : 'Sommaire', cmd : 'betd_index', image : url + '/img/toc.gif'});

		},

		getInfo : function() {
			return {
				longname : 'BetD index',
				author : 'Business & Decision',
				authorurl : 'http://www.businessdecision.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}

	});

	// Register plugin
	tinymce.PluginManager.add('betd_index', tinymce.plugins.betd_index);
})();

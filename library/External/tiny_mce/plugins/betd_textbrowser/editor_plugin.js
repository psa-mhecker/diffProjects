/**
* $Id: editor_plugin_src.js 126 2008-06-27 16:19:55Z spocke $
*
* @author raphael.carles@businessdecision.com
*/

(function() {
	tinymce.create('tinymce.plugins.betd_textbrowser', {
		init : function(ed, url) {
			ed.addCommand('betd_textbrowser', function() {
				var vText = '<?xml version="1.0" encoding="ISO-8859-1" ?><html><body>' + ed.getContent() + '</body></html>';
				vText = vText.replace(/\&nbsp\;/gi,' ');
				var xslStylesheet = url + '/css/screenreader.xsl';
				if (vText) {
					if (tinymce.isIE) {
						source = new ActiveXObject("Microsoft.XMLDOM");
						source.async = false;

						style = new ActiveXObject("Microsoft.XMLDOM");
						style.async = false;

						source.loadXML(vText);

						style.load(xslStylesheet);
						vTextBrowser = source.transformNode(style);
					} else {
						var xsltProcessor = new XSLTProcessor();
						var myXMLHTTPRequest = new XMLHttpRequest();
						myXMLHTTPRequest.open("GET", xslStylesheet, false);
						myXMLHTTPRequest.send(null);
						xslStylesheet = myXMLHTTPRequest.responseXML;
						var parser=new DOMParser(); // création d'un objet XML
						var myDocXML = parser.parseFromString(vText,"text/xml"); // remplissage de l'objet avec vText
						xsltProcessor.importStylesheet(xslStylesheet); // import de la XSL
						tmpTextBrowser = xsltProcessor.transformToDocument(myDocXML); // application de la XSL
						var vTextBrowser = (new XMLSerializer()).serializeToString(tmpTextBrowser); // sérialisation du flux (on doit renvoyer une chaine)
						vTextBrowser = (vTextBrowser.replace(/\&gt\;/gi,'>').replace(/\&lt\;/gi,'<')); // faut-il enlever les balises <transformiix:result> qui encapsulent ???
						// reste à corriger les caractères spéciaux avec &..; ainsi que les images
					}
				}

				ed.windowManager.open({
					file : url + '/textbrowser.htm',
					width : 640,
					height : 430,
					inline : 1
				}, {
					plugin_url : url,
					text : vTextBrowser
				});
			});

			// Register buttons
			ed.addButton('betd_textbrowser', {title : 'betd_textbrowser.desc', cmd : 'betd_textbrowser', image : url + '/img/textbrowser.gif'});
		},

		getInfo : function() {
			return {
				longname : 'Navigateur Texte',
				author : 'Business & Decision',
				authorurl : 'http://www.businessdecision.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('betd_textbrowser', tinymce.plugins.betd_textbrowser);
})();


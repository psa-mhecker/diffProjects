/**
* $Id: editor_plugin_src.js 126 2006-10-22 16:19:55Z spocke $
*
* @author patrick.deroubaix@businessdecision.com
*
*/

(function() {
	tinymce.PluginManager.requireLangPack('betd_internallink');

	tinymce.create('tinymce.plugins.betd_internallink', {
		init : function(ed, url) {
			// Register commands
			ed.addCommand('betd_internallink', function() {
				var se = ed.selection;

				// No selection and not in link
				if (se.isCollapsed() && !ed.dom.getParent(se.getNode(), 'A'))
				return;

				ed.windowManager.open({
					file : url + '/popup_internallink.php',
					width : 600,
					height : 170,
					inline : 1
				}, {
					plugin_url : url
				});
			});
			// Register buttons
			ed.addButton('betd_internallink', {title : 'betd_internallink.desc', cmd : 'betd_internallink', image : url + '/img/internallink.gif'});

			ed.onNodeChange.add(function(ed, cm, n, co) {
				cm.setDisabled('betd_internallink', co && n.nodeName != 'A');
				cm.setActive('betd_internallink', n.nodeName == 'A' && !n.name);
			});
		},

		getInfo : function() {
			return {
				longname : 'BetD internallink',
				author : 'Business & Decision',
				authorurl : 'http://www.businessdecision.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}

	});

	// Register plugin
	tinymce.PluginManager.add('betd_internallink', tinymce.plugins.betd_internallink);
})();


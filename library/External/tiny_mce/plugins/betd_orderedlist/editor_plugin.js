/**
 * $Id: editor_plugin_src.js 539 2008-01-14 19:08:58Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2008, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	tinymce.create('tinymce.plugins.betd_orderedlistPlugin', {
		init : function(ed, url) {
			this.editor = ed;

			// Register commands
			ed.addCommand('betd_orderedlist', function() {
				var se = ed.selection;

				// No selection and not in link
				if (se.isCollapsed() && !ed.dom.getParent(se.getNode(), 'A'))
					return;

				ed.windowManager.open({
					file : url + '/list .htm',
					width : 480 + parseInt(ed.getLang('betd_orderedlist.delta_width', 0)),
					height : 150 + parseInt(ed.getLang('betd_orderedlist.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			// Register buttons
			ed.addButton('betd_orderedlist', {
				title : 'betd_orderedlist.link_desc',
				cmd : 'betd_orderedlist',
				image : url + '/img/betdmailto.gif'
			});

			ed.addShortcut('ctrl+k', 'betd_orderedlist.advlink_desc', 'betd_orderedlist');

			ed.onNodeChange.add(function(ed, cm, n, co) {
				cm.setDisabled('link', co && n.nodeName != 'A');
				cm.setActive('link', n.nodeName == 'A' && !n.name);
			});
		},

		getInfo : function() {
			return {
				longname : 'betd_orderedlist',
				author : 'businessdecision patrick.deroubaix@businessdecision.com',
				authorurl : 'http://www.businessdecision.com',
				infourl : '',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('betd_orderedlist', tinymce.plugins.betd_orderedlistPlugin);
})();
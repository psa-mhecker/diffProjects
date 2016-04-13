/**
 * $Id: editor_plugin_src.js 126 2006-10-22 16:19:55Z spocke $
 *
 * @author patrick.deroubaix@businessdecision.com
 * 
 */
(function() {
	tinymce.create('tinymce.plugins.betd_frameimg', {
		init : function(ed, url) {
			// Register commands
		ed.addCommand('betd_frameimg', function() {
				var se = ed.selection;

				// No selection and not in link
				if (se.isCollapsed() && !ed.dom.getParent(se.getNode(), 'A'))
					return;
		
				//http://backoffice.bnpp.intranet/library/Pelican/Media/public/media_editor.php?path=%2Fimage%2F17%2F1%2F1171.jpg&format=11
					ed.windowManager.open({
						file : 'http://backoffice.bnpp.intranet/library/Pelican/Media/public/media_editor.php?path=%2Fimage%2F17%2F1%2F1171.jpg&format=11',
						width : 640,
						height : 400,
						inline : 1
					}, {
						plugin_url : url
					});
		});
			// Register buttons
			ed.addButton('betd_frameimg', {title : 'betd_frameimg.desc', cmd : 'betd_frameimg', image : 'http://media.bnpp.intranet/library/Pelican/Media/public/images/tool.gif'});
		},

		getInfo : function() {
			return {
				longname : 'BetD Image Framing',
				author : 'Business & Decision',
				authorurl : 'http://www.businessdecision.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}

	});

	// Register plugin
	tinymce.PluginManager.add('betd_frameimg', tinymce.plugins.betd_frameimg);
})();


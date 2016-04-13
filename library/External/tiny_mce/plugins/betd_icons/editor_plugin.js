/**
 * $Id: editor_plugin_src.js 520 2008-01-07 16:30:32Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2008, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	
	tinymce.PluginManager.requireLangPack('betd_icons');

	tinymce.create('tinymce.plugins.betd_icons', {
		init : function(ed, url) {
			// Register commands
			ed.addCommand('mceEmotion', function() {
				ed.windowManager.open({
					file : url + '/icons.htm',
					width : 250 + parseInt(ed.getLang('betd_icons.delta_width', 0)),
					height : 160 + parseInt(ed.getLang('betd_icons.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			// Register buttons
			ed.addButton('betd_icons', {title : 'betd_icons.desc', cmd : 'mceEmotion', image : url + '/img/icons.gif'});
		},

		getInfo : function() {
			return {
				longname : 'BetD Emoticons',
				author : 'Business & Decision',
				authorurl : 'http://www.businessdecision.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('betd_icons', tinymce.plugins.betd_icons);
})();
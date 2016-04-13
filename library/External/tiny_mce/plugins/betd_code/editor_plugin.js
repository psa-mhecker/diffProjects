(function() {
	tinymce.PluginManager.requireLangPack('betd_code'); 
	tinymce.create('tinymce.plugins.AdvancedCodeEditorPlugin', {
    	init : function(ed, url) {
			// Register commands
			ed.addCommand('mceCodeEditor', function() {
				ed.windowManager.open({
					file : url + '/betd_code.html',
					width : 600 + parseInt(ed.getLang('betd_code.delta_width', 0)),
					height : 400 + parseInt(ed.getLang('betd_code.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});
			// Register buttons
			ed.addButton('betd_code', {
				title : 'Afficher le code',
				cmd : 'mceCodeEditor',
				image : url + '/img/betd_code.gif'
			});
			ed.onNodeChange.add(function(ed, cm, n) {});
    	},
		getInfo : function() {
			return {
				longname : 'Advanced Code Editor',
				author : 'Ryan Demmer',
				authorurl : 'http://www.joomlacontenteditor.net',
				infourl : 'http://www.joomlacontenteditor.net',
				version : '1.0.0'
			};
		}
	});

  	// Register plugin
	tinymce.PluginManager.add('betd_code', tinymce.plugins.AdvancedCodeEditorPlugin);
})();

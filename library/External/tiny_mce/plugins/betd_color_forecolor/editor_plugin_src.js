/**
* $Id: editor_plugin_src.js 126 2006-10-22 16:19:55Z spocke $
*
* @author sebastien.maillot@businessdecision.com
*
*/

(function() {
	tinymce.PluginManager.requireLangPack('betd_color_forecolor');

	tinymce.create('tinymce.plugins.betd_color_forecolor', {
            createControl: function(n, cm) {
                    switch (n) {
                        case 'betd_color_forecolor':

                    var o = {};
                    ed=tinyMCE.activeEditor;
                    o.scriptURL = ed.baseURI.getURI();
                    o['class'] = 'mce_forecolor';
                    o.scope=this;
                    o.custom = true;
                    o.title = 'betd_color_forecolor.title';
                    o.onclick = function (color) {  
                        ed.execCommand('ForeColor', false, color);
                    };
                    o.onselect = function (color) {
                        ed.execCommand('ForeColor', false, color);
                    };
                    
                    // limit the colors using own setting                    
                    if (v = ed.getParam('theme_advanced_text_colors')) o.colors = v;
                    if (v = ed.getParam('theme_advanced_text_colors2')) o.colors2 = v;
                    if (v = ed.getParam('theme_advanced_text_colors3')) o.colors3 = v;
                    o.title1 = 'betd_color_forecolor.couleur1';
                    o.title2 = 'betd_color_forecolor.couleur2';
                    o.title3 = 'betd_color_forecolor.couleur3';
                    if (dc = ed.getParam('default_color')) o.default_color = dc;

    	            var mcsb = cm.createColorSplitButton('myForecolor', o);
		                   
                    // return the new ColorSplitButton instance
                    return mcsb;
                }
                return null;
            }

	});

	// Register plugin
	tinymce.PluginManager.add('betd_color_forecolor', tinymce.plugins.betd_color_forecolor);
})();



    
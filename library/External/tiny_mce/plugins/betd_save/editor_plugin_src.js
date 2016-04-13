/**
* $Id: editor_plugin_src.js 520 2008-01-07 16:30:32Z spocke $
*
* @author BETD
* @copyright Copyright ©2008 BETD.
*/

(function() {
	tinymce.create('tinymce.plugins.betd_save', {
		init : function(ed, url) {
			var t = this;
			t.editor = ed;
			// Register commands
			ed.addCommand('mceSave', t._save, t);
			ed.addCommand('mceCancel', t._cancel, t);
			ed.addCommand('getMediaVarPath', function(sHtml) {return t.getMediaVarPath(sHtml);});
			ed.addCommand('getHttpPath', function(sHtml) {return t.getHttpPath(sHtml);});

			// Register buttons
			ed.addButton('betd_save', {title : 'betd_save.save_desc', cmd : 'mceSave', image : url + '/img/save.gif'});
			ed.addButton('cancel', {title : 'betd_save.cancel_desc', cmd : 'mceCancel'});

			ed.onNodeChange.add(t._nodeChange, t);
			ed.addShortcut('ctrl+s', ed.getLang('betd_save.save_desc'), 'mceSave');
		},

		getInfo : function() {
			return {
				longname : 'BetD Save',
				author : 'Business & Decision',
				authorurl : 'http://www.businessdecision.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		},

		// Private methods

		_nodeChange : function(ed, cm, n) {
			var ed = this.editor;

			if (ed.getParam('save_enablewhendirty')) {
				cm.setDisabled('betd_save', !ed.isDirty());
				cm.setDisabled('cancel', !ed.isDirty());
			}
		},

		// Private methods

		_save : function() {
			var t = this;
			var ed = this.editor, formObj, os, i, elementId;

			if (ed.getParam("fullscreen_is_enabled"))
			return true;

			formObj = tinymce.DOM.get(ed.id).form;

			if (ed.getParam("save_enablewhendirty") && !ed.isDirty())
			return true;

			if (formObj) {
				tinyMCE.triggerSave();
				ed.startContent = tinymce.trim(ed.getContent({format : 'raw'}));
				ed.startContent;
				window.opener.getElementById(ed.getParam('boxName')).value=t.getMediaVarPath(ed.startContent);
				t.generatePreview();
				window.close();
				// Use callback instead
				/*
				if (os = ed.getParam("save_onsavecallback")) {
				if (ed.execCallback('save_onsavecallback', ed)) {
				ed.startContent = tinymce.trim(ed.getContent({format : 'raw'}));
				alert(ed.startContent);
				///ed.nodeChanged();
				}

				return;
				}
				*/
				/*
				ed.isNotDirty = true;

				if (formObj.onsubmit == null || formObj.onsubmit() != false)
				tinymce.DOM.get(ed.id).form.submit();

				ed.nodeChanged();
				*/
			} else
			ed.windowManager.alert("Error: No form element found.");

			return true;

		},

		_cancel : function() {
			var ed = this.editor, os, h = tinymce.trim(ed.startContent);

			// Use callback instead
			if (os = ed.getParam("save_oncancelcallback")) {
				ed.execCallback('save_oncancelcallback', ed);
				return;
			}

			ed.setContent(h);
			ed.undoManager.clear();
			ed.nodeChanged();
		},

		generatePreview : function() {
			var ed = this.editor
			var t = this;
			var obj = window.opener.frames.eval('iframeText' + ed.getParam('boxName'));
			//obj.document.clear();
			obj.document.open();
			obj.document.write(t.createHTML(window.opener.getElementById(ed.getParam('boxName')).value));
			obj.document.close();
		},

		getMediaVarPath : function(sHtml) {
			var ed = this.editor
			if (sHtml) {
				if (ed.getParam('MediaHttpPath').length > 0 && ed.getParam('MediaVarPath').length > 0) {
					temp = new RegExp(ed.getParam('MediaHttpPath') , "gi");
					sHtml = sHtml.replace(temp, ed.getParam('MediaVarPath'));
				}
			}
			return sHtml;
		},

		getHttpPath : function(sHtml) {
			var ed = this.editor
			if (ed.getParam('MediaHttpPath').length > 0 && ed.getParam('MediaVarPath').length > 0) {
				temp = new RegExp(ed.getParam('MediaVarPath') , "gi");
				sHtml = sHtml.replace(temp, ed.getParam('MediaHttpPath'));
			}
			return sHtml;
		},

		// Charge le texte dans le composant
		createHTML : function(body) {
			var ed = this.editor
			var t = this;
			var head = "";
			var finish = "";
			head = "<html><head>";
			head += "<link id=\"styleLink\" rel=\"stylesheet\" type=\"text/css\" href=\"" + ed.getParam('CssPath') + "\" />";
			head += "</head><body>";
			body = t.getHttpPath(body);
			finish = "</body></html>";
			return head + body + finish ;
		},

	});

	// Register plugin
	tinymce.PluginManager.add('betd_save', tinymce.plugins.betd_save);
})();

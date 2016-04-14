/**
 * plugin.js
 *
 * Copyright, Hamdi Afrit
 * Released under LGPL License.
 *
 * License: http://www.tinymce.com/license
 * Contributing: http://www.tinymce.com/contributing
 */

/*global tinymce:true */

tinymce.PluginManager.add('infobulle', function(editor) {

	var pluginPath = '/../../../../External/tiny_mce/plugins/infobulle/';
	var pluginPathCss = '/library/External/tiny_mce/plugins/infobulle/';

	tinymce.addI18n('fr',{
		"Add tooltip":"Ajouter un infobulle",
		"Remove tooltip":"Enlever l'infobulle",
		"Tooltip name":"Nom de l'infobulle",
		"Tooltip text":"info text"
	});

	editor.contentStyles.push('span.has-tip.infobulle{' +
		'cursor:default;' +
		'color: #FFFFFF;' +
		'display:inline-block;' +
		'-webkit-user-select:all;' +
		'-webkit-user-modify:read-only;' +
		'-moz-user-select:all;' +
		'-moz-user-modify:read-only;' +
		'user-select:all;' +
		'user-modify:read-only;' +
		'background-color: #FF0000;' +
		'padding: 0 3px;' +
		'}');

	editor.on('click', function(e) {
		var target = e.target;
		var dom = editor.dom;
		var selection = editor.selection;

		if (/^(IMG|HR)$/.test(target.nodeName)) {
			e.preventDefault();
			selection.getSel().setBaseAndExtent(target, 0, target, 1);
		}

		if (target.nodeName == 'SPAN' && dom.hasClass(target, 'infobulle')) {
			e.preventDefault();
			selection.select(target);
		}
	});

	/* Add infobulle function */
	function showInfobulleDialog() {

		var dom = editor.dom;
		var selection = editor.selection;
		var selectedNode = selection.getNode();
		var parent = dom.getParent(selectedNode, 'span.has-tip.infobulle') ? dom.getParent(selectedNode, 'span.has-tip.infobulle') : '';
		var infotext = dom.getAttrib(parent, 'title') ? dom.getAttrib(parent, 'title') : '';

		editor.windowManager.open({
			title: 'Infobulle',
			body: [
				{
					name: 'infotext',
					multiline: true,
					type: 'textbox',
					size: 100,
					label: 'Tooltip text',
					value: infotext,
					autofocus: true
				},
			],
			onsubmit: function(e) {
				editor.execCommand(
					'mceInsertContent', false, editor.dom.createHTML('span', {
							class: 'has-tip infobulle',
							title: e.data.infotext,
							'aria-haspopup': 'true',
							'data-tooltip': ''
						},
						editor.getParam("info_icon_value")
					)
				);
			}
		});
	}

	/* Delete infobulle function */
	function deleteInfobulle() {

		var dom = editor.dom;
		var selection = editor.selection;
		var selectedNode = selection.getNode();
		var parent = dom.getParent(selectedNode, 'span.has-tip.infobulle');

		if (selectedNode.tagName == 'SPAN') {
			dom.remove(parent, 1);
			return;
		}

		editor.formatter.remove("infobulle");
	}

	editor.addButton('infobulle', {
		tooltip: 'Add tooltip',
		onclick: showInfobulleDialog,
		stateSelector: 'span.has-tip.infobulle',
		image : tinymce.plugins.infobulle + pluginPath + 'img/infobulle.png'
	});

	editor.addButton('uninfobulle', {
		tooltip: 'Remove tooltip',
		onclick: deleteInfobulle,
		stateSelector: 'span.has-tip.infobulle',
		image : tinymce.plugins.infobulle + pluginPath + 'img/uninfobulle.png'
	});

	editor.addMenuItem('infobulle', {
		icon: tinymce.plugins.infobulle + pluginPath + 'img/infobulle.png',
		text: 'Infobulle',
		context: 'insert',
		onclick: showInfobulleDialog
	});
});

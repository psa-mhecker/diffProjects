var betd_mediaDialog = {
	insert : function(file, title, id, type) {
		var ed = tinyMCEPopup.editor, dom = ed.dom;
		var obj = current.fileAttribut;
		/*
		 * if (current.img) { obj["width"] = current.img.width; obj["height"] =
		 * current.img.height; }
		 */
		obj["width"] = '';
		obj["height"] = '';

		// for (ii in current) { alert(ii); }
		switch (type) {
		case 'flash': {
			tinyMCEPopup
					.execCommand(
							'mceInsertContent',
							false,
							dom
									.createHTML(
											'img',
											{
												src : '/library/External/tiny_mce/plugins/betd_media/img/flash_tmp.gif',
												alt : 'image temporaire',
												title : 'src:"' + file + '",title:"tmp"',
												'class' : 'mceItemFlash',
												width : obj["width"],
												height : obj["height"],
												id : id,
												border : 0,
												wmode : 'transparent'
											}));
			break;
		}
		case 'file': {
			tinyMCEPopup.execCommand("createlink", false, file);
			break;
		}
		case 'image': {
			tinyMCEPopup.execCommand('mceInsertContent', false, dom.createHTML(
					'img', {
						src : file,
						alt : (obj["alt"] ? obj["alt"] : title),
						title : (title ? title : obj["alt"]),
						border : 0
					}));
			/*
			 * width : (obj["width"]?obj["width"], height : obj["height"],
			 * 
			 */
			break;
		}
		default: {
			tinyMCEPopup.execCommand('mceInsertContent', false, dom.createHTML(
					'img', {
						src : file,
						alt : title,
						title : title,
						border : 0
					}));
		}
			break;
		}
		tinyMCEPopup.close();
	},
	insertMultiple : function(path, files, titles) {
		var ed = tinyMCEPopup.editor, dom = ed.dom;
		var i = 0;
		var imgs = "";
		for (i = 0; i < files.length; i++) {
			imgs = imgs + '<img src="' + path + unescape(files[i]) + '" alt="'
					+ titles[i] + '" title="' + titles[i] + '" border="0"/>';
		}
		tinyMCEPopup.execCommand('mceInsertContent', false, imgs);
		tinyMCEPopup.close();
	}
};

select = function() {
	betd_mediaDialog.insert(httpMediaDir + unescape(current.mediaPath), '', 1,
			current.mediaType);
};

closePopup = function() {
	tinyMCEPopup.close();
}
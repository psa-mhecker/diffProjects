{$doctype}
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
{$header}
{literal}
<script type="text/javascript">
jQuery(document).ready(function($){
    // On ne fait pas l'autoresize sur internet explorer il ne gère pas le changement de feuille de style
	try{
		if($.browser.msie){
			return;
		}
	} catch(ex){
		return;
	}

	// Récupération de l'URL de la feuille de style PHP
	window.styleCssPhpUrl = null;
	window.styleCssPhpElement = null;
	$('head link[rel=stylesheet]').each(function(key, val){
		if( $(this).attr('href').match(/style\.css\.php/) ){
			window.styleCssPhpUrl = $(this).attr('href');
			window.styleCssPhpElement = $(this);
			return;
		}
	});

	// Lors du resize, on met à jour la feuille CSS PHP pour occuper toute la fenêtre
	window.resizeManager = {
		rtime: new Date(1, 1, 2000, 12,00,00),
		timeout: false,
		delta: 200,
		resizeEndCallback: function(){
			if( new Date() - window.resizeManager.rtime < window.resizeManager.delta ){
				setTimeout(window.resizeManager.resizeEndCallback, window.resizeManager.delta);
			} else {
				window.resizeManager.timeout = false;
				
				// Ajout de la nouvelle feuille de style mise à jour
				var newCss = window.styleCssPhpElement.clone();
				newCss.attr('href', window.styleCssPhpUrl +'&'+ $.param({
					'screen_width'  : window.outerWidth,
					'screen_height' : window.innerHeight - 80
				}));
				window.styleCssPhpElement.after(newCss);
				
				// Suppression de l'ancienne balise link une fois que la nouvelle est chargée
				var oldCss = window.styleCssPhpElement;
				window.styleCssPhpElement = newCss;
				setTimeout(function(){
					oldCss.remove();
				}, 2000);
			}
		}
	};
	$(window).resize(function(e){
		window.resizeManager.rtime = new Date();
		if (window.resizeManager.timeout === false){
			window.resizeManager.timeout = true;
			setTimeout(window.resizeManager.resizeEndCallback, window.resizeManager.delta);
		}
	});
});
</script>
{/literal}
    {literal}
    <script type="text/javascript">
    function popupMediaUsage (mediaId){
    var arr;
    var args = new Object;;
    args["mediaid"] = mediaId;
        arr = showModalDialog(
            "/_/Media/popupUsage?media_id="+mediaId,
            args,
            "dialogWidth:640px; dialogHeight:470px; scroll:no; status:no; center:yes; help:no");
    }
    </script>
    {/literal}
</head>
<body id="body_popup" leftmargin="3" topmargin="3" onload="init()">
<fieldset id="div_fieldset"><legend><b>{'POPUP_MEDIA_BROWSER'|t}&nbsp;:&nbsp;
<script type="text/javascript">
		var env = window;
		var mediaType = current.mediaType;
		current.zone = 'popup';
		
		//if (!parent.goMedia) {literal}{{/literal}
			
			parent.goMedia = goMedia;
			//{literal}}{/literal}
		if ('{$mediaType}') {literal}{{/literal}
			mediaType = '{$mediaType}';
			current.mediaType = mediaType;
		{literal}}
		document.open();
		str = "";
		switch (mediaType) {{/literal}
			case "image" :
			str = "{'POPUP_MEDIA_IMAGE'|t}";
			break;
			case "file" :
			str = "{'POPUP_MEDIA_FILE'|t}";
			break;
			case "flash" :
			str = "{'POPUP_MEDIA_FLASH'|t}";
			break;
			case "video" :
			str = "{'POPUP_MEDIA_VIDEO'|t}";
			break;
		{literal}}
		document.write(str);
		document.close();
		{/literal}
		</script> </b></legend></fieldset>

<div id="div_content">
<div id="frame_left_top">{$title}</div>
<div id="frame_left_middle">{$left_middle}</div>
<div id="frame_left_bottom">
<button id="buttonAddFolder" style="display: none"
	onclick="setAction('add','folder');"><img
	src="/library/Pelican/Media/public/images/media_addfolder.gif" alt=""
	name="imgAddFolder" id="imgAddFolder" height="12" border="0" />&nbsp;Aj.</script></button>
&nbsp;
<button id="buttonEditFolder" style="display: none"
	onclick="setAction('edit','folder');"><img
	src="/library/Pelican/Media/public/images/media_editfolder.gif" alt=""
	name="imgDelFolder" id="imgDelFolder" height="12" border="0" />&nbsp;Ed.</button>
&nbsp;
<button id="buttonDelFolder" style="display: none"
	onclick="setAction('del','folder');"><img
	src="/library/Pelican/Media/public/images/media_delfolder.gif" alt=""
	name="imgDelFolder" id="imgDelFolder" height="12" border="0" />&nbsp;Sup.</button>
</div>

<div id="frame_right_top">&nbsp;</div>
<div id="frame_right_middle">{$right_middle}</div>
<div id="frame_right_bottom">
<button id="buttonAddFile" style="display: none"
	onclick="setAction('add','file');"><img
	src="/library/Pelican/Media/public/images/media_add.gif" alt=""
	name="imgAddFile" id="imgAddFile" height="12" border="0" />&nbsp;{'POPUP_LABEL_ADD'|t}</button>
&nbsp;
<button id="buttonPropertiesFile" style="display: none"
	onclick="showProperties();"><img
	src="/library/Pelican/Media/public/images/media_select.gif" alt=""
	name="imgSelect" id="imgSelect" height="12" border="0">&nbsp;{'POPUP_BUTTON_PROPERTIES'|t}</button>
&nbsp;
<button id="buttonDelFile" style="display: none"
	onclick="setAction('del','file');"><img
	src="/library/Pelican/Media/public/images/media_del.gif" alt=""
	name="imgDelFile" id="imgDelFile" height="12" border="0">&nbsp;{'POPUP_LABEL_DEL'|t}</button>
</div>
</div>

<div id="div_popup_footer">
<button id="buttonOk" style="display: none;" onClick="select();">{'POPUP_BUTTON_OK'|t}</button>
&nbsp;
<button id="buttonBack" style="display: none" onClick="goBack();">{'POPUP_BUTTON_BACK'|t}</button>
&nbsp;
<button onclick="closePopup();">{'POPUP_BUTTON_CLOSE'|t}</button>
</div>
{$default} {$footer}
</body>
</html>
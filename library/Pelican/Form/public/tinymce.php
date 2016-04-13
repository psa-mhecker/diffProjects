<?php
/** Editeur HTML
 *
 * @package Pelican
 * @subpackage Pelican_Form
 */
/** Fichier de configuration */
include_once ('config.php');
require_once ("editor.ini.php");


//var_dump( $_EDITOR["TOOLBAR"][0]["THEME_ADVANCED_BUTTONS1"]);
//die();
if ($_GET["limited"]) {
    //getLimitedConf($_GET["limited"]);
}

$skin_variant["itunes"] = "black";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Online Editor</title>
<meta http-equiv="content-type"
	content="text/html<?=(Pelican::$config["CHARSET"] ? "; charset=" . Pelican::$config["CHARSET"] : "")?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<!-- TinyMCE -->
<script type="text/javascript"
	src="/library/External/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript"
	src="/library/Pelican/Form/public/js/xt_editor_functions.js"></script>
<script type="text/javascript">        
	tinyMCE.init({
        // General options
        theme : "advanced",
	language : 'fr',
	mode : 'exact',
	elements : 'editorTiny',
	skins : 'highcontrast',
	site_id: '<?=$_SESSION[APP]['SITE_ID']?>',
	<?=($skin_variant[Pelican::$config["SKIN"]] ? 'skins_variant:' . $skin_variant[Pelican::$config["SKIN"]] . ',' : '')?>

	accessibility_focus : true,
	accessibility_warnings : true,
	button_tile_map : true,
	convert_newlines_to_brs : true,
	fix_nesting : true,
	force_br_newlines : false,
	force_p_newlines : true,
	inline_styles : true,
	relative_urls : false,
        remove_linebreaks :  true,
	remove_script_host : false,
	cleanup : true,
        cleanup_callback : "myCustomCleanup",
	visual : true,
	bEditorGet : false,
	convert_urls : false,
	verify_html: false,
	custom_elements : 'noscript',

	// D�finie l'url de front pour les lien relatif
	document_base_url : 'http://<?=$currentSite['SITE_URL']?>',

	boxName : '<?=$_GET["boxName"]?>',
	MediaHttpPath : '<?=$_EDITOR["MEDIA_HTTP"]?>',
	MediaVarPath : '<?=$_EDITOR["MEDIA_VAR"]?>',
	MediaLibPath : '<?=$_EDITOR["MEDIA_LIB_PATH"]?>',
	content_css : "<?=$_EDITOR["CSS"]?>",
	CssPath : "<?=$_EDITOR["CSS"]?>",
        theme_advanced_text_colors : "<?=($_GET["toolbar_id"]) ? $_EDITOR["TOOLBAR"][$_GET["toolbar_id"]]["THEME_ADVANCED_TEXT_COLORS"] : $_EDITOR["TOOLBAR"][0]["THEME_ADVANCED_TEXT_COLORS"]?>",
        theme_advanced_text_colors2 : "<?=($_GET["toolbar_id"]) ? $_EDITOR["TOOLBAR"][$_GET["toolbar_id"]]["THEME_ADVANCED_TEXT_COLORS2"] : $_EDITOR["TOOLBAR"][0]["THEME_ADVANCED_TEXT_COLORS2"]?>",
        theme_advanced_text_colors3 : "<?=($_GET["toolbar_id"]) ? $_EDITOR["TOOLBAR"][$_GET["toolbar_id"]]["THEME_ADVANCED_TEXT_COLORS3"] : $_EDITOR["TOOLBAR"][0]["THEME_ADVANCED_TEXT_COLORS3"]?>",
        theme_advanced_default_color : "<?=($_GET["toolbar_id"]) ? $_EDITOR["TOOLBAR"][$_GET["toolbar_id"]]["DEFAULT_COLOR"] : $_EDITOR["TOOLBAR"][0]["DEFAULT_COLOR"]?>",

	//plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",
	plugins : 'Jsvk,pagebreak,layer,betd_textbrowser,betd_save,betd_media,betd_icons,betd_internallink,betd_code,style,table,inlinepopups,media,searchreplace,print,contextmenu,paste,visualchars,nonbreaking,xhtmlxtras,advimage,advlink,preview,style,advhr,layer,betd_color_forecolor,betd_color_backcolor',

		// Theme options
	/*theme_advanced_buttons1 : 'styleselect,styleprops,|,formatselect,fontselect,fontsizeselect,bold,italic,underline,strikethrough,sup,sub,|,forecolor,backcolor,|,removeformat,cleanup',
	theme_advanced_buttons2 : 'justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,hr,|,indent,outdent,|,cut,copy,paste,pastetext,pasteword,|,undo,redo',
	theme_advanced_buttons3 : "table,visualaid,tablecontrols,|,insertlayer,moveforward,movebackward,absolute",
	theme_advanced_buttons4 : 'Jsvk,|,search,replace,|,cite,acronym,abbr,|,nonbreaking,pagebreak,charmap,betd_icons,betd_image,betd_file,betd_flash,|,link,betd_internallink,anchor,unlink,|,code,spellchecker,visualchars,|,help,betd_save',*/

    theme_advanced_buttons1 : '<?=($_GET["toolbar_id"]) ? $_EDITOR["TOOLBAR"][$_GET["toolbar_id"]]["THEME_ADVANCED_BUTTONS1"] : $_EDITOR["TOOLBAR"][0]["THEME_ADVANCED_BUTTONS1"]?>',
    theme_advanced_buttons2 : '<?=($_GET["toolbar_id"]) ? $_EDITOR["TOOLBAR"][$_GET["toolbar_id"]]["THEME_ADVANCED_BUTTONS1"] : $_EDITOR["TOOLBAR"][0]["THEME_ADVANCED_BUTTONS2"]?>',
    theme_advanced_buttons3 : '<?=($_GET["toolbar_id"]) ? $_EDITOR["TOOLBAR"][$_GET["toolbar_id"]]["THEME_ADVANCED_BUTTONS1"] : $_EDITOR["TOOLBAR"][0]["THEME_ADVANCED_BUTTONS3"]?>',
    theme_advanced_buttons4 : '<?=($_GET["toolbar_id"]) ? $_EDITOR["TOOLBAR"][$_GET["toolbar_id"]]["THEME_ADVANCED_BUTTONS1"] : $_EDITOR["TOOLBAR"][0]["THEME_ADVANCED_BUTTONS4"]?>',

	theme_advanced_path : true,
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,

	theme_advanced_blockformats : "<?=$_EDITOR["FONTFORMAT"]["TINY"]?>",
	theme_advanced_fonts : "<?=$_EDITOR["FONTNAME"]["TINY"]?>",
	theme_advanced_font_sizes : "<?=str_replace(";", ",", $_EDITOR["FONTSIZE"]["ID"])?>",
	theme_advanced_styles : "<?=str_replace(';',',',$_EDITOR["FONTSTYLE"]["ID"])?>",
	spellchecker_languages : "+Français=fr",

	theme_advanced_source_editor_wrap : true,
	theme_advanced_source_editor_php : true,
	theme_advanced_source_editor_script : true,
	theme_advanced_source_editor_numbers : true,
	theme_advanced_source_editor_highlight : true,

	extended_valid_elements : ""
		+"embed[quality|type|pluginspage|width|height|src|align|allowscriptaccess<always?never?sameDomain],"
		+"frame[class|frameborder|id|longdesc|marginheight|marginwidth|name"
			+"|noresize<noresize|scrolling<auto?no?yes|src|style|title],"
		+"frameset[class|cols|id|onload|onunload|rows|style|title],"
		+"iframe[align<bottom?left?middle?right?top|class|frameborder|height|id"
			+"|longdesc|marginheight|marginwidth|name|scrolling<auto?no?yes|src|style"
			+"|title|width],"
		+"img[src|alt|title|height|width|flashvars|border|style|usemap|class|longdesc],"
		+"label[for],"
		+"noscript[*],"
		+"object[classid|codebase|width|height|align],"
		+"option[value|selected],"
		+"param[name|value],"
		+"select[id|name],"
		+"td[abbr|align<center?char?justify?left?right|axis|bgcolor|char|charoff|class"
			+"|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap|onclick"
			+"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
			+"|onmouseout|onmouseover|onmouseup|rowspan|scope<col?colgroup?row?rowgroup"
			+"|style|title|valign<baseline?bottom?middle?top|width],"
		+"th[abbr|align<center?char?justify?left?right|axis|char|charoff|class"
			+"|colspan|id|rowspan|scope<col?colgroup?row?rowgroup"
			+"|valign<baseline?bottom?middle?top]"
});

	/*
	// Example content CSS (should be your site CSS)
	content_css : "css/content.css",

	// Drop lists for link/image/media/template dialogs
	template_external_list_url : "lists/template_list.js",
	external_link_list_url : "lists/link_list.js",
	external_image_list_url : "lists/image_list.js",
	media_external_list_url : "lists/media_list.js",

	// Style formats
	style_formats : [
		{title : 'Bold text', inline : 'b'},
		{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
		{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
		{title : 'Example 1', inline : 'span', classes : 'example1'},
		{title : 'Example 2', inline : 'span', classes : 'example2'},
		{title : 'Table styles'},
		{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
	],
	*/

function myCustomCleanup(type, value) {
        switch (type) {
                case "get_from_editor":
                        //alert("Value HTML string: " + value);
                        // Do custom cleanup code here
                        value = value.replace(/<p><\/p>/g,"<p>&nbsp;</p>");                      
                        break;
                case "insert_to_editor":   
                        value = value.replace(/<br \/>/g,"");
                        // Do custom cleanup code here
                        //alert("insert Value HTML string: " + value);
                        break;
                /*case "submit_content":
                        //alert("submit content Value HTML Element: " + value);
                        // Do custom cleanup code here
                        break;
                case "get_from_editor_dom":
                        //alert("editor Value DOM Element " + value);
                        // Do custom cleanup code here
                        break;
                case "insert_to_editor_dom":
                        //alert("inser Value DOM Element: " + value);
                        // Do custom cleanup code here
                        break;
                case "setup_content_dom":
                        //alert("setup Value DOM Element: " + value);
                        // Do custom cleanup code here
                        break;
                case "submit_content_dom":
                        //alert("submit Value DOM Element: " + value);
                        // Do custom cleanup code here
                        break;*/
        }

        return value;
}

var bEditorSaved = false;

function toggleEditor(id) {
	var elm = document.getElementById(id);

	if (tinyMCE.getInstanceById(id) == null) {
		tinyMCE.execCommand('mceAddControl', false, id);
	} else {
		tinyMCE.execCommand('mceRemoveControl', false, id);
	}
}
</script>
</head>
<body
	onbeforeunload="if (!bEditorSaved) return 'Si vous quittez maintenant, vos dernières modifications ne seront pas prise en compte.';">
<form>
<textarea id="editorTiny" rows="28" cols="10" style="width: 100%;"></textarea>
</form>
<script type="text/javascript">
var monediteur = document.getElementById('editorTiny');

// Il n'y as pas besoin de mceCreateHTML, la css est inclus par le param�tre 'content_css'.
// Ceci evite un probl�me quand le netoyage de code est d�sactiv�
/* var body = mceCreateHTML(mceGetHttpPath(window.opener.getElementById('<?=$_GET["boxName"]?>').value, '<?=$_EDITOR["MEDIA_HTTP"]?>', '<?=$_EDITOR["MEDIA_VAR"]?>'), '<?=$_EDITOR["CSS"]?>'); */
var contentText = window.opener.document.getElementById('<?=$_GET["boxName"]?>').value;
var regexText = /<br\s*[\/]?>/gi;
contentText = contentText.replace(regexText, '<br>');
var body = mceGetHttpPath(contentText, '<?=$_EDITOR["MEDIA_HTTP"]?>', '<?=$_EDITOR["MEDIA_VAR"]?>');


monediteur.value = body;

</script>
</body>
</html>
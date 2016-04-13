<?php
/** Editeur HTML
	*
	* @package Pelican
	* @subpackage Pelican_Form
	*/
/** Fichier de configuration */
include_once('config.php');
require_once("editor.ini.php");

if($_GET["limited"]) {
	//getLimitedConf($_GET["limited"]);
}

$skin_variant["itunes"] = "black";
?>
<html>
<head>
<title>DHTML Editor</title>
<meta http-equiv="content-type" content="text/html<?=(Pelican::$config["CHARSET"]?"; charset=".Pelican::$config["CHARSET"]:"")?>" />
<script type="text/javascript" src="/library/External/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="/library/Pelican/Form/public/js/xt_editor_functions.js"></script>
<body onbeforeunload="if (!bEditorSaved) return 'Si vous quittez maintenant, vos derni√®res modifications ne seront pas prise en compte.';">
<form>
<textarea id="editorTiny" rows="28" cols="10" style="width:100%;"></textarea>
</form>
<script type="text/javascript">
var bEditorSaved = false;

function toggleEditor(id) {
	var elm = document.getElementById(id);

	if (tinyMCE.getInstanceById(id) == null) {
		tinyMCE.execCommand('mceAddControl', false, id);
	} else {
		tinyMCE.execCommand('mceRemoveControl', false, id);
	}
}

/*
 tinyMCE.init({
	// General options
	theme : 'pelican',
	language : 'fr',
	mode : 'exact',
	elements : 'editorTiny',
	skins : 'o2k7',
	site_id: '<?=$_SESSION[APP]['SITE_ID']?>',
	<?($skin_variant[Pelican::$config["SKIN"]]?'skins_variant:'.$skin_variant[Pelican::$config["SKIN"]].',':'')?>

	accessibility_focus : true,
	accessibility_warnings : true,
	button_tile_map : true,
	convert_newlines_to_brs : false,
	fix_nesting : true,
	force_br_newlines : false,
	force_p_newlines : true,
	inline_styles : true,
	relative_urls : false,
	remove_linebreaks : true,
	remove_script_host : false,
	cleanup : true,
	visual : true,
	bEditorGet : false,
	convert_urls : false,
	verify_html: false,
	custom_elements : 'noscript',

	// DÈfinie l'url de front pour les lien relatif
	document_base_url : 'http://<?=$currentSite['SITE_URL']?>',

	boxName : '<?=$_GET["boxName"]?>',
	MediaHttpPath : '<?=$_EDITOR["MEDIA_HTTP"]?>',
	MediaVarPath : '<?=$_EDITOR["MEDIA_VAR"]?>',
	MediaLibPath : '<?=$_EDITOR["MEDIA_LIB_PATH"]?>',
	content_css : "<?=$_EDITOR["CSS"]?>",
	CssPath : "<?=$_EDITOR["CSS"]?>",

	//	plugins : 'betd_mediadirect,liststyle,betd_file,betd_orderedlist,betd_mailto,betd_flash,betd_mediadirect,betd_save,betd_media,betd_icons,betd_internallink,safari,style,table,inlinepopups,media,searchreplace,print,contextmenu,paste,visualchars,nonbreaking,xhtmlxtras,advimage,advlink',
	//layoutbreak
	plugins : 'Jsvk,betd_code,betd_textbrowser,betd_save,betd_media,betd_icons,betd_internallink,style,table,inlinepopups,media,searchreplace,print,contextmenu,paste,visualchars,nonbreaking,xhtmlxtras,advimage,advlink,preview,style,advhr,layer',
	theme_pelican_blockformats : "<?=$_EDITOR["FONTFORMAT"]["TINY"]?>",
	theme_pelican_fonts : "<?=$_EDITOR["FONTNAME"]["TINY"]?>",
	theme_pelican_font_sizes : "<?=str_replace(";",",",$_EDITOR["FONTSIZE"]["ID"])?>",
	theme_pelican_styles : "<?=$_EDITOR["FONTSTYLE"]["ID"]?>",
	spellchecker_languages : "+Fran√ßais=fr",


	theme_advanced_source_editor_wrap : true,
	theme_advanced_source_editor_php : true,
	theme_advanced_source_editor_script : true,
	theme_advanced_source_editor_wrap : true,
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
//	extended_valid_elements : "object[classid|codebase|width|height|align],param[name|value],embed[quality|type|pluginspage|width|height|src|align],img[src|alt|title|height|width|flashvars|border|style|usemap|class],label[for],select[id|name],option[value|selected],th[abbr|id],td[headers],span[font|text-transform|color|text-indent|white-space|letter-spacing|border-collapse|text-align|orphans],font[*]"

});
*/

var monediteur = document.getElementById('editorTiny');

// Il n'y as pas besoin de mceCreateHTML, la css est inclus par le paramËtre 'content_css'.
// Ceci evite un problËme quand le netoyage de code est dÈsactivÈ
/* var body = mceCreateHTML(mceGetHttpPath(window.opener.getElementById('<?=$_GET["boxName"]?>').value, '<?=$_EDITOR["MEDIA_HTTP"]?>', '<?=$_EDITOR["MEDIA_VAR"]?>'), '<?=$_EDITOR["CSS"]?>'); */

var body = mceGetHttpPath(window.opener.document.getElementById('<?=$_GET["boxName"]?>').value, '<?=$_EDITOR["MEDIA_HTTP"]?>', '<?=$_EDITOR["MEDIA_VAR"]?>');
monediteur.value = body;

</script>
<!-- <a href="javascript:toggleEditor('editorTiny')">test</a> -->
</body>
</html>
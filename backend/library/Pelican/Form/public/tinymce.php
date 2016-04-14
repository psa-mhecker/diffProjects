<?php
/** Editeur HTML
 *
 */
/** Fichier de configuration */
$backend = true;
include_once 'config.php';
require_once 'editor.ini.php';

if ($_GET["limited"]) {
    //getLimitedConf($_GET["limited"]);
}

$langue = Pelican_Cache::fetch("Language", $_SESSION[APP]['LANGUE_ID']);

if ($langue) {
    $language_direction = $langue['LANGUE_DIRECTION'];
}

$trad = Pelican_Cache::fetch("TranslationByLabelIdAndSiteIdAndLangueId",
    array(
        'LABEL_ID' => 'NDP_INFOBULLE_ICON_I',
        'SITE_ID' => $_SESSION[APP]['SITE_ID'],
        'LANGUE_ID' => $_SESSION[APP]['LANGUE_ID']
    )
);

$nationalParameters =  Pelican_Cache::fetch("NationalParametersBySiteId",
    array(
        'SITE_ID' => $_SESSION[APP]['SITE_ID']
    )
);

$skin_variant["itunes"] = "black";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Online Editor</title>
<meta http-equiv="content-type"
    content="text/html<?=(Pelican::$config["CHARSET"] ? "; charset=".Pelican::$config["CHARSET"] : "")?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<link rel="stylesheet" type="text/css" href="<?=$_EDITOR["CSS"]?>">
<!-- TinyMCE -->
<script type="text/javascript"
    src="/library/External/tiny_mce/tinymce.min.js"></script>
<script type="text/javascript"
    src="/library/Pelican/Form/public/js/xt_popup_fonctions.js"></script>
<script type="text/javascript"
    src="/library/Pelican/Form/public/js/xt_editor_functions.js"></script>
<script type="text/javascript">
    tinyMCE.init({
        // General options
    theme : 'modern',
    language : '<?=strtolower($_SESSION[APP]['LANGUE_CODE'])?>',
	menubar : false,
    mode : 'exact',
    elements : 'editorTiny',
    skin : 'xenmce',
    site_id: '<?=$_SESSION[APP]['SITE_ID']?>',
    <?=($skin_variant[Pelican::$config["SKIN"]] ? 'skins_variant:'.$skin_variant[Pelican::$config["SKIN"]].',' : '')?>
    accessibility_focus : true,
    info_icon_value: '<?=$trad['LABEL_TRANSLATE']?>',
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
    directionality : '<?=$language_direction?>',
    custom_elements : 'noscript',
    setup : function(editor) {
        editor.on('SaveContent', function(e) {
            // Add specific class on ul and ol for front-end styling
            tinyMCE.activeEditor.dom.addClass(tinyMCE.activeEditor.dom.select('ul, ol'), 'wysiwyg-list');

            if (window.opener.$('#characterNumber-<?=$_GET["boxName"]?>')) {
                var number = countCharacterNumber(document.getElementById('editorTiny_ifr').contentWindow.document.body.innerHTML);
                window.opener.$('#characterNumber-<?=$_GET["boxName"]?>').text(number);
            }
        });
    },

    // D�finie l'url de front pour les lien relatif
    document_base_url : 'http://<?=$currentSite['SITE_URL']?>',
    boxName : '<?=$_GET["boxName"]?>',
    MediaHttpPath : '<?=$_EDITOR["MEDIA_HTTP"]?>',
    MediaVarPath : '<?=$_EDITOR["MEDIA_VAR"]?>',
    MediaLibPath : '<?=$_EDITOR["MEDIA_LIB_PATH"]?>',
    content_css : "<?=$_EDITOR["CSS"]?>",
    CssPath : "<?=$_EDITOR["CSS"]?>",

	plugins : 'directionality,infobulle,anchor,betd_internallink,betd_media,betd_save,charmap,code,contextmenu,hr,layer,link,media,nonbreaking,pagebreak,paste,preview,print,searchreplace,table,textcolor,visualchars',
        // Theme options
    toolbar1 : ',styleselect,|,formatselect,fontselect,fontsizeselect,|,table,',
	toolbar2 : ',bold,italic,underline,strikethrough,superscript,subscript,|,forecolor,backcolor,|,ltr,rtl,',
    toolbar3 : ',alignleft,aligncenter,alignright,alignjustify,|,bullist,numlist,hr,|,indent,outdent,|,cut,copy,paste,pastetext,|,undo,redo,',
    toolbar4 : 'searchreplace,|,nonbreaking,pagebreak,charmap,|,link,betd_internallink,infobulle,anchor,|,code,visualchars,|,betd_save,',
    toolbar5 : 'removeformat,|,unlink,',

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
        +"infobulle,"
        +"td[abbr|align<center?char?justify?left?right|axis|bgcolor|char|charoff|class"
            +"|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap|onclick"
            +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
            +"|onmouseout|onmouseover|onmouseup|rowspan|scope<col?colgroup?row?rowgroup"
            +"|style|title|valign<baseline?bottom?middle?top|width],"
        +"th[abbr|align<center?char?justify?left?right|axis|char|charoff|class"
            +"|colspan|id|rowspan|scope<col?colgroup?row?rowgroup"
            +"|valign<baseline?bottom?middle?top]",

    fontsize_formats: "8px 10px 12px 14px 16px 18px 20px 22px 24px 26px 28px 30px 32px 34px 36px",
    block_formats: 'Paragraph=p;' +
    'Header 1=h1;' +
    'Header 2=h2;' +
    'Header 3=h3;' +
    'Header 4=h4;' +
    'Div=div;' +
    'Pre=pre;' +
    'Blockquote=blockquote',
    style_formats: [
        {title: 'Headers', items: [
            {title: 'Header 1', format: 'h1'},
            {title: 'Header 2', format: 'h2'},
            {title: 'Header 3', format: 'h3'},
            {title: 'Header 4', format: 'h4'}
        ]},
        {title: 'Inline', items: [
            {title: 'Bold', icon: 'bold', format: 'bold'},
            {title: 'Italic', icon: 'italic', format: 'italic'},
            {title: 'Underline', icon: 'underline', format: 'underline'},
            {title: 'Strikethrough', icon: 'strikethrough', format: 'strikethrough'},
            {title: 'Superscript', icon: 'superscript', format: 'superscript'},
            {title: 'Subscript', icon: 'subscript', format: 'subscript'},
            {title: 'Code', icon: 'code', format: 'code'}
        ]},
        {title: 'Blocks', items: [
            {title: 'Paragraph', format: 'p'},
            {title: 'Blockquote', format: 'blockquote'},
            {title: 'Div', format: 'div'},
            {title: 'Pre', format: 'pre'}
        ]},
        {title: 'Alignment', items: [
            {title: 'Left', icon: 'alignleft', format: 'alignleft'},
            {title: 'Center', icon: 'aligncenter', format: 'aligncenter'},
            {title: 'Right', icon: 'alignright', format: 'alignright'},
            {title: 'Justify', icon: 'alignjustify', format: 'alignjustify'}
        ]}
    ],

    <?php if ($nationalParameters['USE_PEUGEOT_FONT']) { ?>
        font_formats: "Peugeot Regular=peugeot;" +
        "Peugeot Light=peugeotlight;" +
        "Arial=Arial;"
    <?php } else { ?>
        font_formats: "Arial=Arial;"
    <?php } ?>
});

var bEditorSaved = false;

function toggleEditor(id)
{
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

var body = mceGetHttpPath(window.opener.document.getElementById('<?=$_GET["boxName"]?>').value, '<?=$_EDITOR["MEDIA_HTTP"]?>', '<?=$_EDITOR["MEDIA_VAR"]?>');
monediteur.value = body;

</script>
</body>
</html>

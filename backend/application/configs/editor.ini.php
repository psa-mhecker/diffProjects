<?php
/**
 * Fichier de configuration du Miniword.
 */

/**
 * @ignore
 */
/** Feuille de style */

//if (Pelican::$config["SITE"]["INFOS"]["SITE_ROOT"] != "") {
// $_EDITOR["CSS"] = Pelican::$config["MEDIA_HTTP"]."/design/".Pelican::$config["SITE"]["INFOS"]["SITE_ROOT"]."/css/editeur.css";
//$_EDITOR["CSS"] = Pelican::$config["MEDIA_HTTP"]."/design/pelican/css/editor.css";
// Passage du CSS en local pour eviter le cross-domain

$nationalParameters =  Pelican_Cache::fetch("NationalParametersBySiteId",
    array(
        'SITE_ID' => $_SESSION[APP]['SITE_ID']
    )
);

if ($nationalParameters['USE_PEUGEOT_FONT']){
    $_EDITOR["CSS"] = Pelican::$config['DOCUMENT_HTTP']."/css/editorPeugeot.css";
}
else{
    $_EDITOR["CSS"] = Pelican::$config['DOCUMENT_HTTP']."/css/editorWithoutPeugeot.css";
}


if ($_SESSION[APP]['LANGUE_ID']) {
    $css_langue = Pelican::$config["MEDIA_HTTP"]."/design/editor/css/editor_".$_SESSION[APP]['LANGUE_ID'].".css";
    if (file_exists($css_langue)) {
        $_EDITOR["CSS"] = $css_langue;
    }
}

/* Chemins d'upload */
$_EDITOR["MEDIA_HTTP"] = Pelican::$config["MEDIA_HTTP"]."/";
$_EDITOR["MEDIA_VAR"]  = $_EDITOR["MEDIA_HTTP"];

// Mediatheque
$_EDITOR["MEDIA_LIB_PATH"] = Pelican::$config["MEDIA_LIB_PATH"];

// Palette d'icônes (si un des paramètres manque, le bouton ne s'affichera pas)
$_EDITOR["PALETTE_PATH"] = Pelican::$config["MEDIA_HTTP"]."/_work/emoticons/";
$_EDITOR["PALETTE_COLUMNS"] = "8";
$_EDITOR["PALETTE_ICONS"] = "emoticon-smile.gif;emoticon-wink.gif;emoticon-laugh.gif;emoticon-sad.gif;emoticon-ambivalent.gif;emoticon-tongue-in-cheek.gif;emoticon-surprised.gif;emoticon-unsure.gif;em.icon.approve.gif;em.icon.bigsmile.gif;em.icon.blackeye.gif;em.icon.blush.gif;em.icon.clown.gif;em.icon.cool.gif;em.icon.dissapprove.gif;em.icon.evil.gif;em.icon.shocked.gif;em.icon.shy.gif;em.icon.sleepy.gif;em.icon.tongue.gif;em.icon.wink.gif;icon_arrow.gif;icon_eek.gif;icon_exclaim.gif;icon_idea.gif;icon_lol.gif;icon_question.gif;em.icon.question.gif;icon-error.gif;icon-info.gif;icon-warning.gif;emoticon-8.gif;emoticon-b.gif;emoticon-c.gif;emoticon-cat.gif;emoticon-d.gif;emoticon-e.gif;emoticon-f.gif;emoticon-g.gif;emoticon-h.gif;emoticon-i.gif;emoticon-k.gif;emoticon-l.gif;emoticon-u.gif;emoticon-n.gif;emoticon-y.gif;emoticon-s.gif;emoticon-star.gif;emoticon-t.gif;emoticon-m.gif;emoticon-p.gif;wiki-ftp.gif;wiki-mailto.gif;wiki-news.gif;wiki-wiki.gif;asp.gif;avi.gif;bmp.gif;chm.gif;doc.gif;gif.gif;gz.gif;html.gif;jpeg.gif;mov.gif;mp3.gif;mpeg.gif;pdf.gif;png.gif;ppt.gif;txt.gif;xls.gif;xml.gif;xsl.gif";

// Palette d'icônes (si un des paramètres manque, le bouton ne s'affichera pas)
$_EDITOR["CONTROLLERS_ROOT"] = Pelican::$config["LIB_PATH"].Pelican::$config['LIB_FORM']."/controllers/";
$_EDITOR["TEMPLATE_COLUMNS"] = "3";

// Combo des styles (si un des paramètres manque, le bouton ne s'affichera pas)
$_EDITOR["FONTSTYLE"]["ID"] = "More=getMore";
//$_EDITOR["FONTSTYLE"]["ID"] = ";attention;date;download;chevron;help-plugin;information;label-coord;legende-graph;legende-table;vignette-left;vignette-right";
//$_EDITOR["FONTSTYLE"]["LIB"] = ";attention;date;lien_document;lien_chevron;telechargement_plugin;information;contact;legende-graphique;legende-tableau;vignette-gauche;vignette-droite";


// Combo des Formats (si un des paramètres manque, le bouton ne s'affichera pas)
$_EDITOR["FONTFORMAT"]["ID"] = "Normal;En-tête 4;En-tête 5;";
$_EDITOR["FONTFORMAT"]["LIB"] = "Normal;Titre de niveau 4 avec ancre;Titre de niveau 5;";
$_EDITOR["FONTFORMAT"]["TINY"] = "p,address,pre,h1,h2,h3,h4,h5,h6";

// Combo des Polices (si un des paramètres manque, le bouton ne s'affichera pas)
$_EDITOR["FONTNAME"]["LIB"] = "Verdana;Arial;Tahoma;Courier New;Times New Roman;Comic Sans MS;Symbol;Webdings;Wingdings;Wingdings 2;Wingdings 3";
$_EDITOR["FONTNAME"]["TINY"] = "Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats";

// Combo des Tailles de polices (si un des paramètres manque, le bouton ne s'affichera pas)
$_EDITOR["FONTSIZE"]["ID"] = "1;2;3;4;5;6;7";
$_EDITOR["FONTSIZE"]["LIB"] = "1 (8 pt);2 (10 pt);3 (12 pt);4 (14 pt);5 (18 pt);6 (24 pt);7 (36 pt)";

// Choix des boutons de barre d'outil
//FormatToolbar
$_EDITOR["TOOLBAR"]["FONTSTYLE"] = false;
$_EDITOR["TOOLBAR"]["FONTFORMAT"] = true;
$_EDITOR["TOOLBAR"]["FONTNAME"] = true;
$_EDITOR["TOOLBAR"]["FONTSIZE"] = true;
$_EDITOR["TOOLBAR"]["DECMD_BOLD"] = true;
$_EDITOR["TOOLBAR"]["DECMD_ITALIC"] = true;
$_EDITOR["TOOLBAR"]["DECMD_UNDERLINE"] = true;
$_EDITOR["TOOLBAR"]["DECMD_STRIKETHROUGH"] = true;
$_EDITOR["TOOLBAR"]["DECMD_SUPERSCRIPT"] = true;
$_EDITOR["TOOLBAR"]["DECMD_SUBSCRIPT"] = true;
$_EDITOR["TOOLBAR"]["DECMD_SETFORECOLOR"] = true;
$_EDITOR["TOOLBAR"]["DECMD_SETBACKCOLOR"] = true;
$_EDITOR["TOOLBAR"]["DECMD_REMOVEFORMAT"] = true;
$_EDITOR["TOOLBAR"]["DECMD_CLEAN"] = true;
$_EDITOR["TOOLBAR"]["DECMD_TIDY"] = true;
$_EDITOR["TOOLBAR"]["DECMD_ACCESS"] = true;
//ParagraphToolbar
$_EDITOR["TOOLBAR"]["DECMD_JUSTIFYLEFT"] = true;
$_EDITOR["TOOLBAR"]["DECMD_JUSTIFYCENTER"] = true;
$_EDITOR["TOOLBAR"]["DECMD_JUSTIFYRIGHT"] = true;
$_EDITOR["TOOLBAR"]["DECMD_JUSTIFYFULL"] = true;
$_EDITOR["TOOLBAR"]["DECMD_ORDERLIST"] = true;
$_EDITOR["TOOLBAR"]["DECMD_UNORDERLIST"] = true;
$_EDITOR["TOOLBAR"]["DECMD_TOC"] = true;
$_EDITOR["TOOLBAR"]["DECMD_HR"] = true;
$_EDITOR["TOOLBAR"]["DECMD_INDENT"] = true;
$_EDITOR["TOOLBAR"]["DECMD_OUTDENT"] = true;
//ActionToolbar
$_EDITOR["TOOLBAR"]["DECMD_CUT"] = true;
$_EDITOR["TOOLBAR"]["DECMD_COPY"] = true;
$_EDITOR["TOOLBAR"]["DECMD_PASTE"] = true;
$_EDITOR["TOOLBAR"]["DECMD_UNDO"] = true;
$_EDITOR["TOOLBAR"]["DECMD_REDO"] = true;
//TableToolbar
$_EDITOR["TOOLBAR"]["DECMD_INSERTTABLE"] = true;
$_EDITOR["TOOLBAR"]["DECMD_VISIBLEBORDERS"] = true;
$_EDITOR["TOOLBAR"]["DECMD_INSERTROW"] = true;
$_EDITOR["TOOLBAR"]["DECMD_DELETEROWS"] = true;
$_EDITOR["TOOLBAR"]["DECMD_INSERTCOL"] = true;
$_EDITOR["TOOLBAR"]["DECMD_DELETECOLS"] = true;
$_EDITOR["TOOLBAR"]["DECMD_INSERTCELL"] = true;
$_EDITOR["TOOLBAR"]["DECMD_DELETECELLS"] = true;
$_EDITOR["TOOLBAR"]["DECMD_MERGECELLS"] = true;
$_EDITOR["TOOLBAR"]["DECMD_SPLITCELL"] = true;
//ObjectToolbar
$_EDITOR["TOOLBAR"]["DECMD_CHARMAP"] = true;
$_EDITOR["TOOLBAR"]["DECMD_PALETTE"] = true;
$_EDITOR["TOOLBAR"]["DECMD_TEMPLATE"] = true;
$_EDITOR["TOOLBAR"]["DECMD_CONTENT"] = true;
$_EDITOR["TOOLBAR"]["DECMD_IMAGE"] = true;
$_EDITOR["TOOLBAR"]["DECMD_DOCUMENT"] = true;
$_EDITOR["TOOLBAR"]["DECMD_FLASH"] = true;
$_EDITOR["TOOLBAR"]["DECMD_MAKE_ABSOLUTE"] = true;
$_EDITOR["TOOLBAR"]["DECMD_SNAPTOGRID"] = true;
$_EDITOR["TOOLBAR"]["DECMD_LOCK_ELEMENT"] = true;
//LinkToolbar
$_EDITOR["TOOLBAR"]["DECMD_LINK"] = true;
$_EDITOR["TOOLBAR"]["DECMD_ANCHOR"] = true;
$_EDITOR["TOOLBAR"]["DECMD_IMGMAP"] = true;
$_EDITOR["TOOLBAR"]["DECMD_UNLINK"] = true;
$_EDITOR["TOOLBAR"]["DECMD_LINKCHECK"] = true;
//HTMLToolbar
$_EDITOR["TOOLBAR"]["DECMD_HTML"] = true;
$_EDITOR["TOOLBAR"]["DECMD_DETAILS"] = true;
//saveToolbar
$_EDITOR["TOOLBAR"]["DECMD_HELP"] = true;

// Choix des menus contextuels
//GeneralContextMenu
$_EDITOR["TOOLBAR"]["EDITOR_UNDO"] = true;
$_EDITOR["TOOLBAR"]["EDITOR_REDO"] = true;
$_EDITOR["TOOLBAR"]["EDITOR_CUT"] = true;
$_EDITOR["TOOLBAR"]["EDITOR_COPY"] = true;
$_EDITOR["TOOLBAR"]["EDITOR_PASTE"] = true;
$_EDITOR["TOOLBAR"]["EDITOR_SELECTALL"] = true;

//TableContextMenu
$_EDITOR["TOOLBAR"]["EDITOR_INSERTROW"] = true;
$_EDITOR["TOOLBAR"]["EDITOR_DELETEROWS"] = true;
$_EDITOR["TOOLBAR"]["EDITOR_INSERTCOL"] = true;
$_EDITOR["TOOLBAR"]["EDITOR_DELETECOLS"] = true;
$_EDITOR["TOOLBAR"]["EDITOR_INSERTCELL"] = true;
$_EDITOR["TOOLBAR"]["EDITOR_DELETECELLS"] = true;
$_EDITOR["TOOLBAR"]["EDITOR_MERGECELLS"] = true;
$_EDITOR["TOOLBAR"]["EDITOR_SPLITCELL"] = true;

//AbsPosContextMenu
$_EDITOR["TOOLBAR"]["EDITOR_BRING_TO_FRONT"] = true;
$_EDITOR["TOOLBAR"]["EDITOR_SEND_TO_BACK"] = true;
$_EDITOR["TOOLBAR"]["EDITOR_BRING_FORWARD"] = true;
$_EDITOR["TOOLBAR"]["EDITOR_SEND_BACKWARD"] = true;
$_EDITOR["TOOLBAR"]["EDITOR_BRING_ABOVE_TEXT"] = true;
$_EDITOR["TOOLBAR"]["EDITOR_SEND_BELOW_TEXT"] = true;

$_EDITOR["TOOLBAR"][0]["THEME_ADVANCED_BUTTONS1"] = 'fontsizeselect,bold,italic,underline,sup,sub,charmap';
$_EDITOR["TOOLBAR"][0]["THEME_ADVANCED_BUTTONS2"] = 'bullist,numlist';
$_EDITOR["TOOLBAR"][0]["THEME_ADVANCED_BUTTONS3"] = 'table,visualaid,tablecontrols';
$_EDITOR["TOOLBAR"][0]["THEME_ADVANCED_BUTTONS4"] = 'betd_image,betd_file,betd_flash,betd_code,|,link,betd_internallink,anchor,unlink,|,betd_save';

$_LIMITED[1] = array("DECMD_BOLD", "DECMD_ITALIC", "DECMD_UNDERLINE", "DECMD_HTML", "DECMD_IMAGE", "DECMD_LINK",
"DECMD_ANCHOR", "DECMD_UNLINK", "DECMD_CONTENT", "DECMD_DOCUMENT", "DECMD_FLASH", "DECMD_CHARMAP",
"DECMD_UNORDERLIST", "DECMD_INSERTTABLE", "DECMD_JUSTIFYLEFT", "DECMD_JUSTIFYRIGHT", "FONTSTYLE", );

$_TYPE_CONFIG[1] = "Jsvk,bold,link,betd_internallink,italic,cleanup,|,help,betd_save";
$_TYPE_CONFIG[2] = "Jsvk,bold,link,betd_internallink,bullist,cleanup,|,help,betd_save";
$_TYPE_CONFIG[3] = "Jsvk,bold,link,betd_internallink,bullist,anchor,betd_image,cleanup,|,help,betd_save";
$_TYPE_CONFIG[4] = "Jsvk,bold,link,betd_internallink,bullist,anchor,betd_image,cleanup,|,help,betd_save";
$_TYPE_CONFIG[5] = "Jsvk,bold,link,betd_internallink,betd_image,cleanup,|,help,betd_save";
$_TYPE_CONFIG[6] = "Jsvk,bold,link,betd_internallink,italic,betd_image,cleanup,|,help,betd_save";
$_TYPE_CONFIG[7] = "Jsvk,bold,link,betd_internallink,bullist,betd_image,cleanup,|,help,betd_save";
$_TYPE_CONFIG[8] = "Jsvk,bold,link,betd_internallink,bullist,cleanup,|,help,betd_save";

$_HELP["FormatToolbar"] = array(
array("DECMD_BOLD", "EDITOR_BOLD", "bold.gif", "Mettre en gras le texte choisi."),
array("DECMD_ITALIC", "EDITOR_ITALIC", "italic.gif", "Mettre en italique le texte choisi."),
array("DECMD_UNDERLINE", "EDITOR_UNDERLINE", "underline.gif", "Souligner le texte choisi."),
array("DECMD_STRIKETHROUGH", "EDITOR_STRIKETHROUGH", "strikethrough.gif", "Barrer le texte choisi."),
array("DECMD_SUPERSCRIPT", "EDITOR_SUPERSCRIPT", "superscript.gif", "Mettre le texte en exposant."),
array("DECMD_SUBSCRIPT", "EDITOR_SUBSCRIPT", "subscript.gif", "Mettre le texte en indice."),
array("DECMD_SETFORECOLOR", "EDITOR_SETFORECOLOR", "forecolor.gif", "Mettre une couleur de texte pour le texte choisi."),
array("DECMD_SETBACKCOLOR", "EDITOR_SETBACKCOLOR", "backcolor.gif", "Mettre une couleur de fond pour le texte choisi."),
array("DECMD_REMOVEFORMAT", "EDITOR_REMOVEFORMAT", "removeformat.gif", "Enlever la mise en forme."),
array("DECMD_CLEAN", "EDITOR_CLEAN", "cleanup.gif", "Nettoyer le Pelican_Html résultant d'une copie de Microsoft Word."),
array("DECMD_TIDY", "EDITOR_TIDY", "tidy.gif", "Nettoyer le Pelican_Html avec Tidy."),
array("DECMD_ACCESS", "EDITOR_ACCESS", "accessibility.gif", "Simulation d'in navigateur textuel."),
);
$_HELP["ParagraphToolbar"] = array(
array("DECMD_JUSTIFYLEFT", "EDITOR_JUSTIFYLEFT", "left.gif", "Aligner à gauche."),
array("DECMD_JUSTIFYCENTER", "EDITOR_JUSTIFYCENTER", "center.gif", "Centrer."),
array("DECMD_JUSTIFYRIGHT", "EDITOR_JUSTIFYRIGHT", "right.gif", "Aligner à droite."),
array("DECMD_JUSTIFYFULL", "EDITOR_JUSTIFYFULL", "justify.gif", "Justifier le texte."),
array("DECMD_ORDERLIST", "EDITOR_ORDERLIST", "orderlist.gif", "Insérer une liste numérotée."),
array("DECMD_UNORDERLIST", "EDITOR_UNORDERLIST", "unorderlist.gif", "Insérer une liste à puces."),
array("DECMD_TOC", "EDITOR_TOC", "toc.gif", "Insérer un sommaire."),
array("DECMD_HR", "EDITOR_HR", "hr.gif", "Insérer une ligne horizontale."),
array("DECMD_INDENT", "EDITOR_INDENT", "indent.gif", "Augmenter l'indentation d'un paragraphe."),
array("DECMD_OUTDENT", "EDITOR_OUTDENT", "outdent.gif", "Diminuer l'indentation d'un paragraphe."),
);
$_HELP["ActionToolbar"] = array(
array("DECMD_CUT", "EDITOR_CUT", "cut.gif", "Couper dans le presse-papiers."),
array("DECMD_COPY", "EDITOR_COPY", "copy.gif", "Copier dans le presse-papiers."),
array("DECMD_PASTE", "EDITOR_PASTE", "paste.gif", "Coller le contenu du presse-papiers."),
array("DECMD_UNDO", "EDITOR_UNDO", "undo.gif", "Annuler la dernière opération."),
array("DECMD_REDO", "EDITOR_REDO", "redo.gif", "Refaire la dernière opération."),
);
$_HELP["TableToolbar"] = array(
array("DECMD_INSERTTABLE", "EDITOR_INSERTTABLE", "inserttable.gif", "Insérer un tableau."),
array("DECMD_VISIBLEBORDERS", "EDITOR_VISIBLEBORDERS", "borders.gif", "Afficher les bordures de tableau."),
array("DECMD_INSERTROW", "EDITOR_INSERTROW", "insertrow.gif", "Insérer une ligne au-dessus de la ligne choisie."),
array("DECMD_DELETEROWS", "EDITOR_DELETEROWS", "deleterows.gif", "Supprimer la ligne choisie."),
array("DECMD_INSERTCOL", "EDITOR_INSERTCOL", "insertcol.gif", "Insérer une colonne à gauche de la cellule choisie."),
array("DECMD_DELETECOLS", "EDITOR_DELETECOLS", "deletecols.gif", "Supprimer la colonne choisie."),
array("DECMD_INSERTCELL", "EDITOR_INSERTCELL", "insertcell.gif", "Insérer une cellule dans la ligne choisie."),
array("DECMD_DELETECELLS", "EDITOR_DELETECELLS", "deletecells.gif", "Supprimer la cellule choisie."),
array("DECMD_MERGECELLS", "EDITOR_MERGECELLS", "mergecells.gif", "Fusionner les cellules choisies."),
array("DECMD_SPLITCELL", "EDITOR_SPLITCELL", "splitcell.gif", "Scinder la cellule choisie en deux cellules."),
);
$_HELP["ObjectToolbar"] = array(
array("DECMD_CHARMAP", "EDITOR_CHARMAP", "charmap.gif", "Insérer un caractère spécial (accentué)."),
array("DECMD_PALETTE", "EDITOR_PALETTE", "palette.gif", "Insérer un icône."),
array("DECMD_TEMPLATE", "EDITOR_TEMPLATE", "template.gif", "Insérer un modèle de mise en page."),
array("DECMD_CONTENT", "EDITOR_CONTENT", "content.gif", "Insérer un lien interne."),
array("DECMD_IMAGE", "EDITOR_IMAGE", "image.gif", "Insérer une image (format gif, jpeg, bmp et png supportés)."),
array("DECMD_DOCUMENT", "EDITOR_DOCUMENT", "document.gif", "Insérer un document (format word, excel, powerpoint, pdf, zip, rar, texte, Pelican_Html supportés)."),
array("DECMD_FLASH", "EDITOR_FLASH", "swf.gif", "Insérer une animation (format flash supporté)."),
array("DECMD_MAKE_ABSOLUTE", "EDITOR_MAKE_ABSOLUTE", "absolute.gif", "Placer un élément en position absolue."),
array("DECMD_SNAPTOGRID", "EDITOR_SNAPTOGRID", "snapgrid.gif", "Aligner un élément sur la grille."),
array("DECMD_LOCK_ELEMENT", "EDITOR_LOCK_ELEMENT", "lock.gif", "Vérouiller un élément."),
);
$_HELP["LinkToolbar"] = array(
array("DECMD_LINK", "EDITOR_LINK", "link.gif", "Créer un hyperlien."),
array("DECMD_ANCHOR", "EDITOR_ANCHOR", "anchor.gif", "Créer une ancre."),
array("DECMD_UNLINK", "EDITOR_UNLINK", "deletelink.gif", "Supprimer un hyperlien."),
array("DECMD_IMGMAP", "EDITOR_IMAGEMAP", "imagemap.gif", "Image Map."),
array("DECMD_LINKCHECK", "EDITOR_LINKCHECK", "verifylink.gif", "Vérifier les liens du documents (http, ftp, mailto)."),
);
$_HELP["HTMLToolbar"] = array(
array("DECMD_HTML", "EDITOR_HTML", "html.gif", "Afficher le code source HTML,"),
array("DECMD_DETAILS", "EDITOR_DETAILS", "details.gif", "Montrer les détails."),
);
$_HELP["saveToolbar"] = array(
array("DECMD_HELP", "EDITOR_HELP", "help.gif", "Afficher l'écran d'aide."),
array("DECMD_SAVE", "EDITOR_SAVE", "save.gif", "Enregistrer le contenu (configuration popup)."),
);

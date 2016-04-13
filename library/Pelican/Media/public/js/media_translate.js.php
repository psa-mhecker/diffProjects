<?php
/** Fichier de configuration */
header("Content-type: text/javascript");

include_once ('config.php');
/*?>
var aLabel = new Object();
<?php*/
/*Pelican::$lang = Pelican_Translate::getTranslations();
while (list ($name, $value) = each(Pelican::$lang)) {
    echo ("aLabel[\"" . $name . "\"]=\"" . str_replace("'", "\\'", $value) . "\";\n");
}*/
echo ("var aLabel = new Array(); \n");
echo ("aLabel[\"POPUP_MEDIA_MSG_DEL_FILE\"]=\"" . str_replace("'", "\\'", t('POPUP_MEDIA_MSG_DEL_FILE')) . "\";\n");
echo ("aLabel[\"POPUP_MEDIA_MSG_DEL_FOLDER\"]=\"" . str_replace("'", "\\'", t('POPUP_MEDIA_MSG_DEL_FOLDER')) . "\";\n");
echo ("aLabel[\"POPUP_MEDIA_MSG_MOVE_FOLDER\"]=\"" . str_replace("'", "\\'", t('POPUP_MEDIA_MSG_MOVE_FOLDER')) . "\";\n");
echo ("aLabel[\"POPUP_MEDIA_MSG_MOVE_MEDIA\"]=\"" . str_replace("'", "\\'", t('POPUP_MEDIA_MSG_MOVE_MEDIA')) . "\";\n");
echo ("aLabel[\"POPUP_MEDIA_MSG_SELECT_FOLDER\"]=\"" . str_replace("'", "\\'", t('POPUP_MEDIA_MSG_SELECT_FOLDER')) . "\";\n");
echo ("aLabel[\"POPUP_MEDIA_MSG_SELECT_MEDIA\"]=\"" . str_replace("'", "\\'", t('POPUP_MEDIA_MSG_SELECT_MEDIA')) . "\";\n");
?>

function getLabel(label) {
	document.write(aLabel[label]);
}
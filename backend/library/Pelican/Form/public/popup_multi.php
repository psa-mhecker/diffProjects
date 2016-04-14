<?php
/** Popup de création des bouts de formulaires multiples utilisés par createMulti (ajout à la volée)
 * @version 1.0
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 15/01/2003
 */
/** Fichier de configuration */
include_once 'config.php';

include_once pelican_path('Form');
require_once pelican_path('Text.Utf8');

// connection à la base de données
$oConnection = Pelican_Db::getInstance();
?>
<html>
<head>
<!--<meta http-equiv="content-type" content="text/html<?=(Pelican::$config["CHARSET"] ? "; charset=".Pelican::$config["CHARSET"] : "")?>" />-->
<link href="<?=Pelican::$config["DOCUMENT_HTTP"].Pelican::$config["CSS_BACK_PATH"]?>"
	type="text/css" rel="stylesheet" />
<script type="text/javascript">
var libDir='<?=Pelican::$config["LIB_PATH"]?>';
</script>
</head>
<body>
<?php

$values = $_GET;
$oForm = Pelican_Factory::getInstance('Form', false);
$form = $oForm->open();
$form = $oForm->beginFormTable();

ob_start();
$_GET["compteur"] ++;
//$$_GET["prefixe"] = "multi".$_GET["compteur"]."_";
$multi = $_GET["prefixe"];
$$_GET["prefixe"] = $_GET["prefixe"].$_GET["compteur"]."_";
if ($_GET["numberField"] == "true") {
    $compteur = $_GET["compteur"];
    $bAllowDeletion = true;
}
$multi = $$_GET["prefixe"];

pelican_import('Controller.Back');

foreach ($_GET as $key => $value) {
    $tabValues[$key] = $value;
}

$params = explode(',', $_GET["hmvc"]);

if (file_exists($params[0])) {
    include_once $params[0];
}
$form = $oForm->headMulti($multi, $compteur, $readO, $bAllowDeletion);
$form .= call_user_func_array(array(
    $params[1],
    $params[2], ), array(
    $oForm,
    $tabValues,
    false,
    $multi, ));
$form .= $oForm->putHidden();

$form_js = $oForm->_sJS;
endFormTable();
$oForm->close();
$form_eval = $oForm->evalJs;

if (Pelican::$config["CHARSET"] == "UTF-8") {
    pelican_import('Text.Utf8');
    $form = Pelican_Factory::staticCall('Text.Utf8', 'utf8_to_unicode', $form);
}
$form = rawurlencode($form);
$form_js = rawurlencode($form_js);
$form_eval = rawurlencode($form_eval);
?>
<script type="text/javascript">
var aReturn=new Object();
aReturn["html"]='<?=$form?>';
aReturn["js"]='<?=$form_js?>';
aReturn["eval"]='<?=$form_eval?>';
aReturn["name"]='<?=$_GET['name']?>';
//	window.returnValue=aReturn;
//	self.close();
parent.getMulti(aReturn);
</script>
</body>
</html>

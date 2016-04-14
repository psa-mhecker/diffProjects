<?php
/** Popup de création des bouts de formulaires multiples utilisés par createMulti (ajout à la volée)
 * @version 1.0
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 *
 * @since 15/01/2003
 */
/** Fichier de configuration */
include_once 'config.php';

include_once pelican_path('Form');
require_once pelican_path('Text.Utf8');

// connection à la base de données
$oConnection = Pelican_Db::getInstance();

$values = $_GET;
$oForm = Pelican_Factory::getInstance('Form', true);
$form = $oForm->open();
$oForm->hideFormTabTag(array("hideTab" => true, "hideForm" => true, "createXhtml" => true));

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

$oForm->close();
$form_eval = $oForm->evalJs;

if (Pelican::$config["CHARSET"] == "UTF-8") {
    pelican_import('Text.Utf8');
    $form = Pelican_Factory::staticCall('Text.Utf8', 'utf8_to_unicode', $form);
}

echo $form;

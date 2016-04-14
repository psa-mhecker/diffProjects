<?php
/** Popup de sélection de contenus internes
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 15/06/2004
 */
if ($_GET["s"]) {
    $sess = session_id(base64_decode($_GET["s"]));
}

/** Fichier de configuration */
include_once 'config.php';
if ($_GET["s"]) {
    echo Pelican_Html::script("document.location.href=document.location.href.replace('&s=".$_GET["s"]."','');");
} else {
    require_once Pelican::$config["INDEX_ROOT"]."/index_popup.php";
}

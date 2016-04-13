<?php
/** Popup d'ajout à distance d'entrées en base de données (tables de référence)
	*
	* @version 3.0
	* @author Jean-Baptiste Ruscassie <jbruscassie@businessdecision.com>
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 15/01/2002
	* @update 01/05/2007 passage en Ajax
	* @package Pelican
	* @subpackage Pelican_Form
	*/

/**
 * Fichier de configuration
 */
include_once ('config.php');

if (! $_SESSION[APP]["user"]["id"]) {
    echo ("Veuillez vous identifier en Back Office");
    exit();
}

if ($_POST["Find"]) {
    $oConnection = Pelican_Db::getInstance();
    if (@$_SESSION["AssocFromSql_Search"][base64_decode($_POST["Search"])]) {
        // Requête plus complexe, dans une variable de session
        $sqlSearch = str_replace(":RECHERCHE:", str_replace("'", "''", $_POST["Find"]), $_SESSION["AssocFromSql_Search"][base64_decode($_POST["Search"])]);
    } else {
        $sqlSearch = str_replace(":RECHERCHE:", str_replace("'", "''", $_POST["Find"]), base64_decode($_POST["Search"]));
    }
    
    // controle
    if (! Pelican_Db::isSelect($sqlSearch) || strpos(str_replace('#pref#_', APP_PREFIXE, $sqlSearch), 'psa_user') !== false) {
        die();
    }
    
    $oConnection->Query($sqlSearch);
    
    if ($oConnection->rows != 0) {
        if ($_POST["choose"]) {
            $aOption[] = Pelican_Html::option(str_replace("&gt;", ">", str_replace("\"", "&quot;", $sLibChoose)));
        }
        if ($oConnection->data["id"]) {
            while ($ligne = each($oConnection->data["id"])) {
                $aOption[] = Pelican_Html::option(array(
                    value => $ligne["value"]
                ), $oConnection->data["lib"][$ligne["key"]]);
            }
        }
        $strHTML = implode("", $aOption);
    } else {
        $strMessage = t('POPUP_SEARCH_NOFOUND');
    }
} else {
    $strMessage = t('POPUP_SEARCH_EMPTY');
}

if ($strMessage) {
    echo utf8_decode($strMessage);
} else {
    echo utf8_decode($strHTML);
}
?>
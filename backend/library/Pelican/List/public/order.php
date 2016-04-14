<?php
    /** Page de traitement générique pour mettre à jour les numéros d'ordre dans une table (définie par son nom, son champ identifiant et son champ ordre).
     * @see Pelican_List::getTable, Pelican_Db::updateOrder
     */

    /** Fichier de configuration */
    include_once 'config.php';

    if (! $_SESSION[APP]["user"]["id"]) {
        echo("Veuillez vous identifier en Back Office");
        exit();
    }

    $oConnection = Pelican_Db::getInstance();

    if (valueExists($_REQUEST, "session")) {
        $session = $_REQUEST["session"];
    } else {
        $session = "listorder";
    }

    // on recup d'abord les params dans le GEt, sinon, en session
    if (!valueExists($_REQUEST, "table")) {
        $_REQUEST["table"] = $_SESSION[$session ]["table"];
    }
    if (!valueExists($_REQUEST, "order")) {
        $_REQUEST["order"] = $_SESSION[$session ]["order"];
    }
    if (!valueExists($_REQUEST, "field_id")) {
        $_REQUEST["field_id"] = $_SESSION[$session ]["id"];
    }
    if (!valueExists($_REQUEST, "parent")) {
        $_REQUEST["parent"] = $_SESSION[$session ]["parent"];
    }
    if (!valueExists($_REQUEST, "parentIsNeeded")) {
        $_REQUEST["parentIsNeeded"] = $_SESSION[$session ]["parentIsNeeded"];
    }
    if (!valueExists($_REQUEST, "complementWhere")) {
        $_REQUEST["complementWhere"] = $_SESSION[$session ]["complementWhere"];
    }
    if (!valueExists($_REQUEST, "retour")) {
        $_REQUEST["retour"] = $_SESSION[$session ]["retour"];
    }
    if (!valueExists($_REQUEST, "decache")) {
        $_REQUEST["decache"] = $_SESSION[$session ]["decache"];
    }
    $oConnection->updateOrder($_REQUEST["table"], $_REQUEST["order"], $_REQUEST["field_id"], $_REQUEST["id"], "", $_REQUEST["sens"], $_REQUEST["parent"], $_REQUEST["parentIsNeeded"], $_REQUEST["complementWhere"]);

    if ($_REQUEST["decache"]) {
        for ($i = 0; $i < sizeof($_REQUEST["decache"]); $i++) {
            Pelican_Cache::clean($_REQUEST["decache"][$i]);
            //debug("decache de ".$_REQUEST["decache"][$i]);
        }
    }

    header("location: ".$_REQUEST["retour"]);

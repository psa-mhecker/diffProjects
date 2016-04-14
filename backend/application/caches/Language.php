<?php
/**
 */

/**
 * Fichier de Pelican_Cache : langues.
 *
 * @author Lenormand Gilles <glenormand@businessdecision.com>
 *
 * @since   23/04/2007
 * @update  11/07/2013 Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 *          Ajout du filtrage par identifiant de langue et code de langue
 *
 * @param 0 LANGUE_ID Identifiant de la langue dans la base de données
 * @param 1 LANGUE_CODE Code de la langue dans la base de données
 */
class Language extends Pelican_Cache
{
    public $duration = DAY;

    public $isPersistent = true;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {

        /* Initialisation des variables */
        /* Définit si la requête ne doit envoyée qu'une seule ligne*/
        $bSendOneLine = false;
        $aValue = array();
        $sWhereSql = '';

        $oConnection = Pelican_Db::getInstance();
        /* Mise en Bind des paramètres */
        $aBind = array();
        if (!is_null($this->params[0])) {
            $aBind[':LANGUE_ID'] = (int) $this->params[0];
            $bSendOneLine = true;
        }
        if (!is_null($this->params[1])) {
            $aBind[':LANGUE_CODE'] = (string) $oConnection->strToBind($this->params[1]);
            $bSendOneLine = true;
        }

        /* Création de la requête principale */
        $sqlQuery = "select * from #pref#_language";

        /* Ajout d'une contrainte sur l'identifiant de la langue */
        if (array_key_exists(':LANGUE_ID', $aBind)) {
            $sWhereSql .= ' LANGUE_ID = :LANGUE_ID';
        }

        /* Ajout d'une contrainte sur le code de la langue */
        if (array_key_exists(':LANGUE_CODE', $aBind)) {
            if (!empty($sWhereSql)) {
                $sWhereSql .= ' AND ';
            }
            $sWhereSql .= ' LANGUE_CODE = :LANGUE_CODE';
        }
        /* Ajout des contraintes à la requete principale */
        if (!empty($sWhereSql)) {
            $sqlQuery = "{$sqlQuery} WHERE {$sWhereSql}";
        }

        /* Envoi d'un tableau d'une seule dimension si $bSendOneLine vaut true */
        if ($bSendOneLine === true) {
            $aValue = $oConnection->queryRow($sqlQuery, $aBind);
        } else {
            $results = $oConnection->queryTab($sqlQuery, $aBind);

            foreach ($results as $lang) {
                $aValue[$lang['LANGUE_ID']] = $lang;
            }
        }

        $this->value = $aValue;
    }
}

<?php
/**
 * Fichier de Pelican_Cache : OutilAideChoixFinancement/Question.
 */
class Frontend_Citroen_OutilAideChoixFinancement_Question extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $sSqlId = '';

        $aBind = array(
            ':ARBRE_DECISIONNEL_ID' => $this->params[0],
            ':PARENT_ID' => $this->params[1],
            ':SITE_ID' => $_SESSION[APP]['SITE_ID'],
            ':LANGUE_ID' => $_SESSION[APP]['LANGUE_ID'],
        );

        // Récupération des données
        // Les 2 jointures adp1 et adp2 servent à obtenir l'id du parent du parent, pour savoir si il s'agit d'une question de niveau 2
        $sSql = "
        SELECT ad.*, adp2.ARBRE_DECISIONNEL_ID AS 'adp2_id'
        FROM #pref#_arbre_decisionnel ad
        LEFT JOIN #pref#_arbre_decisionnel adp1 ON adp1.ARBRE_DECISIONNEL_ID = ad.ARBRE_DECISIONNEL_PARENT_ID
        LEFT JOIN #pref#_arbre_decisionnel adp2 ON adp2.ARBRE_DECISIONNEL_ID = adp1.ARBRE_DECISIONNEL_PARENT_ID
        WHERE ad.SITE_ID = :SITE_ID AND ad.LANGUE_ID = :LANGUE_ID";

        if (isset($this->params[0]) && !empty($this->params[0])) {
            $sSql .= ' AND ad.ARBRE_DECISIONNEL_ID=:ARBRE_DECISIONNEL_ID';
        }

        if (isset($this->params[1]) && !empty($this->params[1])) {
            $sSql .= ' AND ad.ARBRE_DECISIONNEL_PARENT_ID=:PARENT_ID';
        } else {
            $sSql .= ' AND ad.ARBRE_DECISIONNEL_PARENT_ID IS NULL';
        }

        //$sSql .= ' LIMIT 0,1';
        $oConnection = Pelican_Db::getInstance();
        $this->value =  $oConnection->queryRow($sSql, $aBind);
    }
}

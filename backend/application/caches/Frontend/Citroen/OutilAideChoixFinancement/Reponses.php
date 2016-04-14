<?php
/**
 * Fichier de Pelican_Cache :Reponses.
 */
class Frontend_Citroen_OutilAideChoixFinancement_Reponses extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $sSqlId = '';
        //print_r($this->params[0]);
   /*     if(isset($this->params[0])&&!empty($this->params[0])){
            //print 'entred';
            $sSqlId =' AND ARBRE_DECISIONNEL_ID=:ARBRE_DECISIONNEL_ID';
        }*/
        $aBind = array(
            ':PARENT_ID' => $this->params[0],
            ':SITE_ID' => $_SESSION[APP]['SITE_ID'],
            ':LANGUE_ID' => $_SESSION[APP]['LANGUE_ID'],

        );
        $sSql = 'SELECT * FROM #pref#_arbre_decisionnel WHERE ARBRE_DECISIONNEL_PARENT_ID=:PARENT_ID AND SITE_ID=:SITE_ID AND LANGUE_ID=:LANGUE_ID';

        $sSql .= " ORDER BY ARBRE_DECISIONNEL_ID ASC";

        $oConnection = Pelican_Db::getInstance();
        $aResponses = array();
        $aResultats = $oConnection->queryTab($sSql, $aBind);
        if (count($aResultats) >= 2) {
            foreach ($aResultats as $aResultat) {
                $aResponse = array(
                    'id' => $aResultat['ARBRE_DECISIONNEL_ID'],
                    'pid' => $aResultat['ARBRE_DECISIONNEL_PARENT_ID'],
                    'q' => $aResultat['ARBRE_DECISIONNEL_QUESTION'],
                    'r' => $aResultat['ARBRE_DECISIONNEL_REPONSE'],
                    'p' => $aResultat['PAGE_ID'],
                    'zo' => $aResultat['ZONE_ORDER'],
                    );
                $aResponses[] = array_merge($aResponse, array('json_data' => htmlspecialchars(json_encode($aResponse, true), ENT_QUOTES)));
            }
        }
        $this->value =  $aResponses;
    }
}

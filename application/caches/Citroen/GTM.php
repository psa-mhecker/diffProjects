<?php
/**
 * Fichier de Pelican_Caches_Citroen : Gamme
 *
 * Cache remontant les informations sur les finitions des véhicules 
 * 
 * @package Cache
 * @subpackage Pelican
 * @author  Laurent Boulay <laurent.boulay@businessdecision.com>
 * @since   17/07/2013
 * @param 0 ZONE_ID                 Identifiant de la zone
 * @param 1 gtm_key               Identifiant de la langue
 * 
 * 
 */
class Citroen_GTM extends Pelican_Cache {
	
    public $duration = DAY;
    
    public $isPersistent = true;

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue() {
        $oConnection = Pelican_Db::getInstance();

        /* Mise en Bind des paramètres */
        $aBind = array();
        $aBind[':ZONE_ID'] = (int)$this->params[0];
        $aBind[':GTM_KEY'] = $oConnection->strToBind($this->params[1]);

        /* Création de la requête principale */
        /*$sSqlQuery = "SELECT * FROM #pref#_gtm WHERE zone_id = :ZONE_ID AND gtm_key = :GTM_KEY";
        
        $aResults = $oConnection->queryRow($sSqlQuery,$aBind);
        
        // Traitement des variables transverses (non rattachée à un bloc particulier)
        if( empty($aResults) ){
            $aBind[':ZONE_ID'] = 0;
            $aResults = $oConnection->queryRow($sSqlQuery,$aBind);
        }
        
        $return = array(
            'category' => $aResults['GTM_CATEGORY'],
            'label'    => $aResults['GTM_LABEL'],
            'action'   => $aResults['GTM_ACTION'],
        );*/
        
        $sSqlQuery = "SELECT * FROM #pref#_gtm";
        $aResults = $oConnection->queryTab($sSqlQuery);
        
        $return = array();
        if (is_array($aResults) && !empty($aResults)) {
            foreach($aResults as $result) {
                $return[$result['ZONE_ID']][$result['GTM_KEY']] =  array(
                    'category' => $result['GTM_CATEGORY'],
                    'label'    => $result['GTM_LABEL'],
                    'action'   => $result['GTM_ACTION'],
                );
            }
        }
        
        $this->value = $return;
    }
}

<?php
/**
 * Helper mettant à disposition des méthodes à utiliser pour la partie design
 * du site
 *
 * @package Frontend_Views
 * @subpackage Helper
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 * @since 05/08/2013
 */
Class Frontoffice_Design_Helper 
{
    /**
     * Méthode statique retournant le mode d'affichage correspondant au tableau
     * passé en paramètre
     * @param array     $aParams    Tableau de paramètre du bloc le paramètre ZONE_TITRE19
     *                              est utilisé
     * @param string    $sSrc       Paramètre indiquant la source du mode d'affichage 
     *                              à prendre si il vient de Zone ou de Page
     * @return string   Classe CSS liée au mode d'affichage sélectionné en BO
     */
    public static function getModeAffichage($aParams, $sSrc = 'zone', $isMobile = false)
    {
        /* Initialisation des variables */
        $sCssStyle = '';
        $sSrc = (string)$sSrc;
        /* Initialisation de la clé du tableau pour la récupération par défaut
         * du paramètre de la zone et non de la page
         */
        $sKey = 'ZONE_TITRE19';
        
        /* Si la source du mode d'affichage est la page alors on change la clé
         * du tableau de paramètre
         */
        if ($sSrc === 'page' ){
            $sKey = 'PAGE_MODE_AFFICHAGE';
        }
        
        /* Récupération de la classe CSS liée au mode d'affichage */
		if( $isMobile == false){
			if( is_array($aParams) 
					&& isset($aParams[$sKey]) 
					&& array_key_exists($aParams[$sKey], Pelican::$config['TRANCHE_COL']['MODE_AFF_CSS']) ){
				$sCssStyle = Pelican::$config['TRANCHE_COL']['MODE_AFF_CSS'][$aParams[$sKey]];
			}
		}else{
			if( is_array($aParams) 
					&& isset($aParams[$sKey]) 
					&& array_key_exists($aParams[$sKey], Pelican::$config['TRANCHE_COL']['MODE_AFF_CSS_MOBILE']) ){
				$sCssStyle = Pelican::$config['TRANCHE_COL']['MODE_AFF_CSS_MOBILE'][$aParams[$sKey]];
			}
		}
        
        return $sCssStyle;
    }
}
?>
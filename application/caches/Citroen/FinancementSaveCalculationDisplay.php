<?php
/**
 * Fichier de Pelican_Caches_Citroen : FinancementSaveCalculationDisplay
 *
 * La source de ce fichier de cache est le WebService SimuFin. La webMéthode
 * utilisée est SaveCalculationDisplay.
 *
 * Ce WebService permet de donnée une simulation financière pour l'achat d'un
 * véhicule à crédit. Il en ressort de nombreuses informations
 *  - Prix mensuel
 *  - Mention légale pour le prix mensuel
 *  - Prix du premier loyer
 *  - Une explication du calcul
 *
 * @package Cache
 * @subpackage Pelican
 * @author  Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 * @since   17/07/2013
 * @param 0 string $sPays               Code pays
 * @param 1 string $sLanguage           Code langue pays
 * @param 2 string $sDevise             Devise du pays
 * @param 3 string $sLCVD6              Identifiant vehicule
 * @param 4 string $sLabelVehicule      Identifiant de la langue
 * @param 5 string $sDescVehicule       Description du véhicule
 * @param 6 string $sGammeVehicule      Gamme vehicule
 * @param 7 string $sPrixHTVehicule     Prix vehicule HT
 * @param 8 string $sPrixTTCVehicule    Prix vehicule TTC

    public static function saveCalculationDisplay($sPays, $sLanguage, $sDevise, $sLCVD6, $sLabelVehicule,$sDescVehicule = '', $sGammeVehicule, $sPrixHTVehicule, $sPrixTTCVehicule){
      $serviceParams = array(
 */

use Citroen\Financement;

class Citroen_FinancementSaveCalculationDisplay extends Pelican_Cache {

    public $duration = HOUR;

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue() {
        /* Initialisation des variables */
        $sPays              = (string)$this->params[0];
        $sLanguage          = (string)$this->params[1];
        $sDevise            = (string)$this->params[2];
        $sLCVD6             = (string)$this->params[3];
        $sLabelVehicule     = (string)$this->params[4];
        $sDescVehicule      = (string)$this->params[5];
        $sGammeVehicule     = (string)$this->params[6];
        $sPrixHTVehicule    = (int)preg_replace( '/[^.,0-9]/', '', $this->params[7]);
        $sPrixTTCVehicule   = (int)preg_replace( '/[^.,0-9]/', '', $this->params[8]);
        $aFinancement       = array();

        $aFinancementTemp = Financement::saveCalculationDisplay($sPays, $sLanguage, $sDevise, $sLCVD6, $sLabelVehicule,$sDescVehicule = '', $sGammeVehicule, $sPrixHTVehicule, $sPrixTTCVehicule);

        if( is_array($aFinancementTemp) && !empty($aFinancementTemp) ){
            $aFinancement = array_shift($aFinancementTemp);
        }

        $this->value = $aFinancement;
    }
}
?>

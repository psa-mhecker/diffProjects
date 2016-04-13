<?php
/**
* Classe de gestion de Google Tag Manager (GTM) - plan de marquage web analytics
*
* Utilisation :
* > use Citroen\GTM
* > GTM::...
*
* @author Vincent Paré <vincent.pare@businessdecision.com>
* @since 20/01/2014
*/
namespace Citroen;

use Citroen\ServiceJson;

class GTM
{
	/**
	* Contient l'ensemble des variables transmises au script gtm.js
	*/
	public static $dataLayer = array();

	/**
	* Retourne la valeur de la variable javascript dataLayer, à injecter dans le code source des pages.
	* Exemple: dataLayer = [{ ... }];
	*/
	public static function serializeDataLayerJS(){
		$json = new ServiceJson(ServiceJson::SERVICES_JSON_UNESCAPED_SLASHES);
		$output = $json->encode(self::$dataLayer);
		return is_string($output) ? $output : false;
	}

	/**
	* Retourne les profils de l'utilisateur, serialisé, prêt à enregistrer dans le dataLayer
	*/
	public static function serializeProfile(){
		// Récupération mapping des profils : id/nom
		$profiles = \Pelican_Cache::fetch("Citroen/PersoProfile", array($_SESSION[APP]['LANGUE_CODE']));

		// Génération de la liste des profile de l'utilisateur
		$userProfiles = isset($_SESSION[APP]['PROFILES_USER']) && is_array($_SESSION[APP]['PROFILES_USER']) ? $_SESSION[APP]['PROFILES_USER'] : array();
		$userProfilesLabel = array();
		foreach($userProfiles as $key => $val){
			$profileName = !empty($profiles[$val]['locallabel']) ? $profiles[$val]['locallabel'] : $profiles[$val]['PROFILE_LABEL'];
			$userProfilesLabel[] = $profileName;
		}

		// Sérialisation des profils
		return implode('%', $userProfilesLabel);
	}

	/**
	* Retourne le score de l'utilisateur (variable dataLayer scoringVisit)
    * @param $indexBy string Indique la donnée à utiliser pour identifier le produit (lcdv/label)
	*/
	public static function serializeScore($indexBy = 'lcdv'){
        // Check paramètre
        if (!in_array($indexBy, array('lcdv', 'label'))) {
            trigger_error("Unknown index field '".$indexBy."'", E_USER_WARNING);
            return false;
        }
        
		// Récupération des codes LCDV associé aux produits
		$aLcdv = \Pelican_Cache::fetch("Frontend/Citroen/Perso/Lcdv6ByProduct", array($_SESSION[APP]['SITE_ID']));
		
		// Construction de la liste des scores (<index>=<score>)
		$scoreList = array();
		if( isset($_SESSION[APP]['FLAGS_USER']['tranche_true_score']) && is_array($_SESSION[APP]['FLAGS_USER']['tranche_true_score']) ){
			foreach($_SESSION[APP]['FLAGS_USER']['tranche_true_score'] as $key => $score){
				if(!isset($aLcdv[$key])) continue; // Si on ne dispose pas du LCDV6 pour ce produit, on zappe
                switch ($indexBy) {
                    // Indexation par code LCDV
                    case 'lcdv' :
                        $scoreList[] = $aLcdv[$key].'='.$score;
                        break;
                    // Indexation par label de véhicule
                    case 'label' :
                        // Récupération des infos du véhicule à partir de son LCDV6
                        $vehicule = \Pelican_Cache::fetch("Frontend/Citroen/Perso/VehiculeByLcdv", array($_SESSION[APP]['SITE_ID'], $aLcdv[$key]));
                        
                        $scoreList[] = $vehicule['VEHICULE_LABEL'].'='.$score;
                        break;
                }
			}
		}

		// Sérialisation des scores
		return implode('%', $scoreList);
	}
    
    /**
    * Retourne la liste des profils de l'utilisateur (id => label)
    */
    public static function getProfilesLabel(){
        // Récupération mapping des profils : id/nom
		$profiles = \Pelican_Cache::fetch("Citroen/PersoProfile", array($_SESSION[APP]['LANGUE_CODE']));

		// Génération de la liste des profile de l'utilisateur
		$userProfiles = isset($_SESSION[APP]['PROFILES_USER']) && is_array($_SESSION[APP]['PROFILES_USER']) ? $_SESSION[APP]['PROFILES_USER'] : array();
		$userProfilesLabel = array();
		foreach($userProfiles as $key => $val){
			$profileName = !empty($profiles[$val]['locallabel']) ? $profiles[$val]['locallabel'] : $profiles[$val]['PROFILE_LABEL'];
			$userProfilesLabel[] = $profileName;
		}

		// Sérialisation des profils
		return $userProfilesLabel;
    }
    
    /**
    * Retourne les label des véhicules contenus dans les indicateurs de la perso
    */
    public static function getPersoIndicVehiculeLabel(){
        $flagUser = $_SESSION[APP]['FLAGS_USER'];
        $vehiculeLabel = array();
        
        // Liste des indicateurs correspondant à un véhicule (et type d'identifiants pour chaque champ : PRODUCT_ID/LCDV6)
        $vehiculeIndic = array(
            'current_product'    => 'PRODUCT_ID',
            'preferred_product'  => 'PRODUCT_ID',
            'product_best_score' => 'PRODUCT_ID',
            'recent_product'     => 'PRODUCT_ID',
            'product_owned'      => 'LCDV6',
        );
        
        // Récupération de la liste des produits de perso du site courant
        $products = \Pelican_Cache::fetch("Frontend/Citroen/Perso/Products", array($_SESSION[APP]['SITE_ID']));
        
        // Récupération des infos sur le véhicule pour chaque indicateur
        foreach ($vehiculeIndic as $indic => $idtype) {
            
            // Récupération de la valeur de l'indicateur (dans la session)
            $indicValue = !empty($flagUser[$indic]) ? $flagUser[$indic] : null;
            if (!isset($indicValue)) {
                continue;
            }
            
            // Récupération du label du véhicule
            switch ($idtype) {
                case 'PRODUCT_ID':
                    $vehiculeLabel[$indic] = isset($products[$indicValue]['PRODUCT_LABEL']) ? $products[$indicValue]['PRODUCT_LABEL'] : '';
                    break;
                case 'LCDV6':
                    $vehicule = \Pelican_Cache::fetch("Frontend/Citroen/Perso/VehiculeByLcdv", array($_SESSION[APP]['SITE_ID'], $indicValue));
                    $vehiculeLabel[$indic] = $vehicule['VEHICULE_LABEL'];
                    break;
            }
        }
        
        return $vehiculeLabel;
    }
}
?>
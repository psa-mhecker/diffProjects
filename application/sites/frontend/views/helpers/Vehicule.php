<?php
/**
 * Helper mettant à disposition des méthodes liées au véhicules
 *
 * @package Frontend_Views
 * @subpackage Helper
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 * @since 24/07/2013
 */

use Citroen\GammeFinition\VehiculeGamme;
Class Frontoffice_Vehicule_Helper 
{
    /**
     * Méthode statique d'ajout des données d'un véhicule en session. Ces données
     * sont utilisées notamment pour le comparateur.
     * 
     * @param string    $sLCDV6         Code LDCV6 d'un vehicule (données du WS)     
     * @param int       $iFinitionCode  Code de la finition d'un véhicule (données du WS)
     * @param int       $sEngineCode    Code de finition d'un véhicule (données du WS)
     * @return boolean  $bCanPut        Renvoit true si l'insertion est possible
     *                                  false sinon
     */
    public static function putVehiculeCompInSession($iSiteId, $iLangueId, $iVehiculeId = null, $sLCDV6 = null, $iFinitionCode = null, $sEngineCode = null,$invoker=null )
    {
        if($invoker==null){
            $invoker = 'COMPARATEUR';
        }
        
        /* Initialisation des variables */
        $bCanPut = false;
        $iNbVehiculeInSession = 3;
        if ( !is_null($iVehiculeId) ){
            $iVehiculeId = (int)$iVehiculeId;
        }
        if ( !is_null($sLCDV6) ){
            $sLCDV6 = (string)$sLCDV6;
        }
        if ( !is_null($iFinitionCode) ){
            $iFinitionCode = (string)$iFinitionCode;
        }
        if ( !is_null($sEngineCode) ){
            $sEngineCode = (string)$sEngineCode;
        }
        
        /* Si le tableau est inexistant ou on contient un nombre de véhicule 
         * inférieur à celui autorisé, on ajoute les données du véhicule
         */

        if ( !is_array($_SESSION[APP][$iSiteId][$iLangueId]['COMPARATEUR'][$invoker])
                || 
                (
                is_array($_SESSION[APP][$iSiteId][$iLangueId]['COMPARATEUR'][$invoker])
                && count($_SESSION[APP][$iSiteId][$iLangueId]['COMPARATEUR'][$invoker]) < $iNbVehiculeInSession
                )
        ) {
            /* Vérification de l'inexistance du véhicule dans la session */
//            if ( is_array($_SESSION[APP][$iSiteId][$iLangueId]['COMPARATEUR']) 
//                    && !empty($_SESSION[APP][$iSiteId][$iLangueId]['COMPARATEUR']) 
//                    && !is_null($iVehiculeId) 
//            ){
//                $mFoundVehicule = self::searchVehicule($_SESSION[APP][$iSiteId][$iLangueId]['COMPARATEUR'],'VEHICULE_ID', $iVehiculeId);
//                if ( $mFoundVehicule === false ){
//                    $bCanPut = true;
//                }
//            }else{
                $bCanPut = true;
//            }
        }
        /* Insertion des données dans la session */
        if ( $bCanPut === true ){
            $aVehicule['VEHICULE_ID'] = $iVehiculeId;
            /* Recherche du code LCDV6 du véhicule si absent */
            if ( is_null($sLCDV6) ) {
               $sLCDV6 = VehiculeGamme::getLCDV6($iVehiculeId, $iSiteId, $iLangueId);
            }
            $aVehicule['LCDV6'] = $sLCDV6;
            $aVehicule['FINITION_CODE'] = $iFinitionCode;
            $aVehicule['ENGINE_CODE'] = $sEngineCode;
            $_SESSION[APP][$iSiteId][$iLangueId]['COMPARATEUR'][$invoker][] = $aVehicule;
        }
        

        
        return $bCanPut;
    }
    
    /**
     * Méthode statique supprimant les véhicules du comparateur en session
     */
    public static function cleanVehiculeCompInSession ($iSiteId, $iLangueId,$invoker=null)
    {
        
        if(null == $invoker){
            //ceci n'est pas une erreur on sauvegarde plusieurs vehicules
            // comparateurs selon la tranche appelante
            //par défaut c'est la tranche COMPARATEUR
            unset($_SESSION[APP][$iSiteId][$iLangueId]['COMPARATEUR']['COMPARATEUR']);
        }else{
            
            unset($_SESSION[APP][$iSiteId][$iLangueId]['COMPARATEUR'][$invoker]);
        }
        
    }
    
    /**
     * Méthode statique permettant de récupérer le(s) véhicule(s) du comparateur
     * Si l'un des paramètre VEHICULE_ID ou LCDV6 est présent on recherche le 
     * véhicule correspondant.
     * 
     * @param int       $iSiteId        Identifiant du site
     * @param int       $iLangueId      Identifiant de la langue
     * @param int       $iVehiculeId    Identifiant du véhicule
     * @param string    $sLCDV6         Code LCDV6 du véhicule
     * @return mixed    False Si le tableau n'est pas initialisé en session ou
     *                      si aucun véhicule ne correspond au paramètres passés 
     *                      en session
     *                  Array contenant les informations de véhicule(s) si le tableau
     *                      de session est initialisé et qu'il n'y a pas de paramètre
     *                      ou qu'un véhicule correspond au paramètre
     */
    public static function getVehiculeCompInSession ($iSiteId, $iLangueId, $iVehiculeId = null, $sLCDV6 = null,$invoker=null)
    {
        if( null == $invoker){
             $invoker = 'COMPARATEUR';
        }
        
        /* Initialisation des variables */
        if ( !is_null($iVehiculeId) ){
            $iVehiculeId = (int)$iVehiculeId;
        }
        if ( !is_null($sLCDV6) ){
            $sLCDV6 = (string)$sLCDV6;
        }
        $iSiteId = (int)$iSiteId;
        $iLangueId = (int)$iLangueId;
        $mResult = false;
        $bSessionCompExist = false;
        
        /* Vérification de la présence du tableau de comparateur dans la session */
        if ( is_array($_SESSION[APP][$iSiteId][$iLangueId]) && array_key_exists('COMPARATEUR', $_SESSION[APP][$iSiteId][$iLangueId])
                && is_array($_SESSION[APP][$iSiteId][$iLangueId]['COMPARATEUR'][$invoker])
                && !empty($_SESSION[APP][$iSiteId][$iLangueId]['COMPARATEUR'][$invoker])
        ){
            
            $bSessionCompExist = true;
            $aComp = $_SESSION[APP][$iSiteId][$iLangueId]['COMPARATEUR'][$invoker];
        }
        
        
        
        /* Si le tableau du comparateur existe en session */
        if ( $bSessionCompExist === true ){
            /* Si les deux paramètres sont null, on renvoit l'intégralité du tableau
            * en session
            */
            if ( is_null($iVehiculeId) && is_null($sLCDV6) ) {
               $mResult = $aComp;
            /* Priorité sur la recherche du véhicule par son identifiant 
            *  présent dans notre base
            */
            }elseif ( is_null($iVehiculeId) ){
                $mResult = self::searchVehicule($aComp,'VEHICULE_ID', $iVehiculeId );
            
            }elseif ( is_null($sLCDV6) ){
                $mResult = self::searchVehicule($aComp,'LCDV6', $sLCDV6 );
            }
        }
        
        
        return $mResult;
    }
    
    /**
     * Méthode statique de recherche des données d'un véhicule en fonction d'une donnée
     * du tableau contenant les informations d'un véhicule (identifiant du véhicule
     * code lcdv6,...)
     * 
     * @param array $aCars  Tableau
     * @param mixed $mKey   Clé sur laquelle faire la recherche
     * @param mixed $mValue Valeur de la clé recherchée
     * @return type         False si aucun véhicule correspondant au critère n'a
     *                      été trouvé
     *                      Si un véhicule a été trouvé, la méthode remonte le 
     *                      tableau de données du tableau en question
     */
    public static function searchVehicule($aCars, $mKey, $mValue)
    {
        /* Initialisation des variables */
        $mResult = false;
        
        if ( is_array($aCars) && !empty($aCars) ) {
            foreach ( $aCars as $aOneCar ){
                /* On recherche si les données du véhicule sont bien dans un
                 * tableau, si la clé passée en paramètres est bien présente
                 * et si la valeur est celle passée en paramètre.
                 * En cas de succès, on sort de la boucle
                 */
                if ( is_array($aOneCar) 
                        && array_key_exists($mKey, $aOneCar) 
                        && $aOneCar[$mKey] == $mValue
                ){
                    $mResult = $aOneCar;
                    break;
                }
            }
        }
        
        return $mResult;
    }
	
    /**
     * Méthode statique de récupération de l'url detail d'un vehicule
     * code lcdv6,...)
     * 
     * @param int $iVehiculeId  Identifiant du vehicule
     * @return string  $sUrlCar Url detail du vehicule
     */
    public static function getUrlDetailCar($iVehiculeId,$iSiteId, $iLangueId)
    {
        $sUrlVehicule = Pelican_Cache::fetch("Frontend/Citroen/UrlVehiculeById", array(
						$iVehiculeId,
						Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_ACCUEIL_SELECT_TEINTE'],
						Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'],
						$iLangueId,
						$iSiteId
		));
        
        return $sUrlVehicule;
    }
	
    /**
     * Méthode statique de récupération du bilan carbone en fonction de l'émission de co2
     * 
     * @param int $iEmission  Valeur émission co2
     * @return string  $sLettre Lettre Bilan carbone
     */
    public static function getBilanCarbone($iEmission)
    {
		$sLettre = '';
		if($iEmission <= 100){
			$sLettre = 'a';
		}elseif($iEmission > 100 && $iEmission <= 120){
			$sLettre = 'b';
		}elseif($iEmission > 120 && $iEmission <= 140){
			$sLettre = 'c';
		}elseif($iEmission > 140 && $iEmission <= 160){
			$sLettre = 'd';
		}elseif($iEmission > 160 && $iEmission <= 200){
			$sLettre = 'e';
		}elseif($iEmission > 200 && $iEmission <= 250){
			$sLettre = 'f';
		}elseif($iEmission > 250){
			$sLettre = 'g';
		}
		
        return $sLettre;
    }
    /**
     * Méthode statique permettant de renvoyé les informations de la zone Showroom
     * quelle soit paramétrable ou automatique
     * @param array $aParams        Tableau renvoyé par la méthode $this->getParams();
     * @param int   $iLangueId      Identifiant de la langue
     * @return array                Tableau contenant les informations de 
     *                                  page_zone, page et page_version de la zone en question
     *                                  si elle provient du ShowRoom-Accueil
     *                              Si la zone provient d'un ShowRoom-Interne et que 
     *                                  sa page parente est une Showroom-Accueil, on va
     *                                  chercher les informations de sa page parente
     */
    public static function getShowroomAccueilValues(array $aParams, $iLangueId)
    {
        /* Initialisation des variables */
        $aHomeShowroomParams = array();
        
        /* Si le template_page de la page en cours est celui du Showroom Accueil 
         * ce sont ces paramètres qui seront utilisés
         */
        if ( $aParams['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'] ){
            $aHomeShowroomParams = $aParams;
            
        /* Si le template_page de la page en cours est celui du Showroom Interne 
         * on récupère les informations de la zone paramétrable similaire si la 
         * page parente est une Showroom Accueil
         */
        }elseif ( $aParams['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_INTERNE'] ){
            /* Appel au fichier de cache ramenant les informations sur la page parente */
           $aHomeShowroomPage = Pelican_Cache::fetch('Frontend/Page', 
                   array($aParams['PAGE_PARENT_ID'], $aParams['SITE_ID'] , $iLangueId) );
           /* Vérification que la page parente est une page Showroom Accueil */		   
           if ( $aHomeShowroomPage['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'] ){
               /* Récupération des informations de zone pour la page et la zone correspondante
                * dans le gabarit Showroom-Accueil parent
                */
				
               $aHomeShowroomZone = Pelican_Cache::fetch('Frontend/Page/ZoneTemplateId', array(
                   $aParams['PAGE_PARENT_ID'], 
                   Pelican::$config['SHOWROOM_ZT_ASSOCIATION'][$aParams['ZONE_TEMPLATE_ID']], 
                   Pelican::getPreviewVersion(), 
                   $iLangueId 
                   ));

               if ( is_array($aHomeShowroomPage) 
                       && is_array($aHomeShowroomZone) 
                       && !empty($aHomeShowroomPage) 
                       && !empty($aHomeShowroomZone) 
                       ){
                    /* Mise à jour des données Params passé en paramètre */
                    $aHomeShowroomParams = array_merge($aHomeShowroomPage, $aHomeShowroomZone);
               }
           }
        }
        return $aHomeShowroomParams;
    }
	
	
	
}
?>

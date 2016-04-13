<?php
/**
 * Classe d'affichage Front de la tranche Gamme de la Home
 *
 * @package Layout
 * @subpackage Citroen
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 * @since 31/07/2013
 */
class Layout_Citroen_Gamme_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        /* Récupération des données du bloc */
        $aParams = $this->getParams();
		$this->assign('aData', $aParams); // nécessaire pour data-gtm-js

        /* Récupération des informations du page_zone */
        $aZone = Pelican_Cache::fetch('Frontend/Page/ZoneTemplate', array(
            $aParams['PAGE_ID'],
            $aParams['ZONE_TEMPLATE_ID'],
            $aParams['PAGE_VERSION'],
            $_SESSION[APP]['LANGUE_ID']
        ));
        $this->assign('aZone', $aZone);

        /*
         * Ramène les véhicules de la Gamme Ligne DS
         */
        $aVehiculesDS = Pelican_Cache::fetch("Frontend/Citroen/VehiculesParGamme", array(
            $aParams['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            'GAMME_LIGNE_DS',
            Pelican::getPreviewVersion()
        ));
        if ($aVehiculesDS) {
            foreach($aVehiculesDS as $key => $value) {
                if ($aVehiculesDS[$key]['MEDIA_PATH']) {
                    $aVehiculesDS[$key]['MEDIA_PATH'] = Pelican_Media::getFileNameMediaFormat($aVehiculesDS[$key]['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['WEB_GAMME']);
                }
				$aVehiculesDS[$key]['CTA'] = $this->getCtaGamme($value);
            }
        }
		
        $this->assign("aVehiculesDS", $aVehiculesDS);

        /*
         * Ramène les véhicules de la Gamme Ligne C
         */
        $aVehiculesC = Pelican_Cache::fetch("Frontend/Citroen/VehiculesParGamme", array(
            $aParams['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            'GAMME_LIGNE_C',
            Pelican::getPreviewVersion()
        ));
		
		
        if ($aVehiculesC) {
            foreach($aVehiculesC as $key => $value) {
				
                if ($aVehiculesC[$key]['MEDIA_PATH']) {
                    $aVehiculesC[$key]['MEDIA_PATH'] = Pelican_Media::getFileNameMediaFormat($aVehiculesC[$key]['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['WEB_GAMME']);
                }
				 $aVehiculesC[$key]['CTA'] = $this->getCtaGamme($value);
			}
			
        }
		

        $this->assign("aVehiculesC", $aVehiculesC);

        /*
         * Ramène les véhicules de la Gamme Véhicule utilitaire
         */
        $aVehiculesUtilitaires = Pelican_Cache::fetch("Frontend/Citroen/VehiculesParGamme", array(
            $aParams['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            'GAMME_VEHICULE_UTILITAIRE',
            Pelican::getPreviewVersion()
        ));
        if ($aVehiculesUtilitaires) {
            foreach($aVehiculesUtilitaires as $key => $value) {
                if ($aVehiculesUtilitaires[$key]['MEDIA_PATH']) {
                    $aVehiculesUtilitaires[$key]['MEDIA_PATH'] = Pelican_Media::getFileNameMediaFormat($aVehiculesUtilitaires[$key]['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['WEB_GAMME']);
                }
				$aVehiculesUtilitaires[$key]['CTA'] = $this->getCtaGamme($value);
            }
        }
        $this->assign("aVehiculesUtilitaires", $aVehiculesUtilitaires);
        
        /*
         * Récupération du média de l'utilitaire push
         */
        $aMediaPushUtil = Pelican_Cache::fetch('Media/Detail', array(
                $aZone['MEDIA_ID2']
        ));
        if ($aMediaPushUtil['MEDIA_PATH']) {
            $aMediaPushUtil['MEDIA_PATH'] = Pelican_Media::getFileNameMediaFormat($aMediaPushUtil['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['GAMME_PUSH']);
        }
        $this->assign('aMediaPushUtil', $aMediaPushUtil);

         //Mentions Légales héritées de la page N1                              
        $aZoneGalerieNiveau2 = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateIdLangue", array(
                $aParams['SITE_ID'],
                Pelican::$config['ZONE_TEMPLATE_ID']['GALERIE_N2_VEHICULE'],
                $aParams['LANGUE_ID']
            ));
        
        $this->assign('aZoneGalerieNiveau2', $aZoneGalerieNiveau2);


        $this->fetch();
    }
	
	
	public function getCtaGamme($value){
		
		 $aCtaGamme =  Pelican_Cache::fetch("Frontend/Citroen/VehiculeExpandCTA", array(
                                            intval($value['VEHICULE_ID']),
                                            $_SESSION[APP]['SITE_ID'],
                                            $_SESSION[APP]['LANGUE_ID'],
											'CTA_HOME'
                                        ));
				
				if(!is_array($aCtaGamme)){
					$aCtaGamme =array();	
				}
				
				array_unshift($aCtaGamme, array(
											'VEHICULE_ID' => $value['VEHICULE_ID'],
											'SITE_ID' => $_SESSION[APP]['SITE_ID'],
											'LANGUE_ID' => $_SESSION[APP]['LANGUE_ID'],
											'VEHICULE_CTA_EXPAND_LABEL' => t('DECOUVRIR'),
											'VEHICULE_CTA_EXPAND_VALUE' => '1',
											'VEHICULE_CTA_EXPAND_URL' => $value['PAGE_CLEAR_URL'],
											'EXPAND_GTM_ACTION'=> 'showroom',
											));
				
				 
				 
				 $iCompteurCta = 0;
				  if ( is_array($aCtaGamme)) {
                    foreach ($aCtaGamme as $keyCta => $expandCta) {
						
						if($iCompteurCta < 2){
							$aParams['CTA'] = $expandCta;
							$aParams['CTA']['TYPE'] = \Citroen_CTA_Expand::TYPE;
							$aParams['CTA']['EXPAND_GTM_CATEGORY'] = 'NewCar';

							if (!isset($expandCta['VEHICULE_CTA_EXPAND_OUTIL'])){
								$aParams['CTA']['ADD_CSS'] = 'buttonTransversal';
							} else {
								$aParams['CTA']['ADD_CSS'] = 'buttonLead';
							}
							$aParams['CTA']['POST_EXPAND_GTM_ACTION'] = 'cta';
							$aParams['PAGE_VEHICULE'] = $value['VEHICULE_ID'];
							$aParams['VEHICULE_URL'] = $value['PAGE_CLEAR_URL'];
							$aParams['MODE_OUVERTURE_SHOWROOM'] = $value['MODE_OUVERTURE_SHOWROOM'];

							$aVehiculesCta[$keyCta] .= Pelican_Request::call('_/Layout_Citroen_CTA/', $aParams);
						}
							$iCompteurCta++;
                    }
                }
		
		
		return $aVehiculesCta;
		
	}

}
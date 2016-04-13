<?php
use Citroen\Configurateur;

class Layout_Citroen_GalerieNiveau3Vehicules_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $aParams = $this->getParams();
		


        //Recuperation de la tranche texte 2/3 + CTA 1/3
        $aTrancheContenuCta = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateId", array(
            $aParams['pid'],
            Pelican::$config['ZONE_TEMPLATE_ID']['CONTENU_TEXT_CTA'],
            '' ,
            $_SESSION[APP]["LANGUE_ID"] )
        );

        $this->assign("contenuTextCta", $aTrancheContenuCta);

		$iPageParentId = $aParams['PAGE_PARENT_ID'];
		
        $aZonePageParent = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateId", array(
			$iPageParentId,
            Pelican::$config['ZONE_TEMPLATE_ID']['GALERIE_N2_VEHICULE'],
            Pelican::getPreviewVersion(),
			$_SESSION[APP]['LANGUE_ID']
        ));
		

        $this->assign("aParams", $aParams);
		$this->assign("aPage", $aZonePageParent);

         $aConfiguration = Pelican_Cache::fetch("Frontend/Citroen/Configuration", array(
            $aParams['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion(),
        ));

        //Page parente
        $aPage = Pelican_Cache::fetch("Frontend/Page", array(
            $iPageParentId,
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion()
			));
        $this->assign("aPageParentFull", $aPage);





        $aPagesN1 = Pelican_Cache::fetch("Frontend/Citroen/MasterPageVehiculesN1", array(
            $iPageParentId,
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion()
        ));
        $this->assign("aPagesN1", $aPagesN1);

        $aAutresVehicules = array();
        foreach ($aPagesN1 as $nav) {
            if ($nav['TEMPLATE_PAGE_ID'] != Pelican::$config['TEMPLATE_PAGE']['MASTER_PAGE_VEHICULES_N2']) {
                $aAutresVehicules[] = $nav;
            }
        }
        $this->assign("aAutresVehicules", $aAutresVehicules);

        //Mentions Légales héritées de la page parente
        $aZoneGalerieNiveau2 = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                $aPage['PAGE_ID'],
                Pelican::$config['ZONE_TEMPLATE_ID']['GALERIE_N2_VEHICULE'],
                $aPage['PAGE_VERSION'],
                $_SESSION[APP]['LANGUE_ID']
            ));
                        
        if (isset($aZoneGalerieNiveau2['ZONE_TITRE7'])&&$aZoneGalerieNiveau2['ZONE_TITRE7'] != '') {
		$aMentionsLegales = Pelican_Cache::fetch("Frontend/Page", array(
                    $aZoneGalerieNiveau2['ZONE_TITRE7'],
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    Pelican::getPreviewVersion()
                            )
                );
		}
		if ($aZoneGalerieNiveau2['MEDIA_ID4'] != '') {
			$sVisuelML = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aZoneGalerieNiveau2['MEDIA_ID4']), Pelican::$config['MEDIA_FORMAT_ID']['ACTUALITES_PETIT']);
		}
        
        $isMobile = $this->isMobile();

        $aVehicules = Pelican_Cache::fetch("Frontend/Citroen/VehiculesParGamme", array(
            $aParams['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            $aParams['PAGE_GAMME_VEHICULE'],
            Pelican::getPreviewVersion(),
			$aParams['PAGE_ID']
        ));

        $aDatas = $aParams;
        $aDatas['CTA'] = array(
            'BARRE_OUTILS_URL_WEB' => '##URL_CONFIGURATEUR##',
            'BARRE_OUTILS_MODE_OUVERTURE' => 2,
            'BARRE_OUTILS_TITRE' => t('CONFIGURER'),   
            'NO_SPAN'=>true,        
        );
        if ($aVehicules) {
            foreach($aVehicules as $key => $value) {
                $aCta = Pelican_Cache::fetch("Frontend/Citroen/VehiculeExpandCTA", array(
                    $value['VEHICULE_ID'],
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
					'CTA_MASTER'
                ));
				
				if(!is_array($aCta)){
					$aCta =array();	
				}
				
				array_unshift($aCta, array(
											'VEHICULE_ID' => $value['VEHICULE_ID'],
											'SITE_ID' => $_SESSION[APP]['SITE_ID'],
											'LANGUE_ID' => $_SESSION[APP]['LANGUE_ID'],
											'VEHICULE_CTA_EXPAND_LABEL' => t('DECOUVRIR'),
											'VEHICULE_CTA_EXPAND_VALUE' => '1',
											'VEHICULE_CTA_EXPAND_URL' => $value['PAGE_CLEAR_URL'],
											'EXPAND_GTM_ACTION'=> 'showroom',
											));

				 $iCompteurCta = 0;
                if (sizeof($aCta)>0) {
                    foreach ($aCta as $keyCta => $expandCta) {
						if($iCompteurCta < 3){
							$aParams['CTA'] = $expandCta;
							$aParams['CTA']['TYPE'] = \Citroen_CTA_Expand::TYPE;
							$aParams['CTA']['EXPAND_GTM_CATEGORY'] = 'NewCar';

							if(!$expandCta['VEHICULE_CTA_EXPAND_OUTIL']){
								$aParams['CTA']['ADD_CSS'] = 'buttonTransversal';
								$keyClass = "buttonTransversal";
							}else{
								$aParams['CTA']['ADD_CSS'] = 'buttonLead';
								$keyClass = "buttonLead";
							}

							$aParams['CTA']['POST_EXPAND_GTM_ACTION'] = 'cta';
							$aParams['PAGE_VEHICULE'] = $value['VEHICULE_ID'];
							$aParams['VEHICULE_URL'] = $value['PAGE_CLEAR_URL'];
							$aParams['MODE_OUVERTURE_SHOWROOM'] = $value['MODE_OUVERTURE_SHOWROOM'];

							$aVehicules[$key]['CTA'][$keyCta] .= Pelican_Request::call('_/Layout_Citroen_CTA/', $aParams);
						}
						$iCompteurCta++;
                    }
                }
				

                if ($aVehicules[$key]['MEDIA_PATH']) {
                    if ($isMobile) {
                        $aVehicules[$key]['MEDIA_PATH'] = Pelican_Media::getFileNameMediaFormat($aVehicules[$key]['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['MOBILE_TECHNOLOGIE']);
                    }
                    else {
                        $aVehicules[$key]['MEDIA_PATH'] = Pelican_Media::getFileNameMediaFormat($aVehicules[$key]['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['WEB_MASTER_N2_VEHICULE']);
                    }
                }
                $aParams['CTA']['TYPE'] = \Citroen_CTA_Expand::TYPE;
                $aDatas['CTA']['COLOR'] = ($aParams['PAGE_MODE_AFFICHAGE']=='DS')?'brown':'grey';
                $aDatas['vehicule'] = $value;
                $aVehicules[$key]['CONFIGURATEUR'] =  Pelican_Request::call('_/Layout_Citroen_CTA/',$aDatas);

                    
            }

        }
        if($aParams['PAGE_GAMME_VEHICULE'] == 'GAMME_LIGNE_DS'){
            $eventCategory = 'SlideshowRange::DS';
        }elseif($aParams['PAGE_GAMME_VEHICULE'] == 'GAMME_LIGNE_C'){
            $eventCategory = 'SlideshowRange::C';

        }elseif($aParams['PAGE_GAMME_VEHICULE'] == 'GAMME_VEHICULE_UTILITAIRE'){
                        $eventCategory = 'SlideshowRange::VU';
        }
        $this->assign("eventCategory", $eventCategory);

        $this->assign("aVehicules", $aVehicules);
       
        $this->assign("aConfiguration", $aConfiguration);
        $this->assign("sVisuelML", $sVisuelML);
        $this->assign('aMentionsLegales', $aMentionsLegales);
        $this->assign("aZoneGalerieNiveau2", $aZoneGalerieNiveau2);
		if(is_array(Pelican::$config['JAVASCRIPT_FOOTER']['MOBILE'])){
        array_push(Pelican::$config['JAVASCRIPT_FOOTER']['MOBILE'],
                    Pelican::$config['DESIGN_HTTP']."/assets/js/common/new-vehicles-mobile.js"
        );
		}
        $this->fetch();
    }

}
<?php
use Citroen\Configurateur;

class Layout_Citroen_GalerieNiveau2Vehicules_Controller extends Pelican_Controller_Front
{

	public function indexAction()
	{
		$aParams = $this->getParams();
		
		$this->assign("aParams", $aParams);
		$isMobile = $this->isMobile();

		$aConfiguration = Pelican_Cache::fetch("Frontend/Citroen/Configuration", array(
				$aParams['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion()
		));
		/*
		 * Ramène les véhicules de la Gamme Ligne DS
		 */
		$aVehiculesDS = Pelican_Cache::fetch("Frontend/Citroen/VehiculesParGamme", array(
				$aParams['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				'GAMME_LIGNE_DS',
				Pelican::getPreviewVersion()
		));

		$aDatas = $aParams;
		$aDatas['CTA'] = array(
            'BARRE_OUTILS_URL_WEB' => '##URL_CONFIGURATEUR##',
            'BARRE_OUTILS_MODE_OUVERTURE' => 2,
            'BARRE_OUTILS_TITRE' => t('CONFIGURER'),   
            'NO_SPAN'=>true,        
        );

		if ($aVehiculesDS) {
			foreach ($aVehiculesDS as $key => $value) {
				if ($aVehiculesDS[$key]['MEDIA_PATH']) {
					$aVehiculesDS[$key]['MEDIA_PATH'] = Pelican_Media::getFileNameMediaFormat($aVehiculesDS[$key]['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['WEB_MASTER_N1_VEHICULE']);
				}
				$aDatas['CTA']['COLOR'] = 'brown';
		        $aDatas['vehicule'] = $value;
      			$aVehiculesDS[$key]['CONFIGURATEUR'] =  Pelican_Request::call('_/Layout_Citroen_CTA/',$aDatas);

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
			foreach ($aVehiculesC as $key => $value) {
				if ($aVehiculesC[$key]['MEDIA_PATH']) {
					$aVehiculesC[$key]['MEDIA_PATH'] = Pelican_Media::getFileNameMediaFormat($aVehiculesC[$key]['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['WEB_MASTER_N1_VEHICULE']);
				}
				$aDatas['CTA']['COLOR'] = 'grey';
		        $aDatas['vehicule'] = $value;
      			$aVehiculesC[$key]['CONFIGURATEUR'] =  Pelican_Request::call('_/Layout_Citroen_CTA/',$aDatas);

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
			foreach ($aVehiculesUtilitaires as $key => $value) {
				if ($aVehiculesUtilitaires[$key]['MEDIA_PATH']) {
					$aVehiculesUtilitaires[$key]['MEDIA_PATH'] = Pelican_Media::getFileNameMediaFormat($aVehiculesUtilitaires[$key]['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['WEB_MASTER_N1_VEHICULE']);
				}
				$aDatas['CTA']['COLOR'] = 'grey';
		        $aDatas['vehicule'] = $value;
      			$aVehiculesUtilitaires[$key]['CONFIGURATEUR'] =  Pelican_Request::call('_/Layout_Citroen_CTA/',$aDatas);

			}
		}
		unset($aDatas);

		$this->assign("aVehiculesUtilitaires", $aVehiculesUtilitaires);

		$aPagesN1 = Pelican_Cache::fetch("Frontend/Citroen/MasterPageVehiculesN1", array(
				$aParams['pid'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion()
		));
		if ($aPagesN1) {
			foreach ($aPagesN1 as $key => $value) {
				if ($aPagesN1[$key]['MEDIA_PATH']) {
					if ($isMobile) { 
						$aPagesN1[$key]['MEDIA_PATH'] = Pelican_Media::getFileNameMediaFormat($aPagesN1[$key]['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['MOBILE_MASTER_N1_STANDARD']);
					} else {
						$aPagesN1[$key]['MEDIA_PATH'] = Pelican_Media::getFileNameMediaFormat($aPagesN1[$key]['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['WEB_MASTER_N1_STANDARD']);
					}
				}
			}
		}
		$this->assign("aPagesN1", $aPagesN1);

		$aAutresVehicules = array();
		foreach ($aPagesN1 as $nav) {
			if ($nav['TEMPLATE_PAGE_ID'] != Pelican::$config['TEMPLATE_PAGE']['MASTER_PAGE_VEHICULES_N2']) {
				$aAutresVehicules[] = $nav;
			}
		}
		$this->assign("aAutresVehicules", $aAutresVehicules);

		
		$this->assign("aConfiguration", $aConfiguration);

		if ($aParams['ZONE_TITRE7'] != '') {
			$aMentionsLegales = Pelican_Cache::fetch("Frontend/Page", array(
					$aParams['ZONE_TITRE7'],
					$_SESSION[APP]['SITE_ID'],
					$_SESSION[APP]['LANGUE_ID'],
					Pelican::getPreviewVersion()
			));
		}
		if ($aParams['MEDIA_ID4'] != '') {
			$sVisuelML = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aParams['MEDIA_ID4']), Pelican::$config['MEDIA_FORMAT_ID']['ACTUALITES_PETIT']);
		}
		$this->assign('aMentionsLegales', $aMentionsLegales);
		$this->assign('sVisuelML', $sVisuelML);
		$this->fetch();
	}

}
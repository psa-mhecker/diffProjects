<?php

class Citroen_Controller extends Pelican_Controller_Front
{
    public function addToCompareAction ()
    {
		include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Vehicule.php');
		$aData = $this->getParams();
		$bReturn = Frontoffice_Vehicule_Helper::putVehiculeCompInSession($aData['vehiculeId'],null,$aData['finitionId']);
        if($bReturn == false){
			$this->getRequest()->addResponseCommand('script', array(
				 'value' => "alert('".t('ALERT_LIMIT_COMPARATEUR')."');"
			));
		}
    }
    
    public function carPickerAction() {
	
        
        $country = ($_GET['Country'])?$_GET['Country']:$_POST['Country'];
        $lan = ($_GET['Lan'])?$_GET['Lan']:$_POST['Lan'];
        $range = ($_GET['range'])?$_GET['range']:$_POST['range'];
		$media = $_REQUEST['media'];
  		if ($media == '') $media = Pelican::$config["MEDIA_HTTP"]; //__JFO : permet de preciser le host media car GRC n'est appelÃ© que de l'intranet
      
        $aRangeConf = array(
            1 => 'GAMME_LIGNE_DS',
            2 => 'GAMME_LIGNE_C',
            3 => 'GAMME_VEHICULE_UTILITAIRE',
            4 => 'GAMME_BUSINESS',
        );
        
        /**
         * Récupération du site
         */
        $aSite = Pelican_Cache::fetch("Citroen/SiteBySiteCode", array($country));
        if (is_array($aSite) && isset($aSite['SITE_ID'])) $site = $aSite['SITE_ID'];
        else die('Parameters Country not found');

        /**
         * Récupération de la langue
         */
        $aLangue = Pelican_Translate::getLanguageCode();
        $aLan = explode('-', $lan);
        if (is_array($aLangue) && isset($aLangue[$aLan[0]])) $langue = $aLangue[$aLan[0]];
        else die('Parameters Lan not found');
        
        /**
         * Récupération de gamme
         */
        if (!$range) {
            $range = '1,2,3,4';
        }
        $aRange = explode(',', $range);
        
        
        
        $xml = new DOMDocument('1.0', 'UTF-8');
        $structure = $xml->createElement('structure');
        if( isset($_GET['Country']) && !empty($_GET['Country'])){			
			$_SESSION[APP]['CODE_PAYS']    = strtoupper($_GET['Country']);
		}
		if( isset($aLan[0]) && !empty($aLan[0])){			
			$_SESSION[APP]['LANGUE_CODE']  = strtoupper($aLan[0]);
		}
		 
        if (is_array($aRange) && !empty($aRange)) {
            $xmlRanges = $xml->createElement('ranges');
            foreach($aRange as $gamme) {
                if (!$aRangeConf[$gamme]) break;
                /**
                 * Récupération des véhicules
                 */
                $cars = Pelican_Cache::fetch("Frontend/Citroen/VehiculesParGamme", array(
                    $site,
                    $langue,
                    $aRangeConf[$gamme],
                    Pelican::getPreviewVersion()
                ));

                if (is_array($cars) && !empty($cars)) {
                    $xmlRange = $xml->createElement('range');
                    /* Label */
                    $xmlLabel = $xml->createElement('label');
                    $xmlLabelLib = $xml->createCDATASection(t(Pelican::$config['VEHICULE_GAMME'][$aRangeConf[$gamme]]));
                    $xmlLabel->appendChild($xmlLabelLib);
                    $xmlRange->appendChild($xmlLabel);

                    /* isdsline */
                    $xmlDsLine = $xml->createElement('isdsline');
                    $xmlDsLineLib = $xml->createCDATASection(($aRangeConf[$gamme]==='GAMME_LIGNE_DS')?'true':'false');
                    $xmlDsLine->appendChild($xmlDsLineLib);
                    $xmlRange->appendChild($xmlDsLine);

                    $xmlCars = $xml->createElement('cars');
                    foreach($cars as $car) {
                        $xmlCar = $xml->createElement('car');
                        
                        /* guid */
                        $xmlGuid = $xml->createElement('guid');
                        $xmlGuidLib = $xml->createCDATASection($car['LCDV6']);
                        $xmlGuid->appendChild($xmlGuidLib);
                        $xmlCar->appendChild($xmlGuid);
                        
                        /* guid */
                        $xmlCarLabel = $xml->createElement('label');
                        $xmlCarLabelLib = $xml->createCDATASection($car['VEHICULE_LABEL']);
                        $xmlCarLabel->appendChild($xmlCarLabelLib);
                        $xmlCar->appendChild($xmlCarLabel);
                        
                        if ($car['MEDIA_PATH']) {
                            $xmlMedia = $xml->createElement('media');
                            
                            /* guid */
                            $xmlMediaGuid = $xml->createElement('guid');
                            $xmlMediaGuidLib = $xml->createCDATASection($car['MEDIA_ID']);
                            $xmlMediaGuid->appendChild($xmlMediaGuidLib);
                            $xmlMedia->appendChild($xmlMediaGuid);

                            /* guid */
                            $xmlMediaType = $xml->createElement('type');
                            $aFile = pathinfo($car['MEDIA_PATH']);
                            $xmlMediaTypeLib = $xml->createCDATASection($aFile['extension']);
                            $xmlMediaType->appendChild($xmlMediaTypeLib);
                            $xmlMedia->appendChild($xmlMediaType);
                            
                            /* guid */
                            $xmlCarUrl = $xml->createElement('url');
                            $xmlCarUrlLabel = $xml->createCDATASection($media.$car['MEDIA_PATH']);
                            $xmlCarUrl->appendChild($xmlCarUrlLabel);
                            $xmlMedia->appendChild($xmlCarUrl);
                            
                            $xmlCar->appendChild($xmlMedia);
                        }
                        $xmlCars->appendChild($xmlCar);
                    }
                    $xmlRange->appendChild($xmlCars);
                }
                if (!is_null($xmlRange)) {
                    $xmlRanges->appendChild($xmlRange);
                }
            }
            $structure->appendChild($xmlRanges);
        }
        
        $xml->appendChild($structure);
        
        header('Content-type: application/xml; charset=utf-8');
        echo $xml->saveXML();
        die();
    }
    
}

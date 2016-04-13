<?php

namespace Citroen;

use Citroen\Service\AnnuPDV;

/**
 * Class Vehicules gérant les appels vers WS AnnuPDV
 *
 * @author Khadidja Messaoudi <khadidja.messaoudi@businessdecision.com>
 *
 */
class Annuaire
{

    /**
     * Construction de la configuration de la Map
     * Appel WS AnnuPDV : getDealerList
     *
     * @param string $iZoom : Zoom
     * @param string $iStep : Pas
     * @param string $filter : filtre affichage
     * @param string $iPdv : Nb Pdv
     * @param string $iDvn : Nb Dvn
     * @param string $iLat : Latitude
     * @param string $iLon : Longitude
     * @param string $sPays : Pays (ex : FR)
     * @param string $sLocale : Code pays (ex : fr_FR)
     * @param string $iRmax : Rayon
     * @param string $bRegroupement : Regroupement true ou false
     * @param string $bAutocompletion : Autocompletion true ou false
     * @param string $idPdv : identifiant pdv
     * @return array $aVehicules tableau de véhicules
     */
    public static function getMapConfiguration($iZoom, $iStep, $filter, $iPdv, $iDvn, $iLat, $iLon, $sPays, $sLocale, $iRmax, $bRegroupement, $bAutocompletion, $idPdv = '')
    {
        /*$serviceParams = array(
            'consumer' => \Pelican::$config['SERVICE_ANNUPDV']['CONSUMER'],
            'brand' => \Pelican::$config['SERVICE_ANNUPDV']['BRAND'],
            'country' => $sPays,
            'culture' => $sLocale,
            'sort' => ($sPays == 'FR') ? 'custom' : 'distance',
            'details' => 'med',
            'latitude' => 0,
            'longitude' => 0,
        );
        try {
            $service = \Itkg\Service\Factory::getService('CITROEN_SERVICE_ANNUPDV', array());
            $response = $service->call('getDealersList', $serviceParams);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }*/
        $aConfig = array();
        //$aResponse = \Citroen_View_Helper_Global::objectsIntoArray($response->DealersMedium);
        //if (is_array($aResponse) && count($aResponse) > 0) {
        $aConfig['lat'] = (float) $iLat;
        $aConfig['lng'] = (float) $iLon;
        $aConfig['zoom'] = (int) $iZoom;
        $aConfig['timeout'] = (int) 2000;
        $aConfig['country'] = strtolower($sPays);
        $aConfig['autocomplete'] = $bAutocompletion;
        $aConfig['clusterer'] = $bRegroupement;
        $aConfig['filter'] = ($filter == 2) ? "dvnpdv": "rayon";
        $aConfig['search'] = array(
            "step" => (int) $iStep,
            "radius" => (int) $iRmax,
            "types" => array(
                array(
                    "label" => "pdv",
                    "count" => $iPdv),
                array(
                    "label" => "dvn",
                    "count" => $iDvn),
            )
        );
          // $aServiceGlobal = \Pelican::$config['SERVICES_ANNUPDV'];
        
            $aServiceGlobal =  \Pelican_Cache::fetch('Frontend/Citroen/Annuaire/ServicesOrder', array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            "1"
                ));     

        /*foreach ($aResponse as $dealer) {
            if (($idPdv != '' && $idPdv == $dealer['SiteGeo']) || $idPdv == '') {
                if ($idPdv != '') {
                    $aConfig['lat'] = (float) $dealer['Coordinates']['Latitude'];
                    $aConfig['lng'] = (float) $dealer['Coordinates']['Longitude'];
                    $aConfig['zoom'] = 13;
                }
                $aServiceDealer = array();
                $bVN = false;
                if (is_array($dealer['BusinessList']) && count($dealer['BusinessList']) > 0) {
                    foreach ($dealer['BusinessList'] as $i => $business) {
                        if (is_array($aServiceGlobal) && count($aServiceGlobal) > 0) {
                            foreach ($aServiceGlobal as $k => $service) {
                                if ($service['label'] == '' && $service['code'] == $business['Code']) {
                                    $aServiceGlobal[$k]['label'] = $business['Label'];
                                }
                            }
                        }
                        $aServiceDealer[] = \Pelican::$config['SERVICES_ANNUPDV_CORRESPONDANCE'][$business['Code']]['index'];
                        if ($business['Code'] == 'VN') {
                            $bVN = true;
                        }
                    }
                }

                $bDVN = ($bVN == true && $dealer['IsAgent'] != true) ? true : false;
                $aConfig['markers'][] = array(
                    "id" => $dealer['SiteGeo'],
                    "rrdi" => $dealer['RRDI'],
                    "type" => (($bDVN == true) ? "dvn" : "pdv"),
                    "lat" => $dealer['Coordinates']['Latitude'],
                    "lng" => $dealer['Coordinates']['Longitude'],
                    "services" => $aServiceDealer
                );
            }
        }*/
        $aConfig['services'] = $aServiceGlobal;
        //}
        return $aConfig;
    }

    /**
     * Appel WS AnnuPDV : getDealerList
     *
     * @param string $iLat : Latitude
     * @param string $iLon : Longitude
     * @param string $sPays : Pays (ex : FR)
     * @param string $sLocale : Code pays (ex : fr_FR)
     * @param string $iRmax : Rayon
     * @param string $brandActivity : Filtre sur le métier des PDV (DS, AC ou chaîne vide pour n'appliquer aucun filtre et remonter tous les pdv)
     * @return array $aDealers tableau de pdv/dvn
     */
    public static function getDealerList($iLat, $iLon, $sPays, $sLocale, $iRmax, $aRequest, $nbmax,$minpdv,$mindvn, $brandActivity = "")
    {
        if($minpdv != '' && $mindvn != ''){
            $searchtype = 'spiral';
        }else{
            $searchtype = 'standard';
        }
        $serviceParams = array(
            'consumer' => \Pelican::$config['SERVICE_ANNUPDV']['CONSUMER'],
            'brand' => \Pelican::$config['SERVICE_ANNUPDV']['BRAND'],
            'BrandActivity' => $brandActivity,
            'country' => $sPays,
            'culture' => $sLocale,
            /*'sort' => ($sPays == 'FR') ? 'custom' : 'distance', correctif du ticket CPW-3209*/ 
            'sort' =>  'distance',
            'details' => 'max',
            'rmax' => $iRmax,
            'latitude' => $iLat,
            'longitude' => $iLon,
            'resultmax'=>$nbmax,
            'searchtype'=>$searchtype,
            'minpdv'=>$minpdv,
            'mindvn'=>$mindvn,
            'unit'  => ($sPays == 'GB') ? 'mi' : 'km', // __RFI spécifie l'unité de distance

        );

        try {
            $service = \Itkg\Service\Factory::getService('CITROEN_SERVICE_ANNUPDV', array());
            $response = $service->call('getDealersList', $serviceParams);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        $aDealers = array();
		
        $aResponse = \Citroen_View_Helper_Global::objectsIntoArray($response->DealersFull, $sLocale);

        if (is_array($aResponse) && count($aResponse) > 0) {
			$aServiceGlobal =  \Pelican_Cache::fetch('Frontend/Citroen/Annuaire/ServicesOrder', array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            "1"
                ));
            foreach ($aResponse as $dealer) {
				
                $aServiceDealer = array();
                //if (in_array($dealer['SiteGeo'], $aRequest)) {
                $sAddress = '';
                if (!empty($dealer['Address'])) {
                    $sLine = $dealer['Address']['Line1'] . ( $dealer['Address']['Line2'] != '' ? '&nbsp;' . $dealer['Address']['Line2'] : '') . ( $dealer['Address']['Line3'] != '' ? '&nbsp;' . $dealer['dealer']['Line3'] : '');
                    $sAddress = $sLine . '<br />' . $dealer['Address']['ZipCode'] . '&nbsp;' . $dealer['Address']['City'];
                }
                $bVN = false;
                if (is_array($dealer['BusinessList']) && count($dealer['BusinessList']) > 0) {
                    foreach ($dealer['BusinessList'] as $i => $business) {
                        if(is_array($aServiceGlobal) && count($aServiceGlobal) > 0 ){
                            foreach($aServiceGlobal as $key=>$service)
                            {
                                if($business['Code'] == $service['code']){
                                    $aServiceDealer[] = $service['index'];
                                }
                            }
                        }
                        if ($business['Code'] == 'VN') {
                            $bVN = true;
                        }
                    }
                }
                $bDVN = ($bVN == true && $dealer['IsAgent'] != true) ? true : false;

				
                $aDealers[] = array(
                    "id" => $dealer['SiteGeo'],
                    "rrdi" => $dealer['RRDI'],
                    "media" => $dealer['Image'],
                    "name" => utf8_encode_without_cyrilique($dealer['Name'], $sLocale),
                    "address" => utf8_encode_without_cyrilique($sAddress, $sLocale),
                    "phone" => $dealer['Phones']['PhoneNumber'],
                    "distance" => $dealer['DistanceFromPoint'],
                    "services" => $aServiceDealer,
                    "lat" => $dealer['Coordinates']['Latitude'],
                    "lng" => $dealer['Coordinates']['Longitude'],
                    "type" => (($bDVN == true) ? "dvn" : "pdv"),
					"isAgent" => $dealer['IsAgent']
                );
                //}
            }
        }
        return $aDealers;
    }

    /**
     * Appel WS AnnuPDV : getDealerList
     *
     * @param string $id : Identifiant du store
     * @param string $sPays : Pays (ex : FR)
     * @param string $sLocale : Code pays (ex : fr_FR)
     * @param string $iRmax : Rayon
     * @return array $aVehicules tableau de véhicules
     */
    public static function getDealer($id, $sPays, $sLocale)
    {
        $serviceParams = array(
            'consumer' => \Pelican::$config['SERVICE_ANNUPDV']['CONSUMER'],
            'brand' => \Pelican::$config['SERVICE_ANNUPDV']['BRAND'],
            'country' => $sPays,
            'culture' => $sLocale,
            'sitegeo' => $id
        );
        try {
            $service = \Itkg\Service\Factory::getService('CITROEN_SERVICE_ANNUPDV', array());
            $response = $service->call('getDealer', $serviceParams, false);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        $aDealer = array();
        $bAdvisor = false;
        $aResponse = \Citroen_View_Helper_Global::objectsIntoArray($response->Dealer, $sLocale);

        if (is_array($aResponse) && count($aResponse) > 0) {
            $sAddress = '';
            $sLine = '';
            if (!empty($aResponse['Address'])) {
                $sLine = $aResponse['Address']['Line1'] . ( $aResponse['Address']['Line2'] != '' ? '&nbsp;' . $aResponse['Address']['Line2'] : '') . ( $aResponse['Address']['Line3'] != '' ? '&nbsp;' . $aResponse['Address']['Line3'] : '');
                $sAddress = $sLine . '<br />' . $aResponse['Address']['ZipCode'] . '&nbsp;' . $aResponse['Address']['City'];
            }
            $aContacts = array();
            //$aServices = array();
            if (is_array($aResponse['ServiceList']) && count($aResponse['ServiceList']) > 0) {
                foreach ($aResponse['ServiceList'] as $i => $service) {
                    //$aServices[] = $service['APV_SERVICEAPV'];
                    $aCode = explode('_', $service['Code']);
                    if (is_array($aResponse['PersonList']) && count($aResponse['PersonList']) > 0) {
                        foreach ($aResponse['PersonList'] as $j => $person) {
                            if ($service['Code'] == $person['ServiceCode']) {
                                $aContacts[$aCode[0]]['list'][$j] = array(
                                    'name' => utf8_encode_without_cyrilique($person['TitleLabel'] . '&nbsp;' . $person['LastName'] . '&nbsp;' . $person['FirstName'], $sLocale) ,
                                    'office' => utf8_encode_without_cyrilique($person['FunctionLabel'], $sLocale) ,
                                    'phone' => $person['Phone'],
                                    'fax' => $person['Fax'],
                                    'email' => $person['Email']
                                );
                            }
                        }
                    }
                    if (!empty($aContacts[$aCode[0]]['list'])) {
                        $aContacts[$aCode[0]]['group'] = utf8_encode_without_cyrilique($service['Label'], $sLocale) ;
                        //$aContacts[$service['Code']]['timetable'] = utf8_encode_without_cyrilique($service['OpeningHours']);
                    }
                }
            }
            $aServiceDealer = array();
            $aServiceMobile = array();
            $serviceList = array(); // Liste des services avec les données brutes pour chaque service
            $aServiceGlobal =  \Pelican_Cache::fetch('Frontend/Citroen/Annuaire/ServicesOrder', array(
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                "1"
                    ));     
            $bVN = false;        
            if (is_array($aResponse['BusinessList']) && count($aResponse['BusinessList']) > 0) {
                foreach ($aResponse['BusinessList'] as $i => $business) {
                    if(is_array($aServiceGlobal) && count($aServiceGlobal) > 0 ){
                        foreach($aServiceGlobal as $key=>$service)
                        {
                            if($business['Code'] == $service['code']){
                                $aServiceDealer[] = $service['index'];
                                $aServiceMobile[] = array(
                                'code' => $service['index'],
                                'label' => utf8_encode_without_cyrilique($business['Label'], $sLocale)) ;
                                $serviceList[$service['index']] = $service;
                                                            $serviceListCode[$i] = $service['code'];
                            }

                        }
                    }
                    if ($business['Code'] == 'VN') {
                            $bVN = true;
                    }
                    if($business['Code'] == \Pelican::$config['CODE_ADVISOR']){
                        $bAdvisor = true;
                    }
                }
            }
            
            $aBenefits = array();

            if (is_array($aResponse['BenefitList']) && count($aResponse['BenefitList']) > 0) {
                foreach ($aResponse['BenefitList'] as $i => $benefit) {
                    $aBenefits[] = utf8_encode_without_cyrilique($benefit['Label'], $sLocale);
                }
            }
            $sHoraires = '';
            
            if (!empty($aContacts) && is_array($aResponse['OpeningHoursList']) && count($aResponse['OpeningHoursList']) > 0) {
                        foreach ($aResponse['OpeningHoursList'] as $j => $horaires) {
                            if ($horaires['Type'] == 'GENERAL' && trim($horaires['Label'])) {
                                $sHoraires = $horaires['Label'];
                            } else {
                                $aContacts[$horaires['Type']]['timetable'] = utf8_encode_without_cyrilique($horaires['Label'], $sLocale);
                            }
                        }
                    }
            
            $tmp = explode("/", $aResponse['WebSites']['Public']);
            $name = '/' . end($tmp);
                    
            $aDealer = array();
       
            $bDVN = ($bVN == true && $aResponse['IsAgent'] != true) ? true : false;
            $aDealer["type"] = ($bDVN == true) ? "dvn" : "pdv";
            $aDealer["bAdvisor"] = $bAdvisor;       
            $aDealer["nameAdvisor"] = $name; 
            if ($aResponse['SiteGeo']) $aDealer["id"] = $aResponse['SiteGeo'];
            if ($aResponse['Image']) $aDealer["media"] = $aResponse['Image'];
            if ($aResponse['Name']) $aDealer["name"] = utf8_encode_without_cyrilique($aResponse['Name'], $sLocale);
            if ($sAddress) $aDealer["address"] = utf8_encode_without_cyrilique($sAddress, $sLocale);
            if ($aResponse['Phones']['PhoneNumber']) $aDealer["phone"] = $aResponse['Phones']['PhoneNumber'];
            if ($aResponse['FaxNumber']) $aDealer["fax"] = $aResponse['FaxNumber'];
            if ($aResponse['Emails']['Email']) $aDealer["email"] = $aResponse['Emails']['Email'];
            $aDealer["web"] ="";
            if(!empty($aResponse['WebSites']['Public'])){
                $aDealer["web"] = (strpos($aResponse['WebSites']['Public'], 'http://') !== false ? $aResponse['WebSites']['Public'] : 'http://' . $aResponse['WebSites']['Public'] );
            }
            $aDealer["route"] = 'https://maps.google.com/maps?saddr=&daddr=' . $aResponse['Coordinates']['Latitude'] . ',' . $aResponse['Coordinates']['Longitude'];
            if ($sHoraires) $aDealer["timetable"] = utf8_encode_without_cyrilique($sHoraires, $sLocale);
            if ($aResponse['DistanceFromPoint']) $aDealer["kilometrage"] = $aResponse['DistanceFromPoint'];
            if ($aServiceDealer) $aDealer["services"] = $aServiceDealer;
            if ($aServiceMobile) $aDealer["servicesMob"] = $aServiceMobile;
            if ($serviceList) $aDealer["serviceList"] = $serviceList;
            if ($serviceListCode) $aDealer["serviceListCode"] = $serviceListCode;
            if ($aBenefits) $aDealer["benefits"] = $aBenefits;
            if ($aResponse['WelcomeMessage']) $aDealer["welcome"] = utf8_encode_without_cyrilique($aResponse['WelcomeMessage'], $sLocale);
            if ($aContacts) $aDealer["contacts"] = $aContacts;
            if ($aResponse['Address']) $aDealer["addressDetail"] = $aResponse['Address'];
            $aDealer["RRDI"] = $aResponse['RRDI'];
            $aDealer["lat"] = $aResponse['Coordinates']['Latitude'];
            $aDealer["lng"] = $aResponse['Coordinates']['Longitude'];
            
        }
        
               
       
         
        //var_dump($aDealer);
        return $aDealer;
    }

}
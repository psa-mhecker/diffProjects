<?php

use Citroen\GammeFinition\VehiculeGamme;

class Layout_Citroen_DisponibleSurUrl_Controller extends Pelican_Controller_Front
{
    public function indexAction()
    {
        // Recuperation info de la page
        $aData = $this->getParams();

        $multiValues = Citroen_Cache::fetchProfiling($aData['ZONE_PERSO'], "Frontend/Citroen/ZoneMulti", array(
                $aData['PAGE_ID'],
                $aData['LANGUE_ID'],
                Pelican::getPreviewVersion(),
                $aData['ZONE_TEMPLATE_ID'],
                'VEHICULE',
                $aData['AREA_ID'],
                $aData['ZONE_ORDER']
        ));

        //debug($multiValues);

        // cache pour recuperer les vehicules dans la table pour le "disponible sur"
        $aVehicules = Pelican_Cache::fetch("Frontend/Citroen/VehiculeDisponibleSur", array(
                $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $aData['ZONE_TEXTE']
        ));

        if(is_array($multiValues) && count($multiValues)>0){
            foreach ($multiValues as $Vehicule) {
                $tabCar[$Vehicule['PAGE_ZONE_MULTI_TITRE']] = array($Vehicule['PAGE_ZONE_MULTI_URL'],
                                                                    $Vehicule['PAGE_ZONE_MULTI_URL2'],
                                                                    $Vehicule['PAGE_ZONE_MULTI_TITRE2']);
                $aTemp = VehiculeGamme::getShowRoomVehicule(
                        $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $Vehicule['PAGE_ZONE_MULTI_TITRE']
                );
                if ($aTemp[0]) {
                    $aResult[] = $aTemp[0];
                }
            }

            if (is_array($aResult) && count($aResult) > 0) {
                foreach ($aResult as $key => $result) {
                    $aResult[$key]['VEHICULE']['LINK'] = $tabCar[$result['VEHICULE']['VEHICULE_ID']][0];
                    $aResult[$key]['VEHICULE']['MOB_LINK'] = $tabCar[$result['VEHICULE']['VEHICULE_ID']][1];
                    $aResult[$key]['VEHICULE']['MODE_OUVERTURE_LINK'] = $tabCar[$result['VEHICULE']['VEHICULE_ID']][2];
                       
                    $aResult[$key]['VEHICULE']['THUMBNAIL_PATH_FORMATE'] = Citroen_Media::getFileNameMediaFormat($result['VEHICULE']['THUMBNAIL_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['WEB_DISPONIBLE_SUR']);   
                }
            }
        }

        if (!$this->isMobile())
                $this->assign('ZONE_WEB', $aData['ZONE_WEB']);
        else
                $this->assign('ZONE_MOBILE', $aData['ZONE_MOBILE']);
        
        
        $this->assign('ZONE_TITRE', $aData['ZONE_TITRE']);
        $this->assign('aVehicule', $aResult);
        $this->assign('aData', $aData);

        $this->fetch();
    }
}
?>
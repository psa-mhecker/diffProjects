<?php

class Layout_Citroen_VehicleSelector_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $aData = $this->getParams();
        $aZonePromotion = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate",
            array(
                $aData['PAGE_ID'],
                Pelican::$config['ZONE_TEMPLATE_ID']['LISTE_PROMOTIONS'],
                $aData['PAGE_VERSION'],
                $_SESSION[APP]['LANGUE_ID']
            )
        );
        if ($aZonePromotion) {
            //On récupère les informations de la liste des promotion rattachée à la même page que le VehicleSelector
            $aListePromotions = Pelican_Cache::fetch("Frontend/Citroen/MultiPromotion",
                array(
                    $aZonePromotion['PAGE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    $aZonePromotion["PAGE_VERSION"],
                    Pelican::$config['ZONE_TEMPLATE_ID']['LISTE_PROMOTIONS'],
                    "multiPromotion",
                    $_SESSION[APP]['SITE_ID'],
                    getPreviewVersion(),
                    Pelican::$config['TEMPLATE_PAGE']['DETAIL_PROMOTION'],
                    "Promotion"
                )
            );
            if (is_array($aListePromotions) && !empty($aListePromotions)) {
                //On va maintenant chercher les promotions rattachées ainsi que les voitures rattachées à ces dernières
                $aVehicules = array();
                for ($i = 0; $i < count($aListePromotions); $i++) {
                    //On va chercher les voitures
                    if (is_array($aListePromotions[$i]["CHILD"]) && !empty($aListePromotions[$i]["CHILD"])) {
                        foreach ($aListePromotions[$i]["CHILD"] as $promo) {
                            $aVehicules[$promo["VEHICULE_ID"]] = array('label'=>$promo["VEHICULE_LABEL"],
                                'url'=>$promo["PAGE_CLEAR_URL"],
                                'mode_ouverture'=>$promo['PAGE_URL_EXTERNE']?$promo['PAGE_URL_EXTERNE_MODE_OUVERTURE']:1
                                );
                        }
                    }
                }
                if (!empty($aVehicules)) {
                    $this->assign("aData", $aData);
                    $this->assign("titre", $aData["ZONE_TITRE"]);
                    $this->assign("aVehicules", $aVehicules);
                    $this->assign("vidUrl", $_GET["vid"]);
                    $this->assign("currentUrl", $aData["PAGE_CLEAR_URL"]);
                    $this->fetch();
                }
            }
        } else {
            $aZoneParentPromotion = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateId",
                array(
                    $aData['PAGE_PARENT_ID'],
                    Pelican::$config['ZONE_TEMPLATE_ID']['LISTE_PROMOTIONS'],
                    Pelican::getPreviewVersion(),
                    $_SESSION[APP]['LANGUE_ID']
                )
            );
            $aSelectVehicules = Pelican_Cache::fetch("Frontend/Citroen/Promotion",
                array(
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    $aData['PAGE_ID'],
                    getPreviewVersion()
                )
            );

            $vehiculeActif = $aSelectVehicules[0]['IDENTIFIANT'];

            $aListePromotions = Pelican_Cache::fetch("Frontend/Citroen/MultiPromotion",
                array(
                    $aZoneParentPromotion['PAGE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    $aZoneParentPromotion["PAGE_VERSION"],
                    Pelican::$config['ZONE_TEMPLATE_ID']['LISTE_PROMOTIONS'],
                    "multiPromotion",
                    $_SESSION[APP]['SITE_ID'],
                    getPreviewVersion(),
                    Pelican::$config['TEMPLATE_PAGE']['DETAIL_PROMOTION'],
                    "Promotion"
                )
            );
            if (is_array($aListePromotions) && !empty($aListePromotions)) {
                //On va maintenant chercher les promotions rattachées ainsi que les voitures rattachées à ces dernières
                $aVehicules = array();
                for ($i = 0; $i < count($aListePromotions); $i++) {
                    //On va chercher les voitures
                    if (is_array($aListePromotions[$i]["CHILD"]) && !empty($aListePromotions[$i]["CHILD"])) {
                        if(in_array($vehiculeActif,$aListePromotions[$i]["CHILD_LIST"])){
                            foreach ($aListePromotions[$i]["CHILD"] as $promo) {
                                if($promo['IDENTIFIANT'] != $vehiculeActif){
                                    $aVehicules[$promo["VEHICULE_ID"]] = array('label'=>$promo["VEHICULE_LABEL"],
                                        'url'=>$promo["PAGE_CLEAR_URL"],
                                        'mode_ouverture'=>$promo['PAGE_URL_EXTERNE']?$promo['PAGE_URL_EXTERNE_MODE_OUVERTURE']:1
                                        );
                                }else{
                                    $aCurrent = array('label'=>$promo["VEHICULE_LABEL"],
                                        'url'=>$promo["PAGE_CLEAR_URL"],
                                        'mode_ouverture'=>$promo['PAGE_URL_EXTERNE']?$promo['PAGE_URL_EXTERNE_MODE_OUVERTURE']:1
                                        );
                                }
                            }
                        }
                    }
                }
                if (!empty($aVehicules)) {
                    $this->assign("aData", $aData);
                    $this->assign("titre", $aData["ZONE_TITRE"]);
                    $this->assign("aVehicules", $aVehicules);
                    $this->assign("aCurrent", $aCurrent);
                    $this->fetch();
                }
            }
        }
    }

}

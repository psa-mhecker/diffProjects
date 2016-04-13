<?php

class Layout_Citroen_OtherPromotions_Controller extends Pelican_Controller_Front
{

	public function indexAction()
	{
		$aData = $this->getParams();
		$aAllPromotions = explode(',', $aData["ZONE_TEXTE2"]);
		$aPromotionsToDisplay = array();
		if (is_array($aData['aIgnorePromotions']) && count($aData['aIgnorePromotions'])) {
			foreach ($aAllPromotions as $sOneFromAllPromotions) {
				if (!in_array($sOneFromAllPromotions, $aData['aIgnorePromotions'])) {
					$aPromotionsToDisplay[] = $sOneFromAllPromotions;
				}
			}
		} else {
			$sPromotionsToDisplay = $aData["ZONE_TEXTE2"];
		}
		//Attention ceci est une chaine de caractere != $aPromotionsToDisplay
		if (is_array($aPromotionsToDisplay) && count($aPromotionsToDisplay)) {
			$sPromotionsToDisplay = implode(',', $aPromotionsToDisplay);
		}
        $aSelectVehicules = Pelican_Cache::fetch("Frontend/Citroen/Promotion",
            array(
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                $aData['PAGE_ID'],
                getPreviewVersion()
            )
        );
        $vehiculeId = null;
        if(is_array($aSelectVehicules) && count($aSelectVehicules)>0){
            $vehiculeId = $aSelectVehicules[0]['VEHICULE_ID'];
        }
		$aOtherPromotions = Pelican_Cache::fetch(
				"Frontend/Citroen/OtherPromotions", array(
					$_SESSION[APP]['SITE_ID'],
					$_SESSION[APP]['LANGUE_ID'],
					$sPromotionsToDisplay,
					getPreviewVersion(),
					$vehiculeId
				)
		);
		if (is_array($aOtherPromotions) && !empty($aOtherPromotions)) {
			$this->assign("aData", $aData);
			$this->assign("titre", $aData["ZONE_TITRE"]);
			$this->assign("OtherPromotions", $aOtherPromotions);
			$this->assign("sVehicleName", $aOtherPromotions[0]["VEHICULE_LABEL"]);
			$this->fetch();
		}
	}

}

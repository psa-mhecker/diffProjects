<?php

use Citroen\GammeFinition\VehiculeGamme;

class Layout_Citroen_DisponibleSur_Controller extends Pelican_Controller_Front
{

	public function indexAction()
	{
		// Recuperation info de la page
		$aData = $this->getParams();

		// cache pour recuperer les vehicules dans la table pour le "disponible sur"
		$aVehicules = Pelican_Cache::fetch("Frontend/Citroen/VehiculeDisponibleSur", array(
			$_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $aData['ZONE_TEXTE']
		));
        if(is_array($aVehicules) && count($aVehicules)>0){
            foreach ($aVehicules as $Vehicule) {
                $aTemp = VehiculeGamme::getShowRoomVehicule(
                        $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $Vehicule['VEHICULE_ID']
                );
                if ($aTemp[0]) {
                    $aResult[] = $aTemp[0];
                }
            }
            if (is_array($aResult) && count($aResult) > 0) {
                foreach ($aResult as $key => $result) {
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
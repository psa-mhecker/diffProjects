<?php
use Citroen\GammeFinition\VehiculeGamme;
use Citroen\Financement;
require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');

class Layout_Citroen_Finitions_Controller extends Pelican_Controller_Front
{
    public function indexAction()
    {

		$aData = $this->getParams();
                $_SESSION[APP]['PAGE_GAMME_VEHICULE'] = $aData['PAGE_GAMME_VEHICULE'];
		$aLcdv6Gamme = VehiculeGamme::getLCDV6Gamme($aData['ZONE_ATTRIBUT'],$_SESSION[APP]['SITE_ID'],$_SESSION[APP]['LANGUE_ID']);
		$aTemp = VehiculeGamme::getShowRoomVehicule($_SESSION[APP]['SITE_ID'],$_SESSION[APP]['LANGUE_ID'],$aData['ZONE_ATTRIBUT']);
		if ($aTemp[0]) {
			$aVehicule = $aTemp[0];
		}
                
                $mediaDetailPush4 = Pelican_Cache::fetch("Media/Detail", array(
                        $aData["MEDIA_ID4"]
                 ));
                
                if (isset($aData["ZONE_TITRE7"]) && $aData["ZONE_TITRE7"] != "") {
                    $pagePopUp = Pelican_Cache::fetch("Frontend/Page", array(
                                $aData["ZONE_TITRE7"],
                                $aData['SITE_ID'],
                                $aData['LANGUE_ID']
                    ));
                }
            
		/*
		 *  Page globale
		 */
		$pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
			$_SESSION[APP]['SITE_ID'],
			$_SESSION[APP]['LANGUE_ID'],
			'CURRENT',
			Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
		));
		/*
		 *  Configuration
		 */
		$aConfiguration = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
			$pageGlobal['PAGE_ID'],
			Pelican::$config['ZONE_TEMPLATE_ID']['CONFIGURATION'],
			$pageGlobal['PAGE_VERSION'],
			$_SESSION[APP]['LANGUE_ID']
		));

		$sDevise = $aConfiguration['ZONE_TITRE2'];

		//Récupération des finitions
		$aFinitions = Pelican_Cache::fetch("Frontend/Citroen/Finitions", array(
            $aLcdv6Gamme,
			$_SESSION[APP]['SITE_ID'],
			$_SESSION[APP]['LANGUE_ID']
        ));



		$iAffichPrixCredit = Frontoffice_Zone_Helper::getAffichePrixCredit();
                if (isset($aVehicule['VEHICULE']['VEHICULE_DISPLAY_CREDIT_PRICE']) && $aVehicule['VEHICULE']['VEHICULE_DISPLAY_CREDIT_PRICE'] == 1 && ($iAffichPrixCredit == 2 || $iAffichPrixCredit == 1 )) {
                    $hasCreditPrice = true;
                }
        
		if(is_array($aFinitions) && count($aFinitions)>0 && ($iAffichPrixCredit == 2 || ($iAffichPrixCredit == 1 && ($aData['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'] || $aData['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_INTERNE'])))){
			foreach($aFinitions as $key=>$finition){
		
			$numPrix = str_replace(",00 €", "", $finition["PRIMARY_DISPLAY_PRICE"]);
			$numPrix = str_replace(" ", "", $numPrix);

			$sPrixTTC = $numPrix;
			$sPrixHT = $numPrix;

			$sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
			$sCodePays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));
			$sCodeLangue = $sLangue . "-" . strtolower($sCodePays);

			
				$aFinitions[$key]['CREDIT'] = Financement::getCreditPrice($sCodePays,$sCodeLangue,Pelican::$config['DEVISE'][trim($sDevise)], $finition['LCDV6'],$aVersion['LABEL'],'' ,$finition['GAMME'],$sPrixHT,$sPrixTTC);
				$aFinitions[$key]['MENTIONS_LEGALES'] = Financement::getCreditPriceML($sCodePays,$sCodeLangue,Pelican::$config['DEVISE'][trim($sDevise)], $finition['LCDV6'],$finition['MODEL_LABEL'],'' ,$finition['GAMME'],$sPrixHT,$sPrixTTC);
			
			if(!$aFinitions[$key]['CREDIT'] && !$aFinitions[$key]['MENTIONS_LEGALES'])
			{
				if($aVehicule['VEHICULE']['VEHICULE_CREDIT_PRICE_NEXT_RENT'])
				{
				$aFinitions[$key]['CREDIT']['PRIX'] = $aVehicule['VEHICULE']['VEHICULE_CREDIT_PRICE_NEXT_RENT'];
				$aFinitions[$key]['MENTIONS_LEGALES']['HTML'] = $aVehicule['VEHICULE']['VEHICULE_CREDIT_PRICE_NEXT_RENT_LEGAL_MENTION'];
				}
			}



			}
		}

                Frontoffice_Vehicule_Helper::cleanVehiculeCompInSession($_SESSION[APP]['SITE_ID'],$_SESSION[APP]['LANGUE_ID']);
                $_showRoomComparateur['init']=true;
                for($i=0;$i<2;$i++){
                    $_showRoomComparateur['vehicules'][]=array(
                        'id'=>$aVehicule['VEHICULE']['VEHICULE_ID'],
                        'lcdv6'=>$aFinitions[$i]['LCDV6'],
                        'finition_code'=>$aFinitions[$i]['FINITION_CODE']
                    );
                    //Frontoffice_Vehicule_Helper::putVehiculeCompInSession($_SESSION[APP]['SITE_ID'],$_SESSION[APP]['LANGUE_ID'],$aVehicule['VEHICULE']['VEHICULE_ID'], $aFinitions[$i]['LCDV6'], $aFinitions[$i]['FINITION_CODE']);
                }
				
				$aPageShowroomColor = Frontoffice_Showroom_Helper::getShowroomColor($aData["PAGE_ID"],$aData['LANGUE_ID'],Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']);

				if(!empty($aPageShowroomColor['PAGE_PRIMARY_COLOR']) &&  !empty($aPageShowroomColor['PAGE_SECOND_COLOR'])){
					$aData['PRIMARY_COLOR'] = $aPageShowroomColor['PAGE_PRIMARY_COLOR'];
					$aData['SECOND_COLOR']  = $aPageShowroomColor['PAGE_SECOND_COLOR'];
				}

                
                $this->assign('hasCreditPrice', $hasCreditPrice);
		$this->assign("aFinitions", $aFinitions);
		$this->assign("aData", $aData);
		$this->assign("aLcdv6Gamme", $aLcdv6Gamme);
		$this->assign("aVehicule", $aVehicule);
		$this->assign("json_showroom_comparateur", json_encode($_showRoomComparateur));
                
                //mentions légales
                $this->assign('urlPopInMention', $pagePopUp["PAGE_CLEAR_URL"]);
                $this->assign('titlePopInMention', $pagePopUp["PAGE_TITLE"]);
                $this->assign('MEDIA_PATH4', $mediaDetailPush4["MEDIA_PATH"]);
                $this->assign('MEDIA_TITLE4', $mediaDetailPush4["MEDIA_TITLE"]);
            
                 $this->fetch();

    }

	public function addToCompareAction(){
		include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Vehicule.php');
		$aData = $this->getParams();
		$bReturn = Frontoffice_Vehicule_Helper::putVehiculeCompInSession($_SESSION[APP]['SITE_ID'],$_SESSION[APP]['LANGUE_ID'],$aData['vehiculeId'], null, $aData['finitionId']);
		$sAlerte = ($bReturn == true) ? t('ADD_COMPARATEUR_OK') : t('ADD_COMPARATEUR_KO');
		$this->getRequest()->addResponseCommand('script', array(
					 'value' => "promptPop('".$sAlerte."');"
				));
	}

	public function toggleFinitionsAction()
    {
		$aData = $this->getParams();
		
		$iPageId = $aData['form_page_pid'];
	
		if(intval($iPageId)>0 ){
			$aPageShowroomColor = Frontoffice_Showroom_Helper::getShowroomColor($iPageId,$_SESSION[APP]['LANGUE_ID'],Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']);
			
			if(!empty($aPageShowroomColor['PAGE_PRIMARY_COLOR']) &&  !empty($aPageShowroomColor['PAGE_SECOND_COLOR'])){
				$aData['PRIMARY_COLOR'] = $aPageShowroomColor['PAGE_PRIMARY_COLOR'];
				$aData['SECOND_COLOR']  = $aPageShowroomColor['PAGE_SECOND_COLOR'];
			}

		}

		$aLcdv6Gamme = array('LCDV6'=>$aData['lcvd6'], 'GAMME'=>$aData['gamme']);

		$aEquipements = VehiculeGamme::getEquipementDispo($aLcdv6Gamme,$_SESSION[APP]['SITE_ID'],$_SESSION[APP]['LANGUE_ID'],$_SESSION[APP]['PAGE_GAMME_VEHICULE']);
		$aEngineList = VehiculeGamme::getEngineList($aData['finition'],$aData['lcvd6'],$_SESSION[APP]['SITE_ID'],$_SESSION[APP]['LANGUE_ID']);
		$this->assign("aEquipements", $aEquipements[$aData['finition']]);
		$this->assign("aEngineList", $aEngineList);
		$this->assign("aData", $aData);

		if(isset($_SESSION[APP]['FINITIONS']['CARACTERISTIQUES'][$aData['finition']])){
			$engineCode = $_SESSION[APP]['FINITIONS']['CARACTERISTIQUES'][$aData['finition']];
			$aCaracteristiques = VehiculeGamme::getCaracteristiques( $engineCode, $aData['lcvd6'], $aData['gamme'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $aData['finition']);
			$this->assign("aCaracteristiques", $aCaracteristiques);
			$this->assign("engineCode", $engineCode);
		}
        $this->fetch();
		$this->getRequest()->addResponseCommand('assign', array(
			'id' => 'itemTpl',
			'attr' => 'innerHTML',
			'value' => $this->getResponse()
		));

    }

	public function caracteristiquesFinitionsAction()
    {
		$aData = $this->getParams();

		$_SESSION[APP]['FINITIONS']['CARACTERISTIQUES'][$aData['finition']] = $aData['engine_code'];

		$aCaracteristiques = VehiculeGamme::getCaracteristiques($aData['engine_code'], $aData['lcvd6'], $aData['gamme'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $aData['finition']);
		$this->assign("aCaracteristiques", $aCaracteristiques);
		$this->assign("aData", $aData);
        $this->fetch();

		$this->getRequest()->addResponseCommand('assign', array(
			'id' => 'caracteristiques',
			'attr' => 'innerHTML',
			'value' => $this->getResponse()
		));
    }

}
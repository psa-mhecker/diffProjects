<?php
class Layout_Citroen_ContenuTexteCTA_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $aParams = $this->getParams();

        if($aParams['TEMPLATE_PAGE_ID'] != Pelican::$config['TEMPLATE_PAGE']['MASTER_PAGE_VEHICULES_N2']) {
            if ($aParams['isHerite']) {
                $aParams["pid"] = $aParams["PAGE_ID"];
            }
			
			/*temporaire*/
			$iPageId = $aParams["pid"];
			if(!empty($iPageId)){
				$aPageShowroomColor = Frontoffice_Showroom_Helper::getShowroomColor((int)$iPageId,$_SESSION[APP]['LANGUE_ID'],Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']);
				if(!empty($aPageShowroomColor['PAGE_PRIMARY_COLOR']) &&  !empty($aPageShowroomColor['PAGE_SECOND_COLOR'])){
					$aParams['PRIMARY_COLOR'] = $aPageShowroomColor['PAGE_PRIMARY_COLOR'];
					$aParams['SECOND_COLOR']  = $aPageShowroomColor['PAGE_SECOND_COLOR'];
				}else{
					$aColors = Frontoffice_Showroom_Helper::getShowroomColor((int)$iPageId,$_SESSION[APP]['LANGUE_ID'],true);
					 $aParams['PRIMARY_COLOR'] = $aColors['PAGE_PRIMARY_COLOR'];
					 $aParams['SECOND_COLOR']  = $aColors['PAGE_SECOND_COLOR'];
				}
			}
			/*temporaire*/
			
			
            $this->assign("aParams", $aParams);
            $this->assign("session", $_SESSION[APP]);

            $aCTA = Citroen_Cache::fetchProfiling($aParams['ZONE_PERSO'], "Frontend/Citroen/ZoneMulti", array(
                $aParams["pid"],
                $aParams['LANGUE_ID'],
                Pelican::getPreviewVersion(),
                $aParams['ZONE_TEMPLATE_ID'],
                'CTAFORM',
                $aParams['AREA_ID'],
                $aParams['ZONE_ORDER']
            ));
            if (is_array($aCTA) && !empty($aCTA)) {
                foreach ($aCTA as $key => $multi) {
                    if (isset($multi['OUTIL']) && !empty($multi['OUTIL'])) {
                        $aParams['CTA'] = $multi['OUTIL'];
                        $aParams['CTA']['COLOR'] = 'cta';
//
                        $aParams['CTA']['ADD_CSS'] = 'buttonTransversalInvert';
                        $aCTA[$key]['OUTIL'] = Pelican_Request::call('_/Layout_Citroen_CTA/', $aParams);
                    }
                }
            }
            $this->assign("aCTA", $aCTA);
        }

        $this->fetch();
    }

}
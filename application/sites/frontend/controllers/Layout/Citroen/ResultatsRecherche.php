<?php

use Citroen\Recherche;
use Citroen\GTM;

class Layout_Citroen_ResultatsRecherche_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
		$aData = $this->getParams();
		//Récupération multi de la zone : Termes
		$aMulti = Citroen_Cache::fetchProfiling($aData['ZONE_PERSO'],"Frontend/Page/ZoneMulti", array(
			$aData['PAGE_ID'],
			$aData['ZONE_TEMPLATE_ID'],
			$_SESSION[APP]['SITE_ID'],
			$_SESSION[APP]['LANGUE_ID']
		));
		$aTerme =  array();
		if(is_array($aMulti) && count($aMulti)>0){
			foreach($aMulti as $terme){
				$aTerme[] = array(
					"search" => urlencode($terme['PAGE_ZONE_MULTI_LABEL']),
					"label"  => $terme['PAGE_ZONE_MULTI_LABEL']
				);
			}
		}

		$aOutilsSelected = explode('|',$aData['ZONE_TOOL']);

		//Récupération des outils
		$aOutils = Pelican_Cache::fetch("Frontend/Citroen/BarreOutils", array(
			$_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
			$aOutilsSelected
		));
		if (is_array($aOutils) && !empty($aOutils)) {
            foreach ($aOutils as $key=>$OneOutil) {
                $aData['CTA'] = $OneOutil;
                $aOutils[$key] = Pelican_Request::call('_/Layout_Citroen_CTA/',$aData);

            }
		}

		// Marquage GTM
		GTM::$dataLayer['internalSearchKeyword'] = $aData['search'];
		GTM::$dataLayer['internalSearchType'] = Pelican::$config['GTM']['internalSearchType'];

		//Recherche
		$iStart = 0;
		$nbElmt = Pelican::$config['GSA']['NOMBRE_RESULTAT'];
		$iCount = $iStart+$nbElmt;
		$aSearch = array();
		$nbResults = '';
		$sResults = '';
		if($aData['search'] != ''){
			$aResults = self::_getResults($aData['search'],$iStart);
            if(!empty($aResults['RESULTS'])){
                $aSearch['NB_RESULTS'] = $aResults['NB_RESULTS'];
                $aSearch['RESULTS'] = array_slice($aResults['RESULTS'],$iStart,$nbElmt);
            }
		}
		$nbResults = ($aSearch['NB_RESULTS'] != '') ? $aSearch['NB_RESULTS'] : '0';
		$sResults = str_replace(array('##nb##','##search##'), array($nbResults,'<span>'.$aData['search'].'</span>'), t('RESULTS_GSA'));
		$sResultsMob = str_replace(array('##nb##','##search##'), array($nbResults,'<span class="red">'.$aData['search'].'</span>'), t('RESULTS_GSA'));

		$aPagePlanDuSite = Pelican_Cache::fetch("Frontend/Page/Template", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				Pelican::$config['TEMPLATE_PAGE']['PLAN_DU_SITE']));
		$this->assign('sURLPagePlanDuSite', $aPagePlanDuSite['PAGE_CLEAR_URL']);

		$this->assign("aData", $aData);
		$this->assign("aTerme", $aTerme);
		$this->assign("aOutils", $aOutils);
		$this->assign("search", $aData['search']);
		$this->assign("sResults", $sResults);
		$this->assign("sResultsMob", $sResultsMob);
		$this->assign("aSearch", $aSearch['RESULTS']);
		$this->assign("iStart", $iStart);
		$this->assign("iCount", $iCount);
		$this->assign("nbElmt", $nbElmt);
		$this->assign("nbResults", $nbResults);
        $this->fetch();
    }
	public function suggestAction()
    {
		$aData = $this->getParams();
		//$suggestJson = Recherche::suggest($aData['term']);
        $suggestJson = Pelican_Cache::fetch("Frontend/Citroen/ResultatsRecherche/Suggest", array(
			$aData['term'],
            strtoupper($_SESSION[APP]['LANGUE_CODE']) . "-" . $_SESSION[APP]['CODE_PAYS']. "_" . (self::isMobile()?"M":"W")
		));
		echo json_encode($suggestJson);
    }
	public function moreResultsAction()
    {
		$aData = $this->getParams();
		$nbElmt = Pelican::$config['GSA']['NOMBRE_RESULTAT'];
		$iStart = $aData['iStart'];

		$aSearch = self::_getResults($aData['search'],$aData['iStart']);

		$this->assign("aSearch", $aSearch['RESULTS']);
		$this->fetch();
		$sTypeAdd = 'append';
		$this->getRequest()->addResponseCommand($sTypeAdd, array(
			'id' => 'allResults',
			'attr' => 'innerHTML',
			'value' => $this->getResponse()
		));
		$iCount = $iStart + $nbElmt;

		if(count($aSearch['RESULTS']) < $nbElmt || $aSearch['NB_RESULTS'] <= $iCount){
			$this->getRequest()->addResponseCommand('script', array(
				'value' => "$('#seeMoreResults a').css('display','none');"
			));
		}else{
			$this->getRequest()->addResponseCommand('script', array(
				 'value' => "$('#iCount').val(".$iCount.");"
			));
		}

    }
    private static function _getResults($sQuery, $iStart){
        $aResults = Pelican_Cache::fetch("Frontend/Citroen/ResultatsRecherche", array(
                    $sQuery,
                    $iStart,
        strtoupper($_SESSION[APP]['LANGUE_CODE']) . "-" . $_SESSION[APP]['CODE_PAYS']. "_" . (self::isMobile()?"M":"W"),
                    date('YmdHi')
            ));
        if(is_array($aResults)){
            foreach($aResults as &$result){
                $tmp = Pelican_Cache::fetch("Frontend/Citroen/PageByUrlClear", array(
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    Pelican::getPreviewVersion(),
                    $result['url']
                ));
				if($tmp!=null){ //demandé par Romain pour le resultat de recherche
					$result['mode_ouverture'] = $tmp['PAGE_URL_EXTERNE']?$tmp['PAGE_URL_EXTERNE_MODE_OUVERTURE']:1;
				}
            }
        }
        return $aResults;
    }

}
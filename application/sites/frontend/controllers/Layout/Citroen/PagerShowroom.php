<?php
require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');
class Layout_Citroen_PagerShowroom_Controller extends Pelican_Controller_Front {

    public function indexAction() {
        $aParams = $this->getParams();
	
		

		
	
        $this->assign("aData", $aParams);

        $aVehicule = Pelican_Cache::fetch("Frontend/Citroen/VehiculeById", array(
                    $aParams['PAGE_VEHICULE'],
                    $aParams['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID']
        ));
        if (!isset($aVehicule)) {
            $aName = explode("|", $aParams['PAGE_LIBPATH']);
            $aName2 = explode("#", $aName[4]);
            $aVehicule['VEHICULE_LABEL'] = $aName2[0];
        }
        $this->assign('aVehicule', $aVehicule);

        $pidCourant = $aParams['pid'];
        $this->assign("pidCourant", $pidCourant);
        $pageTitle = $aParams['PAGE_TITLE'];
        $this->assign("pageTitle", $pageTitle);

        $aPager = Pelican_Cache::fetch("Frontend/Citroen/StickyBar", array(
                    $pidCourant,
                    $aParams['PAGE_PARENT_ID'],
                    $aParams['TEMPLATE_PAGE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    Pelican::getPreviewVersion()
        ));

        //unset($aPager[0]);
    if(is_array($aPager) && count($aPager)>0)
    {
        foreach ($aPager as $key => $pager) {
            if ($pager['PAGE_ID'] == $pidCourant) {
                $keyPagerPageCourante = $key;
                break;
            }
        }
    }    

        $lastKey = count($aPager) - 1;

        if ($keyPagerPageCourante == 0) {
            $next = $aPager[$keyPagerPageCourante + 1];
            $prev = false;
        } elseif ($keyPagerPageCourante == $lastKey) {
            $next = false;
            $prev = $aPager[$lastKey - 1];
        } else {
            $next = $aPager[$keyPagerPageCourante + 1];
            $prev = $aPager[$keyPagerPageCourante - 1];
        }

        if (isset($next) && !empty($next)) {
            $this->assign("next", $next);
        }
        if (isset($prev) && !empty($prev)) {
            if ($this->isMobile()) {
                if ($prev['PAGE_TITLE'] == t('MODELE_EN_DETAIL')) {
                    $prev['PAGE_CLEAR_URL'] = $prev['PAGE_CLEAR_URL'] . "?sticky#";
                }
            }
            $this->assign("prev", $prev);
        }

        $this->fetch();
    }

}
<?php

class Layout_Citroen_404_Controller extends Pelican_Controller_Front {

    public function indexAction() {
        $aData = $this->getParams();
//        $aData['ZONE_TEXTE'] = substr($aData['ZONE_TEXTE'], 0, -4); 
//        $aData['ZONE_TEXTE'] = substr($aData['ZONE_TEXTE'], 3);
        $recherche = Pelican_Cache::fetch("Frontend/Page/Template", array(
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    Pelican::getPreviewVersion(),
                    Pelican::$config['TEMPLATE_PAGE']['RESULTATS_RECHERCHE']
        ));
        $this->assign("recherche", $recherche);

        $aPagePlanDuSite = Pelican_Cache::fetch("Frontend/Page/Template", array(
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    Pelican::getPreviewVersion(),
                    Pelican::$config['TEMPLATE_PAGE']['PLAN_DU_SITE']));
        $this->assign('sURLPagePlanDuSite', $aPagePlanDuSite['PAGE_CLEAR_URL']);


        
        $this->assign('aData', $aData);
        $this->fetch();
    }

}

?>

<?php
class Layout_Citroen_Global_PlanSiteAutomatique_Controller extends Pelican_Controller_Front
{
    public function indexAction()
    {
        $aData = $this->getParams();
        $this->assign("aData",$aData);

        $aLangue = Pelican_Cache::fetch("Frontend/Citroen/SiteLangues", array(
            $_SESSION[APP]['SITE_ID']
        ));
		$aTraduction = Pelican_Cache::fetch("TranslationByLabelId", array('CHOISISSEZ_VOTRE_LANGUE',$_SESSION[APP]['SITE_ID'],'FRONT'));
        foreach ($aLangue as $key => $value) {
            $aPlanSite[$key] = Pelican_Cache::fetch("Frontend/Citroen/Navigation", array(
                $_SESSION[APP]['SITE_ID'],
                $value['LANGUE_ID'],
                "PAGE_DISPLAY",
                 "PLAN_SITE"
            ));
            $aTrad[$key] = $aTraduction[$value['LANGUE_ID']];
            
            // Url de la home
            $aPage = Pelican_Cache::fetch("Frontend/Page", array($_SESSION[APP]["HOME_PAGE_ID"], $_SESSION[APP]['SITE_ID'], $value['LANGUE_ID'], Pelican::getPreviewVersion()));
            $home[$key] = $aPage['PAGE_CLEAR_URL'];
        }

        $this->assign("bTplPreHome", (in_array($aData['TEMPLATE_PAGE_ID'], array(Pelican::$config['TEMPLATE_PRE_HOME'])))?1:0);
        $this->assign('aLangue', $aLangue);
        $this->assign('aTrad', $aTrad);
        $this->assign('aPlanSite', $aPlanSite);
        $this->assign('home', $home);

        $this->fetch();
    }
}

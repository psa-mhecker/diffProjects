<?php
class Layout_Citroen_PlanSiteAuto_Controller extends Pelican_Controller_Front
{
    public function indexAction()
    {
        $aPlanSite = Pelican_Cache::fetch("Frontend/Citroen/Navigation", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            "PAGE_DISPLAY",
            "PLAN_SITE"
        ));

        $this->assign('aPlanSite', $aPlanSite);
        $this->assign("aData",$this->getParams());
        $this->fetch();
    }
}
?>

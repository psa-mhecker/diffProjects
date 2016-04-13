<?php
class Layout_Citroen_Header_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $this->setParam('ZONE_TITRE', 'titre');
        $this->assign('data', $this->getParams());
        
        $this->assign('Logo', Pelican_Request::call('Layout_Citroen_Global_Logo'));
        $this->assign('QuickAccess', Pelican_Request::call('Layout_Citroen_Global_QuickAccess'));
        $this->assign('SearchZone', Pelican_Request::call('Layout_Citroen_Global_SearchZone'));
        $this->assign('UserCart', Pelican_Request::call('Layout_Citroen_Global_UserCart'));
        $this->assign('nav', Pelican_Request::call('Layout_Citroen_Global_Navigation'));
        $this->assign('Helper', Pelican_Request::call('Layout_Citroen_Global_Helper'));
                
        $this->fetch();
    }
}
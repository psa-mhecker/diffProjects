<?php
class Layout_Citroen_Home_Promotion_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $this->setParam('ZONE_TITRE', 'titre');
        $this->assign('data', $this->getParams());
        $this->fetch();
    }
}
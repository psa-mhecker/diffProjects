<?php
class Layout_Service_Twitter_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $this->setParam('ZONE_TITRE', 'titre');
        $this->assign('data', $this->getParams());
        $this->fetch();
    }
}
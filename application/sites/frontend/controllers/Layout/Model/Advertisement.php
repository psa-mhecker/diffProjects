<?php

class Layout_Model_Advertisement_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $this->setParam('ZONE_TITRE',t('Pub'));
        $this->model();
        $this->fetch();
    }
}

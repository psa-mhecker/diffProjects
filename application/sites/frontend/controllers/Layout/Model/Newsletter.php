<?php

class Layout_Model_Newsletter_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $this->setParam('ZONE_TITRE', t('Newsletter_subscription'));
        $this->model();
        $this->fetch();
    }
}

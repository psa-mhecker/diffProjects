<?php

class Layout_Model_Polls_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $this->setParam('ZONE_TITRE', t('Poll'));
        $this->model();
        $this->fetch();
    }
}
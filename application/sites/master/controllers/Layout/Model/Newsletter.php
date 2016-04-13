<?php

class Layout_Model_Newsletter_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $this->setParam('ZONE_TITRE', 'Inscription à la newsletter');
        $this->model();
        $this->fetch();
    }
}

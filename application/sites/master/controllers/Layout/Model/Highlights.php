<?php

class Layout_Model_Highlights_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $this->setParam('ZONE_TITRE', 'Les derniers articles');
        $this->model();
        $this->fetch();
    }
}
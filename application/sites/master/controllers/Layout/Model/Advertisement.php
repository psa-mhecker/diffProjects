<?php

class Layout_Model_Advertisement_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $this->setParam('ZONE_TITRE','Pub');
        $this->model();
        $this->fetch();
    }
}

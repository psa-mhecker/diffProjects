<?php

class Layout_Model_Text_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $this->model();
        $this->fetch();
    }
}

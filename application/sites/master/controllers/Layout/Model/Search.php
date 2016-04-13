<?php

class Layout_Model_Search_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $this->setParam('ZONE_TITRE', t('POPUP_SEARCH_TITLE'));
        $this->model();
        $this->fetch();
    }
}

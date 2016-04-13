<?php

class Layout_Model_Recentcomment_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $this->setParam('COMMENTS', Pelican_Cache::fetch('Comment/Last', array(
            1, 
            5
        )));
        $this->setParam('ZONE_TITRE', t('Recent_comment'));
        $this->model();
        $this->fetch();
    }
}
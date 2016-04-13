<?php
include_once(Pelican::$config['APPLICATION_CONTROLLERS'] . '/Content.php');

class Content_Blog_Controller extends Content_Controller
{

    public function indexAction()
    {
        parent::indexAction();
    }
}

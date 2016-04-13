<?php

require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');

class Layout_Citroen_TexteRiche_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $aData = $this->getParams();
		
        $this->assign('aData', $aData);
        $this->fetch();
    }
}
?>

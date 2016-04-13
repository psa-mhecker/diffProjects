<?php
class Layout_Citroen_DragAndDrop_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $aParams = $this->getParams();
        $this->assign("aParams", $aParams);

        $this->fetch();
    }

}
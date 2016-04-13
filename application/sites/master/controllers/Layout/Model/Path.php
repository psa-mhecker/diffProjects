<?php

class Layout_Model_Path_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $aPath = Pelican_Cache::fetch("Frontend/Page/Path", array(
            $_GET["pid"] , 
            $_SESSION[APP]['LANGUE_ID'] , 
            Pelican::getPreviewVersion()));
        
        /** Variables SMARTY */
        $this->assign("pathParentName", $aPath[0]);
        $this->assign("pathParentId", $aPath[2]);
        $this->assign("pathParentUrl", $aPath[3]);
        $this->assign("pathIndex", $aPath[4]);
        $this->assign("cid", (! empty($_GET["cid"]) ? $_GET["cid"] : ''));
        
        //        $this->assign('external', Pelican_Request::call('http_www.education.gouv.fr/pid25058/le-calendrier-scolaire.html'));
        

        $this->model();
        $this->fetch();
    }
}
<?php

pelican_import('Controller.Error');
pelican_import('Controller.Front');

class Error_Controller extends Pelican_Controller_Error {

    public function indexAction() {
        $head = $this->getView()->getHead();
        $this->assign('doctype', $head->getDocType());
        $this->assign('header', $head->getHeader(), false);
        $this->assign('footer', $head->getFooter(), false);
        if ($_GET['404']) {
            $this->_forward('code500');
        } else {
            $this->assign('error', $_GET["error"]);
            $this->fetch();
        }
    }

    public function code404Action() {
        $GLOBALS['__mark_code404Action'] = true; // Marquage du passage dans la 404, pour éviter d'entrer dans une boucle infinie via sendError()
        if ($_SESSION[APP]['SITE_ID'] == NULL) {
            $e = "<br/> The current hostname (" . APP . ") do not match any configured site.";
            $this->assign('footer', $e);
            $this->_forward('code500');
        } else {
            $pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
                        $_SESSION[APP]['SITE_ID'],
                        $_SESSION[APP]['LANGUE_ID'],
                        'CURRENT',
                        Pelican::$config['TEMPLATE_PAGE']['404']
            ));

            $_GET["pid"] = $pageGlobal["PAGE_ID"];
            unset($_GET["cid"]);
            unset($_GET["tpl"]);
            $this->_message = Pelican_Request::call('_/Index');

            $this->getRequest()->setStatus(404);

            $this->fetch();

            // clean /fr url
            $this->setResponse(cleanResponse($this->getResponse()));
        }
    }

    public function code500Action() {
        $head = $this->getView()->getHead();
        $head->setTitle("500");
        $this->assign('body', 'INTERNAL SERVER ERROR');
        $this->fetch();
    }

}
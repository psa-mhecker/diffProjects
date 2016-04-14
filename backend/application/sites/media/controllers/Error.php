<?php

class Error_Controller extends Pelican_Controller
{
    private $_exception;

    private static $errorMessage;

    private static $httpCode;

    public function before()
    {
        //$this->_exception = $this->_getParam ( 'error_handler' );


    /*switch ($this->_exception->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER :
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION :
                self::$httpCode = 404;
                self::$errorMessage = 'Page introuvable';
                break;
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER :
                switch (get_class ( $this->_exception->exception )) {
                    case 'Zend_View_Exception' :
                        self::$httpCode = 500;
                        self::$errorMessage = 'Erreur de traitement d\'une vue';
                        break;
                    case 'Zend_Db_Exception' :
                        self::$httpCode = 503;
                        self::$errorMessage = 'Erreur de traitement dans la base de données';
                        break;
                    case 'Metier_Exception' :
                        self::$httpCode = 200;
                        self::$errorMessage = $this->_exception->exception->getMessage ();
                        break;
                    default :
                        self::$httpCode = 500;
                        self::$errorMessage = 'Erreur inconnue : ' . $this->_exception->exception->getMessage ();
                        break;
                }
                break;
        }
    */
    }

    public function indexAction()
    {
        $head = $this->getView()->getHead();
        $this->assign('doctype', $head->getDocType());
        $this->assign('header', $head->getHeader(), false);
        $this->assign('footer', $head->getFooter(), false);
        if ($_GET['404']) {
            $this->_forward('code404');
        } else {
            $this->assign('error', $_GET["error"]);
            $this->fetch();
        }
    }

    public function code404Action()
    {
        $head = $this->getView()->getHead();
        $head->setTitle("404");
        $this->assign('body', 'PAGE NOT FOUND');
        $this->getRequest()->setStatus(404);
        $this->fetch();
    }

    public function code500Action()
    {
        $head = $this->getView()->getHead();
        $head->setTitle("500");
        $this->assign('body', 'INTERNAL SERVER ERROR');
        $this->fetch();
    }
}

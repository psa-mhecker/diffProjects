<?php

class Layout_Model_Login_Controller extends Pelican_Controller_Front
{

    public function indexAction ()
    {
        pelican_import('User.Subscriber');
        $oUser = Pelican_Factory::getUser('Subscriber');
        
        if (!empty($_POST['logout'])) {
            $oUser->logout();
         } else {
            if ($oUser->isLoggedIn()) {
                $this->assign('LOGIN_USER', $oUser->get('id'));
                $this->assign('LOGIN_USER_NAME', $oUser->get('SUBSCRIBER_FIRSTNAME'));
            } else {
                if (!empty($_POST['LOGIN_USER']) || !empty($_POST['LOGIN_PASSWORD'])) {
                    $oAuthResult = $oUser->login($_POST['LOGIN_USER'], $_POST['LOGIN_PASSWORD']);
                    //$_SESSION[APP][Pelican::$config["AUTH_ERROR_SESSION"]] = $oAuthResult->getMessages();
                    $_COOKIE["PHPSESSID"] = Zend_Session::getId();
                    Zend_Session::writeClose(true);
                    if ($oUser->isLoggedIn()) {
                        header('Location: ' . $_SERVER['HTTP_REFERER']);
                    }
                }
            }
        }
        $this->setParam('ZONE_TITRE', t('Authentification'));
        $this->model();
        $this->fetch();
    }
}
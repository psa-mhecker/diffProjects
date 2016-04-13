<?
pelican_import('Security');

class Layout_Subscription_Step1_Controller extends Pelican_Controller_Front
{

    protected $defaultValue2 = true;

    protected $messageConfirmation2 = "";

    public function before ()
    {
        $_SESSION[APP][Pelican::$config["SITE"]["ID"]]["INSCRIPTION_MAIL_ENVOYE"] = 0;
        if ($_GET['confirm']) {
            $this->_forward('confirm');
        }
        parent::before();
    }

    public function indexAction ()
    {
    	$msgSendInscriptionConfirmation = 0;
        /* 	On verifie que le code de securité a ete saisi correctement à la sauvegarde de la 1ère étape */
        //if (isset($_POST["save_etp1"]) && ($_POST["save_etp1"]) && $_SESSION[APP][Pelican::$config["SITE"]["ID"]]["INSCRIPTION_MAIL_ENVOYE"] != 1) {
            /*if (! Pelican_Security::checkCaptcha('RECAPTCHA')) {
                $message .= t('PEL.INSCRIPTION.ERREUR_CAPTCHA');
                $this->assign("message", Pelican_Security::escapeXSS($message));
                //$view->assign("POST", $_POST);
                $defaultValue = false;
            } else {*/
                /* passage à l'étape suivante ou enregistrement de l'inscription */
                require_once (pelican_path('User.Subscriber'));
                $subscriber = new Pelican_User_Subscriber();
                
                if (! $subscriber->subscriber_exist_by_mail($_POST["email"], $_SESSION[APP]['SITE_ID'])) {
                    
                    $subscriber->setFirstname($_POST["firstname"]);
                    $subscriber->setLastname($_POST["lastname"]);
                    $subscriber->setNickname($_POST["nickname"]);
                    $subscriber->setEmail($_POST["email"]);
                    $subscriber->setPassword($_POST["password"]);
                    
                    if ($subscriber->save()) {
                        /* envoi d'un mail de confirmation d'inscription */
                        $getParam = "confirm=" . $subscriber->getPassword() . "&email=" . $_POST["email"];
                        $aParam = array(
                            $_SESSION[APP]['SITE_ID'] , 
                            $_SESSION[APP]['LANGUE_ID'] , 
                            Pelican::getPreviewVersion() , 
                            Pelican::$config["TEMPLATE_PAGE_ID"]["INSCRIPTION"]
                        );
                        $urlInscription = Pelican_Cache::fetch("Frontend/Page/Template", $aParam);
                        $urlInscription = Pelican::$config["DOCUMENT_HTTP"] . $urlInscription["PAGE_CLEAR_URL"] . "?" . $getParam;
                        
                        //$returnMailInscription = Pelican_User_Pelican_User_Subscriber::subscriber_send_inscription_confirm($subscriber->getEmail(), $subscriber->getFirstname(), $subscriber->getLastname(), $urlInscription, Pelican::$config["SITE"]["ID"]);
                        $returnMailInscription = 1;
                        
                        if ($returnMailInscription == 1) {
                            $msgSendInscriptionConfirmation = 1;
                            $_SESSION[APP][Pelican::$config["SITE"]["ID"]]["INSCRIPTION_MAIL_ENVOYE"] = 1;
                        } else 
                            if ($returnMailInscription == 2) {
                                /* pas de Pelican_Security_Password associé */
                                $defaultValue = false;
                            } else {
                                /* pb envoi de mail */
                                $defaultValue = false;
                            }
                    
                    } else {
                        /* l'enregistrement ne s'est pas effectué correctement - message d'erreur??? - on revient sur la page avec les infos????*/
                        $defaultValue = false;
                    }
                } else {

                }
            //}
       // } else {
            $msgSendInscriptionConfirmation = 0;
        //}
        
        $this->assign("msgSendInscriptionConfirmation", $msgSendInscriptionConfirmation);
        if ($message) {

        }
        
        $security = Pelican_Factory::getInstance('Security');
        $options['lang'] = 'fr';
        $options['theme'] = 'white';
        $captcha = $security->inputCaptcha('test', 'RECAPTCHA', $options);
        $this->assign("captcha", $captcha, false);
        
        if ($defaultValue) {
            $this->assign("post_Lastname", "");
            $this->assign("post_Firstname", "");
            $this->assign("post_Nickname", "");
            $this->assign("post_Email", "");
            $this->assign("post_EmailConfirm", "");
        } else {
            $this->assign("post_Lastname", Pelican_Security::escapeXSS($_POST["lastname"]));
            $this->assign("post_Firstname", Pelican_Security::escapeXSS($_POST["firstname"]));
            $this->assign("post_Nickname", Pelican_Security::escapeXSS($_POST["nickname"]));
            $this->assign("post_Email", Pelican_Security::escapeXSS($_POST["email"]));
            $this->assign("post_EmailConfirm", Pelican_Security::escapeXSS($_POST["emailConfirm"]));
        }
        $this->fetch();
    }

    public function confirmAction ()
    {
        /* confirmation d'inscription */
        require_once (pelican_path('User.Subscriber'));
        $subscriber = new Pelican_User_Subscriber();
        /* on vérifie que l'email et le mot correspondent a un Pelican_User_Subscriber qui existe mais qui n'a pas encore confirmé */
        $id = Pelican_User_Pelican_User_Subscriber::subscriber_exist_by_mail_password($_GET["email"], $_GET["confirm"], Pelican::$config["SITE"]["ID"]);
        if ($id) {
            if (Pelican_User_Pelican_User_Subscriber::subscriber_statut($id)) {
                /* déja donné la confirmation */
                $messageConfirmation .= t('PEL.INSCRIPTION.CONFIRMATION_ERREUR1');
            } else {
                Pelican_User_Pelican_User_Subscriber::subscriber_confirmation_inscription($_GET["email"], Pelican::$config["SITE"]["ID"]);
                $messageConfirmation .= t('PEL.INSCRIPTION.CONFIRMATION');
            }
        } else {
            /* compte n'existe pas */
            $messageConfirmation .= t('PEL.INSCRIPTION.CONFIRMATION_ERREUR2');
        }
        $view->assign("messageConfirmation", $messageConfirmation);
    }
}
<?php
/**
 * Fichier de Citroen_Youtube:.
 *
 * Classe Back-Office de contribution des comptes youtube
 */
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php';
use Itkg\Authentication\Provider\OAuth2;

class Citroen_Administration_Youtube_Controller extends Citroen_Controller
{
    //protected $administration = true;
    /* Table utilis�e */
    protected $form_name    = 'youtube';
    /* Champ Identifiant de la table */
    protected $field_id     = 'YOUTUBE_ID';
    /* Champ pour ordonner la liste */
    protected $defaultOrder = 'YOUTUBE_ID';
    /* Activation de la barre de langue */
    protected $multiLangue  = false;

    /**
     * M�thode prot�g�es d'instanciation de la propri�t� listModel.
     * La m�thode instancie listModel avec un tableau de donn�es qui sera utilis�
     * pour afficher la liste de v�hicule.
     */
    protected function setListModel()
    {
        $oConnection = Pelican_Db::getInstance();

        /* Requ�te remontant l'ensemble des v�hicules pour un site
         * et une langue donn�e.
         */
        $sSqlListModel = <<<SQL
                SELECT
                    YOUTUBE_ID,
                    COMPTES_YOUTUBE,
					EMAIL,
					CASE WHEN (REVOKE_ETAT = '1')
						THEN 'NOK'
						ELSE 'OK'
					END REVOKE_ETAT
                FROM
                    #pref#_{$this->form_name}
				WHERE SITE_ID = {$_SESSION[APP]['SITE_ID']}
                ORDER BY {$this->listOrder}
SQL;

        $this->listModel = $oConnection->queryTab($sSqlListModel);
    }

    /**
     * M�thode prot�g�es d'instanciation de la propri�t� editModel.
     * La m�thode instancie editModel avec un tableau de donn�es qui sera utilis�
     * l'instanciation de la propri�t� 'value'.
     */
    protected function setEditModel()
    {
        /* Valeurs Bind�es pour la requ�te */
        $this->aBind[':'.$this->field_id ] = (int) $this->id;
        /* Requ�te remontant les donn�es du v�hicule s�lectionn�e pour un pays
         * et une langue donn�e.
         */
        $sSqlForm = <<<SQL
                SELECT
                    *
                FROM
                    #pref#_{$this->form_name}
                WHERE
                    {$this->field_id} = :{$this->field_id}
                ORDER BY {$this->listOrder}
SQL;
        $this->editModel = $sSqlForm;
    }

    /**
     * M�thode de cr�ation de la liste des �l�ments du formulaire.
     */
    public function listAction()
    {
        parent::listAction();

        /* Initialisation de l'objet List*/
        $oTable = Pelican_Factory::getInstance('List', '', '', 0, 0, 0, 'liste');
        +
        /* Mise en place des valeurs � utiliser pour le tableau de liste */
        $oTable->setValues($this->getListModel(), $this->field_id);
        /* Cr�ation du tableau en utilisant les donn�es du setValues */
        $oTable->addColumn(t('ID'), $this->field_id, '10', 'center', '', 'tblheader', $this->field_id);
        $oTable->addColumn(t('COMPTES_YOUTUBE'), 'COMPTES_YOUTUBE', '90', 'center', '', 'tblheader', 'COMPTES_YOUTUBE');
        //$oTable->addColumn(t('EMAIL_AUTORISE'), 'EMAIL', '90', 'center', '', 'tblheader', 'EMAIL');
        $oTable->addColumn(t('REVOKE_ETAT'), 'REVOKE_ETAT', '90', 'center', '', 'tblheader', 'REVOKE_ETAT');

        $oTable->addInput(t('FORM_BUTTON_EDIT'), 'button', array('id' => $this->field_id), 'center');
        $oTable->addInput(t('POPUP_LABEL_DEL'), 'button', array('id' => $this->field_id, '' => 'readO=true'), 'center');

        /* Affichage du tableau */
        $this->setResponse($oTable->getTable());
    }

    /**
     * Cr�ation du formulaire de contribution.
     */
    public function editAction()
    {
        parent::editAction();
        unset($_SESSION['php-oauth-client']);

        /* Initialisation du formulaire */
        $sForm = $this->startStandardForm();
        $this->oForm->bDirectOutput = false;
        /*$etat	=	'OK';
        if( $this->values['REVOKE_ETAT'] == '1'){
                $etat	=	'NOK';
        }*/

        //$sForm .= $this->oForm->createLabel(t('REVOKE_ETAT'), $etat);

        $sForm .= $this->oForm->createInput('COMPTES_YOUTUBE', t('COMPTES_YOUTUBE'), 255, '', true, $this->values['COMPTES_YOUTUBE'], $this->readO, 44);
        if ($_GET['erreur']) {
            $sForm .= $this->oForm->createLabel(t('CE_COMPTE_EXISTE_DEJA'), '');
        }

        $oauthPopupRevoke    =    '/_/Citroen_Administration_Youtube/revokeTokenOAuth2?id='.$_REQUEST['id'];
        $oauthPopupPermission    =    '/_/Citroen_Administration_Youtube/getOAuth2?id='.$_REQUEST['id'];
        if ($_REQUEST['id'] != '-2') {
            $email  = $this->getEmailCompteYoutube();
            $sForm .= $this->oForm->createLabel(t('COMPTE_GMAIL_AUTORISE'), $email);
            $sForm .= '</br>';
            $sForm .= $this->oForm->createButton(t('REVOKE'), t('REVOKE'), "window.open('".$oauthPopupRevoke."', '','width=500,height=500');");
            $sForm .= '</br>';
            $sForm .= '</br>';
            $sForm .= $this->oForm->createButton(t('GENERER_PERMISSION_AVEC_COMPTE_GOOGLE'), t('GENERER_PERMISSION_AVEC_COMPTE_GOOGLE'), "window.open('".$oauthPopupPermission."', '','width=500,height=500');");
            $sForm .= '</br>';
            $sForm .= '</br>';
        }

        /* Affichage du formulaire */
        $sForm .= $this->stopStandardForm();
        $sFinalForm = formToString($this->oForm, $sForm);
        $this->setResponse($sFinalForm);
    }

    /**
     * Cr�ation d'un compte.
     */
    public function saveAction()
    {
        Pelican_Db::$values ['REVOKE_ETAT']    =    '1';

        if ($this->isEmailExist(Pelican_Db::$values['EMAIL'])) {
            Pelican_Db::$values['form_retour']    = '/_/Index/child?tid=330&tc=&view=O_1&id='.Pelican_Db::$values['YOUTUBE_ID'].'&erreur=true';
        } else {
            parent::saveAction();
        }

        if (Pelican_Db::$values['form_action'] == 'UPD') {
        }
    }

    public function revokeTokenOAuth2Action()
    {
        // R�cup�ration de l'id du compte youtube
        $youtubeId        =    $_REQUEST['id'];

        // R�cup�ration des donn�es du compte youtube
        $aDataCompteYoutube    =    $this->getRefreshTokenBddByYoutubeId($youtubeId);

        // Connexion oauth pour acceder � l'api google plus
        $oauth = new OAuth2();
        $oauth->setParameters(Itkg::$config['ITKG_APIS_GOOGLE_PLUS_V1']['authentication_provider']['PARAMETERS']);
        $oauth->setIsRedirect(true);
        $oauth->setIsAuthenticateRefresh(false);
        $oAuthGooglePlus    =    $oauth->authenticate();

        // appel de la m�thode userInfos de l'api google plus pour r�cup�rer l'email du compte et v�rifier son autorisation.
        if ($oAuthGooglePlus) {
            unset(Itkg::$config['ITKG_APIS_GOOGLE_PLUS_V1']['authentication_provider']);
            $oService = Itkg\Service\Factory::getService('ITKG_APIS_GOOGLE_PLUS_V1');
            $aDatas = array('access_token' => $oAuthGooglePlus->getAccessToken());
            $oResponse = $oService->call('userInfos', $aDatas);
        }
        //Si l'email du compte gmail est autoris� alors on autorise la r�vocation
        $email  = $this->getEmailCompteYoutube();
        if ($oResponse->emails[0]->value == $email) {
            if (!$_SESSION['isConnect']) {
                unset($_SESSION['php-oauth-client']);
            }
            // Connexion oauth pour acceder � l'api youtube
            $oauth = new OAuth2();
            $oauth->setParameters(Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE']['authentication_provider']['PARAMETERS']);
            $oauth->setIsRedirect(true);
            $oauth->setIsAuthenticateRefresh(false);
            $_SESSION['isConnect']    =    true;
            $oRefreshTokenData        =    $oauth->authenticate();
            if ($oRefreshTokenData) {
                $aDatas = array('token' => $oRefreshTokenData->getAccessToken());
                // On unset la config pour eviter d'appeler la m�thode d'authentification oauth lors de l'appel du service
                //Car la m�thode revokeToken du service Youtube fonctionne sans une connection au service.
                unset(Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE']['authentication_provider']);

                // Si on est bien identifi� avec le bon compte on revoke le jeton youtube avec celui-ci
                Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE']['PARAMETERS']['host'] = 'https://accounts.google.com';
                Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE']['PARAMETERS']['uri'] = '/o/oauth2/revoke';
                $oService = Itkg\Service\Factory::getService('ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE');
                $oResponse = $oService->call('revokeToken', $aDatas);

                // maj de la teble psa_youtube avec le statut de revocation � true
                $oConnection                            =    Pelican_Db::getInstance();
                Pelican_Db::$values ['COMPTES_YOUTUBE']    =    $aDataCompteYoutube['COMPTES_YOUTUBE'];
                Pelican_Db::$values ['YOUTUBE_ID']        =    $youtubeId;
                Pelican_Db::$values ['REVOKE_ETAT']        =    '1';
                Pelican_Db::$values ['EMAIL']            =    $aDataCompteYoutube['EMAIL'];
                Pelican_Db::$values ['SITE_ID']            =    $aDataCompteYoutube['SITE_ID'];
                $oConnection->replaceQuery('#pref#_youtube', 'YOUTUBE_ID ='.$youtubeId);

                // On supprime toutes les sessions concernant la connexion oauth de la revocation
                unset($_SESSION[APP]["ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE"]["OAuth2"]["access_token"]);
                unset($_SESSION[APP]["refresh_token"]);
                unset($_SESSION['itkg_consumer_oauth2']);
                unset($_SESSION['php-oauth-client']);

                // Suppression du cache li� au compte youtube.
                $aDatasYoutube    =    $this->getDataYoutubeByYoutubeId($youtubeId);
                if (is_array($aDatasYoutube)) {
                    foreach ($aDatasYoutube as $aDataYoutube) {
                        Pelican_Cache::clean("Service/Youtube", array(
                            'user',
                            $aDataYoutube['SITE_YOUTUBE_USERS'],
                            'forMine',
                            $aDataYoutube['SITE_ID'],
                            )
                        );
                    }
                }
                echo '<br/>';
                echo '<div>'.t('COMPTE_REVOKE_SUCCES').'</div>';
                echo '<br/>';
            }
            unset($_SESSION['php-oauth-client']);
        } else {
            //Si le compte n'est pas autoris� on revoke le token de connexion de celui ci pour pouvoir �tablir une nouvelle connexion.
            if (is_object($oAuthGooglePlus)) {
                $aDatas = array('token' => $oAuthGooglePlus->getAccessToken());
                // On unset la config pour eviter d'appeler la m�thode d'authentification oauth lors de l'appel du service
                //Car la m�thode revokeToken du service Youtube fonctionne sans une connection au service.
                unset(Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE']['authentication_provider']);
                Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE']['PARAMETERS']['host'] = 'https://accounts.google.com';
                Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE']['PARAMETERS']['uri'] = '/o/oauth2/revoke';
                $oService = Itkg\Service\Factory::getService('ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE');
                $oResponse = $oService->call('revokeToken', $aDatas);
                unset($_SESSION[APP]["ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE"]["OAuth2"]["access_token"]);
                unset($_SESSION[APP]["refresh_token"]);
            }
            unset($_SESSION['php-oauth-client']);
            echo '<br/>';
            echo '<div>'.t('COMPTE_NON_AUTORISE').'</div>';
            echo '<br/>';
            echo '<div>'.t('MESSAGE_COMPTE_NON_AUTORISE').'</div>';
            echo '<br/>';
            echo '<div>'.t('VOUS_DEVEZ_VOUS_CONNECTER_AVEC_LE_COMPTE').': '.$email.'</div>';
            echo '<br/>';
        }
        $_SESSION['isConnect']    =    false;
        echo '<a href="javascript:window.close();">'.t('FERMER_FENETRE').'</a> ';
    }

    public function getOAuth2Action()
    {
        // Connexion oauth pour acceder � l'api google plus
        $oauth = new OAuth2();
        $oauth->setParameters(Itkg::$config['ITKG_APIS_GOOGLE_PLUS_V1']['authentication_provider']['PARAMETERS']);
        $oauth->setIsRedirect(true);
        $oauth->setIsAuthenticateRefresh(false);
        $oAuthGooglePlus    =    $oauth->authenticate();
        // appel de la m�thode userInfos de l'api google plus pour r�cup�rer l'email du compte et v�rifier son autorisation.
        if ($oAuthGooglePlus) {
            unset(Itkg::$config['ITKG_APIS_GOOGLE_PLUS_V1']['authentication_provider']);
            $oService = Itkg\Service\Factory::getService('ITKG_APIS_GOOGLE_PLUS_V1');
            $aDatas = array('access_token' => $oAuthGooglePlus->getAccessToken());
            $oResponse = $oService->call('userInfos', $aDatas);
        }
        $youtubeId              =    $_REQUEST['id'];
        $aDataCompteYoutube     =    $this->getRefreshTokenBddByYoutubeId($youtubeId);

        //Si l'email du compte gmail est autoris� association du compte avec l'api youtube
        $email  = $this->getEmailCompteYoutube();
        if ($oResponse->emails[0]->value == $email) {
            if (!$_SESSION['isConnect']) {
                unset($_SESSION['php-oauth-client']);
            }

            // Connexion oauth pour acceder � l'api youtube
            $oauth                =    new OAuth2();
            $oauth->setIsAuthenticateRefresh(false);
            $oauth->setIsRedirect(true);
            $oauth->setParameters(Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE']['authentication_provider']['PARAMETERS']);
            $_SESSION['isConnect']    =    true;
            $oRefreshTokenData    =    $oauth->authenticate();

            // Si l'etat de revocation est �gal � 1 => autorisaution d'associer le compte avec l'api youtube
            if ($aDataCompteYoutube['REVOKE_ETAT'] == '1') {
                if ($oauth->isLogged) {
                    $aDataOauth                                =    $oauth->getAuthToken();
                    $oConnection                            =    Pelican_Db::getInstance();
                    if (!empty($aDataOauth['refresh_token'])) {
                        Pelican_Db::$values ['TOKEN_ID']        =    $aDataOauth['refresh_token'];
                        Pelican_Db::$values ['TOKEN_ID_REVOKE']    =    $aDataOauth['refresh_token'];
                        Pelican_Db::$values ['COMPTES_YOUTUBE']    =    $aDataCompteYoutube['COMPTES_YOUTUBE'];
                        Pelican_Db::$values ['EMAIL']            =    $aDataCompteYoutube['EMAIL'];
                        Pelican_Db::$values ['YOUTUBE_ID']        =    $youtubeId;
                        Pelican_Db::$values ['REVOKE_ETAT']        =    '0';
                        Pelican_Db::$values ['SITE_ID']            =    $aDataCompteYoutube['SITE_ID'];
                        $oConnection->replaceQuery('#pref#_youtube', 'YOUTUBE_ID ='.$youtubeId);
                        echo '<div>'.t('COMPTE_ASSOCIE_AVEC_SUCCES').'</div>';
                        unset($_SESSION['php-oauth-client']);
                    }
                }
            } else {
                echo '<div>'.t('COMPTE_DEJA_ASSOCIE').'</div>';
            }
        } else {
            if (is_object($oAuthGooglePlus)) {
                $aDatas = array('token' => $oAuthGooglePlus->getAccessToken());
                    // On unset la config pour eviter d'appeler la m�thode d'authentification oauth lors de l'appel du service
                    //Car la m�thode revokeToken du service Youtube fonctionne sans une connection au service.
                    unset(Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE']['authentication_provider']);
                Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE']['PARAMETERS']['host'] = 'https://accounts.google.com';
                Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE']['PARAMETERS']['uri'] = '/o/oauth2/revoke';
                $oService = Itkg\Service\Factory::getService('ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE');
                $oResponse = $oService->call('revokeToken', $aDatas);
                unset($_SESSION[APP]["ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE"]["OAuth2"]["access_token"]);
                unset($_SESSION[APP]["refresh_token"]);
                unset($_SESSION['php-oauth-client']);
            }
            unset($_SESSION['php-oauth-client']);
            echo '<br/>';
            echo '<div>'.t('COMPTE_NON_AUTORISE').'</div>';
            echo '<br/>';
            echo '<div>'.t('MESSAGE_COMPTE_NON_AUTORISE').'</div>';
            echo '<br/>';
            echo '<div>'.t('VOUS_DEVEZ_VOUS_CONNECTER_AVEC_LE_COMPTE').': '.$email.'</div>';
            echo '<br/>';
        }
        $_SESSION['isConnect']    =    false;
        echo '<a href="javascript:window.close();">'.t('FERMER_FENETRE').'</a> ';
    }

    public function getDataYoutubeByYoutubeId($youtubeId)
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':YOUTUBE_ID'] = $youtubeId;
        $sql = "SELECT y.*, s.SITE_YOUTUBE_USERS, s.SITE_ID FROM  #pref#_youtube y INNER JOIN #pref#_site s ON s.YOUTUBE_ID = y.YOUTUBE_ID WHERE s.YOUTUBE_ID = :YOUTUBE_ID";
        $aDatas    =    $oConnection->queryTab($sql, $aBind);
        if (empty($aDatas)) {
            return false;
        }

        return $aDatas;
    }

    public function getRefreshTokenBddByYoutubeId($siteId)
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':YOUTUBE_ID'] = $siteId;
        $sql = "SELECT * FROM  #pref#_youtube WHERE YOUTUBE_ID 	 = :YOUTUBE_ID";
        $aDatas    =    $oConnection->queryRow($sql, $aBind);
        if (empty($aDatas)) {
            return false;
        }

        return $aDatas;
    }

    public function isEmailExist($email)
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':EMAIL_YOUTUBE'] = $oConnection->strToBind($email);
        $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];

        $sql = "SELECT * FROM  #pref#_site WHERE EMAIL_YOUTUBE  = :EMAIL_YOUTUBE AND SITE_ID <> :SITE_ID";
        $aDatas    =    $oConnection->queryRow($sql, $aBind);
        if (empty($aDatas)) {
            return false;
        }

        return true;
    }
   /**
    * Permet de setter en session le label du profil via l'id du profil selectionné.
    */
   public function getEmailCompteYoutube()
   {
       $oConnection = Pelican_Db::getInstance();
       $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
       $sql = "Select  EMAIL_YOUTUBE from #pref#_site where SITE_ID= :SITE_ID";
       $result = $oConnection->getRow($sql, $aBind);

       return $result['EMAIL_YOUTUBE'];
   }
}

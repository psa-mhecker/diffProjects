<?php

/**
 * Formulaire de gestion de la configuration de la cartographie.
 *
 * @author Pierre Pottié <pierre.pottie@businessdecision.com>
 *
 * @since 27/05/2015
 */
class Administration_Site_Global_Parameters_Controller extends Ndp_Controller
{

    protected $administration = true;
    protected $form_name = "site";
    protected $field_id = 'SITE_ID';

    const DISABLED = 0;
    const ENABLED_DESKTOP = 1;
    const ENABLED_MOBILE = 2;
    const ENABLED_DESKTOP_MOBILE = 3;
    const SITE_PWD_PREVISU_DEFAULT = "NDP";

    /**
     * 
     */
    protected function init()
    {
        parent::init();
        $params = $this->getParams();
        $this->id = $params['SITE_ID'];
    }

    /**
     * 
     */
    protected function setEditModel()
    {
        $this->aBind[':SITE_ID'] = (int) $this->id;

        $this->editModel = "SELECT * "
            ." FROM #pref#_".$this->form_name." s"
            ." WHERE s.SITE_ID=:SITE_ID";
    }

    /**
     * 
     */
    public function listAction()
    {
        $this->editAction();
    }

    /**
     * 
     */
    public function editAction()
    {
        self::init();
        parent::editAction();

        $oForm = $this->getParam('oForm');

        $form = $oForm->createHidden('complement_tc', $this->getParam('tc'), true);
        $form .= $oForm->createHidden($this->field_id, $this->id, true);
        $form .= $oForm->createInput(
            "SITE_LABEL", t('Nom'), 255, "", true, stripslashes($this->values ["SITE_LABEL"]), $this->readO, 100
        );
        $form .= $oForm->createInput(
            "SITE_TITLE", t('Titre des pages'), 255, "", true, stripslashes($this->values ["SITE_TITLE"]), $this->readO, 100
        );
        $form .= $oForm->createInput(
            "SITE_URL", t('URL principale'), 255, "", true, $this->values ["SITE_URL"], $this->readO, 100, false, "onBlur=check_url('SITE_URL','msg_url',".$this->id.",100)"
        );
        $form .= "  <tr><td></td><td id='msg_url'></td></tr>";
        $form .= $oForm->createTextArea(
            "SITE_ROBOT_DESK", t('Robots desk').' (?)', false, $this->values ["SITE_ROBOT_DESK"], "", $this->readO, 10, 100, false, "", true, "", t("FONCTIONNEMENT_ROBOT_ADMIN")
        );

        $form .= $oForm->createCheckBoxFromList(
            "SITE_MAINTENANCE", t('Site maintenance'), array("1" => ""), $this->values ["SITE_MAINTENANCE"], false, $this->readO, "h"
        );

        $form .= $oForm->createInput(
            "SITE_MAINTENANCE_URL", t('Url maintenance'), 255, "internallink", false, $this->values ["SITE_MAINTENANCE_URL"], $this->readO, 100, false, "", "text", array(), false, ""
        );

        $form .= $oForm->createTextArea(
            "SITE_MAIL_WEBMASTER", t('Mail webmaster').' (?)', true, $this->values ["SITE_MAIL_WEBMASTER"], 1024, $this->readO, 1, 100, false, "", true, "", t("FONCTIONNEMENT_MAIL_WEBMASTER")
        );
        $this->values ["SITE_MAIL_EXPEDITEUR"] = ($this->values ["SITE_MAIL_EXPEDITEUR"] == "") ? "no_return_adresse@mpsa.com" : $this->values ["SITE_MAIL_EXPEDITEUR"];
        $form .= $oForm->createInput(
            "SITE_MAIL_EXPEDITEUR", t('Mail expediteur'), 100, "", true, $this->values ["SITE_MAIL_EXPEDITEUR"], $this->readO, 100, false, "", "text", array(), false, ""
        );
        $this->values ["SITE_LOGIN_PREVISU"] = ($this->values ["SITE_LOGIN_PREVISU"] == "") ? "previsu" : $this->values ["SITE_LOGIN_PREVISU"];
        $form .= $oForm->createInput(
            "SITE_LOGIN_PREVISU", t('Login previsu'), 255, "", true, $this->values ["SITE_LOGIN_PREVISU"], $this->readO, 100, false, ""
        );
        $this->values ["SITE_PWD_PREVISU"] = ($this->values ["SITE_PWD_PREVISU"] == "") ? self::SITE_PWD_PREVISU_DEFAULT : $this->values ["SITE_PWD_PREVISU"];
        $form .= $oForm->createPassword(
            "SITE_PWD_PREVISU", t('Password previsu'), 100, true, $this->values ["SITE_PWD_PREVISU"], $this->readO, 100, false
        );

        // Valeur attribut balise <meta name="google-site-verification" ... />
        $form .= $oForm->createInput(
            "GOOGLE_SITE_VERIFICATION", t('GOOGLE_SITE_VERIFICATION'), 255, "", false, $this->values ["GOOGLE_SITE_VERIFICATION"], $this->readO, 100, false
        );

        if ($this->getParam('tc') !== 'admin') {
            $form .= $oForm->createInput("GTM_ID", t('GTM_ID'), 10, "", true, $this->values ["GTM_ID"], $this->readO, 100, false);
        }

        if ($this->getParam('tc') == 'admin') {

            $aData = array(
                self::DISABLED => t('DESACTIVER'),
                self::ENABLED_DESKTOP => t('ACTIVER_WEB_TABLETTE'),
                self::ENABLED_MOBILE => t('ACTIVER_MOBILE'),
                self::ENABLED_DESKTOP_MOBILE => t('ACTIVER_WEB_TABLETTE_MOBILE')
            );
            $form .= $oForm->createComboFromList("SITE_ACTIVATION_RECHERCHE", t('ACTIVATION_CHAMP_RECHERCHE '), $aData, $this->values["SITE_ACTIVATION_RECHERCHE"], true, $this->readO, 1, false, '', false);

            $form .= $oForm->createComboFromList(
                "SITE_ACTIVATION_AUTOCOMPLETION", t('ACTIVATION_AUTOCOMPLETION'), $aData, $this->values["SITE_ACTIVATION_AUTOCOMPLETION"], true, $this->readO, 1, false, '', false
            );
        }

        $form .= $this->getFormDelayPopin();

        $this->setResponse($form);
    }

    /**
     * 
     */
    public function getFormDelayPopin()
    {
        $params = $this->getParams();

        $params['SITE_ID'] = $this->id;
        $params['SITE_PARAMETER_ID'] = 'DELAY_POPIN';
        $params['LABEL'] = t('NDP_DELAY_POPIN');
        $params['SIZE'] = 30;
        $params['MAX_SIZE'] = 15;
        $params['TYPE'] = 'numeric';

        $form = Pelican_Request::call('_/Administration_Site_Parameter', $params);

        return $form;
    }

    /**
     * 
     */
    public function saveAction()
    {
        self::init();
        $connection = Pelican_Db::getInstance();

        //Gestion Robots.txt
        $sFolderRobot = Pelican::$config["DOCUMENT_INIT"]."/var/robots/".Pelican_Db::$values ['SITE_CODE_PAYS'];
        $FileWeb = $sFolderRobot."/robots.txt";
        $this->verifyDir($sFolderRobot);
        if (Pelican_Db::$values ['SITE_CODE_PAYS'] != "" && Pelican_Db::$values ['SITE_ROBOT_DESK'] != "") {
            @unlink($FileWeb);
            file_put_contents($FileWeb, Pelican_Db::$values ['SITE_ROBOT_DESK']);
        }

        $params = $this->getParams();
        $params['SITE_ID'] = $this->id;
        $params['SITE_PARAMETER_ID'] = 'DELAY_POPIN';

        Pelican_Request::call('_/Administration_Site_Parameter/save', $params);
        Pelican_Cache::clean("Frontend/Site");
    }

    /**
     * 
     * @param type $dir_name
     * @param type $permission
     */
    private function verifyDir($dir_name, $permission = 755)
    {
        if (!is_dir($dir_name)) {
            if (isset($_SERVER['WINDIR'])) {
                mkdir($dir_name, $permission, true);
            } else {
                $cmd = "mkdir -p -m ".$permission." ".$dir_name;
                Pelican::runCommand($cmd);
            }
        }
    }
}

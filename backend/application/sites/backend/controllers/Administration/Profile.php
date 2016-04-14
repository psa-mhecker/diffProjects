<?php
require_once Pelican::$config["APPLICATION_CONTROLLERS"]."/Administration/Directory.php";

/**
 * Formulaire de gestion des profils utilisateurs du Back Office.
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 03/01/2004
 */
class Administration_Profile_Controller extends Pelican_Controller_Back
{
    protected $form_name = "profile";

    protected $field_id = "PROFILE_ID";

    protected $defaultOrder = "PROFILE_LABEL";

    protected $processus = array(
        "#pref#_profile",
        array(
            "#pref#_profile_directory",
            "DIRECTORY_ID",
        ),
        array(
            "#pref#_user_profile",
            "USER_LOGIN",
        ),
    );

    protected function setListModel()
    {
        $sqlList = "SELECT #pref#_profile.PROFILE_ID, PROFILE_LABEL, SITE_LABEL, #pref#_profile.SITE_ID, count(USER_LOGIN) as NB
        	FROM #pref#_profile
        	inner join #pref#_site on (#pref#_profile.SITE_ID=#pref#_site.SITE_ID)
        	left join #pref#_user_profile on (#pref#_profile.PROFILE_ID=#pref#_user_profile.PROFILE_ID and USER_LOGIN!='admin')";
        if (!$_SESSION[APP]["admin"]) {
            $sqlList .= " WHERE #pref#_site.SITE_ID='".$_SESSION[APP]['SITE_ID']."'";
        }
        $sqlList .= " GROUP BY #pref#_profile.PROFILE_ID, PROFILE_LABEL, SITE_LABEL, #pref#_profile.SITE_ID ";
        $sqlList .= " ORDER BY #pref#_profile.SITE_ID, ".$this->listOrder;
        $this->listModel = $sqlList;
    }

    protected function setEditModel()
    {
        $this->aBind[':ID'] = $this->id;
        $this->editModel = "SELECT * FROM #pref#_profile WHERE PROFILE_ID=:ID";
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");

        if ($_SESSION[APP]['SITE_ID'] == Pelican:: $config['SITE_BO']) {
            $table->setFilterField("site", "<b>Site&nbsp;:</b><br />", "#pref#_profile.SITE_ID", "select #pref#_site.SITE_ID as \"id\", SITE_LABEL as lib FROM #pref#_site ORDER BY SITE_LABEL");
            $groupe = "SITE_LABEL";
        }
        $table->setFilterField("PROFILE_ID", "<b>".t('Identifiant')." </b> :", array(
                "#pref#_profile.PROFILE_ID",
            ), "", "1", true, true);
        $table->setFilterField("PROFILE_LABEL", "<b>".t("POPUP_LABEL_NAME")." </b> :", array(
            "PROFILE_LABEL",
        ), "", "1", true, true);
        $table->getFilter(3);
        $table->setCSS(array(
            "tblalt1",
            "tblalt2",
        ));
        $table->setValues($this->getListModel(), "#pref#_profile.PROFILE_ID", $groupe);
        $table->addColumn(t('PROFIL'), "PROFILE_ID", "10", "left", "", "tblheader", "PROFILE_ID");
        $table->addColumn(t('FORM_LABEL'), "PROFILE_LABEL", "45", "left", "", "tblheader", "PROFILE_LABEL");
        $table->addColumn(t('NB_USE'), "NB", "2", "center", "", "tblheader", "NB");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array(
            "id" => "PROFILE_ID",
        ), "center");
        /*$table->addInput(t('POPUP_LABEL_DEL'), "button", array(
            "id" => "PROFILE_ID",
            "" => "readO=true"
        ), "center", array(
            "NB=0"
        ));*/

        $this->aButton["add"] = "";
        Backoffice_Button_Helper::init($this->aButton);

        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        $oConnection = Pelican_Db::getInstance();

        $_SESSION[APP]["form_profile"] = $_SERVER["REQUEST_URI"];
        parent::editAction();
        $form = $this->startStandardForm();

        $form .= $this->oForm->createInput("PROFILE_LABEL", t('FORM_LABEL'), 50, "", true, $this->values["PROFILE_LABEL"], true, 50);
        $form .= $this->oForm->createHidden("PROFILE_ADMIN", $this->values["PROFILE_ADMIN"]);

        // Les utilisateurs du profile
        $form .= $this->oForm->showSeparator();
        $sqlData = "SELECT #pref#_user.USER_LOGIN as ID, USER_NAME as LIB FROM #pref#_user WHERE #pref#_user.USER_LOGIN != 'admin' ORDER BY LIB";
        $sqlSelected = "SELECT #pref#_user.USER_LOGIN as ID, USER_NAME as LIB FROM #pref#_user, #pref#_user_profile WHERE #pref#_user.USER_LOGIN=#pref#_user_profile.USER_LOGIN AND #pref#_user.USER_LOGIN != 'admin' AND PROFILE_ID='".$this->id."' order by lib";
        //$form .= $this->oForm->createAssocFromSql($oConnection, "USER_LOGIN2", t('Users'), $sqlData, $sqlSelected, false, true, $this->readO, 8, 200, false);
        $aUsers = $oConnection->queryTab($sqlSelected);
        if (!empty($aUsers)) {
            foreach ($aUsers as $key => $user) {
                $form .= $this->oForm->createHidden("USER_LOGIN[".$key."]", $user['ID']);
            }
        }

        // Les menus du profil
        $form .= $this->oForm->showSeparator();
        if ($_SESSION[APP]["admin"]) {
            $form .= $this->oForm->createComboFromSql($oConnection, 'SITE_ID', t('SITE'), "SELECT #pref#_site.SITE_ID as ID, SITE_LABEL as LIB FROM #pref#_site ORDER BY SITE_LABEL", $this->values['SITE_ID'], true, $this->readO, "1", false, "", true, false, "onchange=\"changeSubHmvc('profile', 'PROFILE_ID=".$this->id."&SITE_ID=' + document.getElementById('SITE_ID').value);\"");
        } else {
            $this->values['SITE_ID'] = $_SESSION[APP]['SITE_ID'];
            $form .= $this->oForm->createHidden('SITE_ID', $_SESSION[APP]['SITE_ID']);
        }
        if (empty($this->values)) {
            $this->values['SITE_ID'] = 0;
            $this->values['PROFILE_ID'] = $this->id;
        }
        $form .= $this->oForm->createSubFormHmvc("profile", t('Backend access'), array(
            'path' => Pelican::$config["APPLICATION_CONTROLLERS"].'/Administration/Directory.php',
            'class' => 'Administration_Directory_Controller',
            'method' => 'profile',
        ), $this->values, $this->readO);

        $form .= $this->oForm->createHidden($this->field_id, $this->id);

        $form .= $this->stopStandardForm();

        // Zend_Form start
        $form = formToString($this->oForm, $form);
        // Zend_Form stop

        $this->setResponse($form);
    }

    public function before()
    {
        parent::before();
        $_SESSION[APP]["tree_profile"] = true;
    }

    protected function beforeSave()
    {

        /* Ajout systématique de l'administrateur global */
        Pelican_Db::$values["USER_LOGIN"][] = 'admin';
        if (!Pelican_Db::$values["PROFILE_ADMIN"]) {
            Pelican_Db::$values["PROFILE_ADMIN"] = "0";
        }
    }

    protected function afterSave()
    {
        $oConnection = Pelican_Db::getInstance();

        // Sauvegarde de Pelican_Db::$values
        $ordre = 0;
        $DBVALUES_MONO = Pelican_Db::$values;
        if (Pelican_Db::$values["DIRECTORY_ID"]) {
            foreach (Pelican_Db::$values["DIRECTORY_ID"] as $menu) {
                Pelican_Db::$values["PROFILE_ID"] = $DBVALUES_MONO["PROFILE_ID"];
                Pelican_Db::$values["DIRECTORY_ID"] = $menu;
                // tri standard : menus admin en premier puis onglets en premiers
                $ordre ++;
                Pelican_Db::$values["PROFILE_DIRECTORY_ORDER"] = ((intval(Pelican_Db::$values["PROFILE_ID"]) * 1000 + $ordre));
                $oConnection->updateQuery("#pref#_profile_directory");
            }
        }
        // Récupération de Pelican_Db::$values
        Pelican_Db::$values = $DBVALUES_MONO;
    }
}

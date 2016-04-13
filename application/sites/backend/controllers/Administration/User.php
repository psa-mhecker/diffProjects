<?php
require_once (Pelican::$config["APPLICATION_CONTROLLERS"] . "/Administration/Directory.php");

/**
 * Formulaire de gestion des utilisateurs du Back Office
 *
 * @package Pelican_BackOffice
 * @subpackage Administration
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 03/11/2003
 */

class Administration_User_Controller extends Pelican_Controller_Back
{

    protected $administration = true;

    protected $form_name = "user";

    protected $field_id = "USER_LOGIN";

    protected $defaultOrder = "USER_NAME";

    protected $processus = array(
        "#pref#_user" ,
        array(
            "#pref#_user_profile" ,
            "PROFILE_ID"
        ) ,
        array(
            "method" ,
            "Administration_User_Controller::role"
        )
    );

    protected $decacheBack = array(
        "Backend/ContentType" ,
        "Backend/State" ,
        "Backend/User"
    );

    protected function setListModel ()
    {
        $oConnection = Pelican_Db::getInstance();
        
        $sqlList = "SELECT DISTINCT
		#pref#_user.USER_LOGIN,
		USER_NAME,
		USER_EMAIL,
        ".$oConnection->getCaseClause('IS_LDAP', array("1"=>"1"), "NULL")." as IS_LDAP
		FROM #pref#_user,
		#pref#_user_profile ,
		#pref#_profile
		where
		#pref#_user.USER_LOGIN=#pref#_user_profile.USER_LOGIN
		and #pref#_user.SITE_ID=1
		and #pref#_user_profile.PROFILE_ID=#pref#_profile.PROFILE_ID";
        if (! $this->_isAdminSite()) {
            $sqlList .= " AND #pref#_user.USER_LOGIN!='admin' AND #pref#_profile.SITE_ID='" . $_SESSION[APP]['SITE_ID'] . "'";
        }
        $sqlList .= " order by " . $this->listOrder;

        $this->listModel = $sqlList;
    }

    protected function setEditModel ()
    {
        $this->editModel = "SELECT * from #pref#_user WHERE USER_LOGIN='" . $this->id . "'
        and #pref#_user.SITE_ID=1";

    }

    protected function beforeSave ()
    {
        $_SESSION[APP]["form_user"] = $_SERVER["REQUEST_URI"];

        if ($_SESSION[APP]["FORM"] != "") {
            $this->values = $_SESSION[APP]["FORM"];
            unset($_SESSION[APP]["FORM"]);
            echo ("Le login existe déjà !");
        }

    }

    public function saveAction ()
    {

        $oConnection = Pelican_Db::getInstance();

        $aBind[':USER_LOGIN'] = $oConnection->strToBind(Pelican_Db::$values["USER_LOGIN"]);
        $verifLogin = $oConnection->queryItem("SELECT 1 FROM #pref#_user WHERE user_login = :USER_LOGIN", $aBind);
        if ($verifLogin && $this->form_action == Pelican_Db::DATABASE_INSERT) {
            $_SESSION[APP]["LOGIN_BO_EXIST"] = 1;
        } else {
            // md5 du mot de passe à l'insertion ou à la mise à jour
            if (Pelican_Db::$values["USER_PASSWORD_SAUVE"] != Pelican_Db::$values["USER_PASSWORD"]) {
                Pelican_Db::$values["USER_PASSWORD"] = md5(Pelican_Db::$values["USER_PASSWORD"]);
            }
            // USER_ENABLED est obligatoire
            if (! Pelican_Db::$values["USER_ENABLED"]) {
                Pelican_Db::$values["USER_ENABLED"] = "0";
            }

            if (! $_SESSION[APP]["admin"]) {
                $oConnection->query("SELECT #pref#_user_profile.PROFILE_ID FROM #pref#_user_profile, #pref#_profile WHERE #pref#_user_profile.PROFILE_ID=#pref#_profile.PROFILE_ID AND SITE_ID!=" . Pelican_Db::$values['SITE_ID'] . " AND USER_LOGIN='" . Pelican_Db::$values["USER_LOGIN"] . "'");
                if ($oConnection->data["PROFILE_ID"]) {
                    Pelican_Db::$values["PROFILE_ID"] = array_merge(Pelican_Db::$values["PROFILE_ID"], $oConnection->data["PROFILE_ID"]);
                }
            }

            // ATTENTION : CAS DU USER DU BACKOFFICE, DOIT AVOIT LE SITE_ID DU BO
            Pelican_Db::$values['SITE_ID'] = 1;

            parent::saveAction();

            // Sauvegarde du périmètre d'intervention
            if (Pelican_Db::$values["PAGE_ID"]) {
                // 1 - Suppression du périmètre d'intervention de l'utilisateur sur les pages et contenus
                $sqlDeleteInPage = "
				UPDATE
				#pref#_page
				SET
				PAGE_CREATION_USER = REPLACE(PAGE_CREATION_USER, '#" . Pelican_Db::$values['USER_LOGIN'] . "#','')
				";
                $oConnection->query($sqlDeleteInPage);

                $sqlDeleteInContent = "
				UPDATE
				#pref#_content
				SET
				CONTENT_CREATION_USER = REPLACE(CONTENT_CREATION_USER, '#" . Pelican_Db::$values['USER_LOGIN'] . "#','')
				WHERE
				CONTENT_ID in (SELECT #pref#_content_version.CONTENT_ID from #pref#_content_version)
				";
                $oConnection->query($sqlDeleteInContent);

                // 2 - Enregistrement du périmètre d'intervention de l'utilisateur sur les pages et contenus
                foreach (Pelican_Db::$values["PAGE_ID"] as $key => $value) {
                    // Maj du champ PAGE_CREATION_USER pour la rubrique
                    $this->aBind[':PAGE_ID'] = $value;
                    $sqlSavePage = "
					UPDATE
					#pref#_page
					SET
					PAGE_CREATION_USER = " . $oConnection->getConcatClause(array(
                        "REPLACE(PAGE_CREATION_USER, '#" . Pelican_Db::$values['USER_LOGIN'] . "#', '')" ,
                        "'#" . Pelican_Db::$values['USER_LOGIN'] . "#'"
                    )) . "
					WHERE
					PAGE_ID = :PAGE_ID";

                    $oConnection->query($sqlSavePage, $this->aBind);

                    // Maj du champ CONTENT_CREATION_USER pour les contenus de la rubrique
                    $sqlSaveContent = "
					UPDATE
					#pref#_content
					SET
					CONTENT_CREATION_USER = " . $oConnection->getConcatClause(array(
                        "REPLACE(CONTENT_CREATION_USER, '#" . Pelican_Db::$values['USER_LOGIN'] . "#', '')" ,
                        "'#" . Pelican_Db::$values['USER_LOGIN'] . "#'"
                    )) . "
					WHERE
					CONTENT_ID in (SELECT #pref#_content_version.CONTENT_ID from #pref#_content_version where #pref#_content_version.PAGE_ID = :PAGE_ID)
					";
                    $oConnection->query($sqlSaveContent, $this->aBind);
                }
            }
        }
    }

    public function listAction ()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");

        if ($this->_isAdminSite()) {
            $table->setFilterField("site", "<b>" . t('SITE') . "&nbsp;:</b><br />", "#pref#_profile.SITE_ID", "select #pref#_site.SITE_ID as id, SITE_LABEL as lib FROM #pref#_site ORDER BY SITE_LABEL");
            $table->setFilterField();
        }
        $table->setFilterField("nom", "<b>" . t('NAME') . " </b><br />", array(
            "#pref#_user.USER_LOGIN" ,
            "USER_NAME"
        ));
        $table->setFilterField("email", "<b>" . t('EMAIL') . " :</b><br />", "USER_EMAIL");
        $table->getFilter(2);

        $table->setCSS(array(
            "tblalt1" ,
            "tblalt2"
        ));
        $table->setValues($this->getListModel(), "#pref#_user.USER_LOGIN");
        $table->addColumn(t('LOGIN'), "USER_LOGIN", "30", "left", "", "tblheader", "USER_LOGIN");
        $table->addColumn(t('NAME'), "USER_NAME", "45", "left", "", "tblheader", "USER_NAME");
        $table->addColumn(t('EMAIL'), "USER_EMAIL", "45", "left", "email", "tblheader", "USER_EMAIL");
        $table->addColumn(t('LDAP'), "IS_LDAP", "10", "center", "boolean", "tblheader", "IS_LDAP");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array(
            "id" => "USER_LOGIN"
        ), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array(
            "id" => "USER_LOGIN" ,
            "" => "readO=true"
        ), "center");
        $this->setResponse($table->getTable());
    }

    public function editAction ()
    {

        $oConnection = Pelican_Db::getInstance();

        parent::editAction();
        $form = '';
        
        $saveReadO = $this->readO;
        if ($this->values["IS_LDAP"] == 1) {
            $this->readO = true;
            $form .= Pelican_Html::div(
                array("class" => t('ERROR')),
                Pelican_Html::br().Pelican_Html::b(t('USER_IS_LDAP_ONLY_READ')).Pelican_Html::br().Pelican_Html::br()
            );
        }

        $form .= $this->startStandardForm();
        $form .= $this->oForm
            ->createInput("USER_NAME", t('NAME'), 50, "", true, $this->values["USER_NAME"], $this->readO, 50);
        $readOLogin = $this->readO;
        if ($this->form_action != Pelican_Db::DATABASE_INSERT) {
            $readOLogin = true;
        }
        
        $form .= $this->oForm
            ->createHidden("IS_LDAP", $this->values["IS_LDAP"]);
        $form .= $this->oForm
            ->createInput("USER_LOGIN", t('LOGIN'), 50, "", true, $this->values["USER_LOGIN"], $readOLogin, 50, false, "onchange='checkLogin()'");
        if ( $this->readO == true) {
            $form .= $this->oForm
                ->createHidden("USER_PASSWORD", $this->values["USER_PASSWORD"]);
            $form .= $this->oForm
                ->createLabel(t('PASSWORD'), "***");
        } else {
            $form .= $this->oForm
                ->createpassword("USER_PASSWORD", t('PASSWORD'), 20, true, $this->values["USER_PASSWORD"], $this->readO, 50);
        }
        $form .= $this->oForm
            ->createHidden("USER_PASSWORD_SAUVE", $this->values["USER_PASSWORD"]);
        $form .= $this->oForm
            ->createInput("USER_EMAIL", t('EMAIL'), 50, "mail", false, $this->values["USER_EMAIL"], $this->readO, 50);
        $form .= $this->oForm
            ->createTextArea("USER_INFOS", t('Informations'), false, $this->values["USER_INFOS"], 500, $this->readO, 5, 50);
        if ($this->form_action == Pelican_Db::DATABASE_INSERT) {
            $this->values["USER_ENABLED"] = 1;
            $this->values["USER_FULL"] = 0;
        }
        $form .= $this->oForm
            ->createCheckBoxFromList("USER_ENABLED", t('ACTIF'), array(
            "1" => ""
        ), $this->values["USER_ENABLED"], false, $this->readO, "h");
        $form .= $this->oForm
            ->createCheckBoxFromList("USER_FULL", t('Droits etendus'), array(
            "1" => ""
        ), $this->values["USER_FULL"], false, $this->readO, "h");

        $form .= $this->oForm
            ->showSeparator();
        $sqlSelected = "select #pref#_profile.PROFILE_ID as id, " . $oConnection->getConcatClause(array(
            "PROFILE_LABEL" ,
            "' ('" ,
            "SITE_LABEL" ,
            "')'"
        )) . " as lib from #pref#_profile, #pref#_user_profile, #pref#_site where #pref#_profile.SITE_ID=#pref#_site.SITE_ID AND #pref#_profile.PROFILE_ID=#pref#_user_profile.PROFILE_ID and USER_LOGIN='" . $this->id . "' order by lib";
        $comboQuery = "select SITE_ID as id, SITE_LABEL as lib from #pref#_site order by lib";
        $searchQuery = "select PROFILE_ID as \"id\", PROFILE_LABEL as \"lib\" from #pref#_profile where SITE_ID=:RECHERCHE: order by PROFILE_LABEL";

        if ($this->_isAdminSite()) {
            $form .= $this->oForm
                ->createAssocFromSql($oConnection, "PROFILE_ID", t('Profiles'), "", $sqlSelected, true, true, $this->readO, 12, 250, false, array(
                "site" ,
                $comboQuery ,
                $searchQuery
            ));
        } else {
            $searchQuery = str_replace(":RECHERCHE:", "'" . $_SESSION[APP]['SITE_ID'] . "'", $searchQuery);
            $form .= $this->oForm
                ->createAssocFromSql($oConnection, "PROFILE_ID", t('Profiles'), $searchQuery, $sqlSelected, true, true, $this->readO, 12, 250, false);
        }

        $form .= $this->oForm
            ->showSeparator();



        $sqlRow = "select DISTINCT #pref#_content_type.CONTENT_TYPE_ID as \"id\", CONTENT_TYPE_LABEL as \"lib\" from #pref#_content_type ";
        if (! $this->_isAdminSite()) {
            $sqlRow .= ", #pref#_content_type_site WHERE #pref#_content_type.CONTENT_TYPE_ID=#pref#_content_type_site.CONTENT_TYPE_ID
				AND #pref#_content_type_site.SITE_ID=" . $_SESSION[APP]['SITE_ID'];
        }
        $sqlRow .= " order by CONTENT_TYPE_LABEL";

        //Affiche le tableau des roles

        $sqlColumn = "select ROLE_ID as \"id\", ROLE_LABEL as \"lib\" from #pref#_role order by ROLE_LABEL";
        $aSitesTemp	=	array();
        $aInfosSitesSql	=   "select distinct psa_site.SITE_ID, SITE_LABEL, psa_profile.PROFILE_ID
        					from psa_site, psa_profile, psa_user_profile
        					where psa_user_profile.PROFILE_ID=psa_profile.PROFILE_ID
        					and psa_site.SITE_ID=psa_profile.SITE_ID
        					and USER_LOGIN='" . $_GET['id'] . "'
        					order by psa_site.SITE_ID";
        $aInfosSites	=	$oConnection->queryTab($aInfosSitesSql, $aBind);

        /*foreach ($aInfosSites as $aSites){
    	    if( !in_array($aSites['SITE_ID'], $aSitesTemp)){
    	    	$aSitesTemp[]	=	$aSites['SITE_ID'];
	        	if (($this->id != Pelican::$config["DATABASE_INSERT_ID"]) && (strlen($this->id) != 0)) {
	            	$strQueryData = "SELECT ROLE_ID as \"id_col\", CONTENT_TYPE_ID as \"id_row\" from #pref#_user_role where USER_LOGIN = '" . $this->id . "' and SITE_ID=" . $aSites['SITE_ID'];
	        	}
	        	$libelle	=	t('ROLE') . ' ' . $aSites['SITE_LABEL'];
	        	$form .= $this->oForm
	            	->createTabCroiseGenerique($oConnection, "ROLE_ID_".$aSites['SITE_LABEL'], $libelle, $sqlColumn, $sqlRow, $strQueryData, "", "", true, true, $this->readO);
    	    }
        } */

        if ($_SESSION[APP]['SITE_ID'] != Pelican::$config['ADMINISTRATION_SITE_ID']) {
            $this->values['SITE_ID'] = $_SESSION[APP]['SITE_ID'];
            $form .= $this->oForm
                ->createHidden('SITE_ID', $_SESSION[APP]['SITE_ID']);
            $form .= $this->oForm
                ->createSubFormHmvc("rubrique", t("PERI_INTER"), array(
                'class' => 'Administration_Directory_Controller' ,
                'method' => 'page'
            ), $this->values, $this->readO);
        }
        
        $this->readO = $saveReadO;
        $form .= $this->stopStandardForm();
        
        if ($this->values["IS_LDAP"]) {
            $this->aButton["save"] = "";
            Backoffice_Button_Helper::init($this->aButton);
        }
        
        // Zend_Form start
		$form = formToString($this->oForm, $form);
        // Zend_Form stop
        
        $this->assign('content', $form, false);
        $this->assign('id', $this->id);
        $this->fetch();
    }

    public function checkLoginAction ()
    {

        $oConnection = Pelican_Db::getInstance();

        $login = $this->getParam(0);

        $aBind[":USER_LOGIN"] = $oConnection->strTobind($login);

        $unicite = $oConnection->queryItem("select count(*) from #pref#_user where USER_LOGIN=:USER_LOGIN", $aBind);

        if ($unicite) {
            $this->addResponseCommand('alert', array(
                'value' => t('ID_DOUBLON')
            ));
        }
    }

    public static function role ()
    {

        $oConnection = Pelican_Db::getInstance();

        $sqlRow = "select DISTINCT #pref#_content_type.CONTENT_TYPE_ID as \"id\" from #pref#_content_type ";
        if (! $_SESSION[APP]["admin"]) {
            $sqlRow .= ", #pref#_content_type_site WHERE #pref#_content_type.CONTENT_TYPE_ID=#pref#_content_type_site.CONTENT_TYPE_ID
			AND #pref#_content_type_site.SITE_ID=" . Pelican_Db::$values['SITE_ID'];
        }

        recordTabCroiseGenerique($oConnection, "ROLE_ID", Pelican_Db::$values["USER_LOGIN"], "select ROLE_ID as \"id\" from #pref#_role", $sqlRow, "ROLE_ID", "CONTENT_TYPE_ID", "#pref#_user_role", "USER_LOGIN");

    }
}
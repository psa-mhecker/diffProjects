<?php
require_once (Pelican::$config["APPLICATION_CONTROLLERS"] . "/Administration/Directory.php");

/**
 * Formulaire de gestion des sites
 *
 * @package Pelican_BackOffice
 * @subpackage Administration
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 02/07/2004
 */
class Administration_Site_Controller extends Pelican_Controller_Back
{

    protected $administration = true;

    protected $form_name = "site_simple";

    protected $field_id = 'SITE_ID';

    protected $defaultOrder = "SITE_LABEL";

    protected $processus = array(
        "#pref#_site", 
        array(
            "#pref#_directory_site", 
            "DIRECTORY_ID"
        ), 
        array(
            "#pref#_site_dns", 
            "SITE_DNS"
        ), 
        array(
            "#pref#_site_language", 
            'LANGUE_ID'
        ), 
        array(
            "method", 
            "Administration_Site_Controller::siteContentType"
        )
    );

    protected $decacheBack = array(
        "Frontend/Site", 
        "Backend/ContentType", 
        "frontend_Frontend/Site/Url", 
        array(
            "Backend/Generic", 
            "site"
        )
    );

    protected $isMinisite;

    protected function init()
    {
        if ($this->show) {
            $this->form_name = "site";
        }
        $_SESSION[APP]["tree_profile"] = "";
        $this->isMinisite = Pelican::$config["SITE"]["MINISITE"];
        
        /*** minisite */
        //Passer directement aux propriétés du site courant dans le cas d'un minisite
        if ($this->isMinisite) {
            $this->id = $_SESSION[APP]['SITE_ID'];
        
     //??? Pelican::$frontController->id = $this->id;
        }
        /* minisite ***/
    }

    protected function setListModel()
    {
        $this->listModel = "SELECT #pref#_site.*
		FROM #pref#_site order by " . $this->listOrder;
    }

    protected function setEditModel()
    {
        $this->editModel = "SELECT * from #pref#_site WHERE SITE_ID=" . (int) $this->id;
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setCSS(array(
            "tblalt1", 
            "tblalt2"
        ));
        $table->setValues($this->getListModel(), 'SITE_ID');
        $table->addColumn(t('ID'), 'SITE_ID', "10", "left", "", "tblheader", 'SITE_ID');
        $table->addColumn(t('SITE'), "SITE_LABEL", "50", "left", "", "tblheader", "SITE_LABEL");
        $table->addColumn(t('TITRE'), "SITE_TITLE", "25", "left", "", "tblheader", "SITE_TITLE");
        $sqlDns = "select distinct SITE_ID as \"id\", SITE_DNS as \"lib\" from #pref#_site_dns ";
        $table->addMulti(t('URLS'), 'SITE_ID', "25", "left", "<br>", "tblheader", "", $sqlDns);
        
        /*** minisite */
        if ($this->isMinisite) {
            $table->addInput(t('See'), "button", array(
                "id" => 'SITE_ID', 
                "" => "readO=false"
            ), "center");
        } else {
            /* minisite ***/
            $table->addInput(t('FORM_BUTTON_EDIT'), "button", array(
                "id" => 'SITE_ID'
            ), "center");
        /*** minisite */
        }
        /*$table->addInput(t('POPUP_LABEL_DEL'), "button", array(
            "id" => 'SITE_ID', 
            "" => "readO=true"
        ), "center");*/
        /* minisite ***/
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        parent::editAction();
        
        $oConnection = Pelican_Db::getInstance();
        
        $oForm = Pelican_Factory::getInstance('Form', true);
        $oForm->bDirectOutput = false;
        $oForm->setTab("1", t('Global parameters'));
        $oForm->setTab("2", t('Alias'));
        $oForm->setTab("3", t('Languages'));
        //        if ($this->id != Pelican::$config["DATABASE_INSERT_ID"]) {
        if (!$this->isMinisite) {
            $oForm->setTab("4", t('Fonctionnalities'));
            $oForm->setTab("5", t('Content types'));
        
     //$oForm->setTab("6", "Configuration du MiniWord");
        } else {
            $oForm->setTab("7", t('Minisite'));
        }
        //}
        

        $form .= $oForm->open(Pelican::$config["DB_PATH"]);
        $form .= $this->beginForm($oForm);
        
        $form .= $oForm->beginTab("1");
        /*** minisite */
        //Dans le cas d'un minisite, on crée l'évenement qui lance l'ajax de génération de l url temporaire
        if ($this->isMinisite || $this->values["SITE_MINISITE"]) {
            $js_action = "onBlur='fill_url()'";
        }
        /* minisite ***/
        
        $form .= $oForm->createHidden($this->field_id, $this->id);
        $form .= $oForm->createInput("SITE_LABEL", t('FIRST_NAME'), 100, "", true, stripslashes($this->values["SITE_LABEL"]), $this->readO, 100, false, $js_action);
        $form .= $oForm->createInput("SITE_TITLE", t('Titre des pages'), 100, "", true, stripslashes($this->values["SITE_TITLE"]), $this->readO, 100);
        $form .= $oForm->createInput("SITE_URL", t('URL principale'), 100, "", true, $this->values["SITE_URL"], $this->readO, 100, false, "onBlur=check_url('SITE_URL','msg_url'," . $this->id . ",100)");
        $form .= "	<tr><td></td><td id='msg_url'></td></tr>";
        
        //	$form .= $oForm->createInput("SITE_MEDIA_URL", "URL des médias", 100, "", false, $this->values["SITE_MEDIA_URL"], $this->readO, 100);
        $form .= $oForm->createInput("SITE_ROOT", t('Root directory'), 50, "", true, $this->values["SITE_ROOT"], $this->readO, 50);
        $form .= $oForm->createComboFromSql($oConnection, "TAG_TYPE_ID", t('Tag type'), "select TAG_TYPE_ID as id, TAG_TYPE_LABEL as lib from #pref#_tag_type order by TAG_TYPE_LABEL", $this->values["TAG_TYPE_ID"], false, $this->readO);
        $form .= $oForm->createInput("TAG_CLIENT", t('Tag identifier'), 50, "", false, $this->values["TAG_CLIENT"], $this->readO, 50);
        //$oForm->createInput("TAG_CLIENT_RSS", "Identifiant Tag Rss", 50, "", false, $this->values["TAG_CLIENT_RSS"], $this->readO, 50);
        //$oForm->createInput("TAG_CLIENT_MEDIA_SUPPORT", "Identifiant Tag Pelican_Media Support", 50, "", false, $this->values["TAG_CLIENT_MEDIA_SUPPORT"], $this->readO, 50);
        $form .= $oForm->createCheckBoxFromList("SITE_MOBAPP", t('Application mobile'), array(
        		"1" => ""
        ), $this->values["SITE_MOBAPP"], false, $this->readO, "h");
        

        $form .= $oForm->beginTab("2");
        $oConnection->query("select SITE_DNS from #pref#_site_dns where SITE_ID=" . $this->id . " order by SITE_DNS");
        $form .= $oForm->createTextArea("SITE_DNS", t('Available alias'), true, $oConnection->data["SITE_DNS"], "", $this->readO, 10, 50);
        //onBlur=\"check_url_textarea('SITE_DNS','div_dns',".$this->id.")
        if (!$this->isMinisite) {
            $form .= $oForm->createCheckBoxFromList("SITE_PRESERVE_DNS", t('Preserve DNS'), array(
                "1" => ""
            ), $this->values["SITE_PRESERVE_DNS"], false, $this->readO, "h");
        }
        
        $form .= $oForm->beginTab("3");
        // langues associées (en plus du francais)
        $strSQLList = "SELECT langue_id as id, " . $oConnection->getConcatClause(array(
            "langue_label", 
            "' ('", 
            "langue_translate", 
            "')'"
        )) . " as lib
				FROM #pref#_language
				-- WHERE langue_id != 1
				ORDER BY lib";
        
        $strSQLSelectedList = "SELECT sl.langue_id as id, l.langue_label as lib
				FROM #pref#_language l, #pref#_site_language sl
				WHERE sl.langue_id= l.langue_id
				AND sl.site_id = " . $this->id . "
				ORDER BY lib";
        $form .= $oForm->createAssocFromSql($oConnection, "assoc_langue_id", t('Site languages'), $strSQLList, $strSQLSelectedList, true, true, $this->readO, 5, 250, false, "");
        
        $form .= $oForm->beginTab("4");
        $form .= $oForm->createLabel(t('RUBRIQUES'), "");
        $_GET["table"] = "DIRECTORY";
        $form .= $oForm->createSubFormHmvc("directory", t('Backend'), array(
            'class' => 'Administration_Directory_Controller', 
            'method' => 'site'
        ), $this->values, $this->readO);
        
        $form .= $oForm->beginTab("5");
        /*  $sqlData = "select CONTENT_TYPE_USE_ID id, CONTENT_TYPE_USE_LABEL lib from #pref#_role order by lib";
		$sqlSelected = "select #pref#_role.CONTENT_TYPE_USE_ID id, CONTENT_TYPE_USE_LABEL lib from #pref#_role, #pref#_user_role where #pref#_role.CONTENT_TYPE_USE_ID=#pref#_user_role.CONTENT_TYPE_USE_ID and USER_LOGIN='".$this->id."' order by lib";
		$form .= $oForm->createAssocFromSql($oConnection, "CONTENT_TYPE_USE_ID", "Rôle(s)", $sqlData, $sqlSelected, true, true, $this->readO, 3, 200);
		*/
        if (($this->id != Pelican::$config["FORM_ID_AJOUT"]) && (strlen($this->id) != 0)) {
            $strQueryData = "SELECT #pref#_content_type_site.*, #pref#_content_type.CONTENT_TYPE_ID as ID, CONTENT_TYPE_LABEL as LIB from
					#pref#_content_type
					left join #pref#_content_type_site on (#pref#_content_type_site.CONTENT_TYPE_ID = #pref#_content_type.CONTENT_TYPE_ID and SITE_ID='" . $this->id . "')
					ORDER BY CONTENT_TYPE_LABEL";
            $aData = $oConnection->queryTab($strQueryData);
        }
        
        if ($aData) {
            $form .= "<tr>";
            $form .= "<td class=\"" . $oForm->sStyleLib . "\">" . t('Content types');
            $form .= "</td>";
            $form .= $oForm->getDisposition();
            $form .= "<td class=\"" . $oForm->sStyleVal . "\">";
            
            $form .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"1\" width=\"100%\" class=\"tableaucroise\">";
            $form .= "<tr>";
            $form .= "<td align=\"center\" class=\"croiselib\" >&nbsp;&nbsp;</td>";
            $form .= "<td align=\"center\"  class=\"croiselib\">Gestion</td>";
            $form .= "<td align=\"center\"  class=\"croiselib\">Emission</td>";
            $form .= "<td align=\"center\"  class=\"croiselib\">Réception</td>";
            $form .= "<td align=\"center\"  class=\"croiselib\">Alerte</td>";
            $form .= "</tr>";
            foreach ($aData as $ligne) {
                $form .= "<tr>";
                $form .= "<td align=\"center\" class=\"croiselib\" >" . $ligne["LIB"] . "</td>";
                $form .= "<td align=\"center\" class=\"croiseval\" >";
                $form .= $oForm->createCheckBoxFromList("CONTENT_TYPE_ID_GESTION[]", "", array(
                    $ligne["ID"] => ""
                ), $ligne["CONTENT_TYPE_ID"], false, $this->readO, "h", true);
                $form .= "</td>";
                $form .= "<td align=\"center\" class=\"croiseval\" >";
                $form .= $oForm->createCheckBoxFromList("CONTENT_TYPE_ID_EMISSION[]", "", array(
                    $ligne["ID"] => ""
                ), ($ligne["CONTENT_TYPE_SITE_EMISSION"] ? $ligne["ID"] : ""), false, $this->readO, "h", true);
                $form .= "</td>";
                $form .= "<td align=\"center\" class=\"croiseval\" >";
                $form .= $oForm->createCheckBoxFromList("CONTENT_TYPE_ID_RECEPTION[]", "", array(
                    $ligne["ID"] => ""
                ), ($ligne["CONTENT_TYPE_SITE_RECEPTION"] ? $ligne["ID"] : ""), false, $this->readO, "h", true);
                $form .= "</td>";
                $form .= "<td align=\"center\" class=\"croiseval\" >";
                //$oForm->createInput("CONTENT_ALERTE_URL[]", "", array($ligne["ID"] => ""), ($ligne["CONTENT_ALERTE_URL"]?$ligne["ID"]:""), false, $this->readO, "h", true);
                if ($ligne["CONTENT_TYPE_ID"]) {
                    $form .= $oForm->createInput($ligne["CONTENT_TYPE_ID"] . "_ALERTE_URL", t('POPUP_MEDIA_LABEL_HTTP'), 100, "", false, $ligne["CONTENT_ALERTE_URL"], $this->readO, 50, true);
                }
                $form .= "</td>";
                $form .= "</tr>";
            }
            $form .= "</table>";
            $form .= "</td></tr>\n";
        }
        
        if (!$this->isMinisite) {
            $form .= $oForm->beginTab("6");
            
            $aDataValues = array(
                'p' => 'Paragraphe', 
                'h1' => 'Titre de niveau 1', 
                'h2' => 'Titre de niveau 2', 
                'h3' => 'Titre de niveau 3', 
                'h4' => 'Titre de niveau 4', 
                'h5' => 'Titre de niveau 5', 
                'h6' => 'Titre de niveau 6'
            );
            $form .= $oForm->createAssocFromList($oConnection, "SITE_TITLE_LEVEL", "Niveaux de titre", $aDataValues, explode(",", $this->values['SITE_TITLE_LEVEL']), false, true, false, 7);
            
            $form .= '<tr><td class="' . $oForm->sStyleLib . '">Style</td><td class="' . $oForm->sStyleVal . '">';
            $form .= '<table><tr><td>';
            $form .= $oForm->createTextArea("SITE_STYLE", "Style", false, implode("\r\n", explode(";", $this->values['SITE_STYLE'])), "", false, 15, 70, true);
            $form .= '</td><td style="vertical-align:top;">';
            $form .= '
			Ecrire les style de la façon suivante :<br />
			Nom du style=Nom_de_la_class1<br/>
			Nom du style 2=Nom_de_la_class2';
            $form .= '</td></tr></table>';
            $form .= '</td></tr>';
        } else { //if ($this->values["SITE_MINISITE"]) {
            $form .= $oForm->beginTab("7");
            $form .= $oForm->createHidden("SITE_MINISITE", $this->values["SITE_MINISITE"]);
            $form .= $oForm->createRadioFromList("SITE_ONLINE", "Mise en mode hors ligne", array(
                "0" => "Non", 
                "1" => "Oui"
            ), $this->values["SITE_ONLINE"], "", $this->readO);
            $form .= $oForm->createEditor("SITE_OFFLINE_MSG", "Contenu de la page en mode hors ligne", "", $this->values["SITE_OFFLINE_MSG"], $this->readO, true, "", 500, 100);
            $form .= "<tr><td colspan=\"2\"><hr/></td></tr>";
            $form .= '<tr><td><span style="font-weight: bold;">URL Temporaire</span></td></tr>';
            
            $form .= "<tr>";
            $form .= "<td>";
            $form .= "URL temporaire :";
            $form .= "</td>";
            $form .= "<td>";
            $form .= "<div id='input_temp_url'>
								<input id='temp_url' disabled size=75 />
								<input type='hidden' id='SITE_URL_TEMP' name='SITE_URL_TEMP' />
						</div>";
            $form .= "</td>";
            $form .= "</tr>";
            
            //$form .=$oForm->createInput("", "URL temporaire", 80, "", false, "<a href='http://".$this->values["SITE_URL"]."' target=_blank>".$this->values["SITE_URL"]."</a>", false, 80);
            $form .= "<tr><td colspan=\"2\"><hr/></td></tr>";
            $form .= '<tr><td><span style="font-weight: bold;">Page d\'accueil événementielle</span></td></tr>';
            $form .= $oForm->createInput("SITE_PAGE_EVENT", "Page associée", 255, "internallink", false, $this->values["SITE_PAGE_EVENT"], $this->readO, 80);
            // Pour régler le problème de riderection en cas d'un minisite
            $form .= $oForm->createHidden("retour", $_SERVER['REQUEST_URI']);
            $form .= "<tr><td colspan=\"2\"><hr/></td></tr>";
            $form .= '<tr><td><span style="font-weight: bold;">Structure du minisite</span></td></tr>';
            $structure_data = $oConnection->queryRow("select ms.ms_structure_label, m.media_path
									from #pref#_ms_structure ms
									left join  #pref#_media m on ms.media_id = m.media_id
									inner join #pref#_site s on s.site_minisite = ms.ms_structure_id and s.site_id = " . $this->id); //$_SESSION[APP]['SITE_ID']
            $form .= $oForm->createInput("label", t('FORM_LABEL'), 80, "", false, $structure_data["ms_structure_label"], true, 80);
            $form .= '<tr><td class="formlib"><label for="aperçu">' . t('FORM_BUTTON_IMG_TITLE') . '</label></td>';
            $form .= '<td class="formval"><img width=140 height=160 name="aperçu" src="' . Pelican::$config["MEDIA_HTTP"] . $structure_data["media_path"] . '"/></td></tr>';
            /* minisite ***/
        }
        
        $form .= $oForm->endTab();
        $form .= $this->beginForm($oForm);
        $form .= $oForm->beginFormTable();
        $form .= $oForm->endFormTable();
        $form .= $this->endForm($oForm);
        $form .= $oForm->close();
        
		// Zend_Form start
		$form = formToString($this->oForm, $form);
        // Zend_Form stop
		
        $this->assign('isMinisite', $this->isMinisite);
        $this->assign('content', $form, false);
        $this->replaceTemplate('index', 'edit');
        $this->fetch();
    }

    public function saveAction()
    {
        
        
        $oConnection = Pelican_Db::getInstance();
        
        Pelican_Db::$values["SITE_DNS"] = Pelican_Form::splitTextarea(Pelican_Db::$values["SITE_DNS"]);
        $tmp = Pelican_Db::$values['LANGUE_ID'];
        Pelican_Db::$values['LANGUE_ID'] = Pelican_Db::$values["assoc_langue_id"];
        
        if (is_array(Pelican_Db::$values['SITE_TITLE_LEVEL']))
            Pelican_Db::$values['SITE_TITLE_LEVEL'] = implode(",", Pelican_Db::$values['SITE_TITLE_LEVEL']);
        
        Pelican_Db::$values['SITE_STYLE'] = Pelican_Form::splitTextarea(Pelican_Db::$values['SITE_STYLE']);
        if (is_array(Pelican_Db::$values['SITE_STYLE']))
            Pelican_Db::$values['SITE_STYLE'] = implode(";", Pelican_Db::$values['SITE_STYLE']);
        
        if ($_SESSION[APP]["IS_MINISITE"]) {
            Pelican_Db::$values["form_retour"] = Pelican_Db::$values["retour"];
        }
        $oConnection->updateForm($this->form_action, $this->processus);
        
        Pelican_Db::$values['LANGUE_ID'] = $tmp;
    
    }

    public function checkUrlAction()
    {
        
        
        
        set_time_limit(0);
        
        $label = $this->getParam(0);
        $url = $this->getParam(1);
        $div = $this->getParam(2);
        $site_id = $this->getParam(3);
        $width = $this->getParam(4);
        
        if (!$_SESSION[APP]["user"]["id"]) {
            echo ("Veuillez vous identifier en Back Office");
            exit();
        }
        
        $oConnection = Pelican_Db::getInstance();
        $oConnection->setExitOnError(false);
        
        $aBind[':URL'] = $oConnection->strtoBind(trim($url));
        $aBind[':ID'] = $oConnection->strtoBind($site_id);
        
        $sSQL = "
			(
				SELECT site_id 
				FROM #pref#_site
				WHERE 
					site_id<>:ID
					and site_url=:URL
					OR site_media_url=:URL
			)
			UNION
			(
				SELECT site_id 
				FROM #pref#_site_dns 
				WHERE 
					site_id<>:ID
					and site_dns=:URL
			)
			";
        $sSQL2 = "
			(
				SELECT site_id 
				FROM #pref#_site
				WHERE 
					site_id<>" . $site_id . "
					and site_url='" . $url . "'
					OR site_media_url='" . $url . "'
			)
			UNION
			(
				SELECT site_id 
				FROM #pref#_site_dns 
				WHERE 
					site_id<>" . $site_id . "
					and site_dns='" . $url . "'
			)
			";
        $aResult = $oConnection->queryRow($sSQL, $aBind);
        if (count($aResult) > 0) //l'url est deja utilisée
{
            $error = "<span style=\"color: red;\">Url d&eacute;j&agrave; utilis&eacute;e : " . $url . "</span>";
            $url = "";
        
        } else { //la nouvelle url est valide
            $error = "";
        }
        
        $this->getRequest()->addResponseCommand('assign', array(
            'id' => $label, 
            'attr' => 'value', 
            'value' => $url
        ));
        $this->getRequest()->addResponseCommand('assign', array(
            'id' => $div, 
            'attr' => 'innerHTML', 
            'value' => $error
        ));
    
    }

    public function afterInsert()
    {
        
        
        $oConnection = Pelican_Db::getInstance();
        
        Pelican_Db::$values["PAGE_CREATION_DATE"] = ":DATE_COURANTE";
        Pelican_Db::$values["PAGE_CREATION_USER"] = $_SESSION[APP]["user"]["id"];
        Pelican_Db::$values["PAGE_STATUS"] = 1;
        
        /** Template de navigation */
        //$navigation = $oConnection->getNextId("#pref#_template_page", "template_page_id");
        Pelican_Db::$values["TEMPLATE_PAGE_ID"] = -2; //$navigation;
        Pelican_Db::$values["PAGE_TYPE_ID"] = getPageTypeId('GENERAL');
        Pelican_Db::$values["PAGE_GENERAL"] = (Pelican_Db::$values["PAGE_TYPE_ID"] == getPageTypeId('GENERAL'));
        Pelican_Db::$values["TEMPLATE_PAGE_LABEL"] = "- Général -";
        Pelican_Db::$values["PAGE_ID"] = -2; //$oConnection->getNextId("#pref#_page", "page_id");
        Pelican_Db::$values["PAGE_DRAFT_VERSION"] = 1;
        Pelican_Db::$values["PAGE_ORDER"] = 1;
        Pelican_Db::$values["PAGE_TITLE"] = "- Général -";
        Pelican_Db::$values["PAGE_TITLE_BO"] = "- Général -";
        Pelican_Db::$values["PAGE_VERSION"] = 1;
        Pelican_Db::$values["STATE_ID"] = 1;
        
        $oConnection->insertQuery("#pref#_template_page");
        $oConnection->insertQuery("#pref#_page");
        $oConnection->insertQuery("#pref#_page_version");
        
        /** Template de la Home */
        //$home = $oConnection->getNextId("#pref#_template_page", "template_page_id");
        Pelican_Db::$values["TEMPLATE_PAGE_ID"] = -2; //$home;
        Pelican_Db::$values["PAGE_TYPE_ID"] = getPageTypeId('HOME');
        
        Pelican_Db::$values["PAGE_GENERAL"] = (Pelican_Db::$values["PAGE_TYPE_ID"] == getPageTypeId('GENERAL'));
        Pelican_Db::$values["TEMPLATE_PAGE_LABEL"] = "- Accueil -";
        Pelican_Db::$values["PAGE_ID"] = -2; //$oConnection->getNextId("#pref#_page", "page_id");
        Pelican_Db::$values["PAGE_DRAFT_VERSION"] = 1;
        Pelican_Db::$values["PAGE_ORDER"] = 2;
        Pelican_Db::$values["PAGE_TITLE"] = "Accueil";
        Pelican_Db::$values["PAGE_TITLE_BO"] = "Accueil";
        Pelican_Db::$values["PAGE_VERSION"] = 1;
        Pelican_Db::$values["STATE_ID"] = 1;
        
        $oConnection->insertQuery("#pref#_template_page");
        $oConnection->insertQuery("#pref#_page");
        $oConnection->insertQuery("#pref#_page_version");
        
        /** Template de contenu */
        //$content = $oConnection->getNextId("#pref#_template_page", "template_page_id");
        Pelican_Db::$values["TEMPLATE_PAGE_ID"] = -2; //$content;
        Pelican_Db::$values["PAGE_TYPE_ID"] = getPageTypeId('CONTENT');
        Pelican_Db::$values["PAGE_GENERAL"] = (Pelican_Db::$values["PAGE_TYPE_ID"] == getPageTypeId('GENERAL'));
        Pelican_Db::$values["TEMPLATE_PAGE_LABEL"] = "- Contenu -";
        
        $oConnection->insertQuery("#pref#_template_page");
        
        /** Mediatheque */
        //$media = $oConnection->getNextId("#pref#_media_directory", "media_directory_id");//$oConnection->queryItem("select SEQ_MEDIA_DIRECTORY.NEXTVAL from DUAL");
        Pelican_Db::$values["MEDIA_DIRECTORY_ID"] = -2; //$media;
        Pelican_Db::$values["MEDIA_DIRECTORY_LABEL"] = "Mediathèque";
        Pelican_Db::$values["MEDIA_DIRECTORY_PATH"] = "Mediathèque";
        $oConnection->insertQuery("#pref#_media_directory");
        
        /** Profile */
        //$profile = $oConnection->getNextId("#pref#_profile", "profile_id");//$oConnection->queryItem("select SEQ_PROFILE.NEXTVAL from DUAL");
        Pelican_Db::$values["PROFILE_ID"] = -2; //$profile;
        Pelican_Db::$values["PROFILE_LABEL"] = "Administration";
        Pelican_Db::$values["PROFILE_ADMIN"] = "0";
        $oConnection->insertQuery("#pref#_profile");
        
        /** Affectation à l'admin */
        Pelican_Db::$values["USER_LOGIN"] = $_SESSION[APP]["user"]["id"];
        $oConnection->insertQuery("#pref#_user_profile");
        
        /** Accès */
        /*$acces = "select DIRECTORY_ID from #pref#_directory where directory_default = 1";
            $oConnection->query($acces);
            Pelican_Db::$values["DIRECTORY_ID"] = $oConnection->data["DIRECTORY_ID"];
            $oConnection->updateTable(Pelican_Db::DATABASE_INSERT, "#pref#_directory_site", "DIRECTORY_ID");
            */
        foreach (Pelican_Db::$values["DIRECTORY_ID"] as $dir) {
            $i ++;
            $oConnection->Query("INSERT INTO #pref#_profile_directory ( PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER ) VALUES ( " . Pelican_Db::$values["PROFILE_ID"] . ", " . $dir . ", " . Pelican_Db::$values["PROFILE_ID"] . str_pad($i, 3, "0", STR_PAD_LEFT) . ")");
        }
        
        /** Type de contenu */
        $tc = "select CONTENT_TYPE_ID from #pref#_content_type where CONTENT_TYPE_DEFAULT = 1";
        $oConnection->query($tc);
        Pelican_Db::$values["CONTENT_TYPE_ID"] = $oConnection->data["CONTENT_TYPE_ID"];
        $oConnection->updateTable(Pelican_Db::DATABASE_INSERT, "#pref#_content_type_site", "CONTENT_TYPE_ID");
    
    }

    public function afterUpdate()
    {
        
        
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = Pelican_Db::$values['SITE_ID'];
        $oConnection->query('delete from #pref#_profile_directory
		where profile_id in (select profile_id from #pref#_profile where SITE_ID=:SITE_ID) and directory_id not in (select directory_id from #pref#_directory_site where site_id=:SITE_ID)', $aBind);
    }

    public function beforeDelete()
    {
        
        
        $oConnection = Pelican_Db::getInstance();
        
        $cascadeDelete = array(
            '#pref#_site_parameter_dns', 
            '#pref#_site_parameter', 
            array(
                '#pref#_content_version', 
                'content'
            ), 
            array(
                '#pref#_paragraph', 
                'content'
            ), 
            array(
                '#pref#_paragraph_media', 
                'content'
            ), 
            '#pref#_content', 
            '#pref#_content_category', 
            
            array(
                '#pref#_media', 
                'media_directory'
            ), 
            '#pref#_media_directory', 
            
            array(
                '#pref#_navigation', 
                'page'
            ), 
            array(
                '#pref#_page_zone', 
                'page'
            ), 
            array(
                '#pref#_page_zone_content', 
                'page'
            ), 
            array(
                '#pref#_page_zone_media', 
                'page'
            ), 
            array(
                '#pref#_page_version', 
                'page'
            ), 
            '#pref#_page', 
            '#pref#_template_site', 
            array(
                '#pref#_template_page_area', 
                'template_page'
            ), 
            array(
                '#pref#_zone_template', 
                'template_page'
            ), 
            '#pref#_template_page', 
            array(
                '#pref#_user_profile', 
                'profile'
            ), 
            array(
                '#pref#_profile_directory', 
                'profile'
            ), 
            '#pref#_profile', 
            array(
                '#pref#_subscription', 
                'subscriber'
            ), 
            '#pref#_subscriber', 
            '#pref#_service', 
            '#pref#_acl_role', 
            '#pref#_comment', 
            array(
                '#pref#_terms_group_rel', 
                'terms_group'
            ), 
            array(
                '#pref#_terms', 
                'terms_group'
            ), 
            '#pref#_terms_group', 
            '#pref#_rewrite', 
            '#pref#_pub', 
            '#pref#_tag', 
            '#pref#_research_log', 
            '#pref#_research', 
            '#pref#_research_param', 
            '#pref#_research_param_field'
        );
        
        $oConnection->cascadeDelete('site', $cascadeDelete);
    }

    public static function siteContentType()
    {
        
        
        $oConnection = Pelican_Db::getInstance();
        
        $DBVALUES_MONO = Pelican_Db::$values;
        
        $oConnection->query("delete from #pref#_content_type_site where SITE_ID='" . Pelican_Db::$values['SITE_ID'] . "'");
        if (Pelican_Db::$values["CONTENT_TYPE_ID_GESTION"] && Pelican_Db::$values["form_action"] != Pelican_Db::DATABASE_DELETE) {
            if (!$DBVALUES_MONO["CONTENT_TYPE_ID_EMISSION"]) {
                $DBVALUES_MONO["CONTENT_TYPE_ID_EMISSION"] = array();
            }
            if (!$DBVALUES_MONO["CONTENT_TYPE_ID_RECEPTION"]) {
                $DBVALUES_MONO["CONTENT_TYPE_ID_RECEPTION"] = array();
            }
            if (!$DBVALUES_MONO["CONTENT_ALERTE"]) {
                $DBVALUES_MONO["CONTENT_ALERTE"] = array();
            }
            if (!$DBVALUES_MONO["CONTENT_ALERTE_URL"]) {
                $DBVALUES_MONO["CONTENT_ALERTE_URL"] = array();
            }
            foreach ($DBVALUES_MONO["CONTENT_TYPE_ID_GESTION"] as $content_id) {
                Pelican_Db::$values = array();
                Pelican_Db::$values['SITE_ID'] = $DBVALUES_MONO['SITE_ID'];
                Pelican_Db::$values["CONTENT_TYPE_ID"] = $content_id;
                Pelican_Db::$values["CONTENT_TYPE_SITE_EMISSION"] = (in_array($content_id, $DBVALUES_MONO["CONTENT_TYPE_ID_EMISSION"]) ? "1" : "");
                Pelican_Db::$values["CONTENT_TYPE_SITE_RECEPTION"] = (in_array($content_id, $DBVALUES_MONO["CONTENT_TYPE_ID_RECEPTION"]) ? "1" : "");
                if ($DBVALUES_MONO[Pelican_Db::$values["CONTENT_TYPE_ID"] . "_ALERTE_URL"]) {
                    Pelican_Db::$values["CONTENT_ALERTE"] = '1';
                    Pelican_Db::$values["CONTENT_ALERTE_URL"] = $DBVALUES_MONO[Pelican_Db::$values["CONTENT_TYPE_ID"] . "_ALERTE_URL"];
                } else {
                    Pelican_Db::$values["CONTENT_ALERTE"] = '';
                    Pelican_Db::$values["CONTENT_ALERTE_URL"] = '';
                }
                $oConnection->insertQuery("#pref#_content_type_site");
            }
        }
        Pelican_Db::$values = $DBVALUES_MONO;
    }

}
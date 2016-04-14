<?php

pelican_import('Hierarchy');

class Administration_Directory_Controller extends Pelican_Controller_Back
{
    protected $administration = true;
    protected $form_name = "directory";
    protected $field_id = "DIRECTORY_ID";
    protected $processus = array(
        "#pref#_directory",
        array(
            "#pref#_directory_site",
            "SITE_ID",
        ),
    );
    public static $jsUsed = false;
    protected $table = 'directory';
    protected $oTree;

    public function init()
    {
        $oConnection = Pelican_Db::getInstance();

        /* Pour la configuration de l'arbre */
        $_SESSION[APP]["tree_profile"] = false;

        /* Sélection des rubriques à explorer */
        if (!$_GET["type"]) {
            $_GET["type"] = "D";
        }
        switch ($_GET["type"]) {
            case "D": {
                    $this->form_name = "directory";
                    $this->table = "directory";
                    break;
                }
            case "P": {
                    $this->form_name = "page";
                    $this->table = "page";
                    break;
                }
        }

        $this->field_id = strtoupper($this->table)."_ID";

        if ($_GET["id"]) {
            $this->id = $_GET["id"];
        }

        /* variables par défaut */
        if (!$this->id) {
            $this->id = "9999";
        }

        /* Pour ne pas avoir de valeur vide dans les url */
        if (!$_GET["pid"]) {
            $_GET["pid"] = "0";
        }

        /* Création de l'arborescence */
        $sql = $this->_getTreeSQL($this->table, $_GET["type"]);

        $directory = $oConnection->queryTab($sql);
        foreach($directory as $keyDir => $valDir) {
            $titleTranslate = t($valDir['order']);
            if(strpos($titleTranslate, "[cle1:") !== false) {
                $titleTranslate = $valDir['order'];
           }
           $directory[$keyDir]['lib'] = Pelican_Html::b($titleTranslate);
        }
        $directory[] = array(
            "id" => "9999",
            "lib" => t('(Root)'),
            "order" => "1",
            "url" => "javascript:javascript:setDirectory('9999', null, '".$_GET["type"]."')",
        );
        if ($this->id == Pelican::$config["DATABASE_INSERT_ID"]) {
            /* Dans le cas de l'insertion, on crée une rubrique fictive */
            $directory[] = array(
                "id" => $this->id,
                "lib" => t('New page'),
                "pid" => (!$_GET["pid"] ? "9999" : $_GET["pid"]),
                "order" => "1",
                "url" => "javascript:void(0)",
            );
        }

        $this->oTree = Pelican_Factory::getInstance('Hierarchy.Tree', "dtreeDirectory", "id", "pid");
        $this->oTree->addTabNode($directory);
        $this->oTree->setOrder("order", "ASC");
        $this->oTree->setTreeType("dtree");

        /* Création des javascript par défaut */
        /* if ($_GET["pid"] != "9999") {
          $js = "dtreeDirectory.o(" . ($this->oTree->aPosition[$_GET["pid"]] - 1) . ");";
          } */
        if ($this->id != "9999") {
            if ($this->id == - 2) {
                $js = "dtreeDirectory.o(".($this->oTree->aPosition[$this->id] + 1).");";
            } else {
                $js = "dtreeDirectory.o(".($this->oTree->aPosition[$this->id] - 1).");";
            }
        }
        $js = "dtreeDirectory.s(".($this->oTree->aPosition[$this->id] - 1).");";
        $this->assign('js', $js, false);
    }

    protected function setEditModel()
    {
        $this->editModel = "SELECT * from #pref#_".strtolower($this->table)." WHERE ".strtoupper($this->table)."_ID='".$this->id."'";
    }

    public function listAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $oConnection = Pelican_Db::getInstance();

        $parse_url = parse_url($_SERVER["REQUEST_URI"]);
        parse_str($parse_url['query'], $arr);
        if ($this->id != Pelican::$config["DATABASE_INSERT_ID"]) {
            $arr['pid'] = $this->id;
        } else {
            $arr['pid'] = '';
        }
        $arr['id'] = Pelican::$config["DATABASE_INSERT_ID"];
        $this->sAddUrl = $parse_url['path'].'?'.http_build_query($arr);

        parent::editAction();

        if ($this->id != "9999") {
            $this->aButton["add"] = $this->sAddUrl;

            $_SESSION[APP]["session_start_page"] = $_SERVER["REQUEST_URI"];

            $parent = ($_GET["pid"] != $this->id ? $_GET["pid"] : "");
            ob_start();
            $oForm = Pelican_Factory::getInstance('Form', true);
            $oForm->open(Pelican::$config["DB_PATH"]);
            beginFormTable();
            $this->beginForm($oForm);

            $oForm->createInput(strtoupper($this->table)."_LABEL", t('FIRST_NAME'), 50, "", true, $this->values[strtoupper($this->table)."_LABEL"], $this->readO, 50);

            $oForm->createComboFromSql($oConnection, "TEMPLATE_ID", t('TEMPLATE'), "select TEMPLATE_ID as id, ".$oConnection->getConcatClause(array(
                    "TEMPLATE_GROUP_LABEL",
                    "' - '",
                    "TEMPLATE_LABEL",
                ))." as lib from #pref#_template t INNER JOIN #pref#_template_group tg on (t.TEMPLATE_GROUP_ID=tg.TEMPLATE_GROUP_ID) WHERE TEMPLATE_TYPE_ID".(!$parent ? "=2" : "!=2")." order by lib", $this->values["TEMPLATE_ID"], false, $this->readO);
            $oForm->createInput("TEMPLATE_COMPLEMENT", t('Complement'), 50, "", false, $this->values["TEMPLATE_COMPLEMENT"], $this->readO, 50);

            if (!$parent && $this->table == "directory") {
                /* seulement pour les niveaux 1 */
                $oForm->createInput(strtoupper($this->table)."_LEFT_LABEL", t('Left label'), 50, "", false, $this->values[strtoupper($this->table)."_LEFT_LABEL"], $this->readO, 50);
            }

            $oForm->createCheckBoxFromList("DIRECTORY_DEFAULT", t('By default'), array(
                "1" => "",
                ), $this->values["DIRECTORY_DEFAULT"], false, $this->readO, "h");

            if ($parent) {
                $oForm->createInput(strtoupper($this->table)."_ICON", t('Icon'), 50, "", false, $this->values[strtoupper($this->table)."_ICON"], $this->readO, 50);
            }

            // Les profils disponibles
            $oForm->showSeparator();
            $sqlData = "select #pref#_site.SITE_ID as id, SITE_LABEL as lib from #pref#_site order by lib";
            $sqlSelected = "select #pref#_site.SITE_ID as id, SITE_LABEL as lib from #pref#_site, ".Pelican::$config['FW_PREFIXE_TABLE'].$this->table."_site where #pref#_site.SITE_ID=".Pelican::$config['FW_PREFIXE_TABLE'].$this->table."_site.SITE_ID and ".strtoupper($this->table)."_ID='".$this->id."' order by lib";
            $oForm->createAssocFromSql($oConnection, "SITE_ID", t('Sites'), $sqlData, $sqlSelected, false, true, $this->readO, 8, 200, false);

            $oForm->createHidden(strtoupper($this->table)."_PARENT_ID", ($parent && $parent != "9999" ? $parent : "0"));
            $oForm->createHidden($this->field_id, $this->id);

            if (!$this->oTree->aParams[$this->id]["child"]) {
                $this->aButton["delete"] = $oForm->sFormName;
            }
            if (!$this->readO) {
                $this->aButton["save"] = $oForm->sFormName;
            }

            $this->endForm($oForm, "directory");
            endFormTable();
            $oForm->close();
            $content = ob_get_contents();
            ob_clean();

            // Zend_Form start
            $content = formToString($oForm, $content);
            // Zend_Form stop
        } else {
            $title = $this->getTemplateTitle($this->getView()->getHead()->sTitle, t('Edition'));
            $this->aButton["add"] = ($this->sAddUrl ? $this->sAddUrl : "");
            Backoffice_Button_Helper::init($this->aButton);
        }

        if (!$content) {
            $content = Pelican_Html::img(array(
                    'border' => 0,
                    'src' => Pelican::$config["LIB_PATH"]."/index/images/pixel.gif",
                    'width' => "500",
                    'height' => "1",
            ));
        }
        $content = Pelican_Html::nobr(Pelican_Html::span(array(
                    "class" => "path",
                    ), $this->oTree->getNodePath($this->id, "lib", " > ", "", false))).$content;
        $this->assign('content', (Pelican_Html::table("", Pelican_Html::tr("", Pelican_Html::td(array(
                        'valign' => "top",
                        'width' => "200px",
                        ), Pelican_Html::div(array(
                            'style' => "float:left;overflow-y: scroll;overflow-x: scroll;width:200px;border: #CACACA 1px solid;height:390px;bacground-color:white;",
                            ), $this->oTree->getTree())).Pelican_Html::td(array(
                        'valign' => "top",
                        ), Pelican_Html::div(array(
                            'style' => "float:left;width:80%",
                            'valign' => "top",
                            ), $content))))), false);
        $this->assign('url', str_replace("&id=".$this->id, "", str_replace("&pid=".$_GET["pid"], "", $_SERVER["REQUEST_URI"])));
        $this->replaceTemplate('index', 'edit');
        $this->fetch();
    }

    public function saveAction()
    {
        $oConnection = Pelican_Db::getInstance();

        if (Pelican_Db::$values['form_button'] == 'delete') {
            Pelican_Db::$values['form_action'] = Pelican::$config["DATABASE_DELETE"];
            $this->form_action = Pelican_Db::$values['form_action'];
        }
        if (!isset($TYPE)) {
            $TYPE = "DIRECTORY";
        }
        Pelican_Db::$values["DIRECTORY_ADMIN"] = (Pelican_Db::$values["DIRECTORY_ADMIN"] ? "1" : "0");

        if (!Pelican_Db::$values[$TYPE."_PARENT_ID"]) {
            Pelican_Db::$values[$TYPE."_PARENT_ID"] = "";
        }

        if ($TYPE == "DIRECTORY" && Pelican_Db::$values["form_action"] == Pelican::$config["DATABASE_DELETE"]) {
            // suppression de l'association de la fonctionnalité avec les profils
            $oConnection->query("DELETE FROM #pref#_profile_directory WHERE DIRECTORY_ID = ".Pelican_Db::$values["DIRECTORY_ID"]);
        }

        parent::saveAction();
        //$oConnection->updateForm(Pelican_Db::$values["form_action"], $PROCESSUS[Pelican_Db::$values["form_name"]]);


        Pelican_Db::$values[$TYPE."_ADMIN"] = (Pelican_Db::$values[$TYPE."_ADMIN"] ? "1" : "0");

        if (Pelican_Db::$values["form_action"] == Pelican::$config["DATABASE_DELETE"]) {
            Pelican_Db::$values["form_retour"] = str_replace("&id=".Pelican_Db::$values[$TYPE."_ID"], "&id=".Pelican_Db::$values[$TYPE."_PARENT_ID"], Pelican_Db::$values["form_retour"]);
        } else {
            /* Si un menu est de niveau 1, on met à jour les fils */
            if (!Pelican_Db::$values[$TYPE."_PARENT_ID"] && Pelican_Db::$values[$TYPE."_ID"] && $TYPE == "DIRECTORY") {
                $oConnection->query("update ".Pelican::$config['FW_PREFIXE_TABLE'].strtolower($TYPE)." set ".$TYPE."_ADMIN=".Pelican_Db::$values[$TYPE."_ADMIN"]." where ".$TYPE."_PARENT_ID=".Pelican_Db::$values[$TYPE."_ID"]);
            }

            /* Par mesure de précaution */
            $oConnection->query("update ".Pelican::$config['FW_PREFIXE_TABLE'].strtolower($TYPE)." set ".$TYPE."_PARENT_ID=NULL where ".$TYPE."_PARENT_ID=0");

            Pelican_Db::$values["form_retour"] = str_replace("&id=".Pelican::$config["DATABASE_INSERT_ID"], "&id=".Pelican_Db::$values[$TYPE."_ID"], Pelican_Db::$values["form_retour"]);
        }

        /* Intégrité référentielle applicative : suppression des entrées associées aux profils */
        if (Pelican_Db::$values["SITE_ID"] && Pelican_Db::$values["SITE_ID"][0]) {
            if (!is_array(Pelican_Db::$values["SITE_ID"])) {
                Pelican_Db::$values[$TYPE."_ID"] = array(
                    Pelican_Db::$values["SITE_ID"],
                );
            }
            /* Sélection des profils concernés */
            $oConnection->query("SELECT PROFILE_ID FROM #pref#_profile WHERE SITE_ID IN (".implode(",", Pelican_Db::$values["SITE_ID"]).")");
            $profiles = $oConnection->data["PROFILE_ID"];

            if ($profiles) {
                $oConnection->query("DELETE FROM #pref#_profile_".strtolower($TYPE)." WHERE PROFILE_ID NOT IN (".implode(",", $profiles).") AND ".$TYPE."_ID=".Pelican_Db::$values[$TYPE."_ID"]);
            }
        }
    }

    public static function page(&$oForm, $values, $readO)
    {
        $table = "PAGE";
        $sql = '
		select
		p.PAGE_ID as "id",
		PAGE_PARENT_ID as "parent_id",
		PAGE_TITLE_BO as "lib",
		PAGE_ORDER as "order",
		PAGE_CREATION_USER as "creation_user"
		FROM
		#pref#_page p
		INNER JOIN #pref#_page_version pv on (p.PAGE_ID = pv.PAGE_ID AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION AND p.LANGUE_ID = pv.LANGUE_ID)
		WHERE
		p.SITE_ID='.$values["SITE_ID"].'
		AND p.LANGUE_ID = '.($_SESSION[APP]["LANGUE_ID_EDITORIAL_DEFAUT"] ? $_SESSION[APP]["LANGUE_ID_EDITORIAL_DEFAUT"] : 1);

        return self::Pelican_Hierarchy_Tree($oForm, $sql, $table, $values, $readO);
    }

    public static function pageCopieColle(&$oForm, $values, $readO)
    {
        $table = "PAGE";
        $sql = '
		select
		p.PAGE_ID as "id",
		PAGE_PARENT_ID as "parent_id",
		PAGE_TITLE_BO as "lib",
		PAGE_ORDER as "order",
		PAGE_CREATION_USER as "creation_user"
		FROM
		#pref#_page p
		INNER JOIN #pref#_page_version pv on (p.PAGE_ID = pv.PAGE_ID AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION AND p.LANGUE_ID = pv.LANGUE_ID)
		WHERE
		p.SITE_ID='.$values["SITE_ID"].'
        AND (PAGE_GENERAL = 0 OR PAGE_GENERAL IS NULL)
    	AND pv.STATE_ID <> 5
		AND p.LANGUE_ID = '.($values["LANGUE_ID"] ? $values["LANGUE_ID"] : 1).' order by PAGE_ORDER';

        return self::Pelican_Hierarchy_Tree($oForm, $sql, $table, $values, $readO);
    }

    public static function pageDiffusion(&$oForm, $values, $readO)
    {
        $table = "PAGE";
        $sql = '
		select
		p.PAGE_ID as "id",
		PAGE_PARENT_ID as "parent_id",
		PAGE_TITLE_BO as "lib",
		PAGE_ORDER as "order",
		"nobody" as "creation_user",
		pt.PAGE_TYPE_UNIQUE as readO
		FROM
		#pref#_page p
		INNER JOIN #pref#_page_version pv on (p.PAGE_ID = pv.PAGE_ID AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION AND p.LANGUE_ID = pv.LANGUE_ID)
		INNER JOIN #pref#_template_page tp on (pv.TEMPLATE_PAGE_ID = tp.TEMPLATE_PAGE_ID)
		INNER JOIN #pref#_page_type pt on (tp.PAGE_TYPE_ID = pt.PAGE_TYPE_ID)
		WHERE
		p.SITE_ID='.$values["SITE_ID"].'
        AND (PAGE_GENERAL = 0 OR PAGE_GENERAL IS NULL)
    	AND pv.STATE_ID <> 5
		AND p.LANGUE_ID = '.($values["LANGUE_ID"] ? $values["LANGUE_ID"] : 1).' order by PAGE_ORDER';

        return self::Pelican_Hierarchy_Tree($oForm, $sql, $table, $values, $readO);
    }

    public static function pageArbo(&$oForm, $values, $readO)
    {
        $_SESSION[APP]["tree_profile"] = false;
        $table = "ARBO";
        $sql = '
            select
                p.PAGE_ID as "id",
                PAGE_PARENT_ID as "parent_id",
                PAGE_TITLE_BO as "lib",
                PAGE_ORDER as "order",
                PAGE_CREATION_USER as "creation_user"
            FROM #pref#_page p
            INNER JOIN #pref#_page_version pv
            on (p.PAGE_ID = pv.PAGE_ID
                AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION
                AND p.LANGUE_ID = pv.LANGUE_ID)
            WHERE p.SITE_ID='.$values["SITE_ID"].'
            AND p.LANGUE_ID = '.($_SESSION[APP]["LANGUE_ID_EDITORIAL_DEFAUT"] ? $_SESSION[APP]["LANGUE_ID_EDITORIAL_DEFAUT"] : 1).'
            AND (PAGE_GENERAL = 0 OR PAGE_GENERAL IS NULL)
            AND pv.STATE_ID <> 5
            ORDER BY PAGE_ORDER';

        return self::Pelican_Hierarchy_Tree($oForm, $sql, $table, $values, $readO);
    }

    public static function pageArboSource(&$oForm, $values, $readO)
    {
        $_SESSION[APP]["tree_profile"] = false;
        $table = "ARBORESCENCE_SOURCE";
        $sql = '
            select
                p.PAGE_ID as "id",
                PAGE_PARENT_ID as "parent_id",
                PAGE_TITLE_BO as "lib",
                PAGE_ORDER as "order",
                PAGE_CREATION_USER as "creation_user"
            FROM #pref#_page p
            INNER JOIN #pref#_page_version pv
            on (p.PAGE_ID = pv.PAGE_ID
                AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION
                AND p.LANGUE_ID = pv.LANGUE_ID)
            WHERE p.SITE_ID='.$values["SITE_ID"].'
            AND p.LANGUE_ID = '.$values["LANGUE_ID"].'
            AND (PAGE_GENERAL = 0 OR PAGE_GENERAL IS NULL)
            AND pv.STATE_ID <> 5
            ORDER BY PAGE_ORDER';

        return self::Pelican_Hierarchy_Tree($oForm, $sql, $table, $values, $readO);
    }

    public static function pageArboCible(&$oForm, $values, $readO)
    {
        $_SESSION[APP]["tree_profile"] = false;
        $table = "ARBORESCENCE_CIBLE";
        $sql = '
            select
                p.PAGE_ID as "id",
                PAGE_PARENT_ID as "parent_id",
                PAGE_TITLE_BO as "lib",
                PAGE_ORDER as "order",
                PAGE_CREATION_USER as "creation_user"
            FROM #pref#_page p
            INNER JOIN #pref#_page_version pv
            on (p.PAGE_ID = pv.PAGE_ID
                AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION
                AND p.LANGUE_ID = pv.LANGUE_ID)
            WHERE p.SITE_ID='.$values["SITE_ID"].'
            AND p.LANGUE_ID = '.$_SESSION[APP]["LANGUE_ID"].'
            AND (PAGE_GENERAL = 0 OR PAGE_GENERAL IS NULL)
            AND pv.STATE_ID <> 5
            ORDER BY PAGE_ORDER';

        return self::Pelican_Hierarchy_Tree($oForm, $sql, $table, $values, $readO);
    }

    public static function profile(&$oForm, $values, $readO)
    {
        if ($values["PROFILE_ID"] != "") {
            $oConnection = Pelican_Db::getInstance();
            $table = "DIRECTORY";
            $sql = " SELECT
			#pref#_directory.DIRECTORY_ID as \"id\",
			DIRECTORY_PARENT_ID as \"parent_id\",
			DIRECTORY_LABEL as \"lib\",
			#pref#_profile_directory.DIRECTORY_ID as \"used\",
			DIRECTORY_ADMIN as \"admin\"
			FROM #pref#_directory
			inner join #pref#_directory_site on (#pref#_directory.DIRECTORY_ID=#pref#_directory_site.DIRECTORY_ID and #pref#_directory_site.SITE_ID = ".$values["SITE_ID"].")
			left join #pref#_profile_directory on (#pref#_profile_directory.DIRECTORY_ID = #pref#_directory.DIRECTORY_ID and PROFILE_ID =".$values["PROFILE_ID"].")
			order by ".$oConnection->getConcatClause(array(
                    $oConnection->getNVLClause("PROFILE_DIRECTORY_ORDER", 9999),
                    "'_'",
                    "DIRECTORY_LABEL",
                )).", DIRECTORY_LABEL";

            return self::Pelican_Hierarchy_Tree($oForm, $sql, $table, $values, $readO);
        } else {
            return false;
        }
    }

    /*
     * Création de l'arbre de page en corbeille
     * @param array &$oForm Formulaire
     * @param array $values Valeur du formulaire
     * @param bool $readO
     */

    public static function corbeille(&$oForm, $values, $readO)
    {
        $table = "CORBEILLE";

        //Sql qui recherche les parents non présents
        $sqlIn = "
			SELECT DISTINCT p.PAGE_ID
			FROM #pref#_page p
			INNER JOIN #pref#_page_version pv
				ON (p.PAGE_ID = pv.PAGE_ID AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION)
			WHERE p.SITE_ID=".$values['SITE_ID'];
        //on n'affiche pas les éléments de la corbeille
        $sqlIn .= " AND pv.STATE_ID = ".$values['STATE_ID'];

        $sqlIn2 = "
			SELECT DISTINCT c.CONTENT_ID
			FROM #pref#_content c
			INNER JOIN #pref#_content_version cv
				ON (c.CONTENT_ID = cv.CONTENT_ID AND c.CONTENT_DRAFT_VERSION = cv.CONTENT_VERSION)
			WHERE c.SITE_ID=".$values['SITE_ID']."
			AND cv.STATE_ID = ".$values['STATE_ID'];

        //sql pour mettre à 0 le parent_id du premier niveau
        $sql1 = "
			SELECT
				p.PAGE_ID AS \"id\",
				0 AS \"parent_id\",
				PAGE_TITLE_BO AS \"lib\"
			FROM #pref#_page p
			INNER JOIN #pref#_page_version pv
				ON (p.PAGE_ID = pv.PAGE_ID AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION)
			WHERE p.SITE_ID = ".$values['SITE_ID'];
        //on n'affiche pas les éléments de la corbeille
        $sql1 .= " AND pv.STATE_ID = ".$values['STATE_ID'];

        //Sql qui récupère toutes les pages en corbeille
        $sql2 = "
			SELECT
				p.PAGE_ID AS \"id\",
				PAGE_PARENT_ID AS \"parent_id\",
				PAGE_TITLE_BO AS \"lib\"
			FROM #pref#_page p
			INNER JOIN #pref#_page_version pv
				ON (p.PAGE_ID = pv.PAGE_ID AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION)
			WHERE p.SITE_ID = ".$values['SITE_ID'];
        //on n'affiche pas les éléments de la corbeille
        $sql2 .= " AND pv.STATE_ID = ".$values['STATE_ID'];

        //Sql qui récupère toutes les contenus en corbeille
        $sql3 = "
			SELECT DISTINCT
				CONCAT('cid-', c.CONTENT_ID) AS \"id\",
				0 AS \"parent_id\",
				cv.CONTENT_TITLE_BO AS \"lib\"
			FROM #pref#_content c
			INNER JOIN #pref#_content_version cv
				ON (c.CONTENT_ID = cv.CONTENT_ID AND c.CONTENT_DRAFT_VERSION = cv.CONTENT_VERSION)
			WHERE c.SITE_ID = ".$values['SITE_ID'];

        $sSql = $sql1." AND page_parent_id NOT IN (".$sqlIn.")
            UNION ".
            $sql2." AND page_parent_id IN (".$sqlIn.")
			UNION ".
            $sql3." AND c.CONTENT_ID IN (".$sqlIn2.")";

        return self::Pelican_Hierarchy_Tree($oForm, $sSql, $table, $values, $readO);
    }

    public static function site(&$oForm, $values, $readO)
    {
        $table = "DIRECTORY";
        $sql = " SELECT
			#pref#_directory.DIRECTORY_ID as \"id\",
			DIRECTORY_PARENT_ID as \"parent_id\",
			DIRECTORY_LABEL as \"lib\",
			".($values["SITE_ID"] ? "#pref#_directory_site.DIRECTORY_ID" : "DIRECTORY_DEFAULT")." as \"used\",
			DIRECTORY_ADMIN as \"admin\"
			FROM #pref#_directory
			left join #pref#_directory_site on (#pref#_directory.DIRECTORY_ID=#pref#_directory_site.DIRECTORY_ID  and #pref#_directory_site.SITE_ID = ".($values["SITE_ID"] ? $values["SITE_ID"] : 0).")";

        return self::Pelican_Hierarchy_Tree($oForm, $sql, $table, $values, $readO);
    }

    public static function Pelican_Hierarchy_Tree(&$oForm, $sql, $table, $values, $readO)
    {
        $oConnection = Pelican_Db::getInstance();
        $result = $oConnection->QueryTab($sql);

        // Création de la hiérarchie
        /** @var Ndp_Hierarchy_Tree $oTree */
        $oTree = Pelican_Factory::getInstance('Hierarchy', "directory", "id", "parent_id");
        if ($result) {
            foreach ($result as $key => $values) {
                $result[$key]["ordre"] = ($key + 1);
            }
        }
        $oTree->addTabNode($result);
        $oTree->setOrder("ordre", "ASC");
        $subformjs = "if (!top.aChild) top.aChild = new Object();";
        $subformjs .= "if (!top.aChild['".$table."']) top.aChild['".$table."'] = new Object();";
        // affichage de l'arborescence
        $bDirectOutput = $oForm->bDirectOutput;
        $oForm->bDirectOutput = false;
        foreach ($oTree->aNodes as $key => $node) {
            if (!empty($node->creation_user)) {
                if (strpos($node->creation_user, '#'.$_SESSION[APP]['user']['id'].'#') !== false) {
                    $oTree->aNodes[$key]->used = true;
                }
            }
            $arbo[$node->level - 1][] = $node->id;
        }

        $dirList = self::makeLevel($table, $arbo[1], $oForm, $oTree, $subformjs, $readO);

        $oForm->bDirectOutput = $bDirectOutput;
            $return = $dirList.self::getJs($subformjs, $table = '');

        return $return;
    }

    public static function getJs($addon = '')
    {
        $return = "<script type=\"text/javascript\">
function propagation(chk) {
	var obj=chk;
	var type= obj.name.replace('_ID[]','');
	if (top.aChild[type][obj.value]) {
		orb=document.fForm.elements[obj.name];
		for (var i=0 ; i <orb.length ; i++) {
			if (top.aChild[type][obj.value][orb[i].value]) {
				orb[i].checked=obj.checked;
				// Récursisivité
				if (top.aChild[type][orb[i].value]) {
					propagation(orb[i]);
				};
			}
		}
}
}
function swapRow(object, sens) {
	index = null;
	index2 = null;
	if (!object.parentElement) {
		oTD = object.parentNode;
		oTR = oTD.parentNode;
	} else {
		oTD = object.parentElement;
		oTR = oTD.parentElement;
	}
	id = oTR.id;
	tab = id.split('_');
	index = tab[0];
	index2 = tab[1];
	// on détermine la ligne de destination
	id2 = index + '_' + (parseInt(index2)+parseInt(sens));
	if (document.getElementById(id2)) {
		oTR2 = document.getElementById(id2);
		for (var i = 0; i < oTR.cells.length; i++) {
			debut = oTR2.cells[i].innerHTML;
			fin = oTR.cells[i].innerHTML;
			oTR.cells[i].innerHTML = debut;
			oTR2.cells[i].innerHTML = fin;
		}
	}
}
".$addon."
</script>";

        return $return;
    }

    /**
     * Création d'une arborescence de dossier avec cases à cocher.
     *
     * @param $table
     * @param mixed $listId Liste d'identifiants de rubrique
     * @param Pelican_Form $form Objet formulaire
     * @param Pelican_Hierarchy_Tree $tree Objet hiérarchique
     * @param string $subformjs Chaine javascript de traitement
     * @param $readO
     *
     * @return string
     */
    public static function makeLevel($table, $listId, Pelican_Form &$form, &$tree, &$subformjs, $readO)
    {
        $return = '';
        $tr = '';

        if ($listId) {
            foreach ($listId as $id) {
                $node = $tree->aNodes[$tree->aParams[$id]["record"]];
                if ($node) {
                    /* récupération des fils */
                    if ($node->admin) {
                        $node->lib = Pelican_Html::b($node->lib);
                    }
                    if(!isset($node->readO)) {
                        $node->readO = false;
                    }
                    // REGLE : on met en gras les éléments d'administration
                    /* Contenu */
                    if (!empty($tree->aParams[$id]["child"])) {
                        $subformjs .= "top.aChild['".$table."'][".$id."] = new Array();\n";
                        foreach ($tree->aParams[$id]["child"] as $val) {
                            $subformjs .= "top.aChild['".$table."'][".$id."][".$val."]=true;\n";
                        }
                    }
                    $td = "";
                    $content = str_repeat("&nbsp;&nbsp;&nbsp;", ($node->level - 2));
                    $content .= Pelican_Html::img(array(
                            'src' => Pelican::$config["SKIN_PATH"]."/images/folder.gif",
                            'border' => "0",
                            'hspace' => "1",
                            'align' => "top",
                    ));
                    //cas de la corbeille ou le premier niveau est séléctionnable sans propagation
                    //la variable $table est renommé page et après corbeille car si
                    //un jour on veut un liste page il y aura un pb
                    if ($table == "CORBEILLE") {
                        $table = "PAGE";
                        //checkbox que pour le 1er niveau
                        if ($node->parent_id == 0) {
                            $content .= $form->createCheckBoxFromList($table."_ID[]", "", array(
                                $id => $node->lib,
                                ), ($node->used ? $id : ""), false, $readO, "h", true, "");
                            $table = "CORBEILLE";
                        } else {
                            $content .= $node->lib;
                            $table = "CORBEILLE";
                        }
                    } elseif ($table == 'ARBORESCENCE_CIBLE' || $table == 'ARBORESCENCE_SOURCE') {
                        if ($node->used) {
                            $content .= $form->createRadioFromList($table."_ID[]", "", array($id => $node->lib), (""), false, $readO, "h", true, "");
                        } else {
                            $content .= $form->createInput($table."_VOID[]", "", 255, "", false, $node->lib, true, 10, true);
                        }
                    } elseif ($table == 'ARBO') {
                        if ($node->used) {
                            $content .= $form->createCheckBoxFromList($table."_ID[]", "", array($id => $node->lib), (""), false, $readO, "h", true, "onclick=\"propagation(this".(!empty($_GET["page"]) ? ",'".$_GET["page"]."'" : "").");\"");
                        } else {
                            $content .= $form->createInput($table."_VOID[]", "", 255, "", false, $node->lib, true, 10, true);
                        }
                    } else {
                        if($node->readO) {
                            $content .= $node->lib;
                        } else {
                        $content .= $form->createCheckBoxFromList($table."_ID[]", "", array(
                            $id => $node->lib,
                            ), ($node->used ? $id : ""), false, $readO, "h", true, "onclick=\"propagation(this".(!empty($_GET["page"]) ? ",'".$_GET["page"]."'" : "").");\"");
                            }
                    }
                    $content .= self::makeLevel($table, (!empty($tree->aParams[$id]["child"]) ? $tree->aParams[$id]["child"] : ''), $form, $tree, $subformjs, $readO);

                    if (!$readO || ($readO && $node->used)) {
                        $td .= Pelican_Html::td(array(
                                'width' => "100%",
                                ), $content);
                    }
                    /* Flèches */
                    $img = "";
                    if ($_SESSION[APP]["tree_profile"] && !$readO) {
                        $img = Pelican_Html::img(array(
                                'alt' => "Descendre",
                                'border' => "0",
                                'height' => "12",
                                'src' => Pelican::$config["LIB_PATH"].Pelican::$config['LIB_LIST']."/images/ordre_plus.gif",
                                'style' => "cursor:pointer;",
                                'width' => "12",
                                'onclick' => "swapRow(this, 1)",
                        ));
                        $img .= Pelican_Html::img(array(
                                'alt' => "Monter",
                                'border' => "0",
                                'height' => "12",
                                'src' => Pelican::$config["LIB_PATH"].Pelican::$config['LIB_LIST']."/images/ordre_moins.gif",
                                'style' => "cursor:pointer;",
                                'width' => "12",
                                'onclick' => "swapRow(this, -1)",
                        ));
                        $aColor[2] = "#C3DBF7";
                        $aColor[3] = "#FFFFE0";
                        $aColor[4] = "#F0FFF0";
                        $mouseover = (!$readO ? "this.style.backgroundColor = '".$aColor[$node->level]."';" : "");
                        $mouseout = (!$readO ? "this.style.backgroundColor = '';" : "");
                    }
                    $td .= Pelican_Html::td(array(
                            'class' => "tblOrder",
                            'valign' => "top",
                            ), $img);
                    /*  */
                    if (empty($counter[$node->parent_id])) {
                        $counter[$node->parent_id] = 0;
                    }
                    $trid = $node->parent_id."_".(++$counter[$node->parent_id]);
                    $tr .= Pelican_Html::tr(array(
                            'id' => $trid,
                            'onmouseover' => $mouseover,
                            'onmouseout' => $mouseout,
                            ), $td);
                }
            }
            $return = Pelican_Html::table(array(
                    'border' => "0",
                    'cellspacing' => 0,
                    'cellpadding' => 0,
                    ), $tr);
        }

        return $return;
    }

    /**
     * Générattion de la requête sql de récupération d'une arborescence.
     *
     * @param string $type Type : 'DIRECTORY' ou 'PAGE'
     * @param string $code Code de préfixage des identifiants 'D' ou 'P'
     */
    protected function _getTreeSQL($type, $code)
    {
        $oConnection = Pelican_Db::getInstance();

        $sql = "select
			".$type."_ID as \"id\",
			".$oConnection->getConcatClause(array(
                "'<b>'",
                $type."_LABEL",
                "'</b>'",
            ))." as \"lib\",
			".$oConnection->getNVLClause($type."_PARENT_ID", "9999")." as \"pid\",
			".$type."_LABEL as \"order\",
			".$oConnection->getConcatClause(array(
                "'javascript:setDirectory('",
                $type."_ID",
                "','",
                $oConnection->getNVLClause($type."_PARENT_ID", "0"),
                "',''".$code."'''",
                "')'",
            ))." as \"url\",
			'".Pelican::$config['LIB_PATH']."/public/images/rubrique.gif' as \"icon\",
			'".Pelican::$config['LIB_PATH']."/public/images/rubrique.gif' as \"iconOpen\"
			from #pref#_".strtolower($type);

        return $sql;
    }
}

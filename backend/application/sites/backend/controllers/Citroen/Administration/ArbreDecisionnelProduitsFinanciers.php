<?php

pelican_import('Hierarchy');
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php';

class Citroen_Administration_ArbreDecisionnelProduitsFinanciers_Controller extends Pelican_Controller_Back
{
    protected $multiLangue = true;
    protected $administration = true;
    protected $form_name = "arbre_decisionnel";
    protected $processus = array(
        "#pref#_arbre_decisionnel",
    );
    public static $jsUsed = false;
    protected $table = "arbre_decisionnel";
    protected $oTree;
    protected $_can_add_answer = true;
    protected $_max_answer_number = false;
    protected $_minimum_required = false;

    public function init()
    {
        $oConnection = Pelican_Db::getInstance();
        $_SESSION[APP]["tree_profile"] = false;
        $this->field_id = strtoupper($this->table)."_ID";
        if ($_GET['id']) {
            $this->id = $_GET['id'];
        }
        $sql = $this->_getTreeSQL(strtoupper($this->table), $_GET['langue'] ? $_GET['langue'] : ($_SESSION[APP]["LANGUE_ID_EDITORIAL_DEFAUT"] ? $_SESSION[APP]["LANGUE_ID_EDITORIAL_DEFAUT"] : 1));

        $directory = $oConnection->queryTab($sql);
        $aDirectory = array();
        foreach ($directory as $keyDir => $valueDir) {
            if ($valueDir['lib']) {
                $aDirectory[] = $valueDir;
            }
        }
        $directory = $aDirectory;

        /* $directory[] = array(
          "id" => "99999",
          "lib" => t('QUESTIONS'),
          "order" => "1",
          "url" => "javascript:javascript:setDirectory(99999, null, '')"
          ); */
        // Dans le cas de l'insertion, on crée une rubrique fictive
        if ($this->id == Pelican::$config['DATABASE_INSERT_ID']) {
            if (
                    (($_GET['pid'] && $this->id != -2) || (!$_GET['pid'] && sizeof($this->oTree->aNodes) > 0))
                    &&
                    sizeof($this->oTree->aParams[$this->id]['child'])<4) {
                $directory[] = array(
                    "id" => $this->id,
                    "lib" => t('New page'),
                    "pid" => (!$_GET['pid'] ? "0" : $_GET['pid']),
                    "order" => "1",
                    "url" => "javascript:void(0)",
                );
            }
        }

        // tri par libellé - fix setOrder
        for ($it = 0; $it < count($directory); $it++) {
            for ($yt = 0; $yt < count($directory); $yt++) {
                if (strtolower($directory[$it]["lib"]) < strtolower($directory[$yt]["lib"])) {
                    $aTempo = $directory[$it];
                    $directory[$it] = $directory[$yt];
                    $directory[$yt] = $aTempo;
                }
            }
        }

        $this->oTree = Pelican_Factory::getInstance('Hierarchy.Tree', "dtreeDirectory", "id", "pid");

        $this->oTree->addTabNode($directory);
/* commentée car perd des données */
        //$this->oTree->setOrder("order", "ASC");
        $this->oTree->setTreeType("dtree");
        /* Création des javascript par défaut */
        if ($this->id == Pelican::$config['DATABASE_INSERT_ID']) {
            if ($this->id == - 2) {
                $js = "dtreeDirectory.o(".($this->oTree->aPosition[$this->id] + 1).");";
            } else {
                $js = "dtreeDirectory.o(".($this->oTree->aPosition[$this->id] - 1).");";
            }
            $js = "dtreeDirectory.s(".($this->oTree->aPosition[$this->id] - 1).");";
            $this->assign('js', $js, false);
        }
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
        if ((!isset($arr['pid']) && sizeof($this->oTree->aNodes) == 0) || $arr['pid'] === '0') {
            $_GET['type'] = 'Q1';
        } elseif ($arr['pid']) {
            $_GET['type'] = 'Q2';
        }

        if ($this->id != Pelican::$config["DATABASE_INSERT_ID"]) {
            $arr['pid'] = $this->id;
        }

        if (
                (isset($this->id) && !empty($this->id))
                ||
                ($this->id == -2 && isset($_GET['pid']))
                ) {
            if ($this->id != -2) {
                $node_id = $this->id;
            } else {
                $node_id = $_GET['pid'];
            }

            if (isset($this->oTree->aParams[$node_id]['child']) && count($this->oTree->aParams[$node_id]['child']) >= 4) {
                $this->_can_add_answer = false;
                $this->_max_answer_number = true;
            }

            if (isset($this->oTree->aParams[$node_id]['child']) && count($this->oTree->aParams[$node_id]['child']) < 2) {
                $this->_minimum_required = true;
                $this->_can_add_answer = true;
            }
        }

        // URL d'ajout d'une nouvelle question
        $arr['id'] = Pelican::$config["DATABASE_INSERT_ID"];
        $this->sAddUrl = $parse_url['path'].'?'.http_build_query($arr);
        parent::editAction();
        // Formulaire vide (défaut) sans bouton d'ajout
        if (!$this->id || !$_GET['type']) {
            $title = $this->getTemplateTitle($this->getView()->getHead()->sTitle, t('Edition'));
            if (sizeof($this->oTree->aNodes) > 0) {
                $this->aButton['add'] = "";
            }
            Backoffice_Button_Helper::init($this->aButton);
        }

        // Formulaire vide (focus sur la racine de l'arbo) avec bouton d'ajout
        /* elseif ($this->id == '99999') {
          $title = $this->getTemplateTitle($this->getView()->getHead()->sTitle, t('Edition'));
          $this->aButton['add'] = ($this->sAddUrl ? $this->sAddUrl : "");
          Backoffice_Button_Helper::init($this->aButton);
          } */
        // Formulaires
        else {
            if ($this->id != -2 && sizeof($this->oTree->aParams[$node_id]['child']) <4) {
                $this->aButton['add'] = $this->sAddUrl;
            } else {
                $this->aButton['add'] = "";
            }
            $error = '';

            if (!$this->_can_add_answer) {
                if ($this->_max_answer_number) {
                    $this->assign('max_answer', true);
                    $error = Pelican_Html::div(
                                    array(
                                "class" => t('ERROR'),
                                    ), Pelican_Html::b(
                                            sprintf('%s %s', t('MAX_ANSWER_REACHED_F'), $this->oTree->aNodes[$this->oTree->aPosition[$this->id]]->lib
                                            )
                                    )
                    );
                }
            }

            $_SESSION[APP]['session_start_page'] = $_SERVER['REQUEST_URI'];
            ob_start();
            $oForm = Pelican_Factory::getInstance('Form', true);
            $oForm->open(Pelican::$config['DB_PATH']);
            beginFormTable();
            $this->beginForm($oForm);
                //
                if ($_GET['type'] == "Q1") {
                    $oForm->createInput(strtoupper($this->table)."_QUESTION", t('QUESTION'), 255, "", true, $this->values[strtoupper($this->table).'_QUESTION'], $this->readO, 50);
                }
                //
                elseif ($this->_can_add_answer) {
                    $oForm->createInput(strtoupper($this->table)."_REPONSE", t('QUESTION_RESPONSE_PARENTE'), 255, "", true, $this->values[strtoupper($this->table).'_REPONSE'], $this->readO, 50);
                    $oForm->createInput(strtoupper($this->table)."_QUESTION", t('QUESTION_SUIVANTE'), 255, "", false, $this->values[strtoupper($this->table).'_QUESTION'], $this->readO, 50);
                    $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
                    $aBind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
                    $aBind[':PAGE_STATUS'] = 1;
                    $aBind[':ZONE_ID'] = 688;
                    $sSQL = "
					select
						CONCAT_WS('|',pv.PAGE_ID, pmz.ZONE_ORDER) as id,
						CONCAT_WS(' / ', pv.PAGE_TITLE_BO, pmz.ZONE_TITRE) as lib
					from #pref#_page p
					inner join #pref#_page_version pv
						on (pv.PAGE_ID = p.PAGE_ID
							and pv.PAGE_VERSION = p.PAGE_CURRENT_VERSION
							and pv.LANGUE_ID = p.LANGUE_ID)
					inner join #pref#_page_multi_zone pmz
						on (pmz.PAGE_ID = pv.PAGE_ID
							and pmz.PAGE_VERSION = pv.PAGE_VERSION
							and pmz.LANGUE_ID = pv.LANGUE_ID)
					where p.SITE_ID = :SITE_ID
					and p.LANGUE_ID = :LANGUE_ID
					and p.PAGE_STATUS = :PAGE_STATUS
					and pmz.ZONE_ID = :ZONE_ID
					and pmz.ZONE_PARAMETERS = 1
					";
                    $oForm->createComboFromSql($oConnection, "PRODUIT_FINANCIER", t('PRODUIT_FINANCIER'), $sSQL, $this->values['PAGE_ID'].'|'.$this->values['ZONE_ORDER'], false, $this->readO, "1", false, "", true, false, "", "", $aBind);
                    if ($this->values['PAGE_ID']) {
                        $this->aButton['add'] = "";
                    }
                    $oForm->createJS('
					if (($("input[name=\'ARBRE_DECISIONNEL_QUESTION\']").val() == "" && $("select[name=\'PRODUIT_FINANCIER\'] option:selected").val() == "")
						|| ($("input[name=\'ARBRE_DECISIONNEL_QUESTION\']").val() != "" && $("select[name=\'PRODUIT_FINANCIER\'] option:selected").val() != "")) {
						alert(\''.t('QUESTION_OU_PRODUIT_FINANCIER_OBLIGATOIRE', 'js2').'\');
						return false;
					}
				');
                    $oForm->createHidden("ARBRE_DECISIONNEL_PARENT_ID", ($this->id != -2) ? $this->values['ARBRE_DECISIONNEL_PARENT_ID'] : $arr['pid']);
                    $oForm->createHidden($this->field_id, $this->id);
                }

            if (!$this->oTree->aParams[$node_id]["child"] && $this->id != -2) {
                $this->aButton['delete'] = $oForm->sFormName;
            }

            if (!$this->readO) {
                $this->aButton['save'] = $oForm->sFormName;
            }
            $this->endForm($oForm, "directory");
            $this->aButton["back"] = "";
            Backoffice_Button_Helper::init($this->aButton);
            endFormTable();
            $oForm->close();
            $content = ob_get_contents();
            ob_clean();
            $content = formToString($oForm, $content);
        }
        //}

        /* $content = Pelican_Html::nobr(Pelican_Html::span(array(
          "class" => "path"
          ), $this->oTree->getNodePath($this->id, "lib", " > ", "", false))) . $content; */

        $this->assign('content', ($error.Pelican_Html::table("", Pelican_Html::tr("", Pelican_Html::td(array(
                                    valign => "top",
                                    width => "300px",
                                        ), Pelican_Html::div(array(
                                            style => "float:left;overflow-y: scroll;overflow-x: scroll;width:300px;border: #CACACA 1px solid;height:390px;bacground-color:white;",
                                                ), $this->oTree->getTree())).Pelican_Html::td(array(
                                    valign => "top",
                                        ), Pelican_Html::div(array(
                                            style => "float:left;width:80%",
                                            valign => "top",
                                                ), $content))))), false);
        $this->assign('url', str_replace("&id=".$this->id, "", str_replace("&pid=".$_GET["pid"], "", $_SERVER["REQUEST_URI"])));

        $this->replaceTemplate('index', 'edit');
        $this->fetch();
    }

    public function saveAction()
    {
        if (Pelican_Db::$values['form_button'] == "delete") {
            $oConnection = Pelican_Db::getInstance();
            $aBind[':ARBRE_DECISIONNEL_ID'] = Pelican_Db::$values['ARBRE_DECISIONNEL_ID'];
            $sSQL = "
				delete from #pref#_arbre_decisionnel
				where ARBRE_DECISIONNEL_ID = :ARBRE_DECISIONNEL_ID";
            $oConnection->query($sSQL, $aBind);
            $parse_url = parse_url(Pelican_Db::$values['form_retour']);
            parse_str($parse_url['query'], $arr);
            Pelican_Db::$values['form_retour'] = $parse_url['path'].'?tid='.$arr['tid'];
        } else {
            if (Pelican_Db::$values['PRODUIT_FINANCIER']) {
                $temp = explode('|', Pelican_Db::$values['PRODUIT_FINANCIER']);
                Pelican_Db::$values['PAGE_ID'] = $temp[0];
                Pelican_Db::$values['ZONE_ORDER'] = $temp[1];
            }

            parent::saveAction();
        }

        Pelican_Cache::clean("Frontend/Citroen/OutilAideChoixFinancement/Reponses");
        Pelican_Cache::clean("Frontend/Citroen/OutilAideChoixFinancement/Question");
        Pelican_Cache::clean("Frontend/Citroen/OutilAideChoixFinancement/ProduitFinancier");
    }

    /**
     * Générattion de la requête sql de récupération d'une arborescence.
     */
    protected function _getTreeSQL($type, $langueId)
    {
        $oConnection = Pelican_Db::getInstance();
        $sql = "select
			".$type."_ID as 'id',
			".$oConnection->getConcatClause(
                        array(
                            "'<b>'",
                            $oConnection->getNVLClause(
                                    $type."_QUESTION", $oConnection->getConcatClause(array('pv.PAGE_TITLE_BO', '"/"',
                                        'pmz.ZONE_TITRE', )
                                    )
                            ),
                            "'</b>'",
                        )
                )." as 'lib',
			".$oConnection->getNVLClause($type."_PARENT_ID", "0")." as \"pid\",
			".$type."_QUESTION as 'order',
			".$oConnection->getConcatClause(
                        array(
                            "'javascript:setDirectory('",
                            $type."_ID",
                            "','",
                            $oConnection->getNVLClause($type."_PARENT_ID", "0"),
                            "','''')'",
                        )
                )." as 'url',
			'".Pelican::$config['LIB_PATH']."/public/images/rubrique.gif' as 'icon',
			'".Pelican::$config['LIB_PATH']."/public/images/rubrique.gif' as 'iconOpen'
			from #pref#_arbre_decisionnel ad

                        left join #pref#_page p on (ad.PAGE_ID = p.PAGE_ID)
                        left join #pref#_page_version pv
						on (pv.PAGE_ID = p.PAGE_ID
							and pv.PAGE_VERSION = p.PAGE_CURRENT_VERSION
							and pv.LANGUE_ID = p.LANGUE_ID)

			left join psa_page_multi_zone pmz
						on (pmz.PAGE_ID = pv.PAGE_ID
							and pmz.PAGE_VERSION = pv.PAGE_VERSION
							and pmz.LANGUE_ID = pv.LANGUE_ID
                                                        and ad.ZONE_ORDER = pmz.ZONE_ORDER
							)
			where ad.LANGUE_ID = ".$langueId."
			and ad.SITE_ID = ".$_SESSION[APP]['SITE_ID'];

        return $sql;
    }
}

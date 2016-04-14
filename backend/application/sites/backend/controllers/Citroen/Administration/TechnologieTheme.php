<?php
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php';
class Citroen_Administration_TechnologieTheme_Controller extends Citroen_Controller
{
    protected $multiLangue = true;
    protected $administration = true; //false
    protected $form_name = "theme_technogie_gallerie";
    protected $field_id = "THEME_TECHNOLOGIE_GALLERIE_ID";
    protected $defaultOrder = "THEME_TECHNOLOGIE_GALLERIE_ORDER";
    protected $decacheBack = array(
        array('Frontend/Citroen/Technologie/Theme',
            array('SITE_ID', 'LANGUE_ID'),
        ),
    );

    protected function setListModel()
    {
        $oConnection = Pelican_Db::getInstance();

        /* Valeurs Bindées pour la requête */
        $aBindToolBarList[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $aBindToolBarList[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];

        $sqlToolBarList = "
                SELECT
                    THEME_TECHNOLOGIE_GALLERIE_ID,
                    THEME_TECHNOLOGIE_GALLERIE_LABEL
                FROM
                    #pref#_{$this->form_name}
                WHERE
                    SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID";

        if ($_GET['filter_search_keyword'] != '') {
            $sqlToolBarList .= " AND THEME_TECHNOLOGIE_GALLERIE_LABEL like '%".$_GET['filter_search_keyword']."%' ";
        }

        $sqlToolBarList .= " ORDER BY {$this->listOrder}";

        $this->listModel = $oConnection->queryTab($sqlToolBarList, $aBindToolBarList);
    }

    protected function setEditModel()
    {
        $this->aBind[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $this->aBind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $this->aBind[':'.$this->field_id] = (int) $this->id;

        $sSqlToolBarForm = <<<SQL
                SELECT
                    *
                FROM
                    #pref#_{$this->form_name}
                WHERE
                    SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID
                    AND {$this->field_id} = :{$this->field_id}
                ORDER BY {$this->listOrder}
SQL;

        $this->editModel = $sSqlToolBarForm;
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");

        $table->setFilterField("search_keyword", "<b>".t('RECHERCHER')." :</b>", "");
        $table->getFilter(1);

        $table->setTableOrder("#pref#_theme_technogie_gallerie", "THEME_TECHNOLOGIE_GALLERIE_ID", "THEME_TECHNOLOGIE_GALLERIE_ORDER", "", "SITE_ID = ".$_SESSION[APP]['SITE_ID'], array("Frontend/Citroen/Technologie/Theme", "Frontend/Citroen/Technologie/Gallerie"));
        $table->setValues($this->getListModel(), "THEME_TECHNOLOGIE_GALLERIE_ID");
        $table->addColumn(t('ID'), "THEME_TECHNOLOGIE_GALLERIE_ID", "20", "left", "", "tblheader", "THEME_TECHNOLOGIE_GALLERIE_ID");
        $table->addColumn(t('LIBELLE'), "THEME_TECHNOLOGIE_GALLERIE_LABEL", "80", "left", "", "tblheader", "THEME_TECHNOLOGIE_GALLERIE_LABEL");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "THEME_TECHNOLOGIE_GALLERIE_ID"), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "THEME_TECHNOLOGIE_GALLERIE_ID", "" => "readO=true"), "center");
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        parent::editAction();
        if ($this->form_action == 'DEL') {
            $aPagesConflictuelles = Backoffice_Form_Helper::verificationSuppressionReferentiel('THEME_TECHNOLOGIE', $this->values['THEME_TECHNOLOGIE_GALLERIE_ID'], $this->values['SITE_ID']);
            if ($aPagesConflictuelles) {
                foreach ($aPagesConflictuelles as $p) {
                    $error .= $p['PAGE_TITLE_BO'].' (pid : '.$p['PAGE_ID'].')'.Pelican_Html::br();
                }
                $error = Pelican_Html::div(
                    array("class" => t('ERROR')),
                    Pelican_Html::br().Pelican_Html::b(t('SUPP_IMPOS')).Pelican_Html::br().t('CONTENU_UTILISE_DANS').Pelican_Html::br().$error.Pelican_Html::br()
                );
            }
        }
        $form = $error.$this->startStandardForm();
        $form .= $this->oForm->createHidden("THEME_TECHNOLOGIE_GALLERIE_ORDER", $this->values ["THEME_TECHNOLOGIE_GALLERIE_ORDER"]);
        $form .= $this->oForm->createInput("THEME_TECHNOLOGIE_GALLERIE_LABEL", t('LIBELLE'), 255, "", true, $this->values['THEME_TECHNOLOGIE_GALLERIE_LABEL'], $this->readO, 75);

        $form .= $this->stopStandardForm();
        if ($aPagesConflictuelles) {
            $this->aButton["delete"] = "";
            Backoffice_Button_Helper::init($this->aButton);
        }
        $this->setResponse($form);
    }

    public function saveAction()
    {
        parent::saveAction();
        Pelican_Cache::clean("Frontend/Citroen/Technologie/Themes");
    }
}

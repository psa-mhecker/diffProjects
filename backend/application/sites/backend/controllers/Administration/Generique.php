<?php
/**
 * Formulaire de gestion générique des tables de référence (utilise le champ TEMPLATE_COMPLEMENT pour déterminer la table à gérer).
 *
 * Il est possible de différencier les tpye de table de référence à gérer : SYSTEME ou APPLICATIF
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 10/11/2003
 */
define(TABLE_SYSTEME, 1);
define(TABLE_APPLICATION, 2);
define(TABLE_CONTENT, 2);

class Administration_Generique_Controller extends Pelican_Controller_Back
{
    protected $administration = true;

    protected $form_name = "generique";

    protected $field_id = "";

    protected $defaultOrder = "";

    protected $specificProcessus = array(
        'sample' => array(
            '#pref#_sample',
        ),
    );

    //A TESTER// public $decacheBack = array(array("Backend/Generic", $_POST["TEMPLATE_COMPLEMENT"]));


    protected $table;

    protected function setListModel()
    {
        if ($_GET['table'] == 'LANGUE') {
            $tableName = 'language';
        } else {
            $tableName = $_GET['table'];
        }

        $this->listModel = "SELECT T.*
         FROM #pref#_".strtolower($tableName)." T
         order by ".$this->listOrder;
    }

    protected function setEditModel()
    {
        if ($_GET['table'] == 'LANGUE') {
            $tableName = 'language';
        } else {
            $tableName = $_GET['table'];
        }

        $strSqlForm = "SELECT T.* ";
        $strSqlForm .= " FROM #pref#_".strtolower($tableName)." T ";
        $strSqlForm .= " WHERE ".$_GET["table"]."_ID='".$_GET["id"]."'";

        $this->editModel = $strSqlForm;
    }

    public function primarylistAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setCSS(array(
            "tblalt1",
            "tblalt2",
        ));
        $table->setValues($this->table);
        $table->addColumn(t('TABLE'), "lib", "90", "left", "", "tblheader", "lib");
        $table->addInput(t('POPUP_BUTTON_SELECT'), "button", array(
            "table" => "id",
            "libelle" => "lib",
        ), "center");
        $this->setResponse($table->getTable());
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setFilterField("search_keyword", "<b>".t('RECHERCHER')." :</b>", $_GET["table"]."_LABEL");
        $table->getFilter(1);
        $table->setCSS(array(
            "tblalt1",
            "tblalt2",
        ));
        $table->setValues($this->getListModel(), $this->field_id);
        $table->addColumn(t('ID'), $this->field_id, "10", "left", "", "tblheader", $this->field_id);
        $table->addColumn(t('FORM_LABEL'), $_GET["table"]."_LABEL", "90", "left", "", "tblheader", $_GET["table"]."_LABEL");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array(
            "id" => $this->field_id,
        ), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array(
            "id" => $this->field_id,
            "" => "readO=true",
        ), "center");
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        parent::editAction();
        $form = $this->startStandardForm();

        $form .= $this->oForm->createInput($_GET["table"]."_LABEL", t('FORM_LABEL'), 100, "", true, $this->values[$_GET["table"]."_LABEL"], $this->readO, 100);
        switch ($_GET["table"]) {
            case 'CONTENT_CATEGORY':
                $form .= $this->oForm->createInput("CONTENT_CATEGORY_RESEARCH", t("SEARCH_LIB"), 255, "", false, $this->values["CONTENT_CATEGORY_RESEARCH"], $this->readO, 75, false);
                #type de contenu concerné dans un combo
                $strTableName = "content_type";
                $arrayCT = Pelican_Cache::fetch('Backend/ContentType', array(
                    $_SESSION[APP]['SITE_ID'],
                ));
                $aValues = array();
                if (is_array($arrayCT)) {
                    foreach ($arrayCT as $k => $v) {
                        $aValues[$v['id']] = $v['lib'];
                    }
                }
                $form .= $this->oForm->createComboFromList("CONTENT_TYPE_ID", t("ASSOC_CONTENT_TYPE"), $aValues, $this->values["CONTENT_TYPE_ID"], true, $this->readO);
                // $form .= $this->oForm->createCombo($oConnection, "CONTENT_TYPE_ID", "Type de contenu concern�", $strTableName, "", "", $this->values["CONTENT_TYPE_ID"], true, $this->readO);
                $sqlData = "SELECT #pref#_template.TEMPLATE_ID, TEMPLATE_LABEL LIB FROM #pref#_template WHERE #pref#_template.TEMPLATE_TYPE_ID =3 and TEMPLATE_GROUP_ID=2 ORDER BY LIB";
                $sqlSelected = "SELECT #pref#_template.TEMPLATE_ID, TEMPLATE_LABEL LIB FROM #pref#_template, #pref#_content_template WHERE #pref#_template.TEMPLATE_ID=#pref#_content_template.TEMPLATE_ID AND CONTENT_CATEGORY_ID='".$this->id."' order by lib";
                break;
        }
        $form .= $this->oForm->createHidden("TEMPLATE_COMPLEMENT", strtolower($_GET["table"]));

        $form .= $this->stopStandardForm();

    // Zend_Form start
    $form = formToString($this->oForm, $form);
        // Zend_Form stop

        $this->setResponse($form);
    }

    public function before()
    {
        $this->aTable[TABLE_SYSTEME][] = array(
            "id" => "MEDIA_TYPE",
            "lib" => t('Media type'),
        );
        $this->aTable[TABLE_SYSTEME][] = array(
            "id" => "ZONE_TYPE",
            "lib" => t('Zone type'),
        );
        $this->aTable[TABLE_SYSTEME][] = array(
            "id" => "TEMPLATE_TYPE",
            "lib" => t('Template type'),
        );
        $this->aTable[TABLE_SYSTEME][] = array(
            "id" => "LANGUE",
            "lib" => t('Languages'),
        );
        $this->aTable[TABLE_SYSTEME][] = array(
            "id" => "TEMPLATE_GROUP",
            "lib" => t('Template groups'),
        );
        $this->aTable[TABLE_APPLICATION][] = array(
            "id" => "CIVILITY",
            "lib" => t('Civility'),
        );
        $this->aTable[TABLE_APPLICATION][] = array(
            "id" => "COUNTRY",
            "lib" => t('Country'),
        );
        $this->aTable[TABLE_APPLICATION][] = array(
            "id" => "PROFILE_SUBSCRIBER",
            "lib" => t('Subscribers profiles'),
            "order" => "PROFILE_SUBSCRIBER_ORDER",
        );
        $this->aTable[TABLE_CONTENT][] = array(
            "id" => "CONTENT_CATEGORY",
            "lib" => t('Content categories'),
        );
        ksort($this->aTable);

        $this->table = $this->aTable[$_GET["tc"]];

        if (!$_GET["table"]) {
            $titrePage = t('Reference data');
            $this->getTemplateTitle(t('Reference table'), t('Liste'));
            $this->_forward('primarylist');
        } else {
            $this->field_id = $_GET["table"]."_ID";
            $titrePage = $_GET["libelle"];
            if (!$this->listOrder) {
                $this->listOrder = $_GET["table"]."_LABEL";
            }
        }
        parent::before();
    }

    protected function beforeSave()
    {
        $this->processus = $this->specificProcessus['#pref#_'.Pelican_Db::$values["TEMPLATE_COMPLEMENT"]];
        if (!$this->processus) {
            $this->processus = array(
                '#pref#_'.Pelican_Db::$values["TEMPLATE_COMPLEMENT"],
            );
        }
    }

    protected function afterSave()
    {

        /* décache de fichiers */
        if (Pelican_Db::$values["TEMPLATE_COMPLEMENT"]) {
            Pelican_Cache::clean("frontend/".strtolower(Pelican_Db::$values["TEMPLATE_COMPLEMENT"])."_php");
        }
    }
}

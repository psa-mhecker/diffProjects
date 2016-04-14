<?php

class Taxonomy_Controller extends Pelican_Controller_Back
{
    protected $administration = true;

    protected $form_name = "terms_group";

    protected $field_id = "TERMS_GROUP_ID";

    protected $defaultOrder = "TERMS_GROUP_ID";

    protected function setListModel()
    {
        $this->listModel = "SELECT TERMS_GROUP_ID,TERMS_GROUP_LABEL
		             FROM #pref#_terms_group
		             WHERE SITE_ID = ".$_SESSION [APP] ['SITE_ID']."";

        if ($_GET['filter_search_keyword'] != '') {
            $this->listModel .= " AND (
            TERMS_GROUP_LABEL like '%".$_GET['filter_search_keyword']."%'
            )
            ";
        }
        $this->listModel .= " ORDER BY ".$this->listOrder;
    }

    protected function setEditModel()
    {
        $this->editModel = "SELECT *
		             FROM #pref#_terms_group
		             WHERE TERMS_GROUP_ID=".$this->id;
    }

    public function listAction()
    {
        parent::listAction();
        $oConnection = Pelican_Db::getInstance();
        $this->setListModel();
        $rsSqlList = $oConnection->queryTab($this->getListModel(), $this->aBind);
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setFilterField("search_keyword", "<b>".t('RECHERCHER')." :</b>", "");
        $table->getFilter(1);
        $table->setCSS(array("tblalt1", "tblalt2" ));
        $table->setValues($rsSqlList);
        $table->addColumn(t('ID'), "TERMS_GROUP_ID", "10", "right", "", "tblheader");
        $table->addColumn(t('GROUPES'), "TERMS_GROUP_LABEL", "75", "left", "", "tblheader");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "TERMS_GROUP_ID" ), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "TERMS_GROUP_ID", "" => "readO=true" ), "center");

        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        parent::editAction();
        $form = $this->startStandardForm();

        $form .= $this->oForm->createInput("TERMS_GROUP_LABEL", t('FORM_LABEL'), 255, "", true, $this->values ["TERMS_GROUP_LABEL"], $this->readO, 116);
        $form .= $this->oForm->inputTaxonomy("TAXONOMY", t('Taxonomy'), "/_/Taxonomy/suggest", $this->values ["TERMS_GROUP_ID"], 3, $this->values ["TERMS_GROUP_ID"]);

        $form .= $this->stopStandardForm();
        $this->setResponse($form);
    }

    public function suggestAction()
    {
        include 'Pelican/Taxonomy.php';
        $oConnection = Pelican_Db::getInstance();
        $taxo = new Pelican_Taxonomy();
        $aSearch = $taxo->getTermsCompletion($_GET ['q'], $_GET ["gid"]);

        foreach ($aSearch as $value) {
            echo $value ["terms_name"].'|'.$value ["terms_name"]."\n";
        }
    }
}

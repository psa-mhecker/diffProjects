<?php

/**
 * Formulaire de gestion des types de tag de fréquentation.
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 02/12/2005
 */
class Administration_Tag_Type_Controller extends Pelican_Controller_Back
{
    protected $administration = true;

    protected $form_name = "tag_type";

    protected $field_id = "TAG_TYPE_ID";

    protected $defaultOrder = "TAG_TYPE_LABEL";

    protected $decacheBack = array(
        "Tag/Type",
    );

    protected function setListModel()
    {
        $this->listModel = "SELECT * FROM #pref#_tag_type order by ".$this->listOrder;
    }

    protected function setEditModel()
    {
        $this->editModel = "SELECT * from #pref#_tag_type WHERE TAG_TYPE_ID=".$this->id;
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setFilterField("search_keyword", "<b>".t('RECHERCHER')." :</b>", "TAG_TYPE_LABEL");
        $table->getFilter(1);
        $table->setCSS(array(
            "tblalt1",
            "tblalt2",
        ));
        $table->setValues($this->getListModel(), "TAG_TYPE_ID");
        $table->addColumn(t('ID'), "TAG_TYPE_ID", "10", "left", "", "tblheader");
        $table->addColumn(t('FORM_LABEL'), "TAG_TYPE_LABEL", "90", "left", "", "tblheader");

        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array(
            "id" => "TAG_TYPE_ID",
        ), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array(
            "id" => "TAG_TYPE_ID",
            "" => "readO=true",
        ), "center");
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        parent::editAction();
        $form = $this->startStandardForm();

        $form .= $this->oForm->createInput("TAG_TYPE_LABEL", t('FORM_LABEL'), 100, "", true, $this->values["TAG_TYPE_LABEL"], $this->readO, 100);
        $form .= $this->oForm->createInput("TAG_TYPE_JS_LINK", t('Javascript a inclure'), 100, "", false, $this->values["TAG_TYPE_JS_LINK"], $this->readO, 100);
        $form .= $this->oForm->createTextArea("TAG_TYPE_HTTP", t('Tag http'), false, $this->values["TAG_TYPE_HTTP"], 2000, $this->readO, 20, 75);
        $form .= $this->oForm->createTextArea("TAG_TYPE_HTTPS", t('Tag https'), false, $this->values["TAG_TYPE_HTTPS"], 2000, $this->readO, 20, 75);
//$form .= $this->oForm->createInput ( "TAG_URL", t ( 'URL extranet' ), 100, "", true, $this->values ["TAG_URL"], $this->readO, 100);

        $form .= $this->stopStandardForm();

    // Zend_Form start
        $form = formToString($this->oForm, $form);
        // Zend_Form stop

        $this->setResponse($form);
    }
}

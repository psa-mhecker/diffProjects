<?php

class Mobapp_HomeContent_Controller extends Pelican_Controller_Back
{
    protected $administration = false;

    protected $form_name = "mobapp_home";

    protected $field_id = "MOBAPP_SITE_HOME_ID";

    protected $defaultOrder = "MOBAPP_SITE_HOME_ORDER";

    protected function setListModel()
    {
        $this->listModel = "SELECT * from #pref#_mobapp_site_home
        where SITE_ID = ".$_SESSION[APP]['SITE_ID']."
        order by ".$this->listOrder;
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', '', '', 0, 0, 0, 'liste');

        //------------ Begin Input ----------
        $table->setValues($this->getListModel(), "MOBAPP_SITE_HOME_ID");
        $table->addColumn(t('ID'), "MOBAPP_SITE_HOME_ID", "10", "left", "", "tblheader", "MOBAPP_SITE_HOME_ID");
        $table->addColumn(t('POPUP_LABEL_NAME'), "MOBAPP_SITE_HOME_LABEL", "90", "left", "", "tblheader", "MOBAPP_SITE_HOME_LABEL");
        $table->addColumn(t('Order'), "MOBAPP_SITE_HOME_ORDER", "5", "center", "", "tblheader", "MOBAPP_SITE_HOME_ORDER");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array(
            "hid" => "MOBAPP_SITE_HOME_ID",
            "mtid" => "MOBAPP_CONTENT_TYPE_ID",
            "" => "tid=282",
        ), "center");
        //------------ End Input ----------


        $this->setResponse($table->getTable());
    }
}

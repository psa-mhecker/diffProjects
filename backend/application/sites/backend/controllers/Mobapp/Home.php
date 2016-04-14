    <?php
    class Mobapp_HomeConfig_Controller extends Pelican_Controller_Back
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

        protected function setEditModel()
        {
            $this->aBind[':ID'] = $this->id;
            $this->editModel = "SELECT * from #pref#_mobapp_site_home WHERE MOBAPP_SITE_HOME_ID=:ID";
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
            "id" => "MOBAPP_SITE_HOME_ID",
        ), "center");
            $table->addInput(t('POPUP_LABEL_DEL'), "button", array(
            "id" => "MOBAPP_SITE_HOME_ID",
            "" => "readO=true",
        ), "center");
        //------------ End Input ----------

            $this->setResponse($table->getTable());
        }

        public function editAction()
        {
            parent::editAction();
    //------------ Begin startStandardForm ----------
            $this->oForm = Pelican_Factory::getInstance('Form', true);
            $this->oForm->bDirectOutput = false;
            $form = $this->oForm->open(Pelican::$config['DB_PATH']);
            $form .= $this->beginForm($this->oForm);
            $form .= $this->oForm->beginFormTable();
    //------------ End startStandardForm ----------

    //------------ Begin Input ----------
        $form .= $this->oForm->createHidden('SITE_ID', $this->values ['SITE_ID'], $this->readO, 100);
            $form .= $this->oForm->createInput("MOBAPP_SITE_HOME_LABEL", t('title'), 50, "", true, $this->values ["MOBAPP_SITE_HOME_LABEL"], $this->readO, 50);
            $form .= $this->oForm->createMedia("MEDIA_ID", t('Icon'), false, "image", "", $this->values ["MEDIA_ID"], $this->readO);
            $form .= $this->oForm->createInput("MOBAPP_SITE_HOME_ORDER", t('Order'), 10, "number", false, $this->values ["MOBAPP_SITE_HOME_ORDER"], $this->readO, 10, false);
    //------------ End Input ----------

    //------------ Begin stopStandardForm ----------
            $form .= $this->oForm->endFormTable();
            $form .= $this->endForm($this->oForm);
            $form .= $this->oForm->close();
    //------------ End stopStandardForm ----------
            $this->setResponse($form);
        }
    }

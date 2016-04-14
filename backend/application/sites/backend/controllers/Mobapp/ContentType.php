    <?php
    class Mobapp_ContentType_Controller extends Pelican_Controller_Back
    {
        protected $administration = true;

        protected $form_name = "mobapp_content_type";

        protected $field_id = "MOBAPP_CONTENT_TYPE_ID";

        protected $defaultOrder = "MOBAPP_CONTENT_TYPE_LABEL";

        protected function setListModel()
        {
            $this->listModel = "SELECT * from #pref#_mobapp_content_type
            order by ".$this->listOrder;
        }

        protected function setEditModel()
        {
            $this->aBind[':ID'] = $this->id;
            $this->editModel = "SELECT * from #pref#_mobapp_content_type WHERE MOBAPP_CONTENT_TYPE_ID=:ID";
        }

        public function listAction()
        {
            parent::listAction();
            $table = Pelican_Factory::getInstance('List', '', '', 0, 0, 0, 'liste');

     //------------ Begin Input ----------
        $table->setValues($this->getListModel(), "MOBAPP_CONTENT_TYPE_ID");
            $table->addColumn(t('ID'), "MOBAPP_CONTENT_TYPE_ID", "1", "left", "", "tblheader", "MOBAPP_CONTENT_TYPE_ID");
            $table->addImage('Icône', array("_folder_" => "", "_extension_" => "" ), "MOBAPP_CONTENT_TYPE_ICON", "1", "center", "", "tblheader", "MOBAPP_CONTENT_TYPE_ICON");
            $table->addColumn(t('POPUP_LABEL_NAME'), "MOBAPP_CONTENT_TYPE_LABEL", "100", "left", "", "tblheader", "MOBAPP_CONTENT_TYPE_LABEL");
            $table->addInput(t('FORM_BUTTON_EDIT'), "button", array(
            "id" => "MOBAPP_CONTENT_TYPE_ID",
        ), "center");
            $table->addInput(t('POPUP_LABEL_DEL'), "button", array(
            "id" => "MOBAPP_CONTENT_TYPE_ID",
            "" => "readO=true",
        ), "center");
        //------------ End Input ----------


        $this->assign('list', $table->getTable(), false);
            $this->fetch();
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
        $form .= $this->oForm->createInput("MOBAPP_CONTENT_TYPE_LABEL", t('POPUP_LABEL_NAME'), 100, "", true, $this->values["MOBAPP_CONTENT_TYPE_LABEL"], $this->readO, 100);
            $form .= $this->oForm->createInput("MOBAPP_CONTENT_TYPE_PATH", t('BACK_PATH'), 100, "", true, $this->values["MOBAPP_CONTENT_TYPE_PATH"], $this->readO, 100);
            $form .= $this->oForm->createInput("MOBAPP_CONTENT_TYPE_ICON", t('icone'), 100, "", true, $this->values["MOBAPP_CONTENT_TYPE_ICON"], $this->readO, 100);
            $form .= $this->oForm->createInput("MOBAPP_CONTENT_TYPE_LIST", t('Has list'), 100, "", true, $this->values["MOBAPP_CONTENT_TYPE_LIST"], $this->readO, 100);
        //------------ End Input ----------

    //------------ Begin stopStandardForm ----------
            $form .= $this->oForm->endFormTable();
            $form .= $this->endForm($this->oForm);
            $form .= $this->oForm->close();
    //------------ End stopStandardForm ----------
            $this->setResponse($form);
        }
    }

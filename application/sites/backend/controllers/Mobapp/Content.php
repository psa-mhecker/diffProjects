    <?php

    class Mobapp_Content_Controller extends Pelican_Controller_Back
    {

        protected $administration = false;

        protected $hasList = true;

        protected $form_name = "mobapp_content";

        protected $field_id = "MOBAPP_CONTENT_ID";

        protected $defaultOrder = "MOBAPP_CONTENT_TITLE";

        protected function init ()
        {
            $oConnection = Pelican_Db::getInstance();
            
            if (! empty($_GET['mtid'])) {
                
                $aBind[':CONTENT_TYPE_ID'] = $_GET['mtid'];
                $contentType = $oConnection->queryRow('select * from #pref#_mobapp_content_type where MOBAPP_CONTENT_TYPE_ID=:CONTENT_TYPE_ID', $aBind);
                $this->hasList = ($contentType['MOBAPP_CONTENT_TYPE_LIST'] ? true : false);
                if (! $this->hasList) {
                    $aBind[':HID'] = $_GET['hid'];
                    $_GET['id'] = $oConnection->queryItem('select MOBAPP_CONTENT_ID from #pref#_mobapp_content where MOBAPP_SITE_HOME_ID=:HID', $aBind);
                    if (empty($_GET['id'])) {
                        $_GET['id'] = - 2;
                    }
                }
            }
        }

        protected function setListModel ()
        {
            
            $this->listModel = "SELECT * from #pref#_mobapp_content  
            where MOBAPP_SITE_HOME_ID=" . $_GET['hid'] . " AND SITE_ID = " . $_SESSION[APP]['SITE_ID'] . "
            order by " . $this->listOrder;
        }

        protected function setEditModel ()
        {
            $this->aBind[':ID'] = $this->id;
            $this->editModel = "SELECT * from #pref#_mobapp_content WHERE MOBAPP_CONTENT_ID=:ID";
        }

        public function listAction ()
        {
            if ($this->hasList) {
                parent::listAction();
                $table = Pelican_Factory::getInstance('List', '', '', 0, 0, 0, 'liste');
                
                //------------ Begin Input ----------    
                $table->setValues($this->getListModel(), "MOBAPP_CONTENT_ID");
                $table->addColumn(t('ID'), "MOBAPP_CONTENT_ID", "10", "left", "", "tblheader", "MOBAPP_CONTENT_ID");
                $table->addColumn(t('POPUP_LABEL_NAME'), "MOBAPP_CONTENT_TITLE", "90", "left", "", "tblheader", "MOBAPP_CONTENT_TITLE");
                $table->addInput(t('FORM_BUTTON_EDIT'), "button", array(
                    "id" => "MOBAPP_CONTENT_ID" , 
                    "hid" => $_GET['hid'] , 
                    "" => "mtid=" . $_GET['mtid']
                ), "center");
                $table->addInput(t('POPUP_LABEL_DEL'), "button", array(
                    "id" => "MOBAPP_CONTENT_ID" , 
                    "hid" => $_GET['hid'] , 
                    "" => "readO=true&mtid=" . $_GET['mtid']
                ), "center"); //------------ End Input ----------    
                

                $this->setResponse($table->getTable());
            } else {
                $this->_forward('edit');
            }
        }

        public function editAction ()
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
            $form .= $this->oForm->createHidden('LANGUE_ID', $this->values['LANGUE_ID'], $this->readO, 100);
            $form .= $this->oForm->createHidden('SITE_ID', $this->values['SITE_ID'], $this->readO, 100);
            if (empty($this->values["MOBAPP_CONTENT_TYPE_ID"])) {
                $this->values["MOBAPP_CONTENT_TYPE_ID"] = $_GET['mtid'];
            }
            $form .= $this->oForm->createHidden("MOBAPP_CONTENT_TYPE_ID", $this->values["MOBAPP_CONTENT_TYPE_ID"]);
            if (empty($this->values["MOBAPP_SITE_HOME_ID"])) {
                $this->values["MOBAPP_SITE_HOME_ID"] = $_GET['hid'];
            }
            $form .= $this->oForm->createHidden("MOBAPP_SITE_HOME_ID", $this->values["MOBAPP_SITE_HOME_ID"]);
            $form .= $this->oForm->createInput("MOBAPP_CONTENT_TITLE", t('POPUP_LABEL_NAME'), 100, "", true, $this->values["MOBAPP_CONTENT_TITLE"], $this->readO, 100);
            $form .= $this->oForm->createEditor("MOBAPP_CONTENT_TEXT", "Texte", false , $this->values['MOBAPP_CONTENT_TEXT'] , $this->readO);
            $form .= $this->oForm->createInput("MOBAPP_CONTENT_URL", t('Link'), 100, "", false, $this->values["MOBAPP_CONTENT_URL"], $this->readO, 100);
            //MOBAPP_CONTENT_TEXT 	  
            //MOBAPP_CONTENT_SHORTTEXT
            //MOBAPP_CONTENT_DATE 	  
            //MEDIA_ID 	int(11) 			
            //MOBAPP_CONTENT_LATITUDE 
            //MOBAPP_CONTENT_LONGITUDE
            //------------ End Input ----------  
            

            //------------ Begin stopStandardForm ----------  
            $form .= $this->oForm->endFormTable();
            $form .= $this->endForm($this->oForm);
            $form .= $this->oForm->close();
            //------------ End stopStandardForm ----------  
            $this->setResponse($form);
        }
    }  
<?php  
class Mobapp_Notification_Controller extends Pelican_Controller_Back  
{  
    protected $administration = false;  
  
    protected $form_name = "mobapp_notification";  
  
    protected $field_id = "MOBAPP_NOTIFICATION_ID";  
  
    protected $defaultOrder = "MOBAPP_NOTIFICATION_TITLE";  
  
    protected function setListModel ()  
    {  
        $this->listModel = "SELECT * from #pref#_mobapp_notification  
        where SITE_ID = ".$_SESSION[APP]['SITE_ID']."
        order by " . $this->listOrder;  
    }  
  
    protected function setEditModel ()  
    {  
        $this->aBind[':ID'] = $this->id;  
        $this->editModel = "SELECT * from #pref#_mobapp_notification WHERE MOBAPP_NOTIFICATION_ID=:ID";  
    }  
  
    public function listAction ()  
    {  
        parent::listAction();  
        $table = Pelican_Factory::getInstance('List', '', '', 0, 0, 0, 'liste');  
  
 //------------ Begin Input ----------    
 //        {CODE}    
 //------------ End Input ----------    
  
        $this->setResponse($table->getTable());  
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
//        {CODE}  
//------------ End Input ----------  
  
//------------ Begin stopStandardForm ----------  
        $form .= $this->oForm->endFormTable();  
        $form .= $this->endForm($this->oForm);  
        $form .= $this->oForm->close();  
//------------ End stopStandardForm ----------  
        $this->setResponse($form);          
    }  
}
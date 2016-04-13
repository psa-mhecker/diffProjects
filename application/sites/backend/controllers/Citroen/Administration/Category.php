<?php
class Citroen_Administration_Category_Controller extends Pelican_Controller_Back
{
    protected $administration = false; //true

    protected $form_name = "category";

    protected $field_id = "CATEGORY_ID";

    protected $defaultOrder = "CATEGORY_LABEL";

    protected function setListModel ()
    {
        $this->listModel = "SELECT * from #pref#_category
        order by " . $this->listOrder;
    }

    protected function setEditModel ()
    {
        $this->aBind[':ID'] = $this->id;
    	$this->editModel = "SELECT * from #pref#_category WHERE CATEGORY_ID=:ID";
    }

    public function listAction ()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', '', '', 0, 0, 0, 'liste');

          

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

         

//------------ Begin stopStandardForm ----------
        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();
//------------ End stopStandardForm ----------
        $this->setResponse($form);        
    }
}
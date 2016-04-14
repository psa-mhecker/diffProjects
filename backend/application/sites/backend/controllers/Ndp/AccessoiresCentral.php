<?php
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';

class Ndp_AccessoiresCentral_Controller extends Ndp_Controller
{
    protected $form_name    = "accessoires";
    protected $field_id     = "SITE_ID";
    protected $defaultOrder = "SITE_ID";


    protected function setEditModel()
    {
        $this->aBind[':ID'] = (int) $this->id;
        $sql = <<<SQL
                SELECT
                    *
                FROM
                    #pref#_{$this->form_name}
                WHERE
                     SITE_ID = :ID
SQL;
        $this->editModel = $sql;
    }

    /**
     *
     */
    public function listAction()
    {
        $this->id = $_SESSION[APP]['SITE_ID']; ;
        $this->_initBack();
        $this->_forward('edit');
    }

    public function editAction()
    {
        parent::editAction();
        $this->oForm                = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $form                       = $this->oForm->open(Pelican::$config['DB_PATH']);
        $form .= $this->beginForm($this->oForm);
        $form .= $this->oForm->beginFormTable();
        $form .= $this->oForm->createHidden('SITE_ID', $this->id);
        $form .= $this->oForm->createComment(t('NDP_MSG_PARAM_DEFAULT_VISUAL_ACC'));
        $form .= $this->oForm->createMedia("MEDIA_ID", t('NDP_ACCESORIES_VISU'), true, "image", "", $this->values["MEDIA_ID"], $this->readO, true, false, 'NDP_RATIO_ACCESSORIES_MEDIA_SPECIFIC:1443x962');
        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();
        $this->setResponse($form);
    }

    public function saveAction()
    {
        $connection                        = Pelican_Db::getInstance();
        $bind                              = [':SITE_ID' => Pelican_Db::$values['SITE_ID'],
            ':MEDIA_ID' => Pelican_Db::$values['MEDIA_ID']];
        $query                             = 'REPLACE INTO  #pref#_'.$this->form_name.'(SITE_ID, MEDIA_ID) VALUES (:SITE_ID, :MEDIA_ID)';
        $connection->query($query, $bind);
        //Fix du form retour pour ne pas être redigiré ailleurs.
        Pelican_Db::$values['form_retour'] = '/_/Index/child?tid='.$this->getParam('tid').';tc=&amp;view=O_1&amp;toprefresh=1&amp;toprefresh=1';
    }
}

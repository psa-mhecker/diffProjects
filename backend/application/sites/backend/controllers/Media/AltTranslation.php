<?php
require_once pelican_path('Media');
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';

class Media_AltTranslation_Controller extends Ndp_Controller
{
    protected $multiLangue  = true;
    protected $form_name    = "media_alt_translation";
    protected $field_id     = "MEDIA_ID";
    protected $listQuery;
    protected $languages;

    public function indexAction()
    {
        $this->id = $this->getParam('MEDIA_ID');
        $this->editAction();
    }

    /**
     *
     */
    public function editAction()
    {
        parent::editAction();
        $this->oForm = $this->getParam('oForm');

        $this->fillTranslationFormData();
        $this->initTabs();
        $form = '<tr><td colspan="2">';
        $form .= $this->fillTabs();
        $form.'</td></tr>';
        $this->setResponse($form);
    }

    protected function fillTranslationFormData()
    {
        $con = Pelican_Db::getInstance();
        $sql = 'SELECT * FROM #pref#_media_alt_translation WHERE MEDIA_ID = :MEDIA_ID';
        $bind = [':MEDIA_ID' => $this->getParam('MEDIA_ID')];
        $res = $con->queryTab($sql, $bind);
        foreach ($res as $trans) {
            $this->values[$trans['LANGUE_ID']] = $trans;
        }
    }

    protected function initTabs()
    {
        foreach ($this->getLangues() as $language) {
            $this->oForm->setTab($language['LANGUE_ID'], $language['LANGUE_CODE']);
        }
    }

    protected function fillTabs()
    {
        $form = '';
        foreach ($this->getLangues() as $language) {
            $form .= $this->oForm->beginTab($language['LANGUE_ID']);
            $form .= $this->oForm->createInput($this->form_name.'['.$language['LANGUE_ID'].'][TITLE]', t('TITRE'), 255, '', true, $this->values[$language['LANGUE_ID']]['TITLE'], $this->readO, 38);
            $form .= $this->oForm->createInput($this->form_name.'['.$language['LANGUE_ID'].'][ALT]', t('LEGENDE'), 255, '', false, $this->values[$language['LANGUE_ID']]['ALT'], $this->readO, 38);
        }
        $form .= $this->oForm->endTab();

        return $form;
    }

    protected function getLangues()
    {
        if (!isset($this->languages)) {
            $con = Pelican_Db::getInstance();
            $sql = 'SELECT
                    l.*
                 FROM #pref#_site_language sl INNER JOIN #pref#_language l ON l.LANGUE_ID = sl.LANGUE_ID
                 WHERE sl.SITE_ID =:SITE_ID ';
            $bind = [];
            $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
            $this->languages = $con->queryTab($sql, $bind);
        }

        return  $this->languages;
    }

    public function saveAction()
    {
        $connection = Pelican_Db::getInstance();
        $values = Pelican_Db::$values[$this->form_name];
        $params = $this->getParams();
        $oldValue = Pelican_Db::$values;
        // clear old vales
        $sql = 'DELETE FROM #pref#_'.$this->form_name.' WHERE MEDIA_ID = :MEDIA_ID';
        $bind = [':MEDIA_ID' => $params['MEDIA_ID']];
        $connection->query($sql, $bind);
        // save values
        foreach ($values as $langueId => $value) {
            Pelican_Db::$values = $value;
            Pelican_Db::$values['LANGUE_ID'] = $langueId;
            Pelican_Db::$values['MEDIA_ID'] =  $params['MEDIA_ID'];
            $connection->insertQuery('#pref#_'.$this->form_name);
        }

        Pelican_Db::$values = $oldValue;
    }
}

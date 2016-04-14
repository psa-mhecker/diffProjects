<?php

require_once Pelican::$config['APPLICATION_CONTROLLERS'] . '/Ndp.php';

class Ndp_AppliMobile_Controller extends Ndp_Controller
{

    const OPEN_SELF = "_self";
    const OPEN_BLANK = "_blank"; 
    
    protected $multiLangue = true;
    protected $administration = true;
    protected $form_name = "appli_mobile";
    protected $field_id = "APPMOBILE_ID";
    protected $defaultOrder = "APPMOBILE_ID";

    protected function setListModel()
    {
        $connection = Pelican_Db::getInstance();
        $bind = [
            ':SITE_ID' => (int) $_SESSION[APP]['SITE_ID'],
            ':LANGUE_ID' => (int) $_SESSION[APP]['LANGUE_ID']
        ];
        $sql = "
                SELECT
                    APPMOBILE_ID,
                    APPMOBILE_LABEL_BO
                FROM
                    #pref#_{$this->form_name}";
        $sql .= " WHERE
            SITE_ID = :SITE_ID
            AND LANGUE_ID = :LANGUE_ID
            ORDER BY {$this->listOrder}";
        $data = $connection->queryTab($sql, $bind);

        $this->listModel = $data;
    }

    protected function setEditModel()
    {
        $this->aBind[':APPMOBILE_ID'] = (int) $this->id;
        $this->aBind[':SITE_ID'] = (int)  $_SESSION[APP]['SITE_ID'];
        $this->aBind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $sql = <<<SQL
                SELECT
                    *
                FROM
                    #pref#_{$this->form_name}
                WHERE
                     APPMOBILE_ID = :APPMOBILE_ID
                     AND SITE_ID = :SITE_ID
                     AND LANGUE_ID = :LANGUE_ID
SQL;

        $this->editModel = $sql;
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setValues($this->getListModel(), "ID");
        $table->addColumn(t('ID'), "APPMOBILE_ID", "20", "center", "", "tblheader", "APPMOBILE_ID");
        $table->addColumn(t('NDP_LABEL_BO'), "APPMOBILE_LABEL_BO", "45", "center", "", "tblheader", "APPMOBILE_LABEL_BO");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "APPMOBILE_ID"), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "APPMOBILE_ID", "" => "readO=true"), "center");

        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        parent::editAction();

        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $form  = $this->oForm->open(Pelican::$config['DB_PATH']);
        $form .= $this->beginForm($this->oForm);
        $form .= $this->oForm->beginFormTable();
        // application mobile
        $form .= $this->oForm->createHeader(t('NDP_APPLICATION_MOBILE'));
        $form .= $this->oForm->createInput('APPMOBILE_LABEL_BO', t('NDP_LABEL_BO'), 30, '', true, $this->values['APPMOBILE_LABEL_BO'], $this->readO, 44);
        $form .= $this->oForm->createMedia("MEDIA_ID", t('NDP_VISUEL_MOBILE'), true, "image", "", $this->values["MEDIA_ID"], $this->readO, true, false);
        $form .= $this->oForm->createInput('APPMOBILE_URL_VISUEL', t('NDP_URL_VISUEL'), 255, 'internallink', true, $this->values['APPMOBILE_URL_VISUEL'], $this->readO, 44);
        if (empty($this->values['APPMOBILE_MODE_OUVERTURE'])) {
            $this->values['APPMOBILE_MODE_OUVERTURE'] = self::OPEN_SELF;
        }
        $form .= $this->oForm->createRadioFromList("APPMOBILE_MODE_OUVERTURE", t('MODE_OUVERTURE'), array(self::OPEN_SELF => t('NDP_SELF'), self::OPEN_BLANK => t('NDP_BLANK')), $this->values['APPMOBILE_MODE_OUVERTURE'], true, $this->readO);
        $form .= $this->oForm->createEditor('APPMOBILE_LABEL', t('NDP_LABEL_FO'), true, $this->values['APPMOBILE_LABEL'], $this->readO, true, "", 370, 25);
        $form .= $this->oForm->createTextArea('APPMOBILE_TEXTE', t('NDP_DESCRIPTION'), true, $this->values['APPMOBILE_TEXTE'], 300, $this->readO, 2, 44);
        // badge et url
        $form .= $this->oForm->showSeparator();
        $form .= $this->oForm->createHeader(t('NDP_URL_AND_BADGES'));
        $form .= $this->oForm->createInput('APPMOBILE_URL_GOOGLEPLAY', t('NDP_URL_GOOGLE_PLAY'), 255, '', false, $this->values['APPMOBILE_URL_GOOGLEPLAY'], $this->readO, 44);
        $form .= $this->oForm->createMedia("MEDIA_GOOGLEPLAY", t('NDP_BADGE_GOOGLE_PLAY'), false, "image", "", $this->values["MEDIA_GOOGLEPLAY"], $this->readO, true, false);
        $form .= $this->oForm->createInput('APPMOBILE_URL_APPLESTORE', t('NDP_URL_APPLE_STORE'), 255, '', false, $this->values['APPMOBILE_URL_APPLESTORE'], $this->readO, 44);
        $form .= $this->oForm->createMedia("MEDIA_APPLESTORE", t('NDP_BADGE_APPLE_STORE'), false, "image", "", $this->values["MEDIA_APPLESTORE"], $this->readO, true, false);
        $form .= $this->oForm->createInput('APPMOBILE_URL_WINDOWS', t('NDP_URL_WINDOWS'), 255, '', false, $this->values['APPMOBILE_URL_WINDOWS'], $this->readO, 44);
        $form .= $this->oForm->createMedia("MEDIA_WINDOWS", t('NDP_BADGE_WINDOWS'), false, "image", "", $this->values["MEDIA_WINDOWS"], $this->readO, true, false);

        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();

        $this->setResponse($form);
    }

    public function saveAction()
    {
        if (Pelican_Db::$values[$this->field_id] == -2) {
            Pelican_Db::$values[$this->field_id] = $this->getNextId();
        }

        parent::saveAction(); // TODO: Change the autogenerated stub

    }
}

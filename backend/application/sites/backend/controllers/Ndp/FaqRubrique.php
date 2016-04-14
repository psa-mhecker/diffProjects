<?php

require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';


/** Gestion des rubriques de FAQ
 *
 */
class Ndp_FaqRubrique_Controller extends Ndp_Controller
{

    const CONTENT_CATEGORY_CODE_RUB = 'FAQ_RUB';
    const CONTENT_TYPE_FAQ = 6;

    protected $administration = true; //false
    protected $multiLangue = true;
    protected $form_name = "content_category";
    protected $defaultOrder = "CONTENT_CATEGORY_ORDER";
    protected $field_id = "CONTENT_CATEGORY_ID";

    protected function setListModel()
    {
        $connection = Pelican_Db::getInstance();

        $bind = array(
          ':SITE_ID' => $_SESSION[APP]['SITE_ID'],
          ':LANGUE_ID' =>  $_SESSION[APP]['LANGUE_ID'],
          ':CONTENT_CATEGORY_CODE' =>  $connection->strToBind(self::CONTENT_CATEGORY_CODE_RUB)
        );

        $sql = '
            SELECT
                CONTENT_CATEGORY_ID,
                CONTENT_CATEGORY_LABEL,
                CONTENT_CATEGORY_ORDER
            FROM
                #pref#_content_category
            WHERE
                SITE_ID = :SITE_ID
            AND LANGUE_ID = :LANGUE_ID
            AND CONTENT_CATEGORY_CODE =:CONTENT_CATEGORY_CODE
            ORDER BY CONTENT_CATEGORY_ORDER
        ';

        $this->listModel = $connection->queryTab($sql, $bind);
    }

    protected function setEditModel()
    {
        $this->aBind = array(
          ':SITE_ID' => $_SESSION[APP]['SITE_ID'],
          ':LANGUE_ID' =>  $_SESSION[APP]['LANGUE_ID'],
          ':CONTENT_CATEGORY_ID' =>  $this->id
        );
        $this->editModel = 'SELECT
								*
							FROM
								#pref#_content_category
            WHERE
                SITE_ID = :SITE_ID
            AND LANGUE_ID = :LANGUE_ID
            AND CONTENT_CATEGORY_ID = :CONTENT_CATEGORY_ID';
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");

        $table->setValues($this->getListModel(), "");
        $table->setTableOrder("#pref#_content_category",
          "CONTENT_CATEGORY_ID",
          "CONTENT_CATEGORY_ORDER",
          "",
          "SITE_ID = ".$_SESSION[APP]['SITE_ID']." AND LANGUE_ID = ".$_SESSION[APP]['LANGUE_ID']." AND CONTENT_CATEGORY_CODE = '".self::CONTENT_CATEGORY_CODE_RUB."'"
        );
        $table->addColumn(t('ID'), "CONTENT_CATEGORY_ID", "5", "center", "", "tblheader", "");
        $table->addColumn(t('RUBRIQUE'), "CONTENT_CATEGORY_LABEL", "10", "center", "", "tblheader");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => 'CONTENT_CATEGORY_ID'), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "CONTENT_CATEGORY_ID", "" => "readO=true"), "center");

       $this->setResponse($table->getTable());
    }

    public function editAction() {
        parent::editAction();
        $form = $this->startStandardForm();
        $form .= $this->oForm->createHidden($this->field_id, $this->id);
        $form .= $this->oForm->createHidden('SITE_ID', $_SESSION[APP]['SITE_ID']);
        $form .= $this->oForm->createHidden('LANGUE_ID', $_SESSION[APP]['LANGUE_ID']);
        $form .= $this->oForm->createHidden('CONTENT_TYPE_ID', self::CONTENT_TYPE_FAQ);
        if ($this->values['CONTENT_CATEGORY_ORDER'] == null) {
            $this->values['CONTENT_CATEGORY_ORDER'] = count($this->getListModel()) + 1;
        }
        $form .= $this->oForm->createHidden('CONTENT_CATEGORY_ORDER', $this->values['CONTENT_CATEGORY_ORDER']);
        $form .= $this->oForm->createHidden('CONTENT_CATEGORY_CODE', self::CONTENT_CATEGORY_CODE_RUB);
        $form .= $this->oForm->createInput('CONTENT_CATEGORY_LABEL', t('RUBRIQUE'), 25, '', true, $this->values['CONTENT_CATEGORY_LABEL'], $this->readO, 100);
        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();
        $this->setResponse($form);
    }
}

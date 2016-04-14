<?php

require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';


/** Gestion des categories de FAQ
 *
 */
class Ndp_FaqCategory_Controller extends Ndp_Controller
{

    const CONTENT_CATEGORY_CODE_CAT = 'FAQ_CAT';
    const CONTENT_CATEGORY_CODE_RUB = 'FAQ_RUB';
    const CONTENT_TYPE_FAQ = 6;

    protected $administration = true; //false
    protected $multiLangue = true;
    protected $form_name = "content_category";
    protected $defaultOrder = "CONTENT_CATEGORY_LABEL";
    protected $field_id = "CONTENT_CATEGORY_ID";

    protected function setListModel()
    {
        $connection = Pelican_Db::getInstance();

        $bind = array(
            ':SITE_ID' => $_SESSION[APP]['SITE_ID'],
            ':LANGUE_ID' =>  $_SESSION[APP]['LANGUE_ID'],
            ':CONTENT_CATEGORY_CODE' =>  $connection->strToBind(self::CONTENT_CATEGORY_CODE_CAT)
        );

        $sql = '
            SELECT
                c.CONTENT_CATEGORY_ID,
                c.CONTENT_CATEGORY_ID as CHILD_ID,
                c.CONTENT_CATEGORY_LABEL,
                cc.CONTENT_CATEGORY_ORDER
            FROM
                #pref#_content_category as c
            LEFT JOIN #pref#_content_category_category as cc on c.CONTENT_CATEGORY_ID = cc.CHILD_ID
            WHERE
                SITE_ID = :SITE_ID
            AND LANGUE_ID = :LANGUE_ID
            AND CONTENT_CATEGORY_CODE =:CONTENT_CATEGORY_CODE
            GROUP BY CHILD_ID
            ORDER BY cc.CONTENT_CATEGORY_ORDER';

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
        $table->setTableOrder("#pref#_content_category_category",
            "CHILD_ID",
            "CONTENT_CATEGORY_ORDER",
            "",
            ""
        );
        $table->addColumn(t('ID'), "CONTENT_CATEGORY_ID", "5", "center", "", "tblheader", "");
        $table->addColumn(t('CATEGORIE'), "CONTENT_CATEGORY_LABEL", "10", "center", "", "tblheader", '');
        $sql = "select distinct cc.CHILD_ID as \"id\", c.CONTENT_CATEGORY_LABEL as \"lib\"
                from #pref#_content_category_category cc , #pref#_content_category c
                WHERE cc.PARENT_ID = c.CONTENT_CATEGORY_ID";
        $table->addMulti(t('RUBRIQUE'), 'CHILD_ID', "25", "left", "<br>", "tblheader", "", $sql);
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
        $form .= $this->oForm->createHidden('CONTENT_CATEGORY_CODE', self::CONTENT_CATEGORY_CODE_CAT);
        $form .= $this->oForm->createInput('CONTENT_CATEGORY_LABEL', t('CATEGORIE'), 25, '', true, $this->values['CONTENT_CATEGORY_LABEL'], $this->readO, 100);
        $form .= $this->oForm->createCheckBoxFromList('CONTENT_CATEGORY_ATTRIBUT', t('NDP_FAQ_FOCUS'), array("1" => ""), $this->values['CONTENT_CATEGORY_ATTRIBUT'], false, $this->readO);

        $sqlData = "
            SELECT
                CONTENT_CATEGORY_ID as id,
                CONTENT_CATEGORY_LABEL as lib
            FROM
                #pref#_content_category
            WHERE
                SITE_ID = ".$_SESSION[APP]['SITE_ID']."
            AND LANGUE_ID = ".$_SESSION[APP]['LANGUE_ID']."
            AND CONTENT_CATEGORY_CODE ='".self::CONTENT_CATEGORY_CODE_RUB."'
            ORDER BY CONTENT_CATEGORY_ORDER
          ";
        $sqlSelected = "select cc.PARENT_ID as id,
                c.CONTENT_CATEGORY_LABEL as lib
        from #pref#_content_category_category cc,
            #pref#_content_category c
         where cc.CHILD_ID=".$this->id."
         AND cc.CHILD_ID = c.CONTENT_CATEGORY_ID
        order by lib";
        $form .= $this->oForm->createAssocFromSql("", "RUBRIQUE_ID", t('RUBRIQUE'), $sqlData, $sqlSelected, true, true, $this->readO, 8, 200, false, "", array());

        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();
        $this->setResponse($form);
    }

    public function saveAction()
    {
        $connection = Pelican_Db::getInstance();
        if ($this->form_action != Pelican_Db::DATABASE_DELETE) {
            parent::saveAction();
            $connection->query("DELETE FROM #pref#_content_category_category WHERE CHILD_ID = ".Pelican_Db::$values["CONTENT_CATEGORY_ID"]);

            if (count(Pelican_Db::$values["RUBRIQUE_ID"]) > 0) {
                Pelican_Db::$values['CHILD_ID'] = Pelican_Db::$values["CONTENT_CATEGORY_ID"];
                $count = 0;
                foreach (Pelican_Db::$values["RUBRIQUE_ID"] as $parentId) {
                    $count++;
                    Pelican_Db::$values['PARENT_ID'] = $parentId;
                    Pelican_Db::$values['CONTENT_CATEGORY_ORDER'] = $count;
                    $connection->updateTable(Pelican_Db::DATABASE_INSERT, '#pref#_content_category_category');
                }
            }
        } else {
            $connection->query("DELETE FROM #pref#_content_category_category WHERE CHILD_ID = ".Pelican_Db::$values["CONTENT_CATEGORY_ID"]);
            parent::saveAction();
        }
    }
}

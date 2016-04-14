<?php

require_once Pelican::$config['APPLICATION_CONTROLLERS'] . '/Ndp.php';

/**
 * Class Ndp_FilterAfterSaleServices_Controller
 */
class Ndp_FilterAfterSaleServices_Controller extends Ndp_Controller
{
    const MAX_LIMIT = 6;
    const MIN_LIMIT = 2;

    protected $form_name    = "filter_after_sale_services";
    protected $field_id     = "ID";
    protected $defaultOrder = "FILTER_ORDER";
    protected $multiLangue    = true;
    protected $administration = true;
    protected $decacheBackOrchestra = array(
        'strategy' => array(
            array(
                'locale',
                'siteId',
                'filter_after_sale_services',
            )
        ),
    );

    protected function setListModel()
    {
        $con                = Pelican_Db::getInstance();
        $bind               = array();
        $bind[':SITE_ID']   = (int) $_SESSION[APP]['SITE_ID'];
        $bind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $sqlList            = 'SELECT ID, LABEL, LANGUE_ID, SITE_ID
                    FROM
                            #pref#_'. $this->form_name.'
                    WHERE
                        SITE_ID = :SITE_ID
			        AND LANGUE_ID = :LANGUE_ID
                    ORDER BY ' . $this->defaultOrder;
        $aRx             = $con->queryTab($sqlList, $bind);
        $this->listModel = $aRx;
    }

    protected function setEditModel()
    {
        $this->aBind[':' . $this->field_id] = (int) $this->id;
        $this->aBind[':SITE_ID']            = (int) $_SESSION[APP]['SITE_ID'];
        $this->aBind[':LANGUE_ID']          = (int) $_SESSION[APP]['LANGUE_ID'];
        $this->editModel                    = 'SELECT
                                    *
                            FROM
                                    #pref#_'. $this->form_name.'
                            WHERE
                                SITE_ID = :SITE_ID
                            AND LANGUE_ID = :LANGUE_ID
                            AND ' . $this->field_id . ' = :' . $this->field_id;
    }

    protected function getSiteFilter()
    {
        $con                = Pelican_Db::getInstance();
        $bind               = array();
        $bind[':SITE_ID']   = (int) $_SESSION[APP]['SITE_ID'];
        $sqlList            = 'SELECT FILTER_AFTER_SALE_SERVICE
                    FROM
                            #pref#_site
                    WHERE
                        SITE_ID = :SITE_ID';

        return $con->queryItem($sqlList, $bind);
    }

    public function listAction()
    {
        $min = 2;
        $max = 6;
        parent::listAction();
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $form = $this->oForm->open(Pelican::$config['DB_PATH']);
        $form .= $this->beginForm($this->oForm);
        $form .= $this->oForm->createDescription(t('NDP_MIN_AND_MAX_FILTER', "", array('#min#' => $min, '#max#' => $max)));
        $form .= $this->oForm->createDescription(t('NDP_NO_FILTER_NO_SERVICES'));
        $form .= $this->oForm->createCheckBoxFromList('FILTER_AFTER_SALE_SERVICE', t('NDP_NO_FILTER'), array(1 => ""), $this->getSiteFilter(), false, false, 'h', false);
        $table = Pelican_Factory::getInstance('List', '', '', 0, 0, 0, 'liste');
        $table->setTableOrder("#pref#_filter_after_sale_services", "ID", "FILTER_ORDER", "", "SITE_ID = ".$_SESSION[APP]['SITE_ID']." AND LANGUE_ID = ".$_SESSION[APP]['LANGUE_ID']);
        $table->setValues($this->getListModel(), 'ID');

        $table->addColumn(t('ID'), 'ID', '20', 'center', '', 'tblheader', 'ID');
        $table->addColumn(t('NDP_FILTER_AFTER_SALE_SERVICE_LABEL'), 'LABEL', '45', 'center', '', 'tblheader', 'LABEL');
        $table->addInput(t('FORM_BUTTON_EDIT'), 'button', array('id' => 'ID'), 'center');
        if ($this->getCountFilter() > $min) {
            $table->addInput(t('POPUP_LABEL_DEL'), 'button', array('id' => 'ID', '' => 'readO=true'), 'center');
        }

        $form .= $table->getTable();

        if ($this->getCountFilter() >= $max) {
            // hide add button
            $this->aButton["add"] = "";
            Backoffice_Button_Helper::init($this->aButton);
        }

        $form .= $this->oForm->close();

        $this->assign("form", $form, false);
        $this->fetch();
    }

    public function editAction()
    {
        parent::editAction();
        $form     = $this->startStandardForm();
        $form .= $this->oForm->createHidden($this->field_id, $this->id);
        $form .= $this->oForm->createHidden('SITE_ID', $_SESSION[APP]['SITE_ID']);
        $form .= $this->oForm->createHidden('LANGUE_ID', $_SESSION[APP]['LANGUE_ID']);
        $form .= $this->oForm->createHidden("FILTER_ORDER", $this->values["FILTER_ORDER"] ? $this->values["FILTER_ORDER"] : ($this->getCountFilter() + 1));

        $form .= $this->oForm->createInput('LABEL', t('NDP_FILTER_AFTER_SALE_SERVICE_LABEL'), 255, '', true, $this->values['LABEL'], $this->readO, 75);

        $values = array();
        if ($this->readO) {
            $values = $this->getListAfterSaleServices($this->id);

            if (!empty($values)) {
                $form .= $this->oForm->createTextArea('NAME', t('NDP_FILTER_ASSOCIATED_FOLLOWING_AFTER_SALE_SERVICES'), false, $values, 255, true, count($values));
            }
        }
        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);

        if ($this->readO && !empty($values)) {
            $this->aButton["delete"] = "";
            Backoffice_Button_Helper::init($this->aButton);
        }

        $form .= $this->oForm->close();
        $this->setResponse($form);
    }

    protected function getListAfterSaleServices($id)
    {
        $result = array();

        if ((int)$this->getSiteFilter() === 0) {
            $con = Pelican_Db::getInstance();
            $bind = array(':SITE_ID' => (int) $_SESSION[APP]['SITE_ID'],
                ':LANGUE_ID' => (int) $_SESSION[APP]['LANGUE_ID'],
                ':FILTER_ID' => (int) $id
            );
            $sql = 'SELECT AFTER_SALE_SERVICES_ID
                FROM #pref#_after_sale_services_filters_relation
                WHERE SITE_ID = :SITE_ID
                AND LANGUE_ID = :LANGUE_ID
                AND FILTERS_ID = :FILTER_ID';

            $relationRes = $con->queryTab($sql, $bind);

            if (!empty($relationRes)) {
                foreach ($relationRes as $relation) {
                    $result[] = $this->getAfterSaleService($relation['AFTER_SALE_SERVICES_ID']);
                }
            }
        }

        return $result;
    }

    protected function getAfterSaleService($id)
    {
        $con = Pelican_Db::getInstance();
        $bind = array(':SITE_ID' => (int) $_SESSION[APP]['SITE_ID'],
            ':LANGUE_ID' => (int) $_SESSION[APP]['LANGUE_ID'],
            ':ID' => (int) $id
        );
        $sql = 'SELECT LABEL
                FROM #pref#_after_sale_services
                WHERE SITE_ID = :SITE_ID
                AND LANGUE_ID = :LANGUE_ID
                AND ID = :ID';

        return $con->queryItem($sql, $bind);
    }

    protected function getCountFilter()
    {
        $con = Pelican_Db::getInstance();
        $bind = array(':SITE_ID' => (int) $_SESSION[APP]['SITE_ID'],
            ':LANGUE_ID' => (int) $_SESSION[APP]['LANGUE_ID']);
        $sql = 'SELECT count(*)
                FROM #pref#_'. $this->form_name.'
                WHERE SITE_ID = :SITE_ID
                AND LANGUE_ID = :LANGUE_ID';

        return $con->queryItem($sql, $bind);
    }

    public function saveAction()
    {
        if (Pelican_Db::$values[$this->field_id] == -2) {
            Pelican_Db::$values[$this->field_id] = $this->getNextId();
        }
        parent::saveAction();
    }

    public function saveSiteAction()
    {
        $params = $this->getParams();
        $value = $params['FILTER_AFTER_SALE_SERVICE'];
        $con = Pelican_Db::getInstance();
        $bind = array(':SITE_ID' => (int) $_SESSION[APP]['SITE_ID'],
            ':FILTER_AFTER_SALE_SERVICE' => $value);
        $sql = 'UPDATE #pref#_site
                SET FILTER_AFTER_SALE_SERVICE = :FILTER_AFTER_SALE_SERVICE
                WHERE SITE_ID = :SITE_ID';

        $con->queryItem($sql, $bind);
    }
}

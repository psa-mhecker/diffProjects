<?php
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Administration/Site/National/Parameters.php';

class Ndp_Modele_Controller extends Ndp_Controller
{
    const ORDER_AO = 'AO';
    const ORDER_PRICE_INC = 'INC';
    const ORDER_PRICE_DESC = 'DESC';

    protected $form_name = "model_site";
    protected $field_id = "LCDV4";
    protected $defaultOrder = "ID";
    protected $multiLangue = true;

    protected function setListModel()
    {
        $connection = Pelican_Db::getInstance();
        $model = $_GET['filter_MODEL'];
        $query = '
                SELECT
                    @row_number:=@row_number+1 AS ID,
                    m.LCDV4,
                    m.GENDER,
                    m.MODEL,
                    IF (CHAR_LENGTH (ms.SLOGAN) > 30,
                    CONCAT(SUBSTR(ms.SLOGAN, 1, 30), "..."),
                    SUBSTR(ms.SLOGAN, 1, 30)
                    ) as SHORT_SLOGAN,
                    ms.FINISHING_ORDER
                FROM (SELECT @row_number:=0) AS t, #pref#_model  m
                LEFT JOIN #pref#_'.$this->form_name.' ms ON m.LCDV4 = ms.LCDV4
                AND (SITE_ID='.$_SESSION[APP]['SITE_ID'].' OR SITE_ID IS NULL) AND (LANGUE_ID='.$_SESSION[APP]['LANGUE_ID'].' OR LANGUE_ID IS NULL)';

        $where = [];
        if ($_SESSION[APP]['ANGLE_GENDER_FILTER_MODEL'] != $_GET['filter_GENDER']) {
            $model = '';
            $_SESSION[APP]['ANGLE_GENDER_FILTER_MODEL'] = $_GET['filter_GENDER'];
            unset($_GET['filter_MODEL']);
        }
        if (!empty($_GET['filter_GENDER'])) {
            $where[] = " m.GENDER =  '{$_GET['filter_GENDER']}' ";
        }
        if (!empty($model)) {
            $where[] = " m.MODEL =  '{$model}' ";
            $_SESSION[APP]['ANGLE_GENDER_FILTER_MODEL'] = $_GET['filter_GENDER'];
        }
        if (empty($_GET['filter_GENDER'])) {
            unset($_SESSION[APP]['ANGLE_GENDER_FILTER_MODEL']);
        }
        if (!empty($where)) {
            $query .= " WHERE ".implode(" and ", $where);
        }
        $query .= ' ORDER BY '.$this->listOrder;
        unset($_GET['filter_MODEL']);

        $this->listModel = $connection->queryTab($query, array());
    }

    protected function setEditModel()
    {
        $this->editModel = 'SELECT m.LCDV4,
                    m.GENDER,
                    m.MODEL,
                    ms.SLOGAN,
                    ms.FINISHING_ORDER
                FROM #pref#_model  m
                LEFT JOIN #pref#_'.$this->form_name.' ms ON m.LCDV4 = ms.LCDV4 AND (SITE_ID='.$_SESSION[APP]['SITE_ID'].' OR SITE_ID IS NULL) AND (LANGUE_ID='.$_SESSION[APP]['LANGUE_ID'].' OR LANGUE_ID IS NULL)
                WHERE m.LCDV4="'.$this->id.'"';

    }

    protected function updateModelTable()
    {
        $container = $this->getContainer();
        $rangeManager = $container->get('range_manager');
        $models = $rangeManager->getModelsFromSearch();

        $lcdv4 = [];
        if (!empty($models)) {
            foreach ($models as $model) {

                $lcdv4[] = $this->addOrUpdateModel($model);
            }
        }
        $this->deleteOldModel($lcdv4);

    }


    protected function addOrUpdateModel($model)
    {
        $connection = Pelican_Db::getInstance();

        $bind[":LCDV4"]     = $model['LCDV4'];
        $bind[":GENDER"]    = $model['gender'];
        $bind[":MODEL"]     = $model['model'];

        $sql = "INSERT INTO #pref#_model (
                        LCDV4,
                        GENDER,
                        MODEL
                        )
                        VALUES(
                        ':LCDV4',
                        ':GENDER',
                        ':MODEL'
                        )
                ON DUPLICATE KEY UPDATE GENDER = ':GENDER' , MODEL = ':MODEL'";

        $connection->query($sql, $bind);
        $bind[":SITE_ID"]   = $_SESSION[APP]['SITE_ID'];
        $bind[":LANGUE_ID"] = $_SESSION[APP]['LANGUE_ID'];
        $sql = "INSERT INTO #pref#_{$this->form_name} (
                LCDV4,
                SITE_ID,
                LANGUE_ID
                )
                VALUES(
                ':LCDV4',
                ':SITE_ID',
                ':LANGUE_ID'
                )
        ON DUPLICATE KEY UPDATE SLOGAN = SLOGAN";
        $connection->query($sql, $bind);

        return $model['LCDV4'];
    }

    protected function deleteOldModel($lcvd4s)
    {
        $connection = Pelican_Db::getInstance();


        $sql = 'DELETE FROM #pref#_model
              WHERE
              1=1';

        if (!empty($lcvd4s)) {
            $sql .= ' AND LCDV4 NOT IN ( "'.implode('","', $lcvd4s).'" ) ';
        }
        $connection->query($sql, []);

    }

    /**
     *
     */
    public function listAction()
    {
        $this->updateModelTable();

        parent::listAction();
        /** @var Pelican_List $table */
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setFilterField('GENDER', t('NDP_GENDER'), 'GENDER', $this->getFilterValue('GENDER'));
        $table->setFilterField('MODEL', t('NDP_MODELE'), 'MODEL', $this->getFilterValue('MODEL'));
        $table->getFilter(2);
        $orders = $this->getFinishingOrders();
        $table->setValues($this->getListModel(), "m.LCDV4");
        foreach ($table->aTableValues as $idx => $row) {
            $table->aTableValues[$idx]['FINISHING_ORDER'] = $orders[$row['FINISHING_ORDER']];
        }
        $table->addColumn(t('ID'), "ID", "20", "center", "", "tblheader", "ID");
        $table->addColumn(t('NDP_GENDER'), "GENDER", "5", "center", "", "tblheader", "GENDER");
        $table->addColumn(t('NDP_LCDV4'), "LCDV4", "8", "center", "", "tblheader", "LCDV4");
        $table->addColumn(t('NDP_MODELE'), "MODEL", "10", "center", "", "tblheader", "MODEL");
        $table->addColumn(t('NDP_SLOGAN'), "SHORT_SLOGAN", "40", "center", "", "tblheader", "SHORT_SLOGAN");
//        $table->addColumn(t('NDP_FINISHING_ORDER'), "FINISHING_ORDER", "15", "center", "", "tblheader", "FINISHING_ORDER");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "LCDV4"), "center");

        // hide add button
        $this->aButton["add"] = "";
        Backoffice_Button_Helper::init($this->aButton);

        $this->setResponse($table->getTable());
    }

    private function getFilterValue($field)
    {
        $con = Pelican_Db::getInstance();
        $sql = 'SELECT
                    DISTINCT '.$field.'
                FROM
                    #pref#_model
                ';
        $data = $con->queryTab($sql, []);
        $return = [];
        foreach ($data as $row) {
            $return[] = array($row[$field], $row[$field]);
        }

        return  $return;
    }

    public function editAction()
    {
        parent::editAction();
        $form = $this->startStandardForm();
        $form .= $this->oForm->createComment(t('NDP_MODELE').'<span>&nbsp ('.$this->values['GENDER'].' - '.$this->values['MODEL'].')</span>');
        $form .= $this->oForm->createHidden('LCDV4', $this->values['LCDV4']);
        $form .= $this->oForm->createHeader(t('NDP_TITLE_SLOGAN'));
        $form .= $this->oForm->showSeparator();
        $form .= $this->oForm->createDescription(t('NDP_MSG_SLOGAN'));
        $form .= $this->oForm->createTextArea('SLOGAN', t('NDP_SLOGAN'), false, $this->values['SLOGAN'], 60, false, 6, 70);
//        $form .= $this->oForm->createHeader(t('NDP_FINISHING_ENGINE'));
//        $form .= $this->oForm->showSeparator();
//        $form .= $this->oForm->createDescription(t('NDP_MSG_OVERRIDE'));
//        $form .= $this->oForm->createRadioFromList('FINISHING_ORDER', t(NDP_FINISHING_ORDER), $this->getFinishingOrders(), $this->values['FINISHING_ORDER']);
        $form .= $this->stopStandardForm();

        $this->setResponse($form);
    }

    protected function getFinishingOrders()
    {
        $finishingOrders = array(
            self::ORDER_AO => t('NDP_ORDER_AO'),
            self::ORDER_PRICE_INC => t('NDP_PRICE_ASC'),
            self::ORDER_PRICE_DESC => t('NDP_PRICE_DESC'),
        );

        return $finishingOrders;
    }
}

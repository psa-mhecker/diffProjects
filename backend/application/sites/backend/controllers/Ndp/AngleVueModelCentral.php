<?php
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Administration/Site/National/Parameters.php';
use PsaNdp\WebserviceConsumerBundle\Webservices\RangeManager;


class Ndp_AngleVueModelCentral_Controller extends Ndp_Controller
{

    protected $form_name    = "model";
    protected $field_id     = "LCDV4";
    protected $defaultOrder = "LCDV4";

    const MULTI_NAME = 'view_angle';
    const MODEL      = 'MODEL';
    const GENDER     = 'GENDER';
    const WS_GAMME   = 14;

    protected function setListModel()
    {
        $connection = Pelican_Db::getInstance();
        $model = $_GET['filter_MODEL'];
        $query = '
                SELECT
                    @row_number:=@row_number+1 AS MODEL_ID,
                    m.LCDV4,
                    m.GENDER,
                    m.MODEL,
                    COUNT(mva.CODE) AS NB_ANGLE
                FROM
                    (SELECT @row_number:=0) AS t,
                    #pref#_'.$this->form_name.' m  LEFT JOIN #pref#_model_view_angle mva ON m.LCDV4=mva.LCDV4
                    '
        ;
        if ($_SESSION[APP]['ANGLE_GENDER_FILTER'] != $_GET['filter_GENDER']) {
            $model = '';
            $_SESSION[APP]['ANGLE_GENDER_FILTER'] = $_GET['filter_GENDER'];
            unset($_GET['filter_MODEL']);
        }
        if (!empty($_GET['filter_GENDER'])) {
            $where[] = " GENDER =  '{$_GET['filter_GENDER']}' ";
        }
        if (!empty($model)) {
            $where[] = " MODEL =  '{$model}' ";
            $_SESSION[APP]['ANGLE_GENDER_FILTER'] = $_GET['filter_GENDER'];
        }
        if (empty($_GET['filter_GENDER'])) {
            unset($_SESSION[APP]['ANGLE_GENDER_FILTER']);
        }
        if (!empty($where)) {
            $query .= " WHERE ".implode(" and ", $where);
        }
        $query .= ' GROUP BY m.LCDV4 ORDER BY '.$this->listOrder;
        unset($_GET['filter_MODEL']);

        $this->listModel = $connection->queryTab($query, array());
    }

    protected function setEditModel()
    {
        $this->editModel = 'SELECT *
            from #pref#_'.$this->form_name.'
            WHERE LCDV4="'.$this->id.'"';
     }

    protected function updateModelTable()
    {
        $container = $this->getContainer();
        /** @var RangeManager $rangeManager */
        $rangeManager = $container->get('range_manager');
        $models = $rangeManager->getModelsFromSearch();
        $lcdv4 = [];
        if (!empty($models)) {
            foreach ($models as $model) {
                $lcdv4[] = $this->addOrUpdateModel($model);
            }
        }
        // update view angle list for each model  #NDP-4121 to count angle
        if (self::getWsState(self::WS_GAMME)) {
            foreach ($lcdv4 as $code) {
                $this->updateEditModelTable($code);
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

        return $model['LCDV4'];
    }

    protected function deleteOldModel($lcvd4s)
    {
        $connection = Pelican_Db::getInstance();


        $sql = 'DELETE FROM #pref#_model
              WHERE 1 = 1';


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
        $table->setFilterField('GENDER', t('NDP_GENDER'), 'GENDER', $this->getFilterValue(self::GENDER));
        $table->setFilterField('MODEL', t('NDP_MODELE'), 'MODEL', $this->getFilterValue(self::MODEL));
        $table->getFilter(2);
        $table->setValues($this->getListModel(), "LCDV4");
        $table->addColumn(t('ID'), 'MODEL_ID', '5', 'left', '', 'tblheader', 'MODEL_ID');
        $table->addColumn(t('NDP_GENDER'), 'GENDER', '5', 'left', '', 'tblheader', 'GENDER');
        $table->addColumn(t('NDP_LCDV4'), 'LCDV4', '4', 'left', '', 'tblheader', 'LCDV4');
        $table->addColumn(t('NDP_MODEL'), 'MODEL', '8', 'left', '', 'tblheader', 'MODEL');
        $table->addInput(t('NDP_FORM_BUTTON_VIEW'), "button", array("id" => "LCDV4"), "center", array('NB_ANGLE!=0'));

        // hide add button
        $this->aButton["add"] = "";
        Backoffice_Button_Helper::init($this->aButton);

        $this->setResponse($table->getTable());
    }

    /**
     * 
     * @param string $field
     * @return array
     */
    private function getFilterValue($field)
    {
        $con = Pelican_Db::getInstance();
        $sql = 'SELECT
                    DISTINCT '.$field.'
                FROM
                    #pref#_'.$this->form_name;
        if ($field === self::MODEL && !empty($_GET['filter_GENDER'])) {
            $sql .= ' WHERE GENDER = \''.$_GET['filter_GENDER'].'\'';
        }
        $data = $con->queryTab($sql, []);
        $return = [];
        foreach ($data as $row) {
            $return[] = array($row[$field], $row[$field]);
        }
        
        return  $return;
    }

    /**
     * 
     * @param string $lcdv4
     * @return array
     */
    public function getListOfAngle($lcdv4)
    {
       $connection = Pelican_Db::getInstance();
       $sql = 'SELECT
                  CODE, START_ANGLE, ANGLE_ORDER, LCDV4
               FROM #pref#_model_view_angle
                    WHERE
                    LCDV4 = ":LCDV4"
                    ORDER BY ANGLE_ORDER ASC
               ';
        $bind[':LCDV4'] = $lcdv4;

        return  $connection->queryTab($sql, $bind);
    }

    /**
     *
     * @param string $lcdv4
     */
    protected function updateEditModelTable($lcdv4)
    {
        $container = $this->getContainer();
        $gamme = $container->get('ws_gamme');
        $params = [
            'input' => [
                'Site' => Pelican::$config['CODE_LANGUE_CLIENT'][$_SESSION[APP]['LANGUE_CODE']],
                'Culture' => strtolower($_SESSION[APP]['LANGUE_CODE']),
                'Model' => $lcdv4
                ]
            ];

        $models = $gamme->getExteriorViewsByModel($params);
        $order = 0;
        if (!empty($models)) {
            foreach ($models as $model) {
                $model['LCDV4'] = $lcdv4;
                $model['ANGLE_ORDER'] = $order++;
                $this->addOrUpdateEditModel($model);
            }
        }
    }
    
    /**
     * 
     * @param array  $model
     * 
     * @return string
     */
    protected function addOrUpdateEditModel($model)
    {
        $connection = Pelican_Db::getInstance();

        $bind[":LCDV4"]       = $model['LCDV4'];
        $bind[":CODE"]        = $model['code'];
        $bind[":START_ANGLE"] = $model['initial'];
        $bind[":ANGLE_ORDER"] = $model['ANGLE_ORDER'];

        $sql = "REPLACE INTO #pref#_model_view_angle (
                        CODE,
                        START_ANGLE,
                        ANGLE_ORDER,
                        LCDV4
                        )
                        VALUES(
                        ':CODE',
                        ':START_ANGLE',
                        ':ANGLE_ORDER',
                        ':LCDV4'
                        )";

        $connection->query($sql, $bind);
    }
    

    public function editAction()
    {
        parent::editAction();
        $form = $this->startStandardForm();
        $form .= $this->oForm->createComment(t('NDP_MODELE').'<span>&nbsp ('.$this->values['GENDER'].' - '.$this->values['MODEL'].')</span>');
        $form .= $this->oForm->createHidden('MODEL', $this->values['MODEL']);
        $form .= $this->oForm->createHidden('GENDER', $this->values['GENDER']);
        $form .= $this->oForm->createHidden('LCDV4', $this->values['LCDV4']);

        $strLib = array(
            'multiTitle' => '',
            'multiAddButton' => t('NDP_ADD_VIEW_ANGLE')
        );
        $options['noDragNDrop'] = true;
        $form .= $this->oForm->createMultiHmvc(
            $this->multi.self::MULTI_NAME, $strLib, array(
            "path" => __FILE__,
            "class" => __CLASS__,
            "method" => "addViewAngle"
            ), $this->getListOfAngle($this->id), self::MULTI_NAME, $this->readO, array(), false, false, $this->multi.self::MULTI_NAME
            , "values", "multi", "2", '', '', true, $options
        );

        $form .= $this->stopStandardForm();
        $this->aButton['save'] = "";
        Backoffice_Button_Helper::init($this->aButton);

        $this->setResponse($form);
    }


    public static function addViewAngle(Ndp_Form $form, $values, $readO, $multi)
    {
        $return  = $form->createInput($multi.'CODE', t('NDP_CODE'), 3, "alphanum", false, $values['CODE'], $readO, 10);
        $return .= $form->createCheckBoxFromList($multi.'START_ANGLE', t('NDP_INITIAL_ANGLE'), array(1 => ""), $values['START_ANGLE'], false, $readO, 'h', false); ;

        return $return;
    }

    public function saveAction()
    {
        $connection           = Pelican_Db::getInstance();
        $values               = Pelican_Db::$values;
        parent::saveAction();
        $tableAngle = "#pref#_model_view_angle";
        $bind                 = array(':LCDV4' => $values['LCDV4']);
        $connection->query('DELETE FROM '.$tableAngle.' WHERE LCDV4 = ":LCDV4"', $bind);

        $multiAngles = [];
        foreach ($values as $key => $value) {
            if (strpos($key, self::MULTI_NAME) !== false && strpos($key, '__CPT__') == false) {
                $multiAngles[trim(str_replace(self::MULTI_NAME, ' ', $key))] = $value;
            }
        }

        if (!empty($multiAngles)) {
            $usedAngles = [];
            for ($pos = 0; $pos < $multiAngles['count_']; $pos++)
                if ($multiAngles[$pos.'_multi_display'] == 1) {
                    $data = array();
                    $data['ANGLE_ORDER']         = $multiAngles[$pos.'_PAGE_ZONE_MULTI_ORDER'];
                    $data['START_ANGLE'] = isset($multiAngles[$pos.'_START_ANGLE']) ? $multiAngles[$pos.'_START_ANGLE'] : 0;
                    $data['CODE']                = $multiAngles[$pos.'_CODE'];
                    $data['LCDV4'] = $values['LCDV4'];
                    Pelican_Db::$values          = $data;
                    if (!in_array($data['CODE'], $usedAngles)) {
                        $connection->updateTable(Pelican_Db::DATABASE_INSERT, $tableAngle);
                        $usedAngles[] = $data['CODE'];
                    }
                }
        }


        Pelican_Db::$values = $values;
    }
}

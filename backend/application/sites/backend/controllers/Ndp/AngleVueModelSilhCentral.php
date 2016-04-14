<?php
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';

class Ndp_AngleVueModelSilhCentral_Controller extends Ndp_Controller
{
    const MULTI_NAME = "NDP_VIEW";
    const WS_GAMME   = 14;

    protected $form_name = "ws_gdg_model_silhouette";
    protected $defaultOrder = 'GENDER';
    protected $field_id = "ID";

    protected function setListModel()
    {
        $connection = Pelican_Db::getInstance();
        $bind[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $query = 'SELECT *,
                    COUNT(mva.CODE) AS NB_ANGLE
                    FROM
                    #pref#_'.$this->form_name.' wsg
                    LEFT JOIN #pref#_ws_gdg_model_silhouette_angle mva ON wsg.ID=mva.MODEL_SILHOUETTE_ID
                    ';
        $query .= $this->setWhereForList();
        $query .= ' GROUP BY wsg.LCDV6 ORDER BY '.$this->listOrder;
        unset($_GET['filter_SILHOUETTE']);
        
        $this->listModel = $connection->queryTab($query, $bind);
    }

    /**
     *
     * @return string
     */
    protected function setWhereForList()
    {
        $silhouette = '';
        $query      = '';
        if (!empty($_GET['filter_GENDER'])) {
            $where[] = "wsg.GENDER like '%".str_replace("'", "''", $_GET['filter_GENDER'])."%' ";
        }
        if ($_SESSION[APP]['SILH_ANGLE_MODEL_FILTER'] == $_GET['filter_MODEL'] && !empty($_SESSION[APP]['SILH_ANGLE_MODEL_FILTER'])) {
            $silhouette = $_GET['filter_SILHOUETTE'];
        }
        if (!empty($_GET['filter_MODEL'])) {
            $where[] = "wsg.MODEL like '%".str_replace("'", "''", $_GET['filter_MODEL'])."%' ";
            if (!empty($silhouette)) {
                $where[] = "wsg.SILHOUETTE like '%".str_replace("'", "''", $silhouette)."%' ";
            }
            $_SESSION[APP]['SILH_ANGLE_MODEL_FILTER'] = $_GET['filter_MODEL'];
        }
        if (empty($_GET['filter_MODEL'])) {
            unset($_SESSION[APP]['SILH_ANGLE_MODEL_FILTER']);
        }
        if (!empty($where)) {
            $query = " where ".implode(" and ", $where);
        }

        return $query;
    }


    protected function updateModelTable()
    {
        $container = $this->getContainer();
        $rangeManager = $container->get('range_manager');
        $models = $rangeManager->getModelsBodyStyleFromSearch();
        $lcdv6 = [];
        if (!empty($models)) {
            foreach ($models as $model) {
                $lcdv6[] = $this->addOrUpdateModel($model);
            }
        }
        // update view angle list for each model  #NDP-4121 to count angle
        if ($this->getWsState(self::WS_GAMME)) {
            $sql = 'SELECT * FROM #pref#_'.$this->form_name;
            $connection = Pelican_Db::getInstance();
            $models = $connection->queryTab($sql, []);
            foreach ($models as $model) {
                $this->updateEditModelTable($model);
            }
        }
        $this->deleteOldModel($lcdv6);
    }

    protected function addOrUpdateModel($model)
    {
        $connection = Pelican_Db::getInstance();

        $bind[":LCDV6"]     = $model['LCDV6'];
        $bind[":GENDER"]    = $model['gender'];
        $bind[":MODEL"]     = $model['model'];
        $bind[":SILHOUETTE"] = $model['silhouette'];

        $sql = "INSERT INTO #pref#_{$this->form_name} (
                        LCDV6,
                        GENDER,
                        MODEL,
                        SILHOUETTE
                        )
                        VALUES(
                        ':LCDV6',
                        ':GENDER',
                        ':MODEL',
                        ':SILHOUETTE'
                        )
                ON DUPLICATE KEY UPDATE GENDER = ':GENDER' , MODEL = ':MODEL', SILHOUETTE=':SILHOUETTE'";

        $connection->query($sql, $bind);

        return $model['LCDV6'];
    }

    protected function deleteOldModel($lcvd6s)
    {
        $connection = Pelican_Db::getInstance();
        $sql = "DELETE FROM #pref#_{$this->form_name}";
        if (!empty($lcvd6s)) {
            $sql .= ' WHERE LCDV6 NOT IN ( "'.implode('","', $lcvd6s).'" ) ';
        }

        $connection->query($sql, array());
    }
    
    public function listAction()
    {
        $this->updateModelTable();
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', '', '', 0, 0, 0, 'liste');
        $table->setFilterField('GENDER', t('NDP_GENDER'), "GENDER", $this->getSqlForGender());
        $table->setFilterField('MODEL', t('NDP_MODEL'), "MODEL", $this->getSqlForModel());
        $requete = array();
        $js = "<script type='text/javascript'>$('#filter_SILHOUETTE').prop('disabled', true);</script>";
        if (!empty($_GET['filter_MODEL'])) {
            $requete = $this->getSqlForSilhouette();
            $js = "";
        }        
        $table->setFilterField('SILHOUETTE', t('NDP_SILHOUETTE'), "SILHOUETTE", $requete);
        $table->getFilter(3);
        $table->setValues($this->getListModel(), 'ID');
        $table->addColumn(t('ID'), 'ID', '5', 'left', '', 'tblheader', 'ID');
        $table->addColumn(t('NDP_GENDER'), 'GENDER', '5', 'left', '', 'tblheader', 'GENDER');
        $table->addColumn(t('NDP_LCDV6'), 'LCDV6', '5', 'left', '', 'tblheader', 'LCDV6');
        $table->addColumn(t('NDP_MODEL'), 'MODEL', '8', 'left', '', 'tblheader', 'MODEL');
        $table->addColumn(t('NDP_SILHOUETTE'), 'SILHOUETTE', '8', 'left', '', 'tblheader', 'SILHOUETTE');
        $table->addInput(t('NDP_FORM_BUTTON_VIEW'), "button", array("id" => "ID"), "center", array('NB_ANGLE!=0'));

        $this->setResponse($table->getTable().$js);

        $this->aButton['add'] = '';
        Backoffice_Button_Helper::init($this->aButton);
    }

    /**
     *
     * @return string
     */
    public function getSqlForSilhouette()
    {
        $sql = "select DISTINCT SILHOUETTE as id, SILHOUETTE as lib FROM #pref#_{$this->form_name} WHERE MODEL = '{$_GET['filter_MODEL']}'";
        if (!empty($_GET['filter_GENDER'])) {
            $sql .= " AND GENDER = '{$_GET['filter_GENDER']}'";
        }
        $sql .= " ORDER BY SILHOUETTE ASC";

        return  $sql;

    }
    
    /**
     *
     * @return string
     */
    public function getSqlForGender()
    {
        $sql  = "select DISTINCT GENDER as id, GENDER as lib FROM #pref#_{$this->form_name}";
        $sql .= " ORDER BY GENDER ASC";

        return  $sql;
    }

    /**
     *
     * @return string
     */
    public function getSqlForModel()
    {
        $sql = "select DISTINCT MODEL as id, MODEL as lib FROM #pref#_{$this->form_name}";
        if (!empty($_GET['filter_GENDER'])) {
            $sql .= " WHERE GENDER = '{$_GET['filter_GENDER']}'";
        }
        $sql .= " ORDER BY MODEL ASC";

        return  $sql;
    }

    /**
     *
     * @param array $modelInfo
     */
    protected function updateEditModelTable($modelInfo)
    {
        $container = $this->getContainer();
        $gamme = $container->get('ws_gamme');
        $params    = [
            'input' => [
                'Site' => Pelican::$config['CODE_LANGUE_CLIENT'][$_SESSION[APP]['LANGUE_CODE']],
                'Culture' => strtolower($_SESSION[APP]['LANGUE_CODE']),
                'Body' => $modelInfo['LCDV6'],
                'Model' => substr($modelInfo['LCDV6'], 0, 4),
                'GrBody' => $modelInfo['SILHOUETTE']
            ]
        ];

        $models = $gamme->getExteriorViewsByModel($params);
        if (!empty($models)) {
            foreach ($models as $model) {
                $model['MODEL_SILHOUETTE_ID'] = $modelInfo['ID'];
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

        $bind[":MODEL_SILHOUETTE_ID"] = $model['MODEL_SILHOUETTE_ID'];
        $bind[":CODE"]                = $model['code'];
        $bind[":ANGLE"]               = $model['initial'];
        $bind[":ANGLE_ORDER"]         = $model['ANGLE_ORDER'];

        $sql = "INSERT INTO #pref#_ws_gdg_model_silhouette_angle (
                        CODE,
                        ANGLE,
                        ANGLE_ORDER,
                        MODEL_SILHOUETTE_ID
                        )
                        VALUES(
                        ':CODE',
                        ':ANGLE',
                        ':ANGLE_ORDER',
                        ':MODEL_SILHOUETTE_ID'
                        )
                          ON DUPLICATE KEY UPDATE CODE = ':CODE' , ANGLE = ':ANGLE', ANGLE_ORDER=':ANGLE_ORDER'";

        $connection->query($sql, $bind);
    }
    
    public function editAction()
    {
        parent::editAction();
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $form  = $this->oForm->open(Pelican::$config['DB_PATH']);
        $form .= $this->beginForm($this->oForm);
        $form .= $this->oForm->beginFormTable();
        $form .= $this->oForm->createHidden('id', $this->id);
        $form .= $this->oForm->createHidden('GENDER', $this->values['GENDER']);
        $form .= $this->oForm->createHidden('LCDV6', $this->values['LCDV6']);
        $form .= $this->oForm->createHidden('MODEL', $this->values['MODEL']);
        $form .= $this->oForm->createHidden('SILHOUETTE', $this->values['SILHOUETTE']);
        $form .= $this->oForm->createComment(t('NDP_MSG_GENRE_LCDV6')."(".$this->values['GENDER']." - ".$this->values['LCDV6'].")");

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
            ), self::getListOfAngle($this->id), self::MULTI_NAME, $this->readO, array(), false, false, $this->multi.self::MULTI_NAME
            , "values", "multi", "2", '', '', true, $options
        );
        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();
                $this->aButton['save'] = "";
        Backoffice_Button_Helper::init($this->aButton);

        $this->setResponse($form);
    }

    public static function addViewAngle(Ndp_Form $form, $values, $readO, $multi)
    {
        $return  = $form->createInput($multi.'CODE', t('NDP_CODE'), 3, "alphanum", false, $values['CODE'], $readO, 10);
        $return .= $form->createCheckBoxFromList($multi.'ANGLE', t('NDP_INITIAL_ANGLE'), array(1 => ""), $values['ANGLE'], false, $readO, 'h', false); ;

        return $return;
    }

    /**
     *
     * @param int $id
     *
     * @return array
     */
    private function getListOfAngle($id)
    {
        $connection = Pelican_Db::getInstance();
        $bind = array();
        $bind[':MODEL_SILHOUETTE_ID'] = $this->id;

        $sql = <<<SQL
                SELECT
                   *
                FROM
                    #pref#_{$this->form_name}_angle a
                WHERE
                    a.MODEL_SILHOUETTE_ID = :MODEL_SILHOUETTE_ID
                ORDER BY a.ANGLE_ORDER ASC
SQL;
        $result = $connection->queryTab($sql, $bind);

        return $result;
    }

    public function saveAction()
    {
        $connection           = Pelican_Db::getInstance();
        $values               = Pelican_Db::$values;
        parent::saveAction();
        $tableSilhouetteAngle = "#pref#_".$this->form_name."_angle";
        $bind                 = array(':MODEL_SILHOUETTE_ID' => Pelican_Db::$values['id']);
        $modelSilhouetteId    = Pelican_Db::$values['id'];
        $connection->query(""
            ."DELETE FROM $tableSilhouetteAngle "
            ."WHERE MODEL_SILHOUETTE_ID = :MODEL_SILHOUETTE_ID", $bind);

        if ($values['form_action'] != Pelican_Db::DATABASE_DELETE) {
            $multiAngles = [];
            foreach ($values as $key => $value) {
                if (strpos($key, self::MULTI_NAME) !== false && strpos($key,
                        '__CPT__') === false) {
                    $multiAngles[trim(str_replace(self::MULTI_NAME, ' ', $key))]
                        = $value;
                }
            }
            if (!empty($multiAngles)) {
                for ($pos = 0; $pos < $multiAngles['count_']; $pos++)
                    if ($multiAngles[$pos.'_multi_display'] == 1) {

                        $data                        = array();
                        $data['ANGLE_ORDER']         = $multiAngles[$pos.'_PAGE_ZONE_MULTI_ORDER'];
                        $data['ANGLE']               = isset($multiAngles[$pos.'_ANGLE'])
                                ? $multiAngles[$pos.'_ANGLE'] : 0;
                        $data['CODE']                = $multiAngles[$pos.'_CODE'];
                        $data['MODEL_SILHOUETTE_ID'] = $modelSilhouetteId;
                        Pelican_Db::$values          = $data;
                        $connection->updateTable(Pelican_Db::DATABASE_INSERT,
                            $tableSilhouetteAngle);
                    }
            }
        }

        Pelican_Db::$values = $values;
    }

    protected function setEditModel()
    {
        $this->aBind[':ID'] = (int) $this->id;
        $groupingSilhouette = <<<SQL
                SELECT
                    *
                FROM
                    #pref#_{$this->form_name}
                WHERE
                     ID = :ID
SQL;
        $this->editModel = $groupingSilhouette;
    }

}

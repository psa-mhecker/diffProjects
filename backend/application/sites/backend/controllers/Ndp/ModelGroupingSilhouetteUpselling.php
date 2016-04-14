<?php
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';

class Ndp_ModelGroupingSilhouetteUpselling_Controller extends Ndp_Controller
{

    protected $multiLangue  = true;
    protected $form_name    = "ws_gdg_model_silhouette_upselling";
    protected $field_id     = "ID";
    protected $listQuery;
    protected $wsGammeState = false;
    protected $defaultOrder = "BASE_PRICE";
    protected $allModelOff  = false;

    protected $decacheBackOrchestra = array(
        'strategy' => array(
            array(
                'locale',
                'siteId',
                'lcdv6',
            )
        ),
    );

    const ENABLED  = 1;
    const DISABLED = 0;
    const WS_GAMME = 14;

    public function indexAction()
    {
        $this->id = $this->getParam('MODELE_ID');
        $this->listAction();
    }

    protected function setListModel()
    {
        parent::listAction();
        $connection = Pelican_Db::getInstance();
        $bind[':MODELE_ID'] = $this->id;
        $query = 'SELECT *, FINISHING_REFERENCE as FINISHING_REFERENCE_BACK
                    FROM
                    #pref#_'.$this->form_name.'
                    WHERE
                    MODEL_SILHOUETTE_ID = :MODELE_ID';
        $params = $this->getParams();

        if (!empty($params['order_Upselling'])) {
            $this->defaultOrder = $params['order_Upselling'];
        }
        $query .= ' ORDER BY '.$this->defaultOrder;
        $models = $connection->queryTab($query, $bind);
        foreach ($models as $keyModel => $model) {
            $models[$keyModel]['parentSegmentUpselling'] = $this->getStateOfUpsellingSegmentation($model['VEHICULE_USE']);
        }
        
        $this->listModel = $models;
    }

    /**
     *
     * @return array
     */
    protected function getListModelForCombo()
    {
        $retour = [];
        foreach ($this->listModel as $key => $model) {
            $retour[$key] = ['id' => $model['FINISHING_CODE'], 'lib' => $model['FINISHING_LABEL']];
        }

        return $retour;
    }

    protected function updateModelTable()
    {
        $container = $this->getContainer();

        $engine = $container->get('configuration_engine_select');
        $finitions = $engine->getModelByLCDV6($this->getParam('LCDV6'));
        if ($this->wsGammeState) {
            $finitions = $this->buildReferenceFinition($container, $finitions);
        }
        // si la montée en gamme est désactivée pour tous les modèles
        $this->allModelOff = $this->getStateOfUpsellingInAllModels();
        $nouvellesFinitions = [];
        if (!empty($finitions)) {
            foreach ($finitions as $finition) {
                //Si la montée est en gamme est désactivée dans la segmentation de la finition
                $superStateSegment = $this->getStateOfUpsellingSegmentation($finition['VEHICULE_USE']);
                $states = [$this->allModelOff, $superStateSegment];
                if (in_array(false, $states)) {
                    $finition['UPSELLING'] = false;
                }
                $nouvellesFinitions[] = $this->addOrUpdateModel($finition);
            }
        }

        $this->deleteOldModel($nouvellesFinitions);
    }

    /**
     *
     * @param Container $container
     * @param array $finitions
     *
     * @return array
     */
    protected function buildReferenceFinition($container, $finitions = [])
    {
        $gamme = $container->get('ws_gamme');
        $params = [
            'input' => [
                'Site' => Pelican::$config['CODE_LANGUE_CLIENT'][$_SESSION[APP]['LANGUE_CODE']],
                'Country' => $_SESSION[APP]['LANGUE_CODE'],
                'Culture' => strtolower($_SESSION[APP]['LANGUE_CODE']),
                'Model' => substr($this->getParam('LCDV6'), 0, 4)
                ]
            ];
        $finitionGamme = $gamme->getUpsellingAndReference($params);
        foreach ($finitionGamme as $keyGamme => $valueGamme) {
            foreach ($valueGamme['items'] as $keyItem => $valueItem) {
                if (isset($finitions[$valueItem['code']])) {
                    $finitions[$valueItem['code']]['UPSELLING'] = $valueItem['isUpselling'];
                    $finitions[$valueItem['code']]['REFERENCE'] = $valueItem['keyFeaturesVersionReference'];
                }
            }

        }
        foreach ($finitions as $keyFinition => $valueFinition) {
            if ($valueFinition['REFERENCE'] != '') {
                foreach ($finitions as $keySubFinition => $valueSubFinition) {
                    $oldReference = $valueFinition['REFERENCE'];
                    $finitions[$keyFinition]['REFERENCE'] = NULL;
                    if ($oldReference == $valueSubFinition['FINISHING_LABEL']) {
                        $finitions[$keyFinition]['FINISHING_REFERENCE'] = $valueSubFinition['FINISHING_CODE'];
                        $finitions[$keyFinition]['REFERENCE'] = $oldReference;
                        break;
                    }
                }
            }
        }

        return $finitions;
    }

    /**
     *
     * @param array $finition
     *
     * @return string
     */
    protected function addOrUpdateModel($finition)
    {
        $connection = Pelican_Db::getInstance();
        $bind[":FINISHING_CODE"]                  = $finition['FINISHING_CODE'];
        $bind[":FINISHING_LABEL"]                 = $finition['FINISHING_LABEL'];
        $bind[":FINISHING_REFERENCE"]             = $finition['FINISHING_REFERENCE'];
        $bind[":VEHICULE_USE"]                    = $finition['VEHICULE_USE'];
        $bind[":BASE_PRICE"]                      = $finition['BASE_PRICE'];
        $bind[":LCDV16"]                          = $finition['LCDV16'];
        $bind[":UPSELLING"]                       = $finition['UPSELLING'];
        $bind[":SITE_ID"]                         = $_SESSION[APP]['SITE_ID'];
        $bind[":LANGUE_ID"]                       = $_SESSION[APP]['LANGUE_ID'];
        $bind[":MODEL_SILHOUETTE_ID"]             = $this->getParam('MODELE_ID');

        $sql = "INSERT INTO #pref#_{$this->form_name} (
                        FINISHING_CODE,
                        FINISHING_LABEL,
                        MODEL_SILHOUETTE_ID,
                        BASE_PRICE,
                        VEHICULE_USE,
                        LCDV16,
                        SITE_ID,
                        LANGUE_ID
                        ";
        if (!empty($bind[":UPSELLING"]) || $this->wsGammeState) {
            $sql .= ", UPSELLING ";
        }
        if (!empty($bind[":FINISHING_REFERENCE"])) {
            $sql .= ", FINISHING_REFERENCE ";
        }
        $sql .= "       )
                        VALUES(
                        ':FINISHING_CODE',
                        ':FINISHING_LABEL',
                        :MODEL_SILHOUETTE_ID,
                        ':BASE_PRICE',
                        ':VEHICULE_USE',
                        ':LCDV16',
                        ':SITE_ID',
                        ':LANGUE_ID'";
        if (!empty($bind[":UPSELLING"]) || $this->wsGammeState) {
            $sql .= ", :UPSELLING ";
        }
        if (!empty($bind[":FINISHING_REFERENCE"])) {
            $sql .= ", ':FINISHING_REFERENCE' ";
        }
        $sql .= "        ) ON DUPLICATE KEY UPDATE FINISHING_CODE = ':FINISHING_CODE' , VEHICULE_USE = ':VEHICULE_USE', FINISHING_LABEL = ':FINISHING_LABEL', BASE_PRICE = ':BASE_PRICE'";
        if (!empty($bind[":UPSELLING"]) || $this->wsGammeState) {
            $sql .= ", UPSELLING = ':UPSELLING'";
        }
        if (!empty($bind[":FINISHING_REFERENCE"])) {
            $sql .= ", FINISHING_REFERENCE = ':FINISHING_REFERENCE'";
        }
        $connection->query($sql, $bind);

        return $finition['FINISHING_CODE'];
    }

    /**
     * @param string $code
     * @return boolean
     */
    public function getStateOfUpsellingSegmentation($code)
    {
        $connection = Pelican_Db::getInstance();
        $bind = [
          ':SITE_ID' =>  $_SESSION[APP]['SITE_ID'],
          ':LANGUE_ID' =>  $_SESSION[APP]['LANGUE_ID'],
          ':CODE' => $code
        ];
        $sql = "SELECT ENABLE_UPSELLING FROM #pref#_segmentation_finition_site WHERE CODE = ':CODE' AND SITE_ID = :SITE_ID AND LANGUE_ID = :LANGUE_ID";
        $state = $connection->queryRow($sql, $bind)['ENABLE_UPSELLING'];

        return $state;
    }

    /**
     *
     * @return boolean
     */
    public function getStateOfUpsellingInAllModels()
    {
        $connection = Pelican_Db::getInstance();
        $bind = [
          ':SITE_ID' =>  $_SESSION[APP]['SITE_ID'],
          ':LANGUE_ID' =>  $_SESSION[APP]['LANGUE_ID']
        ];
        $sql = "SELECT UPSELLING FROM #pref#_model_config WHERE SITE_ID = :SITE_ID AND LANGUE_ID = :LANGUE_ID";
        $state = $connection->queryRow($sql, $bind)['UPSELLING'];

        return $state;
    }


    /**
     *
     * @param array $finitions
     */
    protected function deleteOldModel($finitions)
    {
        $connection = Pelican_Db::getInstance();
        $sql = "DELETE FROM #pref#_{$this->form_name}";
        if (!empty($finitions)) {
            $sql .= ' WHERE MODEL_SILHOUETTE_ID = '.$this->getParam('MODELE_ID').' AND FINISHING_CODE NOT IN ( "'.implode('","', $finitions).'" ) ';
        }

        $connection->query($sql, array());
    }

    /** affichage des resultats
     *
     */
    public function listAction()
    {
        $this->wsGammeState = self::getWsState(self::WS_GAMME);
        $this->updateModelTable();
        $table = Pelican_Factory::getInstance('List', 'Upselling', '', 0, 0, 0, 'liste');
        $table->setValues($this->getListModel(), 'ID');
        $table->addColumn(t('NDP_FINISHING_CODE'), 'FINISHING_CODE', '20', 'left', '', 'tblheader', 'FINISHING_CODE');
        $table->addColumn(t('NDP_FINISHING_LABEL'), 'FINISHING_LABEL', '8', 'left', '', 'tblheader', 'FINISHING_LABEL');
        $table->addInput('UPSELLING', 'checkbox', array("_javascript_" => "", "_value_field_" => "UPSELLING", "" => "", "param" => ""), "", "", "tblheader", 0, 1, 1, t('NDP_ENABLE_UPSELLING'));
        $attr = ['isResultsArray' => 1, 'src' => $this->getListModelForCombo(), 'selected' => 'FINISHING_REFERENCE'];
        $table->addCombo(t('NDP_FINISHING_REFERENCE'), 'FINISHING_REFERENCE', '45', 'center', '', 'tblheader', 'FINISHING_REFERENCE', $attr);
        $table->addInput('FINISHING_REFERENCE_BACK', 'hidden', array("_javascript_" => "", "_value_field_" => "FINISHING_REFERENCE_BACK", "" => "", "param" => ""), "", "", "tblheader", 0, 1, 1);
        $js = $this->getJsToDisableCheckbox();

        $this->setResponse($table->getTable().$js);
    }

    /**
     *
     * @return string
     */
    protected function getJsToDisableCheckbox()
    {
        $models = $this->listModel;
        if ($this->wsGammeState) {
            $LCDV16s = [];
            foreach ($models as $model) {
                $LCDV16s[] = $model['LCDV16'];
            }
            $container = $this->getContainer();
            $gamme = $container->get('range_manager');
        }
        $js = "<script type='text/javascript'>
                   var models = ".json_encode($models).";
                   var oldKey = undefined;
                   $( document ).ready(function() {
                   var compteur = 0;
                   $.each(models, function() {
                            for (var key in this) {
                                if( key == 'ID') { ";
        $js .= $this->getJsForHandlingUpsellingFormWhenWsGammeIsEnable();
        $js .= $this->getJsToDisableSelectWhenUpsellingIsDisable();
        $js .= $this->getJsForOnChangeFunction($models);

        $js .= "   });
                   </script>
               ";

        return $js;
    }

    /**
     *
     * @return string
     */
    public function getJsForHandlingUpsellingFormWhenWsGammeIsEnable()
    {
        $js = "var idFinition = oldKey; "
            . "if(typeof idFinition == 'undefined') {idFinition = this['FINISHING_CODE'];}"
            . "oldKey = this['FINISHING_CODE'];";
        if ($this->wsGammeState) {
            $js .= ""
                . " if($('input[name=\"Upselling['+this[key]+'][UPSELLING]\"]').prop('checked') == false) {"
                . "     var optionNull = $('<option></option>').attr('value', '').text(' - ').attr('selected', 'selected'); "
                . "     $('select[name=\"Upselling['+this[key]+'][FINISHING_REFERENCE]\"]').empty().append(optionNull); "
                . "     $('select[name=\"Upselling['+this[key]+'][FINISHING_REFERENCE]\"]').prop('disabled', true);"
                . " } else if($('select[name=\"Upselling['+this[key]+'][FINISHING_REFERENCE]\"]').val() != '') { "
                . "     $('select[name=\"Upselling['+this[key]+'][FINISHING_REFERENCE]\"]').prop('disabled', true);"
                . " } else {";
            $js .= $this->getJsForDefaultFinition();
            $js .= "     $('select[name=\"Upselling['+this[key]+'][FINISHING_REFERENCE]\"]').prop('disabled', true);"
                . "      $('input[name=\"Upselling['+this[key]+'][FINISHING_REFERENCE_BACK]\"]').val(idFinition);"
                . " }"
                . " $('input[name=\"Upselling['+this[key]+'][UPSELLING]\"]').prop('disabled', true); compteur++; ";
        }
        if (!$this->wsGammeState || !$this->allModelOff) {
            $js .= $this->getJsForDefaultFinition();
        }

        return $js;
    }

    public function getJsForDefaultFinition()
    {
        $js = " 
                    var isSelected = false;
                    var finitionActuelle = $('select[name=\"Upselling['+this[key]+'][FINISHING_REFERENCE]\"] option:selected').val();
                    if( finitionActuelle != '') {
                        if (finitionActuelle == idFinition) {
                            isSelected = 'selected';
                        }
                    } else {
                        isSelected = 'selected';
                    }
                    var option = $('<option></option>').attr('value', ''+idFinition+'').text('".t('BY_DEFAULT')."').attr('selected', isSelected);
                    $('select[name=\"Upselling['+this[key]+'][FINISHING_REFERENCE]\"]').append(option); 
                    $('select[name=\"Upselling['+this[key]+'][FINISHING_REFERENCE]\"] option:nth-child(1)').remove();
                    if (this['parentSegmentUpselling'] != 1) {
                        $('select[name=\"Upselling['+this[key]+'][FINISHING_REFERENCE]\"]').prop('disabled', true);
                        $('input[name=\"Upselling['+this[key]+'][UPSELLING]\"]').prop('disabled', true);
                    }";

        return $js;
    }

    /**
     *
     * @return string
     */
    public function getJsToDisableSelectWhenUpsellingIsDisable()
    {
        $js = "
                                 if(!$('input[name=\"Upselling['+this[key]+'][UPSELLING]\"]').is(':checked')) {
                                     $('select[name=\"Upselling['+this[key]+'][FINISHING_REFERENCE]\"]').prop('disabled', true);
                                 }
                                }
                            }
                        });
                    ";

        return $js;
    }

    /**
     * @param array $models
     *
     * @return string
     */
    public function getJsForOnChangeFunction($models = [])
    {
        $js = '';
        foreach ($models as $keyModel => $valueModel) {
            $js .= "
                $('input[name=\"Upselling[".$valueModel['ID']."][UPSELLING]\"]').change(function() {
                    if ($('input[name=\"Upselling[".$valueModel['ID']."][UPSELLING]\"]').is(':checked')) {
                        $('select[name=\"Upselling[".$valueModel['ID']."][FINISHING_REFERENCE]\"]').prop('disabled', false);
                     } else {
                         $('select[name=\"Upselling[".$valueModel['ID']."][FINISHING_REFERENCE]\"]').prop('disabled', true);
                     }
                 });
                ";
        }

        return $js;
    }
    /*
     * Enregistrement en BDD
     *
     */
    public function saveAction()
    {
        $connection = Pelican_Db::getInstance();
        $params = $this->getParams();
        if (Pelican_Db::$values['form_name'] != $this->form_name && isset(Pelican_Db::$values['Upselling']) && is_array(Pelican_Db::$values['Upselling'])) {
            foreach (Pelican_Db::$values['Upselling'] as $upsellingSetup_id => $upsellingSetup) {
                $setReference = true;
                if (!$upsellingSetup['UPSELLING']) {
                    $upsellingSetup['FINISHING_REFERENCE'] = $upsellingSetup['FINISHING_REFERENCE_BACK'];
                }
                if (strtoupper($upsellingSetup['FINISHING_REFERENCE']) == 'NULL' || empty($upsellingSetup['FINISHING_REFERENCE'])) {
                    $setReference = false;
                }
                $bind = [
                    ':ID' => $upsellingSetup_id,
                    ':MODEL_SILHOUETTE_ID' => $params['MODELE_ID'],
                    ':FINISHING_REFERENCE' => $upsellingSetup['FINISHING_REFERENCE'],
                    ':UPSELLING' => $upsellingSetup['UPSELLING']
                ];
                $query = 'UPDATE #pref#_'.$this->form_name.' SET '.
                    ' UPSELLING = :UPSELLING'.
                    ', FINISHING_REFERENCE = \':FINISHING_REFERENCE\'';
                if (!$setReference) {
                    $query = 'UPDATE #pref#_'.$this->form_name.' SET '.
                    ' UPSELLING = :UPSELLING'.
                    ', FINISHING_REFERENCE = NULL';
                }
                $query .= ' WHERE ID = :ID';

                $connection->query($query, $bind);
            }
        }
    }
}

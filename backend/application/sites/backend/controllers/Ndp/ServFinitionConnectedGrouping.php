<?php
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';

class Ndp_ServFinitionConnectedGrouping_Controller extends Ndp_Controller
{
    protected $multiLangue         = true;
    protected $form_name           = "services_connect_finition_grouping";
    protected $listQuery;
    protected $models              = [];
    protected $field_id            = "ID";
    protected $lcdv4               = "";
    protected $idFinitionConnected = "";
    protected $services            = [];

    const SERIE                     = 1;
    const OPTION                    = 2;
    const NON_DISPO                 = 3;
    const UNDEFINED_ID              = -2;
    const SIZE_ID_GROUPING_FINITION = 8;
    const LCDV4_DATA                = 0;
    const ID_DATA                   = 1;

    public function indexAction()
    {
        $this->lcdv4               = $this->getParam('Data')[self::LCDV4_DATA];
        $this->idFinitionConnected = $this->getParam('Data')[self::ID_DATA];

        $this->listAction();
    }

    protected function setListModel()
    {
        $connection          = Pelican_Db::getInstance();
        $container           = $this->getContainer();
        $engineConfiguration = $container->get('configuration_engine_select');
        $this->models        = $engineConfiguration->getVersionsCriterionByModel($this->lcdv4);
        $datas               = [];
        $this->services      = $this->getServicesConnected();
        foreach ($this->services as $service) {
            $datas[$service['id']]                  = [];
            $datas[$service['id']]['SERVICE_LABEL'] = $service['lib'];
            $datas[$service['id']]['SERVICE_ID']    = $service['id'];
            $datas[$service['id']]['ID']            = $service['id'];
            foreach ($this->models as $model) {
                $datas[$service['id']][$model['id']] = "";
            }
        }
        if ($this->idFinitionConnected != self::UNDEFINED_ID) {
            $bind                         = [];
            $bind[':CONNECT_FINITION_ID'] = $this->idFinitionConnected;
            $query                        = 'SELECT *
                        FROM
                        #pref#_'.$this->form_name.'
                        WHERE
                        CONNECT_FINITION_ID = :CONNECT_FINITION_ID';
            $results                      = $connection->queryTab($query, $bind);
            $toDelete                     = [];
            foreach ($results as $keyResult => $result) {
                $oldDelete  = $toDelete;
                $toDelete[] = $result['FINITION_GROUPING_ID'];
                if (isset($datas[$result['CONNECTED_SERVICE_ID']][$result['FINITION_GROUPING_ID']])) {
                    $result['OPTIONS'] = ($result['OPTIONS']) ? $result['OPTIONS'] : '';
                    $datas[$result['CONNECTED_SERVICE_ID']][$result['FINITION_GROUPING_ID']] = $result['OPTIONS'];
                    $toDelete = $oldDelete;
                }
            }
            if (!empty($toDelete)) {
                $this->deleteOldModel($toDelete);
            }
        }

        $this->listModel = $datas;
    }

    /**
     *
     * @param array $toDelete
     */
    protected function deleteOldModel($toDelete)
    {
        $connection = Pelican_Db::getInstance();
        $sql        = "DELETE FROM #pref#_{$this->form_name}";
        if (!empty($toDelete)) {
            $sql   .= ' WHERE CONNECT_FINITION_ID = "'.$this->idFinitionConnected.'" AND FINITION_GROUPING_ID IN ( "'.implode('","', $toDelete).'" ) ';
        }

        $connection->query($sql, array());
    }

    /**
     *
     * @return array
     */
    protected function getListModelForCombo()
    {
        $retour    = [];
        $retour[0] = ['id' => '', 'lib' => '-> '.t('CHOISISSEZ')];
        $retour[1] = ['id' => self::SERIE, 'lib' => t('NDP_SERIE')];
        $retour[2] = ['id' => self::OPTION, 'lib' => t('NDP_OPTION')];
        $retour[3] = ['id' => self::NON_DISPO, 'lib' => t('NDP_NON_DISPO')];

        return $retour;
    }

    /**
     *
     * @return array
     */
    public function getServicesConnected()
    {
        $connection = Pelican_Db::getInstance();
        $bind       = [
            ':SITE_ID' => $_SESSION[APP]['SITE_ID'],
            ':LANGUE_ID' => $_SESSION[APP]['LANGUE_ID']
        ];
        $sql        = "
            SELECT
                ID as id,
                LABEL as lib
            FROM
                #pref#_services_connect
            WHERE
                SITE_ID = :SITE_ID
            AND LANGUE_ID = :LANGUE_ID
            ORDER BY LABEL
          ";

        return $connection->queryTab($sql, $bind);
    }

    /** affichage des resultats
     *
     */
    public function listAction()
    {
        $table = Pelican_Factory::getInstance('List', 'FinitionConnected', '',
                0, 0, 0, 'liste');
        $table->setValues($this->getListModel(), 'ID');
        $table->addColumn(t('NDP_REG_FINITION_SERVICE'), 'SERVICE_LABEL', '20', 'left', '', 'tblheader', '');
        foreach ($this->models as $key => $value) {
            $attr = ['isResultsArray' => 1, 'src' => $this->getListModelForCombo(), 'selected' => $value['id']];
            $table->addCombo($value['label'], $value['id'], '45', 'center', '', 'tblheader', '', $attr);
        }

        $this->setResponse($table->getTable().$this->createJs());
    }

    /**
     *
     * @return string
     */
    public function createJs()
    {
        $js = "$(document).ready(function() {
                var services = ".json_encode($this->services).";
                var models = ".json_encode($this->models).";
                $( document ).ready(function() {
                    $.each(services, function() {
                        for (var key in this) {
                            if( key == 'id') {
                            var idService = this[key];
                             $.each(models, function() {
                                for (var keyModel in this) {
                                    $('select[name=\"FinitionConnected['+idService+']['+this[keyModel]+']\"] option:nth-child(1)').remove();
                                }
                              });
                            }
                        }
                    });
                });
               });";
        $js = Pelican_Html::script(array(type => 'text/javascript'), $js);

        return $js;
    }
    /*
     * Enregistrement en BDD
     *
     */

    public function saveAction()
    {
        $connection        = Pelican_Db::getInstance();
        $params            = $this->getParams();
        $connectFinitionId = Pelican_Db::$values['ID'];
        if ($connectFinitionId == self::UNDEFINED_ID) {
            $bind  = [
                ':SITE_ID' => Pelican_Db::$values['SITE_ID'],
                ':LANGUE_ID' => Pelican_Db::$values['LANGUE_ID'],
                ':MODELE' => Pelican_Db::$values['MODELE']
            ];
            $query = 'SELECT ID from #pref#_services_connect_finition  '.
                ' WHERE MODELE = ":MODELE" AND SITE_ID = :SITE_ID AND LANGUE_ID = :LANGUE_ID';

            $connectFinitionId = $connection->queryRow($query, $bind)['ID'];
        }
        if ($connectFinitionId != self::UNDEFINED_ID && Pelican_Db::$values['form_name']
            != $this->form_name && isset(Pelican_Db::$values['FinitionConnected']) && is_array(Pelican_Db::$values['FinitionConnected'])) {
            $bind = [
                ':LCDV4' => $params['LCDV4'],
                ':CONNECT_FINITION_ID' => $connectFinitionId,
                ':LANGUE_ID' => $_SESSION[APP]['LANGUE_ID'],
                ':SITE_ID' => $_SESSION[APP]['SITE_ID']
            ];
            foreach (Pelican_Db::$values['FinitionConnected'] as $keyFinitionConnected => $finitionConnected) {
                $bind[':CONNECTED_SERVICE_ID'] = $keyFinitionConnected;
                foreach ($finitionConnected as $keyVersion => $version) {
                    if (strlen($keyVersion) == self::SIZE_ID_GROUPING_FINITION) {
                        $bind[':FINITION_GROUPING_ID'] = $keyVersion;
                        $bind[':OPTIONS']              = $version;

                        $query = 'REPLACE INTO #pref#_'.$this->form_name.' (LCDV4, OPTIONS, FINITION_GROUPING_ID, CONNECT_FINITION_ID, CONNECTED_SERVICE_ID, LANGUE_ID, SITE_ID) '
                            .'VALUES (":LCDV4", ":OPTIONS", ":FINITION_GROUPING_ID", ":CONNECT_FINITION_ID", ":CONNECTED_SERVICE_ID", ":LANGUE_ID", ":SITE_ID") ';

                        $connection->query($query, $bind);
                    }
                }
            }
        }
    }
}

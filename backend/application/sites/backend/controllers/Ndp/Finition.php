<?php

require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';
require_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Webservice.php';
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp/FinitionCouleurCentral.php';
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp/SegmFinition.php';

class Ndp_Finition_Controller extends Ndp_Controller
{
    protected $administration = true;
    protected $form_name = "finishing_site";
    protected $field_id = "SITE_ID";
    protected $field_order = "f.ID";
    protected $multiLangue = true;
    protected $filterSegment = [];

    const SEGMENT_ORDER = "SEGMENTATION";
    const FIELD_COLOR   = "COLOR_ID";

    /**
     *
     * @param boolean $hexColor
     *
     * @return array
     */
    protected function getListColorForCombo($hexColor = false)
    {
        $sql = 'SELECT ID as id, LABEL as lib FROM #pref#_finishing_color ORDER BY LABEL';
        if ($hexColor) {
            $sql = 'SELECT COLOR_CODE as color, ID as id FROM #pref#_finishing_color ORDER BY LABEL';
        }
        $con = Pelican_Db::getInstance();
        $result = $con->queryTab($sql, []);

        return $result;
    }

    /**
     *
     * @param boolean $urlBadge
     *
     * @return array
     */
    protected function getListBadgeForCombo($urlBadge = false)
    {
        $sql = 'SELECT ID as id, LABEL as lib FROM #pref#_finishing_badge ORDER BY LABEL';
        if ($urlBadge) {
            $sql = 'SELECT ID as id, BADGE_URL FROM #pref#_finishing_badge ORDER BY LABEL';
        }
        $con = Pelican_Db::getInstance();
        $result = $con->queryTab($sql, []);

        return $result;
    }

    protected function updateModelTable()
    {
        $container = $this->getContainer();
        $engine = $container->get('configuration_engine_select');
        $models = $engine->getFinitionCode();
        $finitions = [];
        if (!empty($models)) {
            foreach ($models as $model) {
                $finitions[] = $this->addOrUpdateModel($model);
            }
        }

        $this->deleteOldModel($finitions);
    }

    /**
     *
     * @param array $model
     *
     * @return string
     */
    protected function addOrUpdateModel($model)
    {
        $connection = Pelican_Db::getInstance();

        $bind[":CODE"]                            = $model['CODE'];
        $bind[":FINITION"]                        = $model['FINITION'];
        $bind[":CUSTOMER_TYPE"]                   = $model['CUSTOMER_TYPE'];
        $criteres = [];
        foreach ($model['VERSIONS_CRITERION'] as $critere) {
            $criteres[] = $critere->id;
        }
        $criteres = implode("#", $criteres);
        $bind[':LANGUE_ID']                       = $_SESSION[APP]['LANGUE_ID'];
        $bind[':SITE_ID']                         = $_SESSION[APP]['SITE_ID'];

        $sql = "INSERT INTO #pref#_{$this->form_name} (
                        CODE,
                        FINITION,
                        CUSTOMER_TYPE,
                        VERSIONS_CRITERION,
                        LANGUE_ID,
                        SITE_ID
                        )
                        VALUES(
                        ':CODE',
                        ':FINITION',
                        ':CUSTOMER_TYPE',
                      '".$criteres."',
                        ':LANGUE_ID',
                        ':SITE_ID'
                        )
                ON DUPLICATE KEY UPDATE CODE = ':CODE' , FINITION = ':FINITION', CUSTOMER_TYPE = ':CUSTOMER_TYPE' , VERSIONS_CRITERION = '".$criteres."' ";

        $connection->query($sql, $bind);

        return $model['CODE'];
    }

    protected function deleteOldModel($finitions)
    {
        $connection = Pelican_Db::getInstance();
        $sql = "DELETE FROM #pref#_{$this->form_name}";
        if (!empty($finitions)) {
            $sql .= ' WHERE CODE NOT IN ( "'.implode('","', $finitions).'" ) ';
        }

        $connection->query($sql, array());
    }

    /**
     *
     */
    public function listAction()
    {
        $this->id = $_SESSION[APP]['SITE_ID'];
        $this->updateModelTable();
        $this->_initBack();

        $this->_forward('edit');
    }

    public function editAction()
    {
        $this->id = $_SESSION[APP]['SITE_ID'];
        parent::editAction();
         //------------ Begin startStandardForm ----------
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $form = $this->oForm->bDirectOutput = false;
        $form .= $this->oForm->open(Pelican::$config['DB_PATH']);
        $form .= $this->beginForm($this->oForm);
        $form .= $this->getHiddenForForm();
        //------------ End startStandardForm ----------
        $models = $this->getListModel();
        $table = Pelican_Factory::getInstance('List', 'Finition', '', 0, 0, 0, 'liste');
        $table->setFilterField('SEGMENT', t('NDP_SEGMENTATIONS_OF_FINITION'), '', $this->getSqlForSegmentFilter());
        $table->setFilterField('FINITION', t('NDP_FINISHING_LABEL'), '');
        $table->getFilter(2);
        $table->setValues($models, 'ID');
        $table->addColumn(t('ID'), 'ID', '20', 'left', '', 'tblheader', 'ID');
        $table->addColumn(t('NDP_CODE'), 'CODE', '20', 'left', 'strict', 'tblheader', 'CODE');
        $table->addColumn(t('NDP_FINISHING_LABEL'), 'FINITION', '8', 'left', '', 'tblheader', 'FINITION');
        $table->addColumn(t('NDP_SEGMENTATION_OF_FINITION'), 'SEGMENTATION', '8', 'left', '', 'tblheader', 'SEGMENTATION');
        $attr = ['isResultsArray' => 1, 'src' => $this->getListColorForCombo(), 'selected' => self::FIELD_COLOR, 'empty' => false];
        $table->addCombo(t('COLOR'), self::FIELD_COLOR, '45', 'center', '', 'tblheader', self::FIELD_COLOR, $attr);
        $attr = ['isResultsArray' => 1, 'src' => $this->getListBadgeForCombo(), 'selected' => 'BADGE_ID'];
        $table->addCombo(t('BADGE'), 'BADGE_ID', '45', 'center', '', 'tblheader', 'BADGE_ID', $attr);
        $table->addColumn('', 'BADGE_IMG', '8', 'left', '', 'tblheader');

        $form .= $this->addJsForColorPicker();
        $form .= $this->addJsForBadges();
        $form .= $table->getTable();
        $form .= $this->getFilterForm();
        //------------ Begin stopStandardForm ----------
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();
        $form .= self::getJsDisablingEnterButtonForForm();

        //------------ End stopStandardForm ----------

        $this->setResponse($form);
    }

    /**
     *
     * @return string
     */
    private function getJsDisablingEnterButtonForForm()
    {
        $form = '<script type="text/javascript">'
                .'$("#fForm").keypress(function(e){
                        if (e.which == 13) {
                           return false;
                       }
                    });'
                .'</script>';

        return $form;
    }

    /**
     *
     * @return string
     */
    private function getSqlForSegmentFilter()
    {
        $sql = 'SELECT ID, LABEL_LOCAL from #pref#_segmentation_finition_site
                WHERE
                       SITE_ID='.$_SESSION[APP]['SITE_ID'].'
                       AND LANGUE_ID='.$_SESSION[APP]['LANGUE_ID'];

        return $sql;
    }

    private function getHiddenForForm()
    {
        $form = '
                <input type="hidden" id="SITE_ID" name="SITE_ID" value="'.$this->getParam('SITE_ID').'">
                <input type="hidden" id="form_name" name="form_name" value="finishing_site">
                <input type="hidden" id="form_action" name="form_action" value="UPD">
                <input type="hidden" id="form_database" name="form_database">
                <input type="hidden" id="form_retour" name="form_retour" value="/_/Index/child?tid='.$this->getParam('tid').';tc=&amp;view=O_1&amp;toprefresh=1&amp;toprefresh=1">
                <input type="hidden" id="form_preview" name="form_preview">
                <input type="hidden" id="oldAction" name="oldAction">
                <input type="hidden" id="form_button" name="form_button">
                <input type="hidden" id="form_user" name="form_user" value="admin">
                <input type="hidden" id="form_start" name="form_start" value="/_/Index/child?tid='.$this->getParam('tid').'&amp;tc=&amp;view=O_1&amp;toprefresh=1&amp;toprefresh=1">
                <input type="hidden" id="LANGUE_ID" name="LANGUE_ID" value="'.$this->getParam('LANGUE_ID').'">
                <input type="hidden" id="NEW_LANGUE_ID" name="NEW_LANGUE_ID">';

        return $form;
    }
    /**
     *
     * @return string
     */
    private function getFilterForm()
    {
        $form = '
            <form name="filter_form" id="filter_form" method="get" action="/_/Index/child">
                <input name="filter_SEGMENT" id="filter_SEGMENT2" value="" type="hidden">
                <input name="filter_FINITION" id="filter_FINITION2" value="" type="hidden">
                <input name="view" id="view" value="'.$this->getParam('view').'" type="hidden">
                <input name="tid" id="tid" value="'.$this->getParam('tid').'" type="hidden">
                <input name="tc" id="tc" value="'.$this->getParam('tc').'" type="hidden">


            </form>';

        return $form;
    }

    /**
     *
     * @return string
     */
    protected function addJsForColorPicker()
    {
        $colors = $this->getListColorForCombo(true);
        $colorJs = [];
        foreach ($colors as $keyColor => $valueColor) {
            $colorJs[$valueColor['id']] = $valueColor['color'];
        }
        $models = $this->listModel;
        $js = "<script type='text/javascript'>
            var models = ".json_encode($models).";
            var colors = ".json_encode($colorJs).";
            $( document ).ready(function() {

                   $('select[name*=\"COLOR_ID\"]').each(function(idx, elm){
                               var selectColor = $(elm);
                               var valueColor =selectColor.val();
                               if (typeof colors[valueColor] != 'undefined') {
                                selectColor.css('background-color', colors[valueColor]);
                               }
                               selectColor.find('option').each(function() {
                                if (this.value != '') {
                                    this.style.backgroundColor = colors[this.value];
                                }
                                }); // fin each
                    });// fin each

            ";
        foreach ($models as $keyModel => $valueModel) {
            $js .= "  $('select[name*=\"COLOR_ID\"]').change(function() {

                        var selectColor =  $(this);
                        var colorChange = colors[selectColor.val()];
                        if (typeof colorChange != 'undefined') {
                           selectColor.css('background-color', colorChange);
                        } else {
                            selectColor.css('background-color', '#FFF');
                        }
                    }); // fin select
                ";
        }

        $js .= "    }); // fin readey
            </script>
            ";

        return $js;
    }

    /**
     *
     * @return string
     */
    protected function addJsForBadges()
    {
        $badges   = $this->getListBadgeForCombo(true);
        $badgesJs = [];
        foreach ($badges as $keyBadge => $valueBadge) {
            $badgesJs[$valueBadge['id']] = $valueBadge['BADGE_URL'];
        }
        $models = $this->listModel;
        $js     = "<script type='text/javascript'>
                var models = ".json_encode($models).";
                var badges = ".json_encode($badgesJs).";
                $( document ).ready(function() {

                   $('select[name*=\"BADGE_ID\"]').each(function(idx, elm){
                               var selectBadge = $(elm);
                               var idBadge =selectBadge.val();

                               if (idBadge != '') {
                                    var imgTd =selectBadge.closest('td').next('td');
                                    imgTd.html('<img src=\"'+badges[idBadge]+'\" width=\"50px\" >');
                                }

                   });
            ";
        foreach ($models as $keyModel => $valueModel) {
            $js .= "
                $('select[name*=\"BADGE_ID\"]').change(function() {
                    var selectBadge = $(this);
                     var idBadge =selectBadge.val();
                    if (idBadge != '') {
                         selectBadge.closest('td').next('td').html('<img src=\"'+badges[idBadge]+'\" width=\"50px\" >');
                     } else {
                           selectBadge.closest('td').next('td').next().html('');
                     }
                 });
                ";
        }
        $js .= "});
            </script>
            ";

        return $js;
    }

    /**
     *
     * @return array
     */
    public function getListModel()
    {
        $connection = Pelican_Db::getInstance();
        $q = 'SELECT ID,LABEL_LOCAL FROM #pref#_segmentation_finition_site WHERE ID='.Ndp_SegmFinition_Controller::DEFAULT_SEGMENT;
        $defaultSegment = $connection->queryRow($q, []);

        // ordre de trie
        if (!empty($_GET['order_Finition'])) {
            $this->field_order = $_GET['order_Finition'];
        }

        // requete qui permet de faire une recherche sur la table des segementation
        $subquery = "SELECT sub1.LABEL_LOCAL,sub1.ID,
            CONCAT('.*',REPLACE(sub1.MARKETING_CRITERION,'#','.*|.*'),'.*') as SEARCH_BY_CRITERION,
            CONCAT('.*',REPLACE(sub1.CLIENTELE_DESIGN,'#','.*|.*'),'.*') as SEARCH_BY_CUSTOMER_TYPE
            FROM #pref#_segmentation_finition_site sub1
            WHERE
               sub1.SITE_ID=".$_SESSION[APP]['SITE_ID']."
               AND sub1.LANGUE_ID=".$_SESSION[APP]['LANGUE_ID']."
        ";

       // table de requete sur les finitions
        $query = 'SELECT
              f.ID,
              f.CODE,
              f.FINITION,
              COALESCE(f.COLOR_ID,'.Ndp_FinitionCouleurCentral_Controller::DEFAULT_COLOR.')  AS COLOR_ID,
              (SELECT c.LABEL FROM #pref#_finishing_color c WHERE ID = COALESCE(f.COLOR_ID,'.Ndp_FinitionCouleurCentral_Controller::DEFAULT_COLOR.')) AS COLOR_LABEL,
              COALESCE(sf.LABEL_LOCAL,"'.$defaultSegment['LABEL_LOCAL'].'") as SEGMENTATION,
              f.BADGE_ID,
              sf.SEARCH_BY_CUSTOMER_TYPE,
              sf.SEARCH_BY_CRITERION
         FROM psa_finishing_site f LEFT JOIN
          (
            '.$subquery.'
          ) sf ON (
                (f.CUSTOMER_TYPE REGEXP sf.SEARCH_BY_CUSTOMER_TYPE)
                OR (f.VERSIONS_CRITERION REGEXP sf.SEARCH_BY_CRITERION)
                )
         ';

        $where[]            = 'SITE_ID = '.$_SESSION[APP]['SITE_ID'];
        $where[]            = 'f.LANGUE_ID='.$_SESSION[APP]['LANGUE_ID'];

        if (!empty($_GET['filter_FINITION'])) {
            $where[] = "FINITION like '%".str_replace("'", "''",
                    $_GET['filter_FINITION'])."%' ";
        }

        if (!empty($_GET['filter_SEGMENT'])) {
            if ($defaultSegment['ID'] == $_GET['filter_SEGMENT']) {
                $where[] = ' (sf.ID = '.intval($_GET['filter_SEGMENT']).' OR sf.ID IS NULL) ';
            } else {
                $where[] = 'sf.ID = '.intval($_GET['filter_SEGMENT']);
            }
        }

        if (!preg_match('/'.self::SEGMENT_ORDER.'/i', $this->field_order) && preg_match('/'.self::FIELD_COLOR.'/i', $this->field_order)) {
            $this->field_order = str_replace(self::FIELD_COLOR, "COLOR_LABEL", $this->field_order);
        }

        $query .= " WHERE ".implode(" AND ", $where);
        $query .= "  GROUP BY f.id";
        $query .= ' ORDER BY '.$this->field_order;
        $models = $connection->queryTab($query, []);

        $this->listModel = $models;

        return $models;
    }

    /**
     *
     */
    public function saveAction()
    {
        $connection = Pelican_Db::getInstance();
        foreach (Pelican_Db::$values['Finition'] as $id => $values) {
            $bind = [
                    ':ID' => $id,
                    ':'.self::FIELD_COLOR.'' => $values[self::FIELD_COLOR],
                    ':BADGE_ID' => $values['BADGE_ID'],

                ];
            $query = 'UPDATE  #pref#_'.$this->form_name.' SET '.
                    ' '.self::FIELD_COLOR.' = :'.self::FIELD_COLOR.''.
                    ', BADGE_ID = :BADGE_ID'.
                    ' WHERE ID = :ID';

            $connection->query($query, $bind);
        }
    }
}

<?php

require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';


class Ndp_CatPriorisation_Controller extends Ndp_Controller
{
    protected $form_name = "vehicle_category_site_order";
    protected $field_id = "ID";
    protected $defaultOrder = "CATEGORY_ORDER";
    
    const MAX   = '99999';
    const FIRST = '0';

    protected function setListModel()
    {
        $con = Pelican_Db::getInstance();
        $bind[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $bind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $sqlCategoryGammeVehicule = "
                SELECT
                    vc.ID,
                    vc.LABEL,
                    vc.CRITERES_MARKETING,
                    vcs.CATEGORY_ORDER
                FROM
                    #pref#_vehicle_category_site vc
                LEFT JOIN #pref#_{$this->form_name} vcs ON (vcs.ID = vc.ID) 
                WHERE vc.SITE_ID = :SITE_ID
                AND vc.LANGUE_ID = :LANGUE_ID ";
        $sqlCategoryGammeVehicule .= " ORDER BY vcs.CATEGORY_ORDER ASC";
        
        $this->listModel = self::setModelOrder($con->queryTab($sqlCategoryGammeVehicule, $bind));        
    }
    
    /**
     * 
     * @param array $model
     * @return array
     */
    protected function setModelOrder($model) 
    {
        foreach ($model as $keyModel => $oneModel) {
            if (empty($oneModel['CATEGORY_ORDER']) && $oneModel['CATEGORY_ORDER'] != self::FIRST) {
                $model[$keyModel]['CATEGORY_ORDER'] = self::MAX;
            }
        }
        
        return $model;
    }

    protected function getCategories() {
        $categories = [];
        $cats = $this->getListModel();
        usort($cats, function($a, $b) {
                return strcmp($a["CATEGORY_ORDER"], $b["CATEGORY_ORDER"]);
            });
        foreach ($cats as $row) {
            if (!empty($row['CRITERES_MARKETING'])) {
                $categories[$row['ID']] = $row['LABEL'];
            }
        }

        return $categories;
    }

    public function indexAction()
    {
        parent::indexAction();
        if ($_POST['id'] > 0) {
            $this->_forward('edit');
        } elseif ($_POST['form_action'] == 'saveCategorieOrder') {
            $this->saveCategorieOrderAction();
        }
    }

    protected function saveCategorieOrderAction()
    {
        $connection = Pelican_Db::getInstance();
        $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $bind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $bind[':ORDER'] = 0;
        $sql = "REPLACE INTO #pref#_{$this->form_name}
                (CATEGORY_ORDER, SITE_ID, LANGUE_ID, ID) VALUES (:ORDER, :SITE_ID, :LANGUE_ID, :ID) ";
        if (is_array(Pelican_Db::$values['CATEGORY_IDS'])) {
            foreach (Pelican_Db::$values['CATEGORY_IDS'] as $value) {
                $bind[':ID'] = $value;
                $connection->query($sql, $bind);
                $bind[':ORDER']++;
            }
        }
    }


    public function listAction()
    {
        parent::listAction();
        $values = $this->getCategories();

        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $form = $this->oForm->open('/_/Index/child?tid='.$this->tid);
        $form .= $this->oForm->beginFormTable();
        $this->form_action = 'saveCategorieOrder';
        $form .= $this->oForm->createHidden('id');
        $form .= $this->beginForm($this->oForm);

        $selected = array_keys($values);
      
        $params['datas'] = $selected;
        $params['source'] = $values;
        $params['order'] = true;
        $params['readO'] = false;
        $params['form'] = $this->oForm;
        $form .= $this->oForm->createComment(t('NDP_MSG_PRIORISATION_CAT'));

        $form .= $this->oForm->createAssocFromList(
            null, 'CATEGORY_IDS', t('NDP_LABEL_PRIORISATION'), $params['source'], $selected, false, false, $params['readO'], 10, '200%', false, '', $params['order'], $params['max'], false, array(
                'delOnDlbClick' => false,
                'showSource' => false
            )
        );
        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();


        $this->aButton["add"] = "";
        Backoffice_Button_Helper::init($this->aButton);
        $this->setResponse($form);
    }
}

<?php
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Administration/Site/National/Parameters.php';

class Ndp_CarSelectorFilter_Controller extends Ndp_Controller
{
    protected $form_name = "carselectorfilter";
    protected $field_id = "SITE_ID";

    protected function setEditModel()
    {
        $this->editModel = "SELECT *
            from #pref#_carselectorfilter
            WHERE SITE_ID=".$this->id;
    }


    public function listAction()
    {
        parent::listAction();
        $this->id = $_SESSION[APP]['SITE_ID'];
        $this->_forward('edit');
    }

    public function editAction()
    {
        parent::editAction();
        $form = $this->startStandardForm();

        // unités de mesure issue des params generaux
        $unitsOfMeasure = Administration_Site_National_Parameters_Controller::getParametersValuesBySiteId($_SESSION[APP]['SITE_ID']);

        $form .= $this->oForm->createLabel('', $this->formatLabel('NDP_MSG_FILTER_CARSELECTOR'));
        $form .= $this->oForm->showSeparator();
        $form .= $this->oForm->createLabel($this->formatLabel('NDP_FILTER_PRICE'), '');
        $form .= $this->oForm->showSeparator();
        $form .= $this->oForm->createInput('PRICE_GAUGE', t('NDP_GAUGE_STEP').' - '.t('NDP_CASH_PRICE'), 15, 'float', true, $this->values['PRICE_GAUGE'], false, 15, false, "", "text", array(), false, "", array('message' => $unitsOfMeasure['CURRENCY_SYMBOL']));
        $form .= $this->oForm->createInput('PRICE_GAUGE_MONTHLY', t('NDP_GAUGE_STEP').' - '.t('NDP_MONTHLY_PRICE'), 15, 'float', true, $this->values['PRICE_GAUGE_MONTHLY'], false, 15, false, "", "text", array(), false, "", array('message' => $unitsOfMeasure['CURRENCY_SYMBOL']));
        $form .= $this->oForm->createLabel($this->formatLabel('NDP_FILTER_CONSO'), '');
        $form .= $this->oForm->showSeparator();
        $addAfterInput = $unitsOfMeasure['VOLUME_UNIT'].' / '.$unitsOfMeasure['DISTANCE_UNIT'];
        $form .= $this->oForm->createInput('CONSO_GAUGE', t('NDP_GAUGE_STEP'), 15, 'float', true, $this->values['CONSO_GAUGE'], false, 15, false, "", "text", array(), false, "", array('message' => $addAfterInput));
        $form .= $this->oForm->createLabel($this->formatLabel('NDP_FILTER_LENGTH'), '');
        $form .= $this->oForm->showSeparator();
        $form .= $this->oForm->createInput('LENGTH_GAUGE', t('NDP_GAUGE_STEP'), 15, 'float', true, $this->values['LENGTH_GAUGE'], false, 15, false, "", "text", array(), false, "", array('message' => $unitsOfMeasure['DIMENSION_UNIT']));
        // ATTENTION : ne pas supprimer les commentaires, c'est à activer en lot 2
        //$form .= $this->oForm->createLabel($this->formatLabel('NDP_FILTER_WIDTH'), '');
        //$form .= $this->oForm->showSeparator();
        //$form .= $this->oForm->createInput('WIDTH_GAUGE', t('NDP_GAUGE_STEP'), 15, 'float', true, $this->values['WIDTH_GAUGE'], false, 15, false, "", "text", array(), false, "", array('message' => $unitsOfMeasure['DIMENSION_UNIT']));
        $form .= $this->oForm->createLabel($this->formatLabel('NDP_FILTER_HEIGHT'), '');
        $form .= $this->oForm->showSeparator();
        $form .= $this->oForm->createInput('HEIGHT_GAUGE', t('NDP_GAUGE_STEP'), 15, 'float', true, $this->values['HEIGHT_GAUGE'], false, 15, false, "", "text", array(), false, "", array('message' => $unitsOfMeasure['DIMENSION_UNIT']));
        $form .= $this->oForm->createLabel($this->formatLabel('NDP_FILTER_VOLUME'), '');
        $form .= $this->oForm->showSeparator();
        $form .= $this->oForm->createInput('VOLUME_LVL1', t('NDP_FILTER_VOLUME_MAXVALUE').' '.t('NDP_FILTER_VOLUME_LVL0'), 15, 'number', true, $this->values['VOLUME_LVL1'], false, 15, false, "", "text", array(), false, "", array('message' => $unitsOfMeasure['VOLUME_UNIT']));
        $form .= $this->oForm->createInput('VOLUME_LVL2', t('NDP_FILTER_VOLUME_MAXVALUE').' '.t('NDP_FILTER_VOLUME_LVL1'), 15, 'number', true, $this->values['VOLUME_LVL2'], false, 15, false, "", "text", array(), false, "", array('message' => $unitsOfMeasure['VOLUME_UNIT']));
        $form .= $this->oForm->createLabel($this->formatLabel('NDP_FILTER_CLASS'), '');
        $form .= $this->oForm->showSeparator();
        $form .= $this->oForm->createLabel('', $this->formatLabel('NDP_MSG_FILTER_CLASS'));
        $form .= $this->oForm->createInput('CLASS_A_LABEL', 'A', 10, '', true, $this->values['CLASS_A_LABEL'], false, 15);
        $form .= $this->oForm->createInput('CLASS_B_LABEL', 'B', 10, '', true, $this->values['CLASS_B_LABEL'], false, 15);
        $form .= $this->oForm->createInput('CLASS_C_LABEL', 'C', 10, '', true, $this->values['CLASS_C_LABEL'], false, 15);
        $form .= $this->oForm->createInput('CLASS_D_LABEL', 'D', 10, '', true, $this->values['CLASS_D_LABEL'], false, 15);
        $form .= $this->oForm->createInput('CLASS_E_LABEL', 'E', 10, '', true, $this->values['CLASS_E_LABEL'], false, 15);
        $form .= $this->oForm->createInput('CLASS_F_LABEL', 'F', 10, '', true, $this->values['CLASS_F_LABEL'], false, 15);
        $form .= $this->oForm->createInput('CLASS_G_LABEL', 'G', 10, '', true, $this->values['CLASS_G_LABEL'], false, 15);
        $form .= $this->stopStandardForm();
        $this->setResponse($form);

        // hide add button
        $this->aButton["add"] = "";
        Backoffice_Button_Helper::init($this->aButton);
    }

    /*
     * Enregistrement en BDD
     *
     */
    public function saveAction()
    {
        $connection = Pelican_Db::getInstance();

        // on supprime l'ancien s'il existe
        $bind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
        $connection->query("DELETE FROM #pref#_carselectorfilter where SITE_ID = :SITE_ID", $bind);

        // on le (re)cree
        $connection->insertQuery('#pref#_carselectorfilter');

        parent::saveAction();
    }

    /*
     * Retourne la liste des autres filtres disponibles
     *
     * @return array clé/valeur des filtres
     */
    public function getOtherFiltersAvailable()
    {
        $rs = array(
            'NDP_FILTER_PRICE' => t('NDP_FILTER_PRICE'),
            'NDP_FILTER_ENERGY' => t('NDP_FILTER_ENERGY'),
            'NDP_FILTER_GEARBOX_TYPE' => t('NDP_FILTER_GEARBOX_TYPE'),
            'NDP_FILTER_CONSO' => t('NDP_FILTER_CONSO'),
            'NDP_FILTER_CLASS' => t('NDP_FILTER_CLASS'),
            'NDP_FILTER_SEAT_NB' => t('NDP_FILTER_SEAT_NB'),
            'NDP_FILTER_LENGTH' => t('NDP_FILTER_LENGTH'),
        // commentaire à conserver, ce filtre sera activé en lot 2 :    'NDP_FILTER_WIDTH' => t('NDP_FILTER_WIDTH'),
            'NDP_FILTER_HEIGHT' => t('NDP_FILTER_HEIGHT'),
            'NDP_FILTER_VOLUME' => t('NDP_FILTER_VOLUME')
        );

        return $rs;
    }

    /**
     * Mise en forme specifique des labels
     *
     * @param string $labelKey  code constante de langue
     * 
     * @return string label formaté
     */
    public function formatLabel($labelKey)
    {
        return '<span style="font-weight:bold">'.t($labelKey).'</span>';
    }

}

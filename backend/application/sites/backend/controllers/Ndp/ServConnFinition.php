<?php

require_once Pelican::$config['APPLICATION_CONTROLLERS'] . '/Ndp.php';

class Ndp_ServConnFinition_Controller extends Ndp_Controller
{
    protected $multiLangue    = true;
    protected $administration = true;
    protected $form_name      = "services_connect_finition";
    protected $field_id       = "ID";
    protected $defaultOrder   = "ID";

    const CTA_TYPE    = "CTA_FOR_REF";
    const CTA         = "CTA_SERVICE";
    const CONT_COMPAT = "COMPAT";
    const NO          = 0;
    const YES         = 1;
    const FORM_YES    = "COMPAT_1";

    protected function setListModel()
    {
        $connection = Pelican_Db::getInstance();
        $bind = [
            ':SITE_ID' => (int) $_SESSION[APP]['SITE_ID'],
            ':LANGUE_ID' => (int) $_SESSION[APP]['LANGUE_ID']
        ];
        $sql = "
                SELECT
                    scf.ID,
                    CONCAT('(', LCDV4, ')', ' ', MODEL) as MODELE
                FROM
                    #pref#_{$this->form_name} scf, #pref#_model m";
        $sql .= " WHERE
            SITE_ID = :SITE_ID
            AND LANGUE_ID = :LANGUE_ID
            AND scf.MODELE = m.LCDV4 COLLATE utf8_swedish_ci
            ORDER BY {$this->listOrder}";
        $data = $connection->queryTab($sql, $bind);

        $this->listModel = $data;
    }

    protected function setEditModel()
    {
        $this->aBind[':ID'] = (int) $this->id;
        $sql = <<<SQL
                SELECT
                    *
                FROM
                    #pref#_{$this->form_name}
                WHERE
                     ID = :ID
SQL;

        $this->editModel = $sql;
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setValues($this->getListModel(), "ID");
        $table->addColumn(t('ID'), "ID", "20", "center", "", "tblheader", "ID");
        $table->addColumn(t('NDP_MODELE'), "MODELE", "45", "center", "", "tblheader", "MODELE");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "ID"), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "ID", "" => "readO=true"), "center");

        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        $this->multiLangue = false;
        parent::editAction();
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $form  = $this->oForm->open(Pelican::$config['DB_PATH']);
        $form .= $this->beginForm($this->oForm);
        $form .= $this->oForm->beginFormTable();
        $form .= $this->oForm->createHidden('ID', $this->id);
        $form .= $this->createModelForm();
        $form .= $this->createCompatibilityForm();
        $form .= $this->createLegalForm();
        $form .= $this->createCtaForm();
        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();

        $this->setResponse($form);
    }

    /**
     *
     * @return string
     */
    public function createModelForm()
    {
        $connection = Pelican_Db::getInstance();
        $query = $this->getSqlForModels($this->values["MODELE"]);
        $form  = "<tbody><tr><td class='formlib'>".t('NDP_MODELE')." *</td><td class='formval'>";
        $form .= $this->oForm->createComboFromSql($connection, "MODELE", t('NDP_MODELE'), $query, $this->values["MODELE"], true, $this->readO, "1", false, "", true, true);
        $form .= $this->oForm->createButton("BUTTON_OK", "OK");
        $form .= "</td></tr></tbody>";

        return $form;
    }

    /**
     *
     * @param string $lcdv4
     *
     * @return string
     */
    public function getSqlForModels($lcdv4)
    {
        $sql = "SELECT LCDV4 as id, CONCAT('(', LCDV4, ')', ' ', MODEL) FROM #pref#_model WHERE LCDV4 COLLATE utf8_swedish_ci NOT IN (SELECT MODELE FROM #pref#_{$this->form_name} WHERE SITE_ID = ".$_SESSION[APP]["SITE_ID"]." AND LANGUE_ID = ".$_SESSION[APP]["LANGUE_ID"]." AND MODELE != '".$lcdv4."')  ORDER BY MODEL ASC ";

        return $sql;
    }

    /**
     *
     * @return string
     */
    public function createCompatibilityForm()
    {
        $type = self::CONT_COMPAT;
        $targetsAffichage = array(self::YES => t('NDP_YES'), self::NO => t('NDP_NO'));
        $jsContainerAffichage = self::addJsContainerRadio($type);
        self::setDefaultValueTo('COMPATIBILITE', self::YES);
        $form = $this->oForm->createRadioFromList(
            "COMPATIBILITE",
            t('NDP_FULLFILL_COMPATIBILITY'),
            $targetsAffichage,
            $this->values['COMPATIBILITE'],
            true,
            $this->readO,
            'h',
            false,
            $jsContainerAffichage
        );
        $form .= self::addHeadContainer(self::YES, $this->values['COMPATIBILITE'], $type);
        $form .= self::addFootContainer();
        $form .= self::addHeadContainer(self::NO, $this->values['COMPATIBILITE'], $type);
        $selected = "";
        if ($this->values['SERVICES_CONNECTES'] != '') {
            $selected = explode('#', $this->values['SERVICES_CONNECTES']);
        }
        $form .= $this->oForm->createAssocFromSql("", "SERVICES_CONNECTES", t('NDP_CAPABLE_CONNECTED_SERVICE'), $this->getSqlForConnectedServices(), $selected, true, true, $this->readO, 5, 200, false, "", array());
        $form .= self::addFootContainer();
        $form .= $this->addJstoHideOkButton();
        $form .= $this->addJsToHandleOkButton();
        $form .= $this->oForm->createJS($this->getJsToCheckArrayOfOptions());

        return $form;
    }

    /**
     *
     * @return string
     */
    public function getJsToCheckArrayOfOptions()
    {
        $js = "
                var fullFillArray = $('#').val();
                if ($('input[type=radio][name=COMPATIBILITE]:checked').val() == '".self::YES."')  {
                    var toReturn = true;
                    $('#FinitionConnected select').each(function( index ) {
                       if( $(this).val() == '') {
                            alert('".t('NDP_MSG_FULLFILL_OPTION')."');
                            fwFocus($(this));
                            toReturn = false;

                            return false;
                        }
                    });
                    if (!toReturn) {
                        return false;
                    }
                }
               ";

        return $js;
    }
    
    /**
     *
     * @return string
     */
    public function addJsToHandleOkButton()
    {
        $js = "$(document).ready(function() {
                    var okButton = $('#BUTTON_OK');
                    if ($('input[type=radio][name=COMPATIBILITE]:checked').val() == '".self::YES."' && $('#MODELE').val() != '') {
                        loadArrayCompat();
                    }
                    function loadArrayCompat() {
                        var LCDV4 = $('#MODELE').val();
                            if (LCDV4 != '') {
                                var Data = [LCDV4, '".$this->id."'];
                                window.parent.showLoading('div#frame_right_middle', true);
                                $('#".self::FORM_YES." tr td').html('');
                                callAjax({
                                  url: 'Cms_Page_Ajax/generateGroupingCompatibilityArray',
                                  async: false,
                                  type: 'POST',
                                  data: {
                                     Data: Data
                                  },
                                success: function(e) {
                                      if (e) {
                                      var html = '<br>' + e + '<br>';
                                        $('#".self::FORM_YES." tr td').html(html);
                                      }
                                      window.parent.showLoading('div#frame_right_middle', false);
                                    }
                                });
                            } else {
                                alert('".t('NDP_MSG_CHOOSE_MODEL')."');
                            }
                    }
                    okButton.click(function() {
                        loadArrayCompat();
                    });
               });";
        $js = Pelican_Html::script(array(type => 'text/javascript'), $js);

        return $js;
    }

    /**
     *
     * @return string
     */
    public function addJsToHideOkButton()
    {
        $js = "$(document).ready(function() {
                    var okButton = $('#BUTTON_OK');
                    if ($('input[type=radio][name=COMPATIBILITE]:checked').val() == '".self::NO."') {
                        okButton.hide();
                    }
                    $('input[type=radio][name=COMPATIBILITE]').change(function() {
                        if (this.value == '".self::YES."') {
                            okButton.show();
                        }
                        else {
                            okButton.hide();
                        }
                    });
                });";
        $js = Pelican_Html::script(array(type => 'text/javascript'), $js);

        return $js;
    }

    /**
     *
     * @return string
     */
    public function getSqlForConnectedServices()
    {
        $sql = "
            SELECT
                ID as id,
                LABEL as lib
            FROM
                #pref#_services_connect
            WHERE
                SITE_ID = ".$_SESSION[APP]['SITE_ID']."
            AND LANGUE_ID = ".$_SESSION[APP]['LANGUE_ID']."
            ORDER BY LABEL
          ";

        return $sql;
    }

    /**
     *
     * @return string
     */
    public function createLegalForm()
    {
        $form = $this->oForm->createTextArea('MENTIONS_LEGALES', t('NDP_TERMS_CONDITIONS'), false, $this->values['MENTIONS_LEGALES'], 255, $this->readO, 2, 44);

        return $form;
    }

    /**
     *
     *
     * @return string
     */
    public function createCtaForm()
    {
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $this->buildValuesForCta();
        $ctaComposite->setCta($this->oForm, $this->values, false, self::CTA_TYPE);
        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->addTargetAvailable('_popin', t('NDP_POPIN'));
        $ctaRef->hideStyle(true);
        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->addTargetAvailable('_popin', t('NDP_POPIN'));
        $ctaNew->hideStyle(true);
        $ctaComposite->addInputCta($ctaRef);
        $ctaComposite->addInputCta($ctaNew);

        return $ctaComposite->generate();
    }

    public function buildValuesForCta()
    {
        
         $this->values['PAGE_ZONE_CTA_STATUS'] = $this->values[self::CTA];
         switch ($this->values[self::CTA]) {
            case Ndp_Cta::SELECT_CTA:
                $this->values['CTA_ID'] = $this->values['CTA_SERVICE_ID'];
                $this->values['TARGET'] = $this->values['CTA_SERVICE_TARGET'];
                break;
            case Ndp_Cta::NEW_CTA:
                $this->values['TITLE']  = $this->values['CTA_SERVICE_TITLE'];
                $this->values['ACTION'] = $this->values['CTA_SERVICE_ACTION'];
                $this->values['TARGET'] = $this->values['CTA_SERVICE_TARGET'];
                break;
            default:
                //Nothing
                break;
        }
    }

    public function saveAction()
    {
        $connection = Pelican_Db::getInstance();
        $values = Pelican_Db::$values;
        if (self::YES == Pelican_Db::$values['COMPATIBILITE']) {
            unset(Pelican_Db::$values['SERVICES_CONNECTES']);
        }
        if (!empty(Pelican_Db::$values['SERVICES_CONNECTES'])) {
            Pelican_Db::$values['SERVICES_CONNECTES'] = implode('#', Pelican_Db::$values['SERVICES_CONNECTES']);
        }
        $this->saveCta();

        parent::saveAction();
        $params = [];
        $params['LCDV4'] = $values['MODELE'];
        $params['CONNECT_FINITION_ID'] = $this->id;
        
        Pelican_Request::call('_/Ndp_ServFinitionConnectedGrouping/save', $params);
    }

    /**
     * Méthode pour generer la sauvegarde des CTA du référentiel
     */
    public function saveCta()
    {
        switch (Pelican_Db::$values[self::CTA_TYPE]['PAGE_ZONE_CTA_STATUS']) {
            case Ndp_Cta::SELECT_CTA:
                Pelican_Db::$values['CTA_SERVICE_ID'] = Pelican_Db::$values[self::CTA_TYPE]['SELECT_CTA']['CTA_ID'];
                Pelican_Db::$values['CTA_SERVICE_TARGET'] = Pelican_Db::$values[self::CTA_TYPE]['SELECT_CTA']['TARGET'];
                Pelican_Db::$values[self::CTA] = Ndp_Cta::SELECT_CTA;
                break;
            case Ndp_Cta::NEW_CTA:
                Pelican_Db::$values['CTA_SERVICE_ACTION'] = Pelican_Db::$values[self::CTA_TYPE]['NEW_CTA']['ACTION'];
                Pelican_Db::$values['CTA_SERVICE_TITLE']  = Pelican_Db::$values[self::CTA_TYPE]['NEW_CTA']['TITLE'];
                Pelican_Db::$values['CTA_SERVICE_TARGET'] = Pelican_Db::$values[self::CTA_TYPE]['NEW_CTA']['TARGET'];
                Pelican_Db::$values[self::CTA] = Ndp_Cta::NEW_CTA;
                break;
            default:
                //nothing;
                break;
        }
    }
}

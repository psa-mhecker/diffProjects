<?php

require_once Pelican::$config['APPLICATION_CONTROLLERS'] . '/Ndp.php';

/**
 * Class Ndp_AfterSaleServices_Controller
 */
class Ndp_AfterSaleServices_Controller extends Ndp_Controller
{
    const MAX_CAR1 = '44';
    const MAX_CAR2 = '30';
    const FROM = 0;
    const SHOW_DETAIL = 0;
    const OTHER = 1;
    const ONE_COLUMN = 0;
    const TWO_COLUMN = 1;
    const RIGHT = 2;
    const LEFT = 1;

    protected $form_name = "after_sale_services";
    protected $field_id = "ID";
    protected $defaultOrder = "LABEL";
    protected $administration = true;
    protected $multiLangue    = true;

    protected $decacheBackOrchestra = array(
        'strategy' => array(
            array(
                'locale',
                'siteId',
                'after_sale_services',
            )
        ),
    );

    protected function setListModel()
    {
        $con = Pelican_Db::getInstance();
        $bind               = array();
        $bind[':SITE_ID']   = (int) $_SESSION[APP]['SITE_ID'];
        $bind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $sqlList = 'SELECT ID, LABEL
                            FROM
                            #pref#_'. $this->form_name.'
                            WHERE SITE_ID = :SITE_ID
			                AND LANGUE_ID = :LANGUE_ID
                    ORDER BY ' . $this->defaultOrder;

        $sqlFilter = 'SELECT r.FILTERS_ID, r.AFTER_SALE_SERVICES_ID, f.LABEL
            FROM
                #pref#_after_sale_services_filters_relation as r
            LEFT JOIN #pref#_filter_after_sale_services as f
            ON r.FILTERS_ID = f.ID
            WHERE
                r.SITE_ID = :SITE_ID
                AND r.LANGUE_ID = :LANGUE_ID
        ';

        $resApv = $con->queryTab($sqlList, $bind);
        $resFilter = $con->queryTab($sqlFilter, $bind);

        $result = array();
        foreach ($resApv as $apv) {
            $relation = array();
            foreach ($resFilter as $filter) {
                if ($apv['ID'] === $filter['AFTER_SALE_SERVICES_ID']) {
                    $relation[] = $filter['LABEL'];
                }
            }
            $apv['FILTERS'] = '';
            if (!empty($relation)) {
                $apv['FILTERS'] = implode('<br/>', $relation);
            }
            $result[] = $apv;
        }

        $this->listModel = $result;
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', '', '', 0, 0, 0, 'liste');
        $table->setValues($this->getListModel(), 'ID');
        $table->addColumn(t('ID'), 'ID', 3, 'center', '', 'tblheader', 'ID');
        $table->addColumn(t('NDP_AFTER_SALE_SERVICE_LABEL'), 'LABEL', 10, 'center', '', 'tblheader', 'LABEL');
        $table->addColumn(t('NDP_ASSOCIATED_FILTER'), 'FILTERS', 10, 'center', '', 'tblheader', 'FILTERS');
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => 'ID'), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => 'ID', '' => 'readO=true'), 'center');

        $filterNumber = $this->getCountFilter();
        $withoutFilter = $this->getSiteFilter();
        if ((int)$filterNumber < 1 && !$withoutFilter || count($this->listModel) >= 25) {
            // hide add button
            $this->aButton["add"] = "";
            Backoffice_Button_Helper::init($this->aButton);
        }

        $this->assign("table", $table->getTable(), false);
        $this->assign("count", $filterNumber, false);
        $this->assign("withoutFilter", $withoutFilter, false);
        $this->assign("text", t('NDP_ERROR_NO_FILTER'), false);
        $this->fetch();
    }

    protected function setEditModel ()
    {
        $this->aBind[':' . $this->field_id] = (int) $this->id;
        $this->aBind[':SITE_ID']            = (int) $_SESSION[APP]['SITE_ID'];
        $this->aBind[':LANGUE_ID']          = (int) $_SESSION[APP]['LANGUE_ID'];
        $this->editModel = 'SELECT * FROM #pref#_'. $this->form_name . ' WHERE
            SITE_ID = :SITE_ID
            AND LANGUE_ID = :LANGUE_ID
            AND ' . $this->field_id . ' = :' . $this->field_id;
    }

    public function editAction()
    {
        parent::editAction();
        $form = $this->startStandardForm();
        $form .= $this->oForm->createHidden($this->field_id, $this->id);
        $form .= $this->oForm->createHidden('SITE_ID',  $_SESSION[APP]['SITE_ID']);
        $form .= $this->oForm->createHidden('LANGUE_ID',  $_SESSION[APP]['LANGUE_ID']);

        //titre 1
        $form .= $this->oForm->createDescription(t('NDP_SETTING_APV'));

        $form .= $this->oForm->createInput('LABEL', t('NDP_SERVICE_LABEL'), 50, '', true, $this->values['LABEL'], $this->readO, 80);
        $form .= $this->oForm->createMedia('MEDIA_ID', t('NDP_VISUAL_DESKTOP_THUMBNAIL'), true, "image", "", $this->values["MEDIA_ID"], $this->readO, true, false, 'NDP_RATIO_16_9:858x481');
        // media_id2
        $form .= $this->oForm->createMedia('MEDIA_ID2', t('NDP_VISUAL_MOBILE_THUMBNAIL'), true, "image", "", $this->values["MEDIA_ID2"], $this->readO, true, false, 'NDP_RATIO_1_3:640x213');

        // lib lien desk
        $targetsDetail = [
            self::SHOW_DETAIL => t('NDP_SHOW_DETAIL'),
            self::OTHER => t('NDP_OTHER')
        ];
        $jsActivationDetail = self::addJsContainerRadio('TYPE_LABEL_LINK');
        self::setDefaultValueTo($this->zoneValues, 'TYPE_LABEL_LINK', self::SHOW_DETAIL);
        $form .= $this->oForm->createRadioFromList('TYPE_LABEL_LINK', t('NDP_THUMBNAIL_LINK_LABEL'), $targetsDetail, $this->values['TYPE_LABEL_LINK'], true, $this->readO, 'h', false, $jsActivationDetail, null, null);
        //lib
        $form .= self::addHeadContainer(self::OTHER, $this->values['TYPE_LABEL_LINK'], 'TYPE_LABEL_LINK');
        $form .= $this->oForm->createInput('LABEL_LINK', t('NDP_FIELD_OTHER_LABEL'), 60, '', false, $this->values['LABEL_LINK'], $this->readO, 80, false, "", "text");
        $form .= self::addFootContainer();

        //mentions legales
        $form .= $this->oForm->createInput('LEGAL_NOTICE', t('NDP_LEGAL_NOTICE_NUMBER'), 10, '', false, $this->values['LEGAL_NOTICE'], $this->readO, 20, false);

        //nombre de column
        $targetsColumn = [
            self::ONE_COLUMN => "1 ".t('NDP_COLONNE'),
            self::TWO_COLUMN => "2 ".t('NDP_COLONNES'),
        ];
        $jsActivationColumn = self::addJsContainerRadio('COLUMN_NUMBER');
        self::setDefaultValueTo($this->zoneValues, 'COLUMN_NUMBER', self::ONE_COLUMN);
        $form .= $this->oForm->createRadioFromList('COLUMN_NUMBER', t('NDP_ON_COLUMN'), $targetsColumn, $this->values['COLUMN_NUMBER'], true, $this->readO, 'h', false, $jsActivationColumn, null, null);

        $targetsPricePosition = [
            self::LEFT => t('NDP_PRICE_LEFT'),
            self::RIGHT => t('NDP_PRICE_RIGHT')
        ];
        self::setDefaultValueTo($this->zoneValues, 'PRICE_POSITION', self::LEFT);
        $form .= $this->oForm->createRadioFromList('PRICE_POSITION', t('NDP_PRICE_POSITION'), $targetsPricePosition, $this->values['PRICE_POSITION'], true, $this->readO, 'h', false, '', null, null);

        //affichage libprix [+ lib] + prix
        //libprix
        $targets = [
            self::FROM => t('NDP_FROM_BO'),
            self::OTHER => t('NDP_OTHER')
        ];
        $jsActivation = self::addJsContainerRadio('TYPE_LABEL_PRICE');
        self::setDefaultValueTo($this->zoneValues, 'TYPE_LABEL_PRICE', self::FROM);
        $form .= $this->oForm->createRadioFromList('TYPE_LABEL_PRICE', t('NDP_DISPLAY_PRICE_LABEL'), $targets, $this->values['TYPE_LABEL_PRICE'], true, $this->readO, 'h', false, $jsActivation, null, null);
        //lib
        $form .= self::addHeadContainer(self::OTHER, $this->values['TYPE_LABEL_PRICE'], 'TYPE_LABEL_PRICE');
        $form .= $this->oForm->createInput('PRICE_LABEL', t('NDP_PRICE_LABEL'), 60, '', false, $this->values['PRICE_LABEL'], $this->readO, 80, false);
        $form .= self::addFootContainer();
        //prix
        $form .= $this->oForm->createInput('PRICE', t('NDP_PRICE_BO'), 10, 'number', false, $this->values['PRICE'], $this->readO, 20, false);

        //description
        $infoBulle2 = ['isIcon'  => true,'message' => t('NDP_MSG_TOOLTIP_RECOMMENDATION_SERVICES')];
        $form .= $this->oForm->createTextArea('DESCRIPTION',t('NDP_DESCRIPTION_BULLET_LIST'), true, $this->values['DESCRIPTION'], '', $this->readO, 6, 80, false, "", false, "", $infoBulle2);

        $form .= self::addHeadContainer(self::TWO_COLUMN, $this->values['COLUMN_NUMBER'], 'COLUMN_NUMBER');

        $form .= $this->oForm->showSeparator();

        //affichage libprix [+ lib] + prix
        //libprix
        $jsActivation = self::addJsContainerRadio('TYPE_LABEL_PRICE2');
        self::setDefaultValueTo($this->zoneValues, 'TYPE_LABEL_PRICE2', self::FROM);
        $form .= $this->oForm->createRadioFromList('TYPE_LABEL_PRICE2', t('NDP_DISPLAY_PRICE_LABEL'), $targets, $this->values['TYPE_LABEL_PRICE2'], true, $this->readO, 'h', false, $jsActivation, null, null);
        //lib
        $form .= self::addHeadContainer(self::OTHER, $this->values['TYPE_LABEL_PRICE2'], 'TYPE_LABEL_PRICE2');
        $form .= $this->oForm->createInput('PRICE_LABEL2', t('NDP_PRICE_LABEL'), 30, '', false, $this->values['PRICE_LABEL2'], $this->readO, 80, false, "", "text", array(), false, null, $msgMaxCar2);
        $form .= self::addFootContainer();
        //prix
        $form .= $this->oForm->createInput('PRICE2', t('NDP_PRICE_BO'), 10, 'number', false, $this->values['PRICE2'], $this->readO, 20, false);

        //description
        $infoBulle2 = ['isIcon'  => true,'message' => t('NDP_MSG_TOOLTIP_RECOMMENDATION_SERVICES')];
        $form .= $this->oForm->createTextArea('DESCRIPTION2', t('NDP_DESCRIPTION_BULLET_LIST'), true, $this->values['DESCRIPTION2'], '', $this->readO, 6, 80, false, "", false, "", $infoBulle2);

        $form .= self::addFootContainer();

        $listFilter = $this->getCountFilter();

        $withoutFilter = $this->getSiteFilter();
        if (count($listFilter) > 0 && !$withoutFilter) {
            // titre 2
            $form .= $this->oForm->createTitle(t('NDP_SETTING_FILTER_AND_APV'));

            $form .= $this->oForm->createAssocFromSql('', 'FILTER_ID', t('NDP_RELATION_FILTER_AFTER_SALE_SERVICE_LABEL'), $this->getFiltersSql(), $this->getSelectFilterSql(), true);
        }

        $form .= $this->stopStandardForm();
        $this->setResponse($form);
    }

    public function getCountFilter()
    {
        $con = Pelican_Db::getInstance();
        $bind = array(':SITE_ID' => (int) $_SESSION[APP]['SITE_ID'],
            ':LANGUE_ID' => (int) $_SESSION[APP]['LANGUE_ID']);
        $sql = 'SELECT count(*)
                FROM #pref#_filter_after_sale_services
                WHERE SITE_ID = :SITE_ID
                AND LANGUE_ID = :LANGUE_ID';

        return $con->queryItem($sql, $bind);
    }

    protected function getFiltersSql()
    {
        return 'SELECT ID, LABEL
                    FROM
                        #pref#_filter_after_sale_services
                    WHERE
                        SITE_ID = '.(int) $_SESSION[APP]['SITE_ID'].'
			        AND LANGUE_ID = '.(int) $_SESSION[APP]['LANGUE_ID'].'
                    ORDER BY FILTER_ORDER';
    }

    protected function getSelectFilterSql()
    {
        return 'SELECT ID, LABEL
            FROM
                #pref#_filter_after_sale_services as f
            LEFT JOIN #pref#_after_sale_services_filters_relation as r
            ON f.ID = r.FILTERS_ID
            WHERE
                f.SITE_ID = '.(int) $_SESSION[APP]['SITE_ID'].'
                AND f.LANGUE_ID = '.(int) $_SESSION[APP]['LANGUE_ID'].'
                AND r.AFTER_SALE_SERVICES_ID = '.$this->id.'
            ';
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
        $aRx             = $con->queryItem($sqlList, $bind);

        $return = false;
        if ($aRx === '1') {
            $return = true;
        }

        return $return;
    }

    public function saveAction()
    {
        if (Pelican_Db::$values['TYPE_LABEL_PRICE'] == self::FROM) {
            Pelican_Db::$values['PRICE_LABEL'] = t('NDP_FROM_BO');
        }
        if (Pelican_Db::$values['TYPE_LABEL_LINK'] == self::SHOW_DETAIL) {
            Pelican_Db::$values['LABEL_LINK'] = t('NDP_SHOW_DETAIL');
        }
        if (Pelican_Db::$values[$this->field_id] == -2) {
            Pelican_Db::$values[$this->field_id] = $this->getNextId();
        }
        parent::saveAction();

        if ($this->form_action !== Pelican_Db::DATABASE_DELETE) {
            $con = Pelican_Db::getInstance();
            $sql = 'DELETE FROM #pref#_after_sale_services_filters_relation
                WHERE AFTER_SALE_SERVICES_ID = ' . Pelican_Db::$values['ID'] . '
                AND LANGUE_ID = '.Pelican_Db::$values['LANGUE_ID'].'
                AND SITE_ID = '.Pelican_Db::$values['SITE_ID'].'
               ';
            $con->query($sql);
            foreach (Pelican_Db::$values['FILTER_ID'] as $filter) {
                $sql = 'INSERT INTO #pref#_after_sale_services_filters_relation (AFTER_SALE_SERVICES_ID, LANGUE_ID, SITE_ID, FILTERS_ID) VALUES ('.Pelican_Db::$values['ID'].', '.Pelican_Db::$values['LANGUE_ID'].', '.Pelican_Db::$values['SITE_ID'].', '.$filter.')';
                $con->query($sql);
            }
        }
    }
}

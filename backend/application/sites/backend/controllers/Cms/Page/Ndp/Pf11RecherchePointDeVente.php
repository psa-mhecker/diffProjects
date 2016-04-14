<?php
/**
 * Tranche PF11 - Recherche Point de vente
 *
 * @author Pierre Pottié <pierre.pottie@businessdecision.com>
 *
 * @since 26/03/2012
 */
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';

use PsaNdp\MappingBundle\Entity\PsaPageTypesCode;

/**
 * Cms_Page_Ndp_Pf11RechechePointDeVente.
 */
class Cms_Page_Ndp_Pf11RecherchePointDeVente extends Cms_Page_Ndp
{
    const MODE_SEARCH_PDV = 1;
    const MODE_PROMO_APV = 2;
    const FILTER_RADIUS = 1;
    const FILTER_PDV = 2;

 /**
     * Render.
     *
     * @param Pelican_Controller $controller
     *
     * @return string $return
     */
    public static function render(Pelican_Controller $controller)
    {
        return self::getForm($controller);
    }

     /**
     * getForm.
     *
     * @param Pelican_Controller $controller
     *
     * @return string $return
     */
    public static function getForm(Pelican_Controller $controller)
    {
        $form = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));

        $form .= $controller->oForm->createInput(
            $controller->multi.'ZONE_TITRE',
            t('TITLE'),
            60,
            '',
            true,
            $controller->zoneValues['ZONE_TITRE'],
            $controller->readO,
            75, false, '', 'text', [], false, '', "60 ".t('NDP_MAX_CAR')
        );

        if (empty($controller->zoneValues['ZONE_CRITERIA_ID'])) {
            $controller->zoneValues['ZONE_CRITERIA_ID'] = 1;
        }

        $pageTypeCode = self::getPageTypeCode();
        if ($pageTypeCode === PsaPageTypesCode::PAGE_TYPE_CODE_DEALER_LOCATOR) {

            $modeDeGestion = array(
                self::MODE_SEARCH_PDV => t('NDP_MODE_SEARCH_PDV'),
                self::MODE_PROMO_APV => t('NDP_MODE_PROMO_APV'),
            );
            $typeModeGestion = $controller->multi.'CONTAINER_MODE_GESTION';

            $jsContainerModeGestion = self::addJsContainerRadio($typeModeGestion);
            $form .= $controller->oForm->createComboFromList(
                $controller->multi.'ZONE_CRITERIA_ID',
                t('NDP_MODE_MANAGEMENT'),
                $modeDeGestion,
                $controller->zoneValues['ZONE_CRITERIA_ID'],
                true,
                $controller->readO,
                '1',
                false,
                '',
                true,
                false,
                $jsContainerModeGestion
            );

            $showMap = array(
                self::FILTER_RADIUS => t('NDP_FILTER_RADIUS'),
                self::FILTER_PDV => t('NDP_FILTER_PDV'),
            );
            if (empty($controller->zoneValues['ZONE_CRITERIA_ID2'])) {
                $controller->zoneValues['ZONE_CRITERIA_ID2'] = 1;
            }
            $type = $controller->multi.'CONTAINER_SHOWMAP';
            $jsContainerShowmap = self::addJsContainerRadio($type, true);
            $form .= $controller->oForm->createRadioFromList(
                $controller->multi.'ZONE_CRITERIA_ID2',
                t('NDP_DISPLAY_MAP'),
                $showMap,
                $controller->zoneValues['ZONE_CRITERIA_ID2'],
                true,
                $controller->readO,
                'h',
                false,
                $jsContainerShowmap
            );

            $form .= self::addHeadContainer('1', $controller->zoneValues['ZONE_CRITERIA_ID'], $typeModeGestion);
            $searchCriteria = self::getArrayIdLabelListServicePdv();
            $valueParameters = explode('#', $controller->zoneValues['ZONE_PARAMETERS']);
            $form .= $controller->oForm->createCheckBoxFromList(
                $controller->multi."ZONE_PARAMETERS",
                t('NDP_PF11_SEARCH_CRITERIA'),
                $searchCriteria,
                $valueParameters,
                false,
                $controller->readO
            );

            $form .= self::getRadioFilterNamePdv($controller->oForm, $controller->zoneValues, $controller->readO, $controller->multi);
            $form .= self::addFootContainer();

            if (!isset($controller->zoneValues['ZONE_ATTRIBUT'])) {
                $controller->zoneValues['ZONE_ATTRIBUT'] = 1;
            }
            $form .= $controller->oForm->createRadioFromList(
                $controller->multi.'ZONE_ATTRIBUT',
                t('NDP_GROUPING'),
                array(1 => t('NDP_YES'), 0 => t('NDP_NO')),
                $controller->zoneValues['ZONE_ATTRIBUT'],
                true,
                $controller->readO,
                'h'
            );

            if (!isset($controller->zoneValues['ZONE_ATTRIBUT2'])) {
                $controller->zoneValues['ZONE_ATTRIBUT2'] = 1;
            }
            $form .= $controller->oForm->createRadioFromList(
                $controller->multi.'ZONE_ATTRIBUT2',
                t('NDP_AUTOCOMPLETE'),
                array(1 => t('NDP_YES'), 0 => t('NDP_NO')),
                $controller->zoneValues['ZONE_ATTRIBUT2'],
                true,
                $controller->readO,
                'h'
            );


            $valueTitre2 = (isset($controller->zoneValues['ZONE_TITRE2'])) ? $controller->zoneValues['ZONE_TITRE2'] : 300;
            $form .= $controller->oForm->createInput(
                $controller->multi.'ZONE_TITRE2',
                t('NDP_NB_MAX_PDV'),
                3,
                'number',
                true,
                $valueTitre2,
                $controller->readO,
                4
            );

            $valueTitre3 = (isset($controller->zoneValues['ZONE_TITRE3'])) ? $controller->zoneValues['ZONE_TITRE3'] : 20;
            $form .= $controller->oForm->createInput(
                $controller->multi.'ZONE_TITRE3',
                t('NDP_RADIUS'),
                2,
                'positive-number',
                true,
                $valueTitre3,
                $controller->readO,
                4
            );

            $form .= $controller->oForm->createInput(
                $controller->multi.'ZONE_TITRE4',
                t('NDP_NB_PDV'),
                3,
                'number',
                false,
                $controller->zoneValues['ZONE_TITRE4'],
                $controller->readO,
                4
            );

            $form .= $controller->oForm->createInput(
                $controller->multi.'ZONE_TITRE5',
                t('NDP_NB_DVN'),
                3,
                'number',
                false,
                $controller->zoneValues['ZONE_TITRE5'],
                $controller->readO,
                4
            );

            if (!isset($controller->zoneValues['ZONE_ATTRIBUT3'])) {
                $controller->zoneValues['ZONE_ATTRIBUT3'] = 0;
            }
            $form .= $controller->oForm->createRadioFromList(
                $controller->multi.'ZONE_ATTRIBUT3',
                t('NDP_DISPLAY_PHONE_BUTTON'),
                array(1 => t('OUI'), 0 => t('NON')),
                $controller->zoneValues['ZONE_ATTRIBUT3'],
                true,
                $controller->readO,
                'h'
            );
        }

        return $form;
    }

    /**
     * get array id/label for list service pdv
     *
     * @return array bind
     */
    public static function getArrayIdLabelListServicePdv()
    {
        self::$con = Pelican_Db::getInstance();
        $resultDb = self::$con->queryTab(self::getSqlListServicePdv(), self::getBindListServicePdv());
        $result = array();

        if (!empty($resultDb)) {
            foreach ($resultDb as $item) {
                if (empty($item['labelperso']) || $item['labelperso'] == '') {
                    $item['labelperso'] = $item['label'];
                }
                $result[$item['id']] = $item['labelperso'];
            }
        }

        return $result;
    }

    /**
     * get sql request for list service pdv
     *
     * @return string sql
     */
    public static function getSqlListServicePdv()
    {
        $sql = "SELECT  PDV_SERVICE_ID id,
                    PDV_SERVICE_LABEL label,
                    PDV_SERVICE_LABEL_PERSO labelperso
                    FROM #pref#_pdv_service
                    WHERE SITE_ID =:SITE_ID
                    AND LANGUE_ID =:LANGUE_ID
                    ORDER BY PDV_SERVICE_ORDER";

        return $sql;
    }

    /**
     * @return mixed
     */
    public static function getPageTypeCode()
    {
        $con = Pelican_Db::getInstance();
        $bind[':ID'] = (int) $_SESSION[APP]['TEMPLATE_PAGE_ID'];
        $sql = "SELECT pt.PAGE_TYPE_CODE FROM #pref#_template_page as tp
                LEFT JOIN #pref#_page_type as pt
                ON tp.PAGE_TYPE_ID = pt.PAGE_TYPE_ID
                WHERE tp.TEMPLATE_PAGE_ID = :ID";

        return $con->queryItem($sql, $bind);
    }

    /**
     * get bind array for list service pdv request
     *
     * @return array bind
     */
    public static function getBindListServicePdv()
    {
        $bind = array(
          ':SITE_ID' => $_SESSION[APP]['SITE_ID'],
          ':LANGUE_ID' => $_SESSION[APP]['LANGUE_ID']
        );

        return $bind;
    }

    /**
    *
    * @param Ndp_Form $form
    * @param array $values
    * @param boolean $readO
    * @param string $multi
    *
    * @return string
    */
    public static function getRadioFilterNamePdv(Ndp_Form $form, $values, $readO, $multi)
    {
        $filterPdvPerName = array(
            1 => t('NDP_YES'),
            0 => t('NDP_NO'),
        );
        if (!isset($values['ZONE_CRITERIA_ID3'])) {
            $values['ZONE_CRITERIA_ID3'] = 0;
        }

        $result = $form->createRadioFromList(
            $multi.'ZONE_CRITERIA_ID3',
            t('NDP_FILTER_PDV_LABEL'),
            $filterPdvPerName,
            $values['ZONE_CRITERIA_ID3'],
            true,
            $readO
        );

        return $result;
    }

    /**
     *
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        if (!empty(Pelican_Db::$values['ZONE_PARAMETERS'])) {
            Pelican_Db::$values['ZONE_PARAMETERS'] = implode('#', Pelican_Db::$values['ZONE_PARAMETERS']);
        }
        parent::save();
    }
}

<?php

include_once Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'] . '/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'] . '/Ndp/Multi/Factory.php';

/**
 *
 */
class Cms_Page_Ndp_Pn13Ancres extends Cms_Page_Ndp
{

    const MULTI_TYPE = 'ANCHOR';


    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {

        $controller->zoneValues['ZONE_MOBILE_SHOW'] = true;
        $controller->zoneValues['ZONE_MOBILE'] = true;

        $form = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        $form .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITLE'), 60, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 60, false, "", "text", array(), false, "", 60 .t('NDP_MAX_CAR'));
        $pageId = (isset($controller->zoneValues['PAGE_ID'])) ? $controller->zoneValues['PAGE_ID'] : $_SESSION[APP]['PAGE_ID'];

        $_SESSION[APP]['PAGE_VERSION'] = self::getPageVersion($pageId);
        $_SESSION[APP]['PN13_ORDER'] = (isset($controller->zoneValues['ZONE_ORDER'])) ? $controller->zoneValues['ZONE_ORDER'] : 1;
        $_SESSION[APP]['PN13_AREA_ID'] = (isset($controller->pageValues['TEMPLATE_PAGE_ID'])) ? self::getDynamicAreaIdByTemplateId($controller->pageValues['TEMPLATE_PAGE_ID']) : $controller->pageValues['AREA_ID'];
        $isZoneDynamique = Ndp_Multi::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC,
                $isZoneDynamique);
        $ancresValues = $multi->setMultiType(self::MULTI_TYPE)
            ->hydrate($controller->zoneValues)
            ->getValues();
        foreach ($ancresValues as $key => $ancreValues) {
            $ancresValues[$key]['PAGE_ID'] = $controller->zoneValues['PAGE_ID'];
            $ancresValues[$key]['PAGE_VERSION'] = $controller->zoneValues['PAGE_VERSION'];
            $ancresValues[$key]['CURRENT_UID'] = $controller->zoneValues['UID'];
            $ancresValues[$key]['TEMPLATE_PAGE_ID'] = $controller->pageValues['TEMPLATE_PAGE_ID'];
            // gestion des page diffusé
            $parts = explode('.',$ancreValues['PAGE_ZONE_MULTI_VALUE']);
            if(!empty($controller->pageValues['PAGE_ID']) && ($parts[0] != $controller->pageValues['PAGE_ID'])) {
                $parts[0] = $controller->pageValues['PAGE_ID'];
                $ancresValues[$key]['PAGE_ZONE_MULTI_VALUE'] = implode('.',$parts);
            }
        }

        $strLib = array(
            'multiAddButton' => t('ADD_ANCRE'),
            'oneStrongLine'  => true
        );
        $form .= $controller->oForm->createMultiHmvc($controller->multi . self::MULTI_TYPE,
            $strLib,
            array(
            "path" => __FILE__,
            "class" => __CLASS__,
            "method" => "addAncre"),
            $ancresValues,
            self::MULTI_TYPE,
            $controller->readO,
            array(1, 8),
            true,
            true,
            $controller->multi . self::MULTI_TYPE);
        unset($_SESSION[APP]['PAGE_VERSION']);
        unset($_SESSION[APP]['PN13_ORDER']);
        unset($_SESSION[APP]['PN13_AREA_ID']);

        return $form;
    }

    /**
     *
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        parent::save();
        $saved = Pelican_Db::$values;
        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC);
        $multi->setMultiType(self::MULTI_TYPE)
            ->setMulti($controller->multi)
            ->delete()
            ->save();
        Pelican_Db::$values = $saved;
    }

    /**
     *
     * @param Ndp_Form $form
     * @param array    $values
     * @param boolean  $readO
     * @param string   $multi
     *
     * @return string
     */
    public static function addAncre(Ndp_Form $form, $values, $readO, $multi)
    {
        $formMulti = $form->createInput($multi . 'PAGE_ZONE_MULTI_TITRE',
            t('NDP_TITRE_ANCRE'),
            50,
            '',
            true,
            $values['PAGE_ZONE_MULTI_TITRE'],
            $readO,
            50);

        $listValues = self::getValuesListTranches(
          self::getBindListTranche($values['PAGE_ID'], $values['PAGE_VERSION'], $values['CURRENT_UID'])
        );

        $saveStyleVal = $form->sStyleVal;
        //ajout une classe CSS pour permettre la mise à jour du select avec le JQUERY (script.js : zoneModule.PC78 )
        $form->sStyleVal.=' NDP_ASSOCIATED_SLICE78 '.'_NDP_ASSOCIATED_SLICE78_'.$values['CURRENT_UID'];

        $formMulti .= $form->createComboFromList($multi.'PAGE_ZONE_MULTI_VALUE',
          t('NDP_TITRE_DE_LA_TRANCHE'),
          $listValues,
          $values['PAGE_ZONE_MULTI_VALUE'], true, $readO, 1, false, false,
          false);

        $form->sStyleVal = $saveStyleVal;

        return $formMulti;
    }


    /**
     *
     * @param int $pageId
     * @param int $pageVersion
     * @param string $uid
     *
     * @return array
     */
    public static function getBindListTranche($pageId, $pageVersion, $uid)
    {
        $connection = Pelican_Db::getInstance();
        $bind = [
          ':PAGE_ID' => $pageId,
          ':PAGE_VERSION' => $pageVersion,
          ':MULTI_ZONE_UID' => $connection->strToBind($uid)
        ];

        return $bind;
    }

    public static function getSqlListTranches()
    {
        $sqlData = "SELECT DISTINCT  CONCAT(pab.PERMANENT_ID) AS id, pab.ZONE_ORDER, zt.ZONE_TEMPLATE_LABEL AS lib
                    FROM #pref#_zone z
                    INNER JOIN #pref#_page_areas_blocks pab ON z.ZONE_ID = pab.ZONE_ID
                    INNER JOIN #pref#_zone_template zt ON zt.ZONE_ID = z.ZONE_ID
                    INNER JOIN #pref#_area ar ON ar.AREA_ID = zt.AREA_ID
                    WHERE pab.PAGE_ID =:PAGE_ID
                    AND pab.AREA_ID = ".$_SESSION[APP]['PN13_AREA_ID']."
                    AND pab.ZONE_ORDER >= ".$_SESSION[APP]['PN13_ORDER']."
                    AND pab.PAGE_VERSION =:PAGE_VERSION
                    AND pab.TEMPLATE_PAGE_ID = zt.TEMPLATE_PAGE_ID
                    AND pab.MULTI_ZONE_UID != :MULTI_ZONE_UID
                    ORDER BY pab.TEMPLATE_PAGE_AREA_ORDER, pab.ZONE_ORDER";

        return $sqlData;
    }

    public static function getValuesListTranches($bind)
    {
        $connection = Pelican_Db::getInstance();
        $sqlData = $connection->queryTab(self::getSqlListTranches(), $bind);
        $valuesList = array();
        foreach ($sqlData as $row) {
            $valuesList[$row['id']] = 'N°' . $row['ZONE_ORDER']. ' '.t($row['lib']);
        }
        return $valuesList;
    }


}

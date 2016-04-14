<?php

/**
 * Tranche PT - Engagments.
 *
 * @author Kevin Vignon <kevin.vignon@businessdecision.com>
 *
 * @since 05/03/2015
 */
require_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';

class Cms_Page_Ndp_Pt19Engagements extends Cms_Page_Ndp
{

    const NOT_OK       = -2;
    const PT19_CONTENT = 2;

    /**
     *
     * @param Pelican_Controller $controller
     * @return type
     */
    public static function render(Pelican_Controller $controller)
    {
        $form  = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        $form .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE",
            t('NDP_TITRE_DE_LA_TRANCHE'),
            60,
            "",
            false,
            $controller->zoneValues["ZONE_TITRE"],
            $controller->read0,
            100);

        $form .= $controller->oForm->createLabel('', t('NDP_MSG_ENGAGEMENT'));

        if(empty($controller->zoneValues['ZONE_TEMPLATE_ID'])){
            $valuesEngagements = self::getMultiZoneValuesEngagements($controller->zoneValues);
        } else{
            $valuesEngagements = self::getZoneValuesEngagements($controller->zoneValues);
        }

        $form .= $controller->oForm->createContentFromList($controller->multi . 'CONTENT_ID_GAUCHE',
            t('NDP_ENGAGEMENT_GAUCHE'),
            array(
            $valuesEngagements[0]['id'] => $valuesEngagements[0]['lib']),
            true,
            $controller->readO,
            '1',
            200,
            false,
            true,
            self::PT19_CONTENT,
            false);

        $form .= $controller->oForm->createContentFromList($controller->multi.'CONTENT_ID_MILIEU',
            t('NDP_ENGAGEMENT_MILIEU'),
            array(
            $valuesEngagements[1]['id'] => $valuesEngagements[1]['lib']),
            true,
            $controller->readO,
            '1',
            200,
            false,
            true,
            self::PT19_CONTENT,
            false);

        $form .= $controller->oForm->createContentFromList($controller->multi.'CONTENT_ID_DROIT',
            t('NDP_ENGAGEMENT_DROITE'),
            array(
            $valuesEngagements[2]['id'] => $valuesEngagements[2]['lib']),
            true,
            $controller->readO,
            '1',
            200,
            false,
            true,
            self::PT19_CONTENT,
            false);

        self::addJsControl($controller);

        return $form;
    }

    /**
     *
     * @param type $controller
     */
    public static function addJsControl($controller)
    {
        $controller->oForm->_sJS .='
            var engagementGauche    =   parseInt($(\'#'.$controller->multi.'CONTENT_ID_GAUCHE\').val());
            var engagementMilieu    =   parseInt($(\'#'.$controller->multi.'CONTENT_ID_MILIEU\').val());
            var engagementDroit     =   parseInt($(\'#'.$controller->multi.'CONTENT_ID_DROIT\').val());
            if(engagementGauche == engagementMilieu || engagementMilieu == engagementDroit || engagementDroit == engagementGauche){
                alert("'.t("NDP_MSG_1SEULCONTENU").'");
                return false;
            }
        ';
    }

    public static function save()
    {
        parent::save();
        self::saveContentsValues();
    }

    /**
     *
     * @return type
     */
    public static function getConfigEngagements()
    {

        return array(
            1 => 'CONTENT_ID_GAUCHE',
            2 => 'CONTENT_ID_MILIEU',
            3 => 'CONTENT_ID_DROIT'
        );
    }

    public static function saveContentsValues()
    {
        $connection = Pelican_db::getInstance();
        $saved = Pelican_Db::$values;
        foreach (self::getConfigEngagements() as $key => $typeEngagement){
            if (!empty(Pelican_Db::$values[$typeEngagement])){
                Pelican_Db::$values['CONTENT_ID'] = Pelican_Db::$values[$typeEngagement];
                Pelican_Db::$values['PAGE_ZONE_PARAMETERS'] = $key;
                unset(Pelican_Db::$values['PAGE_ZONE_DATE_DEBUT']);
                unset(Pelican_Db::$values['PAGE_ZONE_DATE_FIN']);
                if(empty(Pelican_Db::$values['ZONE_TEMPLATE_ID'])){
                    $connection->insertQuery('#pref#_page_multi_zone_content');
                } else{
                    $connection->insertQuery('#pref#_page_zone_content');
                }
            }
        }
        Pelican_Db::$values = $saved;
    }

    /**
     *
     * @param type $values
     * @return type
     */
    public static function getMultiZoneValuesEngagements($values)
    {
        $connection = Pelican_Db::getInstance();
        if ($values['PAGE_ID'] != self::NOT_OK){
            $bind[":PAGE_ID"] = $values["PAGE_ID"];
            $bind[":LANGUE_ID"] = $values["LANGUE_ID"];
            $bind[":PAGE_VERSION"] = $values["PAGE_VERSION"];
            $bind[":AREA_ID"] = $values["AREA_ID"];
            $sSql = "SELECT  distinct cv.CONTENT_ID AS id, cv.CONTENT_TITLE_BO AS lib
                FROM #pref#_page_multi_zone_content pmzc
                INNER JOIN #pref#_content_version cv ON pmzc.CONTENT_ID = cv.CONTENT_ID
                WHERE pmzc.PAGE_ID = :PAGE_ID
                AND pmzc.LANGUE_ID = :LANGUE_ID
                AND pmzc.PAGE_VERSION = :PAGE_VERSION
                AND cv.CONTENT_VERSION = ( SELECT tc.CONTENT_CURRENT_VERSION FROM #pref#_content tc WHERE tc.CONTENT_ID = pmzc.CONTENT_ID AND tc.LANGUE_ID = :LANGUE_ID )
                AND pmzc.AREA_ID = :AREA_ID
                ORDER BY PAGE_ZONE_PARAMETERS";
            $values = $connection->queryTab($sSql,
                $bind);

            return $values;
        }
    }

    /**
     *
     * @param type $values
     * @return type
     */
    public static function getZoneValuesEngagements($values)
    {
        $connection = Pelican_Db::getInstance();
        if ($values['PAGE_ID'] != self::NOT_OK){
            $bind[":PAGE_ID"] = $values["PAGE_ID"];
            $bind[":LANGUE_ID"] = $values["LANGUE_ID"];
            $bind[":PAGE_VERSION"] = $values["PAGE_VERSION"];
            $bind[":ZONE_TEMPLATE_ID"] = $values["ZONE_TEMPLATE_ID"];
            $sSql = "SELECT  distinct cv.CONTENT_ID AS id, cv.CONTENT_TITLE_BO AS lib
                FROM #pref#_page_zone_content pzc
                INNER JOIN #pref#_content_version cv ON pzc.CONTENT_ID = cv.CONTENT_ID
                WHERE pzc.PAGE_ID = :PAGE_ID
                AND pzc.LANGUE_ID = :LANGUE_ID
                AND pzc.PAGE_VERSION = :PAGE_VERSION
                AND pzc.ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
                AND cv.CONTENT_VERSION = ( SELECT tc.CONTENT_CURRENT_VERSION FROM #pref#_content tc WHERE tc.CONTENT_ID = pzc.CONTENT_ID AND tc.LANGUE_ID = :LANGUE_ID)
                ORDER BY PAGE_ZONE_PARAMETERS";
            $values = $connection->queryTab($sSql,
                $bind);

            return $values;
        }
    }
}

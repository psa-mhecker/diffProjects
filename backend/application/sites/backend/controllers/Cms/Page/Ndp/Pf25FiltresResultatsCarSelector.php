<?php
/**
 * Tranche PF25 - Filtres et resultats Car Selector
 *
 * @package Pelican_BackOffice
 * @subpackage Tranche
 * @author Joseph FRANCLIN <joseph.franclin@businessdecision.com>
 * @since 25/03/2015
 */

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Ndp/CarSelectorFilter.php';

use PSA\MigrationBundle\Entity\Page\PsaPage;

class Cms_Page_Ndp_Pf25FiltresResultatsCarSelector extends Cms_Page_Ndp
{
    const MAX_OTHER = 9;

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();
        $return = '';

        $selected = [];
        if (!empty($controller->zoneValues['ZONE_PARAMETERS']))
        {
            $selected = explode('#', $controller->zoneValues['ZONE_PARAMETERS']);
        }
        //parametrage
        $params['multi'] = $controller->multi.'ZONE_PARAMETERS';
        $params['title'] = t('NDP_CATEGORIES_TO_SHOW');
        $params['source'] = self::getModelsWithShowRooms();
        $params['datas'] = $selected;
        $params['order'] = true;
        $params['readO'] = $controller->readO;
        $params['form'] = $controller->oForm;
        //set du form
        $return .= self::getFormList($params);

        /**
         * Debut filtres
         */
//        $selected = [];
//        if (!empty($controller->zoneValues['ZONE_LABEL']))
//        {
//            $selected = explode('#', $controller->zoneValues['ZONE_LABEL']);
//        }
//        $params['multi'] = $controller->multi.'ZONE_LABEL';
//        $params['title'] = t('NDP_FILTERS_TO_SHOW');
//        $params['source'] = Ndp_CarSelectorFilter_Controller::getOtherFiltersAvailable();
//        $params['datas'] = $selected;
//        $params['max'] = self::MAX_OTHER;
//        $params['order'] = true;
//        $params['readO'] = $controller->readO;
//        $params['form'] = $controller->oForm;
//        $return .= self::getFormList($params);
        /**
         * Fin filtres
         */

        return $return;
    }

    /**
     *
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();

        if (!empty(Pelican_Db::$values['ZONE_PARAMETERS']))
        {
            Pelican_Db::$values['ZONE_PARAMETERS'] = implode('#', Pelican_Db::$values['ZONE_PARAMETERS']);
        }


//        if (!empty(Pelican_Db::$values['ZONE_LABEL']))
//        {
//            Pelican_Db::$values['ZONE_LABEL'] = implode('#', Pelican_Db::$values['ZONE_LABEL']);
//        }
        Pelican_Db::$values['ZONE_MOBILE'] =1;
        Pelican_Db::$values['ZONE_WEB'] =1;
        parent::save();
    }

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function setJs(Pelican_Controller $controller) {

        $jsText = '
            $(document).ready(function(){
            //2nd liste
            $("#'.$controller->multi.'ZONE_PARAMETERS_last_selected").parent().parent().find("td.formlib").attr("title","'.t('NDP_UP_TO_NINE_CAT').'");
             $("#'.$controller->multi.'ZONE_PARAMETERS_last_selected").parent().parent().find("td.formlib").attr("style","cursor:help;"); 
            })
        ';
        /*
         * //1er liste
             $("#'.$controller->multi.'ZONE_LABEL_last_selected").parent().parent().find("td.formlib").attr("title","'.t('NDP_UP_TO_SIX_CAT').'");
             $("#'.$controller->multi.'ZONE_LABEL_last_selected").parent().parent().find("td.formlib").attr("style","cursor:help;");
         */

        return Pelican_Html::script(array(type => 'text/javascript'), $jsText);
    }

    /**
     *
     *
     * @return array
     */
    private static function getVehiculesArray(Pelican_Controller $controller) {

        $sql = 'SELECT
                    ID, LABEL
                FROM #pref#_vehicle_category_site
                WHERE
                   SITE_ID = :SITE_ID
                   AND LANGUE_ID = :LANGUE_ID
                   AND CRITERES_MARKETING != ""
                ORDER BY CATEGORY_ORDER
                ';
        $bind = [];
        $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $bind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $tmp = self::$con->queryTab($sql, $bind);
        $return = [];
        foreach ($tmp as $row) {
            $return[$row['ID']] = $row['LABEL'];
        }

        return $return;
    }

    /**
     *
     * @param array params
     *
     * @return array
     */
    private static function getFormList(array $params)
    {
        $return = $params['form']->createAssocFromList(
            self::$con,
            $params['multi'],
            $params['title'],
            $params['source'],
            $params['datas'],
            true,
            true,
            $params['readO'],
            10,
            300,
            false,
            '',
            $params['order'],
            $params['max'],
            true
        );

        return $return;
    }

    /**
     * Returns only models having showroom welcome pages
     * @return array
     */
    private static function getModelsWithShowRooms() {

        $codePaysById = Pelican_Cache::fetch('Ndp/CodePaysById');

        $parameters = array(
            'languages' => strtolower($_SESSION[APP]['LANGUE_CODE']),
            'countries' => $codePaysById[$_SESSION[APP]['SITE_ID']],
        );

        $models = Pelican_Application::getContainer()->get('range_manager')->getGammesVehiculesByModelSilhouette($parameters);
        $showRoomWelcomePages  = Pelican_Application::getContainer()->get('open_orchestra_model.repository.node')->findAllShowroomWelcomePages($_SESSION[APP]['SITE_ID'],$_SESSION[APP]['LANGUE_ID']);
        $filteredModels = array_filter(array_flip($models), function($model) use($showRoomWelcomePages){
            $modelShowRoomPage = array_filter($showRoomWelcomePages, function(PsaPage $showRoomWelcomePage) use ($model){

                return $model === $showRoomWelcomePage->getVersion()->getGammeVehicule();
            });

            return (!empty($modelShowRoomPage));
        });

        return array_flip($filteredModels);
    }

}

<?php
/**
 * Tranche PN2 - Onglet.
 *
 * @author Pierre POTTIE <pierre.pottie@businessdecision.com>
 *
 * @since 17/03/2015
 */
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi/Factory.php';

/**
 * Cms_Page_Ndp_Pn2Tabs.
 */
class Cms_Page_Ndp_Pn2Tabs extends Cms_Page_Ndp
{

    const MULTI_TYPE = 'FORM_TABS';
    /**
     * Render.
     *
     * @param Pelican_Controller $controller
     *
     * @return string $return
     */
    public static function render(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();
        $return = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        $isZoneDynamique = Ndp_Multi::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC, $isZoneDynamique);
        $multiValues = $multi->setMultiType(self::MULTI_TYPE)
            ->hydrate($controller->zoneValues)
            ->getValues();
        $return .= $controller->oForm->createMultiHmvc(
            $controller->multi.self::MULTI_TYPE,
            '',
            array(
                'path'   => __FILE__,
                'class'  => __CLASS__,
                'method' => 'addTab'
            ),
            $multiValues,
            $controller->multi.self::MULTI_TYPE,
            $controller->readO,
            array(2, 2),
            false,
            false,
            $controller->multi.self::MULTI_TYPE,
             "values",
                "multi", "2", "", "", false, $options = ['showNumberLabel'=>false, 'noDragNDrop'=>false]
        );

        return $return;
    }


     /**
     *
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();
        parent::save();
        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC);
        $multi->setMultiType(self::MULTI_TYPE)
            ->setMulti($controller->multi)
            ->delete()
            ->save();
    }
    /**
     *
     * @param Ndp_Form $form
     * @param array    $values
     * @param boolean  $readO
     * @param string   $multi
     * @param int      $index
     *
     * @return string
     */
    public static function addTab(Ndp_Form $form, $values, $readO, $multi)
    {
        $index++;
        $return = $form->createLabel(t("NDP_TAB").($values['CPT_POS_MULTI'] + 1), '');
        $return .= "<tr>"
                ."<td class='formlib'>"
                .t('NDP_TAB_TITRE2')
                ." *</td>"
                ."<td class='formval'>";

        $return .= $form->createInput(
            $multi.'PAGE_ZONE_MULTI_TITRE2',
            t('NDP_TAB_TITRE2'),
            22,
            '',
            true,
            $values['PAGE_ZONE_MULTI_TITRE2'],
            $readO,
            75,
            true
        );
        $return .= Pelican_Html::span(array('style' =>'padding-left:10px;'), "22 ".t('NDP_LIMIT_LIB'));
        $return .= '</td></tr>';

        $return .= "<tr>"
                ."<td class='formlib'>"
                .t('NDP_TAB_TITRE')
                ."</td>"
                ."<td class='formval'>";

        $return .= $form->createInput(
            $multi.'PAGE_ZONE_MULTI_TITRE',
            t('NDP_TAB_TITRE'),
            44,
            '',
            false,
            $values['PAGE_ZONE_MULTI_TITRE'],
            $readO,
            75,
            true
        );
        $return .= Pelican_Html::span(array('style' =>'padding-left:10px;'), "44 ".t('NDP_LIMIT_LIB'));
        $return .= '</td></tr>';
        $message = t('NDP_NB_TAB_TOOLTIP');
        if ($values['CPT_POS_MULTI'] == 1) {
            $message = t('NDP_NB_TAB_TOOLTIP2');
        }
        $return .= $form->createInput(
            $multi.'PAGE_ZONE_MULTI_VALUE',
            t('NDP_NB_ZONE').' '.($values['CPT_POS_MULTI'] + 1),
            5,
            'numerique',
            true,
            $values['PAGE_ZONE_MULTI_VALUE'],
            $readO,
            10,
            false, 
            "", 
            "text", 
            array(), 
            false, 
            array('isIcon' => true, 'message'=> $message)
        );        

        return $return;
    }
}

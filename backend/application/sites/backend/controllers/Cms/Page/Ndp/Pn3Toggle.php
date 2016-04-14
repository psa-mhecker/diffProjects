<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi/Factory.php';

/**
 *
 */
class Cms_Page_Ndp_Pn3Toggle extends Cms_Page_Ndp
{
    const MULTI_TYPE = "TOGGLE_FORM";

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
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
        $return .= $controller->oForm->createMultiHmvc($controller->multi.self::MULTI_TYPE,
                t('NDP_ADD_TOGGLE'),
                array(
                    "path"   => __FILE__,
                    "class"  => __CLASS__,
                    "method" => "addToggle"),
                $multiValues,
                $controller->multi.self::MULTI_TYPE,
                $controller->readO,
                array(1, 10),
                true,
                true,
                $controller->multi.self::MULTI_TYPE,
                "values",
                "multi",
                "2",
                "",
                "",
                false,
                array('noDragNDrop' => true, 'showNumberLabel' => false));

        $return .= $controller->oForm->createJS(''
               .' var count = $(\'#count_'.$controller->multi.self::MULTI_TYPE.'\').val();'
               .' for(i = 0; i < count; i++)'
               .'{'
               .' if($("#'.$controller->multi.self::MULTI_TYPE.'"+i+"_PAGE_ZONE_MULTI_VALUE").val() > 5)'
               .' {'
               .'  alert("'.t('NDP_MORE_THAN').' 5 '.t('NDP_SELECTED_ZONE').'");'
               .'  fwFocus(eval("'.$controller->multi.self::MULTI_TYPE.'"+i+"_PAGE_ZONE_MULTI_VALUE"));'
               .'  return false;'
               .' }'
               .'}');

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
     *
     * @return string
     */
    public static function addToggle(Ndp_Form $form, $values, $readO, $multi)
    {
        $return = "<tr>"
                ."<td class='formlib'>".t('NDP_TOGGLE').' '.(isset($values['CPT_POS_MULTI']) ? $values['CPT_POS_MULTI'] + 1 : '__CPT1__')." *</td>"
                ."<td class='formval'>";
        $return .= $form->createInput($multi."PAGE_ZONE_MULTI_TITRE", '', 50, "", true, $values["PAGE_ZONE_MULTI_TITRE"], $readO, 50, true);
        $return .= "<span> ".t('NDP_MSG_PRECO_UPPERCASE_TITLE')." </span></td></tr>";
        $typAffichage = array(
            1 => t('OPEN'),
            2 => t('NDP_CLOSED'),
        );
        if (empty($values['PAGE_ZONE_MULTI_MODE'])) {
            $values['PAGE_ZONE_MULTI_MODE'] = 2;
        }
        $return .= '<tr><td colspan="2" class="formlib" style="padding-top:10px;">'.t('NDP_MSG_TOGGLE').'</td></tr>'
                ."<tr>"
                ."<td class='formlib'></td>"
                ."<td class='formval'>";
        $return .= $form->createRadioFromList($multi.'PAGE_ZONE_MULTI_MODE', t('NDP_MODE_OUVERTURE'), $typAffichage, $values['PAGE_ZONE_MULTI_MODE'], true, $readO, 'v', true);

        $return .= "</td></tr>";

        $return .= $form->createInput($multi."PAGE_ZONE_MULTI_VALUE", t('NDP_ADD_SLICE_TOGGLE'), 1, "otherNumeric", true, $values["PAGE_ZONE_MULTI_VALUE"], $readO, 5);

        return $return;
    }
}

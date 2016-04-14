<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/Factory.php';

/**
 *
 */
class Cms_Page_Ndp_Pc59Tools extends Cms_Page_Ndp
{
    const TYPE_CTA = 'container_cta';
    const MODE_CTA = '4_CTA';
    const TYPE_AFFICHAGE = 'CTA';

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();
        $controller->zoneValues['ZONE_WEB_SHOW'] = false;
        $controller->zoneValues['ZONE_WEB'] = '0';
        $return = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 60, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 50);
        $styles = array(
                'style_niveau1' => t('NDP_STYLE_NIVEAU1'),
                'style_niveau2' => t('NDP_STYLE_NIVEAU2'),
                'style_niveau3' => t('NDP_STYLE_NIVEAU3')
        );
        $return .= $controller->oForm->createComboFromList($controller->multi."ZONE_TOOL2", t('NDP_STYLE'), $styles, $controller->zoneValues["ZONE_TOOL2"], true, $controller->readO, 1, false);

        $typAffichage = array(
            '4_CTA' => '4 '.t('CTAFORM'),
            '2_CTA' => '2 '.t('CTAFORM')
        );

        if (empty($controller->zoneValues['ZONE_TOOL'])) {
            $controller->zoneValues['ZONE_TOOL'] = "4_CTA";
        }

        $type = $controller->multi.self::TYPE_CTA;
        $js = self::addJsContainerRadio($type);
        $return .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_TOOL', t('NDP_FORMAT_MOBILE'), $typAffichage, $controller->zoneValues['ZONE_TOOL'], true, $controller->readO, 'h', false, $js);
        $return .= self::addHeadContainer('4_CTA', $controller->zoneValues['ZONE_TOOL'], $type);
        $return .= $controller->oForm->createLabel("", t('NDP_MSG_PC59_CTA'));
        $return .= self::addFootContainer();
        $return .= self::makeCTAs($controller, $type);

        return $return;
    }

    /**
     *
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();
        $ctaNumber = 4;
        Pelican_Db::$values['ZONE_WEB'] = '0';
        if (self::MODE_CTA !== Pelican_Db::$values['ZONE_TOOL']) {
            $ctaNumber = 2;
            unset(Pelican_Db::$values['MEDIA_ID']);
        }

        parent::save();

        self::saveCTAs($controller, $ctaNumber);
    }

    /**
     *
     * @param Pelican_Controller $controller
     * @param int $nombre
     */
    public static function saveCTAs(Pelican_Controller $controller, $nombre = 1)
    {
        for ($i = 1; $i <= $nombre; $i++) {
            $ctaSimple = Ndp_Cta_Factory::getInstance(Ndp_Cta::SIMPLE);
            $ctaSimple->setCtaType(self::TYPE_AFFICHAGE.$i)
            ->setMulti($controller->multi)
            ->setStyle(Pelican_Db::$values['ZONE_TOOL2'])
            ->setCtaId($i)
            ->delete()
            ->save();
        }
    }

    /**
     *
     * @param Pelican_Controller $controller
     * @param string $type
     */
    public static function makeCTAs(Pelican_Controller $controller, $type)
    {
        $return = '';
        for ($i = 1; $i < 5; $i++) {
            if ($i == 3) {
                $return .= self::addHeadContainer(self::MODE_CTA, $controller->zoneValues['ZONE_TOOL'], $type);
            }
            $return .= $controller->oForm->showSeparator();
            $return .= $controller->oForm->createLabel("CTA ".$i);
            $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
            $ctaComposite->setCta($controller->oForm, $controller->zoneValues, $controller->multi, self::TYPE_AFFICHAGE.$i, false, (Cms_Page_Ndp::isTranslator() || $controller->readO));
            $ctaRef       = Pelican_Factory::getInstance('CtaRef');
            $ctaRef->typeStyle(1)->hideStyle(true);
            $ctaComposite->addInputCta($ctaRef);
            $return .= $ctaComposite->generate();
            if ($i == 4) {
                $return .= self::addFootContainer();
            }
        }

        return $return;
    }

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function addJs($controller) {
        $jsText = '
            $(document).ready(function(){ =
             $("#'.$controller->multi.'ZONE_TOOL2").change(function () {
                 var val = $(this).val();
                 $( "input[name=\''.$controller->multi.'CTA1[SELECT_CTA][STYLE]\'").val(val);
                 $( "input[name=\''.$controller->multi.'CTA2[SELECT_CTA][STYLE]\'").val(val);
                 $( "input[name=\''.$controller->multi.'CTA3[SELECT_CTA][STYLE]\'").val(val);
                 $( "input[name=\''.$controller->multi.'CTA4[SELECT_CTA][STYLE]\'").val(val);
             })
             .trigger("change");
            })
        ';

        return Pelican_Html::script(array(type => 'text/javascript'), $jsText);
    }
}

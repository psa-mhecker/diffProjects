<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi/Factory.php';

/**
 *
 */
class Cms_Page_Ndp_Pc39SlideshowOffre extends Cms_Page_Ndp
{
    const CINEMASCOPE_MOB = "CINEMASCOPE_MOBILE";
    const CINEMASCOPE_WEB = "CINEMASCOPE_WEB";
    const VISUELS_3_WEB = "VISUELS_3_WEB";

    const MIN_VISUELS_3_WEB = 3;
    const MAX_VISUELS_3_WEB = 9;
    const MAX_CINEMASCOPE_WEB = 3;

    const SHOW = 1;

    /**
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();

        $controller->zoneValues['ZONE_WEB'] = (isset($controller->zoneValues['ZONE_WEB'])) ? $controller->zoneValues['ZONE_WEB'] : self::SHOW;
        $controller->zoneValues['ZONE_MOBILE'] =  (isset($controller->zoneValues['ZONE_MOBILE'])) ? $controller->zoneValues['ZONE_MOBILE'] : self::SHOW;

        $return = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        $typAffichage = array(
            self::CINEMASCOPE_WEB => t('NDP_CINEMASCOPE'),
            self::VISUELS_3_WEB => t('TROIS_VISUELS'),
        );
        if (empty($controller->zoneValues['ZONE_TOOL'])) {
            $controller->zoneValues['ZONE_TOOL'] = self::CINEMASCOPE_WEB;
        }
        $type = $controller->multi."FORM_WEB";
        $js = self::addJsContainerRadio($type);
        $return .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_TOOL', t('TYPE_AFFICHAGE'), $typAffichage, $controller->zoneValues['ZONE_TOOL'], true, $controller->readO, 'h', false, $js);

        $isZoneDynamique = Ndp_Multi::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC, $isZoneDynamique);

        $return .= self::addHeadContainer($controller->zoneValues['ZONE_WEB'], $controller->zoneValues['ZONE_WEB'], $controller->multi.self::CINEMASCOPE_WEB);

        for ($i = 0; $i < 2; $i++) {
            $typeForm = self::getTypes();
            $typeForm = $typeForm[$i];
            $slides = $multi->setMultiType($typeForm)
                ->hydrate($controller->zoneValues)
                ->getValues();
            $return .= self::addHeadContainer($typeForm, $controller->zoneValues['ZONE_TOOL'], $type);
            $strLib = array(
                'multiTitle'     => t('NDP_VERSION_WEB'),
                'multiAddButton' => t('NDP_ADD_VISUEL')
            );
            $return .= $controller->oForm->createMultiHmvc($controller->multi.$typeForm, $strLib, array(
                "path"   => __FILE__,
                "class"  => __CLASS__,
                "method" => "addSlide", ),
                $slides,
                $typeForm,
                (Cms_Page_Ndp::isTranslator() || $controller->readO),
                array(1, constant('self::MAX_'.$typeForm)),
                true,
                true,
                $controller->multi.$typeForm,
                '',
                '',
                '2',
                '',
                '',
                false,
                ['noDragNDrop' => Cms_Page_Ndp::isTranslator()]
            );
            $return .= self::addFootContainer();
        }
        $return .= self::addFootContainer();

        $slidesMob = $multi->setMultiType(self::CINEMASCOPE_MOB)
            ->hydrate($controller->zoneValues)
            ->getValues();
        $strLib['multiTitle'] = t('NDP_VERSION_MOBILE');
        $return .= $controller->oForm->createMultiHmvc($controller->multi.self::CINEMASCOPE_MOB, $strLib, array(
            "path"   => __FILE__,
            "class"  => __CLASS__,
            "method" => "addSlide", ),
            $slidesMob,
            self::CINEMASCOPE_MOB,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            array(1, 5),
            true,
            true,
            $controller->multi.self::CINEMASCOPE_MOB
        );

        $return .= $controller->oForm->createJS("
           var selected = $('input[name=".$controller->multi."ZONE_TOOL]:checked', '#fForm').val();
           if (selected == '".self::VISUELS_3_WEB."' && $('.".$controller->multi.self::VISUELS_3_WEB."_subForm').length <= ".self::MIN_VISUELS_3_WEB.") {
               alert('".t('NDP_3_VISUELS_3_WEB')."');
               return false;
           }
       ");

        return $return;
    }

    /**
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();
        parent::save();
        foreach (self::getTypes() as $type){
            $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC);
            $multi->setMultiType($type)
                ->setMulti($controller->multi)
                ->delete(); // on efface tout
            // mais on sauvegarde que celui choisi
            if(($type == self::CINEMASCOPE_MOB) || $type == Pelican_Db::$values['ZONE_TOOL'] )  {
                $multi->save();
            }
        }
    }

    /**
     *
     * @return array
     */
    public static function getTypes()
    {
        return array(self::CINEMASCOPE_WEB, self::VISUELS_3_WEB, self::CINEMASCOPE_MOB);

    }

    /**
     * @param Ndp_Form $form
     * @param array    $values
     * @param boolean  $readO
     * @param string   $multi
     *
     * @return string
     */
    public static function addSlide(Ndp_Form $form, $values, $readO, $multi)
    {
        $lib = t('NDP_CINEMASCOPE');

        $format  = 'NDP_RATIO_IAB_BILLBOARD:970x250';
        if (preg_match("/".self::VISUELS_3_WEB."/i", $multi)){
            $lib = t('TROIS_VISUELS');
            $format = 'NDP_RATIO_IAB_PAVE:300x250';
        }
        if (preg_match("/".self::CINEMASCOPE_MOB."/i", $multi)){
            $format = 'NDP_RATIO_IAB_BILLBOARD:640x165';
        }
        $return = $form->createMedia($multi.'MEDIA_ID', $lib, true, 'image', '', $values['MEDIA_ID'], (Cms_Page_Ndp::isTranslator() || $readO), true, false, $format);
        $return .= $form->createInput($multi.'PAGE_ZONE_MULTI_TITRE', t('TITLE'), 60, "", false, $values['PAGE_ZONE_MULTI_TITRE'], $readO, 75, false, '', 'text', [], false, '');
        $return .= $form->createInput($multi."PAGE_ZONE_MULTI_TITRE2", t('SOUS_TITRE'), 60, "", false, $values["PAGE_ZONE_MULTI_TITRE2"], $readO, 75, false, '', 'text', [], false, '');
        $return .= $form->createInput($multi."PAGE_ZONE_MULTI_LABEL", t('NDP_LIBELLE_CTA'), 60, "", false, $values["PAGE_ZONE_MULTI_LABEL"], $readO, 75, false, '', 'text', [], false, '');
        $return .= $form->createInput($multi."PAGE_ZONE_MULTI_URL", t('NDP_URL_CTA_AND_VISUEL'), 255, 'internallink', true, $values["PAGE_ZONE_MULTI_URL"], $readO, 100);

        $targets = array(
                '_self'  => t('NDP_SELF'),
                '_blank' => t('NDP_BLANK'),
            );
        $return .= $form->createRadioFromList($multi."PAGE_ZONE_MULTI_VALUE", t('NDP_MODE_OUVERTURE'), $targets, $values['PAGE_ZONE_MULTI_VALUE'], true, $readO, 'h');

        return $return;
    }

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function addJsContainer(Pelican_Controller $controller)
    {
        $type = $controller->multi.self::OFFRE_VIGNETTE;
        $jsText = '
            $(document).ready(function(){
                $("#'.$controller->multi.'ZONE_ATTRIBUT").change(function () {
                    $(\'.'.$type.'\').hide();
                    var selectedRadio =   $(this).val();
                    $(\'#'.$type.'_\' + selectedRadio).show();
                    $(\'.'.$type.'\').addClass(\'isNotRequired\');
                    $(\'#'.$type.'_\' + selectedRadio + \'\').removeClass(\'isNotRequired\');
                })
             .trigger("change");
            })
        ';

        return Pelican_Html::script(array(type => 'text/javascript'), $jsText);
    }
}

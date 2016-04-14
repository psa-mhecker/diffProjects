<?php

/**
 * Content - Slideshow
 *
 * @package Pelican_BackOffice
 * @subpackage Content
 * @author Laurent Franchomme <laurent.franchomme@businessdecision.com>
 * @since 09/04/2015
 */
class Cms_Content_Ndp_Pc33Slideshow extends Cms_Content_Module
{
    const TYPE_SLIDESHOW = 'SLIDESHOW';
    const SLIDE_MULTI_NAME = 'SLIDESHOW';
    const SLIDE_IMAGE = 'image';
    const SLIDE_STREAMLIKE = 'streamlike';
    const MIN_MULTI = 1;
    const MAX_MULTI = 5;

    const IS_CONTENT = true;

    public static function render(Pelican_Controller $controller)
    {
        Ndp_Cta_Factory::setContext('Content');

        $return = '';

        $return .= $controller->oForm->createInput(
            "CONTENT_SUBTITLE",
            t('NDP_SOUS_TITRE').' '.t('SLIDESHOW'),
            50,
            "",
            false,
            $controller->values["CONTENT_SUBTITLE"],
            $controller->read0,
            60,
            false, 
            '', 
            'text', 
            [], 
            false, 
            '', 
            50 .t('NDP_MAX_CAR')                
        );

        $return .= $controller->oForm->createInput(
            "CONTENT_TITLE2",
            t('TITLE').' '.t('PUSH'),
            80,
            "",
            false,
            $controller->values["CONTENT_TITLE2"],
            $controller->read0,
            100, 
            false, 
            '', 
            'text', 
            [], 
            false, 
            '', 
            80 .t('NDP_MAX_CAR')
        );

        $return .= $controller->oForm->createTextArea(
            "CONTENT_TEXT",
            t('NDP_DESCRIPTION').' '.t('PUSH'),
            false,
            $controller->values["CONTENT_TEXT"],
            500,
            $controller->readO,
            5,
            100,
            false,
            "",
            true
        );

        $return .= self::getCtaAffichageClassique($controller);

    //@TODO video de type streamlike

        $return .= $controller->oForm->createMultiHmvc(
            self::SLIDE_MULTI_NAME,
            t('NDP_ADD_SLIDE'),
            array(
            'path' => __FILE__,
            'class' => __CLASS__,
            'method' => 'addSlide',
            ),
            self::getContentVersionMediaValues($controller, self::SLIDE_MULTI_NAME),
            self::SLIDE_MULTI_NAME,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            [self::MIN_MULTI, self::MAX_MULTI],
            true,
            true,
            self::SLIDE_MULTI_NAME,
            'values',
            'multi',
            '2',
            '',
            '',
            false,
            ['noDragNDrop' => Cms_Page_Ndp::isTranslator()]
        );

        Ndp_Cta_Factory::setDefaultContext();

        return $return;
    }

     public static function getCtaAffichageClassique($controller)
    {

        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');

        $ctaComposite->setCta($controller->oForm, $controller->values, $controller->multi, 'AFFICHAGE_CLASSIQUE', false, (Cms_Page_Ndp::isTranslator() || $controller->readO));

        $ctaDisable = Pelican_Factory::getInstance('CtaDisable');
        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaNew = Pelican_Factory::getInstance('CtaNew');

        $ctaComposite->addInputCta($ctaDisable);
        $ctaComposite->addInputCta($ctaRef);
        $ctaComposite->addInputCta($ctaNew);

        $retour = $ctaComposite->generate();

        return $retour;
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
        $typAffichage = array(
          self::SLIDE_IMAGE => t('FORM_VISUAL'),
          self::SLIDE_STREAMLIKE => t('NDP_VIDEO'),
        );

        $js = self::addJsContainerRadio($multi);
        $return  = $form->createRadioFromList($multi.'MEDIA_TYPE_ID', t('NDP_TYPE_AFFICHAGE'), $typAffichage, $values['MEDIA_TYPE_ID'], true, $readO, 'h', false, $js);

        $return .= self::addHeadContainer(self::SLIDE_IMAGE, $values['MEDIA_TYPE_ID'], $multi);
        $return .= $form->createMedia($multi.'MEDIA_ID', t('FORM_VISUAL'), true, 'image', '', $values['MEDIA_ID'], (Cms_Page_Ndp::isTranslator() || $readO), true, false, '872x440');
        $return .= self::addFootContainer();

        $return .= self::addHeadContainer(self::SLIDE_STREAMLIKE, $values['MEDIA_TYPE_ID'], $multi);
        $return .= $form->createMedia($multi.'MEDIA_ID_VIDEO', t('NDP_VIDEO'), false, 'streamlike', '', $values['MEDIA_ID'], (Cms_Page_Ndp::isTranslator() || $readO), true, false);
        $return .= self::addFootContainer();

        return $return;
    }


    /**
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        Ndp_Cta_Factory::setContext('Content');

        $saved = Pelican_Db::$values;
        parent::save($controller);

        //Loop to fix MEDIA_ID_VIDEO on MEDIA_ID if MEDIA_TYPE_ID is SLIDE_STREAMLIKE
        $countMulti = (int) Pelican_Db::$values['count_'.self::SLIDE_MULTI_NAME];

        if($countMulti > 0) {
            for($i=0;$i<$countMulti;$i++) {
                if(Pelican_Db::$values[self::SLIDE_MULTI_NAME.$i.'_MEDIA_TYPE_ID'] == self::SLIDE_STREAMLIKE) {
                    Pelican_Db::$values[self::SLIDE_MULTI_NAME.$i.'_MEDIA_ID'] = Pelican_Db::$values[self::SLIDE_MULTI_NAME.$i.'_MEDIA_ID_VIDEO'];
                }
            }
        }

        $ctaSimple = Ndp_Cta_Factory::getInstance(Ndp_Cta::SIMPLE);
        $ctaSimple->setCtaType('AFFICHAGE_CLASSIQUE')
               ->setMulti($controller->multi)
               ->delete()
               ->save();

        self::saveContentVersionMediaValues(self::SLIDE_MULTI_NAME, self::SLIDE_MULTI_NAME);
        Pelican_Db::$values = $saved;

        Ndp_Cta_Factory::setDefaultContext();

    }
}

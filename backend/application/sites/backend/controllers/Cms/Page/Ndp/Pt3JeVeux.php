<?php
/**
 * Tranche PT3 Je veux
 *
 * @package Pelican_BackOffice
 * @subpackage Tranche
 * @author David Moaté <david.moate@businessdecision.com>
 * @since 12/03/2015
 */
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/Factory.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi/Factory.php';

/**
 *
 */
class Cms_Page_Ndp_Pt3JeVeux extends Cms_Page_Ndp
{

    const TYPE_CTA = "_CTA";

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {

        $controller->zoneValues['ZONE_MOBILE_SHOW'] = false;
        $controller->zoneValues['ZONE_WEB'] = (isset($controller->zoneValues['ZONE_WEB'])) ? $controller->zoneValues['ZONE_WEB'] : 1;
        $form = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        if (empty($controller->zoneValues['ZONE_TITRE'])) {
            $controller->zoneValues['ZONE_TITRE'] = t('NDP_JE_VEUX');
        }
        $form .= $controller->oForm->createInput($controller->multi.'ZONE_TITRE', t('NDP_LABEL'), 25, '', true, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 100);
        $form .= $controller->oForm->createInput($controller->multi.'ZONE_TITRE2', t('NDP_TITRE_EXPAND'), 50, '', false, $controller->zoneValues['ZONE_TITRE2'], $controller->readO, 100);
        $form .= $controller->oForm->createLabel('', t('NDP_MSG_PICTO_COLONNE'));
        $form .= $controller->oForm->createLabel('', t('NDP_MSG_PICTO_MANQUANT'));
        foreach (self::getTypesColonnes() as $colonne) {
            $form .= self::getColonne($controller, $colonne);
        }

        return $form;
    }

    public static function getTypesColonnes()
    {
        return array('NDP_COLONNE1', 'NDP_COLONNE2', 'NDP_COLONNE3', 'NDP_COLONNE4');
    }

    /**
     * 
     * @param Pelican_Controller $controller
     * @param string $colonne
     * @return string
     */
    public static function getColonne(Pelican_Controller $controller, $colonne)
    {
        $isZoneDynamique = Ndp_Multi::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::SIMPLE, $isZoneDynamique);
        $values = $multi->setMultiType($colonne)
            ->hydrate($controller->zoneValues)
            ->getValues();
        $form = $controller->oForm->showSeparator();
        $form .= $controller->oForm->createLabel(t($colonne));

        $form .= $controller->oForm->createMedia($controller->multi.$colonne.'_MEDIA_ID', t('PICTO'), false, 'image', '', $values['MEDIA_ID'], (Cms_Page_Ndp::isTranslator() || $controller->readO), true, false, 'NDP_RATIO_16_9:650x365');
        if ($colonne != 'NDP_COLONNE4') {
            $form .= $controller->oForm->createInput($controller->multi.$colonne.'_PAGE_ZONE_MULTI_TITRE', t('TITLE'), 50, '', false, $values['PAGE_ZONE_MULTI_TITRE'], $controller->readO, 100);
            $form .= self::getCtaMulti($controller, $colonne);
        }
        return $form;
    }
 
    /**
     * 
     * @param Pelican_Controller $controller
     * @param string $colonne
     * @return string
     */
    public static function getCtaMulti(Pelican_Controller $controller, $colonne)
    {
        $typeForm = $colonne.self::TYPE_CTA;
        $isZoneDynamique = Ndp_Cta::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $ctaMulti = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC, $isZoneDynamique);
        $ctaMulti->hydrate($controller->zoneValues)->setCtaType($typeForm);
        $valuesCta = $ctaMulti->getValues();
        $strLib = array(
            'multiTitle' => t('NDP_CTA'),
            'multiAddButton' => t('ADD_FORM_CTA')
        );
        $form = $controller->oForm->createMultiHmvc(
            $controller->multi.$typeForm, $strLib, array(
            'path' => __FILE__,
            'class' => __CLASS__,
            'method' => 'addCtaMulti'),
            $valuesCta,
            $controller->multi.$typeForm,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            array(3, 5),
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

        return $form;
    }

    /**
     * 
     * @param Ndp_Form $form
     * @param array $values
     * @param boolean $readO
     * @param string $multi
     * @return string
     */
    public function addCtaMulti(Ndp_Form $form, $values, $readO, $multi)
    {
        $pos = isset($values['CPT_POS_MULTI']) ? $values['CPT_POS_MULTI'] + 1 : $values['__CPT1__'];
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaComposite->setValueDefaultTypeCta('2');
        $ctaComposite->setLabel(t('CTAFORM').' '.$pos);
        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->typeStyle(0)->hideStyle(true);
        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->hideStyle(true);
        $ctaComposite->setCta($form, $values, $multi, '', true, (Cms_Page_Ndp::isTranslator() || $readO));
        $ctaComposite->addInputCta($ctaRef);
        $ctaComposite->addInputCta($ctaNew);

        return $ctaComposite->generate();
    }

    /**
     *
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        $save = Pelican_Db::$values;
        Pelican_Db::$values['ZONE_MOBILE'] = 0;
        parent::save();
        $multiId = 1;
        foreach (self::getTypesColonnes() as $colonne) {
            if ($colonne != 'NDP_COLONNE4') {
                $ctaHmvc = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);
                $ctaHmvc->setCtaType($colonne)
                    ->setMulti($controller->multi)
                    ->setCtaType($colonne.self::TYPE_CTA)
                    ->delete()
                    ->save();
            }
            $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::SIMPLE);
            $multi->setMultiType($colonne)
                ->setMultiId($multiId)
                ->delete()
                ->save();
            $multiId++;
        }
        Pelican_Db::$values = $save;
    }
}

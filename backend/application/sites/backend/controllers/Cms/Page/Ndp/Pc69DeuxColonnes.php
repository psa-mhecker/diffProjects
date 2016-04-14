<?php
/**
 * Tranche PC5 - Une Colonne
 *
 * @package Pelican_BackOffice
 * @subpackage Tranche
 * @author Joseph FRANCLIN <joseph.franclin@businessdecision.com>
 * @since 24/02/2015
 */
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi/Factory.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/Factory.php';

use PsaNdp\MappingBundle\Object\Block\Pc69Contenu2Colonnes;

class Cms_Page_Ndp_Pc69DeuxColonnes extends Cms_Page_Ndp
{

    const COL_UN = 'COLUMN_1_4';
    const COL_TROIS = 'COLUMN_3_4';


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
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITLE'), 60, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 50, false, '', 'text', [], false, '', 60 .t('NDP_MAX_CAR'));
        $typAffichage = array(
            1 => t('NDP_3_4'),
            2 => t('NDP_1_4')
        );
        if (empty($controller->zoneValues['ZONE_PARAMETERS'])) {
            $controller->zoneValues['ZONE_PARAMETERS'] = 1;
        }
        $return .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_PARAMETERS', t('NDP_AFFICHAGE_COLONNE'), $typAffichage, $controller->zoneValues['ZONE_PARAMETERS'], true, $controller->readO, 'h', false);


        foreach (self::getTypesLevels() as $level => $levelLabel) {
            $insert = self::makeColonne($controller, $levelLabel);
            $return .= $controller->oForm->showSeparator();
            $return .= $controller->oForm->showSeparator();

            $return .= self::getLevelCta(
                $controller,
                $level,
                t("NDP_".$levelLabel),
                2,
                1,
                false,
                [
                    'CTA' => [
                        'forceValues' => ['CTADisable' => false],
                        'maxCta' => '2',
                        'CTA_READONLY' =>(Cms_Page_Ndp::isTranslator() || $controller->readO),
                        'noDragNDrop' => Cms_Page_Ndp::isTranslator()
                    ],
                    'insertAfterStyle' => $insert,
                    'CTA_LD' => ['showNumberLabel' => false, 'noSeparator' => true],
                    'needed' => true
                ]
            );
        }


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
        foreach (self::getTypesLevels() as $type) {
            $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC);
            $multi->setMultiType($type)
                ->setMulti($controller->multi)
                ->delete()
                ->save();
        }

        foreach (self::getTypesLevels() as $type => $levelLabel) {
            $ctaHmvc = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);
            $ctaHmvc->setCtaType($type.self::TYPE_CTA)
                ->setMulti($controller->multi)
                ->setTypeCtaDropDown($type.self::TYPE_CTA_LD)
                ->delete() //suppression des anciens CTA (liste deroulante compris)
                ->save();
            
            $ctaLDHmvc = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);
            $ctaLDHmvc->setCtaType($type.self::TYPE_CTA_LD)
                ->setMulti($controller->multi)
                ->setCtaDropDown(true)
                ->save();
        }
    }

    /**
     *
     * @return array
     */
    public static function getTypesLevels()
    {
        return array(self::COL_UN.'_' => self::COL_UN, self::COL_TROIS.'_' => self::COL_TROIS);
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
    public static function multiColonne(Ndp_Form $form, $values, $readO, $multi)
    {
        $maxCharacter = 500;

        $return = $form->createInput($multi."PAGE_ZONE_MULTI_TITRE", t('NDP_TITRE_COLONNE'), 255, "", false, $values["PAGE_ZONE_MULTI_TITRE"], $readO, 50);
        switch($values['PAGE_ZONE_MULTI_TYPE']) {
            case self::COL_UN:
                $return .= $form->createNewImage(
                    $multi.'MEDIA_ID',
                    t('VISUEL'),
                    false,
                    $values['MEDIA_ID'],
                    (Cms_Page_Ndp::isTranslator() || $readO),
                    false,
                    Pc69Contenu2Colonnes::RATIO_VISUEL_MOBILE,
                    [t('DESKTOP') => Pc69Contenu2Colonnes::RATIO_VISUEL_1_4, t('MOBILE') => Pc69Contenu2Colonnes::RATIO_VISUEL_MOBILE]
                );
                break;
            case self::COL_TROIS:
                $return .= $form->createNewImage(
                    $multi.'MEDIA_ID',
                    t('VISUEL'),
                    false,
                    $values['MEDIA_ID'],
                    (Cms_Page_Ndp::isTranslator() || $readO),
                    false,
                    Pc69Contenu2Colonnes::MIN_DIMENSION,
                    [t('DESKTOP') => Pc69Contenu2Colonnes::RATIO_VISUEL_3_4, t('MOBILE') => Pc69Contenu2Colonnes::RATIO_VISUEL_MOBILE]
                );
                break;
            default:
                // do nothing
        }

        $return .= $form->createEditor(
            $multi."PAGE_ZONE_MULTI_TEXT",
            t('NDP_DESCRIPTION'),
            false,
            $values["PAGE_ZONE_MULTI_TEXT"],
            $readO,
            true,
            "",
            650,
            150,
            null,
            array('message'=>t('NDP_DYN_MAX_CAR', null, array('max_characters'=>$maxCharacter)), 'maxCharacterNumber' => $maxCharacter)
        );
        return $return;
    }

    /**
     *
     * @param Pelican_Controller $controller
     * @param string             $col
     *
     * @return string
     */
    public static function makeColonne(Pelican_Controller $controller, $col)
    {
        $isZoneDynamique = Ndp_Multi::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC, $isZoneDynamique);
        $colonne = $multi->setMultiType($col)
            ->hydrate($controller->zoneValues)
            ->getValues();
        $colonne[0]['PAGE_ID'] = $controller->zoneValues['PAGE_ID'];
        $colonne[0]['PAGE_ZONE_MULTI_TYPE'] = $col;
        $strLib = array(
            'multiTitle' => t("NDP_".$col),
            'multiAddButton' => ""
        );
        $return = $controller->oForm->createMultiHmvc(
            $controller->multi.$col,
            $strLib,
            array(
                "path" => __FILE__,
                "class" => __CLASS__,
                "method" => "multiColonne"
            ),
            $colonne,
            $col,
            $controller->readO,
            array(1, 1),
            false,
            false,
            $controller->multi.$col,
            'values',
            'multi',
            '2',
            '',
            '',
            false,
            ['noDragNDrop' => Cms_Page_Ndp::isTranslator()]
        );

        return $return;
    }

    /**
     *
     * @param Pelican_Controller $controller
     * @param string             $typeForm
     *
     * @return string
     */
    public static function makeCtas(Pelican_Controller $controller, $typeForm)
    {
        //Affichage des CTA en mode multi
        $isZoneDynamique = Ndp_Cta::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $ctaMulti = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC, $isZoneDynamique);
        $valuesCta = $ctaMulti->hydrate($controller->zoneValues)->setCtaType($typeForm)->getValues();
        $strLib = array(
            'multiTitle' => t('NDP_CTA'),
            'multiAddButton' => t('ADD_FORM_CTA')
        );
        $return = $controller->oForm->createMultiHmvc($controller->multi.$typeForm, $strLib, array(
            "path" => __FILE__,
            "class" => __CLASS__,
            "method" => "AddCtaMulti"),
            $valuesCta,
            $controller->multi.$typeForm,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            array(0, 2),
            true,
            true,
            $controller->multi.$typeForm,
            'values',
            'multi',
            "2",
            '',
            '',
            false,
            ['noDragNDrop' => Cms_Page_Ndp::isTranslator()]
        );

        return $return;
    }
}

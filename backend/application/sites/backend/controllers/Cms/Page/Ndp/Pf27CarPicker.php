<?php
/**
 * Tranche PF27 - Car Picker
 *
 * @author Pierre Pottié <pierre.pottie@businessdecision.com>
 *
 * @since 23/03/2015
 */
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi/Factory.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/Factory.php';

use PSA\MigrationBundle\Entity\Page\PsaPage;

/**
 * Cms_Page_Ndp_Pf27CarPicker.
 */
class Cms_Page_Ndp_Pf27CarPicker extends Cms_Page_Ndp
{

    const CAR_PICKER = 'CAR_PICKER';
    const MIN_CARS   = 1;
    const MAX_CARS   = 10;
    const TYPE_CTA   = "CTA_TYPE";

    /**
     * Render.
     *
     * @param Pelican_Controller_Back $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller_Back $controller)
    {
        $return  = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));

        $return .= $controller->oForm->createInput(
            $controller->multi.'ZONE_TITRE',
            t('TITRE'),
            100,
            "text",
            false,
            $controller->zoneValues['ZONE_TITRE'],
            $controller->readO,
            75
        );

        $return .= $controller->oForm->createDescription(t('NDP_CARPICKER_VEHICULES_INFO'));

        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC);

        $RangeBarValues = $multi
            ->setMultiType(self::CAR_PICKER)
            ->hydrate($controller->zoneValues)
            ->getValues();

        $return .= $controller->oForm->createMultiHmvc(
            $controller->multi.self::CAR_PICKER,
            t('NDP_ADD_MODEL'),
            array(
                "path" => __FILE__,
                "class" => __CLASS__,
                "method" => "addCarPicker",
            ),
            $RangeBarValues,
            $controller->multi.self::CAR_PICKER,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            array(self::MIN_CARS, self::MAX_CARS),
            !Cms_Page_Ndp::isTranslator(),
            true,
            $controller->multi.self::CAR_PICKER,
            'values',
            'multi',
            2,
            '',
            '',
            false,
            [
                'noDragNDrop' => Cms_Page_Ndp::isTranslator(),
            ]
        );

        $return .= $controller->oForm->showSeparator();

        $return .= $controller->oForm->createRadioFromList(
            $controller->multi.'ZONE_ATTRIBUT',
            t('NDP_SHOW_PRICE'),
            array(
                1 => t('NDP_YES'),
                0 => t('NDP_NO')
            ),
            $controller->zoneValues['ZONE_ATTRIBUT'],
            true,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            'h'
        );

        $return .= self::addCtaSimple($controller);

        $return .= $controller->oForm->createTextArea(
            $controller->multi.'ZONE_TEXTE',
            t('MENTIONS_LEGALES'),
            false,
            $controller->zoneValues['ZONE_TEXTE'],
            250,
            $controller->readO,
            5,
            75,
            false,
            '',
            true
        );

        return $return;
    }

    public static function addCarPicker(Ndp_Form $form, $values, $readO, $multi){

        $return = $form->createComboFromList($multi.'PAGE_ZONE_MULTI_VALUE',
            t('NDP_CATEGORIES_TO_SHOW'),
            self::getModelsWithShowRooms(),
            $values['PAGE_ZONE_MULTI_VALUE'],
            true,
            (Cms_Page_Ndp::isTranslator() || $readO),
            1,
            false,
            false,
            false
        );

        $return .= self::addCtaInMulti($form, $values, $readO, $multi);

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

    /**
     *
     * @param Ndp_Form $form
     * @param array $values
     * @param bool $readO
     * @param array $multi
     *
     * @return string
     */
    public function addCtaInMulti(Ndp_Form $form, $values, $readO, $multi)
    {
        // Ajout formulaire des CTA
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaDisable   = Pelican_Factory::getInstance('CtaDisable');
        $ctaRef = Pelican_Factory::getInstance('CtaRef');

        $ctaRef->hideStyle(true);
        
        $ctaComposite->setCta(
            $form,
            $values,
            $multi,
            self::CAR_PICKER,
            false,
            (Cms_Page_Ndp::isTranslator() || $readO),
            Ndp_Cta::SIMPLE_INTO_MULTI_HMVC,
            'Ndp_Multi'
        );

        $ctaComposite->addInputCta($ctaDisable);
        $ctaComposite->addInputCta($ctaRef);

        return $ctaComposite->generate();
    }

    /**
     * @param Pelican_Controller_Back $controller
     *
     * @return mixed
     */
    public function addCtaSimple(Pelican_Controller_Back $controller)
    {
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaComposite->setCta(
            $controller->oForm,
            $controller->zoneValues,
            $controller->multi,
            self::TYPE_CTA,
            false,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            Ndp_Cta::SIMPLE
        );

        $ctaDisable   = Pelican_Factory::getInstance('CtaDisable');
        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->addStyleAvailable('style_niveau4', t('NDP_STYLE_NIVEAU4'));
        $ctaComposite->addInputCta($ctaDisable);
        $ctaComposite->addInputCta($ctaRef);

        return $ctaComposite->generate();
    }

    /**
     * save
     * @param Pelican_Controller_Back $controller
     */
    public static function save(Pelican_Controller_Back $controller)
    {
        parent::save();

        $cta = Ndp_Cta_Factory::getInstance(Ndp_Cta::SIMPLE_INTO_MULTI_HMVC);
        $cta->setCtaType(self::CAR_PICKER);
        $multi        = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC);
        $multi->setMultiType(self::CAR_PICKER)
            ->setMulti($controller->multi)
            ->addChild($cta)
            ->delete()
            ->save();

        $ctaSimple = Ndp_Cta_Factory::getInstance(Ndp_Cta::SIMPLE);
        $ctaSimple->setCtaType(self::TYPE_CTA)
            ->setMulti($controller->multi)
            ->delete()
            ->save();

    }

}

<?php

/**
 * Tranche PC60 - Récapitulatif et CTA showroom.
 *
 * @author David Moate <david.moate@businessdecision.com>
 *
 * @since 26/02/2015
 *
 * @todo ajouter traduction manquante, gerer les nom de CTA différents selon le multi ( cta/lien cta mobile , supprimer les données non utile a la sauvegarde: CTA lebillé onglet etc  )
 */
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/Factory.php';

use PSA\MigrationBundle\Entity\Cta\PsaCta;

/**
 * Class Cms_Page_Ndp_Pc60RecapitulatifEtCtaShowroom
 */
class Cms_Page_Ndp_Pc60RecapitulatifEtCtaShowroom extends Cms_Page_Ndp
{
    const NDP_DESACTIVE = 0;
    const NDP_ACTIVE = 1;
    const NDP_ONGLET = 2;
    const NDP_PAGE_PARENT = 1;
    const NDP_PAGE_CHILD = 2;

    /**
     * @return array
     */
    private static function getEnabledDisabled()
    {
        return  array(
            self::NDP_DESACTIVE => t('NDP_DESACTIVE'),
            self::NDP_ACTIVE => t('NDP_ACTIVE'),
        );
    }

    /**
     * @param Pelican_Controller $controller
     *
     * @return string|void
     */
    public static function render(Pelican_Controller $controller)
    {
        if (self::isChildPage()) {
            $form = self::renderChildPage($controller);
        } else {
            $form = self::renderParentPage($controller);
        }

        return $form;
    }

    /**
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    private static function renderChildPage(Pelican_Controller $controller)
    {
        $form = $controller->oForm->createDescription(
            t('NDP_MSG_CTA_SHOWROOM_DISPLAY_CONDITION_CHILD')
            .'<br />'
            .t('NDP_MSG_CTA_SHOWROOM_DISPLAY_CONDITION_CHILD_SUITE')
        );

        // Affichage WEB / MOBILE
        $form .= $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));

        // permet d'enregistrer la zone dans la tranche même si aucun champs affiché
        $form .= $controller->oForm->createHidden($controller->multi.'ZONE_PARAMETERS', self::NDP_PAGE_CHILD);

        return $form;
    }

    /**
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    private static function renderParentPage(Pelican_Controller $controller)
    {
        $form = $controller->oForm->createHidden($controller->multi.'ZONE_PARAMETERS', self::NDP_PAGE_PARENT);
        $form .= $controller->oForm->createDescription(t('NDP_MSG_CTA_SHOWROOM_DISPLAY_CONDITION'));

        // CTA BUTTON
        $form .= self::getCtaDesktop($controller);

        $form .= self::getCtaLiens($controller);

        return $form;
    }

    /**
     * @param Ndp_Form $form
     * @param $values
     * @param $readO
     * @param $multi
     *
     * @return mixed
     */
    public function addCtaMultiMobile(Ndp_Form $form, $values, $readO, $multi)
    {
        // Ajout formulaire des CTA
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->hideStyle(true);
        $ctaRef->addTargetAvailable('_popin', t('NDP_POPIN'));
        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->addTargetAvailable('_popin', t('NDP_POPIN'));
        $ctaNew->hideStyle(true);
        $ctaComposite->setCta($form, $values, $multi, '', true, (Cms_Page_Ndp::isTranslator() || $readO));
        $ctaComposite->setValueDefaultTypeCta(Ndp_Cta::SELECT_CTA);

        $ctaComposite->addInputCta($ctaRef);
        $ctaComposite->addInputCta($ctaNew);
        $ctaComposite->setLabel(t('NDP_CTA_MOBILE'));

        return $ctaComposite->generate();
    }

    /**
     * @param Ndp_Form $form
     * @param array $values
     * @param bool $readO
     * @param string $multi
     *
     * @return string
     */
    public function addCtaMulti(Ndp_Form $form, $values, $readO, $multi)
    {
        // Ajout formulaire des CTA
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->hideStyle(true);
        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->hideStyle(true);
        $ctaComposite->setCta($form, $values, $multi, '', true, (Cms_Page_Ndp::isTranslator() || $readO));
        $ctaComposite->setValueDefaultTypeCta(Ndp_Cta::SELECT_CTA);

        $ctaComposite->addInputCta($ctaRef);
        $ctaComposite->addInputCta($ctaNew);
        $ctaComposite->setLabel(t('NDP_CTA_AND_LIEN'));

        return $ctaComposite->generate();
    }

    /**
     * @return bool
     */
    private static function isChildPage()
    {
        $bind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $parentPages = explode('/', $_SESSION[APP]['CURRENT_PAGE_PATH']);
        if ($_SESSION[APP]['PAGE_ID'] != Pelican_db::DATABASE_INSERT_ID) {
            array_pop($parentPages);
        }
        $templatesShowRoom = implode(',', Pelican::$config['TEMPLATE_PAGE_SHOWROOM']);
        $sql = 'SELECT
                  p.PAGE_ID
                FROM #pref#_page p
                INNER JOIN #pref#_page_version pv ON p.PAGE_ID = pv.PAGE_ID AND p.LANGUE_ID=pv.LANGUE_ID AND p.PAGE_CURRENT_VERSION =pv.PAGE_VERSION
                WHERE
                   pv.TEMPLATE_PAGE_ID  IN ('.$templatesShowRoom.')
                   AND p.PAGE_ID IN ('.implode(',', $parentPages).')
                   AND p.LANGUE_ID=:LANGUE_ID
                   AND p.SITE_ID=:SITE_ID
                LIMIT 0,1';
        $con = Pelican_Db::getInstance();
        $item = $con->queryItem($sql, $bind);

        return !empty($item);
    }

    /**
     * @param $controller
     *
     * @return mixed
     */
    private static function getCtaDesktop($controller)
    {
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaComposite->setValueDefaultTypeCta(Ndp_Cta::SELECT_CTA);
        $ctaComposite->setLabel(t('NDP_MAIN_CTA'));
        $ctaComposite->setNeeded(true);

        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->typeStyle(0);
        $ctaRef->setReadO($controller->readO);
        $ctaRef->hideStyle(true);
        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->typeStyle(0);
        $ctaNew->hideStyle(true);
        $ctaNew->setReadO($controller->readO);

        $ctaComposite->setCta($controller->oForm, $controller->zoneValues, $controller->multi, 'CTA_DESKTOP', false, (Cms_Page_Ndp::isTranslator() || $controller->readO));

        $ctaComposite->addInputCta($ctaRef);
        $ctaComposite->addInputCta($ctaNew);

        return $ctaComposite->generate();
    }

    /**
     * @param $controller
     *
     * @return mixed
     */
    public static function getCtaLiens($controller)
    {
        //Affichage des CTA en mode multi
        $typeForm = 'CTA_LIENS';
        $isZoneDynamique = Ndp_Cta::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $ctaMulti = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC, $isZoneDynamique);
        $valuesCta = $ctaMulti->hydrate($controller->zoneValues)
            ->setCtaType($typeForm)
            ->getValues();
        $strLib = array(
                'multiTitle' => t('NDP_CTA_LIENS'),
                'multiAddButton' => t('NDP_ADD_CTA_LIENS'),
            );

        $form = $controller->oForm->createDescription(t('NDP_MSG_MAIN_CTA_DISPLAY_ON_MOBILE'));

        $form .= $controller->oForm->createMultiHmvc($controller->multi.$typeForm, $strLib, array(
                'path' => __FILE__,
                'class' => __CLASS__,
                'method' => 'addCtaMulti', ),
                $valuesCta,
                $controller->multi.$typeForm,
                (Cms_Page_Ndp::isTranslator() || $controller->readO),
                array(0, 3),
                true,
                true,
                $controller->multi.$typeForm,
                'values',
                'multi',
                3,
                '',
                '',
                false,
                ['noDragNDrop' => Cms_Page_Ndp::isTranslator()]
            );

        return $form;
    }

    /**
     * @return array
     */
    private static function getTypeCtas()
    {
        $typeCtas = array(
            'CTA_DESKTOP' => 'SIMPLE',
            'CTA_LIENS' => 'HMVC',
        );

        return $typeCtas;
    }

    /**
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        parent::save();

        foreach (self::getTypeCtas() as $typeCta => $modeMulti) {
            $typeInstance = Ndp_Cta::HMVC;
            if ($modeMulti == 'SIMPLE') {
                $typeInstance = Ndp_Cta::SIMPLE;
            }
            $cta = Ndp_Cta_Factory::getInstance($typeInstance);
            $cta->setCtaType($typeCta)
                ->setMulti($controller->multi)
                ->delete()
                ->save();
        }
    }
}

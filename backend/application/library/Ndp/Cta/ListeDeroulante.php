<?php

require_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/Interface.php';

/**
 * Gestion des input d'un CTA.
 *
 * @author David Moate <david.moate@businessdecision.com>
 *
 * @since 02/03/2015
 */
class Ndp_Cta_Liste_Deroulante extends Ndp_Cta implements Ndp_Cta_Interface
{
    const TYPE = parent::LISTE_DEROULANTE_CTA;
    const TYPE_TRADUCTION = 'LISTE_DEROULANTE_CTA';
    const TYPE_FORM_CTA = 'LISTE_DEROULANTE';
    const CODE_STYLE = 'style_niveau5';
    const STYLE_LABEL = 'NDP_STYLE_NIVEAU5';

    protected $isStyle = false;

    public function __construct()
    {
        parent::__construct();
        //active l'a gestion'affichage de la liste deroulante comme style et non comme type
        $this->setIsStyle(true);
    }
    /*
     * Génération du formulaire pour un nouveau CTA
     * @param object $form
     * @param string $multi
     * @param array $values
     * @param bool $readO
     * @param bool $needed
     *
     * @return string
     */

    public function getInput(Ndp_Form $form, $multi, $values, $readO, $needed = true)
    {
        $formCtaListeDeroulante = $form->createInput($multi.'[LD][PAGE_ZONE_CTA_LABEL]', t('LABEL'), 60, '', $needed, $values['PAGE_ZONE_CTA_LABEL'], $readO, 100);
        $isZoneDynamique = Ndp_Cta::isZoneDynamique($values['ZONE_TEMPLATE_ID']);
        $ctaMulti = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC_INTO_CTA, $isZoneDynamique);

        $valuesCta = $ctaMulti->hydrate($values)
            ->setCtaType($values['PAGE_ZONE_CTA_TYPE'])
            ->setParentId($values['PAGE_ZONE_CTA_ID'])
            ->getValues();

        if (isset($values['CTA_ID']) && $values['PAGE_ZONE_CTA_STATUS'] == self::TYPE) {
            $formCtaListeDeroulante .= $form->createHidden($multi.'[LD][CTA_ID]', $values['CTA_ID']);
        }

        $strLib = array();
        $strLib['multiTitle'] = t('LISTE_DEROULANTE_CTA');
        $strLib['multiAddButton'] = t('NDP_ADD_CTA_LISTE_DEROULANTE');

        $formCtaListeDeroulante .= $form->createMultiHmvc(
            $multi.self::TYPE_FORM_CTA,
            $strLib,
            array(
                'path' => Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php',
                'class' => __CLASS__,
                'method' => 'addCtaMulti',
            ),
            $valuesCta,
            $multi.self::TYPE_FORM_CTA,
            $readO,
            array(0, 15),
            true,
            true,
            $multi.self::TYPE_FORM_CTA
        );

        return $form->output($formCtaListeDeroulante);
    }

    public function addCtaMulti(Ndp_Form $form, $values, $readO, $multi)
    {
        // Ajout formulaire des CTA
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaComposite->setCta($form, $values, $multi, '', true, $readO);
        $ctaComposite->setValueDefaultTypeCta(Ndp_Cta::SELECT_CTA);
        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->hideStyle(true);
        $ctaRef->setReadO($readO)->hideStyle(true)->addTargetAvailable('_popin', t('NDP_POPIN'));
        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->setReadO($readO)->hideStyle(true)->addTargetAvailable('_popin', t('NDP_POPIN'));
        $ctaComposite->addInputCta($ctaRef);
        $ctaComposite->addInputCta($ctaNew);

        return $ctaComposite->generate();
    }

    /**
     * @param bool $isStyle
     *
     * @return \Ndp_Cta_Liste_Deroulante
     */
    public function setIsStyle($isStyle)
    {
        $this->isStyle = $isStyle;
        $this->addStyleAvailable(self::CODE_STYLE, t(self::STYLE_LABEL));

        return $this;
    }

    /**
     * @return \Ndp_Cta_Liste_Deroulante
     */
    public function getIsStyle()
    {
        return $this->isStyle;
    }
}

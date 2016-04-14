<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/CtaDisable.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/Factory.php';

/**
 * Composite des inputs d'un CTA.
 *
 * @author David Moate <david.moate@businessdecision.com>
 *
 * @since 02/03/2015
 */
class Ndp_Cta_CtaComposite
{
    /**
     * @var array
     */
    private $inputCta = [];
    /**
     * @var Ndp_Cta
     */
    private $cta;
    /**
     * @var bool
     */
    private $needed = true;
    /**
     * @var int
     */
    private $valueDefaultTypeCta = 1;
    /**
     * @var string
     */
    private $label = '';
    /**
     * @var bool
     */
    private $useSameStyles = false;
    /**
     * @var bool
     */
    private $useOnlyOneStylesSelector = false;
    /**
     * @var bool
     */
    private $disabled = false;

    //styles commun aux CTA du composite
    /**
     * @var array
     */
    protected static $styles = array();

    /**
     * @return bool
     */
    public function getUseOnlyOneStylesSelector()
    {
        return $this->useOnlyOneStylesSelector;
    }

    /**
     * @param bool $useOnlyOne
     *
     * @return $this
     */
    public function setUseOnlyOneStylesSelector($useOnlyOne)
    {
        $this->useOnlyOneStylesSelector = $useOnlyOne;
        $this->setUseSameStyles($useOnlyOne);

        return $this;
    }

    /**
     * @param Ndp_Form$form
     * @param string $multi
     * @param array $value
     * @param string $type
     * @param bool $needed
     * @param bool $readO
     *
     * @return string
     */
    public function generateFormularStyle($form, $multi, $value, $type, $needed = false, $readO = false)
    {
        $form = $form->createCtaStyle($multi, $value, $readO, self::$styles, $needed, $type);
        //vide la liste des styles apres generation du selecteur
        self::$styles = [];

        return $form;
    }

    /**
     * @param bool $useSameStyles
     *
     * @return $this
     */
    public function setUseSameStyles($useSameStyles)
    {
        $this->useSameStyles = $useSameStyles;

        return $this;
    }

    /**
     * @return bool
     */
    public function getUseSameStyles()
    {
        return $this->useSameStyles;
    }

    /**
     * @param Ndp_Cta $ctas
     *
     * @return $this
     */
    public function addInputCta(Ndp_Cta $ctas)
    {
        $this->inputCta[] = $ctas;

        return $this;
    }

    /**
     * Permet de créer un objet Ndp_Cta et de l'hydrater.
     *
     * @param Ndp_Form $form
     * @param array    $zoneValues
     * @param string   $multi
     * @param string   $typeForm
     * @param bool     $isMulti
     * @param bool     $readO
     * @param int      $typeObjetCta
     *
     * @return $this
     */
    public function setCta(Ndp_Form $form, $zoneValues, $multi, $typeForm = 'UNIQUE', $isMulti = false, $readO = false, $typeObjetCta = Ndp_Cta::SIMPLE, $parent = '')
    {
        $isZoneDynamique = Ndp_Cta::isZoneDynamique($zoneValues['ZONE_TEMPLATE_ID']);
        $cta = Ndp_Cta_Factory::getInstance($typeObjetCta, $isZoneDynamique);
        $cta->setForm($form)
            ->setIsMulti($isMulti)
            ->setCtaType($typeForm)
            ->setMulti($multi)
            ->setReadO($readO)
            ->setZoneValue($zoneValues)
            ->hydrate($zoneValues);
        $this->cta = $cta;
        if (!empty($parent)) {
            $this->setParent($parent, $zoneValues);
        }

        return $this;
    }

    /**
     * @param string $parent
     * @param array  $zoneValues
     *
     * @return $this
     */
    public function setParent($parent, $zoneValues)
    {
        $id = $type = null;
        if ($parent == 'Ndp_Cta') {
            $id = $zoneValues['PAGE_ZONE_CTA_ID'];
            $type = $zoneValues['PAGE_ZONE_CTA_TYPE'];
        }
        if ($parent == 'Ndp_Multi') {
            $id = $zoneValues['PAGE_ZONE_MULTI_ID'];
            $type = $zoneValues['PAGE_ZONE_MULTI_TYPE'];
        }
        $this->cta->setParentId($id);
        $this->cta->setParentType($type);

        return $this;
    }

    /**
     * @param string
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        if (empty($this->label)) {
            $this->setLabel(t('PAGE_ZONE_CTA_STATUS'));
        }

        return $this->label;
    }

    /**
     * @return Ndp_Cta
     */
    public function getCta()
    {
        return $this->cta;
    }

    /**
     * @param bool $needed
     *
     * @return $this
     */
    public function setNeeded($needed)
    {
        $this->needed = $needed;

        return $this;
    }

    /**
     * @param bool $valueDefaultTypeCta
     *
     * @return $this
     */
    public function setValueDefaultTypeCta($valueDefaultTypeCta)
    {
        $this->valueDefaultTypeCta = $valueDefaultTypeCta;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDisabled()
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     *
     * @return $this
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * @param array   $valueCta
     * @param Ndp_Cta $cta
     * @param Ndp_Cta $inputCta
     *
     * @return string
     */
    public function getInput($valueCta, $cta, $inputCta)
    {
        $formCta = $inputCta->getInput($cta->getForm(), $cta->getMulti(), $valueCta, $cta->getReadO(), $this->needed);

        return $formCta;
    }

    /**
     * Géneration du formulaire des CTAs.
     *
     * @param array  $valueCta
     * @param Ndp_Cta   $cta
     * @param string $typeCta
     * @param int    $nbInputCta
     *
     * @return null|string
     */
    public function getFormulaireCta($valueCta, $cta, $typeCta, $nbInputCta)
    {
        $formCta = null;
        if ($nbInputCta == 1) {
            $formCta = $this->getFormulaireCtaWithoutSelected($valueCta, $cta);
        } elseif ($nbInputCta > 1) {
            $formCta = $this->getFormulaireCtaWithSelected($valueCta, $cta, $typeCta);
        }

        return $formCta;
    }

    /**
     * @param array $styles
     */
    private function mergeStyles($styles = [])
    {
        foreach ($styles as $key_style => $label_style) {
            self::$styles[$key_style] = $label_style;
        }
    }

    /**
     * @return array
     */
    public function getStyles()
    {
        return self::$styles;
    }

    /**
     * Génération du formulaire des Cta avec la possibilité de choisir la provenance des Ctas voir les désactiver.
     *
     * @param array $valueCta
     * @param Ndp_Cta $cta
     * @param string $typeCta
     * @return string
     */
    public function getFormulaireCtaWithSelected($valueCta, Ndp_Cta $cta, $typeCta)
    {
        $options = [];
        if ($this->isDisabled()) {
            $options['disabled'] = true;
        }

        $formCta = $cta->getForm()->createRadioFromList(
            $cta->getMulti().'[PAGE_ZONE_CTA_STATUS]', $this->getLabel(), $typeCta, $valueCta['PAGE_ZONE_CTA_STATUS'], $this->needed, $cta->getReadO(), 'h', false, $cta->getJsContainer($cta), null, $options
        );
        /** @var Ndp_Cta $inputCta */
        foreach ($this->inputCta as $inputCta) {
            if ($this->getUseOnlyOneStylesSelector()) {
                $inputCta->hideStyle(true);
                $inputCta->setStylesAvailable(self::$styles);
            }

            $formCta .= $cta->addTbodyCta($cta->getMulti(), $valueCta['PAGE_ZONE_CTA_STATUS'], $inputCta::TYPE);
            $formCta .= $this->getInput($valueCta, $cta, $inputCta);
            $formCta .= $cta->addFootTbodyCta();
        }

        return $formCta;
    }

    /**
     * Génération du formulaire des Cta sans la possibilité de choisir la provenance des Ctas.
     *
     * @param array $valueCta
     * @param Ndp_Cta  $cta
     *
     * @return type
     */
    public function getFormulaireCtaWithoutSelected($valueCta, $cta)
    {
        $formCta = '';
        /** @var Ndp_Cta $inputCta */
        foreach ($this->inputCta as $inputCta) {
            if ($this->getUseSameStyles()) {
                $this->mergeStyles($inputCta->getStylesAvailable());
                $inputCta->setStylesAvailable(self::$styles);
            }
            if (!$this->getUseOnlyOneStylesSelector()) {
                $inputCta->typeStyle(0);
            }
            $formCta = $cta->getForm()->createHidden($cta->getMulti().'[PAGE_ZONE_CTA_STATUS]', $inputCta::TYPE);
            $formCta .= $this->getInput($valueCta, $cta, $inputCta);
        }

        return $formCta;
    }

    /**
     * Génération du Html du formulaire des Ctas.
     *
     * @return string
     */
    public function generate()
    {
        if (empty($this->inputCta) || !is_array($this->inputCta)) {
            return $this;
        }
        $typeCta = [];
        /** @var Ndp_Cta $inputCta */
        foreach ($this->inputCta as $inputCta) {
            // on desactive les champs si le composite est disabled
            $inputCta->setDisabled($this->disabled);

            // CTA LD à gerer , un seul selecteur de style est toléré
            if ($inputCta::TYPE == Ndp_Cta::LISTE_DEROULANTE_CTA) {
                $this->setUseOnlyOneStylesSelector(true);
            }
            // une seule liste de styles communes à tout type de CTA [+ LD]
            $this->mergeStyles($inputCta->getStylesAvailable());
            $typeCta[$inputCta::TYPE] = t($inputCta::TYPE_TRADUCTION);
        }

        $cta = $this->getCta();
        $valueCta = $cta->getZoneValue();
        if ($cta->getIsMulti() != true && Ndp_Cta::TYPE_FORM_CTA_FOR_REF != $cta->getType()) {
            $valueCta = $cta->getValues();
        }
        $nbInputCta = count($this->inputCta);

        // Par défaut on set le type de CTA à disable
        if ((empty($valueCta['PAGE_ZONE_CTA_STATUS']) && isset($typeCta[Ndp_Cta_CtaDisable::TYPE]) && $nbInputCta > 1) || !isset($valueCta['PAGE_ZONE_CTA_STATUS'])) {
            $valueCta['PAGE_ZONE_CTA_STATUS'] = Ndp_Cta_CtaDisable::TYPE;
            if (!empty($this->valueDefaultTypeCta)) {
                $valueCta['PAGE_ZONE_CTA_STATUS'] = $this->valueDefaultTypeCta;
            }
        }

        $formCta = $this->getFormulaireCta($valueCta, $cta, $typeCta, $nbInputCta);

        return $formCta;
    }
}

<?php
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/Interface.php';

/**
 * Gestion des input d'un CTA.
 *
 * @author David Moate <david.moate@businessdecision.com>
 *
 * @since 02/03/2015
 */
class Ndp_Cta_CtaDisable extends Ndp_Cta implements Ndp_Cta_Interface
{

    protected $showUrlVisuel = false;
    protected $label = 'NDP_URL_CTA';

    const TYPE = parent::DISABLE_CTA;
    const TYPE_TRADUCTION = 'DISABLED';

    /**
     * Génération du formulaire pour désactiver un CTA
     *
     * @param Ndp_Form $form
     * @param string   $multi
     * @param array    $values
     * @param bool     $readO
     * @param bool     $needed
     *
     * @return string
     */
    public function getInput(Ndp_Form $form, $multi, $values, $readO, $needed = false)
    {
        $formCtaDisable = '';
        if (isset($values['CTA_ID']) && $this->isRefByCtaId($values['CTA_ID'])) {
            $values = [];
        }
        $valueId = '';
        if (isset($values['CTA_ID']) && $values['PAGE_ZONE_CTA_STATUS'] == self::TYPE) {
            $valueId = $values['CTA_ID'];
        }
        $formCtaDisable .= $form->createHidden($multi.'[DISABLE_CTA][CTA_ID]', $valueId);

        if ($this->getShowUrlVisuel()) {
            // si les valeurs correspondent a un cta referentiel on en tient pas compte
            if (isset($values['CTA_ID']) && $this->isRefByCtaId($values['CTA_ID'])) {
                $values = [];
            }

            $urlValues = '';

            if (self::TYPE == $values['PAGE_ZONE_CTA_STATUS'] && isset($values['ACTION'])) {
                $urlValues .= $values['ACTION'];
            }

            $formCtaDisable .= $form->createInput($multi.'[DISABLE_CTA][ACTION]', t($this->label), 255, 'internallink', $needed, $urlValues, $readO, 100);
        }

        return $form->output($formCtaDisable);
    }

    /**
     *
     * @param bool $show
     *
     * @return Ndp_Cta_CtaDisable
     */
    public function setShowUrlVisuel($show = false)
    {
        $this->showUrlVisuel = $show;

        return $this;
    }

    /**
     *
     * @return bool
     */
    public function getShowUrlVisuel()
    {

        return $this->showUrlVisuel;
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function setCtaLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getCtaLabel()
    {
        return $this->label;
    }
}

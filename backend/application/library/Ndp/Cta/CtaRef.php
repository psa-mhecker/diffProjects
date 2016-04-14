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
class Ndp_Cta_CtaRef extends Ndp_Cta implements Ndp_Cta_Interface
{

    const TYPE = parent::SELECT_CTA;
    const TYPE_TRADUCTION = 'CTA_REF';
    const HIDDEN_STYLE = 0;
    const SHOW_STYLE = 1;

    /**
     * Génération du formulaire pour un nouveau CTA
     * @param Ndp_Form $form
     * @param string $multi
     * @param array  $values
     * @param bool   $readO
     * @param bool   $required
     *
     * @return string
     */
    public function getInput(Ndp_Form $form, $multi, $values, $readO, $required = true)
    {
        // si les valeurs ne correspondent pas a un cta referentiel on en tient pas compte
        $formCtaRef = '';
        if (isset($values['CTA_ID']) && !$this->isRefByCtaId($values['CTA_ID'])) {
            $values = [];
        }
        $options['readOnly'] = $readO;
        $options['disabled'] = $this->isDisabled();
        if (false === $this->hideListeCta) {
            $modeCta = $this->getModeCta();
            $addSql = '';
            if (!empty($modeCta)) {
                $addSql = ' AND TYPE = "'.$modeCta.'"';
            }
            $sql = 'SELECT ID,TITLE_BO FROM #pref#_cta WHERE `TYPE`="STANDARD" AND IS_REF =1 AND SITE_ID='.$_SESSION[APP]["SITE_ID"].' AND LANGUE_ID='.$_SESSION[APP]["LANGUE_ID"].$addSql;
            $formCtaRef .= $form->createComboFromSql(null, $multi.'[SELECT_CTA][CTA_ID]', t('SELECT_CTA'), $sql, $values['CTA_ID'], $required, $options, 1, false);
        }
        if (empty($values['TARGET'])) {
            $values['TARGET'] = $this->getTargetDefault();
        }
        if (empty($values['STYLE'])) {
            $values['STYLE'] = $this->getStyleByDefault();
        }
        $options =  [];
        if (false === $this->hideTarget) {
            $formCtaRef .= $form->createCtaTarget($multi.'[SELECT_CTA]', $values['TARGET'], $readO, $this->getTargetsAvailable(), $required, $options);
        }
        if (false === $this->hideStyle) {
            switch ($this->typeStyle) {
                case self::HIDDEN_STYLE:
                    $formCtaRef .= $form->createCtaStyle($multi.'[SELECT_CTA]', $values['STYLE'], $readO, $this->getStylesAvailable(), $required, $multi);
                    break;
                case self::SHOW_STYLE:
                    $formCtaRef .= $form->createCtaStyle($multi.'[SELECT_CTA]', $values['STYLE'], $readO, '', $required, $multi);
                    break;
            }
        }
        if (false === $this->hidePicto) {
            $formCtaRef .= $form->createMedia($multi.'[SELECT_CTA][DESCRIPTION]', t('NDP_PICTO_CTA'), $required, 'image', '', $values['DESCRIPTION'], $readO, true, false, 'picto_cta');
        }

        return $formCtaRef;
    }
}

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
class Ndp_Cta_CtaNew extends Ndp_Cta implements Ndp_Cta_Interface
{

    const TYPE = parent::NEW_CTA;
    const TYPE_TRADUCTION = 'CTA_NEW';

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
        $options =  [];
        
        if($this->isDisabled()) {
            $options['attributes'] =  ['disabled'=>'disabled', 'readOnly'=>'readOnly'];
        }
        // si les valeurs correspondent a un cta referentiel on en tient pas compte
        if (isset($values['CTA_ID']) && $this->isRefByCtaId($values['CTA_ID'])) {
            $values = [];
        }
        $formCtaNew = '';
        $valueId = '';
        if (isset($values['CTA_ID']) && $values['PAGE_ZONE_CTA_STATUS'] == self::TYPE) {
            $valueId = $values['CTA_ID'];
        }
        $formCtaNew .= $form->createHidden($multi.'[NEW_CTA][CTA_ID]', $valueId);

        if (false === $this->hideTitle) {
            $title = '';
            if ($values['PAGE_ZONE_CTA_STATUS'] == self::TYPE) {
                $title = $values['TITLE'];
            }
            $formCtaNew .= $form->createInput($multi.'[NEW_CTA][TITLE]', t('NDP_LABEL_CTA'), self::MAX_TITLE_LENGTH, 'text', $needed, $title, $readO, 100, NULL, NULL, NULL, NULL, NULL, $infobull, $options);
        }
        if (false === $this->hideAction) {
            $actionUrl = 'http://';

            if (self::TYPE === $values['PAGE_ZONE_CTA_STATUS']) {
                $actionUrl = $values['ACTION'];
            }
            $formCtaNew .= $form->createInput($multi.'[NEW_CTA][ACTION]', t('NDP_URL_CTA'), 255, 'internallink', $needed, $actionUrl, $readO, 100,  NULL, NULL, NULL, NULL, NULL, '', $options);
        }
        if (empty($values['TARGET'])) {
            $values['TARGET'] = $this->getTargetDefault();
        }
        if (empty($values['STYLE'])) {
            $values['STYLE'] = $this->getStyleByDefault();
        }       
        if (false === $this->hideTarget) {   
            $formCtaNew .= $form->createCtaTarget($multi.'[NEW_CTA]', $values['TARGET'], $readO, $this->getTargetsAvailable(), $needed);
        }
        if (false === $this->hideStyle) {
            switch ($this->typeStyle) {
                case 0:
                    $formCtaNew .= $form->createCtaStyle($multi.'[NEW_CTA]', $values['STYLE'], $readO, $this->getStylesAvailable(), $needed, $multi);
                    break;
                case 1:
                    $formCtaNew .= $form->createCtaStyle($multi.'[NEW_CTA]', $values['STYLE'], $readO, '', $needed, $multi);
                    break;
            }
        }

        if (false === $this->hidePicto) {
            $formCtaNew .= $form->createMedia($multi.'[NEW_CTA][MEDIA_WEB_ID]', t('NDP_PICTO_CTA'), $needed, 'image', '', $values['MEDIA_WEB_ID'], $readO, true, false, 'NDP_RATIO_SQUARE_1_1:169x169');
        }

        $this->addJsCheckUrl($form, $multi.'[NEW_CTA][ACTION]', $readO);

        return $form->output($formCtaNew);
    }

    protected function addJsCheckUrl(Ndp_Form $form, $cta_input_name, $readO)
    {
        $js = null;
        $errorMessage = t('NDP_MSG_PLEASE_CHOOSE') . t('NDP_FOR') . (strip_tags(str_replace('"', '\\"', t('NDP_URL_CTA'))));

        if (!(Cms_Page_Ndp::isTranslator() || $readO)) {
            $js = sprintf('
                var newCTAValue =  $("input[name=\'%s\']");
                if( !newCTAValue.parents("tbody").hasClass("isNotRequired") &&  newCTAValue.val() == "http:\/\/" ) {
                  alert("%s");
                    newCTAValue.focus();
                    return false;
                }
            ',
                $cta_input_name,
                $errorMessage
            );
        }

        $form->createJs($js);
    }
}
